<?php
$active_theme=$this->read_cookie_param('active_theme');
if($active_theme=="")
	$active_theme=Configuration::get_instance()->read('active_theme');
$external_theme_exists=Configuration::get_instance()->read('exsternal_theme_exists');
if($external_theme_exists!=1)
	$this->dispatch("layout/header/21");
else
	{
	?>
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/jquery.min.js'></script>
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/jquery-ui-1.8.23.custom.min.js'></script>
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/bootstrap.min.js'></script>

<link rel="icon" href="<?php echo BASE;?>favicon.ico">
<link rel="stylesheet" type="text/css" media="all" href="<?php echo COMMON_DIR_PATH;?>css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo COMMON_DIR_PATH;?>font-awesome/css/font-awesome.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo THEME_DIR_PATH.$active_theme;?>/css/animate.min.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo BASE;?>css/public-style.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo THEME_DIR_PATH.$active_theme;?>/css/style.css" />

<?php
$direction=$this->get_variable('direction');
if($direction ==1) {?>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo COMMON_DIR_PATH;?>css/bootstrap-rtl.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo BASE;?>css/public-rtl-style.css" />
<?php }
}




if($external_theme_exists!=1)
    $this->dispatch("layout/footer");
    ?>

<?php if($external_theme_exists == 1){?>
<script type="text/javascript"> 
$('#forgot_password').prop("target", "_top");
</script>
<?php }?>