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
<?php 
$duration=$this->get_variable('duration');
$pid=$this->get_variable('pid');
?>
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
	$('.custom-date-div').show();
	else
	{
		$('.custom-date-div').hide();

		$('#from_date').val('');
		$('#to_date').val('');
	}
}

$(document).ready(function() {
	CreateResponsiveTable('table-desktop');
	
	$(window).resize(function()
	{
	 	CreateResponsiveTable('table-desktop');
	});
});
</script>

<div class="container"><h2 class="page_heading"><?php echo $this->get_label('statistics of your sites');?></h2></div>

<div class="container">		
<?php 
$cpc_enabled=$this->get_addon_status('cpc_enabled');
$cpa_enabled=$this->get_addon_status('cpa_enabled');
$cpm_enabled=$this->get_addon_status('cpm_enabled');
$html_enabled=$this->get_addon_status('html_enabled');
$interstitial_enabled=$this->get_addon_status('interstitial_enabled');
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
 
 if($cpv_enabled ==1)
 $rowspan=$rowspan+1;	
 

if($from_date =='' && $duration ==7)
$duration=1;

$form1=$this->create_form();
$form1->start("sitestatistics","","post");
?>
<div class="search_div" style="float: left;width: 100%;">
<div class="form-group search_div_items" style="width: 160px;">
<select class="form-control" name="duration" id="duration" style="width: 150px;">
<option value="1" <?php if($duration==1) { echo "selected"; } ?>><?php echo $this->get_label('today');?></option>
<option value="6" <?php if($duration==6) { echo "selected"; } ?>><?php echo $this->get_label('yesterday');?></option>
<option value="2" <?php if($duration==2) { echo "selected"; } ?>><?php echo $this->get_label('last 14');?></option>
<option value="3" <?php if($duration==3) { echo "selected"; } ?>><?php echo $this->get_label('last 30');?></option>
<option value="4" <?php if($duration==4) { echo "selected"; } ?>><?php echo $this->get_label('last 12 month');?></option>
<option value="5" <?php if($duration==5) { echo "selected"; } ?>><?php echo $this->get_label('all time');?></option>
<option value="7" <?php if($duration==7) { echo "selected"; } ?>><?php echo $this->get_label('custom date');?></option>
</select>
</div>

<div class="form-group custom-date-div search_div_items" style="width: 225px;"> 
<input class="form-control" type="text" readonly="readonly" name="from_date" id="from_date" value="<?php echo $from_date;?>" placeholder="<?php echo $this->get_label('from date');?>" />  
&nbsp;
<input class="form-control" type="text" readonly="readonly" name="to_date" id="to_date" value="<?php echo $to_date;?>" placeholder="<?php echo $this->get_label('to date');?>" />
</div>

<div class="form-group search_div_items">
<input class="btn btn-danger" type="submit" name="search" value="<?php echo $this->get_label('go');?>" />
</div>
</div>
<?php $form1->end(); ?> 	

<table id="table-desktop" class="data_table" cellpadding="0" cellspacing="0" >
<tr class="data_table_head">
<td><?php echo $this->get_label('site name');?></td>

<?php if($rowspan >0){?>
<td><?php echo $this->get_label('type');?></td>
<td><?php echo $this->get_label('impressions');?></td>
<td><?php echo $this->get_label('clicks');?></td>
<td><bdi><?php echo $this->get_label('ctr');?></bdi></td>
<td><bdi><?php echo $this->get_label('ecpm');?> &nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</bdi></td>

<?php if($cpa_enabled ==1){?>
<td width="100px"><bdi><?php echo $this->get_label('conversions');?></bdi></td>
<td width="140px"><bdi><?php echo $this->get_label('conversion ratio');?></bdi></td>
<?php }?>

<td><bdi><?php echo $this->get_label('profit');?> &nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</bdi></td>
<td><?php echo $this->get_label('action');?></td>
<?php }?>

</tr>
<?php 
$res=$this->get_result('res');
if(count($res)==0)
{?>
<tr class="data_table_message"><td colspan="10" ><?php echo $this->get_label('no records found');?></td></tr>
<?php 
}
else
{
	

foreach($res as $key=>$value)
{
	$enterflag=0;
	
	
	$sid=$value['id'];
	
	if($from_date !='')   // for custom date range
	$statistics=$this->get_pub_date_range_statistics($from_date,$to_date,$pid,0,0,$sid);
	else
	$statistics=$this->get_publisher_statistics($duration,$pid,0,0,$sid);
	

	?>

<?php if($cpc_enabled ==1){	?>	
<tr class="data_table_content">
<td rowspan="<?php echo $rowspan;?>"><a href="<?php echo $this->make_url("adunit/statistics/".$sid,BASE);?>" ><?php echo $value['url'];?></a></td>
<td><?php echo $this->get_label('ppc');?></td>
<td ><?php echo $statistics['impression'];?></td>
<td ><?php echo $statistics['click'];?></td>
<td ><?php echo $statistics['ctr'];?></td>
<td ><bdi><?php echo $this->get_number_format($statistics['ecpm']);?></bdi></td>

<?php if($cpa_enabled ==1){?>
<td ><?php echo $this->get_label('na');?></td>
<td ><?php echo $this->get_label('na');?></td>
<?php }?>

<td ><bdi><?php echo $this->get_number_format($statistics['pub_profit']);?></bdi></td>

<td rowspan="<?php echo $rowspan;?>">
<a href="<?php echo $this->make_url("dispatch/category_targeting/12/".$sid,BASE);?>"><i class="fa fa-bar-chart report-icon" title="<?php echo $this->get_label('detailed');?>"></i></a>
</td>
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
<tr class="data_table_content">
<td <?php if($enterflag ==1){?>style="display: none;"<?php }?> <?php if($enterflag ==0){?>rowspan="<?php echo $rowspan;?>"<?php }?>><a href="<?php echo $this->make_url("adunit/statistics/".$sid,BASE);?>" ><?php echo $value['url'];?></a></td>
<td><?php echo $this->get_label('cpm');?></td>
<td><?php echo $totimp;?></td>

<?php if($cpm_enabled ==1){?>
<td ><?php echo $statistics['cpm_click'];?></td>
<td ><?php echo $statistics['cpm_ctr'];?></td>
<?php }else{?>
<td><?php echo $this->get_label('na');?></td>
<td><?php echo $this->get_label('na');?></td>
<?php }?>
<td><?php echo $this->get_label('na');?></td>

<?php if($cpa_enabled ==1){?>
	<td ><?php echo $this->get_label('na');?></td>
	<td ><?php echo $this->get_label('na');?></td>
<?php }?>

<td><bdi><?php echo $this->get_number_format($totprofit);?></bdi></td>

<td <?php if($enterflag ==1){?>style="display: none;"<?php }?> <?php if($enterflag ==0){?>rowspan="<?php echo $rowspan;?>"<?php }?>><a href="<?php echo $this->make_url("dispatch/category_targeting/12/".$sid,BASE);?>"><i class="fa fa-bar-chart report-icon" title="<?php echo $this->get_label('detailed');?>"></i></a></td>

</tr>
<?php 
$enterflag=1;
}?>



<?php if($cpa_enabled ==1){

		$totimp=$statistics['cpa_impression'];
		$totprofit=$statistics['cpa_profit'];
		$totspend=$statistics['cpa_spend'];
	
	
	?>
<tr class="data_table_content">
<td <?php if($enterflag ==1){?>style="display: none;"<?php }?> <?php if($enterflag ==0){?>rowspan="<?php echo $rowspan;?>"<?php }?>><a href="<?php echo $this->make_url("adunit/statistics/".$sid,BASE);?>" ><?php echo $value['url'];?></a></td>
<td ><?php echo $this->get_label('cpa');?></td>
<td ><?php echo $totimp;?></td>
<td ><?php echo $statistics['cpa_click'];?></td>
<td ><?php echo $statistics['cpa_ctr'];?></td>
<td><?php echo $this->get_label('na');?></td>


	<td ><?php echo $statistics['cpa_conversion'];?></td>
	<td ><?php echo $statistics['cpa_ratio'];?></td>



<td ><?php echo $this->get_number_format($totprofit);?></td>
<td <?php if($enterflag ==1){?>style="display: none;"<?php }?> <?php if($enterflag ==0){?>rowspan="<?php echo $rowspan;?>"<?php }?>><a href="<?php echo $this->make_url("dispatch/category_targeting/12/".$sid,BASE);?>"><i class="fa fa-bar-chart report-icon" title="<?php echo $this->get_label('detailed');?>"></i></a></td>
</tr>
<?php 
$enterflag=1;
}?>





<?php if($cpv_enabled ==1){ ?>
<tr class="data_table_content">
<td <?php if($enterflag ==1){?>style="display: none;"<?php }?> <?php if($enterflag ==0){?>rowspan="<?php echo $rowspan;?>"<?php }?>><a href="<?php echo $this->make_url("adunit/statistics/".$sid,BASE);?>" ><?php echo $value['url'];?></a></td>
<td ><?php echo $this->get_label('cpv');?></td>
<td ><?php echo $statistics['cpv_impression'];?></td>
<td ><?php echo $statistics['cpv_click'];?></td>
<td ><?php echo $statistics['cpv_ctr'];?></td>
<td><?php echo $this->get_label('na');?></td>


<?php if($cpa_enabled ==1){?>
<td ><?php echo $this->get_label('na');?></td>
<td ><?php echo $this->get_label('na');?></td>
<?php }?>


<td ><?php echo $this->get_number_format($statistics['cpv_profit']);?></td>
<td <?php if($enterflag ==1){?>style="display: none;"<?php }?> <?php if($enterflag ==0){?>rowspan="<?php echo $rowspan;?>"<?php }?>><a href="<?php echo $this->make_url("dispatch/category_targeting/12/".$sid,BASE);?>"><i class="fa fa-bar-chart report-icon" title="<?php echo $this->get_label('detailed');?>"></i></a></td>
</tr>
<?php 
$enterflag=1;
}?>


<?php 
if($pop_enabled ==1){

	$totimp=$statistics['pop_impression'];
	$totprofit=$statistics['pop_profit'];
	$totspend=$statistics['pop_spend'];
	
	
	
	?>
<tr class="data_table_content">
<td <?php if($enterflag ==1){?>style="display: none;"<?php }?> <?php if($enterflag ==0){?>rowspan="<?php echo $rowspan;?>"<?php }?>><a href="<?php echo $this->make_url("adunit/statistics/".$sid,BASE);?>" ><?php echo $value['url'];?></a></td>
<td><?php echo $this->get_label('pop');?></td>
<td><?php echo $totimp;?></td>

<td><?php echo $this->get_label('na');?></td>
<td><?php echo $this->get_label('na');?></td>
<td><?php echo $this->get_label('na');?></td>

<?php if($cpa_enabled ==1){?>
	<td ><?php echo $this->get_label('na');?></td>
	<td ><?php echo $this->get_label('na');?></td>
<?php }?>

<td><bdi><?php echo $this->get_number_format($totprofit);?></bdi></td>

<td <?php if($enterflag ==1){?>style="display: none;"<?php }?> <?php if($enterflag ==0){?>rowspan="<?php echo $rowspan;?>"<?php }?>><a href="<?php echo $this->make_url("dispatch/category_targeting/12/".$sid,BASE);?>"><i class="fa fa-bar-chart report-icon" title="<?php echo $this->get_label('detailed');?>"></i></a></td>

</tr>
<?php }?>




<?php 
}}?>		
</table>
<?php echo $this->get_variable('pagination');?>
<div style="height: 20px;"></div>
</div>