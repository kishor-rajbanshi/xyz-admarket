<?php
$this->dispatch("layout/header/3/2/a");

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
    $reslt=$this->get_result('reslt');
    $value=$reslt[0];
    
    $req_amount=$value['req_amount'];

    $payment_mode=$value['payment_type'];	
}
	    
$sid=$this->get_variable('sid');

$frompg=$this->get_variable('frompg');
$pt=$this->get_variable('pt');
$st=$this->get_variable('st');
$pg=$this->get_variable('pg');

$re=$this->get_result('re');
?>

<div class="container"><h2 class="page_heading"><?php echo $this->get_label('edit payment');?></h2>
<div class="page_heading-btm"></div>
</div>
  <div class="container label_style special-label">
   <div class="col-md-12 col-sm-12 col-xs-12 box_style">
   <div class="col-md-12 col-sm-12 col-xs-12 box_div">
<?php 
$form=$this->create_form();
$form->start("edit_bank_payment",$this->make_url("advertiser/edit_payment/".$sid),"post",$validate); 
?>

<div class="form-group col-md-12 col-sm-12 col-xs-12">
<label class="col-md-3 col-sm-4 col-xs-12" ><?php echo $this->get_label('payment mode');?></label>
<div class="col-md-3 col-sm-5 col-xs-12"><?php echo $this->get_payment_mode($payment_mode);?></div>
<div class="col-md-6 col-sm-3 col-xs-12"></div>
</div>


<div class="form-group col-md-12 col-sm-12 col-xs-12">
<label class="col-md-3 col-sm-4 col-xs-12" ><?php echo $this->get_label('amount');?> <span class="compulsory">*</span></label>
<div class="col-md-3 col-sm-5 col-xs-12">

<div style="width: 118px;">
<?php if(Configuration::get_instance()->read('currency_position')==1){?>
<span class="dollar_style" ><?php echo Configuration::get_instance()->read('currency_symbol');?></span>
<?php }?>
<input class="form-control dollar_input"  onkeyup="this.value = this.value.replace(/[^0-9\.]/g,'');" type="text" name="amount" id="amount" value="<?php if($_POST) echo $this->get_variable("amount"); else echo $req_amount;?>" />
<?php if(Configuration::get_instance()->read('currency_position')!=1){?>
<span class="dollar_style"><?php echo Configuration::get_instance()->read('currency_symbol');?></span>
<?php }?>
</div>

<div class="notification" style="float: left;"><bdi>[<?php echo $this->get_label('minimum advertiser payment amount',array('x'=>$this->get_money_format(Configuration::get_instance()->read('min_amount_for_advertiser'))));?>]</bdi></div>
</div>
<div class="col-md-6 col-sm-3 col-xs-12"></div>
</div>


<div id="tax-fee" ></div>


<?php if($payment_mode==2) { ?>

<div class="form-group col-md-12 col-sm-12 col-xs-12">
<label class="col-md-3 col-sm-4 col-xs-12" ><?php echo $this->get_label('account number');?> <span class="compulsory">*</span></label>
<div class="col-md-3 col-sm-5 col-xs-12"><input class="form-control" type="text" name="account_number" id="account_number" value="<?php if($_POST) echo $this->get_variable("account_number"); else  echo $value['account_number'];?>" ></div>
<div class="col-md-6 col-sm-3 col-xs-12"></div>
</div>
<?php } else if($payment_mode==1) { ?>


<div class="form-group col-md-12 col-sm-12 col-xs-12">
<label class="col-md-3 col-sm-4 col-xs-12" ><?php echo $this->get_label('check number');?> <span class="compulsory">*</span></label>
<div class="col-md-3 col-sm-5 col-xs-12"><input class="form-control" type="text" name="account_number" id="account_number" value="<?php  if($_POST) echo $this->get_variable("account_number"); else echo $value['check_number'];?>" ></div>
<div class="col-md-6 col-sm-3 col-xs-12"></div>
</div>


<?php }?>

<div class="form-group col-md-12 col-sm-12 col-xs-12">
<label class="col-md-3 col-sm-4 col-xs-12" ><?php echo $this->get_label('bank name');?> <span class="compulsory">*</span></label>
<div class="col-md-3 col-sm-5 col-xs-12"><input class="form-control" type="text" name="b_name" id="b_name" value="<?php   if($_POST) echo $this->get_variable("b_name"); else echo $value['bank_name'];?>" ></div>
<div class="col-md-6 col-sm-3 col-xs-12"></div>
</div>



<div class="form-group col-md-12 col-sm-12 col-xs-12">
<label class="col-md-3 col-sm-4 col-xs-12" ><?php echo $this->get_label('bank address line1');?> <span class="compulsory">*</span></label>
<div class="col-md-3 col-sm-5 col-xs-12"><input class="form-control" type="text" name="b_add1" id="b_add1" value="<?php  if($_POST) echo $this->get_variable("b_add1"); else echo $value['address1'];?>" ></div>
<div class="col-md-6 col-sm-3 col-xs-12"></div>
</div>



<div class="form-group col-md-12 col-sm-12 col-xs-12">
<label class="col-md-3 col-sm-4 col-xs-12" ><?php echo $this->get_label('bank address line2');?> <span class="compulsory">*</span></label>
<div class="col-md-3 col-sm-5 col-xs-12"><input class="form-control" type="text" name="b_add2" id="b_add2" value="<?php  if($_POST) echo $this->get_variable("b_add2"); else echo $value['address2'];?>" ></div>
<div class="col-md-6 col-sm-3 col-xs-12"></div>
</div>



<div class="form-group col-md-12 col-sm-12 col-xs-12">
<label class="col-md-3 col-sm-4 col-xs-12" ><?php echo $this->get_label('city');?> <span class="compulsory">*</span></label>
<div class="col-md-3 col-sm-5 col-xs-12"><input class="form-control" type="text" name="city" id="city" value="<?php  if($_POST) echo $this->get_variable("city"); else echo $value['city'];?>" ></div>
<div class="col-md-6 col-sm-3 col-xs-12"></div>
</div>


<div class="form-group col-md-12 col-sm-12 col-xs-12">
<label class="col-md-3 col-sm-4 col-xs-12" ><?php echo $this->get_label('state');?> <span class="compulsory">*</span></label>
<div class="col-md-3 col-sm-5 col-xs-12"><input class="form-control" type="text" name="state" id="state" value="<?php  if($_POST) echo $this->get_variable("state"); else echo $value['state'];?>" ></div>
<div class="col-md-6 col-sm-3 col-xs-12"></div>
</div>



<div class="form-group col-md-12 col-sm-12 col-xs-12">
<label class="col-md-3 col-sm-4 col-xs-12" ><?php echo $this->get_label('country');?> </label>
<div class="col-md-3 col-sm-5 col-xs-12">
<input type="hidden" name="country"  value="<?php echo $country;?>" /><?php echo $this->get_country_name($this->get_variable('country'));?>

</div>
<div class="col-md-6 col-sm-3 col-xs-12"></div>
</div>

<div class="form-group col-md-12 col-sm-12 col-xs-12">
<label class="col-md-3 col-sm-4 col-xs-12" ><?php echo $this->get_label('account holders name');?> <span class="compulsory">*</span></label>
<div class="col-md-3 col-sm-5 col-xs-12"><input class="form-control" type="text" name="a_name" id="a_name" value="<?php  if($_POST) echo $this->get_variable("a_name"); else echo $value['ac_holder_name'];?>" ></div>
<div class="col-md-6 col-sm-3 col-xs-12"></div>
</div>



<?php if($payment_mode==2) { ?>
<div class="form-group col-md-12 col-sm-12 col-xs-12">
<label class="col-md-3 col-sm-4 col-xs-12" ><?php echo $this->get_label('swift/routing no');?></label>
<div class="col-md-3 col-sm-5 col-xs-12"><input class="form-control" type="text" name="swift" id="swift" value="<?php  if($_POST) echo $this->get_variable("swift"); else echo $value['swift_number'];?>" ></div>
<div class="col-md-6 col-sm-3 col-xs-12"></div>
</div>
<?php }?>

<div class="form-group col-md-12 col-sm-12 col-xs-12">
<label class="col-md-3 col-sm-4 col-xs-12" ></label>
<div class="col-md-3 col-sm-5 col-xs-12">
<input type="hidden" name="pg" value="<?php echo $pg;?>" />
<input type="hidden" name="st" value="<?php echo $st;?>" />
<input type="hidden" name="pt" value="<?php echo $pt;?>" />
<input type="hidden" name="frompg" value="<?php echo $frompg;?>" />
<input type="hidden" name="sid" value="<?php echo $sid;?>" />
<input type="hidden" name="payment_mode" value="<?php echo $payment_mode;?>" />
<input class="btn btn-primary btn-lg" type="submit" name="submit" value="<?php echo $this->get_label('edit payment');?>" />
</div>
<div class="col-md-6 col-sm-3 col-xs-12"></div>
</div>

<?php $form->end(); ?>
</div></div></div>
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
	dataparam 	= "amount="+amount+"&ptype=<?php echo $payment_mode;?>&country=<?php echo $this->get_variable('country');?>&from=1";
	
	
	$.ajax({
				type: "POST",
				data: dataparam,
				url: "<?php echo $this->make_url("advertiser/fund_calculation")?>",
				success: function(msg)
				{
					$("#tax-fee").html(msg);
				}
		  });
}
</script>