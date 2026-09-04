<?php 
$this->dispatch("layout/header/18"); 
$admarket_name=Configuration::get_instance()->read('admarket_name');


$login=0;

if(LoginHelper::validate_user_login())
$login=1;
?>

<!-- subheader -->
<section id="subheader" class="inner_head common-head publisher_head" data-speed="8" data-type="background">
<div class="container">
<div class="row">
<div class="col-md-12">
<h1><?php echo $this->get_label('publisher');?></h1>
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


<div class="col-md-12 text-center" >
<h2><span><?php echo $this->get_label('publisher heading');?></span> <?php echo $admarket_name;?></h2>

<p class="intro">
<?php echo $this->get_label('publisher home desc',array('x'=>$admarket_name));?></p>

<?php if($login ==0){?>
<a href="<?php echo $this->make_url("index/register/2"); ?>"><button class="inner_register"><?php echo $this->get_label('get started now');?></button></a>
<?php }?>

</div>
</div>
</div>


</section>



<section class="section_padding" style="background-color:#eeeeee;">
<div class="container">
<div class="col-md-4 col-lg-4 col-sm-4 col-xs-4 mobile_view adv_steps animated" data-animation="fadeInUp" data-delay="0">
<div class="inner_icon"><i class="fa fa-sign-in" aria-hidden="true"></i></div>
<h3>1.<?php echo $this->get_label('sign up');?></h3>
<p><?php echo $this->get_label('sign up for a free publisher account');?> </p>
</div>

<div class="col-md-4 col-lg-4 col-sm-4 col-xs-4 mobile_view adv_steps animated" data-animation="fadeInUp" data-delay="0">
<div class="inner_icon"><i class="fa fa-code" aria-hidden="true"></i></div>
<h3>2.<?php echo $this->get_label('insert ad code');?></h3>
<p><?php echo $this->get_label('insert  ad code on your website to display ad');?> </p>
</div>

<div class="col-md-4 col-lg-4 col-sm-4 col-xs-4 mobile_view adv_steps animated" data-animation="fadeInUp" data-delay="0">
<div class="inner_icon"><i class="fa fa-money" aria-hidden="true"></i></div>
<h3>3.<?php echo $this->get_label('earn money');?></h3>
<p><?php echo $this->get_label('our system automatically optimizes your revenue');?> </p>
</div>
</div>

</section>


<section id="section-advertiser-content" class="side-bg no-padding advertiser_content_bg" style="padding-top:60px; position:relative;">
<div class="container">
<div class="row"><div class="col-md-12">
<!-- Nav tabs -->
<?php 
$text_image_enabled      = $this->get_addon_status('text-image-ads_enabled');
$interstitial_enabled    = $this->get_addon_status('interstitial_enabled');
$pop_ads_enabled 		 = $this->get_addon_status('pop-ads_enabled');

?>
<div class="card">
<ul class="nav nav-tabs inner_menu" role="tablist">
<li role="presentation" class="active"><a href="#Text-Ads" aria-controls="Text-Ads" role="tab" data-toggle="tab"><?php echo $this->get_label('text ads codes');?></a></li>
<li role="presentation"><a href="#Banner-Ads" aria-controls="Banner-Ads" role="tab" data-toggle="tab"><?php echo $this->get_label('banner ads codes');?></a></li>
<?php 
if($text_image_enabled ==1){ ?>
<li role="presentation"><a href="#Text-Image-Ads" aria-controls="Text-Image-Ads" role="tab" data-toggle="tab"><?php echo $this->get_label('text + image ads codes');?></a></li>
<?php } 
if($interstitial_enabled ==1){ ?>
<li role="presentation"><a href="#Interstitial-Ads" aria-controls="Interstitial-Ads" role="tab" data-toggle="tab"><?php echo $this->get_label('interstitial ads codes');?></a></li>
<?php } 
if($pop_ads_enabled ==1){ ?>
<li role="presentation"><a href="#Pop-Ads" aria-controls="Pop-Ads" role="tab" data-toggle="tab"><?php echo $this->get_label('pop ads codes');?></a></li>
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
<h4><?php echo $this->get_label('text ads codes head');?></h4>

<p style="text-align:justify;"><?php echo $this->get_label('text ads codes content p1');?></p>
<p style="text-align:justify;"><?php echo $this->get_label('text ads codes content p2');?></p>
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
                                        <h4><?php echo $this->get_label('banner ads codes head');?></h4>

                                        <p style="text-align:justify;">
										<?php echo $this->get_label('banner ads codes content p1');?>
                                        </p>
										<p style="text-align:justify;">
										<?php echo $this->get_label('banner ads codes content p2');?>
                                        </p>

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
                                        <h4><?php echo $this->get_label('text + image ads codes head');?></h4>

                                        <p style="text-align:justify;">
											<?php echo $this->get_label('text + image ads codes content p1');?>                                        
										</p>
									    <p style="text-align:justify;">
											<?php echo $this->get_label('text + image ads codes content p2');?>                                        
										</p>


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
                                        <h4><?php echo $this->get_label('pop ads codes head');?></h4>

                                        <p style="text-align:justify;">
											<?php echo $this->get_label('pop ads codes content p1');?>                                        
										</p>
									    <p style="text-align:justify;">
											<?php echo $this->get_label('pop ads codes content p2');?>                                        
										</p>


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
                                        <h4><?php echo $this->get_label('interstitial ads codes head');?></h4>

                                        <p style="text-align:justify;"><?php echo $this->get_label('interstitial ads codes content p1');?>
                                        </p>

										<p style="text-align:justify;"><?php echo $this->get_label('interstitial ads codes content p2');?>
                                        </p>

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
      
 <section id="section-services">
                <div class="container">
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2 text-center">
                            <h1  class="animated" data-animation="fadeInUp"><?php echo $this->get_label('publisher');?> <span class="id-color"><?php echo $this->get_label('features');?></span>
                                <span class="small-border animated" data-animation="fadeInUp"></span>
                            </h1>
                                               
           <div class="spacer-single"></div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- feature box begin -->
                        <div class="feature-box-small-icon col-md-4 animated" data-animation="fadeInUp" data-delay="0">
                            <div class="inner">
                                <i class="fa fa-sign-in" aria-hidden="true"></i>
                                <div class="text">
                                    <h3><?php echo $this->get_label('pub feature1');?> </h3>
                                    <?php echo $this->get_label('pub feature1 desc');?> 
                                </div>
                            </div>
                        </div>
                        <!-- feature box close -->

                        <!-- feature box begin -->
                        <div class="feature-box-small-icon col-md-4 animated" data-animation="fadeInUp" data-delay="400">
                            <div class="inner">
                                <i class="fa fa-code" aria-hidden="true"></i>
                                <div class="text">
                                    <h3><?php echo $this->get_label('pub feature2');?> </h3>
                                <?php echo $this->get_label('pub feature2 desc');?>
                                </div>
                            </div>
                        </div>
                        <!-- feature box close -->

                        <!-- feature box begin -->
                        <div class="feature-box-small-icon col-md-4 animated" data-animation="fadeInUp" data-delay="600">
                            <div class="inner">
                               <i class="fa fa-location-arrow" aria-hidden="true"></i>
                                <div class="text">
                                    <h3><?php echo $this->get_label('pub feature3');?> </h3>
								<?php echo $this->get_label('pub feature3 desc');?>                                 
                               </div>
                            </div>
                        </div>
                        <!-- feature box close -->


                        <!-- feature box begin -->
                        <div class="feature-box-small-icon col-md-4 animated" data-animation="fadeInUp" data-delay="800">
                            <div class="inner">
                                <i class="fa fa-filter" aria-hidden="true"></i>
                                <div class="text">
                                    <h3><?php echo $this->get_label('pub feature4');?> </h3>
									<?php echo $this->get_label('pub feature4 desc');?>     
							   </div>
                            </div>
                        </div>
                        <!-- feature box close -->

                        <!-- feature box begin -->
                        <div class="feature-box-small-icon col-md-4 animated" data-animation="fadeInUp" data-delay="1000">
                            <div class="inner">
                                <i class="fa fa-file-text" aria-hidden="true"></i>
                                <div class="text">
                                    <h3><?php echo $this->get_label('pub feature5');?> </h3>
                                    <?php echo $this->get_label('pub feature5 desc');?> 
                                </div>
                            </div>
                        </div>
                        <!-- feature box close -->
                        
                         <!-- feature box begin -->
                        <div class="feature-box-small-icon col-md-4 animated" data-animation="fadeInUp" data-delay="1000">
                            <div class="inner">
                                <i class="fa fa-money" aria-hidden="true"></i>
                                <div class="text">
                                    <h3><?php echo $this->get_label('pub feature6');?> </h3>
                                    <?php echo $this->get_label('pub feature6 desc');?> 
                                </div>
                            </div>
                        </div>
                        <!-- feature box close -->

                        <div class="spacer-single"></div>

                        
                    </div>
                </div>
            </section>     
       
      
  
<?php $this->dispatch("layout/footer");?>