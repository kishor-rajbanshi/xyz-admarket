<?php

$this->dispatch("layout/header/10/1/b");	

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
		"site"=>array(
				"notNull"=>array($this->get_message("not null")),
				"isDomain"=>array($this->get_message("invalid domain"))
		),
		"country"=>array(
				"notNull"=>array($this->get_message("not null"))
		)		
		
	);


if(!$_POST)
{
$res=$this->get_result('res');
$value1=$res[0];
}

$re=$this->get_result('re');

$uid=$this->get_variable("uid");


	$advurl=$this->make_base_url("user/advertiser_home");
	$puburl=$this->make_base_url("user/publisher_home");
						
?>

<div class="container"><h2 class="page_heading"><?php echo $this->get_label('update profile');?></h2>

<div class="page_heading-btm"></div>
</div>


  <div class="container label_style special-label">
<div class="col-md-12 col-sm-12 col-xs-12 box_style new_box_inner">
   <div class="col-md-12 col-sm-12 col-xs-12">
  <div class="col-md-12 col-sm-12 col-xs-12 box_div box_div_bg">
 
<?php 
$form=$this->create_form();
$form->start("edit_profile",$this->make_url("user/edit_profile"),"post",$validate); 
?>
 
 
 


<div class="col-sm-6 col-md-6 col-xs-12">
<div class="form-group">
<label >
<?php echo $this->get_label('first name');?> <span class="compulsory">*</span></label>
<input class="form-control form_width" type="text" name="fname" id="fname" value="<?php if($_POST) echo $this->get_variable('fname'); else echo $value1['f_name']?>">
</div>

<div class="form-group">
<label >
<?php echo $this->get_label('last name');?> <span class="compulsory">*</span></label>
<input class="form-control form_width" type="text" name="lname" id="lname" value="<?php if($_POST) echo $this->get_variable('lname'); else echo $value1['l_name']?>">
</div>

<div class="form-group">
<label >
<?php echo $this->get_label('address');?> <span class="compulsory">*</span></label>
<textarea style="height:100px;" name="address" id="address" class="form_width" ><?php if($_POST) echo $this->get_variable('address'); else echo $value1['address']?></textarea>
</div>

<div class="form-group">
<label ><?php echo $this->get_label('phone no');?> <span class="compulsory">*</span></label>
<input class="form-control form_width" type="text" name="phone" id="phone" value="<?php if($_POST) echo $this->get_variable('phone'); else echo $value1['phone']?>">
</div>
</div>

<div class="col-sm-6 col-md-6 col-xs-12">
<div class="form-group">
<label >
<?php echo $this->get_label('email');?> <span class="compulsory">*</span></label>
<input class="form-control form_width" type="text" name="email" id="email" value="<?php if($_POST) echo $this->get_variable('email'); else echo $value1['email']?>">
</div>

<div class="form-group">
<label >
<?php echo $this->get_label('your domain');?> <span class="compulsory">*</span></label>
<input class="form-control form_width" type="text" name="site" id="site" value="<?php if($_POST) echo $this->get_variable('site'); else echo $value1['domain']?>">
</div>


<div class="form-group">
<label >
<?php echo $this->get_label('country');?> <span class="compulsory">*</span></label>

<select style="width:98%;" class="form-control" name="country" id="country" >
<?php 
foreach($re as $key=>$r)
{
	$code=$r['code'];
	$country=$r['name'];
	
	if($_POST)
	{
?>
	<option value="<?php echo $code?>" <?php if($this->get_variable('country')==$code){ echo "selected"; } ?>><?php echo $country;?></option>
	<?php } else {?>
<option value="<?php echo $code?>" <?php if($value1['country']==$code){ echo "selected"; } ?>><?php echo $country;?></option>
<?php 
	}
}
?>
</select>
</div>

<div class="form-group">
<label >
<?php echo $this->get_label('company logo');?> <span class="compulsory">*</span></label>
<input class="form-control form_width" type="file" name="clogo" id="clogo" size="10"/><br/>

<span class="notification">[<?php echo $this->get_label('supported image format');?>]&nbsp;<br/>[<?php echo $this->get_label('logo size');?>]</span>

<?php if($value1['logo']!="")
{
?>
<img alt="<?php echo $this->get_label('company logo');?>" title="<?php echo $this->get_label('company logo');?>" src="<?php echo DATA_DIR.'/'.LOGO_DIR.'/'.$uid.'/'.$value1['logo'];?>" />
<a href="<?php echo $this->make_url("user/delete_logo/".$uid);?>" onclick="return confirm('<?php echo $this->get_message('do you really want to delete company logo');?>')"><img alt="<?php echo $this->get_label('delete');?>" src="images/delete.png" title="<?php echo $this->get_label('delete');?>" /></a>
<?php }?></div>


<div class="form-group">
<input class="btn btn-primary btn-lg" type="submit" name="submit" value="<?php echo $this->get_label('submit');?>">
</div></div>




<?php $form->end(); ?>
</div></div></div></div>

<?php $this->dispatch("layout/footer");?>