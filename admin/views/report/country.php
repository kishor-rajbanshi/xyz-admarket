<?php $this->dispatch("layout/header/7/_76");?>
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
LoadSelectBox();

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

function LoadSelectBox()
{
	if($('#type').val() ==0)
	{
		$('#adv').hide();
		$('#pub').hide();
	}
	else if($('#type').val() ==1)
	{
		$('#adv').show();
		$('#pub').hide();
	}
	else if($('#type').val() ==2)
	{
		$('#adv').hide();
		$('#pub').show();
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
?>


<div class="sub_menu_main"><?php echo $this->get_label('countrywise report');?></div>

<?php $this->dispatch("links/links/25");?>


<table style="width: 100%;" cellpadding="0" cellspacing="0" >
<tr><td colspan="4" height="10px">

<div class="search_div">

<?php 
$cpc_enabled=$this->get_addon_status('cpc_enabled');
$cpa_enabled=$this->get_addon_status('cpa_enabled');
$cpm_enabled=$this->get_addon_status('cpm_enabled');
$html_enabled=$this->get_addon_status('html_enabled');
$sponsored_enabled=$this->get_addon_status('sponsored_enabled');
$pop_enabled=$this->get_addon_status('pop-ads_enabled');
$affiliate_enabled=$this->get_addon_status('affiliate-ads_enabled');
$cpv_enabled=$this->get_addon_status('video-ads_enabled');


$pop_addon_usage = intval(Configuration::get_instance()->read('pop_addon_usage'));

if($pop_addon_usage == 1)
$pop_enabled = 0;


$duration=$this->get_variable('duration');
$adv=$this->get_variable('adv');
$pub=$this->get_variable('pub');
$type=$this->get_variable('type');



if($from_date =='' && $duration ==7)
$duration=1;

$results=$this->get_array("results");
$number=count($results);


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


$form=$this->create_form();
$form->start("advstatistics","","post");
?>
  
  
<table class="search_div_table">
<tr>

<td>
<select name="type" id="type" style="width: 125px;" onchange="LoadSelectBox();">
<option value="0" <?php if($type ==0) echo "selected";?>><?php echo $this->get_label('overall');?></option>
<option value="1" <?php if($type ==1) echo "selected";?>><?php echo $this->get_label('advertisers');?></option>
<option value="2" <?php if($type ==2) echo "selected";?>><?php echo $this->get_label('publishers');?></option>
</select>
&nbsp;

<select name="adv" id="adv" style="width: 125px;display: none;">
<?php 
$res1=$this->get_result('res1');
foreach($res1 as $key1=>$value1){?>
<option value="<?php echo $value1['id'];?>" <?php if($adv == $value1['id']) echo "selected";?>><?php echo $value1['username'];?></option>
<?php }?>
</select>

&nbsp;
<select name="pub" id="pub" style="width: 125px;display: none;">
<?php 
$res11=$this->get_result('res11');
foreach($res11 as $key1=>$value1){?>
<option value="<?php echo $value1['id'];?>" <?php if($pub == $value1['id']) echo "selected";?>><?php echo $value1['username'];?></option>
<?php }?>
</select>
  
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

<input type="text" readonly="readonly" name="from_date" id="from_date" value="<?php echo $from_date;?>" placeholder="<?php echo $this->get_label('from date');?>" size="5" />  

<input type="text" readonly="readonly" name="to_date" id="to_date" value="<?php echo $to_date;?>" placeholder="<?php echo $this->get_label('to date');?>" size="5" />

</td>

<td style="width: 100px;">&nbsp;<input type="submit" name="search" value="<?php echo $this->get_label('go');?>" /></td>


<td style="width: 100px;"></td>

</tr>
</table>
<?php $form->end(); ?> 
 
</div>

</td></tr>

<tr><td colspan="4" height="10px"></td></tr>

<tr><td colspan="3">


<table class="data_table" cellpadding="0" cellspacing="0">
<tr class="row_heading_tr">
<td ><?php echo $this->get_label('country');?></td>

<?php if($rowspan >0){?>
<td style="width: 80px;"><?php echo $this->get_label('type');?></td>
<td><?php echo $this->get_label('impressions');?></td>
<td><?php echo $this->get_label('clicks');?></td>
<td><?php echo $this->get_label('ctr');?></td>

<?php if($cpa_enabled ==1 || $affiliate_enabled ==1){?>
<td><?php echo $this->get_label('conversions');?></td>
<td><?php echo $this->get_label('conversion ratio');?></td>
<?php }?>

<td><?php echo $this->get_label('money spend');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>
<td><?php echo $this->get_label('pubprofit');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>
<td><?php echo $this->get_label('balance');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>
<?php }?>
</tr>

<?php 


if($number==0){?>	
<tr><td colspan="10" height="30px">&nbsp;<?php echo $this->get_label('no records found');?></td></tr>	
<?php }

foreach($results as $key=>$value)
{
	
	$enterflag=0;		
	
if($value[0] !='')
{

	if($type ==0 || $type ==1)
	{
		if($type ==0)
		$adv=-1;
			
			
		if($from_date !='')  // for custom date range
		$statistics=$this->get_adv_date_range_statistics($from_date,$to_date,$adv,0,0,0,1,$value[0]);
		else
		$statistics=$this->get_advertiser_statistics($duration,$adv,0,0,0,1,$value[0]);
			
	}
	else 
	{
			if($from_date !='')   // for custom date range
			$statistics=$this->get_pub_date_range_statistics($from_date,$to_date,$pub,0,0,0,1,$value[0]);
			else
			$statistics=$this->get_publisher_statistics($duration,$pub,0,0,0,1,$value[0]);
	}
		
		
	?>
	
	
	
<?php if($cpc_enabled ==1){	?>	
<tr class="row_data_tr">
<td rowspan="<?php echo $rowspan;?>"><img src="<?php echo BASE.'/images/flags/'.strtolower($value[0]).'.png';?>" />&nbsp;<?php echo $value[1];?></td>
<td class="border-left"><?php echo $this->get_label('ppc');?></td>

<td ><?php echo $statistics['impression'];?></td>
<td ><?php echo $statistics['click'];?></td>
<td ><?php echo $statistics['ctr'];?></td>

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
<tr class="row_data_tr">
<td <?php if($enterflag ==1){?>style="display: none;"<?php }?> <?php if($enterflag ==0){?>rowspan="<?php echo $rowspan;?>"<?php }?>><img src="<?php echo BASE.'/images/flags/'.strtolower($value[0]).'.png';?>" />&nbsp;<?php echo $value[1];?></td>
<td class="border-left"><?php echo $this->get_label('cpm');?></td>
<td><?php echo $totimp;?></td>

<?php if($cpm_enabled ==1){?>
<td ><?php echo $statistics['cpm_click'];?></td>
<td ><?php echo $statistics['cpm_ctr'];?></td>
<?php }else{?>
<td><?php echo $this->get_label('na');?></td>
<td><?php echo $this->get_label('na');?></td>
<?php }?>

	<?php if($cpa_enabled ==1 || $affiliate_enabled ==1){?>
	<td ><?php echo $this->get_label('na');?></td>
	<td ><?php echo $this->get_label('na');?></td>
	<?php }?>



<td ><?php echo $this->get_number_format($totspend);?></td>
<?php if($cpm_enabled ==1){?>
<td ><?php echo $this->get_number_format($totprofit);?></td>
<td ><?php echo $this->get_number_format(($totspend-$totprofit));?></td>
<?php }else{?>
<td><?php echo $this->get_label('na');?></td>
<td><?php echo $this->get_label('na');?></td>
<?php }?>
</tr>
	<?php 
	$enterflag=1;
	}?>




<?php if($cpa_enabled ==1){?>

<tr class="row_data_tr">
<td <?php if($enterflag ==1){?>style="display: none;"<?php }?> <?php if($enterflag ==0){?>rowspan="<?php echo $rowspan;?>"<?php }?>><img src="<?php echo BASE.'/images/flags/'.strtolower($value[0]).'.png';?>" />&nbsp;<?php echo $value[1];?></td>
<td class="border-left"><?php echo $this->get_label('cpa');?></td>
<td><?php echo $statistics['cpa_impression'];?></td>
<td ><?php echo $statistics['cpa_click'];?></td>
<td ><?php echo $statistics['cpa_ctr'];?></td>

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
<td <?php if($enterflag ==1){?>style="display: none;"<?php }?> <?php if($enterflag ==0){?>rowspan="<?php echo $rowspan;?>"<?php }?>><img src="<?php echo BASE.'/images/flags/'.strtolower($value[0]).'.png';?>" />&nbsp;<?php echo $value[1];?></td>
<td class="border-left"><?php echo $this->get_label('cpv');?></td>
<td><?php echo $statistics['cpv_impression'];?></td>
<td ><?php echo $statistics['cpv_click'];?></td>
<td ><?php echo $statistics['cpv_ctr'];?></td>


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









    <?php if($pop_enabled ==1){
    

    		$totimp=$statistics['pop_impression'];
    		$totprofit=$statistics['pop_profit'];
    		$totspend=$statistics['pop_spend'];
    	
    	
    	?>
    <tr class="row_data_tr">
	<td <?php if($enterflag ==1){?>style="display: none;"<?php }?> <?php if($enterflag ==0){?>rowspan="<?php echo $rowspan;?>"<?php }?>><img src="<?php echo BASE.'/images/flags/'.strtolower($value[0]).'.png';?>" />&nbsp;<?php echo $value[1];?></td>
	<td class="border-left"><?php echo $this->get_label('pop');?></td>
    <td ><?php echo $totimp;?></td>
    <td ><?php echo $this->get_label('na');?></td>
	<td ><?php echo $this->get_label('na');?></td>
    
    <?php if($cpa_enabled ==1 || $affiliate_enabled ==1){?>
	<td ><?php echo $this->get_label('na');?></td>
	<td ><?php echo $this->get_label('na');?></td>
	<?php }?>
    
    <td><?php echo $this->get_number_format($totspend);?></td>
    <td><?php echo $this->get_number_format($totprofit);?></td>
    <td><?php echo $this->get_number_format(($totspend-$totprofit));?></td>
    </tr>	
	<?php 
	$enterflag=1;
	}?>






   <?php if($affiliate_enabled ==1){
    
    	$totprofit=$statistics['affiliate_profit'];
    	$totspend=$statistics['affiliate_spend'];
    	 	
    	?>
    <tr class="row_data_tr">
	<td <?php if($enterflag ==1){?>style="display: none;"<?php }?> <?php if($enterflag ==0){?>rowspan="<?php echo $rowspan;?>"<?php }?>><img src="<?php echo BASE.'/images/flags/'.strtolower($value[0]).'.png';?>" />&nbsp;<?php echo $value[1];?></td>
	<td class="border-left"><?php echo $this->get_label('affiliate');?></td>
    <td ><?php echo $this->get_label('na');?></td>
    <td><?php echo $statistics['affiliate_click'];?></td>
	<td ><?php echo $this->get_label('na');?></td>
    
    <td ><?php echo $statistics['affiliate_conversion'];?></td>
	<td ><?php echo $statistics['affiliate_ratio'];?></td>
    
    <td><?php echo $this->get_number_format($totspend);?></td>
    
    <td><?php echo $this->get_number_format($totprofit);?></td>
    <td><?php echo $this->get_number_format(($totspend-$totprofit));?></td>
    </tr>	
  	<?php }?>  	

<?php 
}
}?>		

</table>

</td></tr></table>
<?php $this->dispatch("layout/footer");?>