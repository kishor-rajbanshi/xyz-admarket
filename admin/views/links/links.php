<?php
$from=$this->get_variable('from');
$uid=$this->get_variable('uid');
$id=$this->get_variable('id');

$cpc_enabled=$this->get_addon_status('cpc_enabled');
$html_enabled=$this->get_addon_status('html_enabled');
$withdrawal_enabled=$this->get_addon_status('withdrawal_enabled');
$language_enabled=Configuration::get_instance()->read('language_enabled');
$subadmin_enabled=$this->get_addon_status('subadmin_enabled');
$referral_enabled=$this->get_addon_status('referral_enabled');
$feedads_enabled=$this->get_addon_status('feed-ads_enabled');

if($feedads_enabled == 1)
$demand_feed_ads = Configuration::get_instance()->read('demand_feed_ads');
else
$demand_feed_ads = 0;
$dspconnector_enabled=$this->get_addon_status('dsp-connector_enabled');
$sspconnector_enabled=$this->get_addon_status('ssp-connector_enabled');
?>
<?php if($from ==1){?>
<table class="head_links">
<tr>
<td><a href="<?php echo $this->make_url("index/control-panel");?>"><i class="fa fa-home fa-2x"></i></a></td>
</tr>
</table>
<?php }?>
<?php if($from ==2){?>
<table class="head_links">
<tr>
<td><a class="link_button" href="<?php echo $this->make_url("user/list");?>"><?php echo $this->get_label('manage users');?></a></td>
</tr>
</table>
<?php }?>
<?php if($from ==3){?>
<table class="head_links">
<tr>
<td>
<a class="link_button" href="<?php echo $this->make_url("user/list");?>"><?php echo $this->get_label('manage users');?></a>
<a class="link_button" href="<?php echo $this->make_url("user/profile/".$uid."/0");?>"><?php echo $this->get_label('profile');?></a>
</td>
</tr>
</table>
<?php }?>
<?php if($from ==4){?>
<table class="head_links">
<tr>
<td>
<a class="link_button" href="<?php echo $this->make_url("user/list");?>"><?php echo $this->get_label('manage users');?></a>
<a class="link_button" href="<?php echo $this->make_url("user/profile/".$uid."/0");?>"><?php echo $this->get_label('profile');?></a>
</td>
</tr>
</table>
<?php }?>
<?php if($from ==5){?>
<table class="head_links">
<tr>
<td>
<a class="link_button" href="<?php echo $this->make_url("user/list");?>"><?php echo $this->get_label('manage users');?></a>
<a class="link_button" href="<?php echo $this->make_url("user/profile/".$uid."/0");?>"><?php echo $this->get_label('profile');?></a>
</td>
</tr>
</table>
<?php }?>
<?php if($from ==6){?>
<table class="head_links">
<tr>
<td>
<a class="link_button" href="<?php echo $this->make_url("user/list");?>"><?php echo $this->get_label('manage users');?></a>
<a class="link_button" href="<?php echo $this->make_url("user/profile/".$uid."/0");?>"><?php echo $this->get_label('profile');?></a>
</td>
</tr>
</table>
<?php }?>
<?php if($from ==7){?>
<table class="head_links">
<tr>
<td>
<a class="link_button" href="<?php echo $this->make_url("user/list");?>"><?php echo $this->get_label('manage users');?></a>
<a class="link_button" href="<?php echo $this->make_url("user/profile/".$uid."/0");?>"><?php echo $this->get_label('profile');?></a>
</td>
</tr>
</table>
<?php }?>
<?php if($from ==8){?>
<table class="head_links">
<tr>
<td><a class="link_button" href="<?php echo $this->make_url("user/list");?>"><?php echo $this->get_label('manage users');?></a></td>
</tr>
</table>
<?php }?>
<?php if($from ==9){?>
<table class="head_links">
<tr>
<td><a class="link_button" href="<?php echo $this->make_url("ad/list");?>"><?php echo $this->get_label('manageads');?></a></td>
</tr>
</table>
<?php }?>
<?php if($from ==10){?>
<table class="head_links">
<tr>
<td><a class="link_button" href="<?php echo $this->make_url("ad/list");?>"><?php echo $this->get_label('manageads');?></a></td>
</tr>
</table>
<?php }?>
<?php if($from ==11){?>
<table class="head_links">
<tr>
<td>
<a class="link_button" href="<?php echo $this->make_url("user/list");?>"><?php echo $this->get_label('manage users');?></a>
<a class="link_button" href="<?php echo $this->make_url("ad/list");?>"><?php echo $this->get_label('manageads');?></a></td>
</tr>
</table>
<?php }?>
<?php if($from ==12){?>
<table class="head_links">
<tr>
<td>
<a class="link_button" href="<?php echo $this->make_url("user/list");?>"><?php echo $this->get_label('manage users');?></a>
<a class="link_button" href="<?php echo $this->make_url("adunit/manage_user_adcode");?>"><?php echo $this->get_label('manage publisher adunits');?></a>
</td>
</tr>
</table>
<?php }?>
<?php if($from ==13){?>
<table class="head_links">
<tr>
<td>
<a class="link_button" href="<?php echo $this->make_url("user/list");?>"><?php echo $this->get_label('manage users');?></a>
<a class="link_button" href="<?php echo $this->make_url("ad/list");?>"><?php echo $this->get_label('manageads');?></a>
<a class="link_button" href="<?php echo $this->make_url("user/profile/".$uid."/0");?>"><?php echo $this->get_label('profile');?></a>
</td>
</tr>
</table>
<?php }?>
<?php if($from ==14){?>
<table class="head_links">
<tr>
<td>
<a class="link_button" href="<?php echo $this->make_url("ad/list_default");?>"><?php echo $this->get_label('manage default ads');?></a>
<a class="link_button" href="<?php echo $this->make_url("ad/create_default");?>"><?php echo $this->get_label('create default ad');?></a>
</td>
</tr>
</table>
<?php }?>
<?php if($from ==15){?>
<table class="head_links">
<tr>
<td>
<a class="link_button" href="<?php echo $this->make_url("ad/create_default");?>"><?php echo $this->get_label('create default ad');?></a>
</td>
</tr>
</table>
<?php }?>
<?php if($from ==16){?>
<table class="head_links">
<tr>
<td>
<a class="link_button" href="<?php echo $this->make_url("ad/list_default");?>"><?php echo $this->get_label('manage default ads');?></a>
</td>
</tr>
</table>
<?php }?>
<?php if($from ==17){?>
<table class="head_links">
<tr>
<td>
<a class="link_button" href="<?php echo $this->make_url("ad/list_default");?>"><?php echo $this->get_label('manage default ads');?></a>
<a class="link_button" href="<?php echo $this->make_url("ad/create_default");?>"><?php echo $this->get_label('create default ad');?></a>
</td>
</tr>
</table>
<?php }?>
<?php if($from ==18){?>
<table class="head_links">
<tr>
<td>
<a class="link_button" href="<?php echo $this->make_url("adblock/create");?>"><?php echo $this->get_label('create adblock');?></a>
<a class="link_button" href="<?php echo $this->make_url("adblock/manage");?>"><?php echo $this->get_label('manage adblock');?></a>
<a class="link_button" href="<?php echo $this->make_url("credit/manage");?>"><?php echo $this->get_label('manage credit text');?></a>
</td>
</tr>
</table>
<?php }?>
<?php if($from ==19){?>
<table class="head_links">
<tr>
<td>
<a class="link_button" href="<?php echo $this->make_url("banner_dimension/create");?>"><?php echo $this->get_label('new banner dimension');?></a>
<a class="link_button" href="<?php echo $this->make_url("banner_dimension/list");?>"><?php echo $this->get_label('manage banner dimensions');?></a>
</td>
</tr>
</table>
<?php }?>
<?php if($from ==20){?>
<table class="head_links">
<tr>
<td>
<a class="link_button" href="<?php echo $this->make_url("credit/create");?>"><?php echo $this->get_label('create new credit text');?></a>
<a class="link_button" href="<?php echo $this->make_url("credit/manage");?>"><?php echo $this->get_label('manage credit text');?></a>
<a class="link_button" href="<?php echo $this->make_url("adblock/manage");?>"><?php echo $this->get_label('manage adblock');?></a>
</td>
</tr>
</table>
<?php }?>
<?php if($from ==21){?>
<table class="head_links">
<tr>
<td>
<a class="link_button" href="<?php echo $this->make_url("adunit/create");?>"><?php echo $this->get_label('create');?></a>
<a class="link_button" href="<?php echo $this->make_url("adunit/manage");?>"><?php echo $this->get_label('manage adunits');?></a>
<a class="link_button" href="<?php echo $this->make_url("report/adcodes");?>"><?php echo $this->get_label('adunit statistics');?></a>
</td>
</tr>
</table>
<?php }?>
<?php if($from ==22){?>
<table class="head_links">
<tr>
<td>
<a class="link_button" href="<?php echo $this->make_url("email_template/manage");?>"><?php echo $this->get_label('manage email templates');?></a>
</td>
</tr>
</table>
<?php }?>
<?php if($from ==23){?>
<table class="head_links">
<tr>
<td>
<a class="link_button" href="<?php echo $this->make_url("advertiser/payments");?>"><?php echo $this->get_label('manage payments');?></a>
<?php if($id >0) {?>
<a class="link_button" href="<?php echo $this->make_url("advertiser/payment_details/".$id);?>"><?php echo $this->get_label('payment details');?></a>
<?php }?>
<?php if($uid >0) {?>
<a class="link_button" href="<?php echo $this->make_url("user/profile/".$uid."/0");?>"><?php echo $this->get_label('profile');?></a>
<?php }?>
</td>
</tr>
</table>
<?php }?>
<?php if($from ==24){?>
<table class="head_links">
<tr>
<td>
<a class="link_button" href="<?php echo $this->make_url("user/withdrawal_history");?>"><?php echo $this->get_label('manage withdrawals');?></a>
<?php if($id >0) {?>
<a class="link_button" href="<?php echo $this->make_url("user/withdrawal_details/".$id);?>"><?php echo $this->get_label('withdrawal details');?></a>
<?php }?>
<?php if($uid >0) {?>
<a class="link_button" href="<?php echo $this->make_url("user/profile/".$uid."/0");?>"><?php echo $this->get_label('profile');?></a>
<?php }?>
</td>
</tr>
</table>
<?php }?>
<?php if($from ==25){?>
<table class="head_links">
<tr>
<td>
<a class="link_button" href="<?php echo $this->make_url("report/overall");?>"><?php echo $this->get_label('overall statistics');?></a>
<?php if($cpc_enabled ==1){?>

<a class="link_button" href="<?php echo $this->make_url("report/clicks");?>"><?php echo $this->get_label('click analysis');?></a>
<?php }?>

<a class="link_button" href="<?php echo $this->make_url("report/profit");?>"><?php echo $this->get_label('profit statistics');?></a>

<a class="link_button" href="<?php echo $this->make_url("report/verify");?>"><?php echo $this->get_label('check statistics');?></a>

<a class="link_button" href="<?php echo $this->make_url("report/top_list");?>"><?php echo $this->get_label('toppers list');?></a>
<?php if(Configuration::get_instance()->read('countrywise_data_tracking') ==1){?>

<a class="link_button" href="<?php echo $this->make_url("report/country");?>"><?php echo $this->get_label('countrywise report');?></a>
<?php }?>
</td>
</tr>
</table>
<?php }?>
<?php if($from ==26){?>
<table class="head_links">
<tr>
<td>
<a class="link_button" href="<?php echo $this->make_url("system/testimonial");?>"><?php echo $this->get_label('manage testimonials');?></a>
</td>
</tr>
</table>
<?php }?>
<?php if($from ==27){?>
<table class="head_links">
<tr>
<td>
<a class="link_button" href="<?php echo $this->make_url("system/testimonial");?>"><?php echo $this->get_label('manage testimonials');?></a>
</td>
</tr>
</table>
<?php }?>
<?php if($from ==28){?>
<table class="head_links">
<tr>
<td>
<a class="link_button" href="<?php echo $this->make_url("system/testimonial_create");?>"><?php echo $this->get_label('create testimonial');?></a>
</td>
</tr>
</table>
<?php }?>
<?php if($from ==29){?>
<table class="head_links">
<tr>
<td>
<a class="link_button" href="<?php echo $this->make_url("adunit/manage_user_adcode");?>"><?php echo $this->get_label('manage publisher adcode');?></a>
<a class="link_button" href="<?php echo $this->make_url("report/adcodes_publisher");?>"><?php echo $this->get_label('publisher adcode statistics');?></a>
</td>
</tr>
</table>
<?php }?>






<?php if($html_enabled ==1 && $from ==35){?>
<table class="head_links">
<tr>
<td>
<a class="link_button" href="<?php echo $this->make_url("dispatch/html/create");?>"><?php echo $this->get_label('create html ad');?></a>
<a class="link_button" href="<?php echo $this->make_url("dispatch/html/statistics");?>"><?php echo $this->get_label('html statistics');?></a>
<a class="link_button" href="<?php echo $this->make_url("dispatch/html/overall");?>"><?php echo $this->get_label('html overall');?></a>
</td>
</tr>
</table>
<?php }?>

<?php if($html_enabled ==1 && $from ==36){?>
<table class="head_links">
<tr>
<td>
<a class="link_button" href="<?php echo $this->make_url("dispatch/html/manage");?>"><?php echo $this->get_label('manage html ads');?></a>
<a class="link_button" href="<?php echo $this->make_url("dispatch/html/statistics");?>"><?php echo $this->get_label('html statistics');?></a>
<a class="link_button" href="<?php echo $this->make_url("dispatch/html/overall");?>"><?php echo $this->get_label('html overall');?></a>
</td>
</tr>
</table>
<?php }?>


<?php if($html_enabled ==1 && $from ==37){?>
<table class="head_links">
<tr>
<td>
<a class="link_button" href="<?php echo $this->make_url("dispatch/html/create");?>"><?php echo $this->get_label('create html ad');?></a>
<a class="link_button" href="<?php echo $this->make_url("dispatch/html/manage");?>"><?php echo $this->get_label('manage html ads');?></a>
<a class="link_button" href="<?php echo $this->make_url("dispatch/html/statistics");?>"><?php echo $this->get_label('html statistics');?></a>
<a class="link_button" href="<?php echo $this->make_url("dispatch/html/overall");?>"><?php echo $this->get_label('html overall');?></a>
</td>
</tr>
</table>
<?php }?>


<?php if($withdrawal_enabled ==1 && $from ==38){?>
<table class="head_links">
<tr>
<td>
<a class="link_button" href="<?php echo $this->make_url("dispatch/withdrawal/add");?>"><?php echo $this->get_label('add withdrawal option');?></a>
</td>
</tr>
</table>
<?php }?>

<?php if($withdrawal_enabled ==1 && $from ==39){?>
<table class="head_links">
<tr>
<td>
<a class="link_button" href="<?php echo $this->make_url("dispatch/withdrawal/manage");?>"><?php echo $this->get_label('manage withdrawal option');?></a>
</td>
</tr>
</table>
<?php }?>



<?php if($language_enabled ==1 && $from ==40){?>
<table class="head_links">
<tr><td><a class="link_button" href="<?php echo $this->make_url("locale/manage");?>"><?php echo $this->get_label('manage locale');?></a></td></tr>
</table>
<?php }?>


<?php if($language_enabled ==1 && $from ==41){?>
<table class="head_links">
<tr><td><a class="link_button" href="<?php echo $this->make_url("locale/add");?>"><?php echo $this->get_label('add locale');?></a></td></tr>
</table>
<?php }?>


<?php if($language_enabled ==1 && $from ==42){?>
<table class="head_links">
<tr><td>
<a class="link_button" href="<?php echo $this->make_url("locale/add");?>"><?php echo $this->get_label('add locale');?></a>
<a class="link_button" href="<?php echo $this->make_url("locale/manage");?>"><?php echo $this->get_label('manage locale');?></a>
</td></tr>
</table>
<?php }?>


<?php if($subadmin_enabled ==1 && $from ==43){?>
<table class="head_links">
<tr><td>
<a class="link_button" href="<?php echo $this->make_url("dispatch/subadmin/add");?>"><?php echo $this->get_label('add subadmin');?></a>
</td></tr>
</table>
<?php }?>

<?php if($subadmin_enabled ==1 && $from ==44){?>
<table class="head_links">
<tr><td>
<a class="link_button" href="<?php echo $this->make_url("dispatch/subadmin/manage");?>"><?php echo $this->get_label('manage subadmin');?></a>
</td></tr>
</table>
<?php }?>


<?php if($subadmin_enabled ==1 && $from ==45){?>
<table class="head_links">
<tr><td>
<a class="link_button" href="<?php echo $this->make_url("dispatch/subadmin/add");?>"><?php echo $this->get_label('add subadmin');?></a>
<a class="link_button" href="<?php echo $this->make_url("dispatch/subadmin/manage");?>"><?php echo $this->get_label('manage subadmin');?></a>
</td></tr>
</table>
<?php }?>



<?php if($from ==46){?>
<table class="head_links">
<tr>
<td>
<a class="link_button" href="<?php echo $this->make_url("system/add");?>"><?php echo $this->get_label('create new page');?></a>
</td>
</tr>
</table>
<?php }?>

<?php if($from ==47){?>
<table class="head_links">
<tr>
<td>
<a class="link_button" href="<?php echo $this->make_url("system/manage");?>"><?php echo $this->get_label('manage pages');?></a>
</td>
</tr>
</table>
<?php }?>
<?php if($from ==48){?>
<table class="head_links">
<tr>
<td>
<a class="link_button" href="<?php echo $this->make_url("system/manage");?>"><?php echo $this->get_label('manage pages');?></a>
<a class="link_button" href="<?php echo $this->make_url("system/add");?>"><?php echo $this->get_label('create new page');?></a>
</td>
</tr>
</table>
<?php }?>


<?php
$category_enabled=$this->get_addon_status('category-targeting_enabled');

if($category_enabled ==1){?>


<?php if($from ==30){?>
<table class="head_links">
<tr>
<td>
<a class="link_button" href="<?php echo $this->make_url("dispatch/category_targeting/1");?>"><?php echo $this->get_label('add category');?></a>
</td>
</tr>
</table>
<?php }?>

<?php if($from ==31){?>
<table class="head_links">
<tr>
<td>
<a class="link_button" href="<?php echo $this->make_url("dispatch/category_targeting/2");?>"><?php echo $this->get_label('manage categories');?></a>
</td>
</tr>
</table>
<?php }?>

<?php if($from ==32){?>
<table class="head_links">
<tr>
<td>
<a class="link_button" href="<?php echo $this->make_url("dispatch/category_targeting/5");?>"><?php echo $this->get_label('add site');?></a>
<a class="link_button" href="<?php echo $this->make_url("dispatch/category_targeting/21")?>"><?php echo $this->get_label('site statistics admin');?></a>
<a class="link_button" href="<?php echo $this->make_url("dispatch/category_targeting/23")?>"><?php echo $this->get_label('site statistics publisher');?></a>
</td>
</tr>
</table>
<?php }?>

<?php if($from ==33){?>
<table class="head_links">
<tr>
<td>
<a class="link_button" href="<?php echo $this->make_url("dispatch/category_targeting/6");?>"><?php echo $this->get_label('manage sites');?></a>
<a class="link_button" href="<?php echo $this->make_url("dispatch/category_targeting/21")?>"><?php echo $this->get_label('site statistics admin');?></a>
<a class="link_button" href="<?php echo $this->make_url("dispatch/category_targeting/23")?>"><?php echo $this->get_label('site statistics publisher');?></a>
</td>
</tr>
</table>
<?php }?>


<?php if($from ==34){?>
<table class="head_links">
<tr>
<td>
<a class="link_button" href="<?php echo $this->make_url("dispatch/category_targeting/5");?>"><?php echo $this->get_label('add site');?></a>
<a class="link_button" href="<?php echo $this->make_url("dispatch/category_targeting/6");?>"><?php echo $this->get_label('manage sites');?></a>
<a class="link_button" href="<?php echo $this->make_url("dispatch/category_targeting/21")?>"><?php echo $this->get_label('site statistics admin');?></a>
<a class="link_button" href="<?php echo $this->make_url("dispatch/category_targeting/23")?>"><?php echo $this->get_label('site statistics publisher');?></a>
</td>
</tr>
</table>
<?php }?>

<?php }?>
<?php if($from ==54){?>
<table class="head_links">
<tr>
<td>
<a class="link_button" href="<?php echo $this->make_url("locale/manage");?>"><?php echo $this->get_label('manage locale');?></a>
</td>
</tr>
</table>
<?php }?>
<?php if($from ==55){?>
<table class="head_links">
<tr>
<td>
<a class="link_button" href="<?php echo $this->make_url("locale/add");?>"><?php echo $this->get_label('add locale');?></a>
</td>
</tr>
</table>
<?php }?>
<?php if($from ==56){?>
<table class="head_links">
<tr>
<td>
<a class="link_button" href="<?php echo $this->make_url("locale/add");?>"><?php echo $this->get_label('add locale');?></a>
<a class="link_button" href="<?php echo $this->make_url("locale/manage");?>"><?php echo $this->get_label('manage locale');?></a>
</td>
</tr>
</table>
<?php }?>
<?php if($from ==57){?>
<table class="head_links">
<tr>
<td>
<a class="link_button" href="<?php echo $this->make_url("addon/manage");?>"><?php echo $this->get_label('manage addons');?></a>
</td>
</tr>
</table>
<?php }?>


<?php if($from ==58){?>
<table class="head_links">
<tr>
<td>
<a class="link_button" href="<?php echo $this->make_url("system/meta_data"); ?>"><?php echo $this->get_label("meta data");?></a>
<a class="link_button" href="<?php echo $this->make_url("system/about_us"); ?>"><?php echo $this->get_label("about us");?></a>
<a class="link_button" href="<?php echo $this->make_url("system/terms"); ?>"><?php echo $this->get_label("terms & conditions");?></a>
<a class="link_button" href="<?php echo $this->make_url("system/seo_url"); ?>"><?php echo $this->get_label("manage seo url");?></a>
</td>
</tr>
</table>
<?php }?>

<?php if($from ==59){?>
<table class="head_links">
<tr>
<td>
<a class="link_button" href="<?php echo $this->make_url("system/add_banner"); ?>"><?php echo $this->get_label("create banner");?></a>
</td>
</tr>
</table>
<?php }?>
<?php if($from ==60){?>
<table class="head_links">
<tr>
<td>
<a class="link_button" href="<?php echo $this->make_url("system/add_notifications"); ?>"><?php echo $this->get_label("new notification");?></a>
<a class="link_button" href="<?php echo $this->make_url("system/notifications"); ?>"><?php echo $this->get_label("manage notifications");?></a>
</td>
</tr>
</table>
<?php }?>

<?php if($from ==65){?>
<table class="head_links">
<tr>
<td>
<a class="link_button" href="<?php echo $this->make_url("system/create_rule");?>"><?php echo $this->get_label('create tax rule');?></a>
<a class="link_button" href="<?php echo $this->make_url("system/tax_rules");?>"><?php echo $this->get_label('manage tax rules');?></a>
</td>
</tr>
</table>
<?php }?>

<?php if($referral_enabled ==1){?>

<?php if($from ==63){?>
<table class="head_links">
<tr>
<td>
<a class="link_button" href="<?php echo $this->make_url("dispatch/referral/1");?>"><?php echo $this->get_label('referral banners');?></a>
<a class="link_button" href="<?php echo $this->make_url("dispatch/referral/2");?>"><?php echo $this->get_label('referral reports');?></a>
<a class="link_button" href="<?php echo $this->make_url("dispatch/referral/3");?>"><?php echo $this->get_label('referral visits');?></a>
<a class="link_button" href="<?php echo $this->make_url("dispatch/referral/4");?>"><?php echo $this->get_label('referral top');?></a>
</td>
</tr>
</table>
<?php }}?>


<?php if($feedads_enabled == 1 && $demand_feed_ads == 1 && $from == 66){?>
<table class="head_links">
<tr>
<td>
<a class="link_button" href="<?php echo $this->make_url("dispatch/feed-ads/create");?>"><?php echo $this->get_label('create feed ad');?></a>
<a class="link_button" href="<?php echo $this->make_url("dispatch/feed-ads/statistics");?>"><?php echo $this->get_label('feed ads statistics');?></a>
<a class="link_button" href="<?php echo $this->make_url("dispatch/feed-ads/overall");?>"><?php echo $this->get_label('feed ads overall');?></a>
</td>
</tr>
</table>
<?php }?>

<?php if($feedads_enabled == 1 && $demand_feed_ads == 1 && $from == 67){?>
<table class="head_links">
<tr>
<td>
<a class="link_button" href="<?php echo $this->make_url("dispatch/feed-ads/manage");?>"><?php echo $this->get_label('manage feed ads');?></a>
<a class="link_button" href="<?php echo $this->make_url("dispatch/feed-ads/statistics");?>"><?php echo $this->get_label('feed ads statistics');?></a>
<a class="link_button" href="<?php echo $this->make_url("dispatch/feed-ads/overall");?>"><?php echo $this->get_label('feed ads overall');?></a>
</td>
</tr>
</table>
<?php }?>


<?php if($feedads_enabled == 1 && $demand_feed_ads == 1 && $from == 68){?>
<table class="head_links">
<tr>
<td>
<a class="link_button" href="<?php echo $this->make_url("dispatch/feed-ads/create");?>"><?php echo $this->get_label('create feed ad');?></a>
<a class="link_button" href="<?php echo $this->make_url("dispatch/feed-ads/manage");?>"><?php echo $this->get_label('manage feed ads');?></a>
<a class="link_button" href="<?php echo $this->make_url("dispatch/feed-ads/statistics");?>"><?php echo $this->get_label('feed ads statistics');?></a>
<a class="link_button" href="<?php echo $this->make_url("dispatch/feed-ads/overall");?>"><?php echo $this->get_label('feed ads overall');?></a>
</td>
</tr>
</table>
<?php }?>



<?php if($from == 69){?>
<table class="head_links">
<tr>
<td>
<a class="link_button" href="<?php echo $this->make_url("adblock/add_external_font");?>"><?php echo $this->get_label('add font');?></a>
<a class="link_button" href="<?php echo $this->make_url("adblock/external_fonts");?>"><?php echo $this->get_label('external fonts');?></a>
</td>
</tr>
</table>
<?php }?>
<?php if($from ==49){?>
<table class="head_links">
<tr>
<td>
<a class="link_button" href="<?php echo $this->make_url("system/add_faq");?>"><?php echo $this->get_label('create faq');?></a>
</td>
</tr>
</table>
<?php }?>

<?php if($from ==50){?>
<table class="head_links">
<tr>
<td>
<a class="link_button" href="<?php echo $this->make_url("system/manage_faq");?>"><?php echo $this->get_label('manage faq');?></a>
</td>
</tr>
</table>
<?php }?>


<?php if($dspconnector_enabled == 1 && $from == 70){?>
<table class="head_links">
<tr>
<td>
<a class="link_button" href="<?php echo $this->make_url("dispatch/dsp-connector/create");?>"><?php echo $this->get_label('create dsp ad');?></a>
<a class="link_button" href="<?php echo $this->make_url("dispatch/dsp-connector/statistics");?>"><?php echo $this->get_label('dsp ads statistics');?></a>
<a class="link_button" href="<?php echo $this->make_url("dispatch/dsp-connector/overall");?>"><?php echo $this->get_label('dsp ads overall');?></a>
</td>
</tr>
</table>
<?php }?>

<?php if($dspconnector_enabled ==1 && $from ==71){?>
<table class="head_links">
<tr>
<td>
<a class="link_button" href="<?php echo $this->make_url("dispatch/dsp-connector/manage");?>"><?php echo $this->get_label('manage dsp ads');?></a>
<a class="link_button" href="<?php echo $this->make_url("dispatch/dsp-connector/statistics");?>"><?php echo $this->get_label('dsp ads statistics');?></a>
<a class="link_button" href="<?php echo $this->make_url("dispatch/dsp-connector/overall");?>"><?php echo $this->get_label('dsp ads overall');?></a>
</td>
</tr>
</table>
<?php }?>


<?php if($dspconnector_enabled ==1 && $from ==72){?>
<table class="head_links">
<tr>
<td>
<a class="link_button" href="<?php echo $this->make_url("dispatch/dsp-connector/create");?>"><?php echo $this->get_label('create dsp ad');?></a>
<a class="link_button" href="<?php echo $this->make_url("dispatch/dsp-connector/manage");?>"><?php echo $this->get_label('manage dsp ads');?></a>
<a class="link_button" href="<?php echo $this->make_url("dispatch/dsp-connector/statistics");?>"><?php echo $this->get_label('dsp ads statistics');?></a>
<a class="link_button" href="<?php echo $this->make_url("dispatch/dsp-connector/overall");?>"><?php echo $this->get_label('dsp ads overall');?></a>
</td>
</tr>
</table>
<?php }?>



<?php if($sspconnector_enabled == 1 && $from == 73){?>
<table class="head_links">
<tr>
<td>
<a class="link_button" href="<?php echo $this->make_url("dispatch/ssp-connector/create");?>"><?php echo $this->get_label('create ssp endpoint');?></a>
<a class="link_button" href="<?php echo $this->make_url("dispatch/ssp-connector/statistics");?>"><?php echo $this->get_label('ssp endpoints statistics');?></a>
<a class="link_button" href="<?php echo $this->make_url("dispatch/ssp-connector/overall");?>"><?php echo $this->get_label('ssp endpoints overall');?></a>
</td>
</tr>
</table>
<?php }?>

<?php if($sspconnector_enabled ==1 && $from ==74){?>
<table class="head_links">
<tr>
<td>
<a class="link_button" href="<?php echo $this->make_url("dispatch/ssp-connector/manage");?>"><?php echo $this->get_label('manage ssp endpoints');?></a>
<a class="link_button" href="<?php echo $this->make_url("dispatch/ssp-connector/statistics");?>"><?php echo $this->get_label('ssp endpoints statistics');?></a>
<a class="link_button" href="<?php echo $this->make_url("dispatch/ssp-connector/overall");?>"><?php echo $this->get_label('ssp endpoints overall');?></a>
</td>
</tr>
</table>
<?php }?>


<?php if($sspconnector_enabled ==1 && $from ==75){?>
<table class="head_links">
<tr>
<td>
<a class="link_button" href="<?php echo $this->make_url("dispatch/ssp-connector/create");?>"><?php echo $this->get_label('create ssp endpoint');?></a>
<a class="link_button" href="<?php echo $this->make_url("dispatch/ssp-connector/manage");?>"><?php echo $this->get_label('manage ssp endpoints');?></a>
<a class="link_button" href="<?php echo $this->make_url("dispatch/ssp-connector/statistics");?>"><?php echo $this->get_label('ssp endpoints statistics');?></a>
<a class="link_button" href="<?php echo $this->make_url("dispatch/ssp-connector/overall");?>"><?php echo $this->get_label('ssp endpoints overall');?></a>
</td>
</tr>
</table>
<?php }?>



