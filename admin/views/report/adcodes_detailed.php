<script type="text/javascript">
function show_pub_stat(id)
{

	$('.showadustatclass').hide();
	$('.adustatclass').removeClass('tab-selection');
	
	$('#showstat'+id).show();
	$('#adustat'+id).addClass('tab-selection');

	$('#tab').val(id);
}


</script>	
<?php 
$uid=$this->get_variable('uid');

 
if($uid==0)
$this->dispatch("layout/header/5/_54");
else
$this->dispatch("layout/header/5/_55");

$cpc_enabled=$this->get_addon_status('cpc_enabled');
$cpm_enabled=$this->get_addon_status('cpm_enabled');
$cpa_enabled=$this->get_addon_status('cpa_enabled');
$html_enabled=$this->get_addon_status('html_enabled');	
$pop_enabled=$this->get_addon_status('pop-ads_enabled');
$cpv_enabled=$this->get_addon_status('video-ads_enabled');





$aduid=$this->get_variable('aduid');
$duration=$this->get_variable('duration');

$name=$this->get_variable('name');
$tab=$this->get_variable('tab');
$username=$this->get_variable('username');
$adpricing=$this->get_variable('adpricing');

$display_type=$this->get_adunit_preference_value($aduid);

$pop_addon_usage = intval(Configuration::get_instance()->read('pop_addon_usage'));



$adcode_type 	= $this->get_adcode_type($aduid);

if($adcode_type == 9 && $pop_addon_usage ==1)
{
	$cpc_enabled 	= 0;
	$html_enabled	= 0;
}
	
	
$cpm_data_enabled=0;
	
if($cpm_enabled ==1 || $html_enabled ==1)	
$cpm_data_enabled=1;	
		

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

<div class="sub_menu_main"><?php echo $this->get_label('detailed statistics of adunit',array('x'=>'','y'=>$name))." ";?></div>

<?php 
if($uid==0)
$this->dispatch("links/links/21");
else
$this->dispatch("links/links/29");
?>


<table style="width: 100%;"  cellpadding="0" cellspacing="0">
<tr><td colspan="6" style="height: 30px;"><?php echo $this->get_label('created by')." ";?><?php if($uid >0){?><a href="<?php echo $this->make_url("user/profile/".$uid."/0");?>"><?php echo $username;?></a><?php }else{ echo $username;}?></td></tr>
<tr><td colspan="6" >	

<div class="search_div">
<?php  
$form1=$this->create_form();
$form1->start("adunitallstatistics",$this->make_url("report/adcodes_detailed/".$aduid),"post");
?>

<table class="search_div_table">	
<?php 
if($from_date =='' && $duration ==7)
$duration=1;
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
&nbsp;&nbsp;
<input type="text" readonly="readonly" name="from_date" id="from_date" value="<?php echo $from_date;?>" placeholder="From Date" size="8" />  
&nbsp;
<input type="text" readonly="readonly" name="to_date" id="to_date" value="<?php echo $to_date;?>" placeholder="To Date" size="8" />
&nbsp;&nbsp;
</td>
<td>&nbsp;&nbsp; 
<input type="hidden" name="tab" id="tab" value="1"/>
<input type="submit" name="search" value="<?php echo $this->get_label('go');?>" /></td>
</tr>
<tr><td colspan="4" style="height: 10px;"></td></tr>
</table>
<?php $form1->end(); ?> 	

</div>
</td></tr>	

<tr><td colspan="4" style="height: 10px;"></td></tr>


<tr><td colspan="4">
<table style="width: 100%;" >

  <tr class="statistics_header">
    <td onclick="show_pub_stat(1);" id="adustat1" class="adustatclass" style="width: 150px;"><?php echo $this->get_label('overall');?></td>
    <td onclick="show_pub_stat(2);" id="adustat2" class="adustatclass" style="width: 150px;"><?php echo $this->get_label('time based reports');?></td>
    <td></td>
  </tr>


   <tr id="showstat1" class="showadustatclass statistics_tr">
   
  <td colspan="6" class="statistics_td"   style="padding: 5px;">
<?php 

if($from_date !='')   // for custom date range
	$statistics=$this->get_pub_date_range_statistics($from_date,$to_date,$uid,$aduid);
else
	$statistics=$this->get_publisher_statistics($duration,$uid,$aduid);
?>
 
 
 
<table  style="width: 100%;" cellpadding="0" cellspacing="0">
<tr class="no_border" style="background-color: #CCCCCC;">
<td style="width: 160px;height: 30px;"></td>
<td  <?php if($display_type ==4 && (($cpc_enabled ==1 && $cpm_data_enabled ==1) || ($cpc_enabled ==1 && $cpa_enabled ==1) || ($cpm_data_enabled ==1 && $cpa_enabled ==1))){?>style="width: 150px;"<?php }?>>
<?php 
if(($display_type ==0 || $display_type ==4) && $cpc_enabled ==1)
echo $this->get_label('ppc');
else if(($display_type ==1 || $display_type ==4) && $cpm_data_enabled ==1)
echo $this->get_label('cpm');
else if(($display_type ==6 || $display_type ==4) && $cpa_enabled ==1)
echo $this->get_label('cpa');
else if($display_type ==9)  
echo $this->get_label('pop');
else if($display_type ==12)  
echo $this->get_label('affiliate');
else if($display_type ==13)  
echo $this->get_label('cpv');
?> 
</td> 

<?php if($display_type ==4 && $cpm_data_enabled ==1 && $cpc_enabled ==1){?>
<td <?php if($cpa_enabled ==1){?>style="width: 150px;"<?php }?>><?php echo $this->get_label('cpm');?></td>
<?php }?>

<?php if($display_type ==4 && $cpa_enabled ==1 && ($cpc_enabled ==1 || $cpm_data_enabled ==1)){?>
<td><?php echo $this->get_label('cpa');?></td>
<?php }?>

<td></td>
</tr>


<?php if($display_type !=12){?>
<tr class="no_border">
<td style="height:30px;padding-left: 10px;"><?php echo $this->get_label('impressions');?></td>

<?php 
	$totimp=0;
	$totprofit=0;
	$totspend=0;

if($cpm_data_enabled ==1)
{
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
}?>

<td><?php 
if(($display_type ==0 || $display_type ==4) && $cpc_enabled ==1)
echo $statistics['impression'];
else if(($display_type ==1 || $display_type ==4) && $cpm_data_enabled ==1) 
echo $totimp;
else if(($display_type ==6 || $display_type ==4) && $cpa_enabled ==1)
echo $statistics['cpa_impression'];
else if($display_type ==9)
echo $statistics['pop_impression'];
else if($display_type ==13)
echo $statistics['cpv_impression'];
?></td>

<?php if($display_type ==4 && $cpm_data_enabled ==1 && $cpc_enabled ==1){?>
<td><?php echo $totimp;?></td>
<?php }?>

<?php if($display_type ==4 && $cpa_enabled ==1 && ($cpc_enabled ==1 || $cpm_data_enabled ==1)){?>
<td><?php echo $statistics['cpa_impression'];?></td>
<?php }?>

<td></td>
</tr>
<?php }?>




<?php if($display_type !=9){?>

<?php if($display_type !=1 || ($display_type ==1 && $cpm_enabled ==1)){?>


<tr class="no_border">
<td style="height:30px;padding-left: 10px;"><?php echo $this->get_label('clicks');?></td>
<td>

<?php 
if(($display_type ==0 || $display_type ==4) && $cpc_enabled ==1)
echo $statistics['click'];
else if(($display_type ==1 || $display_type ==4) && $cpm_enabled ==1) 
echo $statistics['cpm_click'];
else if(($display_type ==1 || $display_type ==4) && $cpm_enabled ==0) 
echo $this->get_label('na');
else if(($display_type ==6 || $display_type ==4) && $cpa_enabled ==1)
echo $statistics['cpa_click'];
else if($display_type ==9)
echo $this->get_label('na');
else if($display_type ==12)
echo $statistics['affiliate_click'];
else if($display_type ==13)
echo $statistics['cpv_click'];
?>
</td>


<?php if($display_type ==4 && $cpm_data_enabled ==1 && $cpc_enabled ==1){?>
	<td>
	<?php 
		if($cpm_enabled ==1)
		echo $statistics['cpm_click'];
		else 
		echo $this->get_label('na');
		?></td>
	<?php }?>
	
	<?php if($display_type ==4 && $cpa_enabled ==1 && ($cpc_enabled ==1 || $cpm_data_enabled ==1)){?>
	<td><?php echo $statistics['cpa_click'];?></td>
	<?php }?>		
	

<td></td>
</tr>


<?php if($display_type !=12){?>
<tr class="no_border">
<td style="height:30px;padding-left: 10px;"><?php echo $this->get_label('ctr');?></td>

<td>
<?php 
if(($display_type ==0 || $display_type ==4) && $cpc_enabled ==1)
echo $statistics['ctr'];
else if(($display_type ==1 || $display_type ==4) && $cpm_enabled ==1) 
echo $statistics['cpm_ctr'];
else if(($display_type ==1 || $display_type ==4) && $cpm_enabled ==0) 
echo $this->get_label('na');
else if(($display_type ==6 || $display_type ==4) && $cpa_enabled ==1)
echo $statistics['cpa_ctr'];
else if($display_type ==9)
echo $this->get_label('na');
else if($display_type ==13)
echo $statistics['cpv_ctr'];
?>
</td>


	<?php if($display_type ==4 && $cpm_data_enabled ==1 && $cpc_enabled ==1){?>
	<td>
	<?php 
		if($cpm_enabled ==1)
		echo $statistics['cpm_ctr'];
		else 
		echo $this->get_label('na');
		?></td>
	<?php }?>
	
	<?php if($display_type ==4 && $cpa_enabled ==1 && ($cpc_enabled ==1 || $cpm_data_enabled ==1)){?>
	<td><?php echo $statistics['cpa_ctr'];?></td>
	<?php }?>		

<td></td>
</tr>
<?php }}}?>






	<?php if(($display_type ==4 || $display_type ==6) && $cpa_enabled ==1){?>
	<tr class="no_border">
	<td style="height:30px;padding-left: 10px;"><?php echo $this->get_label('conversions');?></td>
	
	<?php if($display_type ==4 && $cpc_enabled ==1){?>
	<td ><?php echo $this->get_label('na');?></td>
	<?php }?>	
	
	<?php if($display_type ==4 && $cpm_data_enabled ==1){?>
	<td ><?php echo $this->get_label('na');?></td>
	<?php }?>	
	
	<td ><?php echo $statistics['cpa_conversion'];?></td>
	<td></td>
	</tr>
	
	
	<tr class="no_border">
	<td style="height:30px;padding-left: 10px;"><?php echo $this->get_label('conversion ratio');?></td>
	
	<?php if($display_type ==4 && $cpc_enabled ==1){?>
	<td ><?php echo $this->get_label('na');?></td>
	<?php }?>	
	
	<?php if($display_type ==4 && $cpm_data_enabled ==1){?>
	<td ><?php echo $this->get_label('na');?></td>
	<?php }?>
		
	<td ><?php echo $statistics['cpa_ratio'];?></td>
	<td></td>
	</tr>
	<?php }?>



	<?php if($display_type ==12 && $affiliate_enabled ==1){?>
	<tr class="no_border">
	<td style="height:30px;padding-left: 10px;"><?php echo $this->get_label('conversions');?></td>
	<td ><?php echo $statistics['affiliate_conversion'];?></td>
	<td></td>
	</tr>
	
	
	<tr class="no_border">
	<td style="height:30px;padding-left: 10px;"><?php echo $this->get_label('conversion ratio');?></td>
	<td ><?php echo $statistics['affiliate_ratio'];?></td>
	<td></td>
	</tr>
	<?php }?>









<tr class="no_border">
<td style="height:30px;padding-left: 10px;"><?php echo $this->get_label('money spend');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>

<td>

<?php 
if(($display_type ==0 || $display_type ==4) && $cpc_enabled ==1)
echo $this->get_number_format($statistics['money_spent']);
else if(($display_type ==1 || $display_type ==4) && $cpm_data_enabled ==1) 
echo $this->get_number_format($totspend);
else if(($display_type ==6 || $display_type ==4) && $cpa_enabled ==1)
echo $this->get_number_format($statistics['cpa_spend']);
else if($display_type ==9)
echo $this->get_number_format($statistics['pop_spend']);
else if($display_type ==12)
echo $this->get_number_format($statistics['affiliate_spend']);
else if($display_type ==13)
echo $this->get_number_format($statistics['cpv_spend']);

?>
</td>


	<?php if($display_type ==4 && $cpm_data_enabled ==1 && $cpc_enabled ==1){?>
	<td><?php echo $this->get_number_format($totspend);?></td>
	<?php }?>
	
	<?php if($display_type ==4 && $cpa_enabled ==1 && ($cpc_enabled ==1 || $cpm_data_enabled ==1)){?>
	<td ><?php echo $this->get_number_format($statistics['cpa_spend']);?></td>
	<?php }?>	

<td></td>

</tr>



<?php if($display_type !=1 || ($display_type ==1 && $cpm_enabled ==1)){?>


<?php if($uid >0){?>

<tr class="no_border">
<td style="height:30px;padding-left: 10px;"><?php echo $this->get_label('pubprofit');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>

<td>
<?php 

if(($display_type ==0 || $display_type ==4) && $cpc_enabled ==1)
echo $this->get_number_format($statistics['pub_profit']);
else if(($display_type ==1 || $display_type ==4) && $cpm_enabled ==1) 
echo $this->get_number_format($totprofit);
else if(($display_type ==1 || $display_type ==4) && $cpm_enabled ==0) 
echo $this->get_label('na');
else if(($display_type ==6 || $display_type ==4) && $cpa_enabled ==1)
echo $this->get_number_format($statistics['cpa_profit']);
else if($display_type ==9)
echo $this->get_number_format($statistics['pop_profit']);
else if($display_type ==12)
echo $this->get_number_format($statistics['affiliate_profit']);
else if($display_type ==13)
echo $this->get_number_format($statistics['cpv_profit']);

?>
</td>


	<?php if($display_type ==4 && $cpm_data_enabled ==1 && $cpc_enabled ==1){?>
	<td><?php 
	if($cpm_enabled ==1)
	echo $this->get_number_format($totprofit);
	else
	echo $this->get_label('na');
	
	?></td>
	<?php }?>
	
<?php if($display_type ==4 && $cpa_enabled ==1 && ($cpc_enabled ==1 || $cpm_data_enabled ==1)){?>
<td ><?php echo $this->get_number_format($statistics['cpa_profit']);?></td>
<?php }?>	
	
	

<td></td>
</tr>

<?php }?>

<tr class="no_border">
<td style="height:30px;padding-left: 10px;"><?php echo $this->get_label('yourprofit');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>
<td >

<?php if($uid >0){?>
<?php 
if(($display_type ==0 || $display_type ==4) && $cpc_enabled ==1)
echo $this->get_number_format(($statistics['money_spent']-$statistics['pub_profit']));
else if(($display_type ==1 || $display_type ==4) && $cpm_enabled ==1) 
echo $this->get_number_format($totspend-$totprofit);
else if(($display_type ==1 || $display_type ==4) && $cpm_enabled ==0) 
echo $this->get_label('na');
else if(($display_type ==6 || $display_type ==4) && $cpa_enabled ==1)
echo $this->get_number_format($statistics['cpa_spend']-$statistics['cpa_profit']);
else if($display_type ==9)
echo $this->get_number_format($statistics['pop_spend']-$statistics['pop_profit']);
else if($display_type ==12)
echo $this->get_number_format($statistics['affiliate_spend']-$statistics['affiliate_profit']);
else if($display_type ==13)
echo $this->get_number_format($statistics['cpv_spend']-$statistics['cpv_profit']);

?>
<?php }else{?>

<?php 
if(($display_type ==0 || $display_type ==4) && $cpc_enabled ==1)
echo $this->get_number_format(($statistics['money_spent']));
else if(($display_type ==1 || $display_type ==4) && $cpm_enabled ==1) 
echo $this->get_number_format($totspend);
else if(($display_type ==1 || $display_type ==4) && $cpm_enabled ==0) 
echo $this->get_label('na');
else if(($display_type ==6 || $display_type ==4) && $cpa_enabled ==1)
echo $this->get_number_format($statistics['cpa_spend']);
else if($display_type ==9)
echo $this->get_number_format($statistics['pop_spend']);
else if($display_type ==12)
echo $this->get_number_format($statistics['affiliate_spend']);
else if($display_type ==13)
echo $this->get_number_format($statistics['cpv_spend']);
?>

<?php }?>
</td>


<?php if($display_type ==4 && $cpm_data_enabled ==1 && $cpc_enabled ==1){?>
<?php if($uid >0){?>
	<td><?php 
	
	if($cpm_enabled ==1)
	echo $this->get_number_format($totspend-$totprofit);
	else
	echo $this->get_label('na');
	?></td>
	<?php }else{?>
	<td><?php 
	if($cpm_enabled ==1)
	echo $this->get_number_format($totspend);
	else
	echo $this->get_label('na');
	?></td>
	
	<?php }}?>
	
	
	<?php if($display_type ==4 && $cpa_enabled ==1 && ($cpc_enabled ==1 || $cpm_data_enabled ==1)){?>

	<?php if($uid >0){?>
	<td><?php echo $this->get_number_format($statistics['cpa_spend']-$statistics['cpa_profit']);?></td>
	<?php }else{?>
	<td><?php echo $this->get_number_format($statistics['cpa_spend']);	?></td>
	<?php }}?>

<td></td>
</tr>
<?php }?>



</table>
 
  </td>
  </tr>

  
  
    <tr id="showstat2" class="showadustatclass statistics_tr">
  <td colspan="6" class="statistics_td"  style="padding: 5px;">
  
<table  style="width: 100%;" cellpadding="0" cellspacing="0"  class="data_table">




<tr class="row_heading_tr">
<td width="150px"><?php echo $this->get_label('date');?></td>

<td width="80px"><?php echo $this->get_label('type');?></td>

<?php if($display_type !=12){?>
<td width="100px"><?php echo $this->get_label('impressions');?></td>
<?php }?>

<?php if($display_type !=9){?>
<?php if($display_type !=1 || ($display_type ==1 && $cpm_enabled ==1)){?>
<td width="80px"><?php echo $this->get_label('clicks');?></td>

<?php if($display_type !=12){?>
<td width="100px"><?php echo $this->get_label('ctr');?></td>

<?php }}}?>

<?php if(($display_type ==4 || $display_type ==6 || $display_type ==12) && ($cpa_enabled ==1 || $affiliate_enabled ==1)){?>
<td style="width: 100px;"><bdi><?php echo $this->get_label('conversions');?></bdi></td>
<td style="width: 100px;"><bdi><?php echo $this->get_label('conversion ratio');?></bdi></td>
<?php }?>


<td width="130px"><?php echo $this->get_label('money spend');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>

<?php if($display_type !=1 || ($display_type ==1 && $cpm_enabled ==1)){?>
<td width="160px"><?php echo $this->get_label('pubprofit');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>
<td width="130px"><?php echo $this->get_label('balance');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>
<?php }?>
</tr>



<?php 

	if($from_date !='')  // for custom date range
	$statistics=$this->get_pub_date_range_timeperiod_statistics($from_date,$to_date,$uid,$aduid);
	else
	$statistics=$this->get_publisher_timeperiod_statistics($duration,$uid,$aduid);
	
	
	
	
	
	
	
	
	foreach($statistics as $key=>$value)
	{
	
		$rowspan=0;
		
		if($display_type ==4 && $cpc_enabled ==1)
		$rowspan=1;
		
		if($display_type ==4 && $cpm_data_enabled ==1)
		$rowspan=$rowspan+1;
		
		if($display_type ==4 && $cpa_enabled ==1)
		$rowspan=$rowspan+1;		
			
		if($rowspan ==0)
		$rowspan=1;		
		
		
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
	<td rowspan="<?php echo $rowspan;?>"><?php echo $data;?></td>

	<?php if(($display_type ==0 || $display_type ==4) && $cpc_enabled ==1){?>

	<td <?php if($display_type ==4 && ($cpm_data_enabled ==1 || $cpa_enabled ==1)){?>class="border-left"<?php }?>><?php echo $this->get_label('ppc');?></td>

	<td ><?php echo $value['impression'];?></td>
	<td ><?php echo $value['click'];?></td>
	<td ><?php echo $value['ctr'];?></td>
	
	<?php if($display_type ==4 && $cpa_enabled ==1){?>	
	<td><?php echo $this->get_label('na');?></td>
	<td><?php echo $this->get_label('na');?></td>
	<?php }?>		
	
	
	<td><?php echo $this->get_number_format($value['money_spent']);?></td>
   
 
    
    <?php if($uid >0) {?>
    <td><?php echo $this->get_number_format($value['pub_profit']);?></td>
    <td><?php echo $this->get_number_format(($value['money_spent']-$value['pub_profit']));?></td>
    <?php } else {?>
    <td><?php echo $this->get_label("na");?></td>
    <td><?php echo $this->get_number_format($value['money_spent']);?></td>
    <?php }?>
    
	<?php }else if(($display_type ==1 || $display_type ==4) && $cpm_data_enabled ==1)
	{
		
		$totimp=0;
		$totprofit=0;
		$totspend=0;
		if($cpm_enabled ==1)
		{
			$totimp=$value['cpm_impression'];
			$totprofit=$value['cpm_profit'];
			$totspend=$value['cpm_spend'];
		
		}
		
		if($html_enabled ==1)
		{
			$totimp=$totimp+$value['html_impression'];
			$totprofit=$totprofit+$value['html_profit'];
			$totspend=$totspend+$value['html_profit'];
		}
		
		
		?>
		

    <td <?php if($display_type ==4 && ($cpc_enabled ==1 || $cpa_enabled ==1)){?>class="border-left"<?php }?>><?php echo $this->get_label('cpm');?></td>    
    <td ><?php echo $totimp;?></td>
    
     <?php if($cpm_enabled ==1){?>
    <td ><?php echo $value['cpm_click'];?></td>
	<td ><?php echo $value['cpm_ctr'];?></td>
	<?php }?>
	
	<?php if($display_type ==4 && $cpa_enabled ==1){?>	
	<td><?php echo $this->get_label('na');?></td>
	<td><?php echo $this->get_label('na');?></td>
	<?php }?>		

	
    <td><?php echo $this->get_number_format($totspend);?></td>
    
    
    <?php if($cpm_enabled ==1){?>
    <?php if($uid >0) {?>
    <td><?php echo $this->get_number_format($totprofit);?></td>
    <td><?php echo $this->get_number_format(($totspend-$totprofit));?></td>
    <?php } else {?>
    <td><?php echo $this->get_label("na");?></td>
    <td><?php echo $this->get_number_format($value['cpm_spend']);?></td>
    <?php }?>
	<?php }?>
    
   	<?php }else if($display_type ==9 && $pop_enabled ==1){
	

		$totimp=$value['pop_impression'];
		$totprofit=$value['pop_profit'];
		$totspend=$value['pop_spend'];
				
		?>
    <td ><?php echo $this->get_label('pop');?></td>      
    <td ><?php echo $totimp;?></td>
    <td><?php echo $this->get_number_format($totspend);?></td>
    
    
    <?php if($uid >0) {?>
    <td><?php echo $this->get_number_format($totprofit);?></td>
    <td><?php echo $this->get_number_format(($totspend-$totprofit));?></td>
    <?php } else {?>
    <td><?php echo $this->get_label("na");?></td>
    <td><?php echo $this->get_number_format($totspend);?></td>
    <?php }?>
    
    
    <?php }else if(($display_type ==6 || $display_type ==4) && $cpa_enabled ==1){?>
    
    
	<td <?php if($display_type ==4 && ($cpm_data_enabled ==1 || $cpc_enabled ==1)){?>class="border-left"<?php }?> ><?php echo $this->get_label('cpa');?></td>     
    <td ><?php echo $value['cpa_impression'];?></td>
    <td ><?php echo $value['cpa_click'];?></td>
	<td ><?php echo $value['cpa_ctr'];?></td>
	
	
	<td ><?php echo $value['cpa_conversion'];?></td>
	<td ><?php echo $value['cpa_ratio'];?></td>
	
	
    <td><?php echo $this->get_number_format($value['cpa_spend']);?></td>
    
    
    <?php if($uid >0) {?>
    <td><?php echo $this->get_number_format($value['cpa_profit']);?></td>
    <td><?php echo $this->get_number_format(($value['cpa_spend']-$value['cpa_profit']));?></td>
    <?php } else {?>
    <td><?php echo $this->get_label("na");?></td>
    <td><?php echo $this->get_number_format($value['cpa_spend']);?></td>
    <?php }?>
    
    
    
    <?php }else if($display_type ==12){?>
    
    <td ><?php echo $this->get_label('affiliate');?></td>  
    <td ><?php echo $value['affiliate_click'];?></td>
	<td ><?php echo $value['affiliate_conversion'];?></td>
	<td ><?php echo $value['affiliate_ratio'];?></td>
    <td><?php echo $this->get_number_format($value['affiliate_spend']);?></td>
    
    
    <?php if($uid >0) {?>
    <td><?php echo $this->get_number_format($value['affiliate_profit']);?></td>
    <td><?php echo $this->get_number_format(($value['affiliate_spend']-$value['affiliate_profit']));?></td>
    <?php } else {?>
    <td><?php echo $this->get_label("na");?></td>
    <td><?php echo $this->get_number_format($value['affiliate_spend']);?></td>
    <?php }?>
    
    
    <?php }else if($display_type ==13){?>

    <td ><?php echo $this->get_label('cpv');?></td>    
    <td ><?php echo $value['cpv_impression'];?></td>
    <td ><?php echo $value['cpv_click'];?></td>
	<td ><?php echo $value['cpv_ctr'];?></td>
    <td><?php echo $this->get_number_format($value['cpv_spend']);?></td>
    
    <?php if($uid >0) {?>
    <td><?php echo $this->get_number_format($value['cpv_profit']);?></td>
    <td><?php echo $this->get_number_format(($value['cpv_spend']-$value['cpv_profit']));?></td>
    <?php } else {?>
    <td><?php echo $this->get_label("na");?></td>
    <td><?php echo $this->get_number_format($value['cpv_spend']);?></td>
    <?php }?>

    <?php }?>
 
	</tr>	
	

	<?php if($display_type ==4 && $cpm_data_enabled ==1 && $cpc_enabled ==1){

	$totimp=0;
	$totprofit=0;
	$totspend=0;
	if($cpm_enabled ==1)
	{
		$totimp=$value['cpm_impression'];
		$totprofit=$value['cpm_profit'];
		$totspend=$value['cpm_spend'];
	
	}
	
	if($html_enabled ==1)
	{
		$totimp=$totimp+$value['html_impression'];
		$totprofit=$totprofit+$value['html_profit'];
		$totspend=$totspend+$value['html_profit'];
	}
	?>
<tr class="row_data_tr">



<td class="border-left"><?php echo $this->get_label('cpm');?></td>



<td><?php echo $totimp;?></td>

<?php if($cpm_enabled ==1){?>
<td><?php echo $value['cpm_click'];?></td>
<td><?php echo $value['cpm_ctr'];?></td>
<?php }else{?>
<td><?php echo $this->get_label('na');?></td>
<td><?php echo $this->get_label('na');?></td>
<?php }?>


	<?php if($display_type ==4 && $cpa_enabled ==1){?>	
	<td><?php echo $this->get_label('na');?></td>
	<td><?php echo $this->get_label('na');?></td>
	<?php }?>	


<td><?php echo $this->get_number_format($totspend);?></td>


<?php if($cpm_enabled ==1){?>
<?php if($uid >0) {?>
<td><?php echo $this->get_number_format($totprofit);?></td>
<td><?php echo $this->get_number_format($totspend-$totprofit);?></td>
<?php }else{?>
<td><?php echo $this->get_label("na");?></td>
<td><?php echo $this->get_number_format($value['cpm_spend']);?></td>
<?php }?>
<?php }else{?>
<td><?php echo $this->get_label('na');?></td>
<td><?php echo $this->get_label('na');?></td>
<?php }?>

</tr>
<?php }?>



	<?php if($display_type ==4 && $cpa_enabled ==1 && ($cpc_enabled ==1 || $cpm_data_enabled ==1)){?>
	
	<tr class="row_data_tr">
	<td class="border-left"><?php echo $this->get_label('cpa');?></td>
	<td ><?php echo $value['cpa_impression'];?></td>
    <td ><?php echo $value['cpa_click'];?></td>
	<td ><?php echo $value['cpa_ctr'];?></td>
	
	<td ><?php echo $value['cpa_conversion'];?></td>
	<td ><?php echo $value['cpa_ratio'];?></td>
	
    <td><?php echo $this->get_number_format($value['cpa_spend']);?></td>
    
    
    <?php if($uid >0) {?>
    <td><?php echo $this->get_number_format($value['cpa_profit']);?></td>
    <td><?php echo $this->get_number_format(($value['cpa_spend']-$value['cpa_profit']));?></td>
    <?php } else {?>
    <td><?php echo $this->get_label("na");?></td>
    <td><?php echo $this->get_number_format($value['cpa_spend']);?></td>
    <?php }?>
    	
	</tr>
	<?php }?>	

	<?php }?>

</table>

  
  
   </td>
  </tr>


</table>
  
</td></tr>	
</table>
<?php $this->dispatch("layout/footer");?>
<script type="text/javascript">
show_pub_stat(<?php echo $tab;?>);
</script>