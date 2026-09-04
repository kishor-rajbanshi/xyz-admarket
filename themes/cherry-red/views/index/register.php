<?php
$register_success      = intval($this->get_variable('register_success'));
$external_theme_exists = Configuration::get_instance()->read('external_theme_exists');

if($external_theme_exists == 1)
$baseUrl = Configuration::get_instance()->read('external_theme_url');
else
$baseUrl = BASE;

$active_theme   	  = $this->read_cookie_param('active_theme');

if($active_theme == "")
$active_theme   	  = Configuration::get_instance()->read('active_theme');

$admarketName 	  = Configuration::get_instance()->read('admarket_name');
$public_page_logo = Configuration::get_instance()->read('public_page_logo');

if(DEMO_MODE)
$public_page_logo = THEME_DIR_PATH.$active_theme."/images/logo.png";
else if($public_page_logo != "")
$public_page_logo = BASE.DATA_DIR."/logo/".$public_page_logo;

$cpatchaEnabled = Configuration::get_instance()->read('enable_captcha_verification');
$advertiser_dashboard_status = Configuration::get_instance()->read('advertiser_dashboard_status');
$publisher_dashboard_status  = Configuration::get_instance()->read('publisher_dashboard_status');


$this->dispatch("layout/header_assets/6");

if($register_success == 1)
{
    $email_verification_enabled = Configuration::get_instance()->read('email_verification_enabled');
?>
<script type='text/javascript'>
  $(document).ready(function() {

    $("#resend_email").click(function() {
        resend_email();
  });
  });

  function resend_email()
  {
  	 $(".resend-loading").show();

     $.ajax(
     {

  		type: "POST",
  		url: "<?php echo $this->make_url("index/email_confirmation_resend")?>",
  		success: function(msg)
  		{
  			$(".resend-loading").hide();

  			if(msg == 1)
  			message = '<?php echo $this->get_message("invalid operation");?>';
  			else if(msg == 3)
  			message = '<?php echo $this->get_message("email resend success");?>';
  		  	else
  			message = '<?php echo $this->get_message("email resend failed");?>';

  			if(msg != 3)
  			$('#resend_report').css("color","red");

  			$('#resend_report').html(message);
  		}
  	});
  }
</script>
<section class="normal-dialog">
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-8 col-sm-8 col-md-6 col-lg-5 col-xl-4 col-xxl-3 d-flex align-items-center normal-dialog-color">
              <div class="card-body p-4 p-lg-5 text-black">

									<div class="align-items-center mb-2 text-center">
											<a class = "dialog-popup-a" href="<?php echo $baseUrl;?>">
												<?php if($public_page_logo != ""){ ?>
												<img class = "dialog-popup img-fluid" src="<?php echo $public_page_logo;?>" alt="<?php echo $admarketName;?>" title="<?php echo $admarketName;?>" />
												<?php  } else { ?>
												<h2><?php echo $admarketName;?></h2>
												<?php } ?>
											</a>
									</div>

                  <?php
                  if($email_verification_enabled == 1)
                  {?>
                  	<p><?php echo $this->get_message('registration success theme',array('x'=>$baseUrl));?></p>

                  	<a id="resend_email" class="button-grad"><?php echo $this->get_label('resend email'); ?></a>
                  	<div class="resend-loading"><img src="images/load.gif" /></div>
                  	<div id="resend_report"></div>
                  <?php
                  }
                  else {?>
                  <p><?php echo $this->get_message('registration success page theme',array('x'=>$baseUrl));?></p>
                  <?php } ?>
              </div>

							<!--<a href="<?php echo BASE;?>"><div class="box-close">x</div></a>-->
            </div>
    </div>
  </div>
</section>

<?php } else { ?>

  <script type="text/javascript">
  function trim(stringData)
  {
  return stringData.replace(/(^\s*|\s*$)/, "");
  }

  function loadTerms()
  {
  	window.open("<?php echo $this->make_url("index/terms/1"); ?>","popup","menubar=1,resizable=1,width=800,height=450,scrollbars=1");
  }



function checkavailable()
{
	var username=trim($('#username').val());

   if(username =="")
   return;

   $('#imgload').show();
   $.ajax(
   {
		type: "GET",
		url: "<?php echo $this->make_url("user/check_availability/");?>"+username,
		success: function(msg)
		{
			$('#imgload').hide();
			$('#check').html(msg);
		}
	});

}
</script>
<?php
$validate = array(
		"username"=>array(
				"notNull"=>array($this->get_message("not null")),
				"isUsername"=>array($this->get_message("invalid input for user name"))
		),
		"password"=>array(
				"notNull"=>array($this->get_message("not null")),
				"minLength"=>array(Configuration::get_instance()->read('password_length'),$this->get_message("password length",array('x'=>Configuration::get_instance()->read('password_length'))))

		),
		"cpassword"=>array(
				"notNull"=>array($this->get_message("not null")),
				"isSame"=>array('password',$this->get_message("password mismatch"))

		),
		"fname"=>array(
				"notNull"=>array($this->get_message("not null"))

		),
		"lname"=>array(
				"notNull"=>array($this->get_message("not null"))

		),
		"phone"=>array(
				"notNull"=>array($this->get_message("not null")),
				"isPhone"=>array($this->get_message("invalid phone no"))
		),
		"email"=>array(
				"notNull"=>array($this->get_message("not null")),
				"isEmail"=>array($this->get_message("email format"))

		),
		"country"=>array(
				"notNull"=>array($this->get_message("not null"))

		),
		"terms"=>array(
				"isChecked"=>array($this->get_message("terms condition agree"))

		)
);


$countryResult = $this->get_result('resultCountry');

$recaptcha_public_key=$this->get_variable("recaptcha_public_key");
$terms=$this->get_variable('terms');
$countrycurrent=$this->get_variable("countrycurrent");


$registerseo=$this->get_seo_name('index/register');

if($registerseo !='')
$registerurl=BASE.$registerseo;
else
$registerurl=$this->make_url("index/register/".$this->get_variable('category'));


$loginseo = $this->get_seo_name('index/login');

if($loginseo != '')
$loginurl = BASE.$loginseo;
else
$loginurl = $this->make_url("index/login");


?>

<section class = "register">
  <div class="container py-5 h-100">
    <div class="row justify-content-center align-items-center h-100 mx-auto">
      <div class="card align-items-center col-lg-6 col-md-6 col-sm-12 col-12 ">
        <div class="col-12 d-flex align-items-center register-color">
          <div class="card-body p-4 text-black">

            <div class="align-items-center mb-3 text-center">
                <a class = "dialog-popup-a" href="<?php echo $baseUrl;?>">
                  <?php if($public_page_logo != ""){ ?>
                  <img class = "dialog-popup img-fluid" src="<?php echo $public_page_logo;?>" alt="<?php echo $admarketName;?>" title="<?php echo $admarketName;?>" />
                  <?php  } else { ?>
                  <h2><?php echo $admarketName;?></h2>

                  <?php } ?>
                </a>
            </div>

            <h5 class="fw-normal mb-3 text-center">
              <?php if($external_theme_exists != 1){ echo $this->get_label('user registration');} ?>
            </h5>

            <?php
              $form=$this->create_form();
              $form->start("register",$registerurl,"post",$validate);
            ?>

            <div class="form-outline mb-4">
              <div class="icon d-flex align-items-center justify-content-center"><span class="fa fa-user"></span></div>
              <input type="text" class="form-control register-input" name="username" id="username" value="<?php echo $this->get_variable('username');?>" onBlur="javascript:checkavailable();" placeholder="<?php echo $this->get_label('user name');?>" /><span class="compulsory" style="right:-12px;">*</span>
		<div id="imgload" style="width: 100%;display: none;">
    <img  src="images/load.gif" style="vertical-align: middle;" />
  </div>
  <div id="check" style="width: 100%;"></div>

              <span class="notification"><?php echo $this->get_label('username validation');?></span>
            </div>

            <div class="row">
              <div class="form-outline col-lg-6 col-md-6 col-sm-12 col-12 mb-4">
                <div class="icon d-flex align-items-center justify-content-center"><span class="fa fa-key"></span></div>
                <input class="form-control register-input" type="password" name="password" id="password" value="" placeholder="<?php echo $this->get_label('password');?>" /><span class="compulsory">*</span>
                <span class="notification"><?php echo $this->get_label('password length limit',array('x'=>Configuration::get_instance()->read('password_length')));?></span>
              </div>

              <div class="form-outline col-lg-6 col-md-6 col-sm-12 col-12 mb-4">
                <div class="icon d-flex align-items-center justify-content-center"><span class="fa fa-key"></span></div>
                <input class="form-control register-input" type="password" name="cpassword" id="cpassword" value="" placeholder="<?php echo $this->get_label('confirm password');?>" /><span class="compulsory">*</span>
              </div>
            </div>


    				<?php if(Configuration::get_instance()->read('enable_seperate_registration') ==1){?>
    					<div class="form-outline mb-4">
                <div class="icon d-flex align-items-center justify-content-center"><span class="fa fa-th-large"></span></div>
                <select class="form-select register-input" name="category" id="category">
                  <?php if($advertiser_dashboard_status == 1){ ?>
        		<option value="1" <?php if($this->get_variable('category')==1) { echo "selected"; } ?>><?php echo $this->get_label('advertiser');?></option>
                  <?php } 
                  if($publisher_dashboard_status == 1){ ?>
        		<option value="2" <?php if($this->get_variable('category')==2) { echo "selected"; } ?>><?php echo $this->get_label('publisher');?></option>
                  <?php }
                  if($advertiser_dashboard_status == 1 && $publisher_dashboard_status == 1){ ?>
        		<option value="3" <?php if($this->get_variable('category')==3) { echo "selected"; } ?>><?php echo $this->get_label('advertiser publisher');?></option>
                  <?php } ?>
      	        </select>
              </div>
            <?php }else{ ?>
    		        <input type="hidden" name="category" id="category" value="3" />
    		    <?php } ?>


            <div class="row">
              <div class="form-outline col-lg-6 col-md-6 col-sm-12 col-12 mb-4">
                <div class="icon d-flex align-items-center justify-content-center"><span class="fa fa-cube"></span></div>
                <input type="text" class="form-control register-input" name="fname" id="fname" value="<?php echo $this->get_variable('fname');?>" placeholder="<?php echo $this->get_label('first name');?>" /><span class="compulsory">*</span>
              </div>

              <div class="form-outline col-lg-6 col-md-6 col-sm-12 col-12 mb-4">
                <div class="icon d-flex align-items-center justify-content-center"><span class="fa fa-cube"></span></div>
                <input type="text" class="form-control register-input" name="lname" id="lname" value="<?php echo $this->get_variable('lname');?>" placeholder="<?php echo $this->get_label('last name');?>" /><span class="compulsory">*</span>
              </div>
            </div>

            <div class="row">
              <div class="form-outline col-lg-6 col-md-6 col-sm-12 col-12 mb-4">
                <div class="icon d-flex align-items-center justify-content-center"><span class="fa fa-phone"></span></div>
                <input type="text" class="form-control register-input" name="phone" id="phone" value="<?php echo $this->get_variable('phone');?>" placeholder="<?php echo $this->get_label('phone no');?>" /><span class="compulsory">*</span>
              </div>

              <div class="form-outline col-lg-6 col-md-6 col-sm-12 col-12 mb-4">
                <div class="icon d-flex align-items-center justify-content-center"><span class="fa fa-envelope"></span></div>
                <input type="text" class="form-control register-input" name="email" id="email" value="<?php echo $this->get_variable('email');?>" placeholder="<?php echo $this->get_label('email');?>" /><span class="compulsory">*</span>
              </div>
            </div>

            <div class="form-outline mb-4">
              <div class="icon d-flex align-items-center justify-content-center"><span class="fa fa-globe"></span></div>
              <select class="form-select register-input" name="country" id="country">
      					<?php foreach($countryResult as $key => $countryValue)
      					{
      						$code    = $countryValue['code'];
      						$country = $countryValue['name'];
      					?>
      						<option value="<?php echo $code;?>" <?php if($code == $this->get_variable('country')){ echo "selected"; }else if($code == $countrycurrent){echo "selected";}?>><?php echo $country;?></option>
      					<?php }?>
    					</select>
            </div>


            <?php if($cpatchaEnabled == 1){?>
              <div class="form-outline mb-4">
        				<script src='//www.google.com/recaptcha/api.js'></script>
        				<div class="g-recaptcha" data-sitekey="<?php echo $recaptcha_public_key; ?>"></div>
              </div>
    				<?php }?>

            <div class="form-outline mb-4">
      				<input class="form-check-input" type="checkbox" name="terms" id="terms" value="1" <?php if($terms == 1){ echo "checked";} ?> />
      				<a href="#" onClick="loadTerms();"><?php echo $this->get_label('terms conditions');?></a>
    		    </div>

            <div class="pt-1 mb-4">
              <input class="btn btn-dark btn-lg btn-block register-submit" type="submit" name="user_register" value="<?php echo $this->get_label('submit');?>" />
            </div>


            <div class="login-register">
              <p>
                <bdi><?php echo $this->get_label('already registered');?></bdi>
                <a href="<?php echo $loginurl;?>"><?php echo $this->get_label('login');?></a>
              </p>
            </div>

            <?php $form->end(); ?>
          </div>


          <a href="<?php echo BASE;?>"><div class="box-close">x</div></a>






        </div>
      </div>
    </div>
  </div>
</section>

<?php }

$this->dispatch("layout/footer_assets");
?>
