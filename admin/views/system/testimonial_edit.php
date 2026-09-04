<?php
$this->dispatch("layout/header/3/_34");

$language_enabled=$this->get_variable('language_enabled');

if($language_enabled ==1)
$localedata=$this->get_result('localedata');


$validate=array(
		"testimonial"=>array(
				"notNull"=>array($this->get_message("not null"))
		)		
);

$id=$this->get_variable("id");
$pg=$this->get_variable("pg");
$usr=$this->get_variable("usr");

$uid=$this->get_variable("uid");
$testimonial=$this->get_variable("testimonial");

?>
<div class="sub_menu_main"><?php echo $this->get_label('edit testimonial');?></div>

<?php $this->dispatch("links/links/27");?>

<div class="inner-box">
<div class="pages-input">


<table style="width: 100%;" cellpadding="0" cellspacing="0">
<tr><td colspan="2">
<?php 
$form=$this->create_form();
$form->start("testimonial_edit",$this->make_url("system/testimonial_edit"),"post",$validate);
?>
<table style="width: 100%;" cellpadding="0" cellspacing="0">
<tr>
<td></td>
<td><?php echo $this->get_label('compulsory message');?></td>
</tr>

<tr>
<td style="width: 125px;"><?php echo $this->get_label('username');?></td>
<td><strong style="font-size: 12;font-weight: bold;"><?php echo $this->escape($this->get_user_name($uid));?></strong></td>
</tr>

<tr>
<td><?php echo $this->get_label('testimonial');?></td>
<td><textarea  name="testimonial" id="testimonial" rows="10" cols="100"><?php echo $testimonial;?></textarea><span class="compulsory">*</span></td>
</tr>
  

<tr><td></td><td>
<input type="hidden" name="usr" id="usr" value="<?php echo $usr;?>" />
<input type="hidden" name="pg" id="pg" value="<?php echo $pg;?>" />
<input type="hidden" name="id" id="id" value="<?php echo $id;?>" />
<input type="hidden" name="uid" id="uid" value="<?php echo $uid;?>" />

<input type="submit" name="submit" value="<?php echo $this->get_label('edit testimonial');?>"></td></tr>
</table>

<?php $form->end(); ?>
</td></tr>
</table>
</div>
</div>
<?php $this->dispatch("layout/footer");?>