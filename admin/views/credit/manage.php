<?php $this->dispatch("layout/header/4/_46");?>


<div class="sub_menu_main"><?php echo $this->get_label('manage credit text');?></div>

<?php $this->dispatch("links/links/20");?>

<table style="width: 100%;" cellpadding="0" cellspacing="0" >
<tr><td colspan="3" >

<table class="data_table" cellpadding="0" cellspacing="0">
<tr class="row_heading_tr">
<td ><?php echo $this->get_label('credit');?></td>
<td ><?php echo $this->get_label('credit icon');?></td>
<td ><?php echo $this->get_label('credit type');?></td>
<td ><?php echo $this->get_label('options');?></td>

</tr>
<tr><td colspan="4"></td></tr>
<?php 
$res=$this->get_result('res');
if(count($res)==0){?>
	 <tr><td colspan="4">&nbsp;<?php echo $this->get_label("no records found");?></td></tr>
	 <?php 
}
else 
{

foreach($res as $key=>$value)
{
	$id=$value['id'];
	
?>
<tr class="row_data_tr">
<td  style="width: 200px;">
<?php 
if($value['type'] ==0)
echo $value['credittext'];
else
{

if($value['image'] !=''){?>
<img src="<?php echo "../".DATA_DIR."/credit/".$value['image'];?>" style="max-width: 200px;max-height: 50px;margin-top: 5px;margin-bottom: 5px;" />
<?php }else{?>&nbsp;<?php }
}?></td>
<td  style="width: 200px;">
<?php 
if($value['icon_type'] ==0)
echo $value['icon_content'];
else
{

if($value['icon_content'] !=''){?>
<img src="<?php echo "../".DATA_DIR."/credit/".$value['icon_content'];?>" style="max-width: 15px;max-height: 15px;margin-top: 5px;margin-bottom: 5px;" />
<?php }else{?>&nbsp;<?php }
}?>
</td>
<td  style="width: 250px;">
<?php 
if($value['type'] ==0)
echo $this->get_label('text credit');
else if($value['type'] ==1)
echo $this->get_label('image credit');
?></td>

<td align="left">
<a href="<?php echo $this->make_url("credit/edit/".$id);?>"><i class="fa fa-edit edit-icon" title="<?php echo $this->get_label('edit');?>"></i></a>

<a href="<?php echo $this->make_url("credit/delete/".$id);?>" onclick="return confirm('<?php echo $this->get_message('credittext delete alert');?>');"><i class="fa fa-trash delete-icon" title="<?php echo $this->get_label('delete');?>"></i></a>

</td>
</tr>
<?php } }?>	
</table>
</td>
</tr>	
</table>
<?php $this->dispatch("layout/footer");?>	