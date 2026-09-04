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
function show_pub_stat(id)
{
	$('.tabcontent').hide();
	$('.report_main_table_tab').removeClass('report_main_table_tab_temp');

	$('#tab').val(id);
	$('#showstat'+id).show();
	$('#tab_'+id).addClass('report_main_table_tab_temp');
}

$(document).ready(function() {
	CreateResponsiveTable('table-desktop');
	CreateResponsiveTable('table-desktop1');
	
	$(window).resize(function()
	{
	 	CreateResponsiveTable('table-desktop');
		CreateResponsiveTable('table-desktop1');
	});
});
</script>	
<?php 
$sid=$this->get_variable('sid');
$pid=$this->get_variable('pid');
$tab=$this->get_variable('tab');
$duration=$this->get_variable('duration');
$sitename=$this->get_variable('sitename');

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
 
 if($cpv_enabled ==1)
 $rowspan=$rowspan+1;  	 

if($from_date =='' && $duration ==7)
$duration=1;
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
</script>

<div class="container">
<h2 class="page_heading"><?php echo $this->get_label('detailed statistics of your site',array('x'=>$sitename));?></h2>
</div>


<div class="container">	


<?php 

$form1=$this->create_form();
$form1->start("allstatistics","","post");
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
<input type="hidden" name="sid" id="sid" value="<?php echo $sid;?>" />
<input type="hidden" name="tab" id="tab" value="1" />
<input class="btn btn-danger" type="submit" name="search" value="<?php echo $this->get_label('go');?>" />
</div>
</div>
<?php $form1->end(); ?> 	



 <ul>
  <li class="col-md-3 col-sm-3 col-xs-12 report_main_table_tab" id="tab_1" onclick="show_pub_stat(1);"><?php echo $this->get_label('overall statistics');?></li>
  <li class="col-md-3 col-sm-3 col-xs-12 report_main_table_tab" id="tab_2" onclick="show_pub_stat(2);"><?php echo $this->get_label('time based statistics');?></li>
  </ul>   




<table cellpadding="0" cellspacing="0" class="report_main_table">
<tr ><td ><div class="under_li_main1"></div></td></tr>
   
<tr id="showstat1" class="tabcontent">
  <td colspan="2">
 
 
 <div class="report_main_table_data1">
<table id="table-desktop" class="data_table" cellpadding="0" cellspacing="0">

<tr class="data_table_head">
<td width="160px"></td>

<?php if($cpc_enabled ==1){?>
<td <?php if($cpm_enabled ==1 || $html_enabled ==1 || $cpa_enabled ==1 || $cpv_enabled ==1 || $pop_enabled ==1){?> style="width: 150px;"<?php }?>><?php echo $this->get_label('ppc');?></td>
<?php }?>

<?php if($cpm_enabled ==1 || $html_enabled ==1){?>
<td <?php if($cpa_enabled ==1 || $cpv_enabled ==1 || $pop_enabled ==1){?> style="width: 150px;"<?php }?>><?php echo $this->get_label('cpm');?></td>
<?php }?>

<?php if($cpa_enabled ==1){?>
<td <?php if($cpv_enabled ==1 || $pop_enabled ==1){?> style="width: 150px;"<?php }?>><?php echo $this->get_label('cpa');?></td>
<?php }?>

<?php if($cpv_enabled ==1){?>
<td <?php if($pop_enabled ==1){?> style="width: 150px;"<?php }?>><?php echo $this->get_label('cpv');?></td>
<?php }?>

<?php if($pop_enabled ==1){?>
<td ><?php echo $this->get_label('pop');?></td>
<?php }?>
</tr>

<?php 

if($from_date !='')   // for custom date range
	$statistics=$this->get_pub_date_range_statistics($from_date,$to_date,$pid,0,0,$sid);
else
$statistics=$this->get_publisher_statistics($duration,$pid,0,0,$sid);

?>	
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
	
}?>


	<tr class="data_table_content">
	<td ><?php echo $this->get_label('impressions');?></td>
	
	<?php if($cpc_enabled ==1){?>
	<td ><?php echo $statistics['impression'];?></td>
	<?php }?>
	
	<?php if($cpm_enabled ==1 || $html_enabled ==1){?>
	<td><?php echo $totimp;?></td>
	<?php }?>
	
	<?php if($cpa_enabled ==1){?>
	<td ><?php echo $statistics['cpa_impression'];?></td>
	<?php }?>
	
	<?php if($cpv_enabled ==1){?>
	<td ><?php echo $statistics['cpv_impression'];?></td>
	<?php }?>
	
	<?php if($pop_enabled ==1){?>
	<td><?php echo $statistics['pop_impression'];?></td>
	<?php }?>
	</tr>
	
	
	<tr class="data_table_content">
	<td ><?php echo $this->get_label('clicks');?></td>
	
	<?php if($cpc_enabled ==1){?>
	<td ><?php echo $statistics['click'];?></td>
	<?php }?>
	
	<?php if($cpm_enabled ==1 || $html_enabled ==1){?>
	<?php if($cpm_enabled ==1){?>
	<td><?php echo $statistics['cpm_click'];?></td>
	<?php }else{?>
	<td><?php echo $this->get_label('na');?></td>
	<?php }}?>
	
	<?php if($cpa_enabled ==1){?>
	<td ><?php echo $statistics['cpa_click'];?></td>
	<?php }?>

	<?php if($cpv_enabled ==1){?>
	<td ><?php echo $statistics['cpv_click'];?></td>
	<?php }?>		

	<?php if($pop_enabled ==1){?>
	<td ><?php echo $this->get_label('na');?></td>
	<?php }?>
	</tr>
	
	
	
	<tr class="data_table_content">
	<td ><bdi><?php echo $this->get_label('ctr');?></bdi></td>
	
	<?php if($cpc_enabled ==1){?>
	<td ><?php echo $statistics['ctr'];?></td>
	<?php }?>
	
	<?php if($cpm_enabled ==1 || $html_enabled ==1){?>
	<?php if($cpm_enabled ==1){?>
	<td ><?php echo $statistics['cpm_ctr'];?></td>
	<?php }else{?>
	<td><?php echo $this->get_label('na');?></td>
	<?php }}?>
	
	<?php if($cpa_enabled ==1){?>
	<td ><?php echo $statistics['cpa_ctr'];?></td>
	<?php }?>


	<?php if($cpv_enabled ==1){?>
	<td ><?php echo $statistics['cpv_ctr'];?></td>
	<?php }?>
	
	<?php if($pop_enabled ==1){?>
	<td ><?php echo $this->get_label('na');?></td>
	<?php }?>
	</tr>
	
	
	<tr class="data_table_content">
	<td ><bdi><?php echo $this->get_label('ecpm');?> &nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</bdi></td>
	
	<?php if($cpc_enabled ==1){?>	
	<td ><bdi><?php echo $this->get_number_format($statistics['ecpm']);?></bdi></td>
	<?php }?>
	
	<?php if($cpm_enabled ==1 || $html_enabled ==1){?>
	<td><?php echo $this->get_label('na');?></td>
	<?php }?>
	
	<?php if($cpa_enabled ==1){?>
	<td><?php echo $this->get_label('na');?></td>
	<?php }?>
	
	<?php if($cpv_enabled ==1){?>
	<td ><?php echo $this->get_label('na');?></td>
	<?php }?>	
	
	
	<?php if($pop_enabled ==1){?>
	<td ><?php echo $this->get_label('na');?></td>
	<?php }?>
	</tr>
		
	
	<?php if($cpa_enabled ==1){?>
	<tr class="data_table_content">
	<td ><bdi><?php echo $this->get_label('conversions');?></bdi></td>
	
	<?php if($cpc_enabled ==1){?>	
	<td ><?php echo $this->get_label('na');?></td>
	<?php }?>
	
	<?php if($cpm_enabled ==1 || $html_enabled ==1){?>
	<td ><?php echo $this->get_label('na');?></td>
	<?php }?>
	<td ><?php echo $statistics['cpa_conversion'];?></td>
	
	<?php if($cpv_enabled ==1){?>
	<td ><?php echo $this->get_label('na');?></td>
	<?php }?>		
	<?php if($pop_enabled ==1){?>
	<td ><?php echo $this->get_label('na');?></td>
	<?php }?>
	</tr>
	
	
	<tr class="data_table_content">
	<td ><bdi><?php echo $this->get_label('conversion ratio');?></bdi></td>
	
	<?php if($cpc_enabled ==1){?>	
	<td ><?php echo $this->get_label('na');?></td>
	<?php }?>
	
	<?php if($cpm_enabled ==1 || $html_enabled ==1){?>
	<td ><?php echo $this->get_label('na');?></td>
	<?php }?>
	<td ><?php echo $statistics['cpa_ratio'];?></td>
	
	<?php if($cpv_enabled ==1){?>
	<td ><?php echo $this->get_label('na');?></td>
	<?php }?>		
	
	<?php if($pop_enabled ==1){?>
	<td ><?php echo $this->get_label('na');?></td>
	<?php }?>
	</tr>
	
	<?php }?>
	
	
	
	
	<tr class="data_table_content">
	<td ><bdi><?php echo $this->get_label('profit');?> &nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</bdi></td>
	
	<?php if($cpc_enabled ==1){?>	
	<td ><bdi><?php echo $this->get_number_format($statistics['pub_profit']);?></bdi></td>
	<?php }?>
	
	<?php if($cpm_enabled ==1 || $html_enabled ==1){?>
	<td ><bdi><?php echo $this->get_number_format($totprofit);?></bdi></td>
	<?php }?>
	
	<?php if($cpa_enabled ==1){?>
	<td ><bdi><?php echo $this->get_number_format($statistics['cpa_profit']);?></bdi></td>
	<?php }?>
	
	<?php if($cpv_enabled ==1){?>
	<td ><?php echo $this->get_number_format($statistics['cpv_profit']);?></td>
	<?php }?>	
	
	<?php if($pop_enabled ==1){?>
	<td><bdi><?php echo $this->get_number_format($statistics['pop_profit']);?></bdi></td>
	<?php }?>
	</tr>
		

</table>

</div>
 
  </td>
  </tr>
  
  
  
  
  
  
    <tr id="showstat2" class="tabcontent">
  <td colspan="2">
  
  
 <div class="report_main_table_data1">
<table id="table-desktop1" class="data_table" cellpadding="0" cellspacing="0">
<tr class="data_table_head">
<td style="width: 100px;"><?php echo $this->get_label('date');?></td>

<?php if($rowspan >0){?>
<td style="width: 80px;"><?php echo $this->get_label('type');?></td>
<td style="width: 100px;"><?php echo $this->get_label('impressions');?></td>
<td style="width: 60px;"><?php echo $this->get_label('clicks');?></td>
<td style="width: 60px;"><bdi><?php echo $this->get_label('ctr');?></bdi></td>
<td style="width: 60px;"><bdi><?php echo $this->get_label('ecpm');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</bdi></td>

<?php if($cpa_enabled ==1){?>
<td style="width: 100px;"><bdi><?php echo $this->get_label('conversions');?></bdi></td>
<td style="width: 100px;"><bdi><?php echo $this->get_label('conversion ratio');?></bdi></td>
<?php }?>

<td style="width: 70px;"><bdi><?php echo $this->get_label('profit');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</bdi></td>
<?php }?>

</tr>


<?php 


	 

if($from_date !='')  // for custom date range
$statistics=$this->get_pub_date_range_timeperiod_statistics($from_date,$to_date,$pid,0,$sid);
else
$statistics=$this->get_publisher_timeperiod_statistics($duration,$pid,0,$sid);
	
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
	<td><?php echo $this->get_label('ppc');?></td>

	<td ><?php echo $value['impression'];?></td>
	<td ><?php echo $value['click'];?></td>
	<td ><?php echo $value['ctr'];?></td>
	<td ><bdi><?php echo $this->get_number_format($value['ecpm']);?></bdi></td>
	
	<?php if($cpa_enabled ==1){?>
	<td ><?php echo $this->get_label('na');?></td>
	<td ><?php echo $this->get_label('na');?></td>
	<?php }?>
	
	<td ><bdi><?php echo $this->get_number_format($value['pub_profit']);?></bdi></td>
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
		
<tr class="data_table_content">
<td <?php if($enterflag ==1){?>style="display: none;"<?php }?> <?php if($enterflag ==0){?>rowspan="<?php echo $rowspan;?>"<?php }?>><?php echo $data;?></td>
<td ><?php echo $this->get_label('cpm');?></td>
<td><?php echo $totimp;?></td>

<?php if($cpm_enabled ==1){?>
<td><?php echo $value['cpm_click'];?></td>
<td><?php echo $value['cpm_ctr'];?></td>
<?php }else{?>
<td ><?php echo $this->get_label('na');?></td>
<td ><?php echo $this->get_label('na');?></td>
<?php }?>

<td ><?php echo $this->get_label('na');?></td>

<?php if($cpa_enabled ==1){?>
	<td ><?php echo $this->get_label('na');?></td>
	<td ><?php echo $this->get_label('na');?></td>
	<?php }?>

<td><?php echo $this->get_number_format($totprofit);?></td>
</tr>
  
<?php 
$enterflag=1;
}?>
    
    
    
    
  	<?php if($cpa_enabled ==1){
	
		
			$totimp=$value['cpa_impression'];
			$totprofit=$value['cpa_profit'];
			$totspend=$value['cpa_spend'];
		
		
		
		?>
		
<tr class="data_table_content">
<td <?php if($enterflag ==1){?>style="display: none;"<?php }?> <?php if($enterflag ==0){?>rowspan="<?php echo $rowspan;?>"<?php }?>><?php echo $data;?></td>
<td><?php echo $this->get_label('cpa');?></td>
<td><?php echo $totimp;?></td>
<td><?php echo $value['cpa_click'];?></td>
<td><?php echo $value['cpa_ctr'];?></td>
<td ><?php echo $this->get_label('na');?></td>

	<td ><?php echo $value['cpa_conversion'];?></td>
	<td ><?php echo $value['cpa_ratio'];?></td>


<td><?php echo $this->get_number_format($totprofit);?></td>
</tr>
  
<?php 
$enterflag=1;
}?>     
    
    
    
    
    
    

   
<?php if($cpv_enabled ==1){	?>
		
<tr class="data_table_content">
<td <?php if($enterflag ==1){?>style="display: none;"<?php }?> <?php if($enterflag ==0){?>rowspan="<?php echo $rowspan;?>"<?php }?>><?php echo $data;?></td>
<td><?php echo $this->get_label('cpv');?></td>
<td><?php echo $value['cpv_impression'];?></td>
<td><?php echo $value['cpv_click'];?></td>
<td><?php echo $value['cpv_ctr'];?></td>
<td ><?php echo $this->get_label('na');?></td>

<?php if($cpa_enabled ==1){?>
<td ><?php echo $this->get_label('na');?></td>
<td ><?php echo $this->get_label('na');?></td>
<?php }?>

<td><?php echo $this->get_number_format($value['cpv_profit']);?></td>
</tr>
<?php 
$enterflag=1;
}?>
  

    
    
    	<?php if($pop_enabled ==1){
	
			$totimp=$value['pop_impression'];
			$totprofit=$value['pop_profit'];
			$totspend=$value['pop_spend'];
		
		
		
		?>
		
<tr class="data_table_content">
<td <?php if($enterflag ==1){?>style="display: none;"<?php }?> <?php if($enterflag ==0){?>rowspan="<?php echo $rowspan;?>"<?php }?>><?php echo $data;?></td>
<td ><?php echo $this->get_label('pop');?></td>
<td><?php echo $totimp;?></td>

<td ><?php echo $this->get_label('na');?></td>
<td ><?php echo $this->get_label('na');?></td>
<td ><?php echo $this->get_label('na');?></td>

<?php if($cpa_enabled ==1){?>
	<td ><?php echo $this->get_label('na');?></td>
	<td ><?php echo $this->get_label('na');?></td>
	<?php }?>

<td><?php echo $this->get_number_format($totprofit);?></td>
</tr>
  
<?php }?>
    

	
		
	<?php }?>
</table>

	</div>
  
   </td>
  </tr>
</table>
<div style="height: 20px;"></div>
</div>
<script type="text/javascript">show_pub_stat(<?php echo $tab;?>);</script>