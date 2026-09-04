<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/jquery.min.js'></script>
<title><?php echo $this->get_label("index title");?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="<?php echo BASE.ADMIN_DIR."/css/style.css";?>" type="text/css" />
<link rel="stylesheet" href="<?php echo COMMON_DIR_PATH;?>font-awesome/css/font-awesome.css" />

</head>
<body  class="class-body">
<?php 
$username				= $this->get_variable("username");
$password				= $this->get_variable("password");
$adminid				= $this->get_variable("adminid");
$security_key			= $this->get_variable("security_key");
$admin_login_security 	= intval($this->get_variable("admin_login_security"));

$validate=array(
		"username"=>array(
				"notNull"=>array($this->get_message("mandatory"))
		),
		"password"=>array(
				"notNull"=>array($this->get_message("mandatory"))
		)
);

$validate1=array(
        "twofa_authentication"=>array(
            "notNull"=>array($this->get_message("mandatory"))
        )
);

if($admin_login_security == 0)
{
	$form=$this->create_form();
	$form->start("loginadmin",$this->make_url("index/index"),"post",$validate);
?>

<div class="logindiv" id="logindiv">
<div class="loginbox">
<div class="title-login">
<div class="title-image"><img src="images/admarket-logo.png" /></div>
<h4><?php echo $this->get_label("index title");?></h4></div>
<div class="login-input"><div class="icon_box"><i class="fa fa-user"></i></div><input type="text" class="txt_box" name="username" id="username" value="<?php echo $username;?>" placeholder="<?php echo $this->get_label("username");?>" /></div>
<div class="login-input"><div class="icon_box"><i class="fa fa-lock" style="margin-left:11px;"></i></div><input type="password" class="txt_box" name="password" id="password" value="<?php echo $password;?>" placeholder="<?php echo $this->get_label("password");?>" /></div>
<div class="login-input"><input class="login_btn" type="submit" name="login_button" value="<?php echo $this->get_label("login");?>" /></div>
<?php if(!DEMO_MODE){?>
<div class="forgot"><a href="<?php echo $this->make_url("index/reset_password");?>"><?php echo $this->get_label('forgot password');?></a></div>
<?php }?>
</div>
</div>
<?php 
	$form->end(); 
}
else
{
	$form=$this->create_form();
	$form->start("loginadmin1",$this->make_url("index/index"),"post",$validate1);
?>
<div class="logindiv" id="logindiv">
<div class="loginbox">
<div class="title-login">
<div class="title-image"><img src="images/admarket-logo.png" /></div>
<h4><?php echo $this->get_label("index title");?></h4></div>

<div class="login-input"><div class="icon_box"><i class="fa fa-lock" style="margin-left:11px;"></i></div>
<input type="text" class="txt_box" name="security_key" id="security_key" value="<?php echo $twofa_authentication;?>" placeholder="<?php echo $this->get_label("security key");?>" /></div>
<input type="hidden" id="username" name="username" value="<?php echo $username;?>">
<input type="hidden" id="password" name="password" value="<?php echo $password;?>">
<input type="hidden" id="adminid" name="adminid" value="<?php echo $adminid;?>">

<div class="login-input"><input class="login_btn" type="submit" name="login_button1" value="<?php echo $this->get_label("login");?>" /></div>
</div>
</div>
<?php $form->end();} ?>
</body>
<script language="javascript" type="text/javascript">
	var ie=document.all && !window.opera;
	var iebody=(document.compatMode=="CSS1Compat")? document.documentElement : document.body ;

	ht=(ie)? iebody.clientHeight: window.innerHeight ;
	wt=(ie)? iebody.clientWidth : window.innerWidth ;
	
	ofht=document.getElementById("logindiv").offsetHeight;
	ofwt=document.getElementById("logindiv").offsetWidth;

	ofht=parseFloat(ofht)-33;

	document.getElementById("logindiv").style.top=(ht/2)-parseFloat(ofht/2) +'px';
	document.getElementById("logindiv").style.left=(wt/2)-parseFloat(ofwt/2) +'px';
</script>
</html>