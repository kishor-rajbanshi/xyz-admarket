<?php
$active_theme   	  = $this->read_cookie_param('active_theme');

if($active_theme == "")
$active_theme   	  = Configuration::get_instance()->read('active_theme');

$captchaEnabled = Configuration::get_instance()->read('enable_captcha_verification');
$recaptcha_public_key=$this->get_variable("recaptcha_public_key");
$admarketName    	= Configuration::get_instance()->read('admarket_name');
$public_page_logo = Configuration::get_instance()->read('public_page_logo');

if(DEMO_MODE)
$public_page_logo = THEME_DIR_PATH.$active_theme."/images/logo.png";
else if($public_page_logo != "")
$public_page_logo = BASE.DATA_DIR."/logo/".$public_page_logo;

$external_theme_exists = Configuration::get_instance()->read('external_theme_exists');

if($external_theme_exists == 1)
$baseUrl = Configuration::get_instance()->read('external_theme_url');
else
$baseUrl = BASE;

$validate = array(
		"username"=>array(
		"notNull"=>array($this->get_message("not null"))
		)
);

$registerseo = $this->get_seo_name('index/register');

if($registerseo != '')
$registerurl = BASE.$registerseo;
else
$registerurl = $this->make_url("index/register");

$pwdseo      = $this->get_seo_name('index/forgot_password');

if($pwdseo != "")
$pwdurl = BASE.$pwdseo;
else
$pwdurl = $this->make_url("index/forgot_password");

$this->dispatch("layout/header_assets/7");
?>
<section class="forgot-password">
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="card align-items-center col-lg-5 col-md-6 col-sm-12 col-12">
            <div class="col-12  forgot-password-color">
              <div class="card-body p-4 p-lg-5 text-black">
								<?php
								$form=$this->create_form();
								$form->start("forgot_password",$pwdseo,"post",$validate);
								?>
									<div class="align-items-center mb-2 text-center">
											<a class = "dialog-popup-a" href="<?php echo $baseUrl;?>">
												<?php if($public_page_logo != ""){ ?>
												<img class = "dialog-popup img-fluid" src="<?php echo $public_page_logo;?>" alt="<?php echo $admarketName;?>" title="<?php echo $admarketName;?>" />
												<?php  } else { ?>
												<h2><?php echo $admarketName;?></h2>
												<?php } ?>
											</a>
									</div>

    						  <h5 class="fw-normal mb-3 text-center">
									<?php echo $this->get_label('forgot password'); ?>
									</h5>

                  <div class="form-outline mb-4">
										<div class="icon d-flex align-items-center justify-content-center"><span class="fa fa-user"></span></div>
                  	<input type="text" class="form-control forgot-password-input" name="username" id="username" value="<?php echo $this->get_variable('username');?>" placeholder="<?php echo $this->get_label('your username');?>" />
                  </div>
									<?php if($captchaEnabled == 1){?>
										<div class="form-outline mb-4">
											<script src='//www.google.com/recaptcha/api.js'></script>
											<div class="g-recaptcha" data-sitekey="<?php echo $recaptcha_public_key; ?>"></div>
										</div>
									<?php }?>

                  <div class="pt-1 mb-4">
                    <input class="btn btn-dark btn-lg btn-block forgot-password-submit" type="submit" name="password_submit" value="<?php echo $this->get_label('submit');?>" />
                  </div>
								<?php $form->end();	?>
              </div>

							<a href="<?php echo BASE;?>"><div class="box-close">x</div></a>
            </div>
        </div>
    </div>
  </div>
</section>
<?php $this->dispatch("layout/footer_assets");?>
