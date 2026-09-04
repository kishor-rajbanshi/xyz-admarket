<?php
include_once COMMON_DIR_PATH.'helpers'.DS."utility-helper.php";
class PushcronController extends ApplicationController
{
	function before_execute()
	{
		parent::before_execute();
		setlocale(LC_ALL,'en_US'); //In case of some languages,decimal places replace with ','.
	}

	function update_subscribers_action()
	{
		$db= DAL::get_instance();
		$res=$db->execute_query("SELECT id,push_ads_apikey,push_ads_appid FROM ".TABLE_PREFIX."sites WHERE status=? AND push_notification_service_enabled=?",array(1,1));
		$error_flag=0;
		
		$db->execute_query("BEGIN");
		while($result= $res->fetch_assoc())
		{
			$sid= $result['id'];
			$api_key= $result['push_ads_apikey'];
			$app_id= $result['push_ads_appid'];
				
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/apps/".$app_id);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
					'Authorization: Basic '.$api_key));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_HEADER, FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
				
			$response = curl_exec($ch); 
			$response = json_decode($response, true);
			curl_close($ch);
				
			$update_res=$db->execute_query("UPDATE ".TABLE_PREFIX."sites SET active_subscribers_count=? WHERE id=?",array($response['messageable_players'],$sid));
			if($update_res->error !='')
				$error_flag=1;
		}

		if($error_flag == 0)
		{
			$db->execute_query("COMMIT");
			echo "success";
			exit;
		}
		else
		{	$db->execute_query("ROLLBACK");
			echo "failed";
			exit;
		}
}


	
	function pushads_action()
	{
		$db= DAL::get_instance(); 
		$this->disable_notice_area();
		if(file_exists("../".CACHE_DIR."/cron/push-data.txt"))
		{
			$file_creation_time=filemtime("../".CACHE_DIR."/cron/push-data.txt");

			if(date('d',$file_creation_time) != date('d',time()))
				unlink("../".CACHE_DIR."/cron/push-data.txt");
		}

		if(!is_dir("../".CACHE_DIR."/cron"))
			mkdir("../".CACHE_DIR."/cron",0777);


			$fp = fopen("../".CACHE_DIR."/cron/push-data.txt", "a+");

			if(flock($fp, LOCK_EX | LOCK_NB))
			{


			$cpp_ads_enabled=$this->get_addon_status('cpp_enabled');
			if($cpp_ads_enabled == 1){
				$res=$db->execute_query("SELECT id,pid,catid,url,last_ad_serve_time,push_adserve_interval,push_ads_country_target,push_ads_apikey,push_ads_appid,active_subscribers_count FROM ".TABLE_PREFIX."sites WHERE status=? AND push_notification_service_enabled=? AND (last_ad_serve_time+(push_adserve_interval*60)) < ".time()." order by last_ad_serve_time ASC limit 25",array(1,1)); 
				
				$ad_status_check_string = "";
				$ad_status_check_string = " AND a.status =1 ";

				$ad_pause_status_check_string = "";
				$ad_pause_status_check_string = " AND a.pause_status =0 ";

				$user_status_check_string = "";
				$user_status_check_string = " AND a.user_status =1 ";

				$time_targeting_enabled=$this->get_addon_status('time-targeting_enabled');
				$date_condition='';
				$date_flag=0;
				if($time_targeting_enabled ==1)
				{
					$datetime=time();
					if(Configuration::get_instance()->read('date_filter_enabled') ==1 || Configuration::get_instance()->read('time_filter_enabled') ==1 || Configuration::get_instance()->read('day_filter_enabled') ==1)
					{



						$date_condition.=' AND ((a.date_filter IS NULL AND a.time_filter IS NULL AND a.day_filter IS NULL) OR (';


						if(Configuration::get_instance()->read('date_filter_enabled') ==1)
						{
							$date_condition.=' (a.date_filter =0 OR ((a.date_filter =1 OR a.date_filter =2) AND a.tmt_start_date <='.$datetime.' AND a.tmt_end_date >='.$datetime.')) ';
							$date_flag=1;
						}

						if(Configuration::get_instance()->read('time_filter_enabled') ==1)
						{
							$datehour=date('G',time());


							$dateperiod1='';
							if($datehour >=6 && $datehour < 9)
								$dateperiod1=' AND a.time_period1=1 ';
								else if($datehour >=9 && $datehour < 12)
									$dateperiod1=' AND a.time_period2=1 ';
									else if($datehour >=12 && $datehour < 16)
										$dateperiod1=' AND a.time_period3=1 ';
										else if($datehour >=16 && $datehour < 20)
											$dateperiod1=' AND a.time_period4=1 ';
											else if($datehour >=20 || $datehour < 6)
												$dateperiod1=' AND a.time_period5=1 ';

												if($date_flag ==1)
													$date_condition.=' AND ';

													$date_flag=1;

													$date_condition.=' (a.time_filter =0 OR (a.time_filter =1 AND ((a.tmt_start_time < '.$datehour.' AND a.tmt_end_time > '.$datehour.') OR (a.tmt_start_time > '.$datehour.' AND a.tmt_end_time = '.$datehour.') OR (a.tmt_start_time > '.$datehour.' AND a.tmt_end_time > '.$datehour.' AND a.tmt_start_time > a.tmt_end_time) OR a.tmt_start_time ='.$datehour.' OR a.tmt_end_time ='.$datehour.')) OR (a.time_filter =2 '.$dateperiod1.')) ';
						}


						if(Configuration::get_instance()->read('day_filter_enabled') ==1)
						{
							$dateday=date('w',time());

							if($dateday ==0)
								$dateday=7;

								$dateday1='';
								if($dateday ==7)
									$dateday1=' a.day_period7=1 ';
									else if($dateday ==1)
										$dateday1=' a.day_period1=1 ';
										else if($dateday ==2)
											$dateday1=' a.day_period2=1 ';
											else if($dateday ==3)
												$dateday1=' a.day_period3=1 ';
												else if($dateday ==4)
													$dateday1=' a.day_period4=1 ';
													else if($dateday ==5)
														$dateday1=' a.day_period5=1 ';
														else if($dateday ==6)
															$dateday1=' a.day_period6=1 ';


															if($date_flag ==1)
																$date_condition.=' AND ';

																$date_condition.=' (a.day_filter =0 OR (a.day_filter =1 AND ((a.tmt_start_day < '.$dateday.' AND a.tmt_end_day > '.$dateday.') OR (a.tmt_start_day > '.$dateday.' AND a.tmt_end_day ='.$dateday.') OR (a.tmt_start_day > '.$dateday.' AND a.tmt_end_day >'.$dateday.' AND a.tmt_start_day > a.tmt_end_day) OR a.tmt_start_day ='.$dateday.' OR a.tmt_end_day ='.$dateday.')) OR (a.day_filter =2 AND '.$dateday1.')) ';
						}



						$date_condition.='))';

					}
				}

				$ad_changing		= "ORDER BY a.last_display ASC";

				while($result=$res->fetch_assoc())
				{
					$country_string="";
					$site_country_string= $result['push_ads_country_target'];
					$catid=$result['catid'];
					$pid = $result['pid'];
					$sid= $result['id'];
					$push_api_key = $result['push_ads_apikey'];
					$site_app_id = $result['push_ads_appid'];

					$category_string='';
					$category_string1='';
					if($catid > 0)
					{
						$category_string=UtilityHelper::get_category_last_childs_display($catid);

						if($category_string !='')
						{
							$categoryStringArray = explode(',', $category_string);

							if(!in_array($catid,$categoryStringArray))
								$categoryStringArray[] = $catid;

								$category_string = implode(',', $categoryStringArray);

								$category_string=' ( cam.catid IN ('.$category_string.') OR cam.catid IS NULL ) AND';
						}
						else
							$category_string=' AND cam.catid IS NULL AND';

							$category_string1=' LEFT OUTER JOIN '.TABLE_PREFIX.'ad_category_mapping cam ON a.id = cam.aid ';
					}


					if($site_country_string != ""){
						$country_string=" a.id = g.aid AND g.country_code IN ('".$site_country_string."') AND";

					}
					$cpp_budget_str="";
					$subscribers_count = $result['active_subscribers_count'];
					$cpp_budget_str=' AND a.pricing_status=1 AND (a.daily_budget >0 AND (a.daily_budget - a.daily_budget_used) >= (a.default_rate * '.$subscribers_count.'))  AND (a.total_ad_budget >= (a.total_budget_used + (a.default_rate * '.$subscribers_count.'))) ';


					$push_ad_getting_query="SELECT a.id as aid,a.name,a.default_rate,a.title,a.description,a.click_url, a.banner ".$key_select.",a.uid as userid,a.display_type as dsp,a.type,a.display_url
			FROM ".TABLE_PREFIX."ad_geographic_mapping g,".TABLE_PREFIX."ads a
			".$category_string1."
			WHERE
			".$category_string."
			a.display_type=18
			AND a.type =18
			".$ad_status_check_string."
			".$ad_pause_status_check_string."
			".$user_status_check_string."
			".$cpp_budget_str."
			".$ad_changing."
			LIMIT 0,1";


					$ad_res=$db->execute_query($push_ad_getting_query);
               
					if($ad_res->num_records == 0)
						continue;
						else
						{
							$ad_result = $ad_res->fetch_assoc();
							$push_ad_head = $ad_result['title'];
							$push_ad_content = $ad_result['description'];
							$aid = $ad_result['aid'];
							$uid = $ad_result['userid'];
							$banner = $ad_result['banner'];
							$ad_type = $ad_result['display_type'];
							$click_url = $ad_result['click_url'];
							$default_rate= $ad_result['default_rate'];
							$bid= $db->read_single_column("select id from ".TABLE_PREFIX."adunit where sid=?",array($sid));
							$icon_url=BASE.DATA_DIR.'/notification_icons/'.$aid.'_'.$banner;
								
							$budget_minus = (($subscribers_count / 1000) * $default_rate);


							$content      = array(
									"en" => $push_ad_content
							);

							$head = array(
									"en" => $push_ad_head
							);
								
								
								
							$fields = array(
									'app_id' => $site_app_id,
									'included_segments' => array(
											'All'
									),
									'data' => array(
											"foo" => "bar"
									),
									'contents' => $content,
									'headings' => $head,
									'chrome_web_image'=>$icon_url,
									'url'=>$click_url,
                              						'ttl'=> '259200'
										

							);

							$fields = json_encode($fields);


							$ch = curl_init();
							curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
							curl_setopt($ch, CURLOPT_HTTPHEADER, array(
									'Content-Type: application/json; charset=utf-8',
									'Authorization: Basic '.$push_api_key
							));
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
							curl_setopt($ch, CURLOPT_HEADER, FALSE);
							curl_setopt($ch, CURLOPT_POST, TRUE);
							curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
							curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

							$response = curl_exec($ch);
							curl_close($ch);
			
							$data = json_decode($response, true);
							
					


					if($data['id']!='')
					{
						$notification_id = $data['id'];

						$db->execute_query("BEGIN");
						$failed=0;

						$time = time();

						$ad_budget_update=$db->execute_query("UPDATE ".TABLE_PREFIX."ads SET daily_budget_used=daily_budget_used+?,total_budget_used=total_budget_used+?,last_display=? WHERE id=?",array($budget_minus,$budget_minus,$time,$aid));
						if($ad_budget_update->error != "")
							$failed=1;

						if($failed == 0)
						$insert=$db->execute_query("INSERT INTO ".TABLE_PREFIX."notification_list (`notification_id`,`uid`,`aid`,`pub_id`,`sid`,`adcode_id`,`push_time`,`cpp_spend`,`cpp_impression`) values (?,?,?,?,?,?,?,?,?)",array($notification_id,$uid,$aid,$pid,$sid,$bid,$time,$budget_minus,$subscribers_count));
						if($insert->error != "")
							$failed=1;

						if($failed == 0)
						$set_success=$db->execute_query("UPDATE ".TABLE_PREFIX."sites SET push_api_error_msg=?,last_ad_serve_time=? WHERE id=?",array('',$time,$sid)); 

						if($set_success->error != "")
							$failed=1;

						if($failed ==0)
						{
							$db->execute_query("COMMIT");
						}
                                                else
						{
							$db->execute_query("ROLLBACK");

                	        echo "<br>Data insertion to ".TABLE_PREFIX."notification_list failed<br>".$insert->get_sql();
						}

					}
					else {
						$set_error=$db->execute_query("UPDATE ".TABLE_PREFIX."sites SET push_api_error_msg=? WHERE id=?",array($data['errors'][0],$sid));

					}
						
					}

				}

				$stat_fetch_interval = ceil($subscribers_count/1000);
              
              			
             
              
				
				$res_notification=$db->execute_query("SELECT id,uid,aid,notification_id,sid,pub_id,adcode_id,cpp_spend,cpp_impression FROM ".TABLE_PREFIX."notification_list WHERE completed_time=? AND push_time+".$stat_fetch_interval." < ?   order by id desc",array(0,time()));
              
				
				while($result_notify=$res_notification->fetch_assoc())
				{
					$list_id = $result_notify['id'];
					$notification_id= $result_notify['notification_id'];
						
					$sid_notification = $result_notify['sid'];
					$aid = $result_notify['aid'];
					$uid = $result_notify['uid'];
					$pub_id = $result_notify['pub_id'];
					$sid = $result_notify['sid'];
					$adcode_id = $result_notify['adcode_id'];
					$cpp_impression = $result_notify['cpp_impression'];
					$cpp_spend = $result_notify['cpp_spend'];
					$site_data=$db->execute_query("SELECT push_ads_appid,push_ads_apikey FROM ".TABLE_PREFIX."sites WHERE id=?",array($sid_notification));
					$site_data_result= $site_data->fetch_assoc();
                
					$push_api_key = $site_data_result['push_ads_apikey'];
					$site_app_id = $site_data_result['push_ads_appid'];

					$profit_percentage= $db->read_single_column("select cpp_profit_percentage from ".TABLE_PREFIX."users where id=?",array($pub_id));
             
					if($profit_percentage == 0)
                  	                $profit_percentage=Configuration::get_instance()->read('cpp_profit_percentage'); 
					
                
						
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications/".$notification_id."?app_id=".$site_app_id);
					curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
							'Authorization: Basic '.$push_api_key));
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					curl_setopt($ch, CURLOPT_HEADER, FALSE);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

					$response = curl_exec($ch);  
					$return = json_decode($response, true);
						
					curl_close($ch);


					$db->execute_query("BEGIN");
					$failed=0;
						
					
					$update=$db->execute_query("UPDATE ".TABLE_PREFIX."notification_list SET completed_time = ? WHERE id=?",array($return["completed_at"],$list_id));

					if($update->error != "")
					$failed=1;
					
					$current_day_time =date("Y",time());
					$current_day_time.=date("m",time());
					$current_day_time.=date("d",time());
                     
				
					$current_day_hour_time = $current_day_time.date("H",time()); 
                  	                $impTimeMinute=$current_day_hour_time.date("i",time());
                                        $impTimeSecond=$impTimeMinute.date("s",time());
                                        $profit= (($cpp_spend * $profit_percentage) / 100);  

                  	                if($failed == 0)
					$ins=$db->execute_query("INSERT INTO ".TABLE_PREFIX."impression_hourly_".$current_day_hour_time." (`uid`,`aid`,`pid`,`sid`,`bid`,`cpp_impression`,`cpp_spend`,`cpp_click`,`cpp_success`,`cpp_failed`,`cpp_profit`,`time`,`minute_time`,`second_time`) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?)",array($uid,$aid,$pub_id,$sid,$adcode_id,$cpp_impression,$cpp_spend,$return["converted"],$return["successful"],$return["failed"],$profit,$current_day_hour_time,$impTimeMinute,$impTimeSecond));

					if($ins->error != "")
					$failed=1;

					if($pub_id > 0 && $profit > 0 && $failed == 0)
					$pupdate=$db->execute_query("UPDATE ".TABLE_PREFIX."users SET pub_account_balance=pub_account_balance+? WHERE id=?",array($profit,$pub_id));
					
					if($pupdate->error != "")
					$failed=1;

				
					if($failed == 0 && $return["converted"] > 0)
					$daily_ins=$db->execute_query("INSERT INTO ".TABLE_PREFIX."dailyclicks (`uid`,`aid`,`kid`,`pid`,`bid`,`time`,`org_time`,`sid`,`cpp_click`,`click_type`) values (?,?,?,?,?,?,?,?,?,?)",array($uid,$aid,0,$pid,$adcode_id,$current_day_hour_time,time(),$sid,$return["converted"],18));
						
					if($daily_ins->error != "")
					$failed=1;
						
					if($failed == 0)
					$delete=$db->execute_query("DELETE FROM ".TABLE_PREFIX."notification_list WHERE id= ?",array($list_id));
					if($delete->error != "")
					$failed=1;


						if($failed ==0)
						{
							$db->execute_query("COMMIT");
						}
                                                else
						{
							$db->execute_query("ROLLBACK");

                	                                echo "<br>Push Notification table updations failed<br>";
						}

				}
					

			}
				echo "<br/>------Cron success------<br/>";
				}
			 else
			 {
			 echo "<br/>Another cron is already running<br/>";
			 }
			 fclose($fp);  

			exit;
	}


	
};
?>

