<script type="text/javascript">
function show_pub_stat(id)
{

	$('.showadustatclass').hide();
	$('.adustatclass').removeClass('tab-selection');

	$('#showstat'+id).show();
	$('#adustat'+id).addClass('tab-selection');

	$('#tab').val(id);
}
</script>
<?php
$duration=$this->get_variable('duration');
$tab=$this->get_variable('tab');

$cpc_enabled=$this->get_addon_status('cpc_enabled');
$cpa_enabled=$this->get_addon_status('cpa_enabled');
$cpm_enabled=$this->get_addon_status('cpm_enabled');
$html_enabled=$this->get_addon_status('html_enabled');
$cpd_enabled=$this->get_addon_status('sponsored_enabled');
$cpp_enabled=$this->get_addon_status('cpp_enabled');

?>
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/jquery-1.7.min.js'></script>
<link rel="stylesheet" href="<?php echo BASE.ADMIN_DIR."/css/style.css";?>" type="text/css" />
<link rel="stylesheet" href="<?php echo COMMON_DIR_PATH;?>font-awesome/css/font-awesome.css" />

<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>jquery-ui/jquery-ui.min.js'></script>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo COMMON_DIR_PATH;?>jquery-ui/jquery-ui.min.css" />



<script type="text/javascript" src="//www.google.com/jsapi"></script>
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


$reportResultTimeperiod = $this->get_array("reportResultTimeperiod");

if($duration ==7)
$hdata=',showTextEvery: 3';
else
$hdata=',showTextEvery: 1';


$fieldArray['cpc']['data'] 			= array("impressions","clicks","ctr");
$fieldArray['cpc']['earning'] 		= array("spend","profit","adminprofit","ecpm");

$fieldArray['cpm']['data'] 			= array("impressions","clicks","ctr");
$fieldArray['cpm']['earning'] 		= array("spend","profit","adminprofit");

$fieldArray['html']['data'] 		= array("impressions");
$fieldArray['html']['earning'] 		= array("spend");


$fieldArray['cpa']['data'] 			= array("impressions","clicks","conversions","conversionratio");
$fieldArray['cpa']['earning'] 		= array("spend","profit","adminprofit");



$fieldArray['cpp']['data'] 			= array("impressions","clicks","ctr");
$fieldArray['cpp']['earning'] 		= array("spend","profit","adminprofit");

$fieldArray['cpd']['data'] 			= array("impressions","clicks","ctr");
$fieldArray['cpd']['earning'] 		= array("spend","profit","adminprofit");

$datavalue        = "";
$earningvalue     = "";
$datahead         = "['".$this->get_label('date')."'";
$earninghead      = "['".$this->get_label('date')."'";
$currencyvariable = " (".Configuration::get_instance()->read('currency_symbol').")";

$iindex         = 0;
$firstIteration = 0;


foreach($reportResultTimeperiod as $rkey => $rvalue)
{
    if($iindex == 0) //Remove heading
    {
    	$iindex++;
    	continue;
    }


    $data = $rkey;



	if($datavalue != "")
	$datavalue.=',';

	if($earningvalue != "")
	$earningvalue.=',';



	$sparray='';

	$datavalue.="['".$data."'";
	$earningvalue.="['".$data."'";


	$indexvalue = -1;


	foreach($rvalue as $rkey1 => $rvalue1)
	{
		if($rkey1 == "cpc" || $rkey1 == "cpm" || $rkey1 == "cpd" || $rkey1 == "cpp")
		{
		 	$datavalue.=','.$rvalue1['impression'].','.$rvalue1['click'].','.$rvalue1['ctr'];

		  	if($sparray !='')
		 	$sparray.=',';

		 	$indexvalue = $indexvalue + 3;

		 	$sparray.=$indexvalue.': {type: "line",targetAxisIndex:1}';


			$earningvalue.=",".$rvalue1['spend'].",".$rvalue1['profit'].",".$rvalue1['adminprofit'];

			if($rkey1 == "cpc")
			{
				if($rvalue1['adminprofit'] > 0 && $rvalue1['impression'] > 0)
				$ppcecpm = ($rvalue1['adminprofit']*1000)/$rvalue1['impression'];
				else
				$ppcecpm = 0;

				$earningvalue.=",".round($ppcecpm,2);
			}
		}
		else if($rkey1 == "cpa")
		{
		 	$datavalue.=','.$rvalue1['impression'].','.$rvalue1['click'].','.$rvalue1['conversion'].','.$rvalue1['conversionratio'];

		  	if($sparray !='')
		 	$sparray.=',';

		 	$indexvalue = $indexvalue + 4;

		 	$sparray.=$indexvalue.': {type: "line",targetAxisIndex:1}';

			$earningvalue.=",".$rvalue1['spend'].",".$rvalue1['profit'].",".$rvalue1['adminprofit'];
		}
		else if($rkey1 == "html")
		{
	 		$datavalue.=','.$rvalue1['impression'];

		 	$indexvalue = $indexvalue + 1;

			$earningvalue.=",".$rvalue1['spend'];
		}


		if($firstIteration == 0 && isset($fieldArray[$rkey1]))
		{
			foreach($fieldArray[$rkey1]['data'] as $fkey => $fvalue)
			{
				$datahead.=",'".$this->get_label('graph fields',array("x" => strtoupper($rkey1),"y" => $this->get_label($fvalue),"z" => ""))."'";
			}

			foreach($fieldArray[$rkey1]['earning'] as $fkey => $fvalue)
			{
				$earninghead.=",'".$this->get_label('graph fields',array("x" => strtoupper($rkey1),"y" => $this->get_label($fvalue),"z" => $currencyvariable))."'";
			}
		}
	}

	$datavalue.="]";
	$earningvalue.="]";

	$iindex++;
	$firstIteration++;
}

$datahead.="]";
$earninghead.="]";

?>
<script type="text/javascript">
var data;
var data1;
var options;
var options1;
var chart;
var chart1;
var chartArea;



google.load("visualization", "1", {packages:["corechart"]});
google.setOnLoadCallback(drawVisualization);


function drawVisualization() {

  data = google.visualization.arrayToDataTable([
    <?php echo $datahead;?>,
	<?php echo $datavalue; ?>
  ]);

  options = {

    vAxes: {viewWindow: {min: 0},0: {title:"<?php echo $this->get_label('count');?>",logScale: true, scaleType:"mirrorLog"},1: {maxValue: 100,title:"<?php echo $this->get_label('ctr');?>",logScale: true, scaleType:"mirrorLog"},gridlines: {count: 10}},

    hAxis: {slantedText:true,slantedTextAngle:60,title: "<?php echo $this->get_label('date');?>"<?php echo $hdata;?>,logScale:true},
    seriesType: "bars",
    series: {<?php echo $sparray;?>},
    animation:{
        duration: 1000,
        easing: 'in',
        startup: true
      },
    chartArea: {left:100,top:50,width:'85%'},
    legend:{position: 'top',textStyle: {fontSize: 10}}
  };

  chart = new google.visualization.ComboChart(document.getElementById('chart_div'));
  chart.draw(data, options);



  options1 = {
		    vAxis: {viewWindow: {min: 0},title: "<?php echo $this->get_label('earnings');?>",gridlines: {count: 10}},
		    hAxis: {slantedText:true,slantedTextAngle:60,title: "<?php echo $this->get_label('date');?>"<?php echo $hdata;?>},
		    seriesType: "line",
		    animation:{
		        duration: 1000,
		        easing: 'in',
		        startup: true
		      },
		    chartArea: {left:100,top:50,width:'100%'},
		    legend:{position: 'top',textStyle: {fontSize: 9}}
		  };

  data1 = google.visualization.arrayToDataTable([
                                                    <?php echo $earninghead;?>,
                                                	<?php echo $earningvalue; ?>
                                                  ]);



  chart1 = new google.visualization.ComboChart(document.getElementById('chart_div1'));
  chart1.draw(data1, options1);


  show_pub_stat(<?php echo $tab;?>);
}
</script>



<?php
$geo_enabled=Configuration::get_instance()->read('countrywise_data_tracking');
$site_enabled=$this->get_addon_status('category-targeting_enabled');

$style_string='';

if($geo_enabled ==1 || $site_enabled ==1)
$style_string=' style="width: 99.6%;" ';
?>


<div class="inner-box home-box">
<div class="report_div">

<div class="toppers-head"><i class="fa fa-bar-chart" title="<?php echo $this->get_label('graphical reports');?>"></i><?php echo $this->get_label('graphical reports');?></div>



<table style="width: 100%;" >

  <tr class="statistics_header">
    <td onclick="show_pub_stat(1);" id="adustat1" class="adustatclass" style="width: 125px;"><?php echo $this->get_label('data graph');?></td>
    <td onclick="show_pub_stat(2);" id="adustat2" class="adustatclass" style="width: 125px;"><?php echo $this->get_label('earnings graph');?></td>
    <td class="adustatclass">

<?php
if($from_date =='' && $duration ==7)
$duration=1;

$form1=$this->create_form();
$form1->start("overallstatistics","","post");
?>
<div style="float: right;margin-right: 5px;">
<select name="duration" id="duration">
<option value="1" <?php if($duration==1) { echo "selected"; } ?>><?php echo $this->get_label('today');?></option>
<option value="6" <?php if($duration==6) { echo "selected"; } ?>><?php echo $this->get_label('yesterday');?></option>
<option value="2" <?php if($duration==2) { echo "selected"; } ?>><?php echo $this->get_label('last 14');?></option>
<option value="3" <?php if($duration==3) { echo "selected"; } ?>><?php echo $this->get_label('last 30');?></option>
<option value="4" <?php if($duration==4) { echo "selected"; } ?>><?php echo $this->get_label('last 12 month');?></option>
<option value="5" <?php if($duration==5) { echo "selected"; } ?>><?php echo $this->get_label('all time');?></option>
<option value="7" <?php if($duration==7) { echo "selected"; } ?>><?php echo $this->get_label('custom date');?></option>
</select>
&nbsp;
<input type="text" readonly="readonly" name="from_date" id="from_date" value="<?php echo $from_date;?>" placeholder="From Date" size="5" />
&nbsp;
<input type="text" readonly="readonly" name="to_date" id="to_date" value="<?php echo $to_date;?>" placeholder="To Date" size="5" />
&nbsp;

<input type="hidden" name="tab" id="tab" value="1"/>
<input type="submit" name="search" value="<?php echo $this->get_label('go');?>" />
</div>
<?php $form1->end(); ?>
</td>
</tr>




<tr class="statistics_tr"><td class="statistics_td" colspan="3" style="height: 20px;border-bottom: 0px;"></td></tr>

<tr id="showstat1" class="showadustatclass statistics_tr">
<td colspan="3" class="statistics_td">
<div id="chart_div" style="width: 100%; height: 413px;"></div>
</td>
</tr>

<tr id="showstat2" class="showadustatclass statistics_tr">
<td colspan="3" class="statistics_td">
<div id="chart_div1" style="width: 100%; height: 413px;"></div>
</td>
</tr>
</table>
</div>
</div>
