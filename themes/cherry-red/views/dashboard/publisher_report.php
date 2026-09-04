<?php
$this->dispatch("layout/header_iframe/8");

$direction = $this->get_variable('direction');
$currency_symbol = Configuration::get_instance()->read('currency_symbol');

if($direction == 1)
$graphdir = -1;
else
$graphdir = 1;

$uid       = $this->get_variable('uid');



$res             = $this->get_result('res');
$value           = $res[0];
$payment_amount  = $this->get_all_withdrawal_amount($uid);
$account_balance = $value['pub_account_balance'];

	$reportResultTimeperiod = $this->get_array("reportResultTimeperiod");

	$currencyvariable = " (".$currency_symbol.")";
	$iindex         = 0;

	$dateString 	= "";

	$data = ['impression' => [], 'click' => [], 'ctr' => [], 'profit' => []];

	$dateKeys = [];

	foreach($reportResultTimeperiod as $date => $metrics)
	{
		if($iindex == 0) //Remove heading
		{
			$iindex++;
			continue;
		}

		$dateKeys[] = "'$date'";

		$ImpressionSum = 0;
		$ClickSum      = 0;
		$ProfitSum     = 0;

		foreach($metrics as $model => $values)
		{
			if(isset($values['impression']))
			$ImpressionSum = $ImpressionSum + $values['impression'];

			if(isset($values['click']))
			$ClickSum = $ClickSum + $values['click'];

			if(isset($values['profit']))
			$ProfitSum = $ProfitSum + $values['profit'];
		}

		$data['impression'][] = $ImpressionSum;
		$data['click'][]      = $ClickSum;
		$data['profit'][]     = $ProfitSum;

		$iindex++;
	}

	$dateString = "[" . implode(",", $dateKeys) . "]";

	$ImpressionString = implode(",", $data['impression']);
	$ClickString      = implode(",", $data['click']);
	$ProfitString      = implode(",", $data['profit']);

	$totalImpression = $totalClick = $totalProfit = 0;

	foreach ($data['impression'] as $ImpressionValue)
	$totalImpression+=$ImpressionValue;

	foreach ($data['click'] as $ClickValue)
	$totalClick+=$ClickValue;

	foreach ($data['profit'] as $ProfitValue)
	$totalProfit+=$ProfitValue;

	$accountsGraphData['impression'] = $this->get_graph_data(2, $ImpressionString, -1, -1, -1);
	$accountsGraphData['click']      = $this->get_graph_data(2, -1, $ClickString, -1, -1);
	$accountsGraphData['profit']     = $this->get_graph_data(2, -1, -1, -1, -1, $ProfitString);
?>
<div class="container-fluid mt-2">
	<div class="row">



		<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
		

		<div class="row mg-top-row">
			<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 mobile_view mb-3">
				<div class="home-box-div tile_red">
					<div class="row mx-auto">
						<div class="col-md-5 icon-outer">
							<div class="icon_td background-red"><i class="fa fa-location-arrow" aria-hidden="true"></i></div>
						</div>
						<div class="col-md-7">
							<div class="home-box-td-first">
								<?php echo $this->get_active_adcode_count($uid);?>
							</div>
							<div class="home-box-td-second"><?php echo $this->get_label('adcodes');?></div>
						</div>
					</div>
					<div class="bottom-sec btn-red"><a target="_parent" href="<?php echo $this->make_url("adunit/view"); ?>"><i class="fa fa-long-arrow-right" aria-hidden="true"></i></a></div>
					<div class="Ft-sec"><a class="text-red" target="_parent" href="<?php echo $this->make_url("adunit/view"); ?>"><?php echo $this->get_label('more details'); ?> <i class="fa fa-angle-double-right" aria-hidden="true"></i></a></div>
				</div>
			</div>
			<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 mobile_view mb-3">
				<div class="home-box-div tile_green">
					<div class="row mx-auto">
						<div class="col-md-5 icon-outer">
							<div class="icon_td background-green"><i class="fa fa-money" aria-hidden="true"></i></div>
						</div>
						<div class="col-md-7">
							<div class="home-box-td-first">
								<?php echo $this->get_number_format($payment_amount);?>
							</div>
							<div class="home-box-td-second"><?php echo $this->get_label('withdrawal');?> (<?php echo Configuration::get_instance()->read('currency_symbol'); ?>)</div>
						</div>
					</div>
					<div class="bottom-sec btn-green"><a target="_parent" href="<?php echo $this->make_url("user/withdrawal_history"); ?>"><i class="fa fa-long-arrow-right" aria-hidden="true"></i></a></div>
					<div class="Ft-sec"><a class="text-green" target="_parent" href="<?php echo $this->make_url("user/withdrawal_history"); ?>"><?php echo $this->get_label('more details'); ?> <i class="fa fa-angle-double-right" aria-hidden="true"></i></a></div>
				</div>
			</div>
			<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 mobile_view mb-3">
				<div class="home-box-div tile_blue">
					<div class="row mx-auto">
						<div class="col-md-5 icon-outer">
							<div class="icon_td background-blue"><i class="fa fa-user" aria-hidden="true"></i></div>
						</div>
						<div class="col-md-7">
							<div class="home-box-td-first">
								<?php echo $this->get_number_format($account_balance);?>
							</div>
							<div class="home-box-td-second"><?php echo $this->get_label('account balance');?> (<?php echo Configuration::get_instance()->read('currency_symbol'); ?>)</div>
						</div>
					</div>

					<div class="bottom-sec btn-blue"><a btn-green target="_parent" href="<?php echo $this->make_url("user/account"); ?>"><i class="fa fa-long-arrow-right" aria-hidden="true"></i></a></div>
					<div class="Ft-sec"><a class="text-blue" target="_parent" href="<?php echo $this->make_url("user/account"); ?>"><?php echo $this->get_label('more details'); ?> <i class="fa fa-angle-double-right" aria-hidden="true"></i></a></div>
				</div>
			</div>
		</div>
			
			
			<div class="row">
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<div class="graph-outer">
			<div id="accounts-graph-impression" class="graph-div background_red"></div>
					<div class="graph-head">

			<h6><i class="fa fa-eye ic-red" aria-hidden="true"></i><?php echo $this->get_label('impressions'); ?></h6>
		<p><?php echo $totalImpression; ?></p>
		</div>
		<div class="graph-bottom"><a class="btn-red" target="_parent" href="<?php echo $this->make_url("publisher/statistics"); ?>"><?php echo $this->get_label('more details'); ?></a></div>
			</div></div>


			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<div class="graph-outer">
			<div id="accounts-graph-click" class="graph-div background_green"></div>
					<div class="graph-head">

              <h6><i class="fa fa-hand-o-up ic-green" aria-hidden="true"></i><?php echo $this->get_label('clicks'); ?></h6>

		<p><?php echo $totalClick; ?></p>
		</div>

		<div class="graph-bottom"><a class="btn-green" target="_parent" href="<?php echo $this->make_url("publisher/statistics"); ?>"><?php echo $this->get_label('more details'); ?></a></div>
			</div></div>
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<div class="graph-outer">
			<div id="accounts-graph-profit" class="graph-div background_blue"></div>
					<div class="graph-head">

			<h6><i class="fa fa-credit-card ic-blue" aria-hidden="true"></i><?php echo $this->get_label('profit'); ?></h6>
		<p><?php echo $this->get_money_format($totalProfit); ?></p>

		</div>

		<div class="graph-bottom"><a class="btn-blue"  target="_parent" href="<?php echo $this->make_url("publisher/statistics"); ?>"><?php echo $this->get_label('more details'); ?></a></div>

			</div></div>
		</div>
			
			
	</div>
</div>
<script type="text/javascript">
$(document).ready(function()
{
	<?php
	foreach($accountsGraphData as $key => $value)
	{
		$toolTip   =  "";

		if ($key == "impression") {
		$waveColor = '#ff0000';
		$axisTickColor = '#ff0000';
		$yAxisLabelColor = '#031d2a';
		$gridLineColor = '#dddddd';
		$yAxisColor = '#ff0000';
		$xAxisColor = '#ff0000';
	}
	else if ($key == "click") {
		$waveColor = '#1da13e';
		$axisTickColor = '#1da13e';
		$yAxisLabelColor = '#031d2a';
		$gridLineColor = '#dddddd';
		$yAxisColor = '#1da13e';
		$xAxisColor = '#1da13e';
	}
	else if($key == "profit"){
		$toolTip = $currency_symbol;
$waveColor = '#09b2c7';
		$axisTickColor = '#09b2c7';
		$yAxisLabelColor = '#031d2a';
		$gridLineColor = '#dddddd';
		$yAxisColor = '#09b2c7';
		$xAxisColor = '#09b2c7';
	}

		?>
	var optionsAccounts = {
	    	     	series: [<?php echo $value[0];?>],
	    	      	chart: {
								redrawOnParentResize: true,
								height: 200,
								toolbar: {show: false},
								zoom:  { enabled: false },
	    	      			},
						colors: ['<?php echo $waveColor; ?>'],
	    	     	stroke: { curve: 'smooth',width: [2,2,2] },  //smooth  //straight
	    	       	fill: { opacity: [0.25, 0.25, 0.25] },
						grid: 	{
										show: true,
										borderColor: '<?php echo $gridLineColor; ?>',
										strokeDashArray: 0,
										yaxis: {
											lines: {
												show: true
											}
										},
					xaxis: {
											lines: {
												show: false
											}
										}
								},
						xaxis: {
									categories: <?php echo $dateString; ?>,
									labels: { show: false },
								axisTicks: {
									show: true,  // Shows small vertical ticks (like points)
										color: '<?php echo $axisTickColor; ?>',  // Optional: customize tick color
										height: 4       // Optional: adjust tick height
								},
									axisBorder: {
										show: true,
										color: '<?php echo $xAxisColor; ?>'
									}
							},
								yaxis: Object.assign(
									<?php echo json_encode($value[1]); ?>,
									{
										labels: {
											style: {
												colors: '<?php echo $yAxisLabelColor; ?>',
												fontSize: '12px'
											}
										},
										axisBorder: {
									    show: true,
									    color: '<?php echo $yAxisColor; ?>'
									  }
									}
									),
	    	    	tooltip: {
	    	          	 y: {
								formatter: function (value) {
								return value + " <?php echo $toolTip;?>";
	         	            }
	    	        	 }
	        	    }
	    	    };
				new ApexCharts(document.querySelector("#accounts-graph-<?php echo $key;?>"), optionsAccounts).render();
	<?php } ?>
});
</script>
<?php $this->dispatch("layout/footer_iframe");?>
