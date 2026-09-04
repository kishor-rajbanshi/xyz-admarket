<?php $this->dispatch("layout/header/17");
$logedin=intval($this->get_variable('logedin'));
$admarket_name=Configuration::get_instance()->read('admarket_name');
?>

<!-- subheader -->
<section id="subheader" class="inner_head common-head advertiser_head" data-speed="8" data-type="background">
<div class="container">
<div class="row">
<div class="col-md-12">
<h1><?php echo $this->get_label('advertiser');?></h1>

</div>
</div>
</div>
</section>
<!-- subheader close -->

<!-- content begin -->

 
 
 
 
<section id="section-advertiser-content" class="side-bg" style="position:relative; padding-top:60px; padding-bottom:60px;">
<div class="container">
<div class="row">
 


<!-- <div class="col-md-5 animated fadeInRight" data-animation="fadeInRight" data-delay="200" style="opacity: 0;">
<img class="img-responsive" src="images/search-ads.png"/>

</div>-->


<div class="col-md-12 text-center " data-animation="fadeInUp" data-delay="0">
<h2><span><?php echo $this->get_label('increase your roi with');?></span><?php echo $admarket_name;?></h2>

<p class="intro"><?php echo $this->get_label('advertiser home desc',array('x'=>$admarket_name));?>
</p>

<?php if($logedin ==0){?>
<a href="<?php echo $this->make_url("index/register/1"); ?>"><button class="inner_register"><?php echo $this->get_label('get started now');?></button></a>
<?php }?>

</div>
</div>


</div>


</section>






<section class="section_padding" style="background-color:#eeeeee;">
<div class="container">

<div class="col-md-4 col-lg-4 col-sm-4 col-xs-4 adv_steps mobile_view animated" data-animation="fadeInUp" >
<div class="inner_icon"><i class="fa fa-sign-in" aria-hidden="true"></i></div>
<h3>1.<?php echo $this->get_label('sign up');?></h3>
<p><?php echo $this->get_label('sign up for a free advertiser account');?> </p>
</div>

<div class="col-md-4 col-lg-4 col-sm-4 col-xs-4 adv_steps mobile_view animated" data-animation="fadeInUp" >
<div class="inner_icon"><i class="fa fa-location-arrow" aria-hidden="true"></i></div>
<h3>2.<?php echo $this->get_label('create ads');?></h3>
<p><?php echo $this->get_label('you can create ads');?> </p>
</div>

<div class="col-md-4 col-lg-4 col-sm-4 col-xs-4 adv_steps mobile_view animated" data-animation="fadeInUp" >
<div class="inner_icon"><i class="fa fa-signal" aria-hidden="true"></i></div>
<h3>3.<?php echo $this->get_label('increase leads');?></h3>
<p><?php echo $this->get_label('our system will premote your products or services');?></p>
</div>
</div>

</section>






<section id="section-advertiser-content" class="side-bg no-padding advertiser_content_bg" style="padding-top:60px; position:relative;">
<div class="container">
<div class="row"><div class="col-md-12">
<!-- Nav tabs -->
<?php 
$ecommerce_enabled         = $this->get_addon_status('ecommerce-ads_enabled');
$text_image_enabled      = $this->get_addon_status('text-image-ads_enabled');
$interstitial_enabled    = $this->get_addon_status('interstitial_enabled');
$pop_ads_enabled 		 = $this->get_addon_status('pop-ads_enabled');

?>
<div class="card">
<ul class="nav nav-tabs inner_menu" role="tablist">
<li role="presentation" class="active"><a href="#Text-Ads" aria-controls="Text-Ads" role="tab" data-toggle="tab"><?php echo $this->get_label('text ads');?></a></li>
<li role="presentation"><a href="#Banner-Ads" aria-controls="Banner-Ads" role="tab" data-toggle="tab"><?php echo $this->get_label('banner ads');?></a></li>
<?php if($ecommerce_enabled ==1){ ?>
<li role="presentation"><a href="#E-Commerce-Ads" aria-controls="E-Commerce-Ads" role="tab" data-toggle="tab"><?php echo $this->get_label('e-commerce ads');?></a></li>
<?php } 
if($text_image_enabled ==1){ ?>
<li role="presentation"><a href="#Text-Image-Ads" aria-controls="Text-Image-Ads" role="tab" data-toggle="tab"><?php echo $this->get_label('text+image ads');?></a></li>
<?php } 
if($interstitial_enabled ==1){ ?>
<li role="presentation"><a href="#Interstitial-Ads" aria-controls="Interstitial-Ads" role="tab" data-toggle="tab"><?php echo $this->get_label('interstitial ads');?></a></li>
<?php } 
if($pop_ads_enabled ==1){ ?>
<li role="presentation"><a href="#Pop-Ads" aria-controls="Pop-Ads" role="tab" data-toggle="tab"><?php echo $this->get_label('pop ads');?></a></li>
<?php } 
?>
</ul>
<!-- Tab panes -->
<div class="col-lg-12">
<div class="tab-content row">

 
<div role="tabpanel" class="tab-pane col-lg-12 active" id="Text-Ads">
<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
<div class="row">

<div class="col-lg-5 col-sm-5 col-md-5 col-xs-12 animated" data-animation="fadeInLeft" data-delay="100">
<img class="img-responsive" src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/text-ads.png"/>
</div>

<div class="col-lg-7 col-sm-7 col-md-7 col-xs-12 animated" data-animation="fadeInRight" data-delay="100">
<h4><?php echo $this->get_label('text ad head');?></h4>

<p style="text-align:justify;"><?php echo $this->get_label('text ad content p1',array('x'=>$admarket_name));?></p>
<p style="text-align:justify;"><?php echo $this->get_label('text ad content p2',array('x'=>$admarket_name));?></p>
                                        </div>

                                        </div>

                                        </div>
                                        </div>


                                        <div role="tabpanel" class="tab-pane fade" id="E-Commerce-Ads">

                                        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                                        <div class="row">

                                        <div class="col-lg-5 col-sm-5 col-md-5 col-xs-12 animated" data-animation="fadeInLeft" data-delay="100">
                                       <img class="img-responsive" src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/search-ads.png"/>
                                        </div>

                                        <div class="col-lg-7 col-sm-7 col-md-7 col-xs-12 animated" data-animation="fadeInRight" data-delay="100">
                                        <h4><?php echo $this->get_label('e-commerce head');?></h4>

                                        <p style="text-align:justify;"><?php echo $this->get_label('e-commerce content');?></p>


                                        </div>

                                        </div>

                                        </div>
                                        </div>




                                        <div role="tabpanel" class="tab-pane fade" id="Banner-Ads">

                                        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                                        <div class="row">

                                        <div class="col-lg-5 col-sm-5 col-md-5 col-xs-12 animated" data-animation="fadeInLeft" data-delay="100">
                                        <img class="img-responsive" src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/banner-ads.png"/>
                                        </div>

                                        <div class="col-lg-7 col-sm-7 col-md-7 col-xs-12 animated" data-animation="fadeInRight" data-delay="100">
                                        <h4><?php echo $this->get_label('banner ad head');?></h4>

                                        <p style="text-align:justify;"><?php echo $this->get_label('banner ad content p1');?></p>
										<p style="text-align:justify;"><?php echo $this->get_label('banner ad content p2',array('x'=>$admarket_name));?></p>
										

                                        </div>

                                        </div>

                                        </div>

                                        </div>



                                        <div role="tabpanel" class="tab-pane fade" id="Text-Image-Ads">


                                        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                                        <div class="row">

                                        <div class="col-lg-5 col-sm-5 col-md-5 col-xs-12 animated" data-animation="fadeInLeft" data-delay="100">
                                         <img class="img-responsive" src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/text-image.png"/>
                                        </div>

                                        <div class="col-lg-7 col-sm-7 col-md-7 col-xs-12 animated" data-animation="fadeInRight" data-delay="100">
                                        <h4><?php echo $this->get_label('text+image ad head');?></h4>

                                        <p style="text-align:justify;"><?php echo $this->get_label('text+image ad content p1');?></p>

									   <p style="text-align:justify;"><?php echo $this->get_label('text+image ad content p2');?></p>
										

                                        </div>

                                        </div>

                                        </div>




                                        </div>



                                        <div role="tabpanel" class="tab-pane fade" id="Interstitial-Ads">


                                        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                                        <div class="row">

                                        <div class="col-lg-5 col-sm-5 col-md-5 col-xs-12 animated" data-animation="fadeInLeft" data-delay="100">
                                        <img class="img-responsive" src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/search-ads.png"/>
                                        </div>

                                        <div class="col-lg-7 col-sm-7 col-md-7 col-xs-12 animated" data-animation="fadeInRight" data-delay="100">
                                        <h4><?php echo $this->get_label('interstitial ad head');?></h4>

                                        <p style="text-align:justify;"><?php echo $this->get_label('interstitial ad content p1');?></p>

                                        <p style="text-align:justify;"><?php echo $this->get_label('interstitial ad content p2');?></p>


                                        </div>

                                        </div>

                                        </div>
										</div>
										


										 <div role="tabpanel" class="tab-pane fade" id="Pop-Ads">

                                        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                                        <div class="row">

                                        <div class="col-lg-5 col-sm-5 col-md-5 col-xs-12 animated" data-animation="fadeInLeft" data-delay="100">
                                       <img class="img-responsive" src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/search-ads.png"/>
                                        </div>

                                        <div class="col-lg-7 col-sm-7 col-md-7 col-xs-12 animated" data-animation="fadeInRight" data-delay="100">
                                        <h4><?php echo $this->get_label('pop ads head');?></h4>

                                        <p style="text-align:justify;"><?php echo $this->get_label('pop ads content p1',array('x'=>$admarket_name));?></p>
                                         <p style="text-align:justify;"><?php echo $this->get_label('pop ads content p2');?></p>


                                        </div>

                                        </div>

                                        </div>
                                        </div>
										




                                    </div>
</div>
                                </div>
	</div>
</div>

    



                </div>


            </section>
      
      
      
      
      
 <?php 
$cpc_enabled     = $this->get_addon_status('cpc_enabled');
$cpm_enabled     = $this->get_addon_status('cpm_enabled');
$cpa_enabled     = $this->get_addon_status('cpa_enabled');
$sponsored	     = $this->get_addon_status('sponsored_enabled');

if($cpc_enabled==1 || $cpm_enabled==1 || $cpa_enabled==1 || $sponsored==1){
?>     
      
      <section>
           <div class="f-about js-bg-img padding-lg-b20">
            <div class="container padding-lg-lr0">
                <div class="row">

                <div class="content_first_img col-lg-6 col-sm-6 col-md-6 col-xs-12">


                <img class="img-responsive" src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/i1.jpg" />

                </div>

                 <div class="f-second-slide col-lg-6 col-sm-6 col-md-6 col-xs-12 animated" data-animation="fadeInLeft" data-delay="100">
                                    <div class="f-about__img margin-lg-t210 margin-lg-b125 margin-md-b50 "><img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/i1.jpg" alt="img"></div>
                                </div>




                    <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                        <div class="f-about__text padding-lg-t235 padding-sm-t100  padding-sm-b100">
                            <h3 class="f-head-h2 animated main_header2" data-animation="fadeInUp" data-delay="100"><span class="id-color"><?php echo $this->get_label('pricing model');?></span></h3>
                           <div class="row">
                            <div class="f-feature col-md-12 col-lg-12 col-sm-12 col-xs-6 mobile_view">
                              <?php if($cpc_enabled ==1){ ?>
                                <div class="animated pricing_box row" data-animation="fadeInRight" data-delay="100">
                                <div class="col-md-3 col-lg-3 col-sm-3 col-xs-12 ">
                                     <img class="img-responsive img-icon" src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/cpc.png"/>
                                     </div>
                                    <div class="col-md-9 col-lg-9 col-sm-9 col-xs-12 ">
                                    <h5 class="f-head-h5"><?php echo $this->get_label('cost per click');?></h5>
                                    <p style="text-align:justify;"><?php echo $this->get_label('cost per click desc');?></p>
                                </div>
                                </div></div>
                                <?php  } 
                                if($cpm_enabled ==1){ ?>
                                 <div class="f-feature col-md-12 col-lg-12 col-sm-12 col-xs-6 mobile_view">
						 	<div class="animated pricing_box row" data-animation="fadeInRight" data-delay="100">

                                  <div class="col-md-3 col-lg-3 col-sm-3 col-xs-12 ">
                                     <img class="img-responsive img-icon" src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/cpm.png"/>
                                     </div>
                                    <div class="col-md-9 col-lg-9 col-sm-9 col-xs-12 ">
                                    <h5 class="f-head-h5"><?php echo $this->get_label('cost per mile');?></h5>
                                    <p style="text-align:justify;"><?php echo $this->get_label('cost per mile desc');?></p>
                                </div></div>
                                <?php } ?>
                            </div>
                            </div>

                             <div class="row">
                             <div class="f-feature col-md-12 col-lg-12 col-sm-12 col-xs-6 mobile_view">
                             <?php if($cpa_enabled ==1){ ?>
                                 <div class="animated pricing_box row" data-animation="fadeInRight" data-delay="100">
                                  <div class="col-md-3 col-lg-3 col-sm-3 col-xs-12 ">
                                     <img class="img-responsive img-icon" src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/cpa.png"/>
                                     </div>
                                   <div class="col-md-9 col-lg-9 col-sm-9 col-xs-12 ">
                                    <h5 class="f-head-h5"><?php echo $this->get_label('cost per action');?></h5>
                                    <p style="text-align:justify;"><?php echo $this->get_label('cost per action desc');?></p>
                                </div></div></div>
                                <?php } 
                               
                                if($sponsored ==1){ ?>
                                                               <div class="animated pricing_box row" data-animation="fadeInRight" data-delay="100">
                                                                  <div class="col-md-3 col-lg-3 col-sm-3 col-xs-12 ">
                                                                     <img class="img-responsive img-icon" src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/sponsored.png"/>
                                                                     </div>
                                                                   <div class="col-md-9 col-lg-9 col-sm-9 col-xs-12 ">
                                                                    <h5 class="f-head-h5"><?php echo $this->get_label('sponsored');?></h5>
                                                                    <p style="text-align:justify;"><?php echo $this->get_label('sponsored desc');?></p>
                                                                </div></div></div>
                                                                <?php } ?>
                               
                            </div>
                         </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        </div>
<?php }  ?>


 <?php 
$time_enabled    	 = $this->get_addon_status('time-targeting_enabled');
$browser_enabled     = $this->get_addon_status('browser_enabled');
$os_enabled     	 = $this->get_addon_status('os_enabled');
$retargeting_enabled = $this->get_addon_status('retargeting_enabled');
$city_enabled    	 = $this->get_addon_status('city-targeting_enabled');
$category_enabled     = $this->get_addon_status('category-targeting_enabled');

?>    
<section class="advertiser_content_bg2">

	<div class="container">

		<div class="col-md-12 text-center">
			<h1 class="animated main_header fadeInUp" data-animation="fadeInUp"
				style="opacity: 0; color: #FFFFFF;">
				<?php echo $this->get_label('targetting');?> <span class="id-color"> <?php echo $this->get_label('options');?></span> <span
					class="small-border animated fadeInUp" data-animation="fadeInUp"
					style="opacity: 0;"></span>
			</h1>
		
			<div class="spacer-single"></div>
		</div>





		<div class="col-sm-12 text-center row srvc_block">
			<div class="col-lg-3 col-sm-6 col-md-4 col-xs-6 mobile_view animated"  data-animation="fadeInUp">
				<div class="col-lg-12 col-sm-12 cil-md-12 col-xs-12 srvc_item">
					<i class="fa fa-3x fa-bullseye"></i>
					<h3><?php echo $this->get_label('keyword targeting');?></h3>
					<p><?php echo $this->get_label('keyword targeting desc');?>
					</p>

				</div>
			</div>
		
			<div class="col-lg-3 col-sm-6 col-md-4 col-xs-6 mobile_view animated"  data-animation="fadeInUp">
				<div class="col-lg-12 col-sm-12 cil-md-12 col-xs-12 srvc_item">
                	<i class="fa fa-3x fa-map-marker"></i>
					<h3><?php echo $this->get_label('geotargeting');?></h3>
					<p>
					<?php echo $this->get_label('geotargeting desc');?>
						
						</p>

				</div>
			</div>
			
			<div class="col-lg-3 col-sm-6 col-md-4 col-xs-6 mobile_view animated"  data-animation="fadeInUp">
				<div class="col-lg-12 col-sm-12 cil-md-12 col-xs-12 srvc_item">
					<i class="fa fa-3x fa-desktop"></i>
					<h3><?php echo $this->get_label('device targeting');?></h3>
					<p><?php echo $this->get_label('device targeting desc');?>
					</p>

				</div>
			</div>

			<div class="col-lg-3 col-sm-6 col-md-4 col-xs-6 mobile_view animated"  data-animation="fadeInUp">
				<div class="col-lg-12 col-sm-12 cil-md-12 col-xs-12 srvc_item">
					<i class="fa fa-3x fa-globe"></i>

					<h3><?php echo $this->get_label('language targeting');?></h3>
					<p><?php echo $this->get_label('language targeting desc');?>
					</p>

				</div>
			</div>
			
			
			
			
			
			
			<?php if($time_enabled == 1){ ?>
			<div class="col-lg-3 col-sm-6 col-md-4 col-xs-6 mobile_view animated"  data-animation="fadeInUp">
				<div class="col-lg-12 col-sm-12 cil-md-12 col-xs-12 srvc_item">
					<i class="fa fa-3x fa-clock-o"></i>
					<h3><?php echo $this->get_label('time targeting');?></h3>
					<p><?php echo $this->get_label('time targeting desc');?>
					</p>

				</div>
			</div>
		    <?php } 
		    if($browser_enabled == 1){ ?>
			<div class="col-lg-3 col-sm-6 col-md-4 col-xs-6 mobile_view animated"  data-animation="fadeInUp">
				<div class="col-lg-12 col-sm-12 cil-md-12 col-xs-12 srvc_item">
					<i class="fa fa-3x fa-camera "></i>

					<h3><?php echo $this->get_label('browser targeting');?></h3>
					<p><?php echo $this->get_label('browser targeting desc');?>
					</p>

				</div>
			</div>
			<?php }
		    if($os_enabled == 1){ ?>
			<div class="col-lg-3 col-sm-6 col-md-4 col-xs-6 mobile_view animated"  data-animation="fadeInUp">
				<div class="col-lg-12 col-sm-12 cil-md-12 col-xs-12 srvc_item">
					<i class="fa fa-3x fa-picture-o"></i>
					<h3><?php echo $this->get_label('os targeting');?></h3>
					<p><?php echo $this->get_label('os targeting desc');?>
					</p>

				</div>
			</div>
			<?php }
			if($retargeting_enabled == 1){ ?>
			<div class="col-lg-3 col-sm-6 col-md-4 col-xs-6 mobile_view animated"  data-animation="fadeInUp">
				<div class="col-lg-12 col-sm-12 cil-md-12 col-xs-12 srvc_item">
					<i class="fa fa-3x fa fa-share"></i>
					<h3><?php echo $this->get_label('retargeting');?></h3>
					<p><?php echo $this->get_label('retargeting desc');?>
					</p>

				</div>
			</div>
			<?php } 
			if($category_enabled == 1){ ?>
				<div class="col-lg-3 col-sm-6 col-md-4 col-xs-6 mobile_view animated"  data-animation="fadeInUp">
				<div class="col-lg-12 col-sm-12 cil-md-12 col-xs-12 srvc_item">
					<i class="fa fa-3x fa fa-bars"></i>
					<h3><?php echo $this->get_label('category targeting');?></h3>
					<p><?php echo $this->get_label('category targeting desc');?>
					</p>

				</div>
			</div>
			<?php }
			if($city_enabled == 1){ ?>
				<div class="col-lg-3 col-sm-6 col-md-4 col-xs-6 mobile_view animated"  data-animation="fadeInUp">
				<div class="col-lg-12 col-sm-12 cil-md-12 col-xs-12 srvc_item">
					<i class="fa fa-3x fa fa-building-o"></i>
					<h3><?php echo $this->get_label('city targeting');?></h3>
					<p><?php echo $this->get_label('city targeting desc');?>
					</p>

				</div>
			</div>
			<?php }?>
			
		</div>

	</div>

</section>





<section id="section-about-us-4" class="side-bg no-padding">
                <div class="image-container col-md-5 pull-left animated" data-animation="fadeInLeft" data-delay="0"></div>

                <div class="container">
                    
                        
                       <!-- <div class="image-container-mobile col-xl-12">
                        
                          <img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/background/advertiser1.png" class="img-responsive" alt="">
                        
                        </div>-->
                        
                        
                            <div class="col-md-6 col-md-offset-6 content_section animated" data-animation="fadeInRight" data-delay="200">
                                

                                

					<div class="row">

						<div class="col-md-12 text-center">
							<h1 class="animated fadeInUp main_header" data-animation="fadeInUp"
								style="opacity: 0; padding-top:50px;">
								<?php echo $this->get_label('advertiser');?> <span class="id-color"><?php echo $this->get_label('features');?></span>
								<span class="small-border animated fadeInUp"
									data-animation="fadeInUp" style="opacity: 0;"></span>
							</h1>
							
							<div class="spacer-single"></div>
						</div>
					</div>

					<div class="row">
						<!-- feature box begin -->
							<!-- feature box begin -->
						<div class="feature-box-small-icon col-lg-6 col-sm-6 col-md-6 col-xs-6 animated"
							data-animation="fadeInUp" data-delay="100" style="opacity: 0;">
							<div class="inner">
								<i class="fa fa-sign-in" aria-hidden="true"></i>
								<div class="text">
									<h3><?php echo $this->get_label('adv feature4');?></h3>
								<?php echo $this->get_label('adv feature4 desc');?>
								</div>
							</div>
						</div>

						<!-- feature box begin -->
						<div class="feature-box-small-icon col-lg-6 col-sm-6 col-md-6 col-xs-6 animated"
							data-animation="fadeInUp" data-delay="100" style="opacity: 0;">
							<div class="inner">
								<i class="fa fa-location-arrow" aria-hidden="true"></i>
								<div class="text">
									<h3><?php echo $this->get_label('adv feature2');?></h3>
								<?php echo $this->get_label('adv feature2 desc');?>

								</div>
							</div>
						</div>
						
					</div>
						<!-- feature box close -->

						<!-- feature box begin -->
                        <div class="row">
						<div class="feature-box-small-icon col-lg-6 col-sm-6 col-md-6 col-xs-6 animated"
							data-animation="fadeInUp" data-delay="100" style="opacity: 0;">
							<div class="inner">
								<i class="fa fa-file-text" aria-hidden="true"></i>
								<div class="text">
									<h3><?php echo $this->get_label('adv feature3');?></h3>
								<?php echo $this->get_label('adv feature3 desc');?>

								</div>
							</div>
						</div>
						<!-- feature box close -->

								<div class="feature-box-small-icon col-lg-6 col-sm-6 col-md-6 col-xs-6 animated"
							data-animation="fadeInUp" data-delay="100" style="opacity: 0;">
							<div class="inner">
								<i class="fa fa-bullseye" aria-hidden="true"></i>
								<div class="text">
									<h3><?php echo $this->get_label('adv feature1');?></h3>
								<?php echo $this->get_label('adv feature1 desc');?>

								</div>
							</div>
						</div>
						<!-- feature box close -->

					
					
                        </div>
						<!-- feature box close -->


						<!-- feature box begin -->
                        <div class="row">
						<div class="feature-box-small-icon col-lg-6 col-sm-6 col-md-6 col-xs-6 animated"
							data-animation="fadeInUp" data-delay="100" style="opacity: 0;">
							<div class="inner">
								<i class="fa fa-user" aria-hidden="true"></i>
								<div class="text">
									<h3><?php echo $this->get_label('adv feature5');?></h3>
								<?php echo $this->get_label('adv feature5 desc');?>
								</div>
							</div>
						</div>
						<!-- feature box close -->

						<!-- feature box begin -->
						<div class="feature-box-small-icon col-lg-6 col-sm-6 col-md-6 col-xs-6 animated"
							data-animation="fadeInUp" data-delay="100" style="opacity: 0;">
							<div class="inner">
								<i class="fa fa-money" aria-hidden="true"></i>
								<div class="text">
									<h3><?php echo $this->get_label('adv feature6');?></h3>
								<?php echo $this->get_label('adv feature6 desc');?>
								</div>
							
						<!-- feature box close -->

						<div class="spacer-single"></div>


					</div>
				



							  
                            
                            
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </section>

<?php $this->dispatch("layout/footer");?>