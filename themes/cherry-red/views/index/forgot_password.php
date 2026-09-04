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
?>
<?php 

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


<?php if($external_theme_exists == 1)
{
						if(isset($_POST['password_submit'])){?>
						  <div class="container" style="text-align: center;margin: 10px;">
	               		  <div style="width: 100%;float: left;">	
						  <a href="<?php echo Configuration::get_instance()->read('exsternal_theme_url');?>">
						  <?php if(Configuration::get_instance()->read('public_page_logo') !=""){?>
					      <img style="max-width: 235px;max-height: 100px;" src="<?php echo BASE.DATA_DIR;?>/logo/<?php echo Configuration::get_instance()->read('public_page_logo');?>" alt="<?php echo Configuration::get_instance()->read('admarket_name');?>" title="<?php echo Configuration::get_instance()->read('admarket_name');?>" />
					      <?php  }else{ ?>
					      <div style="white-space: nowrap;"><?php echo Configuration::get_instance()->read('admarket_name');?></div>
					      <?php } ?>
					      </a>	               
	                      </div>
	                      </div>
	                      
	               		<?php }?>
<?php }?>



<div class="header_div">
<div class="container">

<h2 class="page_header"><?php echo $this->get_label('forgot password');?></h2> <?php if($external_theme_exists != 1){?><span class="span_link"><a  href="<?php echo BASE;?>"><?php echo $this->get_label('home');?></a> / <a href="<?php echo $advurl;?>"><?php echo $this->get_label('advertiser');?></a> / <a href="<?php echo $puburl;?>"><?php echo $this->get_label('publisher');?></a> </span><?php }?>

</div>
</div>

<section id="contact-page">

<div class="container">
<div class="box_style col-md-12 col-sm-12 col-xs-12" style="margin-top:20px; margin-bottom:20px;min-height: 131px;">


<?php 
$form=$this->create_form();
$form->start("forgot_password",$this->make_url("index/forgot_password"),"post",$validate); ?>


<div class="col-md-12 col-sm-12 col-xs-12">
<div class="form-group">
<label><?php echo $this->get_label('your username');?> <span class="compulsory">*</span></label>

<input class="form-control" type="text" name="username" id="username" value="<?php echo $this->get_variable('username');?>" style="max-width: 250px;" />
</div>


<div class="form-group">
<input class="btn-primary" type="submit" name="password_submit" value="<?php echo $this->get_label('submit');?>">
</div>
</div>
<?php $form->end(); ?>
</div>
</div>
</section>

<?php
if($external_theme_exists!=1)
$this->dispatch("layout/footer");
?>
<?php if($external_theme_exists == 1){?>
<script type="text/javascript"> 
$('#forgot_password').prop("target", "_top");
</script>
<?php }?>