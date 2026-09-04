<?php
$logedin          = intval($this->get_variable('logedin'));
$admarket_name    = Configuration::get_instance()->read('admarket_name');
$powered_by_label = Configuration::get_instance()->read('powered_by_label');
$powered_by_link  = Configuration::get_instance()->read('powered_by_link');
$adminEmail       = Configuration::get_instance()->read('admin_notification_email');
$adminPhone       = Configuration::get_instance()->read('admin_phone_no');

$twitter_url     = Configuration::get_instance()->read('twitter_url');
$facebook_url    = Configuration::get_instance()->read('facebook_url');
$linkedin_url    = Configuration::get_instance()->read('linkedin_url');
$youtube_url     = Configuration::get_instance()->read('youtube_url');
$advertiser_dashboard_status = Configuration::get_instance()->read('advertiser_dashboard_status');
$publisher_dashboard_status  = Configuration::get_instance()->read('publisher_dashboard_status');

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


$contactseo=$this->get_seo_name('index/contact_us');

if($contactseo !='')
$contacturl=BASE.$contactseo;
else
$contacturl=$this->make_base_url("index/contact_us");

$faqseo=$this->get_seo_name('index/faq');
if($faqseo !='')
$faqurl=BASE.$faqseo;
else
$faqurl=$this->make_url("index/faq");

$cookieseo=$this->get_seo_name('index/cookie_policy');

if($cookieseo !='')
$cookieurl=BASE.$cookieseo;
else
$cookieurl=$this->make_base_url("index/cookie_policy");


$privacyseo=$this->get_seo_name('index/privacy_policy');
if($privacyseo !='')
$privacyurl=BASE.$privacyseo;
else
$privacyurl=$this->make_base_url("index/privacy_policy");



$newsletter_addon_folder = $this->xyz_get_addon_folder_name("XYZADMNLR");
if($this->get_addon_status($newsletter_addon_folder.'_enabled')==1 && Configuration::get_instance()->read('enable_optinform_for_site_footer')==1)
$newsLetterSupport = 1;
else
$newsLetterSupport = 0;

$active_theme   	  = $this->read_cookie_param('active_theme');

if($active_theme == "")
$active_theme   	  = Configuration::get_instance()->read('active_theme');

$public_page_logo 	= Configuration::get_instance()->read('public_page_logo');

if(DEMO_MODE)
$public_page_logo = THEME_DIR_PATH.$active_theme."/images/logo.png";
else if($public_page_logo != "")
$public_page_logo = BASE.DATA_DIR."/logo/".$public_page_logo;


$admarket_name    	= Configuration::get_instance()->read('admarket_name');

$page 				= intval($this->get_variable("page"));
$common_page  = intval($this->get_variable('common_page'));
$direction    = $this->get_variable('direction');

$common_page_temp = $this->get_variable("common_page_temp");

if($common_page == 0 || $common_page_temp == 1){?>

    </div>
<?php }?>








<footer id="footer" class="footer-class">
	
	
	<div class="footer-inner">
	
	
	
<?php if($common_page == 1){?>

	
    <div class="footer-top">
      <div class="container">
        <div class="row">

          <div class="col-lg-4 col-md-12 col-sm-12 col-sx-12 footer-contact" data-aos="fade-up" data-aos-delay="150">
            <h3><?php echo $admarket_name;?></h3>
            <p>
           		<?php echo $this->get_label("footer description", array('x'=>$admarket_name));?>
            </p>
          </div>

          <div class="col-lg-2 col-md-6 col-sm-6 col-sx-12 footer-links" data-aos="fade-up" data-aos-delay="250">
            <h4><?php echo $this->get_label('quick links');?></h4>
            <ul>
             <?php
						 if($logedin == 1)
						 {
							  if($this->read_cookie_param(COOKIE_ADMARKETTYPE) == 1 && $advertiser_dashboard_status == 1){?>
      						<li ><a href="<?php echo $this->make_url("user/advertiser_home");?>"><?php echo $this->get_label('advertiser');?></a></li>
      					<?php } else if($this->read_cookie_param(COOKIE_ADMARKETTYPE) == 2 && $publisher_dashboard_status == 1){?>
     	    				<li><a href="<?php echo $this->make_url("user/publisher_home");?>"><?php echo $this->get_label('publisher');?></a></li>
          			<?php } else if($this->read_cookie_param(COOKIE_ADMARKETTYPE) == 3 && $advertiser_dashboard_status == 1 && $publisher_dashboard_status == 1){?>
									<li><a href="<?php echo $this->make_url("user/advertiser_home");?>"><?php echo $this->get_label('advertiser');?></a></li>
									<li><a href="<?php echo $this->make_url("user/publisher_home");?>"><?php echo $this->get_label('publisher');?></a></li>
      					<?php }?>
					
                <?php if($this->faq_link_availability() == 1) { ?>
					<li><a href="<?php echo $this->make_url("index/faq");?>"><?php echo $this->get_label('faq');?></a></li>
               <?php } ?>
	  				<li><a href="<?php echo $this->make_url("user/support");?>"><?php echo $this->get_label('support');?></a></li>
                
							<?php } else {

				      	$advseo = $this->get_seo_name('index/advertiser');

				      	if($advseo != '')
				      	$advurl=BASE.$advseo;
				      	else
				      	$advurl=$this->make_base_url("index/advertiser");

				      	$pubseo = $this->get_seo_name('index/publisher');

				      	if($pubseo != '')
				     		$puburl=BASE.$pubseo;
				      	else
				     		$puburl=$this->make_base_url("index/publisher");
      				

			  if($advertiser_dashboard_status == 1){ ?>
     				<li><a href="<?php echo $advurl;?>"><?php echo $this->get_label('advertiser');?></a></li>
     			  <?php }
     			  if($publisher_dashboard_status == 1){ ?>
						<li><a href="<?php echo $puburl;?>"><?php echo $this->get_label('publisher');?></a></li>
			  <?php } ?>
            <?php if($this->faq_link_availability() == 1) { ?>
						<li><a href="<?php echo $faqurl;?>"><?php echo $this->get_label('faq');?></a></li>		
            <?php } ?>
        		<li><a href="<?php echo $contacturl;?>"><?php echo $this->get_label('contact us');?></a></li>
                
      				<?php }?>
            </ul>
          </div>

          <div class="col-lg-3 col-md-6 col-sm-6 col-sx-12 footer-links" data-aos="fade-up" data-aos-delay="350">
            <h4><?php echo $this->get_label('company info');?></h4>
            <ul>
               <li><a href="<?php echo $abouturl;?>"><?php echo $this->get_label('about us');?></a></li>
               <li><a href="<?php echo $termsurl;?>"><?php echo $this->get_label('terms conditions');?></a></li>
               <li><a href="<?php echo $privacyurl;?>"><?php echo $this->get_label('privacy policy');?></a></li>
               <li><a href="<?php echo $cookieurl;?>"><?php echo $this->get_label('cookie');?></a></li>
            </ul>
          </div>

          <div class="col-lg-3 col-md-12 col-sm-12 col-sx-12 footer-links" data-aos="fade-up" data-aos-delay="450">
	            <h4><?php echo $this->get_label("connect with us");?></h4>
	            <div class="social-links mt-3">
			          <?php if($twitter_url != ""){?>
			        	<a target="_blank" href="//twitter.com/<?php echo $twitter_url;?>" class="twitter"><i class="bx bxl-twitter"></i></a>
			          <?php } ?>

								<?php if($facebook_url != ""){?>
			          	<a target="_blank" class="facebook" href="//facebook.com/<?php echo $facebook_url;?>"><i class="bx bxl-facebook"></i></a>
			          <?php } ?>

			          <?php if($linkedin_url != ""){?>
			  			    <a target="_blank" href="//linkedin.com/<?php echo $linkedin_url;?>" class="linkedin" ><i class="bx bxl-linkedin"></i></a>
			          <?php } ?>

			          <?php if($youtube_url != ""){?>
			  			    <a target="_blank" href="//youtube.com/<?php echo $youtube_url;?>" lass="youtube"><i class="bx bxl-youtube"></i></a>
			          <?php } ?>
	            </div>

							<?php if($adminEmail != ""){?>
								<div class="footer-email"><p><i class="fa fa-envelope" aria-hidden="true"></i>
 <?php echo $adminEmail;?></p></div>
							<?php } ?>

							<?php if($adminPhone != ""){?>
								<div class="footer-phone"><p><i class="fa fa-phone" aria-hidden="true"></i>
 <?php echo $adminPhone;?></p></div>
							<?php } ?>
          </div>

        </div>
      </div>
    </div>
		
		
		<?php if($newsLetterSupport == 1){ ?>
	    <div class="footer-newsletter">
	      <div class="container" data-aos="fade-up">
	        <div class="row">
	          <div class="col-lg-12 justify-content-center">
	            <?php
	              $this->dispatch("list/optin_cherry_red/",ADDON_DIR_PATH.$newsletter_addon_folder.'/admin/');
	            ?>
	          </div>
	        </div>
	      </div>
	    </div>
		<?php } ?>
		
<?php } ?>
		
		
		
		
		
<div class="footer-bottom clearfix">
	
	
	
	
<div class="container">
	
	
	
	
	
	
	
	
	
  <div class="copyright">
	  <?php
	  if($powered_by_label != "")
	  echo $this->get_label('copyright',array('x'=>date("Y"),'y'=>$powered_by_link,'z'=>$powered_by_label));
	  else
	  echo $this->get_label('copyright without powered by',array('x'=>date("Y")));
		?>
  </div>
</div>
</div>
<a href="#" class="back-to-top d-flex align-items-center justify-content-center">
  <i class="ri-arrow-drop-up-line"></i>
</a>
		
		
		
	</div>	
		
</footer>

<?php
if(DEMO_MODE)
{
include(PATH_TO_ROOT.'/demo/demo.php');
  $cookie_button = 'cookie-button';
}
else
  $cookie_button = '';
?>

<div class="cookie-class">
    <span>
        <?php echo $this->get_label('cookie policy label');?>
        <a href="<?php echo $cookieurl;?>"><?php echo $this->get_label('cookie policy');?></a>&emsp;&emsp;<button id="iunderstand" class="btn cookie-policy-button <?php echo $cookie_button ?>"><?php echo $this->get_label('i understand');?></button>
    </span>
</div>

<script type="text/javascript" src="<?php echo THEME_DIR_PATH.$active_theme;?>/js/main.js"></script>

<script type="text/javascript">
$(document).ready(function()
{
    var cpaccepted = Get_Cookie('<?php echo COOKIE_POLICY;?>');

  	if(cpaccepted != 1)
  	$('.cookie-class').show();

	  $('#iunderstand').click(function(){

        Set_Cookie('<?php echo COOKIE_POLICY;?>',1,'','/');

    		$('.cookie-class').hide();
    });
});
</script>
</body>
</html>
