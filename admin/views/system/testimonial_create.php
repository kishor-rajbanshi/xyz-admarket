<?php
$this->dispatch("layout/header/3/_34");

$language_enabled=$this->get_variable('language_enabled');

if($language_enabled ==1)
$localedata=$this->get_result('localedata');

$validate=array(
		"usr"=>array(
				"notNull"=>array($this->get_message("not null"))

		),
		"testimonial"=>array(
				"notNull"=>array($this->get_message("not null"))
		
		)		
);

$usr=$this->get_variable("usr");
$testimonial=$this->get_variable("testimonial");

?>
<div class="sub_menu_main"><?php echo $this->get_label('create testimonial');?></div>

<?php $this->dispatch("links/links/26");?>

<div class="inner-box">
<div class="pages-input">


<table style="width: 100%;" cellpadding="0" cellspacing="0">
<tr><td colspan="2">
<?php 
$form=$this->create_form();
$form->start("testimonial_create",$this->make_url("system/testimonial_create"),"post",$validate);

?>
<table style="width: 100%;" cellpadding="0" cellspacing="0">
<tr>
<td></td>
<td><?php echo $this->get_label('compulsory message');?></td>
</tr>

<tr>
<td style="width: 125px;"><?php echo $this->get_label('select users');?></td>
<td>
<select name="usr" id="usr" style="width: 150px;">
<option value=""><?php echo $this->get_label('select user');?></option>
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
<span class="compulsory">*</span></td>
</tr>

<tr>
<td><?php echo $this->get_label('testimonial');?></td>
<td><textarea  name="testimonial" id="testimonial" rows="10" cols="100"><?php echo $testimonial;?></textarea><span class="compulsory">*</span></td>
</tr>

<tr><td></td><td><input type="submit" name="submit" value="<?php echo $this->get_label('create testimonial');?>"></td></tr>
</table>
<?php $form->end(); ?>
</td></tr>
</table>
</div>
</div>
<?php $this->dispatch("layout/footer");?>