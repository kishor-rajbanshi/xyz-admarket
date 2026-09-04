<?php
class ApplicationController extends Controller
{
	function before_execute()
	{
		date_default_timezone_set(Configuration::get_instance()->read('default_time_zone')); 
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
		$count=$db->read_single_column("SELECT count(*) FROM ".TABLE_PREFIX.$tableName);
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
		$mem_obj=$this->memcache_connect();
		if($mem_obj !=false)
		$value=$mem_obj->get($type);
		
		if($value =='')
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
		
	function get_ad_validation_user($aid,$disptype=0)
	{
		$db= DAL::get_instance();
		
		$disptypestring="";
		
		if($disptype >0)
		$disptypestring=" and display_type=".$disptype." ";
		
		$result=$db->read_single_column("select count(id) from ".TABLE_PREFIX."ads where id=? and uid <>0 ".$disptypestring." ",array($aid));
	
		if($result >0)
		return true;
		else
		return false;
	}
	function get_adunit_exists($aduid,$type=0)
	{
		$db= DAL::get_instance();
		
		$typestring="";
		
		if($type >0)
		$typestring=" and display_type=".$type." ";
		
		$result=$db->read_single_column("select count(id) from ".TABLE_PREFIX."adunit where id=? ".$typestring." ",array($aduid));
		
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
		$mem_obj = $this->memcache_connect();
		
		if($mem_obj != false || ADUNIT_MEMCACHE_ENABLED == 1)
		$row = $mem_obj->get('credit_txt_' . $var);

		
		if($from == 0 && $mem_obj != false && $mem_obj->getResultCode() == Memcached::RES_SUCCESS)
		{
			$creditvalue = $row ['ctxt_name'];
			$creditimage = $row ['image'];
			
			if($row ['cttxt_type'] == 1)
			$data = '<img src="' . BASE . DATA_DIR . '/credit/' . $creditimage . '" />';
			else
			$data = $creditvalue;
			
			if($escape == 1 && $row ['cttxt_type'] == 0)
			$data_return = htmlspecialchars($data,ENT_QUOTES,DEFAULT_CHARSET);
			else
			$data_return = $data;
			
			
			if($row['ctxt_icon_type'] ==1)
			$data_icon='<img src="'.BASE.DATA_DIR.'/credit/'.$row['ctxt_icon_content'].'" />';
			else 
			$data_icon=$row['ctxt_icon_content'];
			
			if($escape==1 && $row['ctxt_icon_type'] ==0)
			$data_return_icon=htmlspecialchars($data_icon,ENT_QUOTES,DEFAULT_CHARSET);
			else
			$data_return_icon=$data_icon;				
			
			
			if($return == 1)
			{
				$data_array[] = $data_return;
				$data_array[] = $row['cttxt_type'];
				$data_array[] = $data_return_icon;
				$data_array[] = $row['ctxt_icon_type'];					
				
				return $data_array;
			}
			else
			return $data_return;
		}
		else
		{
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
	}
	
	



	function get_ad_display_preview($aid,$clksurl,$retargetid) // For Ad Display Preview
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
		$cssstring.='#slbox-'.$rowdata['displayid'].'::before {height:'.$rowdata['slider_height'].'px;}';

		if($rowdata['headline_enabled'] ==1)
		$cssstring.='#hebox-'.$rowdata['displayid'].'::before {height:'.$rowdata['headline_height'].'px;}';

		if($rowdata['logo_enabled'] ==1)
		$cssstring.='#lobox-'.$rowdata['displayid'].'::before {height:'.$rowdata['logo_height'].'px;}';

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


								foreach($newarray as $key111=>$row12345data)
								{
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


				$language_direction=$db->read_single_column("SELECT direction FROM ".TABLE_PREFIX."locale WHERE name=?",array($row12345data['language']));







											$loopdata.='<div class="slideblock-style" id="sectionblock-'.$iiii.'" style="width:'.$blockadspace.'px;height:'.$rowdata['slider_height'].'px;margin-left:'.$differencepadding.'px;margin-right:'.$differencepadding.'px;"><div class="slideblockinner-style">';


											if($dummystyle =="")
											{
												$dummystyle.='<style type="text/css">';

												$dummystyle.='.ecommercetitle a:link,.ecommercetitle a:visited,.ecommercetitle a:hover,.ecommercetitle a:active,.ecommercetitle a:focus {color: '.$row12345data['title_color'].' !important;}';
												$dummystyle.='.ecommercedescription a:link,.ecommercedescription a:visited,.ecommercedescription a:hover,.ecommercedescription a:active,.ecommercedescription a:focus {color: '.$row12345data['desc_color'].' !important;}';
												$dummystyle.='.ecommerceurl a:link,.ecommerceurl a:visited,.ecommerceurl a:hover,.ecommerceurl a:active,.ecommerceurl a:focus {color: '.$row12345data['url_color'].' !important;}';

												$dummystyle.='.dummyprice{color: '.$row12345data['price_color'].' !important;}';
												$dummystyle.='.dummyofferprice{color: '.$row12345data['offer_price_color'].' !important;}';

												$dummystyle.='.dummybutton {background-color: '.$row12345data['ab_background_color'].' !important;}';
												$dummystyle.='.dummybutton a {color: '.$row12345data['ab_text_color'].' !important;}';
												$dummystyle.='.dummybutton:hover {background-color: '.$row12345data['ab_bhover_color'].' !important;}';

												$dummystyle.='.span-text-a{color:'.$row12345data['ah_text_color'].' !important;}';
												$dummystyle.='.span-button-a{color:'.$row12345data['abh_text_color'].' !important;}';

												$dummystyle.='.span-button-preview{color:'.$row12345data['abh_text_color'].' !important;background-color: '.$row12345data['abh_background_color'].' !important;}';
												$dummystyle.='.span-button-preview:hover {background-color: '.$row12345data['abh_bhover_color'].' !important;}';

												$dummystyle.='.display-outer-style{border-color:'.$row12345data['border_color'].' !important;background-color: '.$row12345data['background_color'].' !important;}';
												$dummystyle.='.singleadsection-style{border-color:'.$row12345data['border_color'].' !important;background-color: '.$row12345data['ad_background_color'].' !important;}';

												$dummystyle.='.slideleftinner-style, .sliderightinner-style {border-color:'.$row12345data['border_color'].' !important;color:'.$row12345data['border_color'].' !important;background-color: '.$row12345data['background_color'].' !important;}';

												$dummystyle.='</style>';
											}








										}
										else
										{
											$iiii=$iiii+1;


											$loopdata.='</div></div>';

											$looparray[]=$loopdata;

											$loopdata="";

											$loopdata.='<div class="slideblock-style" id="sectionblock-'.$iiii.'" style="width:'.$blockadspace.'px;height:'.$rowdata['slider_height'].'px;margin-left:'.$differencepadding.'px;margin-right:'.$differencepadding.'px;"><div class="slideblockinner-style">';

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

	if($rowdata['pp_weight'] ==1)
	$pricingstring.='font-weight:normal;';
	else
	$pricingstring.='font-weight:bold;';


	if($rowdata['offer_price_enabled'] ==1 && $row12345data['ad_offer_price'] !="")
	$pricingstring.='text-decoration:line-through;';
	else
	{
		if($rowdata['pp_decoration'] ==1)
		$pricingstring.='text-decoration:none;';
		else
		$pricingstring.='text-decoration:underline;';
	}


	$pricingstring.='">'.$row12345data['ad_price'].'</span> ';


}


if($rowdata['offer_price_enabled'] ==1 && $row12345data['ad_price'] !="" && $row12345data['ad_offer_price'] !="")
{
	$pricingstring.='<span class="dummyofferprice" style="';


	$pricingstring.='font-family:'.$rowdata['ppfont'].';font-size:'.$rowdata['ppsize'].'px;line-height:'.$rowdata['pplineheight'].'px;';

	if($rowdata['pp_weight'] ==1)
	$pricingstring.='font-weight:normal;';
	else
	$pricingstring.='font-weight:bold;';



	if($rowdata['pp_decoration'] ==1)
	$pricingstring.='text-decoration:none;';
	else
	$pricingstring.='text-decoration:underline;';



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

if($rowdata['bb_weight'] ==1)
$pricingstring.='font-weight:normal;';
else
$pricingstring.='font-weight:bold;';


if($rowdata['bb_decoration'] ==1)
$pricingstring.='text-decoration:none;';
else
$pricingstring.='text-decoration:underline;';


$pricingstring.='" target="_blank" href="'.$clksurl.'/'.$row12345data['adlid'].'">'.$row12345data['action_text'].'</a></span>';

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



	$layoutstring.='<tr><td colspan="3" style="text-align: center;vertical-align: middle;width:'.$rowdata['image_width'].'px;"><label style="width:'.$rowdata['image_width'].'px;height:'.$rowdata['image_height'].'px;margin:'.$rowdata['layout_padding'].'px;"><a target="_blank" href="'.$clksurl.'/'.$row12345data['adlid'].'"><img style="max-width:'.$rowdata['image_width'].'px;max-height:'.$rowdata['image_height'].'px;" src="'.$imagepathdir.'" /></a></label></td></tr>';
}


if($rowdata['content_type'] !=1)
{




$layoutstring.='<tr>';
if($rowdata['content_type'] ==2 && $rowdata['layout_position'] ==0)
{

	$imagepathdir=BASE.DATA_DIR.'/ecommerce/'.$aid.'/'.$row12345data['ad_image'];



	$layoutstring.='<td style="width:'.$rowdata['image_width'].'px;"><div style="margin:'.$rowdata['layout_padding'].'px;"><a target="_blank" href="'.$clksurl.'/'.$row12345data['adlid'].'"><img style="max-width:'.$rowdata['image_width'].'px;max-height:'.$rowdata['image_height'].'px;" src="'.$imagepathdir.'" /></a></div></td>';
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

if($rowdata['tt_weight'] ==1)
$layoutstring.='font-weight:normal;';
else
$layoutstring.='font-weight:bold;';


if($rowdata['tt_decoration'] ==1)
$layoutstring.='text-decoration:none;';
else
$layoutstring.='text-decoration:underline;';


$layoutstring.='" target="_blank" href="'.$clksurl.'/'.$row12345data['adlid'].'">'.$row12345data['ad_title'].'</a></div>';

}



if($rowdata['description_enabled'] ==1){



$layoutstring.='<div class="ecommercedescription" style="padding-left:'.$rowdata['layout_padding'].'px;padding-right:'.$rowdata['layout_padding'].'px;';

$layoutstring.='"><a ';

$layoutstring.='style="font-family:'.$rowdata['ddfont'].';font-size:'.$rowdata['ddsize'].'px;line-height:'.$rowdata['ddlineheight'].'px;';

if($rowdata['dd_weight'] ==1)
$layoutstring.='font-weight:normal;';
else
$layoutstring.='font-weight:bold;';


if($rowdata['dd_decoration'] ==1)
$layoutstring.='text-decoration:none;';
else
$layoutstring.='text-decoration:underline;';



$layoutstring.='" target="_blank" href="'.$clksurl.'/'.$row12345data['adlid'].'">'.$row12345data['ad_description'].'</a></div>';

}

 if($rowdata['url_enabled'] ==1){


$layoutstring.='<div class="ecommerceurl" style="padding-left:'.$rowdata['layout_padding'].'px;padding-right:'.$rowdata['layout_padding'].'px;';

$layoutstring.='"><a ';

$layoutstring.='style="font-family:'.$rowdata['uufont'].';font-size:'.$rowdata['uusize'].'px;line-height:'.$rowdata['uulineheight'].'px;';

if($rowdata['uu_weight'] ==1)
$layoutstring.='font-weight:normal;';
else
$layoutstring.='font-weight:bold;';


if($rowdata['uu_decoration'] ==1)
$layoutstring.='text-decoration:none;';
else
$layoutstring.='text-decoration:underline;';


$layoutstring.='" target="_blank" href="'.$clksurl.'/'.$row12345data['adlid'].'">'.$row12345data['ad_display_url'].'</a></div>';

} }




$layoutstring.=$pricingstring;



$layoutstring.='</td>';


if($rowdata['content_type'] ==2 && $rowdata['layout_position'] ==2)
{

	$imagepathdir=BASE.DATA_DIR.'/ecommerce/'.$aid.'/'.$row12345data['ad_image'];



	$layoutstring.='<td><div style="margin:'.$rowdata['layout_padding'].'px;"><a target="_blank" href="'.$clksurl.'/'.$row12345data['adlid'].'"><img style="max-width:'.$rowdata['image_width'].'px;max-height:'.$rowdata['image_height'].'px;" src="'.$imagepathdir.'" /></a></div></td>';
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


	$layoutstring.='<tr><td colspan="3" style="text-align: center;vertical-align: middle;width:'.$rowdata['image_width'].'px;"><label style="width:'.$rowdata['image_width'].'px;height:'.$rowdata['image_height'].'px;margin:'.$rowdata['layout_padding'].'px;"><a target="_blank" href="'.$clksurl.'/'.$row12345data['adlid'].'"><img  style="max-width:'.$rowdata['image_width'].'px;max-height:'.$rowdata['image_height'].'px;" src="'.$imagepathdir.'" /></a></label></td></tr>';
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



			$headlinesection='<div class="headlinesection-style" id="hebox-'.$rowdata['displayid'].'" style="';


			$headlinesection.='width:'.$rowdata['headline_width'].'px;';
			$headlinesection.='height:'.$rowdata['headline_height'].'px;';



			if($headline_type ==0)
			$headlinesection.='"><a class="span-text-a" ';
			else
			$headlinesection.='"><a class="span-button-a" ';


			$headlinesection.='style="font-family:'.$rowdata['hhfont'].';font-size:'.$rowdata['hhsize'].'px;line-height:'.$rowdata['hhlineheight'].'px;';

			if($rowdata['hh_weight'] ==1)
			$headlinesection.='font-weight:normal;';
			else
			$headlinesection.='font-weight:bold;';


			if($rowdata['hh_decoration'] ==1)
			$headlinesection.='text-decoration:none;';
			else
			$headlinesection.='text-decoration:underline;';





		$headlinesection.='" target="_blank" href="'.$clksurl.'/0">';

		if($headline_type ==0)
		$headlinesection.='<span class="span-text">'.$headline_text.'</span>';
		else
		$headlinesection.='<span class="span-button-preview" style="padding:'.$rowdata['layout_padding'].'px;">'.$headline_text.'</span>';

		$headlinesection.='</a></div>';



		$logopath=BASE.DATA_DIR."/ecommerce/logo/".$logo;


		$logosection='<div class="logosection-style" id="lobox-'.$rowdata['displayid'].'" style="';



		$logosection.='width:'.$rowdata['logo_width'].'px;';
		$logosection.='height:'.$rowdata['logo_height'].'px;">';




		$logosection.='<a target="_blank" href="'.$clksurl.'/0"><img style="vertical-align: middle;';

		$logosection.='max-width:'.($rowdata['logo_width']-($rowdata['layout_padding']*2)).'px;max-height:'.($rowdata['logo_height']-($rowdata['layout_padding']*2)).'px;';


		$logosection.='" src="'.$logopath.'" /></a>';



		$logosection.='</div>';


								$innerwidth=$blockadspace+($differencepadding*2);


								if($loopdatanew !="")
								{
									if($iiii >1)
									{
										if($language_direction ==1)
										$loopdatanew='<span class="slideleft-style" style="right:0px;left:unset;"><span class="slideleftinner-style" onclick="SlideRight(1);"><</span></span><div class="slideinnerholder-style" style="width:'.$rowdata['slider_width'].'px;"><div class="slideinner-style" style="width:'.($innerwidth*$iiii).'px;"><input type="hidden" name="currentindex" id="currentindex" value="'.$iiii.'" /><input type="hidden" name="blockcount" id="blockcount" value="'.$iiii.'" />'.$loopdatanew.'</div></div><span class="slideright-style" style="left:0px;right:unset;"><span class="sliderightinner-style" onclick="SlideLeft(1);">></span></span>';
										else
										$loopdatanew='<span class="slideleft-style"><span class="slideleftinner-style" onclick="SlideLeft(0);"><</span></span><div class="slideinnerholder-style" style="width:'.$rowdata['slider_width'].'px;"><div class="slideinner-style" style="width:'.($innerwidth*$iiii).'px;"><input type="hidden" name="currentindex" id="currentindex" value="1" /><input type="hidden" name="blockcount" id="blockcount" value="'.$iiii.'" />'.$loopdatanew.'</div></div><span class="slideright-style"><span class="sliderightinner-style" onclick="SlideRight(0);">></span></span>';
									}
									else
									$loopdatanew='<div class="slideinner-style" style="width:'.($innerwidth*$iiii).'px;">'.$loopdatanew.'</div>';


									$loopdatanew.='<style type="text/css">.slideblock-style::before {height:'.$rowdata['slider_height'].'px;}</style>';
								}




								$slidesection='<div class="slidesection-style" id="slbox-'.$rowdata['displayid'].'" style="width:'.$rowdata['slider_width'].'px;height:'.$rowdata['slider_height'].'px;';

								if($language_direction ==1)
								$slidesection.='direction:rtl;';

								$slidesection.='">'.$loopdatanew.'</div>';



								$outerdiv='<div class="display-outer-style" id="display-outer-'.$rowdata['displayid'].'" style="padding:'.$rowdata['layout_padding'].'px;">';




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
	
	function memcache_connect()
	{ 
		if(defined('MEMCACHE_HOST') && defined('MEMCACHE_PORT') && MEMCACHE_HOST !='' && MEMCACHE_PORT !='')
		{
			$mem_cache =new Memcached();
			$mem_cache->addServer(MEMCACHE_HOST,MEMCACHE_PORT);
	
			$memstatus = $mem_cache->getStats();
	
			if(isset($memstatus[MEMCACHE_HOST.":".MEMCACHE_PORT]) && $memstatus[MEMCACHE_HOST.":".MEMCACHE_PORT]["pid"] > 0)
				return $mem_cache;
			else
				return false;
		}
		else
			return false;
	
	}
	
};
?>