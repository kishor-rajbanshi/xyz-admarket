<?php
include_once COMMON_DIR_PATH.'helpers'.DS."login-helper.php";
include_once COMMON_DIR_PATH.'helpers'.DS."utility-helper.php";
include_once COMMON_DIR_PATH.'helpers'.DS."image-helper.php";

if(!class_exists('CategoryHelper'))
include_once ADDON_DIR_PATH."category-targeting".DS."common".DS."helpers".DS."category-helper.php";

class SiteController extends ApplicationController
{
	function before_execute()
	{
		parent::before_execute();


		if($this->get_action()=="add_admin_site" || $this->get_action()=="edit_admin_site" || $this->get_action()=="manage_admin_site" || $this->get_action()=="delete_logo_admin" || $this->get_action()=="delete_thumb_admin" || $this->get_action()=="delete_admin_site" || $this->get_action()=="activate_site" || $this->get_action()=="block_site" || $this->get_action()=="statistics_admin" || $this->get_action()=="statistics_publisher"  || $this->get_action()=="detail_statistics_admin" || $this->get_action()=="statistics_publisher_profile" || $this->get_action()=="featured" || $this->get_action()=="hot")
		{
			if(!(LoginHelper::validate_admin_login()))
			$this->flash($this->get_message('login failed'), $this->make_base_url('index/index',ADMIN_DIR),0);
		}


		if($this->get_action()=="add_site" || $this->get_action()=="edit_site" || $this->get_action()=="manage_site" || $this->get_action()=="delete_site" || $this->get_action()=="detail_statistics" || $this->get_action()=="statistics" || $this->get_action()=="delete_logo" || $this->get_action()=="delete_thumb" || $this->get_action()=="verification_file")
		{
			if(!(LoginHelper::validate_user_login()))
			$this->flash($this->get_message('login failed'), BASE,0);
		}

		if($this->get_addon_status('category-targeting_enabled') !=1)
		$this->flash($this->get_message('invalid'),BASE,0);
	}

	function verify_site_user_action()
	{
			$db       = DAL::get_instance();

			if(!(LoginHelper::validate_user_login()))
			{
					echo 0; //Invalid operation
					die;
			}

			$pid      = $this->read_cookie_param(COOKIE_LOGINID);
			$siteID   = intval($this->read_post_param("siteID"));

			$enable_website_ownership_verification = Configuration::get_instance()->read('enable_website_ownership_verification');

			if($enable_website_ownership_verification != 1)
			{
					echo 0; //Invalid operation
					die;
			}

			$siteData = $db->execute_query("SELECT id,protocol,url,verification_key FROM ".TABLE_PREFIX."sites WHERE id = ? AND pid = ?",array($siteID,$pid));

			if($siteData->get_num_records() == 0)
			{
					echo 0; //Invalid operation
					die;
			}

			$siteDataContent  = $siteData->fetch_assoc();
			$siteUrl          = $siteDataContent['protocol'].$siteDataContent['url'];
			$verificationKey  = $siteDataContent['verification_key'];
			$verificationFile = $siteUrl."/".$verificationKey.".html";

			if($verificationKey == "")
			{
					echo 0; //Invalid operation
					die;
			}

			$verificationKeyGet = "";

			$contentData = get_meta_tags($siteUrl);//Checks meta tag added into site root head

			if(isset($contentData['admverifysite']))
			$verificationKeyGet = $contentData['admverifysite'];

			if($verificationKeyGet == "")
			{
					$contentData = get_meta_tags($verificationFile);//Checks meta tag file uploaded into site root

					if(isset($contentData['admverifysite']))
					$verificationKeyGet = $contentData['admverifysite'];
			}

			if($verificationKeyGet != "" && $verificationKeyGet == $verificationKey)
			{
					$site_default_status = Configuration::get_instance()->read('site_default_status');

					$db->execute_query("UPDATE ".TABLE_PREFIX."sites SET status = ? WHERE id = ? AND pid = ?",array($site_default_status,$siteID,$pid));

					echo 2;
					die;
			}
			else
			{
					echo 1;
					die;
			}
	}

	function verification_file_action()
	{
			$db       = DAL::get_instance();

			$pid      = $this->read_cookie_param(COOKIE_LOGINID);

			$siteID   = intval($this->read_page_param(1));
			$category = intval($this->read_page_param(2));
			$status   = $this->read_page_param(3);
			$page     = $this->read_page_param(4);

			$enable_website_ownership_verification = Configuration::get_instance()->read('enable_website_ownership_verification');

			if($enable_website_ownership_verification != 1)
			{
					$this->flash($this->get_message('invalid operation'), $this->make_base_url("dispatch/category_targeting/6"),0);
					exit;
			}

			$verificationKey = $db->read_single_column("SELECT verification_key FROM ".TABLE_PREFIX."sites WHERE id = ? AND pid = ?",array($siteID,$pid));

			if($verificationKey == "")
			{
					$this->flash($this->get_message('invalid operation'), $this->make_base_url("dispatch/category_targeting/6"),0);
					exit;
			}

$fileContent = '<html>
<title>'.$this->get_label("website verification").'</title>
<head><meta name="admverifysite" content="'.$verificationKey.'" /></head>
<body></body>
</html>';

			if(!is_dir('../../'.DATA_DIR.'/siteVerification'))
			mkdir('../../'.DATA_DIR.'/siteVerification',0777);

			$fileObject = fopen('../../'.DATA_DIR.'/siteVerification/'.$verificationKey.'.html','w+');
			fwrite($fileObject,$fileContent);
			fclose($fileObject);

			$filePath = '../../'.DATA_DIR.'/siteVerification/'.$verificationKey.'.html';

			if(file_exists($filePath))
			{
					header("Content-Description: File Transfer");
					header("Pragma: no-cache");
					header("Expires: 0");
					header("Pragma: public"); // required
					header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
					header("Cache-Control: private",false); // required for certain browsers
					header("Content-Type: application/octet-stream");
					header("Content-Type: application/download");
					header("Content-Type: application/html");
					header("Content-Disposition: attachment; filename=".basename($filePath));
					header("Content-Length: " . filesize($filePath));

					ob_clean();

          readfile($filePath);

					unlink($filePath);

					//$this->flash($this->get_message('successfully downloaded the file'), $this->make_base_url("dispatch/category_targeting/6/".$category.'/'.$status.'/'.$siteID.'/'.$page));
					exit;
		  }
			else
			{
					$this->flash($this->get_message('file download failed'), $this->make_base_url("dispatch/category_targeting/6/".$category.'/'.$status.'/'.$siteID.'/'.$page),0);
					exit;
			}
	}


	function featured_action()
	{
		$db          = DAL::get_instance();
		$sid         = intval($this->read_page_param(1));
		$operation   = intval($this->read_page_param(2));
		$owner       = $this->read_page_param(3);
		$category    = intval($this->read_page_param(4));
		$status      = $this->read_page_param(5);

		$featured    = $this->read_page_param(6);
		$hot         = $this->read_page_param(7);

		$search_by   = $this->read_page_param(8);
		$search_text = $this->read_page_param(9);
		$page        = $this->read_page_param(10);



		if(!CategoryHelper::get_site_exists($sid))
		{
			$this->flash($this->get_message('site invalid'), $this->make_url('dispatch/category_targeting/6',ADMIN_DIR),0);
			exit;
		}

		$db->execute_query("UPDATE ".TABLE_PREFIX."sites set featured = ? where id = ?",array($operation,$sid));

		$this->flash($this->get_message('featured status successfully updated'), $this->make_url('dispatch/category_targeting/6/'.$owner.'/'.$category.'/'.$status.'/'.$featured.'/'.$hot.'/'.$search_by.'/'.$search_text.'/'.$page,ADMIN_DIR));
		exit;
	}

	function hot_action()
	{
		$db          = DAL::get_instance();
		$sid         = intval($this->read_page_param(1));
		$operation   = intval($this->read_page_param(2));
		$owner       = $this->read_page_param(3);
		$category    = intval($this->read_page_param(4));
		$status      = $this->read_page_param(5);

		$featured    = $this->read_page_param(6);
		$hot         = $this->read_page_param(7);

		$search_by   = $this->read_page_param(8);
		$search_text = $this->read_page_param(9);
		$page        = $this->read_page_param(10);



		if(!CategoryHelper::get_site_exists($sid))
		{
			$this->flash($this->get_message('site invalid'), $this->make_url('dispatch/category_targeting/6',ADMIN_DIR),0);
			exit;
		}

		$db->execute_query("UPDATE ".TABLE_PREFIX."sites set hot = ? where id = ?",array($operation,$sid));

		$this->flash($this->get_message('hot status successfully updated'), $this->make_url('dispatch/category_targeting/6/'.$owner.'/'.$category.'/'.$status.'/'.$featured.'/'.$hot.'/'.$search_by.'/'.$search_text.'/'.$page,ADMIN_DIR));
		exit;

	}



	function add_admin_site_action()
	{
			$db  = DAL::get_instance();
			$pid = 0;

			$category			 = 0;
			$marketplace_display = 0;
			$url				 = "";
			$protocol			 = "http://";
			$title				 = '';
			$description		 = '';
			$keywords		     = '';
			$monthly_impressions_from	 = 1;
			$monthly_impressions		 = 0;

			$api_key               = "";
			$app_id                = "";
			$locations             = "";
		  $push_ad_interval      = 0;
		  $push_service_enabled  = 0;

			$cpp_enabled           = $this->get_addon_status('cpp_enabled');

			$multiCategorySupport  = Configuration::get_instance()->read('support_multiple_category_for_website');
			$this->set_variable("multiCategorySupport", $multiCategorySupport);


			if($multiCategorySupport == 1)
			{
					$rowCategory           = $db->execute_query("SELECT * FROM ".TABLE_PREFIX."categories WHERE pid = 0 ORDER BY name");
					$this->set_result('rowCategory',$rowCategory);
			}

			if($_POST)
			{
				$category=$this->read_post_param('category');
				$url=$this->read_post_param('url');
				$protocol=$this->read_post_param('protocol');
				$keywords=$this->read_post_param('keywords');
				$marketplace_display = $this->read_post_param('marketplace_display');
				$monthly_impressions_from = intval($this->read_post_param('monthly_impressions_from'));
				$monthly_impressions      = intval($this->read_post_param('monthly_impressions'));

				if($monthly_impressions_from == 0 && $monthly_impressions == 0)
				$monthly_impressions_from = 1;

				if($monthly_impressions_from == 1)
				$monthly_impressions = 0;

				$title=$this->read_post_param('title');

				$description=$this->read_post_param('description');

				$push_service_enabled   = intval($this->read_post_param('push_service_enabled'));

		    if($cpp_enabled == 1 && $push_service_enabled == 1)
		    {
		        $min_interval_push_cron = Configuration::get_instance()->read('min_interval_push_cron');

		        $api_key          = $this->read_post_param('api_key');
		        $app_id           = $this->read_post_param('appid');
		        $locations        = $this->read_post_param('a_loc');
		        $push_ad_interval = intval($this->read_post_param('push_ad_interval'));

		        if($push_ad_interval <= 0)
		        $push_ad_interval = $min_interval_push_cron;
		    }


						$logo      = $_FILES["logo"]["name"];
						$thumblogo = $_FILES["thumblogo"]["name"];


						$extensionname='';
						if($logo !='')
						{
							$extension=explode(".",$logo);
							$extensionname=strtolower($extension[count($extension)-1]);

							$logo=time().'.'.$extensionname;
						}

						$thumbextensionname='';
						if($thumblogo !='')
						{
							$thumbextension=explode(".",$thumblogo);
							$thumbextensionname=strtolower($thumbextension[count($thumbextension)-1]);

							$thumblogo=time().'-thumb.'.$thumbextensionname;
						}


						$url=str_ireplace("http://","",$url);
						$url=str_ireplace("https://","",$url);
						$url=str_ireplace("www.","",$url);


						$count = $db->read_single_column("select count(id) from ".TABLE_PREFIX."sites where url=?",array($url));

						$IABListArray = array();
						if($multiCategorySupport == 1)
						{
								$categoryArray     = explode(",",$category);
								$categoryArrayTemp = array();

								foreach($categoryArray as $catKey => $catValue)
								{
										$catValue = trim($catValue);

										if($catValue != "")
										{
												if(CategoryHelper::get_category_exists($catValue))
												{
														$categoryArrayTemp[] = $catValue;
														$IABCategory         = $db->read_single_column("SELECT iab_id FROM ".TABLE_PREFIX."categories WHERE id = ?", array($catValue));
														if($IABCategory != "")
														$IABListArray[]      = $IABCategory;
												}
										}
								}

								$categoryCount = count($categoryArrayTemp);

								if($categoryCount > 0)
								$category = ",".implode("," , $categoryArrayTemp).",";
						}
						else
						{
								if(intval($category) > 0)
{
										$categoryCount = 1;

										$IABCategory         = $db->read_single_column("SELECT iab_id FROM ".TABLE_PREFIX."categories WHERE id = ?", array($category));

										if($IABCategory != "")
										$IABListArray[]      = $IABCategory;
								 }

						}


						$locations                    = substr($locations, 0, -1);
				    $push_ads_country_array       = explode(',', $locations);
				    $push_ads_country_array_count = count($push_ads_country_array);

				    $country_code_string          = "";

				    if($push_ads_country_array_count > 0)
				    {
				        foreach($push_ads_country_array as $key => $value)
				        {
				            if($country_code_string != "")
				            $country_code_string.= " OR ";

				            $country_code_string.= " code = '".$value."' ";
				        }

				        if($country_code_string != "")
				        $country_code_string = " WHERE (".$country_code_string.") ";

				        $result1 = $db->execute_query("select code,name from ".TABLE_PREFIX."countries ".$country_code_string." ORDER BY name");
				        $this->set_result("result1",$result1);
				    }
				    
				    		$IABListString = "";

						if(count($IABListArray) > 0)
						$IABListString = implode("," , $IABListArray);



						 if($title == "")
						 $this->set_notice("mandatory");
						 else if($categoryCount == 0)
						 $this->set_notice("please select a category");
						 else if(!UtilityHelper::is_valid_domain($url))
						 $this->set_notice("invalid url");
						 else if($count >0)
						 $this->set_notice("site name already exists");
						 else if($logo !='' && $extensionname != "gif" && $extensionname != "jpeg" && $extensionname != "pjpeg" && $extensionname != "png" && $extensionname != "jpg" && $extensionname != "svg")
						 $this->set_notice("image not supported");
						 else if($logo !='' && $_FILES["logo"]["error"] > 0)
						 $this->set_notice("error occurred in image uploading");
						 else if($thumblogo !='' && $thumbextensionname != "gif" && $thumbextensionname != "jpeg" && $thumbextensionname != "pjpeg" && $thumbextensionname != "png" && $thumbextensionname != "jpg" && $thumbextensionname != "svg")
						 $this->set_notice("image not supported");
						 else if($thumblogo !='' && $_FILES["thumblogo"]["error"] > 0)
						 $this->set_notice("error occurred in image uploading");
						 else if($cpp_enabled == 1 && $push_service_enabled ==1 && $api_key=='')
						 $this->set_notice("please enter api key");
						 else if($cpp_enabled == 1 && $push_service_enabled ==1 && $app_id=='')
						 $this->set_notice("please enter appid");
						 else
						 {
									$alexa_rank = 0;


																	$sponsored_enabled=$this->get_addon_status('sponsored_enabled');

																	$insertArray = array($pid,$category,$url,1,$title,$description,time(),$alexa_rank,$protocol,$marketplace_display,$keywords,$IABListString);

																	$appendString  = "";
																	$appendString1 = "";


																	if($sponsored_enabled == 1)
																	{
																		$appendString  = ",impression_source,monthly_impressions";
																		$appendString1 = ",?,?";

																		$insertArray[] = $monthly_impressions_from;
																		$insertArray[] = $monthly_impressions;
																	}

																	if($cpp_enabled == 1 && $push_service_enabled == 1)
														      {
														          $appendString.= ",push_ads_apikey,push_ads_appid,push_ads_country_target,push_notification_service_enabled,push_adserve_interval";
														          $appendString1.= ",?,?,?,?,?";

														          $insertArray[] = $api_key;
														          $insertArray[] = $app_id;
														          $insertArray[] = $locations;
														          $insertArray[] = $push_service_enabled;
														          $insertArray[] = $push_ad_interval;
														      }
														      else if($cpp_enabled == 1)
														      {
														          $appendString.= ",push_notification_service_enabled";
														          $appendString1.= ",?";

														          $insertArray[] = $push_service_enabled;
														      }

																	$res = $db->execute_query("INSERT INTO ".TABLE_PREFIX."sites (pid,catid,url,status,title,description,time,alexa_rank,protocol,marketplace_display,keywords,iab_category_id".$appendString.") values (?,?,?,?,?,?,?,?,?,?,?,?".$appendString1.")",$insertArray);
																	$lsid = $res->get_last_id();

																	if($res->error == "" && $cpp_enabled == 1 && $push_service_enabled == 1)
																	{
																			$max_adunit_id = $db->read_single_column("SELECT MAX(id)  FROM ".TABLE_PREFIX."adunit");
																			$max_adunit_id++;
																			$db->execute_query("INSERT INTO ".TABLE_PREFIX."adunit (name,pubid,status,display_type,adcode_type,sid) values (?,?,?,?,?,?)",array('CPP_'.$max_adunit_id,0,1,18,18,$lsid));
																	}


																	if($logo !='')
																	{
																		if(!is_dir('../'.DATA_DIR.'/site_logo'))
																			mkdir('../'.DATA_DIR.'/site_logo',0777);

																			mkdir('../'.DATA_DIR.'/site_logo/'.$lsid,0777);

																			if($res->error =="")
																			{
																				if(move_uploaded_file($_FILES["logo"]["tmp_name"],'../'.DATA_DIR."/site_logo/".$lsid."/".$logo))
																				{
																					$height=70;
																					$width=70;

																					$image=new ImageHelper('../'.DATA_DIR."/site_logo/".$lsid."/".$logo);
																					$image->resize($width,$height,'../'.DATA_DIR."/site_logo/".$lsid."/".$logo);

																					$db->execute_query("UPDATE ".TABLE_PREFIX."sites SET logo=? WHERE id=?",array($logo,$lsid));
																				}
																			}
																	}

																	if($thumblogo !='')
																	{
																		if(!is_dir('../'.DATA_DIR.'/site_logo'))
																			mkdir('../'.DATA_DIR.'/site_logo',0777);

																			mkdir('../'.DATA_DIR.'/site_logo/'.$lsid,0777);

																			if($res->error =="")
																			{
																				if(move_uploaded_file($_FILES["thumblogo"]["tmp_name"],'../'.DATA_DIR."/site_logo/".$lsid."/".$thumblogo))
																				{
																					$height = 200;
																					$width  = 240;

																					$image=new ImageHelper('../'.DATA_DIR."/site_logo/".$lsid."/".$thumblogo);
																					$image->resize($width,$height,'../'.DATA_DIR."/site_logo/".$lsid."/".$thumblogo);

																					$db->execute_query("UPDATE ".TABLE_PREFIX."sites SET thumbshot = 1,thumbshot_image = ? WHERE id=?",array($thumblogo,$lsid));
																				}
																			}
																	}


																	$this->flash($this->get_message('site add success'), $this->make_url('dispatch/category_targeting/6/0',ADMIN_DIR));
																	exit;
																}
			}

			$result = $db->execute_query("select code,name from ".TABLE_PREFIX."countries WHERE code!='A1' and code!='A2' and code!='AP' and code!='EU' ORDER BY name");
			$this->set_result("result",$result);


			$this->set_variable("url",$url);
			$this->set_variable("protocol",$protocol);
			$this->set_variable("category",$category);
			$this->set_variable("title",$title);
			$this->set_variable("api_key",$api_key);
			$this->set_variable("app_id",$app_id);
			$this->set_variable("push_ad_interval",$push_ad_interval);
			$this->set_variable("push_service_enabled",$push_service_enabled);
			$this->set_variable("description",$description);
			$this->set_variable("marketplace_display",$marketplace_display);
			$this->set_variable("keywords",$keywords);
			$this->set_variable("monthly_impressions_from",$monthly_impressions_from);
			$this->set_variable("monthly_impressions",$monthly_impressions);

	}

	function manage_admin_site_action()
	{
		$db= DAL::get_instance();
		$owner="";
		$site_id="";
		$site_name="";
		$ownername="";
		$search_by="";
		$search_text="";
		$status="";
		$featured="";
		$hot="";

		$sponsored_enabled = $this->get_addon_status('sponsored_enabled');

		if($_POST)
		{
			$owner=intval($this->read_post_param('owner'));
	        	$search_by=$this->read_post_param('search_by');
			$search_text=$this->read_post_param('search_text');
			$category=intval($this->read_post_param('category'));
			$status=intval($this->read_post_param('status'));
			$featured=intval($this->read_post_param('featured'));
			$hot=intval($this->read_post_param('hot'));
		}
		else
		{
			$owner=$this->read_page_param(1);
			$category=$this->read_page_param(2);
			$status=$this->read_page_param(3);
			$featured=$this->read_page_param(4);
			$hot=$this->read_page_param(5);
			$search_by=$this->read_page_param(6);
			$search_text=$this->read_page_param(7);

			$exp=explode("-",$category);
			if($exp[0]=="page")
			$category=0;

			$exp=explode("-",$status);
			if($exp[0]=="page")
			$status=-2;

			$exp=explode("-",$owner);
			if($exp[0]=="page")
			$owner=-1;

			$exp=explode("-",$featured);
			if($exp[0]=="page")
			    $featured=-1;

			$exp=explode("-",$hot);
			if($exp[0]=="page")
			    $hot=-1;

			$exp=explode("-",$search_by);
			if($exp[0]=="page")
		        $search_by=0;

		}

		$search_text = str_replace("%","\%",$search_text);
		$search_text = str_replace("_","\_",$search_text);

		if($search_by == 1)
		$site_id = intval($search_text);
		else if($search_by == 2)
		$site_name = $db->sanitize($search_text);
		else if($search_by == 3)
		$ownername = $db->sanitize($search_text);


		if($owner === "")
		$owner=-1;


		if($status === "")
		$status=-2;

		if($featured === "")
		$featured=-1;

		if($hot === "")
		$hot=-1;

		if($search_by === "")
	        $search_by=0;

		if($owner != -1)
		$string=' WHERE pid='.intval($owner).' ';
		else
		$string=' WHERE ( s.pid=0 OR s.pid >0 ) ';

		if($site_id != "")
		$string.=" AND s.id='".$site_id."' ";

		if($site_name != "")
		$string.=" AND s.url LIKE '%".$site_name."%' ";

		if($ownername != "")
		$string.=" AND u.username LIKE '%".$ownername."%' ";


		if($category > 0)
		$string.=' AND ( s.catid LIKE "%,'.intval($category).',%" OR s.catid = '.intval($category).') ';

		if($status != -2)
		$string.=' AND s.status='.intval($status).' ';
		else
		$string.=' AND s.status <> -3 ';


		if($sponsored_enabled == 1 && $featured != -1)
		$string.=' AND s.featured='.intval($featured).' ';

		if($sponsored_enabled == 1 && $hot != -1)
		$string.=' AND s.hot='.intval($hot).' ';


		$this->set_variable("owner",$owner);
		$this->set_variable("category",$category);
		$this->set_variable("status",$status);
		$this->set_variable("featured",$featured);
		$this->set_variable("hot",$hot);




		$pagination = new Pagination("SELECT s.*,u.username FROM ".TABLE_PREFIX."sites s LEFT JOIN ".TABLE_PREFIX."users u ON s.pid=u.id ".$string." ORDER BY s.id DESC");
		$res=$pagination->get_result();
		$this->set_result("res",$res);
		$this->set_variable("pagination",$pagination->links(),0);

		$pg=$pagination->get_page_number();
		$this->set_variable("page","page-".$pg);

		$search_text = str_replace("\%","%",$search_text);
		$search_text = str_replace("\_","_",$search_text);
		$this->set_variable("search_by",$search_by);
		$this->set_variable("search_text",$search_text);



		$data=$db->execute_query("select u.id,u.username from ".TABLE_PREFIX."sites s INNER JOIN ".TABLE_PREFIX."users u ON s.pid=u.id GROUP BY u.id order by u.id ASC");
		$this->set_result("data",$data);



	}

	function edit_admin_site_action()
	{
		$db= DAL::get_instance();

		if($_POST)
		$sid=$this->read_post_param('sid');
		else
		{
			$sid=intval($this->read_page_param(1));
			$owner=$this->read_page_param(2);
			$urlcategory=intval($this->read_page_param(3));
			$status=$this->read_page_param(4);

			$featured=$this->read_page_param(5);
			$hot=$this->read_page_param(6);
			$search_by=$this->read_page_param(7);
			$search_text=$this->read_page_param(8);

			$page=$this->read_page_param(9);
		}



		$multiCategorySupport  = Configuration::get_instance()->read('support_multiple_category_for_website');
		$this->set_variable("multiCategorySupport", $multiCategorySupport);


		if($multiCategorySupport == 1)
		{
				$rowCategory           = $db->execute_query("SELECT * FROM ".TABLE_PREFIX."categories WHERE pid = 0 ORDER BY name");
				$this->set_result('rowCategory',$rowCategory);
		}



		$cpp_enabled = $this->get_addon_status('cpp_enabled');

		if(!CategoryHelper::get_site_exists($sid))
		{
			$this->flash($this->get_message('site invalid'), $this->make_url('dispatch/category_targeting/6',ADMIN_DIR),0);
			exit;
		}

		$logo      = '';
		$thumblogo = '';
		$thumbshot = 0;

		$res = $db->execute_query("select * from ".TABLE_PREFIX."sites where id=?",array($sid));
		$row = $res->fetch_assoc();

		$logo      = $row['logo'];
		$thumblogo = $row['thumbshot_image'];
		$thumbshot = $row['thumbshot'];

		$push_ads_apikey      = "";
		$push_ads_appid       = "";
		$locations            = "";
		$push_ad_interval     = 0;
		$push_service_enabled = 0;

		if($_POST)
		{

			if(DEMO_MODE && $sid <= 10)
			{
				$this->flash($this->get_message('demo mode'), $this->make_url('dispatch/category_targeting/6',ADMIN_DIR),0);
				exit;
			}



      $urlcategory=$this->read_post_param('urlcategory');
			$owner=$this->read_post_param('owner');
			$category=$this->read_post_param('category');
			$url=$this->read_post_param('url');
			$protocol=$this->read_post_param('protocol');
			$status=$this->read_post_param('status');

			$featured=$this->read_post_param('featured');
			$hot=$this->read_post_param('hot');
			$search_by=$this->read_post_param('search_by');
			$search_text=$this->read_post_param('search_text');

			$page=$this->read_post_param('page');
			$keywords=$this->read_post_param('keywords');
			$marketplace_display = $this->read_post_param('marketplace_display');
			$monthly_impressions_from = intval($this->read_post_param('monthly_impressions_from'));
			$monthly_impressions      = intval($this->read_post_param('monthly_impressions'));

			if($monthly_impressions_from == 0 && $monthly_impressions == 0)
			$monthly_impressions_from = 1;

			if($monthly_impressions_from == 1)
			$monthly_impressions = 0;

			$title=$this->read_post_param('title');
			$description=$this->read_post_param('description');


			$push_service_enabled = intval($this->read_post_param('push_service_enabled'));

			if($cpp_enabled == 1 && $push_service_enabled == 1)
			{
					$min_interval_push_cron = Configuration::get_instance()->read('min_interval_push_cron');

					$push_ads_apikey  = $this->read_post_param('api_key');
					$push_ads_appid   = $this->read_post_param('appid');
					$locations        = $this->read_post_param('a_loc');
					$push_ad_interval = intval($this->read_post_param('push_ad_interval'));

					if($push_ad_interval <= 0)
					$push_ad_interval = $min_interval_push_cron;
			}


			$logo       = $_FILES["logo"]["name"];
			$thumblogo  = $_FILES["thumblogo"]["name"];


			$extensionname='';
			if($logo !='')
			{
				$extension=explode(".",$logo);
				$extensionname=strtolower($extension[count($extension)-1]);

				$logo=time().'.'.$extensionname;
			}

			$thumbextensionname='';
			if($thumblogo !='')
			{
				$thumbextension=explode(".",$thumblogo);
				$thumbextensionname=strtolower($thumbextension[count($thumbextension)-1]);

				$thumblogo=time().'-thumb.'.$thumbextensionname;
			}




			$url=str_ireplace("http://","",$url);
			$url=str_ireplace("https://","",$url);
			$url=str_ireplace("www.","",$url);


			$count = $db->read_single_column("select count(id) from ".TABLE_PREFIX."sites where url=? AND id <> ?",array($url,$sid));

			$IABListArray = array();
			if($multiCategorySupport == 1)
			{
					$categoryArray     = explode(",",$category);
					$categoryArrayTemp = array();

					foreach($categoryArray as $catKey => $catValue)
					{
							$catValue = trim($catValue);

							if($catValue != "")
							{
									if(CategoryHelper::get_category_exists($catValue))
									{
									$categoryArrayTemp[] = $catValue;
											$IABCategory         = $db->read_single_column("SELECT iab_id FROM ".TABLE_PREFIX."categories WHERE id = ?", array($catValue));
											if($IABCategory != "")
											$IABListArray[]      = $IABCategory;
									}
							}
					}

					$categoryCount = count($categoryArrayTemp);

					if($categoryCount > 0)
					$category = ",".implode("," , $categoryArrayTemp).",";
			}
			else
			{
					if(intval($category) > 0)
					{
							$categoryCount = 1;

							$IABCategory         = $db->read_single_column("SELECT iab_id FROM ".TABLE_PREFIX."categories WHERE id = ?", array($category));

							if($IABCategory != "")
							$IABListArray[]      = $IABCategory;
					}

			}
			
			$IABListString = "";

			if(count($IABListArray) > 0)
			$IABListString = implode("," , $IABListArray);

			$locations                    = substr($locations, 0, -1);

			$push_ads_country_array       = explode(',', $locations);
			$push_ads_country_array_count = count($push_ads_country_array);

			$country_code_left   = "";
			$country_code_string = "";

			if($push_ads_country_array_count > 0)
			{
					foreach($push_ads_country_array as $key => $value)
					{
							if($country_code_left != "")
							$country_code_left.= " AND ";

							$country_code_left.= " code != '".$value."' ";
					}

					if($country_code_left != "")
					$country_code_left = " ".$country_code_left." AND ";
			}

			$result = $db->execute_query("select code,name from ".TABLE_PREFIX."countries where ".$country_code_left." code!='A1' and code!='A2' and code!='AP' and code!='EU' ORDER BY name");
			$this->set_result("result",$result);


			if($push_ads_country_array_count > 0)
			{
					foreach($push_ads_country_array as $key => $value)
					{
							if($country_code_string != "")
							$country_code_string.= " OR ";

							$country_code_string.=" code = '".$value."' ";
					}

					if($country_code_string != "")
					$country_code_string = " WHERE (".$country_code_string.") ";

					$result1 = $db->execute_query("select code,name from ".TABLE_PREFIX."countries ".$country_code_string." ORDER BY name");
					$this->set_result("result1",$result1);
			}

			if($title == "")
			$this->set_notice("mandatory");
			else if($categoryCount == 0)
			$this->set_notice("please select a category");
			else if(!UtilityHelper::is_valid_domain($url))
			$this->set_notice("invalid url");
			else if($count >0)
			$this->set_notice("site name already exists");
			else if($logo !='' && $extensionname != "gif" && $extensionname != "jpeg" && $extensionname != "pjpeg" && $extensionname != "png" && $extensionname != "jpg" && $extensionname != "svg")
			$this->set_notice("image not supported");
			else if($logo !='' && $_FILES["logo"]["error"] > 0)
			$this->set_notice("error occurred in image uploading");
			else if($thumblogo !='' && $thumbextensionname != "gif" && $thumbextensionname != "jpeg" && $thumbextensionname != "pjpeg" && $thumbextensionname != "png" && $thumbextensionname != "jpg" && $thumbextensionname != "svg")
			$this->set_notice("image not supported");
			else if($thumblogo !='' && $_FILES["thumblogo"]["error"] > 0)
			$this->set_notice("error occurred in image uploading");
			else if($cpp_enabled == 1 && $push_service_enabled ==1 && $api_key=='')
			$this->set_notice("please enter api key");
			else if($cpp_enabled == 1 && $push_service_enabled ==1 && $app_id=='')
			$this->set_notice("please enter appid");
			else
			{
				$alexa_rank = 0;

				$sponsored_enabled=$this->get_addon_status('sponsored_enabled');

				$insertArray = array($url,$category,$title,$description,$alexa_rank,$protocol,$marketplace_display,$keywords,$IABListString);

				$appendString  = "";

				if($sponsored_enabled == 1)
				{
					$appendString  = ",impression_source=?,monthly_impressions=?";

					$insertArray[] = $monthly_impressions_from;
					$insertArray[] = $monthly_impressions;
				}

				if($cpp_enabled == 1 && $push_service_enabled == 1)
				{
						$appendString.= ", push_ads_apikey = ?, push_ads_appid = ?, push_ads_country_target = ?, push_notification_service_enabled = ?, push_adserve_interval = ? ";

						$insertArray[] = $push_ads_apikey;
						$insertArray[] = $push_ads_appid;
						$insertArray[] = $locations;
						$insertArray[] = $push_service_enabled;
						$insertArray[] = $push_ad_interval;
				}
				else if($cpp_enabled == 1)
				{
						$appendString.= ", push_notification_service_enabled = ? ";

						$insertArray[] = $push_service_enabled;
				}


				$insertArray[] = $sid;


				$res = $db->execute_query("UPDATE ".TABLE_PREFIX."sites SET url=?,catid=?,title=?,description=?,alexa_rank=?,protocol=?,marketplace_display=?,keywords=?,iab_category_id=?".$appendString." WHERE id=?",$insertArray);

				if($res->error == "" && $cpp_enabled == 1 && $push_service_enabled == 1)
				{
						$pushAdcodeID = $db->read_single_column("SELECT id FROM ".TABLE_PREFIX."adunit WHERE sid = ? AND display_type = ?",array($sid,18));

						if(intval($pushAdcodeID) == 0)
						{
								$max_adunit_id=$db->read_single_column("SELECT MAX(id)  FROM ".TABLE_PREFIX."adunit");
								$max_adunit_id++;
								$db->execute_query("INSERT INTO ".TABLE_PREFIX."adunit (name,pubid,status,display_type,adcode_type,sid) values (?,?,?,?,?,?)",array('CPP_'.$max_adunit_id,0,1,18,18,$sid));
						}
				}


					if($logo !='')
					{
						if(!is_dir('../'.DATA_DIR.'/site_logo'))
						mkdir('../'.DATA_DIR.'/site_logo',0777);

						if(!is_dir('../'.DATA_DIR.'/site_logo/'.$sid))
						mkdir('../'.DATA_DIR.'/site_logo/'.$sid,0777);

						if($res->error =="")
						{
							if(move_uploaded_file($_FILES["logo"]["tmp_name"],'../'.DATA_DIR."/site_logo/".$sid."/".$logo))
							{
								$height=70;
								$width=70;

								$image=new ImageHelper('../'.DATA_DIR."/site_logo/".$sid."/".$logo);
								$image->resize($width,$height,'../'.DATA_DIR."/site_logo/".$sid."/".$logo);

								$oldlogo=$db->read_single_column("SELECT logo FROM ".TABLE_PREFIX."sites where id=?",array($sid));

								$db->execute_query("UPDATE ".TABLE_PREFIX."sites SET logo=? WHERE id=?",array($logo,$sid));

								unlink('../'.DATA_DIR."/site_logo/".$sid."/".$oldlogo);
							}
						}
					}


					if($thumblogo !='')
					{
						if(!is_dir('../'.DATA_DIR.'/site_logo'))
						mkdir('../'.DATA_DIR.'/site_logo',0777);

						if(!is_dir('../'.DATA_DIR.'/site_logo/'.$sid))
						mkdir('../'.DATA_DIR.'/site_logo/'.$sid,0777);

						if($res->error =="")
						{
							if(move_uploaded_file($_FILES["thumblogo"]["tmp_name"],'../'.DATA_DIR."/site_logo/".$sid."/".$thumblogo))
							{
								$height = 200;
								$width  = 240;

								$image=new ImageHelper('../'.DATA_DIR."/site_logo/".$sid."/".$thumblogo);
								$image->resize($width,$height,'../'.DATA_DIR."/site_logo/".$sid."/".$thumblogo);

								$oldlogo=$db->read_single_column("SELECT thumbshot_image FROM ".TABLE_PREFIX."sites where id=?",array($sid));

								$db->execute_query("UPDATE ".TABLE_PREFIX."sites SET thumbshot = 1,thumbshot_image=? WHERE id=?",array($thumblogo,$sid));

								unlink('../'.DATA_DIR."/site_logo/".$sid."/".$oldlogo);
							}
						}
					}

				    $this->flash($this->get_message('site edit success'), $this->make_url('dispatch/category_targeting/6/'.$owner.'/'.$urlcategory.'/'.$status.'/'.$featured.'/'.$hot.'/'.$search_by.'/'.$search_text.'/'.$page,ADMIN_DIR));
					exit;
			}
		}
		else
		{
			$res=$db->execute_query("select * from ".TABLE_PREFIX."sites where id=?",array($sid));
			$row=$res->fetch_assoc();

			$sid=$row['id'];
			$category=$row['catid'];
			$url=$row['url'];
			$protocol=$row['protocol'];
			$title=$row['title'];
			$push_service_enabled = $row['push_notification_service_enabled'];
			$api_key=$row['push_ads_apikey'];
			$app_id=$row['push_ads_appid'];
			$description=$row['description'];
			$logo=$row['logo'];
			$thumblogo=$row['thumbshot_image'];
			$thumbshot=$row['thumbshot'];

			$marketplace_display = $row['marketplace_display'];
			$keywords = $row['keywords'];

			$monthly_impressions_from = intval($row['impression_source']);
			$monthly_impressions      = intval($row['monthly_impressions']);

			if($cpp_enabled == 1)
			{
					$push_service_enabled    = $row['push_notification_service_enabled'];
					$push_ads_country_string = $row['push_ads_country_target'];
					$push_ads_apikey         = $row['push_ads_apikey'];
					$push_ads_appid          = $row['push_ads_appid'];
					$push_ad_interval        = $row['push_adserve_interval'];

					$push_ads_country_array       = explode(',', $push_ads_country_string);
					$push_ads_country_array_count = count($push_ads_country_array);

					$country_code_left   = "";
					$country_code_string = "";

					if($push_ads_country_array_count > 0)
					{
							foreach($push_ads_country_array as $key => $value)
							{
									if($country_code_left != "")
									$country_code_left.= " AND ";

									$country_code_left.= " code != '".$value."' ";
							}

							if($country_code_left != "")
							$country_code_left = " ".$country_code_left." AND ";
					}

					$result = $db->execute_query("select code,name from ".TABLE_PREFIX."countries where ".$country_code_left." code!='A1' and code!='A2' and code!='AP' and code!='EU' ORDER BY name");
					$this->set_result("result",$result);


					if($push_ads_country_array_count > 0)
					{
							foreach($push_ads_country_array as $key => $value)
							{
									if($country_code_string != "")
									$country_code_string.= " OR ";

									$country_code_string.=" code = '".$value."' ";
							}

							if($country_code_string != "")
							$country_code_string = " WHERE (".$country_code_string.") ";

							$result1 = $db->execute_query("select code,name from ".TABLE_PREFIX."countries ".$country_code_string." ORDER BY name");
							$this->set_result("result1",$result1);
					}
			 }
		}

		$this->set_variable("push_ads_apikey",$push_ads_apikey);
		$this->set_variable("push_ads_appid",$push_ads_appid);
		$this->set_variable("push_ad_interval",$push_ad_interval);
		$this->set_variable("push_service_enabled",$push_service_enabled);

		$this->set_variable("title",$title);
		$this->set_variable("description",$description);
		$this->set_variable("sid",$sid);
		$this->set_variable("url",$url);
		$this->set_variable("protocol",$protocol);
		$this->set_variable("category",$category);
    		$this->set_variable("urlcategory",$urlcategory);
		$this->set_variable("marketplace_display",$marketplace_display);
		$this->set_variable("keywords",$keywords);
		$this->set_variable("owner",$owner);
		$this->set_variable("status",$status);

		$this->set_variable("featured",$featured);
		$this->set_variable("hot",$hot);
		$this->set_variable("search_by",$search_by);
		$this->set_variable("search_text",$search_text);


		$this->set_variable("page",$page);
		$this->set_variable("monthly_impressions_from",$monthly_impressions_from);
		$this->set_variable("monthly_impressions",$monthly_impressions);
		$this->set_variable("logo",$logo);
		$this->set_variable("thumblogo",$thumblogo);
		$this->set_variable("thumbshot",$thumbshot);
	}

	function activate_site_action()
	{
		$db= DAL::get_instance();

		$yesno=0;
		$subject='';
		$message='';
		if($_POST)
		{
			$sid=intval($this->read_post_param('sid'));
			$owner=$this->read_post_param('owner');
			$category=intval($this->read_post_param('category'));
			$status=$this->read_post_param('status');

			$featured=$this->read_post_param('featured');
			$hot=$this->read_post_param('hot');
			$search_by=$this->read_post_param('search_by');
			$search_text=$this->read_post_param('search_text');

			$page=$this->read_post_param('page');
			$yesno=intval($this->read_post_param('yesno'));
		}
		else
		{
			$sid=intval($this->read_page_param(1));
			$owner=$this->read_page_param(2);
			$category=intval($this->read_page_param(3));
			$status=$this->read_page_param(4);

			$featured=$this->read_page_param(5);
			$hot=$this->read_page_param(6);
			$search_by=$this->read_page_param(7);
			$search_text=$this->read_page_param(8);

			$page=$this->read_page_param(9);
		}


		if(!CategoryHelper::get_site_exists($sid))
		{
			$this->flash($this->get_message('site invalid'), $this->make_url('dispatch/category_targeting/6',ADMIN_DIR),0);
			exit;
		}

		$siteownerid=$db->read_single_column("SELECT pid FROM ".TABLE_PREFIX."sites WHERE id=?",array($sid));
		$sitename=$db->read_single_column("SELECT url FROM ".TABLE_PREFIX."sites WHERE id=?",array($sid));

		if($_POST || $siteownerid ==0)
		{
			$res=$db->execute_query("UPDATE ".TABLE_PREFIX."sites SET status=1 WHERE id=?",array($sid));


			if($yesno ==0 && $siteownerid >0)
			{
				$subject=$this->read_post_param('subject');
				$message=$this->read_post_param('message');

				if(DEMO_MODE)
				{
					$this->flash($this->get_message('demo mode'),$this->make_url('dispatch/category_targeting/6',ADMIN_DIR),0);
					exit;
				}

				$email=$db->read_single_column("select email from ".TABLE_PREFIX."users where id=?",array($siteownerid));

				if($subject =="" || $message =="")
				$this->set_notice('mandatory');
				else
				UtilityHelper::send_mail($email,$subject,$message);
			}

			$this->flash($this->get_message('site status success'), $this->make_url('dispatch/category_targeting/6/'.$owner.'/'.$category.'/'.$status.'/'.$featured.'/'.$hot.'/'.$search_by.'/'.$search_text.'/'.$page,ADMIN_DIR));
			exit;
		}
		else if($siteownerid >0)
		{


			$userdata=$db->execute_query("select * from ".TABLE_PREFIX."users where id=?",array($siteownerid));
			$userdata1=$userdata->fetch_assoc();
			$username=$userdata1['username'];

			$language_enabled=Configuration::get_instance()->read('language_enabled');

			$res=$db->execute_query("select * from ".TABLE_PREFIX."email_templates where id=17");
			$result1=$res->fetch_assoc();


			if($language_enabled ==1 && $userdata1['locale'] >0 && isset($result1[$userdata1['locale'].'_message']) && $result1[$userdata1['locale'].'_message'] !='')
			$message=$result1[$userdata1['locale'].'_message'];
			else
			$message=$result1['message'];

			if($language_enabled ==1 && $userdata1['locale'] >0 && isset($result1[$userdata1['locale'].'_subject']) && $result1[$userdata1['locale'].'_subject'] !='')
			$subject=$result1[$userdata1['locale'].'_subject'];
			else
			$subject=$result1['subject'];



			$subject=str_replace("{ADMARKETNAME}",Configuration::get_instance()->read('admarket_name'),$subject);
			$message=str_replace("{ADMARKETNAME}",Configuration::get_instance()->read('admarket_name'),$message);
			$message=str_replace("{USERNAME}",$username,$message);
			$message=str_replace("{NEWSTATUS}",$this->get_label('activated'),$message);
			$subject=str_replace("{NEWSTATUS}",$this->get_label('activated'),$subject);
			$message=str_replace("{SITENAME}",$sitename,$message);
		}

		$this->set_variable('sid', $sid);
		$this->set_variable('owner', $owner);
		$this->set_variable('category', $category);
		$this->set_variable('status', $status);

		$this->set_variable('featured', $featured);
		$this->set_variable('hot', $hot);
		$this->set_variable('search_by', $search_by);
		$this->set_variable('search_text', $search_text);

		$this->set_variable('page', $page);
		$this->set_variable('yesno', $yesno);

		$this->set_variable('subject', $subject);
		$this->set_variable('message', $message,0);
	}

	function block_site_action()
	{
		$db= DAL::get_instance();

		$yesno=0;
		$subject='';
		$message='';
		if($_POST)
		{
			$sid=intval($this->read_post_param('sid'));
			$owner=$this->read_post_param('owner');
			$category=intval($this->read_post_param('category'));
			$status=$this->read_post_param('status');

			$featured=$this->read_post_param('featured');
			$hot=$this->read_post_param('hot');
			$search_by=$this->read_post_param('search_by');
			$search_text=$this->read_post_param('search_text');

			$page=$this->read_post_param('page');
			$yesno=intval($this->read_post_param('yesno'));
		}
		else
		{
			$sid=intval($this->read_page_param(1));
			$owner=$this->read_page_param(2);
			$category=intval($this->read_page_param(3));
			$status=$this->read_page_param(4);

			$featured=$this->read_page_param(5);
			$hot=$this->read_page_param(6);
			$search_by=$this->read_page_param(7);
			$search_text=$this->read_page_param(8);

			$page=$this->read_page_param(9);
		}


		if(!CategoryHelper::get_site_exists($sid))
		{
			$this->flash($this->get_message('site invalid'), $this->make_url('dispatch/category_targeting/6',ADMIN_DIR),0);
			exit;
		}

		if(DEMO_MODE && $sid <= 10)
		{
			$this->flash($this->get_message('demo mode'), $this->make_url('dispatch/category_targeting/6',ADMIN_DIR),0);
			exit;
		}


		$siteownerid=$db->read_single_column("SELECT pid FROM ".TABLE_PREFIX."sites WHERE id=?",array($sid));
		$sitename=$db->read_single_column("SELECT url FROM ".TABLE_PREFIX."sites WHERE id=?",array($sid));

		if($_POST || $siteownerid ==0)
		{
			$res=$db->execute_query("UPDATE ".TABLE_PREFIX."sites SET status=0 WHERE id=?",array($sid));

			if($yesno ==0 && $siteownerid >0)
			{
				$subject=$this->read_post_param('subject');
				$message=$this->read_post_param('message');

				if(DEMO_MODE)
				{
					$this->flash($this->get_message('demo mode'),$this->make_url('dispatch/category_targeting/6',ADMIN_DIR),0);
					exit;
				}

				$email=$db->read_single_column("select email from ".TABLE_PREFIX."users where id=?",array($siteownerid));

				if($subject =="" || $message =="")
				$this->set_notice('mandatory');
				else
				UtilityHelper::send_mail($email,$subject,$message);
			}

			$this->flash($this->get_message('site status success'), $this->make_url('dispatch/category_targeting/6/'.$owner.'/'.$category.'/'.$status.'/'.$featured.'/'.$hot.'/'.$search_by.'/'.$search_text.'/'.$page,ADMIN_DIR));
			exit;
		}
		else if($siteownerid >0)
		{


			$userdata=$db->execute_query("select * from ".TABLE_PREFIX."users where id=?",array($siteownerid));
			$userdata1=$userdata->fetch_assoc();
			$username=$userdata1['username'];

			$language_enabled=Configuration::get_instance()->read('language_enabled');

			$res=$db->execute_query("select * from ".TABLE_PREFIX."email_templates where id=17");
			$result1=$res->fetch_assoc();


			if($language_enabled ==1 && $userdata1['locale'] >0 && isset($result1[$userdata1['locale'].'_message']) && $result1[$userdata1['locale'].'_message'] !='')
			$message=$result1[$userdata1['locale'].'_message'];
			else
			$message=$result1['message'];

			if($language_enabled ==1 && $userdata1['locale'] >0 && isset($result1[$userdata1['locale'].'_subject']) && $result1[$userdata1['locale'].'_subject'] !='')
			$subject=$result1[$userdata1['locale'].'_subject'];
			else
			$subject=$result1['subject'];



			$subject=str_replace("{ADMARKETNAME}",Configuration::get_instance()->read('admarket_name'),$subject);
			$message=str_replace("{ADMARKETNAME}",Configuration::get_instance()->read('admarket_name'),$message);
			$message=str_replace("{USERNAME}",$username,$message);
			$message=str_replace("{NEWSTATUS}",$this->get_label('blocked'),$message);
			$subject=str_replace("{NEWSTATUS}",$this->get_label('blocked'),$subject);
			$message=str_replace("{SITENAME}",$sitename,$message);

		}

		$this->set_variable('sid', $sid);
		$this->set_variable('owner', $owner);
		$this->set_variable('category', $category);
		$this->set_variable('status', $status);

		$this->set_variable('featured', $featured);
		$this->set_variable('hot', $hot);
		$this->set_variable('search_by', $search_by);
		$this->set_variable('search_text', $search_text);

		$this->set_variable('page', $page);
		$this->set_variable('yesno', $yesno);

		$this->set_variable('subject', $subject);
		$this->set_variable('message', $message,0);

	}



	function delete_logo_admin_action()
	{
		$db  = DAL::get_instance();
		$sid = intval($this->read_page_param(1));

		if(!CategoryHelper::get_site_exists($sid))
		{
			$this->flash($this->get_message('site invalid'), $this->make_url('dispatch/category_targeting/6',ADMIN_DIR),0);
			exit;
		}

		if(DEMO_MODE && $sid <= 10)
		{
			$this->flash($this->get_message('demo mode'), $this->make_url('dispatch/category_targeting/6',ADMIN_DIR),0);
			exit;
		}

		$old_logo=$db->read_single_column("SELECT logo FROM ".TABLE_PREFIX."sites WHERE id=?",array($sid));

		$db->execute_query("UPDATE ".TABLE_PREFIX."sites SET logo='' WHERE id=?",array($sid));

		unlink('../'.DATA_DIR."/site_logo/".$sid."/".$old_logo);
		rmdir('../'.DATA_DIR."/site_logo/".$sid);

		$this->flash($this->get_message('site logo deleted'), $this->make_url('dispatch/category_targeting/7/'.$sid,ADMIN_DIR));
		exit;
	}



	function delete_thumb_admin_action()
	{
		$db  = DAL::get_instance();
		$sid = intval($this->read_page_param(1));

		if(!CategoryHelper::get_site_exists($sid))
		{
			$this->flash($this->get_message('site invalid'), $this->make_url('dispatch/category_targeting/6',ADMIN_DIR),0);
			exit;
		}

		if(DEMO_MODE && $sid <= 10)
		{
			$this->flash($this->get_message('demo mode'), $this->make_url('dispatch/category_targeting/6',ADMIN_DIR),0);
			exit;
		}

		$old_logo = $db->read_single_column("SELECT thumbshot_image FROM ".TABLE_PREFIX."sites WHERE id=?",array($sid));

		$db->execute_query("UPDATE ".TABLE_PREFIX."sites SET thumbshot = 0,thumbshot_image = '' WHERE id=?",array($sid));

		unlink('../'.DATA_DIR."/site_logo/".$sid."/".$old_logo);
		rmdir('../'.DATA_DIR."/site_logo/".$sid);

		$this->flash($this->get_message('site thumb deleted'), $this->make_url('dispatch/category_targeting/7/'.$sid,ADMIN_DIR));
		exit;
	}



	function delete_admin_site_action()
	{
		$db= DAL::get_instance();

		$yesno=0;
		$subject='';
		$message='';
		if(isset($_POST['confirm']))
		{
			$confirmed=1;
			$sid=intval($this->read_post_param('sid'));
			$owner=$this->read_post_param('owner');
			$category=intval($this->read_post_param('category'));
			$status=$this->read_post_param('status');

			$featured=$this->read_post_param('featured');
			$hot=$this->read_post_param('hot');
			$search_by=$this->read_post_param('search_by');
			$search_text=$this->read_post_param('search_text');

			$page=$this->read_post_param('page');
			$yesno=intval($this->read_post_param('yesno'));
		}
		else
		{
			$confirmed=0;
			$sid=intval($this->read_page_param(1));
			$owner=$this->read_page_param(2);
			$category=intval($this->read_page_param(3));
			$status=$this->read_page_param(4);

			$featured=$this->read_page_param(5);
			$hot=$this->read_page_param(6);
			$search_by=$this->read_page_param(7);
			$search_text=$this->read_page_param(8);

			$page=$this->read_page_param(9);
		}



		if(!CategoryHelper::get_site_exists($sid))
		{
			$this->flash($this->get_message('site invalid'), $this->make_url('dispatch/category_targeting/6',ADMIN_DIR),0);
			exit;
		}

		if(DEMO_MODE && $sid <= 10)
		{
			$this->flash($this->get_message('demo mode'), $this->make_url('dispatch/category_targeting/6',ADMIN_DIR),0);
			exit;
		}

		$siteownerid=$db->read_single_column("SELECT pid FROM ".TABLE_PREFIX."sites WHERE id=?",array($sid));
		$sitename=$db->read_single_column("SELECT url FROM ".TABLE_PREFIX."sites WHERE id=?",array($sid));
		$adunitcount=$db->read_single_column("SELECT count(id) FROM ".TABLE_PREFIX."adunit WHERE sid=?",array($sid));


		$sponsored_enabled=$this->get_addon_status('sponsored_enabled');

		$mapcount=0;
		if($sponsored_enabled ==1 || $sponsored_enabled ==0)
		$mapcount=$db->read_single_column("SELECT count(id) FROM ".TABLE_PREFIX."sponsored_ad_mapping WHERE site=? AND (status=2 OR status=3)",array($sid));


		if($mapcount >0)
		$this->flash($this->get_message('sponsored mappings site exists'), $this->make_url('dispatch/category_targeting/6',ADMIN_DIR),0);




		if(($adunitcount ==0 && $siteownerid ==0) || $confirmed ==1)
		{
			$siteRow = $db->execute_query("select * from ".TABLE_PREFIX."sites where id=?",array($sid));
			while($siteData = $siteRow->fetch_assoc())
			{
				$siteID    = $siteData['id'];
				$siteLogo  = $siteData['logo'];
				$siteImage = $siteData['thumbshot_image'];


				if($siteLogo !='')
				unlink("../".DATA_DIR."/site_logo/".$siteID."/".$siteLogo);

				if($siteImage !='')
				unlink("../".DATA_DIR."/site_logo/".$siteID."/".$siteImage);
			}

			rmdir("../".DATA_DIR."/site_logo/".$sid."/");

			$db->execute_query("DELETE FROM ".TABLE_PREFIX."sites WHERE id=?",array($sid));



			if($adunitcount >0)
			{
				$slist=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."adunit WHERE sid=?",array($sid));

				$db->execute_query("DELETE FROM ".TABLE_PREFIX."adunit WHERE sid=?",array($sid));

			}

			if($sponsored_enabled ==1 || $sponsored_enabled ==0)
			{
				$db->execute_query("DELETE FROM ".TABLE_PREFIX."sponsored_ad_mapping WHERE site=? AND (status=-1 OR status=0 OR status=1)",array($sid));
			}

			if($yesno ==0 && $siteownerid >0)
			{
				$subject=$this->read_post_param('subject');
				$message=$this->read_post_param('message');

				if(DEMO_MODE)
				{
					$this->flash($this->get_message('demo mode'),$this->make_url('dispatch/category_targeting/6',ADMIN_DIR),0);
					exit;
				}

				$email=$db->read_single_column("select email from ".TABLE_PREFIX."users where id=?",array($siteownerid));

				if($subject =="" || $message =="")
				$this->set_notice('mandatory');
				else
				UtilityHelper::send_mail($email,$subject,$message);
			}

			$this->flash($this->get_message('site delete success'), $this->make_url('dispatch/category_targeting/6/'.$owner.'/'.$category.'/'.$status.'/'.$featured.'/'.$hot.'/'.$search_by.'/'.$search_text.'/'.$page,ADMIN_DIR));
			exit;

		}
		else
		{
			if($siteownerid >0)
			{
				$userdata=$db->execute_query("select * from ".TABLE_PREFIX."users where id=?",array($siteownerid));
				$userdata1=$userdata->fetch_assoc();
				$username=$userdata1['username'];

				$language_enabled=Configuration::get_instance()->read('language_enabled');


				$res=$db->execute_query("select * from ".TABLE_PREFIX."email_templates where id=18");
				$result1=$res->fetch_assoc();


				if($language_enabled ==1 && $userdata1['locale'] >0 && isset($result1[$userdata1['locale'].'_message']) && $result1[$userdata1['locale'].'_message'] !='')
				$message=$result1[$userdata1['locale'].'_message'];
				else
				$message=$result1['message'];

				if($language_enabled ==1 && $userdata1['locale'] >0 && isset($result1[$userdata1['locale'].'_subject']) && $result1[$userdata1['locale'].'_subject'] !='')
				$subject=$result1[$userdata1['locale'].'_subject'];
				else
				$subject=$result1['subject'];


				$subject=str_replace("{ADMARKETNAME}",Configuration::get_instance()->read('admarket_name'),$subject);
				$message=str_replace("{ADMARKETNAME}",Configuration::get_instance()->read('admarket_name'),$message);
				$message=str_replace("{USERNAME}",$username,$message);
				$message=str_replace("{SITENAME}",$sitename,$message);

			}
		}

		$this->set_variable("sid",$sid);
		$this->set_variable("owner",$owner);
		$this->set_variable("category",$category);
		$this->set_variable("status",$status);

		$this->set_variable("featured",$featured);
		$this->set_variable("hot",$hot);
		$this->set_variable("search_by",$search_by);
		$this->set_variable("search_text",$search_text);

		$this->set_variable("page",$page);

		$this->set_variable("siteownerid",$siteownerid);
		$this->set_variable('yesno', $yesno);
		$this->set_variable('subject', $subject);
		$this->set_variable('message', $message,0);

	}



	function statistics_admin_action()
	{
		$db= DAL::get_instance();

		if($_POST)
		{
			$duration = intval($this->read_post_param("duration"));
			$from_date=$this->read_post_param("from_date");
			$to_date=$this->read_post_param("to_date");
			$sortBy     = $this->read_post_param("sortBy");
			$orderBy    = $this->read_post_param("orderBy");
		}
		else
		{
			$duration = 1;
			$from_date='';
			$to_date='';
			$sortBy     = "impression";
			$orderBy    = "desc";
		}

		if($sortBy == "click")
		$sorting = 1;
		else if($sortBy == "conversion")
		$sorting = 4;
		else if($sortBy == "spend")
		$sorting = 2;
		else if($sortBy == "profit" || $sortBy == "adminprofit")
		$sorting = 3;
		else 
		$sorting = 0;

		if($orderBy == "asc")
		$ordering = 1;
		else 
		$ordering = 0;


		if($from_date != "" && $to_date == "")
		$to_date = date("d",time()).'/'.date("m",time()).'/'.date("Y",time());

		if($duration == 7 && ($from_date == "" || $to_date == ""))
		{
			$duration  = 1;

			$from_date = "";
			$to_date   = "";
		}

		$timePeriod[] = $duration;

		if($duration == 7)
		{
			$timePeriod[] = $from_date;
			$timePeriod[] = $to_date;
		}

		$this->set_variable("duration",$duration);
		$this->set_variable("from_date",$from_date);
		$this->set_variable("to_date",$to_date);
		$this->set_variable("sortBy",$sortBy);
		$this->set_variable("orderBy",$orderBy);

		//$timePeriod = array(1), $fromAdmin = 1, $pricing = -1 $pid=-1, $bid=0, $sid=0,  $report_type=0, $sorting = 0, $forMap = 0, $resultLimit = 0, $country = '', $ordering = 0, $publisher = '', $isPublisherData = -1
		$reportResult  = $this->get_publisher_statistics($timePeriod, 1, -1, 0, 0, 0, 7, $sorting, 0, 0, "", $ordering, "", 0);
		$this->set_array("reportResult",$reportResult);	
	}



	function statistics_publisher_action()
	{
		$db= DAL::get_instance();

		if($_POST)
		{
			$duration=intval($this->read_post_param("duration"));
			$publisher=intval($this->read_post_param("publisher"));
			$from_date=$this->read_post_param("from_date");
			$to_date=$this->read_post_param("to_date");
			$sortBy     = $this->read_post_param("sortBy");
			$orderBy    = $this->read_post_param("orderBy");
		}
		else
		{
			$duration=1;
			$publisher=-1;
			$from_date='';
			$to_date='';
			$sortBy     = "impression";
			$orderBy    = "desc";
		}
		if($sortBy == "click")
		$sorting = 1;
		else if($sortBy == "conversion")
		$sorting = 4;
		else if($sortBy == "spend")
		$sorting = 2;
		else if($sortBy == "profit" || $sortBy == "adminprofit")
		$sorting = 3;
		else 
		$sorting = 0;
		if($orderBy == "asc")
		$ordering = 1;
		else 
		$ordering = 0;

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

		$this->set_variable("from_date",$from_date);
		$this->set_variable("to_date",$to_date);
		$this->set_variable("duration",$duration);
		$this->set_variable("publisher",$publisher);
		$this->set_variable("sortBy",$sortBy);
		$this->set_variable("orderBy",$orderBy);


		//$timePeriod = array(1), $fromAdmin = 1, $pricing = -1 $pid=-1, $bid=0, $sid=0,  $report_type=0, $sorting = 0, $forMap = 0, $resultLimit = 0, $country = '', $ordering = 0, $publisher = '', $isPublisherData = -1
		$reportResult  = $this->get_publisher_statistics($timePeriod, 1, -1, $publisher, 0, 0, 7, $sorting, 0, 0, "", $ordering, "", 1);
		$this->set_array("reportResult",$reportResult);	



		$sql1="select id,username from ".TABLE_PREFIX."users where pub_status=1 order by id ASC";
		$res1=$db->execute_query($sql1);
		$this->set_result("res1",$res1);
	}

	function statistics_publisher_profile_action()
	{
		$this->disable_notice_area();
		$db= DAL::get_instance();

		if($_POST)
		{
			$duration=intval($this->read_post_param("duration"));
			$pub=intval($this->read_post_param("pub"));
			$from_date=$this->read_post_param("from_date");
			$to_date=$this->read_post_param("to_date");
		}
		else
		{
			$duration=1;
			$pub=intval($this->read_page_param(1));
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

		$this->set_variable("from_date",$from_date);
		$this->set_variable("to_date",$to_date);
		if($duration == 0)
		$duration=1;

		$timePeriod[] = $duration;

		if($duration == 7)
		{
			$timePeriod[] = $from_date;
			$timePeriod[] = $to_date;
		}

		$pub_string=" and pid='$pub' ";

		$this->set_variable("duration",$duration);
		$this->set_variable("pub",$pub);
		$pagination = new Pagination("SELECT * FROM ".TABLE_PREFIX."sites WHERE (status=1 OR status=0) ".$pub_string." ORDER BY id");
		$res=$pagination->get_result();
		$adResult = array();
		while($resultData = $res->fetch_assoc())
		{
			$sid=$resultData['id'];
			$pid=$resultData['pid'];

			//$timePeriod = array(1), $fromAdmin = 1, $pricing = -1 $pid=-1, $bid=0, $sid=0,  $report_type=0, $country = ''
			$dbResult = $this->get_publisher_statistics($timePeriod, 1, -1, $pid,0,$sid);
			$resultData['reportData'] = $dbResult;
			$adResult[$sid] = $resultData;
		}
		if(count($adResult) > 0)
		$adResult['heading'] = $dbResult['heading'];
		$this->set_array("adResult",$adResult);
		$this->set_result("res",$res);
		$this->set_variable("pagination",$pagination->links(),0);
		$pg=$pagination->get_page_number();
		$this->set_variable("page","page-".$pg);
	}

	function detail_statistics_admin_action()
	{
		$db= DAL::get_instance();
		if($_POST)
		{
			$duration=$this->read_post_param("duration");
			$sid=intval($this->read_post_param("sid"));
			$tab=intval($this->read_post_param("tab"));
			$from_date=$this->read_post_param("from_date");
			$to_date=$this->read_post_param("to_date");

		}
		else
		{
			$sid=intval($this->read_page_param(1));
			$duration=1;
			$tab=0;
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

		$this->set_variable("from_date",$from_date);
		$this->set_variable("to_date",$to_date);

		if(!CategoryHelper::get_site_exists($sid))
		{
			$this->flash($this->get_message('site invalid'), $this->make_url('dispatch/category_targeting/6',ADMIN_DIR),0);
			exit;
		}


		$row_data=$db->execute_query("select * from ".TABLE_PREFIX."sites where id=?",array($sid));
		$row_content=$row_data->fetch_assoc();

		$uid=$row_content['pid'];
		$sitename=$row_content['url'];


		$uid=intval($uid);

		if($uid >0)
		$username=$db->read_single_column("select username from ".TABLE_PREFIX."users where id=?",array($uid));
		else
		$username="Admin";




		if($duration=="" || $duration==0)
		$duration=1;

		$timePeriod[] = $duration;

		if($duration == 7)
		{
			$timePeriod[] = $from_date;
			$timePeriod[] = $to_date;
		}

		//$timePeriod = array(1), $fromAdmin = 1, $pricing = -1 $pid=-1, $bid=0, $sid=0,  $report_type=0, $country = ''
		$dbResult = $this->get_publisher_statistics($timePeriod, 1, -1,$uid,0, $sid);
		$this->set_array("reportResult",$dbResult);

		$dbResultTimeperiod= $this->get_publisher_timeperiod_statistics($timePeriod, 1, -1, $uid,0,$sid);
		$this->set_array("reportResultTimeperiod",$dbResultTimeperiod);




		$this->set_variable("username",$username);
		$this->set_variable("duration",$duration);
		$this->set_variable("sitename",$sitename);
		$this->set_variable("tab",$tab);
		$this->set_variable("sid",$sid);
		$this->set_variable("uid",$uid);
	}




	///////////////////////////////////////////////


	function add_site_action()
	{
		$db= DAL::get_instance();
		$pid=$this->read_cookie_param(COOKIE_LOGINID);


		$category			 = 0;
		$marketplace_display = 0;
		$url="";
		$protocol="http://";
		$title='';
		$description='';
		$keywords	 = '';
		$monthly_impressions_from	 = 1;
		$monthly_impressions		   = 0;

		$api_key               = "";
		$app_id                = "";
		$locations             = "";
		$push_ad_interval      = 0;
		$push_service_enabled  = 0;

		$cpp_enabled           = $this->get_addon_status('cpp_enabled');

		$multiCategorySupport  = Configuration::get_instance()->read('support_multiple_category_for_website');
		$this->set_variable("multiCategorySupport", $multiCategorySupport);


		if($multiCategorySupport == 1)
		{
				$rowCategory           = $db->execute_query("SELECT * FROM ".TABLE_PREFIX."categories WHERE pid = 0 ORDER BY name");
				$this->set_result('rowCategory',$rowCategory);
		}


		if($_POST)
		{
			$category=$this->read_post_param('category');
			$url=$this->read_post_param('url');
			$protocol=$this->read_post_param('protocol');
			$keywords=$this->read_post_param('keywords');
			$marketplace_display = $this->read_post_param('marketplace_display');
			$monthly_impressions_from = intval($this->read_post_param('monthly_impressions_from'));
			$monthly_impressions      = intval($this->read_post_param('monthly_impressions'));

			if($monthly_impressions_from == 0 && $monthly_impressions == 0)
			$monthly_impressions_from = 1;

			if($monthly_impressions_from == 1)
			$monthly_impressions = 0;


			$title=$this->read_post_param('title');
			$description=$this->read_post_param('description');

			$logo      = $_FILES["logo"]["name"];
			$thumblogo = $_FILES["thumblogo"]["name"];

			$push_service_enabled   = intval($this->read_post_param('push_service_enabled'));

			if($cpp_enabled == 1 && $push_service_enabled == 1)
			{
					$min_interval_push_cron = Configuration::get_instance()->read('min_interval_push_cron');

					$api_key          = $this->read_post_param('api_key');
					$app_id           = $this->read_post_param('appid');
					$locations        = $this->read_post_param('a_loc');
					$push_ad_interval = intval($this->read_post_param('push_ad_interval'));

					if($push_ad_interval <= 0 || $min_interval_push_cron > $push_ad_interval)
					$push_ad_interval = $min_interval_push_cron;


		  }

			$extensionname='';
			if($logo !='')
			{
				$extension=explode(".",$logo);
				$extensionname=strtolower($extension[count($extension)-1]);

				$logo=time().'.'.$extensionname;
			}

			$thumbextensionname='';
			if($thumblogo !='')
			{
				$thumbextension=explode(".",$thumblogo);
				$thumbextensionname=strtolower($thumbextension[count($thumbextension)-1]);

				$thumblogo=time().'-thumb.'.$thumbextensionname;
			}

			$url=str_ireplace("http://","",$url);
			$url=str_ireplace("https://","",$url);
			$url=str_ireplace("www.","",$url);


			$enable_website_ownership_verification = Configuration::get_instance()->read('enable_website_ownership_verification');

			if($enable_website_ownership_verification == 1)
			$site_default_status = -3;
			else
			$site_default_status=Configuration::get_instance()->read('site_default_status');

			$count = $db->read_single_column("select count(id) from ".TABLE_PREFIX."sites where url=?",array($url));
			$IABListArray = array();

			if($multiCategorySupport == 1)
			{
					$categoryArray     = explode(",",$category);
					$categoryArrayTemp = array();

					foreach($categoryArray as $catKey => $catValue)
					{
							$catValue = trim($catValue);

							if($catValue != "")
							{
									if(CategoryHelper::get_category_exists($catValue))
									{
									$categoryArrayTemp[] = $catValue;
											$IABCategory         = $db->read_single_column("SELECT iab_id FROM ".TABLE_PREFIX."categories WHERE id = ?", array($catValue));
											if($IABCategory != "")
											$IABListArray[]      = $IABCategory;
									}
							}
					}

					$categoryCount = count($categoryArrayTemp);

					if($categoryCount > 0)
					$category = ",".implode("," , $categoryArrayTemp).",";
			}
			else
			{
					if(intval($category) > 0)
					{
					$categoryCount = 1;
							$IABCategory         = $db->read_single_column("SELECT iab_id FROM ".TABLE_PREFIX."categories WHERE id = ?", array($category));
							if($IABCategory != "")
							$IABListArray[]      = $IABCategory;
					}
			}

			$IABListString = "";

			if(count($IABListArray) > 0)
			$IABListString = implode("," , $IABListArray);
			$locations                    = substr($locations, 0, -1);
			$push_ads_country_array       = explode(',', $locations);
			$push_ads_country_array_count = count($push_ads_country_array);

			$country_code_string          = "";

			if($push_ads_country_array_count > 0)
			{
					foreach($push_ads_country_array as $key => $value)
					{
							if($country_code_string != "")
							$country_code_string.= " OR ";

							$country_code_string.= " code = '".$value."' ";
					}

					if($country_code_string != "")
					$country_code_string = " WHERE (".$country_code_string.") ";

					$result1 = $db->execute_query("select code,name from ".TABLE_PREFIX."countries ".$country_code_string." ORDER BY name");
					$this->set_result("result1",$result1);
			}


			if($title == "")
			$this->set_notice("mandatory");
			else if($categoryCount == 0)
			$this->set_notice("please select a category");
			else if(!UtilityHelper::is_valid_domain($url))
			$this->set_notice("invalid url");
			else if($count >0)
			$this->set_notice("site name already exists");
			else if($logo !='' && $extensionname != "gif" && $extensionname != "jpeg" && $extensionname != "pjpeg" && $extensionname != "png" && $extensionname != "jpg" && $extensionname != "svg")
			$this->set_notice("image not supported");
			else if($logo !='' && $_FILES["logo"]["error"] > 0)
			$this->set_notice("error occurred in image uploading");
			else if($thumblogo !='' && $thumbextensionname != "gif" && $thumbextensionname != "jpeg" && $thumbextensionname != "pjpeg" && $thumbextensionname != "png" && $thumbextensionname != "jpg" && $thumbextensionname != "svg")
			$this->set_notice("image not supported");
			else if($thumblogo !='' && $_FILES["thumblogo"]["error"] > 0)
			$this->set_notice("error occurred in image uploading");
			else if($cpp_enabled == 1 && $push_service_enabled == 1 && $api_key == '')
			$this->set_notice("please enter api key");
			else if($cpp_enabled == 1 && $push_service_enabled == 1 && $app_id == '')
			$this->set_notice("please enter appid");
			else
			{
				$alexa_rank = 0;

				$sponsored_enabled=$this->get_addon_status('sponsored_enabled');

				$insertArray = array($pid,$category,$url,$site_default_status,$title,$description,time(),$alexa_rank,$protocol,$marketplace_display,$keywords,$IABListString);

				$appendString  = "";
				$appendString1 = "";


				if($sponsored_enabled == 1)
				{
					$appendString  = ",impression_source,monthly_impressions";
					$appendString1 = ",?,?";

					$insertArray[] = $monthly_impressions_from;
					$insertArray[] = $monthly_impressions;
				}

				if($cpp_enabled == 1 && $push_service_enabled == 1)
				{
						$appendString.= ",push_ads_apikey,push_ads_appid,push_ads_country_target,push_notification_service_enabled,push_adserve_interval";
						$appendString1.= ",?,?,?,?,?";

						$insertArray[] = $api_key;
						$insertArray[] = $app_id;
						$insertArray[] = $locations;
						$insertArray[] = $push_service_enabled;
						$insertArray[] = $push_ad_interval;
				}
				else if($cpp_enabled == 1)
				{
						$appendString.= ",push_notification_service_enabled";
						$appendString1.= ",?";

						$insertArray[] = $push_service_enabled;
				}


				$res = $db->execute_query("INSERT INTO ".TABLE_PREFIX."sites (pid,catid,url,status,title,description,time,alexa_rank,protocol,marketplace_display,keywords,iab_category_id".$appendString.") values (?,?,?,?,?,?,?,?,?,?,?,?".$appendString1.")",$insertArray);

				$lsid = $res->get_last_id();

				if($res->error == "" && $site_default_status == -3 && $enable_website_ownership_verification == 1)
				{
						$admarketName    = Configuration::get_instance()->read('admarket_name');
						$verificationKey = md5($lsid.$url.$admarketName);

						$db->execute_query("UPDATE ".TABLE_PREFIX."sites SET verification_key = ? WHERE id = ?",array($verificationKey,$lsid));
				}

				if($res->error == "" && $cpp_enabled == 1 && $push_service_enabled == 1)
				{
						$max_adunit_id = $db->read_single_column("SELECT MAX(id)  FROM ".TABLE_PREFIX."adunit");
						$max_adunit_id++;
						$db->execute_query("INSERT INTO ".TABLE_PREFIX."adunit (name,pubid,status,display_type,adcode_type,sid) values (?,?,?,?,?,?)",array('CPP_'.$max_adunit_id,$pid,1,18,18,$lsid));
				}

				if($logo !='')
				{
						if(!is_dir(DATA_DIR.'/site_logo'))
						mkdir(DATA_DIR.'/site_logo',0777);

						mkdir(DATA_DIR.'/site_logo/'.$lsid,0777);

						if($res->error =="")
						{
								if(move_uploaded_file($_FILES["logo"]["tmp_name"],DATA_DIR."/site_logo/".$lsid."/".$logo))
								{
									$height=70;
									$width=70;

									$image=new ImageHelper(DATA_DIR."/site_logo/".$lsid."/".$logo);
									$image->resize($width,$height,DATA_DIR."/site_logo/".$lsid."/".$logo);

									$db->execute_query("UPDATE ".TABLE_PREFIX."sites SET logo=? WHERE id=?",array($logo,$lsid));
								}
						}
				}


				if($thumblogo !='')
				{
						if(!is_dir(DATA_DIR.'/site_logo'))
						mkdir(DATA_DIR.'/site_logo',0777);

						mkdir(DATA_DIR.'/site_logo/'.$lsid,0777);

						if($res->error =="")
						{
								if(move_uploaded_file($_FILES["thumblogo"]["tmp_name"],DATA_DIR."/site_logo/".$lsid."/".$thumblogo))
								{
										$height = 200;
										$width  = 240;

										$image=new ImageHelper(DATA_DIR."/site_logo/".$lsid."/".$thumblogo);
										$image->resize($width,$height,DATA_DIR."/site_logo/".$lsid."/".$thumblogo);

										$db->execute_query("UPDATE ".TABLE_PREFIX."sites SET thumbshot = 1,thumbshot_image = ? WHERE id=?",array($thumblogo,$lsid));
								}
						}
				}

				if($site_default_status == -3 && $enable_website_ownership_verification == 1)
				$this->flash($this->get_message('site add success'), $this->make_url('dispatch/category_targeting/6/0/-3/'.$lsid));
				else
				$this->flash($this->get_message('site add success'), $this->make_url('dispatch/category_targeting/6'));

				exit;
			}
		}

		$result = $db->execute_query("select code,name from ".TABLE_PREFIX."countries WHERE code!='A1' and code!='A2' and code!='AP' and code!='EU' ORDER BY name");
		$this->set_result("result",$result);

		$this->set_variable("url",$url);
		$this->set_variable("protocol",$protocol);
		$this->set_variable("category",$category);
		$this->set_variable("title",$title);

		$this->set_variable("api_key",$api_key);
		$this->set_variable("app_id",$app_id);
		$this->set_variable("push_ad_interval",$push_ad_interval);
		$this->set_variable("push_service_enabled",$push_service_enabled);


		$this->set_variable("description",$description);
		$this->set_variable("marketplace_display",$marketplace_display);
		$this->set_variable("keywords",$keywords);

		$this->set_variable("monthly_impressions_from",$monthly_impressions_from);
		$this->set_variable("monthly_impressions",$monthly_impressions);


		if(isset($_COOKIE['my_locale']))
		$localname=$_COOKIE['my_locale'];
		else
		$localname=DEFAULT_LOCALE;


		$direction=$db->read_single_column("SELECT direction FROM ".TABLE_PREFIX."locale WHERE name=?",array($localname));
		$direction=intval($direction);

		$this->set_variable('direction',$direction);
	}
	function manage_site_action()
	{
		$db= DAL::get_instance();
		$pid=intval($this->read_cookie_param(COOKIE_LOGINID));


		$mngr_str='';
		$usr_str1='';
		$usr_str2='';

		if($this->get_addon_status('subadmin_enabled') ==1)
		{
			$admintype=$this->read_cookie_param(COOKIE_ADMIN_TYPE);
			$adminid=$this->read_cookie_param(COOKIE_ADMIN_LOGINID);

			if($admintype==2 || $admintype==3)
			{
				$usr_str=$this->get_users_under_manager($admintype,$adminid);
				if($usr_str!='')
				$usr_str1=" and uid in (".$usr_str.")";
				else
				$usr_str1=" and uid =-2 ";



				if($admintype ==3)
				{
					$mngr_str=" and pub_managerid=".$adminid;

					if($usr_str!='')
					$usr_str2=" and pid in (".$usr_str.")";
					else
					$usr_str2=" and pid =-2 ";
				}

				elseif ($admintype ==2)
				{
					$mngr_str=" and adv_managerid=".$adminid;

					if($usr_str!='')
					$usr_str2=" and pid in (".$usr_str.")";
					else
					$usr_str2=" and pid =-2 ";
				}

			}
		}

		if($_POST)
		{
			$category=intval($this->read_post_param('category'));
			$status=intval($this->read_post_param('status'));
			$siteID=0;
		}
		else
		{
			$category=$this->read_page_param(1);
			$status=$this->read_page_param(2);
			$siteID=intval($this->read_page_param(3));

			$exp=explode("-",$category);
			if($exp[0]=="page")
			$category=0;

			$exp=explode("-",$status);
			if($exp[0]=="page")
			$status=-2;

			$exp=explode("-",$siteID);
			if($exp[0]=="page")
			$siteID=0;
		}


		if($status === "")
		$status=-2;

		$string=' WHERE pid='.$pid.' ';

		if($category > 0)
		$string.=' AND ( catid LIKE "%,'.intval($category).',%" OR catid = '.intval($category).') ';

		if($status != -2)
		$string.=' AND status='.intval($status).' ';



		$this->set_variable("category",$category);
		$this->set_variable("status",$status);
		$this->set_variable("siteID",$siteID);


		$pagination = new Pagination("SELECT * FROM ".TABLE_PREFIX."sites ".$string." ".$usr_str2." ORDER BY id DESC");
		$res=$pagination->get_result();
		$this->set_result("res",$res);
		$this->set_variable("pagination",$pagination->links(),0);

		$pg=$pagination->get_page_number();
		$this->set_variable("page","page-".$pg);
	}

	function delete_logo_action()
	{
		$db= DAL::get_instance();
		$pid=$this->read_cookie_param(COOKIE_LOGINID);
		$sid=intval($this->read_page_param(1));

		if(!CategoryHelper::get_site_exists($sid,$pid))
		{
			$this->flash($this->get_message('site invalid'), $this->make_url('dispatch/category_targeting/6',BASE),0);
			exit;
		}

		if(DEMO_MODE && $sid <= 10)
		{
			$this->flash($this->get_message('demo mode'), $this->make_url('dispatch/category_targeting/6',BASE),0);
			exit;
		}


		$old_logo=$db->read_single_column("SELECT logo FROM ".TABLE_PREFIX."sites WHERE id=?",array($sid));

		$db->execute_query("UPDATE ".TABLE_PREFIX."sites SET logo='' WHERE id=?",array($sid));

		unlink(DATA_DIR."/site_logo/".$sid."/".$old_logo);
		rmdir(DATA_DIR."/site_logo/".$sid);

		$this->flash($this->get_message('site logo deleted'), $this->make_url('dispatch/category_targeting/7/'.$sid,BASE));
		exit;
	}


	function delete_thumb_action()
	{
		$db  = DAL::get_instance();
		$pid = $this->read_cookie_param(COOKIE_LOGINID);
		$sid = intval($this->read_page_param(1));

		if(!CategoryHelper::get_site_exists($sid,$pid))
		{
			$this->flash($this->get_message('site invalid'), $this->make_url('dispatch/category_targeting/6',BASE),0);
			exit;
		}

		if(DEMO_MODE && $sid <= 10)
		{
			$this->flash($this->get_message('demo mode'), $this->make_url('dispatch/category_targeting/6',BASE),0);
			exit;
		}

		$old_logo = $db->read_single_column("SELECT thumbshot_image FROM ".TABLE_PREFIX."sites WHERE id=?",array($sid));

		$db->execute_query("UPDATE ".TABLE_PREFIX."sites SET thumbshot = 0,thumbshot_image = '' WHERE id=?",array($sid));

		unlink(DATA_DIR."/site_logo/".$sid."/".$old_logo);
		rmdir(DATA_DIR."/site_logo/".$sid);

		$this->flash($this->get_message('site thumb deleted'), $this->make_url('dispatch/category_targeting/7/'.$sid,BASE));
		exit;
	}



	function edit_site_action()
	{
			$db  = DAL::get_instance();
			$pid = $this->read_cookie_param(COOKIE_LOGINID);

			$cpp_enabled       = $this->get_addon_status('cpp_enabled');
			$sponsored_enabled = $this->get_addon_status('sponsored_enabled');

			$multiCategorySupport  = Configuration::get_instance()->read('support_multiple_category_for_website');
			$this->set_variable("multiCategorySupport", $multiCategorySupport);


			if($multiCategorySupport == 1)
			{
					$rowCategory           = $db->execute_query("SELECT * FROM ".TABLE_PREFIX."categories WHERE pid = 0 ORDER BY name");
					$this->set_result('rowCategory',$rowCategory);
			}

			if($_POST)
			$sid=$this->read_post_param('sid');
			else
			{
				$sid=intval($this->read_page_param(1));
				$urlcategory=intval($this->read_page_param(2));
				$status=$this->read_page_param(3);
				$page=$this->read_page_param(4);
			}

			if(!CategoryHelper::get_site_exists($sid,$pid))
			{
				$this->flash($this->get_message('site invalid'), $this->make_url('dispatch/category_targeting/6',BASE),0);
				exit;
			}


			$logo      = '';
			$thumblogo = '';
			$thumbshot = 0;

			$res = $db->execute_query("select * from ".TABLE_PREFIX."sites where id=?",array($sid));
			$row = $res->fetch_assoc();


			$logo      = $row['logo'];
			$thumblogo = $row['thumbshot_image'];
			$thumbshot = $row['thumbshot'];
			$siteOldUrl 	= $row['url'];
			$siteOldStatus 	= $row['status'];


			/*
			if($siteOldStatus == 0) //Blocked site don't delete
			{
					$this->flash($this->get_message('invalid operation'), $this->make_url('dispatch/category_targeting/6',BASE),0);
					exit;
			}
			*/

			$push_ads_apikey      = "";
			$push_ads_appid       = "";
			$locations            = "";
			$push_ad_interval     = 0;
			$push_service_enabled = 0;


			if($_POST)
			{
					if(DEMO_MODE && $sid <= 10)
					{
						$this->flash($this->get_message('demo mode'), $this->make_url('dispatch/category_targeting/6',BASE),0);
						exit;
					}


		    	$urlcategory=$this->read_post_param('urlcategory');
					$category=$this->read_post_param('category');
					$url=$this->read_post_param('url');
					$protocol=$this->read_post_param('protocol');
					$status=$this->read_post_param('status');
					$page=$this->read_post_param('page');
					$keywords=$this->read_post_param('keywords');
					$marketplace_display = $this->read_post_param('marketplace_display');
					$monthly_impressions_from = intval($this->read_post_param('monthly_impressions_from'));
					$monthly_impressions      = intval($this->read_post_param('monthly_impressions'));

					if($monthly_impressions_from == 0 && $monthly_impressions == 0)
					$monthly_impressions_from = 1;

					if($monthly_impressions_from == 1)
					$monthly_impressions = 0;

					$title=$this->read_post_param('title');
					$description=$this->read_post_param('description');

					$logo      = $_FILES["logo"]["name"];
					$thumblogo = $_FILES["thumblogo"]["name"];


				$push_service_enabled = intval($this->read_post_param('push_service_enabled'));

				if($cpp_enabled == 1 && $push_service_enabled == 1)
				{
						$min_interval_push_cron = Configuration::get_instance()->read('min_interval_push_cron');

						$push_ads_apikey  = $this->read_post_param('api_key');
						$push_ads_appid   = $this->read_post_param('appid');
						$locations        = $this->read_post_param('a_loc');
						$push_ad_interval = intval($this->read_post_param('push_ad_interval'));

						if($push_ad_interval <= 0 || $min_interval_push_cron > $push_ad_interval)
						$push_ad_interval = $min_interval_push_cron;
			  }

				$extensionname='';
				if($logo !='')
				{
					$extension=explode(".",$logo);
					$extensionname=strtolower($extension[count($extension)-1]);

					$logo=time().'.'.$extensionname;
				}

				$thumbextensionname='';
				if($thumblogo !='')
				{
					$thumbextension=explode(".",$thumblogo);
					$thumbextensionname=strtolower($thumbextension[count($thumbextension)-1]);

					$thumblogo=time().'-thumb.'.$thumbextensionname;
				}



				$url=str_ireplace("http://","",$url);
				$url=str_ireplace("https://","",$url);
				$url=str_ireplace("www.","",$url);

				$enable_website_ownership_verification = Configuration::get_instance()->read('enable_website_ownership_verification');

				if($enable_website_ownership_verification == 1 && ($siteOldUrl != $url || $siteOldStatus == -3))
				$site_default_status = -3;
				else
				$site_default_status=Configuration::get_instance()->read('site_default_status');


				$count=$db->read_single_column("select count(id) from ".TABLE_PREFIX."sites where url=? AND id <> ?",array($url,$sid));
				$IABListArray = array();

				if($multiCategorySupport == 1)
				{
						$categoryArray     = explode(",",$category);
						$categoryArrayTemp = array();

						foreach($categoryArray as $catKey => $catValue)
						{
								$catValue = trim($catValue);

								if($catValue != "")
								{
										if(CategoryHelper::get_category_exists($catValue))
									{
										$categoryArrayTemp[] = $catValue;
											$IABCategory         = $db->read_single_column("SELECT iab_id FROM ".TABLE_PREFIX."categories WHERE id = ?", array($catValue));
											if($IABCategory != "")
											$IABListArray[]      = $IABCategory;
									}
								}
						}

						$categoryCount = count($categoryArrayTemp);

						if($categoryCount > 0)
						$category = ",".implode("," , $categoryArrayTemp).",";
				}
				else
				{
						if(intval($category) > 0)
					{
						$categoryCount = 1;
							$IABCategory         = $db->read_single_column("SELECT iab_id FROM ".TABLE_PREFIX."categories WHERE id = ?", array($category));
							if($IABCategory != "")
							$IABListArray[]      = $IABCategory;
				}
			}

			$IABListString = "";

			if(count($IABListArray) > 0)
			$IABListString = implode("," , $IABListArray);
				$locations                    = substr($locations, 0, -1);

				$push_ads_country_array       = explode(',', $locations);
				$push_ads_country_array_count = count($push_ads_country_array);

				$country_code_left   = "";
				$country_code_string = "";

				if($push_ads_country_array_count > 0)
				{
						foreach($push_ads_country_array as $key => $value)
						{
								if($country_code_left != "")
								$country_code_left.= " AND ";

								$country_code_left.= " code != '".$value."' ";
						}

						if($country_code_left != "")
						$country_code_left = " ".$country_code_left." AND ";
				}

				$result = $db->execute_query("select code,name from ".TABLE_PREFIX."countries where ".$country_code_left." code!='A1' and code!='A2' and code!='AP' and code!='EU' ORDER BY name");
				$this->set_result("result",$result);


				if($push_ads_country_array_count > 0)
				{
						foreach($push_ads_country_array as $key => $value)
						{
								if($country_code_string != "")
								$country_code_string.= " OR ";

								$country_code_string.=" code = '".$value."' ";
						}

						if($country_code_string != "")
						$country_code_string = " WHERE (".$country_code_string.") ";

						$result1 = $db->execute_query("select code,name from ".TABLE_PREFIX."countries ".$country_code_string." ORDER BY name");
						$this->set_result("result1",$result1);
				}

				if($title == "")
				$this->set_notice("mandatory");
				else if($categoryCount == 0)
				$this->set_notice("please select a category");
				else if(!UtilityHelper::is_valid_domain($url))
				$this->set_notice("invalid url");
				else if($count >0)
				$this->set_notice("site name already exists");
				else if($logo !='' && $extensionname != "gif" && $extensionname != "jpeg" && $extensionname != "pjpeg" && $extensionname != "png" && $extensionname != "jpg" && $extensionname != "svg")
				$this->set_notice("image not supported");
				else if($logo !='' && $_FILES["logo"]["error"] > 0)
				$this->set_notice("error occurred in image uploading");
				else if($thumblogo !='' && $thumbextensionname != "gif" && $thumbextensionname != "jpeg" && $thumbextensionname != "pjpeg" && $thumbextensionname != "png" && $thumbextensionname != "jpg" && $thumbextensionname != "svg")
				$this->set_notice("image not supported");
				else if($thumblogo !='' && $_FILES["thumblogo"]["error"] > 0)
				$this->set_notice("error occurred in image uploading");
				else if($cpp_enabled == 1 && $push_service_enabled == 1 && $push_ads_apikey == '')
				$this->set_notice("please enter api key");
				else if($cpp_enabled == 1 && $push_service_enabled == 1 && $push_ads_appid == '')
				$this->set_notice("please enter appid");
				else
				{
						$alexa_rank  = 0;

						$insertArray = array($url,$category,$site_default_status,$title,$description,$alexa_rank,$protocol,$marketplace_display,$keywords,$IABListString);

						$appendString  = "";

						if($sponsored_enabled == 1)
						{
								$appendString  = ",impression_source=?,monthly_impressions=?";

								$insertArray[] = $monthly_impressions_from;
								$insertArray[] = $monthly_impressions;
						}

						if($cpp_enabled == 1 && $push_service_enabled == 1)
						{
								$appendString.= ", push_ads_apikey = ?, push_ads_appid = ?, push_ads_country_target = ?, push_notification_service_enabled = ?, push_adserve_interval = ? ";

								$insertArray[] = $push_ads_apikey;
								$insertArray[] = $push_ads_appid;
								$insertArray[] = $locations;
								$insertArray[] = $push_service_enabled;
								$insertArray[] = $push_ad_interval;
						}
						else if($cpp_enabled == 1)
						{
								$appendString.= ", push_notification_service_enabled = ? ";

								$insertArray[] = $push_service_enabled;
						}

						$insertArray[] = $sid;

						$res = $db->execute_query("UPDATE ".TABLE_PREFIX."sites SET url=?,catid=?,status=?,title=?,description=?,alexa_rank=?,protocol=?,marketplace_display=?,keywords=?,iab_category_id=?".$appendString." WHERE id=?",$insertArray);

						if($res->error == "" && $site_default_status == -3 && $enable_website_ownership_verification == 1)
						{
								$admarketName    = Configuration::get_instance()->read('admarket_name');
								$verificationKey = md5($sid.$url.$admarketName);

								$db->execute_query("UPDATE ".TABLE_PREFIX."sites SET verification_key = ? WHERE id = ?",array($verificationKey,$sid));
						}


						if($res->error == "" && $cpp_enabled == 1 && $push_service_enabled == 1)
						{
								$pushAdcodeID = $db->read_single_column("SELECT id FROM ".TABLE_PREFIX."adunit WHERE sid = ? AND display_type = ?",array($sid,18));

								if(intval($pushAdcodeID) == 0)
								{
										$max_adunit_id=$db->read_single_column("SELECT MAX(id)  FROM ".TABLE_PREFIX."adunit");
										$max_adunit_id++;
										$db->execute_query("INSERT INTO ".TABLE_PREFIX."adunit (name,pubid,status,display_type,adcode_type,sid) values (?,?,?,?,?,?)",array('CPP_'.$max_adunit_id,$pid,1,18,18,$sid));
								}
						}


						if($logo !='')
						{
							if(!is_dir(DATA_DIR.'/site_logo'))
							mkdir(DATA_DIR.'/site_logo',0777);

							if(!is_dir(DATA_DIR.'/site_logo/'.$sid))
							mkdir(DATA_DIR.'/site_logo/'.$sid,0777);

							if($res->error =="")
							{
								if(move_uploaded_file($_FILES["logo"]["tmp_name"],DATA_DIR."/site_logo/".$sid."/".$logo))
								{
									$height=70;
									$width=70;

									$image=new ImageHelper(DATA_DIR."/site_logo/".$sid."/".$logo);
									$image->resize($width,$height,DATA_DIR."/site_logo/".$sid."/".$logo);

									$oldlogo=$db->read_single_column("SELECT logo FROM ".TABLE_PREFIX."sites where id=?",array($sid));

									$db->execute_query("UPDATE ".TABLE_PREFIX."sites SET logo=? WHERE id=?",array($logo,$sid));

									unlink(DATA_DIR."/site_logo/".$sid."/".$oldlogo);
								}
							}
						}


						if($thumblogo !='')
						{
							if(!is_dir(DATA_DIR.'/site_logo'))
							mkdir(DATA_DIR.'/site_logo',0777);

							if(!is_dir(DATA_DIR.'/site_logo/'.$sid))
							mkdir(DATA_DIR.'/site_logo/'.$sid,0777);

							if($res->error =="")
							{

								if(move_uploaded_file($_FILES["thumblogo"]["tmp_name"],DATA_DIR."/site_logo/".$sid."/".$thumblogo))
								{
									$height = 200;
									$width  = 240;

									$image=new ImageHelper(DATA_DIR."/site_logo/".$sid."/".$thumblogo);
									$image->resize($width,$height,DATA_DIR."/site_logo/".$sid."/".$thumblogo);

									$oldlogo=$db->read_single_column("SELECT thumbshot_image FROM ".TABLE_PREFIX."sites where id=?",array($sid));

									$db->execute_query("UPDATE ".TABLE_PREFIX."sites SET thumbshot = 1,thumbshot_image=? WHERE id=?",array($thumblogo,$sid));

									unlink(DATA_DIR."/site_logo/".$sid."/".$oldlogo);
								}
							}
						}

						if($site_default_status == -3 && $enable_website_ownership_verification == 1)
						$this->flash($this->get_message('site edit success'), $this->make_url('dispatch/category_targeting/6/'.$urlcategory.'/-3/'.$sid.'/'.$page));
						else
						$this->flash($this->get_message('site edit success'), $this->make_url('dispatch/category_targeting/6/'.$urlcategory.'/'.$status.'/0/'.$page));

						exit;
				}
			}
			else
			{
					$res=$db->execute_query("select * from ".TABLE_PREFIX."sites where id=?",array($sid));
					$row=$res->fetch_assoc();

					$sid=$row['id'];
					$category=$row['catid'];
					$url=$row['url'];
					$protocol=$row['protocol'];
					$title=$row['title'];

					$description=$row['description'];
					$logo      = $row['logo'];
					$thumblogo = $row['thumbshot_image'];
					$thumbshot = $row['thumbshot'];
					$marketplace_display = $row['marketplace_display'];
					$keywords  = $row['keywords'];

					if($sponsored_enabled == 1)
					{
							$monthly_impressions_from = intval($row['impression_source']);
							$monthly_impressions      = intval($row['monthly_impressions']);
					}

					if($cpp_enabled == 1)
					{
							$push_service_enabled    = $row['push_notification_service_enabled'];
							$push_ads_country_string = $row['push_ads_country_target'];
							$push_ads_apikey         = $row['push_ads_apikey'];
							$push_ads_appid          = $row['push_ads_appid'];
							$push_ad_interval        = $row['push_adserve_interval'];

							$push_ads_country_array       = explode(',', $push_ads_country_string);
							$push_ads_country_array_count = count($push_ads_country_array);

							$country_code_left   = "";
							$country_code_string = "";

							if($push_ads_country_array_count > 0)
							{
									foreach($push_ads_country_array as $key => $value)
									{
											if($country_code_left != "")
											$country_code_left.= " AND ";

											$country_code_left.= " code != '".$value."' ";
									}

									if($country_code_left != "")
									$country_code_left = " ".$country_code_left." AND ";
							}

							$result = $db->execute_query("select code,name from ".TABLE_PREFIX."countries where ".$country_code_left." code!='A1' and code!='A2' and code!='AP' and code!='EU' ORDER BY name");
							$this->set_result("result",$result);


							if($push_ads_country_array_count > 0)
							{
									foreach($push_ads_country_array as $key => $value)
									{
											if($country_code_string != "")
											$country_code_string.= " OR ";

											$country_code_string.=" code = '".$value."' ";
									}

									if($country_code_string != "")
									$country_code_string = " WHERE (".$country_code_string.") ";

									$result1 = $db->execute_query("select code,name from ".TABLE_PREFIX."countries ".$country_code_string." ORDER BY name");
									$this->set_result("result1",$result1);
							}
					}
			}

			$this->set_variable("push_ads_apikey",$push_ads_apikey);
			$this->set_variable("push_ads_appid",$push_ads_appid);
			$this->set_variable("push_ad_interval",$push_ad_interval);
			$this->set_variable("push_service_enabled",$push_service_enabled);

			$this->set_variable("title",$title);
			$this->set_variable("description",$description);
			$this->set_variable("sid",$sid);
			$this->set_variable("url",$url);
			$this->set_variable("protocol",$protocol);
			$this->set_variable("category",$category);
			$this->set_variable("urlcategory",$urlcategory);
			$this->set_variable("marketplace_display",$marketplace_display);
			$this->set_variable("keywords",$keywords);
			$this->set_variable("status",$status);
			$this->set_variable("page",$page);

			if($sponsored_enabled == 1)
			{
					$this->set_variable("monthly_impressions_from",$monthly_impressions_from);
					$this->set_variable("monthly_impressions",$monthly_impressions);
			}

			$this->set_variable("logo",$logo);
			$this->set_variable("thumbshot",$thumbshot);
			$this->set_variable("thumblogo",$thumblogo);


			if(isset($_COOKIE['my_locale']))
			$localname=$_COOKIE['my_locale'];
			else
			$localname=DEFAULT_LOCALE;


			$direction=$db->read_single_column("SELECT direction FROM ".TABLE_PREFIX."locale WHERE name=?",array($localname));
			$direction=intval($direction);

			$this->set_variable('direction',$direction);

	}

	function delete_site_action()
	{
		$db= DAL::get_instance();
		$pid=$this->read_cookie_param(COOKIE_LOGINID);

		if(isset($_POST['confirm']))
		{
			$confirmed=1;
			$sid=intval($this->read_post_param('sid'));
			$category=intval($this->read_post_param('category'));
			$status=$this->read_post_param('status');
			$page=$this->read_post_param('page');
			$yesno=intval($this->read_post_param('yesno'));
		}
		else
		{
			$confirmed=0;
			$sid=intval($this->read_page_param(1));
			$category=intval($this->read_page_param(2));
			$status=$this->read_page_param(3);
			$page=$this->read_page_param(4);
		}



		if(!CategoryHelper::get_site_exists($sid,$pid))
		{
			$this->flash($this->get_message('site invalid'), $this->make_url('dispatch/category_targeting/6',BASE),0);
			exit;
		}


		if(DEMO_MODE && $sid <= 10)
		{
			$this->flash($this->get_message('demo mode'), $this->make_url('dispatch/category_targeting/6',BASE),0);
			exit;
		}

		$sponsored_enabled=$this->get_addon_status('sponsored_enabled');

		$mapcount=0;
		if($sponsored_enabled ==1 || $sponsored_enabled ==0)
		$mapcount=$db->read_single_column("SELECT count(id) FROM ".TABLE_PREFIX."sponsored_ad_mapping WHERE site=? AND (status=2 OR status=3)",array($sid));


		if($mapcount >0)
		$this->flash($this->get_message('sponsored mappings site exists'), $this->make_url('dispatch/category_targeting/6',BASE),0);


		/*
		$siteStatus = $db->read_single_column("select status from ".TABLE_PREFIX."sites where id = ?",array($sid));
		if($siteStatus == 0) //Blocked site don't delete
		{
				$this->flash($this->get_message('invalid operation'), $this->make_url('dispatch/category_targeting/6',BASE),0);
				exit;
		}
		*/



		$adunitcount=$db->read_single_column("SELECT count(id) FROM ".TABLE_PREFIX."adunit WHERE sid=?",array($sid));

		if($adunitcount ==0 || $confirmed ==1)
		{
			$siteRow = $db->execute_query("select * from ".TABLE_PREFIX."sites where id=?",array($sid));
			while($siteData = $siteRow->fetch_assoc())
			{
				$siteID    = $siteData['id'];
				$siteLogo  = $siteData['logo'];
				$siteImage = $siteData['thumbshot_image'];


				if($siteLogo !='')
				unlink(DATA_DIR."/site_logo/".$siteID."/".$siteLogo);

				if($siteImage !='')
				unlink(DATA_DIR."/site_logo/".$siteID."/".$siteImage);
			}

			rmdir(DATA_DIR."/site_logo/".$sid."/");

			$db->execute_query("DELETE FROM ".TABLE_PREFIX."sites WHERE id=? AND pid=?",array($sid,$pid));


			if($adunitcount >0)
			{
				$slist=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."adunit WHERE sid=? AND pubid=?",array($sid,$pid));

				$db->execute_query("DELETE FROM ".TABLE_PREFIX."adunit WHERE sid=? AND pubid=?",array($sid,$pid));

			}


			if($sponsored_enabled ==1 || $sponsored_enabled ==0)
			{
				$db->execute_query("DELETE FROM ".TABLE_PREFIX."sponsored_ad_mapping WHERE site=? AND (status=-1 OR status=0 OR status=1)",array($sid));
			}

			$this->flash($this->get_message('site delete success'), $this->make_url('dispatch/category_targeting/6/'.$category.'/'.$status.'/'.$page,BASE));
			exit;
		}


		$this->set_variable("sid",$sid);
		$this->set_variable("category",$category);
		$this->set_variable("status",$status);
		$this->set_variable("page",$page);
	}


	function statistics_action()
	{
		$db= DAL::get_instance();
		$pid=$this->read_cookie_param(COOKIE_LOGINID);

		if($_POST)
		{
			$duration=intval($this->read_post_param("duration"));
			$from_date=$this->read_post_param("from_date");
			$to_date=$this->read_post_param("to_date");
			$sortBy     = $this->read_post_param("sortBy");
			$orderBy    = $this->read_post_param("orderBy");
		}
		else
		{
			$duration=1;
			$from_date='';
			$to_date='';
			$sortBy    = "impression";
			$orderBy   = "desc";
		}

		if($sortBy == "click")
		$sorting = 1;
		else if($sortBy == "conversion")
		$sorting = 4;		
		else if($sortBy == "profit")
		$sorting = 3;
		else 
		$sorting = 0;

		if($orderBy == "asc")
		$ordering = 1;
		else 
		$ordering = 0;

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

		$this->set_variable("from_date",$from_date);
		$this->set_variable("to_date",$to_date);
		$this->set_variable("duration",$duration);
		$this->set_variable("pid",$pid);
		$this->set_variable("sortBy",$sortBy);
		$this->set_variable("orderBy",$orderBy);

		//$timePeriod = array(1), $fromAdmin = 1, $pricing = -1 $pid=-1, $bid=0, $sid=0,  $report_type=0, $sorting = 0, $forMap = 0, $resultLimit = 0, $country = '', $ordering = 0, $publisher = '', $isPublisherData = -1
		$reportResult  = $this->get_publisher_statistics($timePeriod, 0, -1, $pid, 0, 0, 7, $sorting, 0, 0, "", $ordering, "", 1);
		$this->set_array("reportResult",$reportResult);	
	}


	function detail_statistics_action()
	{
		$pid=$this->read_cookie_param(COOKIE_LOGINID);
		$db= DAL::get_instance();

		if($_POST)
		{
			$duration=$this->read_post_param("duration");
			$sid=intval($this->read_post_param("sid"));
			$tab=intval($this->read_post_param("tab"));
			$from_date=$this->read_post_param("from_date");
			$to_date=$this->read_post_param("to_date");
		}
		else
		{
			$sid=intval($this->read_page_param(1));
			$duration=1;
			$tab=0;
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

		$this->set_variable("from_date",$from_date);
		$this->set_variable("to_date",$to_date);

		if(!CategoryHelper::get_site_exists($sid,$pid))
		{
			$this->flash($this->get_message('site invalid'), $this->make_url('dispatch/category_targeting/6',BASE),0);
			exit;
		}

		$sitename=$db->read_single_column("select url from ".TABLE_PREFIX."sites where id=?",array($sid));

		if($duration=="" || $duration==0)
		$duration=1;


		if($duration == 0)
		$duration = 1;

		$timePeriod[] = $duration;

		if($duration == 7)
		{
			$timePeriod[] = $from_date;
			$timePeriod[] = $to_date;
		}


		//$timePeriod = array(1), $fromAdmin = 1, $pricing = -1 $pid=-1, $bid=0, $sid=0,  $report_type=0, $country = ''
		$dbResult = $this->get_publisher_statistics($timePeriod, 0, -1, $pid,0,$sid);
		$this->set_array("reportResult",$dbResult);

		$dbResultTimeperiod= $this->get_publisher_timeperiod_statistics($timePeriod, 0, -1, $pid,0,$sid);
		$this->set_array("reportResultTimeperiod",$dbResultTimeperiod);


		$this->set_variable("duration",$duration);
		$this->set_variable("sitename",$sitename);
		$this->set_variable("pid",$pid);
		$this->set_variable("tab",$tab);
		$this->set_variable("sid",$sid);
	}



};
