<?php 
$expiry=$this->get_result('expiry');

if(count($expiry) > 0){?>
<div class="checkbox_head" style="margin-bottom: 10px;"><?php echo $this->get_label('expired pricing');?></div>

<table id="table-desktop" class="data_table" cellspacing="0" cellpadding="0" style="width: 100%;">

<tr class="data_table_head">
<td style="width: 140px;"><?php echo $this->get_label('budget');?></td>
<td style="width: 140px;"><?php echo $this->get_label('budget used');?></td>
<td style="width: 180px;"><?php echo $this->get_label('start date');?></td>
<td style="width: 180px;"><?php echo $this->get_label('end date');?></td>
<td style="width: 180px;"><?php echo $this->get_label('status');?></td>
</tr>


<?php foreach($expiry as $key =>$value){?>
<tr class="data_table_content">
<td><bdi><?php echo $this->get_money_format($value['budget']);?></bdi></td>
<td><bdi><?php echo $this->get_money_format($value['budget_used']);?></bdi></td>
<td><?php if($value['start_date'] >0) {echo $this->get_date_format(2,$value['start_date']);}else {echo $this->get_label('na');}?></td>
<td><?php if($value['end_date'] >0) {echo $this->get_date_format(2,$value['end_date']);}else {echo $this->get_label('na');}?></td>
<td><bdi><?php if($value['status'] == 0) echo $this->get_label('cancelled'); else echo $this->get_label('completed');?></bdi></td>
</tr>
<?php }?>
</table>
<?php } ?>