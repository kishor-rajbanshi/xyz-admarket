<?php 
$this->dispatch("layout/header/7/_71");
$duration=$this->get_variable('duration');
$tab=$this->get_variable('tab');

$cpc_enabled=$this->get_addon_status('cpc_enabled');
$cpa_enabled=$this->get_addon_status('cpa_enabled');
$cpm_enabled=$this->get_addon_status('cpm_enabled');
$html_enabled=$this->get_addon_status('html_enabled');
$pop_enabled=$this->get_addon_status('pop-ads_enabled');
$affiliate_enabled=$this->get_addon_status('affiliate-ads_enabled');
$cpv_enabled=$this->get_addon_status('video-ads_enabled');


$pop_addon_usage = intval(Configuration::get_instance()->read('pop_addon_usage'));

if($pop_addon_usage == 1)
$pop_enabled = 0;

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

?>
<link rel="stylesheet" type="text/css" media="all" href="//code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/jquery-ui-1.8.23.custom.min.js' ></script>
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

<div class="sub_menu_main"><?php echo $this->get_label('overall statistics');?></div>

<?php $this->dispatch("links/links/25");?>

<div class="inner-box">
<div class="report_div">

<table style="width: 100%;" cellpadding="0" cellspacing="0">
<tr><td colspan="6" >	
<div class="search_div">
<table class="search_div_table">
<?php 

if($from_date =='' && $duration ==7)
$duration=1;

$form1=$this->create_form();
$form1->start("overallstatistics",$this->make_url("report/overall"),"post");
?>
  
<tr>
<td style="height: 30px;">
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
&nbsp;
<input type="text" readonly="readonly" name="from_date" id="from_date" value="<?php echo $from_date;?>" placeholder="From Date" size="5" />  
&nbsp;
<input type="text" readonly="readonly" name="to_date" id="to_date" value="<?php echo $to_date;?>" placeholder="To Date" size="5" />
&nbsp;
</td>
<td></td>
<td>
 <input type="hidden" name="tab" id="tab" value="1"/>
<input type="submit" name="search" value="<?php echo $this->get_label('go');?>" /></td>
</tr>
  
<?php $form1->end(); ?> 	

</table>
</div>
</td></tr>	


<tr><td colspan="4" height="10px"></td></tr>	


<tr><td colspan="4" >


<?php 
if($from_date !='')  // for custom date range
$statistics=$this->get_adv_date_range_statistics($from_date,$to_date);
else
$statistics=$this->get_advertiser_statistics($duration);?>	

<table style="width: 100%;" cellpadding="0" cellspacing="0">


  <tr class="statistics_header">
    <td onclick="show_pub_stat(1);" id="adustat1" class="adustatclass" style="width: 150px;"><?php echo $this->get_label('overall');?></td>
    <td onclick="show_pub_stat(2);" id="adustat2" class="adustatclass" style="width: 150px;"><?php echo $this->get_label('time based reports');?></td>
    <td></td>
  </tr>

 
   <tr id="showstat1" class="showadustatclass statistics_tr">
   
   
   
   
  <td colspan="6" style="padding: 5px;" class="statistics_td">
 
 
<table style="width: 100%;" cellpadding="0" cellspacing="0" class="overall" >

<tr class="pricing-head">
<td style="width: 200px;"></td>

<?php if($cpc_enabled ==1){?>
<td <?php if($cpm_enabled ==1 || $html_enabled ==1 || $cpa_enabled ==1 || $cpv_enabled ==1 || $pop_enabled ==1 || $affiliate_enabled ==1){?> style="width: 150px;"<?php }?>><?php echo $this->get_label('ppc');?></td>
<?php }?>

<?php if($cpm_enabled ==1 || $html_enabled ==1){?>
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

<?php if($cpm_enabled ==1 || $html_enabled ==1){

	$totimp=0;
	$totprofit=0;
	$totspend=0;
	$cpmclick=0;
	if($cpm_enabled ==1)
	{
		$totimp=$statistics['cpm_impression'];
		$totprofit=$statistics['cpm_profit'];
		$totspend=$statistics['cpm_spend'];
		$cpmclick=$statistics['cpm_click'];
	}
	
	if($html_enabled ==1)
	{
		$totimp=$totimp+$statistics['html_impression'];
		$totprofit=$totprofit+$statistics['html_profit'];
		$totspend=$totspend+$statistics['html_profit'];
	}
}?>


<tr>
<td style="padding-left: 10px;"><?php echo $this->get_label('impressions');?></td>

<?php if($cpc_enabled ==1){?>
<td><?php echo $statistics['impression'];?></td>
<?php }?>

<?php if($cpm_enabled ==1 || $html_enabled ==1){?>
<td><?php echo $totimp;?></td>
<?php }?>

<?php if($cpa_enabled ==1){?>
<td><?php echo $statistics['cpa_impression'];?></td>
<?php }?>

<?php if($cpv_enabled ==1){?>
<td ><?php echo $statistics['cpv_impression'];?></td>
<?php }?>	


<?php if($pop_enabled ==1){?>
<td><?php echo $statistics['pop_impression'];?></td>
<?php }?>

<?php if($affiliate_enabled ==1){?>
<td><?php echo $this->get_label('na');?></td>
<?php }?>
	
</tr>


<tr>
<td style="padding-left: 10px;"><?php echo $this->get_label('clicks');?></td>

<?php if($cpc_enabled ==1){?>
<td><?php echo $statistics['click'];?></td>
<?php }?>

<?php if($cpm_enabled ==1 || $html_enabled ==1){?>
<?php if($cpm_enabled ==1){?>
<td><?php echo $statistics['cpm_click'];?></td>
<?php }else{?>
<td><?php echo $this->get_label('na');?></td>
<?php }}?>

<?php if($cpa_enabled ==1){?>
<td><?php echo $statistics['cpa_click'];?></td>
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


<tr>
<td style="padding-left: 10px;"><?php echo $this->get_label('ctr');?></td>

<?php if($cpc_enabled ==1){?>
<td><?php echo $statistics['ctr'];?></td>
<?php }?>

<?php if($cpm_enabled ==1 || $html_enabled ==1){?>
<?php if($cpm_enabled ==1){?>
<td><?php echo $statistics['cpm_ctr'];?></td>
<?php }else{?>
<td><?php echo $this->get_label('na');?></td>
<?php }}?>

<?php if($cpa_enabled ==1){?>
<td><?php echo $statistics['cpa_ctr'];?></td>
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
	<tr>
	<td style="padding-left: 10px;"><?php echo $this->get_label('conversions');?></td>
	
	<?php if($cpc_enabled ==1){?>	
	<td ><?php echo $this->get_label('na');?></td>
	<?php }?>
	
	<?php if($cpm_enabled ==1 || $html_enabled ==1){?>
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
	
	
	<tr>
	<td style="padding-left: 10px;"><?php echo $this->get_label('conversion ratio');?></td>
	
	<?php if($cpc_enabled ==1){?>	
	<td ><?php echo $this->get_label('na');?></td>
	<?php }?>
	
	<?php if($cpm_enabled ==1 || $html_enabled ==1){?>
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



<tr>
<td style="padding-left: 10px;"><?php echo $this->get_label('money spend');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>

<?php if($cpc_enabled ==1){?>
<td><?php echo $this->get_number_format($statistics['money_spent']);?></td>
<?php }?>

<?php if($cpm_enabled ==1 || $html_enabled ==1){?>
<td><?php echo $this->get_number_format($totspend);?></td>
<?php }?>

<?php if($cpa_enabled ==1){?>
<td><?php echo $this->get_number_format($statistics['cpa_spend']);?></td>
<?php }?>

<?php if($cpv_enabled ==1){?>
<td ><?php echo $this->get_number_format($statistics['cpv_spend']);?></td>
<?php }?>		

<?php if($pop_enabled ==1){?>
<td><?php echo $this->get_number_format($statistics['pop_spend']);?></td>
<?php }?>

<?php if($affiliate_enabled ==1){?>
<td ><?php echo $this->get_number_format($statistics['affiliate_spend']);?></td>
<?php }?>
</tr>

<tr>
<td style="padding-left: 10px;"><?php echo $this->get_label('pubprofit');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>

<?php if($cpc_enabled ==1){?>
<td><?php echo $this->get_number_format($statistics['pub_profit']);?></td>
<?php }?>

<?php if($cpm_enabled ==1 || $html_enabled ==1){?>
<td><?php echo $this->get_number_format($totprofit);?></td>
<?php }?>

<?php if($cpa_enabled ==1){?>
<td><?php echo $this->get_number_format($statistics['cpa_profit']);?></td>
<?php }?>


<?php if($cpv_enabled ==1){?>
<td ><?php echo $this->get_number_format($statistics['cpv_profit']);?></td>
<?php }?>


<?php if($pop_enabled ==1){?>
<td><?php echo $this->get_number_format($statistics['pop_profit']);?></td>
<?php }?>

<?php if($affiliate_enabled ==1){?>
<td ><?php echo $this->get_number_format($statistics['affiliate_profit']);?></td>
<?php }?>
</tr>


<tr>
<td style="padding-left: 10px;"><?php echo $this->get_label('yourprofit');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>

<?php if($cpc_enabled ==1){?>
<td><?php echo $this->get_number_format(($statistics['money_spent']-$statistics['pub_profit']));?></td>
<?php }?>

<?php if($cpm_enabled ==1 || $html_enabled ==1){?>
<td><?php echo $this->get_number_format($totspend-$totprofit);?></td>
<?php }?>

<?php if($cpa_enabled ==1){?>
<td><?php echo $this->get_number_format($statistics['cpa_spend']-$statistics['cpa_profit']);?></td>
<?php }?>

<?php if($cpv_enabled ==1){?>
<td ><?php echo $this->get_number_format($statistics['cpv_spend']-$statistics['cpv_profit']);?></td>
<?php }?>


<?php if($pop_enabled ==1){?>
<td><?php echo $this->get_number_format($statistics['pop_spend']-$statistics['pop_profit']);?></td>
<?php }?>

<?php if($affiliate_enabled ==1){?>
<td><?php echo $this->get_number_format($statistics['affiliate_spend']-$statistics['affiliate_profit']);?></td>
<?php }?>
</tr>

</table>

  </td>
  </tr>

  
  
  <tr id="showstat2" class="showadustatclass statistics_tr">
  <td colspan="6" style="padding: 5px;" class="statistics_td">
  
<table  style="width: 100%;" cellpadding="0" cellspacing="0"  class="data_table">



<tr class="row_heading_tr">
<td width="130px"><?php echo $this->get_label('date');?></td>

<?php if($rowspan >0){?>
<td width="100px"><?php echo $this->get_label('type');?></td>
<td width="100px"><?php echo $this->get_label('impressions');?></td>
<td width="100px"><?php echo $this->get_label('clicks');?></td>
<td width="100px"><?php echo $this->get_label('ctr');?></td>

<?php if($cpa_enabled ==1 || $affiliate_enabled ==1){?>
<td style="width: 100px;"><bdi><?php echo $this->get_label('conversions');?></bdi></td>
<td style="width: 140px;"><bdi><?php echo $this->get_label('conversion ratio');?></bdi></td>
<?php }?>

<td width="130px"><?php echo $this->get_label('money spend');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>
<td width="140px"><?php echo $this->get_label('pubprofit');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>
<td width="130px"><?php echo $this->get_label('yourprofit');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>
<?php }?>
</tr>

<?php 

	if($from_date !='')  // for custom date range
	$statistics=$this->get_adv_date_range_timeperiod_statistics($from_date,$to_date);
	else
	$statistics=$this->get_advertiser_timeperiod_statistics($duration);
	

	
	foreach($statistics as $key=>$value)
	{
	
	 $enterflag=0;		
		
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

	<?php if($cpc_enabled ==1){	?>
	<tr class="row_data_tr">
	<td rowspan="<?php echo $rowspan;?>"><?php echo $data;?></td>
	
	<td class="border-left"><?php echo $this->get_label('ppc');?></td>
	<td ><?php echo $value['impression'];?></td>
	<td ><?php echo $value['click'];?></td>
	<td ><?php echo $value['ctr'];?></td>

	<?php if($cpa_enabled ==1 || $affiliate_enabled ==1){?>
	<td ><?php echo $this->get_label('na');?></td>
	<td ><?php echo $this->get_label('na');?></td>
	<?php }?>
	
	
	<td><?php echo $this->get_number_format($value['money_spent']);?></td>
    <td><?php echo $this->get_number_format($value['pub_profit']);?></td>
    <td><?php echo $this->get_number_format(($value['money_spent']-$value['pub_profit']));?></td>
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
    <tr class="row_data_tr">
    <td <?php if($enterflag ==1){?>style="display: none;"<?php }?> <?php if($enterflag ==0){?>rowspan="<?php echo $rowspan;?>"<?php }?>><?php echo $data;?></td>
	<td class="border-left"><?php echo $this->get_label('cpm');?></td>
    <td ><?php echo $totimp;?></td>
    
    <?php if($cpm_enabled ==1){?>
    <td><?php echo $value['cpm_click'];?></td>
	<td><?php echo $value['cpm_ctr'];?></td>
    <?php }else{?>
    <td ><?php echo $this->get_label('na');?></td>
	<td ><?php echo $this->get_label('na');?></td>
    <?php }?>
    
    
   	<?php if($cpa_enabled ==1 || $affiliate_enabled ==1){?>
	<td ><?php echo $this->get_label('na');?></td>
	<td ><?php echo $this->get_label('na');?></td>
	<?php }?>
    
    
    <td><?php echo $this->get_number_format($totspend);?></td>
    
    <?php if($cpm_enabled ==1){?>
    <td><?php echo $this->get_number_format($totprofit);?></td>
    <td><?php echo $this->get_number_format(($totspend-$totprofit));?></td>
    <?php }else{?>
	<td ><?php echo $this->get_label('na');?></td>
	<td ><?php echo $this->get_label('na');?></td>
	<?php }?>
    
    </tr>	
	<?php 
	$enterflag=1;
	}?>
    
    
     <?php if($cpa_enabled ==1){
    
    	$totimp=$value['cpa_impression'];
    	$totprofit=$value['cpa_profit'];
    	$totspend=$value['cpa_spend'];
    	 	
    	?>
    <tr class="row_data_tr">
	<td <?php if($enterflag ==1){?>style="display: none;"<?php }?> <?php if($enterflag ==0){?>rowspan="<?php echo $rowspan;?>"<?php }?>><?php echo $data;?></td>
	<td class="border-left"><?php echo $this->get_label('cpa');?></td>
    <td ><?php echo $totimp;?></td>
    
    <td><?php echo $value['cpa_click'];?></td>
	<td><?php echo $value['cpa_ctr'];?></td>
    
    
    <td ><?php echo $value['cpa_conversion'];?></td>
	<td ><?php echo $value['cpa_ratio'];?></td>
    
    
    <td><?php echo $this->get_number_format($totspend);?></td>
    
    <td><?php echo $this->get_number_format($totprofit);?></td>
    <td><?php echo $this->get_number_format(($totspend-$totprofit));?></td>
    </tr>	
	<?php 
	$enterflag=1;
	}?>
	
	
	
	
	
	
	
	
   <?php if($cpv_enabled ==1){
    
    		$totimp=$value['cpv_impression'];
    		$totprofit=$value['cpv_profit'];
    		$totspend=$value['cpv_spend'];

    	
    	?>
    <tr class="row_data_tr">
    <td <?php if($enterflag ==1){?>style="display: none;"<?php }?> <?php if($enterflag ==0){?>rowspan="<?php echo $rowspan;?>"<?php }?>><?php echo $data;?></td>
	<td class="border-left"><?php echo $this->get_label('cpv');?></td>
    <td ><?php echo $totimp;?></td>
    
    <td><?php echo $value['cpv_click'];?></td>
	<td><?php echo $value['cpv_ctr'];?></td>
    
    
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
    	
	
	
	
	   
    
    
        
    <?php if($pop_enabled ==1){
    

    		$totimp=$value['pop_impression'];
    		$totprofit=$value['pop_profit'];
    		$totspend=$value['pop_spend'];
    	
    	
    	?>
    <tr class="row_data_tr">
    <td <?php if($enterflag ==1){?>style="display: none;"<?php }?> <?php if($enterflag ==0){?>rowspan="<?php echo $rowspan;?>"<?php }?>><?php echo $data;?></td>
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
    
    	$totprofit=$value['affiliate_profit'];
    	$totspend=$value['affiliate_spend'];
    	 	
    	?>
    <tr class="row_data_tr">
	<td <?php if($enterflag ==1){?>style="display: none;"<?php }?> <?php if($enterflag ==0){?>rowspan="<?php echo $rowspan;?>"<?php }?>><?php echo $data;?></td>
	<td class="border-left"><?php echo $this->get_label('affiliate');?></td>
    <td ><?php echo $this->get_label('na');?></td>
    <td><?php echo $value['affiliate_click'];?></td>
	<td ><?php echo $this->get_label('na');?></td>
    
    <td ><?php echo $value['affiliate_conversion'];?></td>
	<td ><?php echo $value['affiliate_ratio'];?></td>
    
    
    <td><?php echo $this->get_number_format($totspend);?></td>
    
    <td><?php echo $this->get_number_format($totprofit);?></td>
    <td><?php echo $this->get_number_format(($totspend-$totprofit));?></td>
    </tr>	
  	<?php }?>  	

	
	<?php }?>

</table>
   </td>
  </tr>
</table>  

</td></tr>	

</table>
</div>
</div>
<?php $this->dispatch("layout/footer");?>
<script type="text/javascript">
show_pub_stat(<?php echo $tab;?>);
</script>