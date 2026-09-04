<?php 
$this->dispatch("layout/header/16");

$sponsored_enabled = $this->get_addon_status('sponsored_enabled');
$category_enabled  = $this->get_addon_status('category-targeting_enabled');

$cron_running_time=$this->get_variable("cron_running_time");
$minute_cron_running_time=$this->get_variable("minute_cron_running_time");

$minute_cron_running_time_original=$this->get_variable("minute_cron_running_time_original");
$withdrawalcron_success_time=$this->get_variable("wtime");

$current_hour =date("Y",time());
$current_hour.=date("m",time());
$current_hour.=date("d",time());
$current_hour.=date("H",time());

$previous_hour=$this->get_previous_hour($current_hour);


$current_month =date("Y",time());
$current_month.=date("m",time());

$previous_month=$this->get_previous_month($current_month);


$requestcount=$this->get_variable('requestcount');


$newsletter_addon_folder=$this->xyz_get_addon_folder_name("XYZADMNLR");

$newsletter_enabled=$this->get_addon_status($newsletter_addon_folder.'_enabled');
$newsletter_cron_running_time=$this->get_variable("news_cron_running_time");

$pendingadv=$this->get_variable("pendingadv");
$pendingpub=$this->get_variable("pendingpub");
$pendingads=$this->get_variable("pendingads");
$cachecount=$this->get_variable("cachecount");
$pendingsites=$this->get_variable('pendingsites');


$city_enabled=$this->get_variable("city_enabled");
$geo_modify_time_ip=$this->get_variable("geo_modify_time_ip");
$geo_modify_time_city=$this->get_variable("geo_modify_time_city");


if($this->get_addon_status('subadmin_enabled') ==1 && isset($GLOBALS['privilege']))
$privilege=$GLOBALS['privilege'];
else
$privilege=array();




?>
<style>
.todotable a
{
color: red;
font-weight: bold;
}
.todotable
{
font-size: 13px;
}
</style>

<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0){?>


<div class="sub_menu_main"><?php echo $this->get_label('admin todo');?></div>  


<div class="php-version">
<?php echo $this->get_label('php version');?> : 
<?php 
$phpversion=explode('.',PHP_VERSION);
echo $phpversion_data=$phpversion[0].'.'.$phpversion[1];
?>
</div>


<?php $this->dispatch("links/links/1");?>





<?php }else{?>
<div class="sub_menu_main"><?php echo $this->get_label('subadmin todo');?>


<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==2 ) 
		echo ': '.$this->get_label('advertiser account manager');
	elseif ($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==3)
		echo  ': '.$this->get_label('publisher account manager');
	?>

</div>
<?php }?>



<?php 
if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==2 || $this->read_cookie_param(COOKIE_ADMIN_TYPE) ==3)
{
	
$activeadv=$this->get_variable("activeadv");
$activepub=$this->get_variable("activepub");
$totalads=$this->get_variable("totalads");
$activeads=$this->get_variable("activeads");
$activeadsall=$this->get_variable("activeadsall");
$totalactiveads=$this->get_variable("totalactiveads");


$cpa_enabled=$this->get_addon_status('cpa_enabled');
$cpm_enabled=$this->get_addon_status('cpm_enabled');
$html_enabled=$this->get_addon_status('html_enabled');
$interstitial_enabled=$this->get_addon_status('interstitial_enabled');
$skin_enabled=$this->get_addon_status('skin-ads_enabled');


$pop_enabled=$this->get_addon_status('pop-ads_enabled');
$affiliate_enabled=$this->get_addon_status('affiliate-ads_enabled');

$textimage_enabled=$this->get_addon_status('text-image-ads_enabled');


$totalusers=$this->get_variable("totalusers");
$totaladcodes=$this->get_variable("totaladcodes");

?>

<style>
.ad_report_td_content
{

border:0px !important;
border-bottom:1px solid #CCCCCC !important;

}
</style>






<div class="ad_report" style="margin-bottom: 20px;">

<table cellpadding="0" cellspacing="0" style="width: 100%;" class="report-outer">
<tr ><td colspan="7" class="ad_report_td_head"><?php echo $this->get_label('your system details');?></td></tr>
<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==2){?>
<tr >
<td class="ad_report_td_content"><?php echo $this->get_label('active adv');?></td>

<td class="ad_report_td_content"><a href="<?php echo $this->make_url("user/list/1")?>"><?php echo $activeadv;?></a></td>

<td class="ad_report_td_content"><?php echo $this->get_label('pending advertisers');?></td>
<td class="ad_report_td_content"><a href="<?php echo $this->make_url("user/list/-1")?>"><?php echo $pendingadv;?></a></td>

</tr>


<tr >
<td class="ad_report_td_content"><?php echo $this->get_label('active ads');?></td>
<td class="ad_report_td_content"><a href="<?php echo $this->make_url("ad/list/1")?>"><?php echo $totalactiveads;?></a></td>
<td class="ad_report_td_content"><?php echo $this->get_label('pending ads');?></td>
<td class="ad_report_td_content"><a href="<?php echo $this->make_url("ad/list/-1")?>"><?php echo $pendingads;?></a></td>
</tr>
<tr>
<td class="ad_report_td_content"><?php echo $this->get_label('total no ads ppc');?></td>
<td class="ad_report_td_content"><?php echo $totalads;?></td>
<td class="ad_report_td_content"><?php echo $this->get_label('active ads ppc');?></td>
<td class="ad_report_td_content"><?php echo $activeads;?></td>

</tr>
<?php if($cpm_enabled ==1){

	
	$totaladscpm=$this->get_variable("totaladscpm");
	$activeadscpm=$this->get_variable("activeadscpm");
	$activetextadscpm=$this->get_variable("activetextadscpm");
	$activebanneradscpm=$this->get_variable("activebanneradscpm");
	
	?>
<tr>
<td class="ad_report_td_content"><?php echo $this->get_label('total no ads cpm');?></td>
<td class="ad_report_td_content"><?php echo $totaladscpm;?></td>
<td class="ad_report_td_content"><?php echo $this->get_label('active ads cpm');?></td>
<td class="ad_report_td_content"><?php echo $activeadscpm;?></td>

</tr>



<?php }?>

<?php if($sponsored_enabled ==1){
	$sponsoredads=$this->get_variable('sponsoredads');
	$activesponsoredads=$this->get_variable('activesponsoredads');
	?>
<tr>
<td class="ad_report_td_content" ><?php echo $this->get_label('sponsored ads');?></td>
<td class="ad_report_td_content" ><?php echo $sponsoredads;?></td>
<td class="ad_report_td_content" ><?php echo $this->get_label('active sponsored ads');?></td>
<td class="ad_report_td_content" ><?php echo $activesponsoredads;?></td>
</tr>

<?php }?>



<?php if($interstitial_enabled ==1){
	$interstitialads=$this->get_variable('interstitialads');
	$activeinterstitialads=$this->get_variable('activeinterstitialads');
	?>
<tr>
<td class="ad_report_td_content" ><?php echo $this->get_label('interstitial ads');?></td>
<td class="ad_report_td_content" ><?php echo $interstitialads;?></td>
<td class="ad_report_td_content" ><?php echo $this->get_label('active interstitial ads');?></td>
<td class="ad_report_td_content" ><?php echo $activeinterstitialads;?></td>
</tr>
<?php }?>


<?php if($pop_enabled ==1){
	$popads=$this->get_variable('popads');
	$activepopads=$this->get_variable('activepopads');
	?>
<tr>
<td class="ad_report_td_content" ><?php echo $this->get_label('pop ads');?></td>
<td class="ad_report_td_content" ><?php echo $popads;?></td>
<td class="ad_report_td_content" ><?php echo $this->get_label('active pop ads');?></td>
<td class="ad_report_td_content" ><?php echo $activepopads;?></td>
</tr>
<?php }?>





<?php if($cpa_enabled ==1){

	
	$totaladscpa=$this->get_variable("totaladscpa");
	$activeadscpa=$this->get_variable("activeadscpa");
	$activetextadscpa=$this->get_variable("activetextadscpa");
	$activebanneradscpa=$this->get_variable("activebanneradscpa");
	
	?>
<tr>
<td class="ad_report_td_content"><?php echo $this->get_label('total no ads cpa');?></td>
<td class="ad_report_td_content"><?php echo $totaladscpa;?></td>
<td class="ad_report_td_content"><?php echo $this->get_label('active ads cpa');?></td>
<td class="ad_report_td_content"><?php echo $activeadscpa;?></td>

</tr>






<?php }?>




<?php if($affiliate_enabled ==1){

	$totaladsaffiliate=$this->get_variable("totaladsaffiliate");
	$activeadsaffiliate=$this->get_variable("activeadsaffiliate");
	?>
<tr>
<td class="ad_report_td_content"><?php echo $this->get_label('total no ads affiliate');?></td>
<td class="ad_report_td_content"><?php echo $totaladsaffiliate;?></td>
<td class="ad_report_td_content"><?php echo $this->get_label('active ads affiliate');?></td>
<td class="ad_report_td_content"><?php echo $activeadsaffiliate;?></td>


</tr>

<?php }?>
<?php if( isset($privilege['ap_1']))
{?>
<?php 
$rowresult=$this->get_result('rowresult');

$ii=0;
foreach($rowresult as $key=>$value)
{
	$pendingcount=$this->get_pending_payment_count($value['id']);
	
	if($ii ==0){?>
	<tr>
	<?php }?>	
	
	<td class="ad_report_td_content"><?php echo $this->get_label('pending payments of advertisers',array('x'=>$value['name']));?></td>
	<td class="ad_report_td_content"><a href="<?php echo $this->make_url("advertiser/payments/-1/".$value['id']);?>"><?php echo $pendingcount;?></a></td>

	<?php 
	
	$ii++;
	
	if($ii ==2)
	{
		$ii=0;
		?>
		</tr>
		<?php 
	}
}

if($ii ==1)
{
	?>
	</tr>
	<?php 	
}
?>
<?php }?>


<?php }
else if($this->read_cookie_param(COOKIE_ADMIN_TYPE)==3){?>

<tr>


<td class="ad_report_td_content" ><?php echo $this->get_label('active pub');?></td>
<td class="ad_report_td_content"><a href="<?php echo $this->make_url("user/list/1/2")?>"><?php echo $activepub;?></a></td>

<td class="ad_report_td_content"><?php echo $this->get_label('pending publishers');?></td>
<td class="ad_report_td_content"><a href="<?php echo $this->make_url("user/list/-1/2")?>"><?php echo $pendingpub;?></a></td>

</tr>


<tr>
<td class="ad_report_td_content"><?php echo $this->get_label('total no adcodes');?></td>
<td class="ad_report_td_content"><a href="<?php echo $this->make_url("adunit/manage-user-adcode")?>"><?php echo $totaladcodes;?></a></td>
<td class="ad_report_td_content"><?php echo $this->get_label('total pending sites');?></td>
<td class="ad_report_td_content"><a href="<?php echo $this->make_url("dispatch/category-targeting/6/-1/0/-1")?>"><?php echo $pendingsites;?></a></td>

</tr>

<?php if(isset($privilege['pw_1'])){?>
<?php 
$rowresult123=$this->get_result('rowresult123');

$ii=0;
foreach($rowresult123 as $key=>$value)
{
	$pendingcount=$this->get_pending_withdrawal_count($value['id']);
	
	if($ii ==0){?>
	<tr>
	<?php }?>
	
	
	<td  class="ad_report_td_content" ><?php 
	if($value['id'] !=5)
	echo $this->get_label('pending withdrawals',array('x'=>$value['name']));
	else 
	echo $this->get_label('pending fund transfer');
	
	?></td>
	<td class="ad_report_td_content"><a href="<?php echo $this->make_url("user/withdrawal_history/-1/".$value['id']);?>"><?php echo $pendingcount;?></a></td>
	
	<?php 
	
	$ii++;
	
	if($ii ==2)
	{
		$ii=0;
		?>
		</tr>
		<?php 
	}
}

if($ii ==1)
{
	?>
	</tr>
	<?php 	
}
?>
<?php }?>

<?php }?>

</table>


</div>



<?php 
$cpm_type=0;
if($cpm_enabled ==1 || $interstitial_enabled ==1)
$cpm_type=1;






/*

if($cpm_type ==1 && $html_enabled ==1 && $sponsored_enabled ==1)
$iframe_height=322;
else if($cpm_type ==1 && $html_enabled ==1)
$iframe_height=254;
else if($cpm_type ==1 && $sponsored_enabled ==1)
$iframe_height=254;
else if($cpm_type ==1 || $html_enabled ==1)
$iframe_height=222;
else
$iframe_height=160;



if($cpa_enabled ==1)
$iframe_height=$iframe_height+110;

if($pop_enabled ==1)
$iframe_height=$iframe_height+110;

if($affiliate_enabled ==1)
$iframe_height=$iframe_height+110;





$iframe_height=$iframe_height.'px';

?>
<div>
<iframe frameborder="0" width="100%" height="<?php echo $iframe_height;?>" src="<?php echo $this->make_url("index/statistics");?>" allowtransparency="true" scrolling="no"></iframe>

</div>



<?php */
}


else {


?>


<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0 || isset($privilege['mu_1'])){?>
<div class="inner-box todo-box todo-box-report" style="width: 48%;float: left;">
<div class="todo_div">

<div class="todo-head"><i class="fa fa-tasks" title="<?php echo $this->get_label('pending users');?>"></i><?php echo $this->get_label('pending users');?></div>


<table class="data_table" cellpadding="0" cellspacing="0">
<tr class="data_table_head">

<td style="width: 150px;"><?php echo $this->get_label('advertisers');?></td>
<td style="width: 150px;"><?php echo $this->get_label('publishers');?></td>
<td></td>
</tr>

<tr class="data_table_content pending-operations">
<td><a href="<?php echo $this->make_url("user/list/-1/1");?>"><div class="pending-box" style="background-color: <?php if($pendingadv >0){?>#CF2555<?php }else{?>#1A981A<?php }?>;"><?php echo $pendingadv;?></div></a></td>
<td><a href="<?php echo $this->make_url("user/list/-1/2");?>"><div class="pending-box" style="background-color: <?php if($pendingpub >0){?>#CF2555<?php }else{?>#1A981A<?php }?>;"><?php echo $pendingpub;?></div></a></td>
<td></td>
</tr>
</table>
</div>
</div>

<?php }?>



<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0 || isset($privilege['aa_1']) || isset($privilege['ms_1'])){?>
<div class="inner-box todo-box todo-box-report" style="width: 48%;float: right;">
<div class="todo_div">

<?php 
$heading="";
if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0 || (isset($privilege['aa_1']) && isset($privilege['ms_1'])))
{
	if($category_enabled ==1)
	$heading=$this->get_label('pending ads websites');
	else
	$heading=$this->get_label('pending ads');
}
else if(isset($privilege['aa_1']))
{
	$heading=$this->get_label('pending ads');
}
else if(isset($privilege['ms_1']) && $category_enabled ==1)
{
	$heading=$this->get_label('pending websites');
}
?>

<div class="todo-head"><i class="fa fa-tasks" title="<?php echo $heading;?>"></i><?php echo $heading;?></div>


<table class="data_table" cellpadding="0" cellspacing="0">
<tr class="data_table_head">

<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0 || isset($privilege['aa_1'])){?>
<td style="width: 150px;"><?php echo $this->get_label('ads');?></td>
<?php }?>

<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0 || isset($privilege['ms_1'])){?>
<?php if($category_enabled ==1){?>
<td style="width: 150px;"><?php echo $this->get_label('websites');?></td>
<?php }}?>


<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0 || isset($privilege['aa_1'])){?>
<?php if($sponsored_enabled ==1){?>
<td style="width: 150px;"><?php echo $this->get_label('cpd cancel requests');?></td>
<?php }}?>

<td></td>
</tr>

<tr class="data_table_content pending-operations">
<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0 || isset($privilege['aa_1'])){?>
<td><a href="<?php echo $this->make_url("ad/list/-1");?>"><div class="pending-box" style="background-color: <?php if($pendingads >0){?>#CF2555<?php }else{?>#1A981A<?php }?>;"><?php echo $pendingads;?></div></a></td>
<?php }?>

<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0 || isset($privilege['ms_1'])){?>
<?php if($category_enabled ==1){?>
<td><a href="<?php echo $this->make_url("dispatch/category_targeting/6/-1/0/-1");?>"><div class="pending-box" style="background-color: <?php if($pendingsites >0){?>#CF2555<?php }else{?>#1A981A<?php }?>;"><?php echo $pendingsites;?></div></a></td>
<?php }}?>

<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0 || isset($privilege['aa_1'])){?>
<?php if($sponsored_enabled ==1){?>
<td><a href="<?php echo $this->make_url("dispatch/sponsored/30");?>"><div class="pending-box" style="background-color: <?php if($requestcount >0){?>#CF2555<?php }else{?>#1A981A<?php }?>;"><?php echo $requestcount;?></div></a></td>
<?php }}?>
<td></td>
</tr>
</table>

</div>
</div>
<?php }?>



<?php 
$color_array[]='#1A981A';
$color_array[]='#CF2555';
$color_array[]='#26C6DA';
$color_array[]='#1F9DFE';
$color_array[]='#6440BE';
$color_array[]='#FFB22B';
$color_array[]='#F55753';
$color_array[]='#1B96D1';
?>


<div class="todo-box todo-box-report" style="float: left;width: 100%;border: 0px;margin-bottom: 10px;">

<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0 || isset($privilege['ap_1'])){?>

<?php 
$rowresult=$this->get_result('rowresult');

if(count($rowresult) >0){?>

<div class="inner-box todo-box todo-box-report" style="width: 48%;float: left;">
<div class="todo_div">

<div class="todo-head"><i class="fa fa-tasks" title="<?php echo $this->get_label('pending payments');?>"></i><?php echo $this->get_label('pending payments');?></div>



<table class="data_table" cellpadding="0" cellspacing="0">
<tr class="data_table_head">
<?php foreach($rowresult as $key=>$value){?>
<td style="width: 70px;"><?php echo ucfirst($value['name']);?></td>
<?php }?>
<td></td>
</tr>
<tr class="data_table_content pending-operations">

<?php 
foreach($rowresult as $key=>$value)
{
	$pendingcount=$this->get_pending_payment_count($value['id']);
	
	if($pendingcount >0)
	$i=1;
	else
	$i=0;
	
	?>
	<td><div class="pending-box" style="background-color: <?php echo $color_array[$i];?>;"><a href="<?php echo $this->make_url("advertiser/payments/-1/".$value['id']);?>"><?php echo $pendingcount;?></a></div></td>
	<?php 
}
?>
<td></td>
</tr>
</table>

</div>
</div>
<?php }}?>

<?php 
if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0 || isset($privilege['pw_1'])){?>

<?php 
$rowresult123=$this->get_result('rowresult123');

if(count($rowresult123) >0){?>

<div class="inner-box todo-box todo-box-report" style="width: 48%;float: right;">
<div class="todo_div">

<div class="todo-head"><i class="fa fa-tasks" title="<?php echo $this->get_label('pending payouts');?>"></i><?php echo $this->get_label('pending payouts');?></div>


<table class="data_table" cellpadding="0" cellspacing="0">
<tr class="data_table_head">
<?php foreach($rowresult123 as $key=>$value){?>
<td style="width: 90px;"><?php echo ucfirst($value['name']);?></td>
<?php }?>
<td></td>
</tr>
<tr class="data_table_content pending-operations">
<?php 
foreach($rowresult123 as $key=>$value)
{
	$pendingcount=$this->get_pending_withdrawal_count($value['id']);
	
	if($pendingcount >0)
	$i=1;
	else
	$i=0;
		
	?>
	<td><div class="pending-box" style="background-color: <?php echo $color_array[$i];?>;"><a href="<?php echo $this->make_url("user/withdrawal_history/-1/".$value['id']);?>"><?php echo $pendingcount;?></a></div></td>
	<?php 
}
?>
<td></td>
</tr>
</table>

</div>
</div>
<?php }}?>

</div>


<?php 
if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0){

	
	$year	=substr($minute_cron_running_time_original,0,4);
	$month	=substr($minute_cron_running_time_original,4,2);
	$day	=substr($minute_cron_running_time_original,6,2);
	$hour	=substr($minute_cron_running_time_original,8,2);
	$minute =substr($minute_cron_running_time_original,10,2);

	$data_minute=$this->get_date_format(1,$year,$month,$day,$hour,$minute).':'.$minute;
	
	
	
	$year1	=substr($cron_running_time,0,4);
	$month1	=substr($cron_running_time,4,2);
	$day1	=substr($cron_running_time,6,2);
	$hour1	=substr($cron_running_time,8,2);

	$data_hourly=$this->get_date_format(1,$year1,$month1,$day1,$hour1);
	
	
	?>

<div class="inner-box todo-box todo-box-report" style="margin-top: 400px;">
<div class="todo_div">

<div class="todo-head"><i class="fa fa-tasks" title="<?php echo $this->get_label('system cron management');?>"></i><?php echo $this->get_label('system cron management');?></div>


<table class="data_table" cellpadding="0" cellspacing="0">
<tr class="data_table_head">
<td style="width: 110px;"><?php echo $this->get_label('cron name');?></td>
<td style="width: 100px;"><?php echo $this->get_label('last executed');?></td>
<td style="width: 180px;"><?php echo $this->get_label('status');?></td>
<td><?php echo $this->get_label('command');?></td>
</tr>


<tr class="data_table_content pending-operations">
<td><?php echo $this->get_label('data minute');?></td>
<td>
<?php 
if($year >0)
echo $data_minute;
else
echo '-';
?>
</td>
<td >
<?php if($minute_cron_running_time < $current_hour){?>
<div>
<div class="cron-failed"><?php echo $this->get_label('failed');?> - 
<span class="cron-execution"><a target="_blank" href="<?php echo $this->make_base_url("minutecron/data_backup",CRON_DIR);?>"><?php echo $this->get_label('execute manually');?></a></span>
</div>
</div>
<?php }else{?>
<div class="cron-success"><?php echo $this->get_label('success');?></div>
<?php }?>
</td>
<td> 
<span class="notification">
<?php echo "wget -O /dev/null --quiet ".$this->make_base_url("minutecron/data_backup",CRON_DIR);?>&nbsp;&nbsp;&nbsp;<span style="color: green;"><?php echo $this->get_label('set for every minute');?></span>
</span>
</td>
</tr>


<tr class="data_table_content pending-operations">
<td><?php echo $this->get_label('data hourly');?></td>
<td>
<?php 
if($year1 >0)
echo $data_hourly;
else
echo '-';
?>
</td>
<td >
<?php if($cron_running_time < $previous_hour){?>
<div>
<div class="cron-failed"><?php echo $this->get_label('failed');?> - 
<span class="cron-execution"><a target="_blank" href="<?php echo $this->make_base_url("datacron/data_backup",CRON_DIR);?>"><?php echo $this->get_label('execute manually');?></a></span>
</div>
</div>
<?php }else{?>
<div class="cron-success"><?php echo $this->get_label('success');?></div>
<?php }?>
</td>
<td> 
<span class="notification">
<?php echo "wget -O /dev/null --quiet ".$this->make_base_url("datacron/data_backup",CRON_DIR);?>&nbsp;&nbsp;&nbsp;<span style="color: green;"><?php echo $this->get_label('set for every hour');?></span>
</span>
</td>
</tr>

<?php if($newsletter_enabled ==1){

	$year11	 =substr($newsletter_cron_running_time,0,4);
	$month11 =substr($newsletter_cron_running_time,4,2);
	$day11	 =substr($newsletter_cron_running_time,6,2);
	$hour11	 =substr($newsletter_cron_running_time,8,2);

	$data_newsletter=$this->get_date_format(1,$year11,$month11,$day11,$hour11);	
	
	?>
<tr class="data_table_content pending-operations">
<td><?php echo $this->get_label('newsletter');?></td>
<td>
<?php 
if($year11 >0)
echo $data_newsletter;
else
echo '-';
?>
</td>
<td >
<?php if($newsletter_cron_running_time < $previous_hour){?>
<div>
<div class="cron-failed"><?php echo $this->get_label('failed');?> - 
<span class="cron-execution"><a target="_blank" href="<?php echo $this->make_base_url("dispatch/newsletter-admarket/57",ADMIN_DIR);?>"><?php echo $this->get_label('execute manually');?></a></span>
</div>
</div>
<?php }else{?>
<div class="cron-success"><?php echo $this->get_label('success');?></div>
<?php }?>
</td>
<td> 
<span class="notification">
<?php echo "wget -O /dev/null --quiet ".$this->make_base_url("dispatch/newsletter-admarket/57",ADMIN_DIR);?>&nbsp;&nbsp;&nbsp;<span style="color: green;"><?php echo $this->get_label('set for every hour');?></span>
</span>
</td>
</tr>
<?php }?>





<?php if(Configuration::get_instance()->read('enable_auto_withdrawal')==0)
{

	$year111	= substr($withdrawalcron_success_time,0,4);
	$month111   = substr($withdrawalcron_success_time,4,2);
	$day111	 	= substr($withdrawalcron_success_time,6,2);
	$hour111	= substr($withdrawalcron_success_time,8,2);

	$data_withdrawal=$this->get_date_format(1,$year111,$month111,$day111,$hour111);		
	
	$withdrawal_month=substr($withdrawalcron_success_time,0,4).substr($withdrawalcron_success_time,4,2);
	
	
	$success_month=$year111.$month111;
	$success_previous_month=$this->get_previous_month($success_month);
	
	$success_previous_month=$this->get_date_format(1,substr($success_previous_month,0,4),substr($success_previous_month,4,2));		
	
	?>


<tr class="data_table_content pending-operations">
<td><?php echo $this->get_label('withdrawal cron');?></td>
<td>
<?php 
if($year111 >0)
{
echo $data_withdrawal;?>

<div><?php echo $this->get_label('upto');?>&nbsp;<?php echo $success_previous_month;?></div>
<?php }
else
echo '-';
?>
</td>
<td >
<?php if($withdrawal_month < $previous_month){?>
<div>
<div class="cron-failed"><?php echo $this->get_label('failed');?> - 
<span class="cron-execution"><a target="_blank" href="<?php echo $this->make_base_url("withdrawalcron/auto_request",CRON_DIR);?>"><?php echo $this->get_label('execute manually');?></a></span>
</div>
</div>
<?php }else{?>
<div class="cron-success"><?php echo $this->get_label('success');?></div>
<?php }?>
</td>
<td> 
<span class="notification">
<?php echo "wget -O /dev/null --quiet ".$this->make_base_url("withdrawalcron/auto_request",CRON_DIR);?>&nbsp;&nbsp;&nbsp;<span style="color: green;"><?php echo $this->get_label('set for every month');?></span>
</span>
</td>
</tr>
<?php }?>
</table>


<?php 
$cloudflare_support_enabled=Configuration::get_instance()->read('cloudflare_support_enabled');

if($cloudflare_support_enabled ==1){?>


<div class="cloud-cache">
<a href="<?php echo $this->make_url("system/clear_cloudflare_cache");?>" onclick="return confirm('<?php echo $this->get_message('do you really want to delete cloudflare cache');?>');"><?php echo $this->get_label('clear cloudflare cache');?></a>
</div>




<?php }?>




</div> 
</div>


<div class="inner-box todo-box todo-box-report">
<div class="todo_div">

<?php 
$flag=0;
?>

<div class="todo-head"><i class="fa fa-tasks" title="<?php echo $this->get_label('system configurations');?>"></i><?php echo $this->get_label('system configurations');?></div>

<table class="data_table" cellpadding="0" cellspacing="0">
<tr class="data_table_content pending-operations">
<td style="width: 150px;"><?php echo $this->get_label('output buffering');?></td>
<td style="width: 40px;">:</td>
<td style="width: 100px;"><?php 
if(intval(ini_get('output_buffering')) ==0)
{
	$flag++;
	echo '<span class="configuration-span-no">'.$this->get_label('no').' <span class="configuration-span">*</span></span>';
}
else
echo '<span class="configuration-span-yes">'.$this->get_label('yes').'</span>';
?></td>


<td style="width: 150px;"><?php echo $this->get_label('mbstring extension');?></td>
<td style="width: 40px;">:</td>
<td style="width: 100px;"><?php 
if(!function_exists('mb_strlen'))
{
	$flag++;
	echo '<span class="configuration-span-no">'.$this->get_label('no').' <span class="configuration-span">*</span></span>';
}
else
echo '<span class="configuration-span-yes">'.$this->get_label('yes').'</span>';
?></td>

<td style="width: 150px;"><?php echo $this->get_label('curl extension');?></td>
<td style="width: 40px;">:</td>
<td style="width: 100px;"><?php 
if(!function_exists('curl_init'))
{
	$flag++;
	echo '<span class="configuration-span-no">'.$this->get_label('no').' <span class="configuration-span">*</span></span>';
}
else
echo '<span class="configuration-span-yes">'.$this->get_label('yes').'</span>';
?></td>

</tr>




<tr class="data_table_content pending-operations">
<td><?php echo $this->get_label('url fopen');?></td>
<td>:</td>
<td >
<?php 
if(intval(ini_get('allow_url_fopen')) ==0)
{
	$flag++;
	echo '<span class="configuration-span-no">'.$this->get_label('no').' <span class="configuration-span">*</span></span>';
}
else
echo '<span class="configuration-span-yes">'.$this->get_label('yes').'</span>';
?>
</td>


<td><?php echo $this->get_label('gd support');?></td>
<td>:</td>
<td >
<?php 
if(!extension_loaded('gd') || !function_exists('gd_info'))
{
	$flag++;
	echo '<span class="configuration-span-no">'.$this->get_label('no').' <span class="configuration-span">*</span></span>';
}
else
echo '<span class="configuration-span-yes">'.$this->get_label('yes').'</span>';
?>
</td>

<td><?php echo $this->get_label('gz support');?></td>
<td>:</td>
<td>
<?php 
if(!function_exists('gzopen'))
{
	$flag++;
	echo '<span class="configuration-span-no">'.$this->get_label('no').' <span class="configuration-span">*</span></span>';
}
else
echo '<span class="configuration-span-yes">'.$this->get_label('yes').'</span>';
?>
</td>

</tr>


<tr class="data_table_content pending-operations">
<td><?php echo $this->get_label('upload max size');?></td>
<td>:</td>
<td >
<?php echo ini_get('upload_max_filesize');?><span class="configuration-span">**</span>
</td>


<td><?php echo $this->get_label('execution time');?></td>
<td>:</td>
<td >
<?php echo ini_get('max_execution_time');?><?php echo $this->get_label(' sec');?><span class="configuration-span">**</span>
</td>

<td><?php echo $this->get_label('memory limits');?></td>
<td>:</td>
<td>
<?php echo ini_get('memory_limit');?><span class="configuration-span">**</span>
</td>

</tr>


<tr class="data_table_content geo-updation">
<td colspan="9">
<?php /*if($city_enabled !=1){?>
<div style="width: 45%;float: left;">
<a href="<?php echo $this->make_url("system/update_geo/0");?>"><?php echo $this->get_label('geo ip updation');?></a> [<?php echo $this->get_label('last modified time',array('x'=>$geo_modify_time_ip));?>]
</div>
<?php }*/?>

<?php /*if($city_enabled ==1 || Configuration::get_instance()->read('countrywise_data_tracking') ==1){?>
<div style="width: 45%;float: left;">
<a href="<?php echo $this->make_url("system/update_geo/1");?>"><?php echo $this->get_label('geo city updation');?></a> [<?php echo $this->get_label('last modified time',array('x'=>$geo_modify_time_city));?>]
</div>
<?php }*/?>

</td>
</tr>
</table>

</div>
</div>

<?php }?>
<?php }?>


<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0){?>
<?php if($flag >0){?>
<div class="notification"><span style="color: red;">*&nbsp;&nbsp;</span><?php echo $this->get_label('please enable these settings')?></div>
<?php }?>

<div class="notification" style="height: 30px;">&nbsp;&nbsp;&nbsp;<?php echo $this->get_label('geo message');?></div>


<div class="notification"><span style="color: red;">**&nbsp;</span><?php echo $this->get_label('upgrade settings to improve performance')?></div>
<?php }?>
<?php $this->dispatch("layout/footer");?>