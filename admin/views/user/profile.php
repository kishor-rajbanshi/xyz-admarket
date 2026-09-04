<?php 
$this->dispatch("layout/header/17");

$res=$this->get_result('res');
$value1=$res[0];
$country=$this->get_variable('country');
$adv_status=$value1['adv_status'];
$pub_status=$value1['pub_status'];
$uid=$value1['id'];


$tab=$this->get_variable('tab');
$min_balance=Configuration::get_instance()->read('min_balance_for_publisher');


$adv_balance=$value1['adv_account_balance'];
$pub_balance=$value1['pub_account_balance'];

$pfsum=$this->get_variable('pfsum');


$pub_balance=$pub_balance-$pfsum;


if($pub_balance < 0)
$pub_balance=0;


$special_flag=0;

if(($adv_status !=-2 && $adv_status !=-3 && ($pub_status ==-2 || $pub_status ==-3)) || ($pub_status !=-2 && $pub_status !=-3 && ($adv_status ==-2 || $adv_status ==-3)))
{
	$special_flag=1;	
	?>
<style type="text/css">
	.account-info table td
	{
		height:46px;
	}	
</style>
<?php }else if(($adv_status ==-2 || $adv_status ==-3) && ($pub_status ==-2 || $pub_status ==-3))
{
	$special_flag=1;	
	?>
<style type="text/css">
	.account-info table td
	{
		height:58px;
	}	
</style>
<?php }




$referral_enabled=$this->get_variable('referral_enabled');

$referral_balance=0;
if($referral_enabled ==1)
{
	$referral_balance=$value1['referral_balance'];

	if($referral_balance < 0)
    $referral_balance=0;
    

	$ref_advertisers=$this->get_referred_users($uid,1);
	$ref_publishers=$this->get_referred_users($uid,2);    
  
	
	
	if($referral_balance >0 && ($ref_advertisers >0 || $ref_publishers >0))
	$height='45px';
	else if($referral_balance >0 || $ref_advertisers >0 || $ref_publishers >0)
	$height='38px';
	else
	$height='35px';
	
?>	
	<style type="text/css">
	.contact-info table td
	{
		height:<?php echo $height;?>;
	}
	
	<?php if($special_flag ==0 && ($referral_balance >0 || $ref_advertisers >0 || $ref_publishers >0)){?>
	.account-info table td
	{
		height:35px;
	}	
	<?php }else if($special_flag ==1 && ($referral_balance >0 || $ref_advertisers >0 || $ref_publishers >0)){?>
	.account-info table td
	{
		height:40px;
	}		
	<?php }?>
	</style>
<?php 	
}




$ut=$this->get_variable("ut");
$dur=$this->get_variable("dur");
$tp=$this->get_variable("tp");
$st=$this->get_variable("st");
$pt=$this->get_variable("pt");
$pts=$this->get_variable("pts");
$wt=$this->get_variable("wt");
$wts=$this->get_variable("wts");
$pg=$this->get_variable("pg");
$ppg=$this->get_variable("ppg");
$wpg=$this->get_variable("wpg");
$wty=$this->get_variable("wty");
$adpricing=$this->get_variable("adpricing");



if($this->get_addon_status('subadmin_enabled') ==1 && isset($GLOBALS['privilege']))
$privilege=$GLOBALS['privilege'];
else
$privilege=array();
?>

<div class="sub_menu_main"><?php echo $this->get_label('user profile');?></div>


<?php $this->dispatch("links/links/2");?>

<div class="inner-box">

<table style="width: 100%;" cellpadding="0" cellspacing="0">
<tr ><td colspan="4" >
<div class="profile_div" style="min-height: 290px;">


<div class="profile_details account-info" style="margin-bottom: 10px;float: left;">
<table style="width: 100%;">

<tr class="profile_heading"><td colspan="8"><?php echo $this->get_label('account details');?>   

<span style="float: right;">
<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0){?>
<?php if($adv_status !=-2) {?><a href="<?php echo $this->make_url("advertiser/add_fund/".$uid);?>"><i class="fa fa-money fund-icon" title="<?php echo $this->get_label('add funds');?>"></i></a><?php }?> 
 
<a href="<?php echo $this->make_url("user/mail/".$uid."/2");?>"><i class="fa fa-envelope mail-icon" title="<?php echo $this->get_label('mail');?>"></i></a> 

<a href="<?php echo $this->make_url("user/change_status/".$uid."/2");?>"><i class="fa fa-cogs settings-icon" title="<?php echo $this->get_label('change status');?>"></i></a> 
 
<a href="<?php echo $this->make_url("user/delete/".$uid);?>" onclick="return confirm('<?php echo $this->get_message('do you really want to delete this user');?>');"><i class="fa fa-trash delete-icon" title="<?php echo $this->get_label('delete');?>"></i></a> 
<?php }?>
</span>
</td></tr>


<tr>
<td style="width: 150px;"><?php echo $this->get_label('username');?></td>
<td style="width: 10px;">:</td>
<td style="width: 200px;"><?php echo $value1['username'];	?>&nbsp;&nbsp;&nbsp;
<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0){?>
<a target="_blank" href="<?php echo $this->make_url("user/login/".$uid);?>"><i class="fa fa-sign-in user-icon" title="<?php echo $this->get_label('login');?>"></i></a>
<?php }?>
</td>
<td>

<?php 
if($referral_enabled ==1)
{
	$refusername="";
	
	if($value1['rid'] > 0)
	$refusername=$this->get_user_name($value1['rid']);
	
	if($refusername !=""){?>
	<?php echo $this->get_label('referred by');?> : <a href="<?php echo $this->make_url("user/profile/".$value1['rid']."/0");?>"><?php echo $this->escape($refusername);?></a>
	<?php }
}
?>

</td>
</tr>




<tr>
<td><?php echo $this->get_label('registration time');?></td>
<td>:</td>
<td><?php echo $this->get_date_format(2,$value1['regtime']);?></td>
<td></td>
</tr>


<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) !=3){?>
<tr>
<td><?php echo $this->get_label('advstatus');?></td>
<td >:</td>
<td colspan="2"><span class="span-status <?php if($adv_status ==1){?> span-status-active <?php }else if($adv_status ==0){?> span-status-blocked <?php }else{?>span-status-none<?php }?>"><?php echo $this->get_user_status($adv_status);?></span>
<?php if($adv_status ==-2 && $adv_status ==-3){?>
&nbsp;&nbsp;<a class="link_button" href="<?php echo $this->make_url("user/create_account/".$uid."/1");?>"><?php echo $this->get_label('create account');?></a>
<?php }?>
</td>
</tr> 
<?php }?>


<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) !=3 && $adv_status !=-2 && $adv_status !=-3){?>
<tr>
<td ><?php echo $this->get_label('adv account balance');?></td>
<td>:</td>
<td><?php echo $this->get_money_format($adv_balance);?></td>
<td>
<?php  
if($value1['adv_bonus_balance'] >0){?>
<?php echo $this->get_label('adv bonus balance');?> : <?php echo $this->get_money_format($value1['adv_bonus_balance']);?>
<?php }?>

</td>
</tr>	
<?php } ?>


<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) !=2){?>
<tr>
<td><?php echo $this->get_label('pubstatus');?></td>
<td >:</td>
<td colspan="2"><span class="span-status <?php if($pub_status ==1){?> span-status-active <?php }else if($pub_status ==0){?> span-status-blocked <?php }else{?>span-status-none<?php }?>"><?php echo $this->get_user_status($pub_status);?></span>
<?php if($pub_status ==-2 && $pub_status ==-3){?>
&nbsp;&nbsp;<a class="link_button" href="<?php echo $this->make_url("user/create_account/".$uid."/2");?>"><?php echo $this->get_label('create account');?></a>
<?php }?>
</td>
</tr> 
<?php }?>



<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) !=2 && $pub_status !=-2 && $pub_status !=-3){?>
<tr>
<td ><?php echo $this->get_label('pub account balance');?></td>
<td >:</td>
<td colspan="2"><?php echo $this->get_money_format($pub_balance);?>
<?php /*if($pub_balance >=$min_balance && Configuration::get_instance()->read('enable_auto_withdrawal')==0) {?>&nbsp;&nbsp;<a class="link_button" href="<?php echo $this->make_url("user/auto_request/".$uid);?>"><?php echo $this->get_label('withdrawal request');?></a><?php }*/  ?>
</td>
</tr>	
<?php }?>



<?php 
if($referral_enabled ==1)
{
	if($referral_balance >0){?>
	<tr>
	<td ><?php echo $this->get_label('referral account balance');?></td>
	<td>:</td>
	<td colspan="2"><?php echo $this->get_money_format($referral_balance);?>
	<?php /*if($referral_balance >= $min_balance && Configuration::get_instance()->read('enable_auto_withdrawal')==0) {?>&nbsp;&nbsp;<a class="link_button" href="<?php echo $this->make_url("user/auto_request/".$uid);?>"><?php echo $this->get_label('withdrawal request');?></a><?php } */?>
	</td>
	</tr>
	<?php }?>	

	<?php

	if($ref_advertisers >0 || $ref_publishers >0){?>
	<tr>
	<td ><?php echo $this->get_label('referred users');?></td>
	<td>:</td>
	<td colspan="2">
	
	<?php if($ref_advertisers >0){?>
	<div style="width: 120px;float: left;margin-right: 30px;margin-bottom: 10px;"> 
	<?php echo $this->get_label('advertisers');?> : <?php echo $ref_advertisers;?>
	</div>
	<?php }?>
	
	
	<?php if($ref_publishers >0){?>
	<div style="width: 100px;float: left;margin-bottom: 10px;"> 
	<?php echo $this->get_label('publishers');?> : <?php echo $ref_publishers;?>
	</div>
	<?php }?>	
	
	
	</td>
	</tr>
	<?php }
}
?>
</table>
</div>


<div class="profile_details contact-info" style="margin-bottom: 11px;float: right;">
<table style="width: 100%;">

<tr class="profile_heading"><td colspan="8"><?php echo $this->get_label('contact details');?></td></tr>

<tr>
<td style="width: 160px;"><?php echo $this->get_label('name');?></td>
<td style="width: 20px;">:</td>
<td style="width: 200px;"><?php echo $value1['f_name'].' '.$value1['l_name'];?></td>
<td rowspan="4" style="vertical-align: top;padding-top: 20px;">

<?php if($value1['logo'] !=""){?>
<img style="max-width: 120px;max-height: 70px;margin-bottom: 10px;" alt="<?php echo $this->get_label('company logo');?>" title="<?php echo $this->get_label('company logo');?>" src="<?php echo '../'.DATA_DIR.'/'.LOGO_DIR.'/'.$uid.'/'.$value1['logo'];?>" />
<?php }?>

</td>
</tr>


<tr>
<td ><?php echo $this->get_label('address');?></td>
<td>:</td>
<td style="height: 50px;"><?php echo nl2br($value1['address']);?></td>
</tr>

			 
<tr>
<td ><?php echo $this->get_label('country');?></td>
<td>:</td>
<td><?php echo $this->get_country_name($value1['country']);	?></td>
</tr>	


<tr>
<td ><?php echo $this->get_label('phone');?></td>
<td>:</td>
<td><?php echo $value1['phone'];?></td>
</tr>


<tr>
<td ><?php echo $this->get_label('email');?></td>
<td >:</td>
<td><?php echo $value1['email'];?></td>
<td></td>
</tr>

<tr>
<td ><?php echo $this->get_label('domain');?></td>
<td>:</td>
<td><?php if($value1['domain']==""){echo $this->get_label('na');}else{echo $value1['domain'];}?></td>
<td></td>
</tr>

</table>
</div>


<?php 
if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0){?>

<?php if(($adv_status !=-2 && $adv_status !=-3) || ($pub_status !=-2 && $pub_status !=-3)){?>

<div class="profile_details" style="width: 100%;">
<?php 
$form=$this->create_form();
$form->start("saveuserdata",$this->make_url("user/profile/".$uid."/".$tab),"post");
?>
<table style="width: 100%;">
<tr class="profile_heading"><td colspan="11"><?php echo $this->get_label('overridable user configurations');?></td></tr>
<tr>
<td style="width: 250px;"><?php echo $this->get_label('home logo display');?></td>
<td style="width: 10px;">:</td>
<td style="width: 60px;">
<select name="logo_display" id="logo_display">
<option value="0" <?php if($value1['logo_display'] ==0){?> selected="selected"<?php }?>><?php echo $this->get_label('no');?></option>
<option value="1" <?php if($value1['logo_display'] ==1){?> selected="selected"<?php }?>><?php echo $this->get_label('yes');?></option>
</select>
</td>


<?php if($referral_enabled ==1)
{
	$advertiser_referral_enabled=Configuration::get_instance()->read('advertiser_referral_enabled');
	$publisher_referral_enabled=Configuration::get_instance()->read('publisher_referral_enabled');
	
	$adv_ref_profit_percentage=$value1['adv_ref_profit_percentage'];
	$pub_ref_profit_percentage=$value1['pub_ref_profit_percentage'];
	
	if($adv_ref_profit_percentage ==0)
	$adv_ref_profit_percentage='';	
	
	if($pub_ref_profit_percentage ==0)
	$pub_ref_profit_percentage='';	
?>


<?php if($advertiser_referral_enabled ==1){?>
<td><?php echo $this->get_label('adv referral profit');?></td>
<td>:</td>
<td>
<input type="text" onkeyup="this.value = this.value.replace(/[^0-9\.]/g,'');" class="profit-text" name="adv_ref_profit_percentage" id="adv_ref_profit_percentage" value="<?php echo $adv_ref_profit_percentage;?>" size="3" />
% 
</td>
<?php }?>


<?php if($publisher_referral_enabled ==1){?>
<td><?php echo $this->get_label('pub referral profit');?></td>
<td>:</td>
<td>
<input type="text" onkeyup="this.value = this.value.replace(/[^0-9\.]/g,'');" class="profit-text" name="pub_ref_profit_percentage" id="pub_ref_profit_percentage" value="<?php echo $pub_ref_profit_percentage;?>" size="3" />
% 
</td>
<?php }?>
<?php }?>

<td style="width: 100px;"><input type="submit" name="saveuserdatabutton" id="saveuserdatabutton" class="link_button" value="<?php echo $this->get_label('update');?>"/></td>


<?php if($referral_enabled ==0){?> 
<td></td>
<?php }?>

</tr>
</table>
<?php $form->end(); ?>
</div>
<?php }}?>


<?php 

	$cpc_enabled		= $this->get_addon_status('cpc_enabled');
	$cpm_enabled		= $this->get_addon_status('cpm_enabled');
	$cpa_enabled		= $this->get_addon_status('cpa_enabled');
	$cpd_enabled		= $this->get_addon_status('sponsored_enabled');
	$pop_enabled		= $this->get_addon_status('pop-ads_enabled');
	$affiliate_enabled  = $this->get_addon_status('affiliate-ads_enabled');
	$video_enabled		= $this->get_addon_status('video-ads_enabled');
	$subadmin_enabled   = $this->get_addon_status('subadmin_enabled');

	
	$pop_addon_usage = intval(Configuration::get_instance()->read('pop_addon_usage'));
	
	if($pop_addon_usage == 1)
	$pop_enabled = 0;		
	

	$adv_sub_admin_count	= 0;
	$pub_sub_admin_count	= 0;
	
	if($subadmin_enabled ==1)
	{
		$adv_res				= $this->get_result('adv_res');
		$pub_res				= $this->get_result('pub_res');

		$adv_sub_admin_count	= count($adv_res);
		$pub_sub_admin_count	= count($pub_res);
	}

	
	
	
$ppc_minrate		=0;	
$cpm_minrate		=0;	
$cpa_minrate		=0;	
$pop_minrate		=0;	
$video_minrate		=0;
$affiliate_minrate	=0;	

	
	

if($cpc_enabled ==1)
$ppc_minrate		= $value1['ppc_minrate'];

if($cpm_enabled ==1)
$cpm_minrate		= $value1['cpm_minrate'];

if($cpa_enabled ==1)
$cpa_minrate		= $value1['cpa_minrate'];

if($pop_enabled ==1)
$pop_minrate		= $value1['pop_minrate'];

if($video_enabled ==1)
$video_minrate		= $value1['video_minrate'];

if($affiliate_enabled ==1)
$affiliate_minrate	= $value1['affiliate_minrate'];


if($ppc_minrate ==0)
$ppc_minrate		='';	


if($cpm_minrate ==0)
$cpm_minrate		='';	

if($cpa_minrate ==0)
$cpa_minrate		='';	

if($pop_minrate ==0)
$pop_minrate		='';	

if($video_minrate ==0)
$video_minrate		='';

if($affiliate_minrate ==0)
$affiliate_minrate	='';	

	
if($cpc_enabled ==1 || $cpm_enabled ==1 || $cpa_enabled ==1 || $pop_enabled ==1 || $affiliate_enabled ==1 || $video_enabled ==1 || $adv_sub_admin_count > 0)
{
	if($this->read_cookie_param(COOKIE_ADMIN_TYPE) !=3)
	{
		if($adv_status !=-2 && $adv_status !=-3)
		{
	?>
<div class="profile_details" style="width: 100%;">
<?php  
$form=$this->create_form();
$form->start("saveadvertiserdata",$this->make_url("user/profile/".$uid."/".$tab),"post");
?>

<table style="width: 100%;">
<tr class="profile_heading"><td colspan="13"><?php echo $this->get_label('overridable advertiser configurations');?></td></tr>

<?php if($cpc_enabled ==1 || $cpm_enabled ==1 || $cpa_enabled ==1 || $pop_enabled ==1 || $affiliate_enabled ==1 || $video_enabled ==1){?>	
<tr>	
<td style="width: 150px;height: 60px;"><?php echo $this->get_label('minimum rate');?> (<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>
<td style="width: 30px;">:</td>		
		
<?php if($cpc_enabled ==1){?>
<td style="width: 75px;">
<?php echo $this->get_label('cpc');?>
<br/>
<input type="text" onkeyup="this.value = this.value.replace(/[^0-9\.]/g,'');" class="profit-text" name="ppc_minrate" id="ppc_minrate" value="<?php echo $ppc_minrate;?>" size="3" />
</td>
<?php }?>



<?php if($cpm_enabled ==1){?>
<td style="width: 75px;">
<?php echo $this->get_label('cpm');?>
<br/>
<input type="text" onkeyup="this.value = this.value.replace(/[^0-9\.]/g,'');" class="profit-text" name="cpm_minrate" id="cpm_minrate" value="<?php echo $cpm_minrate;?>" size="3" />
</td>
<?php }?>



<?php if($cpa_enabled ==1){?>
<td style="width: 75px;">
<?php echo $this->get_label('cpa');?>
<br/>
<input type="text" onkeyup="this.value = this.value.replace(/[^0-9\.]/g,'');" class="profit-text" name="cpa_minrate" id="cpa_minrate" value="<?php echo $cpa_minrate;?>" size="3" />
</td>
<?php }?>


<?php if($pop_enabled ==1){?>
<td style="width: 75px;">
<?php echo $this->get_label('pop');?>
<br/>
<input type="text" onkeyup="this.value = this.value.replace(/[^0-9\.]/g,'');" class="profit-text" name="pop_minrate" id="pop_profit_percentage" value="<?php echo $pop_minrate;?>" size="3" />
</td>
<?php }?>


<?php if($affiliate_enabled ==1){?>
<td style="width: 75px;">
<?php echo $this->get_label('affiliate');?>
<br/>
<input type="text" onkeyup="this.value = this.value.replace(/[^0-9\.]/g,'');" class="profit-text" name="affiliate_minrate" id="affiliate_minrate" value="<?php echo $affiliate_minrate;?>" size="3" />
</td>
<?php }?>

<?php if($video_enabled ==1){?>
<td style="width: 75px;">
<?php echo $this->get_label('cpv');?>
<br/>
<input type="text" onkeyup="this.value = this.value.replace(/[^0-9\.]/g,'');" class="profit-text" name="video_minrate" id="video_minrate" value="<?php echo $video_minrate;?>" size="3" />
</td>
<?php }?>
</tr>
<?php }?>






<?php if($adv_sub_admin_count > 0){?>
<tr>
<td style="width: 120px;"><?php echo $this->get_label('account manager');?></td>
<td style="width: 30px;">:</td>
<td colspan="6">
<select name="adv_mngr" id="adv_mngr" style="width: 150px;">
<option value="0"><?php echo $this->get_label('select');?></option>
<?php foreach($adv_res as $key=>$value){?>
<option value="<?php echo $value['id'];?>" <?php if($value1['adv_managerid']==$value['id']) {echo 'selected';}?>><?php echo $value['username'];?></option>
<?php }?>
</select>
</td>
</tr>
<?php }?>
<tr>
<td></td>
<td></td>
<td colspan="6">
<input type="submit" name="saveadvertiserdatabutton" id="saveadvertiserdatabutton" class="link_button" value="<?php echo $this->get_label('update');?>"/>
</td>

</tr>
</table>
<?php $form->end(); ?>
</div>
<?php }}}?>




<?php 
if($cpc_enabled ==1 || $cpm_enabled ==1 || $cpa_enabled ==1 || $cpd_enabled ==1 || $pop_enabled ==1 || $affiliate_enabled ==1 || $video_enabled ==1 || $pub_sub_admin_count > 0)
{
	if($this->read_cookie_param(COOKIE_ADMIN_TYPE) !=2)
	{
		if($pub_status !=-2 && $pub_status !=-3) 
		{
		
			
$ppc_profit_percentage			= 0;			
$cpm_profit_percentage			= 0;			
$cpa_profit_percentage			= 0;			
$sponsored_profit_percentage	= 0;			
$pop_profit_percentage			= 0;			
$get_pop_link					= 0;			
$cpv_profit_percentage			= 0;			
$vast_adcode_enabled			= 0;			
$affiliate_profit_percentage	= 0;			



if($cpc_enabled ==1)
$ppc_profit_percentage			= $value1['ppc_profit_percentage'];

if($cpm_enabled ==1)
$cpm_profit_percentage			= $value1['cpm_profit_percentage'];

if($cpa_enabled ==1)
$cpa_profit_percentage			= $value1['cpa_profit_percentage'];

if($cpd_enabled ==1)
$sponsored_profit_percentage	= $value1['sponsored_profit_percentage'];

if($pop_enabled ==1)
{
	$pop_profit_percentage		= $value1['pop_profit_percentage'];
	$get_pop_link				= intval($value1['get_pop_link']);
}	

if($video_enabled ==1)
{
	$cpv_profit_percentage		= $value1['cpv_profit_percentage'];
	$vast_adcode_enabled		= $value1['vast_adcode_enabled'];
}	

if($affiliate_enabled ==1)
$affiliate_profit_percentage	= $value1['affiliate_profit_percentage'];

			
					
if($ppc_profit_percentage ==0)
$ppc_profit_percentage			='';				
			
if($cpm_profit_percentage ==0)
$cpm_profit_percentage			='';			
			
if($cpa_profit_percentage ==0)
$cpa_profit_percentage			='';	

if($sponsored_profit_percentage ==0)
$sponsored_profit_percentage	='';	

if($affiliate_profit_percentage ==0)
$affiliate_profit_percentage	='';	

if($pop_profit_percentage ==0)
$pop_profit_percentage			='';				
			
if($cpv_profit_percentage ==0)
$cpv_profit_percentage			='';	


$flag_count		=0;

if($cpc_enabled ==1 || $cpm_enabled ==1 || $cpa_enabled ==1 || $cpd_enabled ==1 || $pop_enabled ==1 || $affiliate_enabled ==1 || $video_enabled ==1)
$flag_count++;

if($pub_sub_admin_count > 0 || $cpc_enabled ==1 || (($pop_enabled ==1 && intval(Configuration::get_instance()->read('pop_direct_link_enabled')) ==1)) || $video_enabled ==1)
$flag_count++;


?>

<div class="profile_details" style="width: 100%;">
<?php 
$form=$this->create_form();
$form->start("savepublisherdata",$this->make_url("user/profile/".$uid."/".$tab),"post");
?>

<table style="width: 100%;">

<tr class="profile_heading"><td colspan="12"><?php echo $this->get_label('overridable publisher configurations');?></td></tr>


<?php if($cpc_enabled ==1 || $cpm_enabled ==1 || $cpa_enabled ==1 || $cpd_enabled ==1 || $pop_enabled ==1 || $affiliate_enabled ==1 || $video_enabled ==1){?>
<tr>
<td style="width: 150px;height: 60px;"><?php echo $this->get_label('publisher profit');?> (%)</td>
<td style="width: 30px;">:</td>


<?php if($cpc_enabled ==1){?>
<td style="width: 100px;">
<?php echo $this->get_label('cpc');?>
<br/>
<input type="text" onkeyup="this.value = this.value.replace(/[^0-9\.]/g,'');" class="profit-text" name="ppc_profit_percentage" id="ppc_profit_percentage" value="<?php echo $ppc_profit_percentage;?>" size="3" />
</td>
<?php }?>



<?php if($cpm_enabled ==1){?>
<td style="width: 100px;">
<?php echo $this->get_label('cpm');?>
<br/>
<input type="text" onkeyup="this.value = this.value.replace(/[^0-9\.]/g,'');" class="profit-text" name="cpm_profit_percentage" id="cpm_profit_percentage" value="<?php echo $cpm_profit_percentage;?>" size="3" />
</td>
<?php }?>



<?php if($cpa_enabled ==1){?>
<td style="width: 100px;">
<?php echo $this->get_label('cpa');?>
<br/>
<input type="text" onkeyup="this.value = this.value.replace(/[^0-9\.]/g,'');" class="profit-text" name="cpa_profit_percentage" id="cpa_profit_percentage" value="<?php echo $cpa_profit_percentage;?>" size="3" />
</td>
<?php }?>


<?php if($cpd_enabled ==1){?>
<td style="width: 100px;">
<?php echo $this->get_label('cpd');?>
<br/>
<input type="text" onkeyup="this.value = this.value.replace(/[^0-9\.]/g,'');" class="profit-text" name="sponsored_profit_percentage" id="sponsored_profit_percentage" value="<?php echo $sponsored_profit_percentage;?>" size="3" />
</td>
<?php }?>


<?php if($pop_enabled ==1){?>
<td style="width: 100px;">
<?php echo $this->get_label('pop');?>
<br/>
<input type="text" onkeyup="this.value = this.value.replace(/[^0-9\.]/g,'');" class="profit-text" name="pop_profit_percentage" id="pop_profit_percentage" value="<?php echo $pop_profit_percentage;?>" size="3" /> 
</td>
<?php }?>


<?php if($video_enabled ==1){?>
<td style="width: 100px;">
<?php echo $this->get_label('cpv');?>
<br/>
<input type="text" onkeyup="this.value = this.value.replace(/[^0-9\.]/g,'');" class="profit-text" name="cpv_profit_percentage" id="cpv_profit_percentage" value="<?php echo $cpv_profit_percentage;?>" size="3" />
</td>
<?php }?>


<?php if($affiliate_enabled ==1){?>
<td style="width: 100px;">
<?php echo $this->get_label('affiliate');?>
<br/>
<input type="text" onkeyup="this.value = this.value.replace(/[^0-9\.]/g,'');" class="profit-text" name="affiliate_profit_percentage" id="affiliate_profit_percentage" value="<?php echo $affiliate_profit_percentage;?>" size="3" />
</td>
<?php }?>
<td></td>
</tr>
<?php }?>


<?php if($pub_sub_admin_count > 0){?>
<tr>
<td style="width: 150px;"><?php echo $this->get_label('account manager');?></td>
<td style="width: 30px;">:</td>
<td style="width: 150px;" colspan="2">
<select name="pub_mngr" id="pub_mngr" style="width: 150px;">
<option value="0"><?php echo $this->get_label('select');?></option>
<?php foreach($pub_res as $key=>$value){?>
<option value="<?php echo $value['id'];?>" <?php if($value1['pub_managerid'] == $value['id']) {echo 'selected';}?>><?php echo $value['username'];?></option>
<?php }?>
</select>
</td>
</tr>
<?php }?>

<?php if($cpc_enabled ==1){?>
<tr>
<td style="width: 200px;"><?php echo $this->get_label('captcha verification for clicks');?></td>
<td style="width: 30px;">:</td>
<td style="width: 150px;" colspan="2">
<select name="pub_captcha_status" id="pub_captcha_status" style="width: 150px;">
<option value="0" <?php if($value1['pub_captcha_status'] ==0){?> selected="selected"<?php }?>><?php echo $this->get_label('disabled');?></option>
<option value="1" <?php if($value1['pub_captcha_status'] ==1){?> selected="selected"<?php }?>><?php echo $this->get_label('enabled');?></option>
</select>
</td>
</tr>
<?php }?>

<?php if($pop_enabled ==1 && intval(Configuration::get_instance()->read('pop_direct_link_enabled')) ==1){?>
<tr>
<td style="width: 225px;"><?php echo $this->get_label('allow pop direct link');?></td>
<td style="width: 30px;">:</td>
<td style="width: 150px;" colspan="2">
<select name="get_pop_link" id="get_pop_link" style="width: 150px;"> 
<option value="1" <?php if($get_pop_link ==1){?> selected="selected" <?php }?> ><?php echo $this->get_label('yes');?></option>
<option value="0" <?php if($get_pop_link ==0){?> selected="selected" <?php }?> ><?php echo $this->get_label('no');?></option>
</select>
</td>
</tr>
<?php }?>

<?php if($video_enabled ==1){?>
<tr>
<td style="width: 200px;"><?php echo $this->get_label('allow vast adcode');?></td>
<td style="width: 30px;">:</td>
<td style="width: 150px;" colspan="2">
<select name="vast_adcode_enabled" id="vast_adcode_enabled" style="width: 150px;"> 
<option value="1" <?php if($vast_adcode_enabled ==1){?> selected="selected" <?php }?> ><?php echo $this->get_label('yes');?></option>
<option value="0" <?php if($vast_adcode_enabled ==0){?> selected="selected" <?php }?> ><?php echo $this->get_label('no');?></option>
</select>
</td>
</tr>
<?php }?>


<tr>
<td style="width: 200px;"></td>
<td style="width: 30px;"></td>
<td ><input type="submit" name="savepublisherdatabutton" id="savepublisherdatabutton" class="link_button" value="<?php echo $this->get_label('update');?>"/></td>
</tr>

</table>
<?php $form->end(); ?>

</div>
<div style="clear: both;"></div>


<?php }}}?>

</div>

</td></tr>

<?php 
if($pub_status !=-2) 
{
	$rest_num=$this->get_variable('rest_num');

	if($rest_num > 0){?>
	<tr><td colspan="4" style="background-color: #F6F6F6;">	
	<div class="profile_div">
	<div class="profile_details" style="width: 100%;">
	<table style="width: 100%;">
	<tr class="profile_heading"><td colspan="5"><?php echo $this->get_label('site filters');?></td></tr>
	
	<?php 
	$rest_res1=$this->get_array('sites');	
	$ii=0;
	
	foreach($rest_res1 as $key=>$rest_res)
	{
		if($ii ==5){
		$ii=0;
		?>
		</tr>
		<?php }?>
	
		<?php if($ii ==0){?>
		<tr>
		<?php }?>
	<td style="width: 20%;"><i class="fa fa-square restricted-site"></i>&nbsp;<?php echo $rest_res;?></td>
	<?php 
	$ii++;
	}
	?>
	</tr>
	
	
	
	</table>
	</div>
	</div>	
	</td></tr>
<?php }}?>


<?php 
$flagdata=0;



if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0 || isset($privilege['mu_3']) || isset($privilege['mu_4']) || isset($privilege['mu_5'])){?>
	
<tr>
<td colspan="4" height="10px" style="font-size: 15px;">
<div class="profile_div">

<?php if($adv_status !=-2 && $adv_status !=-3 && ($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0 || isset($privilege['mu_3'])))
{
	$flagdata=1;
	?>
<input type="radio" name="stit" id="stit_adv" value="1" onclick="javascript:return select_statistics(1,0);" checked="checked" /><?php echo $this->get_label('advertiser report');?>&nbsp;&nbsp;
<?php }?>	

<?php if($pub_status !=-2 && $pub_status !=-3 && ($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0 || isset($privilege['mu_4']))){?>
<input type="radio" name="stit" id="stit_pub" value="2" onclick="javascript:return select_statistics(2,0);" <?php if($flagdata ==0){?>checked="checked"<?php }?> /><?php echo $this->get_label('publisher report');?>&nbsp;&nbsp;
<?php
$flagdata=1;
}?>

<?php if((($adv_status !=-2 && $adv_status !=-3) || ($pub_status !=-2 && $pub_status !=-3)) && ($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0 || isset($privilege['mu_5']))){?>
<input type="radio" name="stit" id="stit_other" value="3" onclick="javascript:return select_statistics(3,0);" <?php if($flagdata ==0 || $tab ==8 || $tab ==9){?>checked="checked"<?php }?> /><?php echo $this->get_label('other report');?>&nbsp;&nbsp;
<?php
$flagdata=1;
}?>

</div>
</td></tr>				
<?php }?>	
		

			
	
<?php if($adv_status !=-2 && $adv_status !=-3) {?>			
<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0 || isset($privilege['mu_3'])){?>	

<tr id="stat_row_1" class="statrow">
<td colspan="4" height="100%">

<div class="profile_div">

<table style="width: 100%;">
<tr><td colspan="4" height="100%">		
		
<table style="width: 100%;">
  <tr class="statistics_header">
  <td onclick="show_stat_data(1);" id="stat1" class="statclass" style="width: 150px;"><?php echo $this->get_label('overall');?></td>
  <td onclick="show_stat_data(2);" id="stat2" class="statclass" style="width: 150px;"><?php echo $this->get_label('ads');?></td>
  <td onclick="show_stat_data(3);" id="stat3" class="statclass" style="width: 150px;"><?php echo $this->get_label('time based reports');?></td>
  <td></td>
  </tr>
  
  <tr id="showstat1" class="showstatclass statistics_tr">
  <td colspan="4" class="statistics_td">
  <iframe width="100%" height="450px" frameborder="0" src="<?php echo $this->make_url("user/advall/".$uid);?>" allowtransparency="true" scrolling="no"></iframe>
  </td>
  </tr>
  

  
  <tr id="showstat2" class="showstatclass statistics_tr">
  <td colspan="4" class="statistics_td">
  <iframe width="100%" height="940px" frameborder="0" src="<?php echo $this->make_url("user/advadstat/".$uid."/".$dur."/".$tp."/".$st."/".$adpricing."/".$pg);?>" allowtransparency="true"></iframe>
  </td>
  </tr>
  
  
  <tr id="showstat3" class="showstatclass statistics_tr">
  <td colspan="4" class="statistics_td">
  <iframe width="100%" height="1010px" frameborder="0" src="<?php echo $this->make_url("user/advtimestat/".$uid);?>" allowtransparency="true"></iframe>
  </td>
  </tr>
</table>
		
		
		
	</td>
			</tr>	
</table>

</div>
</td></tr>
<?php }?>			
<?php }?>			
<?php if($pub_status !=-2 && $pub_status !=-3) {?>	
<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0 || isset($privilege['mu_4'])){?>
<tr id="stat_row_2" class="statrow">
<td colspan="4" height="100%">	
		
<div class="profile_div">
		
<table style="width: 100%;">
			
		<tr><td colspan="4" height="100%">	
		
		
		<?php 
		$catenabled=$this->get_addon_status('category-targeting_enabled');
		$sponsenabled=$this->get_addon_status('sponsored_enabled');
		?>
		
<table style="width: 100%;">
  <tr class="statistics_header">
    <td onclick="show_stat_data(4);" id="stat4" class="statclass" style="width: 150px;"><?php echo $this->get_label('overall');?></td>
    <td onclick="show_stat_data(5);" id="stat5" class="statclass" style="width: 150px;"><?php echo $this->get_label('adunits');?></td>
    
    <?php if($catenabled ==1 || $sponsenabled ==1){?>
    <td onclick="show_stat_data(6);" id="stat6" class="statclass" style="width: 150px;"><?php echo $this->get_label('sites');?></td>
    <?php }?>
    
    <td onclick="show_stat_data(7);" id="stat7" class="statclass" style="width: 150px;"><?php echo $this->get_label('time based reports');?></td>
    <td></td>
  </tr>
  
  
  <tr id="showstat4" class="showstatclass statistics_tr">
  <td colspan="5" class="statistics_td">
  <iframe width="100%" height="450px" frameborder="0" src="<?php echo $this->make_url("user/puball/".$uid);?>" allowtransparency="true" scrolling="no"></iframe>
  </td>
  </tr>
  
  
  <tr id="showstat5" class="showstatclass statistics_tr">
  <td colspan="5" class="statistics_td">
  <iframe height="940px" width="100%" frameborder="0" src="<?php echo $this->make_url("user/pubadunitstat/".$uid);?>" allowtransparency="true"></iframe>
  </td>
  </tr>
  
  
  <?php if($catenabled ==1 || $sponsenabled ==1){?>
  <tr id="showstat6" class="showstatclass statistics_tr">
  <td colspan="5" class="statistics_td">
  

  <iframe height="940px" width="100%" frameborder="0" src="<?php echo $this->make_base_url("site/statistics_publisher_profile/".$uid,ADDON_DIR.'/category-targeting');?>" allowtransparency="true"></iframe>

  </td>
  </tr>
  <?php }?>
  
    
  <tr id="showstat7" class="showstatclass statistics_tr">
  <td colspan="5" class="statistics_td">
  <iframe height="1010px" width="100%" frameborder="0" src="<?php echo $this->make_url("user/pubtimestat/".$uid);?>" allowtransparency="true"></iframe>
  </td>
  </tr>
  
</table>
		
				
		
	</td>
			</tr>		
		
</table>

</div>
</td></tr>		
<?php }?>
<?php }?>		
		
		
<?php if(($adv_status !=-2 || $pub_status !=-2) && ($adv_status !=-3 || $pub_status !=-3)){?>		
<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0 || isset($privilege['mu_5']))
{
	?>
<tr id="stat_row_3" class="statrow">
<td colspan="4" height="100%">
<div class="profile_div">

<table style="width: 100%;">
	<tr><td colspan="4" height="100%">


<table style="width: 100%;">
  <tr class="statistics_header">



    <?php if($adv_status !=-2 && $adv_status !=-3 && ($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0 || isset($privilege['mu_5']))){?>
    <td onclick="show_stat_data(8);" id="stat8" class="statclass" style="width: 150px;"><?php echo $this->get_label('payments');?></td>
    <?php }?>

    <?php if($referral_enabled ==1 || ($pub_status !=-2 && $pub_status !=-3 && ($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0 || isset($privilege['mu_4'])))){?>
    <td onclick="show_stat_data(9);" id="stat9" class="statclass" style="width: 150px;"><?php echo $this->get_label('withdrawals');?></td>
    <?php }?>

	<?php if($referral_enabled ==1){?>
	<td onclick="show_stat_data(10);" id="stat10" class="statclass" style="width: 150px;"><?php echo $this->get_label('referral');?></td>
	<td onclick="show_stat_data(11);" id="stat11" class="statclass" style="width: 150px;"><?php echo $this->get_label('time based reports');?></td>
	<td onclick="show_stat_data(12);" id="stat12" class="statclass" style="width: 150px;"><?php echo $this->get_label('referral visits');?></td>
	<?php }?>
	<td></td>
  </tr>





  <?php if($adv_status !=-2 && $adv_status !=-3 && ($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0 || isset($privilege['mu_5']))){?>
  <tr id="showstat8" class="showstatclass statistics_tr">
  <td colspan="6" class="statistics_td">
  <iframe width="100%" height="800px" frameborder="0" src="<?php echo $this->make_url("user/advpayment/".$uid."/".$pt."/".$pts."/".$ppg);?>" allowtransparency="true" scrolling="auto"></iframe>
  </td>
  </tr>
  <?php }?>


  <?php if($referral_enabled ==1 || ($pub_status !=-2 && $pub_status !=-3 && ($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0 || isset($privilege['mu_5'])))){?>
  <tr id="showstat9" class="showstatclass statistics_tr">
  <td colspan="6" class="statistics_td">
  <iframe height="800px" width="100%" frameborder="0" src="<?php echo $this->make_url("user/pubwithdrawal/".$uid."/".$wt."/".$wts."/".$wty."/".$wpg);?>" allowtransparency="true" scrolling="auto"></iframe>
  </td>
  </tr>
  <?php }?>


  <?php if($referral_enabled ==1){?>
  <tr id="showstat10" class="showstatclass statistics_tr">
  <td colspan="6" class="statistics_td">
  <iframe width="100%" height="300px" frameborder="0" src="<?php echo $this->make_base_url("referral/user_report/".$uid,ADDON_DIR."/referral");?>" allowtransparency="true" scrolling="no"></iframe>
  </td>
  </tr>

  <tr id="showstat11" class="showstatclass statistics_tr">
  <td colspan="6" class="statistics_td">
  <iframe width="100%" height="500px" frameborder="0" src="<?php echo $this->make_base_url("referral/user_report_timebased/".$uid,ADDON_DIR."/referral");?>" allowtransparency="true" scrolling="auto"></iframe>
  </td>
  </tr>



  <tr id="showstat12" class="showstatclass statistics_tr">
  <td colspan="6" class="statistics_td">
  <iframe width="100%" height="500px" frameborder="0" src="<?php echo $this->make_base_url("referral/visits/".$uid,ADDON_DIR."/referral");?>" allowtransparency="true" scrolling="auto"></iframe>
  </td>
  </tr>


  <?php }?>

</table>
</td>
</tr>

</table>
</div>
</td>
</tr>				
			
			
<?php }}?>			
</table>
	
</div>


<script type="text/javascript">
function show_stat_data(id)
{
	$('.showstatclass').hide();
	$('.statclass').removeClass('tab-selection');

	$('#showstat'+id).show();
	$('#stat'+id).addClass('tab-selection');
}

function select_statistics(type,tab)
{
	$('.statrow').hide();
	$('#stat_row_'+type).show();

	id=0;

	if(tab ==0)
	{
		if(type ==1 && $('#stat1').length >0)
		id=1;
		else if(type ==2 && $('#stat4').length >0)
		id=4;
		else if(type ==3 && $('#stat8').length >0)
		id=8;
		else if(type ==3 && $('#stat9').length >0)
		id=9;
		else if(type ==3 && $('#stat10').length >0)
		id=10;
		else if(type ==3 && $('#stat11').length >0)
		id=11;
	}
	else if(tab ==2 || tab ==8 || tab ==9)
	{
		id=tab;
	}

	if(id >0)
	show_stat_data(id);
}



<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0 || isset($privilege['mu_3']) || isset($privilege['mu_4']) || isset($privilege['mu_5'])){  ?>
$(document).ready(function() 
{
	checked=$('input[name=stit]:checked').val();

	select_statistics(checked,<?php echo $tab;?>);
});
<?php }?>

</script>	
<?php $this->dispatch("layout/footer");?>