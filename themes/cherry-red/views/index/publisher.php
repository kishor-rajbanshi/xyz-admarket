<?php
$this->dispatch("layout/header/18");

$login = 0;

if(LoginHelper::validate_user_login())
$login = 1;

$adcodeCreateURL = "";

if($login == 1)
{
	$adcodeCreateURL = $this->make_base_url("adunit/create");
}
else 
{
	$loginseo      = $this->get_seo_name('index/login');

	if($loginseo != '')
	$loginurl      = BASE.$loginseo;
	else
	$loginurl      = $this->make_url("index/login");

	$adcodeCreateURL = $loginurl;
}


$cpc_enabled    = $this->get_addon_status('cpc_enabled');
$cpm_enabled    = $this->get_addon_status('cpm_enabled');
$cpa_enabled    = $this->get_addon_status('cpa_enabled');
$cpd_enabled    = $this->get_addon_status('sponsored_enabled');

$admarketName            = Configuration::get_instance()->read('admarket_name');

$text_ads_enabled    = Configuration::get_instance()->read('text-ads_enabled');
$text_image_enabled  = $this->get_addon_status('text-image-ads_enabled');
$native_ads_enabled  = $this->get_addon_status('native-ad-display_enabled');
$directlink_enabled  = $this->get_addon_status('direct-link-ads_enabled');
$inpage_push_enabled = $this->get_addon_status('inpage-push-ads_enabled');
$interstitial_enabled    = $this->get_addon_status('interstitial_enabled');
$pop_ads_enabled         = $this->get_addon_status('pop-ads_enabled');
$skin_ads_enabled        = $this->get_addon_status('skin-ads_enabled');
$video_ads_enabled       = $this->get_addon_status('video-ads_enabled');
$affiliate_ads_enabled = $this->get_addon_status('affiliate-ads_enabled');

$count = 0;

$pricingString = "";
$paymentOptionString = "";

if($cpc_enabled == 1)
$pricingString = "CPC";	

if($cpm_enabled == 1)
{
	if($pricingString != "")
	$pricingString.= " / ";

	$pricingString.= "CPM";	
}

if($cpa_enabled == 1)
{
	if($pricingString != "")
	$pricingString.= " / ";

	$pricingString.= "CPA";	
}

if($cpd_enabled == 1)
{
	if($pricingString != "")
	$pricingString.= " / ";

	$pricingString.= "CPD";	
}

$row = $this->get_result('row');

foreach($row as $key => $value)
{
	if($paymentOptionString != "")
	$paymentOptionString.= " / ";

	$paymentOptionString.= $this->get_label($value['name']);				
}
?>
<!--=====================Home Banner publisher Start========================-->
<section class="section publisher-banner">
	<div class="shapes-container hero-header">
		<div class="shape" data-aos="fade-down-left" data-aos-duration="1500" data-aos-delay="100"></div>
		<div class="shape" data-aos="fade-down" data-aos-duration="1000" data-aos-delay="100"></div>
		<div class="shape" data-aos="fade-up-right" data-aos-duration="1000" data-aos-delay="200"></div>
		<div class="shape" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200"></div>
		<div class="shape" data-aos="fade-down-left" data-aos-duration="1000" data-aos-delay="100"></div>
		<div class="shape" data-aos="fade-down-left" data-aos-duration="1000" data-aos-delay="100"></div>
		<div class="shape" data-aos="zoom-in" data-aos-duration="1000" data-aos-delay="300"></div>
		<div class="shape" data-aos="fade-down-right" data-aos-duration="500" data-aos-delay="200"></div>
		<div class="shape" data-aos="fade-down-right" data-aos-duration="500" data-aos-delay="100"></div>
		<div class="shape" data-aos="zoom-out" data-aos-duration="2000" data-aos-delay="500"></div>
		<div class="shape" data-aos="fade-up-right" data-aos-duration="500" data-aos-delay="200"></div>
		<div class="shape" data-aos="fade-down-left" data-aos-duration="500" data-aos-delay="100"></div>
		<div class="shape" data-aos="fade-up" data-aos-duration="500" data-aos-delay="0"></div>
		<div class="shape" data-aos="fade-down" data-aos-duration="500" data-aos-delay="0"></div>
		<div class="shape" data-aos="fade-up-right" data-aos-duration="500" data-aos-delay="100"></div>
		<div class="shape" data-aos="fade-down-left" data-aos-duration="500" data-aos-delay="0"></div>
	</div>			
	<div class="container">
		<div class="row align-items-center">
			<div class="col-md-6 order-2 order-md-1 col-lg-6 order-lg-1 text-md-left publisher-banner-content">
				<h1 class="text-white font-weight-bold animated" data-aos="zoom-in">
					<?php echo $this->get_label('publisher');?>
				</h1>
				<p class="text-white animated" data-aos="zoom-in">
					<?php echo $this->get_label('publisher page title');?>
				</p>
				<div class="d-flex gap-2 banner-button">
					<a href="<?php echo $adcodeCreateURL; ?>" class="start-btn animated" data-aos="zoom-in">
						<?php echo $this->get_label('start earning'); ?><span></span><span></span><span></span><span></span>
					</a>
				</div>
			</div>			
			<div class="col-lg-6 col-md-6 order-1 order-md-2 order-lg-2 publisher-banner-image">
				<img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/publisher-page-main-banner.webp" class="img-fluid animation animated" data-aos="zoom-out" title="<?php echo $this->get_label('publisher');?>" alt="<?php echo $this->get_label('publisher');?>" />
			</div>
		</div>
	</div>
</section>

<!--=====================Ad format Section start========================-->
<section class="ads-section">	
	<div class="container py-5">

		<div class="section-title">					
			<div class="col-12 swich-bg text-center">
				<h2 class="text-center pb-2" data-aos="zoom-in" data-aos-delay="150">
					<?php echo $this->get_label_format($this->get_label('ad format title'));?>
				</h2>
				<div class="devider" data-aos="zoom-in" data-aos-delay="180"></div>
			</div>	
		</div>

		<ul class="nav nav-tabs d-none d-lg-flex" id="myTab" role="tablist">
			
			<li class="nav-item" role="presentation">
				<button class="nav-link active" id="banner-tab" data-bs-toggle="tab" data-bs-target="#banner-tab-pane" type="button" role="tab" aria-controls="banner-tab-pane" aria-selected="true">
					<?php  echo $this->get_label('publisher banner ads');?>
				</button>
			</li>

			<?php if($text_ads_enabled == 1){?>		
				<li class="nav-item" role="presentation">
					<button class="nav-link" id="text-tab" data-bs-toggle="tab" data-bs-target="#text-tab-pane" type="button" role="tab" aria-controls="text-tab-pane" aria-selected="false">
						<?php  echo $this->get_label('publisher text ads');?>
					</button>
				</li>
			<?php } ?>	
			<?php if($text_image_enabled == 1){?>			
				<li class="nav-item" role="presentation">
					<button class="nav-link" id="textimage-tab" data-bs-toggle="tab" data-bs-target="#textimage-tab-pane" type="button" role="tab" aria-controls="textimage-tab-pane" aria-selected="false">
						<?php  echo $this->get_label('publisher textimage ads');?>
					</button>
				</li>
			<?php } ?>	

			<?php if($directlink_enabled == 1){?>	
				<li class="nav-item" role="presentation">
					<button class="nav-link" id="directlink-tab" data-bs-toggle="tab" data-bs-target="#directlink-tab-pane" type="button" role="tab" aria-controls="directlink-tab-pane" aria-selected="false">
						<?php  echo $this->get_label('publisher directlink ads');?>
					</button>
				</li>
			<?php } ?>	

			<?php if($inpage_push_enabled == 1){?>	
				<li class="nav-item" role="presentation">
					<button class="nav-link" id="inpagepush-tab" data-bs-toggle="tab" data-bs-target="#inpagepush-tab-pane" type="button" role="tab" aria-controls="inpagepush-tab-pane" aria-selected="false">
						<?php  echo $this->get_label('publisher inpage push ads');?>
					</button>
				</li>
			<?php } ?>	

			<?php if($pop_ads_enabled == 1){?>
			<li class="nav-item" role="presentation">
				<button class="nav-link" id="pop-tab" data-bs-toggle="tab" data-bs-target="#pop-tab-pane" type="button" role="tab" aria-controls="pop-tab-pane" aria-selected="false">
					<?php  echo $this->get_label('publisher pop ads');?>
				</button>
			</li>
			<?php }?>
			
			<?php if($interstitial_enabled == 1){?>
				<li class="nav-item" role="presentation">
					<button class="nav-link" id="interstitial-tab" data-bs-toggle="tab" data-bs-target="#interstitial-tab-pane" type="button" role="tab" aria-controls="interstitial-tab-pane" aria-selected="false">
						<?php  echo $this->get_label('publisher interstitial ads');?>
					</button>
				</li>
			<?php }?>

			<?php if($skin_ads_enabled == 1){?>
				<li class="nav-item" role="presentation">
					<button class="nav-link" id="skin-tab" data-bs-toggle="tab" data-bs-target="#skin-tab-pane" type="button" role="tab" aria-controls="skin-tab-pane" aria-selected="false">
						<?php  echo $this->get_label('publisher skin ads');?>
					</button>
				</li>
			<?php }?>
			
			<?php if($video_ads_enabled == 1){?>
				<li class="nav-item" role="presentation">
					<button class="nav-link" id="video-tab" data-bs-toggle="tab" data-bs-target="#video-tab-pane" type="button" role="tab" aria-controls="video-tab-pane" aria-selected="false">
						<?php echo $this->get_label('publisher video ads');?>
					</button>
				</li>
			<?php }?>
			<?php if($affiliate_ads_enabled == 1){?>
				<li class="nav-item" role="presentation">
					<button class="nav-link" id="affiliate-tab" data-bs-toggle="tab" data-bs-target="#affiliate-tab-pane" type="button" role="tab" aria-controls="affiliate-tab-pane" aria-selected="false">
						<?php echo $this->get_label('publisher affiliate ads');?>
					</button>
				</li>
			<?php }?>
		</ul>
		
	<div class="tab-content tab-custom accordion" id="myTabContent">
		<div class="tab-pane fade show active accordion-item" id="banner-tab-pane" role="tabpanel" aria-labelledby="banner-tab" tabindex="0">
			<h2 class="accordion-header d-lg-none" id="headingOne">
				<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
					<?php  echo $this->get_label('publisher banner ads');?>
				</button>
			</h2>
			<div id="collapseOne" class="accordion-collapse collapse show d-lg-block" aria-labelledby="headingOne" data-bs-parent="#myTabContent">
				<div class="accordion-body">
					<div class="row align-items-center">
						<div class="col-lg-8 col-md-6" data-aos="zoom-in"><h3>
							<?php  echo $this->get_label('publisher banner ads');?></h3>
							<p><?php  echo $this->get_label('publisher banner ads section',array('x'=>$admarketName));?></p>
						</div>
						<div class="col-lg-4 col-md-6" data-aos="zoom-out">
							<img class="img-fluid" src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/banner-ads.png"/>
						</div>
					</div>
				</div>
			</div>
		</div>	

		<?php if($text_ads_enabled == 1){?>		
			<div class="tab-pane fade accordion-item" id="text-tab-pane" role="tabpanel" aria-labelledby="text-tab" tabindex="1">	
				<h2 class="accordion-header d-lg-none" id="headingTwo">
					<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
						<?php  echo $this->get_label('publisher text ads');?>
					</button>
				</h2>
				<div id="collapseTwo" class="accordion-collapse collapse d-lg-block" aria-labelledby="headingTwo" data-bs-parent="#myTabContent">
					<div class="accordion-body">
						<div class="row align-items-center">
							<div class="col-lg-8 col-md-6" data-aos="zoom-in">
								<h3><?php  echo $this->get_label('publisher text ads');?></h3>
								<p><?php  echo $this->get_label('publisher text ads section',array('x'=>$admarketName));?></p>
							</div>
							<div class="col-lg-4 col-md-6" data-aos="zoom-out">
								<img class="img-fluid" src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/text-ads.png"/>
							</div>
						</div>
					</div>
				</div>
			</div>	
		<?php } ?>		
		
		
		<?php if($text_image_enabled == 1){?>	
			<div class="tab-pane fade accordion-item" id="textimage-tab-pane" role="tabpanel" aria-labelledby="textimage-tab" tabindex="2">
				<h2 class="accordion-header d-lg-none" id="headingThree">
					<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
						<?php  echo $this->get_label('publisher textimage ads');?>
					</button>
				</h2>
				<div id="collapseThree" class="accordion-collapse collapse d-lg-block" aria-labelledby="headingThree" data-bs-parent="#myTabContent">
					<div class="accordion-body">
						<div class="row align-items-center">
							<div class="col-lg-8 col-md-6" data-aos="zoom-in"><h3>
								<?php  echo $this->get_label('publisher textimage ads');?></h3>
								<p><?php  echo $this->get_label('publisher textimage ads section',array('x'=>$admarketName));?></p>
							</div>
							<div class="col-lg-4 col-md-6" data-aos="zoom-out">
								<img class="img-fluid" src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/text+image-ads.png"/>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>


		<?php if($directlink_enabled == 1){?>			
			<div class="tab-pane fade accordion-item" id="directlink-tab-pane" role="tabpanel" aria-labelledby="directlink-tab" tabindex="3">
				<h2 class="accordion-header d-lg-none" id="headingFour">
					<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
						<?php  echo $this->get_label('publisher directlink ads');?>
					</button>
				</h2>
				<div id="collapseFour" class="accordion-collapse collapse d-lg-block" aria-labelledby="headingFour" data-bs-parent="#myTabContent">
					<div class="accordion-body">
						<div class="row align-items-center">
							<div class="col-lg-8 col-md-6" data-aos="zoom-in"><h3>
								<?php  echo $this->get_label('publisher directlink ads');?></h3>
								<p><?php  echo $this->get_label('publisher directlink ads section',array('x'=>$admarketName));?></p>
							</div>
							<div class="col-lg-4 col-md-6" data-aos="zoom-out">
								<img class="img-fluid" src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/direct-link-ads.png"/>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>


		<?php if($pop_ads_enabled == 1){?>
			<div class="tab-pane fade accordion-item" id="pop-tab-pane" role="tabpanel" aria-labelledby="pop-tab" tabindex="4">
				<h2 class="accordion-header d-lg-none" id="headingFive">
					<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
						<?php  echo $this->get_label('publisher pop ads');?>
					</button>
				</h2>
				<div id="collapseFive" class="accordion-collapse collapse d-lg-block" aria-labelledby="headingFive" data-bs-parent="#myTabContent">
					<div class="accordion-body">
						<div class="row align-items-center">
							<div class="col-lg-8 col-md-6" data-aos="zoom-in"><h3>
								<?php  echo $this->get_label('publisher pop ads');?></h3>
								<p><?php  echo $this->get_label('publisher pop ads section',array('x'=>$admarketName));?></p>
							</div>
							<div class="col-lg-4 col-md-6" data-aos="zoom-out">
								<img class="img-fluid" src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/pop-ads.png"/>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>


		<?php if($interstitial_enabled == 1){?>
			<div class="tab-pane fade accordion-item" id="interstitial-tab-pane" role="tabpanel" aria-labelledby="interstitial-tab" tabindex="5">
				<h2 class="accordion-header d-lg-none" id="headingSix">
					<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
						<?php  echo $this->get_label('publisher interstitial ads');?>
					</button>
				</h2>
				<div id="collapseSix" class="accordion-collapse collapse d-lg-block" aria-labelledby="headingSix" data-bs-parent="#myTabContent">
					<div class="accordion-body">
						<div class="row align-items-center">
							<div class="col-lg-8 col-md-6" data-aos="zoom-in">
								<h3><?php  echo $this->get_label('publisher interstitial ads');?></h3>
								<p><?php  echo $this->get_label('publisher interstitial ads section',array('x'=>$admarketName));?></p>
							</div>
							<div class="col-lg-4 col-md-6" data-aos="zoom-out">
								<img class="img-fluid" src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/interstitial-ads.png"/>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php }?>

		<?php if($skin_ads_enabled == 1){?>
			<div class="tab-pane fade accordion-item" id="skin-tab-pane" role="tabpanel" aria-labelledby="skin-tab" tabindex="6">
				<h2 class="accordion-header d-lg-none" id="headingSeven">
					<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
						<?php  echo $this->get_label('publisher skin ads');?>
					</button>
				</h2>
				<div id="collapseSeven" class="accordion-collapse collapse d-lg-block" aria-labelledby="headingSeven" data-bs-parent="#myTabContent">
					<div class="accordion-body">
						<div class="row align-items-center">
						<div class="col-lg-8 col-md-6" data-aos="zoom-in">
							<h3><?php  echo $this->get_label('publisher skin ads');?></h3>
							<p><?php  echo $this->get_label('publisher skin ads section',array('x'=>$admarketName));?></p></div>
							<div class="col-lg-4 col-md-6" data-aos="zoom-out">
								<img class="img-fluid" src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/skin-ads.png"/>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php }?>

		<?php if($video_ads_enabled == 1){?>
			<div class="tab-pane fade accordion-item" id="video-tab-pane" role="tabpanel" aria-labelledby="video-tab" tabindex="7">
				<h2 class="accordion-header d-lg-none" id="headingEight">
					<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
						<?php  echo $this->get_label('publisher video ads');?>
					</button>
				</h2>
				<div id="collapseEight" class="accordion-collapse collapse d-lg-block" aria-labelledby="headingEight" data-bs-parent="#myTabContent">
					<div class="accordion-body">
						<div class="row align-items-center">
							<div class="col-lg-8 col-md-6" data-aos="zoom-in">
								<h3><?php  echo $this->get_label('publisher video ads');?></h3>
								<p><?php  echo $this->get_label('publisher video ads section',array('x'=>$admarketName));?></p>
							</div>
							<div class="col-lg-4 col-md-6" data-aos="zoom-out">
								<img class="img-fluid" src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/video-ads.png"/>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>	

		<?php if($inpage_push_enabled == 1){?>
			<div class="tab-pane fade accordion-item" id="inpagepush-tab-pane" role="tabpanel" aria-labelledby="inpagepush-tab" tabindex="9">
				<h2 class="accordion-header d-lg-none" id="headingNine">
					<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseNine" aria-expanded="false" aria-controls="collapseNine">
						<?php  echo $this->get_label('publisher inpage push ads');?>
					</button>
				</h2>
				<div id="collapseNine" class="accordion-collapse collapse d-lg-block" aria-labelledby="headingNine" data-bs-parent="#myTabContent">
					<div class="accordion-body">
						<div class="row align-items-center">
							<div class="col-lg-8 col-md-6" data-aos="zoom-in">
								<h3><?php  echo $this->get_label('publisher inpage push ads');?></h3>
								<p><?php  echo $this->get_label('publisher inpage push ads section',array('x'=>$admarketName));?></p>
							</div>
							<div class="col-lg-4 col-md-6" data-aos="zoom-out">
								<img class="img-fluid" src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/inpage-push-ads.png"/>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>	
		<?php if($affiliate_ads_enabled == 1){?>
			<div class="tab-pane fade accordion-item" id="affiliate-tab-pane" role="tabpanel" aria-labelledby="affiliate-tab" tabindex="10">
				<h2 class="accordion-header d-lg-none" id="headingTen">
					<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTen" aria-expanded="false" aria-controls="collapseTen">
						<?php  echo $this->get_label('publisher affiliate ads');?>
					</button>
				</h2>
				<div id="collapseTen" class="accordion-collapse collapse d-lg-block" aria-labelledby="headingTen" data-bs-parent="#myTabContent">
					<div class="accordion-body">
						<div class="row align-items-center">
							<div class="col-lg-8 col-md-6" data-aos="zoom-in">
								<h3><?php  echo $this->get_label('publisher affiliate ads');?></h3>
								<p><?php  echo $this->get_label('publisher affiliate ads section',array('x'=>$admarketName));?></p>
							</div>
							<div class="col-lg-4 col-md-6" data-aos="zoom-out">
								<img class="img-fluid" src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/affiliate-ads.png"/>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>	
		</div>
	</div>
</section>
<!--=====================Publisher features section start========================-->
<section class="publisher-features-section">
	<div class="container">				
		<div class="section-title position-relative text-center mb-5 pb-2">
			<h2 class="mt-2 pb-2 text-white" data-aos="zoom-in" data-aos-delay="150">
				<?php echo $this->get_label_format($this->get_label('publisher features'));?>
			</h2>
			<div class="devider-white" alt="" data-aos="zoom-out" data-aos-delay="180"></div>
		</div>
				
		<div class="row gutter-40 align-items-center">
			<div class="col-12 col-lg-12">
				<div class="featured-servcies-tabs row">
					<div class="tab-btn-wrapper col-lg-6 aos-init" data-aos="fade-up" data-aos-delay="150">
						<div class="featured-services-tab-single">
							<div class="tab-single_icon">
								<i class="bx bxs-time"></i>
							</div>
							<div class="tab-single_content">
								<h5><?php  echo $this->get_label('publisher features title one');?></h5>
								<p><?php  echo $this->get_label('publisher features description one');?></p>
							</div>
						</div>
						<span class="count-prefix">0<?php echo ++$count; ?></span>
					</div>
						
					<div class="tab-btn-wrapper col-lg-6  aos-init" data-aos="fade-up" data-aos-delay="150">
						<div class="featured-services-tab-single">
							<div class="tab-single_icon">
								<i class='bx bxs-badge-dollar'></i>
							</div>
							<div class="tab-single_content">
								<h5><?php  echo $this->get_label('publisher features title two');?></h5>
								<p><?php  echo $this->get_label('publisher features description two');?></p>
							</div>
						</div>
						<span class="count-prefix">0<?php echo ++$count; ?></span>
					</div>

					<div class="tab-btn-wrapper col-lg-6 aos-init" data-aos="fade-up" data-aos-delay="160">
						<div class="featured-services-tab-single">
							<div class="tab-single_icon">
								<i class='bx bx-desktop'></i>
							</div>
							<div class="tab-single_content">
								<h5><?php  echo $this->get_label('publisher features title three');?></h5>
								<p><?php  echo $this->get_label('publisher features description three');?></p>
							</div>
						</div>
						<span class="count-prefix">0<?php echo ++$count; ?></span>
					</div>

					<div class="tab-btn-wrapper col-lg-6 aos-init" data-aos="fade-up" data-aos-delay="155">
						<div class="featured-services-tab-single">
							<div class="tab-single_icon">
								<i class='bx bxs-credit-card'></i>
							</div>
							<div class="tab-single_content">
								<h5><?php echo $this->get_label('publisher features title four');?></h5>
								<p><?php echo $this->get_label('publisher features description four', array("x" => $pricingString));?></p>
							</div>
						</div>
						<span class="count-prefix">0<?php echo ++$count; ?></span>
					</div>

					<div class="tab-btn-wrapper col-lg-6 aos-init" data-aos="fade-up" data-aos-delay="160">
						<div class="featured-services-tab-single">
							<div class="tab-single_icon">
								<i class='bx bx-line-chart'></i>
							</div>
							<div class="tab-single_content">
								<h5><?php  echo $this->get_label('publisher features title five');?></h5>
								<p><?php  echo $this->get_label('publisher features description five');?></p>
							</div>
						</div>
						<span class="count-prefix">0<?php echo ++$count; ?></span>
					</div>
					
					<div class="tab-btn-wrapper col-lg-6 aos-init" data-aos="fade-up" data-aos-delay="165">
						<div class="featured-services-tab-single">
							<div class="tab-single_icon">
								<i class='bx bx-windows'></i>
							</div>
							<div class="tab-single_content">
								<h5><?php  echo $this->get_label('publisher features title six');?></h5>
								<p><?php  echo $this->get_label('publisher features description six');?></p>
							</div>
						</div>
						<span class="count-prefix">0<?php echo ++$count; ?></span>
					</div>

					<div class="tab-btn-wrapper col-lg-6 aos-init" data-aos="fade-up" data-aos-delay="170">
						<div class="featured-services-tab-single">
							<div class="tab-single_icon">
								<i class='bx bx-credit-card'></i>
							</div>
							<div class="tab-single_content">
								<h5><?php  echo $this->get_label('publisher features title seven');?></h5>
								<p><?php  echo $this->get_label('publisher features description seven', array("x" => $paymentOptionString));?></p>
							</div>
						</div>
						<span class="count-prefix">0<?php echo ++$count; ?></span>
					</div>

					<div class="tab-btn-wrapper col-lg-6 aos-init" data-aos="fade-up" data-aos-delay="175">
						<div class="featured-services-tab-single">
							<div class="tab-single_icon">
								<i class='bx bx-filter-alt' ></i>
							</div>
							<div class="tab-single_content">
								<h5><?php  echo $this->get_label('publisher features title eight');?></h5>
								<p><?php  echo $this->get_label('publisher features description eight');?></p>
							</div>
						</div>
						<span class="count-prefix">0<?php echo ++$count; ?></span>
					</div>

					<div class="tab-btn-wrapper col-lg-6 aos-init" data-aos="fade-up" data-aos-delay="180">
						<div class="featured-services-tab-single">
							<div class="tab-single_icon">
								<i class='bx bx-window-alt'></i>
							</div>
							<div class="tab-single_content">
								<h5><?php  echo $this->get_label('publisher features title nine');?></h5>
								<p><?php  echo $this->get_label('publisher features description nine');?></p>
							</div>
						</div>
						<span class="count-prefix">0<?php echo ++$count; ?></span>
					</div>

					<div class="tab-btn-wrapper col-lg-6 aos-init" data-aos="fade-up" data-aos-delay="185">
						<div class="featured-services-tab-single">
							<div class="tab-single_icon">
								<i class='bx bx-support' ></i>
							</div>
							<div class="tab-single_content">
								<h5><?php  echo $this->get_label('publisher features title ten');?></h5>
								<p><?php  echo $this->get_label('publisher features description ten');?></p>
							</div>
						</div>
						<span class="count-prefix"><?php echo ++$count; ?></span>
					</div>

				</div>
			</div>
		</div>
	</div>
		
	<div class="bubbles">
		<div class="bubble"></div>
		<div class="bubble"></div>
		<div class="bubble"></div>
		<div class="bubble"></div>
		<div class="bubble"></div>
		<div class="bubble"></div>
		<div class="bubble"></div>
		<div class="bubble"></div>
		<div class="bubble"></div>
		<div class="bubble"></div>
	</div>
</section>

<!--=====================pricing model Section========================-->
<?php if($cpc_enabled == 1 || $cpm_enabled == 1 || $cpa_enabled == 1 || $cpd_enabled == 1){?>
<section id="pricing-model" class="pricing-model-section light-background">
	<div class="section-title position-relative text-center mb-5 pb-2">
		<h2 class="mt-2 pb-2" data-aos="zoom-out" data-aos-delay="150"><?php echo $this->get_label_format($this->get_label('earning models')); ?></h2>
		<div class="devider aos-init" alt="" data-aos="zoom-in" data-aos-delay="180"></div>
	</div>
	
	<div class="container">
		<div class="row gy-4 justify-content-center">		
			<?php if($cpc_enabled == 1){?>
				<div class="col-xl-3 col-lg-6 col-sm-6 col-6 aos-init" data-aos="zoom-in" data-aos-delay="150">
					<div class="spectacledcoder-card2">
						<div class="circle2"></div>
					<img width="40" height="40" src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/pricing-model-cpc.png" alt="<?php echo $this->get_label('cpc'); ?>"/>
						<p class="content-price"><?php echo $this->get_label('cpc'); ?></p>
					</div> 
				</div>
			<?php } ?>
			
			<?php if($cpm_enabled == 1){?>
				<div class="col-xl-3 col-lg-6 col-sm-6 col-6 aos-init" data-aos="zoom-in" data-aos-delay="155">
					<div class="spectacledcoder-card2">
						<div class="circle2"></div>
						<img width="40" height="40" src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/pricing-model-cpm.png" alt="<?php echo $this->get_label('cpm'); ?>"/>
						<p class="content-price"><?php echo $this->get_label('cpm'); ?></p>
					</div> 
				</div>
			<?php } ?>
			
			<?php if($cpa_enabled == 1){?>
				<div class="col-xl-3 col-lg-6 col-sm-6 col-6 aos-init" data-aos="zoom-in" data-aos-delay="160">
					<div class="spectacledcoder-card2">
						<div class="circle2"></div>
					<img width="40" height="40" src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/pricing-model-cpa.png" alt="<?php echo $this->get_label('cpa'); ?>"/>
						<p class="content-price"><?php echo $this->get_label('cpa'); ?></p>
					</div> 
				</div>
			<?php } ?>
			
			<?php if($cpd_enabled == 1){?>
				<div class="col-xl-3 col-lg-6 col-sm-6 col-6 aos-init" data-aos="zoom-in" data-aos-delay="165">
					<div class="spectacledcoder-card2">
						<div class="circle2"></div>
						<img width="40" height="40" src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/pricing-model-cpd.png" alt="<?php echo $this->get_label('cpd'); ?>"/>
						<p class="content-price"><?php echo $this->get_label('cpd'); ?></p>
					</div> 
				</div>	
			<?php } ?>
		</div>
	</div>
</section>
<?php } ?>

<?php if(is_array($row) && count($row) > 0){?>
	<section id="payment-section" class="payment-section">
		<div class="container">
			<div class="row">
				<div class="section-title position-relative text-center mb-5 pb-2">
					<h2 class="mt-2 pb-2" data-aos="zoom-out" data-aos-delay="150">
						<?php  echo $this->get_label_format($this->get_label('payout options'));?>
					</h2>
					<div class="devider aos-init" alt="" data-aos="zoom-in" data-aos-delay="180"></div>
				</div>
			</div>
			<div class="row gy-4 justify-content-center">
				<?php foreach($row as $key => $value){?>
					<div class="col-lg-3 col-md-3 col-sm-3 col-6 payment-methods">
						<img class="img-fluid" src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/<?php echo $value['name'];?>.webp" alt="<?php echo $this->get_label($value['name']);?>" title="<?php echo $this->get_label($value['name']);?>" data-aos="zoom-in" data-aos-delay="200" />
					</div>			
				<?php } ?>	
			</div>
		</div>
	</section>
<?php } ?>

<?php $this->dispatch("layout/footer/18");?>