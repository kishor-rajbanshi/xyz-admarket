<?php 
$this->dispatch("layout/header/1/1/a");

$res=$this->get_result('res');
$value=$res[0];

$adv_status=$value['adv_status'];
$account_balance=$value['adv_account_balance'];


$cpc_enabled=$this->get_addon_status('cpc_enabled');
$cpa_enabled=$this->get_addon_status('cpa_enabled');
$cpm_enabled=$this->get_addon_status('cpm_enabled');
$sponsored_enabled=$this->get_addon_status('sponsored_enabled');
$pop_enabled=$this->get_addon_status('pop-ads_enabled');
$affiliate_enabled=$this->get_addon_status('affiliate-ads_enabled');
$referral_enabled=$this->get_addon_status('referral_enabled');
$cpv_enabled=$this->get_addon_status('video-ads_enabled');


$uid=$this->get_variable('uid');


$statistics=$this->get_advertiser_statistics(5,$uid);				


$impression=0;
$clicks=0;
$conversion=0;
$spend=0;
$balance=0;

if($cpc_enabled ==1)
{
	$impression=$impression+$statistics['impression'];
	$clicks=$clicks+$statistics['click'];
	$spend=$spend+$statistics['money_spent'];
}

if($cpm_enabled ==1)
{
	$impression=$impression+$statistics['cpm_impression'];
	$clicks=$clicks+$statistics['cpm_click'];
	$spend=$spend+$statistics['cpm_spend'];
}

if($cpv_enabled ==1)
{
	$impression=$impression+$statistics['cpv_impression'];
	$clicks=$clicks+$statistics['cpv_click'];
	$spend=$spend+$statistics['cpv_spend'];
}


if($cpa_enabled ==1)
{
	$impression=$impression+$statistics['cpa_impression'];
	$clicks=$clicks+$statistics['cpa_click'];
	$conversion=$conversion+$statistics['cpa_conversion'];
	$spend=$spend+$statistics['cpa_spend'];
}

if($affiliate_enabled ==1)
{
	$clicks=$clicks+$statistics['affiliate_click'];
	$conversion=$conversion+$statistics['affiliate_conversion'];
	$spend=$spend+$statistics['affiliate_spend'];
}
													
													
if($pop_enabled ==1)
{
	$impression=$impression+$statistics['pop_impression'];
	$spend=$spend+$statistics['pop_spend'];
}										

$boxcount=1;

?>
<div class="container">
<h2 class="page_heading"><?php echo $this->get_label('adv controlpanel',array('x'=>$this->escape($this->read_cookie_param(COOKIE_USERNAME))));?></h2>
<div class="page_heading-btm"></div>
</div>



<div class="container">


<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 padding-side home-box-outer">

<div class="col-lg-2 col-sm-2 col-md-2 col-xs-6 mobile_view" style="margin-bottom: 5px;">
<div class="home-box-div">
<table style="width: 100%;"> 
<tr><td class="home-box-td-first"> 
<?php echo $this->get_active_ads_count($uid);?>
</td></tr>
<tr><td class="home-box-td-second"><?php echo $this->get_label('ads');?></td></tr>
</table>
</div>
</div>


<?php if($cpc_enabled ==1 || $cpm_enabled ==1 || $cpa_enabled ==1 || $sponsored_enabled ==1 || $pop_enabled ==1 || $cpv_enabled ==1){?>
<div class="col-lg-2 col-sm-2 col-md-2 col-xs-6 mobile_view" style="margin-bottom: 5px;">
<div class="home-box-div">
<table style="width: 100%;"> 
<tr><td class="home-box-td-first"> 
<?php echo $impression;?>
</td></tr>
<tr><td class="home-box-td-second"><?php echo $this->get_label('impressions');?></td></tr>
</table>
</div>
</div>
<?php 
$boxcount=$boxcount+1;
}?>


<?php if($cpc_enabled ==1 || $cpm_enabled ==1 || $cpa_enabled ==1 || $sponsored_enabled ==1 || $affiliate_enabled ==1 || $cpv_enabled ==1){?>
<div class="col-lg-2 col-sm-2 col-md-2 col-xs-6 mobile_view" style="margin-bottom: 5px;">
<div class="home-box-div">
<table style="width: 100%;"> 
<tr><td class="home-box-td-first"> 
<?php echo $clicks;?>
</td></tr>
<tr><td class="home-box-td-second"><?php echo $this->get_label('clicks');?></td></tr>
</table>
</div>
</div>
<?php 
$boxcount=$boxcount+1;
}?>


<?php if($cpa_enabled ==1 || $affiliate_enabled ==1){?>
<div class="col-lg-2 col-sm-2 col-md-2 col-xs-6 mobile_view" style="margin-bottom: 5px;">
<div class="home-box-div">
<table style="width: 100%;"> 
<tr><td class="home-box-td-first"> 
<?php echo $conversion;?>
</td></tr>
<tr><td class="home-box-td-second"><?php echo $this->get_label('conversions');?></td></tr>
</table>
</div>
</div>
<?php 
$boxcount=$boxcount+1;
}?>


<?php if($cpc_enabled ==1 || $cpm_enabled ==1 || $cpa_enabled ==1 || $affiliate_enabled ==1 || $pop_enabled ==1 || $cpv_enabled ==1){?>
<div class="col-lg-2 col-sm-2 col-md-2 col-xs-6 mobile_view" style="margin-bottom: 5px;">
<div class="home-box-div">
<table style="width: 100%;"> 
<tr><td class="home-box-td-first"> 
<?php echo $this->get_money_format($spend);?>
</td></tr>
<tr><td class="home-box-td-second"><?php echo $this->get_label('spend');?></td></tr>
</table>
</div>
</div>
<?php 
$boxcount=$boxcount+1;
}?>

<?php if($referral_enabled ==1){

$statistics_ref=$this->get_referral_statistics(5,$uid);

$total_ref_earning=$statistics_ref['adv_earning']+$statistics_ref['pub_earning'];	
	
?>
<div class="col-lg-2 col-sm-2 col-md-2 col-xs-6 mobile_view" style="margin-bottom: 5px;">
<div class="home-box-div">
<table style="width: 100%;"> 
<tr><td class="home-box-td-first"> 

<?php echo $this->get_money_format($total_ref_earning);?>

</td></tr>
<tr><td class="home-box-td-second"><?php echo $this->get_label('referral profit');?></td></tr>
</table>
</div>
</div>
<?php 
$boxcount=$boxcount+1;
}?>



<?php if($boxcount < 6){

	$boxcount=$boxcount+1;
	
	if($boxcount ==4)
	$class_string=" col-lg-3 col-sm-3 col-md-3 col-xs-6 ";
	else
	$class_string=" col-lg-2 col-sm-2 col-md-2 col-xs-6 ";
	
	
	$payment_amount=$this->get_all_payment_amount($uid);
?>
<div class=" <?php echo $class_string;?> mobile_view" style="margin-bottom: 5px;">
<div class="home-box-div">
<table style="width: 100%;"> 
<tr><td class="home-box-td-first"> 

<?php echo $this->get_money_format($payment_amount);?>

</td></tr>
<tr><td class="home-box-td-second"><?php echo $this->get_label('payments');?></td></tr>
</table>
</div>
</div>	
<?php }?>

<?php if($boxcount < 6){

	$boxcount=$boxcount+1;
	
	if($boxcount ==5)
	$class_string=" col-lg-3 col-sm-3 col-md-3 col-xs-6 ";
	else
	$class_string=" col-lg-2 col-sm-2 col-md-2 col-xs-6 ";
		
	?>

<div class=" <?php echo $class_string;?> " style="margin-bottom: 5px;">
<div class="home-box-div">
<table style="width: 100%;"> 
<tr><td class="home-box-td-first"> 

<?php echo $this->get_money_format($account_balance);?>

</td></tr>
<tr><td class="home-box-td-second"><?php echo $this->get_label('account balance');?></td></tr>
</table>
</div>
</div>	

<?php }?>
</div>



<?php if(Configuration::get_instance()->read('countrywise_data_tracking') ==1){?>

<style type="text/css">
.iframe-geo
{
	height: 475px;
}

@media (max-width: 991px)
{
	.iframe-geo
	{
		height: 875px;
	}
}
</style>

<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 padding-side theme_padding">
<div class="iframe-geo">
<iframe class="iframe-geo" frameborder="0" style="width: 100%;" src="<?php echo $this->make_base_url("user/adv_country");?>" allowtransparency="true" scrolling="no"></iframe>
</div>
<div style="height: 2px;"></div>
</div>

<?php }?>

<?php if($adv_status==1)
{
	$heightdata=575;
	
	if($cpc_enabled ==1 || $cpm_enabled ==1 || $cpa_enabled ==1 || $sponsored_enabled ==1 || $cpv_enabled ==1)
	$heightdata=900;
	else if($affiliate_enabled ==1)
	$heightdata=750;
	else if($pop_enabled ==1)
	$heightdata=675;

	?>		
<style type="text/css">
.iframe-top-first
{
	height: 575px;
}

@media (max-width: 991px)
{
	.iframe-top-first
	{
		height: <?php echo $heightdata;?>px;
	}
}

@media (min-width: 992px) and (max-width: 1199px)
{
	.iframe-top-first
	{
		height: <?php echo $heightdata;?>px;
	}
}
</style>

<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 padding-side theme_padding">

<div class="ad_report-div iframe-top-first">
<iframe id="top-ad-iframe" class="iframe-top-first" frameborder="0" style="width: 100%;" src="<?php echo $this->make_url("user/ad_report");?>" allowtransparency="true" scrolling="yes"></iframe>
</div>

<div style="height: 20px;"></div>
</div>


<style type="text/css">
.iframe-table-list{	height: 325px;}
</style>


<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 padding-side theme_padding">
<div id="top-ad-div1" class="ad_report-div iframe-table-list">
<iframe id="top-ad-iframe1" class="iframe-table-list" frameborder="0" style="width: 100%;"  src="<?php echo $this->make_url("user/top_ads");?>" allowtransparency="true" scrolling="yes"></iframe>
</div>
</div>



<?php }?>
</div>


<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 padding-side">
<div id="custom-date-div" class="container custom-date-div custom-top-message" style="display:none;margin-bottom: 5px;">* <?php echo $this->get_label('custom date message',array('x'=>Configuration::get_instance()->read('daily_based_data_backup_expiry')));?></div>
<div style="height: 20px;"></div>
</div>

<?php $this->dispatch("layout/footer");?>