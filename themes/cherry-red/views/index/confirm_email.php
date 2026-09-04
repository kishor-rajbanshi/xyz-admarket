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

<?php
$active_theme=$this->read_cookie_param('active_theme');
if($active_theme =="")
$active_theme=Configuration::get_instance()->read('active_theme');
?>

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
<span><?php echo $this->get_label('registration success page',array('x'=>BASE));?></span>
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