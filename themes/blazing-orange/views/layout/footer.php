<?php
$logedin=intval($this->get_variable('logedin'));
$admarket_name=Configuration::get_instance()->read('admarket_name');
$page=$this->get_variable("page");
if($logedin!=1){
$googleplus_url=Configuration::get_instance()->read('googleplus_url');
$twitter_url=Configuration::get_instance()->read('twitter_url');
$facebook_url=Configuration::get_instance()->read('facebook_url');
$linkedin_url=Configuration::get_instance()->read('linkedin_url');
$youtube_url=Configuration::get_instance()->read('youtube_url');
}

?>
<!-- footer begin -->


</div>
            <footer <?php   if($logedin==1) { ?>class="footer_section" <?php } else { ?> class="footer" <?php }?>>
                <div class="container">
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                    <div class="row">
                        <div class="col-md-12 text-center">
                      
                            <div class="social-icons" <?php   if($logedin==1) { ?> style="display:none;" <?php  }  ?> >
                            
                            <?php if($googleplus_url !=""){?>
                            	<a href="//plus.google.com/<?php echo $googleplus_url;?>/"><i class="fa fa-google-plus fa-lg"></i></a>
                            <?php } else { ?> 
                                <a href="#"><i class="fa fa-google-plus fa-lg"></i></a>
                            <?php } ?>
                            
                            <?php if($twitter_url !=""){?>
                            	<a href="//twitter.com/<?php echo $twitter_url;?>"><i class="fa fa-twitter fa-lg"></i></a>
                            <?php } else { ?> 
                                <a href="#"><i class="fa fa-twitter fa-lg"></i></a>
                            <?php } ?>
                            
                            <?php if($facebook_url !=""){?>
                            	<a href="//facebook.com/<?php echo $facebook_url;?>"><i class="fa fa-facebook fa-lg"></i></a>
                            <?php } else { ?> 
                                <a href="#"><i class="fa fa-facebook fa-lg"></i></a>
                            <?php } ?>
                            
                            
                            <?php if($linkedin_url !=""){?>
                            	<a href="//linkedin.com/<?php echo $linkedin_url;?>"><i class="fa fa-linkedin fa-lg"></i></a>
                            <?php } else { ?> 
                                <a href="#"><i class="fa fa-linkedin fa-lg"></i></a>
                            <?php } ?>
                            
                            <?php if($youtube_url !=""){?>
                            	<a href="//youtube.com/<?php echo $youtube_url;?>"><i class="fa fa-youtube fa-lg"></i></a>
                            <?php } else { ?> 
                                <a href="#"><i class="fa fa-youtube fa-lg"></i></a>
                            <?php } ?>
                            
                                
                            </div>
                          
                            <div class="clearfix"></div>
                            
                             <div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
					<div class="footer-main-links">
						<ul>
						
						
      <?php 
      if($logedin ==1)
	  {
		  if($this->read_cookie_param(COOKIE_ADMARKETTYPE)==1)
		  {?>
      		<li class="foot_links"><a href="<?php echo $this->make_url("user/advertiser_home");?>"><?php echo $this->get_label('advertiser');?></a></li>
      		<?php }else if($this->read_cookie_param(COOKIE_ADMARKETTYPE)==2){?>
     	    <li class="foot_links"><a href="<?php echo $this->make_url("user/publisher_home");?>"><?php echo $this->get_label('publisher');?></a></li>
          <?php }else if($this->read_cookie_param(COOKIE_ADMARKETTYPE)==3){?>
			<li class="foot_links"><a href="<?php echo $this->make_url("user/advertiser_home");?>"><?php echo $this->get_label('advertiser');?></a></li>
			<li class="foot_links"><a href="<?php echo $this->make_url("user/publisher_home");?>"><?php echo $this->get_label('publisher');?></a></li>
      <?php }}else{
      	
      	$advseo=$this->get_seo_name('index/advertiser');
      	
      	if($advseo !='')
      	$advurl=BASE.$advseo;
      	else
      	$advurl=$this->make_base_url("index/advertiser");
      	
      	
      	$pubseo=$this->get_seo_name('index/publisher');
      	
      	if($pubseo !='')
     	$puburl=BASE.$pubseo;
      	else
     	$puburl=$this->make_base_url("index/publisher");
      	
      	?>
     	<li class="foot_links"><a href="<?php echo $advurl;?>"><?php echo $this->get_label('advertiser');?></a></li>
		<li class="foot_links"><a href="<?php echo $puburl;?>"><?php echo $this->get_label('publisher');?></a></li>
      <?php }?>
						
						
						<li class="foot_links">
						<?php if($logedin==1) { ?>
							<a href="<?php echo $this->make_url("user/support");?>"><?php echo $this->get_label('support');?></a>
						<?php } 
						else {?>
							<a href="<?php echo $this->make_url("index/contact_us");?>"><?php echo $this->get_label('contact us');?></a>
						<?php }?>
						</li>
						<li class="foot_links"><a href="<?php echo $this->make_url("index/about");?>"><?php echo $this->get_label('about us');?></a></li>
						<li class="foot_links"><a href="<?php echo $this->make_url("index/terms");?>"><?php echo $this->get_label('terms');?></a></li>
						</ul>
					</div>
					</div>
					<?php 
				
				$newsletter_addon_folder=$this->xyz_get_addon_folder_name("XYZADMNLR");
				if($this->get_addon_status($newsletter_addon_folder.'_enabled')==1 && Configuration::get_instance()->read('enable_optinform_for_site_footer')==1)
								{
									
									$this->dispatch("list/optin_blazing_orange/",ADDON_DIR_PATH.$newsletter_addon_folder.'/admin/');
								}?>
<div class="col-md-12 col-sm-12 col-lg-12 col-xs-12 text-center">
					
				

                   <?php
				   $powered_by_label=Configuration::get_instance()->read('powered_by_label');
				   $powered_by_link=Configuration::get_instance()->read('powered_by_link');
				   
				   
                   if($powered_by_label !="")
                   echo $this->get_label('copyright',array('x'=>date("Y"),'y'=>$powered_by_link,'z'=>$powered_by_label));
                   else
                   echo $this->get_label('copyright without powered by',array('x'=>date("Y")));
                   ?>                           
                  </div>
                  </div>
              </div>
            </footer>
            <!-- footer close -->
       


<?php if(DEMO_MODE){

	$base_array=explode('/',BASE);
	?>    
<div class="demo-link">  
<div><a href="<?php echo BASE; ?>admin/" target="_blank">Admin Demo</a></div>
<?php if($base_array[count($base_array)-2] != 'xyz-admarket-addons'){?>
<div><a href="http://demo.xyzscripts.com/xyz-admarket-addons/admin/" target="_blank">Addons / Themes Demo</a></div>
<?php }?>
<div><a href="<?php echo BASE; ?>index.php?page=demo/demo" target="_blank">Ad Display Demo</a></div>
</div>    
<?php }?>  


</body>
</html>