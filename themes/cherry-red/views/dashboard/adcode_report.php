
<?php
$this->dispatch("layout/header_iframe/2");

$cpc_enabled=$this->get_addon_status('cpc_enabled');
$cpa_enabled=$this->get_addon_status('cpa_enabled');
$cpm_enabled=$this->get_addon_status('cpm_enabled');
$html_enabled=$this->get_addon_status('html_enabled');
$cpd_enabled=$this->get_addon_status("sponsored_enabled");
$cpp_enabled=$this->get_addon_status('cpp_enabled');
$currency_symbol = Configuration::get_instance()->read('currency_symbol');

$cpm_addon_enabled = 0;
if($cpm_enabled == 1 || $html_enabled == 1)
$cpm_addon_enabled = 1;

$uid          = $this->get_variable('uid');
$reportResult = $this->get_array("reportResult");

$tabCount = 0;
$tabWidth = 0;

if(is_array($reportResult))
{
    $tabCount = count($reportResult) - 2;//($tabCount-2) Using for exclude the array key "total and heading"
    $tabWidth = round((100 / $tabCount), 2);
}

$index = -1;

$direction = $this->get_locale_direction();
?>

<div class="container-fluid mt-2">
	<div class="row">
		<div class="col-lg-6 col-sm-6 col-md-6 col-12">
			<div class="tab-nav">
				<?php if($cpc_enabled ==1 && isset($reportResult['cpc']))
				{
				$index++;
				?>
				<button class="tab-btn tab-btn-0" data-index="<?php echo $index; ?>" onClick="show_tab(0, 'cpc');"><i class="fa fa-hand-o-up tab-icon" aria-hidden="true"></i><span><?php echo $this->get_label('cpc');?></span></button>
				<?php }?>

				<?php if($cpm_addon_enabled ==1 && isset($reportResult['cpm']))
				{
				$index++;
				?>
				<button class="tab-btn tab-btn-1" data-index="<?php echo $index; ?>" onClick="show_tab(1, 'cpm');"><i class="fa fa-usd tab-icon" aria-hidden="true"></i><span><?php echo $this->get_label('cpm');?></span></button>
				<?php }?>

				<?php if($cpa_enabled ==1 && isset($reportResult['cpa']))
				{
				$index++;
				?>
				<button class="tab-btn tab-btn-6" data-index="<?php echo $index; ?>" onClick="show_tab(6, 'cpa');"><i class="fa fa-desktop tab-icon" aria-hidden="true"></i><span><?php echo $this->get_label('cpa');?></span></button>
				<?php }?>

				<?php if($cpd_enabled ==1 && isset($reportResult['cpd']))
				{
				$index++;
				?>
				<button class="tab-btn tab-btn-3" data-index="<?php echo $index; ?>" onClick="show_tab(3, 'cpd');"><span><i class="fa fa-calendar tab-icon" aria-hidden="true"></i><?php echo $this->get_label('cpd');?></span></button>
				<?php }?>

				<?php if($cpp_enabled ==1 && isset($reportResult['cpp']))
				{
				$index++;
				?>
				<button class="tab-btn tab-btn-18" data-index="<?php echo $index; ?>" onClick="show_tab(18, 'cpp');"><span><i class="fa fa-calendar tab-icon" aria-hidden="true"></i><?php echo $this->get_label('cpp');?></span></button>
				<?php }?>

				<div class="tab-indicator" id="tabIndicator" style="width: <?php echo $tabWidth; ?>%;"></div>
			</div>
		</div>

		<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 mb-2">
				<?php if($cpc_enabled ==1 && isset($reportResult['cpc'])){?>
				<div class="report-div report-div-0 mb-2 d-none">
					<table id="table-desktop0" class="data_table" cellpadding="0" cellspacing="0">
						<tr class="data_table_head">
							<td><?php echo $this->get_label('impressions');?></td>
							<td><?php echo $this->get_label('clicks');?></td>
							<td><bdi><?php echo $this->get_label('ctr');?></bdi></td>
							<td><bdi><?php echo $this->get_label('ecpm')." (".$currency_symbol.")";?></bdi></td>
							<td><bdi><?php echo $this->get_label('profit')." (".$currency_symbol.")";?></bdi></td>
						</tr>

						<tr class="data_table_content">
							<td><?php echo $reportResult['cpc']['impression'];?></td>
							<td><?php echo $reportResult['cpc']['click'];?></td>
							<td><bdi><?php echo $reportResult['cpc']['ctr'];?></bdi></td>
							<td><bdi><?php echo $reportResult['cpc']['ecpm'];?></bdi></td>
							<td><bdi><?php echo $reportResult['cpc']['profit'];?></bdi></td>
						</tr>
					</table>
				</div>
				<div class="row graph-div-outer graph-div-outer-0 d-none">
					<div class="col-lg-6 col-sm-12 col-md-6 col-xs-12 mb-2">
						<div class="graph-box">
						<div id="traffic-graph-cpc" class="graph-div graph-div-0"></div>
					</div></div>
					<div class="col-lg-6 col-sm-12 col-md-6 col-xs-12 mb-2">
						<div class="graph-box">
						<div id="accounts-graph-cpc" class="graph-div graph-div-0"></div>
					</div></div>
				</div>
				<?php }?>

				<?php if($cpm_addon_enabled ==1 && isset($reportResult['cpm'])){?>
				<div class="report-div report-div-1 mb-2 d-none">
					<table id="table-desktop1" class="data_table" cellpadding="0" cellspacing="0">
						<tr class="data_table_head">
							<td><?php echo $this->get_label('impressions');?></td>

							<?php if($cpm_enabled ==1){?>
							<td><?php echo $this->get_label('clicks');?></td>
							<td><bdi><?php echo $this->get_label('ctr');?></bdi></td>
							<?php }?>

							<td><bdi><?php echo $this->get_label('profit')." (".$currency_symbol.")";?></bdi></td>
						</tr>

						<tr class="data_table_content">
							<td><?php echo $reportResult['cpm']['impression'];?></td>

							<?php if($cpm_enabled ==1){?>
							<td><?php echo $reportResult['cpm']['click'];?></td>
							<td><bdi><?php echo $reportResult['cpm']['ctr'];?></bdi></td>
							<?php }?>

							<td><bdi><?php echo $reportResult['cpm']['profit'];?></bdi></td>
						</tr>
					</table>
				</div>
				<div class="row graph-div-outer graph-div-outer-1 d-none">
					<div class="col-lg-6 col-sm-12 col-md-6 col-xs-12 mb-2">
						<div class="graph-box">
						<div id="traffic-graph-cpm" class="graph-div graph-div-1"></div>
					</div></div>
					<div class="col-lg-6 col-sm-12 col-md-6 col-xs-12 mb-2">
						<div class="graph-box">
						<div id="accounts-graph-cpm" class="graph-div graph-div-1"></div>
					</div></div>
				</div>
				<?php }?>

				<?php if($cpa_enabled ==1 && isset($reportResult['cpa'])){?>
				<div class="report-div report-div-6 mb-2 d-none">
					<table id="table-desktop6" class="data_table" cellpadding="0" cellspacing="0">
						<tr class="data_table_head">
							<td><?php echo $this->get_label('impressions');?></td>
							<td><?php echo $this->get_label('clicks');?></td>
							<td><?php echo $this->get_label('conversions');?></td>
							<td><bdi><?php echo $this->get_label('conversion ratio');?></bdi></td>
							<td><bdi><?php echo $this->get_label('profit')." (".$currency_symbol.")";?></bdi></td>
						</tr>

						<tr class="data_table_content">
							<td><?php echo $reportResult['cpa']['impression'];?></td>
							<td><?php echo $reportResult['cpa']['click'];?></td>
							<td><?php echo $reportResult['cpa']['conversion'];?></td>
							<td><bdi><?php echo $reportResult['cpa']['conversionratio'];?></bdi></td>
							<td><bdi><?php echo $reportResult['cpa']['profit'];?></bdi></td>
						</tr>
					</table>
				</div>
				<div class="row graph-div-outer graph-div-outer-6 d-none">
					<div class="col-lg-6 col-sm-12 col-md-6 col-xs-12 mb-2">
						<div class="graph-box">
						<div id="traffic-graph-cpa" class="graph-div graph-div-6"></div>
					</div></div>
					<div class="col-lg-6 col-sm-12 col-md-6 col-xs-12 mb-2">
						<div class="graph-box">
						<div id="accounts-graph-cpa" class="graph-div graph-div-6"></div>
					</div></div>
				</div>
				<?php }?>

				<?php if($cpd_enabled ==1 && isset($reportResult['cpd']))
				{
					$sponsoredrunning=$this->get_variable("sponsoredrunning");
					$sponsoredexpired=$this->get_variable("sponsoredexpired");
					?>
					<div class="report-div report-div-3 mb-2 d-none">
						<table id="table-desktop3" class="data_table" cellpadding="0" cellspacing="0">
							<tr class="data_table_head">
								<td ><?php echo $this->get_label('impressions');?></td>
								<td ><?php echo $this->get_label('clicks');?></td>
								<td ><bdi><?php echo $this->get_label('ctr');?></bdi></td>
								<td ><bdi><?php echo $this->get_label('profit')." (".$currency_symbol.")";?></bdi></td>
								<td ><?php echo $this->get_label('sponsored running');?></td>
								<td ><?php echo $this->get_label('sponsored expired');?></td>
							</tr>

							<tr class="data_table_content">
								<td><?php echo $reportResult['cpd']['impression']?></td>
								<td><?php echo $reportResult['cpd']['click'];?></td>
								<td><bdi><?php echo $reportResult['cpd']['ctr'];?></bdi></td>
								<td><bdi><?php echo $reportResult['cpd']['profit'];?></bdi></td>
								<td><?php echo $sponsoredrunning;?></td>
								<td><?php echo $sponsoredexpired;?></td>
							</tr>
						</table>
					</div>
					<div class="row graph-div-outer graph-div-outer-3 d-none">
						<div class="col-lg-6 col-sm-12 col-md-6 col-xs-12 mb-2">
							<div class="graph-box">
							<div id="traffic-graph-cpd" class="graph-div graph-div-3"></div>
						</div></div>
						<div class="col-lg-6 col-sm-12 col-md-6 col-xs-12 mb-2">
							<div class="graph-box">
							<div id="accounts-graph-cpd" class="graph-div graph-div-3"></div>
						</div></div>
					</div>
				<?php }?>

				<?php if($cpp_enabled ==1 && isset($reportResult['cpp'])){?>
				<div class="report-div report-div-18 mb-2 d-none">
					<table id="table-desktop18" class="data_table" cellpadding="0" cellspacing="0">
						<tr class="data_table_head">
							<td ><?php echo $this->get_label('impressions');?></td>
							<td ><?php echo $this->get_label('clicks');?></td>
							<td ><bdi><?php echo $this->get_label('ctr');?></bdi></td>
							<td ><bdi><?php echo $this->get_label('profit')." (".$currency_symbol.")";?></bdi></td>
						</tr>

						<tr class="data_table_content">
							<td><?php echo $reportResult['cpp']['impression'];?></td>
							<td><?php echo $reportResult['cpp']['click'];?></td>
							<td><bdi><?php echo $reportResult['cpp']['ctr'];?></bdi></td>
							<td><bdi><?php echo $reportResult['cpp']['profit'];?></bdi></td>
						</tr>
					</table>
				</div>
				<div class="row graph-div-outer graph-div-outer-18 d-none">
					<div class="col-lg-6 col-sm-12 col-md-6 col-xs-12 mb-2">
						<div class="graph-box">
						<div id="traffic-graph-cpp" class="graph-div graph-div-18"></div>
					</div></div>
					<div class="col-lg-6 col-sm-12 col-md-6 col-xs-12 mb-2">
						<div class="graph-box">
						<div id="accounts-graph-cpp" class="graph-div graph-div-18"></div>
					</div></div>
				</div>
				<?php }?>
		</div>
	</div>
</div>
<?php
$reportResultTimeperiod = $this->get_array("reportResultTimeperiod");

$currencyvariable = " (".$currency_symbol.")";
$iindex         = 0;

$dateString 		 = "";

$data = [
    'cpc' => ['impression' => [], 'click' => [], 'profit' => []],//, 'ctr' => [], 'ecpm' => []
    'cpm' => ['impression' => [], 'click' => [], 'profit' => []],//, 'ctr' => []
    'cpa' => ['impression' => [], 'click' => [], 'conversion' => [], 'profit' => []],//, 'ctr' => [], 'conversionratio' => []
	'cpd' => ['impression' => [], 'click' => [], 'profit' => []],//, 'ctr' => []
	'cpp' => ['impression' => [], 'click' => [], 'profit' => []],//, 'ctr' => []
];
$dateKeys = [];

foreach($reportResultTimeperiod as $date => $metrics)
{
    if($iindex == 0) //Remove heading
    {
    	$iindex++;
    	continue;
    }

    $dateKeys[] = "'$date'";

    foreach ($metrics as $model => $values)
	{
        if(!isset($data[$model]))
		continue;

        foreach ($values as $key => $value)
		{
            if(isset($data[$model][$key]))
            $data[$model][$key][] = $value;
		}
	}

	$iindex++;
}

$dateString = "[" . implode(",", $dateKeys) . "]";

if($cpc_enabled == 1)
{
	$ImpressionString = implode(",", $data['cpc']['impression']);
	$ClickString      = implode(",", $data['cpc']['click']);
	$ProfitString     = implode(",", $data['cpc']['profit']);
	$CtrString        = -1;//implode(",", $data['cpc']['ctr']);

	//Impressions/clicks/ctr
	$trafficGraphData['cpc']  = $this->get_graph_data(1, $ImpressionString, $ClickString, $CtrString, -1);

	//Profit
	$accountsGraphData['cpc'] = $this->get_graph_data(2, -1, -1, -1, -1, $ProfitString);
}

if($cpm_enabled == 1)
{
	$ImpressionString = implode(",", $data['cpm']['impression']);
	$ClickString      = implode(",", $data['cpm']['click']);
	$ProfitString     = implode(",", $data['cpm']['profit']);
	$CtrString        = -1;//implode(",", $data['cpm']['ctr']);


	//Impressions/clicks/ctr
	$trafficGraphData['cpm']  = $this->get_graph_data(1, $ImpressionString, $ClickString, $CtrString, -1);

	//Profit
	$accountsGraphData['cpm'] = $this->get_graph_data(2, -1, -1, -1, -1, $ProfitString);
}

if($cpa_enabled == 1)
{
	$ImpressionString      = implode(",", $data['cpa']['impression']);
	$ClickString           = implode(",", $data['cpa']['click']);
	$ConversionString      = implode(",", $data['cpa']['conversion']);
	$ProfitString          = implode(",", $data['cpa']['profit']);
	$CtrString             = -1;//implode(",", $data['cpa']['ctr']);
	$ConversionRatioString = -1;//implode(",", $data['cpa']['conversionratio']);

	//Impressions/clicks/ctr
	$trafficGraphData['cpa'] = $this->get_graph_data(1, $ImpressionString, $ClickString, $CtrString, -1, -1, -1, $ConversionString, $ConversionRatioString);

	//Profit
	$accountsGraphData['cpa'] = $this->get_graph_data(2, -1, -1, -1, -1, $ProfitString);
}

if($cpd_enabled == 1)
{
	$ImpressionString = implode(",", $data['cpd']['impression']);
	$ClickString      = implode(",", $data['cpd']['click']);
	$ProfitString     = implode(",", $data['cpd']['profit']);
	$CtrString        = -1;//implode(",", $data['cpd']['ctr']);

	//Impressions/clicks/ctr
	$trafficGraphData['cpd']  = $this->get_graph_data(1, $ImpressionString, $ClickString, $CtrString, -1);

	//Profit
	$accountsGraphData['cpd'] = $this->get_graph_data(2, -1, -1, -1, -1, $ProfitString);
}

if($cpp_enabled == 1)
{
	$ImpressionString = implode(",", $data['cpp']['impression']);
	$ClickString      = implode(",", $data['cpp']['click']);
	$ProfitString     = implode(",", $data['cpp']['profit']);
	$CtrString        = -1;//implode(",", $data['cpp']['ctr']);


	//Impressions/clicks/ctr
	$trafficGraphData['cpp']  = $this->get_graph_data(1, $ImpressionString, $ClickString, $CtrString, -1);

	//Profit
	$accountsGraphData['cpp'] = $this->get_graph_data(2, -1, -1, -1, -1, $ProfitString);
}
?>
<script type="text/javascript">
var trafficCharts  = {};
var accountsCharts = {};

function show_tab(id, pricing)
{
	$('.report-div').addClass("d-none");
	$('.graph-div-outer').addClass("d-none");
	$('.tab-btn').removeClass('active');

	$('.report-div-'+id).removeClass("d-none");
	$('.graph-div-outer-'+id).removeClass("d-none");
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

	var container = document.querySelector(`#traffic-graph-${pricing}`);

	if(!container.dataset.chartRendered)
	{
		trafficCharts[pricing].render();
		accountsCharts[pricing].render();
		container.dataset.chartRendered = "true";
	}

	var contentHeight = $(".body-section").outerHeight();
		contentHeight = parseFloat(contentHeight) + 10;

	$('#iframe-section-2', window.parent.document).css("height", contentHeight+"px");
}

$(document).ready(function() {
	<?php foreach($trafficGraphData as $key => $value){?>

    document.querySelector("#traffic-graph-<?php echo $key; ?>")
  .insertAdjacentHTML("beforebegin",
    '<div class="chart-title"><i class="fa fa-bar-chart icon_blue"></i><?php echo $this->get_label("traffic analytics"); ?></div>'
  );

  var TrafficAnalyticsGraphSeries1Color = '#FF0000';
  var TrafficAnalyticsGraphSeries2Color = '#33B5FF';
  var TrafficAnalyticsGraphSeries3Color = '#28A745';

  var adRevenueGraphCurveColor = '#8B0000';
  var adRevenueGraphFillColor = '#DEB887';

  var optionsTraffic = {
    series: [<?php echo $value[0];?>],
    chart: {
      redrawOnParentResize: true,
      height: 425,
      toolbar: { show: false },
      zoom: { enabled: false },
    },
    colors: [TrafficAnalyticsGraphSeries1Color, TrafficAnalyticsGraphSeries2Color, TrafficAnalyticsGraphSeries3Color],
    plotOptions: {
      bar: { endingShape: 'rounded' }
    },
    stroke: { curve: 'straight', width: [2, 2, 2, 2, 2] },
    legend: { show: true },
    xaxis: {
      categories: <?php echo $dateString;?>,
      axisTicks: { show: true },
      axisBorder: { show: true },
      labels: { show: false }
    },
    yaxis: [<?php echo $value[1];?>]
  };

			trafficCharts['<?php echo $key; ?>'] = new ApexCharts(document.querySelector("#traffic-graph-<?php echo $key;?>"), optionsTraffic);
	<?php } ?>
	<?php foreach($accountsGraphData as $key => $value){?>

    document.querySelector("#accounts-graph-<?php echo $key; ?>")
  .insertAdjacentHTML("beforebegin",
    '<div class="chart-title"><i class="fa fa-line-chart icon_green"></i><?php echo $this->get_label("ad revenue"); ?></div>'
  );
          var optionsAccounts = {
          series: [<?php echo $value[0];?>],
          chart: {
            redrawOnParentResize: true,
            height: 425,
            toolbar: { show: false },
            zoom: { enabled: false }
          },
          colors: [adRevenueGraphCurveColor],
          stroke: { curve: 'smooth', width: 2, colors: [adRevenueGraphCurveColor] },
          fill: { type: 'solid', colors: [adRevenueGraphFillColor], opacity: 0.5},
          legend: { show: true, showForSingleSeries: true },
          xaxis: {
            categories: <?php echo $dateString;?>,
            axisTicks: { show: true, color: '#999', height: 4 },
            axisBorder: { show: true, color: '#999', height: 1 },
            labels: { show: false },
            tooltip: { enabled: true }
          },
          yaxis: <?php echo $value[1];?>,
          tooltip: {
            y: {
              formatter: function (value) {
                return value + " <?php echo $currency_symbol;?>";
              }
            }
          }
        };

			accountsCharts['<?php echo $key; ?>'] = new ApexCharts(document.querySelector("#accounts-graph-<?php echo $key;?>"), optionsAccounts);
	<?php } ?>

	<?php if($cpc_enabled ==1){?>
	show_tab(0, 'cpc');
	<?php }else if($cpm_addon_enabled ==1){?>
	show_tab(1, 'cpm');
	<?php }else if($cpa_enabled ==1){?>
	show_tab(6, 'cpa');
	<?php }else if($cpd_enabled ==1){?>
	show_tab(3, 'cpd');
	<?php }else if($cpp_enabled ==1){?>
	show_tab(18, 'cpp');
	<?php }?>

	<?php if($cpc_enabled ==1){?>
	CreateResponsiveTable('table-desktop0');
	<?php }?>

	<?php if($cpm_addon_enabled ==1){?>
	CreateResponsiveTable('table-desktop1');
	<?php }?>

	<?php if($cpd_enabled ==1){?>
	CreateResponsiveTable('table-desktop3');
	<?php }?>

	<?php if($cpa_enabled ==1){?>
	CreateResponsiveTable('table-desktop6');
	<?php }?>

	<?php if($cpp_enabled ==1){?>
	CreateResponsiveTable('table-desktop18');
	<?php }?>

	$(window).resize(function()
	{
		<?php if($cpc_enabled ==1){?>
	 	CreateResponsiveTable('table-desktop0');
		<?php }?>

	 	<?php if($cpm_addon_enabled ==1){?>
		CreateResponsiveTable('table-desktop1');
		<?php }?>

		<?php if($cpd_enabled ==1){?>
		CreateResponsiveTable('table-desktop3');
		<?php }?>

		<?php if($cpa_enabled ==1){?>
		CreateResponsiveTable('table-desktop6');
		<?php }?>

		<?php if($cpp_enabled ==1){?>
		CreateResponsiveTable('table-desktop18');
		<?php }?>
	});
});
</script>
<?php $this->dispatch("layout/footer_iframe");?>
