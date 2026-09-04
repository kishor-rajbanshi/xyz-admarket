<?php 
$this->dispatch("layout/header/1/_11");
$res=$this->get_result('res');
$val=$res[0];
$aid=$val['id'];
$uid=$val['uid'];

$deviceenabled=$this->get_addon_status('device-targeting_enabled');
$category_enabled=$this->get_addon_status('category-targeting_enabled');
$time_enabled=$this->get_addon_status('time-targeting_enabled');
$retarget_enabled=$this->get_addon_status('retargeting_enabled');
$language_enabled=$this->get_addon_status('language-targeting_enabled');
$isp_enabled=$this->get_addon_status('isp-targeting_enabled');
$connection_enabled=$this->get_addon_status('connectiontype-targeting_enabled');


$adpriceing=$this->get_ad_pricing_value($aid);

$cpmenabled=0;
$cpaenabled=0;
$cpvenabled=0;
$popenabled=0;
$affiliateenabled=0;

if($adpriceing ==6 && $this->get_addon_status('cpa_enabled') ==1)
$cpaenabled=1;

if($adpriceing ==1 && $this->get_addon_status('cpm_enabled') ==1)
$cpmenabled=1;

if($adpriceing ==9 && $this->get_addon_status('pop-ads_enabled') ==1)
$popenabled=1;

if($adpriceing ==12 && $this->get_addon_status('affiliate-ads_enabled') ==1)
$affiliateenabled=1;

if($adpriceing ==13 && $this->get_addon_status('video-ads_enabled') ==1)
$cpvenabled=1;



$sponsored_enabled=$this->get_addon_status('sponsored_enabled');


$stringarray=array();
if($category_enabled ==1)
{
	$category_enabled_ads=Configuration::get_instance()->read('category_enabled_ads');
				
	if($category_enabled_ads !='')
	$stringarray=explode('_',$category_enabled_ads);
}


?>
<link rel="stylesheet" type="text/css" media="all" href="//code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/jquery-ui-1.8.23.custom.min.js' ></script>

<script type="text/javascript">
$(document).ready(function() {
	 $("#from_date").datepicker({dateFormat : 'dd/mm/yy'});
	 $("#to_date").datepicker({dateFormat : 'dd/mm/yy'});
});
</script>
<?php 
$from_date=$this->get_variable('from_date');
$to_date=$this->get_variable('to_date');

if($from_date !='' && $to_date =='')
$to_date=date("d",time()).'/'.date("m",time()).'/'.date("Y",time());	
?>
<script type="text/javascript">
function show_adv_stat(id)
{
	$('.statistics_tr').hide();
	$('#showstat'+id).show();
	$('#tab').val(id);

	$('.advstatclass').removeClass('tab-selection');
	$('#advstat'+id).addClass('tab-selection');
}
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

<div class="sub_menu_main"><?php echo $this->get_label('ad details',array('x'=>$this->get_ad_pricing($aid)));?></div>

<?php $this->dispatch("links/links/13/".$uid);?>

<div class="inner-box">
<div class="pages-input">


<table style="width: 100%;" cellpadding="0" cellspacing="0">
<tr><td colspan="2"><?php $this->dispatch("ad/preview/".$aid."/1");?></td></tr>

<?php 
if($uid !=0)
{
	
$keywords=$this->get_result('keyword');
$count=$this->get_variable('count');	
$adid=$this->get_variable('adid');		
$duration=$this->get_variable('duration');	

$tab=$this->get_variable('tab');

if($from_date =='' && $duration ==7)
$duration=1;

?>

<tr><td colspan="2">


<table style="width: 100%;" >
  <tr class="statistics_header">
  
    <?php if($adpriceing !=3){?>
    
    <td onclick="show_adv_stat(6);" id="advstat6" class="advstatclass" style="width: 130px;"><?php echo $this->get_label('pricing');?></td>
    
    
    
    <?php if($val['device'] ==0 || $val['device'] ==2){?>
    <td onclick="show_adv_stat(1);" id="advstat1" class="advstatclass" style="width: 130px;"><?php echo $this->get_label('keywords');?></td>
    <?php }?>
    <?php }?>
    
    
    <?php if($adpriceing !=12){?>
    <?php if($adpriceing ==3 && $sponsored_enabled ==1){?>
    <td onclick="show_adv_stat(10);" id="advstat10" class="advstatclass" style="width: 130px;"><?php echo $this->get_label('sponsored mappings');?></td>
    <?php }?>
    
    
    <?php if($adpriceing !=3){?>
    <td onclick="show_adv_stat(2);" id="advstat2" class="advstatclass" style="width: 130px;"><?php echo $this->get_label('locations');?></td>
    <?php }?>
    
    
        
    <?php if($category_enabled ==1 && in_array($adpriceing,$stringarray)){?>
    <td onclick="show_adv_stat(7);" id="advstat7" class="advstatclass" style="width: 130px;"><?php echo $this->get_label('categories');?></td>
    <?php }?>
    

    
    
    <?php if($deviceenabled ==1 && $adpriceing !=3){?>
    <td onclick="show_adv_stat(8);" id="advstat8" class="advstatclass" style="width: 130px;"><?php echo $this->get_label('target device');?></td>
    <?php }?>
    <?php }?>
    
     <?php if($language_enabled ==1 ){?>
    <td onclick="show_adv_stat(12);" id="advstat12" class="advstatclass" style="width: 130px;"><?php echo $this->get_label('target language');?></td>
    <?php }?>
    
    
    <?php  if($connection_enabled ==1 ){?>
    <td onclick="show_adv_stat(20);" id="advstat20" class="advstatclass" style="width: 130px;"><?php echo $this->get_label('connection');?></td>
    
    <?php } if($isp_enabled ==1 ){?>
    <td onclick="show_adv_stat(21);" id="advstat21" class="advstatclass" style="width: 130px;"><?php echo $this->get_label('isp');?></td>
    
    <?php }?>
    
    
    <?php if($adpriceing !=3){?>
    
    
    <?php if($adpriceing !=12){?>
    <?php if($time_enabled ==1 && (Configuration::get_instance()->read('date_filter_enabled') ==1 || Configuration::get_instance()->read('time_filter_enabled') ==1 || Configuration::get_instance()->read('day_filter_enabled') ==1)){?>
    <td onclick="show_adv_stat(9);" id="advstat9" class="advstatclass" style="width: 130px;"><?php echo $this->get_label('time targeting');?></td>
    <?php }?>
    <?php }?>
    
    
    <?php if($retarget_enabled ==1 && $val['retargeting'] ==1){?>
    <td onclick="show_adv_stat(11);" id="advstat11" class="advstatclass" style="width: 130px;"><?php echo $this->get_label('retargeting');?></td>
    <?php }?>    
    
    
    
    
    <td onclick="show_adv_stat(3);" id="advstat3" class="advstatclass" style="width: 130px;"><?php echo $this->get_label('reports');?></td>
    
    <?php if($adpriceing !=12){?>
    <td onclick="show_adv_stat(4);" id="advstat4" class="advstatclass" style="width: 130px;"><?php echo $this->get_label('keyword statistics');?></td>
    <?php }?>
    
    
    <td onclick="show_adv_stat(5);" id="advstat5" class="advstatclass" style="width: 130px;"><?php echo $this->get_label('time statistics');?></td>
    

    <?php }?>
    
    <td class="advstatclass" style="text-align: right;padding-right: 2px;">
    
    
 

<?php if($adpriceing !=3){


$form=$this->create_form();
$form->start("showstatistics",$this->make_url("ad/view/").$adid.'/'.$tab,"post");
?>

<select name="duration" id="duration" style="height: 30px;">
<option value="1" <?php if($duration==1) { echo "selected"; } ?>><?php echo $this->get_label('today');?></option>
<option value="6" <?php if($duration==6) { echo "selected"; } ?>><?php echo $this->get_label('yesterday');?></option>
<option value="2" <?php if($duration==2) { echo "selected"; } ?>><?php echo $this->get_label('last 14');?></option>
<option value="3" <?php if($duration==3) { echo "selected"; } ?>><?php echo $this->get_label('last 30');?></option>
<option value="4" <?php if($duration==4) { echo "selected"; } ?>><?php echo $this->get_label('last 12 month');?></option>
<option value="5" <?php if($duration==5) { echo "selected"; } ?>><?php echo $this->get_label('all time');?></option>
<option value="7" <?php if($duration==7) { echo "selected"; } ?>><?php echo $this->get_label('custom date');?></option>
</select>


<input type="text" readonly="readonly" name="from_date" id="from_date" value="<?php echo $from_date;?>" placeholder="From Date" size="8" />  

<input type="text" readonly="readonly" name="to_date" id="to_date" value="<?php echo $to_date;?>" placeholder="To Date" size="8" />


<input type="hidden" name="view" id="view" value="1"/>
<input type="hidden" name="tab" id="tab" value="1"/>

<input type="submit" name="stat" value="<?php echo $this->get_label('go');?>"/>
<?php $form->end(); ?>
<?php }?>
    
    
    
    
    
    
    
    </td>
  </tr>





  <?php if($adpriceing !=3){?>

  <tr id="showstat6" class="statistics_tr">
  <td colspan="15" class="statistics_td">
  <?php $this->dispatch("ad/pricing/".$aid);?>
  </td>
  </tr>


  
  <?php if($val['device'] ==0 || $val['device'] ==2){?>
  
 <tr id="showstat1" class="statistics_tr">
  <td colspan="15" class="statistics_td">
 
<table  style="width: 98%;" cellpadding="0" cellspacing="0">

	
	<tr class="no_border"><td colspan="2" height="10px"></td></tr>
	
	<tr class="no_border"><td colspan="2" height="20px" class="heading-underline" style="padding: 0px;padding-left: 10px;"><?php echo $this->get_label('targeted keywords of',array('x'=>$val['name']));?></td><td></td></tr>
	<tr class="no_border">
	<td width="50%" style="font-size: 14px;font-weight: bold;"><?php echo $this->get_label('keyword');?></td>
	<td></td>
	</tr>
	
	<?php
	$numbers=$this->get_variable('numbers');

	$query_res=$this->get_result('query_res');
	if($numbers >0)
	{
		
	foreach($query_res as $key=>$value_res)
	{
	?>
	<tr class="no_border">
	<td ><?php echo $value_res['keyword'];?></td>
	<td></td>
	</tr>
	<?php 
	}
	}
	else
	{
	?>	
	<tr class="no_border">
	<td  height="40px"><?php echo $this->get_label('global targeted');?></td>
	<td></td>
	</tr>
	<?php 	
	}
	?>
	
	</table>
 
 
 
 
 
  </td>
  </tr>
  
  <?php }?>
  <?php }?>
  
  
  
  
  
  
    <?php if($adpriceing !=12){?>
    <?php if($adpriceing ==3 && $sponsored_enabled ==1){?>

   <tr id="showstat10" class="statistics_tr">
   <td colspan="15" class="statistics_td">
 
	<?php $this->dispatch("site/sponsored_mappings_admin/".$aid,PATH_TO_ROOT.ADDON_DIR.'/sponsored/');?>

  </td>
  </tr>
  
  
    <?php }?>
  
  
  
  
  
   <?php if($adpriceing !=3){?>
  <tr id="showstat2" class="statistics_tr">
  <td colspan="15" class="statistics_td">
 
  <table  style="width: 99%;margin-left: 10px;" cellpadding="0" cellspacing="0"  >

  <tr class="no_border"><td  colspan="2" height="10px"></td></tr>
	<tr class="no_border"><td  height="40px"  class="heading-underline" style="padding: 0px;padding-left: 10px;"><?php echo $this->get_label('targeted locations of',array('x'=>$val['name']));?></td><td></td></tr>

	
	
	<?php if($this->get_addon_status('city-targeting_enabled') ==1){
		
		
	 $this->dispatch("city/locations_admin/".$aid,PATH_TO_ROOT.ADDON_DIR.'/city-targeting/');
	
	}
	else {
	$locnumbers=$this->get_variable('locnumbers');
	$locquery_res=$this->get_result('locquery_res');
	if($locnumbers >0){ 
	foreach($locquery_res as $key=>$locvalue_res){?>
	<tr class="no_border">
	<td ><?php if($locvalue_res['country_code'] !="") { echo $this->get_country_name($locvalue_res['country_code']);}?></td>
	<td ></td>
	</tr>
	<?php }	}else {?><tr class="no_border"><td colspan="2" height="40px"><?php echo $this->get_label('worldwide');?></td></tr><?php }?>
	<?php }?>
	
	
	
	</table>
 
 
 
  </td>
  </tr>
   <?php }?>
  
  
  
  
  <?php if($category_enabled ==1 && in_array($adpriceing,$stringarray)){?>
  
   <tr id="showstat7" class="statistics_tr">
   <td colspan="15" class="statistics_td">
   
   <?php $this->dispatch("category/category_targeting_admin/".$aid,PATH_TO_ROOT.ADDON_DIR.'/category-targeting/');?>
   
 

  </td>
  </tr>
  <?php }?>
  
  
  

  
  
  
  
    <?php if($deviceenabled ==1 && $adpriceing !=3){?>
  
   <tr id="showstat8" class="statistics_tr">
   <td colspan="15" class="statistics_td">

 	<?php $this->dispatch("device/device_targeting_admin/".$aid,PATH_TO_ROOT.ADDON_DIR.'/device-targeting/');?>
 	
  </td>
  </tr>
  <?php }?>
  
  
  
    <?php if($language_enabled ==1 ){?>
  
   <tr id="showstat12" class="statistics_tr">
   <td colspan="15" class="statistics_td" >

 	<?php $this->dispatch("language/targeting_view/".$aid,PATH_TO_ROOT.ADDON_DIR.'/language-targeting/');?>
 	
  </td>
  </tr>
  <?php }?>
  <?php if($connection_enabled ==1 ){?>
  	
 <tr id="showstat20" class="statistics_tr">
 <td colspan="15" class="statistics_td">
  <?php $this->dispatch("connection/targeting_view/".$aid,PATH_TO_ROOT.ADDON_DIR.'/isp-connection-targeting/'); ?>
   	
  </td>
  </tr>
  <?php  }?>
   	
  <?php if($isp_enabled ==1 ){?>
  <tr id="showstat21" class="statistics_tr">
  <td colspan="15" class="statistics_td"> 	
  <?php $this->dispatch("isp/targeting_view/".$aid,PATH_TO_ROOT.ADDON_DIR.'/isp-connection-targeting/');?>

  </td>
  </tr>
  <?php }?>
  <?php }?>
  
 
  <?php if($adpriceing !=3){?>
  
  <?php if($adpriceing !=12){?>
  <?php if($time_enabled ==1 && (Configuration::get_instance()->read('date_filter_enabled') ==1 || Configuration::get_instance()->read('time_filter_enabled') ==1 || Configuration::get_instance()->read('day_filter_enabled') ==1)){?>
  
    <tr id="showstat9" class="statistics_tr">
   <td colspan="15" class="statistics_td">
  
  
  <?php $this->dispatch("time/time_targeting_admin/".$aid,PATH_TO_ROOT.ADDON_DIR.'/time-targeting/');?>

  </td>
  </tr>

 
 <?php }?>
<?php }?>
 
  
 
 <?php if($retarget_enabled ==1 && $val['retargeting'] ==1){?>

 <tr id="showstat11" class="statistics_tr">
 <td colspan="15" class="statistics_td">
 <?php $this->dispatch("retargeting/ad_retarget_admin/".$aid,PATH_TO_ROOT.ADDON_DIR.'/retargeting/');?>
 </td></tr>

 <?php }?> 
 
 
 
  
  
   <tr id="showstat3" class="statistics_tr">
   
   
   
   
  <td colspan="15" class="statistics_td">
 <table  style="width: 99%;padding: 5px;" cellpadding="0" cellspacing="0">
 
 
 <tr class="no_border"><td height="10px"></td></tr>

<?php 
if($from_date !='')  // for custom date range
	$statistics=$this->get_adv_date_range_statistics($from_date,$to_date,$uid,$adid);
else
	$statistics=$this->get_advertiser_statistics($duration,$uid,$adid);
?>	




 <?php if($adpriceing !=12){?>
 <tr class="no_border">
 <td style="width: 140px;"><?php echo $this->get_label('impressions');?></td>
 <td style="width: 15px;">:</td>
 <td>
  <?php 
  if($adpriceing ==0)
  echo $statistics['impression'];
  else if($adpriceing ==1 || $adpriceing ==5)
  echo $statistics['cpm_impression'];
  else if($adpriceing ==6)
  echo $statistics['cpa_impression'];
  else if($adpriceing ==9)
  echo $statistics['pop_impression'];
  else if($adpriceing ==13)
  echo $statistics['cpv_impression'];
  ?>
  </td>
  </tr>
  <?php }?>
 
 
 
 <?php if($adpriceing !=9){?>
 <tr class="no_border">
 <td style="width: 140px;"><?php echo $this->get_label('clicks');?></td>
 <td style="width: 15px;">:</td>
 <td>
 <?php 
  if($adpriceing ==0)
  echo $statistics['click'];
  else if($adpriceing ==1)
  echo $statistics['cpm_click'];
  else if($adpriceing ==6)
  echo $statistics['cpa_click'];
  else if($adpriceing ==12)
  echo $statistics['affiliate_click'];
  else if($adpriceing ==13)
  echo $statistics['cpv_click'];  
 ?>
 </td>
 </tr>
 
 
 <?php if($adpriceing !=12){?>
 <tr class="no_border">
 <td><?php echo $this->get_label('ctr');?></td>
 <td>:</td>
 <td>
 
 <?php 
  if($adpriceing ==0)
  echo $statistics['ctr'];
  else if($adpriceing ==1)
  echo $statistics['cpm_ctr'];
  else if($adpriceing ==6)
  echo $statistics['cpa_ctr'];
  else if($adpriceing ==13)
  echo $statistics['cpv_ctr'];   
  
 ?>
 
 </td>
 </tr>
 <?php }?>
 
 
 <?php }?>
 
 <?php if($adpriceing ==6){?>
 
 <tr class="no_border">
 <td><?php echo $this->get_label('conversions');?></td>
 <td>:</td>
 <td><?php echo $statistics['cpa_conversion']; ?></td>
 </tr>
 
 
  <tr class="no_border">
 <td><?php echo $this->get_label('conversion ratio');?></td>
 <td>:</td>
 <td><?php echo $statistics['cpa_ratio']; ?></td>
 </tr>
 
<?php }?>
 
 <?php if($adpriceing ==12){?>
 
 <tr class="no_border">
 <td><?php echo $this->get_label('conversions');?></td>
 <td>:</td>
 <td><?php echo $statistics['affiliate_conversion']; ?></td>
 </tr>
 
 
  <tr class="no_border">
 <td><?php echo $this->get_label('conversion ratio');?></td>
 <td>:</td>
 <td><?php echo $statistics['affiliate_ratio']; ?></td>
 </tr>
 
<?php }?>
 
 
 
 
 <tr class="no_border">
 <td><?php echo $this->get_label('money spend');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>
 <td>:</td>
 <td>
 <?php
  if($adpriceing ==0)
  echo $this->get_number_format($statistics['money_spent']);
  else if($adpriceing ==1 || $adpriceing ==5)
  echo $this->get_number_format($statistics['cpm_spend']);
  else if($adpriceing ==6)
  echo $this->get_number_format($statistics['cpa_spend']);
  else if($adpriceing ==9)
  echo $this->get_number_format($statistics['pop_spend']);
  else if($adpriceing ==12)
  echo $this->get_number_format($statistics['affiliate_spend']);
  else if($adpriceing ==13)
  echo $this->get_number_format($statistics['cpv_spend']);  
 ?>
 
 </td>
 </tr>
 
 <tr class="no_border">
 <td><?php echo $this->get_label('pubprofit');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>
 <td>:</td>
 <td><?php 
  if($adpriceing ==0)
  echo $this->get_number_format($statistics['pub_profit']);
  else if($adpriceing ==1 || $adpriceing ==5)
  echo $this->get_number_format($statistics['cpm_profit']);
  else if($adpriceing ==6)
  echo $this->get_number_format($statistics['cpa_profit']);
  else if($adpriceing ==9)
  echo $this->get_number_format($statistics['pop_profit']);
  else if($adpriceing ==12)
  echo $this->get_number_format($statistics['affiliate_profit']);
  else if($adpriceing ==13)
  echo $this->get_number_format($statistics['cpv_profit']);   
  
 ?>
 </td>
 </tr>
 
 <tr class="no_border">
 <td><?php echo $this->get_label('yourprofit');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>
 <td>:</td>
 <td><?php 
 if($adpriceing ==0)
 echo $this->get_number_format(($statistics['money_spent']-$statistics['pub_profit']));
 else if($adpriceing ==1 || $adpriceing ==5)
 echo $this->get_number_format(($statistics['cpm_spend']-$statistics['cpm_profit']));
 else if($adpriceing ==6)
 echo $this->get_number_format(($statistics['cpa_spend']-$statistics['cpa_profit']));
 else if($adpriceing ==9)
 echo $this->get_number_format(($statistics['pop_spend']-$statistics['pop_profit']));
 else if($adpriceing ==12)
 echo $this->get_number_format(($statistics['affiliate_spend']-$statistics['affiliate_profit']));
 else if($adpriceing ==13)
 echo $this->get_number_format(($statistics['cpv_spend']-$statistics['cpv_profit']));   
 
 
 ?></td>
 </tr>

 </table>
  </td></tr>
  
  
  
  
  
<?php if($adpriceing !=12){?>
   <tr id="showstat4" class="statistics_tr">
  <td colspan="15" class="statistics_td">
 
 
 <table  style="width: 99%;padding: 5px;" cellpadding="0" cellspacing="0">
 
 <tr><td>
 
 
<table  style="width: 100%;" cellpadding="0" cellspacing="0" class="data_table"  >
<tr class="row_heading_tr">
<td width="160px"><?php echo $this->get_label('keyword');?></td>
<td width="90px"><?php echo $this->get_label('impressions');?></td>

<?php if($adpriceing !=9){?>
<td width="75px"><?php echo $this->get_label('clicks');?></td>
<td width="75px"><?php echo $this->get_label('ctr');?></td>
<?php }?>


 <?php if($adpriceing ==6){?>
<td style="width: 120px;"><?php echo $this->get_label('conversions');?></td>
<td style="width: 120px;"><?php echo $this->get_label('conversion ratio');?></td>

 <?php }?>


<td width="110px"><?php echo $this->get_label('money spend');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>
<td width="125px"><?php echo $this->get_label('pubprofit');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>
<td width="100px"><?php echo $this->get_label('yourprofit');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>
</tr>

<?php 
if($count >0)
{

	
foreach($keywords as $key=>$row)
{
	
	$mpid=$this->get_mapping_id($uid,$adid,$row['id']);
	
	if($from_date !='')  // for custom date range
		$statistics=$this->get_adv_date_range_statistics($from_date,$to_date,$uid,$adid,$row['id']);
	else
	$statistics=$this->get_advertiser_statistics($duration,$uid,$adid,$row['id']);
	
?>	
	<tr class="row_data_tr">
	<td ><?php echo $row['keyword'];?></td>
	
	<td >
	<?php 
  if($adpriceing ==0)
  echo $statistics['impression'];
  else if($adpriceing ==1)
  echo $statistics['cpm_impression'];
  else if($adpriceing ==6)
  echo $statistics['cpa_impression'];
  else if($adpriceing ==9)
  echo $statistics['pop_impression'];
  else if($adpriceing ==13)
  echo $statistics['cpv_impression'];  
  ?>
	</td>
	
	
	
<?php if($adpriceing !=9){?>	
  <td >
  <?php 
  if($adpriceing ==0)
  echo $statistics['click'];
  else if($adpriceing ==1)
  echo $statistics['cpm_click'];
  else if($adpriceing ==6)
  echo $statistics['cpa_click'];
  else if($adpriceing ==13)
  echo $statistics['cpv_click'];   
  
  
  ?>
	</td>
	
	
	<td >
	<?php 
  if($adpriceing ==0)
  echo $statistics['ctr'];
  else if($adpriceing ==1)
  echo $statistics['cpm_ctr'];
  else if($adpriceing ==6)
  echo $statistics['cpa_ctr'];
  else if($adpriceing ==13)
  echo $statistics['cpv_ctr'];     
  
 ?>
</td>
<?php }?>	
	
	 <?php if($adpriceing ==6){?>
	<td><?php echo $statistics['cpa_conversion'];?></td>
	<td><?php echo $statistics['cpa_ratio'];?></td>
	 <?php }?>
	
	
  <td >
  <?php 
  if($adpriceing ==0)
  echo $this->get_number_format($statistics['money_spent']);
  else if($adpriceing ==1)
  echo $this->get_number_format($statistics['cpm_spend']);
  else if($adpriceing ==6)
  echo $this->get_number_format($statistics['cpa_spend']);
  else if($adpriceing ==9)
  echo $this->get_number_format($statistics['pop_spend']);
  else if($adpriceing ==13)
  echo $this->get_number_format($statistics['cpv_spend']);  
  ?>
  </td>
	
	<td >
	<?php 
  if($adpriceing ==0)
  echo $this->get_number_format($statistics['pub_profit']);
  else if($adpriceing ==1)
  echo $this->get_number_format($statistics['cpm_profit']);
  else if($adpriceing ==6)
  echo $this->get_number_format($statistics['cpa_profit']);
  else if($adpriceing ==9)
  echo $this->get_number_format($statistics['pop_profit']);
  else if($adpriceing ==13)
  echo $this->get_number_format($statistics['cpv_profit']);  
 ?>
</td>

<td >
	<?php 
  if($adpriceing ==0)
  echo $this->get_number_format(($statistics['money_spent']-$statistics['pub_profit']));
  else if($adpriceing ==1)
  echo $this->get_number_format(($statistics['cpm_spend']-$statistics['cpm_profit']));
  else if($adpriceing ==6)
  echo $this->get_number_format(($statistics['cpa_spend']-$statistics['cpa_profit']));
  else if($adpriceing ==9)
  echo $this->get_number_format(($statistics['pop_spend']-$statistics['pop_profit']));
  else if($adpriceing ==13)
  echo $this->get_number_format(($statistics['cpv_spend']-$statistics['cpv_profit']));    
  
 ?>
</td>

	</tr>	
<?php 	
	
}	

if($from_date !='')  // for custom date range
	$statistics_global=$this->get_adv_date_range_statistics($from_date,$to_date,$uid,$adid,-2);
else
$statistics_global=$this->get_advertiser_statistics($duration,$uid,$adid,-2);

?>
<tr class="row_data_tr">
<td ><?php echo $this->get_label("global targeted");?></td>

	
	<td >
	<?php 
  if($adpriceing ==0)
  echo $statistics_global['impression'];
  else if($adpriceing ==1)
  echo $statistics_global['cpm_impression'];
  else if($adpriceing ==6)
  echo $statistics_global['cpa_impression'];
  else if($adpriceing ==9)
  echo $statistics_global['pop_impression'];
  else if($adpriceing ==13)
  echo $statistics_global['cpv_impression'];    
  ?>
	</td>
	
	
	
<?php if($adpriceing !=9){?>	
	<td >
<?php 
  if($adpriceing ==0)
  echo $statistics_global['click'];
  else if($adpriceing ==1)
  echo $statistics_global['cpm_click'];
  else if($adpriceing ==6)
  echo $statistics_global['cpa_click'];
  else if($adpriceing ==13)
  echo $statistics_global['cpv_click'];     
 ?>
	</td>
	
	
	<td >
  <?php 
  if($adpriceing ==0)
  echo $statistics_global['ctr'];
  else if($adpriceing ==1)
  echo $statistics_global['cpm_ctr'];
  else if($adpriceing ==6)
  echo $statistics_global['cpa_ctr'];
  else if($adpriceing ==13)
  echo $statistics_global['cpv_ctr'];   
  
  
  ?>
</td>
<?php }?>
	
		 <?php if($adpriceing ==6){?>
	<td><?php echo $statistics_global['cpa_conversion'];?></td>
	<td><?php echo $statistics_global['cpa_ratio'];?></td>
	 <?php }?>
	
	
	<td >
	<?php 
  if($adpriceing ==0)
  echo $this->get_number_format($statistics_global['money_spent']);
  else if($adpriceing ==1)
  echo $this->get_number_format($statistics_global['cpm_spend']);
  else if($adpriceing ==6)
  echo $this->get_number_format($statistics_global['cpa_spend']);
  else if($adpriceing ==9)
  echo $this->get_number_format($statistics_global['pop_spend']);
  else if($adpriceing ==13)
  echo $this->get_number_format($statistics_global['cpv_spend']);    
  
 ?>
</td>
	
	<td >
	<?php 
  if($adpriceing ==0)
  echo $this->get_number_format($statistics_global['pub_profit']);
  else if($adpriceing ==1)
  echo $this->get_number_format($statistics_global['cpm_profit']);
  else if($adpriceing ==6)
  echo $this->get_number_format($statistics_global['cpa_profit']);
  else if($adpriceing ==9)
  echo $this->get_number_format($statistics_global['pop_profit']);
  else if($adpriceing ==13)
  echo $this->get_number_format($statistics_global['cpv_profit']);    
  
 ?>
</td>

<td >
	<?php 
  if($adpriceing ==0)
  echo $this->get_number_format(($statistics_global['money_spent']-$statistics_global['pub_profit']));
  else if($adpriceing ==1)
  echo $this->get_number_format(($statistics_global['cpm_spend']-$statistics_global['cpm_profit']));
  else if($adpriceing ==6)
  echo $this->get_number_format(($statistics_global['cpa_spend']-$statistics_global['cpa_profit']));
  else if($adpriceing ==9)
  echo $this->get_number_format(($statistics_global['pop_spend']-$statistics_global['pop_profit']));
  else if($adpriceing ==13)
  echo $this->get_number_format(($statistics_global['cpv_spend']-$statistics_global['cpv_profit']));    
 ?>
</td>


</tr>
<?php




}
else
{

	if($from_date !='')  // for custom date range
		$statistics_global=$this->get_adv_date_range_statistics($from_date,$to_date,$uid,$adid,-2);
	else
$statistics_global=$this->get_advertiser_statistics($duration,$uid,$adid,-2);

?>
<tr class="row_data_tr">
<td ><?php echo $this->get_label("global targeted");?></td>
	
	<td >
	<?php 
  if($adpriceing ==0)
  echo $statistics_global['impression'];
  else if($adpriceing ==1)
  echo $statistics_global['cpm_impression'];
  else if($adpriceing ==6)
  echo $statistics_global['cpa_impression'];
  else if($adpriceing ==9)
  echo $statistics_global['pop_impression'];
  else if($adpriceing ==13)
  echo $statistics_global['cpv_impression'];    
  ?>
	</td>
	
	
<?php if($adpriceing !=9){?>
	<td >
	<?php 
  if($adpriceing ==0)
  echo $statistics_global['click'];
  else if($adpriceing ==1)
  echo $statistics_global['cpm_click'];
  else if($adpriceing ==6)
  echo $statistics_global['cpa_click'];
  else if($adpriceing ==13)
  echo $statistics_global['cpv_click'];       
 ?>
	</td>
	
	
	<td >
  <?php 
  if($adpriceing ==0)
  echo $statistics_global['ctr'];
  else if($adpriceing ==1)
  echo $statistics_global['cpm_ctr'];
  else if($adpriceing ==6)
  echo $statistics_global['cpa_ctr'];
  else if($adpriceing ==13)
  echo $statistics_global['cpv_ctr'];   
 ?>
</td>
<?php }?>	
	
			 <?php if($adpriceing ==6){?>
	<td><?php echo $statistics_global['cpa_conversion'];?></td>
	<td><?php echo $statistics_global['cpa_ratio'];?></td>
	 <?php }?>
	
	<td >
	<?php 
  if($adpriceing ==0)
  echo $this->get_number_format($statistics_global['money_spent']);
  else if($adpriceing ==1)
  echo $this->get_number_format($statistics_global['cpm_spend']);
  else if($adpriceing ==6)
  echo $this->get_number_format($statistics_global['cpa_spend']);
  else if($adpriceing ==9)
  echo $this->get_number_format($statistics_global['pop_spend']);
  else if($adpriceing ==13)
  echo $this->get_number_format($statistics_global['cpv_spend']);   
 ?>
</td>
	
	<td >
	<?php 
  if($adpriceing ==0)
  echo $this->get_number_format($statistics_global['pub_profit']);
  else if($adpriceing ==1)
  echo $this->get_number_format($statistics_global['cpm_profit']);
  else if($adpriceing ==6)
  echo $this->get_number_format($statistics_global['cpa_profit']);
  else if($adpriceing ==9)
  echo $this->get_number_format($statistics_global['pop_profit']);
  else if($adpriceing ==13)
  echo $this->get_number_format($statistics_global['cpv_profit']);   
  
 ?>
</td>

<td >
	<?php 
  if($adpriceing ==0)
  echo $this->get_number_format(($statistics_global['money_spent']-$statistics_global['pub_profit']));
  else if($adpriceing ==1)
  echo $this->get_number_format(($statistics_global['cpm_spend']-$statistics_global['cpm_profit']));
  else if($adpriceing ==6)
  echo $this->get_number_format(($statistics_global['cpa_spend']-$statistics_global['cpa_profit']));
  else if($adpriceing ==9)
  echo $this->get_number_format(($statistics_global['pop_spend']-$statistics_global['pop_profit']));
  else if($adpriceing ==13)
  echo $this->get_number_format(($statistics_global['cpv_spend']-$statistics_global['cpv_profit']));   
 ?>
</td>




</tr>


<?php 
}	

?>
</table>
 
 
 </td></tr>
 </table>
 
  </td>
  </tr>
<?php }?>  
  
  
  
    <tr id="showstat5" class="statistics_tr">
  <td colspan="15" class="statistics_td">

  <table  style="width: 99%;padding: 5px;" cellpadding="0" cellspacing="0">
 
 <tr><td> 
  
  
<table  style="width: 100%;" cellpadding="0" cellspacing="0" class="data_table">
<tr class="row_heading_tr">
<td width="100px"><?php echo $this->get_label('date');?></td>


<?php if($adpriceing !=12){?>
<td width="100px"><?php echo $this->get_label('impressions');?></td>
<?php }?>


<?php if($adpriceing !=9){?>
<td width="60px"><?php echo $this->get_label('clicks');?></td>

<?php if($adpriceing !=12){?>
<td width="60px"><?php echo $this->get_label('ctr');?></td>
<?php }}?>



 <?php if($adpriceing ==6 || $adpriceing ==12){?>
<td style="width: 120px;"><?php echo $this->get_label('conversions');?></td>
<td style="width: 120px;"><?php echo $this->get_label('conversion ratio');?></td>
 <?php }?>



<td width="100px"><?php echo $this->get_label('money spend');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>
<td width="120px"><?php echo $this->get_label('pubprofit');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>
<td width="70px"><?php echo $this->get_label('yourprofit');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>

</tr>



<?php 

if($from_date !='')  // for custom date range
	$statistics=$this->get_adv_date_range_timeperiod_statistics($from_date,$to_date,$uid,$adid);
else
	$statistics=$this->get_advertiser_timeperiod_statistics($duration,$uid,$adid);
	
	
	
	
	
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
	 
?>	
	<tr class="row_data_tr">
	<td ><?php echo $data;?></td>
	
	
	
	<?php if($adpriceing !=12){?>
		<td >
	<?php 
  if($adpriceing ==0)
  echo $value['impression'];
  else if($adpriceing ==1)
  echo $value['cpm_impression'];
  else if($adpriceing ==6)
  echo $value['cpa_impression'];
  else if($adpriceing ==9)
  echo $value['pop_impression'];
  else if($adpriceing ==13)
  echo $value['cpv_impression'];  
  ?>
	</td>
	<?php }?>
	
	
<?php if($adpriceing !=9){?>	
	<td >
	<?php 
  if($adpriceing ==0)
  echo $value['click'];
  else if($adpriceing ==1)
  echo $value['cpm_click'];
  else if($adpriceing ==6)
  echo $value['cpa_click'];
  else if($adpriceing ==12)
  echo $value['affiliate_click'];
  else if($adpriceing ==13)
  echo $value['cpv_click'];   
  
  
 ?>
	</td>
	
	
	<?php if($adpriceing !=12){?>
	<td >
	<?php 
  if($adpriceing ==0)
  echo $value['ctr'];
  else if($adpriceing ==1)
  echo $value['cpm_ctr'];
  else if($adpriceing ==6)
  echo $value['cpa_ctr'];
  else if($adpriceing ==13)
  echo $value['cpv_ctr'];    
  
 ?>
</td>
	<?php }}?>
	
	
	<?php if($adpriceing ==6){?>
	<td><?php echo $value['cpa_conversion'];?></td>
	<td><?php echo $value['cpa_ratio'];?></td>
	<?php }?>
	
	
	
	
	<?php if($adpriceing ==12){?>
	<td><?php echo $value['affiliate_conversion'];?></td>
	<td><?php echo $value['affiliate_ratio'];?></td>
	<?php }?>
	
	
	
	
	
	<td >
	<?php 
  if($adpriceing ==0)
  echo $this->get_number_format($value['money_spent']);
  else if($adpriceing ==1)
  echo $this->get_number_format($value['cpm_spend']);
  else if($adpriceing ==6)
  echo $this->get_number_format($value['cpa_spend']);
  else if($adpriceing ==9)
  echo $this->get_number_format($value['pop_spend']);
  else if($adpriceing ==12)
  echo $this->get_number_format($value['affiliate_spend']);
  else if($adpriceing ==13)
  echo $this->get_number_format($value['cpv_spend']);
  
  
 ?>
</td>
	
	<td >
	<?php 
  if($adpriceing ==0)
  echo $this->get_number_format($value['pub_profit']);
  else if($adpriceing ==1)
  echo $this->get_number_format($value['cpm_profit']);
  else if($adpriceing ==6)
  echo $this->get_number_format($value['cpa_profit']);
  else if($adpriceing ==9)
  echo $this->get_number_format($value['pop_profit']);
  else if($adpriceing ==12)
  echo $this->get_number_format($value['affiliate_profit']);
  else if($adpriceing ==13)
  echo $this->get_number_format($value['cpv_profit']);  
 ?>
</td>

<td >
  <?php 
  if($adpriceing ==0)
  echo $this->get_number_format(($value['money_spent']-$value['pub_profit']));
  else if($adpriceing ==1)
  echo $this->get_number_format(($value['cpm_spend']-$value['cpm_profit']));
  else if($adpriceing ==6)
  echo $this->get_number_format(($value['cpa_spend']-$value['cpa_profit']));
  else if($adpriceing ==9)
  echo $this->get_number_format(($value['pop_spend']-$value['pop_profit']));
  else if($adpriceing ==12)
  echo $this->get_number_format(($value['affiliate_spend']-$value['affiliate_profit']));
  else if($adpriceing ==13)
  echo $this->get_number_format(($value['cpv_spend']-$value['cpv_profit']));
  
 ?>
</td>
	
	</tr>	
	
	<?php }?>

</table>

  
 </td></tr></table>

 <?php }?>
 
 </table> 
 
 

   </td>
  </tr>
  
  


<?php }?>
</table>
</div>
</div>
<?php $this->dispatch("layout/footer");?>

<?php if($uid !=0){?>
<script type="text/javascript">
<?php 
if($adpriceing ==3 && $sponsored_enabled ==1)
$tab=10;
else
$tab=6;
?>
show_adv_stat(<?php echo $tab;?>);
</script>
<?php }?>