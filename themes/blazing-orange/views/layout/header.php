<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height" />
<html>
<head>

  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="//oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

 
<?php
$active_theme=$this->read_cookie_param('active_theme');
if($active_theme=="")
	$active_theme=Configuration::get_instance()->read('active_theme');
?>


<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/jquery.min.js'></script>
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/jquery-ui-1.8.23.custom.min.js'></script>
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/bootstrap.min.js'></script>
<script type="text/javascript" src="<?php echo COMMON_DIR_PATH;?>js/jquery.simplyscroll.js"></script>
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/common.js'></script>

<?php 
$page=$this->get_variable("page");
if($page==16 || $page==17 || $page==18 || $page==19)
{
?>
    <!-- Javascript Files
    ================================================== -->
    
    <script src="<?php echo THEME_DIR_PATH.$active_theme;?>/js/jpreLoader.js"></script> 

    <script src="<?php echo THEME_DIR_PATH.$active_theme;?>/js/jquery.isotope.min.js"></script>
  	<script src="<?php echo THEME_DIR_PATH.$active_theme;?>/js/jquery.prettyPhoto.js"></script>
   	<script src="<?php echo THEME_DIR_PATH.$active_theme;?>/js/easing.js"></script>
    <script src="<?php echo THEME_DIR_PATH.$active_theme;?>/js/jquery.ui.totop.js"></script>
    <script src="<?php echo THEME_DIR_PATH.$active_theme;?>/js/jquery.flexslider-min.js"></script>
    <script src="<?php echo THEME_DIR_PATH.$active_theme;?>/js/jquery.scrollto.js"></script>
    <script src="<?php echo THEME_DIR_PATH.$active_theme;?>/js/owl.carousel.js"></script>
    <script src="<?php echo THEME_DIR_PATH.$active_theme;?>/js/classie.js"></script>
    <script src="<?php echo THEME_DIR_PATH.$active_theme;?>/js/designesia.js"></script>
    

    <!-- SLIDER REVOLUTION SCRIPTS  -->
    <script type="text/javascript" src="<?php echo THEME_DIR_PATH.$active_theme;?>/js/jquery.themepunch.plugins.min.js"></script>
    <script type="text/javascript" src="<?php echo THEME_DIR_PATH.$active_theme;?>/js/jquery.themepunch.revolution.min.js"></script>
<?php } ?>
  
<link rel="icon" href="<?php echo BASE;?>favicon.ico">
<link rel="stylesheet" type="text/css" media="all" href="<?php echo COMMON_DIR_PATH;?>css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo COMMON_DIR_PATH;?>font-awesome/css/font-awesome.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo BASE;?>css/public-style.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo THEME_DIR_PATH.$active_theme;?>/css/style.css" />

<?php
$direction=$this->get_variable('direction');
if($direction ==1) {?>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo COMMON_DIR_PATH;?>css/bootstrap-rtl.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo BASE;?>css/public-rtl-style.css" />
<?php }?>



    
    
     <?php
    if($page==16 || $page==17 || $page==18 || $page==19)                   // checking to include themecss files
    {
    ?>
   
    <link rel="stylesheet" href="<?php echo THEME_DIR_PATH.$active_theme;?>/css/animate.css" type="text/css">
    <link rel="stylesheet" href="<?php echo THEME_DIR_PATH.$active_theme;?>/css/flexslider.css" type="text/css">
    <link rel="stylesheet" href="<?php echo THEME_DIR_PATH.$active_theme;?>/css/plugin.css" type="text/css">
    <link rel="stylesheet" href="<?php echo THEME_DIR_PATH.$active_theme;?>/css/owl.carousel.css" type="text/css">
    <link rel="stylesheet" href="<?php echo THEME_DIR_PATH.$active_theme;?>/css/owl.theme.css" type="text/css">

   
    <!-- revolution slider -->
    <link rel="stylesheet" href="<?php echo THEME_DIR_PATH.$active_theme;?>/css/settings.css" type="text/css">
    <link rel="stylesheet" href="<?php echo THEME_DIR_PATH.$active_theme;?>/css/rev-settings.css" type="text/css">
 <?php } ?>
 
    <!-- load fonts -->
    
    
    
    
    
   <!--  PleaSE CHECK WHAT NEEDED -->  
   <link href="//fonts.googleapis.com/css?family=Oswald|Raleway" rel="stylesheet">
   <link href="//fonts.googleapis.com/css?family=Anton" rel="stylesheet">
   
   <!--  PleaSE CHECK WHAT NEEDED -->  
   
   
   
    

<?php
$localeid=$this->get_variable('localeid');
$logedin=intval($this->get_variable('logedin'));
$customres=$this->get_result('customres');
$sub_page=$this->get_variable("sub_page");
$cpanel=$this->get_variable("cpanel");
$logourl=$this->get_variable("logo_url");

?>


<script type="text/javascript">

function change_theme(theme)
{     
	document.cookie = "active_theme="+theme+"; path=<?php echo $this->get_base_path();?>";
	location.reload();
}
function LoadLocaleFile(language)
{  
	if(language !="")
	{
		languageurl='<?php echo $this->make_base_url("layout/change_language/");?>';
		$.ajax(
		{
			type: "GET",
			url: languageurl+language,
			success: function(msg)
			{
            
				<?php if($logedin ==1){?>
			
				if($('#top-ad-iframe').length >0)
				$('#top-ad-iframe').attr('src',$('#top-ad-iframe').attr('src'));

				if($('#top-ad-iframe1').length >0)
				$('#top-ad-iframe1').attr('src',$('#top-ad-iframe1').attr('src'));

				if($('#top-adcode-iframe').length >0)
				$('#top-adcode-iframe').attr('src',$('#top-adcode-iframe').attr('src'));

				if($('#ad-keyword-iframe').length >0)
				$('#ad-keyword-iframe').attr('src',$('#ad-keyword-iframe').attr('src'));

				if($('#ad-location-iframe').length >0)
				$('#ad-location-iframe').attr('src',$('#ad-location-iframe').attr('src'));
				
				if($('#ad-pricing-iframe').length >0)
				$('#ad-pricing-iframe').attr('src',$('#ad-pricing-iframe').attr('src'));
				
				if($('#ad-position-iframe').length >0)
				$('#ad-position-iframe').attr('src',$('#ad-position-iframe').attr('src'));

				if($('#ad-category-iframe').length >0)
				$('#ad-category-iframe').attr('src',$('#ad-category-iframe').attr('src'));

				if($('#ad-device-iframe').length >0)
				$('#ad-device-iframe').attr('src',$('#ad-device-iframe').attr('src'));

				if($('#ad-time-iframe').length >0)
				$('#ad-time-iframe').attr('src',$('#ad-time-iframe').attr('src'));


				window.setTimeout(function(){window.location.reload();},20);

				<?php }else{?>
				window.location.reload(); 
				<?php }?>
			}
		});
	} 
}





function LoadNotifications()
{
	admvaluestring=$('#admvaluestring').val();

	if(admvaluestring !="")
	{
		var currentcookie=Get_Cookie('adm_content');
	
		if(currentcookie != null && currentcookie !="")
		currentcookie=currentcookie+'-'+admvaluestring;
		else
		currentcookie=admvaluestring;	
	
		Set_Cookie('adm_content',currentcookie,20000,"/") ;
	
		$('#admvaluestring').val('');
	}

	window.location.href="<?php echo $this->make_url('index/notifications');?>";
}

var today = new Date();
today.setTime(today.getTime());
today.setHours(0);
today.setMinutes(0);
today.setSeconds(0);

function Get_Cookie( name )
{
	var start = document.cookie.indexOf( name + "=" );
	var len = start + name.length + 1;
	if ( ( !start ) && ( name != document.cookie.substring( 0, name.length ) ) )
	{
		return null;
	}

	if ( start == -1 ) return null;
	var end = document.cookie.indexOf( ";", len );
	if ( end == -1 ) end = document.cookie.length;
	return unescape( document.cookie.substring( len, end ) );
}

function Set_Cookie( name, value, expires, path, domain, secure ) 
{	
	if(expires)
	expires = expires * 1000 * 60 * 60 ;

	var expires_date = new Date( today.getTime() + (expires) );
	document.cookie = name + "=" +escape( value ) + ";expires=" + expires_date.toUTCString()  + ( ( path ) ? ";path=" + path : "" ) + ( ( domain ) ? ";domain=" + domain : "" ) + ( ( secure ) ? ";secure" : "" );
}




</script>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">


<?php if($page ==28){?>
<meta name="keywords" content="admarket,xyzscripts" />
<?php }else{?>
<meta name="keywords" content="<?php echo $this->get_variable("mkey");?>" />
<?php }?>


<meta name="description" content="<?php echo str_ireplace("{x}",Configuration::get_instance()->read('admarket_name'),$this->get_variable("mdesc"));?>" />

<title>
<?php 

if($this->get_title('')=="")
echo Configuration::get_instance()->read('admarket_name');
else
echo $this->get_title().' - '.Configuration::get_instance()->read('admarket_name');
?>
</title>


<?php echo Configuration::get_instance()->read('google_analytics_code');?>
</head>


<?php 
$validate12345=array(
		"username1"=>array("notNull"=>array($this->get_message("not null"))),
		"password1"=>array("notNull"=>array($this->get_message("not null")))
);


$googleplus_url=Configuration::get_instance()->read('googleplus_url');
$twitter_url=Configuration::get_instance()->read('twitter_url');
$facebook_url=Configuration::get_instance()->read('facebook_url');
$linkedin_url=Configuration::get_instance()->read('linkedin_url');
$youtube_url=Configuration::get_instance()->read('youtube_url');

$menu_array=$GLOBALS['menu'];
if($logedin ==1)
	$menu_array = $menu_array['login'];
else
	$menu_array = $menu_array['logout'];

if($logedin ==1)
{
	$res=$this->get_result('res');
	$value=$res[0];
	$adv_status=$value['adv_status'];
	$pub_status=$value['pub_status'];
	
	
	
	$adv_bonus_balance=$value['adv_bonus_balance'];
	$adv_balance=$value['adv_account_balance'];
	$pub_balance=$value['pub_account_balance'];
	
	$pfsum=$this->get_variable('pfsum');
	$afsum=$this->get_variable('afsum');
	
	$pub_balance=$pub_balance-$pfsum;
	$adv_balance=$adv_balance+$afsum;
	
	if($pub_balance < 0)
	$pub_balance=0;
	
		

	$referral_enabled=$this->get_variable('referral_enabled');
	
	$referral_balance=0;
	
	
	if($referral_enabled ==1 && (($adv_status ==1 && Configuration::get_instance()->read('advertiser_referral_enabled') ==1) || ($pub_status ==1 && Configuration::get_instance()->read('publisher_referral_enabled') ==1)))
	$referral_enabled=1;
	else
	$referral_enabled=0;
	
	
	
	if($referral_enabled ==1)
	{
		$referral_balance=$value['referral_balance'];
	
		if($referral_balance < 0)
	    $referral_balance=0;
	}
	
	$uid=$this->get_variable("uid");	
		
		
	if($cpanel != "b" && $cpanel != "s" && $cpanel !="f" && $cpanel !="r")
	{
		if($this->read_cookie_param(COOKIE_ADMARKETTYPE)==1)
		$cpanel="a";
		if($this->read_cookie_param(COOKIE_ADMARKETTYPE)==2)
		$cpanel="p";
	}

}




		$category_enabled=$this->get_addon_status('category-targeting_enabled');
		$sponsored_enabled=$this->get_addon_status('sponsored_enabled');
		$affiliate_enabled=$this->get_addon_status('affiliate-ads_enabled');
		
?>

<body>

    <div id="wrapper">
        
    
        <!-- header begin -->
        <header <?php if($page==19){ ?> class="" <?php } ?>>
        
   
        
            
             <nav class="navbar navbar-default main_menu_bg" role="navigation">
             
             
             
             
             
  <div class="container">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 <?php if(DEMO_MODE){?>margin-container<?php }?>" style="margin-bottom:0px;">
      
      
      <ul class="social-share">
                                                              
<?php if($googleplus_url !=""){?>
<li><a target="_blank" href="//plus.google.com/<?php echo $googleplus_url;?>/"><i class="fa fa-google-plus"></i></a></li>
<?php }else{?>
<li><a target="_blank" href="#"><i class="fa fa-google-plus"></i></a></li>
<?php }?> 
  
  

<?php if($twitter_url !=""){?>
<li><a target="_blank" href="//twitter.com/<?php echo $twitter_url;?>"><i class="fa fa-twitter"></i></a></li>
<?php }else {?>
<li><a target="_blank" href="#"><i class="fa fa-twitter"></i></a></li>
<?php }?>



<?php if($facebook_url !=""){?>
<li><a target="_blank" href="//facebook.com/<?php echo $facebook_url;?>"><i class="fa fa-facebook"></i></a></li>
<?php }else {?>
<li><a target="_blank" href="#"><i class="fa fa-facebook"></i></a></li>
<?php }?>





<?php if($linkedin_url !=""){?>
<li><a target="_blank" href="//linkedin.com/<?php echo $linkedin_url;?>"><i class="fa fa-linkedin"></i></a></li>
<?php }else{?>
<li><a href="#"><i class="fa fa-linkedin"></i></a></li>
<?php }?>


<?php if($youtube_url !=""){?>
<li><a target="_blank" href="//youtube.com/<?php echo $youtube_url;?>"><i class="fa fa-youtube"></i></a></li>
<?php }else{?>
<li><a target="_blank" href="#"><i class="fa fa-youtube"></i></a></li>
<?php }?>

                
                 </ul>
      
      
      
      
      
      
      
      
   

         
          
          
 <ul class="nav navbar-nav navbar-right login_section">	   
 
 
<?php if(DEMO_MODE){

$theme_array=$GLOBALS["xyz_admarket_themes"];

if(count($theme_array) >1){
?>
<li class="language_select">
			<a style="background-color: #ff5a00 !important;" href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $this->get_label('themes');?><span class="caret"></span></a>
		    <ul class="dropdown-menu language_drop_menu" style="min-width:110px !important;">
		    <?php foreach($theme_array as $key => $value){ ?>
		    <li><a href="#" onclick="change_theme('<?php echo $value["folder_name"];?>');" <?php if($active_theme == $value["folder_name"]){?> style="background-color:#73777a !important;color:#ffffff;"<?php }?>><?php echo $value['name']?></a></li>
		    <?php }?>
		    </ul>
</li> 
<?php }}?> 

 

 <li>
  <!-- notifications -->              
        <?php 
        $notificationcount=$this->get_variable('notificationcount');
        $notificationcount1=$this->get_variable('notificationcount1');
        $valuestring=$this->get_variable('valuestring');
        ?>
        
        <?php if($notificationcount1 >0){?>
        <div class="login-note">
        <i class="fa fa-bell-o notification-bell" onClick="LoadNotifications();"></i>
        
        <input type="hidden" name="admvaluestring" id="admvaluestring" value="<?php echo $valuestring;?>" />
        
        <?php if($notificationcount >0){?>
        <i class="fa fa-circle notification-round" onClick="LoadNotifications();"></i>
        <span class="notification-count " style="<?php if($direction ==1){ if(strlen($notificationcount) >1){?>right: 30px;<?php }else{?>right: 33px;<?php }}else{ if(strlen($notificationcount) >1){?>left: 30px;<?php }else{?>left: 33px;<?php }}?>" onClick="LoadNotifications();"><?php echo $notificationcount;?></span>
		<?php }else{?>
		<i class="fa fa-circle notification-round-readed" onClick="LoadNotifications();"></i>
		<span class="notification-count" style="<?php if($direction ==1){ if(strlen($notificationcount1) >1){?>right: 30px;<?php }else{?>right: 33px;<?php }}else{ if(strlen($notificationcount1) >1){?>left: 30px;<?php }else{?>left: 33px;<?php }}?>" onClick="LoadNotifications();"><?php echo $notificationcount1;?></span>
		<?php }?>
		
		</div>
        <?php }?>     
 
 </li>
 
 
 
            <li class="language_select">
                     
 			<?php if(Configuration::get_instance()->read('language_enabled') ==1){
			$localname='';
			if(isset($_COOKIE['my_locale']))
			$localname=$_COOKIE['my_locale'];
			
			if($localname =='')
			$localname=DEFAULT_LOCALE;
			
			$languagestring=$this->get_variable('languagestring');
			      
			echo $languagestring;
			
        	}?>
				                          			
           </li>
           
           
           <!-- login name -->
           
<?php if($logedin ==1){?>           
<li class="user-details">
                     
 <span><?php echo $this->get_variable("uname");?></span>
 
 
 </li>
 <li> <a class="logout_section_btn" href="<?php echo $this->make_base_url("user/logout");?>"><i class="fa fa-power-off" aria-hidden="true" title="<?php echo $this->get_label('sign out');?>"></i></a>
 </li>
<?php }else{

	
	$registerseo=$this->get_seo_name('index/register');
	
	if($registerseo !='')
	$registerurl=BASE.$registerseo;
	else
	$registerurl=$this->make_url("index/register");
	
	
	$pwdseo=$this->get_seo_name('index/forgot_password');
	
	if($pwdseo !='')
	$pwdurl=BASE.$pwdseo;
	else
	$pwdurl=$this->make_url("index/forgot_password");
	
	
	?>	
			
 <li class="dropdown top_login_sel">
          <a class="login_section_btn" href="#" class="dropdown-toggle" data-toggle="dropdown"><b><?php echo $this->get_label('login');?></b> <span class="caret"></span></a>
			<ul id="login-dp" class="dropdown-menu login_box" >
				<li>
					 <div class="row">
							<div class="col-md-12">
								
<?php 
$form=$this->create_form();
$form->start("loginheader","","post",$validate12345); 
?>										<div class="form-group">
											 <label class="sr-only" ><?php echo $this->get_label('user name');?></label>
											 <input type="text" class="form-control" id="username1" name="username1" value="<?php echo $this->get_variable('username1');?>" placeholder="<?php echo $this->get_label('user name');?>" required>
										</div>
										<div class="form-group">
											 <label class="sr-only"><?php echo $this->get_label('password');?></label>
											 <input type="password" class="form-control" id="password1" name="password1" value="<?php echo $this->get_variable('password1');?>" placeholder="<?php echo $this->get_label('password');?>" required>
                                             <div class="help-block text-right"><a href="<?php echo $this->make_base_url("index/forgot_password");?>"><?php echo $this->get_label('forgot password');?></a></div>
										</div>
										<div class="form-group">
											 <input type="submit" name="submit_login" id="submit_login" class="btn btn-primary btn-block" value="<?php echo $this->get_label('signin');?>">
										</div>
										
<?php $form->end(); ?>		
							</div>
							<div class="bottom text-center">
								<?php echo $this->get_label('new user ?');?> <a href="<?php echo $this->make_base_url("index/register");?>"><b><?php echo $this->get_label('register');?></b></a>
							</div>
					 </div>
				</li>
				</ul>
				</li>
	<?php }?>  
</ul>             
           </div></div> 
           </nav>            
                      
             <nav class="navbar navbar-default main_menu_bg2" role="navigation">
  <div class="container">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button style="margin-top:20px;" type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only"><?php echo $this->get_label('toggle navigation');?></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a style="white-space: nowrap;" class="navbar-brand logo_head" href="<?php echo $logourl;?>"><?php if(Configuration::get_instance()->read('public_page_logo') !=""){?>
      <img class="logo-2 img-responsive" style="max-width: 235px;max-height: 50px;"  src="<?php echo BASE.DATA_DIR;?>/logo/<?php echo Configuration::get_instance()->read('public_page_logo');?>" alt="">
      <?php  }else{ ?>
      <div class="logo_padg"><?php echo Configuration::get_instance()->read('admarket_name');?></div>
      <?php } ?>
      </a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
     
     
      <ul class="nav navbar-nav navbar-right main_menu_right" style="margin-top:10px;">
       
     <?php
		   if($logedin ==1){
			   
			   
                   	foreach($menu_array as $menukey0=>$menuvalue0)
                   	{ 
                   		if($menukey0 !='advertiser_menu' && $menukey0 !='publisher_menu' && $menukey0 !='common_menu' && $menukey0 !='referral_menu')
                   		{
                   		
						if($menuvalue0['child'] =="")
						{ ?>
		                    <li><a <?php if($menuvalue0['link'] !=""){?>href="<?php echo $menuvalue0['link'];?>"<?php }?> class="<?php echo $menuvalue0['class'];?>"><?php if($menuvalue0['icon'] !=""){?><i class="fa <?php echo $menuvalue0['icon'];?>"></i><?php }?><?php echo $menuvalue0['label'];?></a></li>
							
							<?php }else{
							
							if($menukey0 =='marketplace' || $menukey0 =='pages'){?>
							
							<li class="dropdown">
                            <a  href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php if($menuvalue0['icon'] !=""){?><i class="fa <?php echo $menuvalue0['icon'];?>"></i><?php }?><?php echo $menuvalue0['label'];?><span class="caret"></span></a>
							<ul class="dropdown-menu main_menu_drop">
							<?php foreach($menuvalue0['child'] as $menukey1=>$menuvalue1){?>
							
							<li><a <?php if($menuvalue1['link'] !=""){?>href="<?php echo $menuvalue1['link'];?>"<?php }?>><?php echo $menuvalue1['label'];?></a></li>
							
							<?php }?>
							</ul>
							</li> 					
		                   	<?php 
							}
						} 
                   	  }
                   	}			   
			    }
                else
                { 
                   	foreach($menu_array as $menukey0=>$menuvalue0)
                   	{ 

						if($menuvalue0['child'] =="")
						{?>
	                    
	                    <li><a <?php if($menuvalue0['link'] !=""){?>href="<?php echo $menuvalue0['link'];?>"<?php }?> class="<?php echo $menuvalue0['class'];?>"><?php if($menuvalue0['icon'] !=""){?><i class="fa <?php echo $menuvalue0['icon'];?>"></i><?php }?><?php echo $menuvalue0['label'];?></a></li>
						
						<?php }else{?>
						
						<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
						<?php if($menuvalue0['icon'] !=""){?><i class="fa <?php echo $menuvalue0['icon'];?>"></i><?php }?><?php echo $menuvalue0['label'];?><span class="caret"></span></a>
						<ul class="dropdown-menu main_menu_drop">
						<?php foreach($menuvalue0['child'] as $menukey1=>$menuvalue1){?>
						
						<li><a <?php if($menuvalue1['link'] !=""){?>href="<?php echo $menuvalue1['link'];?>"<?php }?>><?php echo $menuvalue1['label'];?></a></li>
						
						<?php }?>
						</ul>
						</li> 					
	                   	<?php 
						} 
                   	}
				 }?>
                   
      </ul>
    </div>                        <!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
   
     
        <!-- header close -->
        
        
        
        
        
  
        <?php 
        
       	$url1='';
		$url2='';
		$url3='';
        
		if($page !=16 && $page !=17 && $page !=18 && $page !=20 && $page !=21 && $page !=22 && $page !=23 && $page !=24)
		{
		
		if($logedin ==1 && ($cpanel =='a' || $cpanel =='p' || $cpanel =='b' || $cpanel =='r') && ($page !=25 && $page !=26 && $page !=27)){?>
				
        
        
<div class="second-menu-list">
  <nav class="navbar navbar-default main_menu_bg3" role="navigation">
  <div class="container">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-3">
        <span class="sr-only"><?php echo $this->get_label('toggle navigation');?></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
     
    </div>
        
        
        <div class="collapse navbar-collapse head_padding" id="bs-example-navbar-collapse-3">
        

        <?php 
        if(($cpanel =="a"  && $adv_status==1) || ($cpanel =="p"  && $pub_status==1) || ($cpanel =="b" && ($adv_status==1 || $pub_status==1)) || ($cpanel=="r" && $referral_enabled ==1 && ($adv_status==1 || $pub_status==1)))
        {
    			if($cpanel=="a"  && $adv_status==1)
      			$menu_array=$GLOBALS['menu']['login']['advertiser_menu'];
      			else if($cpanel=="p"  && $pub_status==1)
      			$menu_array=$GLOBALS['menu']['login']['publisher_menu'];
      			else if($cpanel=="b"  && ($adv_status==1 || $pub_status==1))
      			$menu_array=$GLOBALS['menu']['login']['common_menu'];	        	
      			else if($cpanel=="r" && $referral_enabled ==1 && ($adv_status==1 || $pub_status==1))
      			$menu_array=$GLOBALS['menu']['login']['referral_menu'];	        	
	        	
        	?>			
					<ul class="nav navbar-nav sub_head sub_top" style="margin-top:10px;">
					
					<?php
					foreach($menu_array as $menukey0=>$menuvalue0)
                   	{ 
						if(count($menuvalue0['child']) ==0)
						{?>
                    	<li class="menu-first" id="_<?php echo $menuvalue0['id'];?>" onClick="show_sub_tab(<?php echo $menuvalue0['id'];?>,0,0);"><a class="<?php echo $menuvalue0['class'];?>" <?php if($menuvalue0['link'] !=""){?>href="<?php echo $menuvalue0['link'];?>"<?php }?>><?php if($menuvalue0['icon'] !=""){?><i class="fa <?php echo $menuvalue0['icon'];?>"></i><?php }?> <?php echo $menuvalue0['label'];?></a></li>
						<?php }else{?>
						
						
						
						
						<li class="menu-first dropdown" id="_<?php echo $menuvalue0['id'];?>" onClick="show_sub_tab(<?php echo $menuvalue0['id'];?>,0,0);"><a href="#" class="dropdown-toggle <?php echo $menuvalue0['class'];?>" data-toggle="dropdown" role="button" aria-expanded="false"><?php if($menuvalue0['icon'] !=""){?><i class="fa <?php echo $menuvalue0['icon'];?>"></i><?php }?> <?php echo $menuvalue0['label'];?><span class="caret"></span></a>
	                    
	                    <ul class="nav navbar-nav sub_head submenu-mobile" id="a<?php echo $menuvalue0['id'];?>-mobile" style="display: none;">
	                    
	                    <?php foreach($menuvalue0['child'] as $menukey1=>$menuvalue1){?>
	                    <li><a id="<?php echo $menuvalue1['id'];?>-mobile" class="<?php echo $menuvalue1['class'];?>" href="<?php echo $menuvalue1['link'];?>" ><?php if($menuvalue1['icon'] !=""){?><i class="fa <?php echo $menuvalue1['icon'];?>"></i><?php }?> <?php echo $menuvalue1['label'];?></a></li>
	                    <?php }?>
	                                     
	                    </ul>
	                    </li>						
					
	                   	<?php 
						} 
                   	}					
					?>

			<?php if($cpanel=="a"  && $adv_status==1){?>		
			<li class="account-li">	
			<div class="account-balance-div"><bdi><?php echo $this->get_label('adv account balance');?> : <?php echo $this->get_money_format($adv_balance); ?></bdi></div>
			<?php if($adv_bonus_balance >0){?>
			<div class="account-balance-div"><bdi><?php echo $this->get_label('bonus balance');?> : <?php echo $this->get_money_format($adv_bonus_balance); ?>
			<span class='bonushelp' onMouseOver="LoadAlert();" onMouseOut="HideAlert();">[?]</span>
			</bdi></div>
			<div class='bonusalert'><?php echo $this->get_label('bonus balance message');?></div>
			<?php }?>
			</li>
			<?php } else if($cpanel=="p"  && $pub_status==1){?>
			<li class="account-li-pub">	
			<div class="account-balance-div"><bdi><?php echo $this->get_label('pub account balance');?> : <?php echo $this->get_money_format($pub_balance); ?></bdi></div>
			</li>	
			<?php } else if($cpanel=="r" && $referral_enabled ==1 && ($adv_status==1 || $pub_status==1)){?>
			<li class="account-li-pub">
			<div class="account-balance-div"><bdi><?php echo $this->get_label('referral balance');?> : <?php echo $this->get_money_format($referral_balance,2); ?></bdi></div>
			</li>			
			<?php } ?>

			</ul>
	  <?php }?>
	  
     
      
    <?php if(($cpanel=="a"  && $adv_status==1) || ($cpanel=="p"  && $pub_status==1) || ($cpanel=="b" && ($pub_status==1 || $referral_enabled ==1))){	
        
    				if($cpanel=="a"  && $adv_status==1)
    	  			$menu_array=$GLOBALS['menu']['login']['advertiser_menu'];
    	  			else if($cpanel=="p"  && $pub_status==1)
    	  			$menu_array=$GLOBALS['menu']['login']['publisher_menu'];
    	  			else if($cpanel=="b" && ($pub_status==1 || $referral_enabled ==1))
    	  			$menu_array=$GLOBALS['menu']['login']['common_menu'];
    	  			 
					foreach($menu_array as $menukey0=>$menuvalue0)
                   	{ 
						if(count($menuvalue0['child']) >0)
						{?>
						<ul class="nav navbar-nav sub_head submenu" id="a<?php echo $menuvalue0['id'];?>" style="display: none;">
	                    <?php foreach($menuvalue0['child'] as $menukey1=>$menuvalue1){?>
	                    <li><a id="<?php echo $menuvalue1['id'];?>" class="<?php echo $menuvalue1['class'];?>" href="<?php echo $menuvalue1['link'];?>" ><?php if($menuvalue1['icon'] !=""){?><i class="fa <?php echo $menuvalue1['icon'];?>"></i><?php }?> <?php echo $menuvalue1['label'];?></a></li>
	                    <?php }?>
	                    </ul>
	                   	<?php 
						} 
                   	}					
	 }
?>


</div>
</div>
</nav>
</div>
      <?php }}?>
      
</header>      
</div>

   

<div class="container custom-date-div custom-top-message" style="display:none;"> <?php echo $this->get_label('custom date message',array('x'=>Configuration::get_instance()->read('daily_based_data_backup_expiry')));?></div>

<div id="content_section" 
<?php if($page==14 || $page==15){?>class="top-margin-section-190"
<?php } else if(($page==13 && $cpanel !="p") ||  $page==17 || $page==18 || $page==19 || $page==20  || $page==21 || $page==22 || $page==23 || $page==24 || $page==25 || $page==26 || $page==27){?> class="top-margin-section-116" 
<?php } else if($cpanel=="a" || $cpanel=="p" || $cpanel=="b"){?> class="top-margin-section-190" <?php }?>>

<script type="text/javascript">

function LoadAlert()
{
	$('.bonusalert').show();
}

function HideAlert()
{
	$('.bonusalert').hide();
}



function show_sub_tab(id,id1,from)
{
 	$('.submenu').hide();
	$('.submenu-mobile').hide();
	
	
	$('.submenu li a').removeClass('active');
	$('.submenu-mobile li a').removeClass('active');
	
	$('.menu-first').removeClass('active');
	$('.menu-first a').removeClass('active');
	

	 if($(window).width() < 768)
	 {
		 if($("#a"+id+"-mobile").length >0)
		 {
			 if(from ==1)
			 $("#a"+id+"-mobile").show();
			 else
			 $("#a"+id+"-mobile").slideDown(600);
			 

		 if(id1 >0 && $("#a"+id+"a"+id1+"-mobile").length >0)
		 $("#a"+id+"a"+id1+"-mobile").addClass("active");
	
		 }
	 }
	 else
	 {
		 if($("#a"+id).length >0)
		 { 
			 if(from ==1)
			 $("#a"+id).show();
			 else
		 	$("#a"+id).slideDown(600);	 
		
		 	if(id1 >0 && $("#a"+id+"a"+id1).length >0)
		 	$("#a"+id+"a"+id1).addClass("active");


		 	$('.sub_top').css("border-bottom","1px solid #CCCCCC");
		 	
		 }
	 }
	 


	$('#_'+id).addClass('active');
	//$('#_'+id+' a').addClass('active');
	 

}




$(document).ready(function(){

  var windowHeight = $(window).height(); 
  var headerHeight = $('header').height();
  var footerHeight = $('footer').height();

 
  
  if($('.second-menu-list').length >0)
  {
		var submenuHeight = parseFloat($('.second-menu-list').css('margin-top'));


		
		<?php if($page == 4 || $page == 8 || $page == 9 || $page == 10 || $page == 11 || $page == 14 || $page == 15){?>
		submenuHeight=submenuHeight+115;
		<?php }else{?>
		if($('.sub_head').length >0)
		submenuHeight=submenuHeight+115;
		else
		submenuHeight=submenuHeight+80;
		<?php }?>
  }
  else
  var submenuHeight =81;

  

	
  var min_cnt_height = windowHeight - (parseFloat(headerHeight)+parseFloat(footerHeight)+parseFloat(submenuHeight));


  
  $('#content_section').css('min-height',min_cnt_height+'px');



	var timerclose;
	var showmenu=<?php echo $page;?>;
	if(showmenu >0)
	show_sub_tab(showmenu,<?php echo $sub_page;?>,1);
	
	
	$(window).resize(function(){
		var showmenu=<?php echo $page;?>;


		$('.sub_top').css("border-bottom","0px");
		$('.menu-first').removeClass('active');
		$('.menu-first').removeClass('open');

		
		
		if(showmenu >0)
		{
			clearTimeout(window.timerclose);
			window.timerclose = setTimeout(function()
			{
				show_sub_tab(showmenu,<?php echo $sub_page;?>,1);
		    }, 50);
		}
	});
});



</script>  