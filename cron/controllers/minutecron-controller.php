<?php
class MinutecronController extends ApplicationController
{
	function before_execute()
	{
	     parent::before_execute();
	     setlocale(LC_ALL,'en_US'); //In case of some languages,decimal places replace with ','.
	}


	function mapping_action()
	{
		$this->disable_notice_area();


		if(file_exists("../".CACHE_DIR."/cron/lock-mapping.txt"))
		{
			$file_creation_time=filemtime("../".CACHE_DIR."/cron/lock-mapping.txt");

			if(date('d',$file_creation_time) != date('d',time()))
			unlink("../".CACHE_DIR."/cron/lock-mapping.txt");
		}

        if(!is_dir("../".CACHE_DIR."/cron"))
       	mkdir("../".CACHE_DIR."/cron",0777);


		$fp = fopen("../".CACHE_DIR."/cron/lock-mapping.txt", "a+");

		if(flock($fp, LOCK_EX | LOCK_NB))
		{
			$cron_running_time=date('Y-m-d-H:i:s',time());

			$appendstring="Locked At : ".$cron_running_time." - Mapping Data Cron == ";


			set_time_limit(0);

			$db= DAL::get_instance();

			$query_limit=Configuration::get_instance()->read('db_query_execution_limit');


			$tending =date("Y",time());
			$tending.=date("m",time());
			$tending.=date("d",time());
			$tending.=date("H",time());


			$last_updated_time=$db->read_single_column("SELECT time FROM ".TABLE_PREFIX."statistics_updation WHERE task=?",array('mapping_updation_minute'));

			$start_time=floatval($last_updated_time);


			$minute='59';

			if($last_updated_time >0)
			{
				$last_updated_time=substr($last_updated_time,0,-2);

				$year=substr($start_time,0,4);
				$month=substr($start_time,4,2);
				$day=substr($start_time,6,2);
				$hour=substr($start_time,8,2);
				$minute=substr($start_time,10,2);

				$timedata=mktime($hour,$minute+1,'0',$month,$day,$year);

				$year1=date("Y",$timedata);
				$month1=date("m",$timedata);
				$day1=date("d",$timedata);
				$hour1=date("H",$timedata);
				$minute1=date("i",$timedata);

				$start_time=$year1.$month1.$day1.$hour1.$minute1;
			}



			$timearray=array();
			$tables=$db->execute_query("SHOW TABLES LIKE '".TABLE_PREFIX."impression_hourly_%'");

			while($tablesdata=$tables->fetch_array())
			{
				$tablename=$tablesdata[0];

				$tablenamearray=explode('_',$tablename);

				$timevalue=$tablenamearray[count($tablenamearray)-1];

				if($timevalue <= $tending)
				{
					if($timevalue == $tending || $timevalue > $last_updated_time || ($timevalue == $last_updated_time && $minute !='59'))
					$timearray[]=$timevalue;
				}
			}






			array_multisort($timearray);

			$tablecount=count($timearray);




			echo "<br/><br/><strong>Mapping Updation Every Minute</strong><br/>";


			if($tablecount ==0)
			{
				$timeminus=time()-60;

				$current_minute =date("Y",$timeminus);
				$current_minute.=date("m",$timeminus);
				$current_minute.=date("d",$timeminus);
				$current_minute.=date("H",$timeminus);
				$current_minute.=date("i",$timeminus);   // Get Previous Minute

				$statupdation1=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET time=?,status=? WHERE task=?",array($current_minute,2,"mapping_updation_minute"));

				echo "<br/>No Data Available<br/>";

			}
			else
			{
				   $timeminus=time()-60;

				   $current_minute =date("Y",$timeminus);
				   $current_minute.=date("m",$timeminus);
				   $current_minute.=date("d",$timeminus);
				   $current_minute.=date("H",$timeminus);
				   $current_minute.=date("i",$timeminus);   // Get Previous Minute


				   $current_minute_backup=$current_minute;


					if($start_time > $current_minute)
					{
						echo "<br/>BREAKING - LAST MINUTE UPDATION IS COMPLETE";

					}
					else
					{

				   	if($start_time ==0 && $tablecount >0)
				   	$start_time=$timearray[0].'00';

					echo "<br>Currently building data for ".$start_time." - ".$current_minute;

					$increment=0;
					$failed=0;
					$valuetime=0;

					$remove_mapping_cpm=array();
					$remove_mapping_pop=array();
					$remove_mapping_html=array();
					$remove_mapping_cpv=array();
					$remove_mapping_cpp=array();


					$cpm_enabled=$this->get_addon_status('cpm_enabled');
					$html_enabled=$this->get_addon_status('html_enabled');
					$cpd_enabled=$this->get_addon_status('sponsored_enabled');
					$pop_enabled=$this->get_addon_status('pop-ads_enabled');
					$cpv_enabled=$this->get_addon_status('video-ads_enabled');
					$cpp_enabled=$this->get_addon_status('cpp_enabled');


					if($cpm_enabled ==1 || $cpm_enabled ==0)
					$cpm_enabled=1;

					if($html_enabled ==1 || $html_enabled ==0)
					$html_enabled=1;

					if($cpd_enabled ==1 || $cpd_enabled ==0)
					$cpd_enabled=1;

					if($pop_enabled ==1 || $pop_enabled ==0)
					$pop_enabled=1;

					if($cpv_enabled ==1 || $cpv_enabled ==0)
					$cpv_enabled=1;

					if($cpp_enabled ==1 || $cpp_enabled ==0)
					$cpp_enabled=1;

					foreach($timearray as $keytime=>$valuetime)
					{
						$increment=$increment+1;

						if($increment >1)
						$start_time=$valuetime.'00';


						foreach($remove_mapping_cpm as $mkey=>$mvalue)    //If mapping already compleated, comes into next hour loop then remove inserted impressions
						{
							if(intval($mvalue) >0)
							$db->execute_query("DELETE FROM ".TABLE_PREFIX."impression_hourly_".$valuetime." WHERE aid=?",array($mvalue));
						}


						foreach($remove_mapping_pop as $mkey=>$mvalue)    //If mapping already compleated, comes into next hour loop then remove inserted impressions
						{
							if(intval($mvalue) >0)
							$db->execute_query("DELETE FROM ".TABLE_PREFIX."impression_hourly_".$valuetime." WHERE aid=?",array($mvalue));
						}


						foreach($remove_mapping_html as $mkey=>$mvalue)    //If mapping already compleated, comes into next hour loop then remove inserted impressions
						{
							if(intval($mvalue) >0)
							$db->execute_query("DELETE FROM ".TABLE_PREFIX."impression_hourly_".$valuetime." WHERE aid=?",array($mvalue));
						}


						foreach($remove_mapping_cpv as $mkey=>$mvalue)    //If mapping already compleated, comes into next hour loop then remove inserted impressions
						{
							if(intval($mvalue) >0)
							$db->execute_query("DELETE FROM ".TABLE_PREFIX."impression_hourly_".$valuetime." WHERE aid=?",array($mvalue));
						}


						foreach($remove_mapping_cpp as $mkey=>$mvalue)    //If mapping already compleated, comes into next hour loop then remove inserted impressions
						{
							if(intval($mvalue) >0)
							$db->execute_query("DELETE FROM ".TABLE_PREFIX."impression_hourly_".$valuetime." WHERE aid=?",array($mvalue));
						}



						if($valuetime != $tending)
						{
							$current_minute = $valuetime.'59';
							$update_time    = $valuetime."59";
						}
						else
						{
							$current_minute = $current_minute_backup;
							$update_time	= $current_minute;
						}


						/******************* CPM Mapping Updation *********************/

						if($cpm_enabled ==1)
						{
							$dataresult=$db->execute_query("SELECT aid,cpm_spend as spend_rate,COALESCE(sum(cpm_spend),0) as cpm_spend,second_time FROM ".TABLE_PREFIX."impression_hourly_".$valuetime." WHERE minute_time >=? AND minute_time <=? AND cpm_spend >0 GROUP BY aid",array($start_time,$current_minute));

							if($dataresult->error !="")
							{
								$failed=1;
								echo "<br>Data retrieval failed from ".TABLE_PREFIX."impression_hourly_".$valuetime." table<br>".$dataresult->get_sql();
								break;
							}


							$iii=0;                   // For Batch Updation
							$iiii=0;                  // For Batch Updation

							$arraybatch=array();   	  // For Batch Updation

							$update_id="";
							$update_spend="";
							$update_spend_today="";
							$update_cron_time="";
							$update_last_display="";

							while($content=$dataresult->fetch_assoc())
							{
								$maxid                  = 0;
								$balance_daily_budget	= 0;
								$mapid                  = $content['aid'];
								$sum_spend              = $content['cpm_spend'];
								$second_time			= $content['second_time'];


								$mapping_data=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."ads WHERE id=? AND ( pricing_status = 0 OR pricing_status = 1 OR pricing_status = 2 ) AND display_type =1",array($mapid));
								$mapping_data1=$mapping_data->fetch_assoc();

								$cron_update_time       = $mapping_data1['cron_update_time'];

								$total_budget			= $mapping_data1['total_ad_budget'];
								$total_budget_used		= $mapping_data1['total_budget_used'];
								$daily_budget			= $mapping_data1['daily_budget'];
								$daily_budget_used		= $mapping_data1['daily_budget_used'];

								$balance_budget			= $total_budget-$total_budget_used;

								if($daily_budget >0)
								$balance_daily_budget	= $daily_budget-$daily_budget_used;


								$spend_rate=floatval($content['spend_rate']);
								$spend_rate_array=explode(".",$spend_rate);

								$spend_rate_length=0;

								if(isset($spend_rate_array[1]))
								$spend_rate_length=strlen($spend_rate_array[1]);


								if($spend_rate_length >0)
								{
									$balance_budget=number_format($balance_budget,$spend_rate_length,'.', '');
									$balance_daily_budget=number_format($balance_daily_budget,$spend_rate_length,'.', '');

								}
								else
								{
									if(!is_float($balance_budget))
									$balance_budget=number_format($balance_budget,1,'.', '');    //For convert into float value. Eg:- 2 => 2.0
								}





								if($total_budget_used >= $total_budget || ($daily_budget_used >= $daily_budget && $daily_budget >0))
								{
									$db->execute_query("DELETE FROM ".TABLE_PREFIX."impression_hourly_".$valuetime." WHERE aid=? ",array($mapid));
								}



								if($cron_update_time > $start_time || $sum_spend > $balance_budget || ($sum_spend > $balance_daily_budget && $daily_budget >0))
								{
									$dataresult123=$db->execute_query("SELECT id,
																@cpm_spend := @cpm_spend+cpm_spend AS cpm_spend
								    FROM (".TABLE_PREFIX."impression_hourly_".$valuetime.",(SELECT
								    @cpm_spend := 0
								    ) data)
									WHERE @cpm_spend <= ".$balance_budget." AND aid=? AND minute_time >=? AND minute_time <=? ORDER BY @cpm_spend DESC",array($mapid,$cron_update_time,$current_minute));



									$dataget=0;

									while($dataresult1234=$dataresult123->fetch_assoc())
									{
										if($dataresult1234['cpm_spend'] > $balance_budget)  //First take first row then second row
										continue;
										else
										{
											$dataget        = 1;
											$maxid          = $dataresult1234['id'];
											$sum_spend      = $dataresult1234['cpm_spend'];

											if(!in_array($mapid,$remove_mapping_cpm))
											$remove_mapping_cpm[]	= $mapid;

											$db->execute_query("DELETE FROM ".TABLE_PREFIX."impression_hourly_".$valuetime." WHERE aid=? AND id >?",array($mapid,$maxid));

											break;
										}
									}

									$dataresult123->free_result();



									if($dataget ==0)
									{
										$sum_spend=0;
										$db->execute_query("DELETE FROM ".TABLE_PREFIX."impression_hourly_".$valuetime." WHERE aid=?",array($mapid));
									}
								}



								if($sum_spend >0)
								{
									if($update_id !="")
									$update_id.=",";

									$update_id.=$mapid;
									$update_spend.=" WHEN ".$mapid." THEN total_budget_used+".$sum_spend." ";
									$update_spend_today.=" WHEN ".$mapid." THEN daily_budget_used+".$sum_spend." ";
									$update_cron_time.=" WHEN ".$mapid." THEN ".$update_time." ";
									$update_last_display.=" WHEN ".$mapid." THEN ".$second_time." ";

									/*********   For Batch Updation   ********/
									$iiii++;

									if($iiii % $query_limit ==0)
									{
										$arraybatch[$iii][0]=$update_spend;
										$arraybatch[$iii][1]=$update_spend_today;
										$arraybatch[$iii][2]=$update_cron_time;
										$arraybatch[$iii][3]=$update_last_display;
										$arraybatch[$iii][4]=$update_id;

										$update_id="";
										$update_spend="";
										$update_spend_today="";
										$update_cron_time="";
										$update_last_display="";

										$iii++;
										$iiii=0;
									}
									/*********   For Batch Updation   ********/
								}
							}



							$dataresult->free_result();



							/*********   For Batch Updation   ********/
							if($iiii >0)
							{
								$arraybatch[$iii][0]=$update_spend;
								$arraybatch[$iii][1]=$update_spend_today;
								$arraybatch[$iii][2]=$update_cron_time;
								$arraybatch[$iii][3]=$update_last_display;
								$arraybatch[$iii][4]=$update_id;
							}
							/*********   For Batch Updation   ********/

							foreach($arraybatch as $akey=>$avalue)
							{
								if($avalue[0] !="" && $avalue[1] !="" && $avalue[2] !="" && $avalue[3] !="" && $avalue[4] !="")
								{
									$batchupdate=$db->execute_query("UPDATE ".TABLE_PREFIX."ads SET
									`total_budget_used` = (CASE id ".$avalue[0]." ELSE `total_budget_used` END),
									`daily_budget_used` = (CASE id ".$avalue[1]." ELSE `daily_budget_used` END),
									`cron_update_time` = (CASE id ".$avalue[2]." ELSE `cron_update_time` END),
									`last_display` = (CASE id ".$avalue[3]." ELSE `last_display` END)
									WHERE id IN (".$avalue[4].")");

		                            				$batchupdate1=$db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET
									`total_budget_used` = (CASE id ".$avalue[0]." ELSE `total_budget_used` END),
									`daily_budget_used` = (CASE id ".$avalue[1]." ELSE `daily_budget_used` END)
									WHERE id IN (".$avalue[4].")");

									if($batchupdate->error !="" && $batchupdate1->error !="")
									{
										$failed=1;
										echo "<br>Data updation to ".TABLE_PREFIX."ads failed<br>".$batchupdate->get_sql();
										break;
									}





								}
							}
						}

						/******************* CPM Mapping Updation *********************/



						/******************* POP Mapping Updation *********************/

						if($pop_enabled ==1 && $failed ==0)
						{
							$dataresult=$db->execute_query("SELECT aid,pop_spend as spend_rate,COALESCE(sum(pop_spend),0) as pop_spend,second_time FROM ".TABLE_PREFIX."impression_hourly_".$valuetime." WHERE minute_time >=? AND minute_time <=? AND pop_spend >0 GROUP BY aid",array($start_time,$current_minute));

							if($dataresult->error !="")
							{
								$failed=1;
								echo "<br>Data retrieval failed from ".TABLE_PREFIX."impression_hourly_".$valuetime." table<br>".$dataresult->get_sql();
								break;
							}



							$update_id="";
							$update_spend="";
							$update_spend_today="";
							$update_cron_time="";
							$update_last_display="";


							$iii=0;                   // For Batch Updation
							$iiii=0;                  // For Batch Updation

							$arraybatch=array();   	  // For Batch Updation


							while($content=$dataresult->fetch_assoc())
							{
								$maxid                  = 0;
								$balance_daily_budget	= 0;
								$mapid                  = $content['aid'];
								$sum_spend              = $content['pop_spend'];
								$second_time			= $content['second_time'];



								$mapping_data=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."ads WHERE id=? AND ( pricing_status = 0 OR pricing_status = 1 OR pricing_status = 2 ) AND display_type = 1",array($mapid));
								$mapping_data1=$mapping_data->fetch_assoc();

								$cron_update_time       = $mapping_data1['cron_update_time'];

								$total_budget			= $mapping_data1['total_ad_budget'];
								$total_budget_used		= $mapping_data1['total_budget_used'];
								$daily_budget			= $mapping_data1['daily_budget'];
								$daily_budget_used		= $mapping_data1['daily_budget_used'];

								$balance_budget			= $total_budget-$total_budget_used;

								if($daily_budget >0)
								$balance_daily_budget	= $daily_budget-$daily_budget_used;


								$spend_rate=floatval($content['spend_rate']);
								$spend_rate_array=explode(".",$spend_rate);

								$spend_rate_length=0;

								if(isset($spend_rate_array[1]))
								$spend_rate_length=strlen($spend_rate_array[1]);


								if($spend_rate_length >0)
								{
									$balance_budget=number_format($balance_budget,$spend_rate_length,'.', '');
									$balance_daily_budget=number_format($balance_daily_budget,$spend_rate_length,'.', '');

								}
								else
								{
									if(!is_float($balance_budget))
									$balance_budget=number_format($balance_budget,1,'.', '');    //For convert into float value. Eg:- 2 => 2.0
								}




								if($total_budget_used >= $total_budget || ($daily_budget_used >= $daily_budget && $daily_budget >0))
								{

									$db->execute_query("DELETE FROM ".TABLE_PREFIX."impression_hourly_".$valuetime." WHERE aid=? ",array($mapid));
								}


								if($cron_update_time > $start_time || $sum_spend > $balance_budget || ($sum_spend > $balance_daily_budget && $daily_budget >0))
								{
									$dataresult123=$db->execute_query("SELECT id,
																@pop_spend := @pop_spend+pop_spend AS pop_spend
								    FROM (".TABLE_PREFIX."impression_hourly_".$valuetime.",(SELECT
								    @pop_spend := 0
								    ) data)
									WHERE @pop_spend <= ".$balance_budget." AND aid=? AND minute_time >=? AND minute_time <=? ORDER BY @pop_spend DESC",array($mapid,$cron_update_time,$current_minute));


									$dataget=0;

									while($dataresult1234=$dataresult123->fetch_assoc())
									{
										if($dataresult1234['pop_spend'] > $balance_budget)  //First take first row then second row
										continue;
										else
										{
											$dataget        = 1;
											$maxid          = $dataresult1234['id'];
											$sum_spend      = $dataresult1234['pop_spend'];

											if(!in_array($mapid,$remove_mapping_pop))
											$remove_mapping_pop[]	= $mapid;

											$db->execute_query("DELETE FROM ".TABLE_PREFIX."impression_hourly_".$valuetime." WHERE aid=? AND id >?",array($mapid,$maxid));

											break;
										}
									}

									$dataresult123->free_result();



									if($dataget ==0)
									{
										$sum_spend=0;
										$db->execute_query("DELETE FROM ".TABLE_PREFIX."impression_hourly_".$valuetime." WHERE aid=?",array($mapid));
									}
								}

								if($sum_spend >0)
								{
									if($update_id !="")
									$update_id.=",";

									$update_id.=$mapid;
									$update_spend.=" WHEN ".$mapid." THEN total_budget_used+".$sum_spend." ";
									$update_spend_today.=" WHEN ".$mapid." THEN daily_budget_used+".$sum_spend." ";
									$update_cron_time.=" WHEN ".$mapid." THEN ".$update_time." ";
									$update_last_display.=" WHEN ".$mapid." THEN ".$second_time." ";

									/*********   For Batch Updation   ********/
									$iiii++;

									if($iiii % $query_limit ==0)
									{
										$arraybatch[$iii][0]=$update_spend;
										$arraybatch[$iii][1]=$update_spend_today;
										$arraybatch[$iii][2]=$update_cron_time;
										$arraybatch[$iii][3]=$update_last_display;
										$arraybatch[$iii][4]=$update_id;

										$update_id="";
										$update_spend="";
										$update_spend_today="";
										$update_cron_time="";
										$update_last_display="";

										$iii++;
										$iiii=0;
									}

									/*********   For Batch Updation   ********/
								}
							}

							$dataresult->free_result();




							/*********   For Batch Updation   ********/
							if($iiii >0)
							{
								$arraybatch[$iii][0]=$update_spend;
								$arraybatch[$iii][1]=$update_spend_today;
								$arraybatch[$iii][2]=$update_cron_time;
								$arraybatch[$iii][3]=$update_last_display;
								$arraybatch[$iii][4]=$update_id;
							}
							/*********   For Batch Updation   ********/

							foreach($arraybatch as $akey=>$avalue)
							{
								if($avalue[0] !="" && $avalue[1] !="" && $avalue[2] !="" && $avalue[3] !="" && $avalue[4] !="")
								{
									$batchupdate=$db->execute_query("UPDATE ".TABLE_PREFIX."ads SET
									`total_budget_used` = (CASE id ".$avalue[0]." ELSE `total_budget_used` END),
									`daily_budget_used` = (CASE id ".$avalue[1]." ELSE `daily_budget_used` END),
									`cron_update_time` = (CASE id ".$avalue[2]." ELSE `cron_update_time` END),
									`last_display` = (CASE id ".$avalue[3]." ELSE `last_display` END)
									WHERE id IN (".$avalue[4].")");

									 $batchupdate1=$db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET
									`total_budget_used` = (CASE id ".$avalue[0]." ELSE `total_budget_used` END),
									`daily_budget_used` = (CASE id ".$avalue[1]." ELSE `daily_budget_used` END)
									WHERE id IN (".$avalue[4].")");

									if($batchupdate->error !="" && $batchupdate1->error !="")
									{
										$failed=1;
										echo "<br>Data updation to ".TABLE_PREFIX."ads failed<br>".$batchupdate->get_sql();
										break;
									}


								}
							}
						}

						/******************* POP Mapping Updation *********************/


						/******************* HTML Mapping Updation *********************/

						if($html_enabled ==1 && $failed ==0)
						{
							$dataresult=$db->execute_query("SELECT aid,html_profit as spend_rate,COALESCE(sum(html_profit),0) as html_profit,second_time FROM ".TABLE_PREFIX."impression_hourly_".$valuetime." WHERE minute_time >=? AND minute_time <=? AND html_profit >0 GROUP BY aid",array($start_time,$current_minute));

							if($dataresult->error !="")
							{
								$failed=1;
								echo "<br>Data retrieval failed from ".TABLE_PREFIX."impression_hourly_".$valuetime." table<br>".$dataresult->get_sql();
								break;
							}



							$update_id="";
							$update_spend="";
							$update_spend_today="";
							$update_cron_time="";
						    $update_last_display="";

							$iii=0;                   // For Batch Updation
							$iiii=0;                  // For Batch Updation

							$arraybatch=array();   	  // For Batch Updation


							while($content=$dataresult->fetch_assoc())
							{
								$maxid                  = 0;
								$balance_daily_budget	= 0;
								$mapid                  = $content['aid'];
								$sum_spend              = $content['html_profit'];
								$second_time			= $content['second_time'];



								$mapping_data=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."ads WHERE id=? AND ( pricing_status = 0 OR pricing_status = 1 OR pricing_status = 2 ) AND display_type =2",array($mapid));
								$mapping_data1=$mapping_data->fetch_assoc();

								$cron_update_time       = $mapping_data1['cron_update_time'];

								$total_budget			= $mapping_data1['total_ad_budget'];
								$total_budget_used		= $mapping_data1['total_budget_used'];
								$daily_budget			= $mapping_data1['daily_budget'];
								$daily_budget_used		= $mapping_data1['daily_budget_used'];

								$balance_budget			= $total_budget-$total_budget_used;

								if($daily_budget >0)
								$balance_daily_budget	= $daily_budget-$daily_budget_used;


								$spend_rate=floatval($content['spend_rate']);
								$spend_rate_array=explode(".",$spend_rate);

								$spend_rate_length=0;

								if(isset($spend_rate_array[1]))
								$spend_rate_length=strlen($spend_rate_array[1]);


								if($spend_rate_length >0)
								{
									$balance_budget=number_format($balance_budget,$spend_rate_length,'.', '');
									$balance_daily_budget=number_format($balance_daily_budget,$spend_rate_length,'.', '');

								}
								else
								{
									if(!is_float($balance_budget))
									$balance_budget=number_format($balance_budget,1,'.', '');    //For convert into float value. Eg:- 2 => 2.0
								}



								if($total_budget_used >= $total_budget || ($daily_budget_used >= $daily_budget && $daily_budget >0))
								{

									$db->execute_query("DELETE FROM ".TABLE_PREFIX."impression_hourly_".$valuetime." WHERE aid=? ",array($mapid));
								}


								if($cron_update_time > $start_time || $sum_spend > $balance_budget || ($sum_spend > $balance_daily_budget && $daily_budget >0))
								{
								    $dataresult123=$db->execute_query("SELECT id,
								    @html_profit := @html_profit+html_profit AS html_profit
								    FROM (".TABLE_PREFIX."impression_hourly_".$valuetime.",(SELECT
								    @html_profit := 0
								    ) data)
									WHERE @html_profit <= ".$balance_budget." AND aid=? AND minute_time >=? AND minute_time <=? ORDER BY @html_profit DESC",array($mapid,$cron_update_time,$current_minute));


									$dataget=0;

									while($dataresult1234=$dataresult123->fetch_assoc())
									{
										if($dataresult1234['html_profit'] > $balance_budget)  //First take first row then second row
										continue;
										else
										{
											$dataget        = 1;
											$maxid          = $dataresult1234['id'];
											$sum_spend = $dataresult1234['html_profit'];

											if(!in_array($mapid,$remove_mapping_html))
											$remove_mapping_html[]	= $mapid;

											$db->execute_query("DELETE FROM ".TABLE_PREFIX."impression_hourly_".$valuetime." WHERE aid=? AND id >?",array($mapid,$maxid));

											break;
										}
									}

									$dataresult123->free_result();



									if($dataget ==0)
									{
										$sum_spend=0;
										$db->execute_query("DELETE FROM ".TABLE_PREFIX."impression_hourly_".$valuetime." WHERE aid=?",array($mapid));
									}
								}

								if($sum_spend >0)
								{
									if($update_id !="")
									$update_id.=",";

									$update_id.=$mapid;
									$update_spend.=" WHEN ".$mapid." THEN total_budget_used+".$sum_spend." ";
									$update_spend_today.=" WHEN ".$mapid." THEN daily_budget_used+".$sum_spend." ";
									$update_cron_time.=" WHEN ".$mapid." THEN ".$update_time." ";
									$update_last_display.=" WHEN ".$mapid." THEN ".$second_time." ";


									/*********   For Batch Updation   ********/
									$iiii++;

									if($iiii % $query_limit ==0)
									{
										$arraybatch[$iii][0]=$update_spend;
										$arraybatch[$iii][1]=$update_spend_today;
										$arraybatch[$iii][2]=$update_cron_time;
										$arraybatch[$iii][3]=$update_last_display;
										$arraybatch[$iii][4]=$update_id;


										$update_id="";
										$update_spend="";
										$update_spend_today="";
										$update_cron_time="";
									    $update_last_display="";

										$iii++;
										$iiii=0;
									}

									/*********   For Batch Updation   ********/
								}
							}

							$dataresult->free_result();




							/*********   For Batch Updation   ********/
							if($iiii >0)
							{
								$arraybatch[$iii][0]=$update_spend;
								$arraybatch[$iii][1]=$update_spend_today;
								$arraybatch[$iii][2]=$update_cron_time;
								$arraybatch[$iii][3]=$update_last_display;
								$arraybatch[$iii][4]=$update_id;
							}
							/*********   For Batch Updation   ********/

							foreach($arraybatch as $akey=>$avalue)
							{
								if($avalue[0] !="" && $avalue[1] !="" && $avalue[2] !="" && $avalue[3] !="" && $avalue[4] !="")
								{
									$batchupdate=$db->execute_query("UPDATE ".TABLE_PREFIX."ads SET
									`total_budget_used` = (CASE id ".$avalue[0]." ELSE `total_budget_used` END),
									`daily_budget_used` = (CASE id ".$avalue[1]." ELSE `daily_budget_used` END),
									`cron_update_time` = (CASE id ".$avalue[2]." ELSE `cron_update_time` END),
									`last_display` = (CASE id ".$avalue[3]." ELSE `last_display` END)
									WHERE id IN (".$avalue[4].")");

                                    					$batchupdate1=$db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET
									`total_budget_used` = (CASE id ".$avalue[0]." ELSE `total_budget_used` END),
									`daily_budget_used` = (CASE id ".$avalue[1]." ELSE `daily_budget_used` END)
									WHERE id IN (".$avalue[4].")");

									if($batchupdate->error !="" && $batchupdate1->error !="")
									{
										$failed=1;
										echo "<br>Data updation to ".TABLE_PREFIX."ads failed<br>".$batchupdate->get_sql();
										break;
									}
								}
							}
						}

						/******************* HTML Mapping Updation *********************/


						/******************* CPV Mapping Updation *********************/



						if($cpv_enabled ==1)
						{
							$dataresult=$db->execute_query("SELECT aid,cpv_spend as spend_rate,COALESCE(sum(cpv_spend),0) as cpv_spend,second_time FROM ".TABLE_PREFIX."impression_hourly_".$valuetime." WHERE minute_time >=? AND minute_time <=? AND cpv_spend >0 GROUP BY aid",array($start_time,$current_minute));

							if($dataresult->error !="")
							{
								$failed=1;
								echo "<br>Data retrieval failed from ".TABLE_PREFIX."impression_hourly_".$valuetime." table<br>".$dataresult->get_sql();
								break;
							}


							$iii=0;                   // For Batch Updation
							$iiii=0;                  // For Batch Updation

							$arraybatch=array();   	  // For Batch Updation

							$update_id="";
							$update_spend="";
							$update_spend_today="";
							$update_cron_time="";
						    $update_last_display="";


							while($content=$dataresult->fetch_assoc())
							{
								$maxid                  = 0;
								$balance_daily_budget	= 0;
								$mapid                  = $content['aid'];
								$sum_spend              = $content['cpv_spend'];
								$second_time			= $content['second_time'];


								$mapping_data=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."ads WHERE id=? AND ( pricing_status = 0 OR pricing_status = 1 OR pricing_status = 2 ) AND display_type =1",array($mapid));
								$mapping_data1=$mapping_data->fetch_assoc();

								$cron_update_time       = $mapping_data1['cron_update_time'];

								$total_budget			= $mapping_data1['total_ad_budget'];
								$total_budget_used		= $mapping_data1['total_budget_used'];
								$daily_budget			= $mapping_data1['daily_budget'];
								$daily_budget_used		= $mapping_data1['daily_budget_used'];

								$balance_budget			= $total_budget-$total_budget_used;

								if($daily_budget >0)
								$balance_daily_budget	= $daily_budget-$daily_budget_used;


								$spend_rate=floatval($content['spend_rate']);
								$spend_rate_array=explode(".",$spend_rate);

								$spend_rate_length=0;

								if(isset($spend_rate_array[1]))
								$spend_rate_length=strlen($spend_rate_array[1]);


								if($spend_rate_length >0)
								{
									$balance_budget=number_format($balance_budget,$spend_rate_length,'.', '');
									$balance_daily_budget=number_format($balance_daily_budget,$spend_rate_length,'.', '');

								}
								else
								{
									if(!is_float($balance_budget))
									$balance_budget=number_format($balance_budget,1,'.', '');    //For convert into float value. Eg:- 2 => 2.0
								}



								if($total_budget_used >= $total_budget || ($daily_budget_used >= $daily_budget && $daily_budget >0))
								{

									$db->execute_query("DELETE FROM ".TABLE_PREFIX."impression_hourly_".$valuetime." WHERE aid=? ",array($mapid));
								}


								if($cron_update_time > $start_time || $sum_spend > $balance_budget || ($sum_spend > $balance_daily_budget && $daily_budget >0))
								{
									$dataresult123=$db->execute_query("SELECT id,
																@cpv_spend := @cpv_spend+cpv_spend AS cpv_spend
								    FROM (".TABLE_PREFIX."impression_hourly_".$valuetime.",(SELECT
								    @cpv_spend := 0
								    ) data)
									WHERE @cpv_spend <= ".$balance_budget." AND aid=? AND minute_time >=? AND minute_time <=? ORDER BY @cpv_spend DESC",array($mapid,$cron_update_time,$current_minute));



									$dataget=0;

									while($dataresult1234=$dataresult123->fetch_assoc())
									{
										if($dataresult1234['cpv_spend'] > $balance_budget)  //First take first row then second row
										continue;
										else
										{
											$dataget        = 1;
											$maxid          = $dataresult1234['id'];
											$sum_spend      = $dataresult1234['cpv_spend'];


											if(!in_array($mapid,$remove_mapping_cpv))
											$remove_mapping_cpv[]	= $mapid;


											$db->execute_query("DELETE FROM ".TABLE_PREFIX."impression_hourly_".$valuetime." WHERE aid=? AND id >?",array($mapid,$maxid));

											break;
										}
									}


									$dataresult123->free_result();



									if($dataget ==0)
									{
										$sum_spend=0;
										$db->execute_query("DELETE FROM ".TABLE_PREFIX."impression_hourly_".$valuetime." WHERE aid=?",array($mapid));
									}
								}



								if($sum_spend >0)
								{
									if($update_id !="")
									$update_id.=",";

									$update_id.=$mapid;
									$update_spend.=" WHEN ".$mapid." THEN total_budget_used+".$sum_spend." ";
									$update_spend_today.=" WHEN ".$mapid." THEN daily_budget_used+".$sum_spend." ";
									$update_cron_time.=" WHEN ".$mapid." THEN ".$update_time." ";
									$update_last_display.=" WHEN ".$mapid." THEN ".$second_time." ";


									/*********   For Batch Updation   ********/
									$iiii++;

									if($iiii % $query_limit ==0)
									{
										$arraybatch[$iii][0]=$update_spend;
										$arraybatch[$iii][1]=$update_spend_today;
										$arraybatch[$iii][2]=$update_cron_time;
										$arraybatch[$iii][3]=$update_last_display;
										$arraybatch[$iii][4]=$update_id;

										$update_id="";
										$update_spend="";
										$update_spend_today="";
										$update_cron_time="";
									    $update_last_display="";

										$iii++;
										$iiii=0;
									}
									/*********   For Batch Updation   ********/
								}
							}


							$dataresult->free_result();



							/*********   For Batch Updation   ********/
							if($iiii >0)
							{
								$arraybatch[$iii][0]=$update_spend;
								$arraybatch[$iii][1]=$update_spend_today;
								$arraybatch[$iii][2]=$update_cron_time;
								$arraybatch[$iii][3]=$update_last_display;
								$arraybatch[$iii][4]=$update_id;
							}
							/*********   For Batch Updation   ********/

							foreach($arraybatch as $akey=>$avalue)
							{
								if($avalue[0] !="" && $avalue[1] !="" && $avalue[2] !="" && $avalue[3] !="" && $avalue[4] !="")
								{
									$batchupdate=$db->execute_query("UPDATE ".TABLE_PREFIX."ads SET
									`total_budget_used` = (CASE id ".$avalue[0]." ELSE `total_budget_used` END),
									`daily_budget_used` = (CASE id ".$avalue[1]." ELSE `daily_budget_used` END),
									`cron_update_time` = (CASE id ".$avalue[2]." ELSE `cron_update_time` END),
									`last_display` = (CASE id ".$avalue[3]." ELSE `last_display` END)
									WHERE id IN (".$avalue[4].")");

                                    					$batchupdate1=$db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET
									`total_budget_used` = (CASE id ".$avalue[0]." ELSE `total_budget_used` END),
									`daily_budget_used` = (CASE id ".$avalue[1]." ELSE `daily_budget_used` END)
									WHERE id IN (".$avalue[4].")");

									if($batchupdate->error !="" && $batchupdate1->error !="" )
									{
										$failed=1;
										echo "<br>Data updation to ".TABLE_PREFIX."ads failed<br>".$batchupdate->get_sql();
										break;
									}
								}
							}
						}

						/******************* CPV Mapping Updation *********************/





						/******************* CPP Mapping Updation *********************/

						if($cpp_enabled ==1)
						{
							$dataresult=$db->execute_query("SELECT aid,cpp_spend as spend_rate,COALESCE(sum(cpp_spend),0) as cpp_spend,second_time FROM ".TABLE_PREFIX."impression_hourly_".$valuetime." WHERE minute_time >=? AND minute_time <=? AND cpp_spend >0 GROUP BY aid",array($start_time,$current_minute));

							if($dataresult->error !="")
							{
								$failed=1;
								echo "<br>Data retrieval failed from ".TABLE_PREFIX."impression_hourly_".$valuetime." table<br>".$dataresult->get_sql();
								break;
							}


							$iii=0;                   // For Batch Updation
							$iiii=0;                  // For Batch Updation

							$arraybatch=array();   	  // For Batch Updation

							$update_id="";
							$update_spend="";
							$update_spend_today="";
							$update_cron_time="";
							$update_last_display="";

							while($content=$dataresult->fetch_assoc())
							{
								$maxid                  = 0;
								$balance_daily_budget	= 0;
								$mapid                  = $content['aid'];
								$sum_spend              = $content['cpp_spend'];
								$second_time			= $content['second_time'];


								$mapping_data=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."ads WHERE id=? AND ( pricing_status = 0 OR pricing_status = 1 OR pricing_status = 2 ) AND display_type =18",array($mapid));
								$mapping_data1=$mapping_data->fetch_assoc();

								$cron_update_time       = $mapping_data1['cron_update_time'];

								$total_budget			= $mapping_data1['total_ad_budget'];
								$total_budget_used		= $mapping_data1['total_budget_used'];
								$daily_budget			= $mapping_data1['daily_budget'];
								$daily_budget_used		= $mapping_data1['daily_budget_used'];

								$balance_budget			= $total_budget-$total_budget_used;

								if($daily_budget >0)
								$balance_daily_budget	= $daily_budget-$daily_budget_used;


								$spend_rate=floatval($content['spend_rate']);
								$spend_rate_array=explode(".",$spend_rate);

								$spend_rate_length=0;

								if(isset($spend_rate_array[1]))
								$spend_rate_length=strlen($spend_rate_array[1]);


								if($spend_rate_length >0)
								{
									$balance_budget=number_format($balance_budget,$spend_rate_length,'.', '');
									$balance_daily_budget=number_format($balance_daily_budget,$spend_rate_length,'.', '');

								}
								else
								{
									if(!is_float($balance_budget))
									$balance_budget=number_format($balance_budget,1,'.', '');    //For convert into float value. Eg:- 2 => 2.0
								}





								if($total_budget_used >= $total_budget || ($daily_budget_used >= $daily_budget && $daily_budget >0))
								{
									$db->execute_query("DELETE FROM ".TABLE_PREFIX."impression_hourly_".$valuetime." WHERE aid=? ",array($mapid));
								}



								if($cron_update_time > $start_time || $sum_spend > $balance_budget || ($sum_spend > $balance_daily_budget && $daily_budget >0))
								{
									$dataresult123=$db->execute_query("SELECT id,
																@cpp_spend := @cpp_spend+cpp_spend AS cpp_spend
								    FROM (".TABLE_PREFIX."impression_hourly_".$valuetime.",(SELECT
								    @cpp_spend := 0
								    ) data)
									WHERE @cpp_spend <= ".$balance_budget." AND aid=? AND minute_time >=? AND minute_time <=? ORDER BY @cpp_spend DESC",array($mapid,$cron_update_time,$current_minute));



									$dataget=0;

									while($dataresult1234=$dataresult123->fetch_assoc())
									{
										if($dataresult1234['cpp_spend'] > $balance_budget)  //First take first row then second row
										continue;
										else
										{
											$dataget        = 1;
											$maxid          = $dataresult1234['id'];
											$sum_spend      = $dataresult1234['cpp_spend'];

											if(!in_array($mapid,$remove_mapping_cpp))
											$remove_mapping_cpp[]	= $mapid;

											$db->execute_query("DELETE FROM ".TABLE_PREFIX."impression_hourly_".$valuetime." WHERE aid=? AND id >?",array($mapid,$maxid));

											break;
										}
									}

									$dataresult123->free_result();



									if($dataget ==0)
									{
										$sum_spend=0;
										$db->execute_query("DELETE FROM ".TABLE_PREFIX."impression_hourly_".$valuetime." WHERE aid=?",array($mapid));
									}
								}



								if($sum_spend >0)
								{
									if($update_id !="")
									$update_id.=",";

									$update_id.=$mapid;
									$update_spend.=" WHEN ".$mapid." THEN total_budget_used+".$sum_spend." ";
									$update_spend_today.=" WHEN ".$mapid." THEN daily_budget_used+".$sum_spend." ";
									$update_cron_time.=" WHEN ".$mapid." THEN ".$update_time." ";
									$update_last_display.=" WHEN ".$mapid." THEN ".$second_time." ";

									/*********   For Batch Updation   ********/
									$iiii++;

									if($iiii % $query_limit ==0)
									{
										$arraybatch[$iii][0]=$update_spend;
										$arraybatch[$iii][1]=$update_spend_today;
										$arraybatch[$iii][2]=$update_cron_time;
										$arraybatch[$iii][3]=$update_last_display;
										$arraybatch[$iii][4]=$update_id;

										$update_id="";
										$update_spend="";
										$update_spend_today="";
										$update_cron_time="";
										$update_last_display="";

										$iii++;
										$iiii=0;
									}
									/*********   For Batch Updation   ********/
								}
							}



							$dataresult->free_result();



							/*********   For Batch Updation   ********/
							if($iiii >0)
							{
								$arraybatch[$iii][0]=$update_spend;
								$arraybatch[$iii][1]=$update_spend_today;
								$arraybatch[$iii][2]=$update_cron_time;
								$arraybatch[$iii][3]=$update_last_display;
								$arraybatch[$iii][4]=$update_id;
							}
							/*********   For Batch Updation   ********/

							foreach($arraybatch as $akey=>$avalue)
							{
								if($avalue[0] !="" && $avalue[1] !="" && $avalue[2] !="" && $avalue[3] !="" && $avalue[4] !="")
								{
									$batchupdate=$db->execute_query("UPDATE ".TABLE_PREFIX."ads SET
									`total_budget_used` = (CASE id ".$avalue[0]." ELSE `total_budget_used` END),
									`daily_budget_used` = (CASE id ".$avalue[1]." ELSE `daily_budget_used` END),
									`cron_update_time` = (CASE id ".$avalue[2]." ELSE `cron_update_time` END),
									`last_display` = (CASE id ".$avalue[3]." ELSE `last_display` END)
									WHERE id IN (".$avalue[4].")");

		                            				$batchupdate1=$db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET
									`total_budget_used` = (CASE id ".$avalue[0]." ELSE `total_budget_used` END),
									`daily_budget_used` = (CASE id ".$avalue[1]." ELSE `daily_budget_used` END)
									WHERE id IN (".$avalue[4].")");

									if($batchupdate->error !="" && $batchupdate1->error !="")
									{
										$failed=1;
										echo "<br>Data updation to ".TABLE_PREFIX."ads failed<br>".$batchupdate->get_sql();
										break;
									}
								}
							}
						}

						/******************* CPP Mapping Updation *********************/
















						/******************* CPD Mapping Updation *********************/

						if($cpd_enabled ==1 && $failed ==0)
						{
							$dataresult=$db->execute_query("SELECT cpdid,COALESCE(sum(cpd_impression),0) as cpd_impression FROM ".TABLE_PREFIX."impression_hourly_".$valuetime." WHERE minute_time >=? AND minute_time <=? AND cpd_impression >0 GROUP BY cpdid",array($start_time,$current_minute));

							if($dataresult->error !="")
							{
								$failed=1;
								echo "<br>Data retrieval failed from ".TABLE_PREFIX."impression_hourly_".$valuetime." table<br>".$dataresult->get_sql();
								break;
							}


							$update_id="";
							$update_impressions="";


							$iii=0;                   // For Batch Updation
							$iiii=0;                  // For Batch Updation

							$arraybatch=array();   	  // For Batch Updation


							while($content=$dataresult->fetch_assoc())
							{
								$mapid                  = $content['cpdid'];
								$sum_impression         = $content['cpd_impression'];


								if($sum_impression >0)
								{
									if($update_id !="")
									$update_id.=",";

									$update_id.=$mapid;
									$update_impressions.=" WHEN ".$mapid." THEN impressions+".$sum_impression." ";


									/*********   For Batch Updation   ********/
									$iiii++;

									if($iiii % $query_limit ==0)
									{
										$arraybatch[$iii][0]=$update_impressions;
										$arraybatch[$iii][1]=$update_id;

										$update_id="";
										$update_impressions="";

										$iii++;
										$iiii=0;
									}

									/*********   For Batch Updation   ********/
								}
							}


							$dataresult->free_result();


							/*********   For Batch Updation   ********/
							if($iiii >0)
							{
								$arraybatch[$iii][0]=$update_impressions;
								$arraybatch[$iii][1]=$update_id;
							}
							/*********   For Batch Updation   ********/

							foreach($arraybatch as $akey=>$avalue)
							{
								if($avalue[0] !="" && $avalue[1] !="")
								{
									$batchupdate=$db->execute_query("UPDATE ".TABLE_PREFIX."sponsored_ad_mapping SET `impressions` = (CASE id ".$avalue[0]." ELSE `impressions` END) WHERE id IN (".$avalue[1].")");

									if($batchupdate->error !="")
									{
										$failed=1;
										echo "<br>Data updation to ".TABLE_PREFIX."sponsored_ad_mapping failed<br>".$batchupdate->get_sql();
										break;
									}
								}
							}
						}

						/******************* CPD Mapping Updation *********************/

						/****************** Ads Cache Updation ************************/
						$dataresult = $db->execute_query("SELECT a.id,a.display_type,a.default_rate,a.total_ad_budget,a.total_budget_used FROM ".TABLE_PREFIX."ads a
								INNER JOIN ".TABLE_PREFIX."ads_cache ac ON a.id = ac.id
								WHERE (((a.display_type = 1 || a.display_type = 2) AND ((a.total_budget_used+(a.default_rate / 1000)) > a.total_ad_budget)) OR ((a.display_type = 0 || a.display_type = 6 || a.display_type = 18) AND ((a.total_budget_used + a.default_rate) > a.total_ad_budget)))
 									AND ac.pricing_status = 1 AND ac.budget_available = 1");

						while($content=$dataresult->fetch_assoc())
						{
							$db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET pricing_status = 0,budget_available = 0,daily_budget_available = 0 WHERE id=?",array($content['id']));
						}




						$dataresult = $db->execute_query("SELECT a.id,a.display_type,a.default_rate,a.daily_budget,a.daily_budget_used FROM ".TABLE_PREFIX."ads a
								INNER JOIN ".TABLE_PREFIX."ads_cache ac ON a.id = ac.id
								WHERE a.daily_budget > 0 AND (((a.display_type = 1 || a.display_type = 2) AND ((a.daily_budget_used+(a.default_rate / 1000)) > a.daily_budget)) OR ((a.display_type = 0 || a.display_type = 6 || a.display_type = 18) AND ((a.daily_budget_used + a.default_rate) > a.daily_budget)))
 									AND ac.daily_budget_available = 1");

						while($content=$dataresult->fetch_assoc())
						{
							$db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET daily_budget_available = 0 WHERE id=?",array($content['id']));
						}
					    /****************** Ads Cache Updation ************************/




					    /******************* CPC / CPA Last Display Time Updation *******************/
						/*
						$dataresult=$db->execute_query("SELECT aid,MAX(second_time) as second_time FROM ".TABLE_PREFIX."impression_hourly_".$valuetime." WHERE minute_time >=? AND minute_time <=? AND (cpc_impression >0 OR cpa_impression >0) GROUP BY aid",array($start_time,$current_minute));

						$update_id="";
						$update_last_display="";


						$iii=0;                   // For Batch Updation
						$iiii=0;                  // For Batch Updation

						$arraybatch=array();   	  // For Batch Updation


						while($content=$dataresult->fetch_assoc())
						{
								if($update_id !="")
								$update_id.=",";

								$update_id.=$content['aid'];
								$update_last_display.=" WHEN ".$content['aid']." THEN ".$content['second_time']." ";


								$iiii++;

								if($iiii % $query_limit ==0)
								{
									$arraybatch[$iii][0]=$update_last_display;
									$arraybatch[$iii][1]=$update_id;

									$update_id="";
									$update_last_display="";

									$iii++;
									$iiii=0;
								}
						}


						$dataresult->free_result();



						if($iiii >0)
						{
							$arraybatch[$iii][0]=$update_last_display;
							$arraybatch[$iii][1]=$update_id;
						}

						foreach($arraybatch as $akey=>$avalue)
						{
							if($avalue[0] !="" && $avalue[1] !="")
							$batchupdate=$db->execute_query("UPDATE ".TABLE_PREFIX."ads SET `last_display` = (CASE id ".$avalue[0]." ELSE `last_display` END) WHERE id IN (".$avalue[1].")");
						}
						*/

						/******************* CPC / CPA Last Display Time Updation *******************/






						if($failed ==1)
						break;
						else
						{
							if($increment == $tablecount && ($tablecount ==0 || ($tablecount >0 && $valuetime < $tending)))  //If last hours table not exists
							{
							   $end_minute =date("Y",$timeminus);
							   $end_minute.=date("m",$timeminus);
							   $end_minute.=date("d",$timeminus);
							   $end_minute.=date("H",$timeminus);
							   $end_minute.=date("i",$timeminus);   // Get Previous Minute

							   $update_time=$end_minute;
							}


							$statupdation1=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET time=?,status=? WHERE task=?",array($update_time,2,"mapping_updation_minute"));

							if($statupdation1->error !="")
							{
								echo "<br>Data updation to ".TABLE_PREFIX."statistics_updation failed<br>".$statupdation1->get_sql();
								$failed=1;
							    break;
							}

						}
					}



					//$db->execute_query("OPTIMIZE TABLE ".TABLE_PREFIX."ads");



					if($failed ==0)
					echo "<br>BREAKING - Successfully Updated Data";
				}
			}

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

	function data_backup_action()
	{
		$this->disable_notice_area();

		    $db= DAL::get_instance();
		    if(Configuration::get_instance()->read("product_version") !== PRODUCT_VERSION)
		    {
		    	die();
		    }
			$this->dispatch("minutecron/mapping");


			if(file_exists("../".CACHE_DIR."/cron/lock-minute.txt"))
			{
				$file_creation_time=filemtime("../".CACHE_DIR."/cron/lock-minute.txt");

				if(date('d',$file_creation_time) != date('d',time()))
				unlink("../".CACHE_DIR."/cron/lock-minute.txt");
			}


			$fp = fopen("../".CACHE_DIR."/cron/lock-minute.txt", "a+");

			if(flock($fp, LOCK_EX | LOCK_NB))
			{
				$cron_running_time=date('Y-m-d-H:i:s',time());

				$appendstring="Locked At : ".$cron_running_time." - Minute Data Cron == ";



			  set_time_limit(0);
			  $success_time_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET status =? WHERE task=?",array(0,'minutecron_success_time'));


			 $query_limit=Configuration::get_instance()->read('db_query_execution_limit');



			 $category_targeting_enabled=$this->get_addon_status('category-targeting_enabled');

			 $cpa_enabled=$this->get_addon_status('cpa_enabled');
			 $cpm_enabled=$this->get_addon_status('cpm_enabled');
			 $cpd_enabled=$this->get_addon_status('sponsored_enabled');
			 $html_enabled=$this->get_addon_status('html_enabled');
			 $pop_enabled=$this->get_addon_status('pop-ads_enabled');
			 $cpv_enabled=$this->get_addon_status('video-ads_enabled');
			 $cpp_enabled=$this->get_addon_status('cpp_enabled');
			 $referral_enabled=$this->get_addon_status('referral_enabled');




			 if($cpm_enabled ==1 || $cpm_enabled ==0)
			 $cpm_enabled=1;

			 if($cpa_enabled ==1 || $cpa_enabled ==0)
			 $cpa_enabled=1;

			 if($cpd_enabled ==1 || $cpd_enabled ==0)
			 $cpd_enabled=1;

			 if($html_enabled ==1 || $html_enabled ==0)
			 $html_enabled=1;

			 if($pop_enabled ==1 || $pop_enabled ==0)
			 $pop_enabled=1;

			 if($cpv_enabled ==1 || $cpv_enabled ==0)
			 $cpv_enabled=1;

			 if($cpp_enabled ==1 || $cpp_enabled ==0)
			 $cpp_enabled=1;

			 if($referral_enabled ==1 || $referral_enabled ==0)
			 $referral_enabled=1;



			 $adv_ref_enabled=0;
			 $pub_ref_enabled=0;


			 $cpmrefstring1='';
			 $cpmrefstring2='';

			 $arefperc=0;
			 $prefperc=0;

			 if($referral_enabled ==1)
			 {
			  	  $adv_ref_enabled=Configuration::get_instance()->read('advertiser_referral_enabled');
				  $pub_ref_enabled=Configuration::get_instance()->read('publisher_referral_enabled');


				  if($adv_ref_enabled ==1)
				  $arefperc=Configuration::get_instance()->read('advertiser_referral_profit_percentage');

				  if($pub_ref_enabled ==1)
				  $prefperc=Configuration::get_instance()->read('publisher_referral_profit_percentage');


				  if($cpm_enabled ==1)
				  {
				  	  $cpmrefstring1.=',`cpm_referral`';
				      $cpmrefstring2.=',?';
				  }

			 	  if($html_enabled ==1)
				  {
				  	  $cpmrefstring1.=',`html_referral`';
				      $cpmrefstring2.=',?';
				  }

			 	  if($pop_enabled ==1)
				  {
				  	  $cpmrefstring1.=',`pop_referral`';
				      $cpmrefstring2.=',?';
				  }

			 	  if($cpv_enabled ==1)
				  {
				  	  $cpmrefstring1.=',`cpv_referral`';
				      $cpmrefstring2.=',?';
				  }

			 	  if($cpp_enabled ==1)
				  {
				  	  $cpmrefstring1.=',`cpp_referral`';
				      $cpmrefstring2.=',?';
				  }
			  }



			  $siddata1='';
			  $siddata2='';
			  if($category_targeting_enabled ==1)
			  {
			  	$siddata1=',`sid`';
			  	$siddata2=',?';
			  }



			  $cpm_insert1="";
			  $cpm_insert2="";
			  $select_data="";





			  if($cpm_enabled ==1)
			  {
			  	  $select_data.=" ,COALESCE(sum(cpm_impression),0) as cpm_impression,COALESCE(sum(cpm_spend),0) as cpm_spend,COALESCE(sum(cpm_profit),0) as cpm_profit ";

				  $cpm_insert1.=" ,`cpm_impression`,`cpm_spend`,`cpm_profit` ";
				  $cpm_insert2.=" ,?,?,? ";
			  }


			  if($html_enabled ==1)
			  {
			  	  $select_data.=" ,COALESCE(sum(html_impression),0) as html_impression,COALESCE(sum(html_profit),0) as html_profit ";

				  $cpm_insert1.=" ,`html_impression`,`html_profit` ";
				  $cpm_insert2.=" ,?,? ";
			  }

			  if($pop_enabled ==1)
			  {
			  	  $select_data.=" ,COALESCE(sum(pop_impression),0) as pop_impression,COALESCE(sum(pop_spend),0) as pop_spend,COALESCE(sum(pop_profit),0) as pop_profit ";

				  $cpm_insert1.=" ,`pop_impression`,`pop_spend`,`pop_profit` ";
				  $cpm_insert2.=" ,?,?,? ";
			  }


			  if($cpa_enabled ==1)
			  {
			  	  $select_data.=" ,COALESCE(sum(cpa_impression),0) as cpa_impression ";

				  $cpm_insert1.=" ,`cpa_impression` ";
				  $cpm_insert2.=" ,? ";
			  }

			  if($cpd_enabled ==1)
			  {
			  	  $select_data.=" ,COALESCE(sum(cpd_impression),0) as cpd_impression,COALESCE(cpd_spend,0) as cpd_spend,COALESCE(cpd_profit,0) as cpd_profit ";

				  $cpm_insert1.=" ,`sponsored_impression`,`sponsored_spend`,`sponsored_profit` ";
				  $cpm_insert2.=" ,?,?,? ";
			  }


			  if($cpv_enabled ==1)
			  {
			  	  $select_data.=" ,COALESCE(sum(cpv_impression),0) as cpv_impression,COALESCE(sum(cpv_spend),0) as cpv_spend,COALESCE(sum(cpv_profit),0) as cpv_profit ";

				  $cpm_insert1.=" ,`cpv_impression`,`cpv_spend`,`cpv_profit` ";
				  $cpm_insert2.=" ,?,?,? ";
			  }


			  if($cpp_enabled ==1)
			  {
			  	  $select_data.=" ,COALESCE(sum(cpp_impression),0) as cpp_impression,COALESCE(sum(cpp_spend),0) as cpp_spend,COALESCE(sum(cpp_profit),0) as cpp_profit ";

				  $cpm_insert1.=" ,`cpp_impression`,`cpp_spend`,`cpp_profit` ";
				  $cpm_insert2.=" ,?,?,? ";
			  }




			  $tending =date("Y",time());
			  $tending.=date("m",time());
			  $tending.=date("d",time());
			  $tending.=date("H",time());

			  $tendingnew=$tending;

			  $tending=$this->get_previous_hour($tending);


			   $timeminus=time()-60;

			   $current_minute =date("Y",$timeminus);
			   $current_minute.=date("m",$timeminus);
			   $current_minute.=date("d",$timeminus);
			   $current_minute.=date("H",$timeminus);
			   $current_minute.=date("i",$timeminus);   // Get Previous Minute



			  $mapping_update_time=$tending.'59';
			  $mapping_update_timeDB=$db->read_single_column("SELECT time FROM ".TABLE_PREFIX."statistics_updation WHERE task=?",array('mapping_updation_minute'));


			  if($mapping_update_timeDB < $current_minute)  //$mapping_update_time
		  	  echo "<br>LAST MINUTE MAPPINGS TABLE UPDATION NOT COMPLETED";
			  else
			  {
				$timearray=array();
				$tables=$db->execute_query("SHOW TABLES LIKE '".TABLE_PREFIX."impression_hourly_%'");

				while($tablesdata=$tables->fetch_array())
				{
					$tablename=$tablesdata[0];

					$tablenamearray=explode('_',$tablename);

					$timevalue=$tablenamearray[count($tablenamearray)-1];

					if($timevalue <= $tendingnew)           // <= Is used for current hour data building |  < Is used for data building upto last hour data
					$timearray[]=$timevalue;
				}

				array_multisort($timearray);

				$tablecount=count($timearray);


				/////////////////////////////////////////////////////////


				echo "<br><br><strong>Impression Minute Statistics</strong><br>";


				$supdation_res=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."statistics_updation WHERE task=?",array('impression_updation_minute'));

				if($supdation_res->error !="")
				echo "<br>Data not got from ".TABLE_PREFIX."statistics_updation table<br>".$supdation_res->get_sql();
				else
				{
					$st_update_row=$supdation_res->fetch_assoc();
					$start_time=$st_update_row['time'];

					if($start_time >= $current_minute)
					echo "<br>BREAKING - LAST MINUTE STATISTICS IS COMPLETE";
					else
					{
					   $start_time_split=0;
					   $minute='00';
					   $first_running=0;
					   $ending_time="";

					   if($start_time ==0)
					   $first_running=1;


					   $timearraynew=array();


					   if($start_time ==0 && $tablecount >0)
					   {
					   	   $timearraynew=$timearray;
					   	   $start_time=$timearraynew[0];
					   }
					   else if($start_time >0 && $tablecount >0)
					   {
						   $start_time_split=substr($start_time,0,-2);
						   $minute=substr($start_time,10,2);

					   	   foreach($timearray as $key1=>$value1)
					   	   {
					   	   	   if($value1 > $start_time_split || ($value1 == $start_time_split && $minute !=59))
					   	   	   $timearraynew[]=$value1;
					   	   }


							$year=substr($start_time,0,4);
							$month=substr($start_time,4,2);
							$day=substr($start_time,6,2);
							$hour=substr($start_time,8,2);
							$minute0=substr($start_time,10,2);

							$timedata=mktime($hour,$minute0+1,'0',$month,$day,$year);

							$year1=date("Y",$timedata);
							$month1=date("m",$timedata);
							$day1=date("d",$timedata);
							$hour1=date("H",$timedata);
							$minute1=date("i",$timedata);

							$start_time=$year1.$month1.$day1.$hour1.$minute1;
					   }

					   echo "<br>Currently building data for ".$start_time." - ".$current_minute;

						$failed=0;
						$valuetime=0;
						$count=1;

						$tablecount=count($timearraynew);


				foreach($timearraynew as $keytime=>$valuetime)
				{

					if($first_running ==1 && $valuetime == $tendingnew)
					{
						$condition_string=" WHERE minute_time >=".$valuetime."00 AND minute_time <=".$current_minute." ";
						$ending_time=$current_minute;
					}
					else if($valuetime != $tendingnew && $valuetime != $start_time_split)  // Not last hour and not partial hour
					{
						$condition_string=" WHERE time =".$valuetime." ";
						$ending_time=$valuetime.'59';
					}
					else if($valuetime == $tendingnew)
					{
						$condition_string=" WHERE minute_time >".$valuetime.$minute." AND minute_time <=".$current_minute." ";
						$ending_time=$current_minute;
					}
					else //if($valuetime == $start_time_split)
					{
						$condition_string=" WHERE minute_time >".$valuetime.$minute." AND minute_time <=".$valuetime."59 ";
						$ending_time=$valuetime.'59';
					}


					$dataresult=$db->execute_query("SELECT uid,aid,kid,time,country,source,COALESCE(sum(cpc_impression),0) as cpc_impression,ruid,rpid ".$select_data." FROM ".TABLE_PREFIX."impression_hourly_".$valuetime." ".$condition_string." GROUP BY country,uid,aid,kid,rpid,time");

					if($dataresult->error !="")
					{
						echo "<br>Data retrieval failed from ".TABLE_PREFIX."impression_hourly_".$valuetime." table<br>".$dataresult->get_sql();
						break;
					}


					$dataresultcount=intval($dataresult->get_num_records());

					$insertresultcount=0;

					$advarray=array();



					$insertcount=0;
					$valuestring="";



					$advarraybatch=array();   // For Batch Insertion
					$iii=0;                   // For Batch Insertion
					$iiii=0;                  // For Batch Insertion


					while($content=$dataresult->fetch_assoc())
					{
						$rid=0;
						$prid=0;

						$cpm_referral=0;
						$html_referral=0;
						$pop_referral=0;
						$cpv_referral=0;
						$cpp_referral=0;


						$adv_cpm_referral=0;
						$adv_pop_referral=0;
						$adv_cpv_referral=0;
						$adv_cpp_referral=0;

						$pub_cpm_referral=0;
						$pub_html_referral=0;
						$pub_pop_referral=0;
						$pub_cpv_referral=0;
						$pub_cpp_referral=0;

						$adv_referral_cpm=0;
						$pub_referral_cpm=0;

						$pub_referral_html=0;

						$adv_referral_pop=0;
						$pub_referral_pop=0;

						$adv_referral_cpv=0;
						$pub_referral_cpv=0;

						$adv_referral_cpp=0;
						$pub_referral_cpp=0;

						if($referral_enabled ==1)
						{
							if($cpm_enabled ==1)
							{
								$adv_cpm_referral=$content['cpm_spend'];
								$pub_cpm_referral=$content['cpm_profit'];
							}

							if($html_enabled ==1)
							$pub_html_referral=$content['html_profit'];

							if($pop_enabled ==1)
							{
								$adv_pop_referral=$content['pop_spend'];
								$pub_pop_referral=$content['pop_profit'];
							}

							if($cpv_enabled ==1)
							{
								$adv_cpv_referral=$content['cpv_spend'];
								$pub_cpv_referral=$content['cpv_profit'];
							}

							if($cpp_enabled ==1)
							{
								$adv_cpp_referral=$content['cpp_spend'];
								$pub_cpp_referral=$content['cpp_profit'];
							}

							if($adv_ref_enabled ==1 && ($adv_cpm_referral >0 || $adv_pop_referral >0 || $adv_cpv_referral >0 || $adv_cpp_referral >0))
							{
								$rid=$content['ruid'];

								if($rid >0)
								{
									$datacontent=$this->get_referral_user_active($rid,1);

									$rid			= $datacontent[0];
									$adv_ref_perc	= $datacontent[1];
								}


								if($rid >0)
								{
									if($adv_ref_perc > 0)
									$arefperc=$adv_ref_perc;
								}



								if($adv_cpm_referral >0 && $rid >0)
								$adv_referral_cpm=$adv_cpm_referral*$arefperc/100;

								if($adv_pop_referral >0 && $rid >0)
								$adv_referral_pop=$adv_pop_referral*$arefperc/100;

								if($adv_cpv_referral >0 && $rid >0)
								$adv_referral_cpv=$adv_cpv_referral*$arefperc/100;

								if($adv_cpp_referral >0 && $rid >0)
								$adv_referral_cpp=$adv_cpp_referral*$arefperc/100;
							}

							if($pub_ref_enabled ==1 && ($pub_cpm_referral >0 || $pub_html_referral >0 || $pub_pop_referral >0 || $pub_cpv_referral >0 || $pub_cpp_referral >0))
							{
								$prid=$content['rpid'];

								if($prid >0)
								{
									$datacontent=$this->get_referral_user_active($prid,2);

									$prid			= $datacontent[0];
									$pub_ref_perc	= $datacontent[1];
								}

								if($prid >0)
								{
									if($pub_ref_perc > 0)
									$prefperc=$pub_ref_perc;
								}


								if($pub_cpm_referral >0 && $prid >0)
								$pub_referral_cpm=$pub_cpm_referral*$prefperc/100;

								if($pub_html_referral >0 && $prid >0)
								$pub_referral_html=$pub_html_referral*$prefperc/100;

								if($pub_pop_referral >0 && $prid >0)
								$pub_referral_pop=$pub_pop_referral*$prefperc/100;

								if($pub_cpv_referral >0 && $prid >0)
								$pub_referral_cpv=$pub_cpv_referral*$prefperc/100;

								if($pub_cpp_referral >0 && $prid >0)
								$pub_referral_cpp=$pub_cpp_referral*$prefperc/100;
							}
						}


						$cpm_referral=$adv_referral_cpm+$pub_referral_cpm;
						$html_referral=$pub_referral_html;
						$pop_referral=$adv_referral_pop+$pub_referral_pop;
						$cpv_referral=$adv_referral_cpv+$pub_referral_cpv;
						$cpp_referral=$adv_referral_cpp+$pub_referral_cpp;



						if($valuestring !="")
						$valuestring.=',';


						$valuestring.="(?,?,?,?,?,?,?,?".$cpm_insert2." ".$cpmrefstring2.")";



						$advarray[]='';
						$advarray[]=$content['uid'];
						$advarray[]=$content['aid'];
						$advarray[]=$content['kid'];
						$advarray[]=$content['time'];
						$advarray[]=$content['cpc_impression'];
						$advarray[]=$content['country'];
						$advarray[]=$content['source'];

						if($cpm_enabled ==1)
						{
							$advarray[]=$content['cpm_impression'];
							$advarray[]=$content['cpm_spend'];
							$advarray[]=$content['cpm_profit'];
						}

						if($html_enabled ==1)
						{
							$advarray[]=$content['html_impression'];
							$advarray[]=$content['html_profit'];
						}

						if($pop_enabled ==1)
						{
							$advarray[]=$content['pop_impression'];
							$advarray[]=$content['pop_spend'];
							$advarray[]=$content['pop_profit'];
						}

						if($cpa_enabled ==1)
						$advarray[]=$content['cpa_impression'];

						if($cpd_enabled ==1)
						{
							$advarray[]=$content['cpd_impression'];
							$advarray[]=$content['cpd_spend'];
							$advarray[]=$content['cpd_profit'];
						}

						if($cpv_enabled ==1)
						{
							$advarray[]=$content['cpv_impression'];
							$advarray[]=$content['cpv_spend'];
							$advarray[]=$content['cpv_profit'];
						}


						if($cpp_enabled ==1)
						{
							$advarray[]=$content['cpp_impression'];
							$advarray[]=$content['cpp_spend'];
							$advarray[]=$content['cpp_profit'];
						}


						if($referral_enabled ==1)
						{
							if($cpm_enabled ==1)
							$advarray[]=$cpm_referral;

							if($html_enabled ==1)
							$advarray[]=$html_referral;

							if($pop_enabled ==1)
							$advarray[]=$pop_referral;

							if($cpv_enabled ==1)
							$advarray[]=$cpv_referral;

							if($cpp_enabled ==1)
							$advarray[]=$cpp_referral;
						}



						$insertcount++;


						/*********   For Batch Insertion   ********/

						$iiii++;

						if($iiii % $query_limit ==0)
						{
							$advarraybatch[$iii][0]=$advarray;
							$advarraybatch[$iii][1]=$valuestring;

							$advarray=array();

							$valuestring="";

							$iii++;
							$iiii=0;
						}

						/*********   For Batch Insertion   ********/


					}


					$dataresult->free_result();


					/*********   For Batch Insertion   ********/
					if($iiii >0)
					{
						$advarraybatch[$iii][0]=$advarray;
						$advarraybatch[$iii][1]=$valuestring;
					}

					/*********   For Batch Insertion   ********/



					$dataresultpub=$db->execute_query("SELECT pid,bid,sid,cpdid,country,source,time,COALESCE(sum(cpc_impression),0) as cpc_impression,ruid,rpid ".$select_data." FROM ".TABLE_PREFIX."impression_hourly_".$valuetime."  ".$condition_string." GROUP BY country,pid,sid,bid,cpdid,ruid,time");

					if($dataresultpub->error !="")
					{
						echo "<br>Data retrieval failed from ".TABLE_PREFIX."impression_hourly_".$valuetime." table<br>".$dataresultpub->get_sql();
						break;
					}



					$dataresultcountpub=intval($dataresultpub->get_num_records());

					$insertresultcountpub=0;

					$pubarray=array();

					$valuestringpub="";



					$pubarraybatch=array();   // For Batch Insertion
					$iii=0;                   // For Batch Insertion
					$iiii=0;                  // For Batch Insertion


					while($content=$dataresultpub->fetch_assoc())
					{
						$rid=0;
						$prid=0;

						$cpm_referral=0;
						$html_referral=0;
						$pop_referral=0;
						$cpv_referral=0;
						$cpp_referral=0;

						$adv_cpm_referral=0;
						$adv_pop_referral=0;
						$adv_cpv_referral=0;
						$adv_cpp_referral=0;

						$pub_cpm_referral=0;
						$pub_html_referral=0;
						$pub_pop_referral=0;
						$pub_cpv_referral=0;
						$pub_cpp_referral=0;

						$adv_referral_cpm=0;
						$pub_referral_cpm=0;

						$pub_referral_html=0;

						$adv_referral_pop=0;
						$pub_referral_pop=0;

						$adv_referral_cpv=0;
						$pub_referral_cpv=0;

						$adv_referral_cpp=0;
						$pub_referral_cpp=0;


						if($referral_enabled ==1)
						{
							if($cpm_enabled ==1)
							{
								$adv_cpm_referral=$content['cpm_spend'];
								$pub_cpm_referral=$content['cpm_profit'];
							}

							if($html_enabled ==1)
							$pub_html_referral=$content['html_profit'];

							if($pop_enabled ==1)
							{
								$adv_pop_referral=$content['pop_spend'];
								$pub_pop_referral=$content['pop_profit'];
							}

							if($cpv_enabled ==1)
							{
								$adv_cpv_referral=$content['cpv_spend'];
								$pub_cpv_referral=$content['cpv_profit'];
							}

							if($cpp_enabled ==1)
							{
								$adv_cpp_referral=$content['cpp_spend'];
								$pub_cpp_referral=$content['cpp_profit'];
							}



							if($adv_ref_enabled ==1 && ($adv_cpm_referral >0 || $adv_pop_referral >0 || $adv_cpv_referral >0 || $adv_cpp_referral >0))
							{
								$rid=$content['ruid'];

								if($rid >0)
								{
									$datacontent=$this->get_referral_user_active($rid,1);

									$rid			= $datacontent[0];
									$adv_ref_perc	= $datacontent[1];
								}


								if($rid >0)
								{
									if($adv_ref_perc > 0)
									$arefperc=$adv_ref_perc;
								}


								if($adv_cpm_referral >0 && $rid >0)
								$adv_referral_cpm=$adv_cpm_referral*$arefperc/100;

								if($adv_pop_referral >0 && $rid >0)
								$adv_referral_pop=$adv_pop_referral*$arefperc/100;

								if($adv_cpv_referral >0 && $rid >0)
								$adv_referral_cpv=$adv_cpv_referral*$arefperc/100;

								if($adv_cpp_referral >0 && $rid >0)
								$adv_referral_cpp=$adv_cpp_referral*$arefperc/100;
							}

							if($pub_ref_enabled ==1 && ($pub_cpm_referral >0 || $pub_html_referral >0 || $pub_pop_referral >0 || $pub_cpv_referral >0 || $pub_cpp_referral >0))
							{
								$prid=$content['rpid'];

								if($prid >0)
								{
									$datacontent=$this->get_referral_user_active($prid,2);

									$prid			= $datacontent[0];
									$pub_ref_perc	= $datacontent[1];
								}

								if($prid >0)
								{
									if($pub_ref_perc > 0)
									$prefperc=$pub_ref_perc;
								}


								if($pub_cpm_referral >0 && $prid >0)
								$pub_referral_cpm=$pub_cpm_referral*$prefperc/100;

								if($pub_html_referral >0 && $prid >0)
								$pub_referral_html=$pub_html_referral*$prefperc/100;

								if($pub_pop_referral >0 && $prid >0)
								$pub_referral_pop=$pub_pop_referral*$prefperc/100;

								if($pub_cpv_referral >0 && $prid >0)
								$pub_referral_cpv=$pub_cpv_referral*$prefperc/100;

								if($pub_cpp_referral >0 && $prid >0)
								$pub_referral_cpp=$pub_cpp_referral*$prefperc/100;
							}
						}

						$cpm_referral=$adv_referral_cpm+$pub_referral_cpm;
						$html_referral=$pub_referral_html;
						$pop_referral=$adv_referral_pop+$pub_referral_pop;
						$cpv_referral=$adv_referral_cpv+$pub_referral_cpv;
						$cpp_referral=$adv_referral_cpp+$pub_referral_cpp;


						if($valuestringpub !="")
						$valuestringpub.=',';


						$valuestringpub.="(?,?,?,?,?,?,?,?".$siddata2." ".$cpm_insert2." ".$cpmrefstring2.")";


						$pubarray[]='';
						$pubarray[]=$content['pid'];
						$pubarray[]=$content['bid'];
						$pubarray[]=$content['time'];
						$pubarray[]=$content['cpc_impression'];
						$pubarray[]=$content['country'];
						$pubarray[]=$content['source'];

						if($cpd_enabled ==1 && $content['cpd_impression'] > 0)
						$pubarray[]=$content['cpdid'];
						else
						$pubarray[]=0;


						if($category_targeting_enabled ==1)
						$pubarray[]=$content['sid'];


						if($cpm_enabled ==1)
						{
							$pubarray[]=$content['cpm_impression'];
							$pubarray[]=$content['cpm_spend'];
							$pubarray[]=$content['cpm_profit'];
						}

						if($html_enabled ==1)
						{
							$pubarray[]=$content['html_impression'];
							$pubarray[]=$content['html_profit'];
						}



						if($pop_enabled ==1)
						{
							$pubarray[]=$content['pop_impression'];
							$pubarray[]=$content['pop_spend'];
							$pubarray[]=$content['pop_profit'];
						}

						if($cpa_enabled ==1)
						$pubarray[]=$content['cpa_impression'];

						if($cpd_enabled ==1)
						{
							$pubarray[]=$content['cpd_impression'];
							$pubarray[]=$content['cpd_spend'];
							$pubarray[]=$content['cpd_profit'];
						}

						if($cpv_enabled ==1)
						{
							$pubarray[]=$content['cpv_impression'];
							$pubarray[]=$content['cpv_spend'];
							$pubarray[]=$content['cpv_profit'];

						}

						if($cpp_enabled ==1)
						{
							$pubarray[]=$content['cpp_impression'];
							$pubarray[]=$content['cpp_spend'];
							$pubarray[]=$content['cpp_profit'];
						}



						if($referral_enabled ==1)
						{
							if($cpm_enabled ==1)
							$pubarray[]=$cpm_referral;

							if($html_enabled ==1)
							$pubarray[]=$html_referral;

							if($pop_enabled ==1)
							$pubarray[]=$pop_referral;

							if($cpv_enabled ==1)
							$pubarray[]=$cpv_referral;

							if($cpp_enabled ==1)
							$pubarray[]=$cpp_referral;
						}




						$insertcount++;

						/*********   For Batch Insertion   ********/

						$iiii++;

						if($iiii % $query_limit ==0)
						{
							$pubarraybatch[$iii][0]=$pubarray;
							$pubarraybatch[$iii][1]=$valuestringpub;

							$pubarray=array();

							$valuestringpub="";

							$iii++;
							$iiii=0;
						}

						/*********   For Batch Insertion   ********/

					}


					$dataresultpub->free_result();




					/*********   For Batch Insertion   ********/
					if($iiii >0)
					{
						$pubarraybatch[$iii][0]=$pubarray;
						$pubarraybatch[$iii][1]=$valuestringpub;
					}
					/*********   For Batch Insertion   ********/


					$db->execute_query("BEGIN");

					if($insertcount >0)
					{
						foreach($advarraybatch as $advkey=>$advvalue)
						{
							$batchinsert=$db->execute_query("INSERT INTO ".TABLE_PREFIX."adv_impression_hourly_backup (`id`,`uid`,`aid`,`kid`,`time`,`cpc_impression`,`country`,`source`".$cpm_insert1." ".$cpmrefstring1.") VALUES ".$advvalue[1]." ",$advvalue[0]);

							if($batchinsert->error !="")
							{
								$failed=1;
								echo "<br>Data insertion to ".TABLE_PREFIX."adv_impression_hourly_backup failed<br>".$batchinsert->get_sql();
								break;
							}
							else
							$insertresultcount=$insertresultcount+$batchinsert->get_num_records();
						}


						if($failed ==0)
						{
							foreach($pubarraybatch as $pubkey=>$pubvalue)
							{
								$batchinsertpub=$db->execute_query("INSERT INTO ".TABLE_PREFIX."pub_impression_hourly_backup (`id`,`pid`,`bid`,`time`,`cpc_impression`,`country`,`source`,`cpd_target_id`".$siddata1." ".$cpm_insert1." ".$cpmrefstring1.") VALUES ".$pubvalue[1]." ",$pubvalue[0]);

								if($batchinsertpub->error !="")
								{
									$failed=1;
									echo "<br>Data insertion to ".TABLE_PREFIX."pub_impression_hourly_backup failed<br>".$batchinsertpub->get_sql();
									break;
								}
								else
								$insertresultcountpub=$insertresultcountpub+$batchinsertpub->get_num_records();
							}
						}
					}


					if($failed ==0 && $insertresultcount == $dataresultcount && $insertresultcountpub == $dataresultcountpub)
					{
						if($valuetime == $tendingnew)
						$lasttime = $current_minute;
						else
						$lasttime = $ending_time;


						$statupdation=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET time=?,status=? WHERE task=?",array($lasttime,2,"impression_updation_minute"));

						if($statupdation->error !="")
						{
							$failed=1;
						    echo "<br>Data updation to ".TABLE_PREFIX."statistics_updation failed<br>".$statupdation->get_sql();
						}
					}


					if($failed ==0 && $tablecount == $count && $ending_time < $current_minute)  // Used For no data in 2-3 hours.This time statistics updation upto last hour.
					{
						$statupdation1=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET time=?,status=? WHERE task=?",array($current_minute,2,"impression_updation_minute"));

						if($statupdation1->error !="")
						{
							$failed=1;
							echo "<br>Data updation to ".TABLE_PREFIX."statistics_updation failed<br>".$statupdation1->get_sql();
						}
					}


					if($failed ==1)
					{
						$db->execute_query("ROLLBACK");
						break;
					}
					else
					{
						$db->execute_query("COMMIT");
					}


					$count=$count+1;
				}



				if($failed ==0)
				{
					echo "<br>BREAKING - Successfully Updated Impression Statistics";

					if($tablecount ==0)
					$statupdation1=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET time=?,status=? WHERE task=?",array($current_minute,2,"impression_updation_minute"));
				}
			}
		}


		////////////////////////////////////////////////



		echo "<br><br><strong>Publisher Account Balance Updation</strong><br>";


		$supdation_res=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."statistics_updation WHERE task=?",array('account_balance_minute'));

		if($supdation_res->error !="")
		echo "<br>Data not got from ".TABLE_PREFIX."statistics_updation table<br>".$supdation_res->get_sql();
		else
		{
			$st_update_row=$supdation_res->fetch_assoc();
			$start_time=$st_update_row['time'];

			if($start_time >= $current_minute)
			echo "<br>BREAKING - LAST MINUTE DATA UPDATION IS COMPLETE";
			else
			{
			   $start_time_split=0;
			   $minute='00';
			   $first_running=0;
			   $ending_time="";

			   if($start_time ==0)
			   $first_running=1;


			   $timearraynew=array();

				   if($start_time ==0 && $tablecount >0)
				   {
					   $timearraynew=$timearray;
				  	   $start_time=$timearraynew[0];
				   }
				   else if($start_time >0 && $tablecount >0)
				   {
					   $start_time_split=substr($start_time,0,-2);
					   $minute=substr($start_time,10,2);

				   	   foreach($timearray as $key1=>$value1)
				   	   {
				   	   	   if($value1 > $start_time_split || ($value1 == $start_time_split && $minute !=59))
				   	   	   $timearraynew[]=$value1;
				   	   }

					   $year=substr($start_time,0,4);
					   $month=substr($start_time,4,2);
					   $day=substr($start_time,6,2);
					   $hour=substr($start_time,8,2);
					   $minute0=substr($start_time,10,2);

					   $timedata=mktime($hour,$minute0+1,'0',$month,$day,$year);

					   $year1=date("Y",$timedata);
					   $month1=date("m",$timedata);
					   $day1=date("d",$timedata);
					   $hour1=date("H",$timedata);
					   $minute1=date("i",$timedata);

					   $start_time=$year1.$month1.$day1.$hour1.$minute1;
				   }


				echo "<br>Currently building data for ".$start_time." - ".$current_minute;

				$failed=0;
				$valuetime=0;
				$count=1;

				$tablecount=count($timearraynew);



				$mapid_string="";

				$mapid_string=" AND aid >0 ";

				foreach($timearraynew as $keytime=>$valuetime)
				{
					if($first_running ==1 && $valuetime == $tendingnew)
					{
						$condition_string=" WHERE minute_time >=".$valuetime."00 AND minute_time <=".$current_minute." ".$mapid_string;
						$ending_time=$current_minute;
					}
					else if($valuetime != $tendingnew && $valuetime != $start_time_split)  // Not last hour and not partial hour
					{
						$condition_string=" WHERE time =".$valuetime." ".$mapid_string;
						$ending_time=$valuetime.'59';
					}
					else if($valuetime == $tendingnew)
					{
						$condition_string=" WHERE minute_time >".$valuetime.$minute." AND minute_time <=".$current_minute." ".$mapid_string;
						$ending_time=$current_minute;
					}
					else //if($valuetime == $start_time_split)
					{
						$condition_string=" WHERE minute_time >".$valuetime.$minute." AND minute_time <=".$valuetime."59 "." ".$mapid_string;
						$ending_time=$valuetime.'59';
					}


					$dataresultpub=$db->execute_query("SELECT pid ".$select_data." FROM ".TABLE_PREFIX."impression_hourly_".$valuetime." ".$condition_string." GROUP BY pid");

					if($dataresultpub->error !="")
					{
						echo "<br>Data retrieval failed from ".TABLE_PREFIX."impression_hourly_".$valuetime." table<br>".$dataresultpub->get_sql();
						break;
					}



					$updatestring="";
					$updatestring1="";

					$refarraybatch=array();   // For Batch Insertion


					while($content=$dataresultpub->fetch_assoc())
					{
						$totprofit=0;

						if($cpm_enabled ==1)
						$totprofit=$totprofit+$content['cpm_profit'];

						if($html_enabled ==1)
						$totprofit=$totprofit+$content['html_profit'];

						if($pop_enabled ==1)
						$totprofit=$totprofit+$content['pop_profit'];

						if($cpv_enabled ==1)
						$totprofit=$totprofit+$content['cpv_profit'];

						if($cpp_enabled ==1)
						$totprofit=$totprofit+$content['cpp_profit'];


						if($content['pid'] >0 && $totprofit >0)
						{
							$updatestring.=" WHEN ".$content['pid']." THEN pub_account_balance+".$totprofit." ";

							if($updatestring1 !="")
							$updatestring1.=",";

							$updatestring1.=$content['pid'];
						}


						/*********   For Batch Insertion   ********/
						$iiii++;

						if($iiii % $query_limit ==0)
						{
							///////////
							$refarraybatch[$iii][0]=$updatestring;
							$refarraybatch[$iii][1]=$updatestring1;

							$updatestring="";
							$updatestring1="";
							//////////

							$iii++;
							$iiii=0;
						}
						/*********   For Batch Insertion   ********/
					}


					$dataresultpub->free_result();




					/*********   For Batch Insertion   ********/
					if($iiii >0)
					{
						//////////
						$refarraybatch[$iii][0]=$updatestring;
						$refarraybatch[$iii][1]=$updatestring1;
						//////////
					}
					/*********   For Batch Insertion   ********/


					$db->execute_query("BEGIN");

					foreach($refarraybatch as $refkey=>$refvalue)
					{
						if($refvalue[0] !="" && $refvalue[1] !="")
						{
							$batchupdatepub=$db->execute_query("UPDATE ".TABLE_PREFIX."users SET pub_account_balance = (CASE id ".$refvalue[0]." END) WHERE id IN (".$refvalue[1].")");

							if($batchupdatepub->error !="")
							{
								$failed=1;
								echo "<br>Data updation to ".TABLE_PREFIX."users failed<br>".$batchupdatepub->get_sql();
								break;
							}
						}
					}


					if($failed ==0)
					{
						if($valuetime == $tendingnew)
						$lasttime = $current_minute;
						else
						$lasttime = $ending_time;

						$statupdation=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET time=?,status=? WHERE task=?",array($lasttime,2,"account_balance_minute"));

						if($statupdation->error !="")
						{
							$failed=1;
						    echo "<br>Data updation to ".TABLE_PREFIX."statistics_updation failed<br>".$statupdation->get_sql();
						}
					}

					if($failed ==0 && $tablecount == $count && $ending_time < $current_minute)  // Used For no data in 2-3 hours.This time statistics updation upto last hour.
					{
						$statupdation1=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET time=?,status=? WHERE task=?",array($current_minute,2,"account_balance_minute"));

						if($statupdation1->error !="")
						{
							$failed=1;
							echo "<br>Data updation to ".TABLE_PREFIX."statistics_updation failed<br>".$statupdation1->get_sql();
						}
					}


					if($failed ==1)
					{
						$db->execute_query("ROLLBACK");
						break;
					}
					else
					{
						$db->execute_query("COMMIT");
					}


					$count=$count+1;
			}

			if($failed ==0)
			{
				echo "<br>BREAKING - Successfully Updated Publisher Account Balance";

				if($tablecount ==0)
				$statupdation1=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET time=?,status=? WHERE task=?",array($current_minute,2,"account_balance_minute"));
			}
		}
	}




///////////////////////////////////////////////////////////////////////////////////////











	if($referral_enabled ==1)
	{
		echo "<br><br><strong>Referral Impression Hourly Statistics</strong><br>";


		$supdation_res=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."statistics_updation WHERE task=?",array('referral_data_minute'));

		if($supdation_res->error !="")
		echo "<br>Data not got from ".TABLE_PREFIX."statistics_updation table<br>".$supdation_res->get_sql();
		else
		{
			$st_update_row=$supdation_res->fetch_assoc();
			$start_time=$st_update_row['time'];

			if($start_time >= $current_minute)
			echo "<br>BREAKING - LAST MINUTE STATISTICS IS COMPLETE";
			else
			{
			   $start_time_split=0;
			   $minute='00';
			   $first_running=0;
			   $ending_time="";

			   if($start_time ==0)
			   $first_running=1;


			   $timearraynew=array();


			   if($start_time ==0 && $tablecount >0)
			   {
				   $timearraynew=$timearray;
			  	   $start_time=$timearraynew[0];
			   }
			   else if($start_time >0 && $tablecount >0)
			   {
					   $start_time_split=substr($start_time,0,-2);
					   $minute=substr($start_time,10,2);

				   	   foreach($timearray as $key1=>$value1)
				   	   {
				   	   	   if($value1 > $start_time_split || ($value1 == $start_time_split && $minute !=59))
				   	   	   $timearraynew[]=$value1;
				   	   }

					   $year=substr($start_time,0,4);
					   $month=substr($start_time,4,2);
					   $day=substr($start_time,6,2);
					   $hour=substr($start_time,8,2);
					   $minute0=substr($start_time,10,2);

					   $timedata=mktime($hour,$minute0+1,'0',$month,$day,$year);

					   $year1=date("Y",$timedata);
					   $month1=date("m",$timedata);
					   $day1=date("d",$timedata);
					   $hour1=date("H",$timedata);
					   $minute1=date("i",$timedata);

					   $start_time=$year1.$month1.$day1.$hour1.$minute1;
			   }


				echo "<br>Currently building data for ".$start_time." - ".$current_minute;

				$failed=0;
				$valuetime=0;
				$count=1;

				$tablecount=count($timearraynew);

				foreach($timearraynew as $keytime=>$valuetime)
				{
					if($first_running ==1 && $valuetime == $tendingnew)
					{
						$condition_string=" WHERE minute_time >=".$valuetime."00 AND minute_time <=".$current_minute." ";
						$ending_time=$current_minute;
					}
					else if($valuetime != $tendingnew && $valuetime != $start_time_split)  // Not last hour and not partial hour
					{
						$condition_string=" WHERE time =".$valuetime." ";
						$ending_time=$valuetime.'59';
					}
					else if($valuetime == $tendingnew)
					{
						$condition_string=" WHERE minute_time >".$valuetime.$minute." AND minute_time <=".$current_minute." ";
						$ending_time=$current_minute;
					}
					else //if($valuetime == $start_time_split)
					{
						$condition_string=" WHERE minute_time >".$valuetime.$minute." AND minute_time <=".$valuetime."59 ";
						$ending_time=$valuetime.'59';
					}



				$dataresult=$db->execute_query("SELECT ruid,time ".$select_data." FROM ".TABLE_PREFIX."impression_hourly_".$valuetime."  ".$condition_string."  GROUP BY ruid,time");

				if($dataresult->error !="")
				{
					echo "<br>Data retrieval failed from ".TABLE_PREFIX."impression_hourly_".$valuetime." table<br>".$dataresult->get_sql();
					break;
				}


				$dataresultpub=$db->execute_query("SELECT rpid,time ".$select_data." FROM ".TABLE_PREFIX."impression_hourly_".$valuetime."  ".$condition_string."  GROUP BY rpid,time");

				if($dataresultpub->error !="")
				{
					echo "<br>Data retrieval failed from ".TABLE_PREFIX."impression_hourly_".$valuetime." table<br>".$dataresultpub->get_sql();
					break;
				}


				$advarray=array();

				while($content=$dataresult->fetch_assoc())
				{
					$rid=0;
					$adv_referral=0;

					$adv_spend_sum=0;

					if($cpm_enabled ==1)
					$adv_spend_sum=$adv_spend_sum+$content['cpm_spend'];

					if($pop_enabled ==1)
					$adv_spend_sum=$adv_spend_sum+$content['pop_spend'];

					if($cpv_enabled ==1)
					$adv_spend_sum=$adv_spend_sum+$content['cpv_spend'];

					if($cpp_enabled ==1)
					$adv_spend_sum=$adv_spend_sum+$content['cpp_spend'];


					if($adv_spend_sum >0 && $adv_ref_enabled ==1)
					{
						$rid=$content['ruid'];

						if($rid >0)
						{
							$datacontent=$this->get_referral_user_active($rid,1);

							$rid			= $datacontent[0];
							$adv_ref_perc	= $datacontent[1];
						}

						if($rid >0)
						{
							if($adv_ref_perc > 0)
							$arefperc=$adv_ref_perc;
						}

						if($rid >0)
						$adv_referral=$adv_spend_sum*$arefperc/100;
					}


					if($adv_referral >0)
					{
						$kdata=$content['ruid'].":".$content['time'];

						$advarray[$kdata][0]=$content['ruid'];
						$advarray[$kdata][1]=$adv_referral;
						$advarray[$kdata][2]=0;
						$advarray[$kdata][3]=$content['time'];
					}
				}


				$dataresult->free_result();







				while($contentpub=$dataresultpub->fetch_assoc())
				{
					$prid=0;
					$pub_referral=0;

					$pub_profit_sum=0;

					if($cpm_enabled ==1)
					$pub_profit_sum=$pub_profit_sum+$contentpub['cpm_profit'];

					if($html_enabled ==1)
					$pub_profit_sum=$pub_profit_sum+$contentpub['html_profit'];

					if($pop_enabled ==1)
					$pub_profit_sum=$pub_profit_sum+$contentpub['pop_profit'];

					if($cpv_enabled ==1)
					$pub_profit_sum=$pub_profit_sum+$contentpub['cpv_profit'];

					if($cpp_enabled ==1)
					$pub_profit_sum=$pub_profit_sum+$contentpub['cpp_profit'];


					if($pub_profit_sum >0 && $pub_ref_enabled ==1)
					{
						$prid=$contentpub['rpid'];

						if($prid >0)
						{
							$datacontent=$this->get_referral_user_active($prid,2);

							$prid			= $datacontent[0];
							$pub_ref_perc	= $datacontent[1];
						}

						if($prid >0)
						{
							if($pub_ref_perc > 0)
							$prefperc=$pub_ref_perc;
						}

						if($prid >0)
						$pub_referral=$pub_profit_sum*$prefperc/100;
					}


					if($pub_referral >0)
					{
						$kdata=$contentpub['rpid'].":".$contentpub['time'];

						$advarray[$kdata][0]=$contentpub['rpid'];

						if(!isset($advarray[$kdata][1]))
						$advarray[$kdata][1]=0;

						$advarray[$kdata][2]=$pub_referral;
						$advarray[$kdata][3]=$contentpub['time'];
					}
				}

				$dataresultpub->free_result();


				$dataresultcount=intval(count($advarray));

				$insertresultcount=0;

				$insertcount=0;
				$valuestring="";
				$array=array();

				$arraybatch=array();   // For Batch Insertion
				$iii=0;                // For Batch Insertion
				$iiii=0;               // For Batch Insertion






				foreach($advarray as $keycontent=>$valuecontent)
				{
					if($valuestring !="")
					$valuestring.=',';

					$valuestring.="(?,?,?,?,?)";

					$array[]='';
					$array[]=$valuecontent[0];
					$array[]=$valuecontent[1];
					$array[]=$valuecontent[2];
					$array[]=$valuecontent[3];

					$insertcount++;

					/*********   For Batch Insertion   ********/

					$iiii++;

					if($iiii % $query_limit ==0)
					{
						$arraybatch[$iii][0]=$array;
						$arraybatch[$iii][1]=$valuestring;

						$array=array();

						$valuestring="";

						$iii++;
						$iiii=0;
					}

					/*********   For Batch Insertion   ********/
				}

				/*********   For Batch Insertion   ********/
				if($iiii >0)
				{
					$arraybatch[$iii][0]=$array;
					$arraybatch[$iii][1]=$valuestring;
				}
				/*********   For Batch Insertion   ********/


				$db->execute_query("BEGIN");

				if($insertcount >0)
				{
					foreach($arraybatch as $advkey=>$advvalue)
					{
						$batchinsert=$db->execute_query("INSERT INTO ".TABLE_PREFIX."referral_hourly_backup (`id`,`uid`,`adv_earning`,`pub_earning`,`time`) VALUES ".$advvalue[1]." ",$advvalue[0]);

						if($batchinsert->error !="")
						{
							$failed=1;
							echo "<br>Data insertion to ".TABLE_PREFIX."referral_hourly_backup failed<br>".$batchinsert->get_sql();
							break;
						}
						else
						$insertresultcount=$insertresultcount+$batchinsert->get_num_records();
					}
				}


				if($failed ==0 && $insertresultcount == $dataresultcount)
				{
					if($valuetime == $tendingnew)
					$lasttime = $current_minute;
					else
					$lasttime = $ending_time;


					$statupdation=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET time=?,status=? WHERE task=?",array($lasttime,2,"referral_data_minute"));

					if($statupdation->error !="")
					{
						$failed=1;
					    echo "<br>Data updation to ".TABLE_PREFIX."statistics_updation failed<br>".$statupdation->get_sql();
					}
				}

				if($failed ==0 && $tablecount == $count && $ending_time < $current_minute)  // Used For no data in 2-3 hours.This time statistics updation upto last hour.
				{
					$statupdation1=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET time=?,status=? WHERE task=?",array($current_minute,2,"referral_data_minute"));

					if($statupdation1->error !="")
					{
						$failed=1;
						echo "<br>Data updation to ".TABLE_PREFIX."statistics_updation failed<br>".$statupdation1->get_sql();
					}
				}

				if($failed ==1)
				{
					$db->execute_query("ROLLBACK");
					break;
				}
				else
				{
					$db->execute_query("COMMIT");
				}


				$count=$count+1;

			}

			if($failed ==0)
			{
				echo "<br>BREAKING - Successfully Updated Referral Statistics";

				if($tablecount ==0)
				$statupdation1=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET time=?,status=? WHERE task=?",array($current_minute,2,"referral_data_minute"));
			}
		}
	}


	//////////////////////////////////////////////////////////////////////////


				echo "<br><br><strong>Referral Account Balance Updation</strong><br>";

				$supdation_res=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."statistics_updation WHERE task=?",array('referral_balance_minute'));

				if($supdation_res->error !="")
				echo "<br>Data not got from ".TABLE_PREFIX."statistics_updation table<br>".$supdation_res->get_sql();
				else
				{

					$st_update_row=$supdation_res->fetch_assoc();
					$start_time=$st_update_row['time'];

					if($start_time >= $current_minute)
					echo "<br>BREAKING - LAST MINUTE DATA UPDATION IS COMPLETE";
					else
					{
					   $start_time_split=0;
					   $minute='00';
					   $first_running=0;
					   $ending_time="";

					   if($start_time ==0)
					   $first_running=1;


					   $timearraynew=array();



				   if($start_time ==0 && $tablecount >0)
				   {
				   		$timearraynew=$timearray;
				  	  	$start_time=$timearraynew[0];
				   }
				   else if($start_time >0 && $tablecount >0)
				   {
					    $start_time_split=substr($start_time,0,-2);
					    $minute=substr($start_time,10,2);

				   	    foreach($timearray as $key1=>$value1)
				   	    {
				   	   	   if($value1 > $start_time_split || ($value1 == $start_time_split && $minute !=59))
				   	   	   $timearraynew[]=$value1;
				   	    }

					    $year=substr($start_time,0,4);
					    $month=substr($start_time,4,2);
					    $day=substr($start_time,6,2);
					    $hour=substr($start_time,8,2);
					    $minute0=substr($start_time,10,2);

					    $timedata=mktime($hour,$minute0+1,'0',$month,$day,$year);

					    $year1=date("Y",$timedata);
					    $month1=date("m",$timedata);
					    $day1=date("d",$timedata);
					    $hour1=date("H",$timedata);
					    $minute1=date("i",$timedata);

					    $start_time=$year1.$month1.$day1.$hour1.$minute1;
				   }


				echo "<br>Currently building data for ".$start_time." - ".$current_minute;



				$failed=0;
				$valuetime=0;
				$count=1;

				$tablecount=count($timearraynew);


				$mapid_string="";
				$mapid_string=" AND aid >0 ";


				foreach($timearraynew as $keytime=>$valuetime)
				{
					if($first_running ==1 && $valuetime == $tendingnew)
					{
						$condition_string=" WHERE minute_time >=".$valuetime."00 AND minute_time <=".$current_minute." ".$mapid_string;
						$ending_time=$current_minute;
					}
					else if($valuetime != $tendingnew && $valuetime != $start_time_split)  // Not last hour and not partial hour
					{
						$condition_string=" WHERE time =".$valuetime." ".$mapid_string;
						$ending_time=$valuetime.'59';
					}
					else if($valuetime == $tendingnew)
					{
						$condition_string=" WHERE minute_time >".$valuetime.$minute." AND minute_time <=".$current_minute." ".$mapid_string;
						$ending_time=$current_minute;
					}
					else //if($valuetime == $start_time_split)
					{
						$condition_string=" WHERE minute_time >".$valuetime.$minute." AND minute_time <=".$valuetime."59 "." ".$mapid_string;
						$ending_time=$valuetime.'59';
					}




					$dataresult=$db->execute_query("SELECT ruid,time ".$select_data."  FROM ".TABLE_PREFIX."impression_hourly_".$valuetime." ".$condition_string." GROUP BY ruid");

					if($dataresult->error !="")
					{
						echo "<br>Data retrieval failed from ".TABLE_PREFIX."impression_hourly_".$valuetime." table<br>".$dataresult->get_sql();
						break;
					}


					$dataresultpub=$db->execute_query("SELECT rpid,time ".$select_data."  FROM ".TABLE_PREFIX."impression_hourly_".$valuetime." ".$condition_string." GROUP BY rpid");

					if($dataresultpub->error !="")
					{
						echo "<br>Data retrieval failed from ".TABLE_PREFIX."impression_hourly_".$valuetime." table<br>".$dataresultpub->get_sql();
						break;
					}


					$advarray=array();

					while($content=$dataresult->fetch_assoc())
					{
						$rid=0;
						$adv_referral=0;

						$adv_spend_sum=0;

						if($cpm_enabled ==1)
						$adv_spend_sum=$adv_spend_sum+$content['cpm_spend'];

						if($pop_enabled ==1)
						$adv_spend_sum=$adv_spend_sum+$content['pop_spend'];

						if($cpv_enabled ==1)
						$adv_spend_sum=$adv_spend_sum+$content['cpv_spend'];
						if($cpp_enabled ==1)
						$adv_spend_sum=$adv_spend_sum+$content['cpp_spend'];

						if($adv_spend_sum >0 && $adv_ref_enabled ==1)
						{
							$rid=$content['ruid'];

							if($rid >0)
							{
								$datacontent=$this->get_referral_user_active($rid,1);

								$rid			= $datacontent[0];
								$adv_ref_perc	= $datacontent[1];
							}

							if($rid >0)
							{
								if($adv_ref_perc > 0)
								$arefperc=$adv_ref_perc;
							}

							if($rid >0)
							$adv_referral=$adv_spend_sum*$arefperc/100;
						}


						if($adv_referral >0)
						{
							$kdata=$content['ruid'].":".$content['time'];

							$advarray[$kdata][0]=$content['ruid'];
							$advarray[$kdata][1]=$adv_referral;
							$advarray[$kdata][2]=0;
							$advarray[$kdata][3]=$content['time'];
						}

					}


					$dataresult->free_result();



					while($contentpub=$dataresultpub->fetch_assoc())
					{
						$prid=0;
						$pub_referral=0;

						$pub_profit_sum=0;

						if($cpm_enabled ==1)
						$pub_profit_sum=$pub_profit_sum+$contentpub['cpm_profit'];

						if($html_enabled ==1)
						$pub_profit_sum=$pub_profit_sum+$contentpub['html_profit'];

						if($pop_enabled ==1)
						$pub_profit_sum=$pub_profit_sum+$contentpub['pop_profit'];

						if($cpv_enabled ==1)
						$pub_profit_sum=$pub_profit_sum+$contentpub['cpv_profit'];
						if($cpp_enabled ==1)
						$pub_profit_sum=$pub_profit_sum+$contentpub['cpp_profit'];

						if($pub_profit_sum >0 && $pub_ref_enabled ==1)
						{
							$prid=$contentpub['rpid'];

							if($prid >0)
							{
								$datacontent=$this->get_referral_user_active($prid,2);

								$prid			= $datacontent[0];
								$pub_ref_perc	= $datacontent[1];
							}

							if($prid >0)
							{
								if($pub_ref_perc > 0)
								$prefperc=$pub_ref_perc;
							}

							if($prid >0)
							$pub_referral=$pub_profit_sum*$prefperc/100;
						}


						if($pub_referral >0)
						{
							$kdata=$contentpub['rpid'].":".$contentpub['time'];

							$advarray[$kdata][0]=$contentpub['rpid'];

							if(!isset($advarray[$kdata][1]))
							$advarray[$kdata][1]=0;

							$advarray[$kdata][2]=$pub_referral;
							$advarray[$kdata][3]=$contentpub['time'];
						}

					}


					$dataresultpub->free_result();


					$updatestring="";
					$updatestring1="";

					$iii=0;                   // For Batch Insertion
					$iiii=0;                  // For Batch Insertion


					$refarraybatch=array();   // For Batch Insertion


					foreach($advarray as $k1=>$v1)
					{
						$totprofit=$v1[1]+$v1[2];

						if($v1[0] >0 && $totprofit >0)
						{
							$updatestring.=" WHEN ".$v1[0]." THEN referral_balance+".$totprofit." ";

							if($updatestring1 !="")
							$updatestring1.=",";

							$updatestring1.=$v1[0];
						}

						/*********   For Batch Insertion   ********/

						$iiii++;

						if($iiii % $query_limit ==0)
						{
							///////////
							$refarraybatch[$iii][0]=$updatestring;
							$refarraybatch[$iii][1]=$updatestring1;

							$updatestring="";
							$updatestring1="";
							//////////

							$iii++;
							$iiii=0;
						}

						/*********   For Batch Insertion   ********/
					}

					/*********   For Batch Insertion   ********/
					if($iiii >0)
					{
						//////////
						$refarraybatch[$iii][0]=$updatestring;
						$refarraybatch[$iii][1]=$updatestring1;
						//////////
					}
					/*********   For Batch Insertion   ********/

					$db->execute_query("BEGIN");

					foreach($refarraybatch as $refkey=>$refvalue)
					{
						if($refvalue[0] !="" && $refvalue[1] !="")
						{
							$batchupdate=$db->execute_query("UPDATE ".TABLE_PREFIX."users SET referral_balance = (CASE id ".$refvalue[0]." END) WHERE id IN (".$refvalue[1].")");

							if($batchupdate->error !="")
							{
								$failed=1;
								echo "<br>Data updation to ".TABLE_PREFIX."users failed<br>".$batchupdate->get_sql();
								break;
							}
						}
					}


					if($failed ==0)
					{
						if($valuetime == $tendingnew)
						$lasttime = $current_minute;
						else
						$lasttime = $ending_time;

						$statupdation=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET time=?,status=? WHERE task=?",array($lasttime,2,"referral_balance_minute"));

						if($statupdation->error !="")
						{
							$failed=1;
						    echo "<br>Data updation to ".TABLE_PREFIX."statistics_updation failed<br>".$statupdation->get_sql();
						}
					}



					if($failed ==0 && $tablecount == $count && $ending_time < $current_minute)  // Used For no data in 2-3 hours.This time statistics updation upto last hour.
					{
						$statupdation1=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET time=?,status=? WHERE task=?",array($current_minute,2,"referral_balance_minute"));

						if($statupdation1->error !="")
						{
							$failed=1;
							echo "<br>Data updation to ".TABLE_PREFIX."statistics_updation failed<br>".$statupdation1->get_sql();
						}
					}

					if($failed ==1)
					{
						$db->execute_query("ROLLBACK");
						break;
					}
					else
					{
						$db->execute_query("COMMIT");
					}

					$count=$count+1;
				}

				if($failed ==0)
				{
					echo "<br>BREAKING - Successfully Updated Referral Account Balance";

					if($tablecount ==0)
					$statupdation1=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET time=?,status=? WHERE task=?",array($current_minute,2,"referral_balance_minute"));
				}
			}
		}
	 }
  }




	$referral_condition_string="";

	if($referral_enabled ==1)
	$referral_condition_string=" OR task='referral_balance_minute' OR task='referral_data_minute' ";



			$updationtime=$db->read_single_column("SELECT time FROM ".TABLE_PREFIX."statistics_updation WHERE task='impression_updation_minute' OR task='account_balance_minute' ".$referral_condition_string." ORDER BY time LIMIT 0,1");




			$updationhour=substr($updationtime,0,-2);
			$updationminute=substr($updationtime,10,2);


			$timearray=array();
			$timearraysub=array();

			$tables=$db->execute_query("SHOW TABLES LIKE '".TABLE_PREFIX."impression_hourly_%'");

			while($tablesdata=$tables->fetch_array())
			{
				$tablename=$tablesdata[0];

				$tablenamearray=explode('_',$tablename);

				$timevalue=$tablenamearray[count($tablenamearray)-1];

				if($timevalue < $tendingnew && ($timevalue < $updationhour || ($timevalue == $updationhour && $updationminute ==59)))
				$timearray[]=$timevalue;

				if(($timevalue == $updationhour || $timevalue == $tendingnew) && $updationminute < 59)
				$timearraysub[]=$timevalue;
			}

			array_multisort($timearray);



			foreach($timearray as $key=>$value)
			{
				$db->execute_query("DROP TABLE IF EXISTS `".TABLE_PREFIX."impression_hourly_".$value."`");
			}


			if(count($timearraysub) >0)
			{
				//Change minute_time <= ? to minute_time < ? for second cron running


				$db->execute_query("DELETE FROM `".TABLE_PREFIX."impression_hourly_".$timearraysub[0]."` WHERE minute_time < ?",array($timearraysub[0].$updationminute));


			}


			$success_time_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET time=?,status =? WHERE task=?",array($current_minute,2,'minutecron_success_time'));


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
