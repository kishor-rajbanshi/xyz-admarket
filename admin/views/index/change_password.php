<?php 
$this->dispatch("layout/header");

$validate=array(
		"password"=>array(
				"notNull"=>array($this->get_message("mandatory"))

		),
		"newpassword"=>array(
				"notNull"=>array($this->get_message("mandatory")),
				"minLength"=>array(3,$this->get_message("check password length"))

		),
		"newpassword1"=>array(
				"notNull"=>array($this->get_message("mandatory")),
				"isSame"=>array('newpassword',$this->get_message("password mismatch"))
		
		) 
);
$form=$this->create_form();
$form->start("change_password",$this->make_url("index/change_password"),"post",$validate);

?>

<div class="sub_menu_main"><?php echo $this->get_label('change pwd');?></div>


<table style="width: 100%;" cellpadding="0" cellspacing="0">




<tr>
<td width="200px"><?php echo $this->get_label('current pwd');?></td>
<td><input type="password" name="password" id="password"><span class="compulsory">*</span></td>
</tr>

<tr><td height="10px" colspan="2"></td></tr>


<tr>
<td><?php echo $this->get_label('new pwd');?></td>
<td><input type="password" name="newpassword" id="newpassword"><span class="compulsory">*</span></td>
</tr>

<tr><td height="10px" colspan="2"></td></tr>


<tr>
<td><?php echo $this->get_label('confirm new pwd');?></td>
<td><input type="password" name="newpassword1" id="newpassword1"><span class="compulsory">*</span></td>
</tr>

<tr><td height="10px" colspan="2"></td></tr>


<tr>
<td></td><td><input type ="submit" value ="<?php echo $this->get_label('submit');?>"></td>
</tr>

</table>

<?php $form->end(); ?>
<?php $this->dispatch("layout/footer");?>