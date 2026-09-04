<?php
$this->dispatch("layout/header_iframe/4");

$google_map_api_key = Configuration::get_instance()->read('google_map_api_key');
$uid                = $this->get_variable('uid');
$mapDataStringArray = json_decode($this->get_variable('mapDataStringArray'), 1);
$sumDataArray       = $this->get_array('sumDataArray');
$countryReportArray = $this->get_array('countryReportArray');

$tabCount = 0;
$tabWidth = 0;

if(is_array($mapDataStringArray) && count($mapDataStringArray) > 0)
{
    $tabCount = count($mapDataStringArray);
    $tabWidth = round((100 / $tabCount), 2);
}

$cpc_enabled=$this->get_variable('cpc_enabled');
$cpa_enabled=$this->get_variable('cpa_enabled');
$cpm_enabled=$this->get_variable('cpm_enabled');
$html_enabled=$this->get_variable('html_enabled');
$cpd_enabled=$this->get_variable("cpd_enabled");
$cpp_enabled=$this->get_variable('cpp_enabled');

$cpm_addon_enabled = 0;
if($cpm_enabled == 1 || $html_enabled == 1)
$cpm_addon_enabled = 1;

$duration      = $this->get_variable("duration");
$from_date_get = $this->get_variable("from_date_get");
$to_date_get   = $this->get_variable("to_date_get");
$sortby        = $this->get_variable("sortby");
$index         = -1;

$direction = $this->get_locale_direction();
?>
<?php if($tabCount > 0){ ?>
<div class="container-fluid mt-2">
	<div class="col-lg-6 col-sm-12 col-md-6 col-12">
		<div class="tab-nav">
			<?php if($cpc_enabled ==1 && isset($mapDataStringArray['cpc']))
			{
			$index++;
			?>
			<button class="tab-btn tab-btn-0" data-index="<?php echo $index; ?>" onClick="show_tab(0, 'cpc');"><span><i class="fa fa-hand-o-up tab-icon" aria-hidden="true"></i><?php echo $this->get_label('cpc');?></span></button>
			<?php }?>

			<?php if($cpm_addon_enabled ==1 && isset($mapDataStringArray['cpm']))
			{
			$index++;
			?>
			<button class="tab-btn tab-btn-1" data-index="<?php echo $index; ?>" onClick="show_tab(1, 'cpm');"><span><i class="fa fa-usd tab-icon" aria-hidden="true"></i><?php echo $this->get_label('cpm');?></span></button>
			<?php }?>

			<?php if($cpa_enabled ==1 && isset($mapDataStringArray['cpa']))
			{
			$index++;
			?>
			<button class="tab-btn tab-btn-6" data-index="<?php echo $index; ?>" onClick="show_tab(6, 'cpa');"><span><i class="fa fa-desktop tab-icon" aria-hidden="true"></i><?php echo $this->get_label('cpa');?></span></button>
			<?php }?>

			<?php if($cpd_enabled ==1 && isset($mapDataStringArray['cpd']))
			{
			$index++;
			?>
			<button class="tab-btn tab-btn-3" data-index="<?php echo $index; ?>" onClick="show_tab(3, 'cpd');"><span><i class="fa fa-calendar tab-icon" aria-hidden="true"></i><?php echo $this->get_label('cpd');?></span></button>
			<?php }?>

			<?php if($cpp_enabled ==1 && isset($mapDataStringArray['cpp']))
			{
			$index++;
			?>
			<button class="tab-btn tab-btn-18" data-index="<?php echo $index; ?>" onClick="show_tab(18, 'cpp');"><span><i class="fa fa-calendar tab-icon" aria-hidden="true"></i><?php echo $this->get_label('cpp');?></span></button>
			<?php }?>

			<div class="tab-indicator" id="tabIndicator" style="width: <?php echo $tabWidth; ?>%;"></div>
		</div>
	</div>
	<div class="col-lg-12 col-md-12 col-sm-12 col-12 p-3 map-div">
		<div class="row">
			<div class="col-lg-6 col-md-6 col-sm-6 col-6 pt-2">
				<h5 class="main-title"> <i title="<?php echo $this->get_label('geographic report'); ?>" class="fa fa-globe geography-icon" aria-hidden="true"></i><span class="geographic-report-title"> &nbsp;<?php echo $this->get_label('geographic report');?></span></h5>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-6 pt-2">
				<?php
				$form2 = $this->create_form();
				$form2->start("overall_adreport",$this->make_url("dashboard/publisher_country/".$duration."/".$from_date_get."/".$to_date_get),"post");
				?>
				<input type="hidden" name="duration" id="duration" value="<?php echo $duration; ?>" />
				<input type="hidden" name="from_date" id="from_date" value="<?php echo $from_date_get; ?>" />
				<input type="hidden" name="to_date" id="to_date" value="<?php echo $to_date_get; ?>" />
					<span class="duration-filter d-flex">
						<span class="me-2">
							<select class="form-select" name="sortby" id="sortby">
								<option value="0" <?php if($sortby == 0) { echo "selected"; } ?>><?php echo $this->get_label('impressions');?></option>
								<option value="1" <?php if($sortby == 1) { echo "selected"; } ?>><?php echo $this->get_label('clicks');?></option>
								<?php if($cpa_enabled ==1){?>
								<option value="2" <?php if($sortby == 2) { echo "selected"; } ?>><?php echo $this->get_label('conversions');?></option>
								<?php }?>
								<option value="3" <?php if($sortby == 3) { echo "selected"; } ?>><?php echo $this->get_label('profit');?></option>
							</select>
						</span>
						<span>
							<input class="btn btn-info" type="submit" name="search" value="<?php echo $this->get_label('go');?>" />
						</span>
					</span>
				<?php $form2->end(); ?>
			</div>

		<?php foreach($mapDataStringArray as $key => $value){?>
			<div class="col-lg-12 col-sm-12 col-md-12 col-12 mb-2 country-map-outer country-map-outer-<?php echo $key;?> d-none">
				<div class="row">
					<div class="col-lg-6 col-sm-6 col-md-6 col-12 mb-2 country-map-left">
						<div class="country-map-div" id="country-map-<?php echo $key;?>"></div>
					</div>
					<div class="col-lg-6 col-sm-6 col-md-6 col-12 mb-2 country-map-right">
						<?php if(!isset($countryReportArray[$key])){?>
							<div class="row py-3 country-section">
								<div class="col-lg-12 col-sm-12 col-md-12 col-12">
									<?php echo $this->get_label('no records found');?>
								</div>
							</div>
						<?php } else {
						foreach($countryReportArray[$key] as $key1 => $value1){?>
							<div class="row py-3 country-section">
								<div class="col-lg-1 col-sm-1 col-md-1 col-1">
									<?php if(file_exists('images/flags/'.strtolower($value1[0]).'.png')){?>
										<img src="<?php echo 'images/flags/'.strtolower($value1[0]).'.png';?>" />
									<?php } ?>
								</div>
								<div class="col-lg-5 col-sm-5 col-md-5 col-5"><?php echo $value1[1];?></div>
                				<div class="col-lg-3 col-sm-3 col-md-3 col-3">
								<?php
									if($sortby == 3)
									echo $this->get_money_format($value1[2]);
									else
									echo $value1[2];
								?>
								</div>
								<div class="col-lg-3 col-sm-3 col-md-3 col-3">
									<?php
                  if($sumDataArray[$key] > 0)
                  echo number_format(($value1[2]/$sumDataArray[$key])*100,1)."%";
									?>
								</div>
							</div>
						<?php }} ?>
					</div>
				</div>
			</div>
		<?php } ?>
		</div>
	</div>
</div>

<script type="text/javascript">
	var regionMap  = {};
    <?php foreach($mapDataStringArray as $key => $value){?>
		regionMap['<?php echo $key; ?>'] = '<?php echo $value; ?>';
	<?php } ?>

    function drawRegionsMap(pricing)
	{
		var data    = google.visualization.arrayToDataTable(JSON.parse(regionMap[pricing]));
        var options = {};
        var chart   = new google.visualization.GeoChart(document.getElementById('country-map-'+pricing));

        chart.draw(data, options);
    }

	function show_tab(id, pricing)
	{
		$('.country-map-outer').addClass("d-none");
		$('.tab-btn').removeClass('active');

		$('.country-map-outer-'+pricing).removeClass("d-none");
		$('.tab-btn-'+id).addClass('active');

		var tabLength = <?php echo $tabCount; ?>;
		var tabWidth  = 100 / tabLength;

		const indicator = document.getElementById("tabIndicator");
		const index     = $('.tab-btn-'+id).attr("data-index");

       	<?php if($direction == 1){?>
		indicator.style.right = `${index * tabWidth}%`;	
		<?php } else { ?>
		indicator.style.left = `${index * tabWidth}%`;
		<?php } ?>

		google.charts.setOnLoadCallback(function () { drawRegionsMap(pricing); });

		var contentHeight = $(".body-section").outerHeight();
			contentHeight = parseFloat(contentHeight) + 10;

		$('#iframe-section-3', window.parent.document).css("height", contentHeight+"px");
	}

    $(document).ready(function()
    {
		google.charts.load('current', {'packages':['geochart'],'mapsApiKey': '<?php echo $google_map_api_key;?>'});

		$(window).resize(function()
		{
			//For resize map
			$(".tab-li-selected").click();
		});

		<?php if($cpa_enabled == 1 && $sortby == 2){?>
		show_tab(6, 'cpa');
		<?php } else if($cpc_enabled ==1){?>
		show_tab(0, 'cpc');
		<?php } else if($cpm_addon_enabled ==1){?>
		show_tab(1, 'cpm');
		<?php } else if($cpa_enabled ==1){?>
		show_tab(6, 'cpa');
		<?php } else if($cpd_enabled ==1){?>
		show_tab(3, 'cpd');
		<?php } else if($cpp_enabled ==1){?>
		show_tab(18, 'cpp');
		<?php }?>
    });
</script>
<?php } ?>
<?php $this->dispatch("layout/footer_iframe");?>
