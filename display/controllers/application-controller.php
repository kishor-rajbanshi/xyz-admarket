<?php
class ApplicationController extends Controller
{
	function before_execute()
	{
		date_default_timezone_set(Configuration::get_instance()->read('default_time_zone'));
	}

	function get_track_domain($fromAdDisplay = 1)
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

			$trackDomain = str_replace('https://','',$trackDomain);
			$trackDomain = str_replace('http://','',$trackDomain);

			return "//".$trackDomain;
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


	function get_bannersize_adblock($var,$adtype=0)
	{
		$db= DAL::get_instance();

		if($adtype ==11)
		$string=' textimage_size ';
		else
		$string=' bannersize ';


		$result=$db->read_single_column("select ".$string." from ".TABLE_PREFIX."adblock where id=?",array($var));

		return $result;
	}

	function get_row_count($tableName,$check=2)
	{
		$db= DAL::get_instance();
		$count=$db->read_single_column("SELECT count(*) FROM ".TABLE_PREFIX.$db->sanitize($tableName));
		return $count;
	}




	function get_credit_type($id)
	{
		$db= DAL::get_instance();
		$type=$db->read_single_column("select type from ".TABLE_PREFIX."credittext where id=?",array($id));

		return intval($type);
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



function get_banner_exists($id)
{
	$db= DAL::get_instance();
	$return=$db->read_single_column("SELECT id FROM ".TABLE_PREFIX."custom_banners WHERE id=?",array($id));
	return $return;
}

	function get_banner_dimension($id)
	{
		$db= DAL::get_instance();

		$row=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."banner_dimensions WHERE id=?",array($id));
		$rowdata=$row->fetch_assoc();

		return $rowdata['width'].'-'.$rowdata['height'];
	}

	function get_dimension($blockid,$bannerid=0,$ad_type=0)
	{
		$db= DAL::get_instance();

		$adbtype=$db->read_single_column("SELECT type FROM ".TABLE_PREFIX."adblock WHERE id=?",array($blockid));

		if($bannerid ==0)
		$id=$db->read_single_column("SELECT bannersize FROM ".TABLE_PREFIX."adblock WHERE id=?",array($blockid));
		else
		$id=$bannerid;

		if($id >0 && ($adbtype !=4 || $ad_type==11))
		$result=$db->execute_query("select * from ".TABLE_PREFIX."banner_dimensions where id=?",array($id));
		else
		$result=$db->execute_query("select * from ".TABLE_PREFIX."adblock where id=?",array($blockid));

		$row=$result->fetch_assoc();

		return $row['width'].' x '.$row['height'];
	}


	function get_referred_users($uid,$type)
	{
		$db= DAL::get_instance();

		$string='';
		if($type ==1)
		$string=' AND adv_status=1 ';
		else if($type ==2)
		$string=' AND pub_status=1 ';


		$count=$db->read_single_column("select count(id) from ".TABLE_PREFIX."users where rid=? ".$string." ",array($uid));

		return intval($count);


	}

	function get_referrer_id($uid)
	{
		$db= DAL::get_instance();

		$rid=$db->read_single_column("SELECT rid FROM ".TABLE_PREFIX."users WHERE id=?",array($uid));

		return intval($rid);
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

	function get_language_name($var)
	{
		$db= DAL::get_instance();
		$result=$db->read_single_column("select name from ".TABLE_PREFIX."language where id=?",array($var));
		return $result;
	}
	function get_isp_name($var)
	{
		$db= DAL::get_instance();
		$result=$db->execute_query("select name,country from ".TABLE_PREFIX."isp where id=?",array($var));
		$row=$result->fetch_row();


		return $row;
	}
	function get_connection_name($var)
	{
		$db= DAL::get_instance();
		$result=$db->read_single_column("select name from ".TABLE_PREFIX."connection where id=?",array($var));
		return $result;
	}
	function get_os_name($var)
	{
		$db= DAL::get_instance();
		$result=$db->read_single_column("select name from ".TABLE_PREFIX."os where id=?",array($var));
		return $result;
	}
	function get_browser_name($var)
	{
		$db= DAL::get_instance();
		$result=$db->read_single_column("select name from ".TABLE_PREFIX."browser where id=?",array($var));
		return $result;
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


	function get_ad_validation_user($aid, $disptype = 0, $adtype = 0)
	{
		$db= DAL::get_instance();

		$disptypestring="";
		$arr   = array();
		$arr[] = $aid;

		if($disptype >0)
		{
			$disptypestring.= " and display_type=? ";
			$arr[]          = $disptype;
		}

		if($adtype >0)
		{
			$disptypestring.= " and type=? ";
			$arr[]          = $adtype;
		}

		$result=$db->read_single_column("select count(id) from ".TABLE_PREFIX."ads where id=? and uid <>0 ".$disptypestring." ",$arr);

		if($result >0)
		return true;
		else
		return false;
	}
	function get_adunit_exists($aduid, $displaytype = 0, $adcodetype = 0)
	{
		$db= DAL::get_instance();

		$typestring="";
		$arr   = array();
		$arr[] = $aduid;

		if($displaytype >0)
		{
			$typestring.= " and display_type=? ";
			$arr[]      = $displaytype;
		}

		if($adcodetype >0)
		{
			$typestring.= " and adcode_type=? ";
			$arr[]      = $adcodetype;
		}

		$result=$db->read_single_column("select count(id) from ".TABLE_PREFIX."adunit where id=? ".$typestring." ",$arr);

		if($result >0)
		return true;
		else
		return false;
	}


	function get_locale_id($name)
	{
		$id=DAL::get_instance()->read_single_column("select id from ".TABLE_PREFIX."locale where name=?",array($name));
		return $id;
	}

	function get_locale_name($code)
	{
		$db= DAL::get_instance();
		$description=$db->read_single_column("select description from ".TABLE_PREFIX."locale where name=?",array($code));

		return $description;
	}

	function get_credittext($var, $escape = 0, $return = 0, $from = 0)
	{
		//$return ==1 Return as array from ad display

		$db = DAL::get_instance();

		$result = $db->execute_query("select * from " . TABLE_PREFIX . "credittext where id=?",array ($var));
		$row = $result->fetch_assoc();
		$creditvalue = $row ['credittext'];

		$creditimage = $row ['image'];

		if($row ['type'] == 1)
		$data = '<img src="' . BASE . DATA_DIR . '/credit/' . $creditimage . '" />';
		else
		$data = $creditvalue;

		if($escape == 1 && $row ['type'] == 0)
		$data_return = htmlspecialchars($data,ENT_QUOTES,DEFAULT_CHARSET);
		else
		$data_return = $data;


		if($row['icon_type'] ==1)
		$data_icon='<img src="'.BASE.DATA_DIR.'/credit/'.$row['icon_content'].'" />';
		else
		$data_icon=$row['icon_content'];

		if($escape==1 && $row['icon_type'] ==0)
		$data_return_icon=htmlspecialchars($data_icon,ENT_QUOTES,DEFAULT_CHARSET);
		else
		$data_return_icon=$data_icon;


		if($return == 1)
		{
			$data_array[] = $data_return;
			$data_array[] = $row['type'];
			$data_array[] = $data_return_icon;
			$data_array[] = $row['icon_type'];

			return $data_array;
		}
		else
		return $data_return;
	}



	function get_ad_display_preview($aid,$clksurlpath,$retargetid) // For Ad Display Preview
	{
		$db= DAL::get_instance();
		$row=$db->execute_query("SELECT dl.*,al.*,dl.id as displayid FROM ".TABLE_PREFIX."display_layout dl INNER JOIN ".TABLE_PREFIX."ad_layout al ON dl.ad_layout = al.id INNER JOIN ".TABLE_PREFIX."ads ad ON ad.display_layout=dl.id WHERE ad.id=?",array($aid));


		$rowdata=$row->fetch_assoc();

		$outerdiv="";
		$dummystyle="";

		$maxtitlelength=Configuration::get_instance()->read('max_ad_title_length');
		$maxdesclength=Configuration::get_instance()->read('max_ad_desc_length');
		$maxurllength=Configuration::get_instance()->read('max_display_url_length');

		if($rowdata['displayid'] >0)
		{
			$ad_count=$rowdata['ad_count'];

			if($retargetid >0)
			$row12345=$db->execute_query("SELECT a.*,al.*,al.id as adlid,am.rid FROM ".TABLE_PREFIX."ads a INNER JOIN ".TABLE_PREFIX."ads_list al ON a.id=al.aid LEFT OUTER JOIN ".TABLE_PREFIX."ad_retargeting_mapping am ON al.id=am.alid WHERE a.id=? AND a.type=7 AND (am.aid=? OR am.aid IS NULL) ORDER BY al.display_time",array($aid,$aid));
			else
			$row12345=$db->execute_query("SELECT a.*,al.*,al.id as adlid FROM ".TABLE_PREFIX."ads a INNER JOIN ".TABLE_PREFIX."ads_list al ON a.id=al.aid WHERE a.id=? AND a.type=7 ORDER BY al.display_time",array($aid));

			$queryarray=array();

			if($row12345->get_num_records() >0)
			{

				$this->set_result('row12345',$row12345);

				$row12345=$this->get_result('row12345');


				$siteid=0;
				$idlist=array();
				$newarray=array();

				if($retargetid >0)
				{
					$retarget=$this->read_cookie_param("ads_retarget");


					$retargetarray=explode(',',$retarget);
					$retargetarraycount=count($retargetarray);

					for($i=0;$i < $retargetarraycount;$i++)
					{
						if($retargetarray[$i] !="")
						{
							$retargetarray1=explode('-',$retargetarray[$i]);

							if($retargetarray1[1] == $retargetid)
							{
								$siteid=$retargetarray1[0];
								break;
							}
						}
					}



					for($i=0;$i < $retargetarraycount;$i++)
					{
						if($retargetarray[$i] !="")
						{
							$retargetarray1=explode('-',$retargetarray[$i]);

							if($retargetarray1[0] == $siteid)
							$idlist[]=$retargetarray1[1];
						}
					}



					foreach($idlist as $key=>$value)
					{
						if(intval($value) >0)
						{
							foreach($row12345 as $key1=>$value1)
							{
								if($value1['rid'] == $value)
								{
									$newarray[]=$value1;
									unset($row12345[$key1]);
									break;
								}
							}
						}
					}
				}


				shuffle($row12345);    // For changing ad display order in ecommerce


				foreach($row12345 as $key11=>$value11)
				{
					$newarray[]=$value11;
					unset($row12345[$key11]);
				}





		$cssstring='<style type="text/css">';
		if($rowdata['slider_enabled'] ==1)
		$cssstring.='#slbox-'.$rowdata['displayid'].'-'.$aid.'::before {height:'.$rowdata['slider_height'].'px;}';

		if($rowdata['headline_enabled'] ==1)
		$cssstring.='#hebox-'.$rowdata['displayid'].'-'.$aid.'::before {height:'.$rowdata['headline_height'].'px;}';

		if($rowdata['logo_enabled'] ==1)
		$cssstring.='#lobox-'.$rowdata['displayid'].'-'.$aid.'::before {height:'.$rowdata['logo_height'].'px;}';

		$cssstring.='</style>';




		$sliderwidth=$rowdata['slider_width'];


		$loopdata="";
		$looparray=array();




			$singleadspace=$rowdata['ad_width']+($rowdata['layout_padding']*2);//+2
			$singleadspaceheight=$rowdata['ad_height']+($rowdata['layout_padding']*2);//+2


				if($rowdata['slider_width'] > $singleadspace || $rowdata['slider_height'] > $singleadspaceheight)
								{
									$sectionadcount=1;
									$sectionrowcount=1;

									if($rowdata['slider_width'] > $singleadspace)
									$sectionadcount=intval($rowdata['slider_width'] / $singleadspace);

									if($rowdata['slider_height'] > $singleadspaceheight)
									$sectionrowcount=intval($rowdata['slider_height'] / $singleadspaceheight);

                                    $sectiontotaladcount=$sectionadcount*$sectionrowcount;

								}
								else
								{
									$sectionadcount=1;
									$sectiontotaladcount=1;
								}

								if($ad_count > $sectionadcount)
								$blockadspace=$singleadspace * $sectionadcount;
								else
								$blockadspace=$singleadspace * $ad_count;


								$difference=$sliderwidth-$blockadspace;

								if($difference < 0)
								$difference=0;

								$differencepadding=$difference/2;






								$iii=0;
								$iiii=1;
								$iiiii=1;


								$headline_type=0;
								$headline_text="";
								$logo="";

								$language_direction=0;

								$clksurlhead = str_replace('{ECOMID}',0,$clksurlpath);

								foreach($newarray as $key111=>$row12345data)
								{
									$clksurl     = str_replace('{ECOMID}',$row12345data['adlid'],$clksurlpath);

									$layoutstring="";

									if($iiiii > $ad_count)
									break;

									$iiiii=$iiiii+1;

									if($iii ==0 || $iii == $sectiontotaladcount)
									{
										if($iii ==0)
										{
													$headline_type=$row12345data['headline_type'];
													$headline_text=$row12345data['headline_text'];
													$logo=$row12345data['logo'];


													//$language_direction=$db->read_single_column("SELECT direction FROM ".TABLE_PREFIX."locale WHERE name=?",array($row12345data['language']));
													//Currently ads have no rtl support

											$loopdata.='<div class="slideblock-style" id="sectionblock-'.$iiii.'-'.$aid.'" style="width:'.$blockadspace.'px;height:'.$rowdata['slider_height'].'px;margin-left:'.$differencepadding.'px;margin-right:'.$differencepadding.'px;"><div class="slideblockinner-style">';

											if($dummystyle =="")
											{
												$dummystyle.='<style type="text/css">';

												$dummystyle.='#content-box-section-'.$aid.' .ecommercetitle a:link, #content-box-section-'.$aid.' .ecommercetitle a:visited, #content-box-section-'.$aid.' .ecommercetitle a:hover, #content-box-section-'.$aid.' .ecommercetitle a:active, #content-box-section-'.$aid.' .ecommercetitle a:focus {color: '.$row12345data['title_color'].' !important;}';
												$dummystyle.='#content-box-section-'.$aid.' .ecommercedescription a:link, #content-box-section-'.$aid.' .ecommercedescription a:visited, #content-box-section-'.$aid.' .ecommercedescription a:hover, #content-box-section-'.$aid.' .ecommercedescription a:active, #content-box-section-'.$aid.' .ecommercedescription a:focus {color: '.$row12345data['desc_color'].' !important;}';
												$dummystyle.='#content-box-section-'.$aid.' .ecommerceurl a:link, #content-box-section-'.$aid.' .ecommerceurl a:visited, #content-box-section-'.$aid.' .ecommerceurl a:hover, #content-box-section-'.$aid.' .ecommerceurl a:active, #content-box-section-'.$aid.' .ecommerceurl a:focus {color: '.$row12345data['url_color'].' !important;}';

												$dummystyle.='#content-box-section-'.$aid.' .dummyprice{color: '.$row12345data['price_color'].' !important;}';
												$dummystyle.='#content-box-section-'.$aid.' .dummyofferprice{color: '.$row12345data['offer_price_color'].' !important;}';

												$dummystyle.='#content-box-section-'.$aid.' .dummybutton {background-color: '.$row12345data['ab_background_color'].' !important;}';
												$dummystyle.='#content-box-section-'.$aid.' .dummybutton a {color: '.$row12345data['ab_text_color'].' !important;}';
												$dummystyle.='#content-box-section-'.$aid.' .dummybutton:hover {background-color: '.$row12345data['ab_bhover_color'].' !important;}';

												$dummystyle.='#content-box-section-'.$aid.' .span-text-a{color:'.$row12345data['ah_text_color'].' !important;}';
												$dummystyle.='#content-box-section-'.$aid.' .span-button-a{color:'.$row12345data['abh_text_color'].' !important;}';

												$dummystyle.='#content-box-section-'.$aid.' .span-button-preview{color:'.$row12345data['abh_text_color'].' !important;background-color: '.$row12345data['abh_background_color'].' !important;}';
												$dummystyle.='#content-box-section-'.$aid.' .span-button-preview:hover {background-color: '.$row12345data['abh_bhover_color'].' !important;}';

												$dummystyle.='#content-box-section-'.$aid.' .display-outer-style{border-color:'.$row12345data['border_color'].' !important;background-color: '.$row12345data['background_color'].' !important;}';
												$dummystyle.='#content-box-section-'.$aid.' .singleadsection-style{border-color:'.$row12345data['border_color'].' !important;background-color: '.$row12345data['ad_background_color'].' !important;}';

												$dummystyle.='#content-box-section-'.$aid.' .slideleftinner-style, #content-box-section-'.$aid.' .sliderightinner-style {border-color:'.$row12345data['border_color'].' !important;color:'.$row12345data['border_color'].' !important;background-color: '.$row12345data['background_color'].' !important;}';

												$dummystyle.='</style>';
											}

										}
										else
										{
											$iiii=$iiii+1;


											$loopdata.='</div></div>';

											$looparray[]=$loopdata;

											$loopdata="";

											$loopdata.='<div class="slideblock-style" id="sectionblock-'.$iiii.'-'.$aid.'" style="width:'.$blockadspace.'px;height:'.$rowdata['slider_height'].'px;margin-left:'.$differencepadding.'px;margin-right:'.$differencepadding.'px;"><div class="slideblockinner-style">';

											$iii=0;
										}
									}



	$pricingstring="";


if($row12345data['ad_price'] !="" || $row12345data['action_text'] !="")
{


if($rowdata['price_enabled'] ==1 || $rowdata['button_enabled'] ==1){

if($rowdata['price_enabled'] ==1 && $row12345data['ad_price'] !="")
{
	$pricingstring.='<span class="dummyprice" style="padding-left:'.$rowdata['layout_padding'].'px;padding-right:'.$rowdata['layout_padding'].'px;';


	$pricingstring.='font-family:'.$rowdata['ppfont'].';font-size:'.$rowdata['ppsize'].'px;line-height:'.$rowdata['pplineheight'].'px;';

	$pricingstring.='font-weight:'.$rowdata['pp_weight'].';';

	if($rowdata['offer_price_enabled'] ==1 && $row12345data['ad_offer_price'] !="")
	$pricingstring.='text-decoration:line-through;';
	else
	$pricingstring.='text-decoration:'.$rowdata['pp_decoration'].';';

	$pricingstring.='">'.$row12345data['ad_price'].'</span> ';
}


if($rowdata['offer_price_enabled'] ==1 && $row12345data['ad_price'] !="" && $row12345data['ad_offer_price'] !="")
{
	$pricingstring.='<span class="dummyofferprice" style="';


	$pricingstring.='font-family:'.$rowdata['ppfont'].';font-size:'.$rowdata['ppsize'].'px;line-height:'.$rowdata['pplineheight'].'px;';

	$pricingstring.='font-weight:'.$rowdata['pp_weight'].';';

	$pricingstring.='text-decoration:'.$rowdata['pp_decoration'].';';

	$pricingstring.='">'.$row12345data['ad_offer_price'].'</span> ';
}


if($rowdata['button_enabled'] ==1 && $row12345data['action_text'] !="")
{
$pricingstring.='<span class="dummybutton" ';

$pricingstring.='style="padding:'.$rowdata['layout_padding'].'px;margin:0px '.$rowdata['layout_padding'].'px;';

if($rowdata['content_type'] ==2 && ($rowdata['layout_position'] ==1 || $rowdata['layout_position'] ==3))
$pricingstring.='float: none;';


$pricingstring.='"><a ';

$pricingstring.='style="font-family:'.$rowdata['bbfont'].';font-size:'.$rowdata['bbsize'].'px;line-height:'.$rowdata['bblineheight'].'px;';

$pricingstring.='font-weight:'.$rowdata['bb_weight'].';';

$pricingstring.='text-decoration:'.$rowdata['bb_decoration'].';';

$pricingstring.='" target="_blank" href="'.$clksurl.'">'.$row12345data['action_text'].'</a></span>';

}

}

}

$layoutstring='<div class="singleadsection-style" style="width:'.$rowdata['ad_width'].'px;height:'.$rowdata['ad_height'].'px;margin:'.$rowdata['layout_padding'].'px;padding:'.$rowdata['layout_padding'].'px;">';

$layoutstring.='<table class="preview-display-style ';

if($language_direction ==1)
$layoutstring.=' display-style-right ';

$layoutstring.='">';

if($rowdata['content_type'] ==1 || ($rowdata['content_type'] ==2 && $rowdata['layout_position'] ==1))
{

	$imagepathdir=BASE.DATA_DIR.'/ecommerce/'.$aid.'/'.$row12345data['ad_image'];

	$layoutstring.='<tr><td colspan="3" style="text-align: center;vertical-align: middle;width:'.$rowdata['image_width'].'px;"><label style="width:'.$rowdata['image_width'].'px;height:'.$rowdata['image_height'].'px;margin:'.$rowdata['layout_padding'].'px;"><a target="_blank" href="'.$clksurl.'"><img style="max-width:'.$rowdata['image_width'].'px;max-height:'.$rowdata['image_height'].'px;" src="'.$imagepathdir.'" /></a></label></td></tr>';
}


if($rowdata['content_type'] !=1)
{




$layoutstring.='<tr>';
if($rowdata['content_type'] ==2 && $rowdata['layout_position'] ==0)
{

	$imagepathdir=BASE.DATA_DIR.'/ecommerce/'.$aid.'/'.$row12345data['ad_image'];



	$layoutstring.='<td style="width:'.$rowdata['image_width'].'px;"><div style="margin:'.$rowdata['layout_padding'].'px;"><a target="_blank" href="'.$clksurl.'"><img style="max-width:'.$rowdata['image_width'].'px;max-height:'.$rowdata['image_height'].'px;" src="'.$imagepathdir.'" /></a></div></td>';
}

$layoutstring.='<td ';
if($rowdata['content_type'] ==2 && ($rowdata['layout_position'] ==1 || $rowdata['layout_position'] ==3))
$layoutstring.='style="text-align: center;"';
$layoutstring.='>';



if($rowdata['content_type'] ==0 || $rowdata['content_type'] ==2){

if($rowdata['title_enabled'] ==1){


$layoutstring.='<div class="ecommercetitle" style="padding-left:'.$rowdata['layout_padding'].'px;padding-right:'.$rowdata['layout_padding'].'px;';

$layoutstring.='"><a ';

$layoutstring.='style="font-family:'.$rowdata['ttfont'].';font-size:'.$rowdata['ttsize'].'px;line-height:'.$rowdata['ttlineheight'].'px;';

$layoutstring.='font-weight:'.$rowdata['tt_weight'].';';

$layoutstring.='text-decoration:'.$rowdata['tt_decoration'].';';

$layoutstring.='" target="_blank" href="'.$clksurl.'">'.$row12345data['ad_title'].'</a></div>';

}



if($rowdata['description_enabled'] ==1){



$layoutstring.='<div class="ecommercedescription" style="padding-left:'.$rowdata['layout_padding'].'px;padding-right:'.$rowdata['layout_padding'].'px;';

$layoutstring.='"><a ';

$layoutstring.='style="font-family:'.$rowdata['ddfont'].';font-size:'.$rowdata['ddsize'].'px;line-height:'.$rowdata['ddlineheight'].'px;';

$layoutstring.='font-weight:'.$rowdata['dd_weight'].';';

$layoutstring.='text-decoration:'.$rowdata['dd_decoration'].';';

$layoutstring.='" target="_blank" href="'.$clksurl.'">'.$row12345data['ad_description'].'</a></div>';

}

if($rowdata['url_enabled'] ==1){


$layoutstring.='<div class="ecommerceurl" style="padding-left:'.$rowdata['layout_padding'].'px;padding-right:'.$rowdata['layout_padding'].'px;';

$layoutstring.='"><a ';

$layoutstring.='style="font-family:'.$rowdata['uufont'].';font-size:'.$rowdata['uusize'].'px;line-height:'.$rowdata['uulineheight'].'px;';

$layoutstring.='font-weight:'.$rowdata['uu_weight'].';';

$layoutstring.='text-decoration:'.$rowdata['uu_decoration'].';';

$layoutstring.='" target="_blank" href="'.$clksurl.'">'.$row12345data['ad_display_url'].'</a></div>';

} }

$layoutstring.=$pricingstring;

$layoutstring.='</td>';


if($rowdata['content_type'] ==2 && $rowdata['layout_position'] ==2)
{
	$imagepathdir=BASE.DATA_DIR.'/ecommerce/'.$aid.'/'.$row12345data['ad_image'];

	$layoutstring.='<td><div style="margin:'.$rowdata['layout_padding'].'px;"><a target="_blank" href="'.$clksurl.'"><img style="max-width:'.$rowdata['image_width'].'px;max-height:'.$rowdata['image_height'].'px;" src="'.$imagepathdir.'" /></a></div></td>';
}

$layoutstring.='</tr>';


}
else
{


	$layoutstring.='<tr><td colspan="3" style="text-align:center">'.$pricingstring.'</td></tr>';

}







if($rowdata['content_type'] ==2 && $rowdata['layout_position'] ==3)
{

	$imagepathdir=BASE.DATA_DIR.'/ecommerce/'.$aid.'/'.$row12345data['ad_image'];


	$layoutstring.='<tr><td colspan="3" style="text-align: center;vertical-align: middle;width:'.$rowdata['image_width'].'px;"><label style="width:'.$rowdata['image_width'].'px;height:'.$rowdata['image_height'].'px;margin:'.$rowdata['layout_padding'].'px;"><a target="_blank" href="'.$clksurl.'"><img  style="max-width:'.$rowdata['image_width'].'px;max-height:'.$rowdata['image_height'].'px;" src="'.$imagepathdir.'" /></a></label></td></tr>';
}

$layoutstring.='</table>';



	$layoutstring.='</div>';



									$loopdata=$loopdata.$layoutstring;

									$iii=$iii+1;

								}

								if($loopdata !="")
								$looparray[]=$loopdata.'</div></div>';


								if($language_direction ==1)
								krsort($looparray);


								$loopdatanew="";
								foreach($looparray as $key=>$value)
								{
									$loopdatanew.=$value;
								}



			$headlinesection='<div class="headlinesection-style" id="hebox-'.$rowdata['displayid'].'-'.$aid.'" style="';


			$headlinesection.='width:'.$rowdata['headline_width'].'px;';
			$headlinesection.='height:'.$rowdata['headline_height'].'px;';



			if($headline_type ==0)
			$headlinesection.='"><a class="span-text-a" ';
			else
			$headlinesection.='"><a class="span-button-a" ';


			$headlinesection.='style="font-family:'.$rowdata['hhfont'].';font-size:'.$rowdata['hhsize'].'px;line-height:'.$rowdata['hhlineheight'].'px;';

			$headlinesection.='font-weight:'.$rowdata['hh_weight'].';';

			$headlinesection.='text-decoration:'.$rowdata['hh_decoration'].';';



			$headlinesection.='" target="_blank" href="'.$clksurlhead.'">';

			if($headline_type ==0)
			$headlinesection.='<span class="span-text">'.$headline_text.'</span>';
			else
			$headlinesection.='<span class="span-button-preview" style="padding:'.$rowdata['layout_padding'].'px;">'.$headline_text.'</span>';

			$headlinesection.='</a></div>';



			$logopath=BASE.DATA_DIR."/ecommerce/logo/".$logo;


			$logosection='<div class="logosection-style" id="lobox-'.$rowdata['displayid'].'-'.$aid.'" style="';



			$logosection.='width:'.$rowdata['logo_width'].'px;';
			$logosection.='height:'.$rowdata['logo_height'].'px;">';




			$logosection.='<a target="_blank" href="'.$clksurlhead.'"><img style="vertical-align: middle;';

			$logosection.='max-width:'.($rowdata['logo_width']-($rowdata['layout_padding']*2)).'px;max-height:'.($rowdata['logo_height']-($rowdata['layout_padding']*2)).'px;';


			$logosection.='" src="'.$logopath.'" /></a>';



			$logosection.='</div>';


								$innerwidth=$blockadspace+($differencepadding*2);


								if($loopdatanew !="")
								{
									if($iiii >1)
									{
										if($language_direction ==1)
										$loopdatanew='<span class="slideleft-style" style="right:0px;left:unset;"><span class="slideleftinner-style" onclick="SlideRight('.$aid.');"><</span></span><div class="slideinnerholder-style" style="width:'.$rowdata['slider_width'].'px;"><div class="slideinner-style" style="width:'.($innerwidth*$iiii).'px;"><input type="hidden" name="currentindex" id="currentindex-'.$aid.'" value="'.$iiii.'" /><input type="hidden" name="blockcount" id="blockcount-'.$aid.'" value="'.$iiii.'" />'.$loopdatanew.'</div></div><span class="slideright-style" style="left:0px;right:unset;"><span class="sliderightinner-style" onclick="SlideLeft('.$aid.');">></span></span>';
										else
										$loopdatanew='<span class="slideleft-style"><span class="slideleftinner-style" onclick="SlideLeft('.$aid.');"><</span></span><div class="slideinnerholder-style" style="width:'.$rowdata['slider_width'].'px;"><div class="slideinner-style" style="width:'.($innerwidth*$iiii).'px;"><input type="hidden" name="currentindex" id="currentindex-'.$aid.'" value="1" /><input type="hidden" name="blockcount" id="blockcount-'.$aid.'" value="'.$iiii.'" />'.$loopdatanew.'</div></div><span class="slideright-style"><span class="sliderightinner-style" onclick="SlideRight('.$aid.');">></span></span>';
									}
									else
									$loopdatanew='<div class="slideinner-style" style="width:'.($innerwidth*$iiii).'px;">'.$loopdatanew.'</div>';


									$loopdatanew.='<style type="text/css">.slideblock-style::before {height:'.$rowdata['slider_height'].'px;}</style>';
								}




								$slidesection='<div class="slidesection-style" id="slbox-'.$rowdata['displayid'].'-'.$aid.'" style="width:'.$rowdata['slider_width'].'px;height:'.$rowdata['slider_height'].'px;';

								if($language_direction ==1)
								$slidesection.='direction:rtl;';

								$slidesection.='">'.$loopdatanew.'</div>';



								$outerdiv='<div class="display-outer-style" id="display-outer-'.$rowdata['displayid'].'-'.$aid.'" style="padding:'.$rowdata['layout_padding'].'px;">';




								$content_order=$rowdata['content_order'];
								$content_order_array=explode('-',$content_order);

								if(isset($content_order_array[0]) && $content_order_array[0] =='slbox' && $rowdata['slider_enabled'] ==1)
								$outerdiv.=$slidesection;
								else if(isset($content_order_array[0]) && $content_order_array[0] =='hebox' && $rowdata['headline_enabled'] ==1)
								$outerdiv.=$headlinesection;
								else if(isset($content_order_array[0]) && $content_order_array[0] =='lobox' && $rowdata['logo_enabled'] ==1)
								$outerdiv.=$logosection;




								if(isset($content_order_array[1]) && $content_order_array[1] =='slbox' && $rowdata['slider_enabled'] ==1)
								$outerdiv.=$slidesection;
								else if(isset($content_order_array[1]) && $content_order_array[1] =='hebox' && $rowdata['headline_enabled'] ==1)
								$outerdiv.=$headlinesection;
								else if(isset($content_order_array[1]) && $content_order_array[1] =='lobox' && $rowdata['logo_enabled'] ==1)
								$outerdiv.=$logosection;


								if(isset($content_order_array[2]) && $content_order_array[2] =='slbox' && $rowdata['slider_enabled'] ==1)
								$outerdiv.=$slidesection;
								else if(isset($content_order_array[2]) && $content_order_array[2] =='hebox' && $rowdata['headline_enabled'] ==1)
								$outerdiv.=$headlinesection;
								else if(isset($content_order_array[2]) && $content_order_array[2] =='lobox' && $rowdata['logo_enabled'] ==1)
								$outerdiv.=$logosection;


								$outerdiv.='</div>'.$cssstring.$dummystyle;

		}
		}

		return $outerdiv;


	}



	function get_banner_dimension_support($id,$type)
	{
		$db= DAL::get_instance();

		$typestring="";

		if($type ==2)
		$typestring=' AND image_support=1 ';
		else if($type ==7)
		$typestring=' AND ecommerce_support=1 ';
		else if($type ==13)
		$typestring=' AND vast_video_support=1 ';


		$count=$db->read_single_column("SELECT id FROM ".TABLE_PREFIX."banner_dimensions WHERE id=? ".$typestring." ",array($id));

		return intval($count);
	}

	function get_check_advertiser_ad($aid)
	{
		$db= DAL::get_instance();

		$result=$db->read_single_column("select count(id) from ".TABLE_PREFIX."ads where id=? and uid<>?",array($aid,0));

		if($result >0)
		return true;
		else
		return false;
	}

	function get_ad_status_value($aid)
	{
		$db= DAL::get_instance();
		$value=$db->read_single_column("SELECT status FROM ".TABLE_PREFIX."ads WHERE id=?",array($aid));

		return $value;
	}



	function get_adlayout_preview($adcodeResultData = array(),$adResultData = array(),$layoutResultData = array(),$fontResultData = array(),$themeResultData = array(),$previewSectionOuterWidth = 0,$adcodeDisplayType = 0)
	{
		if(count($adcodeResultData) == 0)
		return;

		$textimageWidth         = 0;
		$textimageHeight        = 0;


		$adcodeID    			= $adcodeResultData['adcodeID'];
		$displayAds    			= $adcodeResultData['displayAds'];
		$adLayoutType  			= $adcodeResultData['adLayoutType'];
		$borderType    			= $adcodeResultData['borderType'];

		$imagePosition    		= $adcodeResultData['imagePosition'];
		$adblockWidth    		= $adcodeResultData['width'];
		$adblockHeight    		= $adcodeResultData['height'];


		$nativeHeading    		= $adcodeResultData['nativeHeading'];
		$nativeRows    			= $adcodeResultData['nativeRows'];
		$nativeColumns    		= $adcodeResultData['nativeColumns'];
		$nativeAdsCount    		= $adcodeResultData['nativeAdsCount'];
		$responsiveSupport    	= $adcodeResultData['responsiveSupport'];

		if($adLayoutType == 11)
		{
			$textimageWidth     = $adcodeResultData['textimageWidth'];
			$textimageHeight    = $adcodeResultData['textimageHeight'];
		}

		$creditText    			= $adcodeResultData['creditText'];
		$creditType    			= $adcodeResultData['creditType'];
		$creditIcon    			= $adcodeResultData['creditIcon'];
		$creditIconType    		= $adcodeResultData['creditIconType'];
		$creditAlignment    	= $adcodeResultData['creditAlignment'];
		$creditPositioning    	= $adcodeResultData['creditPositioning'];
		$creditUrl    			= $adcodeResultData['creditUrl'];


		//$displayAds => 0 Normal Ads
		//$displayAds => 1 Native Ads

		$pathString = "../";

		$adsMargin		     = 0;
		$nativeSingleAdWidth = 0;
		$textimageMargin     = 5;

		if($displayAds == 1)
		$adsMargin		     = 5;


		$db = DAL::get_instance();

	    $sectionCount 				 = 0;
	    $contentWidth        		 = 0;
	    $contentHeight       		 = 0;
		$singleRowHeight 	 		 = 0;

		$title_enabled       		 = 0;
		$description_enabled    	 = 0;
		$url_enabled       			 = 0;
		$button_enabled       		 = 0;
		$contentSlide       		 = 0;
		$slideDirection       		 = 0;
		$slideDuration       		 = 0;
		$CTA_position       		 = 0;
		$buttonSectionWidth      	 = 0;
		$buttonSectionHeight     	 = 0;
		$CTA_border_radius      	 = 0;
		$title_border_bottom    	 = 0;
		$description_border_bottom   = 0;
		$displayurl_border_bottom    = 0;
		$CTA_padding_vertical        = 0;
		$CTA_padding_horizontal      = 0;

		$responseResult              = "";
		$minimumSizeString           = "";

		$overrideTheme  = Configuration::get_instance()->read('allow_publishers_to_override_theme');


		$adblockTotalWidth  	= $adblockWidth;
		$adblockTotalHeight  	= $adblockHeight;

		$adSizeChanged          = 0;
		if($adLayoutType == 1 || $adLayoutType == 11)
		{
			$adblockWidth        	= $adblockWidth  - 2;
			$adblockHeight       	= $adblockHeight - 2;
		$layoutMaxWidth  		= 0;
		$layoutMaxHeight  		= 0;
		$singleAdSectionWidth   = 0;
		$singleAdSectionHeight  = 0;
		$textimageBoxWidth   	= 0;
	   	$textimageBoxHeight  	= 0;
		$contentSectionWidth    = 0;
		$contentSectionHeight   = 0;
		}


		
		if($adLayoutType == 2 || $adLayoutType == 13)
		$borderType  			= 2; //No border


		if(count($fontResultData) == 0)
		return;

		if(count($themeResultData) == 0)
		return;


		if($adLayoutType == 1 || $adLayoutType == 11)
		{
			if(count($layoutResultData) == 0)
			return;

			//Adlayout Result


			$title_enabled       		 = $layoutResultData['title_enabled'];
			$description_enabled    	 = $layoutResultData['description_enabled'];
			$url_enabled       			 = $layoutResultData['url_enabled'];
			$button_enabled       		 = $layoutResultData['button_enabled'];
			$contentSlide       		 = $layoutResultData['content_slide'];
			$slideDirection       		 = $layoutResultData['slide_direction']; //slide_direction  0 vertical/ 1 horizontal
			$slideDuration       		 = $layoutResultData['slide_duration'];
			$CTA_position       		 = $layoutResultData['CTA_position'];
			$buttonSectionWidth      	 = $layoutResultData['CTA_section_width'];
			$buttonSectionHeight     	 = $layoutResultData['CTA_section_height'];
			$CTA_border_radius      	 = $layoutResultData['CTA_border_radius'];
			$title_border_bottom    	 = $layoutResultData['title_border_bottom'];
			$description_border_bottom   = $layoutResultData['description_border_bottom'];
			$displayurl_border_bottom    = $layoutResultData['displayurl_border_bottom'];
			$CTA_padding_vertical        = $layoutResultData['CTA_padding_vertical'];
			$CTA_padding_horizontal      = $layoutResultData['CTA_padding_horizontal'];
				$layoutMaxWidth  			 = $layoutResultData['maximum_width'];
				$layoutMaxHeight  			 = $layoutResultData['maximum_height'];

			if($displayAds == 1) // Native
			{
				$contentSlide       	= 0;


				$adblockTotalWidth  	= $layoutResultData['minimum_width'];
				$adblockTotalHeight  	= $layoutResultData['minimum_height'];

				if($adLayoutType == 11)
				{
					if($imagePosition == 0 || $imagePosition == 2) //Left or Right
					$adblockTotalWidth = $adblockTotalWidth + $textimageWidth + ($textimageMargin * 2); //Side margin space  left & right side
					else if($imagePosition == 1 || $imagePosition == 3) //Top or Bottom
					$adblockTotalHeight = $adblockTotalHeight + $textimageHeight + ($textimageMargin * 2); //Side margin space Top or Bottom
				}

				if($borderType == 2)
				$borderMinus = 0;
				else
				$borderMinus = 2;


				if($responsiveSupport == 1)
				{
					$previewSectionOuterWidth = $previewSectionOuterWidth - $borderMinus;//Outer border width minus

					$adblockTotalWidth = $adblockTotalWidth + ($adsMargin * 2) + $borderMinus;
					$adblockTotalHeight = $adblockTotalHeight + ($adsMargin * 2) + $borderMinus;


					$minimumSizeString.="<input type='hidden' name='minimumWidth' id='minimumWidth' value='".$adblockTotalWidth."' />";
					$minimumSizeString.="<input type='hidden' name='minimumHeight' id='minimumHeight' value='".$adblockTotalHeight."' />";
					$minimumSizeString.="<input type='hidden' name='textimageWidth' id='textimageWidth' value='".($textimageWidth + ($textimageMargin * 2))."' />";
					$minimumSizeString.="<input type='hidden' name='textimageHeight' id='textimageHeight' value='".($textimageHeight + ($textimageMargin * 2))."' />";

					if($previewSectionOuterWidth >= $adblockTotalWidth)
					{
						if($nativeAdsCount > 1)
						{
							$widthDivide  = $previewSectionOuterWidth / $adblockTotalWidth;

							if(intval($widthDivide) >= $nativeAdsCount)
							$nativeColumns = $nativeAdsCount;
							else
							$nativeColumns = intval($widthDivide);

							$adblockTotalWidth = $previewSectionOuterWidth / $nativeColumns;
						}
						else //Only one ad
						{
							$adblockTotalWidth = $previewSectionOuterWidth;
							$nativeColumns     = 1;
						}
					}
					else //Section width less than minimum width
					{
						if($previewSectionOuterWidth > 0)
						$adblockTotalWidth = $previewSectionOuterWidth;


						$nativeColumns     = 1;
					}

					$adblockWidth        	= $adblockTotalWidth - ($adsMargin * 2) - $borderMinus;
					$adblockHeight       	= $adblockTotalHeight - ($adsMargin * 2) - $borderMinus;

				}
				else
				{
					$adblockWidth        	= $adblockTotalWidth;
					$adblockHeight       	= $adblockTotalHeight;

					$nativeSingleAdWidth    = $adblockTotalWidth + ($adsMargin * 2) + $borderMinus;
					$nativeSingleAdHeight   = $adblockTotalHeight + ($adsMargin * 2) + $borderMinus;
					$minimumSizeString.="<input type='hidden' name='minimumWidth' id='minimumWidth' value='".$nativeSingleAdWidth."' />";
					$minimumSizeString.="<input type='hidden' name='minimumHeight' id='minimumHeight' value='".$nativeSingleAdHeight."' />";
				}
			}
		}


		//Font Result

	    $titleFont               = $fontResultData['title_font'];
	    $titleFontSize           = $fontResultData['title_size'];
	    $titleFontWeight         = $fontResultData['title_weight'];
	    $titleLineheight         = $fontResultData['title_lineheight'];
	    $titleDecoration         = $fontResultData['title_decoration'];

	    $descriptionFont         = $fontResultData['desc_font'];
	    $descriptionFontSize     = $fontResultData['desc_size'];
	    $descriptionFontWeight   = $fontResultData['desc_weight'];
	    $descriptionLineheight   = $fontResultData['desc_lineheight'];
	    $descriptionDecoration   = $fontResultData['desc_decoration'];

	    $urlFont                 = $fontResultData['url_font'];
	    $urlFontSize             = $fontResultData['url_size'];
	    $urlFontWeight           = $fontResultData['url_weight'];
	    $urlLineheight           = $fontResultData['url_lineheight'];
	    $urlDecoration           = $fontResultData['url_decoration'];

	    $creditFont              = $fontResultData['credit_font'];
	    $creditFontSize          = $fontResultData['credit_size'];
	    $creditFontWeight        = $fontResultData['credit_weight'];
	    $creditLineheight        = $fontResultData['credit_lineheight'];
	    $creditDecoration        = $fontResultData['credit_decoration'];

	    $buttonFont              = $fontResultData['button_font'];
	    $buttonFontSize          = $fontResultData['button_size'];
	    $buttonFontWeight        = $fontResultData['button_weight'];
	    $buttonLineheight        = $fontResultData['button_lineheight'];
	    $buttonDecoration        = $fontResultData['button_decoration'];


	    $headingFont             = $fontResultData['head_font'];
	    $headingFontSize         = $fontResultData['head_size'];
	    $headingFontWeight       = $fontResultData['head_weight'];
	    $headingLineheight       = $fontResultData['head_lineheight'];
	    $headingDecoration       = $fontResultData['head_decoration'];


	    //Theme Result

		$headingColor            = $themeResultData['heading_color'];
		$headingBackground       = $themeResultData['heading_background'];
		$headingHoverColor       = $themeResultData['heading_hover_color'];
	    $titleColor              = $themeResultData['title'];
	    $titleBackground         = $themeResultData['title_background'];
	    $titleHoverColor         = $themeResultData['title_hover_color'];
	    $descriptionColor        = $themeResultData['description'];
	    $descriptionBackground   = $themeResultData['description_background'];
	    $descriptionHoverColor   = $themeResultData['description_hover_color'];
	    $urlColor                = $themeResultData['url'];
	    $urlBackground           = $themeResultData['url_background'];
	   	$urlHoverColor           = $themeResultData['url_hover_color'];
	    $imageBackground         = $themeResultData['image_background'];
	    $creditColor             = $themeResultData['credit'];
	    $background              = $themeResultData['background'];
	    $borderColor             = $themeResultData['border'];
	    $buttonTextColor         = $themeResultData['button'];
	    $buttonColor             = $themeResultData['button_background'];
	    $buttonHoverColor        = $themeResultData['button_hover'];
	    $buttonBackground        = $themeResultData['cta_background'];


	    if($title_enabled == 1)
	    $sectionCount++;

	    if($description_enabled == 1)
	    $sectionCount++;

	    if($url_enabled == 1)
	    $sectionCount++;

		//Adblock width or height very high case
 		if(($adLayoutType == 1 || $adLayoutType == 11) && $displayAds == 0 && $layoutMaxWidth > 0 && $layoutMaxHeight > 0)
		{
			if($adblockWidth >= ($layoutMaxWidth + 50) && $adblockHeight >= ($layoutMaxHeight + 50)) //50px extra spaceing , Its not important
			{
				$singleAdSectionWidth   = $layoutMaxWidth;
				$singleAdSectionHeight  = $layoutMaxHeight;
				$adSizeChanged          = 1;
			}
			else if($adblockWidth >= ($layoutMaxWidth + 50))
			{
				$singleAdSectionWidth   = $layoutMaxWidth;
				$singleAdSectionHeight  = $adblockHeight - 10;
				$adSizeChanged          = 1;
			}
			else if($adblockHeight >= ($layoutMaxHeight + 50))
			{
				$singleAdSectionWidth   = $adblockWidth - 10;
				$singleAdSectionHeight  = $layoutMaxHeight;
				$adSizeChanged          = 1;
			}
		}

		if($adSizeChanged == 0)
		{
			$singleAdSectionWidth   = $adblockWidth;
			$singleAdSectionHeight  = $adblockHeight;
		}
		$sectionTotalWidth   = $singleAdSectionWidth;
	   	$sectionTotalHeight  = $singleAdSectionHeight;
	    if($sectionTotalWidth <= 0 || $sectionTotalHeight <= 0)
    	die;
		//From adcode $adLayoutType == 11 means text+image
		if($adLayoutType == 11)
		{
		  	if($textimageWidth > 0 && $textimageHeight > 0)
		   	{
				//$textimageBoxWidth   = $textimageWidth + ($textimageMargin * 2); //Side margin space left & right side


	    		if($textimageWidth >= $sectionTotalWidth)
	    		$textimageBoxWidth  = $sectionTotalWidth;
	    		else
	    		{
	    		    $textimageBoxWidth  = $textimageWidth + ($textimageMargin * 2); //Side margin space left & right side

	    		    if($textimageBoxWidth >= $sectionTotalWidth)
	    		    $textimageBoxWidth  = $textimageWidth;
	    		}



		    	//$textimageBoxHeight  = $textimageHeight + ($textimageMargin * 2); //Side margin space top & bottom side;

	    		if($textimageHeight >= $sectionTotalHeight)
	    		$textimageBoxHeight  = $sectionTotalHeight;
	    		else
	    		{
	    		    $textimageBoxHeight  = $textimageHeight + ($textimageMargin * 2); //Side margin space top & bottom side;

	    		    if($textimageBoxHeight >= $sectionTotalHeight)
	    		    $textimageBoxHeight  = $textimageHeight;
	    		}


	    		if($imagePosition == 0 || $imagePosition == 2) //Left or Right
	    		{
					if($textimageBoxHeight > $sectionTotalHeight)
					{
					    //$sectionTotalHeight = $textimageBoxHeight;
					}
					else
	    			$textimageBoxHeight  = $sectionTotalHeight;
	    		}
	    		else if($imagePosition == 1 || $imagePosition == 3) //Top or Bottom
	    		{
					if($textimageBoxWidth > $sectionTotalWidth)
					{
					    //$sectionTotalWidth = $textimageBoxWidth;
					}
					else
	    			$textimageBoxWidth   = $sectionTotalWidth;
	    		}



				if($CTA_position == 2 && $imagePosition == 2)
				$floatString = "float: none;";
				else
				$floatString = "float: left;";
	    	}
	    }



		$creditSection = "";

		if($creditText != "")
		{
			$creditSection.='<div id="creditIconDiv" class="creditTextFirst" onMouseOver="AdmarketCreditLoad();">'.$creditIcon.'</div>';

			$creditSection.='<div id="creditDiv" class="creditTextSecond" style="display:none;" onMouseOut="AdmarketCreditDisable();" ><a target="_blank"  href="'.$creditUrl.'">'.$creditText.'</a></div>';
		}


		if($displayAds == 0 && $creditText != "")
		$responseResult.=$creditSection;

		$responseResult.=$minimumSizeString;

		$iData = 0;
		
	foreach($adResultData as $key => $value)
	{
		$adID             = intval($value['adID']);
		$adType           = intval($value['adType']);
		$htmlAd           = intval($value['htmlAd']); //1 => 3rd party html otherwise 0
		$html5Ad          = intval($value['html5']);
		$retargetID       = intval($value['retargetID']);
		$expandable       = intval($value['expandable']);
		$CTASupport       = intval($value['cta_support']);
		$advertiserAd     = intval($value['advertiserAd']);
		$durationSeconds  = intval($value['duration_seconds']);

		if(isset($value['feedAds']))
		$feedAds 					= intval($value['feedAds']);
		else
		$feedAds 					= 0;

		if(isset($value['exchangeAds']))
		$exchangeAds 			= intval($value['exchangeAds']);
		else
		$exchangeAds 			= 0;
		if($feedAds == 1 || $exchangeAds == 1)
		$iDataString = "_".$iData;
		else
		$iDataString = "";


		$buttonTextDB     = $value['buttonText'];
		$html5SrcUrl      = $value['srcUrl'];
		$pixelUrl         = $value['pixelUrl'];     // From feed
		$impressionUrl    = $value['impressionUrl']; // From exchange
		$mediaUrl         = $value['mediaUrl'];
		$renderContent    = $this->mybase64_decode($value['renderContent']);
		$clickUrl 		  	= $value['clickUrl'];
		$mimeType         = $value['mime_type'];

		//For avoide url cache when request generate.
		if(($feedAds == 1 || $exchangeAds == 1) && $iData > 0)
		{
			if($pixelUrl != "")
			$pixelUrl.= "&cb=".$iData;

			if($impressionUrl != "")
			$impressionUrl.= "&cb=".$iData;
		}


		if($htmlAd == 1)  //3rd party html
		$descriptionText  = $value['description'];
		else
		$descriptionText  = '<p>'.$value['description'].'</p>';

		$titleText        = '<p>'.$value['title'].'</p>';
		$displayurlText   = '<p>'.$value['displayUrl'].'</p>';



		if($buttonTextDB == "")
		$buttonText = '<div class="CTADefaultButton"><a target="_blank" href="'.$clickUrl.'"><div>&rsaquo;</div></a></div>';
		else if($buttonTextDB != "")
		$buttonText = '<div class="CTAButton"><a target="_blank" href="'.$clickUrl.'">'.$buttonTextDB.'</a></div>';

    	$divBox 		  = "";


		if($adLayoutType == 11 && $textimageWidth > 0 && $textimageHeight > 0)
		{
			$divBox ='<div class="ad-layout-image" style="width:'.$textimageBoxWidth.'px;height:'.$textimageBoxHeight.'px;'.$floatString.'"><div class="ad-layout-image-inner" style="width:'.$textimageWidth.'px;height:'.$textimageHeight.'px;overflow:hidden;"><a target="_blank" href="'.$clickUrl.'">';

			if($mediaUrl != "")
			$divBox.='<img alt="Ad Banner" src="'.$mediaUrl.'" style="width:'.$textimageWidth.'px;height:'.$textimageHeight.'px;border : 0px;"  />';

			$divBox.='</a></div></div>';
		}

		$responseResult.='<div class="content-box-section" id="content-box-section-'.$adID.$iDataString.'" style="display: none;">';

		if($adLayoutType == 2)
		{
			$responseResult.='<div class="contentDivOuter" style="  width : '.$sectionTotalWidth.'px;height : '.$sectionTotalHeight.'px;">';

			if($htmlAd == 1)
			$responseResult.=$descriptionText;
			else if($adType == 7) //E-commerce
			$responseResult.=$this->get_ad_display_preview($adID,$clickUrl,$retargetID);
			else
			{
				if($html5Ad == 1)
				{
					if($adType == 2 && $expandable == 1)
					$responseResult.='<button type="button" class="expand-button" onclick="LoadExpandableData('.$adID.');">Expand</button>';

					$responseResult.='<a target="_blank" ';

					if($CTASupport == 0)
					$responseResult.=' href="'.$clickUrl.'" style="width : '.$sectionTotalWidth.'px;height : '.$sectionTotalHeight.'px;position:absolute;" ';

					if($adcodeDisplayType == 1) //Interstitial
					$responseResult.=' onclick="CloseSkipAd();" ';

					$responseResult.='></a>';

					$responseResult.='<iframe frameborder="0" src="'.$html5SrcUrl.'" allowtransparency="true" style="width : '.$sectionTotalWidth.'px;height : '.$sectionTotalHeight.'px;" scrolling="no"></iframe>';
				}
				else
				{
					if($adType == 2 && $expandable == 1)
					$responseResult.='<button type="button" class="expand-button" onclick="LoadExpandableData('.$adID.');">Expand</button>';
					if($renderContent != "")//Exchange or Feed banner response
					$responseResult.= $renderContent;
					else //Normal banner ads case
					{
					$responseResult.='<a target="_blank" href="'.$clickUrl.'" ';

					if($adcodeDisplayType == 1) //Interstitial
					$responseResult.=' onclick="CloseSkipAd();" ';

					$responseResult.='><img style="width : '.$sectionTotalWidth.'px;height : '.$sectionTotalHeight.'px;border:0px;" src="'.$mediaUrl.'" /></a>';
					}															

					//Need to check if below commented condition is required or not
					//Some 3rd parties provide pixel tracking data inside the html content
					//if($adcodeDisplayType != 1)
					{
						if($pixelUrl != "")
						$responseResult.='<img src="'.$pixelUrl.'" style="width: 0px;height: 0px;" />';

						if($impressionUrl != "")
						$responseResult.='<img src="'.$impressionUrl.'" style="width: 0px;height: 0px;" />';
					}
				}
			}

			$responseResult.='</div>';
		}
		else if($adLayoutType == 13)
		{
			$controls_enabled = Configuration::get_instance()->read('html5_video_controls_enabled');
			$autoplay_enabled = Configuration::get_instance()->read('html5_video_autoplay_enabled');
			$cpv_interval	  = intval(Configuration::get_instance()->read('html5_player_impression_tracking_interval'));
			$video_muted	  = Configuration::get_instance()->read('html5_video_mute_enabled');


			/* Don't remove
			$responseResult.='<!-- Click tag for all section
				<a id="video-outer-layer-'.$adID.'" target="_blank" href="'.$clickUrl.'" style="display:none;position: absolute;z-index: 1;">
				<div style="width: '.$sectionTotalWidth.'px;height: '.($sectionTotalHeight-40).'px;position: absolute;"></div>
			 	</a>
			 	-->';
			*/

			$responseResult.='<video style="width : '.$sectionTotalWidth.'px;height : '.$sectionTotalHeight.'px;"';

			if($video_muted ==1)
			$responseResult.=' muted ';

			if($controls_enabled ==1)
			$responseResult.=' controls ';

			if($autoplay_enabled ==1)
			$responseResult.=' autoplay ';

			if($advertiserAd == 0)
			$responseResult.=' onloadeddata = "TrackData(1,'.$durationSeconds.','.$cpv_interval.','.$advertiserAd.','.$adID.');" ';
			else
			{
				$responseResult.=' onplay = "TrackData(1,'.$durationSeconds.','.$cpv_interval.','.$advertiserAd.','.$adID.');" ';
				$responseResult.=' onpause = "TrackData(2,'.$durationSeconds.','.$cpv_interval.','.$advertiserAd.','.$adID.');" ';
			}

			$responseResult.='>
			<source src="'.$mediaUrl.'" type="'.$mimeType.'"></source>Your browser does not support HTML5 video
			</video><div class="video-link"><a target="_blank" href="'.$clickUrl.'">'.$displayurlText.'</a></div>';
		}
		else
		{
			$contentSectionWidth  = $sectionTotalWidth;
			$contentSectionHeight = $sectionTotalHeight;
			if($adLayoutType == 11)
			{
				if($imagePosition == 0 || $imagePosition == 2) //Left & Right or Text case
				$contentSectionWidth  = $contentSectionWidth - $textimageBoxWidth;
	    		else if($imagePosition == 1 || $imagePosition == 3) //Top or Bottom Text + Image case
    			$contentSectionHeight = $contentSectionHeight - $textimageBoxHeight;
			}


			if($CTA_position == 1) // Right side
	        {
	    	    $contentSectionWidth = $contentSectionWidth - $buttonSectionWidth;

	           if($contentSectionWidth < 0)
	           $contentSectionWidth = 0;


				$responseResult.='<div class="contentDivOuter" style="  width : '.$sectionTotalWidth.'px;height : '.$sectionTotalHeight.'px;">';

				if($imagePosition == 0 || $imagePosition == 1)
				$responseResult.=$divBox;
				$responseResult.='<div class="ad-layout-content" style="float:left;overflow:hidden; width : '.$contentSectionWidth.'px;height : '.$contentSectionHeight.'px;">';

				$responseResult.='<div class="ad-layout-content-inner">';

				if($title_enabled == 1)
				$responseResult.='<div class="ad-layout-title" ><a target="_blank" href="'.$clickUrl.'">'.$titleText.'</a></div>';

				if($description_enabled == 1)
				$responseResult.='<div class="ad-layout-description" ><a target="_blank" href="'.$clickUrl.'">'.$descriptionText.'</a></div>';

				if($url_enabled == 1)
				$responseResult.='<div class="ad-layout-url" ><a target="_blank" href="'.$clickUrl.'">'.$displayurlText.'</a></div>';

				$responseResult.='</div>';

				$responseResult.='</div>';


				$responseResult.='<div class="ad-layout-button" style="width : '.$buttonSectionWidth.'px;height : '.$contentSectionHeight.'px;">';

				$responseResult.=$buttonText;

				$responseResult.='</div>';


	        		if($imagePosition == 2 || $imagePosition == 3)
				$responseResult.=$divBox;
				if($pixelUrl != "")
				$responseResult.='<img src="'.$pixelUrl.'" style="width: 0px;height: 0px;" />';

				if($impressionUrl != "")
				$responseResult.='<img src="'.$impressionUrl.'" style="width: 0px;height: 0px;" />';
				$responseResult.='</div>';

			}
			else if($CTA_position == 0 || $CTA_position == 2) // No button or Bottom side
	        {

	            $contentSectionHeight = $contentSectionHeight - $buttonSectionHeight;

	            if($contentSectionHeight < 0)
	            $contentSectionHeight = 0;

				$responseResult.='<div class="contentDivOuter" style="  width : '.$sectionTotalWidth.'px;height : '.$sectionTotalHeight.'px;">';

				if($imagePosition == 0 || $imagePosition == 1)
				$responseResult.=$divBox;
				$responseResult.='<div class="ad-layout-content" style="float:left;overflow:hidden; width : '.$contentSectionWidth.'px;height : '.$contentSectionHeight.'px;">';

				$responseResult.='<div class="ad-layout-content-inner">';

				if($title_enabled == 1)
				$responseResult.='<div class="ad-layout-title" style="width : 100%;"><a target="_blank" href="'.$clickUrl.'">'.$titleText.'</a></div>';

				if($description_enabled == 1)
				$responseResult.='<div class="ad-layout-description" style="width : 100%;"><a target="_blank" href="'.$clickUrl.'">'.$descriptionText.'</a></div>';

				if($url_enabled == 1)
				$responseResult.='<div class="ad-layout-url" style="width : 100%;"><a target="_blank" href="'.$clickUrl.'">'.$displayurlText.'</a></div>';

				$responseResult.='</div>';

				$responseResult.='</div>';


				if($button_enabled == 1)
				{
					if($adLayoutType == 11 && ($imagePosition == 0 || $imagePosition == 2))
					$widthValue = $contentSectionWidth.'px';
					else
					$widthValue = '100%';


					$responseResult.='<div class="ad-layout-button" style="width : '.$widthValue.';height : '.$buttonSectionHeight.'px;">';

					$responseResult.=$buttonText;

					$responseResult.='</div>';
				}

	        	if($imagePosition == 2 || $imagePosition == 3)
				$responseResult.=$divBox;
				if($pixelUrl != "")
				$responseResult.='<img src="'.$pixelUrl.'" style="width: 0px;height: 0px;" />';

				if($impressionUrl != "")
				$responseResult.='<img src="'.$impressionUrl.'" style="width: 0px;height: 0px;" />';
				$responseResult.='</div>';

	        }
	        else if($CTA_position == 3) // Next to title
	        {
	            $titleSectionWidth = $contentSectionWidth - $buttonSectionWidth;

	            if($titleSectionWidth < 0)
	            $titleSectionWidth = 0;


				$responseResult.='<div class="contentDivOuter" style="  width : '.$sectionTotalWidth.'px;height : '.$sectionTotalHeight.'px;">';

				if($imagePosition == 0 || $imagePosition == 1)
				$responseResult.=$divBox;
				$responseResult.='<div class="ad-layout-content" style="float:left;overflow:hidden; width : '.$contentSectionWidth.'px;height : '.$contentSectionHeight.'px;">';
	        	$responseResult.='<div class="ad-layout-title" style="float:left;width : '.$titleSectionWidth.'px;"><a target="_blank" href="'.$clickUrl.'">'.$titleText.'</a></div><div class="ad-layout-button" style="width : '.$buttonSectionWidth.'px;">'.$buttonText.'</div>';

	            if($description_enabled == 1)
	            $responseResult.='<div class="ad-layout-description" style="width : 100%;"><a target="_blank" href="'.$clickUrl.'">'.$descriptionText.'</a></div>';

	            if($url_enabled == 1)
	            $responseResult.='<div class="ad-layout-url" style="width : 100%;"><a target="_blank" href="'.$clickUrl.'">'.$displayurlText.'</a></div>';

				$responseResult.='</div>';
	        		if($imagePosition == 2 || $imagePosition == 3)
				$responseResult.=$divBox;
				if($pixelUrl != "")
				$responseResult.='<img src="'.$pixelUrl.'" style="width: 0px;height: 0px;" />';
				if($impressionUrl != "")
				$responseResult.='<img src="'.$impressionUrl.'" style="width: 0px;height: 0px;" />';

	        	$responseResult.='</div>';
	        }
	        else if($CTA_position == 4) // Next to description
	        {
	            $descriptionSectionWidth = $contentSectionWidth - $buttonSectionWidth;

	            if($descriptionSectionWidth < 0)
	            $descriptionSectionWidth = 0;

				$responseResult.='<div class="contentDivOuter" style="  width : '.$sectionTotalWidth.'px;height : '.$sectionTotalHeight.'px;">';

				if($imagePosition == 0 || $imagePosition == 1)
				$responseResult.=$divBox;

				$responseResult.='<div class="ad-layout-content" style="float:left;overflow:hidden; width : '.$contentSectionWidth.'px;height : '.$contentSectionHeight.'px;">';
	        	if($title_enabled == 1)
				$responseResult.='<div class="ad-layout-title" style="width : 100%;"><a target="_blank" href="'.$clickUrl.'">'.$titleText.'</a></div>';


	        	$responseResult.='<div class="ad-layout-description" style="float:left;width : '.$descriptionSectionWidth.'px;"><a target="_blank" href="'.$clickUrl.'">'.$descriptionText.'</a></div><div class="ad-layout-button" style="width : '.$buttonSectionWidth.'px;">'.$buttonText.'</div>';


	            if($url_enabled == 1)
	            $responseResult.='<div class="ad-layout-url" style="width : 100%;"><a target="_blank" href="'.$clickUrl.'">'.$displayurlText.'</a></div>';


				$responseResult.='</div>';
	        		if($imagePosition == 2 || $imagePosition == 3)
				$responseResult.=$divBox;
				if($pixelUrl != "")
				$responseResult.='<img src="'.$pixelUrl.'" style="width: 0px;height: 0px;" />';

				if($impressionUrl != "")
				$responseResult.='<img src="'.$impressionUrl.'" style="width: 0px;height: 0px;" />';
	   			$responseResult.='</div>';
	        }
	        else if($CTA_position == 5) // Next to displayurl
	        {
	            $urlSectionWidth = $contentSectionWidth - $buttonSectionWidth;

	            if($urlSectionWidth < 0)
	            $urlSectionWidth = 0;


				$responseResult.='<div class="contentDivOuter" style="  width : '.$sectionTotalWidth.'px;height : '.$sectionTotalHeight.'px;">';

				if($imagePosition == 0 || $imagePosition == 1)
				$responseResult.=$divBox;
				$responseResult.='<div class="ad-layout-content" style="float:left;overflow:hidden; width : '.$contentSectionWidth.'px;height : '.$contentSectionHeight.'px;">';
	        	if($title_enabled == 1)
				$responseResult.='<div class="ad-layout-title" style="width : 100%;"><a target="_blank" href="'.$clickUrl.'">'.$titleText.'</a></div>';

	            if($description_enabled == 1)
	            $responseResult.='<div class="ad-layout-description" style="width : 100%;"><a target="_blank" href="'.$clickUrl.'">'.$descriptionText.'</a></div>';

	        	$responseResult.='<div class="ad-layout-url" style="float:left;width : '.$urlSectionWidth.'px;"><a target="_blank" href="'.$clickUrl.'">'.$displayurlText.'</a></div><div class="ad-layout-button" style="width : '.$buttonSectionWidth.'px;">'.$buttonText.'</div>';

			$responseResult.='</div>';
	        	if($imagePosition == 2 || $imagePosition == 3)
				$responseResult.=$divBox;
				if($pixelUrl != "")
				$responseResult.='<img src="'.$pixelUrl.'" style="width: 0px;height: 0px;" />';
				if($impressionUrl != "")
				$responseResult.='<img src="'.$impressionUrl.'" style="width: 0px;height: 0px;" />';

	        	$responseResult.='</div>';
	        }
			else if($CTA_position == 6) // Next to description & displayurl
	        {
	            $descurlSectionWidth = $contentSectionWidth - $buttonSectionWidth;

	            if($descurlSectionWidth < 0)
	            $descurlSectionWidth = 0;


				$responseResult.='<div class="contentDivOuter" style="  width : '.$sectionTotalWidth.'px;height : '.$sectionTotalHeight.'px;">';

				if($imagePosition == 0 || $imagePosition == 1)
				$responseResult.=$divBox;
				$responseResult.='<div class="ad-layout-content" style="float:left;overflow:hidden; width : '.$contentSectionWidth.'px;height : '.$contentSectionHeight.'px;">';
	        	if($title_enabled == 1)
				$responseResult.='<div class="ad-layout-title" style="width : 100%;"><a target="_blank" href="'.$clickUrl.'">'.$titleText.'</a></div>';


				$responseResult.='<div class="ad-layout-content-inner" style="float:left;width : '.$descurlSectionWidth.'px;">';
	            $responseResult.='<div class="ad-layout-description" style="width : 100%;"><a target="_blank" href="'.$clickUrl.'">'.$descriptionText.'</a></div>';
	            $responseResult.='<div class="ad-layout-url" style="width : 100%;"><a target="_blank" href="'.$clickUrl.'">'.$displayurlText.'</a></div>';
	            $responseResult.='</div>';

	        	$responseResult.='<div class="ad-layout-button" style="width : '.$buttonSectionWidth.'px;">'.$buttonText.'</div>';

				$responseResult.='</div>';

	        		if($imagePosition == 2 || $imagePosition == 3)
				$responseResult.=$divBox;
				if($pixelUrl != "")
				$responseResult.='<img src="'.$pixelUrl.'" style="width: 0px;height: 0px;" />';

				if($impressionUrl != "")
				$responseResult.='<img src="'.$impressionUrl.'" style="width: 0px;height: 0px;" />';
	        	$responseResult.='</div>';
	        }




		}

		$responseResult.='</div>';

		if($feedAds == 1 || $exchangeAds == 1)
		$iData++;
	}


	$adContentResult = $responseResult;

	$responseResult  = "";

	$responseResult.='<style type="text/css">';

	if($adLayoutType == 1 || $adLayoutType == 11)
    {
		if($CTA_position == 1)
		{
	        $contentWidth            = $contentSectionWidth;

	        if($contentWidth < 0)
	        $contentWidth = 0;


	        $contentHeight  = $contentSectionHeight;


	        if($contentSlide == 0)
	        $singleRowHeight = $contentHeight / $sectionCount;
	        else
	        $singleRowHeight = $contentHeight;


	        if($contentSlide == 1)
	        {
	        	$responseResult.='.ad-layout-content-inner {';

	            if($slideDirection == 1)
	            $responseResult.='width : '.($contentWidth * $sectionCount).'px;';
	            else
	            $responseResult.='height : '.($contentHeight * $sectionCount).'px;';

	        	$responseResult.='}';

	        }
		}
		else if($CTA_position == 0 || $CTA_position == 2)
		{
	        $contentHeight       = $contentSectionHeight;

	        $contentWidth        = $sectionTotalWidth;

	        if($contentHeight < 0)
	        $contentHeight = 0;

	        if($contentSlide == 0)
	        $singleRowHeight = $contentHeight / $sectionCount;
	        else
	        $singleRowHeight = $contentHeight;

	        if($contentSlide == 1)
	        {
	        	$responseResult.='.ad-layout-content-inner {';

	            if($slideDirection == 1)
	            $responseResult.='width : '.($contentWidth * $sectionCount).'px;';
	            else
	            $responseResult.='height : '.($contentHeight * $sectionCount).'px;';

	        	$responseResult.='}';
	        }
		}
		else
		{
			$contentHeight   = $contentSectionHeight;

			$singleRowHeight = $contentHeight / $sectionCount;
		}



		if($title_enabled == 1)
		{
       		$responseResult.='.ad-layout-title {';

	    	$responseResult.='box-sizing        : border-box !important; ';
	    	$responseResult.='overflow          : hidden;';

			if($title_border_bottom == 1 && $borderType != 2)
			$responseResult.='border-bottom : 1px solid; ';

			$responseResult.='border-color     : '.$borderColor.';';
			$responseResult.='background-color : '.$titleBackground.';';

			$responseResult.='display          : flex;';
			$responseResult.='align-items      : center;';


			if($CTA_position == 1)
			{
	            if($contentSlide == 1)
	            {
	            	$responseResult.='width 		: '.$contentWidth.'px;';
	            	$responseResult.='float      	: left;';
	            }

	            $responseResult.='height 			: '.$singleRowHeight.'px;';
	        }
	        else if($CTA_position == 0 || $CTA_position == 2)
	        {
	            if($contentSlide == 1)
	            {
	            	if($slideDirection == 1) //Horizontal
	            	$responseResult.='width 		: '.$contentWidth.'px !important;';

	            	$responseResult.='height 		: '.$contentHeight.'px;';
	            	$responseResult.='float      	: left;';
	            }
	            else
            	$responseResult.='height 		: '.$singleRowHeight.'px;';
	        }
	        else
           	$responseResult.='height 		: '.$singleRowHeight.'px;';

       		$responseResult.='}';


	  		$responseResult.='.ad-layout-title a {';

			$responseResult.='font-family 		: '.$titleFont.';';
			$responseResult.='font-size   		: '.$titleFontSize.'px;';
			$responseResult.='line-height 		: '.$titleLineheight.'px;';
			$responseResult.='font-weight       : '.$titleFontWeight.';';
	    	$responseResult.='text-decoration 	: '.$titleDecoration.';';
			$responseResult.='color            : '.$titleColor.';';
	    	$responseResult.='word-break        : break-word;';
	    	$responseResult.='max-height 		: '.$singleRowHeight.'px;';



			if($adLayoutType != 11 || ($adLayoutType == 11 && ($imagePosition == 0 || $imagePosition == 2)))  //Left or Right
			$responseResult.='padding-left 		: 5px;';


			if($adLayoutType == 11 && ($imagePosition == 1 || $imagePosition == 3))  //Top or Bottom
			$responseResult.='margin 		    : 0px auto;';


	   		$responseResult.='}';



			$responseResult.='.ad-layout-title a:hover {';

			$responseResult.='color             : '.$titleHoverColor.';';

	   		$responseResult.='}';

			$responseResult.='.ad-layout-title p {';

			if($adLayoutType == 11 && ($imagePosition == 1 || $imagePosition == 3))  //Top or Bottom
			$responseResult.='text-align	    : center;';
			$responseResult.='padding		: 0px;';
			$responseResult.='margin		: 0px;';

	   		$responseResult.='}';


		}




		if($description_enabled == 1)
		{
       		$responseResult.='.ad-layout-description {';

	    	$responseResult.='box-sizing        : border-box !important; ';
	    	$responseResult.='overflow          : hidden;';

			if($description_border_bottom == 1 && $borderType != 2)
			$responseResult.=' border-bottom 	: 1px solid; ';

			$responseResult.='border-color 		: '.$borderColor.';';
			$responseResult.='background-color  : '.$descriptionBackground.';';

			$responseResult.='display 			: flex;';
			$responseResult.='align-items 		: center;';


			if($CTA_position == 1)
			{
	            if($contentSlide == 1)
	            {
	            	$responseResult.='width 		: '.$contentWidth.'px;';
	            	$responseResult.='float      	: left;';
	            }

	            $responseResult.='height 			: '.$singleRowHeight.'px;';
	        }
	        else if($CTA_position == 0 || $CTA_position == 2)
	        {
	            if($contentSlide == 1)
	            {
	            	if($slideDirection == 1) //Horizontal
	            	$responseResult.='width 		: '.$contentWidth.'px !important;';

	            	$responseResult.='height 		: '.$contentHeight.'px;';
	            	$responseResult.='float      	: left;';
	            }
	            else
            	$responseResult.='height 		: '.$singleRowHeight.'px;';
	        }
	        else
           	$responseResult.='height 		: '.$singleRowHeight.'px;';

			$responseResult.='}';

			$responseResult.='.ad-layout-description a {';

       		$responseResult.='font-family 		: '.$descriptionFont.';';
			$responseResult.='font-size 		: '.$descriptionFontSize.'px;';
			$responseResult.='line-height 		: '.$descriptionLineheight.'px;';
			$responseResult.='font-weight       : '.$descriptionFontWeight.';';
	    	$responseResult.='text-decoration 	: '.$descriptionDecoration.';';
			$responseResult.='color 			: '.$descriptionColor.';';
	    	$responseResult.='word-break        : break-word;';
	    	$responseResult.='max-height 		: '.$singleRowHeight.'px;';


			if($adLayoutType != 11 || ($adLayoutType == 11 && ($imagePosition == 0 || $imagePosition == 2)))  //Left or Right
			$responseResult.='padding-left 		: 5px;';

			if($adLayoutType == 11 && ($imagePosition == 1 || $imagePosition == 3))  //Top or Bottom
			$responseResult.='margin 		    : 0px auto;';

			$responseResult.='}';



			$responseResult.='.ad-layout-description a:hover {';

			$responseResult.='color             : '.$descriptionHoverColor.';';

	   		$responseResult.='}';


			$responseResult.='.ad-layout-description p {';

			if($adLayoutType == 11 && ($imagePosition == 1 || $imagePosition == 3))  //Top or Bottom
			$responseResult.='text-align	    : center;';

			$responseResult.='padding	     : 0px;';
			$responseResult.='margin	     : 0px;';
	   		$responseResult.='}';

       	}


		if($url_enabled == 1)
		{
       		$responseResult.='.ad-layout-url {';

	    	$responseResult.='box-sizing        : border-box !important; ';
	    	$responseResult.='overflow          : hidden;';


			if($displayurl_border_bottom == 1 && $borderType != 2)
			$responseResult.=' border-bottom 	: 1px solid; ';

			$responseResult.='border-color 		: '.$borderColor.';';
			$responseResult.='background-color 	: '.$urlBackground.';';


			$responseResult.='display 			: flex;';
			$responseResult.='align-items 		: center;';

			if($CTA_position == 1)
			{
	            if($contentSlide == 1)
	            {
	            	$responseResult.='width 		: '.$contentWidth.'px;';
	            	$responseResult.='float      	: left;';
	            }

	            $responseResult.='height 			: '.$singleRowHeight.'px;';
	        }
	        else if($CTA_position == 0 || $CTA_position == 2)
	        {
	            if($contentSlide == 1)
	            {
	            	if($slideDirection == 1) //Horizontal
	            	$responseResult.='width 		: '.$contentWidth.'px !important;';

	            	$responseResult.='height 		: '.$contentHeight.'px;';
	            	$responseResult.='float      	: left;';
	            }
	            else
            	$responseResult.='height 		: '.$singleRowHeight.'px;';
	        }
	        else
           	$responseResult.='height 		: '.$singleRowHeight.'px;';

       		$responseResult.='}';


       		$responseResult.='.ad-layout-url a {';

       		$responseResult.='font-family 		: '.$urlFont.';';
			$responseResult.='font-size 		: '.$urlFontSize.'px;';
			$responseResult.='line-height 		: '.$urlLineheight.'px;';
			$responseResult.='font-weight       : '.$urlFontWeight.';';
	    	$responseResult.='text-decoration 	: '.$urlDecoration.';';
	    	$responseResult.='max-height 		: '.$singleRowHeight.'px;';

			$responseResult.='color 			: '.$urlColor.';';
			$responseResult.='white-space       : nowrap;';


			if($adLayoutType != 11 || ($adLayoutType == 11 && ($imagePosition == 0 || $imagePosition == 2)))  //Left or Right
			$responseResult.='padding-left 		: 5px;';

			if($adLayoutType == 11 && ($imagePosition == 1 || $imagePosition == 3))  //Top or Bottom
			$responseResult.='margin 		    : 0px auto;';



       		$responseResult.='}';


			$responseResult.='.ad-layout-url a:hover {';

			$responseResult.='color             : '.$urlHoverColor.';';

	   		$responseResult.='}';


			$responseResult.='.ad-layout-url p {';

			if($adLayoutType == 11 && ($imagePosition == 1 || $imagePosition == 3))  //Top or Bottom
			$responseResult.='text-align	    : center;';
			$responseResult.='padding	 	: 0px;';
			$responseResult.='margin		: 0px;';

	   		$responseResult.='}';



       	}


		if($button_enabled == 1)
		{
   			$responseResult.='.ad-layout-button {';




	        if($CTA_position == 3)
	        $responseResult.='background-color 	: '.$titleBackground.';';
	        else if($CTA_position == 4)
	        $responseResult.='background-color 	: '.$descriptionBackground.';';
	        else if($CTA_position == 5)
	        $responseResult.='background-color 	: '.$urlBackground.';';
	        else
	        $responseResult.='background-color 	: '.$buttonBackground.';';

	    	$responseResult.='float 			: left;';

			$responseResult.='display 			: flex;';
			$responseResult.='align-items 		: center;';
	    	$responseResult.='box-sizing        : border-box !important; ';


			if($CTA_position == 2)
			$responseResult.='height 		: '.$buttonSectionHeight.'px;';
			else if($CTA_position == 6)
			$responseResult.='height 		: '.($singleRowHeight * 2).'px;';
			else
			{
				if(($CTA_position == 3 && $title_border_bottom == 1) || ($CTA_position == 4 && $description_border_bottom == 1))
				{
					$responseResult.='height 		: '.$singleRowHeight.'px;';

					if($borderType != 2)
					$responseResult.='border-bottom : 1px solid; ';
				}
				else if($CTA_position == 3 || $CTA_position == 4 || $CTA_position == 5)
            	$responseResult.='height 		: '.$singleRowHeight.'px;';
			}


			$responseResult.='border-color 		: '.$borderColor.' !important;';

   			$responseResult.='}';


   			$responseResult.='.ad-layout-button div {';

			$responseResult.='margin       : 0px auto;';

   			$responseResult.='}';

			$responseResult.='.CTAButton {';

			$responseResult.='background-color 	: '.$buttonColor.';';
			$responseResult.='padding 			: '.$CTA_padding_vertical.'px '.$CTA_padding_horizontal.'px;';
			$responseResult.='border-radius 	: '.$CTA_border_radius.'px;';

			$responseResult.='cursor  : pointer;';

   			$responseResult.='}';


			$responseResult.='.CTADefaultButton {';

			$responseResult.='background-color 	: '.$buttonColor.';';

			$responseResult.='box-shadow        : 0 0 2px 0 rgba(0,0,0,0.12), 0 2px 2px 0 rgba(0,0,0,0.24);';
			$responseResult.='text-shadow       : 1px 1px 0 rgba(255,255,255,0.1);';

			if(time() % 2 == 0)
			$responseResult.='border-radius : 50%;';
		    else
			$responseResult.='border-radius : 5px;';

			$responseResult.='cursor  : pointer;';

   			$responseResult.='}';



			$responseResult.='.CTAButton a {';

			$responseResult.='font-family 		: '.$buttonFont.';';
			$responseResult.='font-size 		: '.$buttonFontSize.'px;';
			$responseResult.='line-height 		: '.$buttonLineheight.'px;';
			$responseResult.='font-weight       : '.$buttonFontWeight.';';
	    	$responseResult.='text-decoration 	: '.$buttonDecoration.';';
	    	$responseResult.='white-space       : nowrap;';
			$responseResult.='color 			: '.$buttonTextColor.';';

			$responseResult.='}';


			$responseResult.='.CTADefaultButton a {';

			$responseResult.='display           : flex;';
			$responseResult.='align-items       : center;';
			$responseResult.='text-decoration 	: none;';


			if($CTA_position == 1)
			$buttonSize = $buttonSectionWidth;
			else if($CTA_position == 2)
			$buttonSize = $buttonSectionHeight;
			else if($CTA_position == 6)
			$buttonSize = ($singleRowHeight * 2);
			else
			$buttonSize = $singleRowHeight;


			if($buttonSize >= 50)
			$buttonSize = 45;
			else
			$buttonSize = $buttonSize - ($buttonSize * 20)/100;

			$responseResult.='height 		: '.$buttonSize.'px;';	//Width & Height are same
			$responseResult.='width         : '.$buttonSize.'px;';	//Width & Height are same

			$responseResult.='}';


			$responseResult.='.CTADefaultButton div {';

			$responseResult.='color 			: '.$buttonTextColor.';';
			$responseResult.='font-size 		: 30px;';
			$responseResult.='font-weight       : bold;';
			$responseResult.='margin-top        : -4px !important;';
			$responseResult.='padding-left      : 2px;';

			$responseResult.='}';

		}

		}


		$responseResult.='.contentDivOuter {';

		$responseResult.='float             : left;';
		$responseResult.='overflow          : hidden;';

		if($adSizeChanged == 1)
		{
			$responseResult.='margin        	: 0px auto; ';
			$responseResult.='box-sizing        : content-box !important; ';
			$responseResult.='border-color 		: '.$borderColor.' !important;';
		    if($borderType == 0 || $borderType == 1)
		    $responseResult.='border 			: 1px solid;';
		    else
		    $responseResult.='border 			: 0px;';
			if($borderType == 0)
			$responseResult.='border-radius 	: 10px;';
			$responseResult.='box-shadow        : 0 0 2px 0 rgba(0,0,0,0.12), 0 2px 2px 0 rgba(0,0,0,0.24);';
		}
		$responseResult.='}';

		$responseResult.='.ad-layout-content-inner {';

		$responseResult.='position          : relative;';
		$responseResult.='overflow          : hidden;';
		$responseResult.='left              : 0px;';
		$responseResult.='top               : 0px;';

		$responseResult.='}';



		$responseResult.='.content-box-section {';

		if($displayAds == 1 && $adLayoutType == 11)
		{
			if($textimageBoxWidth > $adblockWidth)
			$responseResult.='width 			: '.$textimageBoxWidth.'px;';
			else
		$responseResult.='width 			: '.$adblockWidth.'px;';
			if($textimageBoxHeight > $adblockHeight)
			$responseResult.='height 			: '.$textimageBoxHeight.'px;';
			else
		$responseResult.='height 			: '.$adblockHeight.'px;';
		}
		else
		{
			$responseResult.='width 			: '.$adblockWidth.'px;';
			$responseResult.='height 			: '.$adblockHeight.'px;';
		}
		$responseResult.='border-color 		: '.$borderColor.' !important;';
		$responseResult.='background-color 	: '.$background.';';
	    $responseResult.='box-sizing        : content-box !important; ';
	    $responseResult.='table-layout      : fixed; ';

		if($adSizeChanged == 1)
		{
			$responseResult.='display 			: flex;';
			$responseResult.='align-items 		: center;';
		}

		if($displayAds == 1)
		{
			$responseResult.='float 			: left;';
			$responseResult.='margin 			: '.$adsMargin.'px;';
		}

	    if(($borderType == 0 || $borderType == 1) && ($adLayoutType == 1 || $adLayoutType == 11))
	    $responseResult.='border 			: 1px solid;';
	    else
	    $responseResult.='border 			: 0px;';


		if($borderType == 0)
		$responseResult.='border-radius 	: 10px;';

		$responseResult.='overflow          : hidden;';
		$responseResult.='position          : relative;';

		$responseResult.='}';


		if($displayAds == 1)
		{
			$responseResult.='.native-outer-div {';

			$responseResult.='border-color 		: '.$borderColor.';';
			$responseResult.='box-sizing        : content-box !important; ';


			if($borderType == 2)
			$outerBorderWidth = 0;
			else
			$outerBorderWidth = 2;

			if($responsiveSupport == 0)
			$responseResult.='width 	: '.(($nativeSingleAdWidth * $nativeColumns)+0.1).'px;';//0.1 for special adjustment
			else
			$responseResult.='width 	: '.$previewSectionOuterWidth.'px;';


			$responseResult.='overflow 			: hidden;';
			$responseResult.='position 			: relative;';


		    if($borderType == 0 || $borderType == 1)
		    $responseResult.='border 			: 1px solid;';
		    else
		    $responseResult.='border 			: 0px;';


			if($borderType == 0)
			$responseResult.='border-radius 	: 10px;';

			$responseResult.='}';


			$responseResult.='.native-head {';

			$responseResult.='display 			: flex;';
			$responseResult.='align-items 		: center;';
			$responseResult.='background-color 	: '.$headingBackground.';';

			$responseResult.='}';


			$responseResult.='.native-head h4 {';

       		$responseResult.='font-family 		: '.$headingFont.';';
			$responseResult.='font-size 		: '.$headingFontSize.'px;';
			$responseResult.='line-height 		: '.$headingLineheight.'px;';


			$responseResult.='font-weight       : '.$headingFontWeight.';';
	    	$responseResult.='text-decoration 	: '.$headingDecoration.';';

			$responseResult.='color 	        : '.$headingColor.';';
			$responseResult.='margin-left 		: 5px;';
			$responseResult.='cursor 		    : default;';

			$responseResult.='}';


			$responseResult.='.native-head h4:hover {';

			$responseResult.='color 	        : '.$headingHoverColor.';';

			$responseResult.='}';
		}



		if($adLayoutType == 11)
		{
			$responseResult.='.ad-layout-image {';
			$responseResult.='background-color 	: '.$imageBackground.';';

			if($imagePosition == 0 && $borderType != 2)
			$responseResult.='border-right 		: 1px solid;';
			else if($imagePosition == 2 && $borderType != 2)
			$responseResult.='border-left 		: 1px solid;';
			else if($imagePosition == 1 && $borderType != 2)
			$responseResult.='border-bottom     : 1px solid;';
			else if($imagePosition == 3 && $borderType != 2)
			$responseResult.='border-top        : 1px solid;';

			$responseResult.='border-color      : '.$borderColor.';';

			$responseResult.='box-sizing        : border-box !important;';

			$responseResult.='display 		    : flex;';
			$responseResult.='align-items 	    : center;';

			$responseResult.='}';


			$responseResult.='.ad-layout-image-inner {';
			$responseResult.='background-color 	: '.$borderColor.';';
			$responseResult.='margin 			: 0px auto;';
			$responseResult.='}';
		}


		$responseResult.='.creditTextFirst {';

		$responseResult.='width 			: '.$creditLineheight.'px;';
		$responseResult.='line-height 	    : '.$creditLineheight.'px;';
		$responseResult.='font-family 		: '.$creditFont.';';
		$responseResult.='font-size 		: '.$creditFontSize.'px;';
		$responseResult.='font-weight       : '.$creditFontWeight.';';

		$responseResult.='text-align       : center;';
		$responseResult.='vertical-align   : middle;';
		$responseResult.='z-index          : 1;';

		$responseResult.='color 		   : '.$creditColor.';';
		$responseResult.='background-color : '.$borderColor.';';

		if($borderType == 0)
		$responseResult.='border-radius    : '.($creditLineheight/2).'px;';

		$responseResult.='position         : absolute;';


		if($creditPositioning == 1) //Top
		$responseResult.='top              : 0px;';
		else if($creditPositioning == 0) //Bottom
		$responseResult.='bottom           : 0px;';


		if($creditAlignment == 1) //Right
		$responseResult.='right            : 0px;';
		else if($creditAlignment == 0) //Left
		$responseResult.='left             : 0px;';

		$responseResult.='}';




		$responseResult.='.creditTextSecond {';

		if($creditType == 0)
		{
			$responseResult.='line-height 	    : '.$creditLineheight.'px;';

			$responseResult.='background-color  : '.$borderColor.';';
			$responseResult.='padding           : 0px 2px;';
		}

		$responseResult.='max-width 			: '.($adblockWidth - 10).'px;';

		$responseResult.='overflow              : hidden;';
		$responseResult.='white-space           : nowrap;';
		$responseResult.='margin                : 0px;';
		$responseResult.='z-index               : 2;';

		$responseResult.='position              : absolute;';

		if($creditPositioning == 1) //Top
		$responseResult.='top                   : 0px;';
		else if($creditPositioning == 0) //Bottom
		$responseResult.='bottom                : 0px;';


		if($creditAlignment == 1) //Right
		{
			$responseResult.='right             : 0px;';
			$responseResult.='text-align        : right;';

			if($borderType == 0)
			{
				if($creditPositioning == 1) //Top
				$responseResult.='border-radius  : 0px 10px 0px 10px;';
				else
				$responseResult.='border-radius  : 10px 0px 10px 0px;';
			}
		}
		else if($creditAlignment == 0) //Left
		{
			$responseResult.='left               : 0px;';
			$responseResult.='text-align         : left;';

			if($borderType == 0)
			{
				if($creditPositioning == 1) //Top
				$responseResult.='border-radius  : 10px 0px 10px 0px;';
				else
				$responseResult.='border-radius  : 0px 10px 0px 10px;';
			}
		}

		$responseResult.='}';


		if($displayAds == 1 && $creditPositioning == 1 && $creditAlignment == 1)
		{
			$responseResult.='.creditTextSecond {';
			$responseResult.='top            : unset;';
			$responseResult.='right          : 5px;';
			$responseResult.='}';
		}

		$responseResult.='.creditTextSecond a {';

		$responseResult.='font-family 		: '.$creditFont.';';
		$responseResult.='font-size 		: '.$creditFontSize.'px;';
		$responseResult.='color 			: '.$creditColor.';';
		$responseResult.='font-weight       : '.$creditFontWeight.';';
	    $responseResult.='text-decoration 	: '.$creditDecoration.';';
	    $responseResult.='white-space       : nowrap;';

		$responseResult.='}';


		if($adcodeDisplayType == 1)
		{
			$skipposition = Configuration::get_instance()->read('skip_button_position');

			$responseResult.='.skip-div {';

			$responseResult.=' position   	: absolute; ';
			$responseResult.=' z-index   	: 1; ';

			if($skipposition == 0)
			{
				$responseResult.=' top  	: 0px; ';
				$responseResult.=' left 	: 0px; ';
			}
			else if($skipposition == 1)
			{
				$responseResult.=' top  	: 0px; ';
				$responseResult.=' right  	: 0px; ';
			}
			else if($skipposition == 2)
			{
				$responseResult.=' bottom  	: 0px; ';
				$responseResult.=' right  	: 0px; ';
			}
			else if($skipposition == 3)
			{
				$responseResult.=' bottom  	: 0px; ';
				$responseResult.=' left  	: 0px; ';
			}
			$responseResult.=' } ';

			$responseResult.='.skip-button {';

			$responseResult.='float 		: left;';
			$responseResult.='cursor 		: pointer;';
			$responseResult.='background 	: url('.BASE.'images/skipad.png);';
			$responseResult.='height 		: 30px;';
			$responseResult.='width 		: 100px;';

			$responseResult.='}';


			$responseResult.='.skip-counter {';

			if($skipposition == 0 || $skipposition == 3)
			$responseResult.='float 			: right;';
		    else
			$responseResult.='float 			: left;';


			$responseResult.='border 			: 1px solid #FFFFFF;';
			$responseResult.='background-color 	: '.$borderColor.';';
			$responseResult.='color 			:'.$creditColor.';';
			$responseResult.='min-width 		: 15px;';
			$responseResult.='height 		    : 19px;';
			$responseResult.='padding 			: 4.5px 5px;';
			$responseResult.='text-align 		: center;';

			$responseResult.='}';
		}


		$responseResult.='.expand-button {';
		$responseResult.='cursor 			: pointer;';
		$responseResult.='position 			: absolute;';
		$responseResult.='top 				: 15px;';
		$responseResult.='right 			: 5px;';
		$responseResult.='padding 			: 5px;';
		$responseResult.='background-color  : '.$borderColor.';';
		$responseResult.='color 			: '.$creditColor.';';
		$responseResult.='border 			: 1px solid '.$creditColor.';';
		$responseResult.='}';


		if($adLayoutType == 13)
		{
			$responseResult.='.video-link {';

			$responseResult.='position         : absolute;';
			$responseResult.='top              : 1px;';
			$responseResult.='left             : 1px;';
			$responseResult.='background-color : '.$borderColor.';';
			$responseResult.='padding          : 2px;';

			$responseResult.='}';


			$responseResult.='.video-link a {';

			$responseResult.='color 		  : '.$creditColor.';';
			$responseResult.='font-size       : '.$creditFontSize.'px;';
			$responseResult.='font-family 	  : '.$creditFont.';';
			$responseResult.='text-decoration : none;';
			$responseResult.='outline         : none;';

			$responseResult.='}';
			$responseResult.='.video-link p {';
			$responseResult.='padding	 	: 0px;';
			$responseResult.='margin		: 0px;';
			$responseResult.='}';
		}


		$responseResult.='</style>';

		$adStyleResult   = $responseResult;

		$responseResult  = "";

		if($adLayoutType == 1 || $adLayoutType == 11)
		{
			$responseResult.='<script type="text/javascript">
							  $(document).ready(function() {';



			if($contentSlide == 1 && ($CTA_position == 0 || $CTA_position == 1 || $CTA_position == 2))
			{
				$responseResult.='startContentSlide('.$contentWidth.','.$contentHeight.','.$CTA_position.','.$sectionCount.','.$contentSlide.','.$slideDirection.','.$slideDuration.');';
			}

			if($button_enabled == 1)
			{
				$responseResult.='$(".CTAButton").hover(function(){
	    						  $(this).css("background-color", "'.$buttonHoverColor.'");
	        					  }, function(){
	          					  $(this).css("background-color", "'.$buttonColor.'");
	        					  });';

				$responseResult.='$(".CTADefaultButton").hover(function(){
	    						  $(this).css("background-color", "'.$buttonHoverColor.'");
	        					  }, function(){
	          					  $(this).css("background-color", "'.$buttonColor.'");
	        					  });';
			}

			$responseResult.='});
						     </script>';
		}

		$adScriptResult = $responseResult;

		return $adContentResult.$adStyleResult.$adScriptResult;
	}

	function get_site_name($sid)
	{
		$db=DAL::get_instance();

		$name=$db->read_single_column("SELECT url FROM ".TABLE_PREFIX."sites WHERE id=?",array($sid));

		return $name;
	}

	function get_processed_pop_ads_result(
											$pid, 
											$resquery, 
											$adcodeID,
											$pop_up_support,
											$pop_window_width,
											$pop_window_height,
											$originalpopad,
											$sid,
											$deviceType,
											$osname,
											$browsername,
											$catid,		
											$geo_country,									
											$pop_specific_profit = 0, 											
											$referral_enabled = 0, 
											$adv_ref_enabled = 0, 
											$countrywise_pricing_enabled = 0, 										
											$publisherRid = 0,
											$decoded_array = array()										
										)
	{

		$adListArray = array();
		while($addata = $resquery->fetch_assoc())
		{
			$rid			= 0;
			$profit			= 0;

			if($originalpopad == 1)
			{
				if($referral_enabled ==1 && $adv_ref_enabled ==1 && $addata['refferal_status'] == 1)
				$rid			= intval($addata['rid']);
			
				if($countrywise_pricing_enabled == 1)
				{
					if($addata['price'] > 0)
					$singleimprate = $addata['price']/1000;
					else
					{
						if(isset($decoded_array[$geo_country]['pop']) && $decoded_array[$geo_country]['pop'] > 0)
						{
								$countryPrice  = $decoded_array[$geo_country]['pop'];
								$singleimprate = $countryPrice/1000;
						}
						else
						$singleimprate	= $addata['default_rate']/1000;
					}
				}
				else
				$singleimprate	= $addata['default_rate']/1000;

				if($pid > 0)
				$profit=$singleimprate*$pop_specific_profit/100;
				else
				$profit=$singleimprate;

				$pop_ad_strings=$addata['userid'].'|'.$addata['aid'].'|'.$addata['kid'].'|'.$pid.'|'.$adcodeID.'|1|'.$sid.'|9|'.$addata['keyid'].'|'.$addata['aid'].'|'.$profit.'|'.$singleimprate.'|'.$rid.'|'.$publisherRid;

				$adListArray[0][$addata['aid']] 					 = $pop_ad_strings;
			}
			else 
			$adListArray[1][$addata['aid']] 						= $addata['aid'];

			if($pop_up_support == 1)
			$adListArray[4][$addata['aid']]['poptype'] 			 = 1;//POP UP
			else 
			$adListArray[4][$addata['aid']]['poptype'] 			 = 2;//New Tab

			$adListArray[4][$addata['aid']]['click_url'] 		 = $this->replace_macro($addata['click_url'],$addata['aid'],$adcodeID,$sid,$geo_country,$deviceType,$osname,$browsername,$catid,time());

			$adListArray[4][$addata['aid']]['pop_window_width']  = $pop_window_width;
			$adListArray[4][$addata['aid']]['pop_window_height'] = $pop_window_height;
			$adListArray[4][$addata['aid']]['originalpopad'] 	 = $originalpopad;
			$adListArray[4][$addata['aid']]['pixel_url'] 	     = '';
			$adListArray[4][$addata['aid']]['feed_ad'] 	         = 0;
			$adListArray[4][$addata['aid']]['exchange_ad'] 	     = 0;
		}

		return $adListArray;											
	}

	function get_processed_directlink_ads_result(
													$pid, 
													$resquery, 													
													$adcodeID,
													$originaldirectlinkad,			
													$trackDomain,
													$indexAppend,
													$geo_country = "",
													$cpm_specific_profit = 0, 													
													$referral_enabled = 0, 	
													$adv_ref_enabled = 0, 	
													$countrywise_pricing_enabled = 0, 													
													$publisherRid = 0, 													
													$display_type = 0, 													
													$sid = 0, 	
													$deviceType = 0, 	
													$osname = "",
													$browsername = "",
													$catid = 0,
													$decoded_array = array()
												)
	{

		$adListArray = array();

		while($addata = $resquery->fetch_assoc())
		{
			if($originaldirectlinkad == 1)
			{
				if($display_type == 1)
				{
					$pricingType = 1;
					$rid		 = 0;
					$profit		 = 0;

					if($referral_enabled ==1 && $adv_ref_enabled ==1 && $addata['refferal_status'] == 1)
					$rid			= intval($addata['rid']);

					if($countrywise_pricing_enabled == 1)
					{
						if($addata['price'] > 0)
						$singleimprate = $addata['price']/1000;
						else
						{
							$keyPricing = "cpm";

							if(isset($decoded_array[$geo_country][$keyPricing]) && $decoded_array[$geo_country][$keyPricing] > 0)
							{
									$countryPrice  = $decoded_array[$geo_country][$keyPricing];
									$singleimprate = $countryPrice/1000;
							}
							else
							$singleimprate	= $addata['default_rate']/1000;
						}
					}
					else
					$singleimprate	= $addata['default_rate']/1000;

					if($pid > 0)
					$profit = $singleimprate * $cpm_specific_profit/100;
					else
					$profit = $singleimprate;

					$directlink_ad_strings = $addata['userid'].'|'.$addata['aid'].'|0|'.$pid.'|'.$adcodeID.'|1|0|'.$pricingType.'|0|'.$addata['aid'].'|'.$profit.'|'.$singleimprate.'|'.$rid.'|'.$publisherRid;
					$click_url             = $this->replace_macro($addata['click_url'],$addata['aid'],$adcodeID,$sid,$geo_country,$deviceType,$osname,$browsername,$catid,time());
				
				}	
				else if($display_type == 0 || $display_type == 6)
				{
					//if CPC/CPA directlink, not track adscore here

					$directlink_ad_strings = "";
					$md5Hash               = md5($addata['aid']."0".$adcodeID."0".$pid."0");
					$click_url             = $trackDomain.TRACK_DIR.'/'.$indexAppend.'action/validate/'.$addata['aid'].'/0/'.$adcodeID.'/0/'.$pid.'/{ENCIP}/'.$md5Hash.'/0/0';
				}
				$adListArray[0][$addata['aid']] = $directlink_ad_strings;	
			}
			else 
			{
				$click_url = $trackDomain.TRACK_DIR.'/'.$indexAppend.'action/click_default/'.intval($addata['aid']).'/'.$adcodeID;
				$adListArray[1][$addata['aid']] = $addata['aid'];
			}	

			$adListArray[4][$addata['aid']]['click_url'] 			= $click_url;
			$adListArray[4][$addata['aid']]['originaldirectlinkad'] = $originaldirectlinkad;
			$adListArray[4][$addata['aid']]['pixel_url'] 	        = '';
			$adListArray[4][$addata['aid']]['feed_ad'] 	            = 0;
			$adListArray[4][$addata['aid']]['exchange_ad'] 	        = 0;
		}

		return $adListArray;											
	}

	function get_processed_feed_ads_result($ad_container_type, $feedAdArray)
	{
		$AdTitleData       = "";
		$AdDescriptionData = "";
		$AdDisplayUrlData  = "";
		$AdClickUrlData    = "";
		$AdImageUrlData    = "";
		$AdBidTagData      = "";
		$AdPixelTagData    = "";

		$adStringArray  = array();
		$i = 0;

		foreach($feedAdArray as $faKey => $faValue)
		{
			if($ad_container_type == 1 || $ad_container_type == 11)
			{
				if(isset($faValue[$titleTag]) && !is_array($faValue[$titleTag]) && $faValue[$titleTag] != "")
				$AdTitleData          =   $faValue[$titleTag];
			
				if(isset($faValue[$descriptionTag]) && !is_array($faValue[$descriptionTag]) && $faValue[$descriptionTag] != "")
				$AdDescriptionData    =   $faValue[$descriptionTag];

				if(isset($faValue[$displayUrlTag]) && !is_array($faValue[$displayUrlTag]) && $faValue[$displayUrlTag] != "")
				$AdDisplayUrlData     =   $faValue[$displayUrlTag];
			}
			
			if($ad_container_type == 2 || $ad_container_type == 11)
			{
				if(isset($faValue[$imageUrlTag]))
				$AdImageUrlData       =   $faValue[$imageUrlTag];

				//Manual change, if no image getting case
				if($AdImageUrlData == "" && isset($faValue['icon']))
				$AdImageUrlData       =   $faValue['icon'];			
			}

			if(isset($faValue[$clickUrlTag]) && !is_array($faValue[$clickUrlTag]) && $faValue[$clickUrlTag] != "")
			$AdClickUrlData       =   $faValue[$clickUrlTag];

			if(isset($faValue[$bidTag]))
			$AdBidTagData         =   $faValue[$bidTag];

			if(isset($faValue[$pixelTag]))
			$AdPixelTagData         =   $faValue[$pixelTag];


			if($ad_container_type == 1 && $AdTitleData != "" && $AdClickUrlData != "")
			$adStringArray[$i] = array('title'=>$AdTitleData,'description'=>$AdDescriptionData,'display_url'=>$AdDisplayUrlData,'click_url'=>$AdClickUrlData,'default_rate'=>$AdBidTagData,'pixel_url'=>$AdPixelTagData);
			else if($ad_container_type == 2 && $AdImageUrlData != "" && $AdClickUrlData != "")
			$adStringArray[$i] = array('banner'=>$AdImageUrlData,'click_url'=>$AdClickUrlData,'default_rate'=>$AdBidTagData,'pixel_url'=>$AdPixelTagData);
			else if($ad_container_type == 11 && $AdTitleData != "" && $AdImageUrlData != "" && $AdClickUrlData != "")
			$adStringArray[$i] = array('title'=>$AdTitleData,'description'=>$AdDescriptionData,'display_url'=>$AdDisplayUrlData,'click_url'=>$AdClickUrlData,'default_rate'=>$AdBidTagData,'pixel_url'=>$AdPixelTagData,'banner'=>$AdImageUrlData);

			$i++;
		}

		return $adStringArray;
	}



	function get_processed_feed_pop_ads_result(
											$pid, 
											$pop_specific_profit, 
											$addata, 
											$feedAdArray,
											$countrywise_pricing_enabled, 
											$geo_country,
											$decoded_array,
											$publisherRid,
											$adcodeID,
											$pop_up_support,
											$pop_window_width,
											$pop_window_height,
											$originalpopad,
											$sid
										)
	{

		$adListArray = array();

		foreach($feedAdArray as $faKey => $faValue)
		{
			if(isset($faValue[$clickUrlTag]))
			$AdClickUrlData       =   $faValue[$clickUrlTag];

			if(isset($faValue[$bidTag]))
			$AdBidTagData         =   $faValue[$bidTag];

			if(isset($faValue[$pixelTag]))
			$AdPixelTagData         =   $faValue[$pixelTag];

			if($AdClickUrlData != "")
			{
				if($addata['default_rate'] > 0)
				{
					if($countrywise_pricing_enabled == 1)
					{
						if($addata['price'] > 0)
						$singleimprate = $addata['price']/1000;
						else
						{
							if(isset($decoded_array[$geo_country]['pop']) && $decoded_array[$geo_country]['pop'] > 0)
							{
									$countryPrice  = $decoded_array[$geo_country]['pop'];
									$singleimprate = $countryPrice/1000;
							}
							else
							$singleimprate	= $addata['default_rate']/1000;
						}
					}
					else
					$singleimprate	= $addata['default_rate']/1000;
				}																			
				else if($AdBidTagData > 0)
				$singleimprate	= $AdBidTagData/1000;

				if($pid > 0 && $singleimprate > 0)
				$profit=$singleimprate*$pop_specific_profit/100;
				else
				$profit=$singleimprate;

				$pop_ad_strings=$addata['userid'].'|'.$addata['aid'].'|'.$addata['kid'].'|'.$pid.'|'.$adcodeID.'|1|'.$sid.'|9|'.$addata['keyid'].'|'.$addata['aid'].'|'.$profit.'|'.$singleimprate.'|0|'.$publisherRid;

				$adListArray[0][$addata['aid']] 					   = $pop_ad_strings;

				if($pop_up_support == 1)
				$adListArray[4][$addata['aid']]['poptype'] 		   = 1;//POP UP
				else 
				$adListArray[4][$addata['aid']]['poptype'] 		   = 2;//New Tab

				$adListArray[4][$addata['aid']]['click_url'] 		 = $AdClickUrlData;
				$adListArray[4][$addata['aid']]['pop_window_width']  = $pop_window_width;
				$adListArray[4][$addata['aid']]['pop_window_height'] = $pop_window_height;
				$adListArray[4][$addata['aid']]['originalpopad'] 	 = $originalpopad;
				$adListArray[4][$addata['aid']]['pixel_url'] 	     = $AdPixelTagData;
				$adListArray[4][$addata['aid']]['feed_ad'] 	         = 1;
				$adListArray[4][$addata['aid']]['exchange_ad'] 	     = 0;
			}

			break;
		}

		return $adListArray;											
	}

	function get_processed_feed_directlink_ads_result(
														$pid, 
														$cpm_specific_profit, 
														$addata, 
														$feedAdArray,
														$countrywise_pricing_enabled, 
														$geo_country,
														$decoded_array,
														$publisherRid,
														$adcodeID,
														$display_type,
														$originaldirectlinkad,
														$trackDomain,
														$indexAppend
													)
	{

		$adListArray = array();

		foreach($feedAdArray as $faKey => $faValue)
		{
			if(isset($faValue[$clickUrlTag]) && !is_array($faValue[$clickUrlTag]) && $faValue[$clickUrlTag] != "")
			$AdClickUrlData       =   $faValue[$clickUrlTag];
			
			if(isset($faValue[$bidTag]))
			$AdBidTagData         =   $faValue[$bidTag];
			
			if(isset($faValue[$pixelTag]))
			$AdPixelTagData         =   $faValue[$pixelTag];
			
			if($AdClickUrlData != "")
			{				
				if($display_type == 1)
				{																			
					$pricingType = 1;
					
					if($addata['default_rate'] > 0)
					{
						if($countrywise_pricing_enabled == 1)
						{
							if($addata['price'] > 0)
							$singleimprate = $addata['price']/1000;
							else
							{
								$keyPricing = "cpm";
								if(isset($decoded_array[$geo_country][$keyPricing]) && $decoded_array[$geo_country][$keyPricing] > 0)
								{
									$countryPrice  = $decoded_array[$geo_country][$keyPricing];
									$singleimprate = $countryPrice/1000;
								}
								else
								$singleimprate	= $addata['default_rate']/1000;
							}
						}
						else
						$singleimprate	= $addata['default_rate']/1000;
					}														
					else if($AdBidTagData > 0)
					$singleimprate	= $AdBidTagData/1000;
					
					if($pid > 0 && $singleimprate > 0)
					$profit = $singleimprate * $cpm_specific_profit/100;
					else
					$profit = $singleimprate;

					$directlink_ad_strings = $addata['userid'].'|'.$addata['aid'].'|0|'.$pid.'|'.$adcodeID.'|1|0|'.$pricingType.'|0|'.$addata['aid'].'|'.$profit.'|'.$singleimprate.'|0|'.$publisherRid;
					$adListArray[0][$addata['aid']] 					    = $directlink_ad_strings;
					$adListArray[4][$addata['aid']]['click_url'] 		    = $AdClickUrlData;
					$adListArray[4][$addata['aid']]['originaldirectlinkad'] = $originaldirectlinkad;
					$adListArray[4][$addata['aid']]['pixel_url'] 	        = $AdPixelTagData;
					$adListArray[4][$addata['aid']]['feed_ad'] 	            = 1;
					$adListArray[4][$addata['aid']]['exchange_ad'] 	        = 0;
				}	
				else if($display_type == 0)
				{
					$clickRatePass  = 0;
					
					if($addata['default_rate'] > 0)
					$clickRatePass	= $addata['default_rate'];
					else if($AdBidTagData > 0)
					$clickRatePass	= $AdBidTagData;
					
					$directlink_ad_strings = "";
					$clickRatePassString   = "/".$clickRatePass."/".md5($addata['aid'].$clickRatePass.Configuration::get_instance()->read('admarket_name'));
					$md5Hash               = md5($addata['aid']."0".$adcodeID."0".$pid."0");
					$click_url             = $trackDomain.TRACK_DIR.'/'.$indexAppend.'action/validate/'.$addata['aid'].'/0/'.$adcodeID.'/0/'.$pid.'/{ENCIP}/'.$md5Hash.'/0/0/'.$this->mybase64_encode($AdClickUrlData).$clickRatePassString;
					$adListArray[0][$addata['aid']] 					    = $directlink_ad_strings;
					$adListArray[4][$addata['aid']]['click_url'] 		    = $click_url;
					$adListArray[4][$addata['aid']]['originaldirectlinkad'] = $originaldirectlinkad;
					$adListArray[4][$addata['aid']]['pixel_url'] 	        = $AdPixelTagData;
					$adListArray[4][$addata['aid']]['feed_ad'] 	            = 1;
					$adListArray[4][$addata['aid']]['exchange_ad'] 	        = 0;
				}
			}
			break;
		}

		return $adListArray;											
	}

	function get_bid_request_payload(
										$pid, $sid, $sitename, $adcodeID, $adcodeType, $ad_container_type,
										$dspAuctionType, $adcodeAllowedAds, $IABCategoryArray, $splitKeywords,
	                                    $requestIP, $requestUserAgent, $inpagepush, $native, 
										$language_targeting_enabled, $languageArray,
										$device_targeting_enabled, $deviceType, $page_referrer,
										$osname, $isp_connection_enabled, $isp_enabled, $isp, 
										$connection_enabled, $connection,
										$countryAlpha3, $subdivision1, $city_enabled, $cityid, $latitude, $longitude,  
					$bannerHeight = 0, $bannerWidth = 0, $text_ads_enabled = 0, 
					$textimage_enabled = 0, $textimageWidth = 0, $textimageHeight = 0
	)
	{
		$systemCurrency         = Configuration::get_instance()->read('system_currency');
		$requestTimeout         = Configuration::get_instance()->read('dsp_connection_timeout') * 1000;//Converted into milliseconds
		$minimumBidFloor        = Configuration::get_instance()->read('dsp_bidfloor');
		$geo_location_detection	= Configuration::get_instance()->read('geo_location_detection');

		$DSPSecure = 0;

		if(parse_url($page_referrer, PHP_URL_SCHEME) === 'https')
		$DSPSecure = 1;


		$uniqueID         = md5($adcodeID.'-'.uniqid().'-'.rand(0,100000));
		$requestArray     = array();
		$requestArray["id"]     = $uniqueID;
		$requestArray["cur"][0] = $systemCurrency;
		$requestArray["tmax"]   = $requestTimeout;
		$requestArray['at']     = $dspAuctionType;   //1;//Take first price
		//$requestArray['test']   = 1; //For testing value is 1, otherwise 0
		$requestArray["user"]["id"] = $uniqueID;
		$requestArray["ext"]["sub"]              = $adcodeID;
		$requestArray['site']['id']              = (string)$sid;
		$requestArray['site']['domain']          = $sitename;
		$requestArray['site']['publisher']['id'] = $pid;
		$requestArray["site"]["cat"]    				 = $IABCategoryArray;
		$requestArray["site"]["page"]   				 = $page_referrer;
		//$requestArray["site"]["keywords"]   		 = implode(",", $splitKeywords);
		$requestArray["device"]["ua"]     			 = $requestUserAgent;
		if(filter_var($requestIP, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
		$requestArray["device"]["ip"]     			 = $requestIP;
		else if(filter_var($requestIP, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6))
		$requestArray["device"]["ipv6"]     		 = $requestIP;
		//Not important
		//if($language_targeting_enabled == 1)
		$requestArray["device"]["language"]     	 = $languageArray[0];
		if($device_targeting_enabled == 1)
		{
				if($deviceType =='desktop')
				$requestArray["device"]["devicetype"]        = 2;
				else if($deviceType =='mobile')
				$requestArray["device"]["devicetype"]        = 1;
				//$requestArray["device"]["os"]      		 = $osname;
		}
		//Not important
		if($isp_connection_enabled == 1 && ($isp_enabled == 1 || $connection_enabled == 1))
		{
				//$requestArray["device"]["carrier"]         = $isp;
				//$requestArray["device"]["connectiontype"]  = $connection; //(0 or 2 (Wifi))
		}
		$geoData = array();
		$geoData["country"]   = $countryAlpha3;
		
		if($geo_location_detection == 1)
		$geoData["ipservice"] = 3; //For maxmind

		if($city_enabled == 1)
		{
				$geoData["lat"] 		= $latitude;
				$geoData["lon"] 		= $longitude;
				$geoData["type"] 		= 2;//IP is used for location detection
				//$geoData["region"] = $subdivision1;
				//$geoData["city"]   = $cityid;
		}

		$requestArray["device"]["geo"]         = $geoData;
		$requestArray["imp"][0]["id"]          = $uniqueID;
		if($minimumBidFloor > 0)
		{
		$requestArray["imp"][0]["bidfloor"]    = $minimumBidFloor;
		$requestArray["imp"][0]["bidfloorcur"] = $systemCurrency;
		}
		$requestArray["imp"][0]["tagid"]       = $adcodeID;
		$requestArray["imp"][0]["secure"]      = $DSPSecure;
		
		//Need to check 
		//for pop and interstitial 1
		if($adcodeType == 5 || $adcodeType == 9)
		$requestArray["imp"][0]["instl"]      = 1;
		
            	if($ad_container_type == 13)//Video
		{
			$videoObject = [
					    "skip" => 1,
					    //"skipafter" => 5,
					    //"skipmin" => 10,
					    "mimes" => [
						"video/x-flv",
						"video/mp4"
					    ],
					    "protocols" => [3, 6],  // VAST 2.0 and VAST 4.0 (for example)
					    "linearity" => 1,       // 1 = linear, 2 = non-linear
					    "boxingallowed" => 1
				      ];
			$requestArray["imp"][0]["video"]  = $videoObject;
		}
		if($ad_container_type == 2 || $ad_container_type == 5)//Banner / Interstitial
		{
				$bannerObject      = array();
				$bannerObject["h"] = $bannerHeight;
				$bannerObject["w"] = $bannerWidth;
				$requestArray["imp"][0]["banner"]  = $bannerObject;
		}
		




		
		if(($text_ads_enabled == 1 && $ad_container_type == 1) || ($textimage_enabled == 1 && $ad_container_type == 11))
		{
			$titleLength        = Configuration::get_instance()->read('max_ad_title_length');
			$descriptionLength  = Configuration::get_instance()->read('max_ad_desc_length');
			$displayUrlLength   = Configuration::get_instance()->read('max_display_url_length');
			$ctaButtonLength 	= Configuration::get_instance()->read('cta_button_text_length');

			$DSPRequest = array();
			$DSPRequest['ver']      = '1.2';
			$DSPRequest['plcmtcnt'] = $adcodeAllowedAds;

			if($inpagepush == 1)
			$DSPRequest['plcmttype'] = 500;
			else if($native == 1)
			$DSPRequest['plcmttype'] = 4;
			$assets = [];
			//For Image
			$assets[] = [
					'id' => 1,
					'required' => 1,
					'img' => [
						'wmin' => (int)$textimageWidth,
						'hmin' => (int)$textimageHeight,
						'w' => (int)$textimageWidth,
						'h' => (int)$textimageHeight,
						'type' => 1 //1 => Icon Image, 3 => Large Image 
					]
					];
					//For title

			$assets[] = [
						'id' => 2,
						'required' => 1,
						'title' => [
						'len' => (int)$titleLength
						]
					];
			
			
			
		
			
			$assets[] = [
						'id' => 3,
						'required' => 0,
						'data' => [
						'type' => 2, //1 => Sponsored, 2=> Discription
						'len' => (int)$descriptionLength
						]
					];

			
			
					//For display url
			/**********
			$assets[] = [
						'id' => 5,
						'required' => 0,
						'data' => [
						'type' => 11,
						'len' => (int)$displayUrlLength
						]
					];
					*************/
					//For CTA Button
			/**********
			$assets[] = [
						'id' => 6,
						'required' => 0,
						'data' => [
						'type' => 12,
						'len' => (int)$ctaButtonLength
						]
					];
					*************/
			/**********
			$assets[] = [
						'id' => 4,
						'required' => 0,
						'data' => [
						'type' => 1,
						'len' => 100
						]
					];
					*************/
			$DSPRequest['assets'] = $assets;
			
			$nativeRequest = [
								'native' => $DSPRequest
							 ];
	
			$nativeObject  = [
								'request' => json_encode($nativeRequest, JSON_UNESCAPED_SLASHES),
								'ver' => '1.2'
							 ];
			$requestArray['imp'][0]['native'] = $nativeObject;
		}

		$DSPArrayJSON = json_encode($requestArray, JSON_UNESCAPED_SLASHES);

		return $DSPArrayJSON;
	}

	function get_processed_rtb_response($resultFromExchange, $adcodeType, $ad_container_type, $vastSkipInterval = 0, $trackEvent = "", $vastClickURL = "") 
	{
		$bidRequestID          = "";
		$resultFromExchangeArray   = json_decode($resultFromExchange, 1);
		if(isset($resultFromExchangeArray['id']))
		$bidRequestID = $resultFromExchangeArray['id'];
		if(isset($resultFromExchangeArray['seatbid']))
		{
		    foreach($resultFromExchangeArray['seatbid'] as $key => $value)
		    {
		$impressionID          = "";
		$impressionPrice       = 0;
		$impressionUrl         = "";
		$impressionClickUrl    = "";
		$impressionTitle       = "";
		$impressionImageUrl    = "";
		$impressionDescription = "";
		$impressionDisplayUrl  = "";
		$impressionRenderContent = "";
		$impressionAdContent   = array();

				if(isset($value['bid']))
		{
					if(isset($value['bid'][0]))
					{
						$bidResponseArray    = $value['bid'][0];
						if(isset($bidResponseArray['impid']))
						$impressionID        = $bidResponseArray['impid'];
						if(isset($bidResponseArray['price']))
						$impressionPrice     = $bidResponseArray['price'];
						if(isset($bidResponseArray['nurl']))
						$impressionUrl       = $bidResponseArray['nurl'];
						if(isset($bidResponseArray['adm']))
						{
							if($adcodeType == 9)
							{
								//For openRTB 2.4
								//$impressionAdContent = html_entity_decode($bidResponseArray['adm']);
								//$xmlClickURL         = simplexml_load_string($impressionAdContent);
								//$impressionClickUrl  = (string) $xmlClickURL->popunderAd->url;
								
								//For openRTB 2.5
								$impressionClickUrl  = $bidResponseArray['adm'];
							}
							else if($ad_container_type == 1 || $ad_container_type == 11)
							{
								$impressionAdContent = json_decode($bidResponseArray['adm'], 1);
						
								if(isset($impressionAdContent['native']))
								{
									if(isset($impressionAdContent['native']['link']) && isset($impressionAdContent['native']['link']['url']))
									$impressionClickUrl = $impressionAdContent['native']['link']['url'];
									if(isset($impressionAdContent['native']['assets']) && is_array($impressionAdContent['native']['assets']))
									{
										foreach($impressionAdContent['native']['assets'] as $assetKey => $assetValue)
										{
											if(isset($assetValue['title']) && isset($assetValue['title']['text']))
											$impressionTitle       = $assetValue['title']['text'];
											if(isset($assetValue['img']) && isset($assetValue['img']['url']))
											$impressionImageUrl    = $assetValue['img']['url'];
											//Need to discuss about it
											if(isset($assetValue['data']) && isset($assetValue['data']['value']))
											$impressionDescription = $assetValue['data']['value'];
										}
									}
								}
							}
							else if($ad_container_type == 2)
							{
								//For managing HTML Markup ad content
								//$impressionRenderContent = $bidResponseArray['adm'];
								$xmlString = html_entity_decode($bidResponseArray['adm']);
								$doc = new DOMDocument();
								$doc->loadXML($xmlString);
								// Get <clickUrl>
								$clickUrlNode = $doc->getElementsByTagName('clickUrl')->item(0);
								$impressionClickUrl = $clickUrlNode ? $clickUrlNode->nodeValue : null;
								// Get <imgUrl>
								$imgUrlNode = $doc->getElementsByTagName('imgUrl')->item(0);
								$impressionImageUrl = $imgUrlNode ? $imgUrlNode->nodeValue : null;
							}
							else if($ad_container_type == 13)
							{
			$impressionRenderContent = $this->get_processed_vast_xml($bidResponseArray['adm'], $trackEvent, $vastClickURL, $vastSkipInterval);
							}
						}
					}
				}
				


				$RTBResponse[] = [
						'bidRequestID' => $bidRequestID,
						'impressionID' => $impressionID,
						'impressionPrice' => $impressionPrice,
						'impressionUrl' => $impressionUrl,
						'impressionClickUrl' => $impressionClickUrl,
						'impressionRenderContent' => $impressionRenderContent,
						'impressionTitle' => $impressionTitle,
						'impressionImageUrl' => $impressionImageUrl,
						'impressionDescription' => $impressionDescription,
						'impressionDisplayUrl' => $impressionDisplayUrl
						
					];
			}
		}

		return $RTBResponse;			
	}
	function get_processed_vast_xml($impressionRenderContent, $trackEvent, $vastClickURL, $vastSkipInterval)
							{
								$doc = new DOMDocument();
								$doc->loadXML($impressionRenderContent);	
								//Locate <TrackingEvents> element
								$xpath = new DOMXPath($doc);
								// Find the <Linear> node
$linearElements = $xpath->query('//InLine/Creatives/Creative/Linear');
if ($linearElements->length > 0) {
    $linearElement = $linearElements->item(0);
    // Add or update the skipoffset attribute
	if($vastSkipInterval > 0)
	$linearElement->setAttribute('skipoffset', gmdate('H:i:s', (int)$vastSkipInterval));	
	else
    $linearElement->setAttribute('skipoffset', '00:00:00');
}
								$trackingEventsImpressionURL = "";
						$impressionNodes = $xpath->query('//InLine/Impression');
						$impressionNode  = $impressionNodes->item(0);
						$trackingEventsImpressionURL = $this->mybase64_encode(trim($impressionNode->nodeValue));	
								// Remove each Impression node found
							        foreach ($impressionNodes as $impressionNode) {
									$impressionNode->parentNode->removeChild($impressionNode);
								}	
								// Remove each Error node found
								$errorNodes = $xpath->query('//InLine/Error');
								foreach ($errorNodes as $errorNode) {
									$errorNode->parentNode->removeChild($errorNode);
								}	
								$trackingEventsNodes = $xpath->query('//TrackingEvents');
								$previousNodeEvent     = "";
								$previousNodeOffset    = "";
								$trackingEventsViewURL = "";
								if ($trackingEventsNodes->length > 0) {
									// Already exists — just use the first one
								    $trackingEvents = $trackingEventsNodes->item(0);
									// Find first <Tracking> inside this <TrackingEvents> node
    $firstTracking = $trackingEvents->getElementsByTagName('Tracking')->item(0);
    if ($firstTracking) {
        $previousNodeEvent  = $firstTracking->getAttribute('event');
        $previousNodeOffset = $firstTracking->hasAttribute('offset') ? $firstTracking->getAttribute('offset') : null;
    }
	$trackingEventsViewURL = $this->mybase64_encode(trim($trackingEvents->nodeValue));	
	// Find all <Tracking> nodes under it
    $trackingNodes = $xpath->query('./Tracking', $trackingEvents);
    // Loop backwards to avoid skipping nodes when removing
    for ($i = $trackingNodes->length - 1; $i >= 0; $i--) {
        $trackingEvents->removeChild($trackingNodes->item($i));
    }
								} else {
									// Doesn't exist — create it and insert under <Linear>
									$linearNodes = $xpath->query('//Linear');
									if ($linearNodes->length > 0) {
									    $linear = $linearNodes->item(0);
									    $trackingEvents = $doc->createElement('TrackingEvents');
									    $linear->appendChild($trackingEvents);
									} else {
									    // If <Linear> doesn't exist either, skip adding
									    $trackingEvents = null;
									}
								}
								// Only add Tracking if we have a <TrackingEvents>
								if ($trackingEvents) {
								    $tracking = $doc->createElement('Tracking');
									if($previousNodeEvent != "" && $previousNodeOffset != "")
									{
										$tracking->setAttribute('event', $previousNodeEvent);
										$tracking->setAttribute('offset', $previousNodeOffset);
									}
									else
								    $tracking->setAttribute('event', $trackEvent);
									
		if($trackingEventsViewURL != "" && $trackingEventsImpressionURL != "")
		$cdata = $doc->createCDATASection('{IMPRESSIONTRACKING}/0/'.$trackingEventsViewURL.'/'.$trackingEventsImpressionURL);
		else if($trackingEventsViewURL != "")
		$cdata = $doc->createCDATASection('{IMPRESSIONTRACKING}/0/'.$trackingEventsViewURL);
		else 
								    $cdata = $doc->createCDATASection('{IMPRESSIONTRACKING}');
								    $tracking->appendChild($cdata);

								    $trackingEvents->appendChild($tracking);
								}
								// Remove all <Extensions> nodes
								//$extensionsNodes = $xpath->query('//Extensions');
								//foreach ($extensionsNodes as $extensionsNode) {
								//	$extensionsNode->parentNode->removeChild($extensionsNode);
								//}						
							    	// ----- Remove <Icons> ONLY inside <Linear> -----
							    	$iconsNodes = $xpath->query('//Linear/Icons');
							    	foreach ($iconsNodes as $iconsNode) {
									$iconsNode->parentNode->removeChild($iconsNode);
							    	}
$clickThroughNode = $xpath->query('//VideoClicks/ClickThrough')->item(0);
if ($clickThroughNode) {
    // Encode the existing value
    $clickThroughUrl = $this->mybase64_encode(trim($clickThroughNode->nodeValue));
    $newValue = $vastClickURL . "/" . $clickThroughUrl;
    // Remove all existing children (so we don't mix old + new text)
    while ($clickThroughNode->firstChild) {
        $clickThroughNode->removeChild($clickThroughNode->firstChild);
    }
    // Add new value as CDATA
    $cdata = $doc->createCDATASection($newValue);
    $clickThroughNode->appendChild($cdata);
}
// Find the <Tracking> node
$trackingNode = $xpath->query('//Extensions/Extension/TitleCTA/Tracking')->item(0);
if ($trackingNode) {
    // Prepare your updated value
    $trackingNodeClickURL = $this->mybase64_encode(trim($trackingNode->nodeValue));
    $newValue = $vastClickURL . "/" . $trackingNodeClickURL;
    // Remove existing child nodes (including old CDATA)
    while ($trackingNode->firstChild) {
        $trackingNode->removeChild($trackingNode->firstChild);
    }
    // Add new CDATA section
    $cdata = $doc->createCDATASection($newValue);
    $trackingNode->appendChild($cdata);
								}
								// Get updated XML as string
								return $impressionRenderContent = $doc->saveXML();	
							}

	function get_processed_exchange_pop_ads_result(
														$pid, 
														$pop_specific_profit, 
														$addata, 
														$impressionClickUrl,
														$impressionUrl,
														$impressionPrice,
														$countrywise_pricing_enabled, 
														$geo_country,
														$decoded_array,
														$publisherRid,
														$adcodeID,
														$pop_up_support,
														$pop_window_width,
														$pop_window_height,
														$originalpopad,
														$sid
													)
	{
		$adListArray = array();

		$profit			= 0;
		$singleimprate	= 0;
		if($addata['default_rate'] > 0)
		{
			if($countrywise_pricing_enabled == 1)
			{
				if($addata['price'] > 0)
				$singleimprate = $addata['price']/1000;
				else
				{
					if(isset($decoded_array[$geo_country]['pop']) && $decoded_array[$geo_country]['pop'] > 0)
					{
							$countryPrice  = $decoded_array[$geo_country]['pop'];
							$singleimprate = $countryPrice/1000;
					}
					else
					$singleimprate	= $addata['default_rate']/1000;
				}
			}
			else
			$singleimprate	= $addata['default_rate']/1000;
		}																			
		else if($impressionPrice > 0)
		$singleimprate	= $impressionPrice/1000;


		if($pid > 0 && $singleimprate > 0)
		$profit=$singleimprate*$pop_specific_profit/100;
		else
		$profit=$singleimprate;

		$pop_ad_strings = $addata['userid'].'|'.$addata['aid'].'|'.$addata['kid'].'|'.$pid.'|'.$adcodeID.'|1|'.$sid.'|9|'.$addata['keyid'].'|'.$addata['aid'].'|'.$profit.'|'.$singleimprate.'|0|'.$publisherRid;


		$adListArray[0][$addata['aid']] = $pop_ad_strings;

		if($pop_up_support == 1)
		$adListArray[4][$addata['aid']]['poptype'] 		     = 1;//POP UP
		else 
		$adListArray[4][$addata['aid']]['poptype'] 		     = 2;//New Tab

		$adListArray[4][$addata['aid']]['click_url'] 	     = $impressionClickUrl;
		$adListArray[4][$addata['aid']]['pop_window_width']  = $pop_window_width;
		$adListArray[4][$addata['aid']]['pop_window_height'] = $pop_window_height;
		$adListArray[4][$addata['aid']]['originalpopad'] 	 = $originalpopad;
		$adListArray[4][$addata['aid']]['pixel_url'] 	     = $impressionUrl;
		$adListArray[4][$addata['aid']]['feed_ad'] 	         = 0;
		$adListArray[4][$addata['aid']]['exchange_ad'] 	     = 1;

		return $adListArray;											
	}

	//Jesus 
	

	function get_ad_display_cache(
		$ad_buffer_data, $adListArray, $cacheEncryptedIP, $cacheIP, $cacheUserAgent, $cacheDate, $trackDomain, $display_type, $exchangeAd, $html_ad_get_flag, $ad_container_type,
		$adcodeID, $ecommerceFlag, $adRotationEnabled, $adRotationInterval, $indexAppend, 
		$geo_country, $displayAdCount, $ppc_tracking_interval, $cpm_tracking_interval, 
		$html_tracking_interval, $cpd_tracking_interval, $cpa_tracking_interval, $pop_tracking_interval, 
		$cpc_impression, $cpm_impression, $html_impression, $cpd_impression, $cpa_impression, 
		$pop_impression, $cpv_impression
	)
	{




	    $admarket_name  = Configuration::get_instance()->read("admarket_name");

		$ad_buffer_data = str_replace("{ENCIP}",$cacheEncryptedIP, $ad_buffer_data);
              				
		$trackingInterval  = 1;
		$specialtime	   = 0;
		$impression_cookie = "";
		$jsDisplayString   = "";
		$poptype		   = 0;	
		$sourceType        = 0;
		$popTrackData      = "";

		if($exchangeAd == 1)
		$sourceType        = 2;


		if($display_type == 0)
		{
			$trackingInterval  = $ppc_tracking_interval;
			$specialtime       = time()+$ppc_tracking_interval+10;
			$impression_cookie = $cpc_impression;
		}
		else if($display_type == 1 && $html_ad_get_flag == 0)
		{
			$trackingInterval  = $cpm_tracking_interval;
			$specialtime       = time()+$cpm_tracking_interval+10;
			$impression_cookie = $cpm_impression;
		}
		else if($display_type == 1 && $html_ad_get_flag == 1)
		{
			$trackingInterval  = $html_tracking_interval;
			$specialtime       = time()+$html_tracking_interval+10;
			$impression_cookie = $html_impression;
		}
		else if($display_type == 3)
		{
			$trackingInterval  = $cpd_tracking_interval;
			$specialtime       = time()+$cpd_tracking_interval+10;
			$impression_cookie = $cpd_impression;
		}
		else if($display_type == 6)
		{
			$trackingInterval  = $cpa_tracking_interval;
			$specialtime       = time()+$cpa_tracking_interval+10;
			$impression_cookie = $cpa_impression;
		}
		else if($display_type == 9)
		{
			$trackingInterval  = $pop_tracking_interval;
			$specialtime       = time()+900;
			$impression_cookie = $pop_impression;
		}
		else if($display_type == 13)
		{
			$specialtime       = time()+900;
			$impression_cookie = $cpv_impression;
		}

		$trackingInterval = $trackingInterval * 1000;
		$returnArray      = $this->get_ads_from_ads_list($displayAdCount, $adListArray, $ad_container_type);

		$rotationString   = "";
		$expandableString = "";
		$skinString       = "";

		/******* Ads available for display **********/
		if(isset($returnArray)) 
		{
			if(isset($adListArray[2]) && $adListArray[2] != "") //Skin content
			{
				foreach($adListArray[2] as $skinKey => $skinValue)
				{
					$skinValueReplace = str_replace("{ENCIP}",$cacheEncryptedIP, $skinValue);

					$skinString.="<input type='hidden' name='skin-content-".$skinKey."' id='skin-content-".$skinKey."' value='".$skinValueReplace."' />";
				}
			}	

			if(isset($adListArray[3]) && $adListArray[3] != "") //Expandable content
			{
				foreach($adListArray[3] as $expandKey => $expandValue)
				{
					$expandValueReplace = str_replace("{ENCIP}",$cacheEncryptedIP, $expandValue[0]);

					$expandableString.="<input type='hidden' name='expandable-content-".$expandKey."' id='expandable-content-".$expandKey."' value='".$expandValueReplace."' />";
				}
			}

			if($adRotationEnabled == 1)
			{
				$currentAD = intval($returnArray["ad_id_list"]); //Get adID from here

				if(isset($adListArray[0][$currentAD]))
				unset($adListArray[0][$currentAD]);

				if(isset($adListArray[1][$currentAD]))
				unset($adListArray[1][$currentAD]);

				if(isset($adListArray[0])) //Normal ad
				{
					$indexData = 1;

					foreach($adListArray[0] as $rotateKey => $rotateValue)
					{
						$rotationSpecialTime = $specialtime + ($indexData * $adRotationInterval);

						$impressionRotation	 = $this->get_impression_data($rotateValue,$display_type,$impression_cookie);
						$md5StringRotation	 = md5($impressionRotation.$admarket_name.$cacheIP.$cacheUserAgent.$cacheDate.$rotationSpecialTime.$geo_country);

						$impressionSrc = "";

						if($impressionRotation != "")
						$impressionSrc = $trackDomain.TRACK_DIR."/".$indexAppend."action/impression/".$impressionRotation."/".$md5StringRotation."/".$rotationSpecialTime."/".$geo_country."/".$sourceType."/".$impression_cookie;

						$rotationString.="<input type='hidden' name='ad-rotation-".$rotateKey."' id='ad-rotation-".$rotateKey."' value='".$impressionSrc."' />";

						$indexData++;
					}
				}

				if(isset($adListArray[1])) //Default ad
				{
					foreach($adListArray[1] as $rotateKey => $rotateValue)
					{
						$impressionSrc  = $trackDomain.TRACK_DIR."/".$indexAppend."action/impression_default/".$rotateKey."/".$adcodeID;

						$rotationString.="<input type='hidden' name='ad-rotation-".$rotateKey."' id='ad-rotation-".$rotateKey."' value='".$impressionSrc."' />";
					}
				}
			}

			$ad_buffer_data = str_replace("{IMPRESSIONSRCURL}",$rotationString, $ad_buffer_data);
			$ad_buffer_data = str_replace("{EXPANDABLECONTENT}",$expandableString, $ad_buffer_data);
			$ad_buffer_data = str_replace("{SKINCONTENT}",$skinString, $ad_buffer_data);

			if(isset($returnArray["pop"]) && $returnArray["pop"]["pop_ad"] == 1) //Assume its pop ads
			{
				$popOpenType		= "";
				$popBodyRemove		= "";
				$click_url			= $returnArray["pop"]["click_url"];
				$poptype 			= $returnArray["pop"]["pop_type"];
				$pop_window_width	= $returnArray["pop"]["pop_window_width"];
				$pop_window_height	= $returnArray["pop"]["pop_window_height"];
			}

			if(isset($returnArray["directlink"]) && $returnArray["directlink"]["directlink_ad"] == 1) //Assume its Directlink
			{
				$directlinkOpenType = "";
				$click_url			= $returnArray["directlink"]["click_url"];
			}


			/*
				In $returnArray["paid_ads"] system pass impression tracking data.
				In case of cpc directlink ads, there is no impression tracking.
				So we added below "if" condition for manage non directlink ads and paid directlink ads
			*/
			if(
				( $returnArray["paid_ads"] != "" && $returnArray["directlink"]["directlink_ad"] == 0 ) || 
				( $returnArray["directlink"]["directlink_ad"] == 1 && $returnArray["directlink"]["original_directlink_ad"] == 1	)
			)//Paid ads case
			{
				$originalAdImpressionString = "";
				$impression_data_string     = "";
				$md5StringData              = "";

				if($returnArray["paid_ads"] != "")
				{
					$originalAdImpressionString = $returnArray["paid_ads"];
					$impression_data_string 	= $this->get_impression_data($originalAdImpressionString,$display_type,$impression_cookie);
					$md5StringData				= md5($impression_data_string.$admarket_name.$cacheIP.$cacheUserAgent.$cacheDate.$specialtime.$geo_country);
				}

				if(isset($returnArray["pop"]) && $returnArray["pop"]["pop_ad"] == 1) //Assume its pop ads
				{
					$currentDisplayPOPAd = $returnArray["ad_id_list"];

					if($impression_data_string != "")
					{
						$popImpressionURL = $this->mybase64_encode($trackDomain.TRACK_DIR."/".$indexAppend."action/impression/".$impression_data_string."/".$md5StringData."/".$specialtime."/".$geo_country."/".intval($returnArray["pop"]["source"])."/".$impression_cookie);
						$openURL 		  = $this->make_url("query/pop_render/".$this->mybase64_encode($click_url)."/".$popImpressionURL."/".intval($returnArray["pop"]["source"]));
					
						//Same ads allowed in same visitor / hour
						$capInterval        = intval(Configuration::get_instance()->read('pop_interval'));
						$maxImpressionLimit = intval(Configuration::get_instance()->read('pop_ad_impression_limit_hour'));
						$capTimeCurrent     = time();
						$newElementExpiry   = $capTimeCurrent + ($capInterval * 60 * 60);

						$capCookieTemp 			 = array();
						$cookieFlag          = 0;

						if($impression_cookie != "")
						{
							$capCookieArray      = explode("_",$this->mybase64_decode($impression_cookie));

							foreach($capCookieArray as $capKey => $capValue)
							{
								if(trim($capValue) != "")
								{
									$capValueArray   = explode("-",$capValue);

									if($capValueArray[2] > $capTimeCurrent)
									{
										if($capValueArray[0] == $currentDisplayPOPAd && $capValueArray[1] < $maxImpressionLimit)
										{
												$capCookieTemp[] = $currentDisplayPOPAd."-".($capValueArray[1]+1)."-".$capValueArray[2];
												$cookieFlag      = 1;
										}
										else
										$capCookieTemp[] = $capValue;
									}
								}
							}
						}

						if(count($capCookieTemp) == 0 || $cookieFlag == 0)
						$capCookieTemp[]     = $currentDisplayPOPAd."-1-".$newElementExpiry;

						$capCookieContent    = implode("_",$capCookieTemp);
						$popTrackData        = "Set_Track_Cookie('_data_pop','".$capCookieContent."',0,'/');";
					
						$openURL.= "/".$this->mybase64_encode($capCookieContent); //Append pop cookie data

					    if(($returnArray["pop"]["source"] == 1 || $returnArray["pop"]["source"] == 2) && $returnArray["pop"]["pixel_url"] != "")
						$openURL.= "/".$this->mybase64_encode($returnArray["pop"]["pixel_url"]);
						//if pixel tracking using img src not working, need to pass pixel url in the above link for url fopen
					}
					else
					$openURL 				= $click_url;
				}
				else if(isset($returnArray["directlink"]) && $returnArray["directlink"]["directlink_ad"] == 1) //Assume its Directlink
				{
					$click_url = str_replace("{ENCIP}",$cacheEncryptedIP, $click_url);

					if($impression_data_string != "")
					{
						$directlinkImpressionURL = $this->mybase64_encode($trackDomain.TRACK_DIR."/".$indexAppend."action/impression/".$impression_data_string."/".$md5StringData."/".$specialtime."/".$geo_country."/".intval($returnArray["directlink"]["source"]));
						$openURL 		         = $this->make_url("query/validate/".$this->mybase64_encode($click_url)."/".$directlinkImpressionURL."/".intval($returnArray["directlink"]["source"]));
					
					 	if(($returnArray["directlink"]["source"] == 1 || $returnArray["directlink"]["source"] == 2) && $returnArray["directlink"]["pixel_url"] != "")
						$openURL.= "/".$this->mybase64_encode($returnArray["directlink"]["pixel_url"]);
						//if pixel tracking using img src not working, need to pass pixel url in the above link for url fopen
					}
					else
					$openURL 				= $click_url;
				}
				else
				{
					if($impression_data_string != "" || $ad_container_type == 13 || $ad_container_type == 14)// Video / Skin ads case
					{
						$impressionJSString = $this->get_impression_js(1,$impression_data_string,$md5StringData,$specialtime,$geo_country,$impression_cookie,$adcodeID,$trackingInterval,$display_type,$ad_container_type,$sourceType);

						$ad_buffer_data = str_replace("{IMPRESSION}",$impressionJSString, $ad_buffer_data);
					}
					else
					$ad_buffer_data = str_replace("{IMPRESSION}","", $ad_buffer_data);
				}
			}
			else
			$ad_buffer_data = str_replace("{IMPRESSION}","", $ad_buffer_data);

			if($returnArray["default_ads"] != "")//Default ads case
			{
				$defaultAdImpressionString = $returnArray["default_ads"];

				if(isset($returnArray["pop"]) && $returnArray["pop"]["pop_ad"] == 1) //Assume its pop ads
				{
					if($defaultAdImpressionString !="")
					{
						$popImpressionURL = $this->mybase64_encode($trackDomain.TRACK_DIR."/".$indexAppend."action/impression_default/".$defaultAdImpressionString."/".$adcodeID);
						$openURL 		  = $this->make_url("query/pop_render/".$this->mybase64_encode($click_url)."/".$popImpressionURL);
					}
					else
					$openURL 				= $click_url;					
				}
				else if(isset($returnArray["directlink"]) && $returnArray["directlink"]["directlink_ad"] == 1) //Assume its Directlink
				{
					$click_url = str_replace("{ENCIP}",$cacheEncryptedIP, $click_url);

					if($defaultAdImpressionString != "")
					{
						$directlinkImpressionURL = $this->mybase64_encode($trackDomain.TRACK_DIR."/".$indexAppend."action/impression_default/".$defaultAdImpressionString."/".$adcodeID);
						$openURL 				 = $this->make_url("query/validate/".$this->mybase64_encode($click_url)."/".$directlinkImpressionURL);
					}
					else
					$openURL 				= $click_url;
				}
				else
				{
					$impressionJSString = $this->get_impression_js(0,$defaultAdImpressionString,"",0,"","",$adcodeID,1000,$display_type,$ad_container_type);

					$ad_buffer_data = str_replace("{IMPRESSIONDEFAULT}",$impressionJSString, $ad_buffer_data);
				}
			}
			else
			$ad_buffer_data = str_replace("{IMPRESSIONDEFAULT}","", $ad_buffer_data);


			if($returnArray["pop"]["pop_ad"] == 1)
			{
				if($poptype ==1)
				$popOpenType.= "openWindow = window.open('".$openURL."','','width=".$pop_window_width."px,height=".$pop_window_height."px');";//Up
				else if($poptype ==2)
				$popOpenType.= "openWindow = window.open('".$openURL."');";//NewTab

				//Feed/Exchange ads case pixel tracking
				/*
				if(($returnArray["pop"]["source"] == 1 || $returnArray["pop"]["source"] == 2) && $returnArray["pop"]["pixel_url"] != "")
				$popOpenType.="if(openWindow.open){ var img = document.createElement('img');img.src = '".$returnArray["pop"]["pixel_url"]."';img.style.width = '0px';img.style.height = '0px';document.body.appendChild(img);}";
				*/

				//if($poptype == 1 || $poptype == 2)
				//$popBodyRemove = "for_remove = document.getElementById('pop-body-".$adcodeID."');document.body.removeChild(for_remove);"; // Modification for video click pop display

				$ad_buffer_data = str_replace("{POPWINDOWWIDTH}",$pop_window_width, $ad_buffer_data);
				$ad_buffer_data = str_replace("{POPWINDOWHEIGHT}",$pop_window_height, $ad_buffer_data);
				$ad_buffer_data = str_replace("{POPOPENTYPE}",$popOpenType, $ad_buffer_data);
				$ad_buffer_data = str_replace("{POPBODYREMOVE}",$popBodyRemove, $ad_buffer_data);	
				$ad_buffer_data = str_replace("{POPTRACKDATA}",$popTrackData, $ad_buffer_data);
			}

			if($returnArray["directlink"]["directlink_ad"] == 1)
			{
				$directlinkOpenType.= "window.setTimeout(function () { window.location.href='".$openURL."'; }, 2000);";//NewTab

				//Feed/Exchange ads case pixel tracking
				/*
				if(($returnArray["directlink"]["source"] == 1 || $returnArray["directlink"]["source"] == 2) && $returnArray["directlink"]["pixel_url"] != "")
				$directlinkOpenType.="var img = document.createElement('img');img.src = '".$returnArray["directlink"]["pixel_url"]."';img.style.width = '0px';img.style.height = '0px';document.body.appendChild(img);";
				*/
			
				$ad_buffer_data = str_replace("{DIRECTLINKOPENTYPE}",$directlinkOpenType, $ad_buffer_data);
			}

			if($returnArray["pop"]["pop_ad"] == 0 && $returnArray["directlink"]["directlink_ad"] == 0) //Not POP & Directlink
			{
				if($ad_container_type != 14 && isset($returnArray["ad_id_list"]) &&  $returnArray["ad_id_list"] != "")  //For normal ads
				$jsDisplayString = $this->get_js_data_replace($ad_container_type,$adcodeID,$returnArray["ad_id_list"],$ecommerceFlag);

				if($ad_container_type != 14 && isset($returnArray["expandable_content"]) && $returnArray["expandable_content"] != "")   //For expandable ads
				$jsDisplayString.= $this->get_js_data_replace($ad_container_type,$adcodeID,$returnArray["ad_id_list"],0,$returnArray["expandable_content"]);

				if($ad_container_type == 14 && isset($returnArray["skin_ad_content"]) &&  $returnArray["skin_ad_content"] != "")  //For skin ads
				$jsDisplayString = $this->get_js_data_replace($ad_container_type,$adcodeID,$returnArray["ad_id_list"]);
			}

			$ad_buffer_data = str_replace("{JSCRIPT-REPLACE}",$jsDisplayString, $ad_buffer_data);

			if($returnArray["paid_ads"] == "" && $returnArray["default_ads"] == "")
			{
				$ad_buffer_data = str_replace("{POPWINDOWWIDTH}","", $ad_buffer_data);
				$ad_buffer_data = str_replace("{POPWINDOWHEIGHT}","", $ad_buffer_data);
				$ad_buffer_data = str_replace("{POPOPENTYPE}","", $ad_buffer_data);
				$ad_buffer_data = str_replace("{POPBODYREMOVE}","", $ad_buffer_data);
				$ad_buffer_data = str_replace("{POPTRACKDATA}","", $ad_buffer_data);
				$ad_buffer_data = str_replace("{DIRECTLINKOPENTYPE}","", $ad_buffer_data);
			}

			$ad_buffer_data = str_replace("{FIRSTADID}",intval($returnArray["ad_id_list"]), $ad_buffer_data);

		} /******* Ads available for display **********/
		else /******* Ads not available for display **********/
		{
			$ad_buffer_data = str_replace("{FIRSTADID}",0, $ad_buffer_data);
			$ad_buffer_data = str_replace("{IMPRESSIONSRCURL}",$rotationString, $ad_buffer_data);
			$ad_buffer_data = str_replace("{EXPANDABLECONTENT}",$expandableString, $ad_buffer_data);
			$ad_buffer_data = str_replace("{SKINCONTENT}",$skinString, $ad_buffer_data);					
			$ad_buffer_data = str_replace("{IMPRESSION}","", $ad_buffer_data);
			$ad_buffer_data = str_replace("{IMPRESSIONDEFAULT}","", $ad_buffer_data);
			$ad_buffer_data = str_replace("{JSCRIPT-REPLACE}","", $ad_buffer_data);
			$ad_buffer_data = str_replace("{POPWINDOWWIDTH}","", $ad_buffer_data);
			$ad_buffer_data = str_replace("{POPWINDOWHEIGHT}","", $ad_buffer_data);
			$ad_buffer_data = str_replace("{POPOPENTYPE}","", $ad_buffer_data);
			$ad_buffer_data = str_replace("{POPBODYREMOVE}","", $ad_buffer_data);
			$ad_buffer_data = str_replace("{POPTRACKDATA}","", $ad_buffer_data);
			$ad_buffer_data = str_replace("{DIRECTLINKOPENTYPE}","", $ad_buffer_data);
		} 
		/******* Ads not available for display **********/

		return $ad_buffer_data;
	}
};
?>
