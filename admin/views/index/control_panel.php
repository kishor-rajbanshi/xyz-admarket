<?php

$this->dispatch("layout/header/15");

$activeadsall=$this->get_variable("activeadsall");
$activehtmlads=$this->get_variable("activehtmlads");
$sitecount=$this->get_variable("sitecount");
$totalusers=$this->get_variable("totalusers");

$cpc_enabled=$this->get_addon_status('cpc_enabled');
$cpa_enabled=$this->get_addon_status('cpa_enabled');
$cpm_enabled=$this->get_addon_status('cpm_enabled');
$html_enabled=$this->get_addon_status('html_enabled');
$sponsored_enabled=$this->get_addon_status('sponsored_enabled');
$interstitial_enabled=$this->get_addon_status('interstitial_enabled');
$pop_enabled=$this->get_addon_status('pop-ads_enabled');
$affiliate_enabled=$this->get_addon_status('affiliate-ads_enabled');
$textimage_enabled=$this->get_addon_status('text-image-ads_enabled');
$ecommerce_enabled=$this->get_addon_status('ecommerce-ads_enabled');
$cpv_enabled=$this->get_addon_status('video-ads_enabled');
$skin_enabled=$this->get_addon_status('skin-ads_enabled');



$category_enabled=$this->get_variable('category_enabled');


$geo_enabled=Configuration::get_instance()->read('countrywise_data_tracking');






	$refunded_amount=$this->get_variable('refunded_amount');
	$refunded_bonus=$this->get_variable('refunded_bonus');




$datatransfer=$this->get_result('datatransfer');
$sumamount=0;
foreach($datatransfer as $k123=>$v123)
{
	$sumamount=$sumamount+$v123['amounts'];
}

$moneygained=$sumamount;


$bonus=$this->get_variable("bonus");
$withdrawed=$this->get_variable("withdrawed");



$account=$this->get_variable("account");

$publishertotal=$withdrawed+$account;

$totalearning=($moneygained+$refunded_bonus)-($refunded_amount+$bonus+$publishertotal);


if($totalearning <0)
$totalearning=0;


$statistics=$this->get_advertiser_statistics(5);

$impression=0;
$clicks=0;
$conversion=0;
$spend=0;
$profit=0;
$balance=0;

if($cpc_enabled ==1)
{
	$impression=$impression+$statistics['impression'];
	$clicks=$clicks+$statistics['click'];
	$spend=$spend+$statistics['money_spent'];
	$profit=$profit+$statistics['pub_profit'];
}

if($cpm_enabled ==1)
{
	$impression=$impression+$statistics['cpm_impression'];
	$clicks=$clicks+$statistics['cpm_click'];
	$spend=$spend+$statistics['cpm_spend'];
	$profit=$profit+$statistics['cpm_profit'];
}


if($cpv_enabled ==1)
{
	$impression=$impression+$statistics['cpv_impression'];
	$clicks=$clicks+$statistics['cpv_click'];
	$spend=$spend+$statistics['cpv_spend'];
	$profit=$profit+$statistics['cpv_profit'];
}




if($html_enabled ==1)
{
	$impression=$impression+$statistics['html_impression'];
	$spend=$spend+$statistics['html_profit'];
	$profit=$profit+$statistics['html_profit'];
}										
						
if($cpa_enabled ==1)
{
	$impression=$impression+$statistics['cpa_impression'];
	$clicks=$clicks+$statistics['cpa_click'];
	$conversion=$conversion+$statistics['cpa_conversion'];
	$spend=$spend+$statistics['cpa_spend'];
	$profit=$profit+$statistics['cpa_profit'];
}

if($affiliate_enabled ==1)
{
	$clicks=$clicks+$statistics['affiliate_click'];
	$conversion=$conversion+$statistics['affiliate_conversion'];
	$spend=$spend+$statistics['affiliate_spend'];
	$profit=$profit+$statistics['affiliate_profit'];
}
													
													
if($pop_enabled ==1)
{
	$impression=$impression+$statistics['pop_impression'];
	$spend=$spend+$statistics['pop_spend'];
	$profit=$profit+$statistics['pop_profit'];
}										


$ctr=0;

if($impression >0 && $clicks >0)
$ctr=round(($clicks/$impression)*100,2);


$ctr_box=0;
$payment_box=1;
$withdrawal_box=0;

if(($cpc_enabled ==1 || $cpm_enabled ==1 || $cpv_enabled ==1) && $cpa_enabled ==0 && $affiliate_enabled ==0 && $category_enabled ==0)
$withdrawal_box=1;


if($cpc_enabled ==1 || $cpm_enabled ==1 || $cpa_enabled ==1 || $cpv_enabled ==1)
$ctr_box=1;



if($ctr_box ==1 && $affiliate_enabled ==1 && $category_enabled ==1)
$ctr_box=0;


if($cpa_enabled ==1 && $category_enabled ==1)
$ctr_box=0;

if($cpc_enabled ==0 && $cpm_enabled ==0 && $cpa_enabled ==0 && $cpv_enabled ==0 && $affiliate_enabled ==0 && $category_enabled ==0) //Only html/pop
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


<?php if($cpc_enabled ==0 && $cpm_enabled ==0 && $cpa_enabled ==0 && $cpv_enabled ==0 && $affiliate_enabled ==0 && $category_enabled ==1){?>
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
<span class="box_td_span"><?php echo $activeadsall+$activehtmlads;?></span>
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
<span class="box_td_span"><?php echo $this->get_money_format($spend);?></span>
<div class="box_td_div"><?php echo $this->get_label('money spend');?></div>
</td>
</tr>
</table>
</div>


<div class="tile-box">
<table>
<tr>
<td class="dash-icon"><i class="fa fa-bank fa-2x" style="background-color:#26C6DA;"></i></td>
<td >
<span class="box_td_span"><?php echo $this->get_money_format($profit);?></span>
<div class="box_td_div"><?php echo $this->get_label('pubprofit');?></div>
</td>
</tr>
</table>
</div>



<div class="tile-box">
<table>
<tr>
<td class="dash-icon"><i class="fa fa-money fa-2x" style="background-color:#1E88E5;"></i></td>
<td >
<span class="box_td_span"><?php echo $this->get_money_format($spend-$profit);?></span>
<div class="box_td_div"><?php echo $this->get_label('earnings');?></div>
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
<span class="box_td_span"><?php echo $this->get_money_format($moneygained);?></span>
<div class="box_td_div"><?php echo $this->get_label('payments');?></div>
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
<span class="box_td_span"><?php echo $this->get_money_format($withdrawed);?></span>
<div class="box_td_div"><?php echo $this->get_label('withdrawals');?></div>
</td>
</tr>
</table>
</div>
<?php }?>





<?php if($cpc_enabled ==1 || $cpm_enabled ==1 || $cpa_enabled ==1 || $cpv_enabled ==1 || $pop_enabled ==1 || $html_enabled ==1){?>
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


<?php if($cpc_enabled ==1 || $cpm_enabled ==1 || $cpa_enabled ==1 || $cpv_enabled ==1 || $affiliate_enabled ==1){?>
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

<?php if($cpa_enabled ==1 || $affiliate_enabled ==1){?>
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
	$results=array();
	
	if($cpc_enabled ==1 || $cpm_enabled ==1 || $html_enabled ==1 || $cpa_enabled ==1 || $pop_enabled ==1 || $cpv_enabled ==1)
	$results=$this->get_top_sites(3,0);
	
	$site_count=count($results);
	
	if($site_count ==0)
	$category_enabled=0;	
}

if($geo_enabled ==1)
{
	$results0=array();
	
	if($cpc_enabled ==1 || $cpm_enabled ==1 || $html_enabled ==1 || $cpa_enabled ==1 || $pop_enabled ==1 || $cpv_enabled ==1)
	$results0=$this->get_top_country_advertisers(3,-1,0,-1);
	else if($affiliate_enabled ==1)
	$results0=$this->get_top_country_advertisers(3,-1,1,-1);
	
	$country_count=count($results0);
	
	if($country_count ==0)
	$geo_enabled=0;
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
		foreach($results as $key=>$value){?>
		
		<?php if($value[3] !=""){?>
		<div style="float: left;width: 90%;padding: 5px;height: 30px;"><i class="fa fa-hand-o-right top-site-fa"></i> &nbsp;<a href="<?php echo $this->make_url("dispatch/category_targeting/22/".$value[2]);?>"><?php echo $value[3];?></a></div>
		
		<?php 
		if($geo_enabled ==1)
		{
			if($ii ==7)
			break;
		}
		else
		{
			if($ii ==12)
			break;
		}
		
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

<?php if($geo_enabled ==1){?>
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