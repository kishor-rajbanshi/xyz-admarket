<?php 
$active_theme=$this->read_cookie_param('active_theme');
if($active_theme=="")
$active_theme=Configuration::get_instance()->read('active_theme');

$register_success=intval($this->get_variable('register_success'));

$external_theme_exists=Configuration::get_instance()->read('exsternal_theme_exists');

if($register_success ==1){?>


<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>
<?php 
if($this->get_title('')=="")
echo Configuration::get_instance()->read('admarket_name');
else
echo $this->get_title().' - '.Configuration::get_instance()->read('admarket_name');
?>
</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/jquery.min.js'></script>

<link rel="icon" href="<?php echo BASE;?>favicon.ico">
<link rel="stylesheet" type="text/css" media="all" href="<?php echo BASE;?>css/public-style.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo THEME_DIR_PATH.$active_theme;?>/css/style.css" />
<link rel="stylesheet" href="<?php echo COMMON_DIR_PATH;?>font-awesome/css/font-awesome.css" />


</head>
<body class="class-body">
<table style="width:100%;height:100%;">
<tr>
<td class="confirm-success">

<div class="logindiv" id="logindiv">
<div class="loginbox">
<div class="title-login" style="height:75px;">
<div class="title-image"><img src="images/admarket-logo.png" /></div></div>
<p style="text-align:center; padding-top:20px; color:#666666;">
<span><?php 
if($external_theme_exists == 1)
echo $this->get_label('registration success page',array('x'=>Configuration::get_instance()->read('exsternal_theme_url')));
else
echo $this->get_label('registration success page',array('x'=>BASE));
?></span>
</p>
</div>	
</div>

</td>
</tr>
</table>
<script type="text/javascript">
$(document).ready(function(){
	$('.confirm-success').css('height',$(window).height()+'px');

	totalwidth=$(window).width();

	if(totalwidth >260)
	{
		widthdivide=(totalwidth/2)-130;
		$('.logindiv').css('margin-left',widthdivide+'px');
	}

	$(window).resize(function(){
		$('.confirm-success').css('height',$(window).height()+'px');

		totalwidth=$(window).width();

		if(totalwidth >260)
		{
			widthdivide=(totalwidth/2)-130;
			$('.logindiv').css('margin-left',widthdivide+'px');
		}
		
	});
});
</script>
</dody> 
</html>
<?php }else{?>

<?php
if($external_theme_exists != 1)
$this->dispatch("layout/header/20");
else 
{
?>
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/jquery.min.js'></script>
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/jquery-ui-1.8.23.custom.min.js'></script>
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/bootstrap.min.js'></script>

<link rel="icon" href="<?php echo BASE;?>favicon.ico">
<link rel="stylesheet" type="text/css" media="all" href="<?php echo COMMON_DIR_PATH;?>css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo COMMON_DIR_PATH;?>font-awesome/css/font-awesome.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo THEME_DIR_PATH.$active_theme;?>/css/animate.min.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo BASE;?>css/public-style.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo THEME_DIR_PATH.$active_theme;?>/css/style.css" />

<?php
$direction=$this->get_variable('direction');
if($direction ==1) {?>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo COMMON_DIR_PATH;?>css/bootstrap-rtl.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo BASE;?>css/public-rtl-style.css" />
<?php }
}
?>
<script type="text/javascript">
function trim(stringData)
{
return stringData.replace(/(^\s*|\s*$)/, "");
}

function loadTerms()
{
	window.open("<?php echo $this->make_url("index/terms/1"); ?>","popup","menubar=1,resizable=1,width=800,height=450,scrollbars=1");
}



function checkavailable()
{
	var username=trim($('#username').val());

   if(username =="")
   return;	   

   $('#imgload').show();
   $.ajax(
   {
		type: "GET",
		url: "<?php echo $this->make_url("user/check_availability/")?>"+username,
		success: function(msg)
		{
			$('#imgload').hide();
			$('#check').html(msg);
		}
	});
	
}
</script>
<?php
$validate=array(
		"username"=>array(
				"notNull"=>array($this->get_message("not null")),
				"isUsername"=>array($this->get_message("invalid input for user name"))
		),
		"password"=>array(
				"notNull"=>array($this->get_message("not null")),
				"minLength"=>array(Configuration::get_instance()->read('password_length'),$this->get_message("password length",array('x'=>Configuration::get_instance()->read('password_length'))))

		),
		"cpassword"=>array(
				"notNull"=>array($this->get_message("not null")),
				"isSame"=>array('password',$this->get_message("password mismatch"))
		
		),
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
		
		),
		"terms"=>array(
				"isChecked"=>array($this->get_message("terms condition agree"))
		
		)
);


$re=$this->get_result('re');

$recaptcha_public_key=$this->get_variable("recaptcha_public_key");
$terms=$this->get_variable('terms');
$countrycurrent=$this->get_variable("countrycurrent");

echo Configuration::get_instance()->read('google_analytics_code');
?>


<?php if($external_theme_exists == 1)
{
						if(isset($_POST['user_register'])){?>
						  <div class="container" style="text-align: center;margin: 10px;">
	               		  <div style="width: 100%;float: left;">	
						  <a href="<?php echo Configuration::get_instance()->read('exsternal_theme_url');?>">
						  <?php if(Configuration::get_instance()->read('public_page_logo') !=""){?>
					      <img style="max-width: 235px;max-height: 100px;" src="<?php echo BASE.DATA_DIR;?>/logo/<?php echo Configuration::get_instance()->read('public_page_logo');?>" alt="<?php echo Configuration::get_instance()->read('admarket_name');?>" title="<?php echo Configuration::get_instance()->read('admarket_name');?>" />
					      <?php  }else{ ?>
					      <div style="white-space: nowrap;"><?php echo Configuration::get_instance()->read('admarket_name');?></div>
					      <?php } ?>
					      </a>	               
	                      </div>
	                      </div>
	                      
	               		<?php }?>
<?php }?>







<div class="header_div_inner">
<div class="container">
<h2 class="login_header_inner"><?php echo $this->get_label('user registration');?></h2>
</div>
</div>
		
<?php 
$registerseo=$this->get_seo_name('index/register');

if($registerseo !='')
	$registerurl=BASE.$registerseo;
else
	$registerurl=$this->make_url("index/register/".$this->get_variable('category'));
$form=$this->create_form();
$form->start("register",$registerurl,"post",$validate); 
?>
<div class="container">
<div class="box_style col-md-12 col-sm-12 col-xs-12" style="margin-top:20px; margin-bottom:20px;">

<div class="col-md-6 col-sm-6 col-xs-12">

<div class="small_head"><?php echo $this->get_label('account information');?></div>

<div class="register_div col-md-12 col-sm-12 col-xs-12" style="min-height: 416px; padding:30px;">
<div class="form-group" style="margin-bottom: 5px;">
<label ><?php echo $this->get_label('user name');?><span class="compulsory">*</span></label>

<input class="form-control" type="text" name="username" id="username" value="<?php echo $this->get_variable('username');?>" onblur="javascript:checkavailable();">
<span class="notification">[<?php echo $this->get_label('username validation');?>]</span>
</div>


<span id="imgload" style="display: none;"><img  src="images/load.gif" style="vertical-align: middle;" /></span>
<div id="check"></div>

<div class="form-group">
<label ><?php echo $this->get_label('pwd');?><span class="compulsory">*</span></label>
<input class="form-control" type="password" name="password" id="password">

<span class="notification">[<?php echo $this->get_label('password length limit',array('x'=>Configuration::get_instance()->read('password_length')));?>]</span>
</div>
<div class="form-group">
<label ><?php echo $this->get_label('confirm password');?><span class="compulsory">*</span></label>
<input class="form-control" type="password" name="cpassword" id="cpassword">
</div>

<?php if(Configuration::get_instance()->read('enable_seperate_registration') ==1){?>
<div class="form-group">
<label ><?php echo $this->get_label('account type');?></label>
<select class="form-control"  name="category" id="category">
	<option value="1" <?php if($this->get_variable('category')==1) { echo "selected"; } ?>><?php echo $this->get_label('advertiser');?></option>
	<option value="2" <?php if($this->get_variable('category')==2) { echo "selected"; } ?>><?php echo $this->get_label('publisher');?></option> 
	<option value="3" <?php if($this->get_variable('category')==3) { echo "selected"; } ?>><?php echo $this->get_label('advertiser publisher');?></option>
</select>
</div>
<?php }else{?>
<input type="hidden" name="category" id="category" value="3" />
<?php }?>

<div class="form-group">
<input style="margin-top:15px;" type="checkbox" name="terms" id="terms" value="1" <?php if($terms==1){ echo "checked";} ?> style="vertical-align: middle;" /><a href="#" onclick="loadTerms()"><?php echo $this->get_label('terms conditions');?></a><span class="compulsory">*</span>
</div>

</div></div>




<div class="col-md-6 col-sm-6 col-xs-12">

<div class="small_head"><?php echo $this->get_label('personal information');?></div>
<div class="register_div col-md-12 col-sm-12 col-xs-12" style="min-height: 416px; padding:30px;">
<div class="form-group">
<label ><?php echo $this->get_label('first name');?><span class="compulsory">*</span></label>
<input class="form-control" type="text" name="fname" id="fname" value="<?php echo $this->get_variable('fname');?>">
</div>


<div class="form-group">
<label ><?php echo $this->get_label('last name');?><span class="compulsory">*</span></label>
<input class="form-control" type="text" name="lname" id="lname" value="<?php echo $this->get_variable('lname');?>">
</div>


<div class="form-group">
<label ><?php echo $this->get_label('address');?><span class="compulsory">*</span></label>
<textarea class="form-control" name="address" id="address" rows="2" cols="30"><?php echo $this->get_variable('address');?></textarea>
</div>


<div class="form-group">
<label ><?php echo $this->get_label('phone no');?><span class="compulsory">*</span></label>
<input class="form-control" type="text" name="phone" id="phone" value="<?php echo $this->get_variable('phone');?>">
</div>

<div class="form-group">
<label ><?php echo $this->get_label('email');?><span class="compulsory">*</span></label>
<input class="form-control" type="text" name="email" id="email" value="<?php echo $this->get_variable('email');?>">
</div>

<div class="form-group">
<label ><?php echo $this->get_label('country');?></label>

<select class="form-control" name="country" id="country" >
<?php 
foreach($re as $key=>$r)
{
$code=$r['code'];
$country=$r['name'];
?>
	<option value="<?php echo $code;?>" <?php if($code==$this->get_variable('country')){ echo "selected"; }else if($code==$countrycurrent){echo "selected";}?>><?php echo $country;?></option>
<?php }?>
</select>
</div>

</div></div>







<div class="col-md-12 col-sm-12 col-xs-12">


<div class="small_head"><?php echo $this->get_label('business information');?></div>
<div class="register_div col-md-12 col-sm-12 col-xs-12" style="padding:20px 10px 5px 10px; margin-bottom:10px;">
<div class="col-md-6 col-sm-6 col-xs-12">
<div class="form-group">
<label><?php echo $this->get_label('your domain');?><span class="compulsory">*</span></label>
<input class="form-control" type="text" name="site" id="site" value="<?php echo $this->get_variable('site');?>">
</div></div>

<div class="col-md-6 col-sm-6 col-xs-12">
<div class="form-group">
<label><?php echo $this->get_label('company logo');?></label>
<input class="form-control" type="file" name="clogo" id="clogo" size="10"/>

<br/>
<span class="notification">[<?php echo $this->get_label('supported image format');?>]<br/>[<?php echo $this->get_label('logo size');?>]</span>
</div>
</div></div></div>



<?php if(Configuration::get_instance()->read('enable_captcha_verification')==1){?> 
<div class="col-md-12 col-sm-12 col-xs-12">
<script src='//www.google.com/recaptcha/api.js'></script>
<div class="small_head"><?php echo $this->get_label('image verification');?></div>

<div class="register_div col-md-12 col-sm-12 col-xs-12" style="padding:10px 20px; margin-bottom:5px;">
<div class="form-group">

<label><?php echo $this->get_label('image verification');?><span class="compulsory">*</span></label>
<div class="g-recaptcha" data-sitekey="<?php echo $recaptcha_public_key; ?>"></div>

</div></div></div>
<?php }?>


<div style="width:108px; margin:auto;"><input class="btn-primary" type="submit" name="user_register" value="<?php echo $this->get_label('register');?>"></div>


<?php if($external_theme_exists == 1){?>
<div style="width:108px; margin:auto;"><a class="reg_link_algn" href="<?php echo $this->make_url("index/login");?>"><?php echo $this->get_label('login');?></a></div>
<?php }?>

			</div>		
		

</div>
<?php $form->end(); 

if($external_theme_exists !=1)
$this->dispatch("layout/footer");
?>
<?php }?>


<?php if($external_theme_exists == 1){?>
<script type="text/javascript"> 
$('#register').prop("target", "_top");
</script>
<?php }?>