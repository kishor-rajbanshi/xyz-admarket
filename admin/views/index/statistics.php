<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head><link rel="stylesheet" href="<?php echo BASE.ADMIN_DIR."/css/style.css";?>" type="text/css" />
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/jquery.min.js'></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" type="text/css" media="all">
<link rel="stylesheet" href="<?php echo COMMON_DIR_PATH;?>font-awesome/css/font-awesome.css" />


<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/jquery-ui-1.8.23.custom.min.js' ></script>

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
.report_main_table_tab 
{
    width: 75px !important;
}
</style>



<?php 


$cpc_enabled=$this->get_variable("cpc_enabled");
$cpa_enabled=$this->get_variable("cpa_enabled");
$cpm_enabled=$this->get_variable("cpm_enabled");
$html_enabled=$this->get_variable("html_enabled");
$sponsored_enabled=$this->get_variable("sponsored_enabled");
$pop_enabled=$this->get_variable("pop_enabled");
$affiliate_enabled=$this->get_variable("affiliate_enabled");
$cpv_enabled=$this->get_variable("cpv_enabled");




$skin_enabled=$this->get_variable("skin_enabled");
$interstitial_enabled=$this->get_variable("interstitial_enabled");
$textimage_enabled=$this->get_variable("textimage_enabled");
$ecommerce_enabled=$this->get_variable("ecommerce_enabled");	


$activeads=$this->get_variable("activeads");
$activetextads=$this->get_variable("activetextads");
$activebannerads=$this->get_variable("activebannerads");

$activeadscpm=$this->get_variable("activeadscpm");
$activetextadscpm=$this->get_variable("activetextadscpm");
$activebanneradscpm=$this->get_variable("activebanneradscpm");


$activesponsoredads=$this->get_variable('activesponsoredads');
$activetextsponsoredads=$this->get_variable('activetextsponsoredads');
$activebannersponsoredads=$this->get_variable('activebannersponsoredads');

$activehtmlads=$this->get_variable('activehtmlads');
$activepopads=$this->get_variable('activepopads');
$activeadsaffiliate=$this->get_variable("activeadsaffiliate");

$activeadscpa=$this->get_variable("activeadscpa");
$activetextadscpa=$this->get_variable("activetextadscpa");
$activebanneradscpa=$this->get_variable("activebanneradscpa");


$activetextimageppcads=$this->get_variable("activetextimageppcads");
$activetextimagecpmads=$this->get_variable("activetextimagecpmads");
$activetextimagesponsoredads=$this->get_variable("activetextimagesponsoredads");
$activetextimagecpaads=$this->get_variable("activetextimagecpaads");


$activeecommerceppcads=$this->get_variable("activeecommerceppcads");
$activeecommercecpmads=$this->get_variable("activeecommercecpmads");
$activeecommercesponsoredads=$this->get_variable("activeecommercesponsoredads");
$activeecommercecpaads=$this->get_variable("activeecommercecpaads");


$activeinterstitialppcads=$this->get_variable("activeinterstitialppcads");
$activeinterstitialcpmads=$this->get_variable("activeinterstitialcpmads");
$activeinterstitialsponsoredads=$this->get_variable("activeinterstitialsponsoredads");
$activeinterstitialcpaads=$this->get_variable("activeinterstitialcpaads");


$activeskinppcads=$this->get_variable("activeskinppcads");
$activeskincpmads=$this->get_variable("activeskincpmads");
$activeskinsponsoredads=$this->get_variable("activeskinsponsoredads");
$activeskincpaads=$this->get_variable("activeskincpaads");



$activeadscpv=$this->get_variable("activeadscpv");



$sponsoredrunning=$this->get_variable('sponsoredrunning');
$sponsoredexpired=$this->get_variable('sponsoredexpired');

$text_ads_enabled=$this->get_variable('text_ads_enabled');



$from_date=$this->get_variable('from_date');
$to_date=$this->get_variable('to_date');
$duration=$this->get_variable('duration');


if($from_date !='' && $to_date =='')
$to_date=date("d",time()).'/'.date("m",time()).'/'.date("Y",time());


if($from_date =='' && $duration ==7)
$duration=1;
?>

</head>

<body style="background: none;">

<style type="text/css">
.row_data_tr td
{
	border: 0px;
}

.report_main_table_tab
{
	width: 90px;
}
</style>

<script type="text/javascript">
function show_tab(id)
{
	$('.tabcontent').css('display','none');
	$('.adustatclass').removeClass('tab-selection');

	$('#showstat'+id).css('display','');
	$('#tab_'+id).addClass('tab-selection');
}

$(document).ready(function() {
  <?php if($cpc_enabled ==1){?>   
  show_tab(0);
  <?php }else if($cpm_enabled ==1){?> 
  show_tab(1);
  <?php }else if($html_enabled ==1){?> 
  show_tab(2);
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
});

</script>

<div class="inner_iframe">

<?php 






if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==2 ||$this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0 ){

	if($from_date !='')  // for custom date range
	{
		$statistics=$this->get_adv_date_range_statistics($from_date,$to_date,-1);
		
	}
	else
	{
		$statistics=$this->get_advertiser_statistics($duration,-1);
	}
 }
 else if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==3 ){
	 if($from_date !='')  // for custom date range
	{
		$statistics=$this->get_pub_date_range_statistics($from_date,$to_date,-1);
		
	}
	else
	{
		$statistics=$this->get_publisher_statistics($duration,-1);
	}
 }

/*if($from_date !='')  // for custom date range
$statistics=$this->get_adv_date_range_statistics($from_date,$to_date,-1);
else
$statistics=$this->get_advertiser_statistics($duration,-1);
*/


?>

<div class="inner-box home-box home-box-report">
<div class="report_div">

<div class="toppers-head"><i class="fa fa-line-chart" title="<?php echo $this->get_label('system reports');?>"></i><?php echo $this->get_label('system reports');?></div>



<table style="width: 100%;" cellpadding="0" cellspacing="0">
  <tr class="statistics_header">
  
  <?php if($cpc_enabled ==1){?>   
  <td class="adustatclass tab-selection" id="tab_0" onclick="show_tab(0);" style="width: 70px;"><?php echo $this->get_label('cpc');?></td>
  <?php }?>
  
  <?php if($cpm_enabled ==1){?> 
  <td class="adustatclass" id="tab_1" onclick="show_tab(1);" style="width: 70px;"><?php echo $this->get_label('cpm');?></td>
  <?php }?>
  
  <?php if($html_enabled ==1){?> 
  <td class="adustatclass" id="tab_2" onclick="show_tab(2);" style="width: 70px;"><?php echo $this->get_label('html');?></td>
  <?php }?>  
  
  <?php if($cpa_enabled ==1){?>   
  <td class="adustatclass" id="tab_6" onclick="show_tab(6);" style="width: 70px;"><?php echo $this->get_label('cpa');?></td>
  <?php }?>
  
  <?php if($cpv_enabled ==1){?>   
  <td class="adustatclass" id="tab_13" onclick="show_tab(13);" style="width: 70px;"><?php echo $this->get_label('cpv');?></td>
  <?php }?>
  
  <?php if($sponsored_enabled ==1){?> 
  <td class="adustatclass" id="tab_3" onclick="show_tab(3);" style="width: 70px;"><?php echo $this->get_label('cpd');?></td>
  <?php }?>
  
  <?php if($pop_enabled ==1){?> 
  <td class="adustatclass" id="tab_9" onclick="show_tab(9);" style="width: 70px;"><?php echo $this->get_label('pop');?></td>
  <?php }?>  
  
  <?php if($affiliate_enabled ==1){?> 
  <td class="adustatclass" id="tab_12" onclick="show_tab(12);" style="width: 70px;"><?php echo $this->get_label('affiliate');?></td>
  <?php }?>  

<td class="adustatclass"> 
<?php 
$form2=$this->create_form();
$form2->start("overall_adreport",$this->make_url("index/statistics"),"post");
?>
<div style="float: right;margin-right: 5px;">
<select name="duration" id="duration" style="width:120px;">
<option value="1" <?php if($duration==1) { echo "selected"; } ?>><?php echo $this->get_label('today');?></option>
<option value="6" <?php if($duration==6) { echo "selected"; } ?>><?php echo $this->get_label('yesterday');?></option>
<option value="2" <?php if($duration==2) { echo "selected"; } ?>><?php echo $this->get_label('last 14');?></option>
<option value="3" <?php if($duration==3) { echo "selected"; } ?>><?php echo $this->get_label('last 30');?></option>
<option value="4" <?php if($duration==4) { echo "selected"; } ?>><?php echo $this->get_label('last 12 month');?></option>
<option value="5" <?php if($duration==5) { echo "selected"; } ?>><?php echo $this->get_label('all time');?></option>
<option value="7" <?php if($duration==7) { echo "selected"; } ?>><?php echo $this->get_label('custom date');?></option>
</select>
  

<input type="text" readonly="readonly" name="from_date" id="from_date" value="<?php echo $from_date;?>" placeholder="From Date" style="width: 75px !important;" />  

<input type="text" readonly="readonly" name="to_date" id="to_date" value="<?php echo $to_date;?>" placeholder="To Date" style="width: 75px !important;" />
  
<input type="submit" name="search" value="<?php echo $this->get_label('go');?>" />
</div>
<?php $form2->end(); ?>   

</td>
</tr>
<tr class="showadustatclass statistics_tr home-table-head">
<td colspan="10">
  
<?php if($cpc_enabled ==1){?>   
<table id="showstat0" class="data_table tabcontent" cellpadding="0" cellspacing="0">
<tr class="row_heading_tr">
<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0){?>
<td><?php echo $this->get_label('ads');?></td>

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
<?php }?>
<td><?php echo $this->get_label('impressions');?></td>
<td><?php echo $this->get_label('clicks');?></td>
<td><?php echo $this->get_label('ctr');?></td>
<td><?php echo $this->get_label('money spend');?></td>
<td><?php echo $this->get_label('pubprofit');?></td>
<td><?php echo $this->get_label('balance');?></td>
</tr>
  
<tr class="row_data_tr">
<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0){?>

<td><?php echo $activeads;?></td>  

<?php if($text_ads_enabled ==1){?>
<td><?php echo $activetextads;?></td>  
<?php }?>

<td><?php echo $activebannerads;?></td>  

<?php if($textimage_enabled ==1){?>
<td><?php echo $activetextimageppcads;?></td>  
<?php }?>

<?php if($interstitial_enabled ==1){?>
<td><?php echo $activeinterstitialppcads;?></td> 
<?php }?>

<?php if($skin_enabled ==1){?>
<td><?php echo $activeskinppcads;?></td> 
<?php }?>

<?php if($ecommerce_enabled ==1){?>
<td><?php echo $activeecommerceppcads;?></td>  
<?php }?>
<?php }?>
<td><?php echo $statistics['impression'];?></td>  
<td><?php echo $statistics['click'];?></td>
<td><?php echo $statistics['ctr'];?></td> 
<td><?php echo $this->get_money_format($statistics['money_spent']);?></td> 
<td><?php echo $this->get_money_format($statistics['pub_profit']);?></td> 
<td><?php echo $this->get_money_format($statistics['money_spent']-$statistics['pub_profit']);?></td> 
</tr> 

</table>  
<?php }?>


<?php if($cpm_enabled ==1){?>   
<table id="showstat1" class="data_table tabcontent" cellpadding="0" cellspacing="0">
<tr class="row_heading_tr">
<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0){?>

<td><?php echo $this->get_label('ads');?></td>

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
<?php }?>
<td><?php echo $this->get_label('impressions');?></td>
<td><?php echo $this->get_label('clicks');?></td>
<td><?php echo $this->get_label('ctr');?></td>
<td><?php echo $this->get_label('money spend');?></td>
<td><?php echo $this->get_label('pubprofit');?></td>
<td><?php echo $this->get_label('balance');?></td>
</tr>
  
<tr class="row_data_tr">
<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0){?>

<td><?php echo $activeadscpm;?></td>  

<?php if($text_ads_enabled ==1){?>
<td><?php echo $activetextadscpm;?></td>  
<?php }?>

<td><?php echo $activebanneradscpm;?></td>  

<?php if($textimage_enabled ==1){?>
<td><?php echo $activetextimagecpmads;?></td>  
<?php }?>

<?php if($interstitial_enabled ==1){?>
<td><?php echo $activeinterstitialcpmads;?></td> 
<?php }?>

<?php if($skin_enabled ==1){?>
<td><?php echo $activeskincpmads;?></td> 
<?php }?>

<?php if($ecommerce_enabled ==1){?>
<td><?php echo $activeecommercecpmads;?></td>  
<?php }?>
<?php }?>
<td><?php echo $statistics['cpm_impression'];?></td>  
<td><?php echo $statistics['cpm_click'];?></td>
<td><?php echo $statistics['cpm_ctr'];?></td> 
<td><?php echo $this->get_money_format($statistics['cpm_spend']);?></td> 
<td><?php echo $this->get_money_format($statistics['cpm_profit']);?></td> 
<td><?php echo $this->get_money_format($statistics['cpm_spend']-$statistics['cpm_profit']);?></td> 
</tr> 

</table>  
<?php }?>

<?php if($html_enabled ==1){?>   
<table id="showstat2" class="data_table tabcontent" cellpadding="0" cellspacing="0">
<tr class="row_heading_tr">
<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0){?>

<td><?php echo $this->get_label('ads');?></td>
<?php }?>
<td><?php echo $this->get_label('impressions');?></td>
<td><?php echo $this->get_label('money spend');?></td>
<td><?php echo $this->get_label('profit');?></td>

</tr>
  
<tr class="row_data_tr">
<td><?php echo $activehtmlads;?></td>  
<td><?php echo $statistics['html_impression'];?></td>  
<td><?php echo $this->get_money_format($statistics['html_profit']);?></td> 
</tr> 

</table>  
<?php }?>


<?php if($cpa_enabled ==1){?>   
<table id="showstat6" class="data_table tabcontent" cellpadding="0" cellspacing="0">
<tr class="row_heading_tr">
<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0){?>

<td><?php echo $this->get_label('ads');?></td>

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
<?php }?>
<td><?php echo $this->get_label('impressions');?></td>
<td><?php echo $this->get_label('clicks');?></td>
<td><?php echo $this->get_label('conversions');?></td>
<td><?php echo $this->get_label('money spend');?></td>
<td><?php echo $this->get_label('pubprofit');?></td>
<td><?php echo $this->get_label('balance');?></td>
</tr>
  
<tr class="row_data_tr">
<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0){?>

<td><?php echo $activeadscpa;?></td>  

<?php if($text_ads_enabled ==1){?>
<td><?php echo $activetextadscpa;?></td>  
<?php }?>

<td><?php echo $activebanneradscpa;?></td>  

<?php if($textimage_enabled ==1){?>
<td><?php echo $activetextimagecpaads;?></td>  
<?php }?>

<?php if($interstitial_enabled ==1){?>
<td><?php echo $activeinterstitialcpaads;?></td> 
<?php }?>

<?php if($skin_enabled ==1){?>
<td><?php echo $activeskincpaads;?></td> 
<?php }?>

<?php if($ecommerce_enabled ==1){?>
<td><?php echo $activeecommercecpaads;?></td>  
<?php }?>
<?php }?>
<td><?php echo $statistics['cpa_impression'];?></td>  
<td><?php echo $statistics['cpa_click'];?></td>
<td><?php echo $statistics['cpa_conversion'];?></td> 
<td><?php echo $this->get_money_format($statistics['cpa_spend']);?></td> 
<td><?php echo $this->get_money_format($statistics['cpa_profit']);?></td> 
<td><?php echo $this->get_money_format($statistics['cpa_spend']-$statistics['cpa_profit']);?></td> 
</tr> 

</table>  
<?php }?>




<?php if($cpv_enabled ==1){?>   
<table id="showstat13" class="data_table tabcontent" cellpadding="0" cellspacing="0">
<tr class="row_heading_tr">
<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0){?>
<td><?php echo $this->get_label('ads');?></td>
<?php } ?>
<td><?php echo $this->get_label('impressions');?></td>
<td><?php echo $this->get_label('clicks');?></td>
<td><?php echo $this->get_label('ctr');?></td>
<td><?php echo $this->get_label('money spend');?></td>
<td><?php echo $this->get_label('pubprofit');?></td>
<td><?php echo $this->get_label('balance');?></td>
</tr>
  
<tr class="row_data_tr">
<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0){?>
<td><?php echo $activeadscpv;?></td>  
<?php } ?>
<td><?php echo $statistics['cpv_impression'];?></td>  
<td><?php echo $statistics['cpv_click'];?></td>
<td><?php echo $statistics['cpv_ctr'];?></td> 
<td><?php echo $this->get_money_format($statistics['cpv_spend']);?></td> 
<td><?php echo $this->get_money_format($statistics['cpv_profit']);?></td> 
<td><?php echo $this->get_money_format($statistics['cpv_spend']-$statistics['cpv_profit']);?></td> 
</tr> 

</table>  
<?php }?>





<?php if($sponsored_enabled ==1){?>
<table id="showstat3" class="data_table tabcontent" cellpadding="0" cellspacing="0">
<tr class="row_heading_tr">
<td><?php echo $this->get_label('ads');?></td>

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
  
<tr class="row_data_tr">
<td><?php echo $activesponsoredads;?></td>  

<?php if($text_ads_enabled ==1){?>
<td><?php echo $activetextsponsoredads;?></td>  
<?php }?>

<td><?php echo $activebannersponsoredads;?></td>  


<?php if($textimage_enabled ==1){?>
<td><?php echo $activetextimagesponsoredads;?></td>  
<?php }?>

<?php if($interstitial_enabled ==1){?>
<td><?php echo $activeinterstitialsponsoredads;?></td> 
<?php }?>

<?php if($skin_enabled ==1){?>
<td><?php echo $activeskinsponsoredads;?></td> 
<?php }?>


<?php if($ecommerce_enabled ==1){?>
<td><?php echo $activeecommercesponsoredads;?></td>  
<?php }?>

<td><?php echo $sponsoredrunning;?></td>  
<td><?php echo $sponsoredexpired;?></td>
</tr> 
</table>  
<?php }?>


<?php if($pop_enabled ==1){?>
<table id="showstat9" class="data_table tabcontent" cellpadding="0" cellspacing="0">
<tr class="row_heading_tr">
<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0){?>

<td><?php echo $this->get_label('ads');?></td>
<?php }?>
<td><?php echo $this->get_label('impressions');?></td>
<td><?php echo $this->get_label('money spend');?></td>
<td><?php echo $this->get_label('pubprofit');?></td>
<td><?php echo $this->get_label('balance');?></td>
</tr>
  
<tr class="row_data_tr">
<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0){?>

<td><?php echo $activepopads;?></td>  
<?php }?>
<td><?php echo $statistics['pop_impression'];?></td>  
<td><?php echo $this->get_money_format($statistics['pop_spend']);?></td>  
<td><?php echo $this->get_money_format($statistics['pop_profit']);?></td>  
<td><?php echo $this->get_money_format($statistics['pop_spend']-$statistics['pop_profit']);?></td>
</tr> 
</table>  
<?php }?>

<?php if($affiliate_enabled ==1){?>
<table id="showstat12" class="data_table tabcontent" cellpadding="0" cellspacing="0">
<tr class="row_heading_tr">
<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0){?>

<td><?php echo $this->get_label('ads');?></td>
<?php }?>
<td><?php echo $this->get_label('clicks');?></td>
<td><?php echo $this->get_label('conversions');?></td>
<td><?php echo $this->get_label('money spend');?></td>
<td><?php echo $this->get_label('pubprofit');?></td>
<td><?php echo $this->get_label('balance');?></td>
</tr>
  
<tr class="row_data_tr">
<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0){?>

<td><?php echo $activeadsaffiliate;?></td>  
<?php }?>
<td><?php echo $statistics['affiliate_click'];?></td>
<td><?php echo $statistics['affiliate_conversion'];?></td> 
<td><?php echo $this->get_money_format($statistics['affiliate_spend']);?></td> 
<td><?php echo $this->get_money_format($statistics['affiliate_profit']);?></td> 
<td><?php echo $this->get_money_format($statistics['affiliate_spend']-$statistics['affiliate_profit']);?></td> 
</tr> 

</table>  


<?php }?>

</td>
</tr> 
</table>

</div> 
</div>
</div>
</body>
</html>