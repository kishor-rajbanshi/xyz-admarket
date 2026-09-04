<?php
$currencyPosition = Configuration::get_instance()->read('currency_position');
$currencySymbol   = Configuration::get_instance()->read('currency_symbol');

$system_currency         = Configuration::get_instance()->read('system_currency');
$currencyListStripe      = $this->get_array("currencyListStripe");
$stripeCheckoutType      = $this->get_variable("stripeCheckoutType");

if(count($currencyListStripe) > 0)
$currencyArrayJSONStripe = json_encode($currencyListStripe);
else
$currencyArrayJSONStripe = new stdClass();

if($stripeCheckoutType == 0){?>
	<script type="text/javascript" src="https://js.stripe.com/v3/"></script>

	<style type="text/css">
	#card_number
	{
		
		height: 35px;
		padding: 5px 0px 0px 2px;
	}

	#card_expiry
	{
	
		height: 35px;
		padding: 5px 0px 0px 2px;
	}

	#card_cvc
	{
	
		height: 35px;
		padding: 5px 0px 0px 2px;
	}

	#paymentResponse
	{
		color: red;
	}
	</style>
<?php } ?>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 p-3">
	<div class="col-lg-4 col-md-5 col-sm-6 col-xs-12">

		<form action="" method="post" name="Advertiser_Payments_Stripe" id="Advertiser_Payments_Stripe">

			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="mb-3">
						<label class="form-label"><?php echo $this->get_label('payment currency');?></label>

						<select class="form-select" name="stripe_currency_list" id="stripe_currency_list">
          		<?php
          		foreach($currencyListStripe as $cKey => $cValue){?>
          		<option value="<?php echo $cKey; ?>" <?php if($cKey == $system_currency){?> selected <?php }?> ><?php echo $cKey;?></option>
          		<?php } ?>
          	</select>
  			</div>
			</div>

			<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="mb-3">
							<label class="form-label"><?php echo $this->get_label('amount');?> <span class="compulsory">*</span></label>

		        	<div class="input-group <?php if($currencyPosition == 2){?>flex-row-reverse<?php }?>">
								<div class="input-group-text dollar_style stripe-currency-span"><?php echo $currencySymbol;?></div>


		                <input class="form-control"  onkeyup="this.value = this.value.replace(/[^0-9\.]/g,'');" type="text" name="stripeamount" id="stripeamount" value="" />

		          </div>
							<span id="loading8" style="display: none;"><img src="<?php echo BASE.'images/load.gif';?>" /></span>
	        </div>
      </div>

    <div class="col-md-12 col-sm-12 col-xs-12 stripeSystemCurrencyDiv">
			<div class="mb-3">
					<label class="form-label"><?php echo $this->get_label('amount in',array("x" => $system_currency));?></label>

          <div class="input-group <?php if($currencyPosition == 2){?>flex-row-reverse<?php }?>">
						<div class="input-group-text dollar_style"><?php echo $currencySymbol;?></div>

              <input class="form-control" type="text" name="stripeSystemCurrencyAmount" id="stripeSystemCurrencyAmount" value="" disabled />

          </div>
      </div>
    </div>

		<div class="col-md-12 col-sm-12 col-xs-12 row mb-1" id="tax-fee-8" ></div>

		<?php if($stripeCheckoutType == 0){?>
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="mb-3">
						<label class="form-label"><?php echo $this->get_label('card number');?> <span class="compulsory">*</span></label>

						<div id="card_number"></div>
				</div>
			</div>

			<div class="row">
			<div class="col-md-6 col-sm-12 col-xs-12">
				<div class="mb-3">
						<label class="form-label"><?php echo $this->get_label('card expiry');?> <span class="compulsory">*</span></label>

						<div id="card_expiry"></div>
				</div>
			</div>

			<div class="col-md-6 col-sm-12 col-xs-12">
				<div class="mb-3">
						<label class="form-label"><?php echo $this->get_label('cvc');?> <span class="compulsory">*</span></label>

						<div id="card_cvc"></div>
				</div>
				</div>
			</div>
		<?php } ?>

		<div class="row">
			<div class="form-group">
				<input type="hidden" name="stripeToken" id="stripeToken" value="" />
				<input type="hidden" name="stripeAmountSubmit" id="stripeAmountSubmit" value="" />
				<input type="hidden" name="stripe_currency_code" id="stripe_currency_code" value="" />
				<input type="submit" name="buttonpayment" id="buttonpayment" class="submit-button" value="<?php echo $this->get_label('pay with stripe');?>" />
				<span class="paymentloading" style="display: none;"><img src="<?php echo BASE.'images/load.gif';?>" /></span>

				<?php if($stripeCheckoutType == 0){?>
				<div id="paymentResponse"></div>
				<?php } ?>
			</div>
		</div>
  </form>
 </div>
</div>

<?php if($stripeCheckoutType == 0 && isset($_POST['stripeToken'])){?>
<script language="JavaScript">
	$('.payment-tr').hide();
	$('#payment-tr8').show();
	$("#payment8").prop("checked", true);
</script>
<?php } else if($stripeCheckoutType == 1 && isset($_POST['stripeAmountSubmit'])){?>
	<script language="JavaScript">
		$('.payment-tr').hide();
		$('#payment-tr8').show();
		$("#payment8").prop("checked", true);
	</script>
<?php } ?>

<script type="text/javascript">
$(document).ready(function ()
{
		systemCurrencySectionShow("stripe");

		$("#stripe_currency_list").change(function ()
    {
        systemCurrencySectionShow("stripe");

        amount = $("#stripeamount").val();

        if(amount > 0)
        {
            currencyConversion(amount, "stripe", <?php echo $currencyArrayJSONStripe; ?>);

            fund_calculation(8,amount,"stripe");
        }
    });

	amount = $("#stripeamount").val();

	if(amount > 0)
	{
			currencyConversion(amount, "stripe", <?php echo $currencyArrayJSONStripe; ?>);

			fund_calculation(8,amount,"stripe");
	}

	$("#stripeamount").keyup(function ()
	{
			amount = $("#stripeamount").val();

			if(amount > 0)
			{
					currencyConversion(amount, "stripe", <?php echo $currencyArrayJSONStripe; ?>);

					fund_calculation(8,amount,"stripe");
			}
	});

	var paymentForm 	= document.getElementById('Advertiser_Payments_Stripe');

	<?php if($stripeCheckoutType == 0){?>

	$( "#card_number" ). addClass('form-control');
	$( "#card_expiry" ). addClass('form-control');
	$( "#card_cvc" ). addClass('form-control');

	var stripe          = Stripe('<?php echo Configuration::get_instance()->read('stripe_publishable_key');?>');
	var elements        = stripe.elements();
	var resultContainer = document.getElementById('paymentResponse');

	var cardElement = elements.create('cardNumber');
		cardElement.mount('#card_number');

	var cardExp     = elements.create('cardExpiry');
		cardExp.mount('#card_expiry');

	var cardCvc     = elements.create('cardCvc');
		cardCvc.mount('#card_cvc');

	cardElement.addEventListener('change', function(event) {

		if(event.error && resultContainer.innerHTML == "")
		resultContainer.innerHTML = "<p>"+event.error.message+"</p>";
		else
		resultContainer.innerHTML = "";

	});

	cardExp.addEventListener('change', function(event) {

		if(event.error && resultContainer.innerHTML == "")
		resultContainer.innerHTML = "<p>"+event.error.message+"</p>";
		else
		resultContainer.innerHTML = "";

	});

	cardCvc.addEventListener('change', function(event) {

		if(event.error && resultContainer.innerHTML == "")
		resultContainer.innerHTML = "<p>"+event.error.message+"</p>";
		else
		resultContainer.innerHTML = "";

	});

	function createToken()
	{
		stripe.createToken(cardElement).then(function(result)
		{
		    if(result.error)
		    {
			    // Inform the customer that there was an error.
			    resultContainer.innerHTML = result.error.message;

			    $('#buttonpayment').prop('disabled', false);
					$('.paymentloading').hide();
		    }
		    else
		    {
	      		// Send the token to your server.
		      	stripeTokenHandler(result.token);
		    }
		});
	}

	function stripeTokenHandler(token)
	{
			$('#stripeToken').val(token.id);
			$('#stripeAmountSubmit').val($('#total8').val());
			paymentForm.submit();
	}
	<?php } ?>


	paymentForm.addEventListener('submit', function(event)
	{
  	   event.preventDefault();

		   $('.paymentloading').show();

			 stripeamount            = $('#stripeSystemCurrencyAmount').val();
		   stripePaymentCurrency   = $('#stripe_currency_list').val();
		   stripeCurrencyCode      = $('#stripe_currency_code').val();
		   minamount               = <?php echo Configuration::get_instance()->read('min_amount_for_advertiser');?>;
		   currencyArrayJSONStripe = <?php echo $currencyArrayJSONStripe; ?>;

		   if(stripeamount == "" || stripeamount == 0)
		   {
					  alert("<?php echo $this->get_message("mandatory");?>");
					  document.getElementById("stripeamount").focus();
					  $('.paymentloading').hide();
					  return false;
		   }
		   else if(stripeamount < minamount)
		   {
					  alert("<?php echo $this->get_message("advertiser amount less");?>");
					  document.getElementById("stripeamount").focus();
					  $('.paymentloading').hide();
					  return false;
		   }
			 else if(!currencyArrayJSONStripe[stripePaymentCurrency] || stripePaymentCurrency != stripeCurrencyCode)
			 {
						alert("<?php echo $this->get_message("please select a valid currency for payment");?>");
						$('#stripe_currency_code').val("");
						$('#stripe_currency_list').focus();
						return false;
			 }

		   $('#buttonpayment').attr("disabled", "disabled");

			 <?php if($stripeCheckoutType == 0){?>
				 createToken();
			 <?php } else if($stripeCheckoutType == 1){?>

				 $('#stripeAmountSubmit').val($('#total8').val());
				 paymentForm.submit();

			 <?php } ?>
	});
});
</script>
