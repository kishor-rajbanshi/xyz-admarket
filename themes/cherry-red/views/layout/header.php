<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height" />
<?php
//$page 16 => index
//$page 17 => advertiser
//$page 18 => publisher
//$page 19 => contact-us
//$page 20 => register
//$page 21 => forgot-password
//$page 22 => about-us
//$page 23 => terms
//$page 24 => notification
//$page 25 => custom-pages
//$page 26 => cpd
//$page 27 => affiliate
//$page 28 => cookie-policy
//$page 29 => privacy-policy


//$cpanel => a   For advertiser
//$cpanel => p   For publisher
//$cpanel => r   For referral
//$cpanel => b   For advertiser & publisher
//$cpanel => s   For Support
//$cpanel => cp  For Custom pages

//$userPanel => advertiser / publisher

$admarket_name    	= Configuration::get_instance()->read('admarket_name');
$public_page_logo 	= Configuration::get_instance()->read('public_page_logo');

$public_page_icon   = Configuration::get_instance()->read('public_page_icon');
$language_enabled   = Configuration::get_instance()->read('language_enabled');

$languageSelect     = $this->get_variable('languagestring');
$localnameFull 		  = $this->get_variable("localnameFull");
$localnameFirst 	  = $this->get_variable("localnameFirst");

$active_theme   	  = $this->read_cookie_param('active_theme');

if($active_theme == "")
$active_theme   	  = Configuration::get_instance()->read('active_theme');

$referral_enabled   = 0;

$page 					= intval($this->get_variable("page"));
$sub_page 			= intval($this->get_variable("sub_page"));
$logedin 				= intval($this->get_variable('logedin'));
$localeid 			= intval($this->get_variable('localeid'));
$direction 			= intval($this->get_variable('direction'));
$common_page 		= intval($this->get_variable('common_page'));
$cpanel 				= $this->get_variable("cpanel");
$userPanel 			= $this->get_variable("userPanel");
$common_page_temp = $this->get_variable("common_page_temp");


if(DEMO_MODE)
{
		$public_page_logo = THEME_DIR_PATH.$active_theme."/images/logo.png";
}
else if($public_page_logo != "")
$public_page_logo = BASE.DATA_DIR."/logo/".$public_page_logo;


$adv_managerid 	= intval($this->get_variable("adv_managerid"));
$pub_managerid 	= intval($this->get_variable("pub_managerid"));

if($adv_managerid > 0)
{
		$adv_manager_name 	 = $this->get_variable("adv_manager_name");
		$adv_manager_email 	 = $this->get_variable("adv_manager_email");
		$adv_manager_phone 	 = $this->get_variable("adv_manager_phone");
		$adv_manager_skypeid = $this->get_variable("adv_manager_skypeid");
}

if($pub_managerid > 0)
{
		$pub_manager_name 	 = $this->get_variable("pub_manager_name");
		$pub_manager_email 	 = $this->get_variable("pub_manager_email");
		$pub_manager_phone 	 = $this->get_variable("pub_manager_phone");
		$pub_manager_skypeid = $this->get_variable("pub_manager_skypeid");
}


$pageTitle      = $this->get_variable("pageTitle");

$cpd_enabled    	= $this->get_addon_status('sponsored_enabled');
$cookieCount    	= 0;

if($cpd_enabled == 1)
{
	$positionCookie = $this->read_cookie_param(COOKIE_CPD_CART);

	if($positionCookie != "")
	{
		$positionCookieArray = explode(",",$positionCookie);
		$cookieCount         = count($positionCookieArray);
	}
}


if($logedin == 0)
{
		$registerseo = $this->get_seo_name('index/register');

		if($registerseo != '')
		$registerurl = BASE.$registerseo;
		else
		$registerurl = $this->make_url("index/register");


		$loginseo      = $this->get_seo_name('index/login');

		if($loginseo != '')
		$loginurl      = BASE.$loginseo;
		else
		$loginurl      = $this->make_url("index/login");
}
?>
<html lang="<?php echo $localnameFirst; ?>">
<head>
<link rel="icon" href="<?php echo THEME_DIR_PATH.$active_theme;?>/images/favicon.png" />


<meta http-equiv="Content-Type" content="text/html; charset=<?php echo DEFAULT_CHARSET;?>" />
<meta http-equiv="content-language" content="<?php echo $localnameFull;?>" />
<meta name="keywords" content="<?php echo $this->get_variable("mkey");?>" />
<meta name="description" content="<?php echo str_ireplace("{x}",$admarket_name,$this->get_variable("mdesc"));?>" />

<title>
<?php
if($this->get_title('') == "")
echo $admarket_name;
else
echo $this->get_title().' - '.$admarket_name;
?>
</title>

<?php echo Configuration::get_instance()->read('google_analytics_code');?>

<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/jquery.min.js'></script>

<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/popper.min.js'></script>
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/bootstrap.min.js'></script>
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>jquery-ui/jquery-ui.min.js'></script>
<script type="text/javascript" src="<?php echo COMMON_DIR_PATH;?>js/common.js"></script>

<?php if($page == 16){?>
	<!-- For logo slider -->
	<script type="text/javascript" src="<?php echo COMMON_DIR_PATH;?>js/slick.js"></script>
<?php } ?>

<!-- Index/Advertiser/Publisher/ContactUs/FAQ -->
<?php if($page == 16 || $page == 17 || $page == 18 || $page == 19 || $page == 30){?>
  <script type="text/javascript" src="<?php echo THEME_DIR_PATH.$active_theme;?>/assets/vendor/aos/aos.js"></script>
<?php }?>

<?php if($logedin == 1 && $common_page == 0){ ?>
	<!-- ******* For left menu ********* -->
	<script type="text/javascript" src="<?php echo COMMON_DIR_PATH;?>js/jquery.mousewheel.min.js"></script>
	<script type="text/javascript" src="<?php echo COMMON_DIR_PATH;?>js/jquery.mCustomScrollbar.js"></script>
	<!-- ******* For left menu ********* -->
<?php } ?>

<link rel="stylesheet" type="text/css" media="all" href="<?php echo COMMON_DIR_PATH;?>css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo COMMON_DIR_PATH;?>font-awesome/css/font-awesome.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo COMMON_DIR_PATH;?>jquery-ui/jquery-ui.min.css" />


<?php if($page == 16 || $page == 17 || $page == 18 || $page == 19 || $page == 30){?>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo COMMON_DIR_PATH;?>css/animate.min.css" />

<link rel="stylesheet" type="text/css" media="all" href="<?php echo THEME_DIR_PATH.$active_theme;?>/assets/vendor/aos/aos.css" />
<?php } ?>

<link rel="stylesheet" type="text/css" media="all" href="<?php echo THEME_DIR_PATH.$active_theme;?>/assets/vendor/boxicons/css/boxicons.min.css" />


<link rel="stylesheet" type="text/css" media="all" href="<?php echo THEME_DIR_PATH.$active_theme;?>/assets/vendor/remixicon/remixicon.css" />

<?php if($logedin == 1 && $common_page == 0){?>
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo COMMON_DIR_PATH;?>css/jquery.mCustomScrollbar.css" />
<?php }?>


<?php if($direction == 1){ //shouldn't change from here ?>
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo COMMON_DIR_PATH;?>css/bootstrap.rtl.min.css" />
<?php } ?>

<link rel="stylesheet" type="text/css" media="all" href="<?php echo BASE;?>css/public-style.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo THEME_DIR_PATH.$active_theme.'/css/'.$active_theme.'.css';?>" />

<?php if($common_page == 0 || $common_page_temp == 1){?>
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo THEME_DIR_PATH.$active_theme.'/css/'.$active_theme.'-dashboard.css';?>" />
<?php } ?>


<?php if($direction == 1){?>
		<link rel="stylesheet" type="text/css" media="all" href="<?php echo BASE;?>css/public-style-rtl.css" />
		<link rel="stylesheet" type="text/css" media="all" href="<?php echo THEME_DIR_PATH.$active_theme.'/css/'.$active_theme.'-rtl.css';?>" />

		<?php if($common_page == 0 || $common_page_temp == 1){?>
			<link rel="stylesheet" type="text/css" media="all" href="<?php echo THEME_DIR_PATH.$active_theme.'/css/'.$active_theme.'-dashboard-rtl.css';?>" />
		<?php } ?>
<?php }?>

</head>
<body class="<?php if($common_page == 0 || $common_page_temp == 1){?> bg-light <?php }?>">

<?php

$twitter_url 	= Configuration::get_instance()->read('twitter_url');
$facebook_url = Configuration::get_instance()->read('facebook_url');
$linkedin_url = Configuration::get_instance()->read('linkedin_url');
$youtube_url 	= Configuration::get_instance()->read('youtube_url');
$admin_email  = Configuration::get_instance()->read('admin_notification_email');
$advertiser_dashboard_status = Configuration::get_instance()->read('advertiser_dashboard_status');
$publisher_dashboard_status  = Configuration::get_instance()->read('publisher_dashboard_status');

$menu_array = $GLOBALS['menu'];

if($logedin == 1)
$menu_array = $menu_array['login'];
else
$menu_array = $menu_array['logout'];

if($logedin == 1)
{
	$userResult  = $this->get_result('userResult');
	$value       = $userResult[0];

	$userID         = $value['id'];
	$loginName      = $value['username'];
	$profilePicture = $value['profile_picture'];

	$emaiAddress = $value['email'];
	$userName    = $value['f_name'];

	if($userName != "")
	$userName.= " ";

	$userName.= $value['l_name'];

	$firstLetter  = substr($userName, 0, 1);


	$adv_status = $value['adv_status'];
	$pub_status = $value['pub_status'];


	$adv_bonus_balance 	= $value['adv_bonus_balance'];
	$adv_balance 		    = $value['adv_account_balance'];
	$pub_balance 		    = $value['pub_account_balance'];

	$referral_enabled   = $this->get_variable('referral_enabled');
	$referral_balance   = 0;

	if($referral_enabled ==1 && ($adv_status ==1 || $pub_status ==1) && (Configuration::get_instance()->read('advertiser_referral_enabled') ==1 || Configuration::get_instance()->read('publisher_referral_enabled') ==1))
	$referral_enabled = 1;
	else
	$referral_enabled = 0;

	if($referral_enabled == 1)
	{
			$referral_balance = $value['referral_balance'];

			if($referral_balance < 0)
	    $referral_balance = 0;
	}


	if($cpanel != "b" && $cpanel != "s" && $cpanel != "f" && $cpanel != "r")
	{
		if($this->read_cookie_param(COOKIE_ADMARKETTYPE) == 1)
		$cpanel = "a";
		if($this->read_cookie_param(COOKIE_ADMARKETTYPE) == 2)
		$cpanel = "p";
	}
}


$notificationUnread   = $this->get_variable('notificationcount');
$notificationCount    = $this->get_variable('notificationcount1');
$notificationReaded   = $this->get_variable('valuestring');

if($notificationUnread > 0)
$notificationCount = $notificationUnread;
?>
<header class="header-class fixed-top d-flex <?php if($common_page == 0){?> header-dark <?php } else { ?> header-transparent <?php } ?> <?php if($common_page_temp == 1){?> header-temp <?php } ?>">

	<div class="<?php if($common_page == 0 || $common_page_temp == 1){?> container-fluid <?php } else { ?> container <?php } ?> d-flex align-items-center justify-content-between">
	 	<div class="logo">
	    <h1 class="logo-desktop">
	        <a href="<?php echo BASE;?>">
	    	  		<?php if($public_page_logo != ""){?>
	    	  		<img class="img-fluid" src="<?php echo $public_page_logo;?>" alt="<?php echo $admarket_name;?>" title="<?php echo $admarket_name;?>" />
	    	  		<?php } else {
		    	    echo $admarket_name;
		    	    }?>
	  			</a>
			</h1>

			<h1 class="logo-mobile">
					<a href="<?php echo BASE;?>">
							<?php if($public_page_icon != ""){?>
							<img class="img-fluid" src="<?php echo BASE.DATA_DIR;?>/logo/<?php echo $public_page_icon;?>" alt="<?php echo $admarket_name;?>" title="<?php echo $admarket_name;?>" />
						  <?php } else {?>
							<img class="img-fluid" src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/admarket-logo-icon.png" alt="<?php echo $admarket_name;?>" title="<?php echo $admarket_name;?>" />
							<?php }?>
					</a>
			</h1>

			<?php if($common_page == 0){?>
			<ul class="dashboard-side-menu-toggle">
				<li>
					 <i class="fa fa-bars" aria-hidden="true" id="dashboard-side-menu-icon"></i>
				</li>
			</ul>
			<?php }?>
	  </div>

		<?php if($common_page == 1){?>
			<nav id="navbar" class="navbar">
		     <ul class="navbar-height">
		        <?php
		        		$iIndex = 0;

								foreach($menu_array as $menukey0 => $menuvalue0)
		            {
		            	$menuvalue0['icon'] = "";
									$homeClass          = "";

		            	if($iIndex == 0 && $logedin == 0)
		            	$homeClass = " home-class ";

		            	if(isset($menuvalue0['label']) && $menuvalue0['label'] != "")
		            	{
		                  if($menuvalue0['child'] == ""){?>
		        						<li class="nav-item <?php echo $homeClass;?>">
		                      <a <?php if($menuvalue0['link'] !=""){?>href="<?php echo $menuvalue0['link'];?>"<?php }?> class="<?php echo $menuvalue0['class'];?> nav-link scrollto nav-link-grow-up">
														<?php if($menuvalue0['icon'] !=""){?>
															<i class="fa <?php echo $menuvalue0['icon'];?>"></i>
														<?php }?>
														<?php echo $menuvalue0['label'];?>
													</a>
		                    </li>
		                  <?php } else { ?>
		                  <li class = "nav-item dropdown">
		                    <a id="<?php echo $menukey0;?>-Data" class="page-list <?php echo $menuvalue0['class'];?> nav-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
													<?php if($menuvalue0['icon'] !=""){?>
														<i class="fa <?php echo $menuvalue0['icon'];?>"></i>
													<?php }?>
													<?php echo $menuvalue0['label'];?>
		            				</a>
		                    <ul class="dropdown-menu drop-down-mob" aria-labelledby="<?php echo $menukey0;?>-Data">
		                      <?php foreach($menuvalue0['child'] as $menukey1=>$menuvalue1){?>
		                        <li>
		                        	<a class="dropdown-item" <?php if($menuvalue1['link'] !=""){?>href="<?php echo $menuvalue1['link'];?>"<?php }?>><?php echo $menuvalue1['label'];?></a>
		                        </li>
		                      <?php } ?>
		            				</ul>
		                  </li>
		                  <?php
		                  }
		               }
		               $iIndex++;
		          }
		        	?>
					</ul>

		  </nav><!-- .navbar -->
		<?php } ?>

			<ul class="header-icon-box">
		    	<?php if($language_enabled == 1){?>
		            <li class="dropdown language-li header-icon">
		            		<?php echo $languageSelect;?>
		            </li>
		        <?php }?>


	        <?php if($cpd_enabled == 1 && $logedin == 1){?>
	            <li class="nav-item header-icon" id="cart-position" style="<?php if($cookieCount == 0){?> display:none; <?php } ?>">
	                <a class="cart-section" href="<?php echo $this->make_base_url("dispatch/sponsored/33");?>" title="<?php echo $this->get_label('review cart'); ?>">
	                    <i class="fa fa-cart-plus" aria-hidden="true"></i>
	                </a>
	            </li>
	        <?php } ?>


		        <?php if($notificationCount > 0){?>
		            <li class="nav-item header-icon">
		                <div class="notification-box">
		                    <i class="fa fa-bell-o notification-bell" onClick="LoadNotifications('<?php echo $this->make_url('index/notifications');?>');"></i>
		                    <input type="hidden" name="admvaluestring" id="admvaluestring" value="<?php echo $notificationReaded;?>" />
		                    <div class="notification-round-box" onClick="LoadNotifications('<?php echo $this->make_url('index/notifications');?>');">
		                        <span class="notification-count" ><?php echo $notificationCount;?></span>
		                        <i class="fa fa-circle <?php if($notificationUnread > 0){?> notification-round <?php } else { ?> notification-round-readed <?php } ?>"></i>
		                    </div>
		                </div>
		            </li>
		        <?php }?>


					<?php
					if($logedin == 0)
					{
							?>
							<li class="login-box">
								<button class="login-button-header" onclick="window.location.href='<?php echo $loginurl;?>'">
									<?php echo $this->get_label('login');?>
									<i class="fa fa-sign-in" aria-hidden="true"></i>
								</button>

								<i class="fa fa-sign-in login-button-icon header-icon" aria-hidden="true" title="<?php echo $this->get_label('login');?>" onclick="window.location.href='<?php echo $loginurl;?>'"></i>
							</li>
							<?php
							if(!DEMO_MODE)
							{ ?>
							<li class="signup-box">
								<button class="signup-button-header" onclick="window.location.href='<?php echo $registerurl;?>'">
									<?php echo $this->get_label('signup');?>
									<i class="bx bx-user-circle"></i>
								</button>

								<i class="bx bx-user-circle signup-button-icon header-icon" aria-hidden="true" title="<?php echo $this->get_label('signup');?>" onclick="window.location.href='<?php echo $registerurl;?>'"></i>
							</li>
					<?php }} else {?>
					<li class="dropdown account-li user-detail-icon">
							<a href="#" class="dropdown-toggle user-icon-outer" data-toggle="dropdown" data-bs-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">
								<i class="letter-icon-first">
									<?php echo $firstLetter;?>
								</i>
							</a>

							<ul class="dropdown-menu animated flipInX user-ul-outer">
								<li class="username-li">
										<p class="text-center mb-0">
											<i class="letter-icon-second">
												<?php echo $firstLetter;?>
											</i>
										</p>
										<div class="user_name_pop"><?php  echo $userName;?></div>
										<div class="user_email_pop"><?php  echo $emaiAddress;?></div>
								</li>

								<?php if($userPanel == "advertiser" && $pub_status == 1 && $publisher_dashboard_status == 1){?>
									<li class="menu-li menu-li-account switch-account-button"><a href="<?php echo $this->make_base_url("dashboard/publisher_home");?>"><i class="fa fa-exchange header-account-icon" style="font-size:12px;" title="<?php echo $this->get_label('switch to publisher');?>"></i><?php echo $this->get_label('switch to publisher');?></a></li>
								<?php }?>

								<?php if($userPanel == "publisher" && $adv_status == 1 && $advertiser_dashboard_status == 1){?>
									<li class="menu-li menu-li-account switch-account-button"><a href="<?php echo $this->make_base_url("dashboard/advertiser_home");?>"><i class="fa fa-exchange header-account-icon" style="font-size:12px;" title="<?php echo $this->get_label('switch to advertiser');?>"></i><?php echo $this->get_label('switch to advertiser');?></a></li>
								<?php }?>

								<?php if($userPanel == "advertiser" && $adv_status == 1){?>
									<li class="menu-li menu-li-balance d-flex">
										<i class="fa fa-briefcase header-account-icon" title="<?php echo $this->get_label('advertiser balance');?>"></i>
										<div id="advertiser_account_balance" >
											<bdi><?php echo $this->get_label('advertiser balance');?> : <?php echo $this->get_money_format($adv_balance);?></bdi>
										</div>
									</li>

									<?php if($adv_bonus_balance > 0){?>
										<li class="menu-li menu-li-balance mt-1">
											<i class="fa fa-briefcase header-account-icon" title="<?php echo $this->get_label('advertiser bonus');?>"></i><bdi><?php echo $this->get_label('advertiser bonus');?> : <?php echo $this->get_money_format($adv_bonus_balance);?></bdi>
										</li>
									<?php } ?>
								<?php } ?>

								<?php if($userPanel == "publisher" && $pub_status == 1){?>
									<li class="menu-li menu-li-balance">
										<i class="fa fa-briefcase header-account-icon" title="<?php echo $this->get_label('publisher balance');?>"></i><bdi><?php echo $this->get_label('publisher balance');?> : <?php echo $this->get_money_format($pub_balance);?></bdi>
									</li>
								<?php } ?>

								<?php if($referral_enabled == 1 && $referral_balance > 0){?>
									<li class="menu-li menu-li-balance mt-1">
										<i class="fa fa-briefcase header-account-icon" title="<?php echo $this->get_label('referral account balance');?>"></i><bdi><?php echo $this->get_label('referral account balance');?> : <?php echo $this->get_money_format($referral_balance);?></bdi>
									</li>
								<?php } ?>


								<?php if($adv_status == -2){?>
									<li class="menu-li menu-li-account"><a href="<?php echo $this->make_base_url("user/advertiser_request");?>"><i class="bx bx-git-pull-request header-account-icon" title="<?php echo $this->get_label('adv account request');?>"></i><?php echo $this->get_label('adv account request');?></a></li>
								<?php }?>

								<?php if($pub_status == -2){?>
									<li class="menu-li menu-li-account"><a href="<?php echo $this->make_base_url("user/publisher_request");?>"><i class="bx bx-git-pull-request header-account-icon" title="<?php echo $this->get_label('pub account request');?>"></i><?php echo $this->get_label('pub account request');?></a></li>
								<?php }?>


								<?php if($userPanel == "advertiser" && $adv_managerid > 0){?>
									<li class="sub-admin-wrap-outer">
		                	<ul  class="sub-admin-wrap">
												<li class="menu-li title"><?php echo $this->get_label("advertiser manager");?></li>

											<?php if($adv_manager_name != ""){?>
												<li class="menu-li"><i class='bx bxs-user header-account-icon'></i><?php echo $adv_manager_name;?></li>
											<?php } ?>

											<?php if($adv_manager_email != ""){?>
												<li class="menu-li"><i class='bx bx-envelope header-account-icon'></i><?php echo $adv_manager_email;?></li>
											<?php } ?>

											<?php if($adv_manager_phone != ""){?>
												<li class="menu-li"><i class='bx bx-mobile-alt header-account-icon'></i><?php echo $adv_manager_phone;?></li>
											<?php } ?>

											<?php if($adv_manager_skypeid != ""){?>
												<li class="menu-li"><i class='header-account-icon'><img  src="images/teams.svg" style="vertical-align: middle;" /></i><?php echo $adv_manager_skypeid;?></li>
											<?php } ?>
										</ul>
									</li>
								<?php } ?>


								<?php if($userPanel == "publisher" && $pub_managerid > 0){?>
									<li class="sub-admin-wrap-outer">
		                 <ul class="sub-admin-wrap">
												<li class="menu-li title"><?php echo $this->get_label("publisher manager");?></li>

												<?php if($pub_manager_name != ""){?>
													<li class="menu-li"><i class='bx bxs-user header-account-icon'></i><?php echo $pub_manager_name;?></li>
												<?php } ?>

												<?php if($pub_manager_email != ""){?>
													<li class="menu-li"><i class='bx bx-envelope header-account-icon'></i><?php echo $pub_manager_email;?></li>
												<?php } ?>

												<?php if($pub_manager_phone != ""){?>
													<li class="menu-li"><i class='bx bx-mobile-alt header-account-icon'></i><?php echo $pub_manager_phone;?></li>
												<?php } ?>

												<?php if($pub_manager_skypeid != ""){?>
													<li class="menu-li"><i class='header-account-icon'><img  src="images/teams.svg" style="vertical-align: middle;" /></i><?php echo $pub_manager_skypeid;?></li>
												<?php } ?>
											</ul>
									 </li>
								<?php } ?>

								<li class="menu-li menu-button-li">
									<a class="user-sign-out support" href="<?php echo $this->make_base_url("user/support");?>"><i class="fa fa-gear user-pop-ico" title="<?php echo $this->get_label('support');?>"></i><?php echo $this->get_label('support');?></a>
									<a class="user-sign-out" href="<?php echo $this->make_base_url("user/logout");?>"><i class="fa fa-power-off user-pop-ico" title="<?php echo $this->get_label('sign out');?>"></i><?php echo $this->get_label('sign out');?></a>
								</li>

							</ul>
					</li>
				<?php }?>

				<?php if($common_page == 1){?>
					<li>
						<i class="fa fa-bars mobile-nav-toggle"></i>
					</li>
				<?php } ?>


	  </ul>
	</div>
</header><!-- End Header -->

<?php
if($common_page == 0)
{
		$menu_array   = array();
		if(!isset($GLOBALS['menu']['login']['advertiser_menu']))
		$GLOBALS['menu']['login']['advertiser_menu'] = [];

	 	if(!isset($GLOBALS['menu']['login']['publisher_menu']))
 		$GLOBALS['menu']['login']['publisher_menu'] = [];

		if($userPanel == "advertiser" && $adv_status == 1)
		$menu_array   = array_merge($menu_array,$GLOBALS['menu']['login']['advertiser_menu']);

		if($userPanel == "publisher" && $pub_status == 1)
		$menu_array   = array_merge($menu_array,$GLOBALS['menu']['login']['publisher_menu']);

		$menu_array   = array_merge($menu_array,$GLOBALS['menu']['login']['common_menu']);

		if($referral_enabled ==1 && ($adv_status == 1 || $pub_status == 1))
		$menu_array   = array_merge($menu_array,$GLOBALS['menu']['login']['referral_menu']);
		?>



 	 	<div class="dashboard-side-menu float-start" id="dashboard-side-menu">

						    <div class="sidebar-user-section">
									  <div class="sidebar-user-icon">
											<?php if(file_exists(DATA_DIR.'/'.PROFILE_PICTURE_DIR.'/'.$userID.'/'.$profilePicture)){?>
												<img src="<?php echo DATA_DIR.'/'.PROFILE_PICTURE_DIR.'/'.$userID.'/'.$profilePicture;?>" alt="<?php echo $loginName;?>" title="<?php echo $loginName;?>" />
											<?php }else{ ?>
												<i class='bx bxs-user icon'></i>
											<?php } ?>
										</div>
										<div class="sidebar-user-name"><?php echo $loginName;?></div>
                </div>

								<nav class="sidebar py-2">
                <ul class="nav nav-pills">
                 <?php
          			 foreach($menu_array as $menukey0=>$menuvalue0)
          			 {
        						if(count($menuvalue0['child']) == 0){?>
            					<li class="nav-item mb-2" id="left-menu-<?php echo $menuvalue0['id'];?>">
                          <a class="nav-link <?php if($menuvalue0['class'] != ""){ echo $menuvalue0['class']; } ?>"
														 <?php if($menuvalue0['link'] != ""){?> href="<?php echo $menuvalue0['link'];?>" <?php }?> >
                             <?php if($menuvalue0['icon'] !=""){?>
																<i class="nav-icon float-start me-2 fa <?php echo $menuvalue0['icon'];?>" title="<?php echo $menuvalue0['label'];?>"></i>
														 <?php }?>
                             <p class="float-start"><?php echo $menuvalue0['label'];?></p>
                          </a>
                      </li>
        					  <?php }	else { ?>
      						    <li class="dropdown mb-2" id="left-menu-<?php echo $menuvalue0['id'];?>">
                          <a class="nav-link dropdown-toggle <?php if($menuvalue0['id'] == $page){?>show<?php }?> <?php if($menuvalue0['class'] != ""){ echo $menuvalue0['class']; } ?>" href="#" data-bs-toggle="dropdown" aria-expanded="<?php if($menuvalue0['id'] == $page){?>true<?php } else{ ?>false<?php }?>">
                             <?php if($menuvalue0['icon'] !=""){?>
																<i class="nav-icon float-start me-2 fa <?php echo $menuvalue0['icon'];?>" title="<?php echo $menuvalue0['label'];?>"></i>
														 <?php }?>
                              <p class="float-start"><?php echo $menuvalue0['label'];?></p>
                          </a>
                          <ul class="dropdown-menu <?php if($menuvalue0['id'] == $page){?>show<?php }?>" aria-labelledby="dropdown">
                             <?php foreach($menuvalue0['child'] as $menukey1=>$menuvalue1){?>
	                             <li class="nav-item">
	                                <a class="dropdown-item <?php if($menuvalue1['class'] != "") { echo $menuvalue1['class']; } ?>"
																		 <?php if($menuvalue1['link'] !=""){?> href="<?php echo $menuvalue1['link'];?>" <?php }?> >

																		 <i class="nav-icon float-start me-2 fa fa-circle-o"></i>
	                                   <p><?php echo $menuvalue1['label'];?></p>
	                                </a>
	                             </li>
                             <?php }?>
                          </ul>
                      </li>
        				  <?php } }?>
             </ul>

          </nav>
 	 </div>

	 <div class="content-section">
		 <div class="custom-date-div custom-top-message"> <?php echo $this->get_label('custom date message',array('x'=>Configuration::get_instance()->read('daily_based_data_backup_expiry')));?></div>

	 <?php }?>

	 <?php
	 //For cpd/affiliate pages without login
	 if($common_page_temp == 1){?>
	 <div class="content-section content-section-temp">
	 <?php } ?>

		<?php
			/**********Custom pages section**********/
			if($common_page == 1 && $page == 25){?>
				<section class="common-page-title text-center">
					<div class="common-bg-layer"></div>
					<div class="common-pattern-layer"></div>
					<div class="auto-container">
						<div class="content-box">
							<h1 class="animated" data-aos="zoom-in">
								<?php echo ucwords(strtolower($pageTitle));?>
							</h1>
				        </div>
				      </div>
				</section>
		<?php }
		/**********Custom pages section**********/
		?>



<?php if(DEMO_MODE){?>
	<div class="fabs">
		<div class="dropdown">
			<?php
			$theme_array = $GLOBALS["xyz_admarket_themes"];

			if(count($theme_array) > 1){?>

			  <button class="fab dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
			   <i class="fa fa-desktop" aria-hidden="true"></i>
				 <div class="label-div"><?php echo $this->get_label('themes');?></div>
				 <span class="caret"></span>
			  </button>
			  <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
			  	<?php foreach($theme_array as $key => $value){?>
				    <li class="<?php if($active_theme == $value["folder_name"]){?> theme-list-selected <?php }?>">
				    	<a class="dropdown-item" onclick="change_theme('<?php echo $value["folder_name"];?>','<?php echo $this->get_base_path();?>');" ><?php echo $value['name']?></a>
						</li>
			    <?php }?>
			  </ul>

			<?php	}	else { ?>
				<button class="fab" type="button" aria-expanded="false">
					<a target="_blank" href="https://addons.admarket-demo.xyzscripts.com/">
						 <i class="fa fa-desktop" aria-hidden="true"></i>
						 <div class="label-div"><?php echo $this->get_label('themes');?></div>
					</a>
				</button>
			<?php } ?>
			</div>
		</div>
<?php }?>
