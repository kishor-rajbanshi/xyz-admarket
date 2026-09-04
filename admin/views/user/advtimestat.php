<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link rel="stylesheet" href="<?php echo BASE.ADMIN_DIR."/css/style.css";?>" type="text/css" />
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/jquery.min.js'></script>
<link rel="stylesheet" type="text/css" media="all" href="//code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
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
<?php 
$from_date=$this->get_variable('from_date');
$to_date=$this->get_variable('to_date');

if($from_date !='' && $to_date =='')
$to_date=date("d",time()).'/'.date("m",time()).'/'.date("Y",time());	
?>

</head>
<body style="background: none;">

<table class="iframe_table" cellpadding="0" cellspacing="0" border="0" >

<tr><td colspan="7" height="10px;"></td></tr>



		
<tr><td colspan="7">		
 <div class="search_div">
<table class="search_div_table">	
		<?php 
		$cpc_enabled=$this->get_addon_status('cpc_enabled');
		$cpm_enabled=$this->get_addon_status('cpm_enabled');
		$cpa_enabled=$this->get_addon_status('cpa_enabled');
		$pop_enabled=$this->get_addon_status('pop-ads_enabled');
		$affiliate_enabled=$this->get_addon_status('affiliate-ads_enabled');
		$cpv_enabled=$this->get_addon_status('video-ads_enabled');
		
		
		
	 $rowspan=0;
	
	 if($cpc_enabled ==1)
	 $rowspan=$rowspan+1;
	 
	 if($cpm_enabled ==1)
	 $rowspan=$rowspan+1;
	 
	 if($cpa_enabled ==1)
	 $rowspan=$rowspan+1;
	 
	 if($pop_enabled ==1)
	 $rowspan=$rowspan+1;
	 
	 if($affiliate_enabled ==1)
	 $rowspan=$rowspan+1;		

	 if($cpv_enabled ==1)
	 $rowspan=$rowspan+1;  
	 		

$uid=$this->get_variable('uid');
$duration=$this->get_variable('duration');

if($from_date =='' && $duration ==7)
$duration=1;

$form1=$this->create_form();
$form1->start("timestatistics",$this->make_url("user/advtimestat/").$uid,"post");
?>
  
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
</td>
  
    <td>
        &nbsp;
    <input type="text" readonly="readonly" name="from_date" id="from_date" value="<?php echo $from_date;?>" placeholder="From Date" size="5" />  

<input type="text" readonly="readonly" name="to_date" id="to_date" value="<?php echo $to_date;?>" placeholder="To Date" size="5" />
    &nbsp;
    
    </td>
    <td>
    
    
    </td>
    <td>&nbsp;&nbsp; <input type="submit" name="search" class="link_button" value="<?php echo $this->get_label('go');?>" /></td>
    
    
    
  </tr>
  
 <?php $form1->end(); ?> 	

</table>
</div>
</td></tr>	
		
<tr><td colspan="7" height="10px"></td></tr>		
		
<tr><td colspan="7">		
<table style="width: 99%;margin: 4px;" cellpadding="0" cellspacing="0" border="0" class="data_table">
<tr class="row_heading_tr">

<td style="width: 100px;"><?php echo $this->get_label('date');?></td>

<?php if($rowspan >0){?>
<td style="width: 80px;"><?php echo $this->get_label('type');?></td>
<td><?php echo $this->get_label('impressions');?></td>
<td><?php echo $this->get_label('clicks');?></td>
<td><?php echo $this->get_label('ctr');?></td>

<?php if($cpa_enabled ==1 || $affiliate_enabled ==1){?>
<td style="width: 100px;"><?php echo $this->get_label('conversions');?></td>
<td style="width: 100px;"><?php echo $this->get_label('conversion ratio');?></td>
<?php }?>



<td style="width: 100px;"><?php echo $this->get_label('money spend');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>
<td style="width: 100px;"><?php echo $this->get_label('pubprofit');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>
<td style="width: 100px;"><?php echo $this->get_label('yourprofit');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>
<?php }?>
</tr>


<?php 
if($from_date !='')  // for custom date range
$statistics=$this->get_adv_date_range_timeperiod_statistics($from_date,$to_date,$uid);
else
$statistics=$this->get_advertiser_timeperiod_statistics($duration,$uid);

foreach($statistics as $key=>$value)
{
	$enterflag=0;

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


<?php if($cpc_enabled ==1){	?>
<tr class="row_data_tr">
<td rowspan="<?php echo $rowspan;?>"><?php echo $data;?></td>
<td class="border-left"><?php echo $this->get_label('ppc');?></td>
<td><?php echo $value['impression'];?></td>
<td><?php echo $value['click'];?></td>
<td><?php echo $value['ctr'];?></td>

	<?php if($cpa_enabled ==1 || $affiliate_enabled ==1){?>
	<td ><?php echo $this->get_label('na');?></td>
	<td ><?php echo $this->get_label('na');?></td>
	<?php }?>


<td><?php echo $this->get_number_format($value['money_spent']);?></td>
<td><?php echo $this->get_number_format($value['pub_profit']);?></td>
<td><?php echo $this->get_number_format(($value['money_spent']-$value['pub_profit']));?></td>
</tr>
<?php 
$enterflag=1;
}?>


<?php if($cpm_enabled ==1){?>

<tr class="row_data_tr">
<td <?php if($enterflag ==1){?>style="display: none;"<?php }?> <?php if($enterflag ==0){?>rowspan="<?php echo $rowspan;?>"<?php }?>><?php echo $data;?></td>
<td class="border-left"><?php echo $this->get_label('cpm');?></td>
<td><?php echo $value['cpm_impression'];?></td>
<td><?php echo $value['cpm_click'];?></td>
<td><?php echo $value['cpm_ctr'];?></td>

	<?php if($cpa_enabled ==1 || $affiliate_enabled ==1){?>
	<td ><?php echo $this->get_label('na');?></td>
	<td ><?php echo $this->get_label('na');?></td>
	<?php }?>


<td><?php echo $this->get_number_format($value['cpm_spend']);?></td>
<td><?php echo $this->get_number_format($value['cpm_profit']);?></td>
<td><?php echo $this->get_number_format(($value['cpm_spend']-$value['cpm_profit']));?></td>
</tr>
<?php 
$enterflag=1;
}?>




<?php if($cpa_enabled ==1){?>

<tr class="row_data_tr">
<td <?php if($enterflag ==1){?>style="display: none;"<?php }?> <?php if($enterflag ==0){?>rowspan="<?php echo $rowspan;?>"<?php }?>><?php echo $data;?></td>
<td class="border-left"><?php echo $this->get_label('cpa');?></td>
<td><?php echo $value['cpa_impression'];?></td>
<td><?php echo $value['cpa_click'];?></td>
<td><?php echo $value['cpa_ctr'];?></td>

	<td ><?php echo $value['cpa_conversion'];?></td>
	<td ><?php echo $value['cpa_ratio'];?></td>

<td><?php echo $this->get_number_format($value['cpa_spend']);?></td>
<td><?php echo $this->get_number_format($value['cpa_profit']);?></td>
<td><?php echo $this->get_number_format(($value['cpa_spend']-$value['cpa_profit']));?></td>
</tr>
<?php 
$enterflag=1;
}?>




<?php if($cpv_enabled ==1){?>

<tr class="row_data_tr">
<td <?php if($enterflag ==1){?>style="display: none;"<?php }?> <?php if($enterflag ==0){?>rowspan="<?php echo $rowspan;?>"<?php }?>><?php echo $data;?></td>
<td class="border-left"><?php echo $this->get_label('cpv');?></td>
<td><?php echo $value['cpv_impression'];?></td>
<td><?php echo $value['cpv_click'];?></td>
<td><?php echo $value['cpv_ctr'];?></td>

	<?php if($cpa_enabled ==1 || $affiliate_enabled ==1){?>
	<td ><?php echo $this->get_label('na');?></td>
	<td ><?php echo $this->get_label('na');?></td>
	<?php }?>


<td><?php echo $this->get_number_format($value['cpv_spend']);?></td>
<td><?php echo $this->get_number_format($value['cpv_profit']);?></td>
<td><?php echo $this->get_number_format(($value['cpv_spend']-$value['cpv_profit']));?></td>
</tr>
<?php 
$enterflag=1;
}?>





<?php if($pop_enabled ==1){?>

<tr class="row_data_tr">
<td <?php if($enterflag ==1){?>style="display: none;"<?php }?> <?php if($enterflag ==0){?>rowspan="<?php echo $rowspan;?>"<?php }?>><?php echo $data;?></td>
<td class="border-left"><?php echo $this->get_label('pop');?></td>
<td><?php echo $value['pop_impression'];?></td>
<td><?php echo $this->get_label('na');?></td>
<td><?php echo $this->get_label('na');?></td>

	<?php if($cpa_enabled ==1 || $affiliate_enabled ==1){?>
	<td ><?php echo $this->get_label('na');?></td>
	<td ><?php echo $this->get_label('na');?></td>
	<?php }?>


<td><?php echo $this->get_number_format($value['pop_spend']);?></td>
<td><?php echo $this->get_number_format($value['pop_profit']);?></td>
<td><?php echo $this->get_number_format(($value['pop_spend']-$value['pop_profit']));?></td>
</tr>
<?php 
$enterflag=1;
}?>





<?php if($affiliate_enabled ==1){?>

<tr class="row_data_tr">
<td <?php if($enterflag ==1){?>style="display: none;"<?php }?> <?php if($enterflag ==0){?>rowspan="<?php echo $rowspan;?>"<?php }?>><?php echo $data;?></td>
<td class="border-left"><?php echo $this->get_label('affiliate');?></td>
<td><?php echo $this->get_label('na');?></td>
<td><?php echo $value['affiliate_click'];?></td>
<td><?php echo $this->get_label('na');?></td>

	<td ><?php echo $value['affiliate_conversion'];?></td>
	<td ><?php echo $value['affiliate_ratio'];?></td>

<td><?php echo $this->get_number_format($value['affiliate_spend']);?></td>
<td><?php echo $this->get_number_format($value['affiliate_profit']);?></td>
<td><?php echo $this->get_number_format(($value['affiliate_spend']-$value['affiliate_profit']));?></td>
</tr>


<?php }?>



<?php }?>


	
</table>
</td></tr>	

</table>
</body>
</html>