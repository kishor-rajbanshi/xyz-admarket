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
			$db = DAL::get_instance();

			$page      = intval($this->read_page_param(1));
			$sub_page  = intval($this->read_page_param(2));
			$cpanel    = $this->read_page_param(3);
			$pageTitle = $this->read_page_param(4);

			$common_page = 0;

			if($page == 16 || $page == 17 || $page == 18 || $page == 19 || $page == 20 || $page == 21 || $page == 22 || $page == 23 || $page == 24 || $page == 25 || $page == 28 || $page == 29 || $page == 30)
			$common_page = 1;

			$active_theme=$this->read_cookie_param('active_theme');

			if($active_theme == "")
			$active_theme=Configuration::get_instance()->read('active_theme');

			$logedin=0;

			if(LoginHelper::validate_user_login())
			$logedin=1;


			//cpd/affiliate without login
			$common_page_temp = 0;
			if($common_page == 0 && $logedin == 0 && ($page == 26 || $page == 27))
			{
					$common_page      = 1;
					$common_page_temp = 1;
			}

			$this->set_variable('common_page_temp',$common_page_temp);
			$this->set_variable('logedin',$logedin);
			$this->set_variable('active_theme',$active_theme);
			$this->set_variable('common_page',$common_page);

			$customres=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."custom_pages WHERE status=1 ORDER BY priority ASC");
			$this->set_result('customres',$customres);

			$customrescount=$customres->get_num_records();


			$category_enabled=$this->get_addon_status('category-targeting_enabled');
			$sponsored_enabled=$this->get_addon_status('sponsored_enabled');
			$affiliate_enabled=$this->get_addon_status('affiliate-ads_enabled');
			$retargeting_enabled=$this->get_addon_status('retargeting_enabled');
			$referral_enabled=$this->get_addon_status('referral_enabled');


			$adv_ref_enabled = 0;
			$pub_ref_enabled = 0;

			if($referral_enabled == 1)
			{
					$adv_ref_enabled = Configuration::get_instance()->read('advertiser_referral_enabled');
					$pub_ref_enabled = Configuration::get_instance()->read('publisher_referral_enabled');
			}

			if($adv_ref_enabled == 0 && $pub_ref_enabled == 0)
			$referral_enabled = 0;

			$this->set_variable('referral_enabled',$referral_enabled);

			if(isset($_COOKIE['my_locale']))
			$localname=$_COOKIE['my_locale'];
			else
			$localname=DEFAULT_LOCALE;

			$advertiser_dashboard_status = Configuration::get_instance()->read('advertiser_dashboard_status');
			$publisher_dashboard_status  = Configuration::get_instance()->read('publisher_dashboard_status');



			if($localname != "")
			{
				$localnameFull 	= strtolower(str_replace("_","-",$localname));
				$localnameArray = explode("-",$localnameFull);
				$localnameFirst	= "";

				if(isset($localnameArray[0]))
				$localnameFirst	= $localnameArray[0];

				$this->set_variable("localnameFull",$localnameFull);
				$this->set_variable("localnameFirst",$localnameFirst);
			}


			if(Configuration::get_instance()->read('language_enabled') ==1)
			{
					$languagestring=UtilityHelper::get_locale_list($localname,$logedin);
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

			if($logedin ==1)
			{
				$type_label="login";

				$userName     = $this->read_cookie_param(COOKIE_USERNAME);
				$userPassword = $this->read_cookie_param(COOKIE_PASSWORD);
				$userID       = $this->read_cookie_param(COOKIE_LOGINID);
				$userPanel    = $this->read_cookie_param(COOKIE_USERPANEL);

				$subadmin_enabled = $this->get_addon_status('subadmin_enabled');


				$userResult = $db->execute_query("select * from ".TABLE_PREFIX."users where username = ? and password = ? and id = ?",array($userName,$userPassword,$userID));
				$this->set_result("userResult",$userResult);

				$querydata    = $userResult->fetch_assoc();

				$adv_status   = $querydata['adv_status'];
				$pub_status   = $querydata['pub_status'];


				if($cpanel == "a" && $userPanel != "advertiser" && $adv_status == 1)
				{
						setcookie(COOKIE_USERPANEL,"advertiser",0,$this->get_base_path(),$this->get_base_domain());
						$userPanel = "advertiser";
				}
				else if($cpanel == "p" && $userPanel != "publisher" && $pub_status == 1)
				{
						setcookie(COOKIE_USERPANEL,"publisher",0,$this->get_base_path(),$this->get_base_domain());
						$userPanel = "publisher";
				}
				else if($userPanel != "advertiser" && $userPanel != "publisher")
				{
						if($adv_status == 1)
						{
								setcookie(COOKIE_USERPANEL,"advertiser",0,$this->get_base_path(),$this->get_base_domain());
								$userPanel = "advertiser";
						}
						else if($pub_status == 1)
						{
								setcookie(COOKIE_USERPANEL,"publisher",0,$this->get_base_path(),$this->get_base_domain());
								$userPanel = "publisher";
						}
				}

				$this->set_variable("userPanel", $userPanel);


				$adv_managerid	= 0;
				$pub_managerid	= 0;

				if($subadmin_enabled == 1)
				{
						$adv_managerid = $querydata['adv_managerid'];
						$pub_managerid = $querydata['pub_managerid'];
				}


				if($adv_managerid > 0 && $subadmin_enabled == 1)
				{
					$adv_manager_row = $db->execute_query("SELECT * FROM ".TABLE_PREFIX."admin WHERE type = 2 AND status = 1 AND id = ?",array($adv_managerid));

					if($adv_manager_row->get_num_records() > 0)
					{
						$adv_manager_data = $adv_manager_row->fetch_assoc();

						$adv_manager_name    = $adv_manager_data['username'];
						$adv_manager_email   = $adv_manager_data['email'];
						$adv_manager_phone   = $adv_manager_data['phone'];
						$adv_manager_skypeid = $adv_manager_data['skypeid'];

						$this->set_variable("adv_manager_name",$adv_manager_name);
						$this->set_variable("adv_manager_email",$adv_manager_email);
						$this->set_variable("adv_manager_phone",$adv_manager_phone);
						$this->set_variable("adv_manager_skypeid",$adv_manager_skypeid);
					}
					else
					$adv_managerid = 0;
				}


				if($pub_managerid > 0 && $subadmin_enabled == 1)
				{
					$pub_manager_row = $db->execute_query("SELECT * FROM ".TABLE_PREFIX."admin WHERE type = 3 AND status = 1 AND id = ?",array($pub_managerid));

					if($pub_manager_row->get_num_records() > 0)
					{
						$pub_manager_data = $pub_manager_row->fetch_assoc();

						$pub_manager_name    = $pub_manager_data['username'];
						$pub_manager_email   = $pub_manager_data['email'];
						$pub_manager_phone   = $pub_manager_data['phone'];
						$pub_manager_skypeid = $pub_manager_data['skypeid'];

						$this->set_variable("pub_manager_name",$pub_manager_name);
						$this->set_variable("pub_manager_email",$pub_manager_email);
						$this->set_variable("pub_manager_phone",$pub_manager_phone);
						$this->set_variable("pub_manager_skypeid",$pub_manager_skypeid);
					}
					else
					$pub_managerid = 0;
				}


				$this->set_variable("adv_managerid",$adv_managerid);
				$this->set_variable("pub_managerid",$pub_managerid);


				if($adv_status == 1 && $advertiser_dashboard_status == 1)
				{
						$subarray[$type_label]['advertiser']['child']="";
						$subarray[$type_label]['advertiser']['link']=$this->make_base_url("dashboard/advertiser_home");
						$subarray[$type_label]['advertiser']['label']=$this->get_label('advertiser');

						if($cpanel =="a")
						$selection_class=" active ";
						else
						$selection_class="";

						$subarray[$type_label]['advertiser']['class']=$selection_class;

						$subarray[$type_label]['advertiser']['icon']='fa-user';
				}

				if($pub_status == 1 && $publisher_dashboard_status == 1)
				{
						$subarray[$type_label]['publisher']['child']="";
						$subarray[$type_label]['publisher']['link']=$this->make_base_url("dashboard/publisher_home");
						$subarray[$type_label]['publisher']['label']=$this->get_label('publisher');

						if($cpanel =="p")
						$selection_class=" active ";
						else
						$selection_class="";

						$subarray[$type_label]['publisher']['class']=$selection_class;

						$subarray[$type_label]['publisher']['icon']='fa-bullhorn';
				}


				if($advertiser_dashboard_status == 1 && $userPanel == "advertiser" && $adv_status == 1)
				{
					$subarray[$type_label]['advertiser_menu']['home']['child']=array();
					$subarray[$type_label]['advertiser_menu']['home']['link']=$this->make_base_url("dashboard/advertiser_home");
					$subarray[$type_label]['advertiser_menu']['home']['label']=$this->get_label('home');

					if($page ==1)
					$selection_class=" active ";
					else
					$selection_class="";

					$subarray[$type_label]['advertiser_menu']['home']['class']=$selection_class;

					$subarray[$type_label]['advertiser_menu']['home']['icon']='fa-home';
					$subarray[$type_label]['advertiser_menu']['home']['id']='1';

					
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



                    			if($sponsored_enabled == 1)
                    			{
						$subarray[$type_label]['advertiser_menu']['ads']['child']['cpd targeting']['child']="";
						$subarray[$type_label]['advertiser_menu']['ads']['child']['cpd targeting']['link']=$this->make_base_url("dispatch/sponsored/26");

						$subarray[$type_label]['advertiser_menu']['ads']['child']['cpd targeting']['label']=$this->get_label('cpd ad targeting');

						if($page == 2 && $sub_page == 5)
						$selection_class=" active ";
						else
						$selection_class="";

						$subarray[$type_label]['advertiser_menu']['ads']['child']['cpd targeting']['class']=$selection_class;
						$subarray[$type_label]['advertiser_menu']['ads']['child']['cpd targeting']['icon']="fa-calendar";
						$subarray[$type_label]['advertiser_menu']['ads']['child']['cpd targeting']['id']='a2a5';
					}



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



					$subarray[$type_label]['advertiser_menu']['payments']['child']=array();
					$subarray[$type_label]['advertiser_menu']['payments']['link']=$this->make_base_url("advertiser/payment_history");
					$subarray[$type_label]['advertiser_menu']['payments']['label']=$this->get_label('payments');

					if($page ==3)
					$selection_class=" active ";
					else
					$selection_class="";

					$subarray[$type_label]['advertiser_menu']['payments']['class']=$selection_class;

					$subarray[$type_label]['advertiser_menu']['payments']['icon']='fa-money';
					$subarray[$type_label]['advertiser_menu']['payments']['id']='3';





					$subarray[$type_label]['advertiser_menu']['all statistics']['child']=array();
					$subarray[$type_label]['advertiser_menu']['all statistics']['link']=$this->make_base_url("advertiser/statistics");
					$subarray[$type_label]['advertiser_menu']['all statistics']['label']=$this->get_label('advertiser reports');

					if($page ==4)
					$selection_class=" active ";
					else
					$selection_class="";

					$subarray[$type_label]['advertiser_menu']['all statistics']['class']=$selection_class;

					$subarray[$type_label]['advertiser_menu']['all statistics']['icon']='fa-bar-chart';
					$subarray[$type_label]['advertiser_menu']['all statistics']['id']='4';


				}


				if($publisher_dashboard_status == 1 && $userPanel == "publisher" && $pub_status == 1)
				{
					if($category_enabled ==1)
					{
						$url1=$this->make_base_url("dispatch/category_targeting/5");
						$url2=$this->make_base_url("dispatch/category_targeting/6");
						$url3=$this->make_base_url("dispatch/category_targeting/11");
					}


					$subarray[$type_label]['publisher_menu']['home']['child']=array();
					$subarray[$type_label]['publisher_menu']['home']['link']=$this->make_base_url("dashboard/publisher_home");
					$subarray[$type_label]['publisher_menu']['home']['label']=$this->get_label('home');


					if($page ==5)
					$selection_class=" active ";
					else
					$selection_class="";

					$subarray[$type_label]['publisher_menu']['home']['class']=$selection_class;

					$subarray[$type_label]['publisher_menu']['home']['icon']='fa-home';
					$subarray[$type_label]['publisher_menu']['home']['id']='5';


			
					if($category_enabled == 1)
					{
						$sitesLabel = $this->get_label('websites & adcodes');
						$sitesIcon  = "fa-sitemap";
					}
					else 
					{
						$sitesLabel = $this->get_label('adcodes');
						$sitesIcon  = "fa-file-code-o";
					}
					

					if($category_enabled == 1)
					{
						$subarray[$type_label]['publisher_menu']['adunits']['child']['manage sites']['child']="";
						$subarray[$type_label]['publisher_menu']['adunits']['child']['manage sites']['link']=$url2;
						$subarray[$type_label]['publisher_menu']['adunits']['child']['manage sites']['label']=$this->get_label('manage sites');

						if($page == 6 && $sub_page == 1)
						$selection_class=" active ";
						else
						$selection_class="";

						$subarray[$type_label]['publisher_menu']['adunits']['child']['manage sites']['class']=$selection_class;
						$subarray[$type_label]['publisher_menu']['adunits']['child']['manage sites']['icon']="fa-bullhorn";
						$subarray[$type_label]['publisher_menu']['adunits']['child']['manage sites']['id']='a13a2';
					}

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


					if($category_enabled == 1)
					{
						$subarray[$type_label]['publisher_menu']['adunits']['child']['site statistics']['child']="";
						$subarray[$type_label]['publisher_menu']['adunits']['child']['site statistics']['link']=$url3;
						$subarray[$type_label]['publisher_menu']['adunits']['child']['site statistics']['label']=$this->get_label('site reports');

						if($page == 6 && $sub_page == 3)
						$selection_class=" active ";
						else
						$selection_class="";

						$subarray[$type_label]['publisher_menu']['adunits']['child']['site statistics']['class']=$selection_class;
						$subarray[$type_label]['publisher_menu']['adunits']['child']['site statistics']['icon']="fa-bar-chart";
						$subarray[$type_label]['publisher_menu']['adunits']['child']['site statistics']['id']='a13a3';
					}
               

				
					$subarray[$type_label]['publisher_menu']['adunits']['child']['adunit statistics']['child']="";
					$subarray[$type_label]['publisher_menu']['adunits']['child']['adunit statistics']['link']=$this->make_base_url("adunit/statistics");
					$subarray[$type_label]['publisher_menu']['adunits']['child']['adunit statistics']['label']=$this->get_label('adcode reports');

					if($page == 6 && $sub_page == 5)
					$selection_class=" active ";
					else
					$selection_class="";

					$subarray[$type_label]['publisher_menu']['adunits']['child']['adunit statistics']['class']=$selection_class;
					$subarray[$type_label]['publisher_menu']['adunits']['child']['adunit statistics']['icon']="fa-bar-chart";
					$subarray[$type_label]['publisher_menu']['adunits']['child']['adunit statistics']['id']='a6a5';


					if($sponsored_enabled == 1)
					{
						$subarray[$type_label]['publisher_menu']['adunits']['child']['cpd adcode targeting']['child']="";
						$subarray[$type_label]['publisher_menu']['adunits']['child']['cpd adcode targeting']['link']=$this->make_base_url("dispatch/sponsored/34");

						$subarray[$type_label]['publisher_menu']['adunits']['child']['cpd adcode targeting']['label']=$this->get_label('cpd adcode targeting');

						if($page == 6 && $sub_page == 6)
						$selection_class=" active ";
						else
						$selection_class="";

						$subarray[$type_label]['publisher_menu']['adunits']['child']['cpd adcode targeting']['class']=$selection_class;
						$subarray[$type_label]['publisher_menu']['adunits']['child']['cpd adcode targeting']['icon']="fa-calendar";
						$subarray[$type_label]['publisher_menu']['adunits']['child']['cpd adcode targeting']['id']='a6a6';
					}


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


					$subarray[$type_label]['publisher_menu']['adunits']['link']="";
					$subarray[$type_label]['publisher_menu']['adunits']['label'] = $sitesLabel;

					if($page == 6)
					$selection_class=" active ";
					else
					$selection_class="";

					$subarray[$type_label]['publisher_menu']['adunits']['class']=$selection_class;

					$subarray[$type_label]['publisher_menu']['adunits']['icon']=$sitesIcon;
					$subarray[$type_label]['publisher_menu']['adunits']['id']='6';






					$subarray[$type_label]['publisher_menu']['all statistics']['child']=array();
					$subarray[$type_label]['publisher_menu']['all statistics']['link']=$this->make_base_url("publisher/statistics");
					$subarray[$type_label]['publisher_menu']['all statistics']['label']=$this->get_label('publisher reports');

					if($page ==8)
					$selection_class=" active ";
					else
					$selection_class="";

					$subarray[$type_label]['publisher_menu']['all statistics']['class']=$selection_class;

					$subarray[$type_label]['publisher_menu']['all statistics']['icon']='fa-bar-chart';
					$subarray[$type_label]['publisher_menu']['all statistics']['id']='8';

				}


				if($advertiser_dashboard_status == 1 || $publisher_dashboard_status == 1)
				{
					if($sponsored_enabled == 1 && $affiliate_enabled == 1)
					{
							$subarray[$type_label]['common_menu']['marketplace']['child']['cpd marketplace']['child'] = "";
							$subarray[$type_label]['common_menu']['marketplace']['child']['cpd marketplace']['link']  = $cpdurl;
							$subarray[$type_label]['common_menu']['marketplace']['child']['cpd marketplace']['label'] = $this->get_label('cpd marketplace');

							if($page == 26)
							$selection_class=" active ";
							else
							$selection_class="";

							$subarray[$type_label]['common_menu']['marketplace']['child']['cpd marketplace']['class'] = $selection_class;
							$subarray[$type_label]['common_menu']['marketplace']['child']['cpd marketplace']['icon']  = "fa-plus-square";
							$subarray[$type_label]['common_menu']['marketplace']['child']['cpd marketplace']['id']    = "a26a1";


							$subarray[$type_label]['common_menu']['marketplace']['child']['affiliate marketplace']['child'] = "";
							$subarray[$type_label]['common_menu']['marketplace']['child']['affiliate marketplace']['link']  = $affiliateurl;
							$subarray[$type_label]['common_menu']['marketplace']['child']['affiliate marketplace']['label'] = $this->get_label('affiliate marketplace');

							if($page == 27)
							$selection_class=" active ";
							else
							$selection_class="";

							$subarray[$type_label]['common_menu']['marketplace']['child']['affiliate marketplace']['class'] = $selection_class;
							$subarray[$type_label]['common_menu']['marketplace']['child']['affiliate marketplace']['icon']  = "fa-plus-square";
							$subarray[$type_label]['common_menu']['marketplace']['child']['affiliate marketplace']['id']    = "a27a1";



							$subarray[$type_label]['common_menu']['marketplace']['link']="";
							$subarray[$type_label]['common_menu']['marketplace']['label']=$this->get_label('marketplace');

							if($page == 26 || $page == 27)
							$selection_class=" active ";
							else
							$selection_class="";

							$subarray[$type_label]['common_menu']['marketplace']['class']=$selection_class;

							$subarray[$type_label]['common_menu']['marketplace']['icon']='fa-gift';
							$subarray[$type_label]['common_menu']['marketplace']['id']='26';
					}
					else
					{
						if($sponsored_enabled ==1)
						{
								$subarray[$type_label]['common_menu']['marketplace']['child']=array();
								$subarray[$type_label]['common_menu']['marketplace']['link']=$cpdurl;
								$subarray[$type_label]['common_menu']['marketplace']['label']=$this->get_label('cpd marketplace');

								if($page == 26)
								$selection_class=" active ";
								else
								$selection_class="";

								$subarray[$type_label]['common_menu']['marketplace']['class']=$selection_class;

								$subarray[$type_label]['common_menu']['marketplace']['icon']='fa-gift';
								$subarray[$type_label]['common_menu']['marketplace']['id']='26';
						}

						if($affiliate_enabled ==1)
						{
								$subarray[$type_label]['common_menu']['marketplace']['child']=array();
								$subarray[$type_label]['common_menu']['marketplace']['link']=$affiliateurl;
								$subarray[$type_label]['common_menu']['marketplace']['label']=$this->get_label('affiliate marketplace');

								if($page == 27)
								$selection_class=" active ";
								else
								$selection_class="";

								$subarray[$type_label]['common_menu']['marketplace']['class']=$selection_class;

								$subarray[$type_label]['common_menu']['marketplace']['icon']='fa-gift';
								$subarray[$type_label]['common_menu']['marketplace']['id']='27';
						}
					}
				}



				if(($advertiser_dashboard_status == 1 || $publisher_dashboard_status == 1) && (($userPanel == "publisher" && $pub_status == 1) || ($userPanel == "advertiser" && $adv_status == 1 && $referral_enabled ==1)))
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


				if($referral_enabled ==1 && (($adv_status==1 && $advertiser_dashboard_status == 1) || ($pub_status==1 && $publisher_dashboard_status == 1)))
				{
						$subarray[$type_label]['referral_menu']['referral_data']['child']['get referral code']['child']="";
						$subarray[$type_label]['referral_menu']['referral_data']['child']['get referral code']['link']=$this->make_base_url("dispatch/referral/1");
						$subarray[$type_label]['referral_menu']['referral_data']['child']['get referral code']['label']=$this->get_label('referral code menu');

						if($page == 14 && $sub_page == 1)
						$selection_class=" active ";
						else
						$selection_class="";

						$subarray[$type_label]['referral_menu']['referral_data']['child']['get referral code']['class']=$selection_class;
						$subarray[$type_label]['referral_menu']['referral_data']['child']['get referral code']['icon']="fa-user-plus";
						$subarray[$type_label]['referral_menu']['referral_data']['child']['get referral code']['id']='a14a1';


						$subarray[$type_label]['referral_menu']['referral_data']['child']['referral reports']['child']="";
						$subarray[$type_label]['referral_menu']['referral_data']['child']['referral reports']['link']=$this->make_base_url("dispatch/referral/2");
						$subarray[$type_label]['referral_menu']['referral_data']['child']['referral reports']['label']=$this->get_label('referral reports');

						if($page == 14 && $sub_page == 2)
						$selection_class=" active ";
						else
						$selection_class="";

						$subarray[$type_label]['referral_menu']['referral_data']['child']['referral reports']['class']=$selection_class;
						$subarray[$type_label]['referral_menu']['referral_data']['child']['referral reports']['icon']="fa-edit";
						$subarray[$type_label]['referral_menu']['referral_data']['child']['referral reports']['id']='a15a1';



						$subarray[$type_label]['referral_menu']['referral_data']['link']="";
						$subarray[$type_label]['referral_menu']['referral_data']['label']=$this->get_label('referral system');

						if($page == 14)
						$selection_class=" active ";
						else
						$selection_class="";

						$subarray[$type_label]['referral_menu']['referral_data']['class']=$selection_class;

						$subarray[$type_label]['referral_menu']['referral_data']['icon']='fa-user-plus';
						$subarray[$type_label]['referral_menu']['referral_data']['id']='14';
				}

						
								if(($adv_status == 1 && $advertiser_dashboard_status == 1) || ($pub_status == 1 && $publisher_dashboard_status == 1))
								{
										$subarray[$type_label]['common_menu']['account']['child']['my account']['child']="";
										$subarray[$type_label]['common_menu']['account']['child']['my account']['link']=$this->make_base_url("user/account");
										$subarray[$type_label]['common_menu']['account']['child']['my account']['label']=$this->get_label('my account');

										if($page == 9 && $sub_page == 1)
										$selection_class=" active ";
										else
										$selection_class="";

										$subarray[$type_label]['common_menu']['account']['child']['my account']['class']=$selection_class;

										$subarray[$type_label]['common_menu']['account']['child']['my account']['icon']='fa-envelope';
										$subarray[$type_label]['common_menu']['account']['child']['my account']['id']='a9a1';


										$subarray[$type_label]['common_menu']['account']['child']['edit profile']['child']="";
										$subarray[$type_label]['common_menu']['account']['child']['edit profile']['link']=$this->make_base_url("user/edit_profile");
										$subarray[$type_label]['common_menu']['account']['child']['edit profile']['label']=$this->get_label('edit profile');

										if($page == 9 && $sub_page == 2)
										$selection_class=" active ";
										else
										$selection_class="";

										$subarray[$type_label]['common_menu']['account']['child']['edit profile']['class']=$selection_class;

										$subarray[$type_label]['common_menu']['account']['child']['edit profile']['icon']='fa-edit';
										$subarray[$type_label]['common_menu']['account']['child']['edit profile']['id']='a9a2';



										$subarray[$type_label]['common_menu']['account']['child']['change password']['child']="";
										$subarray[$type_label]['common_menu']['account']['child']['change password']['link']=$this->make_base_url("user/change_password");
										$subarray[$type_label]['common_menu']['account']['child']['change password']['label']=$this->get_label('change password');

										if($page == 9 && $sub_page == 3)
										$selection_class=" active ";
										else
										$selection_class="";

										$subarray[$type_label]['common_menu']['account']['child']['change password']['class']=$selection_class;

										$subarray[$type_label]['common_menu']['account']['child']['change password']['icon']='fa-key';
										$subarray[$type_label]['common_menu']['account']['child']['change password']['id']='a9a3';


										$subarray[$type_label]['common_menu']['account']['child']['change email']['child']="";
										$subarray[$type_label]['common_menu']['account']['child']['change email']['link']=$this->make_base_url("user/change_email");
										$subarray[$type_label]['common_menu']['account']['child']['change email']['label']=$this->get_label('change email');

										if($page == 9 && $sub_page == 4)
										$selection_class=" active ";
										else
										$selection_class="";

										$subarray[$type_label]['common_menu']['account']['child']['change email']['class']=$selection_class;

										$subarray[$type_label]['common_menu']['account']['child']['change email']['icon']='fa-envelope';
										$subarray[$type_label]['common_menu']['account']['child']['change email']['id']='a9a4';




										$subarray[$type_label]['common_menu']['account']['link']="";
										$subarray[$type_label]['common_menu']['account']['label']=$this->get_label('my account');

										if($page ==9)
										$selection_class=" active ";
										else
										$selection_class="";

										$subarray[$type_label]['common_menu']['account']['class']=$selection_class;

										$subarray[$type_label]['common_menu']['account']['icon']='fa-user-plus';
										$subarray[$type_label]['common_menu']['account']['id']='9';


								}

			}
			else
			{
				$type_label="logout";


 				$advseo=$this->get_seo_name('index/advertiser');

				if($advseo !='')
				$advurl=BASE.$advseo;
				else
				$advurl=$this->make_url("index/advertiser");


				$pubseo=$this->get_seo_name('index/publisher');

				if($pubseo !='')
				$puburl=BASE.$pubseo;
				else
				$puburl=$this->make_url("index/publisher");


				$baseurl = BASE;

				$subarray[$type_label]['home']['child']="";
				$subarray[$type_label]['home']['link']=$baseurl;
				$subarray[$type_label]['home']['label']=$this->get_label('home');

				if($page == 16)
				$selection_class=" active ";
				else
				$selection_class="";

				$subarray[$type_label]['home']['class']=$selection_class;

				$subarray[$type_label]['home']['icon']='fa-home';

				if($advertiser_dashboard_status == 1)
				{
					$subarray[$type_label]['advertiser']['child']="";
					$subarray[$type_label]['advertiser']['link']=$advurl;
					$subarray[$type_label]['advertiser']['label']=$this->get_label('advertiser');

					if($page == 17)
					$selection_class=" active ";
					else
					$selection_class="";

					$subarray[$type_label]['advertiser']['class']=$selection_class;

					$subarray[$type_label]['advertiser']['icon']='fa-user';  
			    }


			    if($publisher_dashboard_status == 1)
			    {
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
			}



			if($common_page == 1 && ($advertiser_dashboard_status == 1 || $publisher_dashboard_status == 1))
			{
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
			}

				if($common_page == 1 && Configuration::get_instance()->read('display_custom_pages')==1 && $customrescount > 0)
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

				if($this->faq_link_availability() == 1)
				{
					$subarray[$type_label]['faq']['child']="";
					$subarray[$type_label]['faq']['link']=$faqurl;
					$subarray[$type_label]['faq']['label']=$this->get_label('faq');

					if($page == 30)
					$selection_class=" active ";
					else
					$selection_class="";

					$subarray[$type_label]['faq']['class']=$selection_class;

					$subarray[$type_label]['faq']['icon']='fa-question-circle';
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
					$contactseo=$this->get_seo_name('index/contact_us');

					if($contactseo !='')
					$contacturl=BASE.$contactseo;
					else
					$contacturl=$this->make_url("index/contact_us");



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


			$this->set_variable("cpanel",$cpanel);
			$this->set_variable("page",$page);
			$this->set_variable("sub_page",$sub_page);
			$this->set_variable("pageTitle",$pageTitle);










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


			if($logedin == 0)
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



				if($cpanel =='a')
				$typestring=" AND (type =0 OR type =2) ";
				else if($cpanel =='p')
				$typestring=" AND (type =1 OR type =2) ";
				else
				{
					if($adv_status ==1 && $pub_status ==1)
					$typestring=" AND (type =0 OR type =1 OR type =2) ";
					else if($adv_status ==1)
					$typestring=" AND (type =0 OR type =2) ";
					else if($pub_status ==1)
					$typestring=" AND (type =1 OR type =2) ";
				}

				if($adv_status ==1 || $pub_status ==1)
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

		$common_page = 0;

		if($page == 16 || $page == 17 || $page == 18 || $page == 19 || $page == 20 || $page == 21 || $page == 22 || $page == 23 || $page == 24 || $page == 25 || $page == 28 || $page == 29 || $page == 30)
		$common_page = 1;

		//cpd/affiliate without login
		$common_page_temp = 0;
		if($common_page == 0 && $logedin == 0 && ($page == 26 || $page == 27))
		{
				$common_page      = 1;
				$common_page_temp = 1;
		}

		$this->set_variable('common_page_temp',$common_page_temp);
		$this->set_variable('logedin',$logedin);
		$this->set_variable('common_page',$common_page);
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






	function header_assets_action()
	{
	    $page = intval($this->read_page_param(1));
	    $this->set_variable("page",$page);

	    $db= DAL::get_instance();


		if(isset($_COOKIE['my_locale']))
		$localname = $_COOKIE['my_locale'];
		else
		$localname = DEFAULT_LOCALE;


		$language_enabled=Configuration::get_instance()->read('language_enabled');


		if($language_enabled == 1)
		$localeid = intval($this->get_locale_id($localname));
		else
		$localeid = 0;


		$mkey   = '';
		$mdesc  = '';
		$title  = '';


			$current_page_id = 0;

			if($page > 0)
			$current_page_id = $page;


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



	    $direction=$db->read_single_column("SELECT direction FROM ".TABLE_PREFIX."locale WHERE name=?",array($localname));

	    $this->set_variable('direction',$direction);


		if($localname != "")
		{
			$localnameFull 	= strtolower(str_replace("_","-",$localname));
			$localnameArray = explode("-",$localnameFull);
			$localnameFirst	= "";

			if(isset($localnameArray[0]))
			$localnameFirst	= $localnameArray[0];

			$this->set_variable("localnameFull",$localnameFull);
			$this->set_variable("localnameFirst",$localnameFirst);
		}





	}

	function footer_assets_action()
	{

	}

	function header_iframe_action()
	{
			$page = intval($this->read_page_param(1));

			$db   = DAL::get_instance();

			if(isset($_COOKIE['my_locale']))
		  $localname = $_COOKIE['my_locale'];
			else
		  $localname = DEFAULT_LOCALE;

			$direction = $db->read_single_column("SELECT direction FROM ".TABLE_PREFIX."locale WHERE name = ?",array($localname));
			$direction = intval($direction);

			$this->set_variable("page",$page);
			$this->set_variable('direction',$direction);
	}

	function footer_iframe_action()
	{

	}

};
?>
