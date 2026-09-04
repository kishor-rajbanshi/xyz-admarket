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



<?php 
$google_map_api_key=Configuration::get_instance()->read('google_map_api_key');

//&callback=initMap

if($google_map_api_key !=""){?>
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key=<?php echo $google_map_api_key;?>&sensor=false"></script>
<?php }else{?>
<script type="text/javascript" src="//maps.google.com/maps/api/js?sensor=false"></script> 
<?php } ?>

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

<div class="header_div">
<div class="container">

<h2 class="page_header"><?php echo $this->get_label('contact us');?></h2> 
<?php if($external_theme_exists!=1){?>
<span class="span_link">
<a  href="<?php echo BASE;?>"><?php echo $this->get_label('home');?></a> / <a href="<?php echo $advurl;?>"><?php echo $this->get_label('advertiser');?></a> / <a href="<?php echo $puburl;?>"><?php echo $this->get_label('publisher');?></a>
</span>
<?php } ?>
</div>
</div>


<section>
        

            <div class="container">
            <div class="row">
            
 <div class="col-md-12 col-sm-12 col-xs-12">
 <div class="row">
<?php 
$form=$this->create_form();
$form->start("contactus","","post",$validate); 

$recaptcha_public_key=$this->get_variable("recaptcha_public_key");
?>
<div class="col-sm-6 col-md-6 col-xs-12" >

<div>
  
                        <div class="form-group">
                            <label><?php echo $this->get_label('name');?> <span class="compulsory">*</span></label>
                            <input type="text" name="name" id="name" value="<?php echo $this->get_variable("name");?>" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label><?php echo $this->get_label('email');?> <span class="compulsory">*</span></label>
                            <input type="email" name="email" id="email" value="<?php echo $this->get_variable("email");?>" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label><?php echo $this->get_label('subject');?> <span class="compulsory">*</span></label>
                            <input type="text" name="subject" id="subject" value="<?php echo $this->get_variable("subject");?>" class="form-control" required>
                        </div> 
                        
                        
                        <div class="form-group">
                            <label><?php echo $this->get_label('description');?> <span class="compulsory">*</span></label>
                            <textarea name="description" id="description" rows="12" cols="39"  class="form-control" style="height: 75px !important;"><?php echo $this->get_variable("description");?></textarea>
                        </div> 
                        
                        
                        <?php if(Configuration::get_instance()->read('enable_captcha_verification')==1){?> 
                        <div class="form-group">
			    <script src='//www.google.com/recaptcha/api.js'></script>
                            <label><?php echo $this->get_label('image verification');?> <span class="compulsory">*</span></label>
                            <div class="g-recaptcha" data-sitekey="<?php echo $recaptcha_public_key; ?>"></div>
                        </div> 
                        <?php }?>   
                           
                           
                         <div class="form-group">
                            <button type="submit" name="submit_contact" value ="<?php echo $this->get_label('send mail');?>" class="btn btn-primary btn-lg" required="required">SEND MAIL</button>
                        </div>   
                           
                   </div></div>
<?php $form->end(); ?>

<?php if(Configuration::get_instance()->read('admin_address') !=""){?>
<div class="col-sm-6 col-md-6 col-xs-12">
<div>
<div class="form-group"><label><?php echo $this->get_label('company location',array('x'=>Configuration::get_instance()->read('admin_address')));?></label>
<div id="mapDisplay" style="display:none ;width: 100%;height: 300px;"></div>
</div>
</div>
</div>
<?php }?>

</div>
</div></div></div>
</section>



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
