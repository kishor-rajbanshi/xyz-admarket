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
<?php 
$cpc_enabled=$this->get_addon_status('cpc_enabled');
$cpa_enabled=$this->get_addon_status('cpa_enabled');
$cpm_enabled=$this->get_addon_status('cpm_enabled');
$html_enabled=$this->get_addon_status('html_enabled');
$pop_enabled=$this->get_addon_status('pop-ads_enabled');
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


$duration=$this->get_variable('duration');
$pub=$this->get_variable('pub');
?>

<div class="sub_menu_main"><?php echo $this->get_label('statistics of publisher sites');?></div>

<?php $this->dispatch("links/links/34");?>


<table style="width: 100%;" >
<tr><td colspan="2">
<div class="search_div">
<?php  
$form=$this->create_form();
$form->start("showstatistics","","post");
?>
<table class="search_div_table">
<?php 
if($from_date =='' && $duration ==7)
$duration=1;
?>
<tr>
<td>
<select name="pub" id="pub" style="width: 117px;">
<option value="-1" <?php if($pub==-1) echo "selected";?>><?php echo $this->get_label('all publishers');?></option>
<?php 
$res1=$this->get_result('res1');
foreach($res1 as $key1=>$value1){?>
<option value="<?php echo $value1['id'];?>" <?php if($pub==$value1['id']) echo "selected";?>><?php echo $value1['username'];?></option>
<?php }?>
</select>
</td>
<td>
&nbsp;&nbsp;
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
</td>
<td><input type="submit" name="stat" value="<?php echo $this->get_label('go');?>"/></td>
</tr>
</table>
<?php $form->end(); ?>
</div>
</td></tr>
<tr><td colspan="4" style="height: 10px;"></td></tr>
</table>


<table class="data_table" cellpadding="0" cellspacing="0">
<tr class="row_heading_tr">
<td width="180px"><?php echo $this->get_label('site name');?></td>
<td width="120px"><?php echo $this->get_label('owner');?></td>
<td width="80px"><?php echo $this->get_label('options');?></td>


<?php if($rowspan >0){?>
<td width="80px"><?php echo $this->get_label('type');?></td>
<td width="100px"><?php echo $this->get_label('impressions');?></td>
<td width="80px"><?php echo $this->get_label('clicks');?></td>
<td width="80px"><?php echo $this->get_label('ctr');?></td>

<?php if($cpa_enabled ==1){?>
<td style="width: 100px;"><?php echo $this->get_label('conversions');?></td>
<td style="width: 100px;"><?php echo $this->get_label('conversion ratio');?></td>
<?php }?>


<td width="100px"><?php echo $this->get_label('money spend');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>
<td width="100px"><?php echo $this->get_label('pubprofit');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>
<td width="120px"><?php echo $this->get_label('yourprofit');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>
<?php }?>
</tr>
<?php 

	 
$res=$this->get_result('res');

if(count($res) >0)
{

foreach($res as $key=>$value)
{
	$enterflag=0;
	
	
$sid=$value['id'];
$pid=$value['pid'];

if($from_date !='')   // for custom date range
$stat_row=$this->get_pub_date_range_statistics($from_date,$to_date,$pid,0,0,$sid);
else
$stat_row=$this->get_publisher_statistics($duration,$pid,0,0,$sid);
?>

<?php if($cpc_enabled ==1){	?>
<tr class="row_data_tr">
<td rowspan="<?php echo $rowspan;?>"><a href="<?php echo $this->make_url("report/adcodes_publisher/",ADMIN_DIR);?>"><?php echo $value['url'];?></a></td>
<td rowspan="<?php echo $rowspan;?>"><a href="<?php echo $this->make_url("user/profile/".$value['pid']."/0",ADMIN_DIR);?>"><?php echo $this->escape($this->get_user_name($value['pid']));?></a></td>
<td rowspan="<?php echo $rowspan;?>"><a href="<?php echo $this->make_url("dispatch/category_targeting/22/".$sid,ADMIN_DIR);?>"><i class="fa fa-bar-chart report-icon" title="<?php echo $this->get_label('details');?>"></i></a></td>

<td class="border-left"><?php echo $this->get_label('ppc');?></td>
<td ><?php echo $stat_row['impression'];?></td>
<td ><?php echo $stat_row['click'];?></td>
<td ><?php echo $stat_row['ctr'];?></td>

	<?php if($cpa_enabled ==1){?>
	<td ><?php echo $this->get_label('na');?></td>
	<td ><?php echo $this->get_label('na');?></td>
	<?php }?>
	
<td ><?php echo $this->get_number_format($stat_row['money_spent']);?></td>
<td ><?php echo $this->get_number_format($stat_row['pub_profit']);?></td>
<td ><?php echo $this->get_number_format(($stat_row['money_spent']-$stat_row['pub_profit']));?></td>
</tr>
<?php 
$enterflag=1;
}?>

<?php 

	$totimp=0;
	$totprofit=0;
	$totspend=0;
if($cpm_enabled ==1 || $html_enabled ==1){



	if($cpm_enabled ==1)
	{
		$totimp=$stat_row['cpm_impression'];
		$totprofit=$stat_row['cpm_profit'];
		$totspend=$stat_row['cpm_spend'];
	
	}
	
	if($html_enabled ==1)
	{
		$totimp=$totimp+$stat_row['html_impression'];
		$totprofit=$totprofit+$stat_row['html_profit'];
		$totspend=$totspend+$stat_row['html_profit'];
	}
	
	
	?>
<tr class="row_data_tr">
<td <?php if($enterflag ==1){?>style="display: none;"<?php }?> <?php if($enterflag ==0){?>rowspan="<?php echo $rowspan;?>"<?php }?>><a href="<?php echo $this->make_url("report/adcodes_publisher/",ADMIN_DIR);?>"><?php echo $value['url'];?></a></td>
<td <?php if($enterflag ==1){?>style="display: none;"<?php }?> <?php if($enterflag ==0){?>rowspan="<?php echo $rowspan;?>"<?php }?>><a href="<?php echo $this->make_url("user/profile/".$value['pid']."/0",ADMIN_DIR);?>"><?php echo $this->escape($this->get_user_name($value['pid']));?></a></td>
<td <?php if($enterflag ==1){?>style="display: none;"<?php }?> <?php if($enterflag ==0){?>rowspan="<?php echo $rowspan;?>"<?php }?>><a href="<?php echo $this->make_url("dispatch/category_targeting/22/".$sid,ADMIN_DIR);?>" class="link_button"><?php echo $this->get_label('detailed');?></a></td>

<td class="border-left"><?php echo $this->get_label('cpm');?></td>
<td><?php echo $totimp;?></td>

<?php if($cpm_enabled ==1){?>
<td ><?php echo $stat_row['cpm_click'];?></td>
<td ><?php echo $stat_row['cpm_ctr'];?></td>

	<?php if($cpa_enabled ==1){?>
	<td ><?php echo $this->get_label('na');?></td>
	<td ><?php echo $this->get_label('na');?></td>
	<?php }?>

<td ><?php echo $this->get_number_format($stat_row['cpm_spend']);?></td>
<?php }else{?>
<td><?php echo $this->get_label('na');?></td>
<td><?php echo $this->get_label('na');?></td>


	<?php if($cpa_enabled ==1){?>
	<td ><?php echo $this->get_label('na');?></td>
	<td ><?php echo $this->get_label('na');?></td>
	<?php }?>

<td><?php echo $this->get_label('na');?></td>
<?php }?>

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



<?php 

	$totimp=0;
	$totprofit=0;
	$totspend=0;
if($cpa_enabled ==1){

		$totimp=$stat_row['cpa_impression'];
		$totprofit=$stat_row['cpa_profit'];
		$totspend=$stat_row['cpa_spend'];
		
	?>
<tr class="row_data_tr">
<td <?php if($enterflag ==1){?>style="display: none;"<?php }?> <?php if($enterflag ==0){?>rowspan="<?php echo $rowspan;?>"<?php }?>><a href="<?php echo $this->make_url("report/adcodes_publisher/",ADMIN_DIR);?>"><?php echo $value['url'];?></a></td>
<td <?php if($enterflag ==1){?>style="display: none;"<?php }?> <?php if($enterflag ==0){?>rowspan="<?php echo $rowspan;?>"<?php }?>><a href="<?php echo $this->make_url("user/profile/".$value['pid']."/0",ADMIN_DIR);?>"><?php echo $this->escape($this->get_user_name($value['pid']));?></a></td>
<td <?php if($enterflag ==1){?>style="display: none;"<?php }?> <?php if($enterflag ==0){?>rowspan="<?php echo $rowspan;?>"<?php }?>><a href="<?php echo $this->make_url("dispatch/category_targeting/22/".$sid,ADMIN_DIR);?>" class="link_button"><?php echo $this->get_label('detailed');?></a></td>

<td class="border-left"><?php echo $this->get_label('cpa');?></td>
<td><?php echo $totimp;?></td>
<td ><?php echo $stat_row['cpa_click'];?></td>
<td ><?php echo $stat_row['cpa_ctr'];?></td>

	<td ><?php echo $stat_row['cpa_conversion'];?></td>
	<td ><?php echo $stat_row['cpa_ratio'];?></td>


<td ><?php echo $this->get_number_format($stat_row['cpa_spend']);?></td>
<td ><?php echo $this->get_number_format($totprofit);?></td>
<td ><?php echo $this->get_number_format(($stat_row['cpa_spend']-$stat_row['cpa_profit']));?></td>
</tr>
<?php 
$enterflag=1;
}?>




<?php if($cpv_enabled ==1){	?>
<tr class="row_data_tr">
<td <?php if($enterflag ==1){?>style="display: none;"<?php }?> <?php if($enterflag ==0){?>rowspan="<?php echo $rowspan;?>"<?php }?>><a href="<?php echo $this->make_url("report/adcodes_publisher/",ADMIN_DIR);?>"><?php echo $value['url'];?></a></td>
<td <?php if($enterflag ==1){?>style="display: none;"<?php }?> <?php if($enterflag ==0){?>rowspan="<?php echo $rowspan;?>"<?php }?>><a href="<?php echo $this->make_url("user/profile/".$value['pid']."/0",ADMIN_DIR);?>"><?php echo $this->escape($this->get_user_name($value['pid']));?></a></td>
<td <?php if($enterflag ==1){?>style="display: none;"<?php }?> <?php if($enterflag ==0){?>rowspan="<?php echo $rowspan;?>"<?php }?>><a href="<?php echo $this->make_url("dispatch/category_targeting/22/".$sid,ADMIN_DIR);?>" class="link_button"><?php echo $this->get_label('detailed');?></a></td>

<td class="border-left"><?php echo $this->get_label('cpv');?></td>
<td><?php echo $stat_row['cpv_impression'];?></td>
<td ><?php echo $stat_row['cpv_click'];?></td>
<td ><?php echo $stat_row['cpv_ctr'];?></td>


	<?php if($cpa_enabled ==1){?>
	<td ><?php echo $this->get_label('na');?></td>
	<td ><?php echo $this->get_label('na');?></td>
	<?php }?>


<td ><?php echo $this->get_number_format($stat_row['cpv_spend']);?></td>
<td ><?php echo $this->get_number_format($stat_row['cpv_profit']);?></td>
<td ><?php echo $this->get_number_format(($stat_row['cpv_spend']-$stat_row['cpv_profit']));?></td>
</tr>
<?php 
$enterflag=1;
}?>




<?php if($pop_enabled ==1){	

		$totimp=$stat_row['pop_impression'];
		$totprofit=$stat_row['pop_profit'];
		$totspend=$stat_row['pop_spend'];
	
	
	?>
<tr class="row_data_tr">
<td <?php if($enterflag ==1){?>style="display: none;"<?php }?> <?php if($enterflag ==0){?>rowspan="<?php echo $rowspan;?>"<?php }?>><a href="<?php echo $this->make_url("report/adcodes_publisher/",ADMIN_DIR);?>"><?php echo $value['url'];?></a></td>
<td <?php if($enterflag ==1){?>style="display: none;"<?php }?> <?php if($enterflag ==0){?>rowspan="<?php echo $rowspan;?>"<?php }?>><a href="<?php echo $this->make_url("user/profile/".$value['pid']."/0",ADMIN_DIR);?>"><?php echo $this->escape($this->get_user_name($value['pid']));?></a></td>
<td <?php if($enterflag ==1){?>style="display: none;"<?php }?> <?php if($enterflag ==0){?>rowspan="<?php echo $rowspan;?>"<?php }?>><a href="<?php echo $this->make_url("dispatch/category_targeting/22/".$sid,ADMIN_DIR);?>" class="link_button"><?php echo $this->get_label('detailed');?></a></td>

<td class="border-left"><?php echo $this->get_label('pop');?></td>
<td><?php echo $totimp;?></td>

<td><?php echo $this->get_label('na');?></td>
<td><?php echo $this->get_label('na');?></td>

	<?php if($cpa_enabled ==1){?>
	<td ><?php echo $this->get_label('na');?></td>
	<td ><?php echo $this->get_label('na');?></td>
	<?php }?>

<td ><?php echo $this->get_number_format($totspend);?></td>
<td ><?php echo $this->get_number_format($totprofit);?></td>
<td ><?php echo $this->get_number_format(($totspend-$totprofit));?></td>

</tr>
<?php }?>





<?php }?>
<tr><td colspan="12" align="center"><?php echo $this->get_variable('pagination');?></td></tr>
<?php }else{?>
<tr><td colspan="11" style="padding-left: 5px;" height="30px"><?php echo $this->get_label('no records found');?></td></tr>
<?php }?>
</table>	