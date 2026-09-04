<?php 
$this->dispatch("layout/header/6/5/p");
$duration=$this->get_variable('duration');
$uid=$this->get_variable('uid');
$sid=$this->get_variable('sid');
?>
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



<div class="container"><h2 class="page_heading new_heading"><?php echo $this->get_label('statistics of your adunits');?></h2>
<div class="page_heading-btm"></div>
</div>


<div class="container">	
<?php 
$cpc_enabled=$this->get_addon_status('cpc_enabled');
$cpa_enabled=$this->get_addon_status('cpa_enabled');
$cpm_enabled=$this->get_addon_status('cpm_enabled');
$html_enabled=$this->get_addon_status('html_enabled');
$category_enabled=$this->get_addon_status('category-targeting_enabled');
$sponsored_enabled=$this->get_addon_status('sponsored_enabled');
$affiliate_enabled=$this->get_addon_status('affiliate-ads_enabled');





$adpricing=$this->get_variable('adpricing');


	$stringarray=array();
	if($category_enabled ==1)
	{
		$category_enabled_ads=Configuration::get_instance()->read('category_enabled_ads');

		if($category_enabled_ads !='')
		$stringarray=explode('_',$category_enabled_ads);
	}




if($from_date =='' && $duration ==7)
	$duration=1;

$form1=$this->create_form();
$form1->start("adunitstatistics",$this->make_url("adunit/statistics"),"post");
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
<?php echo $this->get_pricing_box($adpricing,3);?>
</div>

<?php if($category_enabled ==1 && $category_enabled_ads !=""){?>
<div class="form-group site-class search_div_items" style="width: 150px;display: none;">
<?php echo CategoryHelper::get_site_dropdown($uid,$sid);?>
</div>
<?php }?>

<div class="form-group search_div_items">
<input class="btn btn-danger" type="submit" name="search" value="<?php echo $this->get_label('go');?>" />
</div>
</div>
<?php $form1->end(); ?> 	

<table id="table-desktop" class="data_table" cellpadding="0" cellspacing="0" >
<tr class="data_table_head">
<td><?php echo $this->get_label('name');?></td>
<?php if($category_enabled ==1 && $category_enabled_ads !="" && ($adpricing ==-1 || in_array($adpricing,$stringarray))){?>
<td><?php echo $this->get_label('site name');?></td>
<?php }?>
<td><?php echo $this->get_label('adunit type');?></td>


<?php if($adpricing !=12){?>
<td><?php echo $this->get_label('impressions');?></td>
<?php }?>

<?php if($adpricing !=9){?>
<td><?php echo $this->get_label('clicks');?></td>

<?php if($adpricing !=12){?>
<td><bdi><?php echo $this->get_label('ctr');?></bdi></td>
<td><bdi><?php echo $this->get_label('ecpm');?> &nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</bdi></td>
<?php }?>


<?php }?>


<?php if(($adpricing ==-1 || $adpricing ==4 || $adpricing ==6 || $adpricing ==12) && ($cpa_enabled ==1 || $affiliate_enabled ==1)){?>
<td ><bdi><?php echo $this->get_label('conversions');?></bdi></td>
<td ><bdi><?php echo $this->get_label('conversion ratio');?></bdi></td>
<?php }?>


<td><bdi><?php echo $this->get_label('profit');?> &nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</bdi></td>
<td><?php echo $this->get_label('action');?></td>
</tr>
<?php 
$res=$this->get_result('res');
if(count($res)==0){?>
<tr class="data_table_message"><td colspan="11" ><?php echo $this->get_label('no records found');?></td></tr>
<?php }else {
	
	
$cpm_data_enabled=0;

	
if($cpm_enabled ==1 || $html_enabled ==1)	
$cpm_data_enabled=1;




foreach($res as $key=>$value)
{
	$aduid=$value['id'];
	
	if($from_date !='')   // for custom date range
	$statistics=$this->get_pub_date_range_statistics($from_date,$to_date,$uid,$aduid);
	else
	$statistics=$this->get_publisher_statistics($duration,$uid,$aduid);
	
	
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
	
	
<tr class="data_table_content">
<td rowspan="<?php echo $rowspan;?>"><?php echo $value['name'];?></td>

<?php if($category_enabled ==1 && $category_enabled_ads !=""){
if(($adpricing == -1 && in_array($value['display_type'],$stringarray)) || in_array($value['display_type'],$stringarray)){?>
<td rowspan="<?php echo $rowspan;?>"><?php echo CategoryHelper::get_site_name($value['sid']);?></td>
<?php }else if($adpricing == -1 && !in_array($value['display_type'],$stringarray)){?>
<td rowspan="<?php echo $rowspan;?>"><?php echo $this->get_label('na');?></td>
<?php }} ?>

<?php 
if(($value['display_type'] ==0 || $value['display_type'] ==4) && $cpc_enabled ==1){?>
<td><?php echo $this->get_label('ppc');?></td>
<?php } else if(($value['display_type'] ==1 || $value['display_type'] ==4) && $cpm_data_enabled ==1){?>
<td><?php echo $this->get_label('cpm');?></td>
<?php } else if(($value['display_type'] ==6 || $value['display_type'] ==4) && $cpa_enabled ==1){?>
<td><?php echo $this->get_label('cpa');?></td>
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
<td ><?php echo $statistics['impression'];?></td>

<?php if($adpricing !=9){?>
<td ><?php echo $statistics['click'];?></td>
<td ><?php echo $statistics['ctr'];?></td>
<td ><bdi><?php echo $this->get_number_format($statistics['ecpm']);?></bdi></td>
<?php }?>

<?php if(($adpricing ==-1 || $adpricing ==4 || $adpricing ==6 || $adpricing ==12) && ($cpa_enabled ==1 || $affiliate_enabled ==1)){?>
<td><?php echo $this->get_label('na');?></td>
<td><?php echo $this->get_label('na');?></td>
<?php }?>

<td ><bdi><?php echo $this->get_number_format($statistics['pub_profit']);?></bdi></td>
<?php } else if($value['display_type'] ==3){?>

<td ><?php echo $this->get_label('na');?></td>


<?php if($adpricing !=9){?>
<td ><?php echo $this->get_label('na');?></td>
<td ><?php echo $this->get_label('na');?></td>
<td ><?php echo $this->get_label('na');?></td>
<?php }?>

<?php if(($adpricing ==-1 || $adpricing ==4 || $adpricing ==6 || $adpricing ==12) && ($cpa_enabled ==1 || $affiliate_enabled ==1)){?>
<td><?php echo $this->get_label('na');?></td>
<td><?php echo $this->get_label('na');?></td>
<?php }?>
<td ><?php echo $this->get_label('na');?></td>

<?php }else if(($value['display_type'] ==1 || $value['display_type'] ==4) && $cpm_data_enabled ==1){

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
<td ><?php echo $totimp;?></td>


<?php if($adpricing !=9){?>
<?php if($cpm_enabled ==1){?>
<td ><?php echo $statistics['cpm_click'];?></td>
<td ><?php echo $statistics['cpm_ctr'];?></td>
<?php }else{?>
<td ><?php echo $this->get_label('na');?></td>
<td ><?php echo $this->get_label('na');?></td>
<?php }?>
<td ><?php echo $this->get_label('na');?></td>
<?php }?>




<?php if(($adpricing ==-1 || $adpricing ==4 || $adpricing ==6 || $adpricing ==12) && ($cpa_enabled ==1 || $affiliate_enabled ==1)){?>
<td><?php echo $this->get_label('na');?></td>
<td><?php echo $this->get_label('na');?></td>
<?php }?>

<td ><bdi><?php echo $this->get_number_format($totprofit);?></bdi></td>

<?php }else if($value['display_type'] ==9){

	$totimp=$statistics['pop_impression'];
	
	
	?>
<td ><?php echo $totimp;?></td>

<?php if($adpricing !=9){?>
<td ><?php echo $this->get_label('na');?></td>
<td ><?php echo $this->get_label('na');?></td>
<td ><?php echo $this->get_label('na');?></td>
<?php }?>


<?php if(($adpricing ==-1 || $adpricing ==4 || $adpricing ==6 || $adpricing ==12) && ($cpa_enabled ==1 || $affiliate_enabled ==1)){?>
<td><?php echo $this->get_label('na');?></td>
<td><?php echo $this->get_label('na');?></td>
<?php }?>

<td ><bdi><?php echo $this->get_number_format($statistics['pop_profit']);?></bdi></td>

<?php }else if(($value['display_type'] ==6 || $value['display_type'] ==4) && $cpa_enabled ==1){?>
<td ><?php echo $statistics['cpa_impression'];?></td>

<?php if($adpricing !=9){?>
<td ><?php echo $statistics['cpa_click'];?></td>
<td ><?php echo $statistics['cpa_ctr'];?></td>
<td ><?php echo $this->get_label('na');?></td>
<?php }?>


<td><?php echo $statistics['cpa_conversion'];?></td>
<td><?php echo $statistics['cpa_ratio'];?></td>

<td ><bdi><?php echo $this->get_number_format($statistics['cpa_profit']);?></bdi></td>


<?php }else if($value['display_type'] ==12){?>

<?php if($adpricing !=12){?>
<td ><?php echo $this->get_label('na');?></td>
<?php }?>


<?php if($adpricing !=9){?>
<td ><?php echo $statistics['affiliate_click'];?></td>

<?php if($adpricing !=12){?>
<td ><?php echo $this->get_label('na');?></td>
<td ><?php echo $this->get_label('na');?></td>
<?php }?>
<?php }?>


<td><?php echo $statistics['affiliate_conversion'];?></td>
<td><?php echo $statistics['affiliate_ratio'];?></td>
<td ><bdi><?php echo $this->get_number_format($statistics['affiliate_profit']);?></bdi></td>

<?php }else if($value['display_type'] ==13){?>
<td ><?php echo $statistics['cpv_impression'];?></td>

<?php if($adpricing !=9){?>
<td ><?php echo $statistics['cpv_click'];?></td>
<td ><?php echo $statistics['cpv_ctr'];?></td>
<td ><?php echo $this->get_label('na');?></td>
<?php }?>


<?php if(($adpricing ==-1 || $adpricing ==4 || $adpricing ==6 || $adpricing ==12) && ($cpa_enabled ==1 || $affiliate_enabled ==1)){?>
<td><?php echo $this->get_label('na');?></td>
<td><?php echo $this->get_label('na');?></td>
<?php }?>

<td ><bdi><?php echo $this->get_number_format($statistics['cpv_profit']);?></bdi></td>

<?php }?>


<?php if($value['display_type'] ==3){?>
<td rowspan="<?php echo $rowspan;?>"><a href="<?php echo $this->make_url("dispatch/sponsored/17/".$aduid);?>"><i class="fa fa-bar-chart report-icon" title="<?php echo $this->get_label('detailed');?>"></i></a></td>
<?php }else{?>
<td rowspan="<?php echo $rowspan;?>"><a href="<?php echo $this->make_url("adunit/detail_statistics/".$aduid);?>"><i class="fa fa-bar-chart report-icon" title="<?php echo $this->get_label('detailed');?>"></i></a></td>
<?php }?>

</tr>



<?php if($value['display_type'] ==4 && $cpm_data_enabled ==1 && $cpc_enabled ==1){

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
<td style="display: none;"><?php echo $value['name'];?></td>

<?php if($category_enabled ==1 && $category_enabled_ads !=""){
if(($adpricing == -1 && in_array($value['display_type'],$stringarray)) || in_array($value['display_type'],$stringarray)){?>
<td style="display: none;"><?php echo CategoryHelper::get_site_name($value['sid']);?></td>
<?php }else if($adpricing == -1 && !in_array($value['display_type'],$stringarray)){?>
<td style="display: none;"><?php echo $this->get_label('na');?></td>
<?php }} ?>


<td><?php echo $this->get_label('cpm');?></td>
<td><?php echo $totimp;?></td>



<?php if($adpricing !=9){?>
<?php if($cpm_enabled ==1){?>
<td ><?php echo $statistics['cpm_click'];?></td>
<td ><?php echo $statistics['cpm_ctr'];?></td>
<?php }else{?>
<td ><?php echo $this->get_label('na');?></td>
<td ><?php echo $this->get_label('na');?></td>
<?php }?>
<td ><?php echo $this->get_label('na');?></td>
<?php }?>




<?php if(($adpricing ==-1 || $adpricing ==4 || $adpricing ==6 || $adpricing ==12) && ($cpa_enabled ==1 || $affiliate_enabled ==1)){?>
<td><?php echo $this->get_label('na');?></td>
<td><?php echo $this->get_label('na');?></td>
<?php }?>

<td ><bdi><?php echo $this->get_number_format($totprofit);?></bdi></td>
<td style="display: none;"><a href="<?php echo $this->make_url("adunit/detail_statistics/".$aduid);?>"><i class="fa fa-bar-chart report-icon" title="<?php echo $this->get_label('detailed');?>"></i></a></td>
</tr>
<?php }?>

<?php if($value['display_type'] ==4 && $cpa_enabled ==1 && ($cpc_enabled ==1 || $cpm_data_enabled ==1)){?>

<tr class="data_table_content">
<td style="display: none;"><?php echo $value['name'];?></td>

<?php if($category_enabled ==1 && $category_enabled_ads !=""){
if(($adpricing == -1 && in_array($value['display_type'],$stringarray)) || in_array($value['display_type'],$stringarray)){?>
<td style="display: none;"><?php echo CategoryHelper::get_site_name($value['sid']);?></td>
<?php }else if($adpricing == -1 && !in_array($value['display_type'],$stringarray)){?>
<td style="display: none;"><?php echo $this->get_label('na');?></td>
<?php }} ?>


<td><?php echo $this->get_label('cpa');?></td>
<td><?php echo $statistics['cpa_impression'];?></td>

<?php if($adpricing !=9){?>
<td ><?php echo $statistics['cpa_click'];?></td>
<td ><?php echo $statistics['cpa_ctr'];?></td>
<td ><?php echo $this->get_label('na');?></td>
<?php }?>


<td><?php echo $statistics['cpa_conversion'];?></td>
<td><?php echo $statistics['cpa_ratio'];?></td>

<td ><bdi><?php echo $this->get_number_format($statistics['cpa_profit']);?></bdi></td>
<td style="display: none;"><a href="<?php echo $this->make_url("adunit/detail_statistics/".$aduid);?>"><i class="fa fa-bar-chart report-icon" title="<?php echo $this->get_label('detailed');?>"></i></a></td>
</tr>


<?php }?>





<?php 
}}?>		
</table>
<?php echo $this->get_variable('pagination');?>
<div style="height: 20px;"></div>
</div>
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