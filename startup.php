<?php
/*
 Copyright © Renaisoft Solutions Private Limited
*/
//we dont want direct access to startup.php
if (substr($_SERVER['SCRIPT_NAME'],-12)=='/startup.php')
{
	header("Location: index.php");
	die;
}	

define('DATA_DIR', 'upload'); 				/* if you want to change data folder name, rename the folder and update it here */ ;
define('ADMIN_DIR', 'admin'); 				/* if you want to change admin folder name, rename the folder and update it here */ ;
define('INSTALL_DIR', 'installation'); 	    /* if you want to change installation folder name, rename the folder and update it here */ ;
define('DISPLAY_DIR', 'display'); 			/* if you want to change ad display folder name, rename the folder and update it here */;
define('TRACK_DIR', 'track'); 			    /* if you want to change ad track folder name, rename the folder and update it here */;

define('CRON_DIR', 'cron'); 				/* if you want to change cron folder name, rename the folder and update it here */ ;
define('ADDON_DIR','addons'); 				/* if you want to change addon folder name, rename the folder and update it here */ ;
define('CACHE_DIR', 'cache'); 			    /* if you want to change cache folder name, rename the folder and update it here */ ;
define('LOGO_DIR', 'public_logo');

define('DS', DIRECTORY_SEPARATOR);			// let us abbreviate
define('ROOT_DIR_PATH', dirname(__FILE__).'/');		// absolute path to root folder
define('LIB_DIR_PATH', PATH_TO_ROOT.'library/');	// path to library folder with trailing slash
define('CONFIG_DIR_PATH', PATH_TO_ROOT.'config/');	// path to config folder with trailing slash
define('COMMON_DIR_PATH', PATH_TO_ROOT.'common/');	// path to common folder with trailing slash

define('THEME_DIR_PATH', PATH_TO_ROOT.'themes/');   // path to theme folder
define('DATA_DIR_PATH', PATH_TO_ROOT.DATA_DIR.'/');	// path to data folder with trailing slash
define('ADDON_DIR_PATH', PATH_TO_ROOT.ADDON_DIR.'/');	// path to addon folder with trailing slash

define('COOKIE_USERNAME','userName');
define('COOKIE_PASSWORD','passWord');
define('COOKIE_LOGINID','loginId');

define('COOKIE_ADMIN_USERNAME','adminUserName');
define('COOKIE_ADMIN_PASSWORD','adminPassWord');
define('COOKIE_ADMIN_LOGINID','adminLoginId');
define('COOKIE_ADMIN_TYPE','adminLoginType');

define('COOKIE_ADMARKETTYPE','admarketType');
define('COOKIE_DIFFERENCE','market_diff');
define('COOKIE_REFERRAL','market_ref');
define('COOKIE_REFERRAL_URL','market_ref_url');
define('COOKIE_PUB_LOGIN','_data_co');

define('PAGINATION_SIZE',20);


if(($_SERVER['HTTP_HOST'] == "demo.xyzscripts.com") || ($_SERVER['HTTP_HOST'] == "www.demo.xyzscripts.com"))
{
	define('DEMO_MODE',TRUE);		// TRUE for demo
}
else
{
	define('DEMO_MODE',FALSE);		// FALSE for production
}


if(!defined('DB_INTERFACE'))
{
	if(function_exists('mysqli_connect'))
	define('DB_INTERFACE','mysqli');
	else 
	define('DB_INTERFACE','mysql');
}

if(!defined('MOD_REWRITE'))
define('MOD_REWRITE', false);

define('PRODUCT_CODE','XYZADMSTD');
define('PRODUCT_VERSION','V 4.0.3');
define('VALIDATOR_SERVER_COUNT',2);

define('ADV_ACTIVE',1);
define('ADV_PENDING',-1);
define('ADV_BLOCKED',0);
define('ADV_NO_ACCOUNT',-2);
define('ADV_NO_EMAIL_VERIFY',-3);

define('PUB_ACTIVE',1);
define('PUB_PENDING',-1);
define('PUB_BLOCKED',0);
define('PUB_NO_ACCOUNT',-2);
define('PUB_NO_EMAIL_VERIFY',-3);

define('AD_ACTIVE',1);
define('AD_PENDING',-1);
define('AD_BLOCKED',0);


define('DEFAULT_LOCALE', 'en_US'); 		// do not modify unless you need to change default language;
define('DEFAULT_CHARSET', 'utf-8');
define('DB_CHARSET', 'utf8');
define('DB_COLLATION', 'utf8_unicode_ci');


define('MEMCACHE_HOST', '');  
define('MEMCACHE_PORT', '');
define('ADUNIT_MEMCACHE_ENABLED', 0);

if(MEMCACHE_HOST !='' && MEMCACHE_PORT !='') 		//memcache expire  with in 24 hours
define('MEMCACHE_EXPIRY',time() + 24 * 3600);      

if(file_exists(CONFIG_DIR_PATH."system.php")) 
include(CONFIG_DIR_PATH."system.php");


if(!defined('DISPLAY_BASE'))
define('DISPLAY_BASE', BASE);


if(!defined('TRACK_BASE'))
define('TRACK_BASE', BASE);



if(substr(BASE, 0, 5)=='https')
define('USE_HTTPS', true);
else 
define('USE_HTTPS', false);
ob_start();

$display_dir_length=strlen(DISPLAY_DIR)+11;
$track_dir_length=strlen(TRACK_DIR)+11; 




if(substr($_SERVER['SCRIPT_NAME'],-$display_dir_length) =='/'.DISPLAY_DIR.'/index.php')
{
	include(PATH_TO_ROOT.DISPLAY_DIR."/display.php");  
	AdDispatcher::dispatch();  
}
else if(substr($_SERVER['SCRIPT_NAME'],-$track_dir_length) =='/'.TRACK_DIR.'/index.php')
{  
	include(PATH_TO_ROOT.TRACK_DIR."/track.php");
	AdDispatcher::dispatch();
}
else
{
	include(LIB_DIR_PATH."core/main.php"); 
	Dispatcher::dispatch(); 
}
?>
