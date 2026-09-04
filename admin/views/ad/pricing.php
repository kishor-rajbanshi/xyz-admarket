<table  style="width: 98%;padding: 5px;" cellpadding="0" cellspacing="0">
<tr><td>
<?php 
$aid=$this->get_variable('aid');

$adresult=$this->get_result('adrow');

$adrow=$adresult[0];

$ad_pricing=$adrow['display_type'];

$pricing_rate="";

if($ad_pricing == 0)
$pricing_rate=$this->get_label('cpc rate');
else if($ad_pricing == 1)
$pricing_rate=$this->get_label('cpm rate');
else if($ad_pricing == 6)
$pricing_rate=$this->get_label('cpa rate');
else if($ad_pricing == 18)
$pricing_rate=$this->get_label('cpp rate');
?>


<table  style="width: 100%;" cellpadding="0" cellspacing="0" class="data_table">
<tr class="row_heading_tr">
<?php if($adrow['default_rate'] >0){?>
<td width="140px"><?php echo $pricing_rate;?></td>
<?php }?>
<td width="140px"><?php echo $this->get_label('status');?></td>
<td width="140px"><?php echo $this->get_label('budget');?></td>
<?php if($adrow['default_rate'] >0){?>
<td width="140px"><?php echo $this->get_label('daily budget');?></td>
<?php }?>
<td width="150px"><?php echo $this->get_label('start date');?></td>
<td width="150px"><?php echo $this->get_label('end date');?></td>
</tr>

<?php 
$expiry=$this->get_result('expiry');

if($adrow['default_rate'] >0 && $adrow['pricing_status'] == 1){?>
<tr class="data_table_content">
<td ><?php echo $this->get_money_format($adrow['default_rate']);?></td>
<td ><?php echo $this->get_pricing_value($aid,$adrow['pricing_status']);?></td>
<td ><?php echo $this->get_money_format($adrow['total_budget_used'])." / ".$this->get_money_format($adrow['total_ad_budget']);?></td>
<td ><?php echo $this->get_money_format($adrow['daily_budget_used'])." / ".$this->get_money_format($adrow['daily_budget']);?></td>
<td ><?php if($adrow['start_date'] >0) {echo $this->get_date_format(2,$adrow['start_date']);}else {echo $this->get_label('na');}?></td>
<td ><?php echo $this->get_label('na');?></td>
</tr>
<?php }else{ 

if(count($expiry) == 0)
{
?>	
<tr class="data_table_content">
<td colspan="6"><?php echo $this->get_label('no records found');?></td>
</tr>	
<?php 
}

}

foreach($expiry as $key =>$value){?>
<tr class="data_table_content">
<?php if($adrow['default_rate'] >0){?>
<td ><?php echo $this->get_label('na');?></td>
<?php }?>
<td ><?php echo $this->get_pricing_value($aid,$value['status']);?></td>
<td ><?php echo $this->get_money_format($value['budget_used'])." / ".$this->get_money_format($value['budget']);?></td>
<?php if($adrow['default_rate'] >0){?>
<td ><?php echo $this->get_label('na');?></td>
<?php }?>
<td ><?php if($value['start_date'] >0) {echo $this->get_date_format(2,$value['start_date']);}else {echo $this->get_label('na');}?></td>
<td ><?php if($value['end_date'] >0) {echo $this->get_date_format(2,$value['end_date']);}else {echo $this->get_label('na');}?></td>
</tr>
<?php }?>
</table>
</td></tr></table>