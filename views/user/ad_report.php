<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<html>
<head>

<script type="text/javascript" src="<?php echo COMMON_DIR_PATH;?>js/jquery.min.js"></script>
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/jquery-ui-1.8.23.custom.min.js'></script>
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/bootstrap.min.js'></script>
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/common.js'></script>
<script type="text/javascript" src="//www.google.com/jsapi"></script>
<link href="//fonts.googleapis.com/css?family=Oswald|Raleway" rel="stylesheet">

<?php
$active_theme=$this->read_cookie_param('active_theme');
if($active_theme=="")
$active_theme=Configuration::get_instance()->read('active_theme');
?>

<link rel="stylesheet" type="text/css" media="all" href="//code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" type="text/css" media="all" href="<?php echo COMMON_DIR_PATH;?>css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo COMMON_DIR_PATH;?>font-awesome/css/font-awesome.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo BASE;?>css/public-style.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo THEME_DIR_PATH.$active_theme;?>/css/style.css" />

<?php 
$direction=$this->get_variable('direction');
if($direction ==1) {?>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo COMMON_DIR_PATH;?>css/bootstrap-rtl.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo BASE;?>css/public-rtl-style.css" />
<?php }?>



<script type="text/javascript">
$(document).ready(function() {
	 $("#from_date").datepicker({dateFormat : 'dd/mm/yy'});
	 $("#to_date").datepicker({dateFormat : 'dd/mm/yy'});
});
</script>
<?php 

$cpc_enabled=$this->get_variable("cpc_enabled");
$cpa_enabled=$this->get_variable("cpa_enabled");
$cpm_enabled=$this->get_variable("cpm_enabled");
$sponsored_enabled=$this->get_variable("sponsored_enabled");
$pop_enabled=$this->get_variable("pop_enabled");
$affiliate_enabled=$this->get_variable("affiliate_enabled");
$interstitial_enabled=$this->get_variable("interstitial_enabled");
$textimage_enabled=$this->get_variable("textimage_enabled");
$ecommerce_enabled=$this->get_variable("ecommerce_enabled");	
$cpv_enabled=$this->get_variable("cpv_enabled");	
$skin_enabled=$this->get_variable("skin_enabled");




$active_ads_cpc=$this->get_variable("active_ads_cpc");	
$pending_ads_cpc=$this->get_variable("pending_ads_cpc");	
$blocked_ads_cpc=$this->get_variable("blocked_ads_cpc");	
$text_ads_cpc=$this->get_variable("text_ads_cpc");	
$banner_ads_cpc=$this->get_variable("banner_ads_cpc");	
$text_banner_ads_cpc=$this->get_variable("text_banner_ads_cpc");	
$interstitial_ads_cpc=$this->get_variable("interstitial_ads_cpc");	
$ecommerce_ads_cpc=$this->get_variable("ecommerce_ads_cpc");	
$skin_ads_cpc=$this->get_variable("skin_ads_cpc");	


$active_ads_cpm=$this->get_variable("active_ads_cpm");	
$pending_ads_cpm=$this->get_variable("pending_ads_cpm");	
$blocked_ads_cpm=$this->get_variable("blocked_ads_cpm");	
$text_ads_cpm=$this->get_variable("text_ads_cpm");	
$banner_ads_cpm=$this->get_variable("banner_ads_cpm");	
$text_banner_ads_cpm=$this->get_variable("text_banner_ads_cpm");	
$interstitial_ads_cpm=$this->get_variable("interstitial_ads_cpm");	
$ecommerce_ads_cpm=$this->get_variable("ecommerce_ads_cpm");	
$skin_ads_cpm=$this->get_variable("skin_ads_cpm");	


$active_ads_cpa=$this->get_variable("active_ads_cpa");	
$pending_ads_cpa=$this->get_variable("pending_ads_cpa");	
$blocked_ads_cpa=$this->get_variable("blocked_ads_cpa");	
$text_ads_cpa=$this->get_variable("text_ads_cpa");	
$banner_ads_cpa=$this->get_variable("banner_ads_cpa");	
$text_banner_ads_cpa=$this->get_variable("text_banner_ads_cpa");	
$interstitial_ads_cpa=$this->get_variable("interstitial_ads_cpa");	
$ecommerce_ads_cpa=$this->get_variable("ecommerce_ads_cpa");
$skin_ads_cpa=$this->get_variable("skin_ads_cpa");	


$active_ads_cpd=$this->get_variable("active_ads_cpd");	
$pending_ads_cpd=$this->get_variable("pending_ads_cpd");	
$blocked_ads_cpd=$this->get_variable("blocked_ads_cpd");	
$text_ads_cpd=$this->get_variable("text_ads_cpd");	
$banner_ads_cpd=$this->get_variable("banner_ads_cpd");	
$text_banner_ads_cpd=$this->get_variable("text_banner_ads_cpd");	
$interstitial_ads_cpd=$this->get_variable("interstitial_ads_cpd");	
$ecommerce_ads_cpd=$this->get_variable("ecommerce_ads_cpd");		
$skin_ads_cpd=$this->get_variable("skin_ads_cpd");	

		
$active_ads_pop=$this->get_variable("active_ads_pop");	
$pending_ads_pop=$this->get_variable("pending_ads_pop");	
$blocked_ads_pop=$this->get_variable("blocked_ads_pop");	


$active_ads_affiliate=$this->get_variable("active_ads_affiliate");	
$pending_ads_affiliate=$this->get_variable("pending_ads_affiliate");	
$blocked_ads_affiliate=$this->get_variable("blocked_ads_affiliate");	



$active_ads_cpv=$this->get_variable("active_ads_cpv");	
$pending_ads_cpv=$this->get_variable("pending_ads_cpv");	
$blocked_ads_cpv=$this->get_variable("blocked_ads_cpv");


		
$sponsoredrunning=$this->get_variable("sponsoredrunning");			
$sponsoredexpired=$this->get_variable("sponsoredexpired");		


$text_ads_enabled=Configuration::get_instance()->read('text-ads_enabled');

		

if($direction ==1)
$graphdir=-1;
else
$graphdir=1;


$from_date=$this->get_variable('from_date');
$to_date=$this->get_variable('to_date');

if($from_date !='' && $to_date =='')
$to_date=date("d",time()).'/'.date("m",time()).'/'.date("Y",time());	
?>
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
</head>
<body>

<style type="text/css">
.row_data_tr td
{
	border: 0px;
}

.report_main_table_tab
{
	width: 90px;
}

.btn
{
	padding: 4px 6px;
}
	

@media (min-width: 470px) and (max-width: 847px)
{
	.report_main_table_tab
	{
		width: 55px;
	}
	
	#duration
	{
		width:125px !important;
	}
}


@media (max-width: 469px)
{	
	.report_main_table_tab
	{
		width: 30px;
	}
	
	#duration
	{
		width:80px !important;
	}
	
	.btn
	{
		padding: 4px 4px;
	}
	
}
</style>
<script type="text/javascript">
function show_tab(id)
{
	$('.tabcontent').css('display','none');
	$('.report_main_table_tab').removeClass('report_main_table_tab_temp');

	$('#showstat'+id).css('display','block');
	
	$('#tab_'+id).addClass('report_main_table_tab_temp');
}

$(document).ready(function() {


	  <?php if($cpc_enabled ==1){?>   
	  show_tab(0);
	  <?php }else if($cpm_enabled ==1){?> 
	  show_tab(1);
	  <?php }else if($cpa_enabled ==1){?>   
	  show_tab(6);
	  <?php }else if($sponsored_enabled ==1){?> 
	  show_tab(3);
	  <?php }else if($pop_enabled ==1){?> 
	  show_tab(9);
	  <?php }else if($affiliate_enabled ==1){?> 
	  show_tab(12);
	  <?php }else if($cpv_enabled ==1){?> 
	  show_tab(13);	  
	  <?php }?> 

	

	<?php if($cpc_enabled ==1){?>
	CreateResponsiveTable('table-desktop0');
	<?php }?>

	<?php if($cpm_enabled ==1){?>
	CreateResponsiveTable('table-desktop1');
	<?php }?>

	<?php if($sponsored_enabled ==1){?>
	CreateResponsiveTable('table-desktop3');
	<?php }?>
	
	<?php if($cpa_enabled ==1){?>
	CreateResponsiveTable('table-desktop6');
	<?php }?>

	<?php if($pop_enabled ==1){?>
	CreateResponsiveTable('table-desktop9');
	<?php }?>

	<?php if($affiliate_enabled ==1){?>
	CreateResponsiveTable('table-desktop12');
	<?php }?>

	<?php if($cpv_enabled ==1){?>
	CreateResponsiveTable('table-desktop13');
	<?php }?>	
	
	$(window).resize(function()
	{
		<?php if($cpc_enabled ==1){?>
	 	CreateResponsiveTable('table-desktop0');
		<?php }?>

	 	<?php if($cpm_enabled ==1){?>
		CreateResponsiveTable('table-desktop1');
		<?php }?>

		<?php if($sponsored_enabled ==1){?>
		CreateResponsiveTable('table-desktop3');
		<?php }?>
		
		<?php if($cpa_enabled ==1){?>
		CreateResponsiveTable('table-desktop6');
		<?php }?>

		<?php if($pop_enabled ==1){?>
		CreateResponsiveTable('table-desktop9');
		<?php }?>

		<?php if($affiliate_enabled ==1){?>
		CreateResponsiveTable('table-desktop12');
		<?php }?>

		<?php if($cpv_enabled ==1){?>
		CreateResponsiveTable('table-desktop13');
		<?php }?>			
	});
});
</script>


<?php 
$uid=$this->get_variable('uid');
$duration=$this->get_variable('duration');

if($from_date =='' && $duration ==7)
$duration=1;

if($from_date !='')  // for custom date range
$statistics=$this->get_adv_date_range_statistics($from_date,$to_date,$uid);
else
$statistics=$this->get_advertiser_statistics($duration,$uid);

?>  

<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 padding-side">
<div class="col-lg-8 col-sm-8 col-md-8 col-xs-7 padding-side">

  <ul style="list-style-type: none;">
  <?php if($cpc_enabled ==1){?>   
  <li class="report_main_table_tab" id="tab_0" onclick="show_tab(0);"><?php echo $this->get_label('cpc');?></li>
  <?php }?>
  
  <?php if($cpm_enabled ==1){?> 
  <li class="report_main_table_tab" id="tab_1" onclick="show_tab(1);"><?php echo $this->get_label('cpm');?></li>
  <?php }?>
  
  <?php if($cpa_enabled ==1){?>   
  <li class="report_main_table_tab" id="tab_6" onclick="show_tab(6);"><?php echo $this->get_label('cpa');?></li>
  <?php }?>
  
  <?php if($cpv_enabled ==1){?> 
  <li class="report_main_table_tab" id="tab_13" onclick="show_tab(13);"><?php echo $this->get_label('cpv');?></li>
  <?php }?>  
  
  <?php if($sponsored_enabled ==1){?> 
  <li class="report_main_table_tab" id="tab_3" onclick="show_tab(3);"><?php echo $this->get_label('cpd');?></li>
  <?php }?>
  
  <?php if($pop_enabled ==1){?> 
  <li class="report_main_table_tab" id="tab_9" onclick="show_tab(9);"><?php echo $this->get_label('pop');?></li>
  <?php }?>  
  
  <?php if($affiliate_enabled ==1){?> 
  <li class="report_main_table_tab" style="min-width: 60px;" id="tab_12" onclick="show_tab(12);"><?php echo $this->get_label('affiliate');?></li>
  <?php }?>  
    
  </ul>
</div>  
<div class="col-lg-4 col-sm-4 col-md-4 col-xs-5 padding-side">  
<?php 
$form2=$this->create_form();
$form2->start("overall_adreport",$this->make_url("user/ad_report"),"post");
?>
<div style="float: right;text-align:right;margin-bottom: 2px;">
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

<span style="float: left;" class="custom-date-div" style="display:none;"> 
<input class="form-control" type="text" readonly name="from_date" id="from_date" value="<?php echo $from_date;?>" placeholder="<?php echo $this->get_label('from date');?>" style="width: 85px;"/>  
&nbsp;
<input class="form-control" type="text" readonly name="to_date" id="to_date" value="<?php echo $to_date;?>" placeholder="<?php echo $this->get_label('to date');?>" style="width: 85px;" />
</span>

<span style="float: left;">
<input class="btn btn-danger" type="submit" name="search" value="<?php echo $this->get_label('go');?>" />
</span>
</div>
<?php $form2->end(); ?> 
</div>  
</div>


	

<?php if($cpc_enabled ==1){?> 
<div id="showstat0" class="tabcontent">  
<table id="table-desktop0" class="data_table" cellpadding="0" cellspacing="0">
<tr class="data_table_head">
<td><?php echo $this->get_label('active ads');?></td>
<td><?php echo $this->get_label('pending ads');?></td>
<td><?php echo $this->get_label('blocked ads');?></td>

<?php if($text_ads_enabled ==1){?>
<td><?php echo $this->get_label('text');?></td>
<?php }?>

<td><?php echo $this->get_label('banner');?></td>
<?php if($textimage_enabled ==1){?>
<td><?php echo $this->get_label('text+image');?></td>
<?php }?>

<?php if($interstitial_enabled ==1){?>
<td><?php echo $this->get_label('interstitial');?></td>
<?php }?>

<?php if($skin_enabled ==1){?>
<td><?php echo $this->get_label('skin');?></td>
<?php }?>

<?php if($ecommerce_enabled ==1){?>
<td><?php echo $this->get_label('ecommerce');?></td>
<?php }?>

<td><?php echo $this->get_label('impressions');?></td>
<td><?php echo $this->get_label('clicks');?></td>
<td><bdi><?php echo $this->get_label('ctr');?></bdi></td>
<td><?php echo $this->get_label('money spend');?></td>
</tr>
  
<tr class="data_table_content">
<td><?php echo $active_ads_cpc;?></td>
<td><?php echo $pending_ads_cpc;?></td>
<td><?php echo $blocked_ads_cpc;?></td>  

<?php if($text_ads_enabled ==1){?>
<td><?php echo $text_ads_cpc;?></td>  
<?php }?>

<td><?php echo $banner_ads_cpc;?></td>  

<?php if($textimage_enabled ==1){?>
<td><?php echo $text_banner_ads_cpc;?></td>  
<?php }?>

<?php if($interstitial_enabled ==1){?>
<td><?php echo $interstitial_ads_cpc;?></td> 
<?php }?>

<?php if($skin_enabled ==1){?>
<td><?php echo $skin_ads_cpc;?></td> 
<?php }?>


<?php if($ecommerce_enabled ==1){?>
<td><?php echo $ecommerce_ads_cpc;?></td>  
<?php }?>

<td><?php echo $statistics['impression'];?></td>  
<td><?php echo $statistics['click'];?></td>
<td><bdi><?php echo $statistics['ctr'];?></bdi></td> 
<td><bdi><?php echo $this->get_money_format($statistics['money_spent']);?></bdi></td> 
</tr> 
</table>  
</div>
<?php }?>


<?php if($cpm_enabled ==1){?>
<div id="showstat1" class="tabcontent">   
<table id="table-desktop1" class="data_table" cellpadding="0" cellspacing="0">
<tr class="data_table_head">
<td><?php echo $this->get_label('active ads');?></td>
<td><?php echo $this->get_label('pending ads');?></td>
<td><?php echo $this->get_label('blocked ads');?></td>

<?php if($text_ads_enabled ==1){?>
<td><?php echo $this->get_label('text');?></td>
<?php }?>

<td><?php echo $this->get_label('banner');?></td>
<?php if($textimage_enabled ==1){?>
<td><?php echo $this->get_label('text+image');?></td>
<?php }?>

<?php if($interstitial_enabled ==1){?>
<td><?php echo $this->get_label('interstitial');?></td>
<?php }?>

<?php if($skin_enabled ==1){?>
<td><?php echo $this->get_label('skin');?></td>
<?php }?>

<?php if($ecommerce_enabled ==1){?>
<td><?php echo $this->get_label('ecommerce');?></td>
<?php }?>

<td><?php echo $this->get_label('impressions');?></td>
<td><?php echo $this->get_label('clicks');?></td>
<td><bdi><?php echo $this->get_label('ctr');?></bdi></td>
<td><?php echo $this->get_label('money spend');?></td>
</tr>
  
  
<tr class="data_table_content">
<td><?php echo $active_ads_cpm;?></td>
<td><?php echo $pending_ads_cpm;?></td> 
<td><?php echo $blocked_ads_cpm;?></td>   


<?php if($text_ads_enabled ==1){?>
<td><?php echo $text_ads_cpm;?></td>  
<?php }?>

<td><?php echo $banner_ads_cpm;?></td>  

<?php if($textimage_enabled ==1){?>
<td><?php echo $text_banner_ads_cpm;?></td>  
<?php }?>

<?php if($interstitial_enabled ==1){?>
<td><?php echo $interstitial_ads_cpm;?></td> 
<?php }?>

<?php if($skin_enabled ==1){?>
<td><?php echo $skin_ads_cpm;?></td> 
<?php }?>

<?php if($ecommerce_enabled ==1){?>
<td><?php echo $ecommerce_ads_cpm;?></td>  
<?php }?>

<td><?php echo $statistics['cpm_impression'];?></td>  
<td><?php echo $statistics['cpm_click'];?></td>
<td><bdi><?php echo $statistics['cpm_ctr'];?></bdi></td> 
<td><bdi><?php echo $this->get_money_format($statistics['cpm_spend']);?></bdi></td> 
</tr> 
</table>  
</div>
<?php }?>

<?php if($cpa_enabled ==1){?>  
<div id="showstat6" class="tabcontent"> 
<table id="table-desktop6" class="data_table" cellpadding="0" cellspacing="0">
<tr class="data_table_head">
<td><?php echo $this->get_label('active ads');?></td>
<td><?php echo $this->get_label('pending ads');?></td>
<td><?php echo $this->get_label('blocked ads');?></td>


<?php if($text_ads_enabled ==1){?>
<td><?php echo $this->get_label('text');?></td>
<?php }?>


<td><?php echo $this->get_label('banner');?></td>
<?php if($textimage_enabled ==1){?>
<td><?php echo $this->get_label('text+image');?></td>
<?php }?>

<?php if($interstitial_enabled ==1){?>
<td><?php echo $this->get_label('interstitial');?></td>
<?php }?>

<?php if($skin_enabled ==1){?>
<td><?php echo $this->get_label('skin');?></td>
<?php }?>

<?php if($ecommerce_enabled ==1){?>
<td><?php echo $this->get_label('ecommerce');?></td>
<?php }?>

<td><?php echo $this->get_label('impressions');?></td>
<td><?php echo $this->get_label('clicks');?></td>
<td><?php echo $this->get_label('conversions');?></td>
<td><bdi><?php echo $this->get_label('conversion ratio');?></bdi></td>
<td><?php echo $this->get_label('money spend');?></td>
</tr>

  
<tr class="data_table_content">
<td><?php echo $active_ads_cpa;?></td>
<td><?php echo $pending_ads_cpa;?></td>  
<td><?php echo $blocked_ads_cpa;?></td>    


<?php if($text_ads_enabled ==1){?>
<td><?php echo $text_ads_cpa;?></td>  
<?php }?>


<td><?php echo $banner_ads_cpa;?></td>  

<?php if($textimage_enabled ==1){?>
<td><?php echo $text_banner_ads_cpa;?></td>  
<?php }?>

<?php if($interstitial_enabled ==1){?>
<td><?php echo $interstitial_ads_cpa;?></td> 
<?php }?>

<?php if($skin_enabled ==1){?>
<td><?php echo $skin_ads_cpa;?></td> 
<?php }?>

<?php if($ecommerce_enabled ==1){?>
<td><?php echo $ecommerce_ads_cpa;?></td>  
<?php }?>

<td><?php echo $statistics['cpa_impression'];?></td>  
<td><?php echo $statistics['cpa_click'];?></td>
<td><?php echo $statistics['cpa_conversion'];?></td> 
<td><bdi><?php echo $statistics['cpa_ratio'];?></bdi></td> 
<td><bdi><?php echo $this->get_money_format($statistics['cpa_spend']);?></bdi></td> 
</tr> 
</table>  
</div>
<?php }?>




<?php if($cpv_enabled ==1){?>
<div id="showstat13" class="tabcontent">   
<table id="table-desktop13" class="data_table" cellpadding="0" cellspacing="0">
<tr class="data_table_head">
<td><?php echo $this->get_label('active ads');?></td>
<td><?php echo $this->get_label('pending ads');?></td>
<td><?php echo $this->get_label('blocked ads');?></td>
<td><?php echo $this->get_label('impressions');?></td>
<td><?php echo $this->get_label('clicks');?></td>
<td><bdi><?php echo $this->get_label('ctr');?></bdi></td>
<td><?php echo $this->get_label('money spend');?></td>
</tr>
  
  
<tr class="data_table_content">
<td><?php echo $active_ads_cpv;?></td>
<td><?php echo $pending_ads_cpv;?></td> 
<td><?php echo $blocked_ads_cpv;?></td>   
<td><?php echo $statistics['cpv_impression'];?></td>  
<td><?php echo $statistics['cpv_click'];?></td>
<td><bdi><?php echo $statistics['cpv_ctr'];?></bdi></td> 
<td><bdi><?php echo $this->get_money_format($statistics['cpv_spend']);?></bdi></td> 
</tr> 
</table>  
</div>
<?php }?>





<?php if($sponsored_enabled ==1){?>
<div id="showstat3" class="tabcontent">
<table id="table-desktop3" class="data_table" cellpadding="0" cellspacing="0">
<tr class="data_table_head">
<td><?php echo $this->get_label('active ads');?></td>
<td><?php echo $this->get_label('pending ads');?></td>
<td><?php echo $this->get_label('blocked ads');?></td>

<?php if($text_ads_enabled ==1){?>
<td><?php echo $this->get_label('text');?></td>
<?php }?>


<td><?php echo $this->get_label('banner');?></td>
<?php if($textimage_enabled ==1){?>
<td><?php echo $this->get_label('text+image');?></td>
<?php }?>

<?php if($interstitial_enabled ==1){?>
<td><?php echo $this->get_label('interstitial');?></td>
<?php }?>

<?php if($skin_enabled ==1){?>
<td><?php echo $this->get_label('skin');?></td>
<?php }?>

<?php if($ecommerce_enabled ==1){?>
<td><?php echo $this->get_label('ecommerce');?></td>
<?php }?>

<td><?php echo $this->get_label('sponsored running');?></td>
<td><?php echo $this->get_label('sponsored expired');?></td>
</tr>
  
  
<tr class="data_table_content">
<td><?php echo $active_ads_cpd;?></td>
<td><?php echo $pending_ads_cpd;?></td>  
<td><?php echo $blocked_ads_cpd;?></td>    


<?php if($text_ads_enabled ==1){?>
<td><?php echo $text_ads_cpd;?></td>  
<?php }?>


<td><?php echo $banner_ads_cpd;?></td>  


<?php if($textimage_enabled ==1){?>
<td><?php echo $text_banner_ads_cpd;?></td>  
<?php }?>

<?php if($interstitial_enabled ==1){?>
<td><?php echo $interstitial_ads_cpd;?></td> 
<?php }?>

<?php if($skin_enabled ==1){?>
<td><?php echo $skin_ads_cpd;?></td> 
<?php }?>

<?php if($ecommerce_enabled ==1){?>
<td><?php echo $ecommerce_ads_cpd;?></td>  
<?php }?>

<td><?php echo $sponsoredrunning;?></td>  
<td><?php echo $sponsoredexpired;?></td>
</tr> 
</table>  
</div>
<?php }?>


<?php if($pop_enabled ==1){?>
<div id="showstat9" class="tabcontent">
<table id="table-desktop9" class="data_table" cellpadding="0" cellspacing="0">
<tr class="data_table_head">
<td><?php echo $this->get_label('active ads');?></td>
<td><?php echo $this->get_label('pending ads');?></td>
<td><?php echo $this->get_label('blocked ads');?></td>
<td><?php echo $this->get_label('impressions');?></td>
<td><?php echo $this->get_label('money spend');?></td>
</tr>
  
 
<tr class="data_table_content">
<td><?php echo $active_ads_pop;?></td>
<td><?php echo $pending_ads_pop;?></td>
<td><?php echo $blocked_ads_pop;?></td>  
<td><?php echo $statistics['pop_impression'];?></td>  
<td><bdi><?php echo $this->get_money_format($statistics['pop_spend']);?></bdi></td>  
</tr> 
</table>  
</div>
<?php }?>

<?php if($affiliate_enabled ==1){?>
<div id="showstat12" class="tabcontent">
<table id="table-desktop12" class="data_table" cellpadding="0" cellspacing="0">
<tr class="data_table_head">
<td><?php echo $this->get_label('active ads');?></td>
<td><?php echo $this->get_label('pending ads');?></td>
<td><?php echo $this->get_label('blocked ads');?></td>
<td><?php echo $this->get_label('clicks');?></td>
<td><?php echo $this->get_label('conversions');?></td>
<td><bdi><?php echo $this->get_label('conversion ratio');?></bdi></td>
<td><?php echo $this->get_label('money spend');?></td>
</tr>
  
<tr class="data_table_content">
<td><?php echo $active_ads_affiliate;?></td>  
<td><?php echo $pending_ads_affiliate;?></td>
<td><?php echo $blocked_ads_affiliate;?></td>
<td><?php echo $statistics['affiliate_click'];?></td>
<td><?php echo $statistics['affiliate_conversion'];?></td> 
<td><bdi><?php echo $statistics['affiliate_ratio'];?></bdi></td> 
<td><bdi><?php echo $this->get_money_format($statistics['affiliate_spend']);?></bdi></td> 
</tr> 
</table>  
</div>
<?php }?>


<!-- ******************** Graph *********************** -->



<?php 
if($from_date !='')  // for custom date range
$statistics=$this->get_adv_date_range_timeperiod_statistics($from_date,$to_date,$uid);
else
$statistics=$this->get_advertiser_timeperiod_statistics($duration,$uid);

$datavalue='';


if($duration ==7)
$hdata=',showTextEvery: 3';
else
$hdata=',showTextEvery: 1';



	foreach($statistics as $key=>$value)
	{
		 $year=substr($key,0,4);	
		 $month=substr($key,4,2);			
		 $day=substr($key,6,2);	
		 $hour=substr($key,8,2);		
		
	 
	 	 if($hour !="")
		 $data=$this->get_date_format(1,$year,$month,$day,$hour);
		 else 
		 $data=$this->get_date_format(1,$year,$month,$day);
	
		 
		 if($day=="")
		 $data=$this->get_date_format(1,$year,$month);

		 if($day=="" && $month=="")
		 $data=$year;	
		 
		 
		 
		 if($datavalue !='')
		 $datavalue.=',';
		 



		$sparray='';

		$datavalue.="['".$data."'";


		 if($cpc_enabled ==1)
		 {
		 	$datavalue.=",".$value['impression'].",".$value['click'].",".number_format($value['ctr'],2,'.','').",".number_format($value['money_spent'],2,'.','');
		 
			$sparray='2: {type: "line",targetAxisIndex:1},3: {type: "line",targetAxisIndex:1}';
		 }

		 
		 if($cpm_enabled ==1)
		 {
		 	$datavalue.=",".$value['cpm_impression'].','.$value['cpm_click'].','.number_format($value['cpm_ctr'],2,'.','').','.number_format($value['cpm_spend'],2,'.','');
		 	

			if($sparray !='')
		 	$sparray.=',6: {type: "line",targetAxisIndex:1},7: {type: "line",targetAxisIndex:1}';
			else
		 	$sparray='2: {type: "line",targetAxisIndex:1},3: {type: "line",targetAxisIndex:1}';			
		 }
		 
		 
		 if($cpa_enabled ==1)
		 {
		 	$datavalue.=",".$value['cpa_impression'].','.$value['cpa_click'].','.$value['cpa_conversion'].','.number_format($value['cpa_ratio'],2,'.','').','.number_format($value['cpa_spend'],2,'.','');
		 	
		 	
		 	
		 	if($sparray !='')
			{
				if($cpc_enabled ==1 && $cpm_enabled ==1)
		 		$sparray.=',11: {type: "line",targetAxisIndex:1},12: {type: "line",targetAxisIndex:1}';
				else if($cpc_enabled ==1 || $cpm_enabled ==1)
				$sparray.=',7: {type: "line",targetAxisIndex:1},8: {type: "line",targetAxisIndex:1}';
			}
		 	else
		 	$sparray='3: {type: "line",targetAxisIndex:1},4: {type: "line",targetAxisIndex:1}';
		 	
		 }
		 
		 
		if($pop_enabled ==1)
		{
	 		$datavalue.=",".$value['pop_impression'].','.number_format($value['pop_spend'],2,'.','');
	 		

	 		if($sparray !='')
	 		{

	 			if($cpc_enabled ==1 && $cpm_enabled ==1 && $cpa_enabled ==1)
	 			$sparray.=',14: {type: "line",targetAxisIndex:1}';
	 			else if($cpc_enabled ==1 && $cpm_enabled ==1)
	 			$sparray.=',9: {type: "line",targetAxisIndex:1}';
	 			else if(($cpc_enabled ==1 && $cpa_enabled ==1) || ($cpm_enabled ==1 && $cpa_enabled ==1))
	 			$sparray.=',10: {type: "line",targetAxisIndex:1}';
				else if($cpc_enabled ==1 || $cpm_enabled ==1)
	 			$sparray.=',5: {type: "line",targetAxisIndex:1}';
				else if($cpa_enabled ==1)
	 			$sparray.=',6: {type: "line",targetAxisIndex:1}';

	 		}
	 		else
		 	$sparray='1: {type: "line",targetAxisIndex:1}';
		}
		 
		 
		
		 if($affiliate_enabled ==1)
		 {
		 	$datavalue.=','.$value['affiliate_click'].','.$value['affiliate_conversion'].','.number_format($value['affiliate_ratio'],2,'.','').','.number_format($value['affiliate_profit'],2,'.','');
		 	
		 	
		 	if($sparray !='')
	 		{
	 			if($cpc_enabled ==1 && $cpm_enabled ==1 && $cpa_enabled ==1 && $pop_enabled ==1)
	 			$sparray.=',17: {type: "line",targetAxisIndex:1},18: {type: "line",targetAxisIndex:1}';
	 			

				else if($cpc_enabled ==1 && $cpm_enabled ==1 && $cpa_enabled ==1)
	 			$sparray.=',15: {type: "line",targetAxisIndex:1},16: {type: "line",targetAxisIndex:1}';
				else if($cpc_enabled ==1 && $cpm_enabled ==1 && $pop_enabled ==1)
	 			$sparray.=',12: {type: "line",targetAxisIndex:1},13: {type: "line",targetAxisIndex:1}';
				else if(($cpc_enabled ==1 && $cpa_enabled ==1 && $pop_enabled ==1) || ($cpm_enabled ==1 && $cpa_enabled ==1 && $pop_enabled ==1))
	 			$sparray.=',13: {type: "line",targetAxisIndex:1},14: {type: "line",targetAxisIndex:1}';


				else if($cpc_enabled ==1 && $cpm_enabled ==1)
	 			$sparray.=',10: {type: "line",targetAxisIndex:1},11: {type: "line",targetAxisIndex:1}';
				else if(($cpc_enabled ==1 && $cpa_enabled ==1) || ($cpm_enabled ==1 && $cpa_enabled ==1))
	 			$sparray.=',11: {type: "line",targetAxisIndex:1},12: {type: "line",targetAxisIndex:1}';
				else if(($cpc_enabled ==1 && $pop_enabled ==1) || ($cpm_enabled ==1 && $pop_enabled ==1))
	 			$sparray.=',8: {type: "line",targetAxisIndex:1},9: {type: "line",targetAxisIndex:1}';
	 			else if($cpa_enabled ==1 && $pop_enabled ==1)
	 			$sparray.=',9: {type: "line",targetAxisIndex:1},10: {type: "line",targetAxisIndex:1}';
	 			else if($cpc_enabled ==1 || $cpm_enabled ==1)
	 			$sparray.=',6: {type: "line",targetAxisIndex:1},7: {type: "line",targetAxisIndex:1}';
	 			else if($cpa_enabled ==1)
	 			$sparray.=',7: {type: "line",targetAxisIndex:1},8: {type: "line",targetAxisIndex:1}';
	 			else if($pop_enabled ==1)
	 			$sparray.=',4: {type: "line",targetAxisIndex:1},5: {type: "line",targetAxisIndex:1}';
	 		}
	 		else
		 	$sparray='2: {type: "line",targetAxisIndex:1},3: {type: "line",targetAxisIndex:1}';
		 }
		
		
		 
		 
		 if($cpv_enabled ==1)
		 {
		 	$datavalue.=",".$value['cpv_impression'].','.$value['cpv_click'].','.number_format($value['cpv_ctr'],2,'.','').','.number_format($value['cpv_spend'],2,'.','');
		 	
		 	if($sparray !='')
	 		{
	 			if($cpc_enabled ==1 && $cpm_enabled ==1 && $cpa_enabled ==1 && $pop_enabled ==1 && $affiliate_enabled ==1)
	 			$sparray.=',21: {type: "line",targetAxisIndex:1},22: {type: "line",targetAxisIndex:1}';
	 			
	 			
				else if($cpc_enabled ==1 && $cpm_enabled ==1 && $cpa_enabled ==1 && $pop_enabled ==1)
	 			$sparray.=',17: {type: "line",targetAxisIndex:1},18: {type: "line",targetAxisIndex:1}';	
				else if($cpc_enabled ==1 && $cpm_enabled ==1 && $cpa_enabled ==1 && $affiliate_enabled ==1)
	 			$sparray.=',19: {type: "line",targetAxisIndex:1},20: {type: "line",targetAxisIndex:1}';			
				else if($cpc_enabled ==1 && $cpm_enabled ==1 && $pop_enabled ==1 && $affiliate_enabled ==1)
	 			$sparray.=',16: {type: "line",targetAxisIndex:1},17: {type: "line",targetAxisIndex:1}';			
				else if($cpc_enabled ==1 && $cpa_enabled ==1 && $pop_enabled ==1 && $affiliate_enabled ==1) 
	 			$sparray.=',17: {type: "line",targetAxisIndex:1},18: {type: "line",targetAxisIndex:1}';	
				else if($cpm_enabled ==1 && $cpa_enabled ==1 && $pop_enabled ==1 && $affiliate_enabled ==1) 
	 			$sparray.=',17: {type: "line",targetAxisIndex:1},18: {type: "line",targetAxisIndex:1}';		
	 			
	 			
	 			
	 			
	 			
	 			
				
	 			
				else if($cpc_enabled ==1 && $cpm_enabled ==1 && $cpa_enabled ==1) 
	 			$sparray.=',15: {type: "line",targetAxisIndex:1},16: {type: "line",targetAxisIndex:1}';		
				else if($cpc_enabled ==1 && $cpm_enabled ==1 && $pop_enabled ==1) 
	 			$sparray.=',12: {type: "line",targetAxisIndex:1},13: {type: "line",targetAxisIndex:1}';		
				else if($cpc_enabled ==1 && $cpm_enabled ==1 && $affiliate_enabled ==1) 
	 			$sparray.=',14: {type: "line",targetAxisIndex:1},15: {type: "line",targetAxisIndex:1}';		
				else if($cpc_enabled ==1 && $cpa_enabled ==1 && $pop_enabled ==1) 
	 			$sparray.=',13: {type: "line",targetAxisIndex:1},14: {type: "line",targetAxisIndex:1}';			
				else if($cpc_enabled ==1 && $cpa_enabled ==1 && $affiliate_enabled ==1) 
	 			$sparray.=',15: {type: "line",targetAxisIndex:1},16: {type: "line",targetAxisIndex:1}';		
				else if($cpc_enabled ==1 && $pop_enabled ==1 && $affiliate_enabled ==1) 
	 			$sparray.=',12: {type: "line",targetAxisIndex:1},13: {type: "line",targetAxisIndex:1}';		
		 			
				else if($cpm_enabled ==1 && $pop_enabled ==1 && $affiliate_enabled ==1) 
	 			$sparray.=',12: {type: "line",targetAxisIndex:1},13: {type: "line",targetAxisIndex:1}';		
				else if($cpm_enabled ==1 && $cpa_enabled ==1 && $affiliate_enabled ==1) 
	 			$sparray.=',15: {type: "line",targetAxisIndex:1},16: {type: "line",targetAxisIndex:1}';		 			
				else if($cpm_enabled ==1 && $cpa_enabled ==1 && $pop_enabled ==1) 
	 			$sparray.=',13: {type: "line",targetAxisIndex:1},14: {type: "line",targetAxisIndex:1}';				
	 			
				else if($cpa_enabled ==1 && $pop_enabled ==1 && $affiliate_enabled ==1) 
	 			$sparray.=',13: {type: "line",targetAxisIndex:1},14: {type: "line",targetAxisIndex:1}';		
	 			
	 			
	 			

	 			
	 			
				else if($cpc_enabled ==1 && $cpm_enabled ==1)
	 			$sparray.=',10: {type: "line",targetAxisIndex:1},11: {type: "line",targetAxisIndex:1}';		
				else if($cpc_enabled ==1 && $cpa_enabled ==1)
	 			$sparray.=',11: {type: "line",targetAxisIndex:1},12: {type: "line",targetAxisIndex:1}';		
				else if($cpc_enabled ==1 && $pop_enabled ==1)
	 			$sparray.=',8: {type: "line",targetAxisIndex:1},9: {type: "line",targetAxisIndex:1}';			
				else if($cpc_enabled ==1 && $affiliate_enabled ==1)
	 			$sparray.=',10: {type: "line",targetAxisIndex:1},11: {type: "line",targetAxisIndex:1}';					
	 			

	 			
	 			
	 			else if($cpm_enabled ==1 && $cpa_enabled ==1)
	 			$sparray.=',11: {type: "line",targetAxisIndex:1},12: {type: "line",targetAxisIndex:1}';		
				else if($cpm_enabled ==1 && $pop_enabled ==1)
	 			$sparray.=',8: {type: "line",targetAxisIndex:1},9: {type: "line",targetAxisIndex:1}';				
				else if($cpm_enabled ==1 && $affiliate_enabled ==1)
	 			$sparray.=',10: {type: "line",targetAxisIndex:1},11: {type: "line",targetAxisIndex:1}';					
	 			
	 			
				else if($cpa_enabled ==1 && $pop_enabled ==1)
	 			$sparray.=',9: {type: "line",targetAxisIndex:1},10: {type: "line",targetAxisIndex:1}';			 	 			
				else if($cpa_enabled ==1 && $affiliate_enabled ==1)
	 			$sparray.=',11: {type: "line",targetAxisIndex:1},12: {type: "line",targetAxisIndex:1}';		
	 			
				else if($pop_enabled ==1 && $affiliate_enabled ==1)
	 			$sparray.=',8: {type: "line",targetAxisIndex:1},9: {type: "line",targetAxisIndex:1}';		
	 			
	 			
	 			else if($cpc_enabled ==1 || $pop_enabled ==1)
	 			$sparray.=',8: {type: "line",targetAxisIndex:1},9: {type: "line",targetAxisIndex:1}';
	 			else if($cpm_enabled ==1 || $affiliate_enabled ==1)
	 			$sparray.=',10: {type: "line",targetAxisIndex:1},11: {type: "line",targetAxisIndex:1}';	 
	 			else if($cpa_enabled ==1)
	 			$sparray.=',7: {type: "line",targetAxisIndex:1},8: {type: "line",targetAxisIndex:1}';
	 		}
		 	else
		 	$sparray.='2: {type: "line",targetAxisIndex:1},3: {type: "line",targetAxisIndex:1}';		 	
		 }		 
		 		 
		 
		 $datavalue.="]";
	 }
	 
	 
	 
	 $currencyvariable=' ('.Configuration::get_instance()->read('currency_symbol').')';
	 
	 $datahead="['".$this->get_label('date')."'";


	 if($cpc_enabled ==1)
	 $datahead.=",'".$this->get_label('ppc impressions')."','".$this->get_label('ppc clicks')."','".$this->get_label('ppc ctr')."','".$this->get_label('ppc spend').$currencyvariable."'";
	 

	 if($cpm_enabled ==1)
	 $datahead.=",'".$this->get_label('cpm impressions')."','".$this->get_label('cpm clicks')."','".$this->get_label('cpm ctr')."','".$this->get_label('cpm spend').$currencyvariable."'";
	 
	 
	 if($cpa_enabled ==1)
	 $datahead.=",'".$this->get_label('cpa impressions')."','".$this->get_label('cpa clicks')."','".$this->get_label('cpa conversions')."','".$this->get_label('conversion ratio')."','".$this->get_label('cpa spend').$currencyvariable."'";
	
	 
	 if($pop_enabled ==1)
	 $datahead.=",'".$this->get_label('pop impressions graph')."','".$this->get_label('pop spend graph').$currencyvariable."'";
	 
	 if($affiliate_enabled ==1)
	 $datahead.=",'".$this->get_label('affiliate clicks')."','".$this->get_label('affiliate conversions')."','".$this->get_label('affiliate conversion ratio')."','".$this->get_label('affiliate profit').$currencyvariable."'";
	 
	 
	 if($cpv_enabled ==1)
	 $datahead.=",'".$this->get_label('cpv impressions')."','".$this->get_label('cpv clicks')."','".$this->get_label('cpv ctr')."','".$this->get_label('cpv spend').$currencyvariable."'";
	 
	 
	 $datahead.="]";
	 
	 ?>



<script type="text/javascript">
google.load("visualization", "1", {packages:["corechart"]});
google.setOnLoadCallback(drawVisualization);

var data;
var options;
var chart;
var chartArea;
function drawVisualization() {
	
  data = google.visualization.arrayToDataTable([
    <?php echo $datahead;?>,
	<?php echo $datavalue; ?>
  ]);


  options = {

	vAxes: {viewWindow: {min: 0},0: {title:"<?php echo $this->get_label('count');?>",logScale: true, scaleType:"mirrorLog"},1: {title:"<?php echo $this->get_label('graph money spend',array('x'=>Configuration::get_instance()->read('currency_symbol')));?>",logScale: true, scaleType:"mirrorLog"},gridlines: {count: 10}},
	hAxis: {slantedText:true,slantedTextAngle:60,direction:<?php echo $graphdir;?>,title: "<?php echo $this->get_label('date');?>"<?php echo $hdata;?>,logScale:true},
    seriesType: "bars",
    series: {<?php echo $sparray;?>},
    animation:{
        duration: 1000,
        easing: 'in',
        startup: true
      },
    chartArea: {left:100,top:50,width:'85%'},
    legend:{position: 'top',textStyle: {fontSize: 10}}
  };


	if($(window).width() < 720)
	options.chartArea['width']='50%';
	else if($(window).width() >= 720 && $(window).width() < 940)
	options.chartArea['width']='70%';
	else
	options.chartArea['width']='85%';


  
  chart = new google.visualization.ComboChart(document.getElementById('chart_div'));
  chart.draw(data, options);

}
</script>

<div id="chart_div" class="graph-div"></div>
<script type="text/javascript">
$(document).ready(function()
{	
	
	var timerclose;
	$(window).resize(function()
	{	
		clearTimeout(window.timerclose);
		window.timerclose = setTimeout(function()
		{
			if($(window).width() < 720)
			options.chartArea['width']='50%';
			else if($(window).width() >= 720 && $(window).width() < 940)
			options.chartArea['width']='70%';
			else
			options.chartArea['width']='85%';

			chart.draw(data, options);
		}, 50);
	});
});
</script>	
</body>
</html>