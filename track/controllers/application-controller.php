<?php
class ApplicationController extends Controller
{
	function before_execute()
	{
		date_default_timezone_set(Configuration::get_instance()->read('default_time_zone'));
	}

	function get_track_domain($fromAdDisplay = 1, $protocol = 0)
	{
			//$fromAdDisplay =>  0 for cpa/affiliate click & conversion tracking

			$trackDomain = Configuration::get_instance()->read('track_server_domains');

			if($trackDomain == "" && $fromAdDisplay == 0)
			$trackDomain = BASE;
			else if($trackDomain == "")
			{
					$displayDomains = Configuration::get_instance()->read('display_server_domains');

					if($displayDomains != "")
					{
							$serverAddress = $_SERVER['HTTP_HOST'];

							$serverAddress = str_replace("https://",'',$serverAddress);
							$serverAddress = str_replace("http://",'',$serverAddress);
							$serverAddress = str_replace("www.",'',$serverAddress);

							$displayDomainArray  = explode(',',$displayDomains);

							foreach($displayDomainArray as $displayKey => $displayValue)
							{
									$eachDisplayDomain = $displayValue;

									$eachDisplayDomain = str_replace("https://",'',$eachDisplayDomain);
									$eachDisplayDomain = str_replace("http://",'',$eachDisplayDomain);
									$eachDisplayDomain = str_replace("www.",'',$eachDisplayDomain);

									$eachDisplayDomainArray = explode("/",$eachDisplayDomain);

									if($eachDisplayDomainArray[0] == $serverAddress)
									{
											$trackDomain = $displayValue;
											break;
									}
							}
					}

					if($trackDomain == "")
					$trackDomain = BASE;
			}

			if($protocol == 0)
			{
				$trackDomain = str_replace('https://','',$trackDomain);
				$trackDomain = str_replace('http://','',$trackDomain);

				return "//".$trackDomain;
			}
			else 
			return $trackDomain;
	}



	function get_date_format($type,$year,$month="",$day="",$hour="")
	{
		$d=Configuration::get_instance()->read('day_format');
		$m=Configuration::get_instance()->read('month_format');
		$y=Configuration::get_instance()->read('year_format');
		$dp=Configuration::get_instance()->read('day_position');
		$ds=Configuration::get_instance()->read('day_separator');
		$hs=Configuration::get_instance()->read('time_separator');
		$hds=Configuration::get_instance()->read('hour_display_format');
		$h=Configuration::get_instance()->read('hour_format');


		$mfor=Configuration::get_instance()->read('minute_format');
		$sfor=Configuration::get_instance()->read('second_format');


        if($type==2)
        {
			$time=$year;


		$cday=date("j",$time);//9
		$cday1=date("d",$time);//09

		$cmonth0=date("n",$time);//2
		$cmonth=date("m",$time);//02
		$cmonth1=date("M",$time);//Feb

		$cyear=date("y",$time);//12
		$cyear1=date("Y",$time);//2012



		if($hds==12)
		{
			if($h=="HH")
				$chour=date("h",$time);
			else
				$chour=date("g",$time);
		}
		else if($hds==24)
		{
			if($h=="HH")
				$chour=date("H",$time);
			else
				$chour=date("G",$time);
		}





		$cminute=date("i",$time);
		if($mfor=="M")
		{
			if($cminute < 10)
			{
				$cminute=substr($cminute,-1);
			}
		}



		$csecond=date("s",$time);
		if($sfor=="S")
		{
			if($csecond < 10)
			{
				$csecond=substr($csecond,-1);
			}
		}




		$cformat=date("A",$time);






		$datestring="";


		if($d=='d')
		$datestring_day=$cday;
		else if($d=='D')
		$datestring_day=$cday1;


		if($m=='m')
		$daystring_month=$cmonth0;
		else if($m=='M')
		$daystring_month=$cmonth;
		else if($m=='Mon')
		$daystring_month=$cmonth1;



		if($y=='y')
		$daystring_year=$cyear;
		else if($y=='Y')
		$daystring_year=$cyear1;


		if($hds==12)
		{
			$daystring_time=" ".$chour.$hs.$cminute.$hs.$csecond." ".$cformat;
		}
		else if($hds==24)
		{
			$daystring_time=" ".$chour.$hs.$cminute.$hs.$csecond;
		}





		if($dp==1)
		{
			$datestring=$datestring_day.$ds.$daystring_month.$ds.$daystring_year.$daystring_time;
		}
		else if($dp==2)
		{
			$datestring=$daystring_month.$ds.$datestring_day.$ds.$daystring_year.$daystring_time;
		}

		return $datestring;


        }
		if($type==1)
		{

		$day_str="";
		$month_str="";
		$hour_str="";

		if($hour!="")
		{
		   $hour_str=" ".$hour;
		}


		if($day!="")
		{
			if($d=="d")
			{
				$day_first=substr($day,0,1);
				if($day_first==0)
				$day=substr($day,1,1);
			}


			$day_str=$day.$ds;

		}


		if($month!="")
		{
			if($m=="m")
			{
				$month_first=substr($month,0,1);
				if($month_first==0)
				$month=substr($month,1,1);
			}
			else if($m=="Mon")
			{
				if($month=="01")
				$month="Jan";
				else if($month=="02")
				$month="Feb";
				else if($month=="03")
				$month="Mar";
				else if($month=="04")
				$month="Apr";
				else if($month=="05")
				$month="May";
				else if($month=="06")
				$month="Jun";
				else if($month=="07")
				$month="Jul";
				else if($month=="08")
				$month="Aug";
				else if($month=="09")
				$month="Sep";
				else if($month=="10")
				$month="Oct";
				else if($month=="11")
				$month="Nov";
				else if($month=="12")
				$month="Dec";
			}



			$month_str=$month.$ds;


		}



		if($y=="y")
		{
		$year=substr($year,2,2);
		}


		if($dp==1)
		$d_format=$day_str.$month_str.$year.$hour_str;
		else if($dp==2)
		$d_format=$month_str.$day_str.$year.$hour_str;


		return $d_format;

		}


		if($type==3)
		{
			$time=$year;

			$cday=date("j",$time);//9
			$cday1=date("d",$time);//09

			$cmonth0=date("n",$time);//2
			$cmonth=date("m",$time);//02
			$cmonth1=date("M",$time);//Feb

			$cyear=date("y",$time);//12
			$cyear1=date("Y",$time);//2012


			//$chour=date("H",$time);
			//$cminute=date("i",$time);
			//$csecond=date("s",$time);
			//$cformat=date("A",$time);






			$datestring="";


			if($d=='d')
				$datestring_day=$cday;
			else if($d=='D')
				$datestring_day=$cday1;


			if($m=='m')
				$daystring_month=$cmonth0;
			else if($m=='M')
				$daystring_month=$cmonth;
			else if($m=='Mon')
				$daystring_month=$cmonth1;



			if($y=='y')
				$daystring_year=$cyear;
			else if($y=='Y')
				$daystring_year=$cyear1;


			//	if($hds==12)
				//	{
				//		$daystring_time=" ".$chour.$hs.$cminute.$hs.$csecond." ".$cformat;
				//	}
				//	else if($hds==24)
					//	{
					//		$daystring_time=" ".$chour.$hs.$cminute.$hs.$csecond;
					//	}

			$daystring_time="";



			if($dp==1)
			{
				$datestring=$datestring_day.$ds.$daystring_month.$ds.$daystring_year.$daystring_time;
			}
			else if($dp==2)
			{
				$datestring=$daystring_month.$ds.$datestring_day.$ds.$daystring_year.$daystring_time;
			}

			return $datestring;


		}






	}





	function get_number_format($value,$places=2)
	{
		$ts=Configuration::get_instance()->read('thousand_separator');
		$ds=Configuration::get_instance()->read('decimal_separator');
		$ds_places=Configuration::get_instance()->read('decimal_place');


		if($places==0)
		{
			$value=sprintf("%d",round($value,$ds_places));
			return number_format ($value,0,$ds,$ts);
		}
		else
		{
			$value=sprintf("%0.".$ds_places."F",round($value,$ds_places));
			return number_format ($value,$ds_places,$ds,$ts);
		}
	}


	function get_money_format($money,$places=2)
	{

		$money=self::get_number_format($money,$places);

		if(Configuration::get_instance()->read('currency_position')==1)
		$money=Configuration::get_instance()->read('currency_symbol')." ".$money;
		else
		$money=$money." ".Configuration::get_instance()->read('currency_symbol');

		return $money;

	}



																		function get_nexthour($current)
																		{
																		$year=substr($current,0,4);
																		$month=substr($current,4,2);
																		$day=substr($current,6,2);
																		$hour=substr($current,8,2);

																		$new_current=$year.$month.$day;


																		$first_letter=substr($day,0,1);
																		$second_letter=substr($day,1,1);

																				$mfirst_letter=substr($month,0,1);
																				$msecond_letter=substr($month,1,1);

																				$hfirst_letter=substr($hour,0,1);
																				$hsecond_letter=substr($hour,1,1);

																				if($hour =="23")
																				{
																				$current=$this->get_nextday($new_current)."00";

																				}
																					else if($hour=="09")
																					{
																					$current=$year.$month.$day."10";
																					}
	else if($hfirst_letter ==0)
	{
	$current=$year.$month.$day.$hfirst_letter.($hsecond_letter+1);
	}
	else if($hfirst_letter !=0)
	{
	$current=$year.$month.$day.($hour+1);

	}


	return $current;

	}



	function get_previous_hour($current)
	{

	$year=substr($current,0,4);
	$month=substr($current,4,2);
	$day=substr($current,6,2);
	$hour=substr($current,8,2);

	$new_current=$year.$month.$day;


	$first_letter=substr($day,0,1);
	$second_letter=substr($day,1,1);

	$mfirst_letter=substr($month,0,1);
	$msecond_letter=substr($month,1,1);

	$hfirst_letter=substr($hour,0,1);
	$hsecond_letter=substr($hour,1,1);

	if($hour =="00")
	{
	$current=$this->get_previous_day($new_current)."23";

	}

	else if($hour==10)
	{
	$current=$year.$month.$day."09";
	}
	else if($hfirst_letter ==0)
	{
	$current=$year.$month.$day.$hfirst_letter.($hsecond_letter-1);
	}
	else if($hfirst_letter !=0)
	{
	$current=$year.$month.$day.($hour-1);

	}


	return $current;
	}


	function get_previous_day($current)
	{

	$year=substr($current,0,4);
	$month=substr($current,4,2);
	$day=substr($current,6,2);

	$first_letter=substr($day,0,1);
	$second_letter=substr($day,1,1);

	$mfirst_letter=substr($month,0,1);
	$msecond_letter=substr($month,1,1);



	if($day==10)
	{
	$current=$year.$month."09";
	}
	else if($first_letter==0 && $day !='01')
	{

	$current=$year.$month.$first_letter.($second_letter-1);

	}
	else if($first_letter !=0 && $day !='01' && $day !=10)
	{
	$current=$year.$month.($day-1);

	}
	if($day =='01')
	{

	if($month=='02' || $month=='04' || $month=='06'  || $month=='08' || $month=='09' || $month=='11')
	{
	if($mfirst_letter==0 && $month !='11')
	$current=$year.$mfirst_letter.($month-1)."31";
	else
	$current=$year.($month-1)."31";

	}
	else if($month=='05' || $month=='07' || $month=='10' || $month=='12')
	{
	if($mfirst_letter==0)
	$current=$year.$mfirst_letter.($month-1)."30";
	else
	{
	if($month=='10')
	$current=$year."09"."30";
	else if($month=='12')
	$current=$year.($month-1)."30";

	}

	}
	else if($month=='01')
	{
	$current=($year-1)."12"."31";
	}
	else if($month=='03')
	{
	$mod=$year%4;

	if($mod ==0)    //leap year
	$current=$year."02"."29";
	else
	$current=$year."02"."28";


	}





	}

	return $current;

	}

	function get_previous_month($current)
	{
	$year=substr($current,0,4);
	$month=substr($current,4,2);

	$mfirst_letter=substr($month,0,1);
	$msecond_letter=substr($month,1,1);


	if($month !='01')
	{
	if($mfirst_letter==0)
	$current=$year.$mfirst_letter.($msecond_letter-1);
	else if($month ==10)
	$current=$year."0".($month-1);
	else
	$current=$year.($month-1);
	}
	else
	{
	$current=($year-1)."12";
	}

	return $current;

	}
	function get_previous_year($current)
	{
	$year=substr($current,0,4);
	$current=($year-1);

	return $current;
	}

	function get_nextday($current)
	{


	$year=substr($current,0,4);
	$month=substr($current,4,2);
	$day=substr($current,6,2);

	$first_letter=substr($day,0,1);
	$second_letter=substr($day,1,1);

	$mfirst_letter=substr($month,0,1);
	$msecond_letter=substr($month,1,1);

	if($day !=28 && $day !=29 && $day !=30 && $day !=31)
	{
	if($first_letter==0 && $day !='09')
	$current=$year.$month.$first_letter.($second_letter+1);
	else
	$current=$year.$month.($day+1);


	}
	else if(($day ==28 || $day ==29) && $month !='02')
	{
	$current=$year.$month.($day+1);

	}
	else if($day ==28 && $month =='02')
	{

	$mod=$year%4;

	if($mod==0) //leap year
	$current=$year.$month.($day+1);
	else
	$current=$year."03"."01";


	}
	else if($day ==29 && $month =='02')
	{

	$current=$year."03"."01";


	}
	else if($day ==30 && ($month=='01' || $month=='03' || $month=='05' || $month=='07' || $month=='08' || $month=='10' || $month=='12'))
	{
	$current=$year.$month.($day+1);

	}
	else if($day ==30 && ($month=='04' || $month=='06' || $month=='09' || $month=='11'))
	{

	if($mfirst_letter==0 && $month !='09')
	$current=$year.$mfirst_letter.($msecond_letter+1)."01";
	else
	$current=$year.($month+1)."01";





	}
	else if($day ==31 && ($month=='01' || $month=='03' || $month=='05' || $month=='07' || $month=='08' || $month=='10'))
	{

	if($mfirst_letter==0)
	$current=$year.$mfirst_letter.($msecond_letter+1)."01";
	else
	$current=$year.($month+1)."01";



	}
	else if($day ==31 && ($month=='12'))
	{

	$current=($year+1)."01"."01";

	}

	return $current;


	}
	function get_nextmonth($current)
	{
	$year=substr($current,0,4);
	$month=substr($current,4,2);

	$mfirst_letter=substr($month,0,1);
	$msecond_letter=substr($month,1,1);

	if($month !=12)
	{

	if($mfirst_letter==0 && $month !='09')
	$current=$year.$mfirst_letter.($msecond_letter+1);
	else
	$current=$year.($month+1);


	}
	else
	{
	$current=($year+1)."01";

	}
	return $current;

	}
	function get_nextyear($current)
	{
	$current=($current+1);
	return $current;
	}
	function get_previousyear($current)
	{
		$current=($current-1);
		return $current;
	}




	function get_row_count($tableName,$check=2)
	{
		$db= DAL::get_instance();
		$count=$db->read_single_column("SELECT count(*) FROM ".TABLE_PREFIX.$db->sanitize($tableName));
		return $count;
	}


	function get_month_substract($current)
	{
		$backup=Configuration::get_instance()->read('daily_based_data_backup_expiry');

		for($i=0;$i < $backup;$i++)
		{
			$current=$this->get_previous_month($current);
		}

		return $current;
	}






function mybase64_encode($s)
{
	return str_replace(array('+', '/'), array(',', '-'), base64_encode($s));
}

function mybase64_decode($s)
{
	return base64_decode(str_replace(array(',', '-'), array('+', '/'), $s));
}





	function get_referral_user_active($uid,$type=0)
	{
		$db= DAL::get_instance();

		$row=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."users WHERE id=? AND (adv_status =1 OR pub_status =1)",array($uid));

		if($row->get_num_records() >0)
		{
			$rowdata=$row->fetch_assoc();

			$percentage=0;

			if($type ==1 && isset($rowdata['adv_ref_profit_percentage']))
			$percentage=$rowdata['adv_ref_profit_percentage'];
			else if($type ==2 && isset($rowdata['pub_ref_profit_percentage']))
			$percentage=$rowdata['pub_ref_profit_percentage'];

			return array($rowdata['id'],$percentage);
		}
		else
		return array(0,0);
	}




	function get_addon_status($type)
	{
		$configarraypath=PATH_TO_ROOT.CACHE_DIR.'/configuration/configuration.php';

		$configarray=array();

		include($configarraypath);

		if($configurationarray)
		{
			$configarray=json_decode(str_replace("\'","'",$configurationarray),1);

			if(isset($configarray[$type]))
			$value=$configarray[$type];
			else
			$value=-1;
		}
		else
		{
			$db= DAL::get_instance();
			$row=$db->execute_query("SELECT id,value FROM ".TABLE_PREFIX."config WHERE name=?",array($type));

			if($row->get_num_records() >0)
			{
				$rowdata=$row->fetch_assoc();

				$value=intval($rowdata['value']);
			}
			else
			$value=-1;
		}


		return $value;
	}

	function get_ad_pricing_value($adid)
	{
		$db= DAL::get_instance();

		$value=$db->read_single_column("SELECT display_type FROM ".TABLE_PREFIX."ads WHERE id=?",array($adid));



		return $value;
	}

	function get_adtype_from_id($adid)
	{
		$db= DAL::get_instance();
		$type=$db->read_single_column("select type from ".TABLE_PREFIX."ads where id=?",array($adid));
		return $type;
	}

	function sendFraudNotification($pubname,$to,$engname)
	{
		$emailstring = "

Hello,

A fraud click was attempted by the publisher $pubname .
You can find the fraud click statistics of the publisher from admin area .

Thanks.

".$engname;

		UtilityHelper::send_mail($to,"Fraud Click Alert!!!", $emailstring);
	}

	function get_site_name($sid)
	{
		$db=DAL::get_instance();

		$name=$db->read_single_column("SELECT url FROM ".TABLE_PREFIX."sites WHERE id=?",array($sid));

		return $name;
	}


};
?>
