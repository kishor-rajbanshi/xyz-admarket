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

$subject            = $this->get_variable("subject");
$description        = $this->get_variable("description");
$companyAddress     = Configuration::get_instance()->read('admin_address');
$google_map_api_key = Configuration::get_instance()->read('google_map_api_key');

//&callback=initMap

if($google_map_api_key !=""){?>
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key=<?php echo $google_map_api_key;?>&sensor=false"></script>
<?php }else{?>
<script type="text/javascript" src="//maps.google.com/maps/api/js?sensor=false"></script>
<?php } ?>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 p-3 mb-3 support-request">
<h2 class="page-heading"><div class="page-inner"><i class="fa fa fa-users icon_red"></i><?php echo $this->get_label('support desk');?></div></h2>

  <div class="row">

		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<?php
			$form=$this->create_form();
			$form->start("support",$this->make_url("user/support"),"post",$validate);
			?>
				<div class="mb-3">
					<div class="form-group">
						<label class="form-label"><?php echo $this->get_label('subject');?><span class="compulsory">*</span></label>
						<input class="form-control" type="text" name="subject" id="subject" size="33" value="<?php echo $subject; ?>" />
					</div>
				</div>

				<div class="mb-3">
					<div class="form-group">
						<label class="form-label"><?php echo $this->get_label('description');?><span class="compulsory">*</span></label>
						<textarea class="form-control" name="description" id="description" rows="4" cols="47"><?php echo $description; ?></textarea>
					</div>
				</div>

				<div class="form-group">
				<input class="submit-button" type ="submit" value ="<?php echo $this->get_label('send mail');?>" />
				</div>
			<?php $form->end(); ?>
		</div>

		<?php if($companyAddress != ""){?>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="form-group">
					<label class="form-label"><?php echo $this->get_label('company location',array('x'=>$companyAddress));?></label>
					<div id="mapDisplay" style="display:none ;width: 100%;height: 300px;"></div>
				</div>
			</div>
		<?php }?>

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
		 content: "<?php echo $companyAddress;?>",
		 maxWidth:233,
		 });

	 google.maps.event.addListener(marker, "mouseover", function() {
		 infowindow.open(map, marker);
		 });
}

$(document).ready(function()
{
		lat            = "";
		lng            = "";
		companyAddress = "<?php echo $companyAddress;?>";

		if(companyAddress != "")
		{
	     var geocoder =  new google.maps.Geocoder();
	     geocoder.geocode( { 'address':companyAddress}, function(results, status)
	     {
		    	 if(status == google.maps.GeocoderStatus.OK)
			     {
				     	lat = results[0].geometry.location.lat();
					 		lng = results[0].geometry.location.lng();
					 		initialize();
		    	 }
	     });
		}
});
</script>
<?php $this->dispatch("layout/footer");?>
