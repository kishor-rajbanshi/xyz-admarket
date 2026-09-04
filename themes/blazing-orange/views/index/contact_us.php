<?php
$active_theme=$this->read_cookie_param('active_theme');
if($active_theme=="")
	$active_theme=Configuration::get_instance()->read('active_theme');
$external_theme_exists=Configuration::get_instance()->read('exsternal_theme_exists');
if($external_theme_exists!=1)
	$this->dispatch("layout/header/19");
else
	{
	?>
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/jquery.min.js'></script>
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/jquery-ui-1.8.23.custom.min.js'></script>
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/bootstrap.min.js'></script>

<link rel="icon" href="<?php echo BASE;?>favicon.ico">
<link rel="stylesheet" type="text/css" media="all" href="<?php echo COMMON_DIR_PATH;?>css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo COMMON_DIR_PATH;?>font-awesome/css/font-awesome.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo THEME_DIR_PATH.$active_theme;?>/css/animate.min.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo BASE;?>css/public-style.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo THEME_DIR_PATH.$active_theme;?>/css/style.css" />

<?php
$direction=$this->get_variable('direction');
if($direction ==1) {?>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo COMMON_DIR_PATH;?>css/bootstrap-rtl.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo BASE;?>css/public-rtl-style.css" />
<?php }
}
?>
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
<link rel="stylesheet" type="text/css" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css" />

<?php

$validate=array(
		"name"=>array(
		"notNull"=>array($this->get_message("not null"))
		),
		"email"=>array(
		"notNull"=>array($this->get_message("not null")),
		"isEmail"=>array($this->get_message("invalid email address"))
		),
		"subject"=>array(
		"notNull"=>array($this->get_message("not null"))
		),
		"description"=>array(
		"notNull"=>array($this->get_message("not null"))
		)
		
		
		
);
$admin_phone=Configuration::get_instance()->read('admin_phone_no');
$admin_address=Configuration::get_instance()->read('admin_address');
$admin_email=Configuration::get_instance()->read('admin_notification_email');

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
 <!-- subheader -->
<section id="subheader" class="inner_head contact_head" data-speed="8" data-type="background">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1><?php echo $this->get_label('contact us');?></h1>
                 
                </div>
            </div>
        </div>
    </section> 
    <!-- subheader close -->

    <!-- content begin -->
    
           
           
           
           
           
      
           
           
           <div class="contact-social-area cntact_top">
			<div class="container">
				<div class="row">
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 mobile_view">
						<div class="single-contact-social text-center">
							<div class="contact-social-icon">
								<i class="fa fa-phone" aria-hidden="true"></i> 
							</div>
							<div class="content-s-text">
								<h4><?php echo $this->get_label('phone number');?></h4>
								<span><?php echo $admin_phone;?></span>
							</div>
						</div>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 mobile_view">
						<div class="single-contact-social text-center">
							<div class="contact-social-icon">
								<i class="fa fa-envelope" aria-hidden="true"></i>
							</div>
							<div class="content-s-text">
								<h4><?php echo $this->get_label('email');?></h4>
								<span><?php echo $admin_email;?></span>
							</div>
						</div>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 mobile_view">
						<div class="single-contact-social text-center">
							<div class="contact-social-icon">
								<i class="fa fa-map-marker" aria-hidden="true"></i>
							</div>
							<div class="content-s-text">
								<h4><?php echo $this->get_label('location');?></h4>
								<span><?php echo $admin_address;?></span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
           
           
           
           

           <div class="contact-form-area cntact_bottm" >
			<div class="container">
				<div class="row">
<?php 
$form=$this->create_form();
$form->start("contactus","","post",$validate); 

$recaptcha_public_key=$this->get_variable("recaptcha_public_key");
?> 
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<div class="contact-form">
							<h2 class="form-title"><?php echo $this->get_label('send your message us');?></h2>
							<div class="cf-msg" style="display: none;"></div>
							
								<div class="row">
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" placeholder="<?php echo $this->get_label('name');?>" id="name" name="name" >
									</div>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" placeholder="<?php echo $this->get_label('email');?>" id="email" name="email">
									</div>
									<div class="col-md-12 col-sm-12 col-xs-12">
										<input type="text" placeholder="<?php echo $this->get_label('subject');?>" id="subject" name="subject">
									</div>
									<div class="col-md-12 col-sm-12 col-xs-12">
										<textarea class="contact-textarea" placeholder="<?php echo $this->get_label('message');?>" id="description" name="description"></textarea>
									</div>
									
							<?php if(Configuration::get_instance()->read('enable_captcha_verification')==1){?> 
                      				<div class="col-md-12 col-sm-12 col-xs-12">
                      
			    					<script src='//www.google.com/recaptcha/api.js'></script>
                            		<label><?php echo $this->get_label('image verification');?> <span class="compulsory">*</span></label>
                            		<div class="g-recaptcha" data-sitekey="<?php echo $recaptcha_public_key; ?>"></div>
                       				 </div> 
                        	<?php }?>   
									
									
									<div class="col-md-12 col-sm-12 col-xs-12 cnct_btn">
										<button class="cont-submit" id="submit" name="submit"><?php echo $this->get_label('send message');?></button>
									</div>
								</div>
													
						</div>
					</div>
					<?php $form->end(); ?>
				<?php if(Configuration::get_instance()->read('admin_address') !=""){?>
<div class="col-sm-6 col-md-6 col-xs-12">
<div>
<div class="form-group">
<div id="mapDisplay" style="display:none ;width: 100%;height: 300px;"></div>
</div>
</div>
</div>
<?php }?>
				</div>
			</div>
		</div>

       
<script type="text/javascript">
var map,lat,lng;
function initialize() {
	
	var myLatlng = new google.maps.LatLng(lat,lng);
	var myOptions = {
             zoom: 10,
             center: myLatlng,
             mapTypeId: google.maps.MapTypeId.ROADMAP
             }
	 map = new google.maps.Map(document.getElementById('mapDisplay'), myOptions);

	 $('#mapDisplay').show();
	 google.maps.event.trigger(map, 'resize');
	 map.setCenter(myLatlng);

	 marker = new google.maps.Marker({
         position: myLatlng,
         map: map
     });
     
	 var infowindow = new google.maps.InfoWindow({
		 content: "<?php echo Configuration::get_instance()->read('admin_address');?>",
		 maxWidth:233,
		 });

	 google.maps.event.addListener(marker, "mouseover", function() {
		 infowindow.open(map, marker);
		 });
	  	
	 
}

$(document).ready(function() {

	lat="";
	lng="";
	addrstr='<?php echo Configuration::get_instance()->read('admin_address');?>';

	if(addrstr !="")
	{
	     var geocoder =  new google.maps.Geocoder();
	     geocoder.geocode( { 'address':addrstr}, function(results, status)
	     {
	    	 if (status == google.maps.GeocoderStatus.OK) 
		     {
			     lat=results[0].geometry.location.lat();
				 lng=results[0].geometry.location.lng(); 
				 initialize();
	    	 }
	     });
	}
});
</script>
<?php
if($external_theme_exists!=1)
	$this->dispatch("layout/footer");
?>