<?php
$this->dispatch("layout/header/19");
?>

<div class="sub_menu_main"><?php echo $this->get_label('manage email templates');?></div>


<table style="width: 100%;">

<tr><td colspan="4" height="20px"></td></tr>

<tr><td colspan="4">
<table class="data_table" cellpadding="0" cellspacing="0">
<tr class="row_heading_tr">



<td><?php echo $this->get_label('subject');?></td>
<td><?php //echo $this->get_label('message');?></td>
<td><?php echo $this->get_label('edit');?></td>
</tr>
<tr><td colspan="4"></td></tr>
<?php

$res=$this->get_result('res'); 
foreach($res as $key=>$value)
{
$id=$value['id'];
$admarket_name=Configuration::get_instance()->read('admarket_name');
$value['subject'] = str_replace("{ADMARKETNAME}",$admarket_name, $value['subject']);
?>
<tr class="row_data_tr">
<td ><?php echo $value['subject'];?></td>
<td ><?php //echo substr($value['message'], 0,30);?></td>
<td ><a href="<?php echo $this->make_url("email_template/edit/".$id);?>"><i class="fa fa-edit edit-icon" title="<?php echo $this->get_label('edit');?>"></i></a></td>



<?php 
}?>
</table>
</td></tr></table>
<?php $this->dispatch("layout/footer");?>