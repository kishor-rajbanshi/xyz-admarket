<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<html>
<head>

<script type="text/javascript" src='<?php echo BASE;?>common/js/jquery.min.js'></script>
<script type='text/javascript' src='<?php echo BASE;?>common/js/jquery-ui-1.8.23.custom.min.js'></script>
<script type='text/javascript' src='<?php echo BASE;?>common/js/bootstrap.min.js'></script>
<script type='text/javascript' src='<?php echo BASE;?>common/js/common.js'></script>


<?php 

setlocale(LC_ALL,'en_US');
setlocale(LC_CTYPE ,"en_US.UTF-8");

$active_theme=$this->read_cookie_param('active_theme');
if($active_theme =="")
$active_theme=Configuration::get_instance()->read('active_theme');
?>

<link rel="stylesheet" type="text/css" media="all" href="<?php echo COMMON_DIR_PATH;?>css/bootstrap.min.css" />


<link rel="stylesheet" type="text/css" media="all" href="<?php echo BASE;?>css/public-style.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo THEME_DIR_PATH.$active_theme;?>/css/style.css" />


<link rel="stylesheet" type="text/css" media="all" href="<?php echo BASE;?>common/font-awesome/css/font-awesome.css" />
<link rel="stylesheet" type="text/css" media="all" href="//code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">

<?php 
$direction=$this->get_variable('direction');
if($direction ==1) {?>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo COMMON_DIR_PATH;?>css/bootstrap-rtl.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo BASE;?>css/public-rtl-style.css" />
<?php }?>

<script type="text/javascript" src="//www.google.com/jsapi"></script>
<script type="text/javascript" src="//developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js"></script>

<?php 
$google_map_api_key=Configuration::get_instance()->read('google_map_api_key');

//&callback=initMap

if($google_map_api_key !=""){?>
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key=<?php echo $google_map_api_key;?>&sensor=false"></script>
<?php }else{?>
<script type="text/javascript" src="//maps.google.com/maps/api/js?sensor=false"></script> 
<?php } ?>





<link href="//fonts.googleapis.com/css?family=Oswald|Raleway" rel="stylesheet">

</head>
<body>

<script type="text/javascript">
$(document).ready(function() {

$('#duration').change(function()
{
	ShowHideDate();
});

$("#from_date").datepicker({dateFormat : 'dd/mm/yy'});
$("#to_date").datepicker({dateFormat : 'dd/mm/yy'});

ShowHideDate();
});

function ShowHideDate()
{
	if($('#duration').val() ==7)
	{
		parent.document.getElementById('custom-date-div').style.display="";
		$('.custom-date-div').show();
	}
	else
	{
		parent.document.getElementById('custom-date-div').style.display="none";

		$('.custom-date-div').hide();

		$('#from_date').val('');
		$('#to_date').val('');
	}
}
</script>	



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



$uid=$this->get_variable('uid');
$duration=$this->get_variable('duration');

if($from_date =='' && $duration ==7)
$duration=1;

$cpc_enabled=$this->get_variable('cpc_enabled');
$cpa_enabled=$this->get_variable('cpa_enabled');
$cpm_enabled=$this->get_variable('cpm_enabled');
$pop_enabled=$this->get_variable('pop_enabled');
$affiliate_enabled=$this->get_variable('affiliate_enabled');
$cpv_enabled=$this->get_variable('cpv_enabled');



$pop_addon_usage = intval(Configuration::get_instance()->read('pop_addon_usage'));

if($pop_addon_usage == 1)
$pop_enabled = 0;




$impression_map=$this->get_variable('impression_map');
$click_map=$this->get_variable('click_map');
$conversion_map=$this->get_variable('conversion_map');



if(($impression_map ==1 && $click_map ==1) || ($click_map ==1 && $conversion_map ==1))
$width_percentage=' col-lg-6 col-sm-6 col-md-6 col-xs-12 ';
else 
$width_percentage=' col-lg-12 col-sm-12 col-md-12 col-xs-12 ';




$cpa_expiry=0;
$affiliate_expiry=0;
$expiry_data=0;

if($cpa_enabled ==1)
$cpa_expiry=Configuration::get_instance()->read('daily_conversion_data_backup_expiry');

if($affiliate_enabled ==1)
$affiliate_expiry=Configuration::get_instance()->read('daily_affiliate_conversion_data_backup_expiry');

if($cpa_expiry >0 && $affiliate_expiry >0)
{
    if($cpa_expiry > $affiliate_expiry)
    $expiry_data=$affiliate_expiry;
    else
    $expiry_data=$cpa_expiry;		
}
else if($cpa_expiry >0)
$expiry_data=$cpa_expiry;		
else if($affiliate_expiry >0)
$expiry_data=$affiliate_expiry;







$record=UtilityHelper::get_geo_details_from_ip();

$latitude=$record->latitude;
$longitude=$record->longitude;


$latlngstring0=$this->get_variable('latlngstring0');
$latlngstringcpa=$this->get_variable('latlngstringcpa');


$impression_map0="";

if($impression_map ==1)
{
	$results=$this->get_array("results0");
	$impression_map0='["'.$this->get_label('country').'", "'.$this->get_label('impressions').'"]';
	
	foreach($results as $key=>$value)
	{
		$totimp=0;
		
		if($value[0] !='')
		{
				if($from_date !='')  // for custom date range
				$statistics=$this->get_adv_date_range_statistics($from_date,$to_date,$uid,0,0,0,1,$value[0]);
				else
				$statistics=$this->get_advertiser_statistics($duration,$uid,0,0,0,1,$value[0]);				
				
				
				if($cpc_enabled ==1)
				$totimp=$totimp+$statistics['impression'];
				
				if($cpm_enabled ==1)
				$totimp=$totimp+$statistics['cpm_impression'];
				
				if($cpa_enabled ==1)
				$totimp=$totimp+$statistics['cpa_impression'];			
				
				if($pop_enabled ==1)
				$totimp=$totimp+$statistics['pop_impression'];				
				
				if($cpv_enabled ==1)
				$totimp=$totimp+$statistics['cpv_impression'];			
				
				if($totimp >0)
				{
					if($impression_map0 !='');
					$impression_map0.=',';
				
					$impression_map0.='["'.$value[1].'", '.intval($totimp).']';
				}
		}
	}
}
?>	
<style type="text/css">
@media (min-width: 470px) and (max-width: 847px)
{
	#country_impression_home,#country_click_home,#country_conversion_home	{	height: 440px;	}
	
	.special-class
	{
		padding-left: 0px;
		padding-right: 0px;
	}	
	
}


@media (max-width: 469px){	
#country_impression_home,#country_click_home,#country_conversion_home	{	height: 280px;	}

	.special-class
	{
		padding-left: 0px;
		padding-right: 0px;
	}

}

.ui-datepicker
{
	z-index :1001 !important;
}

.report_main_table_tab
{
	width:120px;
}
</style>

<?php 
$form2=$this->create_form();
$form2->start("country_data","","post");
?>
<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 padding-side" style="width: 100%;text-align: right;margin-bottom: 5px;">
<span style="float: right;">
<span style="float: left;">
<select class="form-control" name="duration" id="duration" style="width: 150px;margin-right: 5px;">
<option value="1" <?php if($duration==1) { echo "selected"; } ?>><?php echo $this->get_label('today');?></option>
<option value="6" <?php if($duration==6) { echo "selected"; } ?>><?php echo $this->get_label('yesterday');?></option>
<option value="2" <?php if($duration==2) { echo "selected"; } ?>><?php echo $this->get_label('last 14');?></option>
<option value="3" <?php if($duration==3) { echo "selected"; } ?>><?php echo $this->get_label('last 30');?></option>
<option value="4" <?php if($duration==4) { echo "selected"; } ?>><?php echo $this->get_label('last 12 month');?></option>
<option value="5" <?php if($duration==5) { echo "selected"; } ?>><?php echo $this->get_label('all time');?></option>
<option value="7" <?php if($duration==7) { echo "selected"; } ?>><?php echo $this->get_label('custom date');?></option>
</select>
</span>

<span style="float: left;" class="custom-date-div"> 
<input class="form-control" type="text" readonly="readonly" name="from_date" id="from_date" value="<?php echo $from_date;?>" placeholder="<?php echo $this->get_label('from date');?>" style="width: 85px;" />  
&nbsp;
<input class="form-control" type="text" readonly="readonly" name="to_date" id="to_date" value="<?php echo $to_date;?>" placeholder="<?php echo $this->get_label('to date');?>" style="width: 85px;" />
</span>

<span style="float: left;">
<input class="btn btn-danger" style="padding: 4px 6px;" type="submit" name="search" value="<?php echo $this->get_label('go');?>" />
</span>
</span>

</div>
<?php $form2->end(); ?> 	

   
  <?php if($impression_map ==1){?>
  <div id="showstat11" class="<?php echo $width_percentage;?> special-class" style="margin-bottom: 10px;padding-left: 0px;<?php if($click_map ==0){?>padding-right: 0px;<?php }?>">
  <div >
  <ul style="list-style-type: none;z-index: 1;position: absolute;"><li class="report_main_table_tab"><?php echo $this->get_label('impressions');?></li></ul>
  <div id="country_impression_home" style="width: 100%;height: 400px;border: 1px solid #CCCCCC;"></div>
  </div>
  </div>
  <?php }?>
  
  

  <?php if($click_map ==1){?>
  <div id="showstat22" class="tabcontentdata <?php echo $width_percentage;?> special-class" style="margin-bottom: 10px;<?php if($impression_map ==0){?>padding-left: 0px;<?php }else{?>padding-right: 0px;<?php }?>">
  <label class="notification notification-home" <?php if($impression_map ==0){?>style="left:30%;"<?php }?>><?php echo $this->get_label('click days limit',array('x'=>(Configuration::get_instance()->read('daily_click_data_backup_expiry')*30)));?></label>
  <div >
    
  <ul style="list-style-type: none;z-index: 1;position: absolute;">
  <li class="report_main_table_tab" id="tab_2" <?php if($impression_map ==1 && $click_map ==1 && $conversion_map ==1){?>onclick="show_tab_map(22,'tab_2','tab_22');"<?php }?>><?php echo $this->get_label('clicks');?></li>
  <?php if($conversion_map ==1 && $impression_map ==1){?>
  <li class="report_main_table_tab" id="tab_3" <?php if($impression_map ==1 && $click_map ==1 && $conversion_map ==1){?>onclick="show_tab_map(33,'tab_3','tab_33');"<?php }?>><?php echo $this->get_label('conversions');?></li>
  <?php }?>
  </ul>
  
  <div id="country_click_home" style="width: 100%;height: 400px;border: 1px solid #CCCCCC;"></div>
  </div>
  </div>
  <?php }?>
 
  
  <?php if($conversion_map ==1){?>
  <div id="showstat33" class="tabcontentdata <?php echo $width_percentage;?> special-class" style="margin-bottom: 10px;padding-right: 0px;<?php if($impression_map ==1){?> display: none; <?php }?>">
  <label class="notification notification-home" style=""><?php echo $this->get_label('conversion days limit',array('x'=>($expiry_data*30)));?></label>
  <div >
  
  <ul style="list-style-type: none;z-index: 1;position: absolute;">
  <?php if($impression_map ==1){?>
  <li class="report_main_table_tab" id="tab_22" <?php if($impression_map ==1 && $click_map ==1 && $conversion_map ==1){?>onclick="show_tab_map(22,'tab_2','tab_22');"<?php }?>><?php echo $this->get_label('clicks');?></li>
  <?php }?>
  <li class="report_main_table_tab" id="tab_33" <?php if($impression_map ==1 && $click_map ==1 && $conversion_map ==1){?>onclick="show_tab_map(33,'tab_3','tab_33');"<?php }?>><?php echo $this->get_label('conversions');?></li>
  </ul>  
  
  
  <div id="country_conversion_home" style="width: 100%;height: 400px;border: 1px solid #CCCCCC;"></div>
  </div>
  </div>  
  <?php }?>  
  


  <script type="text/javascript">
  <?php if($impression_map ==1){?>  
      google.load("visualization", "0", {packages:["geochart"]});
      google.setOnLoadCallback(drawRegionsMap);

      function drawRegionsMap() 
      {
      	  var data = google.visualization.arrayToDataTable([<?php echo $impression_map0;?>]);

          var options = {};
          var chart = new google.visualization.GeoChart(document.getElementById('country_impression_home'));

          chart.draw(data, options);
      }
  <?php }?>



      var map;
      var marker;

      var mapcpa;
      var markercpa;
      

      var lat='<?php echo $latitude;?>';
      var lng='<?php echo $longitude;?>';

      <?php if($click_map ==1){?>	  
      function initialize() {

	   	  latlngstring='<?php echo $latlngstring0;?>';
          
    		var myLatlng = new google.maps.LatLng(lat,lng);
    		var myOptions = {
    	             zoom: 1,
    	             center: myLatlng,
    	             mapTypeId: google.maps.MapTypeId.ROADMAP
    	             }
    		 map = new google.maps.Map(document.getElementById('country_click_home'), myOptions);

    		 if(latlngstring !='')
    		 {
				latlngarray=latlngstring.split('_');
				arraylength=latlngarray.length;
    		 	var markers = [];
    		 	for (var i = 0; i < arraylength; i++) 
             	{
                 	 latlngarraysplit=latlngarray[i].split(',');
                 	 latdata=latlngarraysplit[0];
                 	 lngdata=latlngarraysplit[1];
    		      	 var maplatLng = new google.maps.LatLng(latdata,lngdata);
    		      	 var marker = new google.maps.Marker({'position': maplatLng});
    		      	 markers.push(marker);
    			 }

      		 	var options = {
      		            imagePath: 'images/m'
      		        };
  		              			 
    			 var markerCluster = new MarkerClusterer(map, markers, options);
    		 }
    	}
      <?php }?>

      <?php if($conversion_map ==1){?>

      var latlngstringcpa='<?php echo $latlngstringcpa;?>';
      
      function initializecpa() {
    	  
  		var myLatlngcpa = new google.maps.LatLng(lat,lng);
  		var myOptionscpa = {
  	             zoom: 1,
  	             center: myLatlngcpa,
  	             mapTypeId: google.maps.MapTypeId.ROADMAP
  	             }
  		 mapcpa = new google.maps.Map(document.getElementById('country_conversion_home'), myOptionscpa);

  		 if(latlngstringcpa !='')
  		 {
				latlngarray=latlngstringcpa.split('_');
				arraylength=latlngarray.length;
  		 	var markerscpa = [];
  		 	for (var i = 0; i < arraylength; i++) 
           	{
               	 latlngarraysplit=latlngarray[i].split(',');
               	 latdata=latlngarraysplit[0];
               	 lngdata=latlngarraysplit[1];
  		      	 var maplatLngcpa = new google.maps.LatLng(latdata,lngdata);
  		      	 var markercpa = new google.maps.Marker({'position': maplatLngcpa});
  		      	 markerscpa.push(markercpa);
  			 }

  		 	var options = {
  		            imagePath: 'images/m'
  		        };

			 
  			 var markerCluster = new MarkerClusterer(mapcpa, markerscpa, options);
  		 }
  	}
	<?php }?>



  	

     $(document).ready(function() 
     {
    	  <?php if($click_map ==1){?>	
    	  google.maps.event.addDomListener(window, 'load', initialize);	
    	  <?php }?>

	      <?php if($conversion_map ==1){?>
	      google.maps.event.addDomListener(window, 'load', initializecpa);	
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

function show_tab_map(id,id1,id2)
{
	$('.tabcontentdata').hide();

	if(id ==22)
	{
		if($('#tab_3').length >0)
		$('#tab_3').removeClass('report_main_table_tab_temp');

		if($('#tab_33').length >0)
		$('#tab_33').removeClass('report_main_table_tab_temp');	
	}
	else if(id ==33)
	{
		if($('#tab_2').length >0)
		$('#tab_2').removeClass('report_main_table_tab_temp');

		if($('#tab_22').length >0)
		$('#tab_22').removeClass('report_main_table_tab_temp');	
	}
	
	
	$('#showstat'+id).show();

	if($('#'+id1).length >0)
	$('#'+id1).addClass('report_main_table_tab_temp');

	if($('#'+id2).length >0)
	$('#'+id2).addClass('report_main_table_tab_temp');	


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