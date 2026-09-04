<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height" />
<html>
<head>


<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/jquery-1.7.min.js'></script>
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>jquery-ui/jquery-ui.min.js'></script>
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/common.js'></script>
<link rel="icon" href="<?php echo BASE;?>favicon.ico">
<link rel="stylesheet" href="<?php echo BASE.ADMIN_DIR."/css/style.css";?>" type="text/css" />
<link rel="stylesheet" href="<?php echo COMMON_DIR_PATH;?>font-awesome/css/font-awesome.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo COMMON_DIR_PATH;?>jquery-ui/jquery-ui.min.css" />

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>
<?php
if($this->get_title('')=="")
echo Configuration::get_instance()->read('admarket_name');
else
echo Configuration::get_instance()->read('admarket_name')." - ".$this->get_title();
?>
</title>


<?php
if(DEMO_MODE)
echo Configuration::get_instance()->read('google_analytics_code');
?>

</head>
<body>

<?php
$pageid=$this->get_variable("pageid");
$subid=$this->get_variable("subid");
$addoncount=$this->get_variable("addoncount");

$subadmin_enabled=$this->get_addon_status('subadmin_enabled');
$cpc_enabled=$this->get_addon_status('cpc_enabled');
$category_enabled=$this->get_addon_status('category-targeting_enabled');
$sponsored_enabled=$this->get_addon_status('sponsored_enabled');

$countrywise_pricing_enabled=Configuration::get_instance()->read('countrywise_pricing_enabled');
if($subadmin_enabled ==1 && isset($GLOBALS['privilege']))
$privilege=$GLOBALS['privilege'];
else
$privilege=array();

?>
<div class="header header-top">
<div class="header_insider">
<div class="header_logo_section">
<a style="text-decoration: none;" href="<?php echo $this->make_url("index/control_panel");?>"><img  src="images/logo.png" class="logo" alt="<?php echo Configuration::get_instance()->read('admarket_name');?>" title="<?php echo Configuration::get_instance()->read('admarket_name');?>"> <span class="version-logo"><?php echo PRODUCT_VERSION;?></span></a>
</div>
<div style="float: left;">
<div class="showhide showhide1"><i class="fa fa-align-justify" onclick="ChangeMenuStyle(1);"></i></div>
<div class="showhide2"><i class="fa fa-align-justify" onclick="ChangeMenuStyle(2);"></i></div>
</div>


<div class="top_menus">

<div class="account-menu"><i class="fa fa-user account-box" aria-hidden="true" title="<?php echo $this->get_label('my account');?>"></i>

<div class="account-menu-inner">
<?php if(!DEMO_MODE){?>
<div style="border-bottom: 1px solid #CCCCCC;"><i class="fa fa-lock"></i>&nbsp;<a href="<?php echo $this->make_url("index/change_password");?>"><?php echo $this->get_label('change password');?></a></div>

<?php }?>

<div><i class="fa fa-power-off" style="font-size: 16px;"></i>&nbsp;<a href="<?php echo $this->make_url("index/logout");?>"><?php echo $this->get_label('logout');?></a></div>
</div>


</div>



<div class="help-menu"><i class="fa fa-question-circle help-box" title="<?php echo $this->get_label('help');?>"></i>

<div class="help-menu-inner">
<div style="border-bottom: 1px solid #CCCCCC;"><i class="fa fa-support" style="font-size: 14px !important;"></i>&nbsp;<a target="_blank" href="https://xyzscripts.com/support/"><?php echo $this->get_label('support');?></a></div>

<div style="border-bottom: 1px solid #CCCCCC;"><i class="fa fa-table"></i>&nbsp;<a target="_blank" href="https://help.xyzscripts.com/docs/xyz-admarket/user-guide-version-4-0/"><?php echo $this->get_label("user guide");?></a></div>

<div><i class="fa fa-floppy-o"></i>&nbsp;<a target="_blank" href="https://help.xyzscripts.com/docs/xyz-admarket/faq/"><?php echo $this->get_label("knowledge base");?></a></div>
</div>

</div>



<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0){?>
<a title="<?php echo $this->get_label("system update");?>" href="<?php echo $this->make_url("settings/system_update"); ?>"><i class="fa fa-wrench update-box" title="<?php echo $this->get_label("system update");?>"></i></a>
<?php }?>

<a target="_blank" href="https://xyzscripts.com/php-scripts/xyz-admarket/"><i class="fa fa-star about-box" title="<?php echo $this->get_label('about us');?>"></i></a>

</div> <!-- top_menus -->
</div> <!-- header_insider -->
</div> <!-- header -->

<div class="website header-bottom">



<div class="div_narrow_second"></div>
<input type="hidden" id="typedata" value="2" />

<div id="left" class="widget_left">
<ul>

<li><h2 style="height: 40px;line-height: 43px;"><?php echo $this->get_label("dashboard");?> </h2></li>



<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0){?>
<li class="one_row"><a <?php if($pageid=='15') {?>class="div_first_selected"<?php }?> href="<?php echo $this->make_url("index/control_panel"); ?>"><i class="fa fa-home fa-1x" title="<?php echo $this->get_label("home");?>"></i><span><?php echo $this->get_label("home");?></span></a></li>
<?php }?>

<li class="one_row"><a <?php if($pageid=='16') {?>class="div_first_selected"<?php }?> href="<?php echo $this->make_url("system/todo"); ?>"><i class="fa fa-hand-o-right" title="<?php echo $this->get_label("todo");?>"></i><span><?php echo $this->get_label("todo");?></span></a></li>


<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0 || isset($privilege['mu_1'])){?>
<li class="one_row"><a <?php if($pageid=='17') {?>class="div_first_selected"<?php }?> href="<?php echo $this->make_url("user/list"); ?>"><i class="fa fa-users" title="<?php echo $this->get_label("users");?>"></i><span><?php echo $this->get_label("users");?></span></a></li>
<?php }?>






<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0 || isset($privilege['aa_1'])){?>

<li>
<div class="div_first <?php if($pageid==1) {?>div_selected_head<?php }?>" id="1a1_1"><img class="down_menu" id="1aa1_1" src="images/plus_menu.png" /><i id="1aaa1_1" class="fa fa-list" title="<?php echo $this->get_label("ads");?>"></i><span id="1aaaa1_1"><?php echo $this->get_label('ads');?></span></div>

<div class="div_second" id="_1">

<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0 || isset($privilege['aa_1'])){?>
<a <?php if($subid=='_11') {?>class="div_second_selected"<?php }?> href="<?php echo $this->make_url("ad/list");?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label('advertisers ads');?></a>
<?php }?>


<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0){?>
<a <?php if($subid=='_12') {?>class="div_second_selected"<?php }?> href="<?php echo $this->make_url("ad/list_default")?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label('default ads');?></a>
<?php }?>

</div>

</li>
<?php }?>





<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0 || isset($privilege['ac_1']) || isset($privilege['ac_2'])){?>
<li>
<div class="div_first <?php if($pageid==2) {?>div_selected_head<?php }?>" id="1a1_2"><img class="down_menu" id="1aa1_2" src="images/plus_menu.png" /><i id="1aaa1_2" class="fa fa-file-text-o" title="<?php echo $this->get_label("adunits");?>"></i><span id="1aaaa1_2"><?php echo $this->get_label('adunits');?></span></div>
<div class="div_second" id="_2">

<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0 || isset($privilege['ac_1'])){?>
<a <?php if($subid=='_21') {?>class="div_second_selected"<?php }?> href="<?php echo $this->make_url("adunit/manage_user_adcode");?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label('publisher ad codes');?></a>
<?php }?>

<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0 || isset($privilege['ac_2'])){?>
<a <?php if($subid=='_22') {?>class="div_second_selected"<?php }?> href="<?php echo $this->make_url("adunit/manage");?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label('admin ad codes');?></a>

<?php if($sponsored_enabled == 1){?>


<a <?php if($subid=='_23') {?>class="div_second_selected"<?php }?> href="<?php echo $this->make_url("dispatch/sponsored/35");?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label('cpd adcode targeting');?></a>
<?php }?>
<?php }?>

</div>
</li>
<?php }?>




<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0){?>
<li class="one_row"><a <?php if($pageid=='18') {?>class="div_first_selected"<?php }?> href="<?php echo $this->make_url("keyword/list"); ?>"><i class="fa fa-keyboard-o" title="<?php echo $this->get_label("manage keywords");?>"></i><span><?php echo $this->get_label("manage keywords");?></span></a></li>
<?php }?>



<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0){?>


<li class="one_row"><h2><?php echo $this->get_label("configurations");?></h2></li>
<li>
<div class="div_first <?php if($pageid==6) {?>div_selected_head<?php }?>" id="1a1_6"><img class="down_menu" id="1aa1_6" src="images/plus_menu.png" /><i id="1aaa1_6" class="fa fa-gear" title="<?php echo $this->get_label("settings");?>"></i><span id="1aaaa1_6"><?php echo $this->get_label('settings');?></span></div>

<div class="div_second" id="_6">
<a <?php if($subid=='_61') {?>class="div_second_selected"<?php }?> href="<?php echo $this->make_url("settings/configure/1");?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label('general settings');?></a>
<a <?php if($subid=='_62') {?>class="div_second_selected"<?php }?> href="<?php echo $this->make_url("settings/configure/2");?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label('ad display settings');?></a>
<a <?php if($subid=='_63') {?>class="div_second_selected"<?php }?> href="<?php echo $this->make_url("settings/configure/3");?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label('pay & withdraw settings');?></a>
<a <?php if($subid=='_64') {?>class="div_second_selected"<?php }?> href="<?php echo $this->make_url("settings/configure/4");?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label('admin settings');?></a>
<a <?php if($subid=='_65') {?>class="div_second_selected"<?php }?> href="<?php echo $this->make_url("settings/configure/5");?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label('format settings');?></a>
<?php if($countrywise_pricing_enabled == 1){ ?>
<a <?php if($subid=='_66') {?>class="div_second_selected"<?php }?> href="<?php echo $this->make_url("settings/configure/6");?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label('countrywise rate settings');?></a>
<?php } ?>
</div>
</li>



<li>
<div class="div_first <?php if($pageid==4) {?>div_selected_head<?php }?>" id="1a1_4"><img class="down_menu" id="1aa1_4" src="images/plus_menu.png" /><i id="1aaa1_4" class="fa fa-th-large" title="<?php echo $this->get_label("adblocks");?>"></i><span id="1aaaa1_4"><?php echo $this->get_label('adblocks');?></span></div>

<div class="div_second" id="_4">
<a <?php if($subid=='_42') {?>class="div_second_selected"<?php }?> href="<?php echo $this->make_url("adblock/manage")?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label('manage adblocks');?></a>
<a <?php if($subid=='_44') {?>class="div_second_selected"<?php }?> href="<?php echo $this->make_url("banner_dimension/list")?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label('banner dimensions');?></a>
<a <?php if($subid=='_46') {?>class="div_second_selected"<?php }?> href="<?php echo $this->make_url("credit/manage")?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label('manage credit text');?></a>


<a <?php if($subid=='_47') {?>class="div_second_selected"<?php }?> href="<?php echo $this->make_url("adblock/themes")?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label('ad display themes');?></a>
<a <?php if($subid=='_48') {?>class="div_second_selected"<?php }?> href="<?php echo $this->make_url("adblock/fonts")?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label('font settings');?></a>

<a <?php if($subid=='_50') {?>class="div_second_selected"<?php }?> href="<?php echo $this->make_url("adblock/external_fonts")?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label('external fonts');?></a>

<a <?php if($subid=='_49') {?>class="div_second_selected"<?php }?> href="<?php echo $this->make_url("adblock/ad_layout")?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label('text ad layout');?></a>

</div>
</li>


<li>
<div class="div_first <?php if($pageid==3) {?>div_selected_head<?php }?>" id="1a1_3"><img class="down_menu" id="1aa1_3" src="images/plus_menu.png" /><i id="1aaa1_3" class="fa fa-sitemap" title="<?php echo $this->get_label("site content");?>"></i><span id="1aaaa1_3"><?php echo $this->get_label('site content');?></span></div>
<div class="div_second" id="_3">
<a <?php if($subid=='_31') {?>class="div_second_selected"<?php }?>  href="<?php echo $this->make_url("system/meta_data"); ?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label("meta data");?></a>
<a <?php if($subid=='_32') {?>class="div_second_selected"<?php }?>  href="<?php echo $this->make_url("system/about_us"); ?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label("about us");?></a>
<a <?php if($subid=='_33') {?>class="div_second_selected"<?php }?>  href="<?php echo $this->make_url("system/terms"); ?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label("terms & conditions");?></a>
<a <?php if($subid=='_310') {?>class="div_second_selected"<?php }?>  href="<?php echo $this->make_url("system/cookie_policy"); ?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label("cookie policy");?></a>
<a <?php if($subid=='_311') {?>class="div_second_selected"<?php }?>  href="<?php echo $this->make_url("system/privacy_policy"); ?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label("privacy policy");?></a>
<a <?php if($subid=='_34') {?>class="div_second_selected"<?php }?>  href="<?php echo $this->make_url("system/testimonial"); ?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label("testimonials");?></a>
<a <?php if($subid=='_35') {?>class="div_second_selected"<?php }?>  href="<?php echo $this->make_url("system/seo_url"); ?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label("manage seo url");?></a>
<a <?php if($subid=='_39') {?>class="div_second_selected"<?php }?>  href="<?php echo $this->make_url("locale/manage"); ?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label("manage locale");?></a>
<a <?php if($subid=='_36') {?>class="div_second_selected"<?php }?>  href="<?php echo $this->make_url("system/manage"); ?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label("manage pages");?></a>
<a <?php if($subid=='_38') {?>class="div_second_selected"<?php }?>  href="<?php echo $this->make_url("system/notifications"); ?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label("user notifications");?></a>
<a <?php if($subid=='_40') {?>class="div_second_selected"<?php }?>  href="<?php echo $this->make_url("system/manage_faq"); ?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label("manage faq");?></a>

</div>
</li>

<li class="one_row"><a <?php if($pageid=='19') {?>class="div_first_selected"<?php }?> href="<?php echo $this->make_url("email_template/manage"); ?>"><i class="fa fa-envelope" title="<?php echo $this->get_label("manage email templates");?>"></i><span><?php echo $this->get_label("manage email templates");?></span></a></li>

<?php if(Configuration::get_instance()->read('apply_tax_rules') ==1){?>
<li class="one_row"><a <?php if($pageid=='29') {?>class="div_first_selected"<?php }?> href="<?php echo $this->make_url("system/tax_rules"); ?>"><i class="fa fa-money"></i><span><?php echo $this->get_label("manage tax rules");?></span></a></li>
<?php }?>

<li class="one_row"><a <?php if($pageid=='23') {?>class="div_first_selected"<?php }?> href="<?php echo $this->make_url("system/backup"); ?>"><i class="fa fa-database" title="<?php echo $this->get_label("data backup");?>"></i><span><?php echo $this->get_label("data backup");?></span></a></li>

<?php }?>



<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0 || isset($privilege['ap_1']) || isset($privilege['pw_1'])){?>

<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==2){?>
<li class="one_row"><h2><?php echo $this->get_label("payments");?></h2></li>
<?php } else if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==3){?>
<li class="one_row"><h2><?php echo $this->get_label("withdrawals");?></h2></li>
<?php } else {?>
<li class="one_row"><h2><?php echo $this->get_label("payments withdrawals");?></h2></li>
<?php }?>

<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0 || isset($privilege['ap_1'])){?>
<li class="one_row"><a <?php if($pageid=='21') {?>class="div_first_selected"<?php }?> href="<?php echo $this->make_url("advertiser/payments");?>"><i class="fa fa-credit-card" title="<?php echo $this->get_label("payments");?>"></i>&nbsp;<span><?php echo $this->get_label("payments");?></span></a></li>
<?php }?>

<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0 || isset($privilege['pw_1'])){?>


<li><div class="div_first" id="1a1_22"><img class="down_menu" id="1aa1_22" src="images/plus_menu.png" /><i id="1aaa1_22" class="fa fa-bank" title="<?php echo $this->get_label("withdrawals");?>"></i><span id="1aaaa1_22"><?php echo $this->get_label("withdrawals");?></span></div>
<div class="div_second" id="_22">
<a <?php if($subid=='_221') {?>class="div_second_selected"<?php }?> href="<?php echo $this->make_url("user/withdrawal_history");?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label("withdrawals");?></a>
<a <?php if($subid=='_222') {?>class="div_second_selected"<?php }?> href="<?php echo $this->make_url("user/report_files");?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label("report files");?></a>

</div>
<?php }?>
<?php }?>


<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0 || isset($privilege['sr_1']) || isset($privilege['sr_2']) || isset($privilege['sr_3']) || isset($privilege['sr_4']) || isset($privilege['ur_1']) || isset($privilege['ur_2']) || isset($privilege['ur_3']) || isset($privilege['ur_4']) || isset($privilege['ur_5']) || isset($privilege['ur_6']) || isset($privilege['ur_7'])){?>
<li class="one_row"><h2><?php echo $this->get_label("reports");?></h2></li>


<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0 || isset($privilege['ur_1']) || isset($privilege['ur_2']) || isset($privilege['ur_3']) || isset($privilege['ur_4']) || isset($privilege['ur_5']) || isset($privilege['ur_6']) || isset($privilege['ur_7'])){?>

<li>

<div class="div_first <?php if($pageid==5) {?>div_selected_head<?php }?>" id="1a1_5"><img class="down_menu" id="1aa1_5" src="images/plus_menu.png" /><i id="1aaa1_5" class="fa fa-bar-chart" title="<?php echo $this->get_label("user reports");?>"></i><span id="1aaaa1_5"><?php echo $this->get_label('user reports');?></span></div>


<div class="div_second" id="_5">

<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0 || isset($privilege['ur_1'])){?>
<a <?php if($subid=='_51') {?>class="div_second_selected"<?php }?> href="<?php echo $this->make_url("report/advertisers");?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label('advertisers statistics');?></a>
<?php }?>
<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0 || isset($privilege['ur_2'])){?>
<a <?php if($subid=='_52') {?>class="div_second_selected"<?php }?> href="<?php echo $this->make_url("report/publishers");?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label('publishers statistics');?></a>
<?php }?>

<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0 || isset($privilege['ur_3'])){?>
<a <?php if($subid=='_53') {?>class="div_second_selected"<?php }?> href="<?php echo $this->make_url("report/ads")?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label('ad reports');?></a>
<?php }?>
<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0 || isset($privilege['ur_4'])){?>
<a <?php if($subid=='_54') {?>class="div_second_selected"<?php }?> href="<?php echo $this->make_url("report/adcodes")?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label('adunit statistics admin');?></a>
<?php }?>
<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0 || isset($privilege['ur_5'])){?>
<a <?php if($subid=='_55') {?>class="div_second_selected"<?php }?> href="<?php echo $this->make_url("report/adcodes_publisher")?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label('adunit statistics publisher');?></a>
<?php }?>

<?php
if($category_enabled ==1)
{

		$url11='';
		$url22='';

		if($category_enabled ==1)
		{
			$url11=$this->make_url("dispatch/category_targeting/21");
			$url22=$this->make_url("dispatch/category_targeting/23");
		}

?>

<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0 || isset($privilege['ur_6'])){?>
<a <?php if($subid=='_56') {?>class="div_second_selected"<?php }?> href="<?php echo $url11;?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label('site statistics admin');?></a>
<?php }?>
<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0 || isset($privilege['ur_7'])){?>
<a <?php if($subid=='_57') {?>class="div_second_selected"<?php }?> href="<?php echo $url22;?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label('site statistics publisher');?></a>
<?php }?>

<?php


}
?>
</div>
</li>

<?php }?>


<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0 || isset($privilege['sr_1']) || isset($privilege['sr_2']) || isset($privilege['sr_3']) || isset($privilege['sr_4'])){?>

<li>
<div class="div_first <?php if($pageid==7) {?>div_selected_head<?php }?>" id="1a1_7"><img class="down_menu" id="1aa1_7" src="images/plus_menu.png" /><i id="1aaa1_7" class="fa fa-line-chart" title="<?php echo $this->get_label("system statistics");?>"></i><span id="1aaaa1_7"><?php echo $this->get_label('system statistics');?></span></div>

<div class="div_second" id="_7">

<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0 || isset($privilege['sr_1'])){?>
<a <?php if($subid=='_71') {?>class="div_second_selected"<?php }?> href="<?php echo $this->make_url("report/overall");?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label('overall statistics');?></a>
<?php }?>

<?php if($cpc_enabled ==1){
if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0 || isset($privilege['sr_2'])){?>
<a <?php if($subid=='_72') {?>class="div_second_selected"<?php }?> href="<?php echo $this->make_url("report/clicks");?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label('click analysis');?></a>
<?php }}?>

<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0 || isset($privilege['sr_3'])){?>
<a <?php if($subid=='_73') {?>class="div_second_selected"<?php }?> href="<?php echo $this->make_url("report/profit");?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label('profit statistics');?></a>
<?php }?>

<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0 || isset($privilege['sr_4'])){?>
<a <?php if($subid=='_74') {?>class="div_second_selected"<?php }?> href="<?php echo $this->make_url("report/verify");?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label('check statistics');?></a>
<?php }?>


<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0 || isset($privilege['sr_5'])){?>
<a <?php if($subid=='_75') {?>class="div_second_selected"<?php }?> href="<?php echo $this->make_url("report/top_list");?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label('toppers list');?></a>
<?php }?>

<?php if(Configuration::get_instance()->read('countrywise_data_tracking') ==1 && ($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0 || isset($privilege['sr_6']))){?>
<a <?php if($subid=='_76') {?>class="div_second_selected"<?php }?> href="<?php echo $this->make_url("report/country");?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label('countrywise report');?></a>
<?php }?>


</div>
</li>

<?php }?>
<?php }?>




<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0){?>




<li class="one_row">
<h2>
<?php
if($addoncount >0)
echo $this->get_label("addons & themes");
else
echo $this->get_label("themes");
?>
</h2></li>



<?php if($addoncount >0){?>


<?php if(!DEMO_MODE){?>
<li class="one_row"><a <?php if($pageid=='20') {?>class="div_first_selected"<?php }?> href="<?php echo $this->make_url("addon/manage");?>"><i class="fa fa-plug" title="<?php echo $this->get_label("manage addons");?>"></i><span><?php echo $this->get_label("manage addons");?></span></a></li>
<?php }?>



<?php
$pluginarray1=$this->get_array('pluginarray');
$adflg=0;
foreach ($pluginarray1[0] as $pkey =>$pvalue)
{
	if($this->get_addon_status($pvalue.'_enabled') ==1 && $pvalue !='language-targeting' && $pvalue !='withdrawal' && $pvalue !='subadmin' && $pvalue !='ecommerce-ads' && $pvalue !='text-image-ads' && $pvalue !='skin-ads' && $pvalue !='expandable-banners' && $pvalue !='inpage-push-ads')
	{
		$adflg=1;
		break;
	}
}


if($adflg ==1)
{
?>
<li>

<div class="div_first <?php if($pageid==8) {?>div_selected_head<?php }?>" id="1a1_8"><img class="down_menu" id="1aa1_8" src="images/plus_menu.png" /><i id="1aaa1_8" class="fa fa-gears" title="<?php echo $this->get_label("addon settings");?>"></i><span id="1aaaa1_8"><?php echo $this->get_label('addon settings');?></span></div>


<div class="div_second" id="_8">

<?php

$pluginarray=$this->get_array('pluginarray');

$iii=1;
foreach ($pluginarray[0] as $pkey =>$pvalue)
{
	if($this->get_addon_status($pvalue.'_enabled') ==1 && $pvalue !='language-targeting' && $pvalue !='withdrawal' && $pvalue !='subadmin' && $pvalue !='ecommerce-ads'  && $pvalue !='text-image-ads' && $pvalue !='skin-ads' && $pvalue !='expandable-banners' && $pvalue !='inpage-push-ads')
	{
		?>
		<a style="font-size: 12px;" <?php if($subid=='_8'.$iii) {?>class="div_second_selected"<?php }?> href="<?php echo $this->make_url("addon/settings/".$pvalue);?>"><i class="fa fa-circle-o"></i>&nbsp;
			<?php
			if($pvalue == 'sponsored')
			$addonName = 'CPD';
			else
			$addonName = $pvalue;


			echo $this->get_label('addon name',array('x'=>strtoupper($addonName)));
			?>
		</a>
		<?php
	}
	$iii=$iii+1;
}
?>
</div>

</li>
<?php


}
?>

<?php }?>


<?php if(!DEMO_MODE){?>
<li class="one_row"><a <?php if($pageid=='25') {?>class="div_first_selected"<?php }?> href="<?php echo $this->make_url("theme/manage");?>"><i class="fa fa-desktop" title="<?php echo $this->get_label("manage themes");?>"></i><span><?php echo $this->get_label("manage themes");?></span></a></li>
<?php }?>

<?php
if($category_enabled ==1)
{
	if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0 || isset($privilege['ms_1']) || isset($privilege['ms_2']))
	{

		$url1='';
		$url2='';
		$url3='';
		$url4='';


		if($category_enabled ==1)
		{
			$url1=$this->make_url("dispatch/category_targeting/1");
			$url2=$this->make_url("dispatch/category_targeting/2");
			$url3=$this->make_url("dispatch/category_targeting/5");
			$url4=$this->make_url("dispatch/category_targeting/6");
		}
		?>

	<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0){?>
	<li>
	<div class="div_first <?php if($pageid==10) {?>div_selected_head<?php }?>" id="1a1_10"><img class="down_menu" id="1aa1_10" src="images/plus_menu.png" /><i id="1aaa1_10" class="fa fa-folder-open" title="<?php echo $this->get_label("category & sites");?>"></i><span id="1aaaa1_10"><?php echo $this->get_label('category & sites');?></span></div>
	<div class="div_second" id="_10">

	<a <?php if($subid=='_102') {?>class="div_second_selected"<?php }?> href="<?php echo $url2;?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label('manage categories');?></a>

	<a <?php if($subid=='_104') {?>class="div_second_selected"<?php }?> href="<?php echo $url4;?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label('manage websites');?></a>
	</div>
	</li>
	<?php }?>
<?php }}?>


<?php if($subadmin_enabled ==1){?>
<li class="one_row">
<a <?php if($pageid ==14) {?>class="div_first_selected"<?php }?> href="<?php echo $this->make_url("dispatch/subadmin/manage");?>"><i class="fa fa-user-plus" title="<?php echo $this->get_label("manage subadmin");?>"></i><span><?php echo $this->get_label("manage subadmin");?></span></a>
</li>
<?php }?>







<?php if($this->get_addon_status('withdrawal_enabled') ==1){?>
<li>
<div class="div_first <?php if($pageid==12) {?>div_selected_head<?php }?>" id="1a1_12"><img class="down_menu" id="1aa1_12" src="images/plus_menu.png" /><i id="1aaa1_12" class="fa fa-users" title="<?php echo $this->get_label("withdrawal options");?>"></i><span id="1aaaa1_12"><?php echo $this->get_label('withdrawal options');?></span></div>
<div class="div_second" id="_12">
	<a <?php if($subid=='_121') {?>class="div_second_selected"<?php }?> href="<?php echo $this->make_url("dispatch/withdrawal/add");?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label('add option');?></a>
	<a <?php if($subid=='_122') {?>class="div_second_selected"<?php }?> href="<?php echo $this->make_url("dispatch/withdrawal/manage");?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label('manage option');?></a>
</div>
</li>
<?php }?>


<?php if($this->get_addon_status('html_enabled') ==1){?>
<li>
<div class="div_first <?php if($pageid==9) {?>div_selected_head<?php }?>" id="1a1_9"><img class="down_menu" id="1aa1_9" src="images/plus_menu.png" /><i id="1aaa1_9" class="fa fa-html5" title="<?php echo $this->get_label("html ad");?>"></i><span id="1aaaa1_9"><?php echo $this->get_label('html ad');?></span></div>
<div class="div_second" id="_9">
	<a <?php if($subid=='_91') {?>class="div_second_selected"<?php }?> href="<?php echo $this->make_url("dispatch/html/create");?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label('create html ad');?></a>
	<a <?php if($subid=='_92') {?>class="div_second_selected"<?php }?> href="<?php echo $this->make_url("dispatch/html/manage");?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label('manage html ads');?></a>
	<a <?php if($subid=='_93') {?>class="div_second_selected"<?php }?> href="<?php echo $this->make_url("dispatch/html/statistics");?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label('html statistics');?></a>
	<a <?php if($subid=='_94') {?>class="div_second_selected"<?php }?> href="<?php echo $this->make_url("dispatch/html/overall");?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label('html overall');?></a>
</div>
</li>
<?php }?>

<?php if($this->get_addon_status('dsp-connector_enabled') == 1){?>
<li>
<div class="div_first <?php if($pageid == 36){?>div_selected_head<?php }?>" id="1a1_36"><img class="down_menu" id="1aa1_36" src="images/plus_menu.png" /><i id="1aaa1_36" class="fa fa-html5" title="<?php echo $this->get_label("dsp ads");?>"></i><span id="1aaaa1_36"><?php echo $this->get_label('dsp ads');?></span></div>
<div class="div_second" id="_36">
	<a <?php if($subid=='_361') {?>class="div_second_selected"<?php }?> href="<?php echo $this->make_url("dispatch/dsp-connector/create");?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label('create dsp ad');?></a>
	<a <?php if($subid=='_362') {?>class="div_second_selected"<?php }?> href="<?php echo $this->make_url("dispatch/dsp-connector/manage");?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label('manage dsp ads');?></a>
	<a <?php if($subid=='_363') {?>class="div_second_selected"<?php }?> href="<?php echo $this->make_url("dispatch/dsp-connector/statistics");?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label('dsp ads statistics');?></a>
	<a <?php if($subid=='_364') {?>class="div_second_selected"<?php }?> href="<?php echo $this->make_url("dispatch/dsp-connector/overall");?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label('dsp ads overall');?></a>
</div>
</li>
<?php }  ?>

<?php if($this->get_addon_status('ssp-connector_enabled') == 1){?>
<li>
<div class="div_first <?php if($pageid == 37){?>div_selected_head<?php }?>" id="1a1_37"><img class="down_menu" id="1aa1_37" src="images/plus_menu.png" /><i id="1aaa1_37" class="fa fa-html5" title="<?php echo $this->get_label("ssp endpoints");?>"></i><span id="1aaaa1_37"><?php echo $this->get_label('ssp endpoints');?></span></div>
<div class="div_second" id="_37">
	<a <?php if($subid=='_371') {?>class="div_second_selected"<?php }?> href="<?php echo $this->make_url("dispatch/ssp-connector/create");?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label('create ssp endpoint');?></a>
	<a <?php if($subid=='_372') {?>class="div_second_selected"<?php }?> href="<?php echo $this->make_url("dispatch/ssp-connector/manage");?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label('manage ssp endpoints');?></a>
	<a <?php if($subid=='_373') {?>class="div_second_selected"<?php }?> href="<?php echo $this->make_url("dispatch/ssp-connector/statistics");?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label('ssp endpoints statistics');?></a>
	<a <?php if($subid=='_374') {?>class="div_second_selected"<?php }?> href="<?php echo $this->make_url("dispatch/ssp-connector/overall");?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label('ssp endpoints overall');?></a>
</div>
</li>
<?php }  ?>
<?php if($this->get_addon_status('feed-ads_enabled') ==1){

	$demand_feed_ads = Configuration::get_instance()->read('demand_feed_ads');

	if($demand_feed_ads == 1){?>
<li>
<div class="div_first <?php if($pageid==34) {?>div_selected_head<?php }?>" id="1a1_34"><img class="down_menu" id="1aa1_34" src="images/plus_menu.png" /><i id="1aaa1_34" class="fa fa-rss-square" title="<?php echo $this->get_label("feed ads");?>"></i><span id="1aaaa1_34"><?php echo $this->get_label('feed ads');?></span></div>
<div class="div_second" id="_34">
	<a <?php if($subid=='_341') {?>class="div_second_selected"<?php }?> href="<?php echo $this->make_url("dispatch/feed-ads/create");?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label('create feed ad');?></a>
	<a <?php if($subid=='_342') {?>class="div_second_selected"<?php }?> href="<?php echo $this->make_url("dispatch/feed-ads/manage");?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label('manage feed ads');?></a>
	<a <?php if($subid=='_343') {?>class="div_second_selected"<?php }?> href="<?php echo $this->make_url("dispatch/feed-ads/statistics");?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label('feed ads statistics');?></a>
	<a <?php if($subid=='_344') {?>class="div_second_selected"<?php }?> href="<?php echo $this->make_url("dispatch/feed-ads/overall");?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label('feed ads overall');?></a>
</div>
</li>
<?php }}?>





<?php if($this->get_addon_status('video-ads_enabled') ==1){?>
<li class="one_row"><a <?php if($pageid ==32) {?>class="div_first_selected"<?php }?> href="<?php echo $this->make_url("dispatch/video-ads/1");?>"><i class="fa fa-video-camera" title="<?php echo $this->get_label("video aspect ratio");?>"></i><span><?php echo $this->get_label("video aspect ratio");?></span></a></li>
<?php }?>





<?php  if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0){ ?>

<?php if($this->get_addon_status('ecommerce-ads_enabled') ==1){?>
<li>
<div class="div_first <?php if($pageid==27) {?>div_selected_head<?php }?>" id="1a1_27"><img class="down_menu" id="1aa1_27" src="images/plus_menu.png" /><i id="1aaa1_27" class="fa fa-columns" title="<?php echo $this->get_label("ecommerce ads");?>"></i><span id="1aaaa1_27"><?php echo $this->get_label('ecommerce ads');?></span></div>
<div class="div_second" id="_27">
<a <?php if($subid=='_271') {?>class="div_second_selected"<?php }?> href="<?php echo $this->make_url("dispatch/ecommerce-ads/1");?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label('ad layout');?></a>
<a <?php if($subid=='_272') {?>class="div_second_selected"<?php }?> href="<?php echo $this->make_url("dispatch/ecommerce-ads/2");?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label('display layout');?></a>
</div>
</li>
<?php
}}}?>


<?php  if($this->get_addon_status('referral_enabled') ==1){?>
<?php  if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0 || isset($privilege['rr_1']) || isset($privilege['rr_2']) || isset($privilege['rr_3'])){ ?>
<li>
<div class="div_first <?php if($pageid==26) {?>div_selected_head<?php }?>" id="1a1_26"><img class="down_menu" id="1aa1_26" src="images/plus_menu.png" /><i id="1aaa1_26" class="fa fa-user-plus" title="<?php echo $this->get_label("referral system");?>"></i><span id="1aaaa1_26"><?php echo $this->get_label('referral system');?></span></div>
<div class="div_second" id="_26">
<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0 || isset($privilege['rr_1'])){ ?>

<a <?php if($subid=='_261') {?>class="div_second_selected"<?php }?> href="<?php echo $this->make_url("dispatch/referral/1");?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label('referral banners');?></a>
<?php }
 if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0 || isset($privilege['rr_2'])){ ?>
<a <?php if($subid=='_262') {?>class="div_second_selected"<?php }?> href="<?php echo $this->make_url("dispatch/referral/2");?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label('referral reports');?></a>
<?php }

if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0 || isset($privilege['rr_3'])){?>
<a <?php if($subid=='_263') {?>class="div_second_selected"<?php }?> href="<?php echo $this->make_url("dispatch/referral/3");?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label('referral visits');?></a>
<?php }

if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0 || isset($privilege['rr_4'])){ ?>
<a <?php if($subid=='_264') {?>class="div_second_selected"<?php }?> href="<?php echo $this->make_url("dispatch/referral/4");?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label('referral top');?></a>
<?php }?>
</div>
</li>
<?php }}





$addon_folder=$this->xyz_get_addon_folder_name("XYZADMNLR");
$newsletter_enable=$this->get_addon_status($addon_folder.'_enabled');

if($newsletter_enable ==1)
{
	if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0 || isset($privilege['nm_1']) || isset($privilege['nm_2'] )|| isset($privilege['nm_3']))
	{?>
						<li class="one_row"><h2><?php echo $this->get_label("newsletter");?></h2></li>
					<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0 || isset($privilege['nm_1'])){ ?>
					<li>
<div class="div_first <?php if($pageid==28) {?>div_selected_head<?php }?>" id="1a1_28"><img class="down_menu" id="1aa1_28" src="images/plus_menu.png" /><i id="1aaa1_28" class="fa fa-list" title="<?php echo $this->get_label("email_lists");?>"></i><span id="1aaaa1_28"><?php echo $this->get_label('email_lists');?></span></div>
<div class="div_second" id="_28">
<a <?php if($subid=='_281') {?>class="div_second_selected"<?php }?>  href="<?php echo $this->make_url("dispatch/".$addon_folder."/1");?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label("manage_list");?></a>

</div>
</li>
<?php }
 if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0 || isset($privilege['nm_2'])){ ?>
	<li>
<div class="div_first <?php if($pageid==29) {?>div_selected_head<?php }?>" id="1a1_29"><img class="down_menu" id="1aa1_29" src="images/plus_menu.png" /><i id="1aaa1_29" class="fa fa-file-text-o" title="<?php echo $this->get_label("email_addresses");?>"></i><span id="1aaaa1_29"><?php echo $this->get_label('email_addresses');?></span></div>
<div class="div_second" id="_29">
<a <?php if($subid=='_291') {?>class="div_second_selected"<?php }?>  href="<?php echo $this->make_url("dispatch/".$addon_folder."/2");?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label("search");?></a>
<a <?php if($subid=='_292') {?>class="div_second_selected"<?php }?>  href="<?php echo $this->make_url("dispatch/".$addon_folder."/3");?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label("add_email");?></a>
<a <?php if($subid=='_293') {?>class="div_second_selected"<?php }?>  href="<?php echo $this->make_url("dispatch/".$addon_folder."/4");?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label("bulk_insert");?></a>
<a <?php if($subid=='_294') {?>class="div_second_selected"<?php }?>  href="<?php echo $this->make_url("dispatch/".$addon_folder."/5");?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label("list_all_email");?></a>
<a <?php if($subid=='_295') {?>class="div_second_selected"<?php }?>  href="<?php echo $this->make_url("dispatch/".$addon_folder."/6");?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label("bulk_delete");?></a>
<a <?php if($subid=='_296') {?>class="div_second_selected"<?php }?>  href="<?php echo $this->make_url("dispatch/".$addon_folder."/7");?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label("bulk_unsubscribe");?></a>

</div>
</li>
<?php }
 if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0 || isset($privilege['nm_3'])){ ?>
<li>
<div class="div_first <?php if($pageid==31) {?>div_selected_head<?php }?>" id="1a1_31"><img class="down_menu" id="1aa1_31" src="images/plus_menu.png" /><i id="1aaa1_31" class="fa fa-envelope" title="<?php echo $this->get_label("email_campaigns");?>"></i><span id="1aaaa1_31"><?php echo $this->get_label('email_campaigns');?></span></div>
<div class="div_second" id="_31">
<a <?php if($subid=='_311') {?>class="div_second_selected"<?php }?>  href="<?php echo $this->make_url("dispatch/".$addon_folder."/16");?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label("create_campaign_template");?></a>
<a <?php if($subid=='_312') {?>class="div_second_selected"<?php }?>  href="<?php echo $this->make_url("dispatch/".$addon_folder."/17");?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label("manage_campaign_template");?></a>
<a <?php if($subid=='_313') {?>class="div_second_selected"<?php }?>  href="<?php echo $this->make_url("dispatch/".$addon_folder."/18");?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label("create_email_campaigns");?></a>
<a <?php if($subid=='_314') {?>class="div_second_selected"<?php }?>  href="<?php echo $this->make_url("dispatch/".$addon_folder."/19");?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label("manage_email_campaigns");?></a>
<a <?php if($subid=='_315') {?>class="div_second_selected"<?php }?>  href="<?php echo $this->make_url("dispatch/".$addon_folder."/20");?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label("email_campaigns_statistics");?></a>
<a <?php if($subid=='_316') {?>class="div_second_selected"<?php }?>  href="<?php echo $this->make_url("dispatch/".$addon_folder."/21");?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label("manage_campaigns_queue");?></a>
<a <?php if($subid=='_317') {?>class="div_second_selected"<?php }?>  href="<?php echo $this->make_url("dispatch/".$addon_folder."/45");?>"><i class="fa fa-circle-o"></i>&nbsp;<?php echo $this->get_label("manage_system_templates");?></a>

</div>
</li>

<?php }}
}
 ?>





<li class="one_row"><h2 class="menu-last"></h2></li>

</ul>

<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0){?>
<div class="scroll-content-top" style="display: none;"><i class="fa fa-chevron-up"></i></div>
<div class="scroll-content-bottom"><i class="fa fa-chevron-down"></i></div>
<?php }?>

</div>


<div id="right" class="widget_right">
<div  class="right-inner" style="padding-left: 15px;padding-bottom: 20px;">


















<div class="div-extra"></div>


<script type="text/javascript">
$(document).ready(function() {


	pageids=0;
	pageids=<?php echo $pageid;?>;

	if(pageids >0)
	{
		$(".div_second").hide("slow");
		$("#_"+pageids).slideDown("slow");
		$("#1aa1_"+pageids).attr("src","images/minus_menu.png");
	}





	  $(function(){
          this. onclick = function(event)
          {
        		if($('#typedata').val() ==1)
        		return;


           if (!event)
           event = window.event;
           var target = (event.target) ? event.target : event.srcElement;

		   var targetid=target.id;
		   var currentidarray=targetid.split('_');
		   var currentid=currentidarray[1];


		   if(currentidarray[0] == '1a1' || currentidarray[0] == '1aa1' || currentidarray[0] == '1aaa1' || currentidarray[0] == '1aaaa1')
		   {
			   if($("#1aa1_"+currentid).attr("src") == "images/minus_menu.png")
			   {
				   $("#_"+currentid).slideUp("slow");
				   $("#1aa1_"+currentid).attr("src","images/plus_menu.png");
			   }
			   else
			   {
			   	   $(".div_second").slideUp("slow");
			   	   $("#_"+currentid).slideDown("slow");
			   	   $(".down_menu").attr("src","images/plus_menu.png");
			       $("#1aa1_"+currentid).attr("src","images/minus_menu.png");
			   }
		   }
	}
});


	  AdjustPageWidth();

	  AdjustLabelHeader();


	  $(window).resize(function()
	  {
		  AdjustLabelHeader();

		  ChangeMenuStyle(2);
	  });

});


function AdjustLabelHeader()
{
/*
	  if($(window).width() < 1280)
	  {
		  $('.sub_menu_main').css("position",'relative');
		  $('.sub_menu_main').css("top",'0px');
		  $('.div-extra').hide();


		  $('.header-top').css("position",'relative');
		  $('.header-top').css("top",'0px');

		  $('.header-bottom').css("margin-top",'0px');


		  $('.widget_left').css("position",'absolute');
	  }
	  else*/
	  {


		  if($(window).width() > 479)
		  {
			  $('.sub_menu_main').css("position",'fixed');
			  $('.sub_menu_main').css("top",'60px');
		  }
		  else
		  {
			  $('.sub_menu_main').css("position",'relative');
			  $('.sub_menu_main').css("top",'0px');
		  }


		  $('.div-extra').show();

		  $('.header-top').css("position",'fixed');

		  $('.widget_left').css("position",'fixed');


		  $('.header-bottom').css("margin-top",'60px');

	  }





}

function AdjustPageWidth()
{
	type 				= $('#typedata').val();

	full_window_width	= parseFloat($(window).width());

	if(type == 2)
	{
		if($(window).width() > 479)
		$('#left').css("width",'255px');
		else
		$('#left').css("width",'200px');


	}
	else
	$('#left').css("width",'0px');


	if(type == 2)
	{
		left_menu_width 		= parseFloat($('#left').css("width"));

		left_width_percentage   = Math.round(((left_menu_width*100)/full_window_width)*100)/100;

		balance_width 			= 100-left_width_percentage;
	}
	else
	{
		left_menu_width 		= 0;

		left_width_percentage 	= 1;

		balance_width 			= 100-left_width_percentage;
	}

	/*
	if(full_window_width < 1192)
	{
		if($('.narrow_menu').length >0)
		{
			$('#left').css("width",'60px');
			$('#right').css("left",'60px');
			$('#right').css("width",'1100px');
		}
		else
		{
			$('#left').css("width",'255px');
			$('#right').css("width",'950px');
			$('#right').css("left",'240px');
		}
	}
	else
	{
		$('#right').css("width",balance_width+'%');
		$('#right').css("left",(left_width_percentage-1.2)+'%');
	}
	*/


	if(full_window_width < 1192 && type == 2)
	{
		$('#right').css("width",'950px');

		if($(window).width() > 479)
		$('#right').css("left",'240px');
		else
		$('#right').css("left",'200px');

	}
	else
	{
		$('#right').css("width",balance_width+'%');
		$('#right').css("left",(left_width_percentage-1.2)+'%');
	}
}


$(".scroll-content-top").click(function() {

    $('.widget_left').animate({scrollTop: parseInt($('.widget_left').scrollTop())-400},'100');
});


$(".scroll-content-bottom").click(function() {

    $('.widget_left').animate({scrollTop: parseInt($('.widget_left').scrollTop())+400},'100');
});


var previousscrolltop = 0;

$('.widget_left').on('scroll', function() {

	scrolltop=$('.widget_left').scrollTop();

	if(scrolltop > previousscrolltop)
	$(".scroll-content-top").show();
	else
	{
		if($(window).width() > 479)
		$('.scroll-content-top').css("left",'150px');
		else
		$(".scroll-content-top").css("left","140px");

		$(".scroll-content-bottom").show();
	}


    if(scrolltop + $('.widget_left').innerHeight() >= document.getElementById("left").scrollHeight)
    {
   		$(".scroll-content-bottom").hide();

		if($(window).width() > 479)
		$('.scroll-content-top').css("left",'180px');
		else
		$(".scroll-content-top").css("left","160px");

    }

	if(scrolltop ==0)
	{
		$(".scroll-content-top").hide();
		$(".scroll-content-bottom").show();
	}

    previousscrolltop=scrolltop;
});




function ChangeMenuStyle(type)
{
	$('#typedata').val(type);

	if(type ==1)
	{

		$('.showhide1').hide();
		$('.showhide2').show();


/*
		$('#left').addClass('narrow_menu');
		$('#left').removeClass('widget_left');
		$('#left').css("width",'80px');

		$('.narrow_menu li').addClass('sub_div_li');

		$('.div_first').addClass('div_narrow_first');
		$('.div_second').hide();



		$('.down_menu').css("display","none");
		$('.div_first').css("margin-left","20px");
		$('.div_first').css("width","45px");




	  	  $(".div_narrow_first").hover(function()
	  	  {
			  idvalue=this.id;
			  idvalue_array=idvalue.split('_');

			  topvalue=$('#'+idvalue).offset().top;


		      $('.div_narrow_second').html($('#_'+idvalue_array[1]).html());



		      full_window_height	= parseFloat($(window).height());
		      sub_menu_height 		= parseFloat($('.div_narrow_second').outerHeight());
		      substract_height		= full_window_height - topvalue;

		      if(substract_height >= sub_menu_height)             // Sub menu display from top to bottom
	    	  $('.div_narrow_second').css('top',topvalue+'px');
		      else												  // Sub menu display from bottom to top
		      {
					new_top = topvalue - sub_menu_height + 50;

		    	    $('.div_narrow_second').css("top",new_top+'px');
		      }


		      $('.div_narrow_second').show();


		  });

	  	  $(".div_narrow_second").hover(function()
	  	  {


	  	  }, function(){
	  		$('.div_narrow_second').hide();
	  		$('.div_narrow_second').html('');
	  	  });


	  	  $(".one_row").hover(function()
	  	  {
  		  		$('.div_narrow_second').hide();
	  	  		$('.div_narrow_second').html('');
	  	  });
*/


	}
	else
	{
		$('.showhide1').show();
		$('.showhide2').hide();


		//$('.narrow_menu li').removeClass('sub_div_li');



		//$('.div_narrow_second').hide();

		//$('.div_narrow_first').unbind("hover");



		//$('#left').removeClass('narrow_menu');

		//$('#left').css("width",'255px');
		//$('#left').addClass('widget_left');

		//$('.div_first').removeClass('div_narrow_first');

		//$('.down_menu').css("display","block");
		//$('.div_first').css("margin-left","0px");
		//$('.div_first').css("width","100%");


		if(pageids >0)
		{
			$(".div_second").hide();
			$("#_"+pageids).show();
			$("#1aa1_"+pageids).attr("src","images/minus_menu.png");
		}

	}

  	AdjustPageWidth();
}
</script>
