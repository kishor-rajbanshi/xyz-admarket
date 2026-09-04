

	<!-- Website Font style -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/jquery.min.js'></script>
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/jquery-ui-1.8.23.custom.min.js'></script>
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/bootstrap.min.js'></script>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo COMMON_DIR_PATH;?>css/bootstrap.min.css" />
<?php 
$logedin=intval($this->get_variable('log_success'));
if($logedin==1){
?> 
<script type="text/javascript">
top.location.href ='<?php echo $this->make_url("user/advertiser_home")?>';
//top.location.href ='<?php echo BASE;?>index.php?page=user/advertiser_home';
</script>
<?php } ?>
<style type="text/css">
/*
/* Created by Filipe Pina
 * Specific styles of signin, register, component
 */
/*
 * General styles
 */

/*body, html{
     height: 100%;
 	background-repeat: no-repeat;
 	background-color: #d3d3d3;
 	font-family: 'Oxygen', sans-serif;
}

*/
.main{
 	margin-top: 70px;
}

h1.title { 
	font-size: 30px;
	font-family: 'Passion One', cursive; 
	font-weight: 400; 
}

hr{
	width: 10%;
	color: #fff;
}

.form-group{
	margin-bottom: 15px;
}

label{
	margin-bottom: 15px;
}

input,
input::-webkit-input-placeholder {
    font-size: 11px;
    padding-top: 3px;
}

.main-login{
 	background-color: #fff;
    /* shadows and rounded borders */
    -moz-border-radius: 2px;
    -webkit-border-radius: 2px;
    border-radius: 2px;
    -moz-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
    -webkit-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
    box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);

}

.main-center{
 	margin-top: 30px;
 	margin: 0 auto;
 	max-width: 330px;
    padding: 40px 40px;

}

.login-button{
	margin-top: 5px;
	background-color:#ff5a00;
	border-color:#ff5a00;
}
.login-button:hover{    
	background-color:#ff5a00;	
}
.login-register{
	font-size: 11px;
	text-align: center;
}
.fgot_link_algn{      
	float:right;
	color:#ff5a00;
	font-size: 13px;
}
.reg_link_algn{    
	float:left;
	color:#ff5a00;
	font-size: 13px;
 }
.reg_link_algn:hover{     
	color:#ff5a00;	
	text-decoration: none;
}
.fgot_link_algn:hover{     
	color:#ff5a00;	
	text-decoration: none;
}
</style>		
	
<?php 
$validate12345=array(
		"username1"=>array("notNull"=>array($this->get_message("not null"))),
		"password1"=>array("notNull"=>array($this->get_message("not null")))
);

$registerseo=$this->get_seo_name('index/register');

if($registerseo !='')
	$registerurl=BASE.$registerseo;
else
	$registerurl=$this->make_url("index/register");

echo Configuration::get_instance()->read('google_analytics_code');
	
$pwdseo=$this->get_seo_name('index/forgot_password');

if($pwdseo !='')
	$pwdurl=BASE.$pwdseo;
else
	$pwdurl=$this->make_url("index/forgot_password");
?>
		<div class="container">
			<div class="row main">
				<div class="panel-heading">
	               <div class="panel-title text-center">
	               		<h1 class="title"><?php echo $this->get_label('login');?></h1>
	               		<hr />
	               	</div>
	            </div> 
				<div class="main-login main-center">
		<?php 
			$form=$this->create_form();
			$form->start("login",$this->make_url("index/login"),"post",$validate12345); 
		?>
						
						<div class="form-group">
							<label for="username" class="cols-sm-2 control-label"><?php echo $this->get_label('username');?></label>
							<div class="cols-sm-10">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-users fa" aria-hidden="true"></i></span>
									<input type="text" class="form-control" name="username1" id="username1"  placeholder="<?php echo $this->get_label('enter your username');?>"/>
								</div>
							</div>
						</div>

						<div class="form-group">
							<label for="password" class="cols-sm-2 control-label"><?php echo $this->get_label('password');?></label>
							<div class="cols-sm-10">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
									<input type="password" class="form-control" name="password1" id="password1"  placeholder="<?php echo $this->get_label('enter your password');?>"/>
								</div>
							</div>
						</div>

						

						<div class="form-group ">
							<input type="submit"  name="submit_login" id="submit_login" value="login" class="btn btn-primary btn-lg btn-block login-button"></button>
						</div>
						<div class="login-register">
				            <a class="reg_link_algn" href="<?php echo $registerurl;?>"><?php echo $this->get_label('register');?></a>
				            <a class="fgot_link_algn" href="<?php echo $pwdurl;?>"><?php echo $this->get_label('forgot password');?></a>
				         </div>
				        
		<?php $form->end(); ?>		
				</div>
			</div>
		</div>

	