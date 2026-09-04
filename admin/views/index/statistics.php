<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head><link rel="stylesheet" href="<?php echo BASE.ADMIN_DIR."/css/style.css";?>" type="text/css" />
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/jquery-1.7.min.js'></script>
<link rel="stylesheet" href="<?php echo COMMON_DIR_PATH;?>font-awesome/css/font-awesome.css" />

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

<style type="text/css">
.report_main_table_tab
{
    width: 75px !important;
}
</style>



<?php


$cpc_enabled=$this->get_variable("cpc_enabled");
$cpa_enabled=$this->get_variable("cpa_enabled");
$cpm_enabled=$this->get_variable("cpm_enabled");
$html_enabled=$this->get_variable("html_enabled");
$sponsored_enabled=$this->get_variable("sponsored_enabled");
$feedads_enabled=$this->get_variable("feedads_enabled");
$cpp_enabled=$this->get_variable("cpp_enabled");

$skin_enabled=$this->get_variable("skin_enabled");
$textimage_enabled=$this->get_variable("textimage_enabled");
$ecommerce_enabled=$this->get_variable("ecommerce_enabled");


$activeads=$this->get_variable("activeads");
$activeadscpm=$this->get_variable("activeadscpm");
$activeadscpp=$this->get_variable("activeadscpp");
$activesponsoredads=$this->get_variable('activesponsoredads');
$activehtmlads=$this->get_variable('activehtmlads');
$activeadscpa=$this->get_variable("activeadscpa");

$sponsoredrunning=$this->get_variable('sponsoredrunning');
$sponsoredexpired=$this->get_variable('sponsoredexpired');


$from_date=$this->get_variable('from_date');
$to_date=$this->get_variable('to_date');
$duration=$this->get_variable('duration');


if($from_date !='' && $to_date =='')
$to_date=date("d",time()).'/'.date("m",time()).'/'.date("Y",time());


if($from_date =='' && $duration ==7)
$duration=1;
?>

</head>

<body style="background: none;">

<style type="text/css">
.row_data_tr td
{
	border: 0px;
}

.report_main_table_tab
{
	width: 90px;
}
</style>

<script type="text/javascript">
function show_tab(id)
{
	$('.tabcontent').css('display','none');
	$('.adustatclass').removeClass('tab-selection');

	$('#showstat'+id).css('display','');
	$('#tab_'+id).addClass('tab-selection');
}

$(document).ready(function() {
  <?php if($cpc_enabled ==1){?>
  show_tab(0);
  <?php }else if($cpm_enabled ==1){?>
  show_tab(1);
  <?php }else if($html_enabled ==1){?>
  show_tab(2);
  <?php }else if($cpa_enabled ==1){?>
  show_tab(6);
  <?php }else if($sponsored_enabled ==1){?>
  show_tab(3);
  <?php }else if($cpp_enabled ==1){?>
  show_tab(18);
  <?php }?>
});

</script>

<div class="inner_iframe">

<?php
$reportResult = $this->get_array("reportResult");
?>

<div class="inner-box home-box home-box-report">
<div class="report_div">

<div class="toppers-head"><i class="fa fa-line-chart" title="<?php echo $this->get_label('system reports');?>"></i><?php echo $this->get_label('system reports');?></div>



<table style="width: 100%;" cellpadding="0" cellspacing="0">
  <tr class="statistics_header">

  <?php if($cpc_enabled ==1){?>
  <td class="adustatclass tab-selection" id="tab_0" onclick="show_tab(0);" style="width: 70px;"><?php echo $this->get_label('cpc');?></td>
  <?php }?>

  <?php if($cpm_enabled ==1){?>
  <td class="adustatclass" id="tab_1" onclick="show_tab(1);" style="width: 70px;"><?php echo $this->get_label('cpm');?></td>
  <?php }?>

  <?php if($html_enabled ==1){?>
  <td class="adustatclass" id="tab_2" onclick="show_tab(2);" style="width: 70px;"><?php echo $this->get_label('html');?></td>
  <?php }?>

  <?php if($cpa_enabled ==1){?>
  <td class="adustatclass" id="tab_6" onclick="show_tab(6);" style="width: 70px;"><?php echo $this->get_label('cpa');?></td>

  <?php }?>

  <?php if($sponsored_enabled ==1){?>
  <td class="adustatclass" id="tab_3" onclick="show_tab(3);" style="width: 70px;"><?php echo $this->get_label('cpd');?></td>
  <?php }?>

  <?php if($cpp_enabled ==1){?>
  <td class="adustatclass" id="tab_18" onclick="show_tab(18);" style="width: 70px;"><?php echo $this->get_label('cpp');?></td>
  <?php }?>

<td class="adustatclass">
<?php
$form2=$this->create_form();
$form2->start("overall_adreport",$this->make_url("index/statistics"),"post");
?>
<div style="float: right;margin-right: 5px;">
<select name="duration" id="duration" style="width:120px;">
<option value="1" <?php if($duration==1) { echo "selected"; } ?>><?php echo $this->get_label('today');?></option>
<option value="6" <?php if($duration==6) { echo "selected"; } ?>><?php echo $this->get_label('yesterday');?></option>
<option value="2" <?php if($duration==2) { echo "selected"; } ?>><?php echo $this->get_label('last 14');?></option>
<option value="3" <?php if($duration==3) { echo "selected"; } ?>><?php echo $this->get_label('last 30');?></option>
<option value="4" <?php if($duration==4) { echo "selected"; } ?>><?php echo $this->get_label('last 12 month');?></option>
<option value="5" <?php if($duration==5) { echo "selected"; } ?>><?php echo $this->get_label('all time');?></option>
<option value="7" <?php if($duration==7) { echo "selected"; } ?>><?php echo $this->get_label('custom date');?></option>
</select>


<input type="text" readonly="readonly" name="from_date" id="from_date" value="<?php echo $from_date;?>" placeholder="From Date" style="width: 75px !important;" />

<input type="text" readonly="readonly" name="to_date" id="to_date" value="<?php echo $to_date;?>" placeholder="To Date" style="width: 75px !important;" />

<input type="submit" name="search" value="<?php echo $this->get_label('go');?>" />
</div>
<?php $form2->end(); ?>

</td>
</tr>
<tr class="showadustatclass statistics_tr home-table-head">
<td colspan="10">

<?php if($cpc_enabled ==1 && isset($reportResult['cpc'])){?>
<table id="showstat0" class="data_table tabcontent" cellpadding="0" cellspacing="0">
<tr class="row_heading_tr">
<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0){?>
<td><?php echo $this->get_label('ads');?></td>
<?php }?>
<td><?php echo $this->get_label('impressions');?></td>
<td><?php echo $this->get_label('clicks');?></td>
<td><?php echo $this->get_label('ctr');?></td>
<td><?php echo $this->get_label('money spend');?></td>
<td><?php echo $this->get_label('pubprofit');?></td>
<td><?php echo $this->get_label('balance');?></td>
</tr>

<tr class="row_data_tr">
<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0){?>
<td><?php echo $activeads;?></td>
<?php }?>
<td><?php echo $reportResult['cpc']['impression'];?></td>
<td><?php echo $reportResult['cpc']['click'];?></td>
<td><?php echo $reportResult['cpc']['ctr'];?></td>
<td><?php echo $reportResult['cpc']['spend'];?></td>
<td><?php echo $reportResult['cpc']['profit'];?></td>
<td><?php echo $reportResult['cpc']['adminprofit'];?></td>
</tr>

</table>
<?php }?>


<?php if($cpm_enabled ==1 && isset($reportResult['cpm'])){?>
<table id="showstat1" class="data_table tabcontent" cellpadding="0" cellspacing="0">
<tr class="row_heading_tr">
<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0){?>
<td><?php echo $this->get_label('ads');?></td>
<?php }?>
<td><?php echo $this->get_label('impressions');?></td>
<td><?php echo $this->get_label('clicks');?></td>
<td><?php echo $this->get_label('ctr');?></td>
<td><?php echo $this->get_label('money spend');?></td>
<td><?php echo $this->get_label('pubprofit');?></td>
<td><?php echo $this->get_label('balance');?></td>
</tr>

<tr class="row_data_tr">
<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0){?>
<td><?php echo $activeadscpm;?></td>
<?php }?>
<td><?php echo $reportResult['cpm']['impression'];?></td>
<td><?php echo $reportResult['cpm']['click'];?></td>
<td><?php echo $reportResult['cpm']['ctr'];?></td>
<td><?php echo $reportResult['cpm']['spend'];?></td>
<td><?php echo $reportResult['cpm']['profit'];?></td>
<td><?php echo $reportResult['cpm']['adminprofit'];?></td>
</tr>

</table>
<?php }?>

<?php if($html_enabled ==1 && isset($reportResult['html'])){?>
<table id="showstat2" class="data_table tabcontent" cellpadding="0" cellspacing="0">
<tr class="row_heading_tr">
<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0){?>
<td><?php echo $this->get_label('ads');?></td>
<?php }?>
<td><?php echo $this->get_label('impressions');?></td>
<td><?php echo $this->get_label('money spend');?></td>
</tr>

<tr class="row_data_tr">
<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0){?>
<td><?php echo $activehtmlads;?></td>
<?php }?>
<td><?php echo $reportResult['html']['impression'];?></td>
<td><?php echo $reportResult['html']['spend'];?></td>
</tr>

</table>
<?php }?>


<?php if($cpa_enabled ==1 && isset($reportResult['cpa'])){?>
<table id="showstat6" class="data_table tabcontent" cellpadding="0" cellspacing="0">
<tr class="row_heading_tr">
<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0){?>
<td><?php echo $this->get_label('ads');?></td>
<?php }?>
<td><?php echo $this->get_label('impressions');?></td>
<td><?php echo $this->get_label('clicks');?></td>
<td><?php echo $this->get_label('conversions');?></td>
<td><?php echo $this->get_label('money spend');?></td>
<td><?php echo $this->get_label('pubprofit');?></td>
<td><?php echo $this->get_label('balance');?></td>
</tr>

<tr class="row_data_tr">
<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0){?>
<td><?php echo $activeadscpa;?></td>
<?php }?>
<td><?php echo $reportResult['cpa']['impression'];?></td>
<td><?php echo $reportResult['cpa']['click'];?></td>
<td><?php echo $reportResult['cpa']['conversion'];?></td>
<td><?php echo $reportResult['cpa']['spend'];?></td>
<td><?php echo $reportResult['cpa']['profit'];?></td>
<td><?php echo $reportResult['cpa']['adminprofit'];?></td>
</tr>

</table>
<?php }?>











<?php if($sponsored_enabled ==1 && isset($reportResult['cpd'])){?>
<table id="showstat3" class="data_table tabcontent" cellpadding="0" cellspacing="0">
<tr class="row_heading_tr">
<td><?php echo $this->get_label('ads');?></td>
<td><?php echo $this->get_label('sponsored running');?></td>
<td><?php echo $this->get_label('impressions');?></td>
<td><?php echo $this->get_label('clicks');?></td>
<td><?php echo $this->get_label('ctr');?></td>
<td><?php echo $this->get_label('money spend');?></td>
<td><?php echo $this->get_label('pubprofit');?></td>
<td><?php echo $this->get_label('balance');?></td>

</tr>

<tr class="row_data_tr">
<td><?php echo $activesponsoredads;?></td>
<td><?php echo $sponsoredrunning;?></td>
<td><?php echo $reportResult['cpd']['impression'];?></td>
<td><?php echo $reportResult['cpd']['click'];?></td>
<td><?php echo $reportResult['cpd']['ctr'];?></td>
<td><?php echo $reportResult['cpd']['spend'];?></td>
<td><?php echo $reportResult['cpd']['profit'];?></td>
<td><?php echo $reportResult['cpd']['adminprofit'];?></td>
</tr>
</table>
<?php }?>


<?php if($cpp_enabled ==1 && isset($reportResult['cpp'])){?>
<table id="showstat18" class="data_table tabcontent" cellpadding="0" cellspacing="0">
<tr class="row_heading_tr">
<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0){?>
<td><?php echo $this->get_label('ads');?></td>
<?php }?>
<td><?php echo $this->get_label('impressions');?></td>
<td><?php echo $this->get_label('clicks');?></td>
<td><?php echo $this->get_label('ctr');?></td>
<td><?php echo $this->get_label('money spend');?></td>
<td><?php echo $this->get_label('pubprofit');?></td>
<td><?php echo $this->get_label('balance');?></td>
</tr>

<tr class="row_data_tr">
<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0){?>
<td><?php echo $activeadscpp;?></td>
<?php }?>
<td><?php echo $reportResult['cpp']['impression'];?></td>
<td><?php echo $reportResult['cpp']['click'];?></td>
<td><?php echo $reportResult['cpp']['ctr'];?></td>
<td><?php echo $reportResult['cpp']['spend'];?></td>
<td><?php echo $reportResult['cpp']['profit'];?></td>
<td><?php echo $reportResult['cpp']['adminprofit'];?></td>
</tr>

</table>
<?php }?>
</td>
</tr>
</table>

</div>
</div>
</div>
</body>
</html>
