<?php
$this->dispatch("layout/header/3/1/a");

$currencyPosition = Configuration::get_instance()->read('currency_position');
$currencySymbol   = Configuration::get_instance()->read('currency_symbol');


$system_currency         = Configuration::get_instance()->read('system_currency');
$currencyListPaypal      = $this->get_array("currencyListPaypal");

if(count($currencyListPaypal) > 0)
$currencyArrayJSONPaypal = json_encode($currencyListPaypal);
else
$currencyArrayJSONPaypal = new stdClass();
?>
<script language="JavaScript">
function select_payment(id)
{
		$('.payment-tr').hide();
		$('#payment-tr'+id).show();
}

function check_paypal_payment()
{
   amount          = $('#paypalSystemCurrencyAmount').val();
	 paymentCurrency = $('#paypal_currency_list').val();
	 currencyCode    = $('#paypal_currency_code').val();
   minamount       = <?php echo Configuration::get_instance()->read('min_amount_for_advertiser');?>;

	 currencyArrayJSONPaypal = <?php echo $currencyArrayJSONPaypal; ?>;

   if(amount > 0 && amount >= minamount)
   {
		   ptotal       = $('#total3').val();
		   $('#amount').val(ptotal);
		   document.Advertiser_Payments.submit();
   }
	 else if(!currencyArrayJSONPaypal[paymentCurrency] || paymentCurrency != currencyCode)
	 {
			 alert("<?php echo $this->get_message("please select a valid currency for payment");?>");
			 $('#paypal_currency_code').val("");
			 $('#paypal_currency_list').focus();
			 return;
	 }
   else if(amount == "" || amount == 0)
   {
		  alert("<?php echo $this->get_message("mandatory");?>");
		  $('#paamount').focus();
		  return;
   }
   else if(amount < minamount)
   {
		  alert("<?php echo $this->get_message("advertiser amount less");?>");
		  $('#paamount').focus();
		  return;
   }
}
</script>

<?php

$validate=array(
		"camount"=>array(
				"notNull"=>array($this->get_message("not null")),
				"isPositive"=>array($this->get_message("positive value")),
				"isOverMin"=>array(Configuration::get_instance()->read('min_amount_for_advertiser'),$this->get_message("advertiser amount less"))
		),
		"check_number"=>array(
				"notNull"=>array($this->get_message("not null"))
		),
		"b_name"=>array(
				"notNull"=>array($this->get_message("not null"))
		),
		"b_add1"=>array(
				"notNull"=>array($this->get_message("not null"))
		),
		"city"=>array(
				"notNull"=>array($this->get_message("not null"))
		),
		"state"=>array(
				"notNull"=>array($this->get_message("not null"))
		),
		"a_name"=>array(
				"notNull"=>array($this->get_message("not null"))
		)
);

$validate1=array(
		"bamount"=>array(
				"notNull"=>array($this->get_message("not null")),
				"isPositive"=>array($this->get_message("positive value")),
				"isOverMin"=>array(Configuration::get_instance()->read('min_amount_for_advertiser'),$this->get_message("advertiser amount less"))
		),
		"ac_number"=>array(
				"notNull"=>array($this->get_message("not null"))
		),
		"b_name"=>array(
				"notNull"=>array($this->get_message("not null"))
		),
		"b_add1"=>array(
				"notNull"=>array($this->get_message("not null"))
		),
		"city"=>array(
				"notNull"=>array($this->get_message("not null"))
		),
		"state"=>array(
				"notNull"=>array($this->get_message("not null"))
		),
		"a_name"=>array(
				"notNull"=>array($this->get_message("not null"))
		)
);

$row			    = $this->get_result('row');
$payment_mode	= $this->get_variable("payment_mode");
$country_name	= $this->get_variable("country_name");
$country		  = $this->get_variable("country");
?>

<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 payment-mode-list mb-2">
	<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 px-3">
	<h2 class="page-heading "><div class="page-inner"><i class="fa fa-money icon_red"></i><?php echo $this->get_label('add fund');?></div></h2>
	</div>
	<div class="row p-3">
		<div class="form-check-outer">
			<?php
			$iii=0;
			foreach($row as $key =>$value)
			{
				if($iii ==0 && $payment_mode ==0)
				$payment_mode=$value['id'];


				if($value['name'] =='checkout')
				$pathname='2co';
				else
				$pathname=$value['name'];



			 if($value['id'] <= 5 || ($value['id'] >5 && $this->get_addon_status($pathname.'_enabled') ==1)){?>

			 <div class="form-check form-check-inline">
				<input class="form-check-input" type="radio" name="payment" id="payment<?php echo $value['id'];?>" value="<?php echo $value['id'];?>" onchange="javascript:return select_payment(<?php echo $value['id'];?>);" <?php if($_POST && $payment_mode == $value['id']){?>checked="checked"<?php }else if($iii ==0){?>checked="checked"<?php }?> autocomplete="off" />
				<label class="form-check-label"><?php echo $this->get_label($value['name']);?></label>
			 </div>

			<?php
			$iii=$iii+1;
				}
			}?>

			<span id="minimum-payment-desktop-view" class="notification"><bdi>[<?php echo $this->get_label('minimum advertiser payment amount',array('x'=>$this->get_money_format(Configuration::get_instance()->read('min_amount_for_advertiser'))));?>]</bdi></span>
		</div>
			<span id="minimum-payment-mobile-view" class="notification"><bdi>[<?php echo $this->get_label('minimum advertiser payment amount',array('x'=>$this->get_money_format(Configuration::get_instance()->read('min_amount_for_advertiser'))));?>]</bdi></span>
	</div>
</div>

<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 p-3 mb-2 add-fund">

<?php foreach($row as $key =>$value){

if($value['id'] ==1){?>
<div class="row mx-auto payment-tr" id="payment-tr<?php echo $value['id'];?>" style="display: none;">
<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 p-3">
<?php

$form=$this->create_form();
$form->start("add_fund",$this->make_url("advertiser/add_fund"),"post",$validate);

$amount=$this->get_variable('camount');
$check_number=$this->get_variable('check_number');
$b_name=$this->get_variable('b_name');
$b_add1=$this->get_variable('b_add1');
$b_add2=$this->get_variable('b_add2');
$city=$this->get_variable('city');
$state=$this->get_variable('state');
$account_holder_name=$this->get_variable('account_holder_name');
?>

<div class="row">
	<div class="col-md-6 col-sm-6 col-xs-12">
			<label for="amount" class="form-label"><?php echo $this->get_label('amount');?> <span class="compulsory">*</span></label>

			<div class="input-group <?php if($currencyPosition == 2){?>flex-row-reverse<?php }?>">
				<div class="input-group-text dollar_style"><?php echo $currencySymbol;?></div>

				<input class="form-control" type="text" onkeyup="this.value = this.value.replace(/[^0-9\.]/g,'');" name="camount" id="camount" value="<?php echo $amount;?>" />

			</div>
			<span id="loading1" style="display: none;"><img src="<?php echo BASE.'images/load.gif';?>" /></span>
	</div>
</div>

<div class="col-md-12 col-sm-12 col-xs-12 row mt-3 mb-3" id="tax-fee-1" ></div>
  <div class="row">
		<div class="col-md-6 col-sm-6 col-xs-6">
			<div class="mb-3">
  			<label for="checkNumber" class="form-label"><?php echo $this->get_label('check number');?> <span class="compulsory">*</span></label>
  			<input  class="form-control"  type="text" name="check_number" id="check_number" value="<?php echo $check_number;?>">
			</div>
            <div class="mb-3">
            <label for="bankName" class="form-label"><?php echo $this->get_label('bank name');?> <span class="compulsory">*</span></label>
            <input class="form-control"  type="text" name="b_name" id="b_name" value="<?php echo $b_name;?>">
            </div>
            <div class="mb-3">
              	<label for="bankAaddressLine1" class="form-label"><?php echo $this->get_label('bank address line1');?> <span class="compulsory">*</span></label>
              	<input class="form-control"  type="text" name="b_add1" id="b_add1" value="<?php echo $b_add1;?>">
            </div>
            <div class="mb-3">
              	<label for="bankAddressLine2" class="form-label"><?php echo $this->get_label('bank address line2');?> <span class="compulsory">*</span></label>
              	<input class="form-control"  type="text" name="b_add2" id="b_add2" value="<?php echo $b_add2;?>">
            </div>
		</div>
		<div class="col-md-6 col-sm-6 col-xs-6">
			<div class="mb-3">
  				<label for="city" class="form-label"><?php echo $this->get_label('city');?> <span class="compulsory">*</span></label>
  				<input class="form-control"  type="text" name="city" id="city" value="<?php echo $city;?>">
			</div>
      <div class="mb-3">
        	<label for="state" class="form-label"><?php echo $this->get_label('state');?> <span class="compulsory">*</span></label>
        	<input class="form-control"  type="text" name="state" id="state" value="<?php echo $state;?>">
      </div>
			<div class="mb-3">
					<label for="country" class="form-label"><?php echo $this->get_label('country');?></label>
					<select class="form-control" name="countryName" disabled="disabled">
						<option value="<?php echo $country_name;?>"><?php echo $country_name;?></option>
					</select>
			</div>
			<div class="mb-3">
  				<label for="accountHolderName" class="form-label"><?php echo $this->get_label('account holders name');?> <span class="compulsory">*</span></label>
  				<input class="form-control"  type="text" name="a_name" id="a_name" value="<?php echo $account_holder_name;?>">
			</div>
		</div>
	</div>

    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <input type="hidden" name="ptype" id="ptype" value="1"/>
    <input class="submit-button" type="submit" name="submit" value="<?php echo $this->get_label('add fund');?>" />
    </div>

<?php $form->end(); ?>

</div>
<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 p-3">
<div class="addfund-rightbox row">
			<h4 class="section-sub-heading">
				<label><?php echo $this->get_label('send check to the following address');?></label>
			</h4>


		<table id="table-desktop" class="data_table" cellpadding="0" cellspacing="0">

					<tbody>
			<tr class="data_table_content">
					<td style="width: 155px;"><?php echo $this->get_label('payee name');?></td>
					<td style="width: 10px;">:</td>
					<td><?php echo Configuration::get_instance()->read('payee_name')?>
				</td></tr>

			<tr class="data_table_content">
					<td style="width: 155px;"><?php echo $this->get_label('payee address line1');?></td>
					<td style="width: 10px;">:</td>
					<td><?php echo Configuration::get_instance()->read('payee_address_line1')?>
				</td></tr>


			<?php if(Configuration::get_instance()->read('payee_address_line2') !=""){?>
			<tr class="data_table_content">
					<td style="width: 155px;"><?php echo $this->get_label('payee address line2');?></td>
					<td style="width: 10px;">:</td>
					<td><?php echo Configuration::get_instance()->read('payee_address_line2')?>
				</td></tr>
			<?php }?>

			<tr class="data_table_content">
					<td style="width: 155px;"><?php echo $this->get_label('payee city');?></td>
					<td style="width: 10px;">:</td>
					<td><?php echo Configuration::get_instance()->read('payee_city')?>
			</td></tr>

			<tr class="data_table_content">
					<td style="width: 155px;"><?php echo $this->get_label('payee state');?></td>
					<td style="width: 10px;">:</td>
					<td><?php echo Configuration::get_instance()->read('payee_state')?>
			</td></tr>

			<tr class="data_table_content">
					<td style="width: 155px;"><?php echo $this->get_label('payee country');?></td>
					<td style="width: 10px;">:</td>
					<td><?php echo Configuration::get_instance()->read('payee_country')?>
			</td></tr>

	</tbody>
</table></div>
	</div></div>
<?php }?>
<?php if($value['id'] ==2) {?>

<div class="row mx-auto payment-tr" id="payment-tr<?php echo $value['id'];?>" style="display: none;">
<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 p-3">
    <?php

    $form1=$this->create_form();
    $form1->start("add_fund1",$this->make_url("advertiser/add_fund"),"post",$validate1);

    $bamount=$this->get_variable('bamount');
    $ac_number=$this->get_variable('ac_number');
    $bb_name=$this->get_variable('bb_name');
    $bb_add1=$this->get_variable('bb_add1');
    $bb_add2=$this->get_variable('bb_add2');
    $bcity=$this->get_variable('bcity');
    $bstate=$this->get_variable('bstate');
    $baccount_holder_name=$this->get_variable('baccount_holder_name');
    $swift_no=$this->get_variable('swift');
    ?>

   	    <div class="col-md-12 col-sm-12 col-xs-12">
					 <div class="row">
					 	  <div class="col-md-6 col-sm-6 col-xs-6">
		    		       <label for="amount" class="form-label"><?php echo $this->get_label('amount');?> <span class="compulsory">*</span></label>

		                <div class="input-group <?php if($currencyPosition == 2){?>flex-row-reverse<?php }?>">
											<div class="input-group-text dollar_style"><?php echo $currencySymbol;?></div>

		                  	<input class="form-control" type="text" onkeyup="this.value = this.value.replace(/[^0-9\.]/g,'');" name="bamount" id="bamount" value="<?php echo $bamount;?>" />

		                </div>
		                <span id="loading1" style="display: none;"><img src="<?php echo BASE.'images/load.gif';?>" /></span>
					    </div>

							<div class="col-md-6 col-sm-6 col-xs-6">
									<label for="accountNumber" class="form-label"><?php echo $this->get_label('account number');?> <span class="compulsory">*</span></label>
									<input class="form-control" type="text" name="ac_number" id="ac_number" value="<?php echo $ac_number;?>">
						  </div>
				 </div>
      </div>

			<div class="col-md-12 col-sm-12 col-xs-12 row mt-3 mb-3" id="tax-fee-2" ></div>
				<div class="row">
					<div class="col-md-6 col-sm-6 col-xs-6">
            <div class="mb-3">
              <label for="bankName" class="form-label"><?php echo $this->get_label('bank name');?> <span class="compulsory">*</span></label>
              <input  class="form-control" type="text" name="bb_name" id="bb_name" value="<?php echo $bb_name;?>">
            </div>
            <div class="mb-3">
              <label for="bankAddressLine1" class="form-label"><?php echo $this->get_label('bank address line1');?> <span class="compulsory">*</span></label>
              <input type="email" class="form-control" type="text" name="bb_add1" id="bb_add1" value="<?php echo $bb_add1;?>">
            </div>
            <div class="mb-3">
              <label for="bankAddressLine2" class="form-label"><?php echo $this->get_label('bank address line2');?> <span class="compulsory">*</span></label>
              <input class="form-control" type="text" name="bb_add2" id="bb_add2" value="<?php echo $bb_add2;?>">
            </div>
            <div class="mb-3">
              <label for="swift/routingNumber" class="form-label"><?php echo $this->get_label('swift/routing no');?></label>
              <input  class="form-control" type="text" name="swift" id="swift" value="<?php echo $swift_no;?>">
            </div>
					</div>

				<div class="col-md-6 col-sm-6 col-xs-6">
          <div class="mb-3">
            <label for="city" class="form-label"><?php echo $this->get_label('city');?> <span class="compulsory">*</span></label>
            <input  class="form-control"  type="text" name="bcity" id="bcity" value="<?php echo $bcity;?>">
          </div>

          <div class="mb-3">
            <label for="state" class="form-label"><?php echo $this->get_label('state');?> <span class="compulsory">*</span></label>
            <input class="form-control" type="text" name="bstate" id="bstate" value="<?php echo $bstate;?>">
          </div>

					<div class="mb-3">
							<label for="country" class="form-label"><?php echo $this->get_label('country');?></label>
							<select class="form-control" name="countryName" disabled="disabled">
								<option value="<?php echo $country_name;?>"><?php echo $country_name;?></option>
							</select>
					</div>

          <div class="mb-3">
            <label for="accountHoldersName" class="form-label"><?php echo $this->get_label('account holders name');?> <span class="compulsory">*</span></label>
            <input type="email" class="form-control"type="text" name="ba_name" id="ba_name" value="<?php echo $baccount_holder_name;?>">
          </div>
			</div>

        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <input type="hidden" name="ptype" id="ptype2" value="2"/>
        <input class="submit-button" type="submit" name="submit" value="<?php echo $this->get_label('add fund');?>">
        </div>
	</div>

<?php $form1->end(); ?>
</div>

<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 p-3">
<div class="addfund-rightbox">
<h4 class="section-sub-heading"><label><?php echo $this->get_label('bank details');?></label></h4>

<table id="table-desktop" class="data_table" cellpadding="0" cellspacing="0">

					<tbody>
					<tr class="data_table_content">
					<td style="width: 155px;"><?php echo $this->get_label('bank name');?></td>
					<td style="width: 10px;">:</td>
					<td><?php echo Configuration::get_instance()->read('bank_name')?>
					</td></tr>


					<tr class="data_table_content">
					<td style="width: 155px;"><?php echo $this->get_label('beneficiary name');?></td>
					<td style="width: 10px;">:</td>
					<td><?php echo Configuration::get_instance()->read('name_for_fund_transfer')?></td></tr>



<?php if(Configuration::get_instance()->read('swift_routing_number') !=""){?>
					<tr class="data_table_content">
					<td style="width: 155px;"><?php echo $this->get_label('swift/routing number');?></td>
					<td style="width: 10px;">:</td>
					<td><?php echo Configuration::get_instance()->read('swift_routing_number')?>
					</td></tr>
<?php }?>


<tr class="data_table_content">
					<td style="width: 155px;"><?php echo $this->get_label('account number');?></td>
					<td style="width: 10px;">:</td>
					<td><?php echo Configuration::get_instance()->read('bank_account_number')?>
					</td></tr>


<?php if(Configuration::get_instance()->read('bank_account_type') !=""){?>
					<tr class="data_table_content">
					<td style="width: 155px;"><?php echo $this->get_label('account type');?></td>
					<td style="width: 10px;">:</td>
					<td><?php echo Configuration::get_instance()->read('bank_account_type')?>
					</td></tr>
<?php }?>

					<tr class="data_table_content">
					<td style="width: 155px;"><?php echo $this->get_label('bank address line1');?></td>
					<td style="width: 10px;">:</td>
					<td><?php echo Configuration::get_instance()->read('bank_address_line1')?>
					</td></tr>


<?php if(Configuration::get_instance()->read('bank_address_line2') !=""){?>
					<tr class="data_table_content">
					<td style="width: 155px;"><?php echo $this->get_label('bank address line2');?></td>
					<td style="width: 10px;">:</td>
					<td><?php echo Configuration::get_instance()->read('bank_address_line2')?>
					</td></tr>
<?php }?>


<tr class="data_table_content">
					<td style="width: 155px;"><?php echo $this->get_label('bank state');?></td>
					<td style="width: 10px;">:</td>
					<td><?php echo Configuration::get_instance()->read('bank_state')?>
					</td></tr>


<tr class="data_table_content">
					<td style="width: 155px;"><?php echo $this->get_label('bank country');?></td>
					<td style="width: 10px;">:</td>
					<td><?php echo Configuration::get_instance()->read('bank_country')?>
					</td></tr>


<tr class="data_table_content">
					<td style="width: 155px;"><?php echo $this->get_label('bank city');?></td>
					<td style="width: 10px;">:</td>
					<td><?php echo Configuration::get_instance()->read('bank_city')?>
					</td></tr>

</tbody>
</table></div></div></div>
<?php }?>
<?php if($value['id'] ==3){?>

<div class="row mx-auto payment-tr" id="payment-tr<?php echo $value['id'];?>" style="display: none;">
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 p-3">
				<div class="mb-3">
					<label for="paymentCurrency" class="form-label"><?php echo $this->get_label('payment currency');?></label>

					<select class="form-select" name="paypal_currency_list" id="paypal_currency_list">
						<?php
						foreach($currencyListPaypal as $cKey => $cValue){?>
						<option value="<?php echo $cKey; ?>" <?php if($cKey == $system_currency){?> selected <?php }?> ><?php echo $cKey;?></option>
						<?php } ?>
					</select>
				</div>

				<div class="mb-3">
					<label for="amount" class="form-label"><?php echo $this->get_label('amount');?> <span class="compulsory">*</span></label>

					<div class="input-group <?php if($currencyPosition == 2){?>flex-row-reverse<?php }?> mb-3 ">
						<div class="input-group-text dollar_style paypal-currency-span"><?php echo $currencySymbol;?></div>

					<input class="form-control"  onkeyup="this.value = this.value.replace(/[^0-9\.]/g,'');" type="text" name="paamount" id="paamount" value="" />

					</div>

					<span id="loading3" style="display: none;"><img src="<?php echo BASE.'images/load.gif';?>" /></span>
				</div>

				<div class="mb-3 paypalSystemCurrencyDiv">
					<label for="amount" class="form-label"><?php echo $this->get_label('amount in',array("x" => $system_currency));?></label>

					<div class="input-group <?php if($currencyPosition == 2){?>flex-row-reverse<?php }?> mb-3">
						<div class="input-group-text dollar_style"><?php echo $currencySymbol;?></div>

	          	<input type="text" class="form-control" type="text" name="paypalSystemCurrencyAmount" id="paypalSystemCurrencyAmount" value="" disabled>

	        </div>
				</div>

			  <div class="mb-1" id="tax-fee-3" ></div>

			  <div class="form-group">
			    	<!--<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" name="Advertiser_Payments" id="Advertiser_Payments">-->
						<form action="https://www.paypal.com/cgi-bin/webscr" method="post" name="Advertiser_Payments" id="Advertiser_Payments">
						  <input type="hidden" name="cmd" value="_xclick">
							<input type="hidden" name="item_name" value="<?php echo Configuration::get_instance()->read('admarket_name')."-User Fund Deposit";?>">
							<input type="hidden" name="item_number" value="<?php echo $this->get_variable("uid").'_'.time();?>">
							<input type="hidden" name="amount" id="amount" value="">
							<input type="hidden" name="currency_code" id="paypal_currency_code" value="<?php echo $system_currency;?>">
							<input type="hidden" name="notify_url" value="<?php echo $this->make_url('advertiser/paypal_ipn')?>">
							<input type="hidden" name="cancel_return" value="<?php echo $this->make_url('advertiser/paypal_cancel')?>">
							<input type="hidden" name="business" value="<?php echo Configuration::get_instance()->read('paypal_email');?>">
							<input type="hidden" name="no_shipping" value="1">
							<input type="hidden" name="no_note" value="0">
							<input type="hidden" name="custom" value="<?php echo $this->get_variable("uid");?>">
							<input type="hidden" name="rm" value="2">
							<input type="hidden" name="return" value="<?php echo $this->make_url('advertiser/paypal_success')?>">
							<button type="button" name="pay" class="submit-button" onclick="javascript:return check_paypal_payment();"><?php echo $this->get_label('pay with paypal');?></button>
						</form>
				</div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 p-3">
<div class="addfund-rightbox">
<h4 class="section-sub-heading"><label><?php echo $this->get_label('paypal details');?></label></h4>

<table id="table-desktop" class="data_table" cellpadding="0" cellspacing="0">

					<tbody>
					<tr class="data_table_content">
					<td style="width: 120px;"><?php echo $this->get_label('paypal email');?></td>
					<td style="width: 10px;">:</td>
					<td><?php echo Configuration::get_instance()->read('paypal_email')?></ttd>
	</tr>
</tbody></table>

</div>
</div></div>


<?php }?>

<?php
if($value['id'] >5)
{

if($value['name'] =='checkout')
$pathname='2co';
else
$pathname=$value['name'];

?>
<?php if($this->get_addon_status($pathname.'_enabled') ==1){?>
<div class="row mx-auto payment-tr" id="payment-tr<?php echo $value['id'];?>" style="display: none;">

			<?php
			if($value['name'] =='gourl-bitcoin')
			$controllername='bitcoin';
			else
			$controllername=$value['name'];
			?>

			<?php $this->dispatch($controllername."/add_fund",ADDON_DIR_PATH.$pathname.'/');?>

</div>
<?php }}}?>

</div>

<?php $this->dispatch("layout/footer");?>
<script type="text/javascript">
$(document).ready(function ()
{
		select_payment($('input[name=payment]:checked').val());

		systemCurrencySectionShow("paypal");

		$("#paypal_currency_list").change(function ()
		{
				systemCurrencySectionShow("paypal");

				var pamount = $("#paamount").val();

				if(pamount > 0)
				{
						currencyConversion(pamount, "paypal", <?php echo $currencyArrayJSONPaypal; ?>);

						fund_calculation(3,pamount,"paypal");
				}
		});

	payment_mode = <?php echo intval($payment_mode);?>;

	pamount = 0;

	if(payment_mode == 1)
	pamount	= $("#camount").val();
	else if(payment_mode == 2)
	pamount	= $("#bamount").val();
	else if(payment_mode == 3)
	pamount	= $("#paamount").val();


	if(pamount > 0)
	{
			if(payment_mode == 3)
			{
					fund_calculation(payment_mode,pamount,"paypal");

					currencyConversion(pamount, "paypal", <?php echo $currencyArrayJSONPaypal; ?>);
			}
			else
			fund_calculation(payment_mode,pamount);
	}

	$("#camount").keyup(function (){

		pamount=$("#camount").val();

		if(pamount > 0)
		fund_calculation(1,pamount);
	});

	$("#bamount").keyup(function (){

		pamount=$("#bamount").val();

		if(pamount > 0)
		fund_calculation(2,pamount);
	});

	$("#paamount").keyup(function (){

		pamount=$("#paamount").val();

		if(pamount > 0)
		{
				fund_calculation(3,pamount,"paypal");

				currencyConversion(pamount, "paypal", <?php echo $currencyArrayJSONPaypal; ?>);
		}
	});
});

function fund_calculation(ptype,pamount,paymentGateway)
{
		$("#loading"+ptype).show();

		country	= "<?php echo $country;?>";

		if(paymentGateway && $("#"+paymentGateway+"_currency_list").length > 0)
		paymentCurrency = $("#"+paymentGateway+"_currency_list").val();
		else
		paymentCurrency = '<?php echo $system_currency; ?>';

		dataparam = "amount="+pamount+"&ptype="+ptype+"&country="+country+"&paymentCurrency="+paymentCurrency;
	    $.ajax({
					type: "POST",
					data: dataparam,
					url: "<?php echo $this->make_url("advertiser/fund_calculation")?>",
					success: function(msg)
					{
						$("#loading"+ptype).hide();
						$("#tax-fee-"+ptype).html(msg);
					}
			});
}

function systemCurrencySectionShow(paymentGateway)
{
		var systemCurrency  = '<?php echo $system_currency; ?>';

		if($("."+paymentGateway+"-currency-span").length > 0)
		{
				$("."+paymentGateway+"-currency-span").html($("#"+paymentGateway+"_currency_list").val());
				$("#"+paymentGateway+"_currency_code").val($("#"+paymentGateway+"_currency_list").val());

				if($("#"+paymentGateway+"_currency_list").val() != systemCurrency)
				$("."+paymentGateway+"SystemCurrencyDiv").show();
				else
				$("."+paymentGateway+"SystemCurrencyDiv").hide();
		}
}


function currencyConversion(paymentAmount,paymentGateway,supportedCurrencies)
{
	  var paymentCurrency = $("#"+paymentGateway+"_currency_list").val();

	  var conversionRate  = supportedCurrencies[paymentCurrency];
	  var convertedRate   = 0;

		if(conversionRate > 0)
		convertedRate       = Math.round((paymentAmount / conversionRate)*1000000)/1000000;

		if(convertedRate > 0)
		$("#"+paymentGateway+"SystemCurrencyAmount").val(convertedRate);
		else
		$("#"+paymentGateway+"SystemCurrencyAmount").val("");
}
</script>
