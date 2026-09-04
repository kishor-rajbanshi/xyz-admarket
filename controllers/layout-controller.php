<?php
include_once COMMON_DIR_PATH.'helpers'.DS."login-helper.php";
include_once COMMON_DIR_PATH.'helpers'.DS."utility-helper.php";

class LayoutController extends ApplicationController
{
	
	function before_execute()
	{
		parent::before_execute();
		if($this->get_action()=="help")
		{
			if(!(LoginHelper::validate_user_login()))
			{
				$this->flash($this->get_message('login failed'), BASE,0);
			}
		}
	}
	
	
	function help_action()
	{

			$type=$this->read_page_param(1);
			$this->set_variable("type",$type);
	}
	
	
	
	function header_action()
	{
			$db= DAL::get_instance();
		
			$page=intval($this->read_page_param(1));
			$sub_page=intval($this->read_page_param(2));
			$cpanel=$this->read_page_param(3);
			
			
			$active_theme=$this->read_cookie_param('active_theme');
			
			if($active_theme == "")
			$active_theme=Configuration::get_instance()->read('active_theme');			
			
			$this->set_variable('active_theme',$active_theme);
			
			
			
			$theme_type = 0;
			
			
			// $theme_type => 'wild-lavender' => 1
			// $theme_type => 'left-menu' => 2
			
			
			if($active_theme == 'wild-lavender')
			$theme_type = 1;
			else if($active_theme == 'left-menu')
			$theme_type = 2;
			
			
			$logedin=0;

			if(LoginHelper::validate_user_login())
			$logedin=1;
			
			$this->set_variable('logedin',$logedin);
			
			$customres=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."custom_pages WHERE status=1 ORDER BY priority ASC");
			$this->set_result('customres',$customres);		

			$customrescount=$customres->get_num_records();
			
			
			$category_enabled=$this->get_addon_status('category-targeting_enabled');
			$sponsored_enabled=$this->get_addon_status('sponsored_enabled');
			$affiliate_enabled=$this->get_addon_status('affiliate-ads_enabled');			
			$retargeting_enabled=$this->get_addon_status('retargeting_enabled');
			$referral_enabled=$this->get_addon_status('referral_enabled');				
			

			$adv_ref_enabled=0;
			$pub_ref_enabled=0;
			$this->set_variable('referral_enabled',$referral_enabled);			
			if($referral_enabled==1)
			{
				$adv_ref_enabled=Configuration::get_instance()->read('advertiser_referral_enabled');
				$pub_ref_enabled=Configuration::get_instance()->read('publisher_referral_enabled');
				
			}
			if($adv_ref_enabled ==0 && $pub_ref_enabled==0)
				$referral_enabled=0;
			
			if(isset($_COOKIE['my_locale']))
				$localname=$_COOKIE['my_locale'];
			else
				$localname=DEFAULT_LOCALE;
			
			if(Configuration::get_instance()->read('language_enabled') ==1)
			{
				$languagestring=UtilityHelper::get_locale_list($localname);  //echo $languagestring;exit;
				$this->set_variable('languagestring',$languagestring,0);
				
				$localeid=intval($this->get_locale_id($localname));
			}
			else
				$localeid=0;			
			
			
			
			$GLOBALS['menu']=array();			
			
			
			
			$subarray['login']=array();
			$subarray['logout']=array();
			
			
			
			
			$faqseo=$this->get_seo_name('index/faq');
			
			if($faqseo !='')
			$faqurl=BASE.$faqseo;
			else
			$faqurl=$this->make_url("index/faq");				
			
			
			
		
			if($logedin ==1)
			{
				$type_label="login";
				
				$uname=$this->read_cookie_param(COOKIE_USERNAME);
				$pass=$this->read_cookie_param(COOKIE_PASSWORD);
				$uid=$this->read_cookie_param(COOKIE_LOGINID);
				
			
				$res=$db->execute_query("select * from ".TABLE_PREFIX."users where username=? and password=? and id=?",array($uname,$pass,$uid));
				$this->set_result("res",$res);
				
				$querydata=$res->fetch_assoc();				
				
				$adv_status=$querydata['adv_status'];
				$pub_status=$querydata['pub_status'];
				$pub_lastlogin_ip=$querydata['pub_loginip']; 	
				
				$this->set_variable("uid",$uid);
				$this->set_variable("uname",$uname);				
				
				
				
				
				
				
				
				
				
				if($adv_status==1 || $pub_status==1) 
				{
					$subarray[$type_label]['advertiser']['child']="";
					$subarray[$type_label]['advertiser']['link']=$this->make_base_url("user/advertiser_home");
					$subarray[$type_label]['advertiser']['label']=$this->get_label('advertiser');
					
					if($cpanel =="a")
					$selection_class=" active ";
					else
					$selection_class="";
					
					$subarray[$type_label]['advertiser']['class']=$selection_class;
					
					$subarray[$type_label]['advertiser']['icon']='fa-user';				
				
					
					$subarray[$type_label]['publisher']['child']="";
					$subarray[$type_label]['publisher']['link']=$this->make_base_url("user/publisher_home");
					$subarray[$type_label]['publisher']['label']=$this->get_label('publisher');
					
					if($cpanel =="p")
					$selection_class=" active ";
					else
					$selection_class="";
					
					$subarray[$type_label]['publisher']['class']=$selection_class;
					
					$subarray[$type_label]['publisher']['icon']='fa-bullhorn';						
				
				}
				
				$subarray[$type_label]['account']['child']="";
				$subarray[$type_label]['account']['link']=$this->make_base_url("user/account");
				$subarray[$type_label]['account']['label']=$this->get_label('my account');
				
				if($cpanel =="b")
				$selection_class=" active ";
				else
				$selection_class="";
				
				$subarray[$type_label]['account']['class']=$selection_class;
				
				$subarray[$type_label]['account']['icon']='fa-suitcase';						
			
				
				if($referral_enabled ==1)
				{
					$subarray[$type_label]['referral']['child']="";
					$subarray[$type_label]['referral']['link']=$this->make_base_url("dispatch/referral/1");
					$subarray[$type_label]['referral']['label']=$this->get_label('referral');
					
					if($cpanel =="r")
					$selection_class=" active ";
					else
					$selection_class="";
					
					$subarray[$type_label]['referral']['class']=$selection_class;
					
					$subarray[$type_label]['referral']['icon']='fa-users';	     
				}


				
				
				if(($cpanel =="a" || $cpanel == "b" || $cpanel=="r" || $cpanel=="s") && $adv_status ==1)
				{
					$subarray[$type_label]['advertiser_menu']['home']['child']=array();
					$subarray[$type_label]['advertiser_menu']['home']['link']=$this->make_base_url("user/advertiser_home");
					$subarray[$type_label]['advertiser_menu']['home']['label']=$this->get_label('home');
					
					if($page ==1)
					$selection_class=" active ";
					else
					$selection_class="";
					
					$subarray[$type_label]['advertiser_menu']['home']['class']=$selection_class;
					
					$subarray[$type_label]['advertiser_menu']['home']['icon']='fa-home';
					$subarray[$type_label]['advertiser_menu']['home']['id']='1';							
						
					
					$subarray[$type_label]['advertiser_menu']['ads']['child']['create ad']['child']="";
					$subarray[$type_label]['advertiser_menu']['ads']['child']['create ad']['link']=$this->make_base_url("ad/create");
					$subarray[$type_label]['advertiser_menu']['ads']['child']['create ad']['label']=$this->get_label('create ad');
					
					if($page == 2 && $sub_page == 1)
					$selection_class=" active ";
					else
					$selection_class="";					
					
					$subarray[$type_label]['advertiser_menu']['ads']['child']['create ad']['class']=$selection_class;
					$subarray[$type_label]['advertiser_menu']['ads']['child']['create ad']['icon']="fa-plus-square";
					$subarray[$type_label]['advertiser_menu']['ads']['child']['create ad']['id']='a2a1';
					
					
					
					$subarray[$type_label]['advertiser_menu']['ads']['child']['manage ads']['child']="";
					$subarray[$type_label]['advertiser_menu']['ads']['child']['manage ads']['link']=$this->make_base_url("ad/list");
					$subarray[$type_label]['advertiser_menu']['ads']['child']['manage ads']['label']=$this->get_label('manage ads');
					
					if($page == 2 && $sub_page == 2)
					$selection_class=" active ";
					else
					$selection_class="";					
					
					$subarray[$type_label]['advertiser_menu']['ads']['child']['manage ads']['class']=$selection_class;
					$subarray[$type_label]['advertiser_menu']['ads']['child']['manage ads']['icon']="fa-bullhorn";	
					$subarray[$type_label]['advertiser_menu']['ads']['child']['manage ads']['id']='a2a2';
					
					
					
					
					$subarray[$type_label]['advertiser_menu']['ads']['child']['ad statistics']['child']="";
					$subarray[$type_label]['advertiser_menu']['ads']['child']['ad statistics']['link']=$this->make_base_url("ad/statistics");
					$subarray[$type_label]['advertiser_menu']['ads']['child']['ad statistics']['label']=$this->get_label('ad statistics');
					
					if($page == 2 && $sub_page == 3)
					$selection_class=" active ";
					else
					$selection_class="";					
					
					$subarray[$type_label]['advertiser_menu']['ads']['child']['ad statistics']['class']=$selection_class;
					$subarray[$type_label]['advertiser_menu']['ads']['child']['ad statistics']['icon']="fa-bar-chart";	
					$subarray[$type_label]['advertiser_menu']['ads']['child']['ad statistics']['id']='a2a3';
					
					
					
					
					if($retargeting_enabled ==1)
					{
						$subarray[$type_label]['advertiser_menu']['ads']['child']['retargeting']['child']="";
						$subarray[$type_label]['advertiser_menu']['ads']['child']['retargeting']['link']=$this->make_base_url("dispatch/retargeting/1");
						$subarray[$type_label]['advertiser_menu']['ads']['child']['retargeting']['label']=$this->get_label('retargeting');
						
						if($page == 2 && $sub_page == 4)
						$selection_class=" active ";
						else
						$selection_class="";					
						
						$subarray[$type_label]['advertiser_menu']['ads']['child']['retargeting']['class']=$selection_class;
						$subarray[$type_label]['advertiser_menu']['ads']['child']['retargeting']['icon']="fa-recycle";		
						$subarray[$type_label]['advertiser_menu']['ads']['child']['retargeting']['id']='a2a4';
						
					}					
					
					
					
					
					
					
					$subarray[$type_label]['advertiser_menu']['ads']['link']="";
					$subarray[$type_label]['advertiser_menu']['ads']['label']=$this->get_label('ads');
					
					if($page == 2)
					$selection_class=" active ";
					else
					$selection_class="";
					
					$subarray[$type_label]['advertiser_menu']['ads']['class']=$selection_class;
									
					$subarray[$type_label]['advertiser_menu']['ads']['icon']='fa-tags';				
					$subarray[$type_label]['advertiser_menu']['ads']['id']='2';	
					

					
					

				
				
					$subarray[$type_label]['advertiser_menu']['payments']['child']['add fund']['child']="";
					$subarray[$type_label]['advertiser_menu']['payments']['child']['add fund']['link']=$this->make_base_url("advertiser/add_fund");
					$subarray[$type_label]['advertiser_menu']['payments']['child']['add fund']['label']=$this->get_label('add fund');
					
					if($page == 3 && $sub_page == 1)
					$selection_class=" active ";
					else
					$selection_class="";					
					
					$subarray[$type_label]['advertiser_menu']['payments']['child']['add fund']['class']=$selection_class;
					$subarray[$type_label]['advertiser_menu']['payments']['child']['add fund']['icon']="fa-plus-square";
					$subarray[$type_label]['advertiser_menu']['payments']['child']['add fund']['id']='a3a1';
					
					
					
					
					$subarray[$type_label]['advertiser_menu']['payments']['child']['payment history']['child']="";
					$subarray[$type_label]['advertiser_menu']['payments']['child']['payment history']['link']=$this->make_base_url("advertiser/payment_history");
					$subarray[$type_label]['advertiser_menu']['payments']['child']['payment history']['label']=$this->get_label('payment history');
					
					if($page == 3 && $sub_page == 2)
					$selection_class=" active ";
					else
					$selection_class="";					
					
					$subarray[$type_label]['advertiser_menu']['payments']['child']['payment history']['class']=$selection_class;
					$subarray[$type_label]['advertiser_menu']['payments']['child']['payment history']['icon']="fa-history";	
					$subarray[$type_label]['advertiser_menu']['payments']['child']['payment history']['id']='a3a2';
					
					
					
					
					$subarray[$type_label]['advertiser_menu']['payments']['link']="";
					$subarray[$type_label]['advertiser_menu']['payments']['label']=$this->get_label('payments');
					
					if($page == 3)
					$selection_class=" active ";
					else
					$selection_class="";
					
					$subarray[$type_label]['advertiser_menu']['payments']['class']=$selection_class;
									
					$subarray[$type_label]['advertiser_menu']['payments']['icon']='fa-usd';		
					$subarray[$type_label]['advertiser_menu']['payments']['id']='3';				
				
									
					$subarray[$type_label]['advertiser_menu']['all statistics']['child']=array();
					$subarray[$type_label]['advertiser_menu']['all statistics']['link']=$this->make_base_url("advertiser/statistics");
					$subarray[$type_label]['advertiser_menu']['all statistics']['label']=$this->get_label('all statistics');
					
					if($page ==4)
					$selection_class=" active ";
					else
					$selection_class="";
					
					$subarray[$type_label]['advertiser_menu']['all statistics']['class']=$selection_class;
					
					$subarray[$type_label]['advertiser_menu']['all statistics']['icon']='fa-bar-chart';	
					$subarray[$type_label]['advertiser_menu']['all statistics']['id']='4';							
							
				
				}
				else if(($cpanel=="p" || $cpanel == "b" || $cpanel=="r" || $cpanel=="s") && $pub_status==1)
				{
					if($category_enabled ==1)
					{
						$url1=$this->make_base_url("dispatch/category_targeting/5");
						$url2=$this->make_base_url("dispatch/category_targeting/6");
						$url3=$this->make_base_url("dispatch/category_targeting/11");
					}					
					
					
					$subarray[$type_label]['publisher_menu']['home']['child']=array();
					$subarray[$type_label]['publisher_menu']['home']['link']=$this->make_base_url("user/publisher_home");
					$subarray[$type_label]['publisher_menu']['home']['label']=$this->get_label('home');
					
					if($page ==5)
					$selection_class=" active ";
					else
					$selection_class="";
					
					$subarray[$type_label]['publisher_menu']['home']['class']=$selection_class;
					
					$subarray[$type_label]['publisher_menu']['home']['icon']='fa-home';
					$subarray[$type_label]['publisher_menu']['home']['id']='5';		

					
					if($category_enabled ==1)
					{
						$subarray[$type_label]['publisher_menu']['sites']['child']['add site']['child']="";
						$subarray[$type_label]['publisher_menu']['sites']['child']['add site']['link']=$url1;
						$subarray[$type_label]['publisher_menu']['sites']['child']['add site']['label']=$this->get_label('add site');
						
						if($page == 13 && $sub_page == 1)
						$selection_class=" active ";
						else
						$selection_class="";					
						
						$subarray[$type_label]['publisher_menu']['sites']['child']['add site']['class']=$selection_class;
						$subarray[$type_label]['publisher_menu']['sites']['child']['add site']['icon']="fa-plus-square";
						$subarray[$type_label]['publisher_menu']['sites']['child']['add site']['id']='a13a1';
						
						
						
						$subarray[$type_label]['publisher_menu']['sites']['child']['manage sites']['child']="";
						$subarray[$type_label]['publisher_menu']['sites']['child']['manage sites']['link']=$url2;
						$subarray[$type_label]['publisher_menu']['sites']['child']['manage sites']['label']=$this->get_label('manage sites');
						
						if($page == 13 && $sub_page == 2)
						$selection_class=" active ";
						else
						$selection_class="";					
						
						$subarray[$type_label]['publisher_menu']['sites']['child']['manage sites']['class']=$selection_class;
						$subarray[$type_label]['publisher_menu']['sites']['child']['manage sites']['icon']="fa-bullhorn";	
						$subarray[$type_label]['publisher_menu']['sites']['child']['manage sites']['id']='a13a2';
						
						
						
						
						$subarray[$type_label]['publisher_menu']['sites']['child']['site statistics']['child']="";
						$subarray[$type_label]['publisher_menu']['sites']['child']['site statistics']['link']=$url3;
						$subarray[$type_label]['publisher_menu']['sites']['child']['site statistics']['label']=$this->get_label('site statistics');
						
						if($page == 13 && $sub_page == 3)
						$selection_class=" active ";
						else
						$selection_class="";					
						
						$subarray[$type_label]['publisher_menu']['sites']['child']['site statistics']['class']=$selection_class;
						$subarray[$type_label]['publisher_menu']['sites']['child']['site statistics']['icon']="fa-bar-chart";	
						$subarray[$type_label]['publisher_menu']['sites']['child']['site statistics']['id']='a13a3';
						
						
					
						$subarray[$type_label]['publisher_menu']['sites']['link']="";
						$subarray[$type_label]['publisher_menu']['sites']['label']=$this->get_label('sites');
						
						if($page == 13)
						$selection_class=" active ";
						else
						$selection_class="";
						
						$subarray[$type_label]['publisher_menu']['sites']['class']=$selection_class;
										
						$subarray[$type_label]['publisher_menu']['sites']['icon']='fa-sitemap';				
						$subarray[$type_label]['publisher_menu']['sites']['id']='13';	
					}
					
					
					
					$subarray[$type_label]['publisher_menu']['adunits']['child']['new adunit']['child']="";
					$subarray[$type_label]['publisher_menu']['adunits']['child']['new adunit']['link']=$this->make_base_url("adunit/create");
					$subarray[$type_label]['publisher_menu']['adunits']['child']['new adunit']['label']=$this->get_label('new adunit');
					
					if($page == 6 && $sub_page == 1)
					$selection_class=" active ";
					else
					$selection_class="";					
					
					$subarray[$type_label]['publisher_menu']['adunits']['child']['new adunit']['class']=$selection_class;
					$subarray[$type_label]['publisher_menu']['adunits']['child']['new adunit']['icon']="fa-plus-square";
					$subarray[$type_label]['publisher_menu']['adunits']['child']['new adunit']['id']='a6a1';
					
					
					
					$subarray[$type_label]['publisher_menu']['adunits']['child']['manage adunits']['child']="";
					$subarray[$type_label]['publisher_menu']['adunits']['child']['manage adunits']['link']=$this->make_base_url("adunit/view");
					$subarray[$type_label]['publisher_menu']['adunits']['child']['manage adunits']['label']=$this->get_label('manage adunits');
					
					if($page == 6 && $sub_page == 2)
					$selection_class=" active ";
					else
					$selection_class="";					
					
					$subarray[$type_label]['publisher_menu']['adunits']['child']['manage adunits']['class']=$selection_class;
					$subarray[$type_label]['publisher_menu']['adunits']['child']['manage adunits']['icon']="fa-bullhorn";	
					$subarray[$type_label]['publisher_menu']['adunits']['child']['manage adunits']['id']='a6a2';
					
					
					$subarray[$type_label]['publisher_menu']['adunits']['child']['manage restricted sites']['child']="";
					$subarray[$type_label]['publisher_menu']['adunits']['child']['manage restricted sites']['link']=$this->make_base_url("publisher/view_site_filters");
					$subarray[$type_label]['publisher_menu']['adunits']['child']['manage restricted sites']['label']=$this->get_label('site filters');
					
					if($page == 6 && $sub_page == 4)
					$selection_class=" active ";
					else
					$selection_class="";					
					
					$subarray[$type_label]['publisher_menu']['adunits']['child']['manage restricted sites']['class']=$selection_class;
					$subarray[$type_label]['publisher_menu']['adunits']['child']['manage restricted sites']['icon']="fa-filter";		
					$subarray[$type_label]['publisher_menu']['adunits']['child']['manage restricted sites']['id']='a6a4';
						
									
					$subarray[$type_label]['publisher_menu']['adunits']['child']['adunit statistics']['child']="";
					$subarray[$type_label]['publisher_menu']['adunits']['child']['adunit statistics']['link']=$this->make_base_url("adunit/statistics");
					$subarray[$type_label]['publisher_menu']['adunits']['child']['adunit statistics']['label']=$this->get_label('adunit statistics');
					
					if($page == 6 && $sub_page == 5)
					$selection_class=" active ";
					else
					$selection_class="";					
					
					$subarray[$type_label]['publisher_menu']['adunits']['child']['adunit statistics']['class']=$selection_class;
					$subarray[$type_label]['publisher_menu']['adunits']['child']['adunit statistics']['icon']="fa-bar-chart";		
					$subarray[$type_label]['publisher_menu']['adunits']['child']['adunit statistics']['id']='a6a5';					
					
					
					
					$subarray[$type_label]['publisher_menu']['adunits']['link']="";
					$subarray[$type_label]['publisher_menu']['adunits']['label']=$this->get_label('adunits');
					
					if($page == 6)
					$selection_class=" active ";
					else
					$selection_class="";
					
					$subarray[$type_label]['publisher_menu']['adunits']['class']=$selection_class;
									
					$subarray[$type_label]['publisher_menu']['adunits']['icon']='fa-file-code-o';				
					$subarray[$type_label]['publisher_menu']['adunits']['id']='6';	
					

					
					
					
									
					$subarray[$type_label]['publisher_menu']['all statistics']['child']=array();
					$subarray[$type_label]['publisher_menu']['all statistics']['link']=$this->make_base_url("publisher/statistics");
					$subarray[$type_label]['publisher_menu']['all statistics']['label']=$this->get_label('all statistics');
					
					if($page ==8)
					$selection_class=" active ";
					else
					$selection_class="";
					
					$subarray[$type_label]['publisher_menu']['all statistics']['class']=$selection_class;
					
					$subarray[$type_label]['publisher_menu']['all statistics']['icon']='fa-bar-chart';	
					$subarray[$type_label]['publisher_menu']['all statistics']['id']='8';							

				}	
				
				
				if(($cpanel == "b" || $theme_type == 2) && ($adv_status==1 || $pub_status==1))
				{
					$subarray[$type_label]['common_menu']['my account']['child']=array();
					$subarray[$type_label]['common_menu']['my account']['link']=$this->make_base_url("user/account");
					$subarray[$type_label]['common_menu']['my account']['label']=$this->get_label('my account');
					
					if($page ==9)
					$selection_class=" active ";
					else
					$selection_class="";
					
					$subarray[$type_label]['common_menu']['my account']['class']=$selection_class;
					
					$subarray[$type_label]['common_menu']['my account']['icon']='fa-user-plus';
					$subarray[$type_label]['common_menu']['my account']['id']='9';		

					
					
					
					if($pub_status==1 || $referral_enabled ==1)
					{
						$subarray[$type_label]['common_menu']['withdrawals']['child']['configure withdrawal details']['child']="";
						$subarray[$type_label]['common_menu']['withdrawals']['child']['configure withdrawal details']['link']=$this->make_base_url("user/withdrawal_configuration");
						$subarray[$type_label]['common_menu']['withdrawals']['child']['configure withdrawal details']['label']=$this->get_label('configure withdrawal details');
						
						if($page == 7 && $sub_page == 1)
						$selection_class=" active ";
						else
						$selection_class="";					
						
						$subarray[$type_label]['common_menu']['withdrawals']['child']['configure withdrawal details']['class']=$selection_class;
						$subarray[$type_label]['common_menu']['withdrawals']['child']['configure withdrawal details']['icon']="fa-gear";
						$subarray[$type_label]['common_menu']['withdrawals']['child']['configure withdrawal details']['id']='a7a1';
						
						if(Configuration::get_instance()->read('enable_auto_withdrawal')==2)
						{
						
						$subarray[$type_label]['common_menu']['withdrawals']['child']['withdrawal request']['child']="";
						$subarray[$type_label]['common_menu']['withdrawals']['child']['withdrawal request']['link']=$this->make_base_url("user/cash_withdrawal");
						$subarray[$type_label]['common_menu']['withdrawals']['child']['withdrawal request']['label']=$this->get_label('withdrawal request');
						
						if($page == 7 && $sub_page == 2)
						$selection_class=" active ";
						else
						$selection_class="";					
						
						$subarray[$type_label]['common_menu']['withdrawals']['child']['withdrawal request']['class']=$selection_class;
						$subarray[$type_label]['common_menu']['withdrawals']['child']['withdrawal request']['icon']="fa-hand-o-up";	
						$subarray[$type_label]['common_menu']['withdrawals']['child']['withdrawal request']['id']='a7a2';
						
						}
						
						
						$subarray[$type_label]['common_menu']['withdrawals']['child']['withdrawal history']['child']="";
						$subarray[$type_label]['common_menu']['withdrawals']['child']['withdrawal history']['link']=$this->make_base_url("user/withdrawal_history");
						$subarray[$type_label]['common_menu']['withdrawals']['child']['withdrawal history']['label']=$this->get_label('withdrawal history');
						
						if($page == 7 && $sub_page == 3)
						$selection_class=" active ";
						else
						$selection_class="";					
						
						$subarray[$type_label]['common_menu']['withdrawals']['child']['withdrawal history']['class']=$selection_class;
						$subarray[$type_label]['common_menu']['withdrawals']['child']['withdrawal history']['icon']="fa-history";	
						$subarray[$type_label]['common_menu']['withdrawals']['child']['withdrawal history']['id']='a7a3';
						
						
						
						
						if($adv_status==1)
						{
							$subarray[$type_label]['common_menu']['withdrawals']['child']['transfer to advertiser account']['child']="";
							$subarray[$type_label]['common_menu']['withdrawals']['child']['transfer to advertiser account']['link']=$this->make_base_url("user/fund_transfer_request");;
							$subarray[$type_label]['common_menu']['withdrawals']['child']['transfer to advertiser account']['label']=$this->get_label('transfer to advertiser account');
							
							if($page == 7 && $sub_page == 4)
							$selection_class=" active ";
							else
							$selection_class="";					
							
							$subarray[$type_label]['common_menu']['withdrawals']['child']['transfer to advertiser account']['class']=$selection_class;
							$subarray[$type_label]['common_menu']['withdrawals']['child']['transfer to advertiser account']['icon']="fa-arrows";	
							$subarray[$type_label]['common_menu']['withdrawals']['child']['transfer to advertiser account']['id']='a7a4';						
						}

						
						
					
						$subarray[$type_label]['common_menu']['withdrawals']['link']="";
						$subarray[$type_label]['common_menu']['withdrawals']['label']=$this->get_label('withdrawals');
						
						if($page == 7)
						$selection_class=" active ";
						else
						$selection_class="";
						
						$subarray[$type_label]['common_menu']['withdrawals']['class']=$selection_class;
										
						$subarray[$type_label]['common_menu']['withdrawals']['icon']='fa-credit-card';				
						$subarray[$type_label]['common_menu']['withdrawals']['id']='7';	
					}
					
					
									
					$subarray[$type_label]['common_menu']['edit profile']['child']=array();
					$subarray[$type_label]['common_menu']['edit profile']['link']=$this->make_base_url("user/edit_profile");
					$subarray[$type_label]['common_menu']['edit profile']['label']=$this->get_label('edit profile');
					
					if($page ==10)
					$selection_class=" active ";
					else
					$selection_class="";
					
					$subarray[$type_label]['common_menu']['edit profile']['class']=$selection_class;
					
					$subarray[$type_label]['common_menu']['edit profile']['icon']='fa-edit';	
					$subarray[$type_label]['common_menu']['edit profile']['id']='10';		

					
					
					$subarray[$type_label]['common_menu']['change password']['child']=array();
					$subarray[$type_label]['common_menu']['change password']['link']=$this->make_base_url("user/change_password");
					$subarray[$type_label]['common_menu']['change password']['label']=$this->get_label('change password');
					
					if($page ==11)
					$selection_class=" active ";
					else
					$selection_class="";
					
					$subarray[$type_label]['common_menu']['change password']['class']=$selection_class;
					
					$subarray[$type_label]['common_menu']['change password']['icon']='fa-key';	
					$subarray[$type_label]['common_menu']['change password']['id']='11';							
					
					if($adv_status ==-2)
					{
						$subarray[$type_label]['common_menu']['adv account request']['child']=array();
						$subarray[$type_label]['common_menu']['adv account request']['link']=$this->make_base_url("user/advertiser_request");
						$subarray[$type_label]['common_menu']['adv account request']['label']=$this->get_label('adv account request');
						
						if($page ==12)
						$selection_class=" active ";
						else
						$selection_class="";
						
						$subarray[$type_label]['common_menu']['adv account request']['class']=$selection_class;
						
						$subarray[$type_label]['common_menu']['adv account request']['icon']='fa-arrows';	
						$subarray[$type_label]['common_menu']['adv account request']['id']='12';						
												
					}
					
					if($pub_status ==-2) 
					{	
						$subarray[$type_label]['common_menu']['pub account request']['child']=array();
						$subarray[$type_label]['common_menu']['pub account request']['link']=$this->make_base_url("user/publisher_request");
						$subarray[$type_label]['common_menu']['pub account request']['label']=$this->get_label('pub account request');
						
						if($page ==12)
						$selection_class=" active ";
						else
						$selection_class="";
						
						$subarray[$type_label]['common_menu']['pub account request']['class']=$selection_class;
						
						$subarray[$type_label]['common_menu']['pub account request']['icon']='fa-arrows';	
						$subarray[$type_label]['common_menu']['pub account request']['id']='12';						
					}
				}
				
				if(($cpanel=="r" || $theme_type == 2) && $referral_enabled ==1 && ($adv_status==1 || $pub_status==1))
				{
					$subarray[$type_label]['referral_menu']['get referral code']['child']=array();
					$subarray[$type_label]['referral_menu']['get referral code']['link']=$this->make_base_url("dispatch/referral/1");
					$subarray[$type_label]['referral_menu']['get referral code']['label']=$this->get_label('get referral code');
					
					if($page ==14)
					$selection_class=" active ";
					else
					$selection_class="";
					
					$subarray[$type_label]['referral_menu']['get referral code']['class']=$selection_class;
					
					$subarray[$type_label]['referral_menu']['get referral code']['icon']='fa-user-plus';
					$subarray[$type_label]['referral_menu']['get referral code']['id']='14';	

					
					$subarray[$type_label]['referral_menu']['referral reports']['child']=array();
					$subarray[$type_label]['referral_menu']['referral reports']['link']=$this->make_base_url("dispatch/referral/2");
					$subarray[$type_label]['referral_menu']['referral reports']['label']=$this->get_label('referral reports');
					
					if($page ==15)
					$selection_class=" active ";
					else
					$selection_class="";
					
					$subarray[$type_label]['referral_menu']['referral reports']['class']=$selection_class;
					
					$subarray[$type_label]['referral_menu']['referral reports']['icon']='fa-edit';
					$subarray[$type_label]['referral_menu']['referral reports']['id']='15';						
					
				}
				
				
			}
			else
			{
				$type_label="logout";
				
				
				
				if($theme_type == 0)
				{
	 				$advseo=$this->get_seo_name('index/advertiser');
					
					if($advseo !='')
					$advurl=BASE.$advseo;
					else 
					$advurl=$this->make_url("index/advertiser");
				}
				else
				$advurl = BASE.'#advertiser';
				
				
				if($theme_type == 0)
				{				
					$pubseo=$this->get_seo_name('index/publisher');
					
					if($pubseo !='')
					$puburl=BASE.$pubseo;
					else
					$puburl=$this->make_url("index/publisher");
				}
				else
				$puburl = BASE.'#publisher';	

				
				if($theme_type == 0)
				$baseurl = BASE;
				else
				$baseurl = BASE.'#mainslider';		

				
				$subarray[$type_label]['home']['child']="";
				$subarray[$type_label]['home']['link']=$baseurl;
				$subarray[$type_label]['home']['label']=$this->get_label('home');
				
				if($page == 16)
				$selection_class=" active ";
				else
				$selection_class="";
				
				$subarray[$type_label]['home']['class']=$selection_class;
				
				$subarray[$type_label]['home']['icon']='fa-home';
				
				
				$subarray[$type_label]['advertiser']['child']="";
				$subarray[$type_label]['advertiser']['link']=$advurl;
				$subarray[$type_label]['advertiser']['label']=$this->get_label('advertiser');
				
				if($page == 17)
				$selection_class=" active ";
				else
				$selection_class="";
				
				$subarray[$type_label]['advertiser']['class']=$selection_class;			
				
				$subarray[$type_label]['advertiser']['icon']='fa-user';				
				
				$subarray[$type_label]['publisher']['child']="";
				$subarray[$type_label]['publisher']['link']=$puburl;
				$subarray[$type_label]['publisher']['label']=$this->get_label('publisher');
				
				if($page == 18)
				$selection_class=" active ";
				else
				$selection_class="";
				
				$subarray[$type_label]['publisher']['class']=$selection_class;			
				
				$subarray[$type_label]['publisher']['icon']='fa-bullhorn';	

			}
			
			
			
			
			
			
			
			   if($sponsored_enabled ==1)
			   {
					$cpdseo=$this->get_seo_name('dispatch/sponsored/24');
				
					if($cpdseo !='')
					$cpdurl=BASE.$cpdseo;
					else
					$cpdurl=$this->make_url('dispatch/sponsored/24');
			   }
			
			
			   if($affiliate_enabled ==1)
			   {
					$affiliateseo=$this->get_seo_name('dispatch/affiliate-ads/1');
				
					if($affiliateseo !='')
					$affiliateurl=BASE.$affiliateseo;
					else
					$affiliateurl=$this->make_url('dispatch/affiliate-ads/1');
			   }			
			

				if($sponsored_enabled ==1 && $affiliate_enabled ==1)
				{
					
					$subarray[$type_label]['marketplace']['child']['cpd marketplace']['child']="";
					$subarray[$type_label]['marketplace']['child']['cpd marketplace']['link']=$cpdurl;
					$subarray[$type_label]['marketplace']['child']['cpd marketplace']['label']=$this->get_label('cpd marketplace');
					$subarray[$type_label]['marketplace']['child']['cpd marketplace']['class']="";
					$subarray[$type_label]['marketplace']['child']['cpd marketplace']['icon']="";
					
					
					$subarray[$type_label]['marketplace']['child']['affiliate marketplace']['child']="";
					$subarray[$type_label]['marketplace']['child']['affiliate marketplace']['link']=$affiliateurl;
					$subarray[$type_label]['marketplace']['child']['affiliate marketplace']['label']=$this->get_label('affiliate marketplace');
					$subarray[$type_label]['marketplace']['child']['affiliate marketplace']['class']="";
					$subarray[$type_label]['marketplace']['child']['affiliate marketplace']['icon']="";					
					
					
					$subarray[$type_label]['marketplace']['link']="";
					$subarray[$type_label]['marketplace']['label']=$this->get_label('marketplace');
					
					if($page == 26 || $page == 27)
					$selection_class=" active ";
					else
					$selection_class="";
					
					$subarray[$type_label]['marketplace']['class']=$selection_class;
									
					$subarray[$type_label]['marketplace']['icon']='fa-gift';						
				}
				else
				{
					if($sponsored_enabled ==1)
					{
						$subarray[$type_label]['marketplace']['child']="";
						$subarray[$type_label]['marketplace']['link']=$cpdurl;
						$subarray[$type_label]['marketplace']['label']=$this->get_label('cpd marketplace');
						
						if($page == 26)
						$selection_class=" active ";
						else
						$selection_class="";
						
						$subarray[$type_label]['marketplace']['class']=$selection_class;
										
						$subarray[$type_label]['marketplace']['icon']='fa-gift';						
					}
					
					if($affiliate_enabled ==1)
					{
						$subarray[$type_label]['marketplace']['child']="";
						$subarray[$type_label]['marketplace']['link']=$affiliateurl;
						$subarray[$type_label]['marketplace']['label']=$this->get_label('affiliate marketplace');
						
						if($page == 27)
						$selection_class=" active ";
						else
						$selection_class="";
						
						$subarray[$type_label]['marketplace']['class']=$selection_class;
										
						$subarray[$type_label]['marketplace']['icon']='fa-gift';						
					}
				}
				

				if(Configuration::get_instance()->read('display_custom_pages')==1 && $customrescount >0)
				{
					while($value = $customres->fetch_assoc())
					{
						$ppcseo=$this->get_seo_name_custom($value['id']);
						$ppcurl=BASE.$ppcseo;
						
						$customname="";
						
						if($localeid > 0 && isset($value[$localeid.'_name']))
						$customname=$value[$localeid.'_name'];
						
						if($customname =="")
						$customname=$value['name'];
						
						$subarray[$type_label]['pages']['child'][strtolower($customname)]['child']="";
						$subarray[$type_label]['pages']['child'][strtolower($customname)]['link']=$ppcurl;
						$subarray[$type_label]['pages']['child'][strtolower($customname)]['label']=$this->get_label(ucwords(strtolower($customname)));
						$subarray[$type_label]['pages']['child'][strtolower($customname)]['class']="";
						$subarray[$type_label]['pages']['child'][strtolower($customname)]['icon']="";	
					}				

					
					$subarray[$type_label]['pages']['link']="";
					$subarray[$type_label]['pages']['label']=$this->get_label('pages');
					
					if($page == 25)
					$selection_class=" active ";
					else
					$selection_class="";
					
					$subarray[$type_label]['pages']['class']=$selection_class;
									
					$subarray[$type_label]['pages']['icon']='fa-folder-open';						

				}			


				if($logedin ==1)
				{
					$subarray[$type_label]['support']['child']="";
					$subarray[$type_label]['support']['link']=$this->make_base_url("user/support");
					$subarray[$type_label]['support']['label']=$this->get_label('support');
					
					if($cpanel =="s")
					$selection_class=" active ";
					else
					$selection_class="";
					
					$subarray[$type_label]['support']['class']=$selection_class;
					
					$subarray[$type_label]['support']['icon']='fa-life-ring';					
				}
				else
				{
					if($theme_type == 0)
					{				
						$contactseo=$this->get_seo_name('index/contact_us');
						
						if($contactseo !='')
						$contacturl=BASE.$contactseo;
						else
						$contacturl=$this->make_url("index/contact_us");	
					}			
					else
					$contacturl = BASE.'#contact';							

					
					$subarray[$type_label]['contact']['child']="";
					$subarray[$type_label]['contact']['link']=$contacturl;
					$subarray[$type_label]['contact']['label']=$this->get_label('contact us');
					
					if($page == 19)
					$selection_class=" active ";
					else
					$selection_class="";
					
					$subarray[$type_label]['contact']['class']=$selection_class;
									
					$subarray[$type_label]['contact']['icon']='fa-phone';						
				}

				
				
				
				
			
			$GLOBALS['menu']=$subarray;
			

			
			$username="";
			if(isset($_POST['submit_login']) && $logedin ==0)
			{
				$username=$this->read_post_param('username1');
				$password=$this->read_post_param('password1');
			
			
				if($username =="" || $password =="")
				$this->set_notice('mandatory');
				else
				{
			
					$password1=md5($password);
			
					$count=$db->read_single_column("select count(id) from ".TABLE_PREFIX."users where username=? and password=? and (adv_status=1 or pub_status=1)",array($username,$password1));
					if($count >0)
					{
						$res1=$db->execute_query("select * from ".TABLE_PREFIX."users where username=? and password=?",array($username,$password1));
						$value1=$res1->fetch_assoc();
						$userid=$value1['id'];
						$orig_password=$value1['password'];
						$adv_status=$value1['adv_status'];
						$pub_status=$value1['pub_status'];
						$localeid=$value1['locale'];
			
						
						if($localeid >0)
						{
							$localename=$db->read_single_column("select name from ".TABLE_PREFIX."locale where id=?",array($localeid));
							
							if($localename !='')
							$this->set_locale($localename);
						}
						
			
						setcookie(COOKIE_USERNAME,$username,0,$this->get_base_path(),$this->get_base_domain());
						setcookie(COOKIE_PASSWORD,$password1,0,$this->get_base_path(),$this->get_base_domain());
						setcookie(COOKIE_LOGINID,$userid,0,$this->get_base_path(),$this->get_base_domain());
			
			
			
						if($adv_status==1 && $pub_status!=1)
						setcookie(COOKIE_ADMARKETTYPE,1,0,$this->get_base_path(),$this->get_base_domain());
						else if($adv_status!=1 && $pub_status==1)
						setcookie(COOKIE_ADMARKETTYPE,2,0,$this->get_base_path(),$this->get_base_domain());
						else if($adv_status==1 && $pub_status==1)
						setcookie(COOKIE_ADMARKETTYPE,3,0,$this->get_base_path(),$this->get_base_domain());
			
						
			
						if($pub_status ==1)
						{
							$day_begin1 =date("Y",time());
							$day_begin1.=date("m",time());
							$day_begin1.=date("d",time());
							$day_begin1.=date("H",time());
							
							
							
							$ip=UtilityHelper::get_user_ip();
							$result1=$db->execute_query("update ".TABLE_PREFIX."users set pub_logintime=?,pub_loginip=? where id=?",array($day_begin1,$ip,$userid));
						}
			
			
			
						if(($adv_status ==1 && $pub_status !=1) || ($adv_status ==1 && $pub_status ==1))
						header("Location: ".$this->make_url("user/advertiser_home"));
						else if($adv_status !=1 && $pub_status ==1)
						header("Location: ".$this->make_url("user/publisher_home"));
			
						exit;
					}
					else
					{
						$count_second=$db->read_single_column("select count(id) from ".TABLE_PREFIX."users where username=? and password=?",array($username,$password1));
			
						if($count_second==0)
						$this->set_notice('invalid username or password');
						else
						$this->set_notice('your account is inactive');
					}
				}
				$this->set_variable("username1",$username);
			}
			else
			{
				if(DEMO_MODE)
				{
					$this->set_variable('username1','demo');
					$this->set_variable('password1','demo');
				}
			}			
				
			
		
			
			
			$this->set_variable("cpanel",$cpanel);			
			$this->set_variable("page",$page);
			$this->set_variable("sub_page",$sub_page);

			

			if($logedin ==1)
			{
				$pubstatus			= $querydata['pub_status'];
				$login_ip			= $querydata['pub_loginip'];
				$login_time			= $querydata['pub_logintime'];
			

			
				$day_begin1 =date("Y",time());
				$day_begin1.=date("m",time());
				$day_begin1.=date("d",time());
				$day_begin1.=date("H",time());
			
			
				$clk_qry_res=$db->execute_query("select profit from ".TABLE_PREFIX."dailyclicks where pid=? AND profit >0 AND time >=?",array($uid,$day_begin1));
				
				$pfsum=0;
				while($click_row=$clk_qry_res->fetch_assoc())
				{
					$pfsum=$pfsum+$click_row['profit'];
				}
				
				
				
				$this->set_variable('pfsum',$pfsum);
			}
				

			if(isset($_COOKIE['my_locale']))
			$localname=$_COOKIE['my_locale'];
			else
			$localname=DEFAULT_LOCALE;
			
			
			$direction=$db->read_single_column("SELECT direction FROM ".TABLE_PREFIX."locale WHERE name=?",array($localname));
			$direction=intval($direction);
			
			
			
			$this->set_variable('localeid',$localeid);
			$this->set_variable('direction',$direction);
			

			
			$adm_content="";
			if(isset($_COOKIE['adm_content']))
			$adm_content=$_COOKIE['adm_content'];
			
			
			if($adm_content !="")
			$adm_content_array=explode('-',$adm_content);
			else
			$adm_content_array=array();
			
			
			$notificationcount=0;
			$notificationcount1=0;
			$valuestring="";
			$typestring="";

			
			if($logedin ==0)
			{			
				$notification=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."notifications WHERE type=3 AND status=1 AND time >=?",array(time()));
				while($notificationdata=$notification->fetch_assoc())
				{
					$indexvalue=array_search($notificationdata['id'],$adm_content_array);
					
					if(!($indexvalue > -1))
					{
						$notificationcount=$notificationcount+1;
						
						if($valuestring !="")
						$valuestring.='-';
						
						$valuestring.=$notificationdata['id'];
					}
					
					$notificationcount1=$notificationcount1+1;
				}
			}
			else
			{
				$uid=$this->read_cookie_param(COOKIE_LOGINID);
				$usedetails=$db->execute_query("select * from ".TABLE_PREFIX."users where id=?",array($uid));
				$usedetailsrow=$usedetails->fetch_assoc();
				
				$advstatus=$usedetailsrow['adv_status'];
				$pubstatus=$usedetailsrow['pub_status'];
				
									
				if($cpanel =='a')
				$typestring=" AND (type =0 OR type =2) ";
				else if($cpanel =='p')
				$typestring=" AND (type =1 OR type =2) ";
				else
				{
					if($advstatus ==1 && $pubstatus ==1)
					$typestring=" AND (type =0 OR type =1 OR type =2) ";
					else if($advstatus ==1)
					$typestring=" AND (type =0 OR type =2) ";
					else if($pubstatus ==1)
					$typestring=" AND (type =1 OR type =2) ";
				}				
				
				if($advstatus ==1 || $pubstatus ==1)
				{
					$notification=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."notifications WHERE status=1 AND time >=?".$typestring." ",array(time()));
					while($notificationdata=$notification->fetch_assoc())
					{
						$indexvalue=array_search($notificationdata['id'],$adm_content_array);
						
						if(!($indexvalue > -1))
						{
							$notificationcount=$notificationcount+1;
							
							if($valuestring !="")
							$valuestring.='-';
							
							$valuestring.=$notificationdata['id'];
						}
						
						$notificationcount1=$notificationcount1+1;
					}
				}
			}			

			
			
			$this->set_variable('notificationcount',$notificationcount);
			$this->set_variable('notificationcount1',$notificationcount1);
			$this->set_variable('valuestring',$valuestring);
			
			
			
			
			
		$mkey='';
		$mdesc='';
		$title='';
		

       if($page ==26 && ($sub_page ==24 || $sub_page ==25))
	   {
	       $site_description="";
	       
			if($sub_page ==24)
			$catid=intval($cpanel);
			else
			{
			    $site_row=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."sites WHERE id=?",array(intval($cpanel)));
			    
			    if($site_row->get_num_records() >0)
			    {
			        $site_data=$site_row->fetch_assoc();
			        
			        $catid=$site_data['catid'];
			        $site_description=$site_data['description'];
			        
			        if($sub_page ==25 && isset($site_data['keywords']))
			        $mkey=$site_data['keywords'];
			    }
			}
			
			
			if($catid >0)
			{
				$mdata=$db->execute_query("select * from ".TABLE_PREFIX."categories where id=?",array($catid));
				$mrow=$mdata->fetch_assoc();
				
				if($mkey =='')
				$mkey=$mrow['keyword'];
			

			    if($mdesc =='' && $sub_page ==25)
			    $mdesc=$site_description;
			
				if($mdesc =='')
				$mdesc=$mrow['description'];
				
				if($title =='')
				{
					if($sub_page ==24)
					$title=$mrow['name'].' - '.$this->get_label('cpd marketplace');
					else
					$title=$this->get_site_name_report(intval($cpanel)).' - '.$this->get_label('site details');
				}
				
				$this->set_title($title);				
			}	
		}
		
		
		if($page ==25 && $sub_page >0)
		{
			$mdata=$db->execute_query("select * from ".TABLE_PREFIX."custom_pages where id=?",array($sub_page));
			$mrow=$mdata->fetch_assoc();
			
			
			if($localeid > 0 && isset($mrow[$localeid.'_meta_keyword']))
			$mkey=$mrow[$localeid.'_meta_keyword'];
			

			if($mkey =='')
			$mkey=$mrow['meta_keyword'];
			
			if($localeid > 0 && isset($mrow[$localeid.'_meta_description']))
			$mdesc=$mrow[$localeid.'_meta_description'];
			

			if($mdesc =='')
			$mdesc=$mrow['meta_description'];
			
			if($localeid > 0 && isset($mrow[$localeid.'_title']))
			$title=$mrow[$localeid.'_title'];
			

			if($title =='')
			$title=$mrow['title'];
			
			$this->set_title($title);
		}
		
		
		
		
		if($sub_page ==0 || $mkey =='' || $mdesc =='' || $title =='')
		{

			$current_page_id=0;
			
			if($page ==16)
			$current_page_id=5;
			else if($page ==17)
			$current_page_id=1;
			else if($page ==18)
			$current_page_id=2;			
			else if($page ==19)
			$current_page_id=3;			
			else if($page ==20)
			$current_page_id=6;				
			else if($page ==21)
			$current_page_id=7;	
			else if($page ==22)
			$current_page_id=8;	
			else if($page ==23)
			$current_page_id=9;	




			
			$mdata=$db->execute_query("select * from ".TABLE_PREFIX."meta where pageid=?",array($current_page_id));
			$mrow=$mdata->fetch_assoc();
			
			if($mkey =='')
			{
				if($localeid > 0 && isset($mrow[$localeid.'_keyword']))
				$mkey=$mrow[$localeid.'_keyword'];
				

				if($mkey =='')
				$mkey=$mrow['keyword'];
			}
			
			if($mdesc =='')
			{
				if($localeid > 0 && isset($mrow[$localeid.'_description']))
				$mdesc=$mrow[$localeid.'_description'];
				
				if($mdesc =='')
				$mdesc=$mrow['description'];
			}
			
			if($title =='' && $this->get_title() =='')
			{
				if($localeid > 0 && isset($mrow[$localeid.'_title']))
				$title=$mrow[$localeid.'_title'];
				
				if($title =='')
				$title=$mrow['title'];
			
				$this->set_title($title);
			}
		}

		
		if($mkey =='' || $mdesc =='' || $title =='')
		{
			$mdata=$db->execute_query("select * from ".TABLE_PREFIX."meta where id=1");
			$mrow=$mdata->fetch_assoc();
			
			if($mkey =='')
			{
				if($localeid > 0 && isset($mrow[$localeid.'_keyword']))
				$mkey=$mrow[$localeid.'_keyword'];
				

				if($mkey =='')
				$mkey=$mrow['keyword'];
			}
			
			if($mdesc =='')
			{
				if($localeid > 0 && isset($mrow[$localeid.'_description']))
				$mdesc=$mrow[$localeid.'_description'];
				
				if($mdesc =='')
				$mdesc=$mrow['description'];
			}
			
			if($title =='' && $this->get_title() =='')
			{
				if($localeid > 0 && isset($mrow[$localeid.'_title']))
				$title=$mrow[$localeid.'_title'];
				

				if($title =='')
				$title=$mrow['title'];
			
				$this->set_title($title);
			}
		}		
				
		
	
		
		

				
		$this->set_variable("mkey",$mkey);
		$this->set_variable("mdesc",$mdesc);
		
	
		
		if(DEMO_MODE)
		$this->xyz_admarket_theme_include("theme.php","");
		
			
	}
	function footer_action()
	{
		$page = intval($this->read_page_param(1));
		
		$logedin=0;
			
		if(LoginHelper::validate_user_login())
		$logedin=1;
				
		$this->set_variable('logedin',$logedin);
		$this->set_variable('page',$page);
	}
	
	function change_language_action()
	{
		header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . date("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");


		$db= DAL::get_instance();
		$this->disable_notice_area();
		$language=$this->read_page_param(1);
	
		$id=$db->read_single_column("SELECT id FROM ".TABLE_PREFIX."locale WHERE name=?",array($language));
	
		if($id >0)
		$this->set_locale($language);
		if(LoginHelper::validate_user_login())
		{
			$user=$this->read_cookie_param(COOKIE_LOGINID);
			$db->execute_query("update ".TABLE_PREFIX."users set locale=? where id=? ",array($id,$user));
		}
		exit;
	}	
	
	
	
};
?>
