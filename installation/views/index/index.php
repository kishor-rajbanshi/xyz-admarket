<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo $this->get_title();?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/jquery.min.js'></script>


<link rel="stylesheet" href="<?php echo COMMON_DIR_PATH;?>font-awesome/css/font-awesome.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo COMMON_DIR_PATH;?>font-awesome/css/font-awesome.css" />

<style type="text/css">
.class-body
{
	background-image:url(images/bg.png);
 	background-position: center;
  	background-color:#23272a;
	
}
.logindiv
{
	position:relative;
	border: 1px solid #ffffff;
	background-color:#ffffff;
	width: 300px;
	height: 340px;
	padding: 20px;
	border-radius:4px;
	margin:auto;
}



.logindiv_1
{
	position:relative;
	border: 1px solid #ffffff;
	background-color:#ffffff;
	width: 400px;
	min-height: 450px;
	padding: 20px;
	border-radius:4px;
	margin:auto;
}

.logindiv_2
{
	position:relative;
	border: 1px solid #ffffff;
	background-color:#ffffff;
	width: 300px;
	height: 340px;
	padding: 20px;
	border-radius:4px;
	margin:auto;
}


.logindiv2
{
	position:relative;
	border: 1px solid #ffffff;
	background-color:#ffffff;
	max-width: 600px;
	min-height: 340px;
	padding: 20px;
	border-radius:4px;
	margin:auto;
}

.title-image
{
	height: 55px;
  width: 55px;
  border-radius: 40px;
  -moz-border-radius: 40px;
  -webkit-border-radius: 40px;
  border: 2px solid #FFFFFF;
  margin: auto;
  background-color: #FFFFFF;
	
}


.title-image img{margin-top:5px; width:40px;}


.loginbox
{
	width:100%;

}
.loginbox2
{
	width:600px;
	}
	
	.link_section{width:100%; float:left; margin-bottom:10px;}

.link_section ul{
	list-style-type: none;
    margin: 0px;
    padding: 0px;
}

.link_section ul li{
        width: 32.9%;
    display: block;
    text-align: center;
    padding-top: 5px;
    padding-bottom: 5px;
    margin-right: .5%;
}


.icon_box i{color: #ffffff;
  font-size: 26px;
  margin-left: 10px;
  margin-top: 7px; }
  
  
  .orange{background-color:#fe6f1e; float: left;
   
    color: #ffffff;
    }
  
  .light_green{background-color:#027d83; float: left;
   
    color: #ffffff;
   }
  
  .dark_green{background-color:#004f53; float: left;
    
    color: #ffffff;
    }
  

.title-login
{
	height: 110px;
  font-size: 16px;
  color: #888888;
  margin: 0px auto;
  background-color:#1F9DFE;
  padding-top: 20px;
  text-align: center;
  margin-bottom:20px;
  border-radius:2px;
  font-family: 'Raleway', sans-serif;

}


.title-login h4
{
	margin-top: 0px !important;
    font-family: Arial, Helvetica, sans-serif;
    font-weight: 300;
    text-align: center;
    padding-top: 18px;
    text-transform: uppercase;
    color: #FFFFFF;
    font-size: 18px;
    font-family: 'Raleway', sans-serif;
    padding-left: 5px;
    font-weight: 600;
}

.login-input
{
	
	margin: 0px auto;
	margin-bottom:10px;
	width:100%;
}

.login-input-error
{
color:#d50000;font-size: 12px; text-align:center; height: 10px;

}


.login-input input
{
     width:83%;
    font-family: 'Raleway', sans-serif;
    padding-left: 5px;
}


.login-input a
{
	margin-left: 100px;
	color: #888888 !important;
}

.login-input input[type="submit"]
{
	width: 237px;
	text-transform: uppercase;
	
}

.login_btn
{	padding: 10px;
  width: 100% !important;
  background-color:#00A388;
  border: none;
  color: #ffffff;
  font-size: 18px;
  margin-top: 10px; outline:0px; cursor:pointer;
  font-family: 'Raleway', sans-serif;}
  .login_btn_upgde1
{	
  padding: 10px;
  width: 100% !important;
  background-color:#00A388;
  border: none;
  color: #ffffff;
  font-size: 18px;
  outline:0px;
  cursor:pointer;
  float:left;
  font-size: 14px;
  font-family: 'Raleway', sans-serif;
  }
  .login_btn_upgde2
{	
  padding: 10px;
  width: 49% !important;
  background-color:#00A388;
  border: none;
  color: #ffffff;
  font-size: 18px;
  margin-top: 10px; outline:0px;
  cursor:pointer;
  float:left;
  font-size: 14px;
  margin-left:6px;
  }
  
  .login_btn:hover{background-color:#666666 !important; color:#ffffff;}
  
  .icon_box{width: 40px;
  height: 40px;
  float: left;
  background-color:#888888;}

.title-login a
{
	color: #000000 !important;
}

input[type="password"],
input[type="text"]
{
    border: 1px solid #CCCCCC;
    padding: 5px;   
}

.txt_box{height:30px; outline:0px;}


.logindiv input[type="password"]
 {
    padding: 4px;   
  }
	
.logindiv input[type="text"]
  {
   padding: 4px;   
   }
 .tick_rt_margin{     
 	margin-right:5px;
 }


#loading {width: 100%;height: 100%;top: 0px;left: 0px;position: fixed;display: block; z-index: 99}

#loading-image {position: absolute;top: 40%;left: 45%;z-index: 100}
.load_text{position: absolute;top: 44%;left: 45%;z-index: 100 }
.alert-info {
    color: #0c5460;
    background-color: #d1ecf1;
    border-color: #bee5eb;
}
.alert {
position: relative;
    padding: .75rem 1.25rem;
    margin-bottom: 1rem;
    border: 1px solid transparent;
    border-radius: .25rem;
    font-family: 'Raleway', sans-serif;
    font-size: 14px;
    line-height: 22px;
    color: #000;
}

.alert-warning {
    color: #856404;
    background-color: #fff3cd;
    border-color: #ffeeba;
}
.fund_icon 
{     
	padding-left:7px;
	padding-top:5px;
}
.icon_box2 {
    width: 40px;
    height: 36px;
    float: left;
    background-color: #888888;
}
.help_msg 
{
	text-align: justify;
}





@media only screen and (max-width: 600px)

{


.logindiv_1
{
	position:relative;
	border: 1px solid #ffffff;
	background-color:#ffffff;
	width: 300px;
	min-height: 550px;
	padding: 20px;
	border-radius:4px;
	margin:auto;
}


.logindiv_2
{
	position:relative;
	border: 1px solid #ffffff;
	background-color:#ffffff;
	min-width: 300px;
	min-height: 340px;
	padding: 20px;
	border-radius:4px;
	margin:auto;
}


}


@media only screen and (max-width: 480px)

{


.logindiv_1
{
	position:relative;
	border: 1px solid #ffffff;
	background-color:#ffffff;
	min-width: 200px;
	height: 550px;
	padding: 20px;
	border-radius:4px;
	margin:auto;
}


.logindiv_2
{
	position:relative;
	border: 1px solid #ffffff;
	background-color:#ffffff;
	min-width: 200px;
	height: 540px;
	padding: 20px;
	border-radius:4px;
	margin:auto;
}


}






</style>

<script type="text/javascript">
function set_jnotice(type,msg)
{
	$("#system_notice_area").css({"zIndex":"12001"});
	$("#system_notice_area").animate({
		opacity : 'show',
		height : 'show'
		}, 400);
	if(type==0)
	$("#system_notice_area").removeClass("system_notice_area_style1").addClass("system_notice_area_style0");
	else
	$("#system_notice_area").removeClass("system_notice_area_style0").addClass("system_notice_area_style1");
	
	$("#system_notice_area").html(msg+'&nbsp;&nbsp;&nbsp; <span id="system_notice_area_dismiss">X</span>');
	setTimeout(function(){$("#system_notice_area").hide(200);},4000)

	jQuery('#system_notice_area_dismiss').click(function() {
		jQuery('#system_notice_area').animate({
			opacity : 'hide',
			height : 'hide'
		}, 500);

	});
}

function show_loading(step)
{
if(step==1)	
{
var base = $("#base").val();	
var db_server = $("#db_server").val();	
var db_user = $("#db_user").val();	
var db_password = $("#db_password").val();	
var db_name = $("#db_name").val();	
var xyz_email = $("#xyz_email").val();	
var error=0;
var pattern = new RegExp(/^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i);
if(base=='' || db_server=='' || db_user=='' || db_password=='' || db_name=='' || xyz_email=='')
{
	error=1;
}
else if(pattern.test(xyz_email)==false)
{
	error=1;
}
else
{    
	$('#loading').show();
}	
}
else
{    
	$('#loading').show();
}

}

function show_budget_update_div()
{    
	//$("#logindiv0").css({"height":"342px","top":"138px"});
	//$('#logindiv_up2').show();
	$('#logindiv_up2').center();
	$('#logindiv_up1').hide();
	$('#budget_confirm').hide();
	$('#def_rates_input').show();
}

function check_valid_budget()
{   
	var cpc_enabled=$('#cpc_enabled').val();
	var cpa_enabled=$('#cpa_enabled').val();
	var affiliate_enabled=$('#affiliate_enabled').val();


	var error_flag=0;
	if(cpc_enabled == 1 || cpc_enabled == 0)
	{
		var cpc_budget=parseFloat($('#total_budget_cpc').val());
		if(cpc_budget < 0 || isNaN(cpc_budget) || cpc_budget==null)
			error_flag=1;	
	}
	if(cpa_enabled == 1 || cpa_enabled == 0)
	{      
		var cpa_budget=parseFloat($('#total_budget_cpa').val()); 
		if(cpa_budget < 0 || isNaN(cpa_budget) || cpa_budget ==null)
			error_flag=1;	
	}
	if(affiliate_enabled == 1 || affiliate_enabled == 0)
	{      
		var affiliate_budget=parseFloat($('#total_budget_affiliate').val());   
		if(affiliate_budget < 0 || isNaN(affiliate_budget) || affiliate_budget ==null)
			error_flag=1;	
	}

	if(error_flag==1)
	{  
		 var msg1="<?php echo $this->get_message('budget values should not be less than zero and can not be left blank !');?>";
		 set_jnotice(0,msg1);
		 return false;
	}
	else
	{ 
		$('#loading').show();     
		return true;
	}		
}
</script>
</head>
<body class="class-body">
<div id="loading" style="display:none;"><span class="load_text"></span>
<img id="loading-image" src="../images/loading.gif" alt="Loading..." />

</div>  
<?php
$step=$this->get_variable('step');
$script_uri=$this->get_variable('script_uri'); 


if($this->get_variable('success') != 1 && $step==0){
	$cpc_enabled=$this->get_addon_status('cpc_enabled');
	$cpa_enabled=$this->get_addon_status('cpa_enabled');
	$affiliate_enabled=$this->get_addon_status('affiliate-ads_enabled');
?>
	<div class="logindiv_1" id="logindiv_up1" style="top:110;display:none;">
	<div class="loginbox">
	<div class="title-login">
	<div class="title-image"><img src="images/admarket-logo.png"/></div>
	<h4><?php echo $this->get_label("db upgradation");?></h4></div>
<div id="budget_confirm">
<div class="alert alert-info help_msg" role="alert">
 <?php echo $this->get_label('to continue ad display in the new version,your ads require pre-assigned budgets.please specify the default budget to be configured for existing active ads');?>  
</div>

<div class="login-input"><input class="login_btn_upgde1" type="button"  name="update_sucess" id="update_sucess" onClick="show_budget_update_div()"  value="<?php echo $this->get_label("set budget");?>" /></div>
<div class="alert alert-info help_msg" style="float:left;margin-top:15px;" role="alert">
  <?php echo $this->get_label('if you specify zero budget or if advertisers does not have sufficient account balance for applying budget, all such ads will not be displayed.also advertiser who did not have sufficient account balance for  updating ad budget shall be notified in the next cron job.');?>
</div>
</div>
</div>
</div>
<div class="logindiv_2" id="logindiv_up2" style="height:500px;top:110;height:342px;top:138px;display:none;">
	<div class="loginbox">
    <div class="title-login">
	<div class="title-image"><img src="images/admarket-logo.png"/></div>
	<h4><?php echo $this->get_label("db upgradation");?></h4></div>
<div id="def_rates_input">
	<?php 
	$form=$this->create_form();
	$form->start("install_step0","","post",$validate_step1); 
	?>
	<?php if($cpc_enabled == 1 || $cpc_enabled == 0){ ?>
	<div class="login-input"><div title="<?php echo $this->get_label("cpc total ad budget");?>" class="icon_box2"><img class="fund_icon" src="images/cash-icon.png"></div><input class="txt_box"  name="total_budget_cpc" id="total_budget_cpc" type="number"  placeholder="<?php echo $this->get_label("cpc total ad budget");?>" value=""  /></div>
	<?php } if($cpa_enabled == 1 || $cpa_enabled == 0){ ?>
	<div class="login-input"><div title="<?php echo $this->get_label("cpa total ad budget");?>" class="icon_box2"><img class="fund_icon" src="images/cash-icon.png"></div><input class="txt_box"  name="total_budget_cpa" id="total_budget_cpa" type="number"  placeholder="<?php echo $this->get_label("cpa total ad budget");?>" value=""  /></div>
	<?php }
	if($affiliate_enabled == 1 || $affiliate_enabled == 0){ ?>
	<div class="login-input"><div title="<?php echo $this->get_label("affiliate total ad budget");?>" class="icon_box2"><img class="fund_icon" src="images/cash-icon.png"></div><input class="txt_box"  name="total_budget_affiliate" id="total_budget_affiliate" type="number"  placeholder="<?php echo $this->get_label("affiliate total ad budget");?>" value=""  /></div>
	<?php } ?>
	<div class="login-input"><input class="login_btn" type="submit"  name="upgrade_budget" id="upgrade_budget" onClick=" return check_valid_budget()"  value="<?php echo $this->get_label("upgrade");?>" /></div>
	<input type="hidden" name="step" id="step" value="0">
	<input type="hidden" name="cpc_enabled" id="cpc_enabled" value="<?php echo $cpc_enabled;?>">
	<input type="hidden" name="cpa_enabled" id="cpa_enabled" value="<?php echo $cpa_enabled;?>">
	<input type="hidden" name="affiliate_enabled" id="affiliate_enabled" value="<?php echo $affiliate_enabled;?>">
	<?php $form->end(); ?>
</div>

	</div>
	</div>
<?php	
}
else if($this->get_variable('success') != 1 && $step==1){
	$validate_step1=array(
			"base"=>array(
					"notNull"=>array($this->get_message("please enter the base url"))
			),
			"db_server"=>array(
					"notNull"=>array($this->get_message("please enter the database server"))
			),
			"db_user"=>array(
					"notNull"=>array($this->get_message("please enter the database username"))
			),
			"db_password"=>array(
					"notNull"=>array($this->get_message("please enter the database password"))
			),
			"db_name"=>array(
					"notNull"=>array($this->get_message("please enter the database name"))
			),
			"xyz_email"=>array(
					"notNull"=>array($this->get_message("please enter xyz member area email")),
					"isEmail"=>array($this->get_message("invalid email"))
			
			),
	);
	
?>

<div class="logindiv" id="logindiv" style="height:575px;display:none;">
<div class="loginbox">
<div class="title-login">
<div class="title-image"><img src="images/admarket-logo.png"/></div>
<h4><?php echo $this->get_label("installation");?></h4></div>

<div class="link_section">
<ul>
<li class="orange"><?php echo $this->get_label('step1');?></li>
<li class="dark_green"><?php echo $this->get_label('step2');?></li>
<li class="dark_green" style="margin-right:0px;"><?php echo $this->get_label('step3');?></li>
</ul>
</div>
<?php 
$form=$this->create_form();
$form->start("install_step1","","post",$validate_step1); 
?>
<div class="login-input login-input-error">
<?php if($this->get_variable('error') != ""){ echo $this->get_variable('error'); }?></div>

<div class="login-input"><div title="<?php echo $this->get_label("base url");?>" class="icon_box"><i style="font-size: 22px; margin-left: 11px; margin-top: 8px;" class="fa fa-home"></i></div><input class="txt_box"  name="base" id="base" type="text"  placeholder="<?php echo $this->get_label("base url");?> Eg:http://xyzscripts.com/" value="<?php echo $script_uri;?>" /></div>
<div class="login-input"><div class="icon_box"><i style="font-size: 18px; margin-left: 11px; margin-top: 10px;" class="fa fa-server"></i></div><input class="txt_box"  name="db_server" id="db_server" type="text"  placeholder="<?php echo $this->get_label("db server"); ?>" /></div>
<div class="login-input"><div class="icon_box"><i style="font-size: 18px; margin-left: 11px; margin-top: 10px;" class="fa fa-user"></i></div><input class="txt_box"  name="db_user" id="db_user" type="text"  placeholder="<?php echo $this->get_label("db user"); ?>" /></div>
<div class="login-input"><div class="icon_box"><i style="font-size: 18px; margin-left: 11px; margin-top: 10px;" class="fa fa-key"></i></div><input class="txt_box"  name="db_password" id="db_password" type="password"  placeholder="<?php echo $this->get_label("db password"); ?>" /></div>
<div class="login-input"><div class="icon_box"><i style="font-size: 18px; margin-left: 11px; margin-top: 10px;" class="fa fa-database"></i></div><input class="txt_box"  name="db_name" id="db_name" type="text"  placeholder="<?php echo $this->get_label("database name"); ?>" /></div>
<div class="login-input"><div title="<?php echo $this->get_label("table prefix");?>" class="icon_box"><i style="font-size: 18px; margin-left: 11px; margin-top: 10px;" class="fa fa-table"></i></div><input class="txt_box"  name="tbl_prefix" id="tbl_prefix" type="text"  placeholder="<?php echo $this->get_label("table prefix"); ?>" value="xyz_adm_"/></div>

<div class="login-input"><div class="icon_box"><i style="font-size: 18px; margin-left: 11px; margin-top: 10px;" class="fa fa-envelope"></i></div><input class="txt_box"  name="xyz_email" id="xyz_email" type="text"  placeholder="<?php echo $this->get_label("xyzscripts member email"); ?>" /></div>

<div class="login-input"><input class="login_btn" type="submit"  name="config_db" id="config_db" onClick="show_loading(1);"  value="<?php echo $this->get_label("next");?>" /></div>
<input type="hidden" name="step" id="step" value="1">
<?php $form->end(); ?>
</div>
</div>
<?php }
else if($this->get_variable('success') != 1 && $step==2){ 
	$basic_str_cnt=$this->get_variable('basic_str_cnt');
?>	


<?php
$form=$this->create_form();
$form->start("install_step2","","post");
?>
<div class="logindiv2" id="logindiv2" style="min-height:520px;display:none;">
<div class="loginbox2">
<div class="title-login">
<div class="title-image"><img src="images/admarket-logo.png"/></div>
<h4><?php echo $this->get_label("installation");?></h4></div>
<div class="link_section">
<ul>
<li class="light_green"><i class="fa fa-check tick_rt_margin" aria-hidden="true"></i><?php echo $this->get_label("step1");?></li>
<li class="orange"><?php echo $this->get_label("step2");?></li>
<li class="dark_green" style="margin-right:0px;"><?php echo $this->get_label("step3");?></li>
</ul>
</div>

<div>
<h5><?php echo $this->get_label("please create a file system.php in ");?> '<?php echo $script_uri."config/"?>' <?php echo $this->get_label('folder and copy & paste below code in that file');?> </h5>
  <textarea class="form-control" name="basic_str" id="basic_str" style="height:220px;width:100%;" readonly><?php echo $basic_str_cnt;?></textarea>
<i class="fa fa-clipboard" aria-hidden="true" id="copy1" title="<?php echo $this->get_label('copy');?>" style="float:right;margin-top:3px;"></i>
</div>

<input type="hidden" name="basic_cnt_str" id="basic_cnt_str" value="<?php echo $this->mybase64_encode($basic_str_cnt);?>">
<input type="hidden" name="script_uri" value="<?php echo $script_uri;?>">
<div class="login-input"><input class="login_btn" type="submit" onClick="show_loading(2);" name="show_basic_db" id="show_basic_db" value="<?php echo $this->get_label("next");?>" /></div>


</div>
</div>
<?php $form->end(); 
 }
else if($this->get_variable('success') != 1 && $step==3){
	$validate_step3=array(
			"username"=>array(
					"notNull"=>array($this->get_message("please enter the user name"))
			),
			"password"=>array(
					"notNull"=>array($this->get_message("please enter the password"))
			),
			"email"=>array(
					"notNull"=>array($this->get_message("please enter the email"))
			)
	);
?>
<div class="logindiv" id="logindiv3" style="height:385px;display:none;">
<div class="loginbox">
<div class="title-login">
<div class="title-image"><img src="images/admarket-logo.png"/></div>
<h4><?php echo $this->get_label("admin registeration");?></h4></div>
<div class="link_section">
<ul>
<li class="light_green"><i class="fa fa-check tick_rt_margin" aria-hidden="true"></i><?php echo $this->get_label('step1');?></li>
<li class="light_green"><i class="fa fa-check tick_rt_margin" aria-hidden="true"></i><?php echo $this->get_label('step2');?></li>
<li class="orange" style="margin-right:0px;"><?php echo $this->get_label('step3');?></li>
</ul>
</div>
<?php 
$form=$this->create_form();
$form->start("install_step3","","post",$validate_step3); 
?>


<div class="login-input login-input-error">
<?php if($this->get_variable('error') != ""){?><?php echo $this->get_variable('error');?><?php }?></div>

<div class="login-input"><div class="icon_box"><i style="font-size: 22px; margin-left: 11px; margin-top: 8px;" class="fa fa-user"></i></div><input class="txt_box"  name="username" id="username" type="text" value="<?php echo $this->get_variable('username');?>" placeholder="<?php echo $this->get_label("username"); ?>" /></div>
<div class="login-input"><div class="icon_box"><i style="font-size: 22px; margin-left: 12px; margin-top: 8px;" class="fa fa-lock"></i></div><input class="txt_box"  name="password" id="password" type="password" value="" placeholder="<?php echo $this->get_label("password"); ?>" /></div>
<div class="login-input"><div class="icon_box"><i style="font-size: 18px; margin-left: 11px; margin-top: 10px;" class="fa fa-envelope"></i></div><input class="txt_box"  name="email" id="email" type="text" value="<?php echo $this->get_variable('email');?>" placeholder="<?php echo $this->get_label("email"); ?>" /></div>
<div class="login-input"><input class="login_btn" type="submit"  name="admin_register" id="admin_register" value="<?php echo $this->get_label("submit");?>" /></div>


<?php $form->end(); ?>
</div>
</div>	
<?php
 }
else if($this->get_variable('success') == 1 && $step==4)
{
?>
<div class="logindiv" id="logindiv4" style="height:185px;display:none;">
<div class="loginbox">
<div class="title-login" style="height:75px;">
<div class="title-image"><img src="images/admarket-logo.png" /></div></div>

<div class="link_section">
<ul>
<li class="light_green"><i class="fa fa-check tick_rt_margin" aria-hidden="true"></i><?php echo $this->get_label('step1');?></li>
<li class="light_green"><i class="fa fa-check tick_rt_margin" aria-hidden="true"></i><?php echo $this->get_label('step2');?></li>
<li class="light_green" style="margin-right:0px;"><i class="fa fa-check tick_rt_margin" aria-hidden="true"></i><?php echo $this->get_label('step3');?></li>
</ul>
</div>
	<p style="text-align:center; padding-top:20px; color:#666666;"><?php 
		echo $this->get_label("installation completed");    ?>
		&nbsp;<a style='color:#00A388;' href="<?php echo $this->make_base_url('index/index',ADMIN_DIR);?>"><?php echo $this->get_label('go');?></a>
	</p>
	</div>	
</div>

<?php 
}
else
{
?>
<div class="logindiv" id="logindiv5" style="height:185px;display:none;">
<div class="loginbox">
<div class="title-login" style="height:75px;">
<div class="title-image"><img src="images/admarket-logo.png" /></div></div>

<div class="link_section">
<ul>
<li class="light_green"><i class="fa fa-check tick_rt_margin" aria-hidden="true"></i><?php echo $this->get_label('step1');?></li>
<li class="light_green"><i class="fa fa-check tick_rt_margin" aria-hidden="true"></i><?php echo $this->get_label('step2');?></li>
<li class="light_green" style="margin-right:0px;"><i class="fa fa-check tick_rt_margin" aria-hidden="true"></i><?php echo $this->get_label('step3');?></li>
</ul>
</div>
	<p style="text-align:center; padding-top:20px; color:#666666;"><?php 
		echo $this->get_label("installation completed");    ?>
		&nbsp;<a style='color:#00A388;' href="<?php echo $this->make_base_url('index/index',ADMIN_DIR);?>"><?php echo $this->get_label('go');?></a>
	</p>
	</div>	
</div>

<?php 
}
?>
</body>
<script language="javascript" type="text/javascript">

$.fn.center = function () {
	   //this.css("position","absolute");
	   this.css("top", ( $(window).height() - this.height() ) / 2  + "px");
	  // this.css("left", ( $(window).width() - this.width() ) / 2 + "px");
	   this.css("display","block");
	   return this;
	}
$('#logindiv_up1').center();
//$('#logindiv_up2').center();
$('#logindiv').center();
$('#logindiv2').center();
$('#logindiv3').center();
$('#logindiv4').center();
$('#logindiv5').center();
$("#copy1").click(function(){
    $("#basic_str").select();
    document.execCommand('copy');
});

$("#copy2").click(function(){
    $("#db_str").select();
    document.execCommand('copy');
});

</script>
</html>