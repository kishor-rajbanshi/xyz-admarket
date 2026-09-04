<?php
$uid=$this->get_variable('uid');
$duration=$this->get_variable('duration');
?>
<div class="report_main_table_data1"> 
<table id="table-desktop6" class="data_table" cellpadding="0" cellspacing="0">
<tr class="data_table_head">
<td><?php echo $this->get_label('no');?></td>
<td><?php echo $this->get_label('adunit name');?></td>
<td><?php echo $this->get_label('impressions');?></td>
<td><?php echo $this->get_label('clicks');?></td>
<td><?php echo $this->get_label('conversions');?></td>
<td><bdi><?php echo $this->get_label('ctr');?></bdi></td>
<td><bdi><?php echo $this->get_label('conversion ratio');?></bdi></td>
<td><bdi><?php echo $this->get_label('profit');?> (<?php echo Configuration::get_instance()->read('currency_symbol');?>)</bdi></td>
</tr>

<?php 
$results=$this->get_publisher_top_adunits($duration,$uid,6);

$number_flag=0;
$no=1;
foreach($results as $key=>$value)
{
	if($no==6)
	break;
	
	$aduid=$value[0];
	
	if($aduid >0)
	$number_flag=1;
	
	$statistics=$this->get_publisher_statistics($duration,$uid,$aduid);
	
	if($number_flag==1){?>
			
<tr class="data_table_content">
<td><?php echo $no;?></td>
<td><a target="_parent" href="<?php echo $this->make_base_url("adunit/detail_statistics/".$aduid);?>"><?php echo $this->escape($this->get_adunit_name($aduid));?></a></td>

<td><?php echo $statistics['cpa_impression'];?></td>
<td><?php echo $statistics['cpa_click'];?></td>
<td><?php echo $statistics['cpa_conversion'];?></td>
<td><?php echo $statistics['cpa_ctr'];?></td>
<td><?php echo $statistics['cpa_ratio'];?></td>
<td><bdi><?php echo $this->get_number_format($statistics['cpa_profit']);?></bdi></td>
</tr>
<?php }

$no=$no+1;
}?>	

<?php if($number_flag==0){?>
<tr class="data_table_message" style="font-size: 12px;"><td colspan="8"><?php echo $this->get_label('no adunits found');?></td></tr>
<?php }?>	
</table>
</div>

</body>
</html>