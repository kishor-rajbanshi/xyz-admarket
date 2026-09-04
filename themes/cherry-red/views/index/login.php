<?php
$active_theme   	  = $this->read_cookie_param('active_theme');

if($active_theme == "")
$active_theme   	  = Configuration::get_instance()->read('active_theme');

$admarketName    	= Configuration::get_instance()->read('admarket_name');
$public_page_logo = Configuration::get_instance()->read('public_page_logo');
$captchaEnabled = Configuration::get_instance()->read('enable_captcha_verification');
$recaptcha_public_key=$this->get_variable("recaptcha_public_key");

if(DEMO_MODE)
$public_page_logo = THEME_DIR_PATH.$active_theme."/images/logo.png";
else if($public_page_logo != "")
$public_page_logo = BASE.DATA_DIR."/logo/".$public_page_logo;

$external_theme_exists = Configuration::get_instance()->read('external_theme_exists');

if($external_theme_exists == 1)
$baseUrl = Configuration::get_instance()->read('external_theme_url');
else
$baseUrl = BASE;

$logedin = intval($this->get_variable('log_success'));

if($logedin == 1){?>
<script type="text/javascript">
top.location.href ='<?php echo $this->make_url("dashboard/advertiser_home")?>';
</script>
<?php
}

$validate = array(
		"username1"=>array("notNull"=>array($this->get_message("not null"))),
		"password1"=>array("notNull"=>array($this->get_message("not null")))
);

$registerseo = $this->get_seo_name('index/register');

if($registerseo != '')
$registerurl = BASE.$registerseo;
else
$registerurl = $this->make_url("index/register");


$passwordseo = $this->get_seo_name('index/forgot_password');

if($passwordseo != '')
$passwordurl=BASE.$passwordseo;
else
$passwordurl=$this->make_url("index/forgot_password");


$loginseo = $this->get_seo_name('index/login');

if($loginseo != '')
$loginurl = BASE.$loginseo;
else
$loginurl = $this->make_url("index/login");

$this->dispatch("layout/header_assets/10");

?>
<section class="login">
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="card align-items-center col-lg-5 col-md-6 col-sm-12 col-12">
            <div class="col-12 login-box-color">
              <div class="card-body p-4 p-lg-5 text-black">

                <?php
                	$form=$this->create_form();
                	$form->start("login",$loginurl,"post",$validate);
                ?>
                    <div class="align-items-center mb-3 text-center">
                        <a class = "dialog-popup-a" href="<?php echo $baseUrl;?>">
                          <?php if($public_page_logo != ""){ ?>
                          <img class = "dialog-popup img-fluid" src="<?php echo $public_page_logo;?>" alt="<?php echo $admarketName;?>" title="<?php echo $admarketName;?>" />
                          <?php  } else { ?>
                          <h2><?php echo $admarketName;?></h2>
                          <?php } ?>
                        </a>
                    </div>

                    <h5 class="fw-normal mb-3 pb-1 text-center">
                      <?php echo $this->get_label("sign into your account");?>
                    </h5>

                    <div class="form-outline mb-4">
                      <div class="icon d-flex align-items-center justify-content-center"><span class="fa fa-user"></span></div>
                        <input type="text" class="form-control login-input" name="username1" id="username1"  placeholder="<?php echo $this->get_label('user name');?>" value="<?php echo $this->get_variable("username1");?>" />
                    </div>

                    <div class="form-outline mb-4">
                      <div class="icon d-flex align-items-center justify-content-center"><span class="fa fa-lock"></span></div>
                      <input type="password" class="form-control login-input" name="password1" id="password1"  placeholder="<?php echo $this->get_label('password');?>" value="<?php echo $this->get_variable("password1");?>" />
                    </div>
										<?php if($captchaEnabled == 1){?>
				              <div class="form-outline mb-4">
				        				<script src='//www.google.com/recaptcha/api.js'></script>
				        				<div class="g-recaptcha" data-sitekey="<?php echo $recaptcha_public_key; ?>"></div>
				              </div>
				    				<?php }?>

                    <div class="pt-1 mb-4">
                        <input class="btn btn-dark btn-lg btn-block login-submit" type="submit" name="submit_login" id="submit_login" value="<?php echo $this->get_label('login');?>" />
                    </div>
  			            <div class="login-register">
                      <p>
                        <bdi><?php echo $this->get_label('dont have an account');?></bdi>
                        <a href="<?php echo $registerurl;?>"><?php echo $this->get_label('signup');?></a>
                      </p>
                      <a class="small text-muted forgot-password-url" href="<?php echo $passwordurl;?>"><?php echo $this->get_label('forgot password');?></a>
                    </div>

                  <?php $form->end(); ?>

              </div>

              <a href="<?php echo BASE;?>"><div class="box-close">x</div></a>
            </div>
        </div>
    </div>
  </div>
</section>
<?php $this->dispatch("layout/footer_assets"); ?>
