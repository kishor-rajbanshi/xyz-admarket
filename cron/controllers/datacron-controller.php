<?php
include_once COMMON_DIR_PATH.'helpers'.DS."utility-helper.php";

class DatacronController extends ApplicationController
{
	function data_backup_action()
	{
		$this->disable_notice_area();
		
		$db= DAL::get_instance();
		if(Configuration::get_instance()->read("product_version") !== PRODUCT_VERSION)
		{
			die();
		}
		if(file_exists("../".CACHE_DIR."/cron/lock-data.txt"))
		{
			$file_creation_time=filemtime("../".CACHE_DIR."/cron/lock-data.txt");
			
			if(date('d',$file_creation_time) != date('d',time()))
			unlink("../".CACHE_DIR."/cron/lock-data.txt");
		}			
		
		
        if(!is_dir("../".CACHE_DIR."/cron"))
       	mkdir("../".CACHE_DIR."/cron",0777);		
		
		
		$fp = fopen("../".CACHE_DIR."/cron/lock-data.txt", "a+"); 
		
		if(flock($fp, LOCK_EX | LOCK_NB)) 
		{ 	
		    $db= DAL::get_instance();
		    
		    $referral_enabled=$this->get_addon_status('referral_enabled');
		   
		    
		    $time_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET status=? WHERE task=?",array(0,'cron_success_time'));
		    
		    
			$cron_running_time=date('Y-m-d-H:i:s',time());
			
			$appendstring="Locked At : ".$cron_running_time." - Data Backup Cron == ";		
		
			set_time_limit(0);
			
			$query_limit=Configuration::get_instance()->read('db_query_execution_limit');
			
			
			$cpa_addon_enabled=$this->get_addon_status('cpa_enabled');
			$cpm_addon_enabled=$this->get_addon_status('cpm_enabled');
			$html_addon_enabled=$this->get_addon_status('html_enabled');
			$sponsored_enabled=$this->get_addon_status('sponsored_enabled');
			$referral_enabled=$this->get_addon_status('referral_enabled');
			$pop_addon_enabled=$this->get_addon_status('pop-ads_enabled');
			$affiliate_addon_enabled=$this->get_addon_status('affiliate-ads_enabled');
			$video_addon_enabled=$this->get_addon_status('video-ads_enabled');
			
			$category_targeting_enabled=$this->get_addon_status('category-targeting_enabled');
			$countrywise_data_tracking=Configuration::get_instance()->read('countrywise_data_tracking');
						
			$cpmrefstring0='';
			$cpmrefstring1='';
			$cpmrefstring2='';
			$cpmrefstring3='';
			$cpmrefstring4='';
			$cpmrefstring5='';
			$cpmrefstring6='';
				  
			
			$refstring='';
			$refstring1='';
			
			
			$adv_ref_enabled=0;
			$pub_ref_enabled=0;
			$arefperc=0;
			$prefperc=0;			
			

			
			if($referral_enabled ==1)
			{
				 $refstring=',`cpc_referral`,`adv_referral`,`pub_referral`';
			 	 $refstring1=',?,?,?';
			 	 
			 	 $cpmrefstring0.=',sum(cpc_referral) as cpc_referral';
			 	 
			 	 $cpmrefstring4.=',sum(cpc_referral) as cpc_referral';
			 	 $cpmrefstring5.=',`cpc_referral`';
			 	 $cpmrefstring6.=',?';	

				 $adv_ref_enabled=Configuration::get_instance()->read('advertiser_referral_enabled');
				 $pub_ref_enabled=Configuration::get_instance()->read('publisher_referral_enabled');
				 $arefperc=Configuration::get_instance()->read('advertiser_referral_profit_percentage');
				 $prefperc=Configuration::get_instance()->read('publisher_referral_profit_percentage');			 	 
			}		
			
		
			$totimpression='impression';
			
			if($cpm_addon_enabled ==1)
			{
				$totimpression=$totimpression.'+cpm_impression';
				
				$cpm_select=" ,sum(cpm_impression) as cpm_impression,sum(cpm_spend) as cpm_spend,sum(cpm_profit) as cpm_profit ";
				
				$cpm_select1=" ,sum(cpm_click) as cpm_click ";
				
				$cpm_insert1=" ,`cpm_impression`,`cpm_spend`,`cpm_profit`,`cpm_click` ";
				
				$cpm_insert2=" ,?,?,?,? ";
				
				
				if($referral_enabled ==1)
				{
				 	$cpmrefstring1.=',sum(cpm_referral) as cpm_referral'; 
				 	 
				 	$cpmrefstring4.=',sum(cpm_referral) as cpm_referral';
				 	$cpmrefstring5.=',`cpm_referral`';
				 	$cpmrefstring6.=',?';			 	 
				}			
			}
			else 
			{
				$cpm_select="";
				
				$cpm_insert1="";
				
				$cpm_insert2="";
				
				$cpm_select1="";
				
			}
			
			
			
			if($pop_addon_enabled ==1)
			{
				$totimpression=$totimpression.'+pop_impression';
				
				
				$pop_select=" ,sum(pop_impression) as pop_impression,sum(pop_spend) as pop_spend,sum(pop_profit) as pop_profit ";
				
				$pop_insert1=" ,`pop_impression`,`pop_spend`,`pop_profit` ";
				
				$pop_insert2=" ,?,?,? ";
				
				
				if($referral_enabled ==1)
				{
				 	$cpmrefstring1.=',sum(pop_referral) as pop_referral'; 
	
				 	$cpmrefstring4.=',sum(pop_referral) as pop_referral';
				 	$cpmrefstring5.=',`pop_referral`';
				 	$cpmrefstring6.=',?';				 	
				}			
				
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
				
				
				if($referral_enabled ==1)
				{
				 	$cpmrefstring1.=',sum(html_referral) as html_referral'; 
				 	
				 	$cpmrefstring4.=',sum(html_referral) as html_referral';
				 	$cpmrefstring5.=',`html_referral`';
				 	$cpmrefstring6.=',?';				 	
				}			
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
				$cpa_select1=" cpa_conversion";
				$cpa_select3=" ,sum(cpa_impression) as cpa_impression,sum(cpa_click) as cpa_click,sum(cpa_conversion) as cpa_conversion,sum(cpa_spend) as cpa_spend,sum(cpa_profit) as cpa_profit ";
				
				$cpa_insert1=" ,`cpa_impression`,`cpa_click`,`cpa_conversion`,`cpa_spend`,`cpa_profit` ";
				$cpa_insert2=" ,?,?,?,?,? ";
				
				
				
				if($referral_enabled ==1)
				{			
					$cpmrefstring2.=',sum(cpa_referral) as cpa_referral';	
	
					
				 	$cpmrefstring4.=',sum(cpa_referral) as cpa_referral';
				 	$cpmrefstring5.=',`cpa_referral`';
				 	$cpmrefstring6.=',?';					
				}
				
				
			}
			else 
			{
				$cpa_select="";
				$cpa_select1="";
				$cpa_select3="";
				$cpa_insert1="";
				$cpa_insert2="";
			}
			
			
			
			if($affiliate_addon_enabled ==1)
			{
				$affiliate_select3=" ,sum(affiliate_click) as affiliate_click,sum(affiliate_conversion) as affiliate_conversion,sum(affiliate_spend) as affiliate_spend,sum(affiliate_profit) as affiliate_profit ";
				
				$affiliate_insert1=" ,`affiliate_click`,`affiliate_conversion`,`affiliate_spend`,`affiliate_profit` ";
				$affiliate_insert2=" ,?,?,?,? ";
				
				
				if($referral_enabled ==1)
				{			
					$cpmrefstring3.=',sum(affiliate_referral) as affiliate_referral';	
					
				 	$cpmrefstring4.=',sum(affiliate_referral) as affiliate_referral';
				 	$cpmrefstring5.=',`affiliate_referral`';
				 	$cpmrefstring6.=',?';				
				}			
				
				
				if($cpa_select1 !="")
				$cpa_select1=$cpa_select1.'+affiliate_conversion';
				else
				$cpa_select1='affiliate_conversion';
				
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
				
				if($referral_enabled ==1)
				{
				 	$cpmrefstring1.=',sum(cpv_referral) as cpv_referral'; 
				 	 
				 	$cpmrefstring4.=',sum(cpv_referral) as cpv_referral';
				 	$cpmrefstring5.=',`cpv_referral`';
				 	$cpmrefstring6.=',?';			 	 
				}			
			}
			else 
			{
				$cpv_select="";
				$cpv_select1="";
				
				$cpv_insert1="";
				$cpv_insert2="";
			}			
						
			
			
			
			
			if($cpa_addon_enabled ==1 || $affiliate_addon_enabled ==1)
			$cpa_select1=' ,COALESCE(sum('.$cpa_select1.'),0) as conversion';
			else
			$cpa_select1="";
			
			
			$impressionstr=' COALESCE(sum('.$totimpression.'),0) as impression ';
			

			
			
			
			
			
			/******************** Impression Table Creation ***************************/
			
			$current_day_time =date("Y",time());
			$current_day_time.=date("m",time());
			$current_day_time.=date("d",time());
			
			$current_day_hour_time.=$current_day_time.date("H",time());	

			
			$next_day=$this->get_nextday($current_day_time);
			
			$next_day_hour=$next_day.date("H",time());
			
			
			$timearray=array();
			$tables=$db->execute_query("SHOW TABLES LIKE '".TABLE_PREFIX."impression_hourly_%'");
			
			while($tablesdata=$tables->fetch_array())
			{
				$tablename=$tablesdata[0];
				
				$tablenamearray=explode('_',$tablename);
				
				$timearray[]=$tablenamearray[count($tablenamearray)-1];
			}
			
			$tablecount=count($timearray);				

			
			do 
			{
				if($current_day_hour_time > $next_day_hour)
				break;
				
				
				$table_create_flag=0;
				
				if($tablecount >0)
				{
					if(!in_array($current_day_hour_time,$timearray))
					$table_create_flag=1;
				}	
				else
				$table_create_flag=1;		
						
								
				if($table_create_flag ==1)
				{		
					$db->execute_query("CREATE TABLE IF NOT EXISTS `".TABLE_PREFIX."impression_hourly_".$current_day_hour_time."` (
		 			 `id` INT NOT NULL AUTO_INCREMENT,
		 			 `uid` INT NOT NULL,
		 			 `aid` INT NOT NULL,
		 			 `kid` INT NOT NULL,
		 			 `keymapid` INT NOT NULL,
		 			 `pid` INT NOT NULL,
		 			 `bid` INT NOT NULL,
		 			 `sid` INT NOT NULL,
		 			 `ruid` INT NOT NULL,
		 			 `rpid` INT NOT NULL,
		 			 `country` VARCHAR(10) NOT NULL,
		 			 `cpc_impression` INT NOT NULL,
					 `cpa_impression` INT NOT NULL,
					 `cpd_impression` INT NOT NULL,	 			 
					 `cpm_impression` INT NOT NULL,
					 `cpm_spend` DECIMAL( 20, 10 ) NOT NULL,
					 `cpm_profit` DECIMAL( 20, 10 ) NOT NULL,
					 `pop_impression` INT NOT NULL,
					 `pop_spend` DECIMAL( 20, 10 ) NOT NULL,
					 `pop_profit` DECIMAL( 20, 10 ) NOT NULL,				 
					 `html_impression` INT NOT NULL,
					 `html_profit` DECIMAL( 20, 10 ) NOT NULL,
					 `cpv_impression` INT NOT NULL,
					 `cpv_spend` DECIMAL( 20, 10 ) NOT NULL,
					 `cpv_profit` DECIMAL( 20, 10 ) NOT NULL,		
					 `cpdid` INT NOT NULL,
					 `time` INT NOT NULL,
					 `minute_time` BIGINT NOT NULL,
					 `second_time` BIGINT NOT NULL,
		 			 PRIMARY KEY (`id`)
		 	         ) ENGINE=InnoDB CHARSET=".DB_CHARSET." COLLATE=".DB_COLLATION." AUTO_INCREMENT=1");					
				}
					
				
				$current_day_hour_time=$this->get_nexthour($current_day_hour_time);
			}
			while(1);
			
			/******************** Impression Table Creation ***************************/			
			
			
			
			
			
			
			
		
//////////////////////////////////////////////////////////////////////////////////////////////////////////






            echo "<br><br><strong>Click Backup</strong><br>";

            $current_time =date("Y",time());
            $current_time.=date("m",time());
            $current_time.=date("d",time());
            $current_time.=date("H",time());

            $current_time_new=$this->get_previous_hour($current_time);


            $old_updation=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."statistics_updation WHERE task=?",array('dailyclick_backup'));
            $old_data=$old_updation->fetch_assoc();

            $update_status=$old_data['status'];
            $update_time=$old_data['time'];







                if($update_time >0)
                {
                    //$last_tm=substr($update_time,0,8);
                    //$last_hr=substr($update_time,8,2);
                    //$previous_day=$this->get_previous_day($last_tm);
                    //$previous_day=$previous_day.$last_hr;


		    $previous_day=$update_time;	


                }
                else
                {
                    //$last_tm=substr($current_time,0,8);
                    //$last_hr=substr($current_time,8,2);
                    //$previous_day=$this->get_previous_day($last_tm);
                    //$previous_day=$previous_day.$last_hr;



		    $previous_day=$this->get_previous_hour($current_time);	

                }





                if($update_time >=$current_time_new)
                {
                    echo "<br>BREAKING - LAST HOUR CLICK STATISTICS IS COMPLETE !!!!!!!!!!!!!!!";
                }
                else
                {

                   
                    
                        echo "<br>Currently building data for ".$previous_day." - ".$current_time_new;





                        $datarow=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."dailyclicks WHERE time >? AND time <=?",array($previous_day,$current_time_new));

                        $datacount=$datarow->get_num_records();

                        $valuestring="";
                        $insert_data=array();

                        $advarraybatch=array();   // For Batch Insertion
                        $iii=0;                   // For Batch Insertion
                        $iiii=0;                  // For Batch Insertion

                        $idata=0;

                        while($datadata=$datarow->fetch_assoc())
                        {
                            if($valuestring !="")
                            $valuestring.=',';

                            $valuestring.="(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?".$siddata2." ".$refstring1.")";

							$insert_data[]=$datadata['uid'];
							$insert_data[]=$datadata['aid'];
							$insert_data[]=$datadata['kid'];
							$insert_data[]=$datadata['pid'];
							$insert_data[]=$datadata['bid'];
							$insert_data[]=$datadata['clickvalue'];
							$insert_data[]=$datadata['profit'];
							$insert_data[]=$datadata['time'];
							$insert_data[]=$datadata['ip'];
							$insert_data[]=$datadata['country'];
							$insert_data[]=$datadata['browser'];
							$insert_data[]=$datadata['platform'];
							$insert_data[]=$datadata['version'];
							$insert_data[]=$datadata['user_agent'];
							$insert_data[]=$datadata['org_time'];
							$insert_data[]=$datadata['click_type'];
							$insert_data[]=$datadata['latitude'];
							$insert_data[]=$datadata['longitude'];

                            if($category_targeting_enabled ==1)
							$insert_data[]=$datadata['sid'];

                            if($referral_enabled ==1)
						  	{
						  		$insert_data[]=$datadata['cpc_referral'];
						  		$insert_data[]=$datadata['adv_referral'];
						  		$insert_data[]=$datadata['pub_referral'];
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

						
						$datarow->free_result();
						
						
                        /*********   For Batch Insertion   ********/
                        if($iiii >0)
                        {
                            $advarraybatch[$iii][0]=$insert_data;
                            $advarraybatch[$iii][1]=$valuestring;
                        }
                        /*********   For Batch Insertion   ********/









			$db->execute_query("BEGIN");
			$failed=0;

                        if($datacount >0)
                        {
                            foreach($advarraybatch as $advkey=>$advvalue)
                            {
                                $insert=$db->execute_query("INSERT INTO ".TABLE_PREFIX."dailyclicks_backup (`uid`,`aid`,`kid`,`pid`,`bid`,`clickvalue`,`profit`,`time`,`ip`,`country`,`browser`,`platform`,`version`,`user_agent`,`org_time`,`click_type`,`latitude`,`longitude`".$siddata1." ".$refstring.") VALUES ".$advvalue[1]." ",$advvalue[0]);

                                if($insert->error !="")
				{
					$failed=1;
                                	break;
				}
                                
                            }
                        }



                        if($failed ==0)
                        {

                            $update=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET time=?,status=? WHERE task=?",array($current_time_new,2,'dailyclick_backup'));   // full completion


		            if($update->error !="")
			    $failed=1;	
		
			    if($failed ==0)
			    {	
                                $delete=$db->execute_query("DELETE FROM ".TABLE_PREFIX."dailyclicks WHERE time < ?",array($previous_day));

		            	if($delete->error !="")
			    	$failed=1;	

			    }
                        }


			if($failed ==0)
			{
				$db->execute_query("COMMIT");

			}
                        else
			{
				$db->execute_query("ROLLBACK");

                	        echo "<br>Data insertion to ".TABLE_PREFIX."dailyclicks_backup failed<br>".$insert->get_sql();

			}
                    }
                





        ////////////////////////////////////////////
		
		
echo "<br><br><strong>Advertiser Daily</strong><br>";
		
do
{    
	$supdation_res=$db->execute_query("SELECT time,status FROM ".TABLE_PREFIX."statistics_updation WHERE task=?",array('statistics_adv_daily'));
	
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
	
	$previous_hour=$current_day.date("H",time());
	$previous_hour=$this->get_previous_hour($previous_hour);
	//$previous_hour value is time before one hour
	
	if($start_time>=$previous_hour)
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
			$mintm2_res=$db->execute_query("select min(time) as tm from ".TABLE_PREFIX."cpa_daily_conversions");
			if($mintm2_res->error !="")
			{
				echo "<br>Query failed while getting minimum time from ".TABLE_PREFIX."cpa_daily_conversions table<br>".$mintm2_res->get_sql();
				break;
			}
		
			$mintm2_row=$mintm2_res->fetch_assoc();
			$temp2=intval($mintm2_row['tm']);
		}
		
		
		
		
		
		$temp3=0;
		if($affiliate_addon_enabled ==1)
		{
			$mintm3_res=$db->execute_query("select min(time) as tm from ".TABLE_PREFIX."affiliate_daily_conversions");
			if($mintm3_res->error !="")
			{
				echo "<br>Query failed while getting minimum time from ".TABLE_PREFIX."affiliate_daily_conversions table<br>".$mintm3_res->get_sql();
				break;
			}
		
			$mintm3_row=$mintm3_res->fetch_assoc();
			$temp3=intval($mintm3_row['tm']);
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
			
			
			
		if($affiliate_addon_enabled ==1)
		{
			if($temp3 ==0)
			echo "<br>Min time - Affiliate conversion = No Conversions.";
			else	
			echo "<br>Min time - Affiliate conversion = ".$temp3;
		}
		
		
		
		
		///////////////////////////////////////////////////////////////////////////
		
		if($cpa_addon_enabled ==1 || $affiliate_addon_enabled ==1)
		{
			if($temp ==0 && $temp1 ==0 && $temp2 ==0 && $temp3 ==0)
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
		
		
		
		if($cpa_addon_enabled ==1 && $affiliate_addon_enabled ==1)	
		{
			if($temp2 > $temp3 && $temp3 >0)
			$temp2=$temp3;
		}
		
		
		if($temp1 > $temp2 && $temp2 >0)
		$temp1=$temp2;
		
		
		if($temp > $temp1 && $temp1 >0)
		$temp=$temp1;
		
		
		if($temp ==0 && $temp1 >0)
		$temp=$temp1;
		else if($temp ==0 && $temp2 >0)
		$temp=$temp2;		
		else if($temp ==0 && $temp3 >0)
		$temp=$temp3;			
		
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
		$end=$previous_hour;
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

		if($updationhour  < $end)  //$previous_hour		
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
		if( $stat_row_hourly1['time'] < $end)  //$previous_hour
		{
			echo "<br>BREAKING - DAILY CLICK BACKUP TASK INCOMPLETE";
			break;
		}
	}

	$del_hdata_res=$db->execute_query("DELETE FROM ".TABLE_PREFIX."statistics_adv_daily where time >=?",array($day));
	if($del_hdata_res->error !="")
	{
		echo "<br><br>Data clearing from ".TABLE_PREFIX."statistics_adv_daily failed<br>".$del_hdata_res->get_sql();
		break;
	}
	
	$qry_res=$db->execute_query("SELECT uid,aid,kid,sum(cpc_impression) as cpc_impression ".$cpm_select." ".$html_select." ".$cpa_select." ".$pop_select." ".$cpv_select." ".$cpmrefstring1." FROM ".TABLE_PREFIX."adv_impression_hourly_backup WHERE (time>=? and time <=?) group by uid,aid,kid",array($start,$end));
	
	
	if($qry_res->error !="")
	{
		echo "<br>Data retrieval failed from ".TABLE_PREFIX."adv_impression_hourly_backup table<br>".$qry_res->get_sql();
		break;
	}

	
	$qry1_res=$db->execute_query("SELECT uid,aid,kid,count(id) as ids,COALESCE(sum(clickvalue),0) as cvalue,COALESCE(sum(profit),0) as profit,click_type".$cpmrefstring0." FROM ".TABLE_PREFIX."dailyclicks_backup WHERE (time>=? and time <=?) group by click_type,uid,aid,kid",array($start,$end));
	if($qry1_res->error !="")
	{
		echo "<br>Data retrieval failed from ".TABLE_PREFIX."dailyclicks_backup table<br>".$qry1_res->get_sql();
		break;	
	}



    if($cpa_addon_enabled ==1)
	{
		$qry2_res=$db->execute_query("SELECT uid,aid,kid,count(id) as ids,COALESCE(sum(clickvalue),0) as cvalue,COALESCE(sum(profit),0) as profit".$cpmrefstring2." FROM ".TABLE_PREFIX."cpa_daily_conversions WHERE (time>=? and time <=?) group by uid,aid,kid",array($start,$end));
		if($qry2_res->error !="")
		{
			echo "<br>Data retrieval failed from ".TABLE_PREFIX."cpa_daily_conversions table<br>".$qry2_res->get_sql();
			break;	
		}
	}

 	if($affiliate_addon_enabled ==1)
	{
		$qry3_res=$db->execute_query("SELECT uid,aid,kid,count(id) as ids,COALESCE(sum(clickvalue),0) as cvalue,COALESCE(sum(profit),0) as profit".$cpmrefstring3." FROM ".TABLE_PREFIX."affiliate_daily_conversions WHERE (time>=? and time <=?) group by uid,aid,kid",array($start,$end));
		if($qry3_res->error !="")
		{
			echo "<br>Data retrieval failed from ".TABLE_PREFIX."cpa_daily_conversions table<br>".$qry3_res->get_sql();
			break;	
		}
	}


	$result_arr=array();
	while($row=$qry_res->fetch_assoc())
	{
		$key=$row['uid'].':'.$row['aid'].':'.$row['kid'];
		
		$result_arr[$key][0]=$row['uid'];
		$result_arr[$key][1]=$row['aid'];
		$result_arr[$key][2]=$row['kid'];
		$result_arr[$key][3]=$row['cpc_impression'];
		$result_arr[$key][4]=0;
		$result_arr[$key][5]=0;
		$result_arr[$key][6]=0;
		
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
		
		if($cpa_addon_enabled ==1)
		$result_arr[$key][13]=$row['cpa_impression'];
		else
		$result_arr[$key][13]=0;
		
		
		
		$result_arr[$key][14]=0;
		$result_arr[$key][15]=0;
		$result_arr[$key][16]=0;
		
		
		if($pop_addon_enabled ==1)
		{
			$result_arr[$key][17]=$row['pop_impression'];
			$result_arr[$key][18]=$row['pop_spend'];
			$result_arr[$key][19]=$row['pop_profit'];
		}
		
		
		
		
		if($affiliate_addon_enabled ==1)
		{
			$result_arr[$key][20]=0;
			$result_arr[$key][21]=0;
			$result_arr[$key][22]=0;
		}
		
		
		if($referral_enabled ==1)
		{
			if($cpm_addon_enabled ==1)
			$result_arr[$key][23]=$row['cpm_referral'];
			
			if($pop_addon_enabled ==1)
			$result_arr[$key][24]=$row['pop_referral'];			
			
			if($html_addon_enabled ==1)
			$result_arr[$key][25]=$row['html_referral'];	
			
			$result_arr[$key][26]=0;	
			$result_arr[$key][27]=0;	
			$result_arr[$key][28]=0;	
			
			
			if($video_addon_enabled ==1)
			$result_arr[$key][29]=$row['cpv_referral'];				
		}		
		
		
		if($video_addon_enabled ==1)
		{
			$result_arr[$key][30]=$row['cpv_impression'];
			$result_arr[$key][31]=$row['cpv_spend'];
			$result_arr[$key][32]=$row['cpv_profit'];
		}		
		
		
	}
	
	$qry_res->free_result();
	
	
	
	
	
	while($row=$qry1_res->fetch_assoc())
	{
		$key=$row['uid'].':'.$row['aid'].':'.$row['kid'];
	
		$result_arr[$key][0]=$row['uid'];
		$result_arr[$key][1]=$row['aid'];
		$result_arr[$key][2]=$row['kid'];
		if(!isset($result_arr[$key][3]))
		$result_arr[$key][3]=0;
		$result_arr[$key][4]=$row['ids'];
		$result_arr[$key][5]=$row['cvalue'];
		$result_arr[$key][6]=$row['profit'];
		
		
		
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
		
		
		
		if(!isset($result_arr[$key][13]))
		$result_arr[$key][13]=0;
		
		$result_arr[$key][14]=0;
		$result_arr[$key][15]=0;
		$result_arr[$key][16]=0;
		
		
		
		if($pop_addon_enabled ==1)
		{
			if(!isset($result_arr[$key][17]))
			$result_arr[$key][17]=0;
			
			if(!isset($result_arr[$key][18]))
			$result_arr[$key][18]=0;
			
			if(!isset($result_arr[$key][19]))
			$result_arr[$key][19]=0;
		}
		
		
		
		if($affiliate_addon_enabled ==1)
		{
			$result_arr[$key][20]=0;
			$result_arr[$key][21]=0;
			$result_arr[$key][22]=0;
		}
		
		
		if($referral_enabled ==1)
		{
			if(!isset($result_arr[$key][23]))
			$result_arr[$key][23]=0;
			
			if(!isset($result_arr[$key][24]))
			$result_arr[$key][24]=0;			
			
			if(!isset($result_arr[$key][25]))
			$result_arr[$key][25]=0;	
			
			$result_arr[$key][26]=$row['cpc_referral'];			
			
			$result_arr[$key][27]=0;	
			$result_arr[$key][28]=0;	

			if(!isset($result_arr[$key][29]))
			$result_arr[$key][29]=0;			
		}			
		
		
		if($video_addon_enabled ==1)
		{
			if(!isset($result_arr[$key][30]))
			$result_arr[$key][30]=0;
			
			if(!isset($result_arr[$key][31]))
			$result_arr[$key][31]=0;
			
			if(!isset($result_arr[$key][32]))
			$result_arr[$key][32]=0;			
		}
		
	}
	
	$qry1_res->free_result();
	
	
	
	if($cpa_addon_enabled ==1)
	{
	
		while($row=$qry2_res->fetch_assoc())
		{
			$key=$row['uid'].':'.$row['aid'].':'.$row['kid'];
		
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
			
			if(!isset($result_arr[$key][13]))
			$result_arr[$key][13]=0;
			
			$result_arr[$key][14]=$row['ids'];
			$result_arr[$key][15]=$row['cvalue'];
			$result_arr[$key][16]=$row['profit'];
			
				
			if($pop_addon_enabled ==1)
			{
				if(!isset($result_arr[$key][17]))
				$result_arr[$key][17]=0;
				
				if(!isset($result_arr[$key][18]))
				$result_arr[$key][18]=0;
				
				if(!isset($result_arr[$key][19]))
				$result_arr[$key][19]=0;
			}
			
			
			
			if($affiliate_addon_enabled ==1)
			{
				$result_arr[$key][20]=0;
				$result_arr[$key][21]=0;
				$result_arr[$key][22]=0;
			}
			
			if($referral_enabled ==1)
			{
				if(!isset($result_arr[$key][23]))
				$result_arr[$key][23]=0;
				
				if(!isset($result_arr[$key][24]))
				$result_arr[$key][24]=0;			
				
				if(!isset($result_arr[$key][25]))
				$result_arr[$key][25]=0;	
				
				if(!isset($result_arr[$key][26]))
				$result_arr[$key][26]=0;				
				
				$result_arr[$key][27]=$row['cpa_referral'];			
				
				$result_arr[$key][28]=0;
				
				if(!isset($result_arr[$key][29]))
				$result_arr[$key][29]=0;					
			}	

			
			
			if($video_addon_enabled ==1)
			{
				if(!isset($result_arr[$key][30]))
				$result_arr[$key][30]=0;
				
				if(!isset($result_arr[$key][31]))
				$result_arr[$key][31]=0;
				
				if(!isset($result_arr[$key][32]))
				$result_arr[$key][32]=0;				
			}
		}
		
		
		$qry2_res->free_result();
		
	}
	
	
	
	
	if($affiliate_addon_enabled ==1)
	{
	
		while($row=$qry3_res->fetch_assoc())
		{
			$key=$row['uid'].':'.$row['aid'].':'.$row['kid'];
		
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
			
			if(!isset($result_arr[$key][13]))
			$result_arr[$key][13]=0;
			
			
			if(!isset($result_arr[$key][14]))
			$result_arr[$key][14]=0;
			
			if(!isset($result_arr[$key][15]))
			$result_arr[$key][15]=0;
			
			if(!isset($result_arr[$key][16]))
			$result_arr[$key][16]=0;
			
			
				
			if($pop_addon_enabled ==1)
			{
				if(!isset($result_arr[$key][17]))
				$result_arr[$key][17]=0;
				
				if(!isset($result_arr[$key][18]))
				$result_arr[$key][18]=0;
				
				if(!isset($result_arr[$key][19]))
				$result_arr[$key][19]=0;
			}
			
			
			
			if($affiliate_addon_enabled ==1)
			{
				$result_arr[$key][20]=$row['ids'];
				$result_arr[$key][21]=$row['cvalue'];
				$result_arr[$key][22]=$row['profit'];
			}
			
			if($referral_enabled ==1)
			{
				if(!isset($result_arr[$key][23]))
				$result_arr[$key][23]=0;
				
				if(!isset($result_arr[$key][24]))
				$result_arr[$key][24]=0;			
				
				if(!isset($result_arr[$key][25]))
				$result_arr[$key][25]=0;	
				
				if(!isset($result_arr[$key][26]))
				$result_arr[$key][26]=0;				
				
				if(!isset($result_arr[$key][27]))
				$result_arr[$key][27]=0;
								
				$result_arr[$key][28]=$row['affiliate_referral'];		

				if(!isset($result_arr[$key][29]))
				$result_arr[$key][29]=0;					
			}		

			if($video_addon_enabled ==1)
			{
				if(!isset($result_arr[$key][30]))
				$result_arr[$key][30]=0;
				
				if(!isset($result_arr[$key][31]))
				$result_arr[$key][31]=0;
				
				if(!isset($result_arr[$key][32]))
				$result_arr[$key][32]=0;				
			}			
		}
		
		$qry3_res->free_result();
		
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

		$valuestring.="(?,?,?,?,?,?,?,?,?".$cpm_insert2." ".$html_select2." ".$cpa_insert2." ".$pop_insert2." ".$affiliate_insert2." ".$cpmrefstring6." ".$cpv_insert2.")";
		
		
		
		
		$clickcount=$v[4];
		$clickcount1=0;
		$clickcount2=0;
		$clickcount3=0;
		$clickcount4=0;
		$clickcount5=0;
		
		
		if($v[12] ==0)
		$clickcount1=$clickcount;
		else if($v[12] ==1)
		$clickcount2=$clickcount;
		else if($v[12] ==6)
		$clickcount3=$clickcount;
		else if($v[12] ==12)
		$clickcount4=$clickcount;
		else if($v[12] ==13)
		$clickcount5=$clickcount;		
		
		
		
		
		$insert_data[]='';
		$insert_data[]=$v[0];
		$insert_data[]=$v[1];
		$insert_data[]=$v[2];
		$insert_data[]=$v[3];
		$insert_data[]=$clickcount1;
		$insert_data[]=$day;
		$insert_data[]=$v[5];
		$insert_data[]=$v[6];
		

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
			$insert_data[]=$v[13];
			$insert_data[]=$clickcount3;
			$insert_data[]=$v[14];
			$insert_data[]=$v[15];
			$insert_data[]=$v[16];	
		}
		
		
		if($pop_addon_enabled ==1)
		{
			$insert_data[]=$v[17];
			$insert_data[]=$v[18];
			$insert_data[]=$v[19];	
		}
		
		if($affiliate_addon_enabled ==1)
		{
			$insert_data[]=$clickcount4;
			$insert_data[]=$v[20];
			$insert_data[]=$v[21];
			$insert_data[]=$v[22];	
		}
		
		if($referral_enabled ==1)
		{
			$insert_data[]=$v[26];	
			
			if($cpm_addon_enabled ==1)
			$insert_data[]=$v[23];	
			
			if($pop_addon_enabled ==1)
			$insert_data[]=$v[24];	
			
			if($html_addon_enabled ==1)
			$insert_data[]=$v[25];	
			
			if($cpa_addon_enabled ==1)
			$insert_data[]=$v[27];	
			
			if($affiliate_addon_enabled ==1)
			$insert_data[]=$v[28];	
			
			if($video_addon_enabled ==1)
			$insert_data[]=$v[29];			
		}
		
		if($video_addon_enabled ==1)
		{
			$insert_data[]=$v[30];
			$insert_data[]=$v[31];
			$insert_data[]=$v[32];
			$insert_data[]=$clickcount5;
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
			$ins_qry_res=$db->execute_query("INSERT INTO ".TABLE_PREFIX."statistics_adv_daily (`id`,`uid`, `aid`, `kid`, `impression`,`click`,`time`,`money_spent`,`pub_profit`".$cpm_insert1." ".$html_select1." ".$cpa_insert1." ".$pop_insert1." ".$affiliate_insert1." ".$cpmrefstring5." ".$cpv_insert1.") VALUES ".$advvalue[1]." ",$advvalue[0]);		
					
			if($ins_qry_res->error =="")		
			$num_row_inserted=$num_row_inserted+$ins_qry_res->get_num_records();
			else 
			{
				echo "<br>Data insertion to ".TABLE_PREFIX."statistics_adv_daily failed<br>".$ins_qry_res->get_sql();
				break;
			}	
		}
    }
	

	if($num_row_inserted == $result_count)
	{
		if($day == $current_day)
		{		
			if($day."23"!=$previous_hour)
			{
				//partial completion
				$up_st_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET time=?,status=? WHERE task=?",array($previous_hour,1,'statistics_adv_daily'));	
				echo "<br>BREAKING AFTER PARTIALLY UPDATING LAST DAY STATISTICS";
				break;
			}
			else
			{
				//full completion
				$up_st_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET time=?,status=? WHERE task=?",array($previous_hour,2,'statistics_adv_daily'));
				
				if($up_st_res->get_error() =='')
				echo "<br>BREAKING AFTER COMPLETELY UPDATING LAST DAY STATISTICS";
				else 
				echo "<br>Statistics updation failed in ".TABLE_PREFIX."statistics_updation table<br>".$up_st_res->get_sql();
				
				
				break;
			}
		}
		else if($day < $current_day)	
		{
			//full completion
			$up_st_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET time=?,status=? WHERE task=?",array($day."23",2,'statistics_adv_daily'));
		
			if($up_st_res->get_error() !='')
			echo "<br>Statistics updation failed in ".TABLE_PREFIX."statistics_updation table<br>".$up_st_res->get_sql();
		}
	}

flush();
//echo mysql_error();
}while(1);






//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		

echo "<br><br><strong>Publisher Daily</strong><br>";

do
{
	$supdation_res=$db->execute_query("SELECT time,status FROM ".TABLE_PREFIX."statistics_updation WHERE task=?",array('statistics_pub_daily'));

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
		
	$previous_hour=$current_day.date("H",time());
	$previous_hour=$this->get_previous_hour($previous_hour);

	//$previous_hour value is time before one hour

	if($start_time>=$previous_hour)
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
			$mintm2_res=$db->execute_query("select min(time) as tm from ".TABLE_PREFIX."cpa_daily_conversions");
			if($mintm2_res->error !="")
			{
				echo "<br>Query failed while getting minimum time from ".TABLE_PREFIX."cpa_daily_conversions table<br>".$mintm2_res->get_sql();
				break;
			}
		
			$mintm2_row=$mintm2_res->fetch_assoc();
			$temp2=intval($mintm2_row['tm']);
		}
		
		
		
		$temp3=0;
		if($affiliate_addon_enabled ==1)
		{
			$mintm3_res=$db->execute_query("select min(time) as tm from ".TABLE_PREFIX."affiliate_daily_conversions");
			if($mintm3_res->error !="")
			{
				echo "<br>Query failed while getting minimum time from ".TABLE_PREFIX."affiliate_daily_conversions table<br>".$mintm3_res->get_sql();
				break;
			}
		
			$mintm3_row=$mintm3_res->fetch_assoc();
			$temp3=intval($mintm3_row['tm']);
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
			
			
			
		if($affiliate_addon_enabled ==1)
		{
			if($temp3 ==0)
			echo "<br>Min time - Affiliate conversion = No Conversions.";
			else	
			echo "<br>Min time - Affiliate conversion = ".$temp3;
		}
		
		
		
		
		///////////////////////////////////////////////////////////////////////////
		
		if($cpa_addon_enabled ==1 || $affiliate_addon_enabled ==1)
		{
			if($temp ==0 && $temp1 ==0 && $temp2 ==0 && $temp3 ==0)
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
		
		
		
		if($cpa_addon_enabled ==1 && $affiliate_addon_enabled ==1)	
		{
			if($temp2 > $temp3 && $temp3 >0)
			$temp2=$temp3;
		}
		
		
		if($temp1 > $temp2 && $temp2 >0)
		$temp1=$temp2;
		
		
		if($temp > $temp1 && $temp1 >0)
		$temp=$temp1;
		
		if($temp ==0 && $temp1 >0)
		$temp=$temp1;
		else if($temp ==0 && $temp2 >0)
		$temp=$temp2;		
		else if($temp ==0 && $temp3 >0)
		$temp=$temp3;			
		
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
		$end=$previous_hour;
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

		if($updationhour  < $end)  //$previous_hour		
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
		
		if( $stat_row_hourly1['time'] < $end)  //$previous_hour
		{
			echo "<br>BREAKING - DAILY CLICK BACKUP TASK INCOMPLETE";
			break;
		}
	}

	$del_hdata_res=$db->execute_query("DELETE FROM ".TABLE_PREFIX."statistics_pub_daily where time >=?",array($day));
	if($del_hdata_res->error !="")
	{
		echo "<br><br>Data clearing from ".TABLE_PREFIX."statistics_pub_daily failed<br>".$del_hdata_res->get_sql();
		break;
	}

	$qry_res=$db->execute_query("SELECT pid,bid,sum(cpc_impression) as cpc_impression ".$cpm_select." ".$html_select." ".$siddata3." ".$cpa_select." ".$pop_select." ".$cpmrefstring1." ".$cpv_select." FROM ".TABLE_PREFIX."pub_impression_hourly_backup WHERE (time>=? and time <=?) group by pid".$siddata3.",bid",array($start,$end));
	if($qry_res->error !="")
	{
		echo "<br>Data retrieval failed from ".TABLE_PREFIX."pub_impression_hourly_backup table<br>".$qry_res->get_sql();
		break;
	}

	$qry1_res=$db->execute_query("SELECT pid,bid,count(id) as ids,COALESCE(sum(clickvalue),0) as cvalue,COALESCE(sum(profit),0) as profit,click_type".$siddata3." ".$cpmrefstring0." FROM ".TABLE_PREFIX."dailyclicks_backup WHERE (time>=? and time <=?)  group by click_type,pid".$siddata3.",bid",array($start,$end));
	if($qry1_res->error !="")
	{
		echo "<br>Data retrieval failed from ".TABLE_PREFIX."dailyclicks_backup table<br>".$qry1_res->get_sql();
		break;
	}
	

	
	if($cpa_addon_enabled ==1)
	{
		$qry2_res=$db->execute_query("SELECT pid,bid,count(id) as ids,COALESCE(sum(clickvalue),0) as cvalue,COALESCE(sum(profit),0) as profit".$siddata3." ".$cpmrefstring2." FROM ".TABLE_PREFIX."cpa_daily_conversions WHERE (time>=? and time <=?)  group by pid".$siddata3.",bid",array($start,$end));
		if($qry2_res->error !="")
		{
			echo "<br>Data retrieval failed from ".TABLE_PREFIX."cpa_daily_conversions table<br>".$qry2_res->get_sql();
			break;
		}
	}
	
	
	
	
	if($affiliate_addon_enabled ==1)
	{
		$qry3_res=$db->execute_query("SELECT pid,bid,count(id) as ids,COALESCE(sum(clickvalue),0) as cvalue,COALESCE(sum(profit),0) as profit".$siddata3." ".$cpmrefstring3." FROM ".TABLE_PREFIX."affiliate_daily_conversions WHERE (time>=? and time <=?)  group by pid".$siddata3.",bid",array($start,$end));
		if($qry3_res->error !="")
		{
			echo "<br>Data retrieval failed from ".TABLE_PREFIX."cpa_daily_conversions table<br>".$qry3_res->get_sql();
			break;
		}
	}
	


	$result_arr=array();
	while($row=$qry_res->fetch_assoc())
	{
		if($category_targeting_enabled ==1)
		$key=$row['pid'].':'.$row['sid'].':'.$row['bid'];
		else 
		$key=$row['pid'].':'.$row['bid'];
			

		$result_arr[$key][0]=$row['pid'];
		$result_arr[$key][1]=$row['bid'];
		$result_arr[$key][2]=$row['cpc_impression'];
		$result_arr[$key][3]=0;
		$result_arr[$key][4]=0;
		$result_arr[$key][5]=0;
		
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
		
		
		
		$result_arr[$key][21]=0;
		$result_arr[$key][22]=0;
		
		if($affiliate_addon_enabled ==1)
		{
			$result_arr[$key][23]=0;
			$result_arr[$key][24]=0;
			$result_arr[$key][25]=0;
			$result_arr[$key][26]=0;
		}
		
		if($referral_enabled ==1)
		{
			if($cpm_addon_enabled ==1)
			$result_arr[$key][27]=$row['cpm_referral'];
			
			if($pop_addon_enabled ==1)
			$result_arr[$key][28]=$row['pop_referral'];			
			
			if($html_addon_enabled ==1)
			$result_arr[$key][29]=$row['html_referral'];	
			
			$result_arr[$key][30]=0;	
			$result_arr[$key][31]=0;	
			$result_arr[$key][32]=0;	
			
			
			if($video_addon_enabled ==1)
			$result_arr[$key][33]=$row['cpv_referral'];				
		}		
		
		if($video_addon_enabled ==1)
		{
			$result_arr[$key][34]=$row['cpv_impression'];
			$result_arr[$key][35]=$row['cpv_spend'];
			$result_arr[$key][36]=$row['cpv_profit'];
			$result_arr[$key][37]=0;
		}			
	}
	
	
	$qry_res->free_result();
	
	

	while($row=$qry1_res->fetch_assoc())
	{
		if($category_targeting_enabled ==1)
		$key=$row['pid'].':'.$row['sid'].':'.$row['bid'];
		else
		$key=$row['pid'].':'.$row['bid'];

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
		$result_arr[$key][21]=$row['ids'];
		else if($row['click_type'] ==6)
		$result_arr[$key][22]=$row['ids'];
		else if($row['click_type'] ==12)
		$result_arr[$key][23]=$row['ids'];
		else if($row['click_type'] ==13)
		$result_arr[$key][37]=$row['ids'];		
		
		
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
			$result_arr[$key][24]=0;
			$result_arr[$key][25]=0;
			$result_arr[$key][26]=0;
		}
		
		if($referral_enabled ==1)
		{
			if(!isset($result_arr[$key][27]))
			$result_arr[$key][27]=0;
			
			if(!isset($result_arr[$key][28]))
			$result_arr[$key][28]=0;			
			
			if(!isset($result_arr[$key][29]))
			$result_arr[$key][29]=0;	
			
			if($row['click_type'] ==0)
			$result_arr[$key][30]=$row['cpc_referral'];			
			
			$result_arr[$key][31]=0;	
			$result_arr[$key][32]=0;	

			if(!isset($result_arr[$key][33]))
			$result_arr[$key][33]=0;				
		}	
		
		if($video_addon_enabled ==1)
		{
			if(!isset($result_arr[$key][34]))
			$result_arr[$key][34]=0;
			
			if(!isset($result_arr[$key][35]))
			$result_arr[$key][35]=0;
			
			if(!isset($result_arr[$key][36]))
			$result_arr[$key][36]=0;			
		}		
	}
	
	$qry1_res->free_result();
	

	
	if($cpa_addon_enabled ==1)
	{

	while($row=$qry2_res->fetch_assoc())
	{
		if($category_targeting_enabled ==1)
		$key=$row['pid'].':'.$row['sid'].':'.$row['bid'];
		else
		$key=$row['pid'].':'.$row['bid'];

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
		
		
		if(!isset($result_arr[$key][14]))
		$result_arr[$key][14]=0;
			
		$result_arr[$key][15]=$row['ids'];
		$result_arr[$key][16]=$row['cvalue'];
		$result_arr[$key][17]=$row['profit'];
		
		if($pop_addon_enabled ==1)
		{
			if(!isset($result_arr[$key][18]))
			$result_arr[$key][18]=0;
			
			if(!isset($result_arr[$key][19]))
			$result_arr[$key][19]=0;
			
			if(!isset($result_arr[$key][20]))
			$result_arr[$key][20]=0;
		}
		
		
		
		
		if(!isset($result_arr[$key][21]))
		$result_arr[$key][21]=0;
		
		if(!isset($result_arr[$key][22]))
		$result_arr[$key][22]=0;
			
		if(!isset($result_arr[$key][23]))
		$result_arr[$key][23]=0;
			
		
		if($affiliate_addon_enabled ==1)
		{
			$result_arr[$key][24]=0;
			$result_arr[$key][25]=0;
			$result_arr[$key][26]=0;
		}
		
		if($referral_enabled ==1)
		{
			if(!isset($result_arr[$key][27]))
			$result_arr[$key][27]=0;
			
			if(!isset($result_arr[$key][28]))
			$result_arr[$key][28]=0;			
			
			if(!isset($result_arr[$key][29]))
			$result_arr[$key][29]=0;	
			
			if(!isset($result_arr[$key][30]))
			$result_arr[$key][30]=0;				
			
			$result_arr[$key][31]=$row['cpa_referral'];			
			
			$result_arr[$key][32]=0;
			
			if(!isset($result_arr[$key][33]))
			$result_arr[$key][33]=0;				
			
		}		
		
		if($video_addon_enabled ==1)
		{
			if(!isset($result_arr[$key][34]))
			$result_arr[$key][34]=0;
			
			if(!isset($result_arr[$key][35]))
			$result_arr[$key][35]=0;
			
			if(!isset($result_arr[$key][36]))
			$result_arr[$key][36]=0;

			if(!isset($result_arr[$key][37]))
			$result_arr[$key][37]=0;			
		}		
	}
	
	$qry2_res->free_result();
	
	

	}

	
	if($affiliate_addon_enabled ==1)
	{
		
		
		
	while($row=$qry3_res->fetch_assoc())
	{
		if($category_targeting_enabled ==1)
		$key=$row['pid'].':'.$row['sid'].':'.$row['bid'];
		else
		$key=$row['pid'].':'.$row['bid'];

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
		
		
		if(!isset($result_arr[$key][14]))
		$result_arr[$key][14]=0;
			
		
		
		if(!isset($result_arr[$key][15]))
		$result_arr[$key][15]=0;
		
		if(!isset($result_arr[$key][16]))
		$result_arr[$key][16]=0;
		
		if(!isset($result_arr[$key][17]))
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
		
		
		
		
		if(!isset($result_arr[$key][21]))
		$result_arr[$key][21]=0;
		
		if(!isset($result_arr[$key][22]))
		$result_arr[$key][22]=0;
			
		if(!isset($result_arr[$key][23]))
		$result_arr[$key][23]=0;
			
		
		if($affiliate_addon_enabled ==1)
		{
			$result_arr[$key][24]=$row['ids'];
			$result_arr[$key][25]=$row['cvalue'];
			$result_arr[$key][26]=$row['profit'];
		}
		
		if($referral_enabled ==1)
		{
			if(!isset($result_arr[$key][27]))
			$result_arr[$key][27]=0;
			
			if(!isset($result_arr[$key][28]))
			$result_arr[$key][28]=0;			
			
			if(!isset($result_arr[$key][29]))
			$result_arr[$key][29]=0;	
			
			if(!isset($result_arr[$key][30]))
			$result_arr[$key][30]=0;				
			
			if(!isset($result_arr[$key][31]))
			$result_arr[$key][31]=0;
							
			$result_arr[$key][32]=$row['affiliate_referral'];			
			
			if(!isset($result_arr[$key][33]))
			$result_arr[$key][33]=0;			
		}		
		
		if($video_addon_enabled ==1)
		{
			if(!isset($result_arr[$key][34]))
			$result_arr[$key][34]=0;
			
			if(!isset($result_arr[$key][35]))
			$result_arr[$key][35]=0;
			
			if(!isset($result_arr[$key][36]))
			$result_arr[$key][36]=0;

			if(!isset($result_arr[$key][37]))
			$result_arr[$key][37]=0;			
		}		

	}
	
	
	$qry3_res->free_result();	
	
	

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

		$valuestring.="(?,?,?,?,?,?,?,?".$cpm_insert2." ".$html_select2." ".$siddata2." ".$cpa_insert2." ".$pop_insert2." ".$affiliate_insert2." ".$cpmrefstring6." ".$cpv_insert2.")";
		
		
		
		$clickcount1=$v[3];
		$clickcount2=$v[21];
		$clickcount3=$v[22];
		$clickcount4=$v[23];
		$clickcount5=$v[37];		
		
		
		$insert_data[]='';
		$insert_data[]=$v[0];
		$insert_data[]=$v[1];
		$insert_data[]=$v[2];
		$insert_data[]=$clickcount1;
		$insert_data[]=$day;
		$insert_data[]=$v[4];
		$insert_data[]=$v[5];
		
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
			$insert_data[]=$v[24];
			$insert_data[]=$v[25];
			$insert_data[]=$v[26];	
		}
		
		if($referral_enabled ==1)
		{
			$insert_data[]=$v[30];	
			
			if($cpm_addon_enabled ==1)
			$insert_data[]=$v[27];	
			
			if($pop_addon_enabled ==1)
			$insert_data[]=$v[28];	
			
			if($html_addon_enabled ==1)
			$insert_data[]=$v[29];	
			
			if($cpa_addon_enabled ==1)
			$insert_data[]=$v[31];	
			
			if($affiliate_addon_enabled ==1)
			$insert_data[]=$v[32];	
			
			if($video_addon_enabled ==1)
			$insert_data[]=$v[33];	
		}		
		
		
		if($video_addon_enabled ==1)
		{
			$insert_data[]=$v[34];
			$insert_data[]=$v[35];
			$insert_data[]=$v[36];
			$insert_data[]=$clickcount5;
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

			$ins_qry_res=$db->execute_query("INSERT INTO ".TABLE_PREFIX."statistics_pub_daily (`id`,`uid`, `bid`, `impression`,`click`,`time`,`money_spent`,`pub_profit`".$cpm_insert1." ".$html_select1." ".$siddata1." ".$cpa_insert1." ".$pop_insert1." ".$affiliate_insert1." ".$cpmrefstring5." ".$cpv_insert1.") VALUES ".$advvalue[1]." ",$advvalue[0]);
			if($ins_qry_res->error =="")
			$num_row_inserted=$num_row_inserted+$ins_qry_res->get_num_records();
			else
			{
				echo "<br>Data insertion to ".TABLE_PREFIX."statistics_pub_daily failed<br>".$ins_qry_res->get_sql();
				break;
			}
		}
   }
	

	if($num_row_inserted == $result_count)
	{
		if($day == $current_day)
		{
		if($day."23"!=$previous_hour)
		{
			//partial completion
			$up_st_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET time=?,status=? WHERE task=?",array($previous_hour,1,'statistics_pub_daily'));
			echo "<br>BREAKING AFTER PARTIALLY UPDATING LAST DAY STATISTICS";
			break;
		}
		else
		{
			//full completion
			$up_st_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET time=?,status=? WHERE task=?",array($previous_hour,2,'statistics_pub_daily'));
			
			if($up_st_res->get_error() =='')
			echo "<br>BREAKING AFTER COMPLETELY UPDATING LAST DAY STATISTICS";
			else
			echo "<br>Statistics updation failed in ".TABLE_PREFIX."statistics_updation table<br>".$up_st_res->get_sql();
			
			break;
		}
		
		}
		else if($day < $current_day)
		{
			$up_st_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET time=?,status=? WHERE task=?",array($day."23",2,'statistics_pub_daily'));
		
			if($up_st_res->get_error() !='')
			echo "<br>Statistics updation failed in ".TABLE_PREFIX."statistics_updation table<br>".$up_st_res->get_sql();
		}
	}
	flush();
	//echo mysql_error();
}while(1);


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo "<br><br><strong>Advertiser Monthly</strong><br>";
do
{
	$supdation_res=$db->execute_query("SELECT time,status FROM ".TABLE_PREFIX."statistics_updation WHERE task=?",array('statistics_adv_monthly'));
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
		$min_qry_res=$db->execute_query("SELECT min(time) as mt FROM ".TABLE_PREFIX."statistics_adv_daily");
		if($min_qry_res->error =="")
		{
			$min_time_daily_row=$min_qry_res->fetch_assoc();
			if($min_time_daily_row['mt']=='')
			{
				echo "<br>No data in the ".TABLE_PREFIX."statistics_adv_daily table";
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
			echo "<br>Query failed while getting minimum time from ".TABLE_PREFIX."statistics_adv_daily table<br>".$min_qry_res->get_sql();
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
		
	
		$del_qry_res=$db->execute_query("DELETE FROM ".TABLE_PREFIX."statistics_adv_daily WHERE time<=?",array($delete_time));
		if($del_qry_res->error =="")
		echo "<br>Old data deleted from ".TABLE_PREFIX."statistics_adv_daily<br>".$del_qry_res->get_sql();
		else 
		echo "<br>Old data deletion from ".TABLE_PREFIX."statistics_adv_daily failed<br>".$del_qry_res->get_sql();
    }

	$start=	$month."01";
	$end=$month."31";

echo "<br>Currently building data for ".$month;

$st_up_qry_res=$db->execute_query("SELECT time,status FROM ".TABLE_PREFIX."statistics_updation WHERE task=?",array('statistics_adv_daily'));
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



	$st_del_res=$db->execute_query("DELETE FROM ".TABLE_PREFIX."statistics_adv_monthly WHERE time=?",array($month));
	if($st_del_res->error !="")
	{
		echo "<br>Data clearing from ".TABLE_PREFIX."statistics_adv_monthly failed<br>".$st_del_res->get_sql();
		break;
	}
	
	
	
	$sel_qry_res=$db->execute_query("SELECT uid,aid,kid,sum(impression) as imp,sum(click) as clk,sum(money_spent) as spent,sum(pub_profit) as profit ".$cpm_select.$cpm_select1." ".$html_select." ".$cpa_select3." ".$pop_select." ".$affiliate_select3." ".$cpmrefstring4." ".$cpv_select.$cpv_select1." FROM ".TABLE_PREFIX."statistics_adv_daily WHERE (time>=? and time<=?)  group by uid,aid,kid",array($start,$end));
	if($sel_qry_res->error !="")
	{
		echo "<br>Data retrieval failed from ".TABLE_PREFIX."statistics_adv_daily table<br>".$sel_qry_res->get_sql();
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

		$valuestring.="(?,?,?,?,?,?,?,?,?".$cpm_insert2." ".$html_select2." ".$cpa_insert2." ".$pop_insert2." ".$affiliate_insert2." ".$cpmrefstring6." ".$cpv_insert2.")";
		
		
		$insert_data[]='';
		$insert_data[]=$row['uid'];
		$insert_data[]=$row['aid'];
		$insert_data[]=$row['kid'];
		$insert_data[]=$row['imp'];
		$insert_data[]=$row['clk'];
		$insert_data[]=$month;
		$insert_data[]=$row['spent'];
		$insert_data[]=$row['profit'];
		
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
		
		
		if($referral_enabled ==1)
		{
			$insert_data[]=$row['cpc_referral'];	
			
			if($cpm_addon_enabled ==1)
			$insert_data[]=$row['cpm_referral'];	
			
			if($pop_addon_enabled ==1)
			$insert_data[]=$row['pop_referral'];	
			
			if($html_addon_enabled ==1)
			$insert_data[]=$row['html_referral'];	
			
			if($cpa_addon_enabled ==1)
			$insert_data[]=$row['cpa_referral'];	
			
			if($affiliate_addon_enabled ==1)
			$insert_data[]=$row['affiliate_referral'];	
			
			if($video_addon_enabled ==1)
			$insert_data[]=$row['cpv_referral'];			
		}		
		
		
		if($video_addon_enabled ==1)
		{
			$insert_data[]=$row['cpv_impression'];
			$insert_data[]=$row['cpv_spend'];
			$insert_data[]=$row['cpv_profit'];
			$insert_data[]=$row['cpv_click'];
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
			$ins_qry_res=$db->execute_query("INSERT INTO ".TABLE_PREFIX."statistics_adv_monthly (`id`,`uid`, `aid`, `kid`, `impression`,`click`, `time`, `money_spent`,`pub_profit`".$cpm_insert1." ".$html_select1." ".$cpa_insert1." ".$pop_insert1." ".$affiliate_insert1." ".$cpmrefstring5." ".$cpv_insert1.") VALUES ".$advvalue[1]." ",$advvalue[0]);	
			
			if($ins_qry_res->error =="")
			$num_row_inserted=$num_row_inserted+$ins_qry_res->get_num_records();
			else
			{
				echo"<br>Data insertion to ".TABLE_PREFIX."statistics_adv_monthly failed<br>".$ins_qry_res->get_sql();
				break;
			}			
		}
    }
    

	if($num_row_inserted == $result_count)
	{
		$up_qry_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET status=?,time=? WHERE task=?",array(2,$month,'statistics_adv_monthly'));
		if($month==$this->get_previous_month($current_month))
		{
			echo "<br>BREAKING AFTER UPDATING LAST MONTH STATISTICS";
			break;
		}
	}
	else 
	{
		$up_qry_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET status=?,time=? WHERE task=?",array(1,$month,'statistics_adv_monthly'));
		echo "<br>BREAKING AFTER PARTIALLY UPDATING LAST MONTH STATISTICS";
		break;
	}

	//echo mysql_error();
	flush();
}while (1);

///////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo "<br><br><strong>Publisher Monthly</strong><br>";
do
{
	$supdation_res=$db->execute_query("SELECT time,status FROM ".TABLE_PREFIX."statistics_updation WHERE task=?",array('statistics_pub_monthly'));
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
		$min_qry_res=$db->execute_query("SELECT min(time) as mt FROM ".TABLE_PREFIX."statistics_pub_daily");
		if($min_qry_res->error =="")
		{
			$min_time_daily_row=$min_qry_res->fetch_assoc();
			if($min_time_daily_row['mt']=='')
			{
				echo "<br>No data in the ".TABLE_PREFIX."statistics_pub_daily table";
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
			echo "<br>Query failed while getting minimum time from ".TABLE_PREFIX."statistics_pub_daily table<br>".$min_qry_res->get_sql();
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
		

		$del_qry_res=$db->execute_query("DELETE FROM ".TABLE_PREFIX."statistics_pub_daily WHERE time<=?",array($delete_time));
		if($del_qry_res->error =="")
		echo "<br>Old data deleted from ".TABLE_PREFIX."statistics_pub_daily<br>".$del_qry_res->get_sql();
		else
		echo "<br>Old data deletion from ".TABLE_PREFIX."statistics_pub_daily failed<br>".$del_qry_res->get_sql();
	}

	$start=	$month."01";
	$end=$month."31";
	echo "<br>Currently building data for ".$month;

	$st_up_qry_res=$db->execute_query("SELECT time,status FROM ".TABLE_PREFIX."statistics_updation WHERE task=?",array('statistics_pub_daily'));
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


	$st_del_res=$db->execute_query("DELETE FROM ".TABLE_PREFIX."statistics_pub_monthly WHERE time=?",array($month));
	if($st_del_res->error !="")
	{
		echo "<br>Data clearing from ".TABLE_PREFIX."statistics_pub_monthly failed<br>".$st_del_res->get_sql();
		break;
	}

	$sel_qry_res=$db->execute_query("SELECT uid,bid,sum(impression) as imp,sum(click) as clk,sum(money_spent) as spent,sum(pub_profit) as profit ".$cpm_select.$cpm_select1." ".$html_select." ".$siddata3." ".$cpa_select3." ".$pop_select." ".$affiliate_select3." ".$cpmrefstring4." ".$cpv_select.$cpv_select1." FROM ".TABLE_PREFIX."statistics_pub_daily WHERE (time>=? and time<=?)  group by uid".$siddata3.",bid",array($start,$end));
	if($sel_qry_res->error !="")
	{
		echo "<br>Data retrieval failed from ".TABLE_PREFIX."statistics_pub_daily table<br>".$sel_qry_res->get_sql();
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

		$valuestring.="(?,?,?,?,?,?,?,?".$cpm_insert2." ".$html_select2." ".$siddata2." ".$cpa_insert2." ".$pop_insert2." ".$affiliate_insert2." ".$cpmrefstring6." ".$cpv_insert2.")";
		
		
		$insert_data[]='';
		$insert_data[]=$row['uid'];
		$insert_data[]=$row['bid'];
		$insert_data[]=$row['imp'];
		$insert_data[]=$row['clk'];
		$insert_data[]=$month;
		$insert_data[]=$row['spent'];
		$insert_data[]=$row['profit'];
		
		
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
		
		if($referral_enabled ==1)
		{
			$insert_data[]=$row['cpc_referral'];	
			
			if($cpm_addon_enabled ==1)
			$insert_data[]=$row['cpm_referral'];	
			
			if($pop_addon_enabled ==1)
			$insert_data[]=$row['pop_referral'];	
			
			if($html_addon_enabled ==1)
			$insert_data[]=$row['html_referral'];	
			
			if($cpa_addon_enabled ==1)
			$insert_data[]=$row['cpa_referral'];	
			
			if($affiliate_addon_enabled ==1)
			$insert_data[]=$row['affiliate_referral'];	
			
			if($video_addon_enabled ==1)
			$insert_data[]=$row['cpv_referral'];						
		}			
		
		if($video_addon_enabled ==1)
		{
			$insert_data[]=$row['cpv_impression'];
			$insert_data[]=$row['cpv_spend'];
			$insert_data[]=$row['cpv_profit'];
			$insert_data[]=$row['cpv_click'];	
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
			$ins_qry_res=$db->execute_query("INSERT INTO ".TABLE_PREFIX."statistics_pub_monthly (`id`,`uid`, `bid`, `impression`,`click`, `time`, `money_spent`,`pub_profit`".$cpm_insert1." ".$html_select1." ".$siddata1." ".$cpa_insert1." ".$pop_insert1." ".$affiliate_insert1." ".$cpmrefstring5." ".$cpv_insert1.") VALUES ".$advvalue[1]." ",$advvalue[0]);

			if($ins_qry_res->error =="")
			$num_row_inserted=$num_row_inserted+$ins_qry_res->get_num_records();
			else
			{
				echo"<br>Data insertion to ".TABLE_PREFIX."statistics_pub_monthly failed<br>".$ins_qry_res->get_sql();
				break;
			}	
		}
    }


	if($num_row_inserted == $result_count)
	{
		$up_qry_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET status=?,time=? WHERE task=?",array(2,$month,'statistics_pub_monthly'));
		if($month==$this->get_previous_month($current_month))
		{
			echo "<br>BREAKING AFTER UPDATING LAST MONTH STATISTICS";
			break;
		}
	}
	else
	{
		$up_qry_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET status=?,time=? WHERE task=?",array(1,$month,'statistics_pub_monthly'));
		echo "<br>BREAKING AFTER PARTIALLY UPDATING LAST MONTH STATISTICS";
		break;
	}
	//echo mysql_error();
	flush();
}while (1);

/////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo "<br><br><strong>Advertiser Yearly</strong><br>";

do
{
	$supdation_res=$db->execute_query("SELECT time,status FROM ".TABLE_PREFIX."statistics_updation WHERE task=?",array('statistics_adv_yearly'));
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
		$min_qry_res=$db->execute_query("SELECT min(time) as mt FROM ".TABLE_PREFIX."statistics_adv_monthly");
		if($min_qry_res->error =="")
		{
			$min_time_monthly_row=$min_qry_res->fetch_assoc();
			if($min_time_monthly_row['mt']=='')
			{
				echo "<br>No data in the ".TABLE_PREFIX."statistics_adv_monthly table";
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
			echo "<br>Query failed while getting minimum time from ".TABLE_PREFIX."statistics_adv_monthly table<br>".$min_qry_res->get_sql();
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
		$del_qry_res=$db->execute_query("DELETE FROM ".TABLE_PREFIX."statistics_adv_monthly WHERE time<=?",array($delete_time));
	
		if($del_qry_res->error =="")
		echo "<br>Old data deleted from ".TABLE_PREFIX."statistics_adv_monthly<br>".$del_qry_res->get_sql();
		else
		echo "<br>Old data deletion from ".TABLE_PREFIX."statistics_adv_monthly failed<br>".$del_qry_res->get_sql();
	}

$start=	$year."01";
$end=$year."12";

echo "<br>Currently building data for ".$year;

	$st_up_qry_res=$db->execute_query("SELECT time,status FROM ".TABLE_PREFIX."statistics_updation WHERE task=?",array('statistics_adv_monthly'));
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



	$st_del_res=$db->execute_query("DELETE FROM ".TABLE_PREFIX."statistics_adv_yearly WHERE time=?",array($year));
	if($st_del_res->error !="")
	{
		echo "<br>Data clearing from ".TABLE_PREFIX."statistics_adv_yearly failed<br>".$st_del_res->get_sql();
		break;

	}

	$sel_qry_res=$db->execute_query("SELECT uid,aid,kid,sum(impression) as imp,sum(click) as clk,sum(money_spent) as spent,sum(pub_profit) as profit ".$cpm_select.$cpm_select1." ".$html_select." ".$cpa_select3." ".$pop_select." ".$affiliate_select3." ".$cpmrefstring4." ".$cpv_select.$cpv_select1." FROM ".TABLE_PREFIX."statistics_adv_monthly WHERE (time>=? and time<=?)  group by uid,aid,kid",array($start,$end));
	if($sel_qry_res->error !="")
	{
		echo "<br>Data retrieval failed from ".TABLE_PREFIX."statistics_adv_monthly table<br>".$sel_qry_res->get_sql();
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

		$valuestring.="(?,?,?,?,?,?,?,?,?".$cpm_insert2." ".$html_select2." ".$cpa_insert2." ".$pop_insert2." ".$affiliate_insert2." ".$cpmrefstring6." ".$cpv_insert2.")";
		
		
		$insert_data[]='';
		$insert_data[]=$row['uid'];
		$insert_data[]=$row['aid'];
		$insert_data[]=$row['kid'];
		$insert_data[]=$row['imp'];
		$insert_data[]=$row['clk'];
		$insert_data[]=$year;
		$insert_data[]=$row['spent'];
		$insert_data[]=$row['profit'];
		
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
		
		if($referral_enabled ==1)
		{
			$insert_data[]=$row['cpc_referral'];	
			
			if($cpm_addon_enabled ==1)
			$insert_data[]=$row['cpm_referral'];	
			
			if($pop_addon_enabled ==1)
			$insert_data[]=$row['pop_referral'];	
			
			if($html_addon_enabled ==1)
			$insert_data[]=$row['html_referral'];	
			
			if($cpa_addon_enabled ==1)
			$insert_data[]=$row['cpa_referral'];	
			
			if($affiliate_addon_enabled ==1)
			$insert_data[]=$row['affiliate_referral'];	
			
			if($video_addon_enabled ==1)
			$insert_data[]=$row['cpv_referral'];		
		}			
		
		if($video_addon_enabled ==1)
		{
			$insert_data[]=$row['cpv_impression'];
			$insert_data[]=$row['cpv_spend'];
			$insert_data[]=$row['cpv_profit'];
			$insert_data[]=$row['cpv_click'];
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
			$ins_qry_res=$db->execute_query("INSERT INTO ".TABLE_PREFIX."statistics_adv_yearly (`id`,`uid`, `aid`, `kid`, `impression`,`click`, `time`, `money_spent`,`pub_profit`".$cpm_insert1." ".$html_select1." ".$cpa_insert1." ".$pop_insert1." ".$affiliate_insert1." ".$cpmrefstring5." ".$cpv_insert1.") VALUES ".$advvalue[1]." ",$advvalue[0]);
			
			if($ins_qry_res->error =="")
			$num_row_inserted=$num_row_inserted+$ins_qry_res->get_num_records();
			else
			{
				echo"<br>Data insertion to ".TABLE_PREFIX."statistics_adv_yearly failed<br>".$ins_qry_res->get_sql();
				break;
			}	
		}
    }
	

   	if($num_row_inserted == $result_count)
	{
		$up_qry_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET status=?,time=? WHERE task=?",array(2,$year,'statistics_adv_yearly'));
		if($year==$this->get_previous_year($current_year))
		{
			echo "<br>BREAKING AFTER UPDATING LAST YEAR STATISTICS";
			break;
		}
	}
	else
	{
		$up_qry_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET status=?,time=? WHERE task=?",array(1,$year,'statistics_adv_yearly'));
		echo "<br>BREAKING AFTER PARTIALLY UPDATING LAST YEAR STATISTICS";
		break;
	}

//echo mysql_error();
flush();
}while (1);

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////



echo "<br><br><strong>Publisher Yearly</strong><br>";
do
{
	$supdation_res=$db->execute_query("SELECT time,status FROM ".TABLE_PREFIX."statistics_updation WHERE task=?",array('statistics_pub_yearly'));
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
		$min_qry_res=$db->execute_query("SELECT min(time) as mt FROM ".TABLE_PREFIX."statistics_pub_monthly");
		if($min_qry_res->error =="")
		{
			$min_time_monthly_row=$min_qry_res->fetch_assoc();
			if($min_time_monthly_row['mt']=='')
			{
				echo "<br>No data in the ".TABLE_PREFIX."statistics_pub_monthly table";
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
			echo "<br>Query failed while getting minimum time from ".TABLE_PREFIX."statistics_pub_monthly table<br>".$min_qry_res->get_sql();
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
		$del_qry_res=$db->execute_query("DELETE FROM ".TABLE_PREFIX."statistics_pub_monthly WHERE time<=?",array($delete_time));
		if($del_qry_res->error =="")
		echo "<br>Old data deleted from ".TABLE_PREFIX."statistics_pub_monthly<br>".$del_qry_res->get_sql();
		else
		echo "<br>Old data deletion from ".TABLE_PREFIX."statistics_pub_monthly failed<br>".$del_qry_res->get_sql();
	}

	$start=	$year."01";
	$end=$year."12";
	echo "<br>Currently building data for ".$year;

	$st_up_qry_res=$db->execute_query("SELECT time,status FROM ".TABLE_PREFIX."statistics_updation WHERE task=?",array('statistics_pub_monthly'));
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



	$st_del_res=$db->execute_query("DELETE FROM ".TABLE_PREFIX."statistics_pub_yearly WHERE time=?",array($year));
	if($st_del_res->error !="")
	{
		echo "<br>Data clearing from ".TABLE_PREFIX."statistics_pub_yearly failed<br>".$st_del_res->get_sql();
		break;

	}

	$sel_qry_res=$db->execute_query("SELECT uid,bid,sum(impression) as imp,sum(click) as clk,sum(money_spent) as spent,sum(pub_profit) as profit ".$cpm_select.$cpm_select1." ".$html_select." ".$siddata3." ".$cpa_select3." ".$pop_select." ".$affiliate_select3." ".$cpmrefstring4." ".$cpv_select.$cpv_select1."  FROM ".TABLE_PREFIX."statistics_pub_monthly WHERE (time>=? and time<=?)  group by uid".$siddata3.",bid",array($start,$end));
	if($sel_qry_res->error !="")
	{
		echo "<br>Data retrieval failed from ".TABLE_PREFIX."statistics_pub_monthly table<br>".$sel_qry_res->get_sql();
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

		$valuestring.="(?,?,?,?,?,?,?,?".$cpm_insert2." ".$html_select2." ".$siddata2." ".$cpa_insert2." ".$pop_insert2." ".$affiliate_insert2." ".$cpmrefstring6." ".$cpv_insert2.")";
		
		
		$insert_data[]='';
		$insert_data[]=$row['uid'];
		$insert_data[]=$row['bid'];
		$insert_data[]=$row['imp'];
		$insert_data[]=$row['clk'];
		$insert_data[]=$year;
		$insert_data[]=$row['spent'];
		$insert_data[]=$row['profit'];
		
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
		
		if($referral_enabled ==1)
		{
			$insert_data[]=$row['cpc_referral'];	
			
			if($cpm_addon_enabled ==1)
			$insert_data[]=$row['cpm_referral'];	
			
			if($pop_addon_enabled ==1)
			$insert_data[]=$row['pop_referral'];	
			
			if($html_addon_enabled ==1)
			$insert_data[]=$row['html_referral'];	
			
			if($cpa_addon_enabled ==1)
			$insert_data[]=$row['cpa_referral'];	
			
			if($affiliate_addon_enabled ==1)
			$insert_data[]=$row['affiliate_referral'];	
			
			if($video_addon_enabled ==1)
			$insert_data[]=$row['cpv_referral'];			
		}			
			
		if($video_addon_enabled ==1)
		{
			$insert_data[]=$row['cpv_impression'];
			$insert_data[]=$row['cpv_spend'];
			$insert_data[]=$row['cpv_profit'];
			$insert_data[]=$row['cpv_click'];	
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
			$ins_qry_res=$db->execute_query("INSERT INTO ".TABLE_PREFIX."statistics_pub_yearly (`id`,`uid`,`bid`,`impression`,`click`, `time`, `money_spent`,`pub_profit`".$cpm_insert1." ".$html_select1." ".$siddata1." ".$cpa_insert1." ".$pop_insert1." ".$affiliate_insert1." ".$cpmrefstring5." ".$cpv_insert1.") VALUES ".$advvalue[1]." ",$advvalue[0]);

			if($ins_qry_res->error =="")
			$num_row_inserted=$num_row_inserted+$ins_qry_res->get_num_records();
			else
			{
				echo"<br>Data insertion to ".TABLE_PREFIX."statistics_pub_yearly failed<br>".$ins_qry_res->get_sql();
				break;
			}	
		}
   }
			
	
   	if($num_row_inserted == $result_count)
	{
		$up_qry_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET status=?,time=? WHERE task=?",array(2,$year,'statistics_pub_yearly'));
		if($year==$this->get_previous_year($current_year))
		{
			echo "<br>BREAKING AFTER UPDATING LAST YEAR STATISTICS";
			break;
		}
	}
	else
	{
		$up_qry_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET status=?,time=? WHERE task=?",array(1,$year,'statistics_pub_yearly'));
		echo "<br>BREAKING AFTER PARTIALLY UPDATING LAST YEAR STATISTICS";
		break;

	}
	//echo mysql_error();
	flush();
}while (1);

////////*/////////*/////////*/////////*//////////*//////////*//////////*///////////*//////////*//////////*//////////




echo "<br><br><strong>Advertiser Monthly Temp</strong><br>";

do
{
	$supdation_res=$db->execute_query("SELECT time,status FROM ".TABLE_PREFIX."statistics_updation WHERE task=?",array('statistics_adv_monthly_temp'));
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
    $previous_hour=$current_day.date("H",time());
	$previous_hour=$this->get_previous_hour($previous_hour);

	if($start_time!=0)
	{
		$next_hour=$this->get_nexthour($start_time);
		if($next_hour >$previous_hour)
		{
			echo "<br>BREAKING - CURRENT MONTH STATISTICS IS COMPLETE !!!!!!!!!!!!!!!";
			break;
		}
	}
	
	//$start=	$current_month."01"."00";
	
	$start=	$current_month."01";
	$end=$previous_hour;

	echo "<br>Currently building data for ".$start."00"." - ".$end;


	$st_del_res=$db->execute_query("DELETE FROM ".TABLE_PREFIX."statistics_adv_monthly_temp");
	if($st_del_res->error !="")
	{
		echo "<br>Data clearing from ".TABLE_PREFIX."statistics_adv_monthly_temp failed<br>".$st_del_res->get_sql();
		break;
	}
	
	
	$sel_qry_res=$db->execute_query("SELECT uid,aid,kid,sum(impression) as imp,sum(click) as clk,sum(money_spent) as spent,sum(pub_profit) as profit ".$cpm_select.$cpm_select1." ".$html_select." ".$cpa_select3." ".$pop_select." ".$affiliate_select3." ".$cpmrefstring4." ".$cpv_select.$cpv_select1." FROM ".TABLE_PREFIX."statistics_adv_daily WHERE (time>=?)  group by uid,aid,kid",array($start));
	if($sel_qry_res->error !="")
	{
		echo "<br>Data retrieval failed from ".TABLE_PREFIX."statistics_adv_daily table<br>".$sel_qry_res->get_sql();
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

		$valuestring.="(?,?,?,?,?,?,?,?,?".$cpm_insert2." ".$html_select2." ".$cpa_insert2." ".$pop_insert2." ".$affiliate_insert2." ".$cpmrefstring6." ".$cpv_insert2.")";
		

		$insert_data[]='';
		$insert_data[]=$row['uid'];
		$insert_data[]=$row['aid'];
		$insert_data[]=$row['kid'];
		$insert_data[]=$row['imp'];
		$insert_data[]=$row['clk'];
		$insert_data[]=$current_month;
		$insert_data[]=$row['spent'];
		$insert_data[]=$row['profit'];
		
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
		
		if($referral_enabled ==1)
		{
			$insert_data[]=$row['cpc_referral'];
		
			if($cpm_addon_enabled ==1)
			$insert_data[]=$row['cpm_referral'];
			
			if($pop_addon_enabled ==1)
			$insert_data[]=$row['pop_referral'];	

			if($html_addon_enabled ==1)
			$insert_data[]=$row['html_referral'];			

			if($cpa_addon_enabled ==1)
			$insert_data[]=$row['cpa_referral'];			
			
			if($affiliate_addon_enabled ==1)
			$insert_data[]=$row['affiliate_referral'];		

			if($video_addon_enabled ==1)
			$insert_data[]=$row['cpv_referral'];		
		}
		
		if($video_addon_enabled ==1)
		{
			$insert_data[]=$row['cpv_impression'];
			$insert_data[]=$row['cpv_spend'];
			$insert_data[]=$row['cpv_profit'];
			$insert_data[]=$row['cpv_click'];
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
			$ins_qry_res=$db->execute_query("INSERT INTO ".TABLE_PREFIX."statistics_adv_monthly_temp (`id`,`uid`, `aid`, `kid`, `impression`,`click`, `time`, `money_spent`,`pub_profit`".$cpm_insert1." ".$html_select1." ".$cpa_insert1." ".$pop_insert1." ".$affiliate_insert1." ".$cpmrefstring5." ".$cpv_insert1.") VALUES ".$advvalue[1]." ",$advvalue[0]);
			
			if($ins_qry_res->error =="")
			$num_row_inserted=$num_row_inserted+$ins_qry_res->get_num_records();
			else
			{
				echo"<br>Data insertion to ".TABLE_PREFIX."statistics_adv_monthly_temp failed<br>".$ins_qry_res->get_sql();
				break;
			}	
		}
   }
	


	if($result_count == $num_row_inserted)
	{
		$up_qry_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET status=?,time=? WHERE task=?",array(2,$previous_hour,'statistics_adv_monthly_temp'));
		echo "<br>BREAKING AFTER COMPLETELY UPDATING CURRENT MONTH STATISTICS";
		break;
	}
	else
	{
		$up_qry_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET status=?,time=? WHERE task=?",array(1,$previous_hour,'statistics_adv_monthly_temp'));
		echo "<br>BREAKING AFTER PARTIALLY UPDATING CURRENT MONTH STATISTICS";
		break;
	}

//echo mysql_error();
flush();
}while (1);
///////////////////////////////////////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo "<br><br><strong>Publisher Monthly Temp</strong><br>";
do
{
	$supdation_res=$db->execute_query("SELECT time,status FROM ".TABLE_PREFIX."statistics_updation WHERE task=?",array('statistics_pub_monthly_temp'));

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
	$previous_hour=$current_day.date("H",time());
	$previous_hour=$this->get_previous_hour($previous_hour);

	if($start_time!=0)
	{
		$next_hour=$this->get_nexthour($start_time);
		if($next_hour >$previous_hour)
		{
			echo "<br>BREAKING - CURRENT MONTH STATISTICS IS COMPLETE !!!!!!!!!!!!!!!";
			break;
		}
	}
	//$start=	$current_month."01"."00";
	
	$start=	$current_month."01";
	$end=$previous_hour;

	echo "<br>Currently building data for ".$start."00"." - ".$end;

	$st_del_res=$db->execute_query("DELETE FROM ".TABLE_PREFIX."statistics_pub_monthly_temp");
	if($st_del_res->error !="")
	{
		echo "<br>Data clearing from ".TABLE_PREFIX."statistics_pub_monthly_temp failed<br>".$st_del_res->get_sql();
		break;
	}

	$sel_qry_res=$db->execute_query("SELECT uid,bid,sum(impression) as imp,sum(click) as clk,sum(money_spent) as spent,sum(pub_profit) as profit ".$cpm_select.$cpm_select1." ".$html_select." ".$siddata3." ".$cpa_select3." ".$pop_select." ".$affiliate_select3." ".$cpmrefstring4." ".$cpv_select.$cpv_select1." FROM ".TABLE_PREFIX."statistics_pub_daily WHERE (time>=?)  group by uid".$siddata3.",bid",array($start));
	if($sel_qry_res->error !="")
	{
		echo "<br>Data retrieval failed from ".TABLE_PREFIX."statistics_pub_daily table<br>".$sel_qry_res->get_sql();
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

		$valuestring.="(?,?,?,?,?,?,?,?".$cpm_insert2." ".$html_select2." ".$siddata2." ".$cpa_insert2." ".$pop_insert2." ".$affiliate_insert2." ".$cpmrefstring6." ".$cpv_insert2.")";
		

		$insert_data[]='';
		$insert_data[]=$row['uid'];
		$insert_data[]=$row['bid'];
		$insert_data[]=$row['imp'];
		$insert_data[]=$row['clk'];
		$insert_data[]=$current_month;
		$insert_data[]=$row['spent'];
		$insert_data[]=$row['profit'];
		
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
		
		if($referral_enabled ==1)
		{
			$insert_data[]=$row['cpc_referral'];
		
			if($cpm_addon_enabled ==1)
			$insert_data[]=$row['cpm_referral'];
			
			if($pop_addon_enabled ==1)
			$insert_data[]=$row['pop_referral'];	

			if($html_addon_enabled ==1)
			$insert_data[]=$row['html_referral'];			

			if($cpa_addon_enabled ==1)
			$insert_data[]=$row['cpa_referral'];			
			
			if($affiliate_addon_enabled ==1)
			$insert_data[]=$row['affiliate_referral'];		

			if($video_addon_enabled ==1)
			$insert_data[]=$row['cpv_referral'];	
		}
		
		if($video_addon_enabled ==1)
		{
			$insert_data[]=$row['cpv_impression'];
			$insert_data[]=$row['cpv_spend'];
			$insert_data[]=$row['cpv_profit'];
			$insert_data[]=$row['cpv_click'];
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
			$ins_qry_res=$db->execute_query("INSERT INTO ".TABLE_PREFIX."statistics_pub_monthly_temp (`id`,`uid`, `bid`, `impression`,`click`, `time`, `money_spent`,`pub_profit`".$cpm_insert1." ".$html_select1." ".$siddata1." ".$cpa_insert1." ".$pop_insert1." ".$affiliate_insert1." ".$cpmrefstring5." ".$cpv_insert1.") VALUES ".$advvalue[1]." ",$advvalue[0]);
			
			if($ins_qry_res->error =="")
			$num_row_inserted=$num_row_inserted+$ins_qry_res->get_num_records();
			else
			{
				echo"<br>Data insertion to ".TABLE_PREFIX."statistics_pub_monthly_temp failed<br>".$ins_qry_res->get_sql();
				break;
			}	
		}
    }
	
	if($result_count == $num_row_inserted)
	{
		$up_qry_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET status=?,time=? WHERE task=?",array(2,$previous_hour,'statistics_pub_monthly_temp'));
		echo "<br>BREAKING AFTER COMPLETELY UPDATING CURRENT MONTH STATISTICS";
		break;
	}
	else
	{
		$up_qry_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET status=?,time=? WHERE task=?",array(1,$previous_hour,'statistics_pub_monthly_temp'));
		echo "<br>BREAKING AFTER PARTIALLY UPDATING CURRENT MONTH STATISTICS";
		break;
	}
	//echo mysql_error();
	flush();
}while (1);


/////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo "<br><br><strong>Advertiser Yearly Temp</strong><br>";
do
{

$supdation_res=$db->execute_query("SELECT time,status FROM ".TABLE_PREFIX."statistics_updation WHERE task=?",array('statistics_adv_yearly_temp'));
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

$previous_hour=$current_day.date("H",time());
$previous_hour=$this->get_previous_hour($previous_hour);

	if($start_time!=0)
	{
		$next_hour=$this->get_nexthour($start_time);
		if($next_hour >$previous_hour)
		{
			echo "<br>BREAKING - CURRENT YEAR STATISTICS IS COMPLETE !!!!!!!!!!!!!!!";
			break;
		}
	}
//$start=	$current_year."01"."01"."00";

$start=	$current_year."01";
$end=$previous_hour;

$year_begin=$current_year."01";
$month_begin=$current_year.date("m",time());

echo "<br>Currently building data for ".$start."01"."00"." - ".$end;

$st_del_res=$db->execute_query("DELETE FROM ".TABLE_PREFIX."statistics_adv_yearly_temp");
if($st_del_res->error !="")
{
	echo "<br>Data clearing from ".TABLE_PREFIX."statistics_adv_yearly_temp failed<br>".$st_del_res->get_sql();
	break;

}

$sel_qry_res=$db->execute_query("SELECT uid,aid,kid,sum(impression) as imp,sum(click) as clk,sum(money_spent) as spent,sum(pub_profit) as profit ".$cpm_select.$cpm_select1." ".$html_select." ".$cpa_select3." ".$pop_select." ".$affiliate_select3." ".$cpmrefstring4." ".$cpv_select.$cpv_select1." FROM ".TABLE_PREFIX."statistics_adv_monthly WHERE (time>=?)  group by uid,aid,kid",array($year_begin));
if($sel_qry_res->error !="")
{
	echo "<br>Data retrieval failed from ".TABLE_PREFIX."statistics_adv_monthly table<br>".$sel_qry_res->get_sql();
	break;

}

$sel_qry_res1=$db->execute_query("SELECT uid,aid,kid,sum(impression) as imp,sum(click) as clk,sum(money_spent) as spent,sum(pub_profit) as profit ".$cpm_select.$cpm_select1." ".$html_select." ".$cpa_select3." ".$pop_select." ".$affiliate_select3."  ".$cpmrefstring4." ".$cpv_select.$cpv_select1." FROM ".TABLE_PREFIX."statistics_adv_monthly_temp WHERE (time>=?)  group by uid,aid,kid",array($month_begin));
if($sel_qry_res1->error !="")
{
	echo "<br>Data retrieval failed from ".TABLE_PREFIX."statistics_adv_monthly_temp table<br>".$sel_qry_res->get_sql();
	break;

}

$array=array();
while($row=$sel_qry_res->fetch_assoc())
{
	$key=$row['uid'].':'.$row['aid'].':'.$row['kid'];

	$array[$key][0]=$row['uid'];
	$array[$key][1]=$row['aid'];
	$array[$key][2]=$row['kid'];
	$array[$key][3]=$row['imp'];
	$array[$key][4]=$row['clk'];
	$array[$key][5]=$row['spent'];
	$array[$key][6]=$row['profit'];
	
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
	
	
	
	if($cpa_addon_enabled ==1)
	{
		$array[$key][13]=$row['cpa_impression'];
		$array[$key][14]=$row['cpa_click'];
		$array[$key][15]=$row['cpa_conversion'];
		$array[$key][16]=$row['cpa_spend'];
		$array[$key][17]=$row['cpa_profit'];
	}
	
	if($pop_addon_enabled ==1)
	{
		$array[$key][18]=$row['pop_impression'];
		$array[$key][19]=$row['pop_spend'];
		$array[$key][20]=$row['pop_profit'];
	}
	
	
	if($affiliate_addon_enabled ==1)
	{
		$array[$key][21]=$row['affiliate_click'];
		$array[$key][22]=$row['affiliate_conversion'];
		$array[$key][23]=$row['affiliate_spend'];
		$array[$key][24]=$row['affiliate_profit'];
	}
	
	
	if($referral_enabled ==1)
	{
		$array[$key][25]=$row['cpc_referral'];
		
		if($cpm_addon_enabled ==1)
		$array[$key][26]=$row['cpm_referral'];
			
		if($pop_addon_enabled ==1)
		$array[$key][27]=$row['pop_referral'];
		
		if($html_addon_enabled ==1)
		$array[$key][28]=$row['html_referral'];
			
		if($cpa_addon_enabled ==1)
		$array[$key][29]=$row['cpa_referral'];
		
		if($affiliate_addon_enabled ==1)
		$array[$key][30]=$row['affiliate_referral'];
		
		if($video_addon_enabled ==1)
		$array[$key][31]=$row['cpv_referral'];		
	}
	
	if($video_addon_enabled ==1)
	{
		$array[$key][32]=$row['cpv_impression'];
		$array[$key][33]=$row['cpv_spend'];
		$array[$key][34]=$row['cpv_profit'];
		$array[$key][35]=$row['cpv_click'];		
	}

}


$sel_qry_res->free_result();



while($row=$sel_qry_res1->fetch_assoc())
{
	$key=$row['uid'].':'.$row['aid'].':'.$row['kid'];

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
	
	
	
	
	if($cpa_addon_enabled ==1)
	{
		if(!isset($array[$key][13]))
		$array[$key][13]=$row['cpa_impression'];
		else
		$array[$key][13]=$array[$key][13]+$row['cpa_impression'];
		
		if(!isset($array[$key][14]))
		$array[$key][14]=$row['cpa_click'];
		else
		$array[$key][14]=$array[$key][14]+$row['cpa_click'];
		
		if(!isset($array[$key][15]))
		$array[$key][15]=$row['cpa_conversion'];
		else
		$array[$key][15]=$array[$key][15]+$row['cpa_conversion'];
		
		if(!isset($array[$key][16]))
		$array[$key][16]=$row['cpa_spend'];
		else
		$array[$key][16]=$array[$key][16]+$row['cpa_spend'];
		
		if(!isset($array[$key][17]))
		$array[$key][17]=$row['cpa_profit'];
		else
		$array[$key][17]=$array[$key][17]+$row['cpa_profit'];
	}
	
	if($pop_addon_enabled ==1)
	{
		if(!isset($array[$key][18]))
		$array[$key][18]=$row['pop_impression'];
		else
		$array[$key][18]=$array[$key][18]+$row['pop_impression'];
		
		
		if(!isset($array[$key][19]))
		$array[$key][19]=$row['pop_spend'];
		else
		$array[$key][19]=$array[$key][19]+$row['pop_spend'];
		
		if(!isset($array[$key][20]))
		$array[$key][20]=$row['pop_profit'];
		else
		$array[$key][20]=$array[$key][20]+$row['pop_profit'];
	}
	
	
	if($affiliate_addon_enabled ==1)
	{
		if(!isset($array[$key][21]))
		$array[$key][21]=$row['affiliate_click'];
		else
		$array[$key][21]=$array[$key][21]+$row['affiliate_click'];
		
		
		if(!isset($array[$key][22]))
		$array[$key][22]=$row['affiliate_conversion'];
		else
		$array[$key][22]=$array[$key][22]+$row['affiliate_conversion'];
		
		if(!isset($array[$key][23]))
		$array[$key][23]=$row['affiliate_spend'];
		else
		$array[$key][23]=$array[$key][23]+$row['affiliate_spend'];
		
		
		if(!isset($array[$key][24]))
		$array[$key][24]=$row['affiliate_profit'];
		else
		$array[$key][24]=$array[$key][24]+$row['affiliate_profit'];		
		

	}
	
	
	if($referral_enabled ==1)
	{
		if(!isset($array[$key][25]))
		$array[$key][25]=$row['cpc_referral'];
		else
		$array[$key][25]=$array[$key][25]+$row['cpc_referral'];
		
		
		if($cpm_addon_enabled ==1)
		{
			if(!isset($array[$key][26]))
			$array[$key][26]=$row['cpm_referral'];
			else
			$array[$key][26]=$array[$key][26]+$row['cpm_referral'];
		}
		
		if($pop_addon_enabled ==1)
		{
			if(!isset($array[$key][27]))
			$array[$key][27]=$row['pop_referral'];
			else
			$array[$key][27]=$array[$key][27]+$row['pop_referral'];
		}		
		
		if($html_addon_enabled ==1)
		{
			if(!isset($array[$key][28]))
			$array[$key][28]=$row['html_referral'];
			else
			$array[$key][28]=$array[$key][28]+$row['html_referral'];
		}			
		
		if($cpa_addon_enabled ==1)
		{
			if(!isset($array[$key][29]))
			$array[$key][29]=$row['cpa_referral'];
			else
			$array[$key][29]=$array[$key][29]+$row['cpa_referral'];
		}
		
		if($affiliate_addon_enabled ==1)
		{
			if(!isset($array[$key][30]))
			$array[$key][30]=$row['affiliate_referral'];
			else
			$array[$key][30]=$array[$key][30]+$row['affiliate_referral'];
		}

		if($video_addon_enabled ==1)
		{
			if(!isset($array[$key][31]))
			$array[$key][31]=$row['cpv_referral'];
			else
			$array[$key][31]=$array[$key][31]+$row['cpv_referral'];		
		}
	}
	
	
	if($video_addon_enabled ==1)
	{
		if(!isset($array[$key][32]))
		$array[$key][32]=$row['cpv_impression'];
		else
		$array[$key][32]=$array[$key][32]+$row['cpv_impression'];
		
		
		if(!isset($array[$key][33]))
		$array[$key][33]=$row['cpv_spend'];
		else
		$array[$key][33]=$array[$key][33]+$row['cpv_spend'];
		
		if(!isset($array[$key][34]))
		$array[$key][34]=$row['cpv_profit'];
		else
		$array[$key][34]=$array[$key][34]+$row['cpv_profit'];
		
		if(!isset($array[$key][35]))
		$array[$key][35]=$row['cpv_click'];
		else
		$array[$key][35]=$array[$key][35]+$row['cpv_click'];		
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

	$valuestring.="(?,?,?,?,?,?,?,?,?".$cpm_insert2." ".$html_select2." ".$cpa_insert2." ".$pop_insert2." ".$affiliate_insert2." ".$cpmrefstring6." ".$cpv_insert2." )";
	
	
	
	$insert_data[]='';
	$insert_data[]=$v[0];
	$insert_data[]=$v[1];
	$insert_data[]=$v[2];
	$insert_data[]=$v[3];
	$insert_data[]=$v[4];
	$insert_data[]=$current_year;
	$insert_data[]=$v[5];
	$insert_data[]=$v[6];
	
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
		$insert_data[]=$v[13];
		$insert_data[]=$v[14];
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
		$insert_data[]=$v[21];
		$insert_data[]=$v[22];
		$insert_data[]=$v[23];
		$insert_data[]=$v[24];
	}
	
	if($referral_enabled ==1)
	{
		$insert_data[]=$v[25];
	
		if($cpm_addon_enabled ==1)
		$insert_data[]=$v[26];
		
		if($pop_addon_enabled ==1)
		$insert_data[]=$v[27];

		if($html_addon_enabled ==1)
		$insert_data[]=$v[28];		

		if($cpa_addon_enabled ==1)
		$insert_data[]=$v[29];
		
		if($affiliate_addon_enabled ==1)
		$insert_data[]=$v[30];		
		
		if($video_addon_enabled ==1)
		$insert_data[]=$v[31];			
	}	
	
	if($video_addon_enabled ==1)
	{
		$insert_data[]=$v[32];
		$insert_data[]=$v[33];
		$insert_data[]=$v[34];
		$insert_data[]=$v[35];	
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
			$ins_qry_res=$db->execute_query("INSERT INTO ".TABLE_PREFIX."statistics_adv_yearly_temp (`id`,`uid`, `aid`, `kid`, `impression`,`click`, `time`, `money_spent`,`pub_profit`".$cpm_insert1." ".$html_select1." ".$cpa_insert1." ".$pop_insert1." ".$affiliate_insert1." ".$cpmrefstring5." ".$cpv_insert1.") VALUES ".$advvalue[1]." ",$advvalue[0]);
			
			if($ins_qry_res->error =="")
			$num_row_inserted=$num_row_inserted+$ins_qry_res->get_num_records();
			else
			{
				echo"<br>Data insertion to ".TABLE_PREFIX."statistics_adv_yearly_temp failed<br>".$ins_qry_res->get_sql();
				break;
			}
		}
    }




if($num_row_inserted == $result_count)
{
	$up_qry_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET status=?,time=? WHERE task=?",array(2,$previous_hour,'statistics_adv_yearly_temp'));
	echo "<br>BREAKING AFTER COMPLETELY UPDATING LAST YEAR STATISTICS";
	break;
}
else
{
	$up_qry_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET status=?,time=? WHERE task=?",array(1,$previous_hour,'statistics_adv_yearly_temp'));
	echo "<br>BREAKING AFTER PARTIALLY UPDATING LAST YEAR STATISTICS";
    break;
}
//echo mysql_error();
flush();
}while (1);
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo "<br><br><strong>Publisher Yearly Temp</strong><br>";
do
{
	$supdation_res=$db->execute_query("SELECT time,status FROM ".TABLE_PREFIX."statistics_updation WHERE task=?",array('statistics_pub_yearly_temp'));
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

	$previous_hour=$current_day.date("H",time());
	$previous_hour=$this->get_previous_hour($previous_hour);

	if($start_time!=0)
	{
		$next_hour=$this->get_nexthour($start_time);
		if($next_hour >$previous_hour)
		{
			echo "<br>BREAKING - CURRENT YEAR STATISTICS IS COMPLETE !!!!!!!!!!!!!!!";
			break;
		}
	}
	//$start=	$current_year."01"."01"."00";
	
	$start=	$current_year."01";
	$end=$previous_hour;
	
	$year_begin=$current_year."01";
	$month_begin=$current_year.date("m",time());


	echo "<br>Currently building data for ".$start."01"."00"." - ".$end;
	$st_del_res=$db->execute_query("DELETE FROM ".TABLE_PREFIX."statistics_pub_yearly_temp");
	if($st_del_res->error !="")
	{
		echo "<br>Data clearing from ".TABLE_PREFIX."statistics_pub_yearly_temp failed<br>".$st_del_res->get_sql();
		break;
	}

	$sel_qry_res=$db->execute_query("SELECT uid,bid,sum(impression) as imp,sum(click) as clk,sum(money_spent) as spent,sum(pub_profit) as profit ".$cpm_select.$cpm_select1." ".$html_select." ".$siddata3." ".$cpa_select3." ".$pop_select." ".$affiliate_select3." ".$cpmrefstring4." ".$cpv_select.$cpv_select1." FROM ".TABLE_PREFIX."statistics_pub_monthly WHERE (time>=?)  group by uid".$siddata3.",bid",array($year_begin));
	if($sel_qry_res->error !="")
	{
		echo "<br>Data retrieval failed from ".TABLE_PREFIX."statistics_pub_monthly table<br>".$sel_qry_res->get_sql();
		break;
	}

	$sel_qry_res1=$db->execute_query("SELECT uid,bid,sum(impression) as imp,sum(click) as clk,sum(money_spent) as spent,sum(pub_profit) as profit ".$cpm_select.$cpm_select1." ".$html_select." ".$siddata3." ".$cpa_select3." ".$pop_select." ".$affiliate_select3." ".$cpmrefstring4." ".$cpv_select.$cpv_select1." FROM ".TABLE_PREFIX."statistics_pub_monthly_temp WHERE (time>=?)  group by uid".$siddata3.",bid",array($month_begin));
	if($sel_qry_res1->error !="")
	{
		echo "<br>Data retrieval failed from ".TABLE_PREFIX."statistics_pub_monthly_temp table<br>".$sel_qry_res->get_sql();
		break;
	}

	$array=array();
	while($row=$sel_qry_res->fetch_assoc())
	{
		if($category_targeting_enabled ==1)
		$key=$row['uid'].':'.$row['sid'].':'.$row['bid'];
		else
		$key=$row['uid'].':'.$row['bid'];

		$array[$key][0]=$row['uid'];
		$array[$key][1]=$row['bid'];
		$array[$key][2]=$row['imp'];
		$array[$key][3]=$row['clk'];
		$array[$key][4]=$row['spent'];
		$array[$key][5]=$row['profit'];
		
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
	
		
		if($referral_enabled ==1)
		{
			$array[$key][26]=$row['cpc_referral'];
			
			if($cpm_addon_enabled ==1)
			$array[$key][27]=$row['cpm_referral'];
				
			if($pop_addon_enabled ==1)
			$array[$key][28]=$row['pop_referral'];
			
			if($html_addon_enabled ==1)
			$array[$key][29]=$row['html_referral'];
				
			if($cpa_addon_enabled ==1)
			$array[$key][30]=$row['cpa_referral'];
			
			if($affiliate_addon_enabled ==1)
			$array[$key][31]=$row['affiliate_referral'];
			
			if($video_addon_enabled ==1)
			$array[$key][32]=$row['cpv_referral'];				
		}
		
		if($video_addon_enabled ==1)
		{
			$array[$key][33]=$row['cpv_impression'];
			$array[$key][34]=$row['cpv_spend'];
			$array[$key][35]=$row['cpv_profit'];
			$array[$key][36]=$row['cpv_click'];		
		}		
		
		
		
	}
	
	$sel_qry_res->free_result();	
	
	
	

	while($row=$sel_qry_res1->fetch_assoc())
	{
		
		if($category_targeting_enabled ==1)
		$key=$row['uid'].':'.$row['sid'].':'.$row['bid'];
		else
		$key=$row['uid'].':'.$row['bid'];

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
		
		if($referral_enabled ==1)
		{
			if(!isset($array[$key][26]))
			$array[$key][26]=$row['cpc_referral'];
			else
			$array[$key][26]=$array[$key][26]+$row['cpc_referral'];
			
			
			if($cpm_addon_enabled ==1)
			{
				if(!isset($array[$key][27]))
				$array[$key][27]=$row['cpm_referral'];
				else
				$array[$key][27]=$array[$key][27]+$row['cpm_referral'];
			}
			
			if($pop_addon_enabled ==1)
			{
				if(!isset($array[$key][28]))
				$array[$key][28]=$row['pop_referral'];
				else
				$array[$key][28]=$array[$key][28]+$row['pop_referral'];
			}		
			
			if($html_addon_enabled ==1)
			{
				if(!isset($array[$key][29]))
				$array[$key][29]=$row['html_referral'];
				else
				$array[$key][29]=$array[$key][29]+$row['html_referral'];
			}			
			
			if($cpa_addon_enabled ==1)
			{
				if(!isset($array[$key][30]))
				$array[$key][30]=$row['cpa_referral'];
				else
				$array[$key][30]=$array[$key][30]+$row['cpa_referral'];
			}
			
			if($affiliate_addon_enabled ==1)
			{
				if(!isset($array[$key][31]))
				$array[$key][31]=$row['affiliate_referral'];
				else
				$array[$key][31]=$array[$key][31]+$row['affiliate_referral'];
			}

			if($video_addon_enabled ==1)
			{
				if(!isset($array[$key][32]))
				$array[$key][32]=$row['cpv_referral'];
				else
				$array[$key][32]=$array[$key][32]+$row['cpv_referral'];		
			}			
		}
		
		if($video_addon_enabled ==1)
		{
			if(!isset($array[$key][33]))
			$array[$key][33]=$row['cpv_impression'];
			else
			$array[$key][33]=$array[$key][33]+$row['cpv_impression'];
			
			
			if(!isset($array[$key][34]))
			$array[$key][34]=$row['cpv_spend'];
			else
			$array[$key][34]=$array[$key][34]+$row['cpv_spend'];
			
			if(!isset($array[$key][35]))
			$array[$key][35]=$row['cpv_profit'];
			else
			$array[$key][35]=$array[$key][35]+$row['cpv_profit'];
			
			if(!isset($array[$key][36]))
			$array[$key][36]=$row['cpv_click'];
			else
			$array[$key][36]=$array[$key][36]+$row['cpv_click'];		
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

		$valuestring.="(?,?,?,?,?,?,?,?".$cpm_insert2." ".$html_select2." ".$siddata2." ".$cpa_insert2." ".$pop_insert2." ".$affiliate_insert2." ".$cpmrefstring6." ".$cpv_insert2.")";
		
		
		$insert_data[]='';
		$insert_data[]=$v[0];
		$insert_data[]=$v[1];
		$insert_data[]=$v[2];
		$insert_data[]=$v[3];
		$insert_data[]=$current_year;
		$insert_data[]=$v[4];
		$insert_data[]=$v[5];
		
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
		
		
		if($referral_enabled ==1)
		{
			$insert_data[]=$v[26];
		
			if($cpm_addon_enabled ==1)
			$insert_data[]=$v[27];
			
			if($pop_addon_enabled ==1)
			$insert_data[]=$v[28];
	
			if($html_addon_enabled ==1)
			$insert_data[]=$v[29];		
	
			if($cpa_addon_enabled ==1)
			$insert_data[]=$v[30];
			
			if($affiliate_addon_enabled ==1)
			$insert_data[]=$v[31];		
			
			if($video_addon_enabled ==1)
			$insert_data[]=$v[32];			
		}		
		
		if($video_addon_enabled ==1)
		{
			$insert_data[]=$v[33];
			$insert_data[]=$v[34];
			$insert_data[]=$v[35];
			$insert_data[]=$v[36];	
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
			$ins_qry_res=$db->execute_query("INSERT INTO ".TABLE_PREFIX."statistics_pub_yearly_temp (`id`,`uid`, `bid`, `impression`,`click`, `time`, `money_spent`,`pub_profit`".$cpm_insert1." ".$html_select1." ".$siddata1." ".$cpa_insert1." ".$pop_insert1." ".$affiliate_insert1." ".$cpmrefstring5." ".$cpv_insert1.") VALUES ".$advvalue[1]." ",$advvalue[0]);
			if($ins_qry_res->error =="")
			$num_row_inserted=$num_row_inserted+$ins_qry_res->get_num_records();
			else
			{
				echo"<br>Data insertion to ".TABLE_PREFIX."statistics_pub_yearly_temp failed<br>".$ins_qry_res->get_sql();
				break;
			}	
		}
    }	
	
	if($num_row_inserted == $result_count)
	{
		$up_qry_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET status=?,time=? WHERE task=?",array(2,$previous_hour,'statistics_pub_yearly_temp'));
		echo "<br>BREAKING AFTER COMPLETELY UPDATING LAST YEAR STATISTICS";
		break;
	}
	else
	{
		$up_qry_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET status=?,time=? WHERE task=?",array(1,$previous_hour,'statistics_pub_yearly_temp'));
		echo "<br>BREAKING AFTER PARTIALLY UPDATING LAST YEAR STATISTICS";
		break;
	}
	//echo mysql_error();
	flush();
}while (1);

			//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$time_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET time=?,status=? WHERE task=?",array($current_time,2,'cron_success_time'));
		
		/****************************** Code For Daily Budget Reseting **********************************/
		

		$daily_last_updated=$db->read_single_column("SELECT time FROM ".TABLE_PREFIX."statistics_updation WHERE task=?",array('daily_one_time_updation'));


		$daily_last =date("Y",time());
		$daily_last.=date("m",time());
		$daily_last.=date("d",time());



		if($daily_last_updated < $daily_last)   //Once in a day		
		{
		    
		    /***************************Cron status notification for admin****************************************/
		    
		    $datacronstatus=$db->read_single_column("select status from ".TABLE_PREFIX."statistics_updation where task=?",array('cron_success_time'));
		    $minutecronstatus=$db->read_single_column("select status from ".TABLE_PREFIX."statistics_updation where task=?",array('minutecron_success_time'));
		    
		    if($referral_enabled ==1)
		        $referralcronstatus=$db->read_single_column("select status from ".TABLE_PREFIX."statistics_updation where task=?",array('referralcron_success_time'));
		        
		        $optimizecronstatus=$db->read_single_column("select status from ".TABLE_PREFIX."statistics_updation where task=?",array('optimizecron_success_time'));
		        $geocronstatus=$db->read_single_column("select status from ".TABLE_PREFIX."statistics_updation where task=?",array('geo_cron_success_time'));
		        $wcronstatus=$db->read_single_column("select status from ".TABLE_PREFIX."statistics_updation where task=?",array('withdrawalcron_success_time'));
		        
		        if($datacronstatus==0)
		            $dstatus= 'Failed';
		            else $dstatus='Successfully Executed';
		            
		            if($minutecronstatus==0)
		                $mstatus= 'Failed';
		                else $mstatus= 'Failed';
		                if($referral_enabled ==1)
		                {
		                if($referralcronstatus==0)
		                    $rstatus= 'Failed';
		                    else $rstatus= 'Successfully Executed';
		                }
		                    if($optimizecronstatus==0)
		                        $ostatus= 'Failed';
		                        else $ostatus= 'Successfully Executed';
		                        
		                        if($geocronstatus==0)
		                            $gstatus= 'Failed';
		                            else $gstatus= 'Successfully Executed';
		                            
		                            if($wcronstatus==0)
		                                $wstatus= 'Failed';
		                                else $wstatus= 'Successfully Executed';
		                                
		                                
		                                $message ='
		                                    
                    Hello,
		                                    
                    Cron job status of  your '.Configuration::get_instance()->read('admarket_name').'
                        
                    Data Cron   		: '.$dstatus.'
                    Minute Cron  		: '.$mstatus;
                    if($referral_enabled ==1)
		                {
		                    $message.=' Referral Cron	    : '.$rstatus;
		                }
		                
		            $message.='<br>Optimize Cron		: '.$ostatus.'
                    Geo Cron            : '.$gstatus.'
                    Withdrawal Cron     : '.$wstatus.'
                        
                        
                    If cron job failed continuosly please contact XYZ Scripts Support Team.
                        
                    Thank You
                        
                    Best Regards,
                    '.Configuration::get_instance()->read('admarket_name').'
                    ';
		                                
		                                $admin_email=Configuration::get_instance()->read('admin_notification_email');
		                                
		                                UtilityHelper::send_mail($admin_email,Configuration::get_instance()->read('admarket_name')." - Cron job notification",nl2br($message));
		                                
		                                
		    /***************************Cron status notification for admin****************************************/
		                                
		    
		    
			$db->execute_query("UPDATE ".TABLE_PREFIX."ads SET daily_budget_used=0 where daily_budget_used >0 AND display_type <>6 AND display_type <>12");
			
			$dayscount=(Configuration::get_instance()->read('daily_click_data_backup_expiry')*30)+7;  //7 months 31 days others 30 days
			
			
			$deletetime=mktime(0,0,0,date("m",time()),date("d",time())-$dayscount,date("y",time()));
			$db->execute_query("DELETE FROM ".TABLE_PREFIX."dailyclicks_backup where org_time <?",array($deletetime));
			$db->execute_query("DELETE FROM ".TABLE_PREFIX."fraudclicks where org_time <?",array($deletetime));
			
			
			
			if($cpa_addon_enabled ==1)
			{
				$cpadayscount=(Configuration::get_instance()->read('daily_conversion_data_backup_expiry')*30)+7;  //7 months 31 days others 30 days
			
				$cpadeletetime=mktime(0,0,0,date("m",time()),date("d",time())-$cpadayscount,date("y",time()));
				$db->execute_query("DELETE FROM ".TABLE_PREFIX."cpa_daily_conversions where org_time <?",array($cpadeletetime));
			}
			
			
			
			if($affiliate_addon_enabled ==1)
			{
				$affiliatedayscount=(Configuration::get_instance()->read('daily_affiliate_conversion_data_backup_expiry')*30)+7;  //7 months 31 days others 30 days
			
				$affiliatedeletetime=mktime(0,0,0,date("m",time()),date("d",time())-$affiliatedayscount,date("y",time()));
				$db->execute_query("DELETE FROM ".TABLE_PREFIX."affiliate_daily_conversions where org_time <?",array($affiliatedeletetime));
			}
			
			
			
			
			$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET time=? WHERE task=?",array($daily_last,"daily_one_time_updation"));		
		}
		/****************************** Code For Daily Budget Reseting **********************************/
		
		/****************************** Sponsored Mapping Expiry ****************************************/
		

		
		if($sponsored_enabled ==1)
		{
			$time=time();
		
			$impTime =date("Y",time());
			$impTime.=date("m",time());
			$impTime.=date("d",time());
			$impTime.=date("H",time());
			
			
			
			
			$errorflag=0;
			$db->execute_query("BEGIN");
			
			$sponsrow=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."sponsored_ad_mapping WHERE status=2 AND end_time <=?",array(time()));
			
			$sponsored_percentage=Configuration::get_instance()->read('sponsored_profit_percentage');
			
			while($sprow=$sponsrow->fetch_assoc())
			{
				$sppubprofit=0;
				
				if($sprow['publisher'] >0 && $sprow['position_rate'] >0)
				{
					$sponsored_profit_percentage=$db->read_single_column("SELECT sponsored_profit_percentage FROM ".TABLE_PREFIX."users WHERE id=?",array($sprow['publisher']));
					
					if($sponsored_profit_percentage >0)
					$sponsored_percentage=$sponsored_profit_percentage;
					
					$sppubprofit=$sprow['position_rate']*$sponsored_percentage/100;
					
					if($sppubprofit >0)
					{
						$spupdate0=$db->execute_query("UPDATE ".TABLE_PREFIX."users SET pub_account_balance=pub_account_balance+? WHERE id=?",array($sppubprofit,$sprow['publisher']));
						
						if($spupdate0->error !='')
						{
							$errorflag=1;
							break;
						}
					}
				}
				
				
				/********************** For Referral ****************/
				

				
				$advrid=0;
				$pubrid=0;
				$adv_referral=0;
				$pub_referral=0;
				$total_referral=0;	
				$referral_string="";			
				
				if($referral_enabled ==1)
				{
					if($adv_ref_enabled ==1 && $sprow['position_rate'] >0)
					{
						$advrid=$this->get_referrer_id($sprow['uid']);
						
						if($advrid >0)
						{
							$datacontent=$this->get_referral_user_active($advrid,1);		
								
							$advrid			= $datacontent[0];
							$adv_ref_perc	= $datacontent[1];
						}						
					
							
						if($advrid >0)
						{
							if($adv_ref_perc > 0)
							$arefperc=$adv_ref_perc;							
							
							$adv_referral=$sprow['position_rate']*$arefperc/100;
						}
					}
							
					if($pub_ref_enabled ==1 && $sprow['publisher'] >0 && $sppubprofit >0)
					{
						$pubrid=$this->get_referrer_id($sprow['publisher']);
						
						if($pubrid >0)
						{
							$datacontent=$this->get_referral_user_active($pubrid,2);		

							$pubrid			= $datacontent[0];
							$pub_ref_perc	= $datacontent[1];								
						}						
						
						if($pubrid >0)
						{
							if($pub_ref_perc > 0)
							$prefperc=$pub_ref_perc;		
															
							$pub_referral=$sppubprofit*$prefperc/100;
						}
					}
					
							
					$total_referral=$adv_referral+$pub_referral;
					
					$referral_string=",cpd_referral=".$total_referral.",adv_referral=".$adv_referral.",pub_referral=".$pub_referral." ";
				}
								
			
				if($errorflag ==0 && $referral_enabled ==1)
				{
					if($advrid >0 && $adv_referral >0)
					{
						$refacupdate=$db->execute_query("UPDATE ".TABLE_PREFIX."users SET referral_balance=referral_balance+? WHERE id=?",array($adv_referral,$advrid));
												
						if($refacupdate->error =="")														
						{						
							$sqlupref=$db->execute_query("UPDATE ".TABLE_PREFIX."referral_hourly_backup SET adv_earning=adv_earning+? WHERE uid=? AND time=? AND type=3 LIMIT 1",array($adv_referral,$advrid,$impTime));
						
							if($sqlupref->error !='')
							$errorflag=1;
							else
							{
								if($sqlupref->get_num_records() ==0)
								{
									$sqladvref_res=$db->execute_query("INSERT INTO ".TABLE_PREFIX."referral_hourly_backup (`uid`,`adv_earning`,`time`,`type`) values (?,?,?,?)",array($advrid,$adv_referral,$impTime,3));
								
									if($sqladvref_res->error !='')
									$errorflag=1;
								}
							}
						}
						else
						$errorflag=1;						
					}
					
					
					if($pubrid >0 && $pub_referral >0)
					{
						$refacupdate=$db->execute_query("UPDATE ".TABLE_PREFIX."users SET referral_balance=referral_balance+? WHERE id=?",array($pub_referral,$pubrid));
											
						if($refacupdate->error =="")														
						{							
							$sqlupref=$db->execute_query("UPDATE ".TABLE_PREFIX."referral_hourly_backup SET pub_earning=pub_earning+? WHERE uid=? AND time=? AND type=3 LIMIT 1",array($pub_referral,$pubrid,$impTime));
						
							if($sqlupref->error !='')
							$errorflag=1;
							else
							{
								if($sqlupref->get_num_records() ==0)
								{
									$sqladvref_res=$db->execute_query("INSERT INTO ".TABLE_PREFIX."referral_hourly_backup (`uid`,`pub_earning`,`time`,`type`) values (?,?,?,?)",array($pubrid,$pub_referral,$impTime,3));
						
									if($sqladvref_res->error !='')
									$errorflag=1;
								}
							}
						}
						else
						$errorflag=1;								
					}
				}				
				
				/********************** For Referral ****************/
				
				if($errorflag ==0)
				{
					$spupdate1=$db->execute_query("UPDATE ".TABLE_PREFIX."sponsored_ad_mapping SET status=3,recurring=0".$referral_string." WHERE id=?",array($sprow['id']));
					
					if($spupdate1->error !='')
					{
						$errorflag=1;
						break;
					}
				}
			}
			
			if($errorflag ==0)
			$db->execute_query("COMMIT");
			else			
			$db->execute_query("ROLLBACK");
		}
		
		
		
		/****************************** Sponsored Mapping Expiry ****************************************/
		
		
		/*****************************inserting expired budgets to ads_budget_history********************/
		$errorflag=0;
		$expired_array=array();
		$db->execute_query("BEGIN");
		$row=$db->execute_query("SELECT id,uid,display_type,total_ad_budget,total_budget_used,start_date FROM ".TABLE_PREFIX."ads WHERE (total_budget_used+default_rate) > total_ad_budget AND display_type !=3 AND pricing_status = 1");
		while($rowdata=$row->fetch_assoc())
		{
			$res1=$db->execute_query("INSERT INTO ".TABLE_PREFIX."ads_budget_history (uid,aid,display_type,budget,budget_used,start_date,end_date) VALUES (?,?,?,?,?,?,?)",array($rowdata['uid'],$rowdata['id'],$rowdata['display_type'],$rowdata['total_ad_budget'],$rowdata['total_budget_used'],$rowdata['start_date'],time()));
			if($res1->error!='')
			{    
				$errorflag=1;
			}
			else 
			{
				$budget_balance = $rowdata ['total_ad_budget'] - $rowdata ['total_budget_used'];
				
				$res2=$db->execute_query("UPDATE ".TABLE_PREFIX."users SET adv_account_balance=adv_account_balance+? WHERE id=?",array($budget_balance,$rowdata['uid']));
				
				if($res2->error != '')
				$errorflag = 1;
				else 
				{
					$res3=$db->execute_query("UPDATE ".TABLE_PREFIX."ads SET pricing_status=2,total_ad_budget=0,total_budget_used=0,daily_budget=0,daily_budget_used=0 WHERE id=?",array($rowdata['id']));   
	
					
					if($res3->error != '')
					$errorflag = 1;
					else 
					$expired_array[$rowdata['id']] = $rowdata['uid'];
				}
			}
		}
		
		if($errorflag ==0)
		{
			$db->execute_query("COMMIT");
			
			foreach ($expired_array as $key => $value)
			{    
				$email = $this->get_user_email($value);
				$user_name=$this->get_user_name($value);
				$mail_res1 = $db->execute_query("SELECT * FROM " . TABLE_PREFIX . "email_templates WHERE id=13");
				$mail_result = $mail_res1->fetch_assoc();
				
				if($language_enabled == 1 && $user_result ['locale'] > 0 && isset($mail_result [$user_result ['locale'] . '_message']) && $mail_result [$user_result ['locale'] . '_message'] != '')
					$message = $mail_result [$user_result ['locale'] . '_message'];
					else
						$message = $mail_result ['message'];
				
						if($language_enabled == 1 && $user_result ['locale'] > 0 && isset($mail_result [$user_result ['locale'] . '_subject']) && $mail_result [$user_result ['locale'] . '_subject'] != '')
							$subject = $mail_result [$user_result ['locale'] . '_subject'];
							else
								$subject = $mail_result ['subject'];
				
								$message = str_replace("{USERNAME}",$user_name,$message);
								$message = str_replace("{ADMARKETNAME}",Configuration::get_instance()->read('admarket_name'),$message);
								$message = str_replace("{AID}",$key,$message);
				
								$subject = str_replace("{PRICING}",$this->get_ad_pricing($key),$subject);
								$message = str_replace("{PRICING}",$this->get_ad_pricing($key),$message);
				
								$subject = str_replace("{ADMARKETNAME}",Configuration::get_instance()->read('admarket_name'),$subject);
				
								UtilityHelper::send_mail($email,$subject,$message);
			}
		}
		else
		{
			$db->execute_query("ROLLBACK");
		}
		
		
		
		
		
		
		/************** Fix for pricing expiry issue ***************/
		
		$db->execute_query("UPDATE ".TABLE_PREFIX."ads SET total_ad_budget=0,total_budget_used=0,daily_budget=0,daily_budget_used=0 WHERE pricing_status = 0 OR pricing_status = 2");   
		
		/************** Fix for pricing expiry issue ***************/
		
		
		
		
		/*****************************inserting expired budgets to ads_budget_history********************/
		
		
		/************************* Affiliate Ads Notification **************************/
		
		if($affiliate_addon_enabled ==1)
		{
			//check adv status
			//check ad status
			//check ad pause status
			//check ad exists
			//check budget < default value
			//check adv account balance < default value
			
			
			
			/************ For Notification ***************/
			
			$row=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."adunit WHERE display_type =12 AND aid >0 AND pubid >0 AND notified =0");
			
			$language_enabled=Configuration::get_instance()->read('language_enabled');
			$admarket_name=Configuration::get_instance()->read('admarket_name');
			
			$mail_res1=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."email_templates WHERE id=24");
			$mail_result=$mail_res1->fetch_assoc();
			
			while($rowdata=$row->fetch_assoc())
			{
				$flag=0;
				
				$aid=intval($rowdata['aid']);
				
				$adrow=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."ads WHERE id=?",array($aid));
								
				$adrowdata=$adrow->fetch_assoc();
				
				$aidexist=intval($adrowdata['id']);
				
				if($aidexist ==0)
				$flag=1;   //ad deleted
				else
				{
					$adstatus=intval($adrowdata['status']);
					
					if($adstatus !=1)
					$flag=2;   //ad not active
					else
					{
						$adpausestatus=intval($adrowdata['pause_status']);
						
						if($adpausestatus ==1)
						$flag=3;   //ad paused
						else
						{
							$budget=$adrowdata['total_ad_budget'];
							$amountused=$adrowdata['total_budget_used'];
							
							$balance=$budget-$amountused;
							
							if($balance < 0)
							$balance=0;
							
							if($balance < $adrowdata['default_rate'])
							$flag=4;   //ad budget crossed
							else
							{
								$uid=intval($adrowdata['uid']);
								
								$userrow=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."users WHERE id=?",array($uid));
								
								$userrowdata=$userrow->fetch_assoc();
								
								$userstatus=intval($userrowdata['adv_status']);
					
								if($userstatus !=1)
								$flag=5;   //adv not active
								else
								{
									if($userrowdata['adv_account_balance'] < $adrowdata['default_rate'])
									$flag=6;   //adv account balance less
								}
							}
						}
					}
				}

				
				if($flag !=0)
				{
					$pid=intval($adrowdata['pubid']);
					
					
					$share_link='<a href="'.$this->make_base_url('dispatch/affiliate-ads/2/'.$rowdata['id']).'">'.$rowdata['name'].'</a>';
													
					$pubrow=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."users WHERE id=?",array($pid));					
					
					$pubrowdata=$pubrow->fetch_assoc();
					
					$email=$pubrowdata['email'];
					$locale=$pubrowdata['locale'];
					
											
					if($language_enabled ==1 && $locale >0 && isset($mail_result[$locale.'_message']) && $mail_result[$locale.'_message'] !='')
					$message=$mail_result[$locale.'_message'];
					else
					$message=$mail_result['message'];
											
											
					if($language_enabled ==1 && $locale >0 && isset($mail_result[$locale.'_subject']) && $mail_result[$locale.'_subject'] !='')
					$subject=$mail_result[$locale.'_subject'];
					else
					$subject=$mail_result['subject'];
											
					$message=str_replace("{USERNAME}",$pubrowdata['username'],$message);
					$message=str_replace("{ADMARKETNAME}",$admarket_name,$message);
					$message=str_replace("{SHARELINK}",$share_link,$message);
					
					
					
					$subject=str_replace("{ADMARKETNAME}",$admarket_name,$subject);
					
								
					
														
					UtilityHelper::send_mail($email,$subject, $message);
											
					$db->execute_query("UPDATE ".TABLE_PREFIX."adunit SET notified=1 WHERE id=?",array($rowdata['id']));
				}
			}
			
			$row->free_result();
			
			
			
			
			
			
			
			/************ For Notification ***************/
			
			
			
			/************ Remove Notification ***************/
			
			$row=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."adunit WHERE display_type =12 AND aid >0 AND pubid >0 AND notified =1");
			
			while($rowdata=$row->fetch_assoc())
			{
				$flag=0;
				
				$aid=intval($rowdata['aid']);
				
				$adrow=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."ads WHERE id=?",array($aid));
								
				$adrowdata=$adrow->fetch_assoc();
				
				$aidexist=intval($adrowdata['id']);
				
				if($aidexist ==0)
				$flag=1;   //ad deleted
				else
				{
					$adstatus=intval($adrowdata['status']);
					
					if($adstatus !=1)
					$flag=2;   //ad not active
					else
					{
						$adpausestatus=intval($adrowdata['pause_status']);
						
						if($adpausestatus ==1)
						$flag=3;   //ad paused
						else
						{
							$budget=$adrowdata['total_ad_budget'];
							$amountused=$adrowdata['total_budget_used'];
							
							$balance=$budget-$amountused;
							
							if($balance < 0)
							$balance=0;
							
							if($balance < $adrowdata['default_rate'])
							$flag=4;   //ad budget crossed
							else
							{
								$uid=intval($adrowdata['uid']);
								
								$userrow=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."users WHERE id=?",array($uid));
								
								$userrowdata=$userrow->fetch_assoc();
								
								$userstatus=intval($userrowdata['adv_status']);
					
								if($userstatus !=1)
								$flag=5;   //adv not active
								else
								{
									if($userrowdata['adv_account_balance'] < $adrowdata['default_rate'])
									$flag=6;   //adv account balance less
								}
							}
						}
					}
				}

				
				if($flag ==0)
				$db->execute_query("UPDATE ".TABLE_PREFIX."adunit SET notified=0 WHERE id=?",array($rowdata['id']));
				
			}
			
			
			$row->free_result();
			
			
			/************ Remove Notification ***************/
		}
		
		/************************* Affiliate Ads Notification **************************/
		
		
		
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

		
		
		if($referral_enabled ==1 && ($adv_ref_enabled ==1 || $pub_ref_enabled ==1))
		$this->dispatch("referralcron/data_backup");
		
		
		if($countrywise_data_tracking ==1)
		$this->dispatch("geodatacron/data_backup");		
		
		
		if(date("G",time()) ==0)   //Once in a day
		$this->dispatch("autocron/data_backup");		
		
		
		
		
		
		$this->dispatch("optimizecron/optimize");		
		
		

	   /***************** For Deleting Impressions From Backup Table ******************/
		

		$array_time=array();

		$stat_updated_time=$db->read_single_column("SELECT time FROM ".TABLE_PREFIX."statistics_updation WHERE task=?",array('statistics_adv_daily'));

		if($stat_updated_time >0)
		$array_time[]=$stat_updated_time;

		if($countrywise_data_tracking ==1)
		{
			$stat_updated_time=$db->read_single_column("SELECT time FROM ".TABLE_PREFIX."statistics_updation WHERE task=?",array('geo_statistics_adv_daily'));

			if($stat_updated_time >0)
			$array_time[]=$stat_updated_time;
		}

		array_multisort($array_time);

		if(isset($array_time[0]))
		{
			$delete_time=substr($array_time[0],0,-2);

			$delete_time=$delete_time.'00';
			$db->execute_query("DELETE FROM ".TABLE_PREFIX."adv_impression_hourly_backup WHERE time < ?",array($delete_time));
		}
		


		$array_time=array();

		$stat_updated_time=$db->read_single_column("SELECT time FROM ".TABLE_PREFIX."statistics_updation WHERE task=?",array('statistics_pub_daily'));

		if($stat_updated_time >0)
		$array_time[]=$stat_updated_time;

		if($countrywise_data_tracking ==1)
		{
			$stat_updated_time=$db->read_single_column("SELECT time FROM ".TABLE_PREFIX."statistics_updation WHERE task=?",array('geo_statistics_pub_daily'));

			if($stat_updated_time >0)
			$array_time[]=$stat_updated_time;
		}

		array_multisort($array_time);

		if(isset($array_time[0]))
		{
			$delete_time=substr($array_time[0],0,-2);

			$delete_time=$delete_time.'00';
			$db->execute_query("DELETE FROM ".TABLE_PREFIX."pub_impression_hourly_backup WHERE time < ?",array($delete_time));
		}

		/***************** For Deleting Impressions From Backup Table ******************/		
		
		
		/***************** For Deleting Impressions Files ******************/
		if($cpm_addon_enabled ==1 || $html_addon_enabled ==1 || $pop_addon_enabled ==1 || $video_addon_enabled ==1)
		{
			$impressiondir = PATH_TO_ROOT.CACHE_DIR.'/impression/';
			
			if(is_dir($impressiondir))
			{
				$impd = dir($impressiondir);
				while($impression_name = $impd->read())
				{
					$impression_create=filectime($impressiondir.$impression_name);
					if($impression_create <(time()-7200))
					{
						if($impression_name != "." && $impression_name != "..")
						unlink($impressiondir.$impression_name);
					}
				}
				$impd->close();
			}
		}				
		/***************** For Deleting Impressions Files ******************/	
		
		
		/***************** For Deleting Cache Files ******************/			
		
		$cachedir = "../".CACHE_DIR.DS."cache/";
		$cd = dir($cachedir);
		if(is_dir($cachedir))
		{
			while($cache_name = $cd->read())
			{
				$cache_create=filectime($cachedir.$cache_name);
			
				if($cache_create < (time()-3600))
				unlink($cachedir.$cache_name);
			}
			$cd->close();
		}
		
		
		
		/******************* code to remove cache and impression files from display and track servers  ************/
		
		$display_server_domains="";
		$track_server_domains="";
		$res=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."config");
		while($row=$res->fetch_assoc())
		{
			if($row['name'] == 'display_server_domains')
			$display_server_domains=trim($row['value']);
			if($row['name'] == 'track_server_domains')
			$track_server_domains=trim($row['value']);
		}
		
		if($display_server_domains !="")
		{
			$server_array=explode(',',$display_server_domains);
		
			$current_server_address=$_SERVER['SERVER_ADDR'];
		
			foreach($server_array as $akey=>$avalue)
			{
				if($avalue != $current_server_address)
				{
					$param1=$avalue;
					$param1=str_replace("https://",'',$param1);
					$param1=str_replace("http://",'',$param1);
						
					$urldata=$avalue.'/'.DISPLAY_DIR.'/index.php?page=index/clear_cache/'.md5($param1);
						
		
					$this->fetch_file_contents($urldata);
				}
			}
		}
		
		
		
		
		if($track_server_domains !="")
		{
			$server_array=explode(',',$track_server_domains);
		
			$current_server_address=$_SERVER['SERVER_ADDR'];
		
			foreach($server_array as $akey=>$avalue)
			{
				if($avalue != $current_server_address)
				{
					$param1=$avalue;
					$param1=str_replace("https://",'',$param1);
					$param1=str_replace("http://",'',$param1);
					$urldata=$avalue.'/'.TRACK_DIR.'/index.php?page=index/clear_impression/'.md5($param1);
					$this->fetch_file_contents($urldata);
				}
			}
		}
		
		/***************** For Deleting Cache Files ******************/
		

	}
};
?>