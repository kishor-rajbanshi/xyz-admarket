<?php 
$this->dispatch("layout/header/7/_74");
$cpc_enabled=$this->get_addon_status('cpc_enabled');
$cpa_enabled=$this->get_addon_status('cpa_enabled');
$cpm_enabled=$this->get_addon_status('cpm_enabled');
$html_enabled=$this->get_addon_status('html_enabled');
$pop_enabled=$this->get_addon_status('pop-ads_enabled');
$cpv_enabled=$this->get_addon_status('video-ads_enabled');
$affiliate_enabled=$this->get_addon_status('affiliate-ads_enabled');
?>

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



<div class="sub_menu_main"><?php echo $this->get_label('check statistics');?></div>

<?php $this->dispatch("links/links/25");?>

<div class="inner-box">
<div class="report_div">

<table class="verify-box" style="width: 100%;" cellpadding="0" cellspacing="0">

  <tr class="statistics_header">
    <td onclick="show_pub_stat(1);" id="adustat1" class="adustatclass" style="width: 150px;"><?php echo $this->get_label('daily statistics');?></td>
    <td onclick="show_pub_stat(2);" id="adustat2" class="adustatclass" style="width: 150px;"><?php echo $this->get_label('monthly statistics');?></td>
    <td onclick="show_pub_stat(3);" id="adustat3" class="adustatclass" style="width: 150px;"><?php echo $this->get_label('yearly statistics');?></td>
    <td class="adustatclass"></td>
  </tr>


<tr id="showstat1" class="showadustatclass statistics_tr">
  <td colspan="4" style="padding: 5px;" class="statistics_td">
  
<table class="data_table" cellpadding="0" cellspacing="0" style="width: 100%;">
<tr class="row_heading_tr" >
<td style="padding-left: 10px;width: 50px;"><?php echo $this->get_label('date');?></td>
<td style="width: 30px;"></td>

<?php if($cpc_enabled ==1){?>
<td style="width: 60px;" ><?php echo $this->get_label('cpc imp');?></td>
<td style="width: 60px;" ><?php echo $this->get_label('cpc clicks');?></td>
<td style="width: 60px;" ><?php echo $this->get_label('cpc spend');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>
<?php }?>

<?php if($cpm_enabled ==1 || $html_enabled ==1){?>
<td style="width: 60px;" ><?php echo $this->get_label('cpm imp');?></td>
<td style="width: 60px;" ><?php echo $this->get_label('cpm spend');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>
<?php }?>

<?php if($cpa_enabled ==1){?>
<td style="width: 60px;" ><?php echo $this->get_label('cpa imp');?></td>
<td style="width: 60px;" ><?php echo $this->get_label('cpa conv');?></td>
<td style="width: 60px;" ><?php echo $this->get_label('cpa spend');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>
<?php }?>


<?php if($cpv_enabled ==1){?>
<td style="width: 60px;" ><?php echo $this->get_label('cpv imp');?></td>
<td style="width: 60px;" ><?php echo $this->get_label('cpv spend');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>
<?php }?>


<?php if($affiliate_enabled ==1){?>
<td style="width: 60px;" ><?php echo $this->get_label('affiliate conv');?></td>
<td style="width: 60px;" ><?php echo $this->get_label('affiliate spend');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>
<?php }?>


<?php if($pop_enabled ==1){?>
<td style="width: 60px;" ><?php echo $this->get_label('pop imp');?></td>
<td style="width: 60px;" ><?php echo $this->get_label('pop spend');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>
<?php }?>
</tr>
<?php 

$statistics=$this->get_advertiser_check_statistics(3);
$statistics1=$this->get_publisher_check_statistics(3);




foreach($statistics as $key=>$value)
	{
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
	<tr class="row_data_tr">
	<td  rowspan="2" ><?php echo $data;?></td>
	<td class="adv-box"><p><?php echo $this->get_label('adv');?></p></td>
	
	<?php if($cpc_enabled ==1){?>
	<td  ><?php echo $value['impression'];?></td>
	<td  ><?php echo $value['click'];?></td>
	<td  ><?php echo $this->get_number_format($value['money_spent']);?></td>
	<?php }?>
	
	<?php if($cpm_enabled ==1 || $html_enabled ==1){
	
		$totimp=0;
		$totspend=0;
		if($cpm_enabled ==1)
		{
			$totimp=$value['cpm_impression'];
			$totspend=$value['cpm_spend'];
		
		}
		
		if($html_enabled ==1)
		{
			$totimp=$totimp+$value['html_impression'];
			$totspend=$totspend+$value['html_profit'];
		}
		
		
		?>
	<td  ><?php echo $totimp;?></td>
	<td  ><?php echo $this->get_number_format($totspend);?></td>
	<?php }?>
	
		
	<?php if($cpa_enabled ==1){?>
	<td  ><?php echo $value['cpa_impression'];?></td>
	<td  ><?php echo $value['cpa_conversion'];?></td>
	<td  ><?php echo $this->get_number_format($value['cpa_spend']);?></td>
	<?php }?>
	
	
	<?php if($cpv_enabled ==1){?>
	<td  ><?php echo $value['cpv_impression'];?></td>
	<td  ><?php echo $this->get_number_format($value['cpv_spend']);?></td>
	<?php }?>		
	
	<?php if($affiliate_enabled ==1){?>
	<td  ><?php echo $value['affiliate_conversion'];?></td>
	<td  ><?php echo $this->get_number_format($value['affiliate_spend']);?></td>
	<?php }?>	
	
	
		<?php if($pop_enabled ==1){?>
	<td ><?php echo $value['pop_impression'];?></td>
	<td ><?php echo $this->get_number_format($value['pop_spend']);?></td>
	<?php }?>
	</tr>
	
	<tr class="row_data_tr">
	
	<td class="pub-box"><p><?php echo $this->get_label('pub');?></p></td>
	
	<?php if($cpc_enabled ==1){?>
	<td ><?php echo $statistics1[$key]['impression'];?></td>
	<td ><?php echo $statistics1[$key]['click'];?></td>
	<td ><?php echo $this->get_number_format($statistics1[$key]['money_spent']);?></td>
	<?php }?>
	
	<?php if($cpm_enabled ==1 || $html_enabled ==1){
	
		$totimp=0;
		$totspend=0;
		if($cpm_enabled ==1)
		{
			$totimp=$statistics1[$key]['cpm_impression'];
			$totspend=$statistics1[$key]['cpm_spend'];
		
		}
		
		if($html_enabled ==1)
		{
			$totimp=$totimp+$statistics1[$key]['html_impression'];
			$totspend=$totspend+$statistics1[$key]['html_profit'];
		}
		
		?>
	<td ><?php echo $totimp;?></td>
	<td ><?php echo $this->get_number_format($totspend);?></td>
	<?php }?>
	
	
	<?php if($cpa_enabled ==1){?>
	<td  ><?php echo $statistics1[$key]['cpa_impression'];?></td>
	<td  ><?php echo $statistics1[$key]['cpa_conversion'];?></td>
	<td  ><?php echo $this->get_number_format($statistics1[$key]['cpa_spend']);?></td>
	<?php }?>
		
		
	<?php if($cpv_enabled ==1){?>
	<td  ><?php echo $statistics1[$key]['cpv_impression'];?></td>
	<td  ><?php echo $this->get_number_format($statistics1[$key]['cpv_spend']);?></td>
	<?php }?>		
		
	
	<?php if($affiliate_enabled ==1){?>
	<td  ><?php echo $value['affiliate_conversion'];?></td>
	<td  ><?php echo $this->get_number_format($value['affiliate_spend']);?></td>
	<?php }?>	
		
		
			<?php if($pop_enabled ==1){?>
	<td  ><?php echo $statistics1[$key]['pop_impression'];?></td>
	<td  ><?php echo $this->get_number_format($statistics1[$key]['pop_spend']);?></td>
	<?php }?>
	</tr>
	<?php }?>
</table>  
  </td>
</tr>  
  
<tr id="showstat2" class="showadustatclass statistics_tr">
<td colspan="4" style="padding: 5px;" class="statistics_td">  
  
<table class="data_table" cellpadding="0" cellspacing="0" style="width: 100%;">
<tr class="row_heading_tr" >
<td width="10%" style="padding-left: 10px;width: 50px;"><?php echo $this->get_label('month');?></td>
<td style="width: 30px;"></td>

<?php if($cpc_enabled ==1){?>
<td style="width: 60px;" ><?php echo $this->get_label('cpc imp');?></td>
<td style="width: 60px;" ><?php echo $this->get_label('cpc clicks');?></td>
<td style="width: 60px;" ><?php echo $this->get_label('cpc spend');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>
<?php }?>


<?php if($cpm_enabled ==1 || $html_enabled ==1){?>
<td style="width: 60px;" ><?php echo $this->get_label('cpm imp');?></td>
<td style="width: 60px;" ><?php echo $this->get_label('cpm spend');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>
<?php }?>


<?php if($cpa_enabled ==1){?>
<td style="width: 60px;" ><?php echo $this->get_label('cpa imp');?></td>
<td style="width: 60px;" ><?php echo $this->get_label('cpa conv');?></td>
<td style="width: 60px;" ><?php echo $this->get_label('cpa spend');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>
<?php }?>

<?php if($cpv_enabled ==1){?>
<td style="width: 60px;" ><?php echo $this->get_label('cpv imp');?></td>
<td style="width: 60px;" ><?php echo $this->get_label('cpv spend');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>
<?php }?>

<?php if($affiliate_enabled ==1){?>
<td style="width: 60px;" ><?php echo $this->get_label('affiliate conv');?></td>
<td style="width: 60px;" ><?php echo $this->get_label('affiliate spend');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>
<?php }?>

<?php if($pop_enabled ==1){?>
<td style="width: 60px;" ><?php echo $this->get_label('pop imp');?></td>
<td style="width: 60px;" ><?php echo $this->get_label('pop spend');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>
<?php }?>


</tr>


<?php 

$statistics=$this->get_advertiser_check_statistics(4);
$statistics1=$this->get_publisher_check_statistics(4);




foreach($statistics as $key=>$value)
	{
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
	<tr class="row_data_tr">
	<td rowspan="2"><?php echo $data;?></td>
	<td class="adv-box"><p><?php echo $this->get_label('adv');?></p></td>
	
	<?php if($cpc_enabled ==1){?>	
	<td ><?php echo $value['impression'];?></td>
	<td ><?php echo $value['click'];?></td>
	<td ><?php echo $this->get_number_format($value['money_spent']);?></td>
	<?php }?>
	
	<?php if($cpm_enabled ==1 || $html_enabled ==1){
	
		$totimp=0;
		$totspend=0;
		if($cpm_enabled ==1)
		{
			$totimp=$value['cpm_impression'];
			$totspend=$value['cpm_spend'];
		
		}
		
		if($html_enabled ==1)
		{
			$totimp=$totimp+$value['html_impression'];
			$totspend=$totspend+$value['html_profit'];
		}
		
		
		
		?>
	<td ><?php echo $totimp;?></td>
	<td ><?php echo $this->get_number_format($totspend);?></td>
	<?php }?>
	
	
	<?php if($cpa_enabled ==1){?>
	<td ><?php echo $value['cpa_impression'];?></td>
	<td ><?php echo $value['cpa_conversion'];?></td>
	<td ><?php echo $this->get_number_format($value['cpa_spend']);?></td>
	<?php }?>
	
	<?php if($cpv_enabled ==1){?>
	<td ><?php echo $value['cpv_impression'];?></td>
	<td ><?php echo $this->get_number_format($value['cpv_spend']);?></td>
	<?php }?>		
	
	<?php if($affiliate_enabled ==1){?>
	<td ><?php echo $value['affiliate_conversion'];?></td>
	<td ><?php echo $this->get_number_format($value['affiliate_spend']);?></td>
	<?php }?>	
	
	<?php if($pop_enabled ==1){?>
	<td ><?php echo $value['pop_impression'];?></td>
	<td ><?php echo $this->get_number_format($value['pop_spend']);?></td>
	<?php }?>
	</tr>
	
	<tr class="row_data_tr">
	
	<td class="pub-box"><p><?php echo $this->get_label('pub');?></p></td>
	
	<?php if($cpc_enabled ==1){?>
	<td ><?php echo $statistics1[$key]['impression'];?></td>
	<td ><?php echo $statistics1[$key]['click'];?></td>
	<td ><?php echo $this->get_number_format($statistics1[$key]['money_spent']);?></td>
	<?php }?>
	
	<?php if($cpm_enabled ==1 || $html_enabled ==1){
	
		$totimp=0;
		$totspend=0;
		if($cpm_enabled ==1)
		{
			$totimp=$statistics1[$key]['cpm_impression'];
			$totspend=$statistics1[$key]['cpm_spend'];
		
		}
		
		if($html_enabled ==1)
		{
			$totimp=$totimp+$statistics1[$key]['html_impression'];
			$totspend=$totspend+$statistics1[$key]['html_profit'];
		}
		
		
		?>
	<td ><?php echo $totimp;?></td>
	<td ><?php echo $this->get_number_format($totspend);?></td>
	<?php }?>
	
	<?php if($cpa_enabled ==1){?>
	<td ><?php echo $statistics1[$key]['cpa_impression'];?></td>
	<td ><?php echo $statistics1[$key]['cpa_conversion'];?></td>
	<td ><?php echo $this->get_number_format($statistics1[$key]['cpa_spend']);?></td>
	<?php }?>
	
	
	<?php if($cpv_enabled ==1){?>
	<td ><?php echo $statistics1[$key]['cpv_impression'];?></td>
	<td ><?php echo $this->get_number_format($statistics1[$key]['cpv_spend']);?></td>
	<?php }?>		
	
	<?php if($affiliate_enabled ==1){?>
	<td ><?php echo $value['affiliate_conversion'];?></td>
	<td ><?php echo $this->get_number_format($value['affiliate_spend']);?></td>
	<?php }?>	
	
	
	<?php if($pop_enabled ==1){?>
	<td ><?php echo $statistics1[$key]['pop_impression'];?></td>
	<td ><?php echo $this->get_number_format($statistics1[$key]['pop_spend']);?></td>
	<?php }?>
	</tr>
	<?php }?>
</table>

  
  
</td>
</tr> 


<tr id="showstat3" class="showadustatclass statistics_tr">
<td colspan="4" style="padding: 5px;" class="statistics_td"> 

<table class="data_table" cellpadding="0" cellspacing="0" style="width: 100%;">
<tr class="row_heading_tr" >
<td style="padding-left: 10px;width: 50px;"><?php echo $this->get_label('year');?></td>
<td style="width: 30px;" ></td>

<?php if($cpc_enabled ==1){?>
<td style="width: 60px;" ><?php echo $this->get_label('cpc imp');?></td>
<td style="width: 60px;" ><?php echo $this->get_label('cpc clicks');?></td>
<td style="width: 60px;" ><?php echo $this->get_label('cpc spend');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>
<?php }?>


<?php if($cpm_enabled ==1 || $html_enabled ==1){?>
<td style="width: 60px;" ><?php echo $this->get_label('cpm imp');?></td>
<td style="width: 60px;" ><?php echo $this->get_label('cpm spend');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>
<?php }?>


<?php if($cpa_enabled ==1){?>
<td style="width: 60px;" ><?php echo $this->get_label('cpa imp');?></td>
<td style="width: 60px;" ><?php echo $this->get_label('cpa conv');?></td>
<td style="width: 60px;" ><?php echo $this->get_label('cpa spend');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>
<?php }?>


<?php if($cpv_enabled ==1){?>
<td style="width: 60px;" ><?php echo $this->get_label('cpv imp');?></td>
<td style="width: 60px;" ><?php echo $this->get_label('cpv spend');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>
<?php }?>



<?php if($affiliate_enabled ==1){?>
<td style="width: 60px;" ><?php echo $this->get_label('affiliate conv');?></td>
<td style="width: 60px;" ><?php echo $this->get_label('affiliate spend');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>
<?php }?>



<?php if($pop_enabled ==1){?>
<td style="width: 60px;" ><?php echo $this->get_label('pop imp');?></td>
<td style="width: 60px;" ><?php echo $this->get_label('pop spend');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>
<?php }?>

</tr>


<?php 

$statistics=$this->get_advertiser_check_statistics(5);
$statistics1=$this->get_publisher_check_statistics(5);




foreach($statistics as $key=>$value)
	{
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
	<tr class="row_data_tr">
	<td  rowspan="2"><?php echo $data;?></td>
	<td  class="adv-box"><p><?php echo $this->get_label('adv');?></p></td>
	
	<?php if($cpc_enabled ==1){?>	
	<td  ><?php echo $value['impression'];?></td>
	<td  ><?php echo $value['click'];?></td>
	<td  ><?php echo $this->get_number_format($value['money_spent']);?></td>
	<?php }?>
	
	<?php if($cpm_enabled ==1 || $html_enabled ==1){
	
		$totimp=0;
		$totspend=0;
		if($cpm_enabled ==1)
		{
			$totimp=$value['cpm_impression'];
			$totspend=$value['cpm_spend'];
		
		}
		
		if($html_enabled ==1)
		{
			$totimp=$totimp+$value['html_impression'];
			$totspend=$totspend+$value['html_profit'];
		}
		
		
		
		?>
	<td  ><?php echo $totimp;?></td>
	<td  ><?php echo $this->get_number_format($totspend);?></td>
	
	
	
	<?php }?>
	
	
	
	<?php if($cpa_enabled ==1){?>
	<td  ><?php echo $value['cpa_impression'];?></td>
	<td  ><?php echo $value['cpa_conversion'];?></td>
	<td  ><?php echo $this->get_number_format($value['cpa_spend']);?></td>
	<?php }?>
	
	
	<?php if($cpv_enabled ==1){?>
	<td  ><?php echo $value['cpv_impression'];?></td>
	<td  ><?php echo $this->get_number_format($value['cpv_spend']);?></td>
	<?php }?>		
	
	<?php if($affiliate_enabled ==1){?>
	<td  ><?php echo $value['affiliate_conversion'];?></td>
	<td  ><?php echo $this->get_number_format($value['affiliate_spend']);?></td>
	<?php }?>
	
	
	<?php if($pop_enabled ==1){?>
	<td  ><?php echo $value['pop_impression'];?></td>
	<td  ><?php echo $this->get_number_format($value['pop_spend']);?></td>
	<?php }?>
	
	
	
	
	
	</tr>
	
	<tr class="row_data_tr">
	<td class="pub-box"><p><?php echo $this->get_label('pub');?></p></td>
	
	<?php if($cpc_enabled ==1){?>	
	<td ><?php echo $statistics1[$key]['impression'];?></td>
	<td ><?php echo $statistics1[$key]['click'];?></td>
	<td ><?php echo $this->get_number_format($statistics1[$key]['money_spent']);?></td>
	<?php }?>
	
	<?php if($cpm_enabled ==1 || $html_enabled ==1){
	
		$totimp=0;
		$totspend=0;
		if($cpm_enabled ==1)
		{
			$totimp=$statistics1[$key]['cpm_impression'];
			$totspend=$statistics1[$key]['cpm_spend'];
		
		}
		
		if($html_enabled ==1)
		{
			$totimp=$totimp+$statistics1[$key]['html_impression'];
			$totspend=$totspend+$statistics1[$key]['html_profit'];
		}
		
		
		
		?>
	<td ><?php echo $totimp;?></td>
	<td ><?php echo $this->get_number_format($totspend);?></td>
	<?php }?>
	
	
	
	<?php if($cpa_enabled ==1){?>
	<td ><?php echo $statistics1[$key]['cpa_impression'];?></td>
	<td ><?php echo $statistics1[$key]['cpa_conversion'];?></td>
	<td ><?php echo $this->get_number_format($statistics1[$key]['cpa_spend']);?></td>
	<?php }?>
	
	
	<?php if($cpv_enabled ==1){?>
	<td ><?php echo $statistics1[$key]['cpv_impression'];?></td>
	<td ><?php echo $this->get_number_format($statistics1[$key]['cpv_spend']);?></td>
	<?php }?>		
	
	
	
	<?php if($affiliate_enabled ==1){?>
	<td ><?php echo $value['affiliate_conversion'];?></td>
	<td ><?php echo $this->get_number_format($value['affiliate_spend']);?></td>
	<?php }?>
	
	<?php if($pop_enabled ==1){?>
	<td ><?php echo $statistics1[$key]['pop_impression'];?></td>
	<td ><?php echo $this->get_number_format($statistics1[$key]['pop_spend']);?></td>
	<?php }?>
	</tr>
	<?php }?>
</table>

</td>
</tr>
</table> 

</div> 
</div>
<?php $this->dispatch("layout/footer");?>
<script type="text/javascript">
show_pub_stat(1);
</script>