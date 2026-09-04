<?php
error_reporting(0); // PHP error reporting level; 0 recommended for live site

if(!defined('ER'))
define ('ER', 0); // framework error reporting level; 0 recommended for live site

if(!defined('PATH_TO_ROOT'))
define('PATH_TO_ROOT', '../../'); // relative path to application root folder

if(!defined('CONTROL_DIR'))
define('CONTROL_DIR', 'controllers'); // name of folder containing controller files

if(!defined('VIEW_DIR'))
define('VIEW_DIR', 'views'); // name of folder containing view files

// ok let us start
include PATH_TO_ROOT.'startup.php';
?>