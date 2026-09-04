<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link rel="stylesheet" href="<?php echo BASE.ADMIN_DIR."/css/style.css";?>" type="text/css" />
<link rel="stylesheet" href="<?php echo COMMON_DIR_PATH;?>font-awesome/css/font-awesome.css" />

<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/jquery-1.7.min.js'></script>
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>jquery-ui/jquery-ui.min.js'></script>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo COMMON_DIR_PATH;?>jquery-ui/jquery-ui.min.css" />

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
$from_date=$this->get_variable('from_date');
$to_date=$this->get_variable('to_date');

if($from_date !='' && $to_date =='')
$to_date=date("d",time()).'/'.date("m",time()).'/'.date("Y",time());
?>
</head>
<body style="background: none;">

<table  class="iframe_table" cellpadding="0" cellspacing="0" border="0"  >

<tr><td height="10px"></td></tr>


<tr><td colspan="9"></td></tr>


<tr><td colspan="9">
 <div class="search_div" style="height: 40px;">
<table class="search_div_table">
<?php
$cpc_enabled 		= $this->get_addon_status('cpc_enabled');
$cpa_enabled 		= $this->get_addon_status('cpa_enabled');
$cpm_enabled 		= $this->get_addon_status('cpm_enabled');
$html_enabled 		= $this->get_addon_status('html_enabled');
$category_enabled 	= $this->get_addon_status('category-targeting_enabled');
$sticky_enabled     = $this->get_addon_status('sticky-ad-display_enabled');

$stringarray=array();
if($category_enabled == 1)
{
	$category_enabled_ads=Configuration::get_instance()->read('category_enabled_ads');

	if($category_enabled_ads !='')
	$stringarray=explode('_',$category_enabled_ads);
}


$uid=$this->get_variable('uid');
$sid=$this->get_variable('sid');
$duration=$this->get_variable('duration');
$adpricing=$this->get_variable('adpricing');

if($from_date == '' && $duration == 7)
$duration = 1;

$form1=$this->create_form();
$form1->start("adunitstatistics",$this->make_url("user/pubadunitstat/".$uid),"post");
?>

<tr>

<td style="width: 180px;">
<select name="duration" id="duration">
<option value="1" <?php if($duration==1) { echo "selected"; } ?>><?php echo $this->get_label('today');?></option>
<option value="6" <?php if($duration==6) { echo "selected"; } ?>><?php echo $this->get_label('yesterday');?></option>
<option value="2" <?php if($duration==2) { echo "selected"; } ?>><?php echo $this->get_label('last 14');?></option>
<option value="3" <?php if($duration==3) { echo "selected"; } ?>><?php echo $this->get_label('last 30');?></option>
<option value="4" <?php if($duration==4) { echo "selected"; } ?>><?php echo $this->get_label('last 12 month');?></option>
<option value="5" <?php if($duration==5) { echo "selected"; } ?>><?php echo $this->get_label('all time');?></option>
<option value="7" <?php if($duration==7) { echo "selected"; } ?>><?php echo $this->get_label('custom date');?></option>
</select>
</td>

<td style="width: 150px;">

<input type="text" readonly="readonly" name="from_date" id="from_date" value="<?php echo $from_date;?>" placeholder="From Date" size="5" />

<input type="text" readonly="readonly" name="to_date" id="to_date" value="<?php echo $to_date;?>" placeholder="To Date" size="5" />

</td>
<td style="width: 200px;">
<?php echo $this->get_pricing_box($adpricing,3);?>
</td>
<?php if($category_enabled ==1){?>
<td class="site-class" style="width: 200px;">
<?php echo CategoryHelper::get_site_dropdown($uid,$sid);?>
</td>
<?php }?>
<td></td>
<td>&nbsp;<input type="submit" name="search" class="link_button" value="<?php echo $this->get_label('go');?>" /></td>
</tr>
<?php $form1->end(); ?>

</table>
</div>
</td></tr>
<tr><td colspan="9" height="10px"></td></tr>
<tr><td colspan="9">

<?php $adResult = $this->get_array('adResult');?>

<table style="width: 99%;margin: 4px;" cellpadding="0" cellspacing="0" border="0" class="data_table">

<tr class="row_heading_tr">
<td ><?php echo $this->get_label('adunit name');?></td>
<?php if($category_enabled ==1){?>
<td ><?php echo $this->get_label('site name');?></td>
<?php }?>
<td ><?php echo $this->get_label('pricing');?></td>

<?php
if(isset($adResult['heading']))
{
	foreach($adResult['heading'] as $hkey => $hvalue)
	{
		?>
		<td><bdi><?php echo $this->get_label($hvalue);?></bdi></td>
		<?php
	}
}
?>
</tr>
<?php
if(count($adResult) == 0){?><tr><td colspan="16" style="height:30px;"><?php echo $this->get_label('no records found');?></td></tr>
<?php
} else {

	foreach($adResult as $key => $value)
	{
		if($key == "heading")
		continue;

		$aduid 			= $value['id'];
		$adbid 			= $value['blockid'];
		$adcodeType 	= $value['adcode_type'];
	    $pricingvalue 	= $value['display_type'];

		$pricingArray = array();
		$rowspan      = 0;

	    if($pricingvalue == 4)
		{
			if($cpc_enabled == 1 && ($adpricing == -1 || $adpricing == 4 || $adpricing == 0))
			{
				$pricingArray[] = "cpc";
				$rowspan++;
			}

			if(($cpm_enabled == 1 || $html_enabled == 1) && ($adpricing == -1 || $adpricing == 4 || $adpricing == 1) && $adcodeType != 17)
			{
				$pricingArray[] = "cpm";
				$rowspan++;
			}

			if($cpa_enabled == 1 && ($adpricing == -1 || $adpricing == 4 || $adpricing == 6))
			{
				$pricingArray[] = "cpa";
				$rowspan++;
			}
		}
	    else if($pricingvalue == 0)
		$pricingArray[] = "cpc";
		else if($pricingvalue == 1)
		$pricingArray[] = "cpm";
		else if($pricingvalue == 3)
		$pricingArray[] = "cpd";
		else if($pricingvalue == 6)
		$pricingArray[] = "cpa";
		else if($pricingvalue == 18)
		$pricingArray[] = "cpp";

	    if($rowspan == 0)
	    $rowspan=1;

		$iii = 0;
		foreach($pricingArray as $pkey => $pvalue)
		{
			?>
			<tr class="row_data_tr">

			<td <?php if($iii == 0) { ?>rowspan="<?php echo $rowspan;?>" style="width: 150px;"<?php }else { ?>style="display:none;" <?php } ?>  >
			<div style="width: 100px;overflow: hidden;">
			<a target="_parent" href="<?php echo $this->make_base_url("report/adcodes_detailed/".$aduid,ADMIN_DIR);?>"><?php echo $value['name'];?></a>

			<?php if($pricingvalue == 3){?>
			<a target="_parent" href="<?php echo $this->make_url("dispatch/sponsored/13/".$aduid);?>"><i class="fa fa-bell-o mapping-icon" title="<?php echo $this->get_label('mappings');?>"></i></a>
			<?php } ?>

			<?php if($adcodeType != 9 && $adcodeType != 12 && $adcodeType != 17 && $adcodeType != 21 && $value['width'] > 0 && $value['height'] > 0){?>
			<div><?php echo $value['width']." x ".$value['height'];?></div>
			<?php }?>

			<?php
			if($sticky_enabled ==1)
			{
				if($value['sticky_support'] ==1)
				echo '<div>'.$this->get_label('sticky').'</div>';
			}
			?>
			</div>
			</td>

			<?php if($category_enabled ==1){
			if($value['sid'] > 0){?>
			<td <?php if($iii == 0){?>rowspan="<?php echo $rowspan;?>"<?php }else{?> style="display:none;"<?php } ?>> <?php echo $value['siteName'];?></td>
			<?php }else {?>
			<td <?php if($iii == 0){?>rowspan="<?php echo $rowspan;?>"<?php }else{?> style="display:none;"<?php } ?>><?php echo $this->get_label('na');?></td>
			<?php }}?>

			<td <?php if($pricingvalue ==4){?> class="border-left" <?php }?> ><?php echo $this->get_label($pvalue);?></td>

			<?php
			if(isset($value['reportData'][$pvalue]))
			{
				foreach($value['reportData'][$pvalue] as $key1 => $value1)
				{

					?>
					<td><bdi><?php echo $value1;?></bdi></td>
					<?php
				}
			}
			$iii++;
			?>
			</tr>
		<?php
		}
	}?>
	<tr><td colspan="16" align="center"><?php echo $this->get_variable('pagination3');?></td></tr>
<?php
}?>

</table>
</td></tr>
</table>
</body>
</html>