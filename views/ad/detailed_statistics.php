<?php
$this->dispatch("layout/header/2/3/a");

$uid=$this->get_variable('uid');
$aid=$this->get_variable('aid');

$duration=$this->get_variable('duration');
$tab=$this->get_variable('tab');

$rowdata=$this->get_result("rowdata");
$val=$rowdata[0];

$adtype			= $val['type'];
$pricing_value	= $val['display_type'];

$adCloneEnabled = Configuration::get_instance()->read('enable_advertisers_ad_clone_option');
$retargeting_enabled=$this->get_addon_status('retargeting_enabled');

$retargeting=0;

if($retargeting_enabled ==1)
$retargeting=$val['retargeting'];


$expandable_banner="";
$expandable=0;
$expandable_enabled=$this->get_addon_status('expandable-banners_enabled');

if($adtype ==2 && $expandable_enabled ==1)
{
	$expandable_banner=$val['expandable_banner'];
	$expandable=$val['expandable'];

	if($expandable_banner =="")
	$expandable=0;
}

if($adtype ==2 || $adtype ==7 || $adtype ==11)
{
	$diamensions_string=$this->get_banner_dimension($val['banner_id']);

	$diamensions_array=explode('-',$diamensions_string);

	$diamensions=$diamensions_array[0]." x ".$diamensions_array[1];
}
?>
<script type="text/javascript">
$(document).ready(function() {
	 $("#from_date").datepicker({dateFormat : 'dd/mm/yy'});
	 $("#to_date").datepicker({dateFormat : 'dd/mm/yy'});
});
</script>
<?php
$from_date=$this->get_variable('from_date');
$to_date=$this->get_variable('to_date');

if($from_date !='' && $to_date =='')
$to_date=date("d",time()).'/'.date("m",time()).'/'.date("Y",time());

if($from_date == '' && $duration == 7)
$duration = 1;


$adName = $val['name'].' - '.$this->get_ad_pricing($aid,$pricing_value);

if($adtype ==2 || $adtype ==7 || $adtype ==11)
$adName.= ' - '.$diamensions;

if($adtype == 2 && $expandable == 1)
$adName.= ' '.$this->get_label('expandable');

if($adtype == 13)
{
		$aspect_ratio = $this->get_aspect_ratio_value($val['aspect_ratio']);

		if($aspect_ratio > 0)
		$adName.= ' - '.$aspect_ratio;
}
$tabCount = 2;

if(($pricing_value == 3 || Configuration::get_instance()->read('keyword_based_ad_display') ==1) && $adtype != 12)
$tabCount++;

$tabWidth = round((100 / $tabCount), 2);

$direction = $this->get_locale_direction();
?>


	<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 p-3 mb-3 manage-ad-details table-outer-box">
<h2 class="page-heading content-grouping">
<div class="page-inner">
	<i class="fa fa-paper-plane icon_red"></i>
	<bdi><?php echo $adName;?></bdi>
	</div>
	<div class="heading-word-spacing">
	<?php if($retargeting == 1){?>
	<i class="fa fa-recycle retargeting-icon" title="<?php echo $this->get_label('retargeting');?>"></i>
	<?php }?>


	<?php	if($val['html5'] == 1){?>
	<i class="fa fa-html5 html5-icon" title="<?php echo $this->get_label('html5');?>"></i>
	<?php }	?>

	<span class="adstate">
		<bdi>
			<?php if($val['status'] == -1){?>
				<span class="pending"><i class="fa fa-spinner"></i> <?php echo $this->get_ad_status($val['status']);?></span>
			<?php }?>

			<?php if($val['status'] == 1){?>
				<span class="active"><i class="fa fa-check-square-o"></i> <?php echo $this->get_ad_status($val['status']);?></span>
			<?php }?>

			<?php if($val['status'] == 0){?>
				<span class="block"><i class="fa fa-ban"></i> <?php echo $this->get_ad_status($val['status']);?></span>
			<?php }?>

			<?php if($val['status'] == -2){?>
				<span class="draft"><i class="fa fa-spinner"></i> <?php echo $this->get_ad_status($val['status']);?></span>
			<?php }?>

			<?php if($val['status'] == 1 && $val['pause_status'] == 1){?>
			<?php echo " - ".$this->get_label('paused');?>
			<?php }?>
		</bdi>
	</span>

	<div class="float-end">
		<a href="<?php echo $this->make_url("ad/view/".$aid);?>">
			<i class="fa fa-edit edit-icon" title="<?php echo $this->get_label('edit ad');?>"></i>
		</a>

		<?php if($adCloneEnabled == 1 && $val['status'] == 1 && $val['type'] != 7){?>
			<a href="<?php echo $this->make_url("ad/create/-1/".$aid);?>">
				<i class="fa fa-copy clone-icon" title="<?php echo $this->get_label('clone this ad');?>"></i>
			</a>
		<?php } ?>

		<?php if($val['status'] == 1)
		{
				if($val['pause_status'] == 0){?>
					<a href="<?php echo $this->make_url("ad/update_pause_status/".$aid."/1/1");?>">
						<i class="fa fa-pause pause-icon" title="<?php echo $this->get_label('pause this ad');?>"></i>
					</a>
				<?php }else if($val['pause_status'] == 1){?>
					<a href="<?php echo $this->make_url("ad/update_pause_status/".$aid."/2/1");?>">
						<i class="fa fa-play-circle-o resume-icon" title="<?php echo $this->get_label('resume this ad');?>"></i>
					</a>
				<?php }
		}
		?>

		<a href="<?php echo $this->make_url("ad/delete/".$aid);?>" onclick="return confirm('<?php echo $this->get_message('do you really want to delete this ad');?>')">
			<i class="fa fa-trash delete-icon" title="<?php echo $this->get_label('delete');?>"></i>
		</a>
	</div>
</div>
</h2>

<?php if($val['status'] != -2 && $pricing_value != 3){?>

		<?php
				$amount_spend       = $this->get_variable('amount_spend');
				$amount_spend_today = $this->get_variable('amount_spend_today');
				$daily_budget       = $this->get_variable('daily_budget');
				$total_ad_budget    = $this->get_variable('total_ad_budget');

				if($pricing_value == 0)
				$pricing_rate = $this->get_label('cpc rate');
				else if($pricing_value == 1)
				$pricing_rate = $this->get_label('cpm rate');
				else if($pricing_value == 6)
				$pricing_rate = $this->get_label('cpa rate');
				else if($pricing_value == 18)
				$pricing_rate = $this->get_label('cpp rate');
		?>
		<div class="row">

			<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 mb-md-0 mb-sm-2 mb-xs-2">
				<bdi><?php echo $pricing_rate;?> : <?php echo $this->get_money_format($val['default_rate']);?></bdi>
			</div>

			<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 mb-md-0 mb-sm-2 mb-xs-2">
				<bdi><?php echo $this->get_label('budget');?> : <?php echo $this->get_money_format($amount_spend).' / '.$this->get_money_format($total_ad_budget);?></bdi>
			</div>

			<?php if($pricing_value != 6){?>
				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 mb-md-0 mb-sm-2 mb-xs-2">
					<bdi><?php echo $this->get_label('daily budget');?> : <?php echo $this->get_money_format($amount_spend_today).' / '.$this->get_money_format($daily_budget);?></bdi>
				</div>
			<?php } ?>

		</div>
	</div>
<?php }?>

<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 p-3 mb-3 manage-ad-details table-outer-box">

	<div class="row m-0">
		<?php $this->dispatch("ad/preview/".$aid."/1");?>
	</div>

	<?php
	$form2=$this->create_form();
	$form2->start("manageads",$this->make_url("ad/detailed_statistics/".$aid),"post");
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
	<button class="tab-btn tab-btn-<?php echo $index; ?>" data-label="<?php echo $index; ?>" onClick="manageTabClicks('user', 'ad_details', <?php echo $index;?>, <?php echo $tabCount;?>, <?php echo $direction;?>);"><span><?php echo $this->get_label('overall');?></span></button>

	
	<?php $index++; ?>
	<button class="tab-btn tab-btn-<?php echo $index; ?>" data-label="<?php echo $index; ?>" onClick="manageTabClicks('user', 'ad_details', <?php echo $index;?>, <?php echo $tabCount;?>, <?php echo $direction;?>);"><span><?php echo $this->get_label('time based');?></span></button>

<?php if(($pricing_value == 3 || Configuration::get_instance()->read('keyword_based_ad_display') ==1) && $adtype != 12){?>


	<?php $index++; ?>
	<button class="tab-btn tab-btn-<?php echo $index; ?>" data-label="<?php echo $index; ?>" onClick="manageTabClicks('user', 'ad_details', <?php echo $index;?>, <?php echo $tabCount;?>, <?php echo $direction;?>);"><span>
				<?php
			  if($pricing_value == 3)
			  echo $this->get_label('cpd reports');
			  else
			  echo $this->get_label('keyword based');
			  ?>
		</span></button>
	<?php } ?>

	<div class="tab-indicator" id="tabIndicator" style="width: <?php echo $tabWidth; ?>%;"></div>
	</div>


	<div class="row">
		<?php $index = 0; ?>
		<div class="report-div-tab report-div-tab-<?php echo $index;?>">
			<table id="table-desktop-<?php echo $index;?>" class="data_table" cellpadding="0" cellspacing="0">
			<?php
			$reportResult       = $this->get_array("reportResult");
			$reportResultCount  = count($reportResult);

			foreach($reportResult as $rkey => $rvalue)
			{
			    if($reportResultCount <= 3 && $rkey == 'total')
			    continue;

					if($rkey == 'heading'){?>
						<tr class="data_table_head">
						<td ></td>
					<?php }	else { ?>
						<tr class="data_table_content">
						<td ><?php echo $this->get_label($rkey);?></td>
					<?php
					}

					foreach ($rvalue as $rkey1 => $rvalue1){?>
						<td><bdi><?php echo $rvalue1;?></bdi></td>
					<?php	}	?>
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

							<?php foreach ($rvalue as $rkey1 => $rvalue1){?>
								<td><bdi><?php echo $rvalue1;?></bdi></td>
							<?php	}	?>

						</tr>
				<?php } else {

						$enterflag = 0;
						$rowSpan   = count($rvalue);

						foreach($rvalue as $rkey1 => $rvalue1){?>

							<tr class="data_table_content">
								<td <?php if($enterflag == 0){?> rowspan="<?php echo $rowSpan;?>" <?php } else {?> style="display: none;"<?php } ?>><bdi><?php echo $rkey;?></bdi></td>

								<td><bdi><?php echo $this->get_label($rkey1);?></bdi></td>

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
		if(($pricing_value == 3 || Configuration::get_instance()->read('keyword_based_ad_display') ==1) && $adtype != 12)
		{
		    $keywords = $this->get_array('adResult');
			$index++;
		    ?>
				<div class="report-div-tab report-div-tab-<?php echo $index;?>">
			    <table id="table-desktop-<?php echo $index; ?>" class="data_table" cellpadding="0" cellspacing="0">
				    <tr class="data_table_head">
					    <?php
					    if(isset($keywords['heading']))
					    {
					    	  foreach($keywords['heading'] as $hkey => $hvalue){?>
					    		<td><bdi><?php echo $this->get_label($hvalue);?></bdi></td>
					    		<?php
					    	  }
					    }
					    ?>
				    </tr>

			      <?php
			      if(count($keywords) > 1)
			      {
					    	foreach($keywords as $key => $value)
						    {
						        if($key == "heading")
						    		continue;
						        ?>
						        <tr class="data_table_content">
						        	<?php foreach($value as $key1 => $value1){?>
						      	  		<td><bdi><?php echo $value1;?></bdi></td>
						          <?php } ?>
						        </tr>
						        <?php
						    }
			    	} else { ?>
			    	<tr class="data_table_content">
							<td colspan="8"><?php echo $this->get_label("no records found");?></td>
						</tr>
			    <?php	} ?>
			    </table>
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

$(document).ready(function()
{
	$('#duration').change(function()
	{
		ShowHideDate();
	});

	ShowHideDate();
	manageTabClicks('user', 'ad_details', <?php echo $tab;?>, <?php echo $tabCount;?>, <?php echo $direction;?>);
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

