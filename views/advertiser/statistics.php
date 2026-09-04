<?php $this->dispatch("layout/header/4/1/a");?>
<link rel="stylesheet" type="text/css" media="all" href="//code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/jquery-ui-1.8.23.custom.min.js' ></script>

<script type="text/javascript">
$(document).ready(function() {
	 $("#from_date").datepicker({dateFormat : 'dd/mm/yy'});
	 $("#to_date").datepicker({dateFormat : 'dd/mm/yy'});
});
</script>
<?php 
$countrywise_data_tracking=Configuration::get_instance()->read('countrywise_data_tracking');


$cpc_enabled=$this->get_addon_status('cpc_enabled');
$cpa_enabled=$this->get_addon_status('cpa_enabled');
$cpm_enabled=$this->get_addon_status('cpm_enabled');
$pop_enabled=$this->get_addon_status('pop-ads_enabled');
$affiliate_enabled=$this->get_addon_status('affiliate-ads_enabled');
$cpv_enabled=$this->get_addon_status('video-ads_enabled');



	 $rowspan=0;
	
	 if($cpc_enabled ==1)
	 $rowspan=$rowspan+1;
	 
	 if($cpm_enabled ==1)
	 $rowspan=$rowspan+1;
	 
	 if($cpa_enabled ==1)
	 $rowspan=$rowspan+1;
	 
	 if($pop_enabled ==1)
	 $rowspan=$rowspan+1;
	 
	 if($affiliate_enabled ==1)
	 $rowspan=$rowspan+1;
  
	 if($cpv_enabled ==1)
	 $rowspan=$rowspan+1;   


$uid=$this->get_variable('uid');

$duration=$this->get_variable('duration');
$tab=$this->get_variable('tab');


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

	if(document.forms['pagination1'])
	document.forms['pagination1'].tab.value=id;
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

$(document).ready(function() {
	
	CreateResponsiveTable('table-desktop');
	CreateResponsiveTable('table-desktop1');

	<?php if($cpc_enabled ==1){?>	
	CreateResponsiveTable('table-desktop2');
	<?php }?>

	<?php if($countrywise_data_tracking ==1){?>
	CreateResponsiveTable('table-desktop3');
	<?php }?>
	
	$(window).resize(function()
	{
	 	CreateResponsiveTable('table-desktop');
		CreateResponsiveTable('table-desktop1');

		<?php if($cpc_enabled ==1){?>		
		CreateResponsiveTable('table-desktop2');
		<?php }?>

		<?php if($countrywise_data_tracking ==1){?>
		CreateResponsiveTable('table-desktop3');
		<?php }?>
	});
});


</script>
<style type="text/css">
.report_main_table_tab
{
max-width:23%;
}

@media (max-width: 767px)
{
	.report_main_table_tab
	{
		max-width:100%;
	}
}
</style>


<div class="container">
<h2 class="page_heading new_heading"><?php echo $this->get_label('overall statistics of your ads');?></h2>
<div class="page_heading-btm"></div>
</div>





<div class="container">

<?php 
if($from_date =='' && $duration ==7)
$duration=1;

$form2=$this->create_form();
$form2->start("overall",$this->make_url("advertiser/statistics"),"post");
?>
<div class="search_div" style="float: left;width: 100%;">
<div class="form-group search_div_items" style="width: 180px;">
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
<input type="hidden" name="tab" id="tab" value="1" />
<input class="btn btn-danger" type="submit" name="search" value="<?php echo $this->get_label('go');?>" />
</div>
</div>
<?php $form2->end(); ?> 	




  <ul>
  <li class="col-md-3 col-sm-3 col-xs-12 report_main_table_tab" id="tab_1" onclick="show_adv_stat(1);"><?php echo $this->get_label('overall statistics');?></li>
  <li class="col-md-3 col-sm-3 col-xs-12 report_main_table_tab" id="tab_2" onclick="show_adv_stat(2);"><?php echo $this->get_label('time based statistics');?></li>
  
  <?php if($cpc_enabled ==1){?>
  <li class="col-md-3 col-sm-3 col-xs-12 report_main_table_tab" id="tab_3" onclick="show_adv_stat(3);"><?php echo $this->get_label('click analysis');?></li>
  <?php }?>
  
  <?php if($countrywise_data_tracking ==1){?>
  <li class="col-md-3 col-sm-3 col-xs-12 report_main_table_tab" id="tab_4" onclick="show_adv_stat(4);"><?php echo $this->get_label('geo report');?></li>
  <?php }?>
  
  </ul>



<table cellpadding="0" cellspacing="0" class="report_main_table" >
  <tr ><td class="special-report" ><div class="under_li_main1"></div></td></tr>
  
   
  
  
  <tr id="showstat1" class="tabcontent">
  <td  >
 
 <div class="report_main_table_data1">
<table id="table-desktop" class="data_table" cellpadding="0" cellspacing="0">
<tr class="data_table_head">
<td width="160px"></td>

<?php if($cpc_enabled ==1){?>
<td <?php if($cpm_enabled ==1 || $cpa_enabled ==1 || $cpv_enabled ==1 || $pop_enabled ==1 || $affiliate_enabled ==1){?> style="width: 150px;"<?php }?>><?php echo $this->get_label('ppc');?></td>
<?php }?>

<?php if($cpm_enabled ==1){?>
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



<?php 


if($from_date !='')  // for custom date range
	$statistics=$this->get_adv_date_range_statistics($from_date,$to_date,$uid);
else
	$statistics=$this->get_advertiser_statistics($duration,$uid);
	
?>	
	<tr class="data_table_content">
	<td ><?php echo $this->get_label('impressions');?></td>
	
	<?php if($cpc_enabled ==1){?>
	<td ><?php echo $statistics['impression'];?></td>
	<?php }?>
	
	<?php if($cpm_enabled ==1){?>
	<td ><?php echo $statistics['cpm_impression'];?></td>
	<?php }?>
	
	<?php if($cpa_enabled ==1){?>
	<td ><?php echo $statistics['cpa_impression'];?></td>
	<?php }?>
	
	<?php if($cpv_enabled ==1){?>
	<td ><?php echo $statistics['cpv_impression'];?></td>
	<?php }?>	
	
	<?php if($pop_enabled ==1){?>
	<td ><?php echo $statistics['pop_impression'];?></td>
	<?php }?>
	
	<?php if($affiliate_enabled ==1){?>
	<td><?php echo $this->get_label('na');?></td>
	<?php }?>
	
	</tr>
	
	
	<tr class="data_table_content">
	<td ><?php echo $this->get_label('clicks');?></td>
	
	<?php if($cpc_enabled ==1){?>
	<td ><?php echo $statistics['click'];?></td>
	<?php }?>
	
	<?php if($cpm_enabled ==1){?>
	<td ><?php echo $statistics['cpm_click'];?></td>
	<?php }?>
	
	<?php if($cpa_enabled ==1){?>
	<td ><?php echo $statistics['cpa_click'];?></td>
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
	
	</tr>
	
	
	
	<tr class="data_table_content">
	<td ><bdi><?php echo $this->get_label('ctr');?></bdi></td>
	
	<?php if($cpc_enabled ==1){?>
	<td ><?php echo $statistics['ctr'];?></td>
	<?php }?>
	
	<?php if($cpm_enabled ==1){?>
	<td ><?php echo $statistics['cpm_ctr'];?></td>
	<?php }?>
	
	<?php if($cpa_enabled ==1){?>
	<td ><?php echo $statistics['cpa_ctr'];?></td>
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
	<tr class="data_table_content">
	<td ><bdi><?php echo $this->get_label('conversions');?></bdi></td>
	
	<?php if($cpc_enabled ==1){?>
	<td ><?php echo $this->get_label('na');?></td>
	<?php }?>
	
	<?php if($cpm_enabled ==1){?>
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
	
	
	<tr class="data_table_content">
	<td ><bdi><?php echo $this->get_label('conversion ratio');?></bdi></td>
	
	<?php if($cpc_enabled ==1){?>
	<td ><?php echo $this->get_label('na');?></td>
	<?php }?>
	
	<?php if($cpm_enabled ==1){?>
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
	
	
	
	
	<tr class="data_table_content">
	<td ><bdi><?php echo $this->get_label('money spend');?> &nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</bdi></td>
	
	<?php if($cpc_enabled ==1){?>
	<td ><bdi><?php echo $this->get_number_format($statistics['money_spent']);?></bdi></td>
	<?php }?>
	
	<?php if($cpm_enabled ==1){?>
	<td ><bdi><?php echo $this->get_number_format($statistics['cpm_spend']);?></bdi></td>
	<?php }?>
	
	<?php if($cpa_enabled ==1){?>
	<td ><bdi><?php echo $this->get_number_format($statistics['cpa_spend']);?></bdi></td>
	<?php }?>
	
	<?php if($cpv_enabled ==1){?>
	<td ><bdi><?php echo $this->get_number_format($statistics['cpv_spend']);?></bdi></td>
	<?php }?>		
	
	<?php if($pop_enabled ==1){?>
	<td ><bdi><?php echo $this->get_number_format($statistics['pop_spend']);?></bdi></td>
	<?php }?>
	
	
	<?php if($affiliate_enabled ==1){?>
	<td ><bdi><?php echo $this->get_number_format($statistics['affiliate_spend']);?></bdi></td>
	<?php }?>
	
	</tr>
</table>
</div>

  </td>
  </tr>
  
  
 
  
    <tr id="showstat2" class="tabcontent">
  <td >
   <div class="report_main_table_data1">
   

   
   
   
   
   
<table id="table-desktop1" class="data_table" cellpadding="0" cellspacing="0">
<tr class="data_table_head">
<td width="100px"><?php echo $this->get_label('date');?></td>


<?php if($rowspan >0){?>
<td width="100px"><?php echo $this->get_label('type');?></td>
<td width="100px"><?php echo $this->get_label('impressions');?></td>
<td width="60px"><?php echo $this->get_label('clicks');?></td>
<td width="60px"><bdi><?php echo $this->get_label('ctr');?></bdi></td>

<?php if($cpa_enabled ==1 || $affiliate_enabled ==1){?>
<td width="100px"><bdi><?php echo $this->get_label('conversions');?></bdi></td>
<td width="100px"><bdi><?php echo $this->get_label('conversion ratio');?></bdi></td>
<?php }?>

<td width="100px"><bdi><?php echo $this->get_label('money spend');?> &nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</bdi></td>
<?php }?>
</tr>


<?php 

if($from_date !='')  // for custom date range
$statistics=$this->get_adv_date_range_timeperiod_statistics($from_date,$to_date,$uid);
else
$statistics=$this->get_advertiser_timeperiod_statistics($duration,$uid);
	
	 	
	foreach($statistics as $key=>$value)
	{
		
		$enterflag=0;
	
		
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





	<?php if($cpc_enabled ==1){	?>
	<tr class="data_table_content">
	<td rowspan="<?php echo $rowspan;?>"><?php echo $data;?></td>
	
	<td ><?php echo $this->get_label('ppc');?></td>
	<td ><?php echo $value['impression'];?></td>
	<td ><?php echo $value['click'];?></td>
	<td ><?php echo $value['ctr'];?></td>
	
	<?php if($cpa_enabled ==1 || $affiliate_enabled ==1){?>
	<td ><?php echo $this->get_label('na');?></td>
	<td ><?php echo $this->get_label('na');?></td>
	<?php }?>
	
	<td ><bdi><?php echo $this->get_number_format($value['money_spent']);?></bdi></td>
	</tr>
	<?php 
	$enterflag=1;
	}?>
	
		
	<?php if($cpm_enabled ==1){?>
	<tr class="data_table_content">
	<td <?php if($enterflag ==1){?>style="display: none;"<?php }?> <?php if($enterflag ==0){?>rowspan="<?php echo $rowspan;?>"<?php }?>><?php echo $data;?></td>
	
	<td><?php echo $this->get_label('cpm');?></td>
	<td ><?php echo $value['cpm_impression'];?></td>
	<td ><?php echo $value['cpm_click'];?></td>
	<td ><?php echo $value['cpm_ctr'];?></td>
	
	<?php if($cpa_enabled ==1 || $affiliate_enabled ==1){?>
	<td ><?php echo $this->get_label('na');?></td>
	<td ><?php echo $this->get_label('na');?></td>
	<?php }?>
	
	<td ><bdi><?php echo $this->get_number_format($value['cpm_spend']);?></bdi></td>
	</tr>
	<?php 
	$enterflag=1;
	}?>
	
	
	
	<?php if($cpa_enabled ==1){?>
	<tr class="data_table_content">
	<td <?php if($enterflag ==1){?>style="display: none;"<?php }?> <?php if($enterflag ==0){?>rowspan="<?php echo $rowspan;?>"<?php }?>><?php echo $data;?></td>
	
	<td><?php echo $this->get_label('cpa');?></td>
	<td ><?php echo $value['cpa_impression'];?></td>
	<td ><?php echo $value['cpa_click'];?></td>
	<td ><?php echo $value['cpa_ctr'];?></td>
	
	<td ><?php echo $value['cpa_conversion'];?></td>
	<td ><?php echo $value['cpa_ratio'];?></td>
	
	
	<td ><bdi><?php echo $this->get_number_format($value['cpa_spend']);?></bdi></td>
	</tr>
	<?php 
	$enterflag=1;
	}?>
	
	
	<?php if($cpv_enabled ==1){?>
	<tr class="data_table_content">
	<td <?php if($enterflag ==1){?>style="display: none;"<?php }?> <?php if($enterflag ==0){?>rowspan="<?php echo $rowspan;?>"<?php }?>><?php echo $data;?></td>
	
	<td><?php echo $this->get_label('cpv');?></td>
	<td ><?php echo $value['cpv_impression'];?></td>
	<td ><?php echo $value['cpv_click'];?></td>
	<td ><?php echo $value['cpv_ctr'];?></td>
	
	<?php if($cpa_enabled ==1 || $affiliate_enabled ==1){?>
	<td ><?php echo $this->get_label('na');?></td>
	<td ><?php echo $this->get_label('na');?></td>
	<?php }?>
	
	<td ><bdi><?php echo $this->get_number_format($value['cpv_spend']);?></bdi></td>
	</tr>
	<?php 
	$enterflag=1;
	}?>		
	
	
		
	
	
	<?php if($pop_enabled ==1){?>
	<tr class="data_table_content">
	
	<td <?php if($enterflag ==1){?>style="display: none;"<?php }?> <?php if($enterflag ==0){?>rowspan="<?php echo $rowspan;?>"<?php }?>><?php echo $data;?></td>
	
	
	<td><?php echo $this->get_label('pop');?></td>
	<td ><?php echo $value['pop_impression'];?></td>
	<td ><?php echo $this->get_label('na');?></td>
	<td ><?php echo $this->get_label('na');?></td>
	
	<?php if($cpa_enabled ==1 || $affiliate_enabled ==1){?>
	<td ><?php echo $this->get_label('na');?></td>
	<td ><?php echo $this->get_label('na');?></td>
	<?php }?>
	
	<td ><bdi><?php echo $this->get_number_format($value['pop_spend']);?></bdi></td>
	</tr>
	<?php 
	$enterflag=1;
	}?>
	
	
	
	<?php if($affiliate_enabled ==1){?>
	<tr class="data_table_content">
	<td <?php if($enterflag ==1){?>style="display: none;"<?php }?> <?php if($enterflag ==0){?>rowspan="<?php echo $rowspan;?>"<?php }?>><?php echo $data;?></td>
	
	<td><?php echo $this->get_label('affiliate');?></td>
	<td ><?php echo $this->get_label('na');?></td>
	<td ><?php echo $value['affiliate_click'];?></td>
	<td ><?php echo $this->get_label('na');?></td>
	
	<td ><?php echo $value['affiliate_conversion'];?></td>
	<td ><?php echo $value['affiliate_ratio'];?></td>
	
	<td ><bdi><?php echo $this->get_number_format($value['affiliate_spend']);?></bdi></td>
	</tr>
	<?php }?>
	
	
	

	
	<?php }?>

</table>

	
	</div>


  
  
  
   </td>
  </tr>

  

<?php if($cpc_enabled ==1){?>  
<tr id="showstat3" class="tabcontent">
<td >
<div class="report_main_table_data1">
<table id="table-desktop2" class="data_table" cellpadding="0" cellspacing="0">
<tr class="data_table_head">
<td width="35px"><?php echo $this->get_label('no');?></td>
<td width="250px"><?php echo $this->get_label('time');?></td>
<td width="150px"><?php echo $this->get_label('ip');?></td>
<td width="150px"><?php echo $this->get_label('country');?></td>
<td width="200px"><bdi><?php echo $this->get_label('money spend');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</bdi></td>
</tr>


<?php 



if($duration==1 || $duration==2 || $duration==3 || $duration==6)
{
$data_query_data=$this->get_result('data_query_data');

if(count($data_query_data)==0)
{
?>
<tr class="data_table_message"><td colspan="5"><?php echo $this->get_label('no records found');?></td></tr>
<?php 
}
else
{

$i=0;

foreach($data_query_data as $key=>$value)
{
	$i=$i+1;
  ?>
  <tr class="data_table_content">
  <td ><?php echo $i;?></td>
  <td ><?php 
  
   	 $year=substr($value['time'],0,4);	
	 $month=substr($value['time'],4,2);			
	 $day=substr($value['time'],6,2);	
	 $hour=substr($value['time'],8,2);		
		
	 $data=$year."-".$month."-".$day."-".$hour;
	
  
     echo $data;?></td>
  <td ><?php echo $value['ip'];?></td>
  <td ><?php echo $this->get_country_name($value['country']);?></td>
  <td ><bdi><?php echo $this->get_number_format($value['clickvalue']);?></bdi></td> 
  
  
  
  </tr>
<?php }?>  
<tr><td colspan="5" class="data_table_message"><?php echo $this->get_variable('pagination');?></td></tr>
<?php }?>

<?php 
}
else 
{
?>
<tr class="data_table_message"><td colspan="5"><?php echo $this->get_label('click analysis data');?></td></tr>
<?php }?>
  </table>
  	</div>
   </td>
  </tr>
<?php }?>
  
  
  
  
  
<?php if($countrywise_data_tracking ==1){?>  
  
  <tr id="showstat4" class="tabcontent">
  <td>
  <?php 
  
  $newfrom=str_replace('/','-',$from_date);
  $newto=str_replace('/','-',$to_date);
  
  $this->dispatch("advertiser/country/".$duration."/".$newfrom."/".$newto);?>
  
  </td>
  </tr>
  
<?php }?>  
    

</table>

<div style="height: 20px;"></div>

</div>
<?php $this->dispatch("layout/footer");?>
<script type="text/javascript">
show_adv_stat(<?php echo $tab;?>);
</script>