<?php 
$this->dispatch("layout/header/5/_51");
$number=$this->get_variable('number');

?>
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


<div class="sub_menu_main"><?php echo $this->get_label('advertisers statistics');?></div>

<?php $this->dispatch("links/links/11");?>


<table style="width: 100%;" cellpadding="0" cellspacing="0" >

<tr><td colspan="4">

 <div class="search_div">
 
<?php 
$form=$this->create_form();
$form->start("advstatistics",$this->make_url("report/advertisers"),"post");
?>  
 
<table class="search_div_table">
<?php 
$cpc_enabled=$this->get_addon_status('cpc_enabled');
$cpa_enabled=$this->get_addon_status('cpa_enabled');
$cpm_enabled=$this->get_addon_status('cpm_enabled');
$pop_enabled=$this->get_addon_status('pop-ads_enabled');
$affiliate_enabled=$this->get_addon_status('affiliate-ads_enabled');
$cpv_enabled=$this->get_addon_status('video-ads_enabled');


		$pop_addon_usage = intval(Configuration::get_instance()->read('pop_addon_usage'));
		
		if($pop_addon_usage == 1)
		$pop_enabled = 0;


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




$duration=$this->get_variable('duration');
$adv=$this->get_variable('adv');



if($from_date =='' && $duration ==7)
$duration=1;
?>  

<tr>
<td> 
<select name="adv" id="adv" style="width: 150px;">
<option value="0" <?php if($adv==0) echo "selected";?>><?php echo $this->get_label('all advertisers');?></option>
<?php 
$res1=$this->get_result('res1');
foreach($res1 as $key1=>$value1)
{
?>
<option value="<?php echo $value1['id'];?>" <?php if($adv==$value1['id']) echo "selected";?>><?php echo $value1['username'];?></option>
<?php 
}
?>
</select>
  
&nbsp;
</td>  
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


<input type="text" readonly="readonly" name="from_date" id="from_date" value="<?php echo $from_date;?>" placeholder="From Date" size="5" />  

<input type="text" readonly="readonly" name="to_date" id="to_date" value="<?php echo $to_date;?>" placeholder="To Date" size="5" />
</td>
  
<td>&nbsp;&nbsp; <input type="submit" name="search" value="<?php echo $this->get_label('go');?>" /></td>
</tr>
</table>
<?php $form->end(); ?> 
</div>

</td></tr>


<tr><td colspan="4" style="height: 10px;"></td></tr>

<tr><td colspan="3">


<table class="data_table" cellpadding="0" cellspacing="0">
<tr class="row_heading_tr">
<td style="width: 180px;"><?php echo $this->get_label('username');?></td>

<?php if($rowspan >0){?>
<td style="width: 80px;"><?php echo $this->get_label('type');?></td>
<td><?php echo $this->get_label('impressions');?></td>
<td><?php echo $this->get_label('clicks');?></td>

<?php if($cpa_enabled ==1 || $affiliate_enabled ==1){?>
<td style="width: 100px;"><?php echo $this->get_label('conversions');?></td>
<td style="width: 100px;"><?php echo $this->get_label('conversion ratio');?></td>
<?php }?>

<td><?php echo $this->get_label('money spend');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>
<td><?php echo $this->get_label('pubprofit');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>
<td><?php echo $this->get_label('balance');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>
<?php }?>
</tr>

<?php 

if($number==0)
{
?>	
<tr><td colspan="9" height="30px">&nbsp;<?php echo $this->get_label('no users found');?></td></tr>	
<?php 
}



$res=$this->get_result('res');
foreach($res as $key=>$value)
{
	$enterflag=0;			
	
	$uid=$value['id'];

	if($from_date !='')  // for custom date range
	$statistics=$this->get_adv_date_range_statistics($from_date,$to_date,$uid,0,0,0);
	else
	$statistics=$this->get_advertiser_statistics($duration,$uid,0,0,0);
	
	?>
	
<?php if($cpc_enabled ==1){	?>	
<tr class="row_data_tr">
<td rowspan="<?php echo $rowspan;?>"><a href="<?php echo $this->make_url("user/profile/".$uid."/0");?>"><?php echo $value['username'];?></a></td>
<td class="border-left"><?php echo $this->get_label('ppc');?></td>
<td ><?php echo $statistics['impression'];?></td>
<td ><?php echo $statistics['click'];?></td>

	<?php if($cpa_enabled ==1 || $affiliate_enabled ==1){?>
	<td ><?php echo $this->get_label('na');?></td>
	<td ><?php echo $this->get_label('na');?></td>
	<?php }?>


<td ><?php echo $this->get_number_format($statistics['money_spent']);?></td>
<td ><?php echo $this->get_number_format($statistics['pub_profit']);?></td>
<td ><?php echo $this->get_number_format(($statistics['money_spent']-$statistics['pub_profit']));?></td>
</tr>
	<?php 
	$enterflag=1;
	}?>


<?php if($cpm_enabled ==1){?>
<tr class="row_data_tr">
<td <?php if($enterflag ==1){?>style="display: none;"<?php }?> <?php if($enterflag ==0){?>rowspan="<?php echo $rowspan;?>"<?php }?>><a href="<?php echo $this->make_url("user/profile/".$uid."/0");?>"><?php echo $value['username'];?></a></td>
<td class="border-left"><?php echo $this->get_label('cpm');?></td>
<td ><?php echo $statistics['cpm_impression'];?></td>
<td ><?php echo $statistics['cpm_click'];?></td>

	<?php if($cpa_enabled ==1 || $affiliate_enabled ==1){?>
	<td ><?php echo $this->get_label('na');?></td>
	<td ><?php echo $this->get_label('na');?></td>
	<?php }?>

<td ><?php echo $this->get_number_format($statistics['cpm_spend']);?></td>
<td ><?php echo $this->get_number_format($statistics['cpm_profit']);?></td>
<td ><?php echo $this->get_number_format(($statistics['cpm_spend']-$statistics['cpm_profit']));?></td>

</tr>
<?php 
$enterflag=1;
}?>


<?php if($cpa_enabled ==1){?>
<tr class="row_data_tr">
<td <?php if($enterflag ==1){?>style="display: none;"<?php }?> <?php if($enterflag ==0){?>rowspan="<?php echo $rowspan;?>"<?php }?>><a href="<?php echo $this->make_url("user/profile/".$uid."/0");?>"><?php echo $value['username'];?></a></td>
<td class="border-left"><?php echo $this->get_label('cpa');?></td>
<td ><?php echo $statistics['cpa_impression'];?></td>
<td ><?php echo $statistics['cpa_click'];?></td>

	<td ><?php echo $statistics['cpa_conversion'];?></td>
	<td ><?php echo $statistics['cpa_ratio'];?></td>

<td ><?php echo $this->get_number_format($statistics['cpa_spend']);?></td>
<td ><?php echo $this->get_number_format($statistics['cpa_profit']);?></td>
<td ><?php echo $this->get_number_format(($statistics['cpa_spend']-$statistics['cpa_profit']));?></td>

</tr>
<?php 
$enterflag=1;
}?>


<?php if($cpv_enabled ==1){?>
<tr class="row_data_tr">
<td <?php if($enterflag ==1){?>style="display: none;"<?php }?> <?php if($enterflag ==0){?>rowspan="<?php echo $rowspan;?>"<?php }?>><a href="<?php echo $this->make_url("user/profile/".$uid."/0");?>"><?php echo $value['username'];?></a></td>
<td class="border-left"><?php echo $this->get_label('cpv');?></td>
<td ><?php echo $statistics['cpv_impression'];?></td>
<td ><?php echo $statistics['cpv_click'];?></td>

	<?php if($cpa_enabled ==1 || $affiliate_enabled ==1){?>
	<td ><?php echo $this->get_label('na');?></td>
	<td ><?php echo $this->get_label('na');?></td>
	<?php }?>

<td ><?php echo $this->get_number_format($statistics['cpv_spend']);?></td>
<td ><?php echo $this->get_number_format($statistics['cpv_profit']);?></td>
<td ><?php echo $this->get_number_format(($statistics['cpv_spend']-$statistics['cpv_profit']));?></td>

</tr>
<?php 
$enterflag=1;
}?>


<?php if($pop_enabled ==1){?>
<tr class="row_data_tr">
<td <?php if($enterflag ==1){?>style="display: none;"<?php }?> <?php if($enterflag ==0){?>rowspan="<?php echo $rowspan;?>"<?php }?>><a href="<?php echo $this->make_url("user/profile/".$uid."/0");?>"><?php echo $value['username'];?></a></td>
<td class="border-left"><?php echo $this->get_label('pop');?></td>
<td ><?php echo $statistics['pop_impression'];?></td>
<td ><?php echo $this->get_label('na');?></td>

	<?php if($cpa_enabled ==1 || $affiliate_enabled ==1){?>
	<td ><?php echo $this->get_label('na');?></td>
	<td ><?php echo $this->get_label('na');?></td>
	<?php }?>

<td ><?php echo $this->get_number_format($statistics['pop_spend']);?></td>
<td ><?php echo $this->get_number_format($statistics['pop_profit']);?></td>
<td ><?php echo $this->get_number_format(($statistics['pop_spend']-$statistics['pop_profit']));?></td>

</tr>
<?php 
$enterflag=1;
}?>






<?php if($affiliate_enabled ==1){?>
<tr class="row_data_tr">
<td <?php if($enterflag ==1){?>style="display: none;"<?php }?> <?php if($enterflag ==0){?>rowspan="<?php echo $rowspan;?>"<?php }?>><a href="<?php echo $this->make_url("user/profile/".$uid."/0");?>"><?php echo $value['username'];?></a></td>
<td class="border-left"><?php echo $this->get_label('affiliate');?></td>
<td ><?php echo $this->get_label('na');?></td>
<td ><?php echo $statistics['affiliate_click'];?></td>

	<td ><?php echo $statistics['affiliate_conversion'];?></td>
	<td ><?php echo $statistics['affiliate_ratio'];?></td>

<td ><?php echo $this->get_number_format($statistics['affiliate_spend']);?></td>
<td ><?php echo $this->get_number_format($statistics['affiliate_profit']);?></td>
<td ><?php echo $this->get_number_format(($statistics['affiliate_spend']-$statistics['affiliate_profit']));?></td>

</tr>
<?php }?>


<?php 
}?>		

<tr><td colspan="9" align="center"><?php echo $this->get_variable('pagination');?></td></tr>
</table>
</td></tr></table>
<?php $this->dispatch("layout/footer");?>