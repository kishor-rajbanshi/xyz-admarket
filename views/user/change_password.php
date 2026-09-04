<?php
$this->dispatch("layout/header/9/3/b");

$validate=array(
		"password"=>array(
				"notNull"=>array($this->get_message("not null"))

		),
		"newpassword"=>array(
				"notNull"=>array($this->get_message("not null")),
				"minLength"=>array(Configuration::get_instance()->read('password_length'),$this->get_message("password length",array('x'=>Configuration::get_instance()->read('password_length'))))

		),
		"newpassword1"=>array(
				"notNull"=>array($this->get_message("not null")),
				"isSame"=>array('newpassword',$this->get_message("password mismatch"))

		)
);
?>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 p-3 mb-3 change-password">
	<h2 class="page-heading "><div class="page-inner"><i class="fa fa-key icon_red"></i><?php echo $this->get_label('change password');?></div></h2>
	<?php
	$form=$this->create_form();
	$form->start("change_password",$this->make_url("user/change_password"),"post",$validate);
	?>
	 <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 row">

			<div class="col-md-12 col-sm-12 col-xs-12 mb-3">
				<div class="form-group">
					<label for="password" class="form-label"><?php echo $this->get_label('current password');?> <span class="compulsory">*</span></label>
					<input class="form-control" type="password" name="password" id="password"  />
				</div>
			</div>

			<div class="col-md-12 col-sm-12 col-xs-12 mb-3">
				<div class="form-group">
					<label for="newpassword" class="form-label"><?php echo $this->get_label('new password');?> <span class="compulsory">*</span></label>
					<input class="form-control" type="password" name="newpassword" id="newpassword"  />
					<div class="notification">[<?php echo $this->get_label('password length limit',array('x'=>Configuration::get_instance()->read('password_length')));?>]</div>
				</div>
			</div>

			<div class="col-md-12 col-sm-12 col-xs-12 mb-3">
				<div class="form-group">
					<label for="newpassword1" class="form-label"><?php echo $this->get_label('confirm new password');?> <span class="compulsory">*</span></label>
					<input class="form-control" type="password" name="newpassword1" id="newpassword1"  />
				</div>
			</div>

			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="form-group">
					<input class="submit-button" type ="submit" value ="<?php echo $this->get_label('change password');?>" />
				</div>
			</div>
		</div>
  <?php $form->end(); ?>
</div>
<?php $this->dispatch("layout/footer");?>
