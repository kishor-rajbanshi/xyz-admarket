<?php
$this->dispatch("layout/header/3/1/a");

$currencyPosition = Configuration::get_instance()->read('currency_position');
$currencySymbol   = Configuration::get_instance()->read('currency_symbol');

$validate=array(
		"amount"=>array(
				"notNull"=>array($this->get_message("not null")),
				"isPositive"=>array($this->get_message("positive value")),
				"isOverMin"=>array(Configuration::get_instance()->read('min_amount_for_advertiser'),$this->get_message("advertiser amount less"))
		),
		"account_number"=>array(
				"notNull"=>array($this->get_message("not null"))
		),
		"b_name"=>array(
				"notNull"=>array($this->get_message("not null"))
		),
		"b_add1"=>array(
				"notNull"=>array($this->get_message("not null"))
		),
		"b_add2"=>array(
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

if($_POST)
$payment_mode=$this->get_variable('payment_mode');
else
{
    $result=$this->get_result('result');
    $value=$result[0];


    $apply_tax_rules		= Configuration::get_instance()->read('apply_tax_rules');
    $tax_calculation		= Configuration::get_instance()->read('tax_calculation');


    if($apply_tax_rules == 1 && $tax_calculation == 1)
    $req_amount=$value['req_amount'];
	else if($apply_tax_rules == 1 && $tax_calculation == 2)
	$req_amount=$value['amount'];
	else
	$req_amount=$value['req_amount'];


    $payment_mode=$value['payment_type'];
}

$sid=$this->get_variable('sid');

$frompg=$this->get_variable('frompg');
$pt=$this->get_variable('pt');
$st=$this->get_variable('st');
$pg=$this->get_variable('pg');
?>


<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 p-3 edit-payment">
	
	<h2 class="page-heading"><div class="page-inner"><i class="fa fa-pencil-square-o icon_red"></i><?php echo $this->get_label('edit payment');?></dv></h2>
	
<?php
$form=$this->create_form();
$form->start("edit_bank_payment",$this->make_url("advertiser/edit_payment/".$sid),"post",$validate);
?>


<div class="col-md-12 col-sm-12 col-xs-12">
	 <div class="row">
			<div class="col-md-6 col-sm-6 col-xs-6">
					 <label for="amount" class="form-label"><?php echo $this->get_label('amount');?> <span class="compulsory">*</span></label>

						<div class="input-group <?php if($currencyPosition == 2){?>flex-row-reverse<?php }?>">
							<div class="input-group-text dollar_style"><?php echo $currencySymbol;?></div>

								<input class="form-control" type="text" onkeyup="this.value = this.value.replace(/[^0-9\.]/g,'');" name="amount" id="amount" value="<?php if($_POST) echo $this->get_variable("amount"); else echo $req_amount;?>" />

						</div>

						<div class="notification"><bdi>[<?php echo $this->get_label('minimum advertiser payment amount',array('x'=>$this->get_money_format(Configuration::get_instance()->read('min_amount_for_advertiser'))));?>]</bdi></div>

						<span id = "loading" style="display: none;"><img src="<?php echo BASE.'images/load.gif';?>" /></span>
			</div>

			<div class="col-md-6 col-sm-6 col-xs-6">
					<label for="paymentMode" class="form-label"><?php echo $this->get_label('payment mode');?></label>
					<input class="form-control" type="text" name="payment_mode_box" value="<?php echo $this->get_payment_mode($payment_mode);?>" disabled="disabled" />
			</div>
 </div>
</div>

<div class="col-md-12 col-sm-12 col-xs-12 row mt-3 mb-3" id="tax-fee" ></div>

<div class="row">
	<div class="col-md-6 col-sm-6 col-xs-6">

		<?php if($payment_mode == 2){?>
			<div class="mb-3">
				<label for="accountNumber" class="form-label"><?php echo $this->get_label('account number');?> <span class="compulsory">*</span></label>
				<input class="form-control" type="text" name="account_number" id="account_number" value="<?php if($_POST) echo $this->get_variable("account_number"); else  echo $value['account_number'];?>" />
			</div>
		<?php } else if($payment_mode == 1){?>
			<div class="mb-3">
				<label for="checkNumber" class="form-label"><?php echo $this->get_label('check number');?> <span class="compulsory">*</span></label>
				<input class="form-control" type="text" name="account_number" id="account_number" value="<?php  if($_POST) echo $this->get_variable("account_number"); else echo $value['check_number'];?>" />
			</div>
		<?php }?>

		<div class="mb-3">
			<label for="bankAddress1" class="form-label"><?php echo $this->get_label('bank address line1');?> <span class="compulsory">*</span></label>
			<input class="form-control" type="text" name="b_add1" id="b_add1" value="<?php  if($_POST) echo $this->get_variable("b_add1"); else echo $value['address1'];?>" />
		</div>

		<div class="mb-3">
			<label for="countryName" class="form-label"><?php echo $this->get_label('country');?></label>
			<input class="form-control" type="text" name="countryName" value="<?php echo $this->get_country_name($this->get_variable('country'));?>" disabled="disabled" />
			<input type="hidden" name="country"  value="<?php echo $this->get_variable('country');?>" />
		</div>

		<div class="mb-3">
			<label for="cityName" class="form-label"><?php echo $this->get_label('city');?> <span class="compulsory">*</span></label>
			<input class="form-control" type="text" name="city" id="city" value="<?php  if($_POST) echo $this->get_variable("city"); else echo $value['city'];?>" />
		</div>

		<?php if($payment_mode==2){?>
			<div class="mb-3">
				<label for="swift/RoutingNo" class="form-label"><?php echo $this->get_label('swift/routing no');?> <span class="compulsory">*</span></label>
				<input class="form-control" type="text" name="swift" id="swift" value="<?php  if($_POST) echo $this->get_variable("swift"); else echo $value['swift_number'];?>" />
			</div>
		<?php }?>
	</div>

	<div class="col-md-6 col-sm-6 col-xs-6">
		<div class="mb-3">
			<label for="bankName" class="form-label"><?php echo $this->get_label('bank name');?> <span class="compulsory">*</span></label>
			<input class="form-control" type="text" name="b_name" id="b_name" value="<?php   if($_POST) echo $this->get_variable("b_name"); else echo $value['bank_name'];?>" />
		</div>

		<div class="mb-3">
			<label for="bankAddress2" class="form-label"><?php echo $this->get_label('bank address line2');?> <span class="compulsory">*</span></label>
			<input class="form-control" type="text" name="b_add2" id="b_add2" value="<?php  if($_POST) echo $this->get_variable("b_add2"); else echo $value['address2'];?>" />
		</div>

		<div class="mb-3">
			<label for="stateName" class="form-label"><?php echo $this->get_label('state');?> <span class="compulsory">*</span></label>
			<input class="form-control" type="text" name="state" id="state" value="<?php  if($_POST) echo $this->get_variable("state"); else echo $value['state'];?>" />
		</div>

		<div class="mb-3">
			<label for="accountHoldersName" class="form-label"><?php echo $this->get_label('account holders name');?> <span class="compulsory">*</span></label>
			<input class="form-control" type="text" name="a_name" id="a_name" value="<?php  if($_POST) echo $this->get_variable("a_name"); else echo $value['ac_holder_name'];?>" />
		</div>


		<div class="mb-3">
			<input type="hidden" name="pg" value="<?php echo $pg;?>" />
			<input type="hidden" name="st" value="<?php echo $st;?>" />
			<input type="hidden" name="pt" value="<?php echo $pt;?>" />
			<input type="hidden" name="frompg" value="<?php echo $frompg;?>" />
			<input type="hidden" name="sid" value="<?php echo $sid;?>" />
			<input type="hidden" name="payment_mode" value="<?php echo $payment_mode;?>" />
			<input class="submit-button" style="margin:0px;" type="submit" name="submit" value="<?php echo $this->get_label('edit payment');?>" />
		</div>

	</div>
<?php $form->end(); ?>
</div>

<?php $this->dispatch("layout/footer");?>
<script type="text/javascript">
$(document).ready(function () {
	fund_calculation();

    $("#amount").keyup(function (){
    fund_calculation();
    });
});

function fund_calculation()
{
	amount		= $("#amount").val();

	if(amount > 0)
	{
			$("#loading").show();

			dataparam 	= "amount="+amount+"&ptype=<?php echo $payment_mode;?>&country=<?php echo $this->get_variable('country');?>&from=1";

			$.ajax({
						type: "POST",
						data: dataparam,
						url: "<?php echo $this->make_url("advertiser/fund_calculation")?>",
						success: function(msg)
						{
								$("#tax-fee").html(msg);
								$("#loading").hide();
						}
				  });
	 }
	 else
	 $("#tax-fee").html("");
}
</script>
