<?php $this->dispatch("layout/header/8/1/p");?>
<script type="text/javascript">
$(document).ready(function() {
	 $("#from_date").datepicker({dateFormat : 'dd/mm/yy'});
	 $("#to_date").datepicker({dateFormat : 'dd/mm/yy'});
});
</script>
<?php
$countrywise_data_tracking=Configuration::get_instance()->read('countrywise_data_tracking');

$tabCount = 2;

if($countrywise_data_tracking ==1)
$tabCount++;

$tabWidth = round((100 / $tabCount), 2);
$duration=$this->get_variable('duration');
$uid=$this->get_variable('uid');
$tab=$this->get_variable('tab');


$from_date=$this->get_variable('from_date');
$to_date=$this->get_variable('to_date');

if($from_date !='' && $to_date =='')
$to_date=date("d",time()).'/'.date("m",time()).'/'.date("Y",time());

$direction = $this->get_locale_direction();
?>


	<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 p-3 mb-3 manage-reports table-outer-box">
<h2 class="page-heading "><div class="page-inner"><i class="fa fa-bar-chart icon_red"></i> <?php echo $this->get_label('overall statistics');?></div></h2>

		<?php
		if($from_date =='' && $duration ==7)
		$duration=1;

		$form1=$this->create_form();
		$form1->start("adunitallstatistics",$this->make_url("publisher/statistics"),"post");
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
				<input type="hidden" name="tab" id="tab" value="1"/>
			</div>

			<div class="col-auto">
				<input class="btn btn-info" type="submit" name="search" value="<?php echo $this->get_label('go');?>" />
			</div>
		</div>
		<?php $form1->end(); ?>

		<div class="tab-nav">
			<?php $index = 0; ?>
			<button class="tab-btn tab-btn-<?php echo $index; ?>" data-label="<?php echo $index; ?>" onClick="manageTabClicks('user', 'publisher_statistics', <?php echo $index;?>, <?php echo $tabCount;?>, <?php echo $direction;?>);"><span><?php echo $this->get_label('overall');?></span></button>

			<?php $index++; ?>
			<button class="tab-btn tab-btn-<?php echo $index; ?>" data-label="<?php echo $index; ?>" onClick="manageTabClicks('user', 'publisher_statistics', <?php echo $index;?>, <?php echo $tabCount;?>, <?php echo $direction;?>);"><span><?php echo $this->get_label('time based');?></span></button>

			<?php if($countrywise_data_tracking == 1){
					$index++; ?>
					<button class="tab-btn tab-btn-<?php echo $index; ?>" data-label="<?php echo $index; ?>" onClick="manageTabClicks('user', 'publisher_statistics', <?php echo $index;?>, <?php echo $tabCount;?>, <?php echo $direction;?>);"><span><?php echo $this->get_label('geo');?></span></button>
		  <?php }?>
		<div class="tab-indicator" id="tabIndicator" style="width: <?php echo $tabWidth; ?>%;"></div>
	</div>

	<?php $index = 0; ?>
		<div class="row">
		<div class="report-div-tab report-div-tab-<?php echo $index;?>">
			<table id="table-desktop-<?php echo $index;?>" class="data_table" cellpadding="0" cellspacing="0">
					<?php
					$reportResult 		 = $this->get_array("reportResult");
					$reportResultCount = count($reportResult);

					foreach($reportResult as $rkey => $rvalue)
					{
					    if($reportResultCount <= 3 && $rkey == 'total')
					    continue;

							if($rkey == 'heading'){?>
								<tr class="data_table_head">
								<td ></td>
							<?php	}	else { ?>
								<tr class="data_table_content">
								<td ><?php echo $this->get_label($rkey);?></td>
							<?php
							}

							foreach ($rvalue as $rkey1 => $rvalue1){?>
								<td><bdi><?php echo $rvalue1;?></bdi></td>
							<?php } ?>
							</tr>
				 <?php } ?>
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
				    if($iindex == 0){?>
						<tr class="data_table_head">
							<?php	foreach ($rvalue as $rkey1 => $rvalue1){?>
								<td><bdi><?php echo $rvalue1;?></bdi></td>
							<?php	}	?>
						</tr>
						<?php }	else {

						$enterflag = 0;
						$rowSpan   = count($rvalue);

						foreach($rvalue as $rkey1 => $rvalue1){?>

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

			<?php
			if($countrywise_data_tracking ==1)
			{
				  $newfrom=str_replace('/','-',$from_date);
				  $newto=str_replace('/','-',$to_date);

				  $this->dispatch("publisher/country/".$duration."/".$newfrom."/".$newto);
			}
			?>

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
manageTabClicks('user', 'publisher_statistics', <?php echo $tab;?>, <?php echo $tabCount;?>, <?php echo $direction;?>);
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
