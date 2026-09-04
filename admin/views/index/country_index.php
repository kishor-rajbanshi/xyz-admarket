<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<html>
<head>

<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/jquery-1.7.min.js'></script>
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>jquery-ui/jquery-ui.min.js'></script>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo COMMON_DIR_PATH;?>jquery-ui/jquery-ui.min.css" />

<link rel="stylesheet" href="<?php echo BASE.ADMIN_DIR."/css/style.css";?>" type="text/css" />
<link rel="stylesheet" href="<?php echo BASE;?>common/font-awesome/css/font-awesome.css" type="text/css" media="all" />


<script type="text/javascript" src="//www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript" src="//unpkg.com/@googlemaps/markerclusterer/dist/index.min.js"></script>

<?php
$google_map_api_key=Configuration::get_instance()->read('google_map_api_key');

if($google_map_api_key !=""){?>
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key=<?php echo $google_map_api_key;?>&libraries=marker&loading=async"></script>
<?php }else{?>
<script type="text/javascript" src="//maps.google.com/maps/api/js?libraries=marker&loading=async"></script>
<?php } ?>





<script type="text/javascript">
$(document).ready(function() {
	 $("#from_date").datepicker({dateFormat : 'dd/mm/yy'});
	 $("#to_date").datepicker({dateFormat : 'dd/mm/yy'});
});
</script>
<script type="text/javascript">
$(document).ready(function() {

$('#duration').change(function()
{
	ShowHideDate();
});

ShowHideDate();
});

function ShowHideDate()
{
	if($('#duration').val() ==7)
	{
		$('#from_date').show();
		$('#to_date').show();
	}
	else
	{
		$('#from_date').hide();
		$('#to_date').hide();

		$('#from_date').val('');
		$('#to_date').val('');
	}
}
</script>

<style type="text/css">
.ui-datepicker
{
	z-index: 1000 !important;
}
</style>
</head>
<body>
<?php
$from_date=$this->get_variable('from_date');
$to_date=$this->get_variable('to_date');

if($from_date !='' && $to_date =='')
$to_date=date("d",time()).'/'.date("m",time()).'/'.date("Y",time());

$from="";
$to="";

if($from_date !="")
$from=str_replace('/','-',$from_date);

if($to_date !="")
$to=str_replace('/','-',$to_date);


$record=UtilityHelper::get_geo_details_from_ip();

$latitude=$record->latitude;
$longitude=$record->longitude;



$latlngstring0=$this->get_variable('latlngstring0');
$latlngstringcpa=$this->get_variable('latlngstringcpa');

$duration=$this->get_variable('duration');


$cpc_enabled=$this->get_variable('cpc_enabled');
$cpa_enabled=$this->get_variable('cpa_enabled');
$cpm_enabled=$this->get_variable('cpm_enabled');
$html_enabled=$this->get_variable('html_enabled');
$cpd_enabled=$this->get_variable('cpd_enabled');
$cpp_enabled=$this->get_variable('cpp_enabled');


$impression_map=$this->get_variable('impression_map');
$impression_map0=$this->get_variable('impression_map0');
$click_map=$this->get_variable('click_map');
$conversion_map=$this->get_variable('conversion_map');



if(($impression_map ==1 && $click_map ==1) || ($click_map ==1 && $conversion_map ==1))
$width_percentage=49;
else
$width_percentage=100;

$expiry_data=0;

if($cpa_enabled ==1)
$expiry_data=Configuration::get_instance()->read('daily_conversion_data_backup_expiry');

if($from_date =='' && $duration ==7)
$duration=1;
?>

<div class="inner-box home-box">
<div class="report_div">

<div class="toppers-head">

<i class="fa fa-globe" title="<?php echo $this->get_label('geo reports');?>"></i><?php echo $this->get_label('geo reports');?>



<div class="search_div" style="width: auto;float: right;padding: 5px 5px 0px 5px !important;margin-bottom: 10px;">

<?php
$form=$this->create_form();
$form->start("advstatistics","","post");
?>
<table style="width: 100%;" cellpadding="0" cellspacing="0" >
<tr>
<td>
<select name="duration" id="duration">
<option value="1" <?php if($duration==1) { echo "selected"; } ?>><?php echo $this->get_label('today');?></option>
<option value="6" <?php if($duration==6) { echo "selected"; } ?>><?php echo $this->get_label('yesterday');?></option>
<option value="2" <?php if($duration==2) { echo "selected"; } ?>><?php echo $this->get_label('last 14');?></option>
<option value="3" <?php if($duration==3) { echo "selected"; } ?>><?php echo $this->get_label('last 30');?></option>
<option value="4" <?php if($duration==4) { echo "selected"; } ?>><?php echo $this->get_label('last 12 month');?></option>
<option value="5" <?php if($duration==5) { echo "selected"; } ?>><?php echo $this->get_label('all time');?></option>
<option value="7" <?php if($duration==7) { echo "selected"; } ?>><?php echo $this->get_label('custom date');?></option>
</select>
&nbsp;
<input type="text" readonly="readonly" name="from_date" id="from_date" value="<?php echo $from_date;?>" placeholder="<?php echo $this->get_label('from date');?>" size="5" />

<input type="text" readonly="readonly" name="to_date" id="to_date" value="<?php echo $to_date;?>" placeholder="<?php echo $this->get_label('to date');?>" size="5" />

&nbsp;<input type="submit" name="search" value="<?php echo $this->get_label('go');?>" />
</td>
</tr>
</table>
<?php $form->end(); ?>
</div>
</div>


<table style="width: 100%;" cellpadding="0" cellspacing="0" >
<tr><td >


  <div class="map-outer">

  <div class="report_main_table_data1" style="width: 99.7%;border-top:0px;">

  <?php if($impression_map ==1){?>
  <div id="showstat11" class="tabcontent map-outer-box" style="width: <?php echo $width_percentage;?>%;float: left;border-top: 0px;">
  <div >

  <table style="width: 100%;" >
  <tr class="statistics_header">
  <td style="z-index: 1;width: 125px;border: 0px;" class="adustatclass tab-selection"><?php echo $this->get_label('impressions');?></td>
  <td class="adustatclass" style="border-right: 0px;"></td>
  </tr>

  <tr id="showstat1" class="showadustatclass statistics_tr">
  <td colspan="2">
  <div id="country_impression_home" style="width: 100%;height: 380px;"></div>
  </td>
  </tr>
  </table>

  </div>
  </div>
  <?php }?>



  <?php if($click_map ==1){?>
  <div id="showstat22" class="tabcontent tabcontentdata map-outer-box" style="width: <?php echo $width_percentage;?>%;<?php if($impression_map ==1){?>float: right;<?php }else{?>float: left;<?php }?>border-top: 0px;">
  <div >

  <table style="width: 100%;" >
  <tr class="statistics_header">
  <td style="width: 125px;border: 0px;" class="adustatclass tab-selection" id="tab_2" <?php if($impression_map ==1 && $click_map ==1 && $conversion_map ==1){?>onclick="show_tab_map(22,'tab_2','tab_22');"<?php }?>><?php echo $this->get_label('clicks');?></td>

  <?php if($conversion_map ==1 && $impression_map ==1){?>
  <td style="width: 125px;" class="adustatclass" id="tab_3" <?php if($impression_map ==1 && $click_map ==1 && $conversion_map ==1){?>onclick="show_tab_map(33,'tab_3','tab_33');"<?php }?>><?php echo $this->get_label('conversions');?></td>
  <?php }?>

  <td class="adustatclass" style="border-right: 0px;">

  <label class="notification" <?php if($impression_map ==0){?>style="left:30%;"<?php }?>><?php echo $this->get_label('click days limit',array('x'=>(Configuration::get_instance()->read('daily_click_data_backup_expiry')*30)));?></label>

  </td>
  </tr>

  <tr class="showadustatclass statistics_tr">
  <td colspan="3">
  <div id="country_click_home" style="width: 98%;height: 370px;margin: 5px;"></div>
  </td>
  </tr>
  </table>


  </div>
  </div>
  <?php }?>


  <?php if($conversion_map ==1){?>
  <div id="showstat33" class="tabcontent tabcontentdata map-outer-box" style="width: <?php echo $width_percentage;?>%;float: right;<?php if($impression_map ==1){?> display: none; <?php }?>border-top: 0px;">
  <div>

  <table style="width: 100%;" >
  <tr class="statistics_header">
  <?php if($impression_map ==1){?>
  <td style="width: 125px;border-left: 0px;border-right: 1px solid #CCCCCC;" class="adustatclass tab-selection" id="tab_22" <?php if($impression_map ==1 && $click_map ==1 && $conversion_map ==1){?>onclick="show_tab_map(22,'tab_2','tab_22');"<?php }?>><?php echo $this->get_label('clicks');?></td>
  <?php }?>

  <td style="width: 125px;border: 0px;" class="adustatclass tab-selection" id="tab_33" <?php if($impression_map ==1 && $click_map ==1 && $conversion_map ==1){?>onclick="show_tab_map(33,'tab_3','tab_33');"<?php }?>><?php echo $this->get_label('conversions');?></td>

  <td class="adustatclass" style="border-right: 0px;">
  <label class="notification" style=""><?php echo $this->get_label('conversion days limit',array('x'=>($expiry_data*30)));?></label>
  </td>
  </tr>

  <tr class="showadustatclass statistics_tr">
  <td colspan="3">
  <div id="country_conversion_home" style="width: 98%;height: 370px;margin: 5px;"></div>
  </td>
  </tr>
  </table>



  </div>
  </div>
  <?php }?>


	</div>


	</div>
</td></tr>


</table>

</div>
</div>

<script type="text/javascript">

<?php if($impression_map ==1){?>
     google.charts.load('current', {'packages':['geochart'],'mapsApiKey': '<?php echo $google_map_api_key;?>'});
     google.charts.setOnLoadCallback(drawRegionsMap);

     function drawRegionsMap() {
        var data    = google.visualization.arrayToDataTable([<?php echo $impression_map0; ?>]);
        var options = {};
        var chart   = new google.visualization.GeoChart(document.getElementById('country_impression_home'));

        chart.draw(data, options);
      }
<?php }?>



      var map;
      var marker;

      var mapcpa;
      var markercpa;


      <?php if($click_map == 1){?>
	      function initialize() 
	      {
	            	var lat          = '<?php echo $latitude;?>';
      			var lng          = '<?php echo $longitude;?>';
			var latlngstring = '<?php echo $latlngstring0;?>';
	 		var myLatlng     = new google.maps.LatLng(lat,lng);
			var myOptions    = {
			    	       zoom: 1,
			    	       center: myLatlng,
			    	       mapId: google.maps.MapTypeId.ROADMAP
		     		    }
	    		var map          = new google.maps.Map(document.getElementById('country_click_home'), myOptions);

		    	if(latlngstring != '')
		    	{
				var latlngarray   = latlngstring.split('_');
				var arraylength   = latlngarray.length;
			 	var markerOptions = [];
			 	
			 	for (var i = 0; i < arraylength; i++)
		     		{
			 	 	latlngarraysplit = latlngarray[i].split(',');
			 	 	latdata          = latlngarraysplit[0];
			 	 	lngdata          = latlngarraysplit[1];
			      	    var maplatLng 	  = new google.maps.LatLng(latdata,lngdata);
			      	    var marker 	  = new google.maps.Marker({'position': maplatLng});
			      	 	markerOptions.push(marker);
				 }

			 	const markers = markerOptions.map((options) => new google.maps.marker.AdvancedMarkerElement({
					map: map,
					position: options.position
				    }));

	    			new markerClusterer.MarkerClusterer({ map, markers });
		  	}
	    	}
  	<?php }?>

  	<?php if($conversion_map == 1){?>
        function initializecpa() 
        {
        	var lat             = '<?php echo $latitude;?>';
      		var lng             = '<?php echo $longitude;?>';
		var latlngstringcpa = '<?php echo $latlngstringcpa;?>';
  		var myLatlngcpa     = new google.maps.LatLng(lat,lng);
  		var myOptionscpa    = {
			  	             zoom: 1,
			  	             center: myLatlngcpa,
			  	             mapId: google.maps.MapTypeId.ROADMAP
			  	       }
  		 var mapcpa         = new google.maps.Map(document.getElementById('country_conversion_home'), myOptionscpa);

  		 if(latlngstringcpa != '')
  		 {
			var latlngarray      = latlngstringcpa.split('_');
			var arraylength      = latlngarray.length;
  		 	var markerOptionsCPA = [];
  		 	for (var i = 0; i < arraylength; i++)
		   	{
		       	 latlngarraysplit = latlngarray[i].split(',');
		       	 latdata          = latlngarraysplit[0];
		       	 lngdata          = latlngarraysplit[1];
	  		     var maplatLngcpa     = new google.maps.LatLng(latdata,lngdata);
	  		     var markercpa        = new google.maps.Marker({'position': maplatLngcpa});
	  		      	 markerOptionsCPA.push(markercpa);
  			}

  			const markersCPA = markerOptionsCPA.map((options) => new google.maps.marker.AdvancedMarkerElement({
						map: mapcpa,
						position: options.position
					    }));

            		new markerClusterer.MarkerClusterer({ mapcpa, markersCPA });
  			 
  		 }
  	}
	<?php }?>





     $(document).ready(function()
     {
    	  <?php if($click_map ==1){?>
    	  window.addEventListener('load', initialize);
    	  <?php }?>

    	  <?php if($conversion_map ==1){?>
    	  window.addEventListener('load', initializecpa);
    	  <?php }?>

		  $(window).resize(function()
		  {
			  <?php if($impression_map ==1){?>
			  drawRegionsMap();
			  <?php }?>

			  <?php if($click_map ==1){?>
			  initialize();
			  <?php }?>

			  <?php if($conversion_map ==1){?>
			  initializecpa();
			  <?php }?>
		  });


		  <?php if($impression_map ==1 && $click_map ==1 && $conversion_map ==1){?>
		  show_tab_map(22,'tab_2','tab_22');
		  <?php }?>
      });
</script>




<script type="text/javascript">
function show_tab_map(id,id1,id2)
{
	$('.tabcontentdata').hide();

	if(id ==22)
	{
		if($('#tab_3').length >0)
		$('#tab_3').removeClass('tab-selection');

		if($('#tab_33').length >0)
		$('#tab_33').removeClass('tab-selection');
	}
	else if(id ==33)
	{
		if($('#tab_2').length >0)
		$('#tab_2').removeClass('tab-selection');

		if($('#tab_22').length >0)
		$('#tab_22').removeClass('tab-selection');
	}


	$('#showstat'+id).show();

	if($('#'+id1).length >0)
	$('#'+id1).addClass('tab-selection');

	if($('#'+id2).length >0)
	$('#'+id2).addClass('tab-selection');


	if(id ==22)
	{
		<?php if($click_map ==1){?>
		initialize();
		<?php }?>
	}
	else if(id ==33)
	{
		<?php if($conversion_map ==1){?>
		initializecpa();
		<?php }?>
	}
}
</script>
</body>
</html>
