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

<table  class="iframe_table" cellpadding="0" cellspacing="0" border="0"  >

<tr><td height="10px"></td></tr>


		
<tr><td colspan="5">		
 <div class="search_div">
<table class="search_div_table">	
	 
		<?php 
		$cpc_enabled=$this->get_addon_status('cpc_enabled');
		$cpm_enabled=$this->get_addon_status('cpm_enabled');
		$html_enabled=$this->get_addon_status('html_enabled');
		$cpa_enabled=$this->get_addon_status('cpa_enabled');
		$pop_enabled=$this->get_addon_status('pop-ads_enabled');
		$affiliate_enabled=$this->get_addon_status('affiliate-ads_enabled');
		$cpv_enabled=$this->get_addon_status('video-ads_enabled');
		


$uid=$this->get_variable('uid');
$duration=$this->get_variable('duration');

if($from_date =='' && $duration ==7)
	$duration=1;

$form1=$this->create_form();
$form1->start("overallstatistics",$this->make_url("user/puball/").$uid,"post");
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
		
<tr><td height="5px" colspan="5"></td></tr>		
<tr><td colspan="5">
<?php 

if($from_date !='')   // for custom date range
	$statistics=$this->get_pub_date_range_statistics($from_date,$to_date,$uid);
else
$statistics=$this->get_publisher_statistics($duration,$uid);

?>
<table class="overall" style="width: 100%;" cellpadding="0" cellspacing="0">


<tr class="pricing-head">
<td style="width: 160px;"></td>

<?php if($cpc_enabled ==1){?>
<td <?php if($cpm_enabled ==1 || $html_enabled ==1 || $cpa_enabled ==1 || $cpv_enabled ==1 || $pop_enabled ==1 || $affiliate_enabled ==1){?> style="width: 150px;"<?php }?>><?php echo $this->get_label('ppc');?></td>
<?php }?>

<?php if($cpm_enabled ==1 || $html_enabled ==1){?>
<td <?php if($cpa_enabled ==1 || $cpv_enabled ==1 || $pop_enabled ==1 || $affiliate_enabled ==1){?> style="width: 150px;"<?php }?>><?php echo $this->get_label('cpm');?></td>
<?php }?>

<?php if($cpa_enabled ==1){?>
<td <?php if($pop_enabled ==1 || $cpv_enabled ==1 || $affiliate_enabled ==1){?> style="width: 150px;"<?php }?>><?php echo $this->get_label('cpa');?></td>
<?php }?>

<?php if($cpv_enabled ==1){?>
<td <?php if($pop_enabled ==1 || $affiliate_enabled ==1){?> style="width: 150px;"<?php }?>><?php echo $this->get_label('cpv');?></td>
<?php }?>

<?php if($pop_enabled ==1){?>
<td <?php if($affiliate_enabled ==1){?> style="width: 150px;"<?php }?>><?php echo $this->get_label('pop');?></td>
<?php }?>

<?php if($affiliate_enabled ==1){?>
<td ><?php echo $this->get_label('affiliate');?></td>
<?php }?>
</tr>

<tr>
<td style="padding-left: 10px;"><?php echo $this->get_label('impressions');?></td>

<?php if($cpc_enabled ==1){?>
<td><?php echo $statistics['impression'];?></td>
<?php }?>

<?php if($cpm_enabled ==1 || $html_enabled ==1){

	
	$totimp=0;
	$totprofit=0;
	$totspend=0;
	if($cpm_enabled ==1)
	{
		$totimp=$statistics['cpm_impression'];
		$totprofit=$statistics['cpm_profit'];
		$totspend=$statistics['cpm_spend'];
		
	}
	
	if($html_enabled ==1)
	{
		$totimp=$totimp+$statistics['html_impression'];
		$totprofit=$totprofit+$statistics['html_profit'];
		$totspend=$totspend+$statistics['html_profit'];
	}
	?>
<td><?php echo $totimp;?></td>
<?php }?>


<?php if($cpa_enabled ==1){?>
<td><?php echo $statistics['cpa_impression'];?></td>
<?php }?>

<?php if($cpv_enabled ==1){?>
<td ><?php echo $statistics['cpv_impression'];?></td>
<?php }?>

<?php if($pop_enabled ==1){?>
<td><?php echo $statistics['pop_impression'];?></td>
<?php }?>

<?php if($affiliate_enabled ==1){?>
<td><?php echo $this->get_label('na');?></td>
<?php }?>
	
</tr>

<tr>
<td style="padding-left: 10px;"><?php echo $this->get_label('clicks');?></td>

<?php if($cpc_enabled ==1){?>
<td><?php echo $statistics['click'];?></td>
<?php }?>

<?php if($cpm_enabled ==1 || $html_enabled ==1){?>
<?php if($cpm_enabled ==1){?>
<td><?php echo $statistics['cpm_click'];?></td>
<?php }else{?>
<td><?php echo $this->get_label('na');?></td>
<?php }}?>

<?php if($cpa_enabled ==1){?>
<td><?php echo $statistics['cpa_click'];?></td>
<?php }?>

<?php if($cpv_enabled ==1){?>
<td ><?php echo $statistics['cpv_click'];?></td>
<?php }?>	

<?php if($pop_enabled ==1){?>
<td ><?php echo $this->get_label('na');?></td>
<?php }?>

<?php if($affiliate_enabled ==1){?>
<td><?php echo $statistics['affiliate_click'];?></td>
<?php }?>
	
<td></td>
</tr>

<tr>
<td style="padding-left: 10px;"><?php echo $this->get_label('ctr');?></td>

<?php if($cpc_enabled ==1){?>
<td><?php echo $statistics['ctr'];?></td>
<?php }?>

<?php if($cpm_enabled ==1 || $html_enabled ==1){?>
<?php if($cpm_enabled ==1){?>
<td ><?php echo $statistics['cpm_ctr'];?></td>
<?php }else{?>
<td><?php echo $this->get_label('na');?></td>
<?php }}?>


<?php if($cpa_enabled ==1){?>
<td><?php echo $statistics['cpa_ctr'];?></td>
<?php }?>

<?php if($cpv_enabled ==1){?>
<td ><?php echo $statistics['cpv_ctr'];?></td>
<?php }?>	

<?php if($pop_enabled ==1){?>
<td ><?php echo $this->get_label('na');?></td>
<?php }?>

<?php if($affiliate_enabled ==1){?>
<td><?php echo $this->get_label('na');?></td>
<?php }?>
</tr>





<?php if($cpa_enabled ==1 || $affiliate_enabled ==1){?>
	<tr>
	<td style="padding-left: 10px;"><?php echo $this->get_label('conversions');?></td>
	
	<?php if($cpc_enabled ==1){?>	
	<td ><?php echo $this->get_label('na');?></td>
	<?php }?>
	
	
	<?php if($cpm_enabled ==1 || $html_enabled ==1){?>
	<td ><?php echo $this->get_label('na');?></td>
	<?php }?>
	
	<?php if($cpa_enabled ==1){?>
	<td ><?php echo $statistics['cpa_conversion'];?></td>
	<?php }?>
	
	<?php if($cpv_enabled ==1){?>
	<td ><?php echo $this->get_label('na');?></td>
	<?php }?>	
	
	<?php if($pop_enabled ==1){?>
	<td ><?php echo $this->get_label('na');?></td>
	<?php }?>

	<?php if($affiliate_enabled ==1){?>
	<td ><?php echo $statistics['affiliate_conversion'];?></td>
	<?php }?>
	</tr>
	
	
	<tr>
	<td style="padding-left: 10px;"><?php echo $this->get_label('conversion ratio');?></td>
	
	<?php if($cpc_enabled ==1){?>	
	<td ><?php echo $this->get_label('na');?></td>
	<?php }?>
	
	<?php if($cpm_enabled ==1 || $html_enabled ==1){?>
	<td ><?php echo $this->get_label('na');?></td>
	<?php }?>
	
	<?php if($cpa_enabled ==1){?>
	<td ><?php echo $statistics['cpa_ratio'];?></td>
	<?php }?>
	
	<?php if($cpv_enabled ==1){?>
	<td ><?php echo $this->get_label('na');?></td>
	<?php }?>	
	
	<?php if($pop_enabled ==1){?>
	<td ><?php echo $this->get_label('na');?></td>
	<?php }?>

	<?php if($affiliate_enabled ==1){?>
	<td ><?php echo $statistics['affiliate_ratio'];?></td>
	<?php }?>
	</tr>
	<?php }?>

<tr>
<td style="padding-left: 10px;"><?php echo $this->get_label('money spend');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>

<?php if($cpc_enabled ==1){?>
<td><?php echo $this->get_number_format($statistics['money_spent']);?></td>
<?php }?>

<?php if($cpm_enabled ==1 || $html_enabled ==1){?>
<td><?php echo $this->get_number_format($totspend);?></td>
<?php }?>

<?php if($cpa_enabled ==1){?>
<td><?php echo $this->get_number_format($statistics['cpa_spend']);?></td>
<?php }?>

<?php if($cpv_enabled ==1){?>
<td ><?php echo $this->get_number_format($statistics['cpv_spend']);?></td>
<?php }?>	

<?php if($pop_enabled ==1){?>
<td><?php echo $this->get_number_format($statistics['pop_spend']);?></td>
<?php }?>

<?php if($affiliate_enabled ==1){?>
<td ><?php echo $this->get_number_format($statistics['affiliate_spend']);?></td>
<?php }?>
</tr>

<tr>
<td style="padding-left: 10px;"><?php echo $this->get_label('pubprofit');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>

<?php if($cpc_enabled ==1){?>
<td><?php echo $this->get_number_format($statistics['pub_profit']);?></td>
<?php }?>

<?php if($cpm_enabled ==1 || $html_enabled ==1){?>
<td><?php echo $this->get_number_format($totprofit);?></td>
<?php }?>

<?php if($cpa_enabled ==1){?>
<td><?php echo $this->get_number_format($statistics['cpa_profit']);?></td>
<?php }?>

<?php if($cpv_enabled ==1){?>
<td ><?php echo $this->get_number_format($statistics['cpv_profit']);?></td>
<?php }?>

<?php if($pop_enabled ==1){?>
<td><?php echo $this->get_number_format($statistics['pop_profit']);?></td>
<?php }?>

<?php if($affiliate_enabled ==1){?>
<td ><?php echo $this->get_number_format($statistics['affiliate_profit']);?></td>
<?php }?>
</tr>

<tr>
<td style="padding-left: 10px;"><?php echo $this->get_label('yourprofit');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>

<?php if($cpc_enabled ==1){?>
<td ><?php echo $this->get_number_format(($statistics['money_spent']-$statistics['pub_profit']));?></td>
<?php }?>

<?php if($cpm_enabled ==1 || $html_enabled ==1){?>
<td><?php echo $this->get_number_format($totspend-$totprofit);?></td>
<?php }?>

<?php if($cpa_enabled ==1){?>
<td><?php echo $this->get_number_format($statistics['cpa_spend']-$statistics['cpa_profit']);?></td>
<?php }?>

<?php if($cpv_enabled ==1){?>
<td ><?php echo $this->get_number_format($statistics['cpv_spend']-$statistics['cpv_profit']);?></td>
<?php }?>

<?php if($pop_enabled ==1){?>
<td><?php echo $this->get_number_format($statistics['pop_spend']-$statistics['pop_profit']);?></td>
<?php }?>

<?php if($affiliate_enabled ==1){?>
<td><?php echo $this->get_number_format($statistics['affiliate_spend']-$statistics['affiliate_profit']);?></td>
<?php }?>
</tr>

</table>
</td></tr>	

</table>
</body>
</html>