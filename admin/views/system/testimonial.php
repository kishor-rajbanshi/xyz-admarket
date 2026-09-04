<?php $this->dispatch("layout/header/3/_34");?>

<div class="sub_menu_main"><?php echo $this->get_label('manage testimonials');?></div>

<?php $this->dispatch("links/links/28");?>

<table style="width: 100%;" cellpadding="0" cellspacing="0">
<tr><td>
<?php 
$usr=$this->get_variable('usr');
$pg=$this->get_variable("pg");

$form=$this->create_form();
$form->start("managetestimonial",$this->make_url("system/testimonial"),"post");
?>
<div class="search_div">
<table class="search_div_table">
  <tr>
    <td>    
  <select name="usr" id="usr" style="width: 150px;">
<option value="" <?php if($usr==0) echo "selected";?>><?php echo $this->get_label('select user');?></option>
<?php 
$res1=$this->get_result('res1');
foreach($res1 as $key1=>$value1)
{
?>
<option value="<?php echo $value1['id'];?>" <?php if($usr==$value1['id']) echo "selected";?>><?php echo $value1['username'];?></option>
<?php 
}
?>
</select>
    
    </td>
    <td>&nbsp;<input type="submit" name="search" value="<?php echo $this->get_label('go');?>" /></td>
  </tr>
  </table></div>
 <?php $form->end(); ?> 
</td></tr>


<tr><td height="10px" colspan="3"></td></tr>


</table>

<table  style="width: 100%;" class="data_table" cellpadding="0" cellspacing="0">

<tr class="row_heading_tr">
<td><?php echo $this->get_label('username');?></td>
<td><?php echo $this->get_label('testimonial');?></td>
<td><?php echo $this->get_label('time');?></td>
<td><?php echo $this->get_label('options');?></td>
</tr>


<tr><td colspan="4"></td></tr>
<?php 
$res=$this->get_result('res');


if(count($res)==0)
{
?>
<tr><td colspan="4" height="30px">&nbsp;<?php echo $this->get_label('no records found');?></td></tr>
<?php 
}



foreach($res as $key=>$value)
{
	$tid=$value['id'];
	
	?>
<tr class="row_data_tr">

<td ><a href="<?php echo $this->make_url("user/profile/".$value['uid']."/0");?>"><?php echo $this->escape($this->get_user_name($value['uid']));?></a></td>
<td ><?php echo substr($value['description'],0,50);?></td>
<td ><?php echo $this->get_date_format(2,$value['time']);?></td>

<td >
<a href="<?php echo $this->make_url("system/testimonial_edit/".$tid."/".$usr."/".$pg);?>"><i class="fa fa-edit edit-icon" title="<?php echo $this->get_label('edit');?>"></i></a>

<a href="<?php echo $this->make_url("system/testimonial_delete/".$tid."/".$usr."/".$pg);?>" onclick="return confirm('<?php echo $this->get_message('do you really want to delete this testimonial');?>')"><i class="fa fa-trash delete-icon" title="<?php echo $this->get_label('delete');?>"></i></a>

</td>

</tr>
<?php 
}?>

<tr><td align="center" colspan="4"><?php echo $this->get_variable('pagination');?></td></tr>
</table>
<?php $this->dispatch("layout/footer");?>	