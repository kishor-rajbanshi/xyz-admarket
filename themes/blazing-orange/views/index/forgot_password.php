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
$validate=array(
		"username"=>array(
		"notNull"=>array($this->get_message("not null"))
		)
);

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
						
echo Configuration::get_instance()->read('google_analytics_code');
						

?>







<div class="header_div <?php if($external_theme_exists!=1){?>header_margin<?php }?>">
<div class="container">

<h2 class="login_header_inner"><?php echo $this->get_label('forgot password');?></h2></div></div>


<section id="contact-page">

  <div class="container label_style special-label">
   <div class="col-md-12 col-sm-12 col-xs-12 box_style">
   <div class="col-md-12 col-sm-12 col-xs-12 box_div">



<?php 
$form=$this->create_form();
$form->start("forgot_password",$this->make_url("index/forgot_password"),"post",$validate); ?>


<div class="col-md-12 col-sm-12 col-xs-12">
<div class="form-group">
<label><?php echo $this->get_label('your username');?> <span class="compulsory">*</span></label>

<input class="form-control" type="text" name="username" id="username" value="<?php echo $this->get_variable('username');?>" style="max-width: 250px;" />
</div>


<div class="form-group">
<input class="btn-primary" type="submit" name="submit" value="<?php echo $this->get_label('submit');?>">
</div>
</div>
<?php $form->end(); ?>
</div>
</div>
</div>
</section>

<?php
if($external_theme_exists!=1)
	$this->dispatch("layout/footer");
?>