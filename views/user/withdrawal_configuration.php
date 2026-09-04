<?php $this->dispatch("layout/header/7/1/b");?>
<script language="JavaScript">
function select_payment(id)
{
	$('.payment-tr').hide();
	$('#payment-tr'+id).show();
}
</script>
<?php

$row1234=$this->get_result('row1234');


$already=$this->get_variable("already");
$countrycurrent=$this->get_variable("countrycurrent");
$countrycode_check=$countrycurrent;
$countrycode_bank=$countrycurrent;
if($_POST)
{

	$payment=$this->get_variable('payment_mode');
	$check_payee_name=$this->get_variable('check_payee_name');
	$payee_address_line1=$this->get_variable('payee_address_line1');
	$payee_address_line2=$this->get_variable('payee_address_line2');
	$payee_city=$this->get_variable('payee_city');
	$payee_state=$this->get_variable('payee_state');



	$account_number=$this->get_variable('account_number');
	$bank_payee_name=$this->get_variable('bank_payee_name');
	$bank_name=$this->get_variable('bank_name');
	$bank_address_line1=$this->get_variable('bank_address_line1');
	$bank_address_line2=$this->get_variable('bank_address_line2');
	$bank_city=$this->get_variable('bank_city');
	$bank_state=$this->get_variable('bank_state');
	$swift_number=$this->get_variable('swift_number');

	$paypal_email=$this->get_variable('paypal_email');
}
else if($already >0)
{
	$res1=$this->get_result('res1');
	$value=$res1[0];


	$payment=$value['preferred_mode'];

	$check_payee_name=$value['check_payee_name'];
	$payee_address_line1=$value['payee_address_line1'];
	$payee_address_line2=$value['payee_address_line2'];
	$payee_city=$value['payee_city'];
	$payee_state=$value['payee_state'];


	$account_number=$value['account_number'];
	$bank_payee_name=$value['bank_payee_name'];
	$bank_name=$value['bank_name'];
	$bank_address_line1=$value['bank_address_line1'];
	$bank_address_line2=$value['bank_address_line2'];
	$bank_city=$value['bank_city'];
	$bank_state=$value['bank_state'];
	$swift_number=$value['swift_number'];
	$paypal_email=$value['paypal_email'];
}
else
{
	$payment='';
	$check_payee_name='';
	$payee_address_line1='';
	$payee_address_line2='';
	$payee_city='';
	$payee_state='';
	$account_number='';
	$bank_payee_name='';
	$bank_name='';
	$bank_address_line1='';
	$bank_address_line2='';
	$bank_city='';
	$bank_state='';
	$swift_number='';
	$paypal_email='';
}
?>

<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 p-3 mb-2 withdrawal-configuration">
	<h2 class="page-heading "><div class="page-inner"><i class="fa fa fa-credit-card icon_red"></i><?php echo $this->get_label('withdrawal configuration');?></div></h2>
	<div class="row mx-auto">

		<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 p-3 withdrawal-leftbox">

				<?php
				$iii=0;
				foreach($row1234 as $key1234 =>$value1234)
				{
						if($iii == 0 && ($payment == 0 || $payment == ""))
						$payment=$value1234['id'];
						?>
						<div class="form-check">
							<input class="form-check-input" type="radio" name="payment" id="payment<?php echo $value1234['id'];?>" value="<?php echo $value1234['id'];?>" onchange="javascript:return select_payment(<?php echo $value1234['id'];?>);" <?php if($payment == $value1234['id']){?>checked="checked"<?php }?> />
							<label class="form-check-label mt-1"><?php echo $this->get_label($value1234['name']);?></label>
						</div>
						<?php
						$iii=$iii+1;
				}
				?>
		</div>
		<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12 p-3 pt-0">

			<?php if($already > 0){?>
				<div class="notification withdrawal-mode mt-3">
					<?php echo $this->get_label('active withdrawal mode is',array('x'=>$this->get_withdrawal_mode($payment)));?>
				</div>
			<?php } ?>
<?php
foreach($row1234 as $key1234 =>$value1234)
{

if($value1234['id'] ==1)
{
	$validate=array(
			"payee_name"=>array(
					"notNull"=>array($this->get_message("not null"))
			),
			"add1"=>array(
					"notNull"=>array($this->get_message("not null"))
			),
			"add2"=>array(
					"notNull"=>array($this->get_message("not null"))
			),
			"city"=>array(
					"notNull"=>array($this->get_message("not null"))
			),
			"state"=>array(
					"notNull"=>array($this->get_message("not null"))
			)
	);

$form=$this->create_form();
$form->start("check_form",$this->make_url("user/withdrawal_configuration"),"post",$validate);

?>
<div class="payment-tr" id="payment-tr<?php echo $value1234['id'];?>">
	<h2 class="section-heading"><?php echo $this->get_label('check details');?></h2>

		<div class="col-md-12 col-sm-12 col-xs-12">

			<div class="row">

				<div class="col-md-6 col-sm-6 col-xs-12 form-group mb-3">
					<label class="form-label"><?php echo $this->get_label('payee name');?> <span class="compulsory">*</span></label>
					<input class="form-control" type="text" name="payee_name" id="payee_name" value="<?php echo $check_payee_name;?>" />
				</div>

				<div class="col-md-6 col-sm-6 col-xs-12 form-group mb-3">
					<label class="form-label"><?php echo $this->get_label('address line1');?> <span class="compulsory">*</span></label>
					<input class="form-control" type="text" name="add1" id="add1" value="<?php echo $payee_address_line1;?>" />
				</div>

				<div class="col-md-6 col-sm-6 col-xs-12 form-group mb-3">
					<label class="form-label"><?php echo $this->get_label('address line2');?> <span class="compulsory">*</span></label>
					<input class="form-control" type="text" name="add2" id="add2" value="<?php echo $payee_address_line2;?>" />
				</div>

				<div class="col-md-6 col-sm-6 col-xs-12 form-group mb-3">
					<label class="form-label"><?php echo $this->get_label('country');?> </label>
					<select class="form-control" name="countryName" disabled="disabled">
						<option value="<?php echo $countrycode_check;?>"><?php echo $this->get_country_name($countrycode_check);?></option>
					</select>
				</div>

				<div class="col-md-6 col-sm-6 col-xs-12 form-group mb-3">
					<label class="form-label"><?php echo $this->get_label('state');?> <span class="compulsory">*</span></label>
					<input class="form-control" type="text" name="state" id="state" value="<?php echo $payee_state;?>" />
				</div>

				<div class="col-md-6 col-sm-6 col-xs-12 form-group mb-3">
					<label class="form-label"><?php echo $this->get_label('city');?> <span class="compulsory">*</span></label>
					<input class="form-control" type="text" name="city" id="city" value="<?php echo $payee_city;?>" />
				</div>

				<div class="form-group col-md-12 col-sm-12 col-xs-12">
					<input type="hidden" name="country_check" id="country_check" value="<?php echo $countrycode_check;?>" />
					<input type="hidden" name="payment_mode" id="payment_mode" value="1" />
					<input class="submit-button" type="submit" name="submit" value="<?php echo $this->get_label('configure');?>">
				</div>

			</div>
	</div>
</div>
<?php $form->end(); ?>

<?php } else if($value1234['id'] == 2) {

	$validate1=array(
			"ac_number"=>array(
					"notNull"=>array($this->get_message("not null"))
			),
			"payee_name"=>array(
					"notNull"=>array($this->get_message("not null"))
			),
			"b_name"=>array(
					"notNull"=>array($this->get_message("not null"))
			),
			"add1"=>array(
					"notNull"=>array($this->get_message("not null"))
			),
			"add2"=>array(
					"notNull"=>array($this->get_message("not null"))
			),
			"city"=>array(
					"notNull"=>array($this->get_message("not null"))
			),
			"state"=>array(
					"notNull"=>array($this->get_message("not null"))
			),
			"swift"=>array(
					"notNull"=>array($this->get_message("not null"))
			),
	);



$form1=$this->create_form();
$form1->start("bank_form",$this->make_url("user/withdrawal_configuration"),"post",$validate1);

?>
<div class="payment-tr" id="payment-tr<?php echo $value1234['id'];?>" style="display: none">

<h2 class="section-heading"><?php echo $this->get_label('bank details');?></h2>

<div class="col-md-12 col-sm-12 col-xs-12">
	<div class="row">

			<div class="col-md-6 col-sm-6 col-xs-12 form-group mb-3">
				<label class="form-label"><?php echo $this->get_label('account number');?> <span class="compulsory">*</span></label>
				<input class="form-control" type="text" name="ac_number" id="ac_number" value="<?php echo $account_number;?>" />
			</div>

			<div class="col-md-6 col-sm-6 col-xs-12 form-group mb-3">
				<label class="form-label"><?php echo $this->get_label('payee name');?> <span class="compulsory">*</span></label>
				<input class="form-control" type="text" name="payee_name" id="payee_name" value="<?php echo $bank_payee_name;?>" />
			</div>

			<div class="col-md-6 col-sm-6 col-xs-12 form-group mb-3">
				<label class="form-label"><?php echo $this->get_label('bank name');?> <span class="compulsory">*</span></label>
				<input class="form-control" type="text" name="b_name" id="b_name" value="<?php echo $bank_name;?>" />
			</div>

			<div class="col-md-6 col-sm-6 col-xs-12 form-group mb-3">
				<label class="form-label"><?php echo $this->get_label('bank address line1');?> <span class="compulsory">*</span></label>
				<input class="form-control" type="text" name="add1" id="add1" value="<?php echo $bank_address_line1;?>" />
			</div>

			<div class="col-md-6 col-sm-6 col-xs-12 form-group mb-3">
				<label class="form-label"><?php echo $this->get_label('bank address line2');?> <span class="compulsory">*</span></label>
				<input class="form-control" type="text" name="add2" id="add2" value="<?php echo $bank_address_line2;?>" />
			</div>

			<div class="col-md-6 col-sm-6 col-xs-12 form-group mb-3">
				<label class="form-label"><?php echo $this->get_label('country');?> </label>
				<select class="form-control" name="countryName" disabled="disabled">
					<option value="<?php echo $countrycode_bank;?>"><?php echo $this->get_country_name($countrycode_bank);?></option>
				</select>
			</div>

			<div class="col-md-6 col-sm-6 col-xs-12 form-group mb-3">
				<label class="form-label"><?php echo $this->get_label('state');?> <span class="compulsory">*</span></label>
				<input class="form-control" type="text" name="state" id="state" value="<?php echo $bank_state;?>">
			</div>

			<div class="col-md-6 col-sm-6 col-xs-12 form-group mb-3">
				<label class="form-label"><?php echo $this->get_label('city');?> <span class="compulsory">*</span></label>
				<input class="form-control" type="text" name="city" id="city" value="<?php echo $bank_city;?>" />
			</div>

			<div class="col-md-6 col-sm-6 col-xs-12 form-group mb-3">
				<label class="form-label"><?php echo $this->get_label('swift/routing no');?> <span class="compulsory">*</span></label>
				<input class="form-control" type="text" name="swift" id="swift" value="<?php echo $swift_number;?>" />
			</div>

			<div class="col-md-12 col-sm-12 col-xs-12 form-group">
				<input type="hidden" name="payment_mode" id="payment_mode" value="2" />
				<input type="hidden" name="country_bank" id="country_bank" value="<?php echo $countrycode_bank;?>" />
				<input class="submit-button"  type="submit" name="submit" value="<?php echo $this->get_label('configure');?>">
			</div>

		</div>
	</div>
</div>

<?php $form1->end(); ?>

<?php } else if($value1234['id'] == 3) {

	$validate2=array(
			"email"=>array(
					"notNull"=>array($this->get_message("not null")),
					"isEmail"=>array($this->get_message("invalid email address"))
			)
	);

$form2=$this->create_form();
$form2->start("paypal_form",$this->make_url("user/withdrawal_configuration"),"post",$validate2);
?>

<div class="payment-tr" id="payment-tr<?php echo $value1234['id'];?>" style="display: none;">
	<h2 class="section-heading"><?php echo $this->get_label('paypal details');?></h2>

	<div class="form-group col-md-12 col-sm-12 col-xs-12">
			<div class="form-group col-md-6 col-sm-6 col-xs-12">

				<div class="form-group mb-3">
					<label class="form-label"><?php echo $this->get_label('paypal email');?> <span class="compulsory">*</span></label>
					<input class="form-control" type="text" name="email" id="email" value="<?php echo $paypal_email;?>" />
			  </div>

				<div class="form-group">
					<input type="hidden" name="payment_mode" id="payment_mode" value="3" />
					<input class="submit-button" type="submit" name="submit" value="<?php echo $this->get_label('configure');?>" />
				</div>

			</div>
	</div>
</div>
<?php
$form2->end();
}
else if($value1234['id'] > 5)
{
	$uid=$this->get_variable('uid');
	$colalready=$this->get_result('colalready');

	$form3=$this->create_form();
	$form3->start("form_".$value1234['id'],$this->make_url("user/withdrawal_configuration"),"post");
	?>
	<div class="payment-tr" id="payment-tr<?php echo $value1234['id'];?>" style="display: none;">
			<?php
		  $iii=0;
			foreach($colalready as $key=>$row123)
			{
					$carray=explode('_',$row123['Field']);
					if($carray[0] == $value1234['id'])
					{
						  if($iii ==0){?>
							<h2 class="section-heading"><?php echo $this->get_label('withdrawal details label',array('x'=>$value1234['name']));?></h2>

							<div class="form-group col-md-12 col-sm-12 col-xs-12">
							<div class="form-group col-md-6 col-sm-6 col-xs-12">
							<?php
							}

							if($value[$row123['Field']] == 0)
							$value[$row123['Field']] = "";

							if($_POST)
							$data = $this->get_variable($row123['Field']);
							else if($already > 0)
							$data = $value[$row123['Field']];
							else
							$data = "";
							?>

							<div class="form-group mb-3">
							<label class="form-label"><?php echo $row123['Comment'];?> <span class="compulsory">*</span></label>

							<input class="form-control" type="text" name="<?php echo $row123['Field'];?>" id="<?php echo $row123['Field'];?>" value="<?php echo $data;?>" />
							<?php if(
												strtolower($row123['Type']) =='int(11)' ||
												strtolower($row123['Type']) =='int' ||
												strtolower($row123['Type']) =='bigint(20)' ||
												strtolower($row123['Type']) =='bigint'
											){?>
								<div class="notification">[<?php echo $this->get_label('numeric values only');?>]</div><?php }?>
							</div>
							<?php
							$iii = $iii+1;
					}
			}
			?>

			<div class="form-group">
				<input type="hidden" name="payment_mode" id="payment_mode" value="<?php echo $value1234['id'];?>">
				<input class="submit-button" type="submit" name="submit" value="<?php echo $this->get_label('configure');?>" />
			</div>

			</div>
		</div>
	</div>
	<?php
	$form3->end();
}


}
?>
</div>
</div>
</div>

<?php $this->dispatch("layout/footer");?>
<script type="text/javascript">
select_payment($('input[name=payment]:checked').val());
</script>
