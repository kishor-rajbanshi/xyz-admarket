<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/jquery.min.js'></script>
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/jquery-ui-1.8.23.custom.min.js'></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" type="text/css" media="all">
<link rel="stylesheet" href="<?php echo COMMON_DIR_PATH;?>font-awesome/css/font-awesome.css" />


<link rel="stylesheet" href="<?php echo BASE.ADMIN_DIR."/css/style.css";?>" type="text/css" />


<script type="text/javascript">
$(document).ready(function() {
	 $("#from_date").datepicker({dateFormat : 'dd/mm/yy'});
	 $("#to_date").datepicker({dateFormat : 'dd/mm/yy'});
});
</script>
<script type="text/javascript">
$(document).ready(function() {

$('#duration').change(function()
{
	ShowHideDate();
});

ShowHideDate();
});

function ShowHideDate()
{
	if($('#duration').val() ==7)
	{
		$('#from_date').show();
		$('#to_date').show();
	}
	else
	{
		$('#from_date').hide();
		$('#to_date').hide();

		$('#from_date').val('');
		$('#to_date').val('');
	}
}
</script>
<?php 
$duration=$this->get_variable('duration');
$from_date=$this->get_variable('from_date');
$to_date=$this->get_variable('to_date');

if($from_date !='' && $to_date =='')
$to_date=date("d",time()).'/'.date("m",time()).'/'.date("Y",time());	

if($from_date =='' && $duration ==7)
$duration=1;


$cpc_enabled=$this->get_addon_status('cpc_enabled');
$cpa_enabled=$this->get_addon_status('cpa_enabled');
$cpm_enabled=$this->get_addon_status('cpm_enabled');
$html_enabled=$this->get_addon_status('html_enabled');
$category_enabled=$this->get_addon_status('category-targeting_enabled');
$pop_enabled=$this->get_addon_status('pop-ads_enabled');
$affiliate_enabled=$this->get_addon_status('affiliate-ads_enabled');
$cpv_enabled=$this->get_addon_status('video-ads_enabled');


$top=$this->get_variable('top');
$sort=$this->get_variable('sort');


?>




<div class="inner-box home-box">
<div class="report_div">

<div class="toppers-head">

<i class="fa fa-users" title="<?php echo $this->get_label('toppers list');?>"></i><?php echo $this->get_label('toppers list');?>



<div class="search_div" style="width: auto;float: right;margin-bottom: 10px;">

<?php 
$form=$this->create_form();
$form->start("advstatistics","","post");
?>
<table class="search_div_table">
<tr>
<td style="width: 160px;">
<select name="top" id="top" style="width: 150px;" onchange="LoadTopper(1);">
<option value="0" <?php if($top ==0) echo "selected";?>><?php echo $this->get_label('top advertisers');?></option>
<option value="1" <?php if($top ==1) echo "selected";?>><?php echo $this->get_label('top publishers');?></option>
<?php if($category_enabled ==1 && ($cpc_enabled ==1 || $cpm_enabled ==1 || $html_enabled ==1 || $cpa_enabled ==1 || $pop_enabled ==1 || $cpv_enabled ==1)){?>
<option value="2" <?php if($top ==2) echo "selected";?>><?php echo $this->get_label('top sites');?></option>
<?php }?>
</select>
</td>  
<td style="width: 160px;">
<select name="duration" id="duration" style="width: 150px;">
<option value="1" <?php if($duration==1) { echo "selected"; } ?>><?php echo $this->get_label('today');?></option>
<option value="6" <?php if($duration==6) { echo "selected"; } ?>><?php echo $this->get_label('yesterday');?></option>
<option value="2" <?php if($duration==2) { echo "selected"; } ?>><?php echo $this->get_label('last 14');?></option>
<option value="3" <?php if($duration==3) { echo "selected"; } ?>><?php echo $this->get_label('last 30');?></option>
<option value="4" <?php if($duration==4) { echo "selected"; } ?>><?php echo $this->get_label('last 12 month');?></option>
<option value="5" <?php if($duration==5) { echo "selected"; } ?>><?php echo $this->get_label('all time');?></option>
<option value="7" <?php if($duration==7) { echo "selected"; } ?>><?php echo $this->get_label('custom date');?></option>
</select>

</td>
<td>
&nbsp;
<input type="text" readonly="readonly" name="from_date" id="from_date" value="<?php echo $from_date;?>" placeholder="<?php echo $this->get_label('from date');?>" size="5" />  
&nbsp;
<input type="text" readonly="readonly" name="to_date" id="to_date" value="<?php echo $to_date;?>" placeholder="<?php echo $this->get_label('to date');?>" size="5" />
&nbsp;
</td>
<td style="width: 160px;">
<select name="sort" id="sort" style="width: 150px;">
<?php if($cpc_enabled ==1 || $cpm_enabled ==1 || $html_enabled ==1 || $cpa_enabled ==1 || $pop_enabled ==1 || $cpv_enabled ==1){?>
<option value="0" <?php if($sort ==0) { echo "selected"; } ?>><?php echo $this->get_label('sort by impressions');?></option>
<?php }?>

<?php if($cpc_enabled ==1 || $cpm_enabled ==1 || $cpa_enabled ==1 || $affiliate_enabled ==1 || $cpv_enabled ==1){?>
<option value="1" <?php if($sort ==1) { echo "selected"; } ?>><?php echo $this->get_label('sort by clicks');?></option>
<?php }?>


<option id="adv-data" value="2" <?php if($sort ==2) { echo "selected"; } ?>><?php echo $this->get_label('sort by money spend');?></option>
<option id="pub-data" value="3" <?php if($sort ==3) { echo "selected"; } ?>><?php echo $this->get_label('sort by pubprofit');?></option>

<?php if($cpa_enabled ==1 || $affiliate_enabled ==1){?>
<option value="4" <?php if($sort ==4) { echo "selected"; } ?>><?php echo $this->get_label('sort by conversions');?></option>
<?php }?>
</select>
</td>
<td><input type="submit" name="search" value="<?php echo $this->get_label('go');?>" />&nbsp;</td>
</tr>  
</table>
<?php $form->end(); ?> 


</div>



</div>


<table style="width: 100%;" cellpadding="0" cellspacing="0" >
<tr><td colspan="3">

<table class="data_table" cellpadding="0" cellspacing="0">
<tr class="row_heading_tr">
<td><?php echo $this->get_label('no');?></td>
<?php if($top ==2){?> 
<td><?php echo $this->get_label('site name');?></td>
<?php }?>
<td width="20%"><?php echo $this->get_label('username');?></td>

<?php if($cpc_enabled ==1 || $cpm_enabled ==1 || $html_enabled ==1 || $cpa_enabled ==1 || $pop_enabled ==1 || $cpv_enabled ==1){?>
<td><?php echo $this->get_label('impressions');?></td>
<?php }?>

<?php if($cpc_enabled ==1 || $cpm_enabled ==1 || $cpa_enabled ==1 || $affiliate_enabled ==1 || $cpv_enabled ==1){?>
<td><?php echo $this->get_label('clicks');?></td>
<?php }?>

<?php if((($top ==0 || $top ==1) && ($cpa_enabled ==1 || $affiliate_enabled ==1)) || ($top ==2 && $cpa_enabled ==1)){?>
<td style="width: 100px;"><bdi><?php echo $this->get_label('conversions');?></bdi></td>
<?php }?>





<td><?php echo $this->get_label('money spend');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>
<td><?php echo $this->get_label('pubprofit');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>
<td><?php echo $this->get_label('balance');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>
</tr>

<?php 




if($top ==0)
{

$results=$this->get_array("results");
$number=count($results);

if($number==0){?>	
<tr><td colspan="9" height="30px">&nbsp;<?php echo $this->get_label('no users found');?></td></tr>	
<?php }
$no=1;
foreach($results as $key=>$value)
{
	if($no == 11)
	break;
	
	$uid=$value[0];
	$username=$value[1];

	
	if($from_date !='')  // for custom date range
	$statistics=$this->get_adv_date_range_statistics($from_date,$to_date,$uid,0,0,0);
	else
	$statistics=$this->get_advertiser_statistics($duration,$uid,0,0,0);
	
	?>
<tr class="row_data_tr">
<td><?php echo $no;?></td>
<?php if($uid >0){
	
	$impression=0;
	$click=0;
	$spend=0;
	$profit=0;
	$conversions=0;
	
	
	
	if($cpc_enabled ==1)
	{	
		$impression=$impression+$statistics['impression'];
		$click=$click+$statistics['click'];
		$spend=$spend+$statistics['money_spent'];
		$profit=$profit+$statistics['pub_profit'];	
	}
	
	if($cpm_enabled ==1)
	{
		$impression=$impression+$statistics['cpm_impression'];
		$click=$click+$statistics['cpm_click'];
		$spend=$spend+$statistics['cpm_spend'];
		$profit=$profit+$statistics['cpm_profit'];
	}
	
	
	if($cpv_enabled ==1)
	{
		$impression=$impression+$statistics['cpv_impression'];
		$click=$click+$statistics['cpv_click'];
		$spend=$spend+$statistics['cpv_spend'];
		$profit=$profit+$statistics['cpv_profit'];
	}	
	
	
	
	
	if($cpa_enabled ==1)
	{
		$impression=$impression+$statistics['cpa_impression'];
		$click=$click+$statistics['cpa_click'];
		$conversions=$conversions+$statistics['cpa_conversion'];
		$spend=$spend+$statistics['cpa_spend'];
		$profit=$profit+$statistics['cpa_profit'];
	}
	
	
	
	if($affiliate_enabled ==1)
	{
		$click=$click+$statistics['affiliate_click'];
		$conversions=$conversions+$statistics['affiliate_conversion'];
		$spend=$spend+$statistics['affiliate_spend'];
		$profit=$profit+$statistics['affiliate_profit'];
	}
	
	
	
	if($pop_enabled ==1)
	{
		$impression=$impression+$statistics['pop_impression'];
		$spend=$spend+$statistics['pop_spend'];
		$profit=$profit+$statistics['pop_profit'];
	}
	
	
	
	
	$earning=$spend-$profit;
	
	?>

<td >
<?php if($username !=""){?>
<a target="_parent" href="<?php echo $this->make_url("user/profile/".$uid."/0");?>"><?php echo $username;?></a>
<?php }else{?>
<?php echo $this->get_label('deleted');?>
<?php }?>
</td>

<?php if($cpc_enabled ==1 || $cpm_enabled ==1 || $html_enabled ==1 || $cpa_enabled ==1 || $pop_enabled ==1 || $cpv_enabled ==1){?>
<td ><?php echo $impression;?></td>
<?php }?>

<?php if($cpc_enabled ==1 || $cpm_enabled ==1 || $cpa_enabled ==1 || $affiliate_enabled ==1 || $cpv_enabled ==1){?>
<td ><?php echo $click;?></td>
<?php }?>

<?php if($cpa_enabled ==1 || $affiliate_enabled ==1){?>
<td><?php echo $conversions;?></td>
<?php }?>


<td ><?php echo $this->get_number_format($spend);?></td>
<td ><?php echo $this->get_number_format($profit);?></td>
<td ><?php echo $this->get_number_format($earning);?></td>


<?php } else if($uid ==0 && $html_enabled ==1){?>
<td ><?php echo $username;?></td>
<td ><?php echo $statistics['html_impression'];?></td>

<?php if($cpc_enabled ==1 || $cpm_enabled ==1 || $cpa_enabled ==1 || $affiliate_enabled ==1 || $cpv_enabled ==1){?>
<td ><?php echo $this->get_label('na');?></td>
<?php }?>

<?php if($cpa_enabled ==1 || $affiliate_enabled ==1){?>
<td ><?php echo $this->get_label('na');?></td>
<?php }?>

<td ><?php echo $this->get_number_format($statistics['html_profit']);?></td>
<td ><?php echo $this->get_number_format($statistics['html_profit']);?></td>
<td ><?php echo $this->get_number_format($statistics['html_profit']);?></td>
<?php }?>
</tr>
<?php 
$no=$no+1;
}

}
else if($top ==1)
{

$results=$this->get_array("results");
$number=count($results);

if($number==0){?>	
<tr><td colspan="9" height="30px">&nbsp;<?php echo $this->get_label('no users found');?></td></tr>	
<?php }
$no=1;
foreach($results as $key=>$value)
{
	if($no == 11)
	break;
	
	$uid=$value[0];
	$username=$value[1];

	
	if($from_date !='')  // for custom date range
	$statistics=$this->get_pub_date_range_statistics($from_date,$to_date,$uid,0,0,0);
	else
	$statistics=$this->get_publisher_statistics($duration,$uid,0,0,0);
	
	?>
<tr class="row_data_tr">
<?php 
	
	
	$impression=0;
	$click=0;
	$spend=0;
	$profit=0;
	$conversions=0;
	
	
	
	if($cpc_enabled ==1)
	{	
		$impression=$impression+$statistics['impression'];
		$click=$click+$statistics['click'];
		$spend=$spend+$statistics['money_spent'];
		$profit=$profit+$statistics['pub_profit'];	
	}	
	
	
	
	
	if($cpm_enabled ==1)
	{
		$impression=$impression+$statistics['cpm_impression'];
		$click=$click+$statistics['cpm_click'];
		$spend=$spend+$statistics['cpm_spend'];
		$profit=$profit+$statistics['cpm_profit'];
	}
	
	if($cpv_enabled ==1)
	{
		$impression=$impression+$statistics['cpv_impression'];
		$click=$click+$statistics['cpv_click'];
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
		$click=$click+$statistics['cpa_click'];
		$conversions=$conversions+$statistics['cpa_conversion'];
		$spend=$spend+$statistics['cpa_spend'];
		$profit=$profit+$statistics['cpa_profit'];
	}
	
	
	if($affiliate_enabled ==1)
	{
		$click=$click+$statistics['affiliate_click'];
		$conversions=$conversions+$statistics['affiliate_conversion'];
		$spend=$spend+$statistics['affiliate_spend'];
		$profit=$profit+$statistics['affiliate_profit'];
	}
	
	if($pop_enabled ==1)
	{
		$impression=$impression+$statistics['pop_impression'];
		$spend=$spend+$statistics['pop_spend'];
		$profit=$profit+$statistics['pop_profit'];
	}
	
	
	
	$earning=$spend-$profit;
	
	?>
	
<td><?php echo $no;?></td>
<td >

<?php if($uid >0){?>
<?php if($username !=""){?>
<a target="_parent" href="<?php echo $this->make_url("user/profile/".$uid."/0");?>"><?php echo $username;?></a>
<?php }else{?>
<?php echo $this->get_label('deleted');?>
<?php }?>
<?php }else{?>
<?php echo $username;?>
<?php }?>
</td>

<?php if($cpc_enabled ==1 || $cpm_enabled ==1 || $html_enabled ==1 || $cpa_enabled ==1 || $pop_enabled ==1 || $cpv_enabled ==1){?>
<td ><?php echo $impression;?></td>
<?php }?>

<?php if($cpc_enabled ==1 || $cpm_enabled ==1 || $cpa_enabled ==1 || $affiliate_enabled ==1 || $cpv_enabled ==1){?>
<td ><?php echo $click;?></td>
<?php }?>

<?php if($cpa_enabled ==1 || $affiliate_enabled ==1){?>
<td><?php echo $conversions;?></td>
<?php }?>

<td ><?php echo $this->get_number_format($spend);?></td>
<td ><?php echo $this->get_number_format($profit);?></td>
<td ><?php echo $this->get_number_format($earning);?></td>

</tr>
<?php 
$no=$no+1;
}

}
else if($top ==2)
{

$results=$this->get_array("results");
$number=count($results);

if($number==0){?>	
<tr><td colspan="9" height="30px">&nbsp;<?php echo $this->get_label('no sites found');?></td></tr>	
<?php }

$no=1;
foreach($results as $key=>$value)
{
	if($no == 11)
	break;
	
	
	
	$uid=$value[0];
	$username=$value[1];

	
	
	
	
	if($from_date !='')  // for custom date range
	$statistics=$this->get_pub_date_range_statistics($from_date,$to_date,$uid,0,0,$value[2]);
	else
	$statistics=$this->get_publisher_statistics($duration,$uid,0,0,$value[2]);
	
	?>
<tr class="row_data_tr">
<?php 
	
	$impression=0;
	$click=0;
	$spend=0;
	$profit=0;
	
	if($cpc_enabled ==1)
	{	
		$impression=$impression+$statistics['impression'];
		$click=$click+$statistics['click'];
		$spend=$spend+$statistics['money_spent'];
		$profit=$profit+$statistics['pub_profit'];	
	}	

	
	if($cpm_enabled ==1)
	{
		$impression=$impression+$statistics['cpm_impression'];
		$click=$click+$statistics['cpm_click'];
		$spend=$spend+$statistics['cpm_spend'];
		$profit=$profit+$statistics['cpm_profit'];
	}
	
	
	if($cpv_enabled ==1)
	{
		$impression=$impression+$statistics['cpv_impression'];
		$click=$click+$statistics['cpv_click'];
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
		$click=$click+$statistics['cpa_click'];
		$spend=$spend+$statistics['cpa_spend'];
		$profit=$profit+$statistics['cpa_profit'];
	}
	
	
	if($pop_enabled ==1)
	{
		$impression=$impression+$statistics['pop_impression'];
		$spend=$spend+$statistics['pop_spend'];
		$profit=$profit+$statistics['pop_profit'];
	}
	
	$earning=$spend-$profit;
	
	?>
<td><?php echo $no;?></td>

<td >
<?php if($value[3] !=""){?>
<a target="_parent" href="<?php echo $this->make_url("dispatch/category_targeting/22/".$value[2]);?>"><?php echo $value[3];?></a>
<?php }else {?>
<?php echo $this->get_label('deleted');?>
<?php }?>
</td>
<td >
<?php if($uid >0){?>
<a target="_parent" href="<?php echo $this->make_url("user/profile/".$uid."/0");?>"><?php echo $username;?></a>
<?php }else{?>
<?php echo $username;?>
<?php }?>
</td>

<?php if($cpc_enabled ==1 || $cpm_enabled ==1 || $html_enabled ==1 || $cpa_enabled ==1 || $pop_enabled ==1 || $cpv_enabled ==1){?>
<td ><?php echo $impression;?></td>
<?php }?>

<?php if($cpc_enabled ==1 || $cpm_enabled ==1 || $cpa_enabled ==1 || $cpv_enabled ==1){?>
<td ><?php echo $click;?></td>
<?php }?>

<?php if($cpa_enabled ==1){?>
<td><?php echo $statistics['cpa_conversion'];?></td>
<?php }?>


<td ><?php echo $this->get_number_format($spend);?></td>
<td ><?php echo $this->get_number_format($profit);?></td>
<td ><?php echo $this->get_number_format($earning);?></td>

</tr>
<?php 

$no=$no+1;
}

}?>	

</table>
</td></tr>
</table>
</div>
</div>

<script type="text/javascript">
function LoadTopper(from)
{
	if($('#top').val() ==0)
	{
		$('#adv-data').show();
		$('#pub-data').hide();
	}
	else if($('#top').val() ==1 || $('#top').val() ==2)
	{
		$('#adv-data').hide();
		$('#pub-data').show();
	}

	if(from ==1)
	{
		<?php if($cpc_enabled ==1 || $cpm_enabled ==1 || $html_enabled ==1 || $cpa_enabled ==1 || $pop_enabled ==1 || $cpv_enabled ==1){?>
		$('#sort').val(0);
		<?php }else if($cpc_enabled ==1 || $cpm_enabled ==1 || $cpa_enabled ==1 || $affiliate_enabled ==1 || $cpv_enabled ==1){?>
		$('#sort').val(1);
		<?php }?>
	}
}
LoadTopper(0);
</script>