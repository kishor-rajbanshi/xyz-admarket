<?php
$this->dispatch("layout/header/9/4/b");

$validate = array(
		"currentEmail"=>array(
				"notNull"=>array($this->get_message("not null")),
				"isEmail"=>array($this->get_message("email format"))
		),
		"newEmail"=>array(
				"notNull"=>array($this->get_message("not null")),
				"isEmail"=>array($this->get_message("email format"))
		)
);


$currentEmail = $this->get_variable("currentEmail");
$newEmail     = $this->get_variable("newEmail");
?>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 p-3 mb-3 change-email">
	<h2 class="page-heading "><div class="page-inner"><i class="fa fa-envelope icon_red"></i><?php echo $this->get_label('change email');?></div></h2>

	<?php
	$form=$this->create_form();
	$form->start("change_email",$this->make_url("user/change_email"),"post",$validate);
	?>
  	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 row">

			<div class="col-md-12 col-sm-12 col-xs-12 mb-3">
		     <label for="currentEmail" class="form-label"><?php echo $this->get_label('current email');?> <span class="compulsory">*</span></label>
		     <input class="form-control" type="text" name="currentEmail" id="currentEmail"  />
			</div>

	    <div class="col-md-12 col-sm-12 col-xs-12 mb-3">
	       <label for="newEmail" class="form-label"><?php echo $this->get_label('new email');?> <span class="compulsory">*</span></label>
	       <input class="form-control" type="text" name="newEmail" id="newEmail"  />
	    </div>

	    <div class="col-md-12 col-sm-12 col-xs-12">
	       <input class="submit-button" type ="submit" value ="<?php echo $this->get_label('submit');?>" />
	    </div>

		</div>
	<?php $form->end(); ?>
</div>
<?php $this->dispatch("layout/footer");?>
