<?php 
$duration=$this->get_variable('duration');
$uid=$this->get_variable('uid');
?>
<div class="report_main_table_data1">
<?php $results=$this->get_advertiser_top_ads($duration,$uid,1);?>

<table id="table-desktop1" class="data_table" cellpadding="0" cellspacing="0">
<tr class="data_table_head">
<td><?php echo $this->get_label('no');?></td>
<td><?php echo $this->get_label('name');?></td>
<td><?php echo $this->get_label('type');?></td>
<td><?php echo $this->get_label('impressions');?></td>
<td><?php echo $this->get_label('clicks');?></td>
<td><bdi><?php echo $this->get_label('ctr');?></bdi></td>
<td><bdi><?php echo $this->get_label('money spend');?> (<?php echo Configuration::get_instance()->read('currency_symbol');?>)</bdi></td>
<td><?php echo $this->get_label('action');?></td>
</tr>
<?php 



$number_flag=0;
$no=1;
foreach($results as $key=>$value)
{
	if($no==6)
	break;
	
	$adid=$value[0];
	
	if($adid >0)
	$number_flag=1;
	
	$statistics=$this->get_advertiser_statistics($duration,$uid,$adid,0);

	if($number_flag==1)
	{
		
	?>
<tr class="data_table_content">

<td><?php echo $no;?></td>
<td ><a target="_parent" href="<?php echo $this->make_base_url("ad/view/".$adid);?>"><?php echo $this->escape($this->get_ad_name($adid));?></a></td>
<td ><?php echo $this->get_ad_type($this->get_adtype_from_id($adid));?></td>
<td ><?php echo $statistics['cpm_impression'];?></td>
<td ><?php echo $statistics['cpm_click'];?></td>
<td ><?php echo $statistics['cpm_ctr'];?></td>
<td ><bdi><?php echo $this->get_number_format($statistics['cpm_spend']);?></bdi></td>
<td ><a class="name link_button" target="_parent" href="<?php echo $this->make_base_url("ad/detailed_statistics/".$adid);?>"><?php echo $this->get_label('detailed');?></a></td>
</tr>


<?php 
	}
	
	$no=$no+1;
}
?>	
<?php /*if(count($results) >=6){?>
<tr><td colspan="7"></td><td><a target="_parent" href="<?php echo $this->make_base_url("ad/statistics");?>"><?php echo $this->get_label('more ads');?></a></td></tr>
<?php }*/?>

<?php if($number_flag==0){?>
<tr class="data_table_message"><td colspan="8"><?php echo $this->get_label('no ads found');?></td></tr>
<?php }?>
</table>
</div>

<div style="height: 10px;"></div>