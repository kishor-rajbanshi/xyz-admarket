<?php 
$clpath=$this->get_variable("clpath");
if($clpath !=""){?>
	<html>
	<head>
	<title></title>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo DEFAULT_CHARSET;?>">
	
	</head>
	
	<body>
	<br><br><br><br>
	<form name="botcaptcha" action="<?php echo $this->make_url($clpath);?>" method="post">
	<?php $recaptcha_public_key=$this->get_variable("recaptcha_public_key");?>
	<table  width="30%" border="0"  cellpadding="0" cellspacing="0"  bgcolor="#CCCCCC">
	
	<?php if(Configuration::get_instance()->read('enable_captcha_verification')==1){?> 
	<tr><td align="center"><b><?php echo "Image Verification"; ?></b></td></tr>
	<tr><td>&nbsp;</td></tr>
	<tr align="center"><td>
	<script src='//www.google.com/recaptcha/api.js'></script>
	<div class="g-recaptcha" data-sitekey="<?php echo $recaptcha_public_key; ?>"></div>
	</td></tr>
	<?php }?>
	
	<tr><td>&nbsp;</td></tr>
	<tr><td align="center"><input name="captcha_submit" type="submit" value="<?php echo "Submit"; ?>"></td></tr>
	<tr><td height="10px"></td></tr>
	</table>
	</form>
	</body>
	</html>
<?php }?>