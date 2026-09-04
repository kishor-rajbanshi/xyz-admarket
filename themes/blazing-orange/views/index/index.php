<?php
$this->dispatch("layout/header/16");
$admarket_name=Configuration::get_instance()->read('admarket_name');


$login=0;

if(LoginHelper::validate_user_login())
$login=1;


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



?>


<!-- revolution slider begin -->
        <div id="section-slider" class="fullwidthbanner-container">
            <div id="revolution-slider">
                <ul>
                    <li data-transition="fade" data-slotamount="10" data-masterspeed="200" data-thumb="<?php echo THEME_DIR_PATH.$active_theme;?>/images/thumbs/thumb1.jpg">
                        <!--  BACKGROUND IMAGE -->
                        <img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/wide1.jpg" alt="" />
                        <div class="tp-caption big-white sft"
                            data-x="center"
                            data-y="165"
                            data-speed="800"
                            data-start="400"
                            data-easing="easeInOutExpo"
                            data-endspeed="450">
                            <!--WE CREATE--></span>
                        </div>

                        <div class="tp-caption ultra-big-white customin customout start"
                            data-x="center"
                            data-y="center"
                            data-customin="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:2;scaleY:2;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;"
                            data-customout="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0.85;scaleY:0.85;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;"
                            data-speed="800"
                            data-start="400"
                            data-easing="easeInOutExpo"
                            data-endspeed="400">
                            
                            <?php if(DEMO_MODE){ echo $admarket_name; } else { echo $admarket_name;?> - <?php echo $this->get_label('ad market'); } ?></span>
                        </div>

                         <div class="tp-caption sfb"
                            data-x="center"
                            data-y="325"
                            data-speed="400"
                            data-start="800"
                            data-easing="easeInOutExpo">
                            <p href="#" class="btn-slider" style="font-size: 17px;"><?php echo $this->get_label('home banner desc');?>
                            </p>
                        </div>
                    </li>

                    <li data-transition="fade" data-slotamount="10" data-masterspeed="200" data-thumb="<?php echo THEME_DIR_PATH.$active_theme;?>/images/thumbs/thumb1.jpg">
                        <!--  BACKGROUND IMAGE -->
                        <img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/wide2.jpg" alt="" />
                        <div class="tp-caption big-white sft"
                            data-x="center"
                            data-y="165"
                            data-speed="800"
                            data-start="400"
                            data-easing="easeInOutExpo"
                            data-endspeed="450">
                            <!--specialize on--></span>
                        </div>

                        <div class="tp-caption ultra-big-white customin customout start"
                            data-x="center"
                            data-y="center"
                            data-customin="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:2;scaleY:2;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;"
                            data-customout="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0.85;scaleY:0.85;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;"
                            data-speed="800"
                            data-start="400"
                            data-easing="easeInOutExpo"
                            data-endspeed="400">
                            <?php echo $this->get_label('advertiser');?></span>
                        </div>
                              <div class="tp-caption sfb"
                            data-x="center"
                            data-y="300"
                            data-speed="400"
                            data-start="800"
                            data-easing="easeInOutExpo">
                            
    
    
                            <p class="btn-slider"  style="font-size: 14px;"><?php echo $this->get_label('advertiser banner desc');?>
                            </p>
                           </div>
                       
                        <!-- 
                        <div class="tp-caption sfb"
                            data-x="center"
                            data-y="400"
                            data-speed="400"
                            data-start="800"
                            data-easing="easeInOutExpo">
                            <a href="<?php echo $advurl;?>" class="btn-slider">
                            <?php echo $this->get_label('read more');?>
                            </a>
                        </div>
                        -->
                        
                        
                        
                    </li>


<li data-transition="fade" data-slotamount="10" data-masterspeed="200" data-thumb="<?php echo THEME_DIR_PATH.$active_theme;?>/images/thumbs/thumb1.jpg">
                        <!--  BACKGROUND IMAGE -->
                        <img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/wide3.jpg" alt="" />
                        <div class="tp-caption big-white sft"
                            data-x="center"
                            data-y="165"
                            data-speed="800"
                            data-start="400"
                            data-easing="easeInOutExpo"
                            data-endspeed="450">
                            <!--specialize on--></span>
                        </div>

                        <div class="tp-caption ultra-big-white customin customout start"
                            data-x="center"
                            data-y="center"
                            data-customin="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:2;scaleY:2;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;"
                            data-customout="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0.85;scaleY:0.85;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;"
                            data-speed="800"
                            data-start="400"
                            data-easing="easeInOutExpo"
                            data-endspeed="400">
                            <?php echo $this->get_label('publisher');?></span>
                        </div>
                        
                          <div class="tp-caption sfb"
                            data-x="center"
                            data-y="300"
                            data-speed="400"
                            data-start="800"
                            data-easing="easeInOutExpo">
                            <p  class="btn-slider" style="font-size: 14px;"><?php echo $this->get_label('publsher banner desc');?>
                            </p>
                        </div>


						<!--
                        <div class="tp-caption sfb"
                            data-x="center"
                            data-y="400"
                            data-speed="400"
                            data-start="800"
                            data-easing="easeInOutExpo">
                            <a href="<?php echo $puburl;?>" class="btn-slider"><?php echo $this->get_label('read more');?>
                            </a>
                        </div>
                        --> 
                        
                        
                    </li>


                </ul>
            </div>
        </div>
        <!-- revolution slider close -->


        <!-- content begin -->
        <div id="content1" class="no-bottom no-top content-overlay">

            <!-- section begin -->
            <section class="sect_top" id="section-about" style="padding-bottom:0px !important;">
                <div class="container">
                    <div class="row">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <h1 class="animated main_header" data-animation="fadeInUp"><?php echo $this->get_label('welcome');?><span class="id-color"> <?php echo $admarket_name;?></span>
                                    <span class="small-border animated" data-animation="fadeInUp"></span>
                                </h1>
                                <p class="lead animated" data-animation="fadeInUp">
                                    <?php echo $this->get_label('admarket introduction',array('x'=>$admarket_name));?>
                                </p>
                                
 
                                
                                <div class="spacer-single"></div>
                            </div>
                            
                              <div class="col-md-12 text-center animated" data-animation="fadeInUp" data-delay="400" style="margin-bottom:0px !important;">
                            <img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/about_img.png" class="img-responsive" alt="">
                        </div>
                            
                        </div>
                       

                    </div>
                </div>
            </section>
            <!-- section close -->

            <!-- section begin -->
            <section id="section-about-us-2" class="side-bg no-padding">
                <div class="image-container col-md-5 pull-left animated" data-animation="fadeInLeft" data-delay="0"></div>

                <div class="container">
                    <div class="row">
                        <div class="inner-padding">
                        
                       <!-- <div class="image-container-mobile col-xl-12">
                        
                          <img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/background/advertiser1.png" class="img-responsive" alt="">
                        
                        </div>-->
                        
                        
                            <div class="col-md-6 col-md-offset-6 content_section animated" data-animation="fadeInRight" data-delay="200">
                                <h2><?php echo $this->get_label('advertiser');?></h2>

                                <p class="intro" style="text-align:justify;"><?php echo $this->get_label('advertiser desc',array('x'=>$admarket_name));?></p>



							   <?php if($login ==0){
							   
							   	
							   	
                    
                    
								$registerseo=$this->get_seo_name('index/register');
								
								if($registerseo !='')
								$registerurl=BASE.$registerseo;
								else
								$registerurl=$this->make_url("index/register/1");
												   	
							   	
							   	
							   	?>
                               <a href="<?php echo $registerurl;?>" class="btn btn-border btn-big"><?php echo $this->get_label('join');?></a>
                               <a href="<?php echo $advurl;?>" class="btn btn-border btn-big"><?php echo $this->get_label('read more');?></a>
                               <?php }?>
                            
                            
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- section close -->
            <!-- section begin -->
            <section class="section_bg" data-speed="5" data-type="background">
              
                        <div class="container">
                            <div class="row">
                                <div class="col-md-6 col-md-offset-3 text-center">
                                    <h1 style="color:#ffffff;" class="animated main_header" data-animation="fadeInUp"><?php echo $admarket_name;?>
                                        <span class="small-border animated" data-animation="fadeInUp"></span>
                                    </h1>
                                    <div class="animated adv_pup_sep" data-animation="fadeInUp">
										<?php echo $this->get_label('adv pub seperation content',array('x'=>$admarket_name));?> <div class="spacer-single"></div>
                                    </div>
                                </div>
                           
                    </div>

                   


                </div>

            </section>
            <!-- section close -->






<section id="section-about-us-3" class="side-bg no-padding" style="position:relative;">
                

                <div class="container">
                    <div class="row">
                        <div class="inner-padding">
                        
                        
                       <!-- <div class="image-container-mobile col-xl-12">
                        
                          <img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/background/advertiser1.png" class="img-responsive" alt="">
                        
                        </div>-->
                        
                        
                            <div class="col-md-6 content_section animated" data-animation="fadeInRight" data-delay="200">
                                <h2><?php echo $this->get_label('publisher');?></h2>

                                <p class="intro" style="text-align:justify;"><?php echo $this->get_label('publisher desc',array('x'=>$admarket_name));?></p>


								<?php if($login ==0){
								
								$registerseo=$this->get_seo_name('index/register');
								
								if($registerseo !='')
								$registerurl=BASE.$registerseo;
								else
								$registerurl=$this->make_url("index/register/2");									
									
								?>
                                <a href="<?php echo $registerurl;?>" class="btn btn-border btn-big"><?php echo $this->get_label('join');?></a>
                                <a href="<?php echo $puburl;?>" class="btn btn-border btn-big"><?php echo $this->get_label('read more');?></a>
                                <?php }?>
                            
                            
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                
                <div class="image-container col-md-5 animated" data-animation="fadeInLeft" data-delay="0" style="right:0; top:0;"></div>
            </section>




            <!-- section begin -->
            <section id="section-services" class="no-bottom sect_top">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 col-md-offset-3 text-center">
                            <h1  class="animated" data-animation="fadeInUp"><?php echo $this->get_label('our');?> <span class="id-color"><?php echo $this->get_label('features');?></span>
                                <span class="small-border animated" data-animation="fadeInUp"></span>
                            </h1>
                        
                            <div class="spacer-single"></div>
                        </div>
                    </div>

                    <div class="row">
                    
                    	 <!-- feature box begin -->
                        <div class="feature-box-small-icon col-lg-4 col-md-4 col-sm-6 col-xs-12 animated" data-animation="fadeInUp" data-delay="800">
                            <div class="inner">
                                <i class="fa fa-sign-in" aria-hidden="true"></i>
                                <div class="text">
                                    <h3><?php echo $this->get_label('feature5');?></h3>
                                    <?php echo $this->get_label('feature5 desc');?>
                                </div>
                            </div>
                        </div>
                        <!-- feature box close -->
                        
                         <!-- feature box begin -->
                        <div class="feature-box-small-icon col-lg-4 col-md-4 col-sm-6 col-xs-12 animated" data-animation="fadeInUp" data-delay="200">
                            <div class="inner">
                                <i class="fa fa-location-arrow" aria-hidden="true"></i>
                                <div class="text">
                                    <h3><?php echo $this->get_label('feature2');?></h3>
                                    <?php echo $this->get_label('feature2 desc');?>
                                </div>
                            </div>
                        </div>
                        <!-- feature box close -->
                        
                        <!-- feature box begin -->
                        <div class="feature-box-small-icon col-lg-4 col-md-4 col-sm-6 col-xs-12 animated" data-animation="fadeInUp" data-delay="0">
                            <div class="inner">
                                <i class="fa fa-bullseye" aria-hidden="true"></i>
                                <div class="text">
                                    <h3><?php echo $this->get_label('feature1');?></h3>
							<?php echo $this->get_label('feature1 desc');?>             </div>
                            </div>
                        </div>
                        <!-- feature box close -->

                       

                        <!-- feature box begin -->
                        <div class="feature-box-small-icon col-lg-4 col-md-4 col-sm-6 col-xs-12 animated" data-animation="fadeInUp" data-delay="400">
                            <div class="inner">
                                <i class="fa fa-code" aria-hidden="true"></i>
                                <div class="text">
                                    <h3><?php echo $this->get_label('feature3');?></h3>
                                    <?php echo $this->get_label('feature3 desc');?>
                                </div>
                            </div>
                        </div>
                        <!-- feature box close -->
                        
                        
                        <!-- feature box begin -->
                        <div class="feature-box-small-icon col-lg-4 col-md-4 col-sm-6 col-xs-12 animated" data-animation="fadeInUp" data-delay="1000">
                            <div class="inner">
                                <i class="fa fa-user" aria-hidden="true"></i>
                                <div class="text">
                                    <h3><?php echo $this->get_label('feature6');?></h3>
                                    <?php echo $this->get_label('feature6 desc');?>
                                    
                                </div>
                            </div>
                        </div>
                        <!-- feature box close -->

                        <!-- feature box begin -->
                        <div class="feature-box-small-icon col-lg-4 col-md-4 col-sm-6 col-xs-12 animated" data-animation="fadeInUp" data-delay="600">
                            <div class="inner">
                                <i class="fa fa-file-text" aria-hidden="true"></i>
                                <div class="text">
                                    <h3><?php echo $this->get_label('feature4');?></h3>
                                    <?php echo $this->get_label('feature4 desc');?>
                                </div>
                            </div>
                        </div>
                        <!-- feature box close -->


                       

                        
                              <!-- feature box begin -->
                        <div class="feature-box-small-icon col-lg-4 col-md-4 col-sm-6 col-xs-12 animated" data-animation="fadeInUp" data-delay="1000">
                            <div class="inner">
                                <i class="fa fa-cogs" aria-hidden="true"></i>
                                <div class="text">
                                    <h3><?php echo $this->get_label('feature7');?></h3>
                                    <?php echo $this->get_label('feature7 desc');?>
                                    
                                </div>
                            </div>
                        </div>
                        <!-- feature box close -->
                        
                              <!-- feature box begin -->
                        <div class="feature-box-small-icon col-lg-4 col-md-4 col-sm-6 col-xs-12 animated" data-animation="fadeInUp" data-delay="1000">
                            <div class="inner">
                                <i class="fa fa-money" aria-hidden="true"></i>
                                <div class="text">
                                    <h3><?php echo $this->get_label('feature8');?></h3>
                                    <?php echo $this->get_label('feature8 desc');?>
                                    
                                </div>
                            </div>
                        </div>
                        <!-- feature box close -->
                        
                        
                              <!-- feature box begin -->
                        <div class="feature-box-small-icon col-lg-4 col-md-4 col-sm-6 col-xs-12 animated" data-animation="fadeInUp" data-delay="1000">
                            <div class="inner">
                                <i class="fa fa-phone" aria-hidden="true"></i>
                                <div class="text">
                                    <h3><?php echo $this->get_label('feature9');?></h3>
                                    <?php echo $this->get_label('feature9 desc');?>
                                    
                                </div>
                            </div>
                        </div>
                        <!-- feature box close -->

                        <div class="spacer-single"></div>

                        
                    </div>
                </div>
            </section>
            <!-- section close -->


 

 					<?php $this->dispatch("index/testimonial");?>		
					
					
					<?php $this->dispatch("index/slider");?>
       


         
				
	
				
	<!--- Footer Section -->			


<?php $this->dispatch("layout/footer");?>
