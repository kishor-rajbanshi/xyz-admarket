<?php
$this->dispatch("layout/header/17");

$login = 0;

if(LoginHelper::validate_user_login())
$login = 1;

$adCreateURL     = "";

if($login == 1)
{
	$adCreateURL     = $this->make_base_url("ad/create");
}
else 
{
	$loginseo      = $this->get_seo_name('index/login');

	if($loginseo != '')
	$loginurl      = BASE.$loginseo;
	else
	$loginurl      = $this->make_url("index/login");

	$adCreateURL     = $loginurl;
}


$admarketName = Configuration::get_instance()->read('admarket_name');

$time_enabled           = $this->get_addon_status('time-targeting_enabled');
$retargeting_enabled    = $this->get_addon_status('retargeting_enabled');
$city_enabled           = $this->get_addon_status('city-targeting_enabled');
$category_enabled       = $this->get_addon_status('category-targeting_enabled');
$language_enabled       = $this->get_addon_status('language-targeting_enabled');
$device_enabled         = $this->get_addon_status('device-targeting_enabled');
$isp_connection_enabled = $this->get_addon_status('isp-connection-targeting_enabled');
$affiliate_ads_enabled  = $this->get_addon_status('affiliate-ads_enabled');

$browser_enabled    = 0;
$os_enabled         = 0;
$isp_enabled        = 0;    
$connection_enabled = 0;

if($device_enabled == 1)
{
	$browser_enabled        = $this->get_addon_status('browser_enabled');
	$os_enabled             = $this->get_addon_status('os_enabled');
}

if($isp_connection_enabled == 1)
{
	$isp_enabled            = $this->get_addon_status('isp-targeting_enabled');
	$connection_enabled     = $this->get_addon_status('connectiontype-targeting_enabled');
}

$cpc_enabled    = $this->get_addon_status('cpc_enabled');
$cpm_enabled    = $this->get_addon_status('cpm_enabled');
$cpa_enabled    = $this->get_addon_status('cpa_enabled');
$cpd_enabled    = $this->get_addon_status('sponsored_enabled');

$keyword_based_display = Configuration::get_instance()->read('keyword_based_ad_display');
$text_ads_enabled    = Configuration::get_instance()->read('text-ads_enabled');
$text_image_enabled  = $this->get_addon_status('text-image-ads_enabled');
$native_ads_enabled  = $this->get_addon_status('native-ad-display_enabled');
$directlink_enabled  = $this->get_addon_status('direct-link-ads_enabled');
$inpage_push_enabled = $this->get_addon_status('inpage-push-ads_enabled');
$ecommerce_enabled       = $this->get_addon_status('ecommerce-ads_enabled');
$pop_ads_enabled         = $this->get_addon_status('pop-ads_enabled');
$skin_ads_enabled        = $this->get_addon_status('skin-ads_enabled');
$video_ads_enabled       = $this->get_addon_status('video-ads_enabled');

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
<!--=====================Home Banner Advertiser Start========================-->
<section class="section advertiser-banner">		
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
			<div class="col-md-6 order-2 order-md-1 col-lg-6 order-lg-1 text-md-left advertiser-banner-content">
				<h1 class="text-white font-weight-bold animated" data-aos="zoom-in">
					<?php echo $this->get_label('advertiser');?>
				</h1>
				<p class="text-white animated" data-aos="zoom-in">
					<?php echo $this->get_label('advertiser page title');?>
				</p>
				<div class="d-flex gap-2 banner-button">
					<a href="<?php echo $adCreateURL; ?>" class="start-btn animated" data-aos="zoom-in">
						<?php echo $this->get_label('buy premium traffic');?><span></span><span></span><span></span><span></span>
					</a>
				</div>
			</div>
			<div class="col-lg-6 col-md-6 order-1 order-md-2 order-lg-2 advertiser-banner-image">
          		<img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/advertiser-page-main-banner.png"  class="img-fluid animation animated" data-aos="zoom-out" title="<?php echo $this->get_label('advertiser');?>" alt="<?php echo $this->get_label('advertiser');?>" />
			</div>
		</div>
	</div>
</section>

<main>

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
					<?php  echo $this->get_label('advertiser banner ads');?>
				</button>
			</li>

			<?php if($text_ads_enabled == 1){?>		
				<li class="nav-item" role="presentation">
					<button class="nav-link" id="text-tab" data-bs-toggle="tab" data-bs-target="#text-tab-pane" type="button" role="tab" aria-controls="text-tab-pane" aria-selected="false">
						<?php  echo $this->get_label('advertiser text ads');?>
					</button>
				</li>
			<?php } ?>	
			<?php if($text_image_enabled == 1){?>			
				<li class="nav-item" role="presentation">
					<button class="nav-link" id="textimage-tab" data-bs-toggle="tab" data-bs-target="#textimage-tab-pane" type="button" role="tab" aria-controls="textimage-tab-pane" aria-selected="false">
						<?php  echo $this->get_label('advertiser textimage ads');?>
					</button>
				</li>
			<?php } ?>	

			<?php if($directlink_enabled == 1){?>	
				<li class="nav-item" role="presentation">
					<button class="nav-link" id="directlink-tab" data-bs-toggle="tab" data-bs-target="#directlink-tab-pane" type="button" role="tab" aria-controls="directlink-tab-pane" aria-selected="false">
						<?php  echo $this->get_label('advertiser directlink ads');?>
					</button>
				</li>
			<?php } ?>	

			<?php if($inpage_push_enabled == 1){?>	
				<li class="nav-item" role="presentation">
					<button class="nav-link" id="inpagepush-tab" data-bs-toggle="tab" data-bs-target="#inpagepush-tab-pane" type="button" role="tab" aria-controls="inpagepush-tab-pane" aria-selected="false">
						<?php  echo $this->get_label('advertiser inpage push ads');?>
					</button>
				</li>
			<?php } ?>	

			<?php if($pop_ads_enabled == 1){?>
			<li class="nav-item" role="presentation">
				<button class="nav-link" id="pop-tab" data-bs-toggle="tab" data-bs-target="#pop-tab-pane" type="button" role="tab" aria-controls="pop-tab-pane" aria-selected="false">
					<?php  echo $this->get_label('advertiser pop ads');?>
				</button>
			</li>
			<?php }?>
			
			<?php if($ecommerce_enabled == 1){?>
				<li class="nav-item" role="presentation">
					<button class="nav-link" id="ecommerce-tab" data-bs-toggle="tab" data-bs-target="#ecommerce-tab-pane" type="button" role="tab" aria-controls="ecommerce-tab-pane" aria-selected="false">
						<?php  echo $this->get_label('advertiser ecommerce ads');?>
					</button>
				</li>
			<?php }?>
			
			<?php if($skin_ads_enabled == 1){?>
				<li class="nav-item" role="presentation">
					<button class="nav-link" id="skin-tab" data-bs-toggle="tab" data-bs-target="#skin-tab-pane" type="button" role="tab" aria-controls="skin-tab-pane" aria-selected="false">
						<?php  echo $this->get_label('advertiser skin ads');?>
					</button>
				</li>
			<?php }?>
			
			<?php if($video_ads_enabled == 1){?>
				<li class="nav-item" role="presentation">
					<button class="nav-link" id="video-tab" data-bs-toggle="tab" data-bs-target="#video-tab-pane" type="button" role="tab" aria-controls="video-tab-pane" aria-selected="false">
						<?php echo $this->get_label('advertiser video ads');?>
					</button>
				</li>
			<?php }?>
			<?php if($affiliate_ads_enabled == 1){?>
				<li class="nav-item" role="presentation">
					<button class="nav-link" id="affiliate-tab" data-bs-toggle="tab" data-bs-target="#affiliate-tab-pane" type="button" role="tab" aria-controls="affiliate-tab-pane" aria-selected="false">
						<?php echo $this->get_label('affiliate ads');?>
					</button>
				</li>
			<?php }?>
		</ul>
	
		<div class="tab-content tab-custom accordion" id="myTabContent">
			<div class="tab-pane fade show active accordion-item" id="banner-tab-pane" role="tabpanel" aria-labelledby="banner-tab" tabindex="0">
				<h2 class="accordion-header d-lg-none" id="headingOne">
					<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
						<?php  echo $this->get_label('advertiser banner ads');?>
					</button>
				</h2>
				<div id="collapseOne" class="accordion-collapse collapse show d-lg-block" aria-labelledby="headingOne" data-bs-parent="#myTabContent">
					<div class="accordion-body">
						<div class="row align-items-center">
							<div class="col-lg-8 col-md-6" data-aos="zoom-in"><h3>
								<?php  echo $this->get_label('advertiser banner ads');?></h3>
								<p><?php  echo $this->get_label('advertiser banner ads section',array('x'=>$admarketName));?></p>
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
							<?php  echo $this->get_label('advertiser text ads');?>
						</button>
					</h2>
					<div id="collapseTwo" class="accordion-collapse collapse d-lg-block" aria-labelledby="headingTwo" data-bs-parent="#myTabContent">
						<div class="accordion-body">
							<div class="row align-items-center">
								<div class="col-lg-8 col-md-6" data-aos="zoom-in">
									<h3><?php  echo $this->get_label('advertiser text ads');?></h3>
									<p><?php  echo $this->get_label('advertiser text ads section',array('x'=>$admarketName));?></p>
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
							<?php  echo $this->get_label('advertiser textimage ads');?>
						</button>
					</h2>
					<div id="collapseThree" class="accordion-collapse collapse d-lg-block" aria-labelledby="headingThree" data-bs-parent="#myTabContent">
						<div class="accordion-body">
							<div class="row align-items-center">
								<div class="col-lg-8 col-md-6" data-aos="zoom-in"><h3>
									<?php  echo $this->get_label('advertiser textimage ads');?></h3>
									<p><?php  echo $this->get_label('advertiser textimage ads section',array('x'=>$admarketName));?></p>
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
							<?php  echo $this->get_label('advertiser directlink ads');?>
						</button>
					</h2>
					<div id="collapseFour" class="accordion-collapse collapse d-lg-block" aria-labelledby="headingFour" data-bs-parent="#myTabContent">
						<div class="accordion-body">
							<div class="row align-items-center">
								<div class="col-lg-8 col-md-6" data-aos="zoom-in"><h3>
									<?php  echo $this->get_label('advertiser directlink ads');?></h3>
									<p><?php  echo $this->get_label('advertiser directlink ads section',array('x'=>$admarketName));?></p>
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
							<?php  echo $this->get_label('advertiser pop ads');?>
						</button>
					</h2>
					<div id="collapseFive" class="accordion-collapse collapse d-lg-block" aria-labelledby="headingFive" data-bs-parent="#myTabContent">
						<div class="accordion-body">
							<div class="row align-items-center">
								<div class="col-lg-8 col-md-6" data-aos="zoom-in"><h3>
									<?php  echo $this->get_label('advertiser pop ads');?></h3>
									<p><?php  echo $this->get_label('advertiser pop ads section',array('x'=>$admarketName));?></p>
								</div>
								<div class="col-lg-4 col-md-6" data-aos="zoom-out">
									<img class="img-fluid" src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/pop-ads.png"/>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php } ?>

			<?php if($ecommerce_enabled == 1){?>
				<div class="tab-pane fade accordion-item" id="ecommerce-tab-pane" role="tabpanel" aria-labelledby="ecommerce-tab" tabindex="5">
					<h2 class="accordion-header d-lg-none" id="headingSix">
						<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
							<?php  echo $this->get_label('advertiser ecommerce ads');?>
						</button>
					</h2>
					<div id="collapseSix" class="accordion-collapse collapse d-lg-block" aria-labelledby="headingSix" data-bs-parent="#myTabContent">
						<div class="accordion-body">
							<div class="row align-items-center">
								<div class="col-lg-8 col-md-6" data-aos="zoom-in">
									<h3><?php  echo $this->get_label('advertiser ecommerce ads');?></h3>
									<p><?php  echo $this->get_label('advertiser ecommerce ads section',array('x'=>$admarketName));?></p>
								</div>
								<div class="col-lg-4 col-md-6" data-aos="zoom-out">
									<img class="img-fluid" src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/ecommerce-ads.png"/>
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
							<?php  echo $this->get_label('advertiser skin ads');?>
						</button>
					</h2>
					<div id="collapseSeven" class="accordion-collapse collapse d-lg-block" aria-labelledby="headingSeven" data-bs-parent="#myTabContent">
						<div class="accordion-body">
							<div class="row align-items-center">
							<div class="col-lg-8 col-md-6" data-aos="zoom-in">
								<h3><?php  echo $this->get_label('advertiser skin ads');?></h3>
								<p><?php  echo $this->get_label('advertiser skin ads section',array('x'=>$admarketName));?></p></div>
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
							<?php  echo $this->get_label('advertiser video ads');?>
						</button>
					</h2>
					<div id="collapseEight" class="accordion-collapse collapse d-lg-block" aria-labelledby="headingEight" data-bs-parent="#myTabContent">
						<div class="accordion-body">
							<div class="row align-items-center">
								<div class="col-lg-8 col-md-6" data-aos="zoom-in">
									<h3><?php  echo $this->get_label('advertiser video ads');?></h3>
									<p><?php  echo $this->get_label('advertiser video ads section',array('x'=>$admarketName));?></p>
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
							<?php  echo $this->get_label('advertiser inpage push ads');?>
						</button>
					</h2>
					<div id="collapseNine" class="accordion-collapse collapse d-lg-block" aria-labelledby="headingNine" data-bs-parent="#myTabContent">
						<div class="accordion-body">
							<div class="row align-items-center">
								<div class="col-lg-8 col-md-6" data-aos="zoom-in">
									<h3><?php  echo $this->get_label('advertiser inpage push ads');?></h3>
									<p><?php  echo $this->get_label('advertiser inpage push ads section',array('x'=>$admarketName));?></p>
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
							<?php  echo $this->get_label('advertiser affiliate ads');?>
						</button>
					</h2>
					<div id="collapseTen" class="accordion-collapse collapse d-lg-block" aria-labelledby="headingTen" data-bs-parent="#myTabContent">
						<div class="accordion-body">
							<div class="row align-items-center">
								<div class="col-lg-8 col-md-6" data-aos="zoom-in">
									<h3><?php  echo $this->get_label('advertiser affiliate ads');?></h3>
									<p><?php  echo $this->get_label('advertiser affiliate ads section',array('x'=>$admarketName));?></p>
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
	
<!--=====================Advertiser Features Section========================-->
<section class="advertiser-features">
	<div class="container px-lg-5">
		<div class="section-title position-relative text-center mb-5 pb-2">
			<h2 class="mt-2 pb-2" data-aos="zoom-in" data-aos-delay="150"><?php echo $this->get_label_format($this->get_label('advertiser features'));?></h2>
			<div class="devider" alt="" data-aos="zoom-in" data-aos-delay="180"></div>
		</div>		
		<div class="row g-4">			
			<div class="col-lg-4 col-md-6" alt="" data-aos="zoom-in" data-aos-delay="150">
				<div class="service-item d-flex flex-column justify-content-center text-center rounded">
					<div class="service-icon flex-shrink-0">
						<i class="fa fa-calendar fa-2x"></i>
					</div>
					<h5 class="mb-3"><?php  echo $this->get_label('advertiser features title one');?></h5>
					<p><?php  echo $this->get_label('advertiser features description one');?></p>
				</div>
			</div>			
			<div class="col-lg-4 col-md-6" alt="" data-aos="zoom-in" data-aos-delay="150">
				<div class="service-item d-flex flex-column justify-content-center text-center rounded">
					<div class="service-icon flex-shrink-0">
						<i class="fa fa-bullseye fa-2x"></i>
					</div>
					<h5 class="mb-3"><?php  echo $this->get_label('advertiser features title two');?></h5>
					<p><?php  echo $this->get_label('advertiser features description two');?></p>
				</div>
			</div>			
			<div class="col-lg-4 col-md-6" alt="" data-aos="zoom-in" data-aos-delay="160">
				<div class="service-item d-flex flex-column justify-content-center text-center rounded">
					<div class="service-icon flex-shrink-0">
						<i class="fa fa-money fa-2x"></i>
					</div>
					<h5 class="mb-3"><?php  echo $this->get_label('advertiser features title three');?></h5>
					<p><?php  echo $this->get_label('advertiser features description three', array("x" => $pricingString));?></p>
				</div>
			</div>			
			<div class="col-lg-4 col-md-6" alt="" data-aos="zoom-in" data-aos-delay="165">
				<div class="service-item d-flex flex-column justify-content-center text-center rounded">
					<div class="service-icon flex-shrink-0">
						<i class="fa fa-paypal fa-2x"></i>
					</div>
					<h5 class="mb-3"><?php  echo $this->get_label('advertiser features title four');?></h5>
					<p><?php  echo $this->get_label('advertiser features description four', array("x" => $paymentOptionString));?></p>
				</div>
			</div>			
			<div class="col-lg-4 col-md-6" alt="" data-aos="zoom-in" data-aos-delay="170">
				<div class="service-item d-flex flex-column justify-content-center text-center rounded">
					<div class="service-icon flex-shrink-0">
						<i class="fa fa-user-times fa-2x"></i>
					</div>
					<h5 class="mb-3"><?php  echo $this->get_label('advertiser features title five');?></h5>
					<p><?php  echo $this->get_label('advertiser features description five');?></p>
				</div>
			</div>			
			<div class="col-lg-4 col-md-6" alt="" data-aos="zoom-in" data-aos-delay="175">
				<div class="service-item d-flex flex-column justify-content-center text-center rounded">
					<div class="service-icon flex-shrink-0">
						<i class="fa fa-clock-o fa-2x"></i>
					</div>
					<h5 class="mb-3"><?php  echo $this->get_label('advertiser features title six');?></h5>
					<p><?php  echo $this->get_label('advertiser features description six');?></p>
				</div>
			</div>
			<div class="col-lg-4 col-md-6" alt="" data-aos="zoom-in" data-aos-delay="180">
				<div class="service-item d-flex flex-column justify-content-center text-center rounded">
					<div class="service-icon flex-shrink-0">
						<i class="fa fa-globe fa-2x"></i>
					</div>
					<h5 class="mb-3"><?php  echo $this->get_label('advertiser features title seven');?></h5>
					<p><?php  echo $this->get_label('advertiser features description seven');?></p>
				</div>
			</div>
			<div class="col-lg-4 col-md-6" alt="" data-aos="zoom-in" data-aos-delay="185">
				<div class="service-item d-flex flex-column justify-content-center text-center rounded">
					<div class="service-icon flex-shrink-0">
						<i class="fa fa-bus fa-2x"></i>
					</div>
					<h5 class="mb-3"><?php  echo $this->get_label('advertiser features title eight');?></h5>
					<p><?php  echo $this->get_label('advertiser features description eight');?></p>
				</div>
			</div>
			<div class="col-lg-4 col-md-6" alt="" data-aos="zoom-in" data-aos-delay="190">
				<div class="service-item d-flex flex-column justify-content-center text-center rounded">
					<div class="service-icon flex-shrink-0">
						<i class="fa fa-phone fa-2x"></i>
					</div>
					<h5 class="mb-3"><?php  echo $this->get_label('advertiser features title nine');?></h5>
					<p><?php  echo $this->get_label('advertiser features description nine');?></p>
				</div>
			</div>
		</div>
	</div>
</section>

<!--=====================Targeting Options Section========================-->
<section id="targeting-section" class="targeting-section">
	<div class="targeting-section-inner">
		<div class="section-title position-relative text-center mb-5 pb-2" >
			<h2 class="mt-2 pb-2 text-white" alt="" data-aos="zoom-in" data-aos-delay="150">
				<?php  echo $this->get_label('targeting options');?>
			</h2>
			<div class="devider-white" alt="" data-aos="zoom-out" data-aos-delay="180"></div>
		</div>				
		<div class="container">
			<div class="row g-4 justify-content-center">	
				<div class="col-md-6 col-lg-4" data-aos="zoom-in" data-aos-delay="150">
					<div class="promo-card card-2">
						<div class="promo-content">
							<h3 class="promo-title"><?php echo $this->get_label('advertiser geo targeting');?></h3>
							<p class="promo-description"><?php echo $this->get_label('advertiser geo targeting description');?></p>
						</div>
						<div class="promo-image">
								<img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/advertiser-page-targeting-geo.webp" alt="<?php echo $this->get_label('advertiser geo targeting');?>" class="img-fluid" />
						</div>
					</div>
				</div>
				<?php if($city_enabled  == 1){?>
					<div class="col-md-6 col-lg-4" data-aos="zoom-in" data-aos-delay="155">
						<div class="promo-card card-2">
							<div class="promo-content">
								<h3 class="promo-title"><?php echo $this->get_label('advertiser city targeting');?></h3>
								<p class="card-text"><?php echo $this->get_label('advertiser city targeting description');?></p>
							</div>
							<div class="promo-image">
								<img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/advertiser-page-targeting-city.webp" alt="<?php echo $this->get_label('advertiser city targeting');?>" class="img-fluid" />
							</div>
						</div>
					</div>
				<?php } ?>
				<?php if($keyword_based_display  == 1){?>
					<div class="col-md-6 col-lg-4" data-aos="zoom-in" data-aos-delay="160">
						<div class="promo-card card-1">
							<div class="promo-content">
								<h3 class="promo-title"><?php echo $this->get_label('advertiser keyword targeting');?></h3>
								<p class="promo-description"><?php echo $this->get_label('advertiser keyword targeting description');?></p>
							</div>
							<div class="promo-image">
								<img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/advertiser-page-targeting-keyword.webp" alt="<?php echo $this->get_label('advertiser keyword targeting');?>" class="img-fluid" />
							</div>
						</div>
					</div>		
				<?php } ?>			
				<?php if($category_enabled  == 1){?>	
					<div class="col-md-6 col-lg-4" data-aos="zoom-in" data-aos-delay="165">
						<div class="promo-card card-3">
							<div class="promo-content">
								<h3 class="promo-title"><?php echo $this->get_label('advertiser category targeting');?></h3>
								<p class="promo-description"><?php echo $this->get_label('advertiser category targeting description');?></p>
							</div>
							<div class="promo-image">
								<img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/advertiser-page-targeting-category.webp" alt="<?php echo $this->get_label('advertiser category targeting');?>" class="img-fluid" />
							</div>
						</div>
					</div>		
				<?php } ?>	
				<?php if($device_enabled  == 1){?>
					<div class="col-md-6 col-lg-4" data-aos="zoom-in" data-aos-delay="170">
						<div class="promo-card card-4">
							<div class="promo-content">
								<h3 class="promo-title"><?php echo $this->get_label('advertiser device targeting');?></h3>
								<p class="promo-description"><?php echo $this->get_label('advertiser device targeting description');?></p>
							</div>
							<div class="promo-image">
								<img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/advertiser-page-targeting-device.webp" alt="<?php echo $this->get_label('advertiser device targeting');?>" class="img-fluid" />
							</div>
						</div>
					</div>
				<?php } ?>	
				<?php if($os_enabled  == 1){?>	
					<div class="col-md-6 col-lg-4" data-aos="zoom-in" data-aos-delay="175">
						<div class="promo-card card-1">
							<div class="promo-content">
								<h3 class="promo-title"><?php echo $this->get_label('advertiser os targeting');?></h3>
								<p class="promo-description"><?php echo $this->get_label('advertiser os targeting description');?></p>
							</div>
							<div class="promo-image">
								<img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/advertiser-page-targeting-os.webp" alt="<?php echo $this->get_label('advertiser os targeting');?>" class="img-fluid" />
							</div>
						</div>
					</div>
				<?php } ?>	
				<?php if($browser_enabled  == 1){?>		
					<div class="col-md-6 col-lg-4" data-aos="zoom-in" data-aos-delay="180">
						<div class="promo-card card-2">
							<div class="promo-content">
								<h3 class="promo-title"><?php echo $this->get_label('advertiser browser targeting');?></h3>
								<p class="promo-description"><?php echo $this->get_label('advertiser browser targeting description');?></p>
							</div>
							<div class="promo-image">
								<img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/advertiser-page-targeting-browser.webp" alt="<?php echo $this->get_label('advertiser browser targeting');?>" class="img-fluid" />
							</div>
						</div>
					</div>
				<?php } ?>						
				<?php if($language_enabled  == 1){?>
					<div class="col-md-6 col-lg-4" data-aos="zoom-in" data-aos-delay="185">
						<div class="promo-card card-2">
							<div class="promo-content">
								<h3 class="promo-title"><?php echo $this->get_label('advertiser language targeting');?></h3>
								<p class="card-text"><?php echo $this->get_label('advertiser language targeting description');?></p>
							</div>
							<div class="promo-image">
								<img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/advertiser-page-targeting-language.webp" alt="<?php echo $this->get_label('advertiser language targeting');?>" class="img-fluid" />
							</div>
						</div>
					</div>
				<?php } ?>
				<?php if($time_enabled  == 1){?>
					<div class="col-md-6 col-lg-4" data-aos="zoom-in" data-aos-delay="190">
						<div class="promo-card card-2">
							<div class="promo-content">
								<h3 class="promo-title"><?php echo $this->get_label('advertiser time targeting');?></h3>
								<p class="card-text"><?php echo $this->get_label('advertiser time targeting description');?></p>
							</div>
							<div class="promo-image">
								<img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/advertiser-page-targeting-time.webp" alt="<?php echo $this->get_label('advertiser time targeting');?>" class="img-fluid" />
							</div>
						</div>
					</div>
				<?php } ?>
				<?php if($retargeting_enabled  == 1){?>
					<div class="col-md-6 col-lg-4" data-aos="zoom-in" data-aos-delay="195">
						<div class="promo-card card-2">
							<div class="promo-content">
								<h3 class="promo-title"><?php echo $this->get_label('advertiser retargeting');?></h3>
								<p class="card-text"><?php echo $this->get_label('advertiser retargeting description');?></p>
							</div>
							<div class="promo-image">
								<img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/advertiser-page-targeting-retargeting.webp" alt="<?php echo $this->get_label('advertiser retargeting');?>" class="img-fluid" />
							</div>
						</div>
					</div>
				<?php } ?>	

				<?php if($isp_enabled  == 1){?>
					<div class="col-md-6 col-lg-4" data-aos="zoom-in" data-aos-delay="200">
						<div class="promo-card card-2">
							<div class="promo-content">
								<h3 class="promo-title"><?php echo $this->get_label('advertiser isp targeting');?></h3>
								<p class="card-text"><?php echo $this->get_label('advertiser isp targeting description');?></p>
							</div>
							<div class="promo-image">
								<img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/advertiser-page-targeting-isp.webp" alt="<?php echo $this->get_label('advertiser isp targeting');?>" class="img-fluid" />
							</div>
						</div>
					</div>
				<?php } ?>	
				<?php if($connection_enabled  == 1){?>
					<div class="col-md-6 col-lg-4" data-aos="zoom-in" data-aos-delay="205">
						<div class="promo-card card-2">
							<div class="promo-content">
								<h3 class="promo-title"><?php echo $this->get_label('advertiser connection targeting');?></h3>
								<p class="card-text"><?php echo $this->get_label('advertiser connection targeting description');?></p>
							</div>
							<div class="promo-image">
								<img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/advertiser-page-targeting-connection.webp" alt="<?php echo $this->get_label('advertiser connection targeting');?>" class="img-fluid" />
							</div>
						</div>
					</div>
				<?php } ?>	
				</div>
			</div>
	</div>
</section>


<!--=====================pricing model Section========================-->
<?php if($cpc_enabled == 1 || $cpm_enabled == 1 || $cpa_enabled == 1 || $cpd_enabled == 1){?>
<section id="pricing-model" class="pricing-model-section light-background">
	<div class="section-title position-relative text-center mb-5 pb-2">
		<h2 class="mt-2 pb-2" data-aos="zoom-out" data-aos-delay="150"><?php echo $this->get_label_format($this->get_label('pricing models')); ?></h2>
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

	
	<!--=================payment section start===================-->
	
	
<?php if(is_array($row) && count($row) > 0){?>
	<section id="payment-section" class="payment-section">
		<div class="container">
			<div class="row">
				<div class="section-title position-relative text-center mb-5 pb-2">
					<h2 class="mt-2 pb-2" data-aos="zoom-out" data-aos-delay="150">
						<?php  echo $this->get_label_format($this->get_label('payment payout options'));?>
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

</main>
<?php $this->dispatch("layout/footer/17");?>
