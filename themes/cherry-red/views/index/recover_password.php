<?php
$external_theme_exists = Configuration::get_instance()->read('external_theme_exists');

if($external_theme_exists == 1)
$baseUrl = Configuration::get_instance()->read('external_theme_url');
else
$baseUrl = BASE;

$active_theme   	  = $this->read_cookie_param('active_theme');

if($active_theme == "")
$active_theme   	  = Configuration::get_instance()->read('active_theme');

$admarketName           = Configuration::get_instance()->read('admarket_name');
$public_page_logo       = Configuration::get_instance()->read('public_page_logo');

if(DEMO_MODE)
$public_page_logo = THEME_DIR_PATH.$active_theme."/images/logo.png";
else if($public_page_logo != "")
$public_page_logo = BASE.DATA_DIR."/logo/".$public_page_logo;

$this->dispatch("layout/header_assets");
?>
<section class="normal-dialog">
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-9 col-sm-8 col-md-6 col-lg-5 col-xl-4 col-xxl-3 d-flex align-items-center normal-dialog-color">
              <div class="card-body p-4 text-black">

									<div class="align-items-center mb-3">
											<a class = "dialog-popup-a" href="<?php echo $baseUrl;?>">
												<?php if($public_page_logo != ""){ ?>
												<img class = "dialog-popup img-fluid" src="<?php echo $public_page_logo;?>" alt="<?php echo $admarketName;?>" title="<?php echo $admarketName;?>" />
												<?php  } else { ?>
												<h2><?php echo $admarketName;?></h2>
												<?php } ?>
											</a>
									</div>

                  <?php
                  $msg	= $this->get_variable('message');

                  if($msg == 1){?>
                  <p><?php echo $this->get_message('password recovery success theme',array('x'=>$baseUrl));?></p>
                  <?php } else if($msg == 2){?>
                  <p><?php echo $this->get_message('invalid operation theme',array('x'=>$baseUrl));?></p>
                  <?php } else if($msg == 3){?>
                  <p><?php echo $this->get_message('password reset link expired theme',array('x'=>$baseUrl));?></p>
                  <?php } ?>
              </div>

            </div>

    </div>
  </div>
</section>
<?php $this->dispatch("layout/footer_assets");?>
