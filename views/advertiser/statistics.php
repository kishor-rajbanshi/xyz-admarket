<?php $this->dispatch("layout/header/4/1/a");?>
<script type="text/javascript">
$(document).ready(function() {
	 $("#from_date").datepicker({dateFormat : 'dd/mm/yy'});
	 $("#to_date").datepicker({dateFormat : 'dd/mm/yy'});
});
</script>
<?php
$countrywise_data_tracking = Configuration::get_instance()->read('countrywise_data_tracking');
$cpc_enabled               = $this->get_addon_status('cpc_enabled');
$cpa_enabled               = $this->get_addon_status('cpa_enabled');

$uid=$this->get_variable('uid');

$duration=$this->get_variable('duration');
$tab=intval($this->get_variable('tab'));


$from_date=$this->get_variable('from_date');
$to_date=$this->get_variable('to_date');

if($from_date !='' && $to_date =='')
$to_date=date("d",time()).'/'.date("m",time()).'/'.date("Y",time());

$tabCount = 2;

if($countrywise_data_tracking ==1)
$tabCount++;

if($cpc_enabled ==1)
$tabCount++;

if($cpa_enabled ==1)
$tabCount++;


$tabWidth = round((100 / $tabCount), 2);

$direction = $this->get_locale_direction();
?>
<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 p-3 mb-3 manage-reports table-outer-box">

<h2 class="page-heading"><div class="page-inner"><i class="fa fa-file-text icon_red"></i><?php echo $this->get_label('overall statistics');?></div></h2>
<?php
if($from_date =='' && $duration ==7)
$duration=1;

$form2=$this->create_form();
$form2->start("overall",$this->make_url("advertiser/statistics"),"post");
?>
<div class="row mb-3 px-0 search_div">
	<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12 mb-1">
		<select class="form-select" name="duration" id="duration">
			<option value="1" <?php if($duration==1) { echo "selected"; } ?>><?php echo $this->get_label('today');?></option>
			<option value="6" <?php if($duration==6) { echo "selected"; } ?>><?php echo $this->get_label('yesterday');?></option>
			<option value="2" <?php if($duration==2) { echo "selected"; } ?>><?php echo $this->get_label('last 14');?></option>
			<option value="3" <?php if($duration==3) { echo "selected"; } ?>><?php echo $this->get_label('last 30');?></option>
			<option value="4" <?php if($duration==4) { echo "selected"; } ?>><?php echo $this->get_label('last 12 month');?></option>
			<option value="5" <?php if($duration==5) { echo "selected"; } ?>><?php echo $this->get_label('all time');?></option>
			<option value="7" <?php if($duration==7) { echo "selected"; } ?>><?php echo $this->get_label('custom date');?></option>
		</select>
	</div>

	<div class="col-auto mb-1 custom-date-div">
		<input class="form-control" type="text" readonly="readonly" name="from_date" id="from_date" value="<?php echo $from_date;?>" placeholder="<?php echo $this->get_label('from date');?>" />
		<input class="form-control" type="text" readonly="readonly" name="to_date" id="to_date" value="<?php echo $to_date;?>" placeholder="<?php echo $this->get_label('to date');?>" />
		<input type="hidden" name="tab" id="tab" value="1" />
	</div>

	<div class="col-auto">
		<input class="btn btn-info" type="submit" name="search" value="<?php echo $this->get_label('go');?>" />
	</div>
</div>
<?php $form2->end(); ?>

<div class="tab-nav">
	<?php $index = 0; ?>
	<button class="tab-btn tab-btn-<?php echo $index; ?>" data-label="<?php echo $index; ?>" onClick="manageTabClicks('user', 'advertiser_statistics', <?php echo $index;?>, <?php echo $tabCount;?>, <?php echo $direction;?>);"><span><?php echo $this->get_label('overall');?></span></button>

	<?php $index++; ?>
	<button class="tab-btn tab-btn-<?php echo $index; ?>" data-label="<?php echo $index; ?>" onClick="manageTabClicks('user', 'advertiser_statistics', <?php echo $index;?>, <?php echo $tabCount;?>, <?php echo $direction;?>);"><span><?php echo $this->get_label('time based');?></span></button>

	<?php if($countrywise_data_tracking == 1){
			$index++; ?>
			<button class="tab-btn tab-btn-<?php echo $index; ?>" data-label="<?php echo $index; ?>" onClick="manageTabClicks('user', 'advertiser_statistics', <?php echo $index;?>, <?php echo $tabCount;?>, <?php echo $direction;?>);"><span><?php echo $this->get_label('geo');?></span></button>
  <?php }?>

	<?php if($cpc_enabled == 1){
			$index++; ?>
			<button class="tab-btn tab-btn-<?php echo $index; ?>" data-label="<?php echo $index; ?>" onClick="manageTabClicks('user', 'advertiser_statistics', <?php echo $index;?>, <?php echo $tabCount;?>, <?php echo $direction;?>);"><span><?php echo $this->get_label('click analysis');?></span></button>
  <?php }?>

	<?php if($cpa_enabled == 1){
			$index++; ?>
			<button class="tab-btn tab-btn-<?php echo $index; ?>" data-label="<?php echo $index; ?>" onClick="manageTabClicks('user', 'advertiser_statistics', <?php echo $index;?>, <?php echo $tabCount;?>, <?php echo $direction;?>);"><span><?php echo $this->get_label('conversion analysis');?></span></button>
  <?php }?>

<div class="tab-indicator" id="tabIndicator" style="width: <?php echo $tabWidth; ?>%;"></div>
</div>

<?php $index = 0; ?>
<div class="row">

<div class="report-div-tab report-div-tab-<?php echo $index;?>">
	<table id="table-desktop-<?php echo $index;?>" class="data_table" cellpadding="0" cellspacing="0">
			<?php
			$reportResult      = $this->get_array("reportResult");
			$reportResultCount = count($reportResult);

			foreach($reportResult as $rkey => $rvalue)
			{
			    if($reportResultCount <= 3 && $rkey == 'total')
			    continue;

					if($rkey == 'heading')
					{?>
						<tr class="data_table_head">
						<td></td>
					<?php
					}
					else
					{ ?>
						<tr class="data_table_content">
						<td ><?php echo $this->get_label($rkey);?></td>
					<?php
					}

					foreach ($rvalue as $rkey1 => $rvalue1){?>
						<td>
							<bdi><?php echo $rvalue1;?></bdi>
						</td>
					<?php	}	?>
				</tr>
			<?php }?>
		</table>
	</div>

	<?php
	$reportResultTimeperiod = $this->get_array("reportResultTimeperiod");
	$index++;
	?>

	<div class="report-div-tab report-div-tab-<?php echo $index;?>">
		<table id="table-desktop-<?php echo $index;?>" class="data_table" cellpadding="0" cellspacing="0">

		<?php
		$iindex = 0;

		foreach($reportResultTimeperiod as $rkey => $rvalue)
		{
		    if($iindex == 0)
		    {?>
				<tr class="data_table_head">

				<?php
				foreach ($rvalue as $rkey1 => $rvalue1)
				{?>
					<td><bdi><?php echo $rvalue1;?></bdi></td>
				<?php
				}
				?>

				</tr>
			<?php
			}
			else
			{
				$enterflag = 0;
				$rowSpan   = count($rvalue);

				foreach($rvalue as $rkey1 => $rvalue1)
				{?>

					<tr class="data_table_content">
					<td <?php if($enterflag == 0){?> rowspan="<?php echo $rowSpan;?>" <?php } else {?> style="display: none;"<?php } ?>><bdi><?php echo $rkey;?></bdi></td>

					<td class="border-start"><bdi><?php echo $this->get_label($rkey1);?></bdi></td>

					<?php foreach ($rvalue1 as $rkey11 => $rvalue11){?>
					<td><bdi><?php echo $rvalue11;?></bdi></td>
					<?php }?>

					</tr>

					<?php
					$enterflag++;
				}
			}

			$iindex++;
		}
		?>
		</table>
	</div>

	<?php if($countrywise_data_tracking ==1){

		$index++;
	  $newfrom = str_replace('/','-',$from_date);
	  $newto   = str_replace('/','-',$to_date);

	  $this->dispatch("advertiser/country/".$duration."/".$newfrom."/".$newto);?>

	<?php }?>


	<?php if($cpc_enabled == 1){
		$index++;
		?>
		<div class="report-div-tab report-div-tab-<?php echo $index;?>">
			<table id="table-desktop-3" class="data_table" cellpadding="0" cellspacing="0">
				<tr class="data_table_head">
				<td ><?php echo $this->get_label('no');?></td>
				<td ><?php echo $this->get_label('time');?></td>
				<td ><?php echo $this->get_label('ip');?></td>
				<td ><?php echo $this->get_label('country');?></td>
				<td ><bdi><?php echo $this->get_label('money spend');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</bdi></td>
				</tr>
			<?php
			if($duration==1 || $duration==2 || $duration==3 || $duration==6)
			{
				$data_query_data=$this->get_result('data_query_data');

				if(count($data_query_data) == 0){?>
				<tr class="data_table_content">
					<td colspan="5"><?php echo $this->get_label('no records found');?></td>
				</tr>
				<?php } else {

				$i = 0;

				foreach($data_query_data as $key=>$value)
				{
					$i=$i+1;
				  ?>
				  <tr class="data_table_content">
				  <td ><?php echo $i;?></td>
				  <td >
					 <?php
				   $year=substr($value['time'],0,4);
					 $month=substr($value['time'],4,2);
					 $day=substr($value['time'],6,2);
					 $hour=substr($value['time'],8,2);

					 $data=$year."-".$month."-".$day."-".$hour;

				   echo $data;
					 ?>
				  </td>
				  <td ><?php echo $value['ip'];?></td>
				  <td ><?php echo $this->get_country_name($value['country']);?></td>
				  <td ><bdi><?php echo $this->get_number_format($value['clickvalue']);?></bdi></td>
				  </tr>
				<?php }?>
			<?php }?>

	<?php }	else {?>
				<tr class="data_table_content">
					<td colspan="5"><?php echo $this->get_label('click analysis data');?></td>
				</tr>
	<?php }?>
	  </table>

		<?php if(($duration == 1 || $duration == 2 || $duration == 3 || $duration == 6) && count($data_query_data) > 0){?>
			<div class="row">
				<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 mt-3 d-flex flex-row-reverse">
					<?php echo $this->get_variable('pagination');?>
				</div>
			</div>
		<?php } ?>

	</div>
	<?php }?>



	<?php  if($cpa_enabled ==1){
		$index++;
		?>
	<div class="report-div-tab report-div-tab-<?php echo $index;?>">
		<table id="table-desktop-4" class="data_table" cellpadding="0" cellspacing="0">
		<tr class="data_table_head">
		<td ><?php echo $this->get_label('no');?></td>
		<td ><?php echo $this->get_label('clickid');?></td>
		<td ><?php echo $this->get_label('country');?></td>
		<td ><?php echo $this->get_label('ip');?></td>
		<td ><?php echo $this->get_label('time');?></td>
		<td ><bdi><?php echo $this->get_label('money spend');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</bdi></td>
		</tr>

		<?php
		if($duration==1 || $duration==2 || $duration==3 || $duration==6)
		{
			$queryResult = $this->get_result('queryResult');

			if(count($queryResult) == 0){?>
			<tr class="data_table_content">
				<td colspan="6"><?php echo $this->get_label('no records found');?></td>
			</tr>
			<?php } else {

			$i=0;
			foreach($queryResult as $key=>$value)
			{
		 		$i=$i+1;
		  	?>
			  <tr class="data_table_content">
				  <td ><?php echo $i;?></td>
				  <td ><?php echo $value['clickid'];?></td>
				  <td ><?php echo $this->get_country_name($value['country']);?></td>
				  <td ><?php echo $value['ip'];?></td>
				  <td >
						 <?php

					   $year=substr($value['time'],0,4);
						 $month=substr($value['time'],4,2);
						 $day=substr($value['time'],6,2);
						 $hour=substr($value['time'],8,2);

						 $data=$year."-".$month."-".$day."-".$hour;

					   echo $data;
						 ?>
			    </td>
			  	<td ><bdi><?php echo $this->get_number_format($value['clickvalue']);?></bdi></td>
		  	</tr>
			<?php }?>
		<?php }?>

		<?php } else { ?>
			<tr class="data_table_content">
				<td colspan="6"><?php echo $this->get_label('conversion analysis data');?></td>
			</tr>
		<?php }?>
  </table>

	<?php if(($duration == 1 || $duration == 2 || $duration == 3 || $duration == 6) && count($queryResult) > 0){?>
		<div class="row">
			<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 mt-3 d-flex flex-row-reverse">
				<?php echo $this->get_variable('pagination1');?>
			</div>
		</div>
	<?php } ?>

</div>
<?php }?>

	</div>
</div>
<script type="text/javascript">

function ShowHideDate()
{
	if($('#duration').val() ==7)
	$('.custom-date-div').show();
	else
	{
		$('.custom-date-div').hide();

		$('#from_date').val('');
		$('#to_date').val('');
	}
}

$(document).ready(function() {



	$('#duration').change(function()
	{
		ShowHideDate();
	});

	ShowHideDate();

	manageTabClicks('user', 'advertiser_statistics', <?php echo $tab;?>, <?php echo $tabCount;?>, <?php echo $direction;?>);

	const reportDivs = document.querySelectorAll('.tab-btn');

		    reportDivs.forEach(function(div, index)
	{
				const dataLabel = div.getAttribute('data-label');

				CreateResponsiveTable('table-desktop-'+dataLabel);
			});

	$(window).resize(function()
	{
		reportDivs.forEach(function(div, index)
		{
			const dataLabel = div.getAttribute('data-label');

			CreateResponsiveTable('table-desktop-'+dataLabel);
	});
	});
});
</script>

<?php $this->dispatch("layout/footer");?>
