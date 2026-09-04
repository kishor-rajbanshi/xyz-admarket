<?php

$this->dispatch("layout/header/15");

$activeadsall   = intval($this->get_variable("activeadsall"));
$activehtmlads  = intval($this->get_variable("activehtmlads"));
$activefeedads  = intval($this->get_variable("activefeedads"));

$sitecount=$this->get_variable("sitecount");
$totalusers=$this->get_variable("totalusers");

$cpc_enabled=$this->get_addon_status('cpc_enabled');
$cpa_enabled=$this->get_addon_status('cpa_enabled');
$cpm_enabled=$this->get_addon_status('cpm_enabled');
$html_enabled=$this->get_addon_status('html_enabled');
$cpd_enabled=$this->get_addon_status('sponsored_enabled');
$cpp_enabled=$this->get_addon_status('cpp_enabled');



$category_enabled=$this->get_variable('category_enabled');


$geo_enabled        = Configuration::get_instance()->read('countrywise_data_tracking');
$google_map_api_key = Configuration::get_instance()->read('google_map_api_key');

$geo_enabled_map = $geo_enabled;

$refunded_amount=$this->get_variable('refunded_amount');
$refunded_bonus=$this->get_variable('refunded_bonus');

$datatransfer=$this->get_result('datatransfer');
$sumamount=0;
foreach($datatransfer as $k123=>$v123)
{
	$sumamount=$sumamount+$v123['amounts'];
}

$moneygained = $sumamount;

if($refunded_amount > 0)
$moneygained = $moneygained - $refunded_amount;

if($moneygained < 0)
$moneygained = 0;


$bonus=$this->get_variable("bonus");
$withdrawed=$this->get_variable("withdrawed");


$account=$this->get_variable("account");

$publishertotal=$withdrawed+$account;


$impression = 0;
$clicks     = 0;
$ctr 		= 0;
$conversion = 0;
$spend 		= 0;
$profit 	= 0;
$balance 	= 0;

$adResult   = $this->get_array('adResult');

if(count($adResult) > 0)
{
	if(isset($adResult['impression']))
	$impression = $adResult['impression'];

	if(isset($adResult['click']))
	$clicks     = $adResult['click'];

	if(isset($adResult['ctr']))
	$ctr        = $adResult['ctr'];

	if(isset($adResult['conversion']))
	$conversion = $adResult['conversion'];

	if(isset($adResult['spend']))
	$spend      = $adResult['spend'];

	if(isset($adResult['profit']))
	$profit     = $adResult['profit'];

	if(isset($adResult['adminprofit']))
	$balance    = $adResult['adminprofit'];
}


$ctr_box        = 0;
$payment_box    = 1;
$withdrawal_box = 0;

if(($cpc_enabled ==1 || $cpm_enabled ==1 || $cpp_enabled == 1) && $cpa_enabled ==0 && $category_enabled ==0)
$withdrawal_box=1;


if($cpc_enabled ==1 || $cpm_enabled ==1 || $cpa_enabled ==1 || $cpp_enabled == 1)
$ctr_box=1;



if($ctr_box ==1 && $category_enabled ==1)
$ctr_box=0;


if($cpa_enabled ==1 && $category_enabled ==1)
$ctr_box=0;

if($cpc_enabled ==0 && $cpm_enabled ==0 && $cpa_enabled ==0 && $cpp_enabled == 1 && $category_enabled ==0) //Only html
$payment_box=0;

?>
<?php if($payment_box ==0){?>
<style type="text/css">
.tile-box
{
	width:31%;
	margin-right: 20px;
}
</style>
<?php }?>


<?php if($cpc_enabled ==0 && $cpm_enabled ==0 && $cpa_enabled ==0 && $cpp_enabled == 1 && $category_enabled ==1){?>
<style type="text/css">
.tile-box
{
	width:23%;
}
</style>
<?php }?>

<style type="text/css">
.ad_report_td_content
{
	border:0px !important;
	border-bottom:1px solid #CCCCCC !important;
}
</style>

<div class="sub_menu_main"><?php echo $this->get_label('home');?></div>

<div class="home-top-box">


<div class="tile-box">
<table>
<tr>
<td class="dash-icon"><i class="fa fa-users fa-2x" style="background-color:#cf2555;"></i></td>
<td >
<span class="box_td_span"><?php echo $totalusers;?></span>
<div class="box_td_div"><a href="<?php echo $this->make_url('user/list');?>"><?php echo $this->get_label('users');?></a></div>
</td>
</tr>
</table>
</div>



<div class="tile-box">
<table>
<tr>
<td class="dash-icon"><i class="fa fa-bullhorn fa-2x" style="background-color:#1b96d1;"></i></td>
<td >
<span class="box_td_span"><?php echo $activeadsall+$activehtmlads+$activefeedads;?></span>
<div class="box_td_div"><a href="<?php echo $this->make_url('ad/list');?>"><?php echo $this->get_label('ads');?></a></div>
</td>
</tr>
</table>
</div>



<div class="tile-box">
<table>
<tr>
<td class="dash-icon"><i class="fa fa-suitcase fa-2x" style="background-color:#FFB22B;"></i></td>
<td >
<span class="box_td_span"><?php echo $spend;?></span>
<div class="box_td_div"><?php echo $this->get_label('money spend');?> (<?php echo Configuration::get_instance()->read('currency_symbol'); ?>)</div>
</td>
</tr>
</table>
</div>


<div class="tile-box">
<table>
<tr>
<td class="dash-icon"><i class="fa fa-bank fa-2x" style="background-color:#26C6DA;"></i></td>
<td >
<span class="box_td_span"><?php echo $profit;?></span>
<div class="box_td_div"><?php echo $this->get_label('pubprofit');?> (<?php echo Configuration::get_instance()->read('currency_symbol'); ?>)</div>
</td>
</tr>
</table>
</div>



<div class="tile-box">
<table>
<tr>
<td class="dash-icon"><i class="fa fa-money fa-2x" style="background-color:#1E88E5;"></i></td>
<td >
<span class="box_td_span"><?php echo $balance;?></span>
<div class="box_td_div"><?php echo $this->get_label('earnings');?> (<?php echo Configuration::get_instance()->read('currency_symbol'); ?>)</div>
</td>
</tr>
</table>
</div>


<?php if($category_enabled ==1){?>
<div class="tile-box">
<table>
<tr>
<td class="dash-icon"><i class="fa fa-sitemap fa-2x" style="background-color:#1a981a;"></i></td>
<td >
<span class="box_td_span"><?php echo $sitecount;?></span>
<div class="box_td_div"><a href="<?php echo $this->make_url("dispatch/category_targeting/6");?>"><?php echo $this->get_label('sites');?></a></div>
</td>
</tr>
</table>
</div>
<?php }?>



<?php if($payment_box ==1){?>
<div class="tile-box">
<table>
<tr>
<td class="dash-icon"><i class="fa fa-money fa-2x" style="background-color:#6440BE;"></i></td>
<td >
<span class="box_td_span"><?php echo $this->get_number_format($moneygained);?></span>
<div class="box_td_div"><?php echo $this->get_label('payments');?> (<?php echo Configuration::get_instance()->read('currency_symbol'); ?>)</div>
</td>
</tr>
</table>
</div>
<?php }?>




<?php if($withdrawal_box ==1){?>
<div class="tile-box">
<table>
<tr>
<td class="dash-icon"><i class="fa fa-credit-card fa-2x" style="background-color:#FC4B6C;"></i></td>
<td >
<span class="box_td_span"><?php echo $this->get_number_format($withdrawed);?></span>
<div class="box_td_div"><?php echo $this->get_label('withdrawals');?> (<?php echo Configuration::get_instance()->read('currency_symbol'); ?>)</div>
</td>
</tr>
</table>
</div>
<?php }?>





<?php if($cpc_enabled ==1 || $cpm_enabled ==1 || $cpa_enabled ==1 || $html_enabled ==1 || $cpp_enabled == 1){?>
<div class="tile-box">
<table>
<tr>
<td class="dash-icon"><i class="fa fa-eye fa-2x" style="background-color:#2ecc71;"></i></td>
<td >
<span class="box_td_span"><?php echo $impression;?></span>
<div class="box_td_div"><?php echo $this->get_label('impressions');?></div>
</td>
</tr>
</table>
</div>
<?php }?>


<?php if($cpc_enabled ==1 || $cpm_enabled ==1 || $cpa_enabled ==1 || $cpp_enabled == 1){?>
<div class="tile-box">
<table>
<tr>
<td class="dash-icon"><i class="fa fa-hand-o-right fa-2x" style="background-color:#1b96d1;"></i></td>
<td >
<span class="box_td_span"><?php echo $clicks;?></span>
<div class="box_td_div"><?php echo $this->get_label('clicks');?></div>
</td>
</tr>
</table>
</div>
<?php }?>



<?php if($ctr_box ==1){?>
<div class="tile-box">
<table>
<tr>
<td class="dash-icon"><i class="fa fa-line-chart fa-2x" style="background-color:#6440BE;"></i></td>
<td >
<span class="box_td_span"><?php echo $ctr;?></span>
<div class="box_td_div"><?php echo $this->get_label('ctr');?></div>
</td>
</tr>
</table>
</div>
<?php }?>

<?php if($cpa_enabled ==1){?>
<div class="tile-box">
<table>
<tr>
<td class="dash-icon"><i class="fa fa-hand-o-right fa-2x" style="background-color:#F55753;"></i></td>
<td >
<span class="box_td_span"><?php echo $conversion;?></span>
<div class="box_td_div"><?php echo $this->get_label('conversions');?></div>
</td>
</tr>
</table>
</div>
<?php }?>


</div>



<table style="width: 100%;">
<tr>
<td>
<iframe frameborder="0" style="width: 100%;height: 225px;" src="<?php echo $this->make_url("index/statistics");?>" allowtransparency="true" scrolling="no"></iframe>
</td>
</tr>
</table>

<?php
if($category_enabled ==1)
{
	$results = array();

	if($cpc_enabled ==1 || $cpm_enabled ==1 || $html_enabled ==1 || $cpa_enabled ==1 || $cpd_enabled == 1 || $cpp_enabled == 1)
	$results    = $this->get_array("siteArray");

	$site_count = count($results);

	if($site_count == 0)
	$category_enabled = 0;
}

if($geo_enabled ==1)
{
	$results0 = array();

	if($cpc_enabled ==1 || $cpm_enabled ==1 || $html_enabled ==1 || $cpa_enabled ==1 || $cpd_enabled == 1 || $cpp_enabled == 1)
	$results0      = $this->get_array("geoArray");

	$country_count = count($results0);

	if($country_count == 0)
	$geo_enabled = 0;
}
?>

<div style="width: 100%;">

<table style="width: 100%;" cellpadding="0" cellspacing="0">
<tr>
<td style="<?php if($geo_enabled ==1 || $category_enabled ==1){?>width: 71%;<?php }else{?>width: 100%;<?php }?>vertical-align: top;">
<iframe id="graph-frame" frameborder="0" style="width: 100%;height: 570px;" src="<?php echo $this->make_url("index/graph");?>" allowtransparency="true" scrolling="no"></iframe>
</td>
<?php
if($geo_enabled ==1 || $category_enabled ==1){?>
<td style="width: 1%;"></td>

<td style="width: 28%;vertical-align: top;">

<?php if($geo_enabled ==1){?>
		<div class="top_country_box" style="<?php if($category_enabled ==1){?>height: 230px;<?php }else{?>height: 530px;<?php }?>">






	    <div class="toppers-head"><i class="fa fa-rocket" title="<?php echo $this->get_label('top countries');?>"></i><?php echo $this->get_label('top countries');?>
	    <span class="notification"><?php echo $this->get_label('last 30');?></span>
	    </div>


		<?php
		$ii=0;
		foreach($results0 as $key=>$value){?>

		<?php if($value[0] !=""){?>
		<div style="float: left;<?php if(($country_count <=5 && $category_enabled ==1) || ($country_count <=12 && $category_enabled ==0)){?>width: 90%;<?php }else{?>width: 45%;<?php }?>padding: 5px;height: 30px;">
		<div class="country-flag-box"><img src="<?php echo BASE.'/images/flags/'.strtolower($value[0]).'.png';?>" /></div><div class="country-name-box"><?php echo $value[1];?></div>
		</div>

		<?php
		if($category_enabled ==1)
		{
			if($ii ==9)
			break;
		}
		else
		{
			if($ii ==23)
			break;
		}

		$ii=$ii+1;

		}
		}?>
		</div>
<?php }

if($category_enabled ==1){?>

		<div class="top_site_box" style="<?php if($geo_enabled ==1){?>height: 285px;<?php }else{?>height: 530px;<?php }?>">


		<div class="toppers-head"><i class="fa fa-rocket" title="<?php echo $this->get_label('top sites');?>"></i><?php echo $this->get_label('top sites');?>
		<span class="notification"><?php echo $this->get_label('last 30');?></span>
		</div>



		<?php
		$ii=0;
		foreach($results as $key=>$value){

		if($geo_enabled ==1)
		{
			if($ii ==6)
			break;
		}
		else
		{
			if($ii ==12)
			break;
		}

		?>

		<?php if($value[0] > 0){?>
		<div style="float: left;width: 90%;padding: 5px;height: 30px;"><i class="fa fa-hand-o-right top-site-fa"></i> &nbsp;<a href="<?php echo $this->make_url("dispatch/category_targeting/22/".$value[0]);?>"><?php echo $value[1];?></a></div>

		<?php

		$ii=$ii+1;
		}
		}?>
		</div>
<?php }?>

</td>
<?php }?>

</tr>

<tr>
<td></td>
</tr>
</table>

</div>

<?php if($geo_enabled_map ==1 && $google_map_api_key != ""){?>	
<div style="width: 100%;">
<iframe frameborder="0" style="width: 100%;height: 540px;" src="<?php echo $this->make_url("index/country_index");?>" allowtransparency="true" scrolling="no"></iframe>
</div>
<?php }?>

<table style="width: 100%;margin-top: 10px;">
<tr>
<td>
<iframe frameborder="0" width="100%" height="650px;" src="<?php echo $this->make_url("index/top_list");?>" allowtransparency="true" scrolling="no"></iframe>
</td>
</tr>
</table>

<?php $this->dispatch("layout/footer");?>