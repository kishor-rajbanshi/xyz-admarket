<?php 

$this->dispatch("layout/header/11/1/b");	

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


	$advurl=$this->make_base_url("user/advertiser_home");
	$puburl=$this->make_base_url("user/publisher_home");
	
?>

<div class="container"><h2 class="page_heading"><?php echo $this->get_label('change password');?></h2>
<div class="page_heading-btm"></div>
</div>


<?php 
$form=$this->create_form();
$form->start("change_password",$this->make_url("user/change_password"),"post",$validate);
?>


  <div class="container label_style special-label">
  <div class="col-md-12 col-sm-12 col-xs-12 box_style new_box_inner">
   <div class="col-md-12 col-sm-12 col-xs-12">
   <div class="col-md-12 col-sm-12 col-xs-12 box_div box_div_bg">






<div class="col-md-3 col-sm-3 col-xs-12">
<div class="form-group">
<label><?php echo $this->get_label('current password');?> <span class="compulsory">*</span></label>
<input class="form-control" type="password" name="password" id="password"  />
</div>
</div>
<div class="col-md-3 col-sm-3 col-xs-12">
<div class="form-group">
<label><?php echo $this->get_label('new password');?> <span class="compulsory">*</span></label>
<input class="form-control" type="password" name="newpassword" id="newpassword"  />
<span class="notification">[<?php echo $this->get_label('password length limit',array('x'=>Configuration::get_instance()->read('password_length')));?>]</span>
</div>
</div>
<div class="col-md-3 col-sm-3 col-xs-12">
<div class="form-group">
<label><?php echo $this->get_label('confirm new password');?> <span class="compulsory">*</span></label>
<input class="form-control" type="password" name="newpassword1" id="newpassword1"  />
</div>
</div>
<div class="col-md-3 col-sm-3 col-xs-12">
<div class="form-group">
<label></label><input class="btn btn-primary btn-lg btn_margin"  type ="submit" value ="<?php echo $this->get_label('change password');?>">
</div>
</div>

</div>

</div></div></div>
<?php $form->end(); ?>
<?php $this->dispatch("layout/footer");?>