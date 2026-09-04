<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height" />
<?php
$localnameFull 	= $this->get_variable("localnameFull");
$localnameFirst = $this->get_variable("localnameFirst");

$page 	    = $this->get_variable("page");
$direction  = $this->get_variable('direction');

$active_theme     = $this->read_cookie_param('active_theme');
if($active_theme == "")
$active_theme=Configuration::get_instance()->read('active_theme');

$public_page_logo = Configuration::get_instance()->read('public_page_logo');

if(DEMO_MODE)
$public_page_logo = THEME_DIR_PATH.$active_theme."/images/logo.png";
else if($public_page_logo != "")
$public_page_logo = BASE.DATA_DIR."/logo/".$public_page_logo;


$admarket_name    = Configuration::get_instance()->read('admarket_name');

?>
<html lang = "<?php echo $localnameFirst; ?>">
<head>
	<link rel="icon" href="<?php echo THEME_DIR_PATH.$active_theme;?>/images/favicon.png" />
	<!--<link rel="icon" href="<?php echo BASE;?>favicon.ico" />-->

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

	<script type="text/javascript" src="<?php echo COMMON_DIR_PATH;?>js/jquery.min.js"></script>
  <script type="text/javascript" src="<?php echo COMMON_DIR_PATH;?>js/bootstrap.min.js"></script>

  <link rel="stylesheet" type="text/css" media="all" href="<?php echo COMMON_DIR_PATH;?>css/bootstrap.min.css"/>

	<?php if($direction == 1){ //shouldn't change from here?>
		<link rel="stylesheet" type="text/css" media="all" href="<?php echo COMMON_DIR_PATH;?>css/bootstrap.rtl.min.css" />
	<?php } ?>

	<link rel="stylesheet" type="text/css" media="all" href="<?php echo THEME_DIR_PATH.$active_theme;?>/css/<?php echo $active_theme;?>.css"/>

  <?php if($direction == 1){?>
		<link rel="stylesheet" type="text/css" media="all" href="<?php echo THEME_DIR_PATH.$active_theme;?>/css/<?php echo $active_theme;?>-rtl.css" />
  <?php } ?>

  <link rel="stylesheet" type="text/css" media="all" href="<?php echo COMMON_DIR_PATH;?>font-awesome/css/font-awesome.css" />
</head>


<?php
	$external_theme_url  = Configuration::get_instance()->read('external_theme_url');

	if(DEMO_MODE)
	{
			$active_ext_theme = $this->read_cookie_param('active_ext_theme');

			if($active_ext_theme != "")
			$external_theme_url	= 	$this->get_demo_external_url($external_theme_url);
	}
?>
<body>
<div class="container-fluid">
	<div class="row">
