<?php
$this->dispatch("layout/header/29");
?>
<div class="sub_menu_main"><?php echo $this->get_label('manage tax rules');?></div>

<?php $this->dispatch("links/links/65");?>

<table  style="width:100%;" cellpadding="0" cellspacing="0">

<tr><td colspan="5" height="10px"></td></tr>

<tr><td colspan="5">

<table class="data_table" cellpadding="0" cellspacing="0">
<tr class="row_heading_tr">


<td style="width: 100px;"><?php echo $this->get_label('name');?></td>
<td style="width: 60px;"><?php echo $this->get_label('tax');?> (%)</td>
<td style="width: 150px;"><?php echo $this->get_label('applied for');?></td>
<td style="width: 80px;"><?php echo $this->get_label('status');?></td>
<td ><?php echo $this->get_label('country');?></td>
<td style="width: 100px;"><?php echo $this->get_label('action');?></td>
</tr>

<?php
$res=$this->get_result('res');
if(count($res)==0){	?>
<tr><td colspan="6" height="30px">&nbsp;<?php echo $this->get_label('no records found');?></td></tr>
<?php }else
{

foreach($res as $key=>$value)
{
	$id=$value['id'];

	?>

<tr class="row_data_tr">
<td ><?php echo $value['name'];?> </td>
<td ><?php echo $value['tax'];?> </td>
<td>
<?php 
if($value['user_type'] == 1)
echo $this->get_label('advertiser');
else if($value['user_type'] == 2) 
echo $this->get_label('publisher');
else if($value['user_type'] == 3) 
echo $this->get_label('advertiser & publisher');
else if($value['user_type'] == 4) 
echo $this->get_label('agents'); 
?>
</td>
<td >
<?php
if($value['status'] ==1)
echo $this->get_label('active');
else if($value['status'] ==0)
echo $this->get_label('blocked');
else
echo $this->get_label('pending');
?>
</td>

<td >
<div style="width: 95%;">
<?php 
if($value['country'] !="")
{
	$country_array=json_decode($value['country'],1);
	
	foreach($country_array as $ckey=>$cvalue)
	{
		?>
		<div class="tax-country">
		<?php echo $cvalue;?>
		</div>
		<?php 
	}
}
else
{?> 
<div class="tax-country">
<?php echo $this->get_label('all countries');?> 
</div>
<?php }?>
</div>
</td>
<td>

<a href="<?php echo $this->make_url("system/edit_rule/".$id);?>"><i class="fa fa-edit edit-icon" title="<?php echo $this->get_label('edit');?>"></i></a>

<?php if($value['status'] ==0 || $value['status'] ==-1){?>
<a href="<?php echo $this->make_url("system/change_rule_status/".$id.'/1');?>"><i class="fa fa-check-circle-o approve-icon" title="<?php echo $this->get_label('activate');?>"></i></a>
<?php }else{?>
<a href="<?php echo $this->make_url("system/change_rule_status/".$id.'/0');?>"><i class="fa fa-times-circle-o block-icon" title="<?php echo $this->get_label('block');?>"></i></a>
<?php }?>

<a href="<?php echo $this->make_url("system/delete_rule/".$id);?>" onclick="return confirm('<?php echo $this->get_message('do you really want to delete this tax rule');?>')"><i class="fa fa-trash delete-icon" title="<?php echo $this->get_label('delete');?>"></i></a>
</td>

</tr>

<?php }}?>
</table>
</td>
</tr>
</table>
<?php $this->dispatch("layout/footer");?>