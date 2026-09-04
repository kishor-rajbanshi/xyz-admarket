<?php
$active_theme    		= $this->read_cookie_param('active_theme');
if($active_theme == "")
$active_theme    		= Configuration::get_instance()->read('active_theme');
$external_theme_exists  = Configuration::get_instance()->read('external_theme_exists');

if($external_theme_exists != 1)
$this->dispatch("layout/header/19");
else
$this->dispatch("layout/header_assets/3");

$google_map_api_key 	= Configuration::get_instance()->read('google_map_api_key');

//&callback=initMap

if($google_map_api_key !=""){?>
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key=<?php echo $google_map_api_key;?>&sensor=false"></script>
<?php }else{?>
<script type="text/javascript" src="//maps.google.com/maps/api/js?sensor=false"></script>
<?php } ?>

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

$admin_phone     = Configuration::get_instance()->read('admin_phone_no');
$admin_address   = Configuration::get_instance()->read('admin_address');
$admin_email     = Configuration::get_instance()->read('admin_notification_email');
$captche_enabled = Configuration::get_instance()->read('enable_captcha_verification');

$recaptcha_public_key = $this->get_variable("recaptcha_public_key");
?>






<section class="common-page-title text-center">
	<div class="common-bg-layer"></div>
	<div class="common-pattern-layer"></div>
	
	<div class="auto-container">
		<div class="content-box"><h1><?php echo $this->get_label('contact us');?></h1></div></div></section>










<section class="contact-info-section pt_50 pb_30 text-center">
                <div class="container">
					
					
                    
                    <div class="row clearfix">
						
						
						
						<div class="col-lg-8 col-md-7 col-sm-12 mt-5">
							
							
						
						 <div class="form-inner">
                      
													<?php
							           	$form=$this->create_form();
							           	$form->start("contact-form","","post",$validate);
							            ?>
                            <div class="row clearfix">
                                <div class="col-lg-6 col-md-6 col-sm-12 form-group">
																	<input type="text" placeholder="<?php echo $this->get_label('your name');?>" id="name" name="name" />
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 form-group">
                                    <input type="text" name="email" id="email" placeholder="<?php echo $this->get_label('your email');?>" aria-required="true">
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 form-group">
                                    <input type="text" id="subject" name="subject" placeholder="<?php echo $this->get_label('subject');?>" aria-required="true">
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 form-group">
																		<textarea placeholder="<?php echo $this->get_label('message');?>" id="description" name="description"><?php echo $this->get_variable("description");?></textarea>
                                </div>
																<?php if($captche_enabled == 1){?>
										              	<div class="mb-3">
										              		<script src='//www.google.com/recaptcha/api.js'></script>
										                  <div class="g-recaptcha" data-sitekey="<?php echo $recaptcha_public_key; ?>"></div>
										              	</div>
																<?php }?>
                                <div class="col-lg-12 col-md-12 col-sm-12 form-group message-btn text-center">
                                    <button id="submit" type="submit" class="theme-btn" name="submit_contact"><?php echo $this->get_label('send message'); ?><span></span><span></span><span></span><span></span></button>
                                </div>
                            </div>
                        <?php $form->end(); ?>
                    </div>
						
                      </div>
						
						<div class="col-lg-4 col-md-5 col-sm-12">
						
						  <div class="col-lg-12 col-md-12 col-sm-12 info-block">
                            <div class="info-block-one">
                                <div class="inner-box">
                                    <div class="icon-box">
                                        <div class="r-hex"><div class="r-hex-inner"></div></div>
                                        <div class="icon"><i class="fa fa-map-marker" aria-hidden="true"></i>
</div>
                                    </div>
                                    <h3><?php echo $this->get_label('location');?></h3>
																		<?php if($admin_address != ""){?>
										            			  <div class="address">
										              			  
										                        <?php echo $admin_address;?>
										            			  </div>
										             		<?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 info-block">
                            <div class="info-block-one">
                                <div class="inner-box">
                                    <div class="icon-box">
                                        <div class="r-hex"><div class="r-hex-inner"></div></div>
                                        <div class="icon"><i class="fa fa-envelope-o" aria-hidden="true"></i>
</div>
                                    </div>
																		<h3><?php echo $this->get_label('email');?></h3>
																		<?php if($admin_email != ""){?>
																				<div class="email">
																					
																						<?php echo $admin_email;?>
																				</div>
																		<?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 info-block">
                            <div class="info-block-one">
                                <div class="inner-box mb-0">
                                    <div class="icon-box">
                                        <div class="r-hex"><div class="r-hex-inner"></div></div>
                                        <div class="icon"><i class="fa fa-phone" aria-hidden="true"></i>
</div>
                                    </div>
                                    <h3><?php echo $this->get_label('phone')?></h3>
																		<?php if($admin_phone != ""){?>
										              			<div class="phone">
										                      
										                        <?php echo $admin_phone;?>
										              			</div>
										              	<?php } ?>
                                </div>
                            </div>
                        </div>
						
						
						
						
						
						</div>
						
						
						
                    </div>
                </div>
            </section>


	<?php if($admin_address != ""){?>
<section class="map-section">
                <div class="container">
 <div class="map-inner" id="mapDisplay" style="display:none ;width: 100%;height: 300px;"></div>
                </div>
            </section>


					<?php } ?>



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
if($external_theme_exists != 1)
$this->dispatch("layout/footer/19");
else
$this->dispatch("layout/footer_assets/3");
?>
