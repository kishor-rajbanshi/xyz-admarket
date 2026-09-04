<div class="report_main_table_data1">
<?php 
$uid=$this->get_variable('uid');
$duration=$this->get_variable('duration');
$from_date=$this->get_variable('from_date');
$to_date=$this->get_variable('to_date');

$cpc_enabled=$this->get_addon_status('cpc_enabled');
$cpm_enabled=$this->get_addon_status('cpm_enabled');
$html_enabled=$this->get_addon_status('html_enabled');
$cpa_enabled=$this->get_addon_status('cpa_enabled');
$pop_enabled=$this->get_addon_status("pop-ads_enabled");
$affiliate_enabled=$this->get_addon_status("affiliate-ads_enabled");
$sponsored_enabled=$this->get_addon_status('sponsored_enabled');
$cpv_enabled=$this->get_addon_status('video-ads_enabled');



	 $rowspan=0;
	
	 if($cpc_enabled ==1)
	 $rowspan=$rowspan+1;
	 
	 if($cpm_enabled ==1 || $html_enabled ==1)
	 $rowspan=$rowspan+1;
	 
	 if($cpa_enabled ==1)
	 $rowspan=$rowspan+1;
	 
	 if($pop_enabled ==1)
	 $rowspan=$rowspan+1;
	 
	 if($affiliate_enabled ==1)
	 $rowspan=$rowspan+1;
	 
     if($cpv_enabled ==1)
 	 $rowspan=$rowspan+1;  	 
?>




<table id="table-desktop2" class="data_table" cellpadding="0" cellspacing="0">
<tr class="data_table_head">

<td style="width: 100px;"><?php echo $this->get_label('country');?></td>

<?php if($rowspan >0){?>
<td style="width: 80px;"><?php echo $this->get_label('type');?></td>
<td style="width: 100px;"><?php echo $this->get_label('impressions');?></td>
<td style="width: 60px;"><?php echo $this->get_label('clicks');?></td>
<td style="width: 60px;"><?php echo $this->get_label('ctr');?></td>
<td style="width: 60px;"><?php echo $this->get_label('ecpm');?></td>

<?php if($cpa_enabled ==1 || $affiliate_enabled ==1){?>
<td style="width: 80px;"><?php echo $this->get_label('conversions');?></td>
<td style="width: 80px;"><?php echo $this->get_label('conversion ratio');?></td>
<?php }?>

<td style="width: 70px;"><?php echo $this->get_label('profit');?> &nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>
<?php }?>
</tr>

<?php 

$results=$this->get_array("results");
$number=count($results);


if($number==0){?>	
<tr class="data_table_message"><td colspan="9"><?php echo $this->get_label('no records found');?></td></tr>	
<?php }

foreach($results as $key=>$value)
{
if($value[0] !='')
{
	
	$enterflag=0;
			
		if($from_date !='')   // for custom date range
		$statistics=$this->get_pub_date_range_statistics($from_date,$to_date,$uid,0,0,0,1,$value[0]);
		else
		$statistics=$this->get_publisher_statistics($duration,$uid,0,0,0,1,$value[0]);
			
		
if($cpc_enabled ==1){	?>	
<tr class="data_table_content">
<td rowspan="<?php echo $rowspan;?>"><img src="<?php echo BASE.'/images/flags/'.strtolower($value[0]).'.png';?>" />&nbsp;<?php echo $value[1];?></td>
<td ><?php echo $this->get_label('ppc');?></td>

<td ><?php echo $statistics['impression'];?></td>
<td ><?php echo $statistics['click'];?></td>
<td ><?php echo $statistics['ctr'];?></td>
<td ><?php echo $this->get_money_format($statistics['ecpm']);?></td>


<?php if($cpa_enabled ==1 || $affiliate_enabled ==1){?>
<td ><?php echo $this->get_label('na');?></td>
<td ><?php echo $this->get_label('na');?></td>
<?php }?>
<td ><?php echo $this->get_number_format($statistics['pub_profit']);?></td>
</tr>
<?php 
$enterflag=1;
}?>

<?php if($cpm_enabled ==1 || $html_enabled ==1){

	$totimp=0;
	$totprofit=0;
	
	if($cpm_enabled ==1)
	{
		$totimp=$statistics['cpm_impression'];
		$totprofit=$statistics['cpm_profit'];
	}
	
	if($html_enabled ==1)
	{
		$totimp=$totimp+$statistics['html_impression'];
		$totprofit=$totprofit+$statistics['html_profit'];
	}
		
	
	
	?>
<tr class="data_table_content">
<td <?php if($enterflag ==1){?>style="display: none;"<?php }?> <?php if($enterflag ==0){?>rowspan="<?php echo $rowspan;?>"<?php }?>><img src="<?php echo BASE.'/images/flags/'.strtolower($value[0]).'.png';?>" />&nbsp;<?php echo $value[1];?></td>
<td ><?php echo $this->get_label('cpm');?></td>
<td><?php echo $totimp;?></td>

<?php if($cpm_enabled ==1){?>
<td ><?php echo $statistics['cpm_click'];?></td>
<td ><?php echo $statistics['cpm_ctr'];?></td>
<?php }else{?>
<td><?php echo $this->get_label('na');?></td>
<td><?php echo $this->get_label('na');?></td>
<?php }?>

<td><?php echo $this->get_label('na');?></td>


	<?php if($cpa_enabled ==1 || $affiliate_enabled ==1){?>
	<td ><?php echo $this->get_label('na');?></td>
	<td ><?php echo $this->get_label('na');?></td>
	<?php }?>


<td ><?php echo $this->get_number_format($totprofit);?></td>
</tr>
<?php 
$enterflag=1;
}?>


<?php if($cpa_enabled ==1){?>
<tr class="data_table_content">
<td <?php if($enterflag ==1){?>style="display: none;"<?php }?> <?php if($enterflag ==0){?>rowspan="<?php echo $rowspan;?>"<?php }?>><img src="<?php echo BASE.'/images/flags/'.strtolower($value[0]).'.png';?>" />&nbsp;<?php echo $value[1];?></td>
<td ><?php echo $this->get_label('cpa');?></td>
<td ><?php echo $statistics['cpa_impression'];?></td>
<td ><?php echo $statistics['cpa_click'];?></td>
<td ><?php echo $statistics['cpa_ctr'];?></td>
<td ><?php echo $this->get_label('na');?></td>
<td ><?php echo $statistics['cpa_conversion'];?></td>
<td ><?php echo $statistics['cpa_ratio'];?></td>
<td ><?php echo $this->get_number_format($statistics['cpa_profit']);?></td>
</tr>
<?php 
$enterflag=1;
}?>


<?php if($cpv_enabled ==1){	?>
<tr class="data_table_content">
<td <?php if($enterflag ==1){?>style="display: none;"<?php }?> <?php if($enterflag ==0){?>rowspan="<?php echo $rowspan;?>"<?php }?>><img src="<?php echo BASE.'/images/flags/'.strtolower($value[0]).'.png';?>" />&nbsp;<?php echo $value[1];?></td>
<td ><?php echo $this->get_label('cpv');?></td>
<td><?php echo $statistics['cpv_impression'];?></td>
<td ><?php echo $statistics['cpv_click'];?></td>
<td ><?php echo $statistics['cpv_ctr'];?></td>
<td><?php echo $this->get_label('na');?></td>

<?php if($cpa_enabled ==1 || $affiliate_enabled ==1){?>
<td ><?php echo $this->get_label('na');?></td>
<td ><?php echo $this->get_label('na');?></td>
<?php }?>

<td ><?php echo $this->get_number_format($statistics['cpv_profit']);?></td>
</tr>
<?php 
$enterflag=1;
}?>




	<?php if($pop_enabled ==1){?>
	<tr class="data_table_content">
	<td <?php if($enterflag ==1){?>style="display: none;"<?php }?> <?php if($enterflag ==0){?>rowspan="<?php echo $rowspan;?>"<?php }?>><img src="<?php echo BASE.'/images/flags/'.strtolower($value[0]).'.png';?>" />&nbsp;<?php echo $value[1];?></td>
	<td><?php echo $this->get_label('pop');?></td>
	<td ><?php echo $statistics['pop_impression'];?></td>
	<td ><?php echo $this->get_label('na');?></td>
	<td ><?php echo $this->get_label('na');?></td>
	<td ><?php echo $this->get_label('na');?></td>
	<?php if($cpa_enabled ==1 || $affiliate_enabled ==1){?>
	<td ><?php echo $this->get_label('na');?></td>
	<td ><?php echo $this->get_label('na');?></td>
	<?php }?>
	<td ><bdi><?php echo $this->get_number_format($statistics['pop_profit']);?></bdi></td>
	</tr>
	<?php 
	$enterflag=1;
	}?>




	<?php 
	if($affiliate_enabled ==1){?>
	<tr class="data_table_content">
	<td <?php if($enterflag ==1){?>style="display: none;"<?php }?> <?php if($enterflag ==0){?>rowspan="<?php echo $rowspan;?>"<?php }?>><img src="<?php echo BASE.'/images/flags/'.strtolower($value[0]).'.png';?>" />&nbsp;<?php echo $value[1];?></td>
	
	<td><?php echo $this->get_label('affiliate');?></td>
	<td ><?php echo $this->get_label('na');?></td>
	<td ><?php echo $statistics['affiliate_click'];?></td>
	<td ><?php echo $this->get_label('na');?></td>
	<td ><?php echo $this->get_label('na');?></td>
	<td ><?php echo $statistics['affiliate_conversion'];?></td>
	<td ><?php echo $statistics['affiliate_ratio'];?></td>
	<td ><bdi><?php echo $this->get_number_format($statistics['affiliate_profit']);?></bdi></td>
	</tr>
	<?php }?>
<?php }}?>	
</table>
<br/>
</div>