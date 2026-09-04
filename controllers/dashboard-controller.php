<?php
include_once COMMON_DIR_PATH.'helpers'.DS."login-helper.php";
include_once COMMON_DIR_PATH.'helpers'.DS."utility-helper.php";
include_once COMMON_DIR_PATH.'helpers'.DS."image-helper.php";

class DashboardController extends ApplicationController
{
    function before_execute()
    {
        parent::before_execute();

	    if(!(LoginHelper::validate_user_login()))
	    {
	        $this->flash($this->get_message('login failed'), BASE,0);
	    }

	    if($this->get_action()=="advertiser_home")
	    {
	        $uid = intval($this->read_cookie_param(COOKIE_LOGINID));
	        $db  = DAL::get_instance();

	        $status=$db->read_single_column("select adv_status from ".TABLE_PREFIX."users where id=?",array($uid));
		    $adv_dashboard_status= Configuration::get_instance()->read('advertiser_dashboard_status');
	        
            if($adv_dashboard_status != 1)
	        $this->flash($this->get_message('advertiser dashboard is disabled by admin'),BASE,0);

	        if($status !=1)
	        $this->flash($this->get_message('your advertiser account in inactive'), $this->make_url('dashboard/publisher_home'),0);
	    }
	    else if($this->get_action()=="publisher_home")
	    {
	        $uid = intval($this->read_cookie_param(COOKIE_LOGINID));
	        $db  = DAL::get_instance();

	        $status=$db->read_single_column("select pub_status from ".TABLE_PREFIX."users where id=?",array($uid));
            $pub_dashboard_status= Configuration::get_instance()->read('publisher_dashboard_status');
            	
            if($pub_dashboard_status != 1)
            $this->flash($this->get_message('publisher dashboard is disabled by admin'), BASE,0);

	        if($status !=1)
	        $this->flash($this->get_message('your publisher account in inactive'), $this->make_url('dashboard/advertiser_home'),0);
	    }
    }

    function advertiser_home_action()
    {
        $this->set_title($this->get_label('adv home'));

        $uid       = intval($this->read_cookie_param(COOKIE_LOGINID));
        $uname     = $this->read_cookie_param(COOKIE_USERNAME);
        $pass      = $this->read_cookie_param(COOKIE_PASSWORD);
        $mark_type = intval($this->read_cookie_param(COOKIE_ADMARKETTYPE));

        $db= DAL::get_instance();

        if($mark_type != 3)
        {
            $resul=$db->execute_query("select adv_status,pub_status from ".TABLE_PREFIX."users where username=? and password=? and id = ?",array($uname, $pass, $uid));
            $resul_row=$resul->fetch_array();
            $ads=$resul_row['adv_status'];
            $pus=$resul_row['pub_status'];

            if($ads ==1 && $pus ==1)
            {
                setcookie(COOKIE_ADMARKETTYPE,3,0,$this->get_base_path(),$this->get_base_domain());
                $mark_type=3;
            }
        }

        if($_POST)
        {
            $duration=intval($this->read_post_param("duration"));
            $from_date=$this->read_post_param("from_date");
            $to_date=$this->read_post_param("to_date");
        }
        else
        {
            $duration=4;
            $from_date='';
            $to_date='';
        }
	
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
	
    	if($mark_type == 2)
    	$this->flash($this->get_message('invalid operation'),$this->make_url("dashboard/publisher_home"),0);
    	else if($mark_type != 1 && $mark_type != 3)
    	$this->flash($this->get_message('invalid operation'),BASE,0);
            
	    $this->set_variable("from_date",$from_date);
        $this->set_variable("to_date",$to_date);
        $this->set_variable("duration",$duration);
    }

    function publisher_home_action()
    {
        $this->set_title($this->get_label('pub home'));

        $uid       = intval($this->read_cookie_param(COOKIE_LOGINID));
        $uname     = $this->read_cookie_param(COOKIE_USERNAME);
        $pass      = $this->read_cookie_param(COOKIE_PASSWORD);
        $mark_type = intval($this->read_cookie_param(COOKIE_ADMARKETTYPE));

        $db= DAL::get_instance();

        if($_POST)
        {
            $duration=intval($this->read_post_param("duration"));
            $from_date=$this->read_post_param("from_date");
            $to_date=$this->read_post_param("to_date");
        }
        else
        {
            $duration=4;
            $from_date='';
            $to_date='';
        }
	
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
	
	
        if($mark_type != 3)
        {
            $resul=$db->execute_query("select adv_status,pub_status from ".TABLE_PREFIX."users where username=? and password=? and id=?",array($uname, $pass, $uid));
            $resul_row=$resul->fetch_array();
            $ads=$resul_row['adv_status'];
            $pus=$resul_row['pub_status'];

            if($ads == 1 && $pus == 1)
            {
                setcookie(COOKIE_ADMARKETTYPE,3,0,$this->get_base_path(),$this->get_base_domain());
                $mark_type=3;
            }
        }

        if($mark_type == 1)
    	$this->flash($this->get_message('invalid operation'),$this->make_url("dashboard/advertiser_home"),0);
    	else if($mark_type != 2 && $mark_type != 3)
    	$this->flash($this->get_message('invalid operation'),BASE,0);
        $this->set_variable("from_date",$from_date);
        $this->set_variable("to_date",$to_date);
        $this->set_variable("duration",$duration);
    }


    function advertiser_country_action()
    {
        $this->disable_notice_area();
        $db              = DAL::get_instance();
        $uid             = intval($this->read_cookie_param(COOKIE_LOGINID));
        $mark_type       = intval($this->read_cookie_param(COOKIE_ADMARKETTYPE));
        $currency_symbol = Configuration::get_instance()->read('currency_symbol');
        $thousand_separator = Configuration::get_instance()->read('thousand_separator');


        if($mark_type == 2)
    	$this->flash($this->get_message('invalid operation'),$this->make_url("dashboard/publisher_home"),0);
    	else if($mark_type != 1 && $mark_type != 3)
    	$this->flash($this->get_message('invalid operation'),BASE,0);        

        if($_POST)
        {
            $duration      = intval($this->read_post_param("duration"));
            $from_date_get = $this->read_post_param("from_date");
            $to_date_get   = $this->read_post_param("to_date");
            $sortby        = intval($this->read_post_param("sortby"));
        }
        else 
        {
            $duration      = intval($this->read_page_param(1));
            $from_date_get = $this->read_page_param(2);
            $to_date_get   = $this->read_page_param(3);
            $sortby        = 0;
        }

        $this->set_variable("uid",$uid);
        $this->set_variable("duration",$duration);
        $this->set_variable("from_date_get",$from_date_get);
        $this->set_variable("to_date_get",$to_date_get);
        $this->set_variable("sortby",$sortby);
        
        $from_date = $this->mybase64_decode($from_date_get);
        $to_date   = $this->mybase64_decode($to_date_get);    
        
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

        $cpc_enabled=$this->get_addon_status('cpc_enabled');
        $cpa_enabled=$this->get_addon_status('cpa_enabled');
        $cpm_enabled=$this->get_addon_status('cpm_enabled');
        $cpd_enabled=$this->get_addon_status('sponsored_enabled');
        $cpp_enabled=$this->get_addon_status('cpp_enabled');

        $this->set_variable("cpc_enabled",$cpc_enabled);
        $this->set_variable("cpa_enabled",$cpa_enabled);
        $this->set_variable("cpm_enabled",$cpm_enabled);
        $this->set_variable("cpd_enabled",$cpd_enabled);
        $this->set_variable("cpp_enabled",$cpp_enabled);

        $mapData            = "";
        $mapDataArray       = array();
        $mapDataStringArray = array();
        $sumData            = 0;
        $sumDataArray       = array();
        $countryReportArray = array();

        //$timePeriod = array(1), $fromAdmin = 1, $pricing = -1 $uid=-1, $aid=0, $kid=0,  $report_type=0, $sorting=0, $forMap = 0, $country = ''
        $dbResult = $this->get_advertiser_statistics($timePeriod, 0, -1, $uid, 0, 0, 1, 0, 1);

        foreach($dbResult as $rkey => $rvalue)
        {
            if($rkey == 'heading')
            continue;

            $countryCode  = "";
            $countryName  = "";

            $countryArray = explode("::",$rkey);

            if(isset($countryArray[0]))
            $countryCode  = $countryArray[0];

            if(isset($countryArray[1]))
            $countryName  = $countryArray[1];

            if($countryCode == '0')
            continue;

            foreach($rvalue as $rkey1 => $rvalue1)
            {
                if($rkey1 == "total")
                continue;

                $processingData      = 0;
                $processingDataLabel = "";

                if($sortby == 0)
                {
                    $processingData      = (int) str_replace($thousand_separator, '', $rvalue1['impression']);
                    $processingDataLabel = $this->get_label('impressions');
                }
                else if($sortby == 1)
                {
                    $processingData      = (int) str_replace($thousand_separator, '', $rvalue1['click']);
                    $processingDataLabel = $this->get_label('clicks');
                }
                else if($sortby == 2)
                {
                    $processingData      = (int) str_replace($thousand_separator, '', $rvalue1['conversion']);
                    $processingDataLabel = $this->get_label('conversions');
                }
                else 
                {
                    $processingData = (double) str_replace($thousand_separator, '', $rvalue1['spend']);
                    $processingDataLabel = $this->get_label('spend')." (".$currency_symbol.")";
                }

                if(!isset($mapDataArray[$rkey1]))
                $mapDataArray[$rkey1][] = '["'.$this->get_label('country').'", "'.$processingDataLabel.'"]';
                
                if($processingData > 0 && $countryName != "")
                {
                    $mapDataArray[$rkey1][] = '["'.$countryName.'", '.$processingData.']';
                    $sumDataArray[$rkey1]   = $sumDataArray[$rkey1] + $processingData;
                    
                    if(isset($countryReportArray[$rkey1]) && count($countryReportArray[$rkey1]) == 5)
                    continue;

                    $countryReportArray[$rkey1][] = array($countryCode, $countryName, $processingData);
                }
            }
        }

        foreach($mapDataArray as $key => $value)
        {
            $mapDataStringArray[$key] = "[".implode(",", $value)."]";
        }
           
        $this->set_variable("mapDataStringArray", json_encode($mapDataStringArray), 0);
        $this->set_array("sumDataArray", $sumDataArray);
        $this->set_array("countryReportArray",$countryReportArray);
    }

    function publisher_country_action()
    {
        $this->disable_notice_area();
        $db              = DAL::get_instance();
        $uid             = intval($this->read_cookie_param(COOKIE_LOGINID));
        $mark_type       = intval($this->read_cookie_param(COOKIE_ADMARKETTYPE));
        $currency_symbol = Configuration::get_instance()->read('currency_symbol');
        $thousand_separator = Configuration::get_instance()->read('thousand_separator');

        if($mark_type == 1)
        $this->flash($this->get_message('invalid operation'),$this->make_url("dashboard/advertiser_home"),0);
        else if($mark_type != 2 && $mark_type != 3)
        $this->flash($this->get_message('invalid operation'),BASE,0);

        if($_POST)
        {
            $duration      = intval($this->read_post_param("duration"));
            $from_date_get = $this->read_post_param("from_date");
            $to_date_get   = $this->read_post_param("to_date");
            $sortby        = intval($this->read_post_param("sortby"));
        }
        else 
        {
            $duration      = intval($this->read_page_param(1));
            $from_date_get = $this->read_page_param(2);
            $to_date_get   = $this->read_page_param(3);
            $sortby        = 0;
        }
        
        $this->set_variable("uid",$uid);
        $this->set_variable("duration",$duration);
        $this->set_variable("from_date_get",$from_date_get);
        $this->set_variable("to_date_get",$to_date_get);
        $this->set_variable("sortby",$sortby);
        
        $from_date = $this->mybase64_decode($from_date_get);
        $to_date   = $this->mybase64_decode($to_date_get);    
        
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

        $cpc_enabled=$this->get_addon_status('cpc_enabled');
        $cpa_enabled=$this->get_addon_status('cpa_enabled');
        $cpm_enabled=$this->get_addon_status('cpm_enabled');
        $html_enabled=$this->get_addon_status('html_enabled');
        $cpd_enabled=$this->get_addon_status('sponsored_enabled');
        $cpp_enabled=$this->get_addon_status('cpp_enabled');

        $this->set_variable("cpc_enabled",$cpc_enabled);
        $this->set_variable("cpa_enabled",$cpa_enabled);
        $this->set_variable("cpm_enabled",$cpm_enabled);
        $this->set_variable("html_enabled",$html_enabled);
        $this->set_variable("cpd_enabled",$cpd_enabled);
        $this->set_variable("cpp_enabled",$cpp_enabled);


        $mapData            = "";
        $mapDataArray       = array();
        $mapDataStringArray = array();
        $sumData            = 0;
        $sumDataArray       = array();
        $countryReportArray = array();
           
        //$timePeriod = array(1), $fromAdmin = 1, $pricing = -1 $pid=-1, $bid=0, $sid=0,  $report_type=0, $sorting=0, $forMap = 0, $country = ''
        $dbResult = $this->get_publisher_statistics($timePeriod, 0, -1, $uid, 0, 0, 1, 0, 1);


        foreach($dbResult as $rkey => $rvalue)
        {
            if($rkey == 'heading')
            continue;

            $countryCode  = "";
            $countryName  = "";

            $countryArray = explode("::",$rkey);

            if(isset($countryArray[0]))
            $countryCode  = $countryArray[0];

            if(isset($countryArray[1]))
            $countryName  = $countryArray[1];

            if($countryCode == '0')
            continue;

            foreach($rvalue as $rkey1 => $rvalue1)
            {
                if($rkey1 == "total")
                continue;

                $processingData      = 0;
                $processingDataLabel = "";

                if($sortby == 0)
                {
                    $processingData      = (int) str_replace($thousand_separator, '', $rvalue1['impression']);
                    $processingDataLabel = $this->get_label('impressions');
                }
                else if($sortby == 1)
                {
                    $processingData      = (int) str_replace($thousand_separator, '', $rvalue1['click']);
                    $processingDataLabel = $this->get_label('clicks');
                }
                else if($sortby == 2)
                {
                    $processingData      = (int) str_replace($thousand_separator, '', $rvalue1['conversion']);
                    $processingDataLabel = $this->get_label('conversions');
                }
                else 
                {
                    $processingData      = (double) str_replace($thousand_separator, '', $rvalue1['profit']);
                    $processingDataLabel = $this->get_label('profit')." (".$currency_symbol.")";
                }

                if(!isset($mapDataArray[$rkey1]))
                $mapDataArray[$rkey1][] = '["'.$this->get_label('country').'", "'.$processingDataLabel.'"]';
                
                if($processingData > 0 && $countryName != "")
                {
                    $mapDataArray[$rkey1][] = '["'.$countryName.'", '.$processingData.']';
                    $sumDataArray[$rkey1]   = $sumDataArray[$rkey1] + $processingData;
                    
                    if(isset($countryReportArray[$rkey1]) && count($countryReportArray[$rkey1]) == 5)
                    continue;

                    $countryReportArray[$rkey1][] = array($countryCode, $countryName, $processingData);
                }
            }
        }

        foreach($mapDataArray as $key => $value)
        {
            $mapDataStringArray[$key] = "[".implode(",", $value)."]";
        }
           
        $this->set_variable("mapDataStringArray", json_encode($mapDataStringArray), 0);
        $this->set_array("sumDataArray", $sumDataArray);
        $this->set_array("countryReportArray",$countryReportArray);
    }

    function ad_report_action()
    {
        $this->disable_notice_area();
        $uid       = intval($this->read_cookie_param(COOKIE_LOGINID));
        $mark_type = intval($this->read_cookie_param(COOKIE_ADMARKETTYPE));

        if($mark_type == 2)
    	$this->flash($this->get_message('invalid operation'),$this->make_url("dashboard/publisher_home"),0);
    	else if($mark_type != 1 && $mark_type != 3)
    	$this->flash($this->get_message('invalid operation'),BASE,0);


        $duration  = intval($this->read_page_param(1));
        $from_date = $this->mybase64_decode($this->read_page_param(2));
        $to_date   = $this->mybase64_decode($this->read_page_param(3));
        
      

        if($from_date != "" && $to_date == "")
        $to_date = date("d",time()).'/'.date("m",time()).'/'.date("Y",time());

        if($duration == 7 && ($from_date == "" || $to_date == ""))
        {
            $duration  = 1;

            $from_date = "";
            $to_date   = "";
        }


        if($duration == 0)
        $duration=1;

        $this->set_variable("uid",$uid);

        $timePeriod[] = $duration;

        if($duration == 7)
        {
            $timePeriod[] = $from_date;
            $timePeriod[] = $to_date;
        }

        //$timePeriod = array(1), $fromAdmin = 1, $pricing = -1 $uid=-1, $aid=0, $kid=0,  $report_type=0, $country = ''
        $dbResult = $this->get_advertiser_statistics($timePeriod, 0, -1, $uid);
        $this->set_array("reportResult",$dbResult);

        //$timePeriod = array(1), $fromAdmin = 1, $pricing = -1, $uid = -1, $aid = 0, $fromPage = 0, $fromGraph = 0
        $dbResultTimeperiod = $this->get_advertiser_timeperiod_statistics($timePeriod, 0, -1, $uid, 0, 0, 1);
        $this->set_array("reportResultTimeperiod",$dbResultTimeperiod);

        $db= DAL::get_instance();

        $cpc_enabled=$this->get_addon_status('cpc_enabled');
        $cpa_enabled=$this->get_addon_status('cpa_enabled');
        $cpm_enabled=$this->get_addon_status('cpm_enabled');
        $sponsored_enabled=$this->get_addon_status('sponsored_enabled');
        $pop_enabled=$this->get_addon_status('pop-ads_enabled');
        $affiliate_enabled=$this->get_addon_status('affiliate-ads_enabled');
        $textimage_enabled=$this->get_addon_status('text-image-ads_enabled');
        $ecommerce_enabled=$this->get_addon_status('ecommerce-ads_enabled');
        $cpv_enabled=$this->get_addon_status('video-ads_enabled');
        $skin_enabled=$this->get_addon_status('skin-ads_enabled');
        $cpp_enabled=$this->get_addon_status('cpp_enabled');
        $directlink_enabled   = $this->get_addon_status('direct-link-ads_enabled');

        $this->set_variable("cpc_enabled",$cpc_enabled);
        $this->set_variable("cpa_enabled",$cpa_enabled);
        $this->set_variable("cpm_enabled",$cpm_enabled);
        $this->set_variable("sponsored_enabled",$sponsored_enabled);
        $this->set_variable("cpp_enabled",$cpp_enabled);


        $active_ads_cpc=0;
        $active_ads_cpm=0;
        $active_ads_cpa=0;
        $active_ads_cpd=0;

        $sponsoredrunning=0;
        $sponsoredexpired=0;

        $text_ads_enabled=Configuration::get_instance()->read('text-ads_enabled');

        $adtype_str="";

        if($text_ads_enabled !=1)
        $adtype_str.=' AND type <>1 ';

        if($ecommerce_enabled !=1)
        $adtype_str.=' AND type <>7 ';

        if($pop_enabled !=1)
        $adtype_str.=' AND type <>9 ';

        if($textimage_enabled !=1)
        $adtype_str.=' AND type <>11 ';

        if($affiliate_enabled !=1)
        $adtype_str.=' AND type <>12 ';

        if($cpv_enabled !=1)
        $adtype_str.=' AND type <>13 ';    

        if($skin_enabled !=1)
        $adtype_str.=' AND type <>14 ';

        if($cpp_enabled !=1)
        $adtype_str.=' AND type <>18 ';

        if($directlink_enabled !=1)
        $adtype_str.=' AND type <>21 ';

        $spec_string     = " AND ((type =7 AND ecommerce_parent >0) OR ((type =1 OR type =2 OR type =11 OR type =14 OR type =21) AND ecommerce_parent =0)) "; //Ecommerce ads have extra parent row
        $spec_string_cpm = " AND ((type =7 AND ecommerce_parent >0) OR ((type =1 OR type =2 OR type =9 OR type =11 OR type =13 OR type =14 OR type =21) AND ecommerce_parent =0)) "; //Ecommerce ads have extra parent row
        $spec_string_cpa = " AND ((type =7 AND ecommerce_parent >0) OR ((type =1 OR type =2 OR type =11 OR type =12 OR type =14 OR type =21) AND ecommerce_parent =0)) "; //Ecommerce ads have extra parent row

        if($cpc_enabled ==1)
        {
            $active_ads_cpc=$db->read_single_column("select count(id) from ".TABLE_PREFIX."ads where uid=? and status=1 and pricing_status = 1 and display_type=0 ".$spec_string.$adtype_str." ",array($uid));
            $this->set_variable("active_ads_cpc",$active_ads_cpc);
        }

        if($cpa_enabled ==1)
        {
            $active_ads_cpa=$db->read_single_column("select count(id) from ".TABLE_PREFIX."ads where uid=? and status=1 and pricing_status = 1 and display_type=6 ".$spec_string_cpa.$adtype_str." ",array($uid));
            $this->set_variable("active_ads_cpa",$active_ads_cpa);
        }

        if($sponsored_enabled ==1)
        {
            $sponsoredrunning=$db->read_single_column("SELECT count(id) FROM ".TABLE_PREFIX."sponsored_ad_mapping WHERE status=2 AND uid=?",array($uid));
            $sponsoredexpired=$db->read_single_column("SELECT count(id) FROM ".TABLE_PREFIX."sponsored_ad_mapping WHERE status=3 AND uid=?",array($uid));
            $active_ads_cpd=$db->read_single_column("select count(id) from ".TABLE_PREFIX."ads where uid=? and status=1 and pricing_status = 1 and display_type=3 ".$spec_string.$adtype_str." ",array($uid));
            $this->set_variable('sponsoredrunning',$sponsoredrunning);
            $this->set_variable('sponsoredexpired',$sponsoredexpired);
            $this->set_variable("active_ads_cpd",$active_ads_cpd);
        }

        if($cpm_enabled ==1)
        {
            $active_ads_cpm=$db->read_single_column("select count(id) from ".TABLE_PREFIX."ads where uid=? and status=1 and pricing_status = 1 and display_type=1 ".$spec_string_cpm.$adtype_str." ",array($uid));
            $this->set_variable("active_ads_cpm",$active_ads_cpm);
        }

        
        if($cpp_enabled ==1)
        {
            $active_ads_cpp=$db->read_single_column("select count(id) from ".TABLE_PREFIX."ads where uid=? and status=1 and pricing_status = 1 and display_type=18",array($uid));
            $this->set_variable("active_ads_cpp",$active_ads_cpp);
        }
    }

    function adcode_report_action()
    {
        $this->disable_notice_area();
        $uid       = intval($this->read_cookie_param(COOKIE_LOGINID));
        $mark_type = intval($this->read_cookie_param(COOKIE_ADMARKETTYPE));

        if($mark_type == 1)
    	$this->flash($this->get_message('invalid operation'),$this->make_url("dashboard/advertiser_home"),0);
    	else if($mark_type != 2 && $mark_type != 3)
    	$this->flash($this->get_message('invalid operation'),BASE,0);


        $duration  = intval($this->read_page_param(1));
        $from_date = $this->mybase64_decode($this->read_page_param(2));
        $to_date   = $this->mybase64_decode($this->read_page_param(3));
       

        if($from_date != "" && $to_date == "")
        $to_date = date("d",time()).'/'.date("m",time()).'/'.date("Y",time());

        if($duration == 7 && ($from_date == "" || $to_date == ""))
        {
            $duration  = 1;

            $from_date = "";
            $to_date   = "";
        }

        if($duration == 0)
        $duration=1;

        $timePeriod[] = $duration;

        if($duration == 7)
        {
            $timePeriod[] = $from_date;
            $timePeriod[] = $to_date;
        }

        $dbResult = $this->get_publisher_statistics($timePeriod, 0, -1, $uid);
        $this->set_array("reportResult",$dbResult);

        //$timePeriod = array(1), $fromAdmin = 1, $pricing = -1, $pid = -1, $bid = 0, $sid = 0, $fromGraph = 0
        $dbResultTimeperiod= $this->get_publisher_timeperiod_statistics($timePeriod, 0, -1, $uid, 0, 0, 1);
        $this->set_array("reportResultTimeperiod",$dbResultTimeperiod);

        $this->set_variable("uid",$uid);

        $db= DAL::get_instance();

        $sponsoredrunning=0;
        $sponsoredexpired=0;

        $sponsored_enabled=$this->get_addon_status('sponsored_enabled');

        if($sponsored_enabled ==1)
        {
            $sponsoredrunning=$db->read_single_column("SELECT count(id) FROM ".TABLE_PREFIX."sponsored_ad_mapping WHERE status=2 AND publisher=?",array($uid));
            $sponsoredexpired=$db->read_single_column("SELECT count(id) FROM ".TABLE_PREFIX."sponsored_ad_mapping WHERE status=3 AND publisher=?",array($uid));
        }

        $this->set_variable("sponsoredrunning",$sponsoredrunning);
        $this->set_variable("sponsoredexpired",$sponsoredexpired);
    }


    function top_ads_action()
    {
        $this->disable_notice_area();
        $uid       = intval($this->read_cookie_param(COOKIE_LOGINID));
        $mark_type = intval($this->read_cookie_param(COOKIE_ADMARKETTYPE));

        if($mark_type == 2)
    	$this->flash($this->get_message('invalid operation'),$this->make_url("dashboard/publisher_home"),0);
    	else if($mark_type != 1 && $mark_type != 3)
    	$this->flash($this->get_message('invalid operation'),BASE,0);


        $duration=intval($this->read_page_param(1));
        

        if($duration <= 3 || $duration > 5)
        $duration = 3;

        $this->set_variable("uid",$uid);

        $timePeriod[] = $duration;

        //$timePeriod = array(1), $fromAdmin = 1, $pricing = -1 $uid=-1, $aid=0, $kid=0,  $report_type=0, $sorting=0, $forMap = 0, $resultLimit = 0, $country = ''
        $dbResult = $this->get_advertiser_statistics($timePeriod, 0, -1, $uid, 0, 0, 3, 0, 0, 5);
        $this->set_array("reportResult",$dbResult);
    }

    function top_adcodes_action()
    {
        $this->disable_notice_area();
        $uid       = intval($this->read_cookie_param(COOKIE_LOGINID));
        $mark_type = intval($this->read_cookie_param(COOKIE_ADMARKETTYPE));

        if($mark_type == 1)
    	$this->flash($this->get_message('invalid operation'),$this->make_url("dashboard/advertiser_home"),0);
    	else if($mark_type != 2 && $mark_type != 3)
    	$this->flash($this->get_message('invalid operation'),BASE,0);

           
        $duration=intval($this->read_page_param(1));
        

        if($duration <= 3 || $duration > 5)
        $duration = 3;

        $this->set_variable("uid",$uid);


        $timePeriod[] = $duration;

        //$timePeriod = array(1), $fromAdmin = 1, $pricing = -1, $pid = -1, $bid = 0, $sid = 0,  $report_type = 0, $sorting = 0, $forMap = 0, $resultLimit = 0, $country = ''
        $dbResult = $this->get_publisher_statistics($timePeriod, 0, -1, $uid, 0, 0, 4, 0, 0, 5);
        $this->set_array("reportResult",$dbResult);
    }


    function advertiser_report_action()
    {
        $this->disable_notice_area();
        $db = DAL::get_instance();
        $uid       = intval($this->read_cookie_param(COOKIE_LOGINID));
        $uname     = $this->read_cookie_param(COOKIE_USERNAME);
        $pass      = $this->read_cookie_param(COOKIE_PASSWORD);
        $mark_type = intval($this->read_cookie_param(COOKIE_ADMARKETTYPE));

        if($mark_type == 2)
        $this->flash($this->get_message('invalid operation'),$this->make_url("dashboard/publisher_home"),0);
        else if($mark_type != 1 && $mark_type != 3)
        $this->flash($this->get_message('invalid operation'),BASE,0);
        
        
        $res = $db->execute_query("select adv_status,adv_account_balance from ".TABLE_PREFIX."users where username=? and password=? and id = ?",array($uname, $pass, $uid));
        $this->set_result("res",$res);


        $duration  = intval($this->read_page_param(1));
        $from_date = $this->mybase64_decode($this->read_page_param(2));
        $to_date   = $this->mybase64_decode($this->read_page_param(3));
        

        if($from_date != "" && $to_date == "")
        $to_date = date("d",time()).'/'.date("m",time()).'/'.date("Y",time());

        if($duration == 7 && ($from_date == "" || $to_date == ""))
        {
            $duration  = 1;

            $from_date = "";
            $to_date   = "";
        }

        if($duration == 0)
        $duration=1;

        $timePeriod[] = $duration;

        if($duration == 7)
        {
            $timePeriod[] = $from_date;
            $timePeriod[] = $to_date;
        }

        //$timePeriod = array(1), $fromAdmin = 1, $pricing = -1, $uid = -1, $aid = 0, $fromPage = 0, $fromGraph = 0
        $dbResultTimeperiod = $this->get_advertiser_timeperiod_statistics($timePeriod, 0, -1, $uid, 0, 0, 1);
        $this->set_array("reportResultTimeperiod",$dbResultTimeperiod);

        if(isset($_COOKIE['my_locale']))
        $localname = $_COOKIE['my_locale'];
        else
        $localname = DEFAULT_LOCALE;

        $direction=$db->read_single_column("SELECT direction FROM ".TABLE_PREFIX."locale WHERE name=?",array($localname));
        $direction=intval($direction);

        $this->set_variable('direction',$direction);
        $this->set_variable("uid",$uid);
    }

    function publisher_report_action()
    {
        $this->disable_notice_area();
        $db = DAL::get_instance();
        $uname     = $this->read_cookie_param(COOKIE_USERNAME);
        $pass      = $this->read_cookie_param(COOKIE_PASSWORD);
        $uid       = $this->read_cookie_param(COOKIE_LOGINID);
        $mark_type = $this->read_cookie_param(COOKIE_ADMARKETTYPE);

        if($mark_type == 1)
    	$this->flash($this->get_message('invalid operation'),$this->make_url("dashboard/advertiser_home"),0);
    	else if($mark_type != 2 && $mark_type != 3)
    	$this->flash($this->get_message('invalid operation'),BASE,0);

           
        $res=$db->execute_query("select pub_status,pub_account_balance from ".TABLE_PREFIX."users where username=? and password=? and id=?",array($uname,$pass,$uid));
        $this->set_result("res",$res);
        
        $duration  = intval($this->read_page_param(1));
        $from_date = $this->mybase64_decode($this->read_page_param(2));
        $to_date   = $this->mybase64_decode($this->read_page_param(3));
        
        if($from_date != "" && $to_date == "")
        $to_date = date("d",time()).'/'.date("m",time()).'/'.date("Y",time());
    
        if($duration == 7 && ($from_date == "" || $to_date == ""))
        {
            $duration  = 1;
            $from_date = "";
            $to_date   = "";
        }

        if($duration == 0)
        $duration=1;

        $timePeriod[] = $duration;

        if($duration == 7)
        {
            $timePeriod[] = $from_date;
            $timePeriod[] = $to_date;
        }

        //$timePeriod = array(1), $fromAdmin = 1, $pricing = -1, $pid = -1, $bid = 0, $sid = 0, $fromGraph = 0
        $dbResultTimeperiod= $this->get_publisher_timeperiod_statistics($timePeriod, 0, -1, $uid, 0, 0, 1);
        $this->set_array("reportResultTimeperiod",$dbResultTimeperiod);
        
        if(isset($_COOKIE['my_locale']))
        $localname = $_COOKIE['my_locale'];
        else
        $localname = DEFAULT_LOCALE;

        $direction=$db->read_single_column("SELECT direction FROM ".TABLE_PREFIX."locale WHERE name=?",array($localname));
        $direction=intval($direction);

        $this->set_variable('direction',$direction);
        $this->set_variable("uid",$uid);
    }

	function get_graph_data($plottingType, $ImpressionString, $ClickString, $CtrString, $SpendString, $ProfitString = -1, $EcpmString = -1, $ConversionString = -1, $ConversionRatioString = -1)
	{
        //$plottingType => 1 Line & Bar Chart
        //$plottingType => 2 Line Chart Only

        $currencySymbol = Configuration::get_instance()->read('currency_symbol');

		$XAxis = "";
		$YAxis = "";
		if($ImpressionString != -1 && $plottingType == 1)
		{
			$XAxis.="{name:'".$this->get_label('impressions')."',type:'column',data:[".$ImpressionString."]}";	 
 			$YAxis.="{seriesName: '".$this->get_label('impressions')."',axisTicks: {show: true},axisBorder: {show: true,color: '#008FFB'}}";
		}

        	if($ImpressionString != -1 && $plottingType == 2)
		{
            		$XAxis.= "{name:'".$this->get_label('impressions')."',type:'area',data:[".$ImpressionString."]}"; 
			$YAxis.= "{axisTicks: {show: true},axisBorder: {show: true, color: '#2E93fA'}}";
        	}


		if($ClickString != -1 && $plottingType == 1)
		{
			if($XAxis != "")
			$XAxis.=",";
			$XAxis.="{name:'".$this->get_label('clicks')."',type:'column',data:[".$ClickString."]}";	 
			if($YAxis != "")
			$YAxis.=",";
			$YAxis.="{opposite: true,seriesName: '".$this->get_label('clicks')."', axisTicks: {show: true},axisBorder: {show: true,color: '#00E396'}}";			
		}

		if($ClickString != -1 && $plottingType == 2)
		{
            		$XAxis.= "{name:'".$this->get_label('clicks')."',type:'area',data:[".$ClickString."]}"; 
			$YAxis.= "{axisTicks: {show: true},axisBorder: {show: true, color: '#2E93fA'}}";
        	}


        	if($ConversionString != -1 && $plottingType == 1)
		{
			if($XAxis != "")
			$XAxis.=",";
			$XAxis.="{name:'".$this->get_label('conversions')."',type:'column',data:[".$ConversionString."]}";	 
			if($YAxis != "")
			$YAxis.=",";
			$YAxis.="{opposite: true,seriesName: '".$this->get_label('conversions')."', axisTicks: {show: true},axisBorder: {show: true,color: '#00E396'}}";			
		}

		if($ConversionString != -1 && $plottingType == 2)
		{
            		$XAxis.= "{name:'".$this->get_label('conversions')."',type:'area',data:[".$ConversionString."]}"; 
			$YAxis.= "{axisTicks: {show: true},axisBorder: {show: true, color: '#2E93fA'}}";
       		}

		if($CtrString != -1)
		{
			if($XAxis != "")
			$XAxis.=",";
	 		$XAxis.="{name:'".$this->get_label('ctr')."',type:'line',data:[".$CtrString."]}";	 
			if($YAxis != "")
			$YAxis.=",";
			$YAxis.="{opposite: true,seriesName: '".$this->get_label('ctr')."',axisTicks: {show: true},axisBorder: {show: true,color: '#FEB019'}}";			
		}

        
		if($ConversionRatioString != -1)
		{
			if($XAxis != "")
			$XAxis.=",";
	 		$XAxis.="{name:'".$this->get_label('conversion ratio')."',type:'line',data:[".$ConversionRatioString."]}";	 
			if($YAxis != "")
			$YAxis.=",";
			$YAxis.="{opposite: true,seriesName: '".$this->get_label('conversion ratio')."',axisTicks: {show: true},axisBorder: {show: true,color: '#FEB019'}}";			
		}

		if($SpendString != -1 && $plottingType == 2)
		{
			$XAxis.= "{name:'".$this->get_label('spend')." (".$currencySymbol.") ',type:'area',data:[".$SpendString."]}"; 
			$YAxis.= "{seriesName: '".$this->get_label('spend')."', axisTicks: {show: true},axisBorder: {show: true, color: '#2E93fA'}}";
		}
		if($ProfitString != -1 && $plottingType == 2)
		{
			$XAxis.= "{name:'".$this->get_label('profit')." (".$currencySymbol.") ',type:'area',data:[".$ProfitString."]}"; 
			$YAxis.= "{seriesName: '".$this->get_label('profit')."', axisTicks: {show: true},axisBorder: {show: true, color: '#2E93fA'}}";
		}
		return array($XAxis, $YAxis);
	}



}
