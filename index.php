<?php
error_reporting(0); // PHP error reporting level; 0 recommended for live site
define ('ER', 0); // framework error reporting level; 0 recommended for live site

define('PATH_TO_ROOT', './'); // relative path to application root folder
define('CONTROL_DIR', 'controllers'); // name of folder containing controller files
define('VIEW_DIR', 'views'); // name of folder containing view files 


// ok let us start
include PATH_TO_ROOT.'startup.php';
?>
