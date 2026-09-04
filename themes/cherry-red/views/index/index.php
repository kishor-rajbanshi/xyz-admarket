<?php
$this->dispatch("layout/header/16");

$registerseo = $this->get_seo_name('index/register');

if($registerseo != "")
$registerurl = BASE.$registerseo;
else
$registerurl = $this->make_url("index/register");

$localeid=$this->get_variable('localeid');

$login = 0;

if(LoginHelper::validate_user_login())
$login = 1;

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

$admarket_name = Configuration::get_instance()->read('admarket_name');

$adsDemoURL = "";
if(DEMO_MODE)
$adsDemoURL = "https://addons.admarket-demo.xyzscripts.com/demo/ads-demo.php";


$adCreateURL     = "";
$adcodeCreateURL = "";

if($login == 1)
{
	$adCreateURL     = $this->make_base_url("ad/create");
	$adcodeCreateURL = $this->make_base_url("adunit/create");
}
else 
{
	$loginseo      = $this->get_seo_name('index/login');

	if($loginseo != '')
	$loginurl      = BASE.$loginseo;
	else
	$loginurl      = $this->make_url("index/login");

	$adCreateURL     = $loginurl;
	$adcodeCreateURL = $loginurl;
}

$cpc_enabled    = $this->get_variable('cpc_enabled');
$cpm_enabled    = $this->get_variable('cpm_enabled');
$cpa_enabled    = $this->get_variable('cpa_enabled');
$cpd_enabled    = $this->get_variable('sponsored_enabled');
$advertiser_dashboard_status = Configuration::get_instance()->read('advertiser_dashboard_status');
$publisher_dashboard_status  = Configuration::get_instance()->read('publisher_dashboard_status');
$text_ads_enabled    = Configuration::get_instance()->read('text-ads_enabled');
$text_image_enabled  = $this->get_addon_status('text-image-ads_enabled');
$native_ads_enabled  = $this->get_addon_status('native-ad-display_enabled');
$directlink_enabled  = $this->get_addon_status('direct-link-ads_enabled');
$inpage_push_enabled = $this->get_addon_status('inpage-push-ads_enabled');
$affiliate_ads_enabled = $this->get_addon_status('affiliate-ads_enabled');
$ecommerce_enabled       = $this->get_addon_status('ecommerce-ads_enabled');
$pop_ads_enabled         = $this->get_addon_status('pop-ads_enabled');
$skin_ads_enabled        = $this->get_addon_status('skin-ads_enabled');
$video_ads_enabled       = $this->get_addon_status('video-ads_enabled');
$interstitial_enabled    = $this->get_addon_status('interstitial_enabled');





$row = $this->get_result('row');
?>

<!-- ========================= Home Slider Section ========================= -->

<section id="home-banner" class="d-flex align-items-center">
	<div class="home-banner-inner ">
		<div class="home-banner-shape home-banner-bottom"></div>
		<div class="container-xxl container-xl">
			<div class="row pt-40">
				<div class="col-lg-7 col-md-6 col-sm-12 order-2 order-lg-1 order-md-1 d-flex flex-column justify-content-center home-banner-content">
					<?php
						// Split into array of characters
						$chars = preg_split('//u', $admarket_name, -1, PREG_SPLIT_NO_EMPTY);

						// Wrap each character with <span class="key">
						$chars_with_span = array_map(function($ch) {
							return "<span class=\"key\">{$ch}</span>";
						}, $chars);

						// Join with line breaks (for readability like your example)
						$admarket_name_modified = implode("\n  ", $chars_with_span);
					?>
					<div class="keyboard" data-aos="zoom-out" data-aos-delay="180">
						<?php echo $admarket_name_modified; ?>
					</div>

					<h4 class="description-big" data-aos="zoom-in" data-aos-delay="180"><?php echo $this->get_label('home page title'); ?></h4>						
					<h4 class="description-small" data-aos="zoom-out" data-aos-delay="180"><?php echo $this->get_label('home page sub title'); ?></h4>
					
					
					<div class="d-flex gap-2 mt-4 banner-button">
						<?php if($advertiser_dashboard_status == 1){?>
							<a href="<?php echo $adCreateURL;?>" class="start-btn" data-aos="zoom-in" data-aos-delay="180">
								<?php echo $this->get_label('buy premium traffic'); ?><span></span><span></span><span></span><span></span>
							</a>
						<?php } ?>
						
						<?php if($publisher_dashboard_status == 1){?>
							<a href="<?php echo $adcodeCreateURL;?>" class="start-btn" data-aos="zoom-in" data-aos-delay="180">
								<?php echo $this->get_label('start earning'); ?><span></span><span></span><span></span><span></span>
							</a>
						<?php } ?>
					</div>
				</div>
        		<div class="col-lg-5 col-md-6 col-sm-12 order-1 order-lg-2 order-md-2 hero-img">
					<section class="main-container">
						<div class="main">
							<div class="big-circle">
								<div class="icon-block">
									<img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/pricing-model-cpa.png" alt="" />
								</div>
								<div class="icon-block">
									<img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/pricing-model-cpc.png" alt="" />
								</div>
								<div class="icon-block">
									<img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/pricing-model-cpd.png" alt="" />
								</div>
								<div class="icon-block">
									<img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/pricing-model-cpm.png" alt="" />
								</div>
							</div>
							<div class="circle">
								<div class="icon-block">
									<img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/home-page-main-banner-circling-icon-1.png" alt="" />
								</div>
								<div class="icon-block">
									<img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/home-page-main-banner-circling-icon-2.png" alt="" />
								</div>
								<div class="icon-block">
									<img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/home-page-main-banner-circling-icon-3.png" alt="" />
								</div>
								<div class="icon-block">
									<img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/home-page-main-banner-circling-icon-4.png" alt="" />
								</div>
							</div>
							<div class="center-logo">
								<img class="zoom-in-zoom-out" src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/home-page-main-banner-globe.png" alt="ui-ux icon" />
							</div>
						</div>
					</section>
				</div>
			</div>
		</div>
	</div>
</section>

<main>
	
<!-- ========================= Advertiser and Publisher Section ========================= -->
<section class="about-section">	
	<div class="container">
		<div class="row">
			<?php if($advertiser_dashboard_status == 1){?>
				<div class="col-lg-6 col-md-6 col-xs-12">
					<div class="section-title" data-aos="left-in" data-aos-delay="150">
						<img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/home-page-advertiser-image.png" class="img-fluid animated advertiser-image" title="" alt="" data-aos="zoom-in" data-aos-delay="150" />
						<h2 class="text-center py-3" alt="" data-aos="zoom-out" data-aos-delay="180"><?php echo $this->get_label_format($this->get_label('advertiser')); ?></h2>
						<p class="about-description aos animate" data-aos="fade-out-left" data-aos-delay="180"><?php echo $this->get_label('home page advertiser description',array('x'=>$admarket_name)); ?></p>
						<div class="col-lg-12 col-md-12 col-sm-12 form-group message-btn text-center">
							<a class="readmore-button" href="<?php echo $adCreateURL; ?>" data-aos="fade-out" data-aos-delay="150"><?php echo $this->get_label('buy premium traffic');?> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></a>
						</div>
					</div>
				</div>
			<?php } ?>

			<?php if($publisher_dashboard_status == 1){?>
				<div class="col-lg-6 col-md-6 col-xs-12">
					<div class="section-title">
						<img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/home-page-publisher-image.png" class="img-fluid animated publisher-image" title="" alt="" data-aos="zoom-out" data-aos-delay="150" />
						<h2 class="text-center py-3" alt="" data-aos="zoom-out" data-aos-delay="180"><?php echo $this->get_label_format($this->get_label('publisher')); ?></h2>
						<p class="about-description aos animate" data-aos="fade-out" data-aos-delay="180"><?php echo $this->get_label('home page publisher description',array('x'=>$admarket_name));?></p>
						<div class="col-lg-12 col-md-12 col-sm-12 form-group message-btn text-center">
							<a class="readmore-button" href="<?php echo $adcodeCreateURL; ?>" data-aos="fade-out" data-aos-delay="150"><?php echo $this->get_label('start earning');?> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></a>
						</div>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>
</section>
<!-- ========================= Ad formats Section ========================= -->

<section id="ads-section" class="ads-section">
  	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="section-title">					
					<div class="col-12 swich-bg text-center">
						<h2 class="text-center pb-2" data-aos="zoom-in" data-aos-delay="150">
							<?php echo $this->get_label_format($this->get_label('ad format title'));?>
						</h2>
						<div class="devider" data-aos="zoom-in" data-aos-delay="200"></div>

						<?php if($advertiser_dashboard_status == 1 && $publisher_dashboard_status == 1){?>
							<div class="switches-container" data-aos="zoom-out" data-aos-delay="150">
								<input type="radio" id="switchSingle" name="switchPlan" value="><?php echo $this->get_label('advertiser');?>" checked="checked" />
								<input type="radio" id="switchMulti" name="switchPlan" value="<?php echo $this->get_label('publisher');?>" />
								
								<label for="switchSingle"><?php echo $this->get_label('advertiser');?></label>
								<label for="switchMulti"><?php echo $this->get_label('publisher');?></label>
							
								<div class="switch-wrapper">
									<div class="switch">
										<div id="advertiser-button" onclick="showHideVendorType('single')"><?php echo $this->get_label('advertiser');?></div>
										<div id="publisher-button" onclick="showHideVendorType('multi')"><?php echo $this->get_label('publisher');?></div>
									</div>
								</div>
							</div>
						<?php } ?>					
					</div>
				</div>			

					<?php if($advertiser_dashboard_status == 1){
						$rowIndex = 1;
						$delayDuration = 200;
						?>
						<div class="col-lg-12 box-1 advertiser">
							<div class="card ads-box <?php if($rowIndex % 2 == 0){?> white <?php } else { ?> gray <?php } ?>" data-aos="zoom-in" data-aos-delay="150">
								<div class="row g-0">			
									<div class="col-md-4 ads-img-margin order-1 <?php if($rowIndex % 2 == 0){?> order-lg-2 order-md-2 <?php } ?>">
										<img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/banner-ads.png" class="img-fluid animated" data-aos="slide-right" data-aos-delay="150" />
									</div>
									<div class="col-md-8 order-2 <?php if($rowIndex % 2 == 0){?> order-lg-1 order-md-1 <?php } ?>">
										<div class="card-body">
											<h5 class="card-title" data-aos="zoom-in" data-aos-delay="<?php echo $delayDuration += 20;?>"><?php echo $this->get_label('home page display ad advertiser');?></h5>
											<p class="card-text" data-aos="zoom-out" data-aos-delay="<?php echo $delayDuration += 20;?>"><?php echo $this->get_label('home page display ad description advertiser');?></p>
											<div class="d-flex">
												<?php if($adsDemoURL){?>					
												<a class="demo-button shadow-sm" href="<?php echo $adsDemoURL;?>" data-aos="fade-in" data-aos-delay="130"><?php echo $this->get_label('demo');?> <i class="fa fa-play-circle-o" aria-hidden="true"></i></a>
												<?php } ?>
												<a class="learn-more-button shadow-sm" href="<?php echo $adCreateURL; ?>" data-aos="fade-out" data-aos-delay="130"><?php echo $this->get_label('advertise now');?> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></a>
											</div>
										</div>
									</div>
								</div>
							</div>
									
							<?php if($text_ads_enabled == 1){
								$rowIndex++;
								?>						
								<div class="card ads-box <?php if($rowIndex % 2 == 0){?> white <?php } else { ?> gray <?php } ?>" data-aos="zoom-in" data-aos-delay="150">
									<div class="row g-0">	
										<div class="col-md-4 ads-img-margin order-1 <?php if($rowIndex % 2 == 0){?> order-lg-2 order-md-2 <?php } ?>">
											<img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/text-ads.png" class="img-fluid animated" data-aos="slide-left" data-aos-delay="150" />
										</div>								
										<div class="col-md-8 order-2 <?php if($rowIndex % 2 == 0){?> order-lg-1 order-md-1 <?php } ?>">
											<div class="card-body">
												<h5 class="card-title" data-aos="zoom-in" data-aos-delay="<?php echo $delayDuration += 20;?>"><?php echo $this->get_label('home page text ad advertiser');?></h5>
												<p class="card-text" data-aos="zoom-out" data-aos-delay="<?php echo $delayDuration += 20;?>"><?php echo $this->get_label('home page text ad description advertiser');?></p>
												<div class="d-flex">
													<?php if($adsDemoURL){?>
													<a class="demo-button shadow-sm" href="<?php echo $adsDemoURL;?>" data-aos="fade-in" data-aos-delay="130"><?php echo $this->get_label('demo');?> <i class="fa fa-play-circle-o" aria-hidden="true"></i></a>
													<?php } ?>
													<a class="learn-more-button shadow-sm" href="<?php echo $adCreateURL; ?>" data-aos="fade-out" data-aos-delay="130"><?php echo $this->get_label('advertise now');?> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></a>
												</div>
											</div> 
										</div>
									</div>
								</div>
							<?php } ?>	

							<?php if($text_image_enabled == 1){
								$rowIndex++;
								?>									
								<div class="card ads-box <?php if($rowIndex % 2 == 0){?> white <?php } else { ?> gray <?php } ?>" data-aos="zoom-in" data-aos-delay="150">
									<div class="row g-0">			
										<div class="col-md-4 ads-img-margin order-1 <?php if($rowIndex % 2 == 0){?> order-lg-2 order-md-2 <?php } ?>">
											<img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/text+image-ads.png" class="img-fluid animated" data-aos="slide-right" data-aos-delay="150" />
										</div>
										<div class="col-md-8 order-2 <?php if($rowIndex % 2 == 0){?> order-lg-1 order-md-1 <?php } ?>">
											<div class="card-body">
												<h5 class="card-title" data-aos="zoom-in" data-aos-delay="<?php echo $delayDuration += 20;?>"><?php echo $this->get_label('home page text+image ad advertiser');?></h5>
												<p class="card-text" data-aos="zoom-out" data-aos-delay="<?php echo $delayDuration += 20;?>"><?php echo $this->get_label('home page text+image ad description advertiser');?></p>
												<div class="d-flex">
													<?php if($adsDemoURL){?>					
													<a class="demo-button shadow-sm" href="<?php echo $adsDemoURL;?>" data-aos="fade-in" data-aos-delay="130"><?php echo $this->get_label('demo');?> <i class="fa fa-play-circle-o" aria-hidden="true"></i></a>
													<?php } ?>
													<a class="learn-more-button shadow-sm" href="<?php echo $adCreateURL; ?>" data-aos="fade-out" data-aos-delay="130"><?php echo $this->get_label('advertise now');?> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></a>
												</div>
											</div>
										</div> 
									</div>
								</div>
							<?php } ?>		

							<?php if($directlink_enabled == 1){
								$rowIndex++;
								?>						
								<div class="card ads-box <?php if($rowIndex % 2 == 0){?> white <?php } else { ?> gray <?php } ?>" data-aos="zoom-in" data-aos-delay="150">
									<div class="row g-0">
										<div class="col-md-4 ads-img-margin order-1 <?php if($rowIndex % 2 == 0){?> order-lg-2 order-md-2 <?php } ?>">
											<img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/direct-link-ads.png" class="img-fluid animated" data-aos="slide-left" data-aos-delay="150" />
										</div>
										<div class="col-md-8 order-2 <?php if($rowIndex % 2 == 0){?> order-lg-1 order-md-1 <?php } ?>">
											<div class="card-body">
												<h5 class="card-title" data-aos="zoom-in" data-aos-delay="<?php echo $delayDuration += 20;?>"><?php echo $this->get_label('home page directlink ad advertiser');?></h5>
												<p class="card-text" data-aos="zoom-out" data-aos-delay="<?php echo $delayDuration += 20;?>"><?php echo $this->get_label('home page directlink ad description advertiser');?></p>
												<div class="d-flex">
													<?php if($adsDemoURL){?>
													<a class="demo-button shadow-sm" href="<?php echo $adsDemoURL;?>" data-aos="fade-in" data-aos-delay="130"><?php echo $this->get_label('demo');?> <i class="fa fa-play-circle-o" aria-hidden="true"></i></a>
													<?php } ?>
													<a class="learn-more-button shadow-sm" href="<?php echo $adCreateURL; ?>" data-aos="fade-out" data-aos-delay="130"><?php echo $this->get_label('advertise now');?> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></a>
												</div>
											</div> 
										</div>										
									</div>
								</div>
							<?php } ?>		

							<?php if($inpage_push_enabled == 1){
								$rowIndex++;
								?>									
								<div class="card ads-box <?php if($rowIndex % 2 == 0){?> white <?php } else { ?> gray <?php } ?>" data-aos="zoom-in" data-aos-delay="150">
									<div class="row g-0">			
										<div class="col-md-4 ads-img-margin order-1 <?php if($rowIndex % 2 == 0){?> order-lg-2 order-md-2 <?php } ?>">
											<img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/inpage-push-ads.png" class="img-fluid animated" data-aos="slide-right" data-aos-delay="150" />
										</div>
										<div class="col-md-8 order-2 <?php if($rowIndex % 2 == 0){?> order-lg-1 order-md-1 <?php } ?>">
											<div class="card-body">
												<h5 class="card-title" data-aos="zoom-in" data-aos-delay="<?php echo $delayDuration += 20;?>"><?php echo $this->get_label('home page inpage push ad advertiser');?></h5>
												<p class="card-text" data-aos="zoom-out" data-aos-delay="<?php echo $delayDuration += 20;?>"><?php echo $this->get_label('home page inpage push ad description advertiser');?></p>
												<div class="d-flex">
													<?php if($adsDemoURL){?>					
													<a class="demo-button shadow-sm" href="<?php echo $adsDemoURL;?>" data-aos="fade-in" data-aos-delay="130"><?php echo $this->get_label('demo');?> <i class="fa fa-play-circle-o" aria-hidden="true"></i></a>
													<?php } ?>
													<a class="learn-more-button shadow-sm" href="<?php echo $adCreateURL; ?>" data-aos="fade-out" data-aos-delay="130"><?php echo $this->get_label('advertise now');?> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></a>
												</div>
											</div>
										</div>
									</div>
								</div>
							<?php } ?>		

							<?php if($pop_ads_enabled == 1){
								$rowIndex++;
								?>									
								<div class="card ads-box <?php if($rowIndex % 2 == 0){?> white <?php } else { ?> gray <?php } ?>" data-aos="zoom-in" data-aos-delay="150">
									<div class="row g-0">			
										<div class="col-md-4 ads-img-margin order-1 <?php if($rowIndex % 2 == 0){?> order-lg-2 order-md-2 <?php } ?>">
											<img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/pop-ads.png" class="img-fluid animated" data-aos="slide-right" data-aos-delay="150" />
										</div>
										<div class="col-md-8 order-2 <?php if($rowIndex % 2 == 0){?> order-lg-1 order-md-1 <?php } ?>">
											<div class="card-body">
												<h5 class="card-title" data-aos="zoom-in" data-aos-delay="<?php echo $delayDuration += 20;?>"><?php echo $this->get_label('home page pop ad advertiser');?></h5>
												<p class="card-text" data-aos="zoom-out" data-aos-delay="<?php echo $delayDuration += 20;?>"><?php echo $this->get_label('home page pop ad description advertiser');?></p>
												<div class="d-flex">
													<?php if($adsDemoURL){?>					
													<a class="demo-button shadow-sm" href="<?php echo $adsDemoURL;?>" data-aos="fade-in" data-aos-delay="130"><?php echo $this->get_label('demo');?> <i class="fa fa-play-circle-o" aria-hidden="true"></i></a>
													<?php } ?>
													<a class="learn-more-button shadow-sm" href="<?php echo $adCreateURL; ?>" data-aos="fade-out" data-aos-delay="130"><?php echo $this->get_label('advertise now');?> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></a>
												</div>
											</div>
										</div>
									</div>
								</div>
							<?php } ?>		

							<?php if($video_ads_enabled == 1){
								$rowIndex++;
								?>									
								<div class="card ads-box <?php if($rowIndex % 2 == 0){?> white <?php } else { ?> gray <?php } ?>" data-aos="zoom-in" data-aos-delay="150">
									<div class="row g-0">			
										<div class="col-md-4 ads-img-margin order-1 <?php if($rowIndex % 2 == 0){?> order-lg-2 order-md-2 <?php } ?>">
											<img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/video-ads.png" class="img-fluid animated" data-aos="slide-right" data-aos-delay="150" />
										</div>
										<div class="col-md-8 order-2 <?php if($rowIndex % 2 == 0){?> order-lg-1 order-md-1 <?php } ?>">
											<div class="card-body">
												<h5 class="card-title" data-aos="zoom-in" data-aos-delay="<?php echo $delayDuration += 20;?>"><?php echo $this->get_label('home page video ad advertiser');?></h5>
												<p class="card-text" data-aos="zoom-out" data-aos-delay="<?php echo $delayDuration += 20;?>"><?php echo $this->get_label('home page video ad description advertiser');?></p>
												<div class="d-flex">
													<?php if($adsDemoURL){?>					
													<a class="demo-button shadow-sm" href="<?php echo $adsDemoURL;?>" data-aos="fade-in" data-aos-delay="130"><?php echo $this->get_label('demo');?> <i class="fa fa-play-circle-o" aria-hidden="true"></i></a>
													<?php } ?>
													<a class="learn-more-button shadow-sm" href="<?php echo $adCreateURL; ?>" data-aos="fade-out" data-aos-delay="130"><?php echo $this->get_label('advertise now');?> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></a>
												</div>
											</div>
										</div>
									</div>
								</div>
							<?php } ?>	

							<?php if($ecommerce_enabled == 1){
								$rowIndex++;
								?>									
								<div class="card ads-box <?php if($rowIndex % 2 == 0){?> white <?php } else { ?> gray <?php } ?>" data-aos="zoom-in" data-aos-delay="150">
									<div class="row g-0">			
										<div class="col-md-4 ads-img-margin order-1 <?php if($rowIndex % 2 == 0){?> order-lg-2 order-md-2 <?php } ?>">
											<img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/ecommerce-ads.png" class="img-fluid animated" data-aos="slide-right" data-aos-delay="150" />
										</div>
										<div class="col-md-8 order-2 <?php if($rowIndex % 2 == 0){?> order-lg-1 order-md-1 <?php } ?>">
											<div class="card-body">
												<h5 class="card-title" data-aos="zoom-in" data-aos-delay="<?php echo $delayDuration += 20;?>"><?php echo $this->get_label('home page ecommerce ad advertiser');?></h5>
												<p class="card-text" data-aos="zoom-out" data-aos-delay="<?php echo $delayDuration += 20;?>"><?php echo $this->get_label('home page ecommerce ad description advertiser');?></p>
												<div class="d-flex">
													<?php if($adsDemoURL){?>					
													<a class="demo-button shadow-sm" href="<?php echo $adsDemoURL;?>" data-aos="fade-in" data-aos-delay="130"><?php echo $this->get_label('demo');?> <i class="fa fa-play-circle-o" aria-hidden="true"></i></a>
													<?php } ?>
													<a class="learn-more-button shadow-sm" href="<?php echo $adCreateURL; ?>" data-aos="fade-out" data-aos-delay="130"><?php echo $this->get_label('advertise now');?> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></a>
												</div>
											</div>
										</div>
									</div>
								</div>
							<?php } ?>	

							<?php if($skin_ads_enabled == 1){
								$rowIndex++;
								?>									
								<div class="card ads-box <?php if($rowIndex % 2 == 0){?> white <?php } else { ?> gray <?php } ?>" data-aos="zoom-in" data-aos-delay="150">
									<div class="row g-0">			
										<div class="col-md-4 ads-img-margin order-1 <?php if($rowIndex % 2 == 0){?> order-lg-2 order-md-2 <?php } ?>">
											<img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/skin-ads.png" class="img-fluid animated" data-aos="slide-right" data-aos-delay="150" />
										</div>
										<div class="col-md-8 order-2 <?php if($rowIndex % 2 == 0){?> order-lg-1 order-md-1 <?php } ?>">
											<div class="card-body">
												<h5 class="card-title" data-aos="zoom-in" data-aos-delay="<?php echo $delayDuration += 20;?>"><?php echo $this->get_label('home page skin ad advertiser');?></h5>
												<p class="card-text" data-aos="zoom-out" data-aos-delay="<?php echo $delayDuration += 20;?>"><?php echo $this->get_label('home page skin ad description advertiser');?></p>
												<div class="d-flex">
													<?php if($adsDemoURL){?>					
													<a class="demo-button shadow-sm" href="<?php echo $adsDemoURL;?>" data-aos="fade-in" data-aos-delay="130"><?php echo $this->get_label('demo');?> <i class="fa fa-play-circle-o" aria-hidden="true"></i></a>
													<?php } ?>
													<a class="learn-more-button shadow-sm" href="<?php echo $adCreateURL; ?>" data-aos="fade-out" data-aos-delay="130"><?php echo $this->get_label('advertise now');?> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></a>
												</div>
											</div>
										</div>
									</div>
								</div>
							<?php } ?>	

							<?php if($affiliate_ads_enabled == 1){
								$rowIndex++;
								?>
								<div class="card ads-box <?php if($rowIndex % 2 == 0){?> white <?php } else { ?> gray <?php } ?>" data-aos="zoom-in" data-aos-delay="150">
									<div class="row g-0">
										<div class="col-md-4 ads-img-margin order-1 <?php if($rowIndex % 2 == 0){?> order-lg-2 order-md-2 <?php } ?>">
											<img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/affiliate-ads.png" class="img-fluid animated" data-aos="slide-left" data-aos-delay="150" />
										</div>
										<div class="col-md-8 order-2 <?php if($rowIndex % 2 == 0){?> order-lg-1 order-md-1 <?php } ?>">
											<div class="card-body">
												<h5 class="card-title" data-aos="zoom-in" data-aos-delay="<?php echo $delayDuration += 20;?>"><?php echo $this->get_label('home page affiliate ad advertiser');?></h5>
												<p class="card-text" data-aos="zoom-out" data-aos-delay="<?php echo $delayDuration += 20;?>"><?php echo $this->get_label('home page affiliate ad description advertiser');?></p>
												<div class="d-flex">
													<?php if($adsDemoURL){?>
													<a class="demo-button shadow-sm" href="<?php echo $adsDemoURL;?>" data-aos="fade-in" data-aos-delay="130"><?php echo $this->get_label('demo');?> <i class="fa fa-play-circle-o" aria-hidden="true"></i></a>
													<?php } ?>
													<a class="learn-more-button shadow-sm" href="<?php echo $adCreateURL; ?>" data-aos="fade-out" data-aos-delay="130"><?php echo $this->get_label('advertise now');?> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></a>
												</div>
											</div>
										</div>										
									</div>
								</div>
							<?php } ?>
						</div>
					<?php } ?>										
			
					<?php if($publisher_dashboard_status == 1){
						$rowIndex = 1;
						$delayDuration = 200;
						?>
						<div class="col-lg-12 box-2 publisher" <?php if($advertiser_dashboard_status == 1){?> style="display: none;" <?php } ?> >
							<div class="card ads-box <?php if($rowIndex % 2 == 0){?> white <?php } else { ?> gray <?php } ?>" data-aos="zoom-in" data-aos-delay="150">
								<div class="row g-0">	
									<div class="col-md-4 ads-img-margin order-1 <?php if($rowIndex % 2 == 0){?> order-lg-2 order-md-2 <?php } ?>">
										<img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/banner-ads.png" class="img-fluid animated" data-aos="slide-right" data-aos-delay="150" />
									</div>	
									<div class="col-md-8 order-2 <?php if($rowIndex % 2 == 0){?> order-lg-1 order-md-1 <?php } ?>">
										<div class="card-body">
											<h5 class="card-title" data-aos="zoom-in" data-aos-delay="<?php echo $delayDuration += 20;?>"><?php echo $this->get_label('home page display ad publisher');?></h5>
											<p class="card-text" data-aos="zoom-out" data-aos-delay="<?php echo $delayDuration += 20;?>"><?php echo $this->get_label('home page display ad description publisher');?></p>
											<div class="d-flex">
												<?php if($adsDemoURL){?>					
												<a class="demo-button shadow-sm" href="<?php echo $adsDemoURL;?>" data-aos="fade-in" data-aos-delay="130"><?php echo $this->get_label('demo');?> <i class="fa fa-play-circle-o" aria-hidden="true"></i></a>
												<?php } ?>
												<a class="learn-more-button shadow-sm" href="<?php echo $adcodeCreateURL; ?>" data-aos="fade-out" data-aos-delay="130"><?php echo $this->get_label('start earning');?> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></a>
											</div>
										</div>
									</div>
								</div>
							</div>

							<?php if($text_ads_enabled == 1){
								$rowIndex++;
								?>
								<div class="card ads-box <?php if($rowIndex % 2 == 0){?> white <?php } else { ?> gray <?php } ?>" data-aos="zoom-in" data-aos-delay="150">
									<div class="row g-0">
										<div class="col-md-4 ads-img-margin order-1 <?php if($rowIndex % 2 == 0){?> order-lg-2 order-md-2 <?php } ?>">
											<img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/text-ads.png" class="img-fluid animated" data-aos="slide-left" data-aos-delay="150" />
										</div>
										<div class="col-md-8 order-2 <?php if($rowIndex % 2 == 0){?> order-lg-1 order-md-1 <?php } ?>">
											<div class="card-body">
												<h5 class="card-title" data-aos="zoom-in" data-aos-delay="<?php echo $delayDuration += 20;?>"><?php echo $this->get_label('home page text ad publisher');?></h5>
												<p class="card-text" data-aos="zoom-out" data-aos-delay="<?php echo $delayDuration += 20;?>"><?php echo $this->get_label('home page text ad description publisher');?></p>
												<div class="d-flex">
													<?php if($adsDemoURL){?>
													<a class="demo-button shadow-sm" href="<?php echo $adsDemoURL;?>" data-aos="fade-in" data-aos-delay="130"><?php echo $this->get_label('demo');?> <i class="fa fa-play-circle-o" aria-hidden="true"></i></a>
													<?php } ?>
													<a class="learn-more-button shadow-sm" href="<?php echo $adcodeCreateURL; ?>" data-aos="fade-out" data-aos-delay="130"><?php echo $this->get_label('start earning');?> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></a>
												</div>
											</div>
										</div>										
									</div>
								</div>
							<?php } ?>	

							<?php if($text_image_enabled == 1){ 
								$rowIndex++;
								?>
								<div class="card ads-box <?php if($rowIndex % 2 == 0){?> white <?php } else { ?> gray <?php } ?>" data-aos="zoom-in" data-aos-delay="150">
									<div class="row g-0">			
										<div class="col-md-4 ads-img-margin order-1 <?php if($rowIndex % 2 == 0){?> order-lg-2 order-md-2 <?php } ?>">
											<img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/text+image-ads.png" class="img-fluid animated" data-aos="slide-right" data-aos-delay="150" />
										</div>
										<div class="col-md-8 order-2 <?php if($rowIndex % 2 == 0){?> order-lg-1 order-md-1 <?php } ?>">
											<div class="card-body">
												<h5 class="card-title" data-aos="zoom-in" data-aos-delay="<?php echo $delayDuration += 20;?>"><?php echo $this->get_label('home page text+image ad publisher');?></h5>
												<p class="card-text" data-aos="zoom-out" data-aos-delay="<?php echo $delayDuration += 20;?>"><?php echo $this->get_label('home page text+image ad description publisher');?></p>
												<div class="d-flex">
													<?php if($adsDemoURL){?>					
														<a class="demo-button shadow-sm" href="<?php echo $adsDemoURL;?>" data-aos="fade-in" data-aos-delay="130"><?php echo $this->get_label('demo');?> <i class="fa fa-play-circle-o" aria-hidden="true"></i></a>
													<?php } ?>
													<a class="learn-more-button shadow-sm" href="<?php echo $adcodeCreateURL; ?>" data-aos="fade-out" data-aos-delay="130"><?php echo $this->get_label('start earning');?> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></a>
												</div>
											</div>
										</div>
									</div>
								</div>
							<?php } ?>	

							<?php if($directlink_enabled == 1){ 
							$rowIndex++;
							?>
								<div class="card ads-box <?php if($rowIndex % 2 == 0){?> white <?php } else { ?> gray <?php } ?>" data-aos="zoom-in" data-aos-delay="150">
									<div class="row g-0">
										<div class="col-md-4 ads-img-margin order-1 <?php if($rowIndex % 2 == 0){?> order-lg-2 order-md-2 <?php } ?>">
											<img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/direct-link-ads.png" class="img-fluid animated" data-aos="slide-left" data-aos-delay="150" />
										</div>
										<div class="col-md-8 order-2 <?php if($rowIndex % 2 == 0){?> order-lg-1 order-md-1 <?php } ?>">
											<div class="card-body">
												<h5 class="card-title" data-aos="zoom-in" data-aos-delay="<?php echo $delayDuration += 20;?>"><?php echo $this->get_label('home page directlink ad publisher');?></h5>
												<p class="card-text" data-aos="zoom-out" data-aos-delay="<?php echo $delayDuration += 20;?>"><?php echo $this->get_label('home page directlink ad description publisher');?></p>
												<div class="d-flex">
													<?php if($adsDemoURL){?>
													<a class="demo-button shadow-sm" href="<?php echo $adsDemoURL;?>" data-aos="fade-in" data-aos-delay="130"><?php echo $this->get_label('demo');?> <i class="fa fa-play-circle-o" aria-hidden="true"></i></a>
													<?php } ?>
													<a class="learn-more-button shadow-sm" href="<?php echo $adcodeCreateURL; ?>" data-aos="fade-out" data-aos-delay="130"><?php echo $this->get_label('start earning');?> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></a>
												</div>
											</div>
										</div>											
									</div>
								</div>
							<?php } ?>	

							<?php if($inpage_push_enabled == 1){ 
							$rowIndex++;
							?>
								<div class="card ads-box <?php if($rowIndex % 2 == 0){?> white <?php } else { ?> gray <?php } ?>" data-aos="zoom-in" data-aos-delay="150">
									<div class="row g-0">			
										<div class="col-md-4 ads-img-margin order-1 <?php if($rowIndex % 2 == 0){?> order-lg-2 order-md-2 <?php } ?>">
											<img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/inpage-push-ads.png" class="img-fluid animated" data-aos="slide-right" data-aos-delay="150" />
										</div>
										<div class="col-md-8 order-2 <?php if($rowIndex % 2 == 0){?> order-lg-1 order-md-1 <?php } ?>">
											<div class="card-body">
												<h5 class="card-title" data-aos="zoom-in" data-aos-delay="<?php echo $delayDuration += 20;?>"><?php echo $this->get_label('home page inpage push ad publisher');?></h5>
												<p class="card-text" data-aos="zoom-out" data-aos-delay="<?php echo $delayDuration += 20;?>"><?php echo $this->get_label('home page inpage push ad description publisher');?></p>
												<div class="d-flex">
													<?php if($adsDemoURL){?>					
													<a class="demo-button shadow-sm" href="<?php echo $adsDemoURL;?>" data-aos="fade-in" data-aos-delay="130"><?php echo $this->get_label('demo');?> <i class="fa fa-play-circle-o" aria-hidden="true"></i></a>
													<?php } ?>
													<a class="learn-more-button shadow-sm" href="<?php echo $adcodeCreateURL; ?>" data-aos="fade-out" data-aos-delay="130"><?php echo $this->get_label('start earning');?> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></a>
												</div>
											</div>
										</div>
									</div>
								</div>
							<?php } ?>	


							<?php if($pop_ads_enabled == 1){ 
							$rowIndex++;
							?>
								<div class="card ads-box <?php if($rowIndex % 2 == 0){?> white <?php } else { ?> gray <?php } ?>" data-aos="zoom-in" data-aos-delay="150">
									<div class="row g-0">
										<div class="col-md-4 ads-img-margin order-1 <?php if($rowIndex % 2 == 0){?> order-lg-2 order-md-2 <?php } ?>">
											<img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/pop-ads.png" class="img-fluid animated" data-aos="slide-left" data-aos-delay="150" />
										</div>
										<div class="col-md-8 order-2 <?php if($rowIndex % 2 == 0){?> order-lg-1 order-md-1 <?php } ?>">
											<div class="card-body">
												<h5 class="card-title" data-aos="zoom-in" data-aos-delay="<?php echo $delayDuration += 20;?>"><?php echo $this->get_label('home page pop ad publisher');?></h5>
												<p class="card-text" data-aos="zoom-out" data-aos-delay="<?php echo $delayDuration += 20;?>"><?php echo $this->get_label('home page pop ad description publisher');?></p>
												<div class="d-flex">
													<?php if($adsDemoURL){?>
													<a class="demo-button shadow-sm" href="<?php echo $adsDemoURL;?>" data-aos="fade-in" data-aos-delay="130"><?php echo $this->get_label('demo');?> <i class="fa fa-play-circle-o" aria-hidden="true"></i></a>
													<?php } ?>
													<a class="learn-more-button shadow-sm" href="<?php echo $adcodeCreateURL; ?>" data-aos="fade-out" data-aos-delay="130"><?php echo $this->get_label('start earning');?> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></a>
												</div>
											</div>
										</div>											
									</div>
								</div>
							<?php } ?>	

							
							<?php if($video_ads_enabled == 1){ 
							$rowIndex++;
							?>
								<div class="card ads-box <?php if($rowIndex % 2 == 0){?> white <?php } else { ?> gray <?php } ?>" data-aos="zoom-in" data-aos-delay="150">
									<div class="row g-0">
										<div class="col-md-4 ads-img-margin order-1 <?php if($rowIndex % 2 == 0){?> order-lg-2 order-md-2 <?php } ?>">
											<img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/video-ads.png" class="img-fluid animated" data-aos="slide-left" data-aos-delay="150" />
										</div>
										<div class="col-md-8 order-2 <?php if($rowIndex % 2 == 0){?> order-lg-1 order-md-1 <?php } ?>">
											<div class="card-body">
												<h5 class="card-title" data-aos="zoom-in" data-aos-delay="<?php echo $delayDuration += 20;?>"><?php echo $this->get_label('home page video ad publisher');?></h5>
												<p class="card-text" data-aos="zoom-out" data-aos-delay="<?php echo $delayDuration += 20;?>"><?php echo $this->get_label('home page video ad description publisher');?></p>
												<div class="d-flex">
													<?php if($adsDemoURL){?>
													<a class="demo-button shadow-sm" href="<?php echo $adsDemoURL;?>" data-aos="fade-in" data-aos-delay="130"><?php echo $this->get_label('demo');?> <i class="fa fa-play-circle-o" aria-hidden="true"></i></a>
													<?php } ?>
													<a class="learn-more-button shadow-sm" href="<?php echo $adcodeCreateURL; ?>" data-aos="fade-out" data-aos-delay="130"><?php echo $this->get_label('start earning');?> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></a>
												</div>
											</div>
										</div>											
									</div>
								</div>
							<?php } ?>	

							
							<?php if($interstitial_enabled == 1){ 
							$rowIndex++;
							?>
								<div class="card ads-box <?php if($rowIndex % 2 == 0){?> white <?php } else { ?> gray <?php } ?>" data-aos="zoom-in" data-aos-delay="150">
									<div class="row g-0">
										<div class="col-md-4 ads-img-margin order-1 <?php if($rowIndex % 2 == 0){?> order-lg-2 order-md-2 <?php } ?>">
											<img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/interstitial-ads.png" class="img-fluid animated" data-aos="slide-left" data-aos-delay="150" />
										</div>
										<div class="col-md-8 order-2 <?php if($rowIndex % 2 == 0){?> order-lg-1 order-md-1 <?php } ?>">
											<div class="card-body">
												<h5 class="card-title" data-aos="zoom-in" data-aos-delay="<?php echo $delayDuration += 20;?>"><?php echo $this->get_label('home page interstitial ad publisher');?></h5>
												<p class="card-text" data-aos="zoom-out" data-aos-delay="<?php echo $delayDuration += 20;?>"><?php echo $this->get_label('home page interstitial ad description publisher');?></p>
												<div class="d-flex">
													<?php if($adsDemoURL){?>
													<a class="demo-button shadow-sm" href="<?php echo $adsDemoURL;?>" data-aos="fade-in" data-aos-delay="130"><?php echo $this->get_label('demo');?> <i class="fa fa-play-circle-o" aria-hidden="true"></i></a>
													<?php } ?>
													<a class="learn-more-button shadow-sm" href="<?php echo $adcodeCreateURL; ?>" data-aos="fade-out" data-aos-delay="130"><?php echo $this->get_label('start earning');?> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></a>
												</div>
											</div>
										</div>											
									</div>
								</div>
							<?php } ?>	
							
							<?php if($skin_ads_enabled == 1){ 
							$rowIndex++;
							?>
								<div class="card ads-box <?php if($rowIndex % 2 == 0){?> white <?php } else { ?> gray <?php } ?>" data-aos="zoom-in" data-aos-delay="150">
									<div class="row g-0">
										<div class="col-md-4 ads-img-margin order-1 <?php if($rowIndex % 2 == 0){?> order-lg-2 order-md-2 <?php } ?>">
											<img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/skin-ads.png" class="img-fluid animated" data-aos="slide-left" data-aos-delay="150" />
										</div>
										<div class="col-md-8 order-2 <?php if($rowIndex % 2 == 0){?> order-lg-1 order-md-1 <?php } ?>">
											<div class="card-body">
												<h5 class="card-title" data-aos="zoom-in" data-aos-delay="<?php echo $delayDuration += 20;?>"><?php echo $this->get_label('home page skin ad publisher');?></h5>
												<p class="card-text" data-aos="zoom-out" data-aos-delay="<?php echo $delayDuration += 20;?>"><?php echo $this->get_label('home page skin ad description publisher');?></p>
												<div class="d-flex">
													<?php if($adsDemoURL){?>
													<a class="demo-button shadow-sm" href="<?php echo $adsDemoURL;?>" data-aos="fade-in" data-aos-delay="130"><?php echo $this->get_label('demo');?> <i class="fa fa-play-circle-o" aria-hidden="true"></i></a>
													<?php } ?>
													<a class="learn-more-button shadow-sm" href="<?php echo $adcodeCreateURL; ?>" data-aos="fade-out" data-aos-delay="130"><?php echo $this->get_label('start earning');?> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></a>
												</div>
											</div>
										</div>											
									</div>
								</div>
							<?php } ?>	

							<?php if($affiliate_ads_enabled == 1){ 
							$rowIndex++;
							?>
								<div class="card ads-box <?php if($rowIndex % 2 == 0){?> white <?php } else { ?> gray <?php } ?>" data-aos="zoom-in" data-aos-delay="150">
									<div class="row g-0">
										<div class="col-md-4 ads-img-margin order-1 <?php if($rowIndex % 2 == 0){?> order-lg-2 order-md-2 <?php } ?>">
											<img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/affiliate-ads.png" class="img-fluid animated" data-aos="slide-left" data-aos-delay="150" />
										</div>
										<div class="col-md-8 order-2 <?php if($rowIndex % 2 == 0){?> order-lg-1 order-md-1 <?php } ?>">
											<div class="card-body">
												<h5 class="card-title" data-aos="zoom-in" data-aos-delay="<?php echo $delayDuration += 20;?>"><?php echo $this->get_label('home page affiliate ad publisher');?></h5>
												<p class="card-text" data-aos="zoom-out" data-aos-delay="<?php echo $delayDuration += 20;?>"><?php echo $this->get_label('home page affiliate ad description publisher');?></p>
												<div class="d-flex">
													<?php if($adsDemoURL){?>
													<a class="demo-button shadow-sm" href="<?php echo $adsDemoURL;?>" data-aos="fade-in" data-aos-delay="130"><?php echo $this->get_label('demo');?> <i class="fa fa-play-circle-o" aria-hidden="true"></i></a>
													<?php } ?>
													<a class="learn-more-button shadow-smsss" href="<?php echo $adcodeCreateURL; ?>" data-aos="fade-out" data-aos-delay="130"><?php echo $this->get_label('start earning');?> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></a>
												</div>
											</div>
										</div>											
									</div>
								</div>
							<?php } ?>	
						</div>	
					<?php } ?>									
				</div>
			</div>
		</div>
	</div>
</section>

<!-- ========================= Key Features ========================= -->
	
<section id="main-key-features" class="keyfeatures-wrap">
<div class="container">
	  
	<div class="row">
		<div class="col-12">
			<div class="section-title key-features text-center" data-aos="fade-down" data-aos-delay="150">
				<h2 class="pb-2">
					<?php echo $this->get_label_format($this->get_label('why choose', array("x" => $admarket_name))); ?>
				</h2>
				<div class="devider" alt="" data-aos="zoom-out" data-aos-delay="1200"></div>
			</div>
		</div>
	</div>

	<div class="single-head">
		<div class="row">
			
			<div class="col-lg-4 col-md-6 col-12">
			<div class="single-feature" data-aos="zoom-out" data-aos-delay="150">
			<div class="icon">
			<i><img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/advanced-targeting-icon.webp"></i>
			</div>
			<h3><?php echo $this->get_label('key features title one');?></h3>
			<p><?php echo $this->get_label('key features description one');?></p>
			</div>
			</div>
			
		<div class="col-lg-4 col-md-6 col-12">
		<div class="single-feature" data-aos="zoom-out" data-aos-delay="170">
		<div class="icon">
		<i><img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/real-time-analytics.webp"></i>
		</div>
		<h3><?php echo $this->get_label('key features title two');?></h3>
		<p><?php echo $this->get_label('key features description two');?></p>
		</div>
		</div>
			
			<div class="col-lg-4 col-md-6 col-12">
			<div class="single-feature" data-aos="zoom-out" data-aos-delay="190">
			<div class="icon">
			<i><img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/multiple-ad-formats.webp"></i>
			</div>
			<h3><?php echo $this->get_label('key features title three');?></h3>
			<p><?php echo $this->get_label('key features description three');?></p>
			</div>
			</div>
			
		<div class="col-lg-4 col-md-6 col-12">
		<div class="single-feature" data-aos="zoom-out" data-aos-delay="210">
		<div class="icon">
		<i><img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/fraud-protection-icon.webp"></i>
		</div>
		<h3><?php echo $this->get_label('key features title four');?></h3>
		<p><?php echo $this->get_label('key features description four');?></p>
		</div>
		</div>
			
			<div class="col-lg-4 col-md-6 col-12">
			<div class="single-feature" data-aos="zoom-out" data-aos-delay="230">
			<div class="icon">
			<i><img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/intelligent-optimization-icon.webp"></i>
			</div>
			<h3><?php echo $this->get_label('key features title five');?></h3>
			<p><?php echo $this->get_label('key features description five');?></p>
			</div>
			</div>
			
		<div class="col-lg-4 col-md-6 col-12">
			<div class="single-feature" data-aos="zoom-out" data-aos-delay="250">
				<div class="icon">
					<i><img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/publisher-monetization-icon.webp"></i>
				</div>
				<h3><?php echo $this->get_label('key features title six');?></h3>
				<p><?php echo $this->get_label('key features description six');?></p>
			</div>
		</div>


		<div class="col-lg-4 col-md-6 col-12">
			<div class="single-feature" data-aos="zoom-out" data-aos-delay="270">
				<div class="icon">
					<i><img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/advertiser-budget-control-icon.webp"></i>
				</div>
				<h3><?php echo $this->get_label('key features title seven');?></h3>
				<p><?php echo $this->get_label('key features description seven');?></p>
			</div>
		</div>

		<div class="col-lg-4 col-md-6 col-12">
			<div class="single-feature" data-aos="zoom-out" data-aos-delay="290">
				<div class="icon">
				<i><img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/transparent-payments-icon.webp"></i>
				</div>
				<h3><?php echo $this->get_label('key features title eight');?></h3>
				<p><?php echo $this->get_label('key features description eight');?></p>
			</div>
		</div>

		<div class="col-lg-4 col-md-6 col-12">
			<div class="single-feature" data-aos="zoom-out" data-aos-delay="310">
				<div class="icon">
				<i><img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/dedicated-support-icon.webp"></i>
				</div>
				<h3><?php echo $this->get_label('key features title nine');?></h3>
				<p><?php echo $this->get_label('key features description nine');?></p>
			</div>
		</div>
			
		</div>
	</div>
</div>
</section>

	<!-- ========================= digital market section ========================= -->

<section class="digital-market">	
	<div class="container">
		<div class="row join-box" data-aos="zoom-out" data-aos-delay="150">
			<h2 data-aos="fade-down" data-aos-delay="200"><?php echo $this->get_label('digital market title');?></h2>
			<p data-aos="fade-up" data-aos-delay="250"><?php echo $this->get_label('digital market description',array('x'=>$admarket_name));?></p>
			
		<div class="d-flex gap-2 justify-content-center">
		<a href="<?php echo $advurl; ?>" class="join-box-btn" data-aos="zoom-in" data-aos-delay="260"><?php echo $this->get_label('advertiser'); ?><span></span><span></span><span></span><span></span></a>
		<a href="<?php echo $puburl; ?>" class="join-box-btn" data-aos="zoom-in" data-aos-delay="280"><?php echo $this->get_label('publisher'); ?><span></span><span></span><span></span><span></span></a>
		</div>
			
		</div>
	</div>	
</section>
	
	
<!-- ========================= pricing section start========================= -->
<?php if($cpc_enabled == 1 || $cpm_enabled == 1 || $cpa_enabled == 1 || $cpd_enabled == 1){?>
<section class="pricing-section">
	<div class="container">
		<div class="row row justify-content-center">		
			<div class="pricing-titile-section">
				<h2 class="mt-2 pb-2 text-white" data-aos="zoom-out" data-aos-delay="200"><?php  echo $this->get_label_format($this->get_label('pricing earnings models'));?></h2>
				<div class="devider-white" alt="" data-aos="zoom-in" data-aos-delay="400"></div>
			</div>

			<?php if($cpc_enabled == 1){?>
				<div class="pricing-block col-xl-6 col-md-6">
					<div class="inner-box" data-aos="zoom-in" data-aos-delay="150">
						<div class="icon-box">
							<i class="icon" data-aos="zoom-out" data-aos-delay="300">
								<img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/home-page-pricing-cpc.png"/>
							</i>
						</div>
						<div class="content">
							<h6 class="title"><?php echo $this->get_label('cpc');?></h6>
							<div class="text"><?php echo $this->get_label('cpc description');?></div>
						</div>
					</div>
				</div>
			<?php } ?>
				
			<?php if($cpm_enabled == 1){?>
				<div class="pricing-block col-xl-6 col-md-6">
					<div class="inner-box" data-aos="zoom-in" data-aos-delay="200">
						<div class="icon-box">
							<i class="icon" data-aos="zoom-out" data-aos-delay="500">
								<img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/home-page-pricing-cpm.png"/>
							</i>
						</div>
						<div class="content">
							<h6 class="title"><?php echo $this->get_label('cpm');?></h6>
							<div class="text"><?php echo $this->get_label('cpm description');?></div>
						</div>
					</div>
				</div>		
			<?php } ?>

			<?php if($cpa_enabled == 1){?>
				<div class="pricing-block col-xl-6 col-md-6">
					<div class="inner-box" data-aos="zoom-in" data-aos-delay="250">
						<div class="icon-box">
							<i class="icon" data-aos="zoom-out" data-aos-delay="500">
								<img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/home-page-pricing-cpa.png"/>
							</i>
						</div>
						<div class="content">
							<h6 class="title"><?php echo $this->get_label('cpa');?></h6>
							<div class="text"><?php echo $this->get_label('cpa description');?></div>
						</div>
					</div>
				</div>
			<?php } ?>

			<?php if($cpd_enabled == 1){?>
				<div class="pricing-block col-xl-6 col-md-6">
					<div class="inner-box" data-aos="zoom-in" data-aos-delay="300">
						<div class="icon-box">
							<i class="icon" data-aos="zoom-out" data-aos-delay="500">
								<img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/home-page-pricing-cpd.png"/>
							</i>
						</div>
						<div class="content">
							<h6 class="title"><?php echo $this->get_label('cpd');?></h6>
							<div class="text"><?php echo $this->get_label('cpd description');?></div>
						</div>
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

<?php $this->dispatch("index/testimonial");?>

<?php $this->dispatch("index/slider");?>

</main>

<?php if($advertiser_dashboard_status == 1 && $publisher_dashboard_status == 1){?>
	<script type="text/javascript">	
		$(document).ready(function () {
			window.showHideVendorType = function (type) {
			if (type === 'single') {
				$('.publisher').fadeOut(300, function () {
				$('.advertiser').fadeIn(300);
				});
				$('#switchSingle').prop('checked', true);
			} else if (type === 'multi') {
				$('.advertiser').fadeOut(300, function () {
				$('.publisher').fadeIn(300);
				});
				$('#switchMulti').prop('checked', true);
			}
			};

			// Optional: sync with radio buttons too
			$('#switchSingle').on('change', function () {
			showHideVendorType('single');
			});

			$('#switchMulti').on('change', function () {
			showHideVendorType('multi');
			});
		});
	</script>
<?php } ?>	
	
	

<?php $this->dispatch("layout/footer/16");?>
