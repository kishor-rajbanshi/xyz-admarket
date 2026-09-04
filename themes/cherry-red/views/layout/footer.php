<?php 
$googleplus_url=Configuration::get_instance()->read('googleplus_url');
$twitter_url=Configuration::get_instance()->read('twitter_url');
$facebook_url=Configuration::get_instance()->read('facebook_url');
$linkedin_url=Configuration::get_instance()->read('linkedin_url');
$youtube_url=Configuration::get_instance()->read('youtube_url');
?>


</div>
<section id="bottom" class="footer-section">
        <div class="container">
            <div class="row">
            
               <?php $newsletter_addon_folder=$this->xyz_get_addon_folder_name("XYZADMNLR");
				if($this->get_addon_status($newsletter_addon_folder.'_enabled')==1 && Configuration::get_instance()->read('enable_optinform_for_site_footer')==1)
				{
					$class="class='col-md-2 col-sm-6'";
				}
				else
				{
					$class="class='col-md-3 col-sm-6'";
				}
				?>
                <div <?php echo $class;?> >
                    <div class="widget">
        	
        	
        	<?php 
        	$aboutseo=$this->get_seo_name('index/about');
        	
        	if($aboutseo !='')
        	$abouturl=BASE.$aboutseo;
        	else
        	$abouturl=$this->make_base_url("index/about");
        	
        	
        	$termsseo=$this->get_seo_name('index/terms');
        	
        	if($termsseo !='')
        	$termsurl=BASE.$termsseo;
        	else
        	$termsurl=$this->make_base_url("index/terms");
        	
        	
        	?>
        	
        	
            		
      <h3><?php echo $this->get_label('company info');?></h3>
                        <ul>
                            <li> <a href="<?php echo $abouturl; ?>"><?php echo $this->get_label('about us');?></a></li>
                            <li><a href="<?php echo $termsurl;?>"><?php echo $this->get_label('terms conditions');?></a></li>
                            
                            
      
      
      
      
      <?php if(LoginHelper::validate_user_login())
			{?>
      <li><a href="<?php echo $this->make_base_url("user/support"); ?>"><?php echo $this->get_label('support'); ?></a></li>
      <?php }else {
      
      	
      	
      	$contactseo=$this->get_seo_name('index/contact_us');
      	
      	if($contactseo !='')
      	$contacturl=BASE.$contactseo;
      	else
      	$contacturl=$this->make_base_url("index/contact_us");
      	
      	
      	
      	
      	
      	
      	
      	?>
         <li> <a href="<?php echo $contacturl; ?>"><?php echo $this->get_label('contact us'); ?></a>  </li>		
            	<?php }?>	
            		
            		
			 </ul>
                    </div>    
                </div>
                <div  <?php echo $class;?>>
                    <div class="widget">
                        <h3><?php echo $this->get_label("quick links");?></h3>
                        <ul>
                        <li>   <a href="<?php echo BASE; ?>"><?php echo $this->get_label("home");?></a></li>
      
      <?php 
      if(LoginHelper::validate_user_login())
			{
				if($this->read_cookie_param(COOKIE_ADMARKETTYPE)==1)
				{
      ?>
      <li>   <a href="<?php echo $this->make_url("user/advertiser_home");?>"><?php echo $this->get_label('advertiser');?></a></li>
      
      <?php }else if($this->read_cookie_param(COOKIE_ADMARKETTYPE)==2)
				{?>
     <li> <a href="<?php echo $this->make_url("user/publisher_home");?>"><?php echo $this->get_label('publisher');?></a></li>
      <?php }else if($this->read_cookie_param(COOKIE_ADMARKETTYPE)==3)
				{?>
				   <li>   <a href="<?php echo $this->make_url("user/advertiser_home");?>"><?php echo $this->get_label('advertiser');?></a></li>
				    <li>  <a href="<?php echo $this->make_url("user/publisher_home");?>"><?php echo $this->get_label('publisher');?></a></li>
				
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
     				  <li>  <a href="<?php echo $advurl;?>"><?php echo $this->get_label('advertiser');?></a></li>
				    <li>  <a href="<?php echo $puburl;?>"><?php echo $this->get_label('publisher');?></a></li>
      <?php }?>
                   
                   
                   
            	</ul>
                    </div>    
                </div>
    
       <div  <?php echo $class;?> >
                    <div class="widget">
                        <h3><?php echo $this->get_label("payment method");?></h3>
                        <ul>
                            <li><a href="#"><img class="img-responsive" src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/paypal.png" alt="Payments"/></a></li>
                            
                            
                        </ul>
                    </div>    
                </div>
                
                
                
              <div class="col-md-3 col-sm-6">
                    <div class="widget">
                       <h3><?php echo $this->get_label("connect with us");?></h3>
                        <ul>
                        
                        
<?php if($googleplus_url !="" || $twitter_url !="" || $facebook_url !="" || $linkedin_url !="" || $youtube_url !=""){?>                   
                        
                            <li><div class="social"><img border="0" usemap="#Map" src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/ss-icons.png">
<map id="Map" name="Map">
<?php if($googleplus_url !=""){?>
<area coords="13,34,7,26,4,11,10,5,17,5,29,12,31,25" shape="poly" target="_blank" href="https://plus.google.com/<?php echo $googleplus_url;?>/" class="xyz_gplus">
<?php }?> 
  
<?php if($twitter_url !=""){?>
<area coords="44,1,82,33" shape="rect" target="_blank" href="http://twitter.com/<?php echo $twitter_url;?>" >
<?php }?>

<?php if($facebook_url !=""){?>
<area coords="92,-1,126,30" shape="rect" target="_blank" href="http://facebook.com/<?php echo $facebook_url;?>">
<?php }?>

<?php if($linkedin_url !=""){?>
<area coords="141,-5,173,32" shape="rect" target="_blank" href="http://linkedin.com/<?php echo $linkedin_url;?>">
<?php }?>

<?php if($youtube_url !=""){?>
<area coords="187,-2,226,32" shape="rect" target="_blank" href="http://youtube.com/<?php echo $youtube_url;?>">
<?php }?>
</map></div></li>

<?php }?> 



<li><?php echo $this->get_label('email address');?> : <?php echo Configuration::get_instance()->read('admin_notification_email');?>
</li>
     
     </ul>
      </div>
      </div>
     <?php 
				
				$newsletter_addon_folder=$this->xyz_get_addon_folder_name("XYZADMNLR");
			if($this->get_addon_status($newsletter_addon_folder.'_enabled')==1 && Configuration::get_instance()->read('enable_optinform_for_site_footer')==1)
				
				{

				$this->dispatch("list/optin_cherry_red/",ADDON_DIR_PATH.$newsletter_addon_folder.'/admin/');
				//$this->dispatch("list/58/",ADDON_DIR_PATH.$newsletter_addon_folder);
					?>
			
<?php }?>
                 
          
               <!--/.col-md-3-->
            </div>
        </div>
    </section>
    
    
     <footer id="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12" style="text-align: center;">
                
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
        
        
        
        <a href="#" class="go-to-top"></a>
        
        
        
    </footer>
    
    
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
    
    
    
    
    
    
<script type="text/javascript">            
			jQuery(document).ready(function() {
				var offset = 220;
				var duration = 500;
				jQuery(window).scroll(function() {
					if (jQuery(this).scrollTop() > offset) {
						jQuery('.go-to-top').fadeIn(duration);
					} else {
						jQuery('.go-to-top').fadeOut(duration);
					}
				});
				
				jQuery('.go-to-top').click(function(event) {
					event.preventDefault();
					jQuery('html, body').animate({scrollTop: 0}, duration);
					return false;
				})
			});
</script>
</body>
</html>