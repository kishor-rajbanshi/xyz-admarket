<?php $this->dispatch("layout/header_iframe");?>
<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 p-3 mb-3 budget-settings">
	<h2 class="section-heading"><?php echo $this->get_label('mange pricing');?></h2>

<?php
$pricing_status = $this->get_variable('pricing_status');
$adtype    = $this->get_variable('adtype');
$adstatus  = $this->get_variable('adstatus');
$parent_ad = $this->get_variable('parent_ad');

$aid				     = $this->get_variable('aid');
$expiry				   = $this->get_result('expiry');
$ad_pricing			 = $this->get_variable('pricing');
$suggest_value	 = $this->get_variable('suggest_value');
$default_rate		 = $this->get_variable('default_rate');
$total_ad_budget = $this->get_variable('total_ad_budget');
$daily_budget		 = $this->get_variable('daily_budget');


$ds_places = Configuration::get_instance()->read('decimal_place');

if($ad_pricing == 0)
{
		$pricing_rate   = $this->get_label('cpc rate');
		$def_rate_label = $this->get_label('cpc rate label');
}
else if($ad_pricing == 1)
{
		$pricing_rate   = $this->get_label('cpm rate');
		$def_rate_label = $this->get_label('cpm rate label');
}
else if($ad_pricing == 6)
{
		$pricing_rate   = $this->get_label('cpa rate');
		$def_rate_label = $this->get_label('cpa rate label');
}
else if($ad_pricing == 18)
{
		$pricing_rate   = $this->get_label('cpp rate');
		$def_rate_label = $this->get_label('cpp rate label');
}

$currencyPosition = Configuration::get_instance()->read('currency_position');
$currencySymbol   = Configuration::get_instance()->read('currency_symbol');

//$currencyPosition = 1 => left
//$currencyPosition = 2 => right


//$currencyPosition = 2;


if($adtype != 7 || ($adtype == 7 && ($parent_ad > 0 || ($parent_ad == 0 && $adstatus == -2)))){?>

<div class="row m-0" style="display: none;" id="no-budget-section">
	<div class="alert alert-info" role="alert">
		<?php echo $this->get_label('budget for this ad is not active');?>
	</div>
</div>

<?php if($adtype == 7 && $parent_ad == 0 && $adstatus == -2){?>
	<div class="row m-0">
		<div class="alert alert-info" role="alert">
					<bdi>* <?php echo $this->get_label('budget settings for each individual banner size need to be updated separately');?></bdi>
		</div>
	</div>
<?php }?>


<div class="row" id="budget-section">

		<div class="col-md-12 col-sm-12 col-xs-12">

			<div class="row mb-2">
				<div class="col-md-6 col-sm-6 col-xs-12 mb-2">
		        <label class="form-label mb-1"><?php echo $pricing_rate;?><span class="compulsory">*</span></label>
		    		<div class="input-group <?php if($currencyPosition == 2){?>flex-row-reverse<?php }?>">

		            <div class="input-group-text dollar_style"><?php echo $currencySymbol;?></div>

                <input class="form-control" type="text" name="default_rate" id="default_rate" onKeyUp="this.value=this.value.replace(/[^0-9\.]/g, '');CheckDecimalPlaces('default_rate');" value="<?php if($default_rate > 0) { echo round($default_rate,$ds_places); }?>" />

		    		</div>
		    		<div class="notification">
							<bdi>[<?php echo $this->get_label('min rate is',array('x'=>$def_rate_label,'y'=>$this->get_money_format($this->get_variable('min_default_rate'))));?>]</bdi>
						</div>
						<?php if($ad_pricing != 3 && $ad_pricing != 12){ ?>
							<div class="notification">
								<bdi>[<?php echo $this->get_label('system suggested rate is',array('x'=>$def_rate_label,'y'=>$this->get_money_format($suggest_value)));?>]</bdi>
							</div>
						<?php } ?>
	  		</div>

				<div class="col-md-6 col-sm-6 col-xs-12 mb-2 budget-details-section">

					<div class="col-md-12 col-sm-12 col-xs-12">

						<div class="row">

							<?php if($this->get_variable('start_date') > 0){?>
							<div class="col-md-6 col-sm-6 col-xs-6">
							<?php } else {?>
							<div class="col-md-12 col-sm-12 col-xs-12">
							<?php } ?>

								<label class="form-label mb-1"><?php echo $this->get_label('pricing status');?></label>
								<div class="input-group">
									<input class="form-control" type="text" id="pricing-status" disabled="disabled" value="<?php
									if($pricing_status == 1) echo $this->get_label('active');
									else if($pricing_status == 2) echo $this->get_label('completed');
									else if($pricing_status == -1) echo $this->get_label('pending');
									else if($pricing_status == 0) echo $this->get_label('cancelled');
									?>" />
								</div>
							</div>

							<?php if($this->get_variable('start_date') > 0){?>
									<div class="col-md-6 col-sm-6 col-xs-6">
										<label class="form-label mb-1"><?php echo $this->get_label('start date');?></label>
										<input class="form-control" type="text" id="start-time" disabled="disabled" value="<?php echo $this->get_date_format(2,$this->get_variable('start_date'));?>" />
									</div>
							<?php }?>

						</div>
					</div>
				</div>
			</div>

			<div class="row mb-2">
				<div class="col-md-6 col-sm-6 col-xs-12 mb-2">
					<label class="form-label mb-1"><?php echo $this->get_label('ad total budget');?><span class="compulsory">*</span></label>
					<div class="input-group <?php if($currencyPosition == 2){?>flex-row-reverse<?php } ?>">

							 <div class="input-group-text dollar_style"><?php echo $currencySymbol;?></div>

							 <input class="form-control" type="text" name="ad_budget" id="ad_budget" onKeyUp="this.value=this.value.replace(/[^0-9\.]/g, '');CheckDecimalPlaces('ad_budget');" value="<?php if($total_ad_budget >0) { echo round($total_ad_budget,$ds_places); }?>" />

					 </div>
					 <div class="notification"><bdi>[<?php echo $this->get_label('min ad total budget is',array('x'=>$this->get_money_format($this->get_variable('min_ad_budget'))));?>]</bdi></div>
				</div>

				<div class="col-md-6 col-sm-6 col-xs-12 mb-2 budget-details-section">
					<label class="form-label mb-1"><?php echo $this->get_label('ad budget used');?></label>

					<div class="input-group <?php if($currencyPosition == 2){?>flex-row-reverse<?php } ?>">
						<div class="input-group-text dollar_style"><?php echo $currencySymbol;?></div>
						<input class="form-control" type="text" id="used-budget-span" disabled="disabled" value="<?php echo $this->get_number_format($this->get_variable('total_budget_used'));?>" />
					</div>
				</div>
			</div>

			<?php if($ad_pricing != 6){?>
				<div class="row mb-1">
					<div class="col-md-6 col-sm-6 col-xs-12 mb-2">
						<label class="form-label mb-1"><?php echo $this->get_label('ad daily budget');?></label>
						<div class="input-group <?php if($currencyPosition == 2){?>flex-row-reverse<?php } ?>">

								<div class="input-group-text dollar_style"><?php echo $currencySymbol;?></div>

								<input class="form-control" type="text" name="daily_budget" id="daily_budget" onKeyUp="this.value=this.value.replace(/[^0-9\.]/g, '');CheckDecimalPlaces('daily_budget');" value="<?php if($daily_budget >0) { echo round($daily_budget,$ds_places); }?>" />

						</div>
						<div class="notification"><bdi>[<?php echo $this->get_label('min ad daily budget is',array('x'=>$this->get_money_format($this->get_variable('min_daily_budget'))));?>]</bdi></div>
					</div>

					<div class="col-md-6 col-sm-6 col-xs-12 mb-2 budget-details-section">
						<label class="form-label mb-1"><?php echo $this->get_label('daily budget used');?></label>

						<div class="input-group <?php if($currencyPosition == 2){?>flex-row-reverse<?php } ?>">
							<div class="input-group-text dollar_style"><?php echo $currencySymbol;?></div>
							<input class="form-control" type="text" id="used-daily-budget-span" disabled="disabled" value="<?php echo $this->get_number_format($this->get_variable('daily_budget_used'));?>" />
						</div>
					</div>
				</div>
			<?php } ?>

			<div class="row">
					<div class="col-md-6 col-sm-6 col-xs-12 mt-1 mb-2">
	        	<div class="notification"><bdi>[<?php echo $this->get_label('supported decimal places',array('x'=>$ds_places));?>]</bdi></div>

						<input type="hidden" name="pricing_status" id="pricing_status" value="<?php echo $pricing_status;?>" />

						<input class="submit-button" type="button" name="update" id="update_btn" style="display: none;" value="<?php echo $this->get_label('update');?>" onClick="update_budget(1);" />
						<input class="submit-button" type="button" name="add" id="add_btn" style="display: none;" value="<?php echo $this->get_label('add budget');?>" onClick="update_budget(2);" />

						<span id="loader" style="display:none;">
							<img src="images/load.gif" />
						</span>
					</div>

					<?php if($adtype != 7 || ($adtype == 7 && $parent_ad > 0)){?>
							<div class="col-md-6 col-sm-6 col-xs-12 mt-sm-4 budget-details-section">
								<input class="submit-button" type="button" name="reset" value="<?php echo $this->get_label('budget cancel');?>" onClick="cancel_pricing();" />
								<img style="display:none;" id="loader2" src="images/load.gif" />
							</div>
					<?php }?>
			</div>
</div>
<?php }?>


<?php if($adtype == 7 && $parent_ad == 0 && $adstatus != -2){?>
	<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="alert alert-info" role="alert"><?php echo $this->get_label('budget will be applied for each individual banner size separately');?></div>
	</div>
<?php }?>


<?php if($adtype != 7 || ($adtype == 7 && ($parent_ad > 0 || ($parent_ad == 0 && $adstatus == -2)))){?>
	<input type="hidden" name="ac_balance" id="ac_balance" value="<?php echo $this->get_variable('ac_balance');?>" />
	<input type="hidden" name="aid" id="aid" value="<?php echo $aid;?>" />
	<input type="hidden" name="ad_pricing" id="ad_pricing" value="<?php echo $ad_pricing;?>" />
	<input type="hidden" name="min_daily_budget" id="min_daily_budget" value="<?php echo $this->get_variable('min_daily_budget');?>" />
	<input type="hidden" name="min_default_rate" id="min_default_rate" value="<?php echo $this->get_variable('min_default_rate');?>" />
	<input type="hidden" name="min_ad_budget" id="min_ad_budget" value="<?php echo $this->get_variable('min_ad_budget');?>" />
	<input type="hidden" name="prev_budget" id="prev_budget" value="<?php echo $this->get_variable('total_ad_budget');?>" />
	<input type="hidden" name="type" id="type" value="<?php echo $adtype;?>"/>
<?php } ?>

<div class="col-md-12 col-sm-12 col-xs-12" id="ad-run-history"></div>

</div>
<script type="text/javascript">
$(document).ready(function()
{
		<?php if($adtype != 7 || ($adtype == 7 && ($parent_ad > 0 || ($parent_ad == 0 && $adstatus == -2)))){?>

		var pricing_status = $("#pricing_status").val();

		if(pricing_status == 1)
		$(".budget-details-section").show();

		if(pricing_status == -1 || pricing_status == 0 || pricing_status == 2)
		{
				$("#update_btn").hide();
				$("#add_btn").show();
		}
		else if(pricing_status == 1)
		{
				$("#add_btn").hide();
				$("#update_btn").show();
		}

		if(pricing_status == 0 || pricing_status == 2)
		$("#no-budget-section").show();
		else
		$("#no-budget-section").hide();

		<?php }?>


		LoadAdRunHistory();

		if($("#table-desktop").length > 0)
		CreateResponsiveTable('table-desktop');

		$(window).resize(function()
		{
				if($("#table-desktop").length > 0)
		 		CreateResponsiveTable('table-desktop');
		});
});


function CheckDecimalPlaces(elementId)
{
		decimal_places = <?php echo intval(Configuration::get_instance()->read('decimal_place'));?>;

		dataValue      = $('#'+elementId).val();

		dataValueArray = dataValue.split('.');

		if(dataValueArray.length > 1 && dataValueArray[1].length > decimal_places)
		{
				dataValueArray[1] = dataValueArray[1].substring(0,decimal_places);

				$('#'+elementId).val(dataValueArray.join('.'));
		}
}

function update_budget(operation)
{
	if(operation ==1)
	operation_message = "<?php echo $this->get_message('budget update message');?>";
	else
	operation_message = "<?php echo $this->get_message('budget add message');?>";

	var error_flag	 		= 0;

	var aid          			= $("#aid").val();
	var pricing      			= $("#ad_pricing").val();
	var ad_budget 	 			= $("#ad_budget").val();
	var default_rate 			= $("#default_rate").val();
	var min_ad_budget 		= $("#min_ad_budget").val();
	var min_default_rate 	= $("#min_default_rate").val();
	var pricing_status 		= $("#pricing_status").val();
	var prev_budget 			= $("#prev_budget").val();
	var ac_balance 				= $("#ac_balance").val();

	ad_budget							= parseFloat(ad_budget.trim());
	default_rate					= parseFloat(default_rate.trim());
	min_ad_budget					= parseFloat(min_ad_budget.trim());
	min_default_rate			= parseFloat(min_default_rate.trim());
	prev_budget						= parseFloat(prev_budget.trim());

	if(isNaN(ad_budget))
	ad_budget               = 0;

	if(isNaN(default_rate))
	default_rate            = 0;

	if(isNaN(min_ad_budget))
	min_ad_budget           = 0;

	if(isNaN(min_default_rate))
	min_default_rate        = 0;

	if(pricing != 6)
	{
			var daily_budget 	 = $("#daily_budget").val();
			var min_daily_budget = $("#min_daily_budget").val();

			daily_budget		 = parseFloat(daily_budget.trim());
			min_daily_budget     = parseFloat(min_daily_budget.trim());

			if(isNaN(daily_budget))
			daily_budget         = 0;

			if(isNaN(min_daily_budget))
			min_daily_budget     = 0;
	}
	else
	{
			var daily_budget 		 = 0;
			var min_daily_budget = 0;
	}

	if(default_rate == 0 || ad_budget == 0)
	{
			set_jnotice(0,"<?php echo $this->get_message('mandatory'); ?>");
			error_flag = 1;
	}
	else if(( ad_budget - prev_budget) > ac_balance)
	{
			set_jnotice(0,"<?php echo $this->get_message('sufficient balance not exists in your advertiser account'); ?>");
			error_flag = 1;
	}
	else if(default_rate < min_default_rate)
	{
			set_jnotice(0,"<?php echo $this->get_message('rate should be greater than minimum value',array('x'=>$def_rate_label)); ?>");
			error_flag = 1;
	}
	else if(default_rate > ad_budget)
	{
			set_jnotice(0,"<?php echo $this->get_message('ad budget should be greater than rate',array('x'=>$def_rate_label)); ?>");
			error_flag = 1;
	}
	else if(ad_budget < min_ad_budget)
	{
			set_jnotice(0,"<?php echo $this->get_message('ad budget should be greater than minimum value'); ?>");
			error_flag = 1;
	}
	else if(ad_budget < prev_budget && pricing_status ==1)
	{
			set_jnotice(0,"<?php echo $this->get_message('you cannot decrement the ad budget'); ?>");
			error_flag = 1;
	}
	else if(pricing != 6 && daily_budget > ad_budget)
	{
			set_jnotice(0,"<?php echo $this->get_message('daily budget should be less than ad budget'); ?>");
			error_flag = 1;
	}
	else if(pricing != 6 && daily_budget >0 && daily_budget < min_daily_budget)
	{
			set_jnotice(0,"<?php echo $this->get_message('daily budget should be greater than minimum daily budget'); ?>");
			error_flag = 1;
	}
	else if(pricing !=6 && daily_budget >0 && daily_budget < default_rate)
	{
			set_jnotice(0,"<?php echo $this->get_message('daily budget should be greater than default rate',array('x'=>$def_rate_label));?>");
			error_flag = 1;
	}

	var parameter='aid='+aid+'&ad_budget='+ad_budget+'&default_rate='+default_rate+'&daily_budget='+daily_budget;

  if(error_flag == 0 && confirm(operation_message))
  {
			$("#loader").show();

		$.ajax({
        type : "POST",
        url  : "<?php echo $this->make_url("ad/update_pricing");?>",
				data : parameter,
				success: function(data)
				{
						$("#loader").hide();

						if(data=="error-1")
						set_jnotice(0,"<?php echo $this->get_message('mandatory'); ?>");
	        	else if(data=="error-2")
	        	set_jnotice(0,"<?php echo $this->get_message('account balance low'); ?>");
	    	    else if(data=="error-3")
	    	   	set_jnotice(0,"<?php echo $this->get_message('rate should be greater than minimum value',array('x'=>$def_rate_label)); ?>");
            else if(data=="error-4")
           	set_jnotice(0,"<?php echo $this->get_message('ad budget should be greater than rate',array('x'=>$def_rate_label)); ?>");
            else if(data=="error-5")
           	set_jnotice(0,"<?php echo $this->get_message('ad budget should be greater than minimum value'); ?>");
            else if(data=="error-6")
           	set_jnotice(0,"<?php echo $this->get_message('you cannot decrement the ad budget'); ?>");
            else if(data=="error-7")
           	set_jnotice(0,"<?php echo $this->get_message('daily budget should be less than ad budget'); ?>");
            else if(data=="error-8")
            set_jnotice(0,"<?php echo $this->get_message('daily budget should be greater than minimum daily budget'); ?>");
            else if(data=="error-9")
						set_jnotice(0,"<?php echo $this->get_message('daily budget should be greater than default rate',array('x'=>$def_rate_label));?>");
            else if(data=="error-10")
            set_jnotice(0,"<?php echo $this->get_message('error occurred'); ?>");
            else if(data=="error-11")
            set_jnotice(0,"<?php echo $this->get_message('invalid operation'); ?>");
            else if(data=="error-12")
            set_jnotice(0,"<?php echo $this->get_message('demo mode'); ?>");
        		else
            {
            	var jsonData = JSON.parse(data);

							if(jsonData['success'] == 1)
            	{
            			$("#no-budget-section").hide();
            			$(".budget-details-section").show();

            			$("#advertiser_account_balance", window.parent.document).html(jsonData['adv_balance_string']);

                	$("#prev_budget").val(jsonData['ad_budget']);
                	$("#pricing_status").val(jsonData['pricing_status']);

                	if(jsonData['pricing_status'] == 1)
                	{
                			$("#add_btn").hide();
                    	$("#update_btn").show();

                    	$("#pricing-status").val('<?php echo $this->get_label("active");?>');

                    	if(jsonData['start_time'] != 0)
                			$("#start-time").val(jsonData['start_time']);
                	}

										<?php if($adstatus == -2){?>
              			set_jnotice(1,"<?php echo $this->get_message('you have successfully created new ad'); ?>");

										$("#draft-id", window.parent.document).hide();

										if(jsonData['ad_status'] == 1)
										$("#active-id", window.parent.document).show();
										else if(jsonData['ad_status'] == -1)
										$("#pending-id", window.parent.document).show();
                    <?php }else{?>
              			set_jnotice(1,"<?php echo $this->get_message('budget settings updated successfully'); ?>");
										<?php }?>
                }
            }
         },
         error: function(e)
         {
	         	$("#loader").hide();
	      	 	alert("<?php echo $this->get_message("unable to process request")?>");
     	 	 }
	  	});
	 }
}


function LoadAdRunHistory()
{
	var aid 			= $("#aid").val();

	$.ajax({
	        type : "POST",
	        url  : "<?php echo $this->make_url("ad/run_history");?>",
			    data : "aid="+aid,
					success : function(data)
					{
							$("#ad-run-history").html(data);
	        },
	        error : function(e)
	        {
	        		$("#loader").hide();
	      	 		alert("<?php echo $this->get_message("unable to process request")?>");
	        }
     });
}


function cancel_pricing()
{
	var aid 			     = $("#aid").val();
	var pricing_status = $("#pricing_status").val();

	if(pricing_status != 1)
	return;

	if(confirm('<?php echo $this->get_message('budget settings cancel');?>'))
	{
			$("#loader2").show();
			$.ajax({
	        type : "POST",
	        url  : "<?php echo $this->make_url("ad/pricing_cancel");?>",
					data : "aid="+aid,
					success : function(data)
					{
							$("#loader2").hide();

							if(isJson(data))
							{
								var jsonData = JSON.parse(data);

			            	if(jsonData['success'] == 0)
			           		set_jnotice(0,"<?php echo $this->get_message('error occurred'); ?>");
			            	else if(jsonData['success'] == -1)
			           		set_jnotice(0,"<?php echo $this->get_message('invalid operation'); ?>");
			            	else if(jsonData['success'] == 2)
			           		set_jnotice(0,"<?php echo $this->get_message('demo mode'); ?>");
			            	else if(jsonData['success'] == 1)
			            	{
												$("#no-budget-section").show();
												$(".budget-details-section").hide();

												$("#update_btn").hide();
												$("#add_btn").show();

												$("#pricing_status").val(0);
												$("#ad_budget").val("");
												$("#default_rate").val("");

												if($("#daily_budget").length >0)
												$("#daily_budget").val("");

												$("#prev_budget").val(0);

												$("#used-budget-span").val("<?php echo $this->get_number_format(0);?>");

												if($("#used-daily-budget-span").length >0)
												$("#used-daily-budget-span").val("<?php echo $this->get_number_format(0);?>");

												$("#advertiser_account_balance", window.parent.document).html(jsonData['adv_balance_string']);

												set_jnotice(1,"<?php echo $this->get_message('pricing has been cancelled successfully'); ?>");

												LoadAdRunHistory();
				         }
							}
	         },
	         error: function(e)
	         {
	        		$("#loader2").hide();
	      	 		alert("<?php echo $this->get_message("unable to process request")?>");
	         }
	     });
	 }
}

function isJson(str)
{
    try
    {
        JSON.parse(str);
    }
    catch (e)
    {
        return false;
    }
    return true;
}
</script>
<?php $this->dispatch("layout/footer_iframe");?>
