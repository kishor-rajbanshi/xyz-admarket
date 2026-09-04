<?php 
$this->dispatch("layout/header/13/1/s");

$validate=array(
		"subject"=>array(
				"notNull"=>array($this->get_message("not null"))

		),
		"description"=>array(
				"notNull"=>array($this->get_message("not null"))
		)
);

$subject=$this->get_variable("subject");
$description=$this->get_variable("description");


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


<div class="header_div">
<div class="container">
<h2 class="page_header"><?php echo $this->get_label('support desk');?></h2>
</div>
</div>


<section>
  <div class="container support_top">

 <div class="row spt_div">
<?php 
$form=$this->create_form();
$form->start("support",$this->make_url("user/support"),"post",$validate);
?>

<div class="col-sm-6 col-md-6 col-xs-12" >



<div class="form-group">
<label ><?php echo $this->get_label('subject');?><span class="compulsory">*</span></label>
<input class="form-control" type="text" name="subject" id="subject" size="33" value="<?php echo $subject; ?>" />
</div>


<div class="form-group">
<label ><?php echo $this->get_label('description');?><span class="compulsory">*</span></label>
<textarea class="form-control" name="description" id="description" rows="15" cols="47" style="height: 100px !important;" ><?php echo $description; ?></textarea>
</div>


<div class="form-group">
<input class="btn btn-primary btn-lg" type ="submit" value ="<?php echo $this->get_label('send mail');?>">
</div></div>
<?php $form->end(); ?>

<?php if(Configuration::get_instance()->read('admin_address') !=""){?>
<div class="col-sm-6 col-md-6 col-xs-12">
<div style="border:1px solid #cccccc;padding:15px;">

<div class="form-group"><label><?php echo $this->get_label('company location',array('x'=>Configuration::get_instance()->read('admin_address')));?></label>
<div id="mapDisplay" style="display:none ;width: 100%;height: 300px;"></div>
</div>
</div>
</div>
<?php }?>

</div>
</div>
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
<?php $this->dispatch("layout/footer");?>