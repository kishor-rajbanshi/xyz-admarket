<?php $this->dispatch("layout/header_iframe/6");?>
<?php
$uid      			= $this->get_variable('uid');
$reportResult      	= $this->get_array("reportResult");
$tabArray 			= array();

$cpc_enabled       = $this->get_addon_status('cpc_enabled');
$cpm_enabled       = $this->get_addon_status('cpm_enabled');
$html_enabled      = $this->get_addon_status('html_enabled');
$cpa_enabled       = $this->get_addon_status('cpa_enabled');
$cpp_enabled       = $this->get_addon_status('cpp_enabled');
$cpd_enabled       = $this->get_addon_status('sponsored_enabled');

$cpm_addon_enabled = 0;
if($cpm_enabled == 1 || $html_enabled == 1)
$cpm_addon_enabled = 1;

foreach($reportResult as $hkey => $hvalue)
{
	if($hkey == 'heading')
	continue;

	if($cpc_enabled == 1 && $hkey == 'cpc')
	$tabArray['cpc'] = 0;

	if($cpm_addon_enabled == 1 && $hkey == 'cpm')
	$tabArray['cpm'] = 1;

	if($cpa_enabled == 1 && $hkey == 'cpa')
	$tabArray['cpa'] = 6;

	if($cpd_enabled == 1 && $hkey == 'cpd')
	$tabArray['cpd'] = 3;

	if($cpp_enabled == 1 && $hkey == 'cpp')
	$tabArray['cpp'] = 18;
}

if(count($tabArray) > 0)
{
	$tabCount = count($tabArray);
	$tabWidth = round((100 / $tabCount), 2); 	
}

$index = -1;

$direction = $this->get_locale_direction();


if($tabCount == 1)
$columnClass = " col-lg-2 col-sm-2 col-md-2 col-12 ";
else if($tabCount == 2)
$columnClass = " col-lg-3 col-sm-3 col-md-3 col-12 ";
else if($tabCount == 3)
$columnClass = " col-lg-4 col-sm-4 col-md-4 col-12 ";
else 
$columnClass = " col-lg-6 col-sm-6 col-md-6 col-12 ";
?>
<?php if($tabCount > 0){ ?>
<div class="container-fluid top-adcodes">
	<div class="row">

		<div class="col-md-12 col-sm-12 col-xs-12 my-1">
			<h2 class="section-heading mt-0"><?php echo $this->get_label('top performing ad units');?></h2>
		</div>

		<div class="<?php echo $columnClass; ?>">
			<div class="tab-nav">
				<?php foreach($tabArray as $tkey => $tvalue){
					$index++;
					$faIcon = 'fa fa-hand-o-up';
					switch ($tkey) {
							 case 'cpc': $faIcon = "fa fa-hand-o-up"; break;
							 case 'cpm': $faIcon = "fa fa-usd"; break;
							 case 'cpa': $faIcon = "fa fa-desktop"; break;
							 case 'cpd': $faIcon = "fa fa-calendar"; break;
							 case 'cpp': $faIcon = "fa fa-desktop"; break;
					 }
					?>
					<button class="tab-btn tab-btn-<?php echo $tvalue; ?>" id="tab-btn-<?php echo $tvalue; ?>" data-index="<?php echo $index; ?>" onClick="show_tab(<?php echo $tvalue; ?>);"><span><i class="<?php echo $faIcon; ?> tab-icon" aria-hidden="true"></i><?php echo $this->get_label($tkey);?></span></button>
				<?php } ?>		
				<div class="tab-indicator" id="tabIndicator" style="width: <?php echo $tabWidth; ?>%;"></div>
			</div>
		</div>

		<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 mb-2">	
			<?php if($tabCount > 0){ ?>
			<?php foreach($tabArray as $tkey => $tvalue){?>
				<div class="report-div report-div-<?php echo $tvalue; ?> mb-2 d-none">
					<table id="table-desktop<?php echo $tvalue; ?>" class="data_table" cellpadding="0" cellspacing="0">
						<tr class="data_table_head">
							<?php
							foreach($reportResult['heading'] as $rkey1 => $rvalue1)
							{
								if($tkey == 'cpc')
								{
									if($rkey1 == "conversion" || $rkey1 == "conversionratio")
									continue;
								}
								else if($tkey == 'cpm' || $tkey == 'cpd' || $tkey == 'cpp')
								{
									if($rkey1 == "conversion" || $rkey1 == "conversionratio")
									continue;
								}				
								?>
								<td><bdi><?php echo $rvalue1;?></bdi></td>
								<?php
							}
							?>
						</tr>
						<?php
						foreach($reportResult[$tkey] as $rkey => $rvalue)
						{
							$adcodeID    = 0;
							$adcodeName  = "";

							$dataArray = explode("::",$rkey);

							if(isset($dataArray[0]))
							$adcodeID    = $dataArray[0];

							if(isset($dataArray[1]))
							$adcodeName  = $dataArray[1];
							?>
							<tr class="data_table_content">
								<td >
									<?php if($adcodeName != ""){?>
									<a target="_parent" href="<?php echo $this->make_base_url("adunit/detail_statistics/".$adcodeID);?>"><i class="fa fa-bar-chart"></i> <?php echo $adcodeName;?></a>
									<?php }else{?>
									<?php echo $this->get_label('deleted');?>
									<?php }	?>
								</td>

								<?php
								foreach ($rvalue as $rkey1 => $rvalue1)
								{
									if($tkey == 'cpc')
									{
										if($rkey1 == "conversion" || $rkey1 == "conversionratio")
										continue;
									}
									else if($tkey == 'cpm' || $tkey == 'cpd' || $tkey == 'cpp')
									{
										if($rkey1 == "conversion" || $rkey1 == "conversionratio")
										continue;
									}					
									?>
									<td><bdi><?php echo $rvalue1;?></bdi></td>
								<?php } ?>
							</tr>
						<?php } ?>
					</table>
				</div>
			<?php } ?>
			<?php } else { ?>
				<table class="data_table" cellpadding="0" cellspacing="0">
					<tr class="data_table_head">
						<td><bdi><?php echo $this->get_label('name');?></bdi></td>
					</tr>
					<tr class="data_table_content">
						<td colspan="2"><?php echo $this->get_label('no records found');?></td>
					</tr>
				</table>
			<?php } ?>
		</div>
	</div>
</div>
<script type="text/javascript">
function show_tab(id)
{
	$('.report-div').addClass("d-none");
	$('.tab-btn').removeClass('active');

	$('.report-div-'+id).removeClass("d-none");
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

	var contentHeight = $(".body-section").outerHeight();
		contentHeight = parseFloat(contentHeight) + 10;

	$('#iframe-section-6', window.parent.document).css("height", contentHeight+"px");
}

$(document).ready(function()
{
	<?php foreach($tabArray as $tkey => $tvalue){?>
	show_tab(<?php echo $tvalue; ?>);
	<?php
	break;
	} ?>

	<?php foreach($tabArray as $tkey => $tvalue){?>
	CreateResponsiveTable('table-desktop<?php echo $tvalue; ?>');
	<?php } ?>


	<?php if($tabCount > 0){?>
		$(window).resize(function()
		{
			selectid    = $('.tab-btn.active').attr('id');			
			selectarray = selectid.split('-');

			if(selectarray.length > 2)
			show_tab(selectarray[2]);

			<?php foreach($tabArray as $tkey => $tvalue){?>
			CreateResponsiveTable('table-desktop<?php echo $tvalue; ?>');
			<?php } ?>
		});
	<?php } ?>
});
</script>
<?php } ?>
<?php $this->dispatch("layout/footer_iframe");?>
