<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/jquery.min.js'></script>
<title><?php echo $this->get_label("reset password");?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="<?php echo BASE.ADMIN_DIR."/css/style.css";?>" type="text/css" />
<link rel="stylesheet" href="<?php echo COMMON_DIR_PATH;?>font-awesome/css/font-awesome.css" />

</head>

<body class="class-body">
<?php 

$validate=array(
		"email"=>array(
				"notNull"=>array($this->get_message("mandatory")),
				"isEmail"=>array($this->get_message("invalid email address"))

		)
);
$form=$this->create_form();
$form->start("reset_password",$this->make_url("index/reset_password"),"post",$validate);

$email_current=$this->get_variable("email");
?>
<div class="logindiv" id="logindiv" style="height:290px;">
<div class="loginbox">
<div class="title-login">
<div class="title-image"><img src="images/admarket-logo.png" /></div>
<h4><?php echo $this->get_label("reset password");?></h4></div>
<div class="login-input"><div class="icon_box"><i style="font-size: 22px; margin-left: 9px; margin-top: 8px;" class="fa fa-envelope"></i></div><input class="txt_box" type="text" name="email" id="email" value="<?php echo $email_current;?>" placeholder="<?php echo $this->get_label('email address');?>" /></div>
<div class="login-input"><input class="login_btn" type ="submit" value ="<?php echo $this->get_label('submit');?>" /></div>
<div class="forgot"><a style="margin-left: 133px;" href="<?php echo $this->make_url("index/index");?>"><?php echo $this->get_label('login');?></a></div>
</div>
</div>
<?php $form->end(); ?>
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