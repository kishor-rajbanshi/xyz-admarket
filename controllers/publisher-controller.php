<?php
include_once COMMON_DIR_PATH.'helpers'.DS."login-helper.php";
include_once COMMON_DIR_PATH.'helpers'.DS."utility-helper.php";

class PublisherController extends ApplicationController
{
	function before_execute()
	{
		parent::before_execute();
		if($this->get_action()=="statistics" || $this->get_action()=="add_site_filter" || $this->get_action()=="view_site_filters" || $this->get_action()=="delete_site_filter" || $this->get_action()=="update_site_filter" || $this->get_action()=="country")
		{
			if(!(LoginHelper::validate_user_login()))
			{
				$this->flash($this->get_message('login failed'), BASE,0);
			}
		}
	}



	function statistics_action()
	{
		$this->set_title($this->get_label('pub overall statistics'));


		$uid=$this->read_cookie_param(COOKIE_LOGINID);
		



		$db= DAL::get_instance();

		if($_POST)
		{
			$duration  = intval($this->read_post_param("duration"));
			$tab       = intval($this->read_post_param("tab"));
			$from_date = $this->read_post_param("from_date");
			$to_date   = $this->read_post_param("to_date");
		}
		else
		{
			$duration        = 1;

			$tab = 0;


			$from_date = "";
			$to_date   = "";
		}

		if($from_date != "" && $to_date == "")
		$to_date = date("d",time()).'/'.date("m",time()).'/'.date("Y",time());

		if($duration == 7 && ($from_date == "" || $to_date == ""))
		{
			$duration  = 1;

			$from_date = "";
			$to_date   = "";
		}


		$this->set_variable("from_date",$from_date);
		$this->set_variable("to_date",$to_date);


		if($duration == 0)
		$duration = 1;

		$timePeriod[] = $duration;

		if($duration == 7)
		{
			$timePeriod[] = $from_date;
			$timePeriod[] = $to_date;
		}


		//$timePeriod = array(1), $fromAdmin = 1, $pricing = -1 $pid=-1, $bid=0, $sid=0,  $report_type=0, $country = ''
		$dbResult = $this->get_publisher_statistics($timePeriod, 0, -1, $uid);
		$this->set_array("reportResult",$dbResult);


		$dbResultTimeperiod= $this->get_publisher_timeperiod_statistics($timePeriod, 0, -1, $uid);
		$this->set_array("reportResultTimeperiod",$dbResultTimeperiod);





		$this->set_variable("duration",$duration);
		$this->set_variable("uid",$uid);
		$this->set_variable("tab",$tab);


	}

	function country_action()
	{
		$db  = DAL::get_instance();
		$uid = $this->read_cookie_param(COOKIE_LOGINID);

		$duration  = $this->read_page_param(1);
		$from_date = $this->read_page_param(2);
		$to_date   = $this->read_page_param(3);

		$from_date = str_replace('-','/',$from_date);
 		$to_date   = str_replace('-','/',$to_date);


		if($from_date != "" && $to_date == "")
		$to_date = date("d",time()).'/'.date("m",time()).'/'.date("Y",time());

		if($duration == 7 && ($from_date == "" || $to_date == ""))
		{
			$duration  = 1;

			$from_date = "";
			$to_date   = "";
		}


		if($duration == 0)
		$duration = 1;

		$timePeriod[] = $duration;

		if($duration == 7)
		{
			$timePeriod[] = $from_date;
			$timePeriod[] = $to_date;
		}



		//$timePeriod = array(1), $fromAdmin = 1, $pricing = -1 $pid=-1, $bid=0, $sid=0,  $report_type=0, $sorting=0, $country = ''
		$dbResult = $this->get_publisher_statistics($timePeriod, 0, -1, $uid, 0, 0, 1);
		$this->set_array("reportResult",$dbResult);



		$this->set_variable("from_date",$from_date);
		$this->set_variable("to_date",$to_date);
		$this->set_variable("duration",$duration);
		$this->set_variable("uid",$uid);
	}

	function add_site_filter_action()
	{
			$this->disable_notice_area();

			$db = DAL::get_instance();
			$site = $this->read_post_param('site');
			$uid=$this->read_cookie_param(COOKIE_LOGINID);

			$site1 = substr($site,0,7);
			$site2 = substr($site,0,8);
			$site3 = substr($site,0,4);

			$error = 0;

			if($site1 == "http://")
			$site  = str_replace($site1,"",$site);

			if($site2 == "https://")
			$site  = str_replace($site2,"",$site);

			if($site3 == "www.")
			$site  = str_replace($site3,"",$site);

			if($site == "")
			{
					$error      = 1;
					$error_code = 1;
			}
			else if(!UtilityHelper::is_valid_domain($site))
			{
					$error      = 1;
					$error_code = 2;
			}
			else
			{
					$site_json = $db->read_single_column("select restricted_sites from " . TABLE_PREFIX . "users where id=?",array ($uid));

					if($site_json != "")
					$site_array = json_decode($site_json);
					else
					$site_array = array ();

					if(!in_array($site,$site_array))
					{
							array_push($site_array,$site);

							$value = $db->execute_query("UPDATE " . TABLE_PREFIX . "users SET restricted_sites=? WHERE id=?",array (json_encode($site_array),$uid));

							if($value->error != "")
							{
									$error      = 1;
									$error_code = 3;
							}
					}
					else
					{
							$error      = 1;
							$error_code = 4;
					}
			}

			if($error == 1)
			{
					$res_array = array("error"=>$error, "error_code"=>$error_code);
					echo json_encode($res_array);
					exit;
			}
			else
			{
					$this->set_array("sites", $site_array,array(),1);
			}
	}


	function view_site_filters_action()
	{
			$this->set_title($this->get_label('manage restricted sites'));

			$uid=$this->read_cookie_param(COOKIE_LOGINID);
			$db= DAL::get_instance();

			$sites_json  = $db->read_single_column("select restricted_sites from " . TABLE_PREFIX . "users where id=?",array($uid));

			$sites_array = json_decode($sites_json);

			if(isset($sites_array) && count($sites_array) > 0)
			$this->set_array("sites", $sites_array,array(),1);
	}

	function update_site_filter_action()
	{
			$db        = DAL::get_instance();
			$sid       = $this->read_post_param('index');
			$site      = $this->read_post_param('new_value');
			$site_old  = $this->read_post_param('old_value');
			$uid       = $this->read_cookie_param(COOKIE_LOGINID);

			$sites_json  = $db->read_single_column("select restricted_sites from " . TABLE_PREFIX . "users where id=?",array($uid));
			$sites_array = json_decode($sites_json);

			if(!UtilityHelper::is_valid_domain($site))
			{
					$res_array = array("success"=>0,"error_code"=>1);
			}
			else if(in_array($site,$sites_array))
			{
					$res_array = array("success"=>0,"error_code"=>3);
			}
			else if($sites_array[$sid] == $site_old && !in_array($site,$sites_array))
			{
					$sites_array[$sid] = $site;

					$res = $db->execute_query("UPDATE " . TABLE_PREFIX . "users SET  restricted_sites=? WHERE id=?",array(json_encode($sites_array),$uid));

					if($res->error == "")
					{
							$res_array = array("success"=>1);
					}
			}
			else
			{
					$res_array=array("success"=>0, "error_code"=>2);
			}

			echo json_encode($res_array);
			exit;
	}

	function delete_site_filter_action()
	{
			$db 				 = DAL::get_instance();
			$sid 				 = $this->read_post_param('index');
			$uid 				 = $this->read_cookie_param(COOKIE_LOGINID);

			$sites_json  = $db->read_single_column("select restricted_sites from " . TABLE_PREFIX . "users where id=?",array($uid));
			$sites_array = json_decode($sites_json);

			unset($sites_array[$sid]);
			$site_new = array();

			foreach($sites_array as $value)
			{
					$site_new[] = $value;
			}

			$siteCount = count($site_new);

			$deleteQuery = $db->execute_query("UPDATE " . TABLE_PREFIX . "users SET  restricted_sites=? WHERE id=?",array(json_encode($site_new),$uid));

			if($deleteQuery->get_error() == "")
			$success = 1;
			else
			$success=0;

			$res_array = array("success" => $success, "id" => $sid, "siteCount" => $siteCount);
			echo json_encode($res_array);
			exit;
	}




};
