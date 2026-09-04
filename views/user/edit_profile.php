<?php
$this->dispatch("layout/header/9/2/b");

$validate=array(
		"fname"=>array(
				"notNull"=>array($this->get_message("not null"))
		),
		"lname"=>array(
				"notNull"=>array($this->get_message("not null"))
		),

		"address"=>array(
				"notNull"=>array($this->get_message("not null"))
		),
		"phone"=>array(
				"notNull"=>array($this->get_message("not null")),
				"isPhone"=>array($this->get_message("invalid phone no"))
		),
		"email"=>array(
				"notNull"=>array($this->get_message("not null")),
				"isEmail"=>array($this->get_message("email format"))
		),
		"country"=>array(
				"notNull"=>array($this->get_message("not null"))
		)
	);


if(!$_POST)
{
		$res 		= $this->get_result('res');
		$value1 = $res[0];
}

$countryResult = $this->get_result('countryResult');

$uid           = $this->get_variable("uid");
?>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 p-3 mb-3 update-profile">
	<h2 class="page-heading "><div class="page-inner"><i class="fa fa-pencil-square-o icon_red"></i><?php echo $this->get_label('update profile');?></div></h2>


<?php
$form=$this->create_form();
$form->start("edit_profile",$this->make_url("user/edit_profile"),"post",$validate);
?>
  <div class="row">

		<div class="col-sm-6 col-md-6 col-xs-12">
        <div class="form-group mb-3">
            <label for="firstName" class="form-label"><?php echo $this->get_label('first name');?> <span class="compulsory">*</span></label>
            <input class="form-control" type="text" name="fname" id="fname" value="<?php if($_POST) echo $this->get_variable('fname'); else echo $value1['f_name']?>" />
        </div>

        <div class="form-group mb-3">
        	<label for="lastName" class="form-label"><?php echo $this->get_label('last name');?> <span class="compulsory">*</span></label>
            <input class="form-control" type="text" name="lname" id="lname" value="<?php if($_POST) echo $this->get_variable('lname'); else echo $value1['l_name']?>" />
        </div>

				<div class="form-group mb-3">
            <label for="email" class="form-label"><?php echo $this->get_label('email');?> <span class="compulsory">*</span></label>
            <input class="form-control" type="text" name="email" id="email" value="<?php if($_POST) echo $this->get_variable('email'); else echo $value1['email'];?>" />
        </div>

        <div class="form-group mb-3">
            <label for="phoneNo" class="form-label"><?php echo $this->get_label('phone no');?> <span class="compulsory">*</span></label>
            <input class="form-control" type="text" name="phone" id="phone" value="<?php if($_POST) echo $this->get_variable('phone'); else echo $value1['phone']?>" />
        </div>

				<div class="form-group mb-3">
						<label for="address" class="form-label"><?php echo $this->get_label('address');?> <span class="compulsory">*</span></label>
						<textarea name="address" id="address" class="form-control" ><?php if($_POST) echo $this->get_variable('address'); else echo $value1['address'];?></textarea>
				</div>
    </div>

    <div class="col-sm-6 col-md-6 col-xs-12">

        <div class="form-group mb-3">
            <label for="country" class="form-label"><?php echo $this->get_label('country');?> <span class="compulsory">*</span></label>
            <select class="form-select" name="country" id="country">
            <?php
            foreach($countryResult as $key => $value)
            {
	            	$code    = $value['code'];
	            	$country = $value['name'];

	            	if($_POST){?>
            		<option value="<?php echo $code?>" <?php if($this->get_variable('country')==$code){ echo "selected"; } ?>><?php echo $country;?></option>
            		<?php } else { ?>
            		<option value="<?php echo $code?>" <?php if($value1['country']==$code){ echo "selected"; } ?>><?php echo $country;?></option>
            		<?php }
            }
            ?>
            </select>
        </div>

				<div class="form-group mb-3">
						<label for="yourDomain" class="form-label"><?php echo $this->get_label('your domain');?></label>
						<input class="form-control" type="text" name="site" id="site" value="<?php if($_POST) echo $this->get_variable('site'); else echo $value1['domain'];?>" />
				</div>

        <div class="form-group mb-3">
          <label for="companyLogo" class="form-label"><?php echo $this->get_label('company logo');?> </label>
          <input class="form-control" type="file" name="clogo" id="clogo" size="10"/>

          <span class="notification">[<?php echo $this->get_label('supported image format');?>]&nbsp;<br/>[<?php echo $this->get_label('logo size');?>]</span>

          <?php if($value1['logo'] != ""){?>
          <img alt="<?php echo $this->get_label('company logo');?>" title="<?php echo $this->get_label('company logo');?>" style="width:60px;" src="<?php echo DATA_DIR.'/'.LOGO_DIR.'/'.$uid.'/'.$value1['logo'];?>" />
          <a href="<?php echo $this->make_url("user/delete_logo/".$uid);?>" onclick="return confirm('<?php echo $this->get_message('do you really want to delete company logo');?>')"><img alt="<?php echo $this->get_label('delete');?>" src="<?php echo BASE;?>images/delete.png" title="<?php echo $this->get_label('delete');?>" /></a>
          <?php }?>
        </div>

        <div class="form-group mb-3">
            <label for="profilePicture" class="form-label"><?php echo $this->get_label('profile picture');?> </label>
            <input class="form-control" type="file" name="profile_picture" id="profile_picture" size="10"/>

            <span class="notification">[<?php echo $this->get_label('supported image format');?>]&nbsp;<br/>[<?php echo $this->get_label('profile picture size');?>]</span>

            <?php if($value1['profile_picture'] != ""){?>
            <img alt="<?php echo $this->get_label('profile picture');?>" title="<?php echo $this->get_label('profile picture');?>" style="width:60px;" src="<?php echo DATA_DIR.'/'.PROFILE_PICTURE_DIR.'/'.$uid.'/'.$value1['profile_picture'];?>" />
            <a href="<?php echo $this->make_url("user/delete_profile_picture/".$uid);?>" onclick="return confirm('<?php echo $this->get_message('do you really want to delete profile picture');?>')"><img alt="<?php echo $this->get_label('delete');?>" src="<?php echo BASE;?>images/delete.png" title="<?php echo $this->get_label('delete');?>" /></a>
            <?php }?>
        </div>
    </div>

 		<div class="form-group">
        <input class="submit-button" type="submit" name="submit" value="<?php echo $this->get_label('submit');?>" />
    </div>
</div>

<?php $form->end(); ?>
</div>

<?php $this->dispatch("layout/footer");?>
