<?php 
$this->dispatch("layout/header/5/_55");
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

<div class="sub_menu_main"><?php echo $this->get_label('manage adcode statistics');?><?php $this->dispatch("links/links/8");?></div>


<table  cellpadding="0" cellspacing="0" border="0" style="width:100%;" >
<tr><td colspan="9">		
<div class="search_div">
<?php
$form1=$this->create_form();
$form1->start("adunitstatistics",$this->make_url("report/adcodes_publisher"),"post");  
?>
<table class="search_div_table">
<?php 
$cpc_enabled=$this->get_addon_status('cpc_enabled');
$cpa_enabled=$this->get_addon_status('cpa_enabled');		
$cpm_enabled=$this->get_addon_status('cpm_enabled');
$html_enabled=$this->get_addon_status('html_enabled');
$category_enabled=$this->get_addon_status('category-targeting_enabled');
$sponsored_enabled=$this->get_addon_status('sponsored_enabled');
$pop_enabled=$this->get_addon_status('pop-ads_enabled');
$affiliate_enabled=$this->get_addon_status('affiliate-ads_enabled');
$cpv_enabled=$this->get_addon_status('video-ads_enabled');


	$stringarray=array();
	if($category_enabled ==1)
	{
		$category_enabled_ads=Configuration::get_instance()->read('category_enabled_ads');

		if($category_enabled_ads !='')
		$stringarray=explode('_',$category_enabled_ads);
	}

$duration=$this->get_variable('duration');
$number11=$this->get_variable('number11');
$pub=$this->get_variable('pub');
$adpricing=$this->get_variable('adpricing');

if($from_date =='' && $duration ==7)
$duration=1;
?>
  
<tr>
<td>
<select name="pub" id="pub" style="width: 150px;">
<option value="-1" <?php if($pub==-1) echo "selected";?>><?php echo $this->get_label('all publishers');?></option>
<?php 
$res1=$this->get_result('res1');
foreach($res1 as $key1=>$value1)
{
?>
<option value="<?php echo $value1['id'];?>" <?php if($pub==$value1['id']) echo "selected";?>><?php echo $value1['username'];?></option>
<?php 
}
?>
</select>
</td> 
<td>
&nbsp;
<select name="duration" id="duration">
<option value="1" <?php if($duration==1) { echo "selected"; } ?>><?php echo $this->get_label('today');?></option>
<option value="6" <?php if($duration==6) { echo "selected"; } ?>><?php echo $this->get_label('yesterday');?></option>
<option value="2" <?php if($duration==2) { echo "selected"; } ?>><?php echo $this->get_label('last 14');?></option>
<option value="3" <?php if($duration==3) { echo "selected"; } ?>><?php echo $this->get_label('last 30');?></option>
<option value="4" <?php if($duration==4) { echo "selected"; } ?>><?php echo $this->get_label('last 12 month');?></option>
<option value="5" <?php if($duration==5) { echo "selected"; } ?>><?php echo $this->get_label('all time');?></option>
<option value="7" <?php if($duration==7) { echo "selected"; } ?>><?php echo $this->get_label('custom date');?></option>
</select>

&nbsp;&nbsp;
<input type="text" readonly="readonly" name="from_date" id="from_date" value="<?php echo $from_date;?>" placeholder="From Date" size="8" />  
&nbsp;
<input type="text" readonly="readonly" name="to_date" id="to_date" value="<?php echo $to_date;?>" placeholder="To Date" size="8" />
&nbsp;&nbsp;


&nbsp;&nbsp;
<?php echo $this->get_pricing_box($adpricing,3);?>
&nbsp;&nbsp;
</td>
<td>&nbsp;&nbsp; <input type="submit" name="search" value="<?php echo $this->get_label('go');?>" /></td>
</tr>
</table>
<?php $form1->end(); ?> 	

</div>
</td></tr>	
<tr><td colspan="9" style="height: 10px;"></td></tr>


<tr><td colspan="9">
<table style="width: 100%;" cellpadding="0" cellspacing="0" border="0" class="data_table">
		
<tr class="row_heading_tr">
<td style="width: 100px;"><?php echo $this->get_label('name');?></td>
<td style="width: 120px;"><?php echo $this->get_label('publisher');?></td>
<?php if($category_enabled ==1 && $category_enabled_ads !="" && ($adpricing ==-1 || in_array($adpricing,$stringarray))){?>
<td style="width: 120px;"><?php echo $this->get_label('site name');?></td>
<?php }?>

<td style="width: 60px;"><?php echo $this->get_label('type');?></td>

<?php if($adpricing !=12){?>
<td style="width: 80px;"><?php echo $this->get_label('impressions');?></td>
<?php }?>


<?php if($adpricing !=9){?>
<td style="width: 60px;"><?php echo $this->get_label('clicks');?></td>

<?php if($adpricing !=12){?>
<td style="width: 60px;"><?php echo $this->get_label('ctr');?></td>
<?php }}?>


<?php if(($adpricing ==-1 || $adpricing ==4 || $adpricing ==6 || $adpricing ==12) && ($cpa_enabled ==1 || $affiliate_enabled ==1)){?>
<td style="width: 80px;"><?php echo $this->get_label('conversions');?></td>
<td style="width: 120px;"><?php echo $this->get_label('conversion ratio');?></td>
<?php }?>


<td style="width: 80px;"><?php echo $this->get_label('money spend');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>
<td style="width: 80px;"><?php echo $this->get_label('pubprofit');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>
<td style="width: 80px;"><?php echo $this->get_label('balance');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>
</tr>
<?php if($number11==0){?>
<tr><td colspan="10" height="30px">&nbsp;<?php echo $this->get_label('no records found');?></td></tr>
<?php }?>
<?php 


$cpm_data_enabled=0;

	
if($cpm_enabled ==1 || $html_enabled ==1)	
$cpm_data_enabled=1;


$res=$this->get_result('res4');
foreach($res as $key=>$value)
{
	$aduid=$value['id'];
	$adbid=$value['blockid'];
	
	
	if($from_date !='')   // for custom date range
	$statistics=$this->get_pub_date_range_statistics($from_date,$to_date,$value['pubid'],$aduid);
	else
	$statistics=$this->get_publisher_statistics($duration,$value['pubid'],$aduid);
	
	
	
	$rowspan=0;
	
	if($value['display_type'] ==4 && $cpc_enabled ==1)
	$rowspan=1;
	
	if($value['display_type'] ==4 && $cpm_data_enabled ==1)
	$rowspan=$rowspan+1;
	
	if($value['display_type'] ==4 && $cpa_enabled ==1)
	$rowspan=$rowspan+1;		
		
	if($rowspan ==0)
	$rowspan=1;		
	
	
?>
<tr class="row_data_tr">
<td rowspan="<?php echo $rowspan;?>">

<?php if($value['display_type'] ==3){?>
<a target="_parent" href="<?php echo $this->make_url("dispatch/sponsored/13/".$aduid,BASE);?>"><?php echo $value['name'];?></a>
<?php }else{?>
<a target="_parent" href="<?php echo $this->make_base_url("report/adcodes_detailed/".$aduid,ADMIN_DIR);?>"><?php echo $value['name'];?></a>
<?php }?>

</td>
<td rowspan="<?php echo $rowspan;?>"><a href="<?php echo $this->make_url("user/profile/".$value['pubid']."/0");?>"><?php echo $this->escape($this->get_user_name($value['pubid']));?></a></td>


<?php if($category_enabled ==1 && $category_enabled_ads !=""){
if(($adpricing == -1 && in_array($value['display_type'],$stringarray)) || in_array($value['display_type'],$stringarray)){?>
<td rowspan="<?php echo $rowspan;?>"><?php echo CategoryHelper::get_site_name($value['sid']);?></td>
<?php }else if($adpricing == -1 && !in_array($value['display_type'],$stringarray)){?>
<td rowspan="<?php echo $rowspan;?>"><?php echo $this->get_label('na');?></td>
<?php }} ?>





<?php if(($value['display_type'] ==0 || $value['display_type'] ==4) && $cpc_enabled ==1){?>
<td <?php if($value['display_type'] ==4 && ($cpm_data_enabled ==1 || $cpa_enabled ==1)){?>class="border-left"<?php }?>><?php echo $this->get_label('ppc');?></td>
<?php } else if(($value['display_type'] ==1 || $value['display_type'] ==4) && $cpm_data_enabled ==1){?>
<td <?php if($value['display_type'] ==4 && ($cpc_enabled ==1 || $cpa_enabled ==1)){?>class="border-left"<?php }?>><?php echo $this->get_label('cpm');?></td>
<?php } else if(($value['display_type'] ==6 || $value['display_type'] ==4) && $cpa_enabled ==1){?>
<td <?php if($value['display_type'] ==4 && ($cpm_data_enabled ==1 || $cpc_enabled ==1)){?>class="border-left"<?php }?>><?php echo $this->get_label('cpa');?></td>
<?php }else if($value['display_type'] ==3){?>
<td><?php echo $this->get_label('cpd');?></td>
<?php }else if($value['display_type'] ==9){?>
<td><?php echo $this->get_label('pop');?></td>
<?php }else if($value['display_type'] ==12){?>
<td><?php echo $this->get_label('affiliate');?></td>
<?php }else if($value['display_type'] ==13){?>
<td><?php echo $this->get_label('cpv');?></td>
<?php }?>

 

<?php if(($value['display_type'] ==0 || $value['display_type'] ==4) && $cpc_enabled ==1){?>
<td><?php echo $statistics['impression'];?></td>

<?php if($adpricing !=9){?>
<td><?php echo $statistics['click'];?></td>
<td><?php echo $statistics['ctr'];?></td>
<?php }?>

<?php if(($adpricing ==-1 || $adpricing ==4 || $adpricing ==6 || $adpricing ==12) && ($cpa_enabled ==1 || $affiliate_enabled ==1)){?>
<td><?php echo $this->get_label('na');?></td>
<td><?php echo $this->get_label('na');?></td>
<?php }?>


<td><?php echo $this->get_number_format($statistics['money_spent']);?></td>
<td><?php echo $this->get_number_format($statistics['pub_profit']);?></td>
<td ><?php echo $this->get_number_format(($statistics['money_spent']-$statistics['pub_profit']));?></td>
<?php } else if($value['display_type'] ==3){?>

<td ><?php echo $this->get_label('na');?></td>

<?php if($adpricing !=9){?>
<td ><?php echo $this->get_label('na');?></td>
<td ><?php echo $this->get_label('na');?></td>
<?php }?>


<?php if(($adpricing ==-1 || $adpricing ==4 || $adpricing ==6 || $adpricing ==12) && ($cpa_enabled ==1 || $affiliate_enabled ==1)){?>
<td><?php echo $this->get_label('na');?></td>
<td><?php echo $this->get_label('na');?></td>
<?php }?>


<td ><?php echo $this->get_label('na');?></td>
<td ><?php echo $this->get_label('na');?></td>
<td ><?php echo $this->get_label('na');?></td>



<?php }else if(($value['display_type'] ==1 || $value['display_type'] ==4) && $cpm_data_enabled ==1){

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

<?php if($adpricing !=9){?>
<?php if($cpm_enabled ==1){?>
<td><?php echo $statistics['cpm_click'];?></td>
<td><?php echo $statistics['cpm_ctr'];?></td>
<?php }else{?>
<td><?php echo $this->get_label('na');?></td>
<td><?php echo $this->get_label('na');?></td>
<?php }?>
<?php }?>

<?php if(($adpricing ==-1 || $adpricing ==4 || $adpricing ==6 || $adpricing ==12) && ($cpa_enabled ==1 || $affiliate_enabled ==1)){?>
<td><?php echo $this->get_label('na');?></td>
<td><?php echo $this->get_label('na');?></td>
<?php }?>


<td><?php echo $this->get_number_format($totspend);?></td>

<?php if($cpm_enabled ==1){?>
<td><?php echo $this->get_number_format($totprofit);?></td>
<td><?php echo $this->get_number_format($totspend-$totprofit);?></td>
<?php }else{?>
<td><?php echo $this->get_label('na');?></td>
<td><?php echo $this->get_label('na');?></td>
<?php }

}else if($value['display_type'] ==9 && $pop_enabled ==1){


		$totimp=$statistics['pop_impression'];
		$totprofit=$statistics['pop_profit'];
		$totspend=$statistics['pop_spend'];
	

	
	?>
<td><?php echo $totimp;?></td>

<?php if($adpricing !=9){?>
<td><?php echo $this->get_label('na');?></td>
<td><?php echo $this->get_label('na');?></td>
<?php }?>


<?php if(($adpricing ==-1 || $adpricing ==4 || $adpricing ==6 || $adpricing ==12) && ($cpa_enabled ==1 || $affiliate_enabled ==1)){?>
<td><?php echo $this->get_label('na');?></td>
<td><?php echo $this->get_label('na');?></td>
<?php }?>


<td><?php echo $this->get_number_format($totspend);?></td>
<td><?php echo $this->get_number_format($totprofit);?></td>
<td><?php echo $this->get_number_format($totspend-$totprofit);?></td>



<?php }else if(($value['display_type'] ==6 || $value['display_type'] ==4) && $cpa_enabled ==1){

	$totimp=0;
	$totprofit=0;
	$totspend=0;

		$totimp=$statistics['cpa_impression'];
		$totprofit=$statistics['cpa_profit'];
		$totspend=$statistics['cpa_spend'];
	

	
	?>
<td><?php echo $totimp;?></td>

<?php if($adpricing !=9){?>
<td><?php echo $statistics['cpa_click'];?></td>
<td><?php echo $statistics['cpa_ctr'];?></td>
<?php }?>


<td><?php echo $statistics['cpa_conversion'];?></td>
<td><?php echo $statistics['cpa_ratio'];?></td>


<td><?php echo $this->get_number_format($totspend);?></td>
<td><?php echo $this->get_number_format($totprofit);?></td>
<td><?php echo $this->get_number_format($statistics['cpa_spend']-$statistics['cpa_profit']);?></td>




<?php }else if($value['display_type'] ==12){

	$totprofit=0;
	$totspend=0;

		$totprofit=$statistics['affiliate_profit'];
		$totspend=$statistics['affiliate_spend'];
	?>
	
<?php if($adpricing !=12){?>
<td ><?php echo $this->get_label('na');?></td>
<?php }?>

<?php if($adpricing !=9){?>
<td><?php echo $statistics['affiliate_click'];?></td>



<?php if($adpricing !=12){?>
<td ><?php echo $this->get_label('na');?></td>
<?php }}?>


<td><?php echo $statistics['affiliate_conversion'];?></td>
<td><?php echo $statistics['affiliate_ratio'];?></td>


<td><?php echo $this->get_number_format($totspend);?></td>
<td><?php echo $this->get_number_format($totprofit);?></td>
<td><?php echo $this->get_number_format($statistics['affiliate_spend']-$statistics['affiliate_profit']);?></td>




<?php }else if($value['display_type'] ==13 && $cpv_enabled ==1){?>
<td><?php echo $statistics['cpv_impression'];?></td>

<?php if($adpricing !=9){?>
<td><?php echo $statistics['cpv_click'];?></td>
<td><?php echo $statistics['cpv_ctr'];?></td>
<?php }?>

<?php if(($adpricing ==-1 || $adpricing ==4 || $adpricing ==6 || $adpricing ==12) && ($cpa_enabled ==1 || $affiliate_enabled ==1)){?>
<td><?php echo $this->get_label('na');?></td>
<td><?php echo $this->get_label('na');?></td>
<?php }?>

<td><?php echo $this->get_number_format($statistics['cpv_spend']);?></td>

<td><?php echo $this->get_number_format($statistics['cpv_profit']);?></td>
<td><?php echo $this->get_number_format($statistics['cpv_spend']-$statistics['cpv_profit']);?></td>

<?php }?>
</tr>



<?php if($value['display_type'] ==4 && $cpm_data_enabled ==1 && $cpc_enabled ==1){

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
<td class="border-left"><?php echo $this->get_label('cpm');?></td>

<td><?php echo $totimp;?></td>



<?php if($adpricing !=9){?>
<?php if($cpm_enabled ==1){?>
<td><?php echo $statistics['cpm_click'];?></td>
<td><?php echo $statistics['cpm_ctr'];?></td>
<?php }else{?>
<td><?php echo $this->get_label('na');?></td>
<td><?php echo $this->get_label('na');?></td>
<?php }?>
<?php }?>





<?php if(($adpricing ==-1 || $adpricing ==4 || $adpricing ==6 || $adpricing ==12) && ($cpa_enabled ==1 || $affiliate_enabled ==1)){?>
<td><?php echo $this->get_label('na');?></td>
<td><?php echo $this->get_label('na');?></td>
<?php }?>

<td><?php echo $this->get_number_format($totspend);?></td>


<?php if($cpm_enabled ==1){?>
<td><?php echo $this->get_number_format($totprofit);?></td>
<td><?php echo $this->get_number_format($totspend-$totprofit);?></td>
<?php }else{?>
<td><?php echo $this->get_label('na');?></td>
<td><?php echo $this->get_label('na');?></td>
<?php }?>

</tr>
<?php }?>


<?php if($value['display_type'] ==4 && $cpa_enabled ==1 && ($cpc_enabled ==1 || $cpm_data_enabled ==1)){?>

<tr class="row_data_tr">

<td class="border-left"><?php echo $this->get_label('cpa');?></td>
<td><?php echo $statistics['cpa_impression'];?></td>

<?php if($adpricing !=9){?>
<td ><?php echo $statistics['cpa_click'];?></td>
<td ><?php echo $statistics['cpa_ctr'];?></td>
<?php }?>


<td><?php echo $statistics['cpa_conversion'];?></td>
<td><?php echo $statistics['cpa_ratio'];?></td>

<td><?php echo $this->get_number_format($statistics['cpa_spend']);?></td>
<td><?php echo $this->get_number_format($statistics['cpa_profit']);?></td>
<td><?php echo $this->get_number_format($statistics['cpa_spend']-$statistics['cpa_profit']);?></td>

</tr>
<?php }?>



<?php }?>
<tr><td colspan="15" align="center"><?php echo $this->get_variable('pagination3');?></td></tr>

</table>
</td></tr>	
</table>
<?php $this->dispatch("layout/footer");?>	