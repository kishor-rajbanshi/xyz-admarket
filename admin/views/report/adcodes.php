<?php 
$this->dispatch("layout/header/5/_54");
$number=$this->get_variable('number');
$duration=$this->get_variable('duration');
$sid=$this->get_variable('sid');
$adpricing=$this->get_variable('adpricing');
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

<div class="sub_menu_main"><?php echo $this->get_label('manage adunits statistics');?></div>

<?php $this->dispatch("links/links/21");?>

<table style="width: 100%;" >
<tr><td colspan="2">
<div class="search_div">
<?php 
$form=$this->create_form();
$form->start("showstatistics",$this->make_url("report/adcodes"),"post");
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

<input type="text" readonly="readonly" name="from_date" id="from_date" value="<?php echo $from_date;?>" placeholder="From Date" size="5" />  
<input type="text" readonly="readonly" name="to_date" id="to_date" value="<?php echo $to_date;?>" placeholder="To Date" size="5" />



&nbsp;&nbsp;
<?php echo $this->get_pricing_box($adpricing,3);?>
&nbsp;&nbsp;


<?php if($category_enabled ==1 && $category_enabled_ads !=""){?>
<span class="site-class" style="display: none;">
&nbsp;&nbsp;
<?php echo CategoryHelper::get_site_dropdown(0,$sid);?>
</span>
<?php }?>
&nbsp;&nbsp;
</td>
<td><input type="submit" name="stat" value="<?php echo $this->get_label('go');?>"/></td>
</tr>
</table>
<?php $form->end(); ?>
</div>
</td></tr>


<tr><td colspan="2" style="height: 10px;"></td></tr>
</table>


<table class="data_table" cellpadding="0" cellspacing="0">
<tr class="row_heading_tr">
<td width="160px"><?php echo $this->get_label('name');?></td>

<?php if($category_enabled ==1 && $category_enabled_ads !="" && ($adpricing ==-1 || in_array($adpricing,$stringarray))){?>
<td width="100px"><?php echo $this->get_label('site name');?></td>
<?php }?>

<td width="100px"><?php echo $this->get_label('type');?></td>

<td width="60px"><?php echo $this->get_label('impressions');?></td>

<?php if($adpricing !=9){?>
<td width="60px"><?php echo $this->get_label('clicks');?></td>
<td width="60px"><?php echo $this->get_label('ctr');?></td>
<?php }?>

<?php if(($adpricing ==-1 || $adpricing ==4 || $adpricing ==6) && $cpa_enabled ==1){?>
<td><?php echo $this->get_label('conversions');?></td>
<td><?php echo $this->get_label('conversion ratio');?></td>
<?php }?>

<td width="80px"><?php echo $this->get_label('moneygained');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>
</tr>




<?php if($number==0){?>	
<tr><td colspan="12" style="padding-left: 5px;" height="30px"><?php echo $this->get_label('no records found');?></td></tr>
<?php 	
}


$cpm_data_enabled=0;

if($cpm_enabled ==1 || $html_enabled ==1)	
$cpm_data_enabled=1;


$pop_addon_usage = intval(Configuration::get_instance()->read('pop_addon_usage'));


$res=$this->get_result('res');
foreach($res as $key=>$value)
{
	$aduid=$value['id'];
	
	if($from_date !='')   // for custom date range
	$stat_row=$this->get_pub_date_range_statistics($from_date,$to_date,0,$aduid);
	else
	$stat_row=$this->get_publisher_statistics($duration,0,$aduid);
	
	
	$adcode_type 	= $this->get_adcode_type($aduid);
	
	if($adcode_type == 9 && $pop_addon_usage ==1)
	{
		$cpc_enabled 	= 0;
		$html_enabled	= 0;
	}
	else
	{
		$cpc_enabled	= $this->get_addon_status('cpc_enabled');
		$html_enabled	= $this->get_addon_status('html_enabled');		
	}
	
	if($cpm_enabled ==1 || $html_enabled ==1)	
	$cpm_data_enabled=1;		
		
	
	
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
<a href="<?php echo $this->make_url("dispatch/sponsored/13/".$aduid,BASE);?>"><?php echo $value['name'];?></a>
<?php }else{?>
<a href="<?php echo $this->make_url("report/adcodes_detailed/".$aduid);?>"><?php echo $value['name'];?></a>
<?php }?>
</td>

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
<?php }else if($value['display_type'] ==13){?>
<td><?php echo $this->get_label('cpv');?></td>
<?php }?>





<?php if(($value['display_type'] ==0 || $value['display_type'] ==4) && $cpc_enabled ==1){?>
<td ><?php echo $stat_row['impression'];?></td>

<?php if($adpricing !=9){?>
<td ><?php echo $stat_row['click'];?></td>
<td ><?php echo $stat_row['ctr'];?></td>
<?php }?>

<?php if(($adpricing ==-1 || $adpricing ==4 || $adpricing ==6) && $cpa_enabled ==1){?>
<td><?php echo $this->get_label('na');?></td>
<td><?php echo $this->get_label('na');?></td>
<?php }?>


<td ><?php echo $this->get_number_format($stat_row['money_spent']);?></td>
<?php } else if($value['display_type'] ==3){?>

<td ><?php echo $this->get_label('na');?></td>

<?php if($adpricing !=9){?>
<td ><?php echo $this->get_label('na');?></td>
<td ><?php echo $this->get_label('na');?></td>
<?php }?>

<?php if(($adpricing ==-1 || $adpricing ==4 || $adpricing ==6) && $cpa_enabled ==1){?>
<td><?php echo $this->get_label('na');?></td>
<td><?php echo $this->get_label('na');?></td>
<?php }?>


<td ><?php echo $this->get_label('na');?></td>

<?php }else if(($value['display_type'] ==1 || $value['display_type'] ==4) && $cpm_data_enabled ==1){

	$totimp=0;
	if($cpm_enabled ==1)
	$totimp=$stat_row['cpm_impression'];
	
	if($html_enabled ==1)
	$totimp=$totimp+$stat_row['html_impression'];
	
	
	
	?>
<td ><?php echo $totimp;?></td>

<?php if($adpricing !=9){?>
<?php if($cpm_enabled ==1){?>
<td ><?php echo $stat_row['cpm_click'];?></td>
<td ><?php echo $stat_row['cpm_ctr'];?></td>
<?php }else{?>
<td ><?php echo $this->get_label('na');?></td>
<td ><?php echo $this->get_label('na');?></td>
<?php }?>
<?php }?>

<?php if(($adpricing ==-1 || $adpricing ==4 || $adpricing ==6) && $cpa_enabled ==1){?>
<td><?php echo $this->get_label('na');?></td>
<td><?php echo $this->get_label('na');?></td>
<?php }?>


<?php if($cpm_enabled ==1){?>
<td ><?php echo $this->get_number_format($stat_row['cpm_spend']);?></td>
<?php }else{?>
<td ><?php echo $this->get_label('na');?></td>
<?php }?>
<?php

}else if($value['display_type'] ==9 && $pop_enabled ==1){

	$totimp=$stat_row['pop_impression'];
	
	
	?>
<td ><?php echo $totimp;?></td>

<?php if($adpricing !=9){?>
<td ><?php echo $this->get_label('na');?></td>
<td ><?php echo $this->get_label('na');?></td>
<?php }?>


<?php if(($adpricing ==-1 || $adpricing ==4 || $adpricing ==6) && $cpa_enabled ==1){?>
<td><?php echo $this->get_label('na');?></td>
<td><?php echo $this->get_label('na');?></td>
<?php }?>


<td ><?php echo $this->get_number_format($stat_row['pop_spend']);?></td>

<?php }else if(($value['display_type'] ==6 || $value['display_type'] ==4) && $cpa_enabled ==1){?>

<td ><?php echo $stat_row['cpa_impression'];?></td>

<?php if($adpricing !=9){?>
<td ><?php echo $stat_row['cpa_click'];?></td>
<td ><?php echo $stat_row['cpa_ctr'];?></td>
<?php }?>

<td><?php echo $stat_row['cpa_conversion'];?></td>
<td><?php echo $stat_row['cpa_ratio'];?></td>
<td ><?php echo $this->get_number_format($stat_row['cpa_spend']);?></td>



<?php }else if($value['display_type'] ==13 && $cpv_enabled ==1){?>
<td><?php echo $stat_row['cpv_impression'];?></td>

<?php if($adpricing !=9){?>
<td><?php echo $stat_row['cpv_click'];?></td>
<td><?php echo $stat_row['cpv_ctr'];?></td>
<?php }?>

<?php if(($adpricing ==-1 || $adpricing ==4 || $adpricing ==6) && $cpa_enabled ==1){?>
<td><?php echo $this->get_label('na');?></td>
<td><?php echo $this->get_label('na');?></td>
<?php }?>

<td><?php echo $this->get_number_format($stat_row['cpv_spend']);?></td>

<?php }?>



</tr>

<?php if($value['display_type'] ==4 && $cpm_data_enabled ==1 && $cpc_enabled ==1){

	$totimp=0;
	if($cpm_enabled ==1)
	$totimp=$stat_row['cpm_impression'];
	
	if($html_enabled ==1)
	$totimp=$totimp+$stat_row['html_impression'];
	
	
	
	?>
	<tr class="row_data_tr">
	<td class="border-left"><?php echo $this->get_label('cpm');?></td>
	<td ><?php echo $totimp;?></td>
	
	<?php if($adpricing !=9){?>
	<?php if($cpm_enabled ==1){?>
	<td ><?php echo $stat_row['cpm_click'];?></td>
	<td ><?php echo $stat_row['cpm_ctr'];?></td>
	<?php }else{?>
	<td ><?php echo $this->get_label('na');?></td>
	<td ><?php echo $this->get_label('na');?></td>
	<?php }?>
	<?php }?>
	
	
	<?php if(($adpricing ==-1 || $adpricing ==4 || $adpricing ==6) && $cpa_enabled ==1){?>
	<td><?php echo $this->get_label('na');?></td>
	<td><?php echo $this->get_label('na');?></td>
	<?php }?>
	
	
	<?php if($cpm_enabled ==1){?>
	<td ><?php echo $this->get_number_format($stat_row['cpm_spend']);?></td>
	<?php }else{?>
	<td ><?php echo $this->get_label('na');?></td>
	<?php }?>
	</tr>

<?php }?>



<?php if($value['display_type'] ==4 && $cpa_enabled ==1 && ($cpc_enabled ==1 || $cpm_data_enabled ==1)){?>

<tr class="row_data_tr">

<td class="border-left"><?php echo $this->get_label('cpa');?></td>
<td><?php echo $stat_row['cpa_impression'];?></td>

<?php if($adpricing !=9){?>
<td ><?php echo $stat_row['cpa_click'];?></td>
<td ><?php echo $stat_row['cpa_ctr'];?></td>
<?php }?>


<td><?php echo $stat_row['cpa_conversion'];?></td>
<td><?php echo $stat_row['cpa_ratio'];?></td>

<td><?php echo $this->get_number_format($stat_row['cpa_spend']);?></td>
</tr>
<?php }?>








<?php
}?>

<tr><td colspan="13" align="center"><?php echo $this->get_variable('pagination');?></td></tr>
</table>
<script type="text/javascript">
$(document).ready(function() {

	$("#adpricing").change(function()
	{
		<?php if($category_enabled ==1 && $category_enabled_ads !=""){?>
		LoadSiteData();
		<?php }?>
	});

	<?php if($category_enabled ==1 && $category_enabled_ads !=""){?>
	LoadSiteData();
	<?php }?>

	<?php if($affiliate_enabled ==1){?>
	$('#affiliate-data').hide();
	<?php }?>


});

<?php if($category_enabled ==1 && $category_enabled_ads !=""){?>
function LoadSiteData()
{
	$(".site-class").hide();
	var selected=$("#adpricing").val();

	var allowed='<?php echo $category_enabled_ads;?>';
	
	if(selected ==-1 && allowed !="")
	{
		$(".site-class").show();
		return;
	}

	
	
	if(allowed !='')
	{
		allowed_array=allowed.split('_');
	
		if($.inArray(selected , allowed_array) >-1)
		$(".site-class").show();
		else
		$("#sid").val(0);	
	}
}
<?php }?>
</script>
<?php $this->dispatch("layout/footer");?>	