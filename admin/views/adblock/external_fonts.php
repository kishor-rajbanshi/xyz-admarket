<?php 
$this->dispatch("layout/header/4/_50");

$pg = $this->get_variable("pg");
?>
<div class="sub_menu_main"><?php echo $this->get_label('external fonts');?></div>

<?php $this->dispatch("links/links/69");?>

<table  style="width:100%;" cellpadding="0" cellspacing="0">

<tr><td colspan="5" height="10px"></td></tr>

<tr><td colspan="5">

<table class="data_table" cellpadding="0" cellspacing="0">
<tr class="row_heading_tr">
<td><?php echo $this->get_label('name');?></td>
<td><?php echo $this->get_label('slug name');?></td>
<td style="width: 300px;"><?php echo $this->get_label('file name');?></td>
<td><?php echo $this->get_label('status');?></td>
<td><?php echo $this->get_label('action');?></td>
</tr>

<?php 
$res = $this->get_result('res');
if(count($res)==0){?>
<tr><td colspan="5" style="height: 30px;"><?php echo $this->get_label('no records found');?></td></tr>
<?php 
} else {

foreach($res as $key=>$value)
{
	   $fontID = $value['id'];
	   ?>
<tr class="row_data_tr">
<td ><?php echo $value['name'];?> </td>
<td ><?php echo $value['slug_name'];?></td>
<td >
<?php 
if($value['file_name'] != "")
echo str_replace($fontID.'_', '', $value['file_name']);
else
echo "-";
?>
</td>
<td >
<?php 
if($value['status'] == 1)
echo $this->get_label('active');
else
echo $this->get_label('blocked');
?>
<?php 
if($value['status'] == 0){?>
<div><?php echo $this->get_label('font file not properly uploaded');?></div>
<?php } ?>
</td>

<td >
 <a href="<?php echo $this->make_url("adblock/edit_external_font/".$fontID."/".$pg);?>"><i class="fa fa-edit edit-icon" title="<?php echo $this->get_label('edit');?>"></i></a>
 
 <a href="<?php echo $this->make_url("adblock/delete_external_font/".$fontID."/".$pg);?>" onclick="return confirm('<?php echo $this->get_message('do you really want to delete this external font');?>')"><i class="fa fa-trash delete-icon" title="<?php echo $this->get_label('delete');?>"></i></a>
</td>
</tr>
<?php }?>	
<tr><td colspan="5" align="center"><?php echo $this->get_variable('pagination');?></td></tr>
<?php }?>
</table>
</td>
</tr>
</table>
<?php $this->dispatch("layout/footer");?>	