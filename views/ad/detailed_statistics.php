<?php 
$this->dispatch("layout/header/2/3/a");

$uid=$this->get_variable('uid');
$aid=$this->get_variable('aid');

$duration=$this->get_variable('duration');
$tab=$this->get_variable('tab');


$keywords=$this->get_result('keyword');
$count=$this->get_variable('count');


$rowdata=$this->get_result("rowdata");
$val=$rowdata[0];

$adtype			= $val['type'];
$pricing_value	= $val['display_type'];


$retargeting_enabled=$this->get_addon_status('retargeting_enabled');

$retargeting=0;

if($retargeting_enabled ==1)
$retargeting=$val['retargeting'];


$expandable_banner="";
$expandable=0;
$expandable_enabled=$this->get_addon_status('expandable-banners_enabled');

if($adtype ==2 && $expandable_enabled ==1)
{
	$expandable_banner=$val['expandable_banner'];
	$expandable=$val['expandable'];
	
	if($expandable_banner =="")
	$expandable=0;	
}

if($adtype ==2 || $adtype ==5 || $adtype ==7 || $adtype ==10 || $adtype ==11)
{
	$diamensions_string=$this->get_banner_dimension($val['banner_id']);
	
	$diamensions_array=explode('-',$diamensions_string);
	
	$diamensions=$diamensions_array[0]." x ".$diamensions_array[1];
}
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
function show_adv_stat(id)
{
	$('.tabcontent').hide();
	$('.report_main_table_tab').removeClass('report_main_table_tab_temp');

	$('#tab').val(id);
	$('#showstat'+id).show();
	$('#tab_'+id).addClass('report_main_table_tab_temp');
}



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
	$('.custom-date-div').show();
	else
	{
		$('.custom-date-div').hide();

		$('#from_date').val('');
		$('#to_date').val('');
	}
}



<?php if($pricing_value !=3){?>
$(document).ready(function() {
	CreateResponsiveTable('table-desktop');

	<?php if($pricing_value !=12){?>
	CreateResponsiveTable('table-desktop1');
	<?php }?>
	
	CreateResponsiveTable('table-desktop2');
	
	$(window).resize(function()
	{
	 	CreateResponsiveTable('table-desktop');

	 	<?php if($pricing_value !=12){?>
		CreateResponsiveTable('table-desktop1');
		<?php }?>
		
		CreateResponsiveTable('table-desktop2');
	});
});
<?php }?>


</script>

<div class="container">
<h2 class="page_heading"><?php echo $this->get_label('detailed reports');?>

<div style="float: right;">
<a href="<?php echo $this->make_url("ad/view/".$aid);?>"><i class="fa fa-edit edit-icon" title="<?php echo $this->get_label('edit ad');?>"></i></a>


<a href="<?php echo $this->make_url("ad/delete/".$aid);?>" onclick="return confirm('<?php echo $this->get_message('do you really want to delete this ad');?>')"><i class="fa fa-trash delete-icon" title="<?php echo $this->get_label('delete');?>"></i></a>

<?php if($val['status']==1){
if($val['pause_status']==0){?>	
<a href="<?php echo $this->make_url("ad/update_pause_status/".$aid."/1/1");?>"><i class="fa fa-pause pause-icon" title="<?php echo $this->get_label('pause this ad');?>"></i></a>
<?php }if($val['pause_status']==1){?>
<a href="<?php echo $this->make_url("ad/update_pause_status/".$aid."/2/1");?>"><i class="fa fa-play-circle-o resume-icon" title="<?php echo $this->get_label('resume this ad');?>"></i></a>
<?php }}?>
</div>
</h2>


</div>

<div class="container">
<?php 


if($from_date =='' && $duration ==7)
$duration=6;

$form2=$this->create_form();
$form2->start("manageads",$this->make_url("ad/detailed_statistics/".$aid),"post");
?>
  
<div class="search_div" style="float: left;width: 100%;">
<div class="form-group search_div_items" style="width: 180px;">
<select class="form-control" name="duration" id="duration" style="width: 150px;">
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
<input type="hidden" name="tab" id="tab" value="1"/>
<input class="btn btn-danger" type="submit" name="search" value="<?php echo $this->get_label('go');?>" />
</div>
</div>
<?php $form2->end(); ?> 	
</div>



<div class="container">
<h2 class="login_header_inner view_head_div search_div" style="margin-bottom: 8px;width: 100%;padding: 10px;min-height: 40px;"><bdi>

<?php if($retargeting ==1){?>
<i class="fa fa-recycle" title="<?php echo $this->get_label('retargeting');?>"></i>&nbsp;
<?php }?>

<?php echo $this->get_label('name');?> : <?php echo $val['name'];?><?php if($adtype ==2 || $adtype ==7){ echo  ' - '.$diamensions;}?>

<?php if($adtype ==2 && $expandable ==1){ echo $this->get_label('expandable');} ?>
<?php 
echo " - ".$this->get_ad_pricing($aid,$pricing_value);
		
	
if($adtype ==13)
{
	$aspect_ratio=$this->get_aspect_ratio_value($val['aspect_ratio']);
	
	if($aspect_ratio >0)
	echo '<span style="font-size:12px;"> - '.$this->get_label('aspect ratio').' : '.$aspect_ratio.'</span>';
}
?>
</bdi>
<div class="adstate span_link_inner2"><bdi>
<?php echo $this->get_label('status');?> : 
<?php if($val['status']==-1) {?><span class="pending"><i class="fa fa-spinner"></i> <?php echo $this->get_ad_status($val['status']);?></span><?php }?>
<?php if($val['status']==1) {?><span class="active"><i class="fa fa-check-square-o"></i> <?php echo $this->get_ad_status($val['status']);?></span><?php }?>
<?php if($val['status']==0) {?><span class="block"><i class="fa fa-ban"></i> <?php echo $this->get_ad_status($val['status']);?></span><?php }?>
<?php if($val['status']==-2) {?><span class="pending"><i class="fa fa-spinner"></i> <?php echo $this->get_ad_status($val['status']);?></span><?php }?>
<?php if($val['status']==1 && $val['pause_status']==1){?>
(<?php echo $this->get_label('paused');?>)	
<?php }?></bdi>
</div>
</h2>
</div>




<div class="container">
<?php $this->dispatch("ad/preview/".$aid."/1");?>
</div>

<div style="height: 20px;"></div>

<div class="container">
<?php if($pricing_value !=3){?>

  <ul>
  <li class="col-md-3 col-sm-3 col-xs-12 report_main_table_tab" id="tab_1" onclick="show_adv_stat(1);"><?php echo $this->get_label('overall statistics');?></li>
<?php if(Configuration::get_instance()->read('keyword_based_ad_display') ==1){?>
  
  <?php if($pricing_value !=12){?>
  <li class="col-md-3 col-sm-3 col-xs-12 report_main_table_tab" id="tab_2" onclick="show_adv_stat(2);"><?php echo $this->get_label('keyword based statistics');?></li>
  <?php }?>
  <?php }?>
  
  <li class="col-md-3 col-sm-3 col-xs-12 report_main_table_tab" id="tab_3" onclick="show_adv_stat(3);"><?php echo $this->get_label('time based statistics');?></li>
  </ul>




<table cellpadding="0" cellspacing="0" class="report_main_table">
  <tr ><td ><div class="under_li_main1"></div></td></tr>
  
    
  
  
   <tr id="showstat1" class="tabcontent">
  <td colspan="5">
 
 <div class="report_main_table_data1">
<table id="table-desktop" class="data_table" cellpadding="0" cellspacing="0">
<tr class="data_table_head">


<?php if($pricing_value == 6){?>
<td width="100px"><?php echo $this->get_label('pricing');?></td>
<?php }?>

<?php if($pricing_value !=12){?>
<td width="100px"><?php echo $this->get_label('impressions');?></td>
<?php }?>


<?php if($pricing_value !=9 && $adtype !=9){?>
<td width="100px"><?php echo $this->get_label('clicks');?></td>

<?php if($pricing_value !=12){?>
<td width="100px"><bdi><?php echo $this->get_label('ctr');?></bdi></td>
<?php }?>

<?php }?>


<?php if($pricing_value ==6 || $pricing_value ==12){?>
<td style="width: 120px;"><?php echo $this->get_label('conversions');?></td>

<?php if($adtype !=9){?>
<td style="width: 120px;"><bdi><?php echo $this->get_label('conversion ratio');?></bdi></td>
<?php }}?>

<td width="100px"><bdi><?php echo $this->get_label('money spend');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</bdi></td>
</tr>


<?php 

if($from_date !='')  // for custom date range
	$statistics=$this->get_adv_date_range_statistics($from_date,$to_date,$uid,$aid);
else
	$statistics=$this->get_advertiser_statistics($duration,$uid,$aid);
	


?>	
	<tr class="data_table_content">
	
	<?php if($pricing_value == 6){?>
	<td width="100px"><?php echo $this->get_label('cpa');?></td>
	<?php }?>	
	
	
	<?php if($pricing_value ==0){?>
	<td ><?php echo $statistics['impression'];?></td>
	<td ><?php echo $statistics['click'];?></td>
	<td ><?php echo $statistics['ctr'];?></td>
	<td ><bdi><?php echo $this->get_number_format($statistics['money_spent']);?></bdi></td>
	<?php } else if($pricing_value ==1){?>
	<td ><?php echo $statistics['cpm_impression'];?></td>
	
	<?php if($adtype !=9){?>
	<td ><?php echo $statistics['cpm_click'];?></td>
	<td ><?php echo $statistics['cpm_ctr'];?></td>
	<?php }?>
	
	<td ><bdi><?php echo $this->get_number_format($statistics['cpm_spend']);?></bdi></td>
	
	
	<?php } else if($pricing_value ==9){?>
	<td ><?php echo $statistics['pop_impression'];?></td>
	<td ><bdi><?php echo $this->get_number_format($statistics['pop_spend']);?></bdi></td>
	<?php } else if($pricing_value ==6){?>
	<td ><?php echo $statistics['cpa_impression'];?></td>
	
	
	<?php if($adtype !=9){?>
	<td ><?php echo $statistics['cpa_click'];?></td>
	<td ><?php echo $statistics['cpa_ctr'];?></td>
	<?php }?>
	
	
	<td><?php echo $statistics['cpa_conversion'];?></td>
	
	<?php if($adtype !=9){?>
	<td><?php echo $statistics['cpa_ratio'];?></td>
	<?php }?>
	
	<td ><bdi><?php echo $this->get_number_format($statistics['cpa_spend']);?></bdi></td>	
	<?php } else if($pricing_value ==12){?>
	<td ><?php echo $statistics['affiliate_click'];?></td>
	<td><?php echo $statistics['affiliate_conversion'];?></td>
	<td><?php echo $statistics['affiliate_ratio'];?></td>
	<td ><bdi><?php echo $this->get_number_format($statistics['affiliate_spend']);?></bdi></td>
	
	<?php } else if($pricing_value ==13){?>
	<td ><?php echo $statistics['cpv_impression'];?></td>
	<td ><?php echo $statistics['cpv_click'];?></td>
	<td ><?php echo $statistics['cpv_ctr'];?></td>
	<td ><bdi><?php echo $this->get_number_format($statistics['cpv_spend']);?></bdi></td>
	<?php }?>
	
	</tr>
	
	<?php if($pricing_value == 6){?>
	<tr class="data_table_content">
	<td width="100px"><?php echo $this->get_label('cpm');?></td>	
	<td ><?php echo $statistics['cpm_impression'];?></td>
	
	<?php if($adtype !=9){?>
	<td ><?php echo $statistics['cpm_click'];?></td>
	<td ><?php echo $statistics['cpm_ctr'];?></td>
	<?php }?>
	
	<td ><?php echo $statistics['cpm_conversion'];?></td>
	
	<?php if($adtype !=9){?>
	<td ><?php echo $statistics['cpm_ratio'];?></td>
	<?php }?>
	
	<td ><bdi><?php echo $this->get_number_format($statistics['cpm_spend']);?></bdi></td>
	</tr>
	<?php }?>

</table>
</div>
  </td>
  </tr>
  
  
  



<?php if(Configuration::get_instance()->read('keyword_based_ad_display') ==1){?>

<?php if($pricing_value !=12){?>
<tr id="showstat2" class="tabcontent">
<td colspan="5">
 
 <div class="report_main_table_data1">
<table id="table-desktop1" class="data_table" cellpadding="0" cellspacing="0">
<tr class="data_table_head">
<td width="160px"><?php echo $this->get_label('keyword');?></td>

<?php if($pricing_value == 6){?>
<td width="100px"><?php echo $this->get_label('pricing');?></td>
<?php }?>

<td width="100px"><?php echo $this->get_label('impressions');?></td>

<?php if($pricing_value !=9 && $adtype !=9){?>
<td width="75px"><?php echo $this->get_label('clicks');?></td>
<td width="75px"><bdi><?php echo $this->get_label('ctr');?></bdi></td>
<?php }?>



<?php if($pricing_value ==6){?>
<td style="width: 120px;"><?php echo $this->get_label('conversions');?></td>

<?php if($pricing_value !=9 && $adtype !=9){?>
<td style="width: 120px;"><bdi><?php echo $this->get_label('conversion ratio');?></bdi></td>
<?php }}?>


<td width="110px"><bdi><?php echo $this->get_label('money spend');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</bdi></td>
</tr>

<?php 

	$rowspan = 1;
	
	if($pricing_value == 6)
	$rowspan = 2;



if($count >0)
{

	
foreach($keywords as $key=>$row)
{
	$mpid=$this->get_mapping_id($uid,$aid,$row['id']);
	
	
	if($from_date !='')  // for custom date range
	$statistics=$this->get_adv_date_range_statistics($from_date,$to_date,$uid,$aid,$row['id']);
	else
	$statistics=$this->get_advertiser_statistics($duration,$uid,$aid,$row['id']);
	

?>	
	<tr class="data_table_content">
	<td rowspan="<?php echo $rowspan;?>"><?php echo $row['keyword'];?></td>
	
	<?php if($pricing_value == 6){?>
	<td style="width: 100px;"><?php echo $this->get_label('cpa');?></td>
	<?php }?>
	
	
	<?php if($pricing_value ==0){?>
	<td ><?php echo $statistics['impression'];?></td>
	<td ><?php echo $statistics['click'];?></td>
	<td ><?php echo $statistics['ctr'];?></td>
	<td ><bdi><?php echo $this->get_number_format($statistics['money_spent']);?></bdi></td>
	<?php } else if($pricing_value ==1){?>
	<td ><?php echo $statistics['cpm_impression'];?></td>
	
	<?php if($pricing_value !=9 && $adtype !=9){?>
	<td ><?php echo $statistics['cpm_click'];?></td>
	<td ><?php echo $statistics['cpm_ctr'];?></td>
	<?php }?>
	
	<td ><bdi><?php echo $this->get_number_format($statistics['cpm_spend']);?></bdi></td>
	
	
	<?php } else if($pricing_value ==9){?>
	<td ><?php echo $statistics['pop_impression'];?></td>
	<td ><bdi><?php echo $this->get_number_format($statistics['pop_spend']);?></bdi></td>
	<?php } else if($pricing_value ==6){?>
	<td ><?php echo $statistics['cpa_impression'];?></td>
	
	<?php if($adtype !=9){?>
	<td ><?php echo $statistics['cpa_click'];?></td>
	<td ><?php echo $statistics['cpa_ctr'];?></td>
	<?php }?>
	
	<td><?php echo $statistics['cpa_conversion'];?></td>
	
	<?php if($adtype !=9){?>
	<td><?php echo $statistics['cpa_ratio'];?></td>
	<?php }?>
	
	<td ><bdi><?php echo $this->get_number_format($statistics['cpa_spend']);?></bdi></td>
	<?php } else if($pricing_value ==13){?>
	<td ><?php echo $statistics['cpv_impression'];?></td>
	<td ><?php echo $statistics['cpv_click'];?></td>
	<td ><?php echo $statistics['cpv_ctr'];?></td>
	<td ><bdi><?php echo $this->get_number_format($statistics['cpv_spend']);?></bdi></td>
	<?php }?>
	</tr>
	

	
	<?php if($pricing_value == 6){?>
	<tr class="data_table_content">
	<td rowspan="<?php echo $rowspan;?>" style="display: none;"><?php echo $row['keyword'];?></td>
	
	<td style="width: 100px;"><?php echo $this->get_label('cpm');?></td>
	<td ><?php echo $statistics['cpm_impression'];?></td>
	
	<?php if($adtype !=9){?>
	<td ><?php echo $statistics['cpm_click'];?></td>
	<td ><?php echo $statistics['cpm_ctr'];?></td>
	<?php }?>
	<td><?php echo $statistics['cpm_conversion'];?></td>
	<?php if($adtype !=9){?>
	<td><?php echo $statistics['cpm_ratio'];?></td>
	<?php }?>	
	<td ><bdi><?php echo $this->get_number_format($statistics['cpm_spend']);?></bdi></td>
	</tr>
	<?php }?>	
	

		
<?php 	
	
}	

if($from_date !='')  // for custom date range
$statistics_global=$this->get_adv_date_range_statistics($from_date,$to_date,$uid,$aid,-2);
else
$statistics_global=$this->get_advertiser_statistics($duration,$uid,$aid,-2);


?>


<tr class="data_table_content">
<td rowspan="<?php echo $rowspan;?>"><?php echo $this->get_label("global targeted");?></td>

	<?php if($pricing_value == 6){?>
	<td style="width: 100px;"><?php echo $this->get_label('cpa');?></td>
	<?php }?>


	<?php if($pricing_value ==0){?>
	<td ><?php echo $statistics_global['impression'];?></td>
	<td ><?php echo $statistics_global['click'];?></td>
	<td ><?php echo $statistics_global['ctr'];?></td>
	<td ><bdi><?php echo $this->get_number_format($statistics_global['money_spent']);?></bdi></td>
	<?php } else if($pricing_value ==1){?>
	<td ><?php echo $statistics_global['cpm_impression'];?></td>
	
	<?php if($pricing_value !=9 && $adtype !=9){?>
	<td ><?php echo $statistics_global['cpm_click'];?></td>
	<td ><?php echo $statistics_global['cpm_ctr'];?></td>
	<?php }?>
	
	
	<td ><bdi><?php echo $this->get_number_format($statistics_global['cpm_spend']);?></bdi></td>
	
	
	<?php } else if($pricing_value ==9){?>
	<td ><?php echo $statistics_global['pop_impression'];?></td>
	<td ><bdi><?php echo $this->get_number_format($statistics_global['pop_spend']);?></bdi></td>
	
	
	<?php } else if($pricing_value ==6){?>
	<td ><?php echo $statistics_global['cpa_impression'];?></td>
	
	<?php if($adtype !=9){?>
	<td ><?php echo $statistics_global['cpa_click'];?></td>
	<td ><?php echo $statistics_global['cpa_ctr'];?></td>
	<?php }?>
	
	<td><?php echo $statistics_global['cpa_conversion'];?></td>
	
	<?php if($adtype !=9){?>
	<td><?php echo $statistics_global['cpa_ratio'];?></td>
	<?php }?>
	
	<td ><bdi><?php echo $this->get_number_format($statistics_global['cpa_spend']);?></bdi></td>
	
	<?php } else if($pricing_value ==13){?>
	<td ><?php echo $statistics_global['cpv_impression'];?></td>
	<td ><?php echo $statistics_global['cpv_click'];?></td>
	<td ><?php echo $statistics_global['cpv_ctr'];?></td>
	<td ><bdi><?php echo $this->get_number_format($statistics_global['cpv_spend']);?></bdi></td>

	
	<?php }?>
</tr>


	<?php if($pricing_value == 6){?>
	<tr class="data_table_content">
	<td rowspan="<?php echo $rowspan;?>" style="display: none;"><?php echo $this->get_label("global targeted");?></td>
	
	<td style="width: 100px;"><?php echo $this->get_label('cpm');?></td>
	<td ><?php echo $statistics_global['cpm_impression'];?></td>
	
	<?php if($adtype !=9){?>
	<td ><?php echo $statistics_global['cpm_click'];?></td>
	<td ><?php echo $statistics_global['cpm_ctr'];?></td>
	<?php }?>
	<td><?php echo $statistics_global['cpm_conversion'];?></td>
	<?php if($adtype !=9){?>
	<td><?php echo $statistics_global['cpm_ratio'];?></td>
	<?php }?>	
	<td ><bdi><?php echo $this->get_number_format($statistics_global['cpm_spend']);?></bdi></td>
	</tr>
	<?php }?>






<?php 
}
else
{
	
	if($from_date !='')  // for custom date range
		$statistics_global=$this->get_adv_date_range_statistics($from_date,$to_date,$uid,$aid,-2);
	else
	$statistics_global=$this->get_advertiser_statistics($duration,$uid,$aid,-2);
	

?>


	<tr class="data_table_content">
	<td rowspan="<?php echo $rowspan;?>"><?php echo $this->get_label("global targeted");?></td>
	
	<?php if($pricing_value == 6){?>
	<td style="width: 100px;"><?php echo $this->get_label('cpa');?></td>
	<?php }?>	
	
	
	<?php if($pricing_value ==0){?>
	<td ><?php echo $statistics_global['impression'];?></td>
	<td ><?php echo $statistics_global['click'];?></td>
	<td ><?php echo $statistics_global['ctr'];?></td>
	<td ><bdi><?php echo $this->get_number_format($statistics_global['money_spent']);?></bdi></td>
	<?php } else if($pricing_value ==1){?>
	<td ><?php echo $statistics_global['cpm_impression'];?></td>
	
	<?php if($pricing_value !=9 && $adtype !=9){?>
	<td ><?php echo $statistics_global['cpm_click'];?></td>
	<td ><?php echo $statistics_global['cpm_ctr'];?></td>
	<?php }?>
	
	
	<td ><bdi><?php echo $this->get_number_format($statistics_global['cpm_spend']);?></bdi></td>
	
	<?php } else if($pricing_value ==9){?>
	<td ><?php echo $statistics_global['pop_impression'];?></td>
	<td ><bdi><?php echo $this->get_number_format($statistics_global['pop_spend']);?></bdi></td>
	
	
	<?php } else if($pricing_value ==6){?>
	<td ><?php echo $statistics_global['cpa_impression'];?></td>
	
	<?php if($adtype !=9){?>
	<td ><?php echo $statistics_global['cpa_click'];?></td>
	<td ><?php echo $statistics_global['cpa_ctr'];?></td>
	<?php }?>
	
	
	<td><?php echo $statistics_global['cpa_conversion'];?></td>
	
	<?php if($adtype !=9){?>
	<td><?php echo $statistics_global['cpa_ratio'];?></td>
	<?php }?>
	
	<td ><bdi><?php echo $this->get_number_format($statistics_global['cpa_spend']);?></bdi></td>
	
	<?php } else if($pricing_value ==13){?>
	<td ><?php echo $statistics_global['cpv_impression'];?></td>
	<td ><?php echo $statistics_global['cpv_click'];?></td>
	<td ><?php echo $statistics_global['cpv_ctr'];?></td>
	<td ><bdi><?php echo $this->get_number_format($statistics_global['cpv_spend']);?></bdi></td>

	<?php }?>	
	</tr>	
	
	
	<?php if($pricing_value == 6){?>
	<tr class="data_table_content">
	<td rowspan="<?php echo $rowspan;?>" style="display: none;"><?php echo $this->get_label("global targeted");?></td>
	
	<td style="width: 100px;"><?php echo $this->get_label('cpm');?></td>
	<td ><?php echo $statistics_global['cpm_impression'];?></td>
	
	<?php if($adtype !=9){?>
	<td ><?php echo $statistics_global['cpm_click'];?></td>
	<td ><?php echo $statistics_global['cpm_ctr'];?></td>
	<?php }?>
	<td><?php echo $statistics_global['cpm_conversion'];?></td>
	<?php if($adtype !=9){?>
	<td><?php echo $statistics_global['cpm_ratio'];?></td>
	<?php }?>	
	<td ><bdi><?php echo $this->get_number_format($statistics_global['cpm_spend']);?></bdi></td>
	</tr>
	<?php }?>	
	
	

<?php 
}	

?>
</table>
 </div>
 
 
 
  </td>
  </tr>
<?php }?>  
<?php }?>  
  
  
  
  
  
  
    <tr id="showstat3" class="tabcontent">
  <td colspan="5">
  <div class="report_main_table_data2">
<table id="table-desktop2" class="data_table" cellpadding="0" cellspacing="0">
<tr class="data_table_head">
<td width="100px"><?php echo $this->get_label('date');?></td>

<?php if($pricing_value == 6){?>
<td width="100px"><?php echo $this->get_label('pricing');?></td>
<?php }?>


<?php if($pricing_value !=12){?>
<td width="100px"><?php echo $this->get_label('impressions');?></td>
<?php }?>

<?php if($pricing_value !=9 && $adtype !=9){?>
<td width="100px"><?php echo $this->get_label('clicks');?></td>

<?php if($pricing_value !=12){?>
<td width="100px"><bdi><?php echo $this->get_label('ctr');?></bdi></td>
<?php }}?>

<?php if($pricing_value ==6 || $pricing_value ==12){?>
<td style="width: 120px;"><?php echo $this->get_label('conversions');?></td>

<?php if($pricing_value !=9 && $adtype !=9){?>
<td style="width: 120px;"><bdi><?php echo $this->get_label('conversion ratio');?></bdi></td>
<?php }}?>

<td width="100px"><bdi><?php echo $this->get_label('money spend');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</bdi></td>
</tr>


<?php 

if($from_date !='')  // for custom date range
$statistics=$this->get_adv_date_range_timeperiod_statistics($from_date,$to_date,$uid,$aid);
else
$statistics=$this->get_advertiser_timeperiod_statistics($duration,$uid,$aid);
	
	
	$rowspan = 1;
	
	if($pricing_value == 6)
	$rowspan = 2;	
	
	
	foreach($statistics as $key=>$value)
	{
	
		
	 $year=substr($key,0,4);	
	 $month=substr($key,4,2);			
	 $day=substr($key,6,2);	
	 $hour=substr($key,8,2);		
		
	 
	 if($hour !="")
	 $data=$year."-".$month."-".$day."-".$hour;
	 else 
	 $data=$year."-".$month."-".$day;
	 
	 
	 
	 
	 if($day=="")
	 $data=$year."-".$month;
	 if($day=="" && $month=="")
	 $data=$year;	
	 
?>	
	<tr class="data_table_content">
	<td rowspan="<?php echo $rowspan;?>"><?php echo $data;?></td>
	
	<?php if($pricing_value == 6){?>
	<td style="width: 100px;"><?php echo $this->get_label('cpa');?></td>
	<?php }?>	
	
	<?php if($pricing_value ==0){?>
	<td ><?php echo $value['impression'];?></td>
	<td ><?php echo $value['click'];?></td>
	<td ><?php echo $value['ctr'];?></td>
	<td ><bdi><?php echo $this->get_number_format($value['money_spent']);?></bdi></td>
	<?php } else if($pricing_value ==1){?>
	<td ><?php echo $value['cpm_impression'];?></td>
	
	<?php if($pricing_value !=9 && $adtype !=9){?>	
	<td ><?php echo $value['cpm_click'];?></td>
	<td ><?php echo $value['cpm_ctr'];?></td>
	<?php }?>
	
	<td ><bdi><?php echo $this->get_number_format($value['cpm_spend']);?></bdi></td>
	
	<?php } else if($pricing_value ==9){?>
	<td ><?php echo $value['pop_impression'];?></td>
	<td ><bdi><?php echo $this->get_number_format($value['pop_spend']);?></bdi></td>
	
	
	<?php } else if($pricing_value ==6){?>
	<td ><?php echo $value['cpa_impression'];?></td>
	
	<?php if($adtype !=9){?>
	<td ><?php echo $value['cpa_click'];?></td>
	<td ><?php echo $value['cpa_ctr'];?></td>
	<?php }?>
	
	
	<td><?php echo $value['cpa_conversion'];?></td>
	
	<?php if($adtype !=9){?>
	<td><?php echo $value['cpa_ratio'];?></td>
	<?php }?>
	
	<td ><bdi><?php echo $this->get_number_format($value['cpa_spend']);?></bdi></td>
	
	
	<?php } else if($pricing_value ==12){?>
	<td ><?php echo $value['affiliate_click'];?></td>
	<td><?php echo $value['affiliate_conversion'];?></td>
	<td><?php echo $value['affiliate_ratio'];?></td>
	<td ><bdi><?php echo $this->get_number_format($value['affiliate_spend']);?></bdi></td>
	
	<?php } else if($pricing_value ==13){?>
	<td ><?php echo $value['cpv_impression'];?></td>
	<td ><?php echo $value['cpv_click'];?></td>
	<td ><?php echo $value['cpv_ctr'];?></td>
	<td ><bdi><?php echo $this->get_number_format($value['cpv_spend']);?></bdi></td>
	<?php }?>	
	</tr>	
	
	
	<?php if($pricing_value == 6){?>
	<tr class="data_table_content">
	<td rowspan="<?php echo $rowspan;?>" style="display: none;"><?php echo $data;?></td>
	
	<td style="width: 100px;"><?php echo $this->get_label('cpm');?></td>
	<td ><?php echo $value['cpm_impression'];?></td>
	
	<?php if($adtype !=9){?>
	<td ><?php echo $value['cpm_click'];?></td>
	<td ><?php echo $value['cpm_ctr'];?></td>
	<?php }?>
	<td><?php echo $value['cpm_conversion'];?></td>
	<?php if($adtype !=9){?>
	<td><?php echo $value['cpm_ratio'];?></td>
	<?php }?>	
	<td ><bdi><?php echo $this->get_number_format($value['cpm_spend']);?></bdi></td>
	</tr>
	<?php }?>	

	
	<?php }?>

</table>

</div>
	
   </td>
  </tr>


</table>

<?php }?>
<div style="height: 20px;"></div>
</div>
<?php $this->dispatch("layout/footer");?>

<?php if($pricing_value !=3){?>
<script type="text/javascript">
show_adv_stat(<?php echo $tab;?>);
</script>
<?php }?>