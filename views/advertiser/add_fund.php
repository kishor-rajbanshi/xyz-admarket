<?php $this->dispatch("layout/header/3/1/a");?>
<script language="JavaScript"> 
function select_payment(id)
{
	$('.payment-tr').hide();
	$('#payment-tr'+id).show();
}
function check_paypal_payment()
{
   amount=$('#paamount').val();

   minamount=<?php echo Configuration::get_instance()->read('min_amount_for_advertiser');?>;

   if(amount >0 && amount >=minamount)
   {
	   ptotal=$('#total3').val();
	   $('#amount').val(ptotal);
	   document.Advertiser_Payments.submit();
   }
   else if(amount =="" || amount==0)
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

$row			= $this->get_result('row');

$payment_mode	= $this->get_variable("payment_mode");
$country_name	= $this->get_variable("country_name");
$country		= $this->get_variable("country");
?>

<div class="container"><h2 class="page_heading"><?php echo $this->get_label('add fund to your advertiser account');?></h2>
<div class="page_heading-btm"></div>
</div>

<div class="container label_style special-label">



<div class="form-group checkbox_head">
<label><?php echo $this->get_label('payment mode');?></label>
</div>

<div class="col-md-12 col-sm-12 col-xs-12 box_div box_div_bg home-box-outer" style="padding-top:0px;">
<div class="form-group" style="padding-top: 15px;">


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

<label style="font-size: 14px;"><input type="radio" name="payment" id="payment<?php echo $value['id'];?>" value="<?php echo $value['id'];?>" onchange="javascript:return select_payment(<?php echo $value['id'];?>);" <?php if($_POST && $payment_mode ==$value['id']){?>checked="checked"<?php }else if($iii ==0){?>checked="checked"<?php }?> /><?php echo $this->get_label($value['name']);?></label>&nbsp;&nbsp;
<?php 
$iii=$iii+1;

}

}?>


<span class="notification"><bdi>[<?php echo $this->get_label('minimum advertiser payment amount',array('x'=>$this->get_money_format(Configuration::get_instance()->read('min_amount_for_advertiser'))));?>]</bdi></span>

</div></div>
  




<?php foreach($row as $key =>$value){


if($value['id'] ==1) {?>

<div class="row">
<div class="col-md-12 col-sm-12 col-xs-12 payment-tr padding-side" id="payment-tr<?php echo $value['id'];?>" style="display: none;">




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


<div class="col-md-8 col-sm-12 col-xs-12">

<div class="col-md-12 col-sm-12 col-xs-12 box_div box_div_bg">


<div class="form-group col-md-12 col-sm-12 col-xs-12 " ><label><?php echo $this->get_label('amount');?> <span class="compulsory">*</span></label>


<div style="max-width:120px;">
<?php if(Configuration::get_instance()->read('currency_position')==1){?>
<span class="dollar_style" ><?php echo Configuration::get_instance()->read('currency_symbol');?></span>
<?php }?>
<input class="form-control dollar_input" type="text" onkeyup="this.value = this.value.replace(/[^0-9\.]/g,'');" name="camount" id="camount" value="<?php echo $amount;?>" />

<?php if(Configuration::get_instance()->read('currency_position')!=1){?>
<span class="dollar_style"><?php echo Configuration::get_instance()->read('currency_symbol');?></span>
<?php }?>
</div>
<span id="loading1" style="display: none;"><img src="<?php echo BASE.'images/load.gif';?>" /></span>

</div>


<div class="row" id="tax-fee-1" ></div>


<div class="col-md-6 col-sm-6 col-xs-12">


<div class="form-group"><label><?php echo $this->get_label('check number');?> <span class="compulsory">*</span></label>
<input class="form-control" type="text" name="check_number" id="check_number" value="<?php echo $check_number;?>" />
</div>


<div class="form-group"><label><?php echo $this->get_label('bank name');?> <span class="compulsory">*</span></label>
<input class="form-control" type="text" name="b_name" id="b_name" value="<?php echo $b_name;?>" />
</div>


<div class="form-group"><label><?php echo $this->get_label('bank address line1');?> <span class="compulsory">*</span></label>
<input class="form-control" type="text" name="b_add1" id="b_add1" value="<?php echo $b_add1;?>" />
</div>


<div class="form-group"><label><?php echo $this->get_label('bank address line2');?> <span class="compulsory">*</span></label>
<input class="form-control" type="text" name="b_add2" id="b_add2" value="<?php echo $b_add2;?>" />
</div>

</div> 
<div class="col-md-6 col-sm-6 col-xs-12">

<div class="form-group"><label><?php echo $this->get_label('city');?> <span class="compulsory">*</span></label>
<input class="form-control" type="text" name="city" id="city" value="<?php echo $city;?>" />
</div>



<div class="form-group"><label><?php echo $this->get_label('state');?> <span class="compulsory">*</span></label>
<input class="form-control" type="text" name="state" id="state" value="<?php echo $state;?>" />
</div>


<div class="form-group"><label></label></div>
<div class="form-group"><label></label></div>

<div class="form-group"><label><?php echo $this->get_label('country');?> </label> : <?php echo $country_name;?>
</div>




<div class="form-group"><label><?php echo $this->get_label('account holders name');?> <span class="compulsory">*</span></label>
<input class="form-control" type="text" name="a_name" id="a_name" value="<?php echo $account_holder_name;?>" />
</div>



</div> 
<div class="form-group" style="clear: both"><label class="col-md-5 col-sm-5 col-xs-12" ></label>
<input type="hidden" name="ptype" id="ptype" value="1"/>
<input class="btn btn-primary btn-lg" type="submit" name="submit" value="<?php echo $this->get_label('add fund');?>" />
</div>
</div>
</div>
<?php $form->end(); ?>

<div class="col-md-4 col-sm-8 col-xs-12 special-label-new">

<div class="col-md-12 col-sm-12 col-xs-12 box_div box_div_bg">

<div class="form-group checkbox_head"><label><?php echo $this->get_label('send check to the following address');?></label></div>


<div class="form-inline">
<div class="form-group"><label style="width: 135px;"><?php echo $this->get_label('payee name');?></label>
<label style="width: 20px;">:</label>
<label><?php echo Configuration::get_instance()->read('payee_name')?></label>
</div></div>


<div class="form-inline">
<div class="form-group"><label style="width: 135px;"><?php echo $this->get_label('payee address line1');?></label>
<label style="width: 20px;">:</label>
<label><?php echo Configuration::get_instance()->read('payee_address_line1')?></label>
</div></div>


<?php if(Configuration::get_instance()->read('payee_address_line2') !=""){?>
<div class="form-inline">
<div class="form-group"><label style="width: 135px;"><?php echo $this->get_label('payee address line2');?></label>
<label style="width: 20px;">:</label>
<label><?php echo Configuration::get_instance()->read('payee_address_line2')?></label>
</div></div>
<?php }?>

<div class="form-inline">
<div class="form-group"><label style="width: 135px;"><?php echo $this->get_label('payee city');?></label>
<label style="width: 20px;">:</label>
<label><?php echo Configuration::get_instance()->read('payee_city')?></label>
</div></div>

<div class="form-inline">
<div class="form-group"><label style="width: 135px;"><?php echo $this->get_label('payee state');?></label>
<label style="width: 20px;">:</label>
<label><?php echo Configuration::get_instance()->read('payee_state')?></label>
</div></div>


<div class="form-inline">
<div class="form-group"><label style="width: 135px;"><?php echo $this->get_label('payee country');?></label>
<label style="width: 20px;">:</label>
<label><?php echo Configuration::get_instance()->read('payee_country')?></label>
</div></div>

</div>
</div>
</div>
</div>
<?php }?>
<?php if($value['id'] ==2) {?>
<div class="row">
<div class="col-md-12 col-sm-12 col-xs-12 payment-tr padding-side" id="payment-tr<?php echo $value['id'];?>" style="display: none;">

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

<div class="col-md-8 col-sm-12 col-xs-12">

<div class="col-md-12 col-sm-12 col-xs-12 box_div box_div_bg">


<div class="form-group col-md-12 col-sm-12 col-xs-12 " ><label><?php echo $this->get_label('amount');?> <span class="compulsory">*</span></label>
<div style="width: 118px;">
<?php if(Configuration::get_instance()->read('currency_position')==1){?>
<span class="dollar_style" ><?php echo Configuration::get_instance()->read('currency_symbol');?></span>
<?php }?>
<input class="form-control dollar_input"  onkeyup="this.value = this.value.replace(/[^0-9\.]/g,'');" type="text" name="bamount" id="bamount" value="<?php echo $bamount;?>" />

<?php if(Configuration::get_instance()->read('currency_position')!=1){?>
<span class="dollar_style"><?php echo Configuration::get_instance()->read('currency_symbol');?></span>
<?php }?>
</div>
<span id="loading2" style="display: none;"><img src="<?php echo BASE.'images/load.gif';?>" /></span>

</div>

<div class="row" id="tax-fee-2" ></div>

<div class="col-md-6 col-sm-6 col-xs-12">

<div class="form-group"><label><?php echo $this->get_label('account number');?> <span class="compulsory">*</span></label>
<input class="form-control" type="text" name="ac_number" id="ac_number" value="<?php echo $ac_number;?>" >
</div>

<div class="form-group"><label><?php echo $this->get_label('bank name');?> <span class="compulsory">*</span></label>
<input class="form-control" type="text" name="bb_name" id="bb_name" value="<?php echo $bb_name;?>" >
</div>

<div class="form-group"><label><?php echo $this->get_label('bank address line1');?> <span class="compulsory">*</span></label>
<input class="form-control" type="text" name="bb_add1" id="bb_add1" value="<?php echo $bb_add1;?>" >
</div>

<div class="form-group"><label><?php echo $this->get_label('bank address line2');?> <span class="compulsory">*</span></label>
<input class="form-control" type="text" name="bb_add2" id="bb_add2" value="<?php echo $bb_add2;?>" >
</div>
<div class="form-group"><label><?php echo $this->get_label('swift/routing no');?></label>
<input class="form-control" type="text" name="swift" id="swift" value="<?php echo $swift_no;?>" >
</div>

</div>
<div class="col-md-6 col-sm-6 col-xs-12">


<div class="form-group"><label><?php echo $this->get_label('city');?> <span class="compulsory">*</span></label>
<input class="form-control" type="text" name="bcity" id="bcity" value="<?php echo $bcity;?>" >
</div>

<div class="form-group"><label><?php echo $this->get_label('state');?> <span class="compulsory">*</span></label>
<input class="form-control" type="text" name="bstate" id="bstate" value="<?php echo $bstate;?>" >
</div>
<div class="form-group"><label></label></div>
<div class="form-group"><label></label></div>

<div class="form-group"><label><?php echo $this->get_label('country');?></label> : <?php echo $country_name;?>







</div>

<div class="form-group"><label><?php echo $this->get_label('account holders name');?> <span class="compulsory">*</span></label>
<input class="form-control" type="text" name="ba_name" id="ba_name" value="<?php echo $baccount_holder_name;?>" >
</div>






</div> 
<div class="form-group" style="clear: both;"><label class="col-md-5 col-sm-5 col-xs-12"></label>
<input type="hidden" name="ptype" id="ptype" value="2"/>
<input class="btn btn-primary btn-lg" type="submit" name="submit" value="<?php echo $this->get_label('add fund');?>">
</div>
</div>
</div>
<?php $form1->end(); ?>

<div class="col-md-4 col-sm-8 col-xs-12 special-label-new">

<div class="col-md-12 col-sm-12 col-xs-12 box_div box_div_bg">

<div class="form-group checkbox_head"><label><?php echo $this->get_label('bank details');?></label></div>


<div class="form-inline">
<div class="form-group"><label style="width: 135px;"><?php echo $this->get_label('bank name');?></label>
<label style="width: 20px;">:</label>
<label><?php echo Configuration::get_instance()->read('bank_name')?></label>
</div></div>


<div class="form-inline">
<div class="form-group"><label style="width: 135px;"><?php echo $this->get_label('beneficiary name');?></label>
<label style="width: 20px;">:</label>
<label><?php echo Configuration::get_instance()->read('name_for_fund_transfer')?></label>
</div></div>



<?php if(Configuration::get_instance()->read('swift_routing_number') !=""){?>
<div class="form-inline">
<div class="form-group"><label style="width: 135px;"><?php echo $this->get_label('swift/routing number');?></label>
<label style="width: 20px;">:</label>
<label><?php echo Configuration::get_instance()->read('swift_routing_number')?></label>
</div></div>
<?php }?>


<div class="form-inline">
<div class="form-group"><label style="width: 135px;"><?php echo $this->get_label('account number');?></label>
<label style="width: 20px;">:</label>
<label><?php echo Configuration::get_instance()->read('bank_account_number')?></label>
</div></div>


<?php if(Configuration::get_instance()->read('bank_account_type') !=""){?>
<div class="form-inline">
<div class="form-group"><label style="width: 135px;"><?php echo $this->get_label('account type');?></label>
<label style="width: 20px;">:</label>
<label><?php echo Configuration::get_instance()->read('bank_account_type')?></label>
</div></div>
<?php }?>

<div class="form-inline">
<div class="form-group"><label style="width: 135px;"><?php echo $this->get_label('bank address line1');?></label>
<label style="width: 20px;">:</label>
<label><?php echo Configuration::get_instance()->read('bank_address_line1')?></label>
</div></div>


<?php if(Configuration::get_instance()->read('bank_address_line2') !=""){?>
<div class="form-inline">
<div class="form-group"><label style="width: 135px;"><?php echo $this->get_label('bank address line2');?></label>
<label style="width: 20px;">:</label>
<label><?php echo Configuration::get_instance()->read('bank_address_line2')?></label>
</div></div>
<?php }?>


<div class="form-inline">
<div class="form-group"><label style="width: 135px;"><?php echo $this->get_label('bank state');?></label>
<label style="width: 20px;">:</label>
<label><?php echo Configuration::get_instance()->read('bank_state')?></label>
</div></div>


<div class="form-inline">
<div class="form-group"><label style="width: 135px;"><?php echo $this->get_label('bank country');?></label>
<label style="width: 20px;">:</label>
<label><?php echo Configuration::get_instance()->read('bank_country')?></label>
</div></div>


<div class="form-inline">
<div class="form-group"><label style="width: 135px;"><?php echo $this->get_label('bank city');?></label>
<label style="width: 20px;">:</label>
<label><?php echo Configuration::get_instance()->read('bank_city')?></label>
</div></div>



</div>
</div>
</div>
</div>
<?php }?>
<?php if($value['id'] ==3){?>

<div class="row">
<div class="col-md-12 col-sm-12 col-xs-12 payment-tr " id="payment-tr<?php echo $value['id'];?>" style="display: none;">



<div class="col-md-8 col-sm-12 col-xs-12 box_div box_div_bg home-box-outer">


<div class="form-group col-md-6 col-sm-12 col-xs-12 " ><label><?php echo $this->get_label('amount');?> <span class="compulsory">*</span></label>


<div style="width: 118px;">
<?php if(Configuration::get_instance()->read('currency_position')==1){?>
<span class="dollar_style" ><?php echo Configuration::get_instance()->read('currency_symbol');?></span>
<?php }?>
<input class="form-control dollar_input"  onkeyup="this.value = this.value.replace(/[^0-9\.]/g,'');" type="text" name="paamount" id="paamount" value="" />

<?php if(Configuration::get_instance()->read('currency_position')!=1){?>
<span class="dollar_style"><?php echo Configuration::get_instance()->read('currency_symbol');?></span>
<?php }?>
</div>
<span id="loading3" style="display: none;"><img src="<?php echo BASE.'images/load.gif';?>" /></span>

</div>

<div class="row" id="tax-fee-3" ></div>

<div class="col-md-6 col-sm-6 col-xs-12">

<div class="form-group col-md-12 col-sm-12 col-xs-12 padding-side ">
    <!--<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" name="Advertiser_Payments" id="Advertiser_Payments">-->
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post" name="Advertiser_Payments" id="Advertiser_Payments">
    <input type="hidden" name="cmd" value="_xclick">
	<input type="hidden" name="item_name" value="<?php echo Configuration::get_instance()->read('admarket_name')."-User Fund Deposit";?>">
	<input type="hidden" name="item_number" value="<?php echo $this->get_variable("uid").'_'.time();?>">
	<input type="hidden" name="amount" id="amount" value="">
	<input type="hidden" name="currency_code" value="<?php echo Configuration::get_instance()->read('system_currency');?>">
	<input type="hidden" name="notify_url" value="<?php echo $this->make_url('advertiser/paypal_ipn')?>">
	<input type="hidden" name="cancel_return" value="<?php echo $this->make_url('advertiser/paypal_cancel')?>">
	<input type="hidden" name="business" value="<?php echo Configuration::get_instance()->read('paypal_email');?>">
	<input type="hidden" name="no_shipping" value="1">
	<input type="hidden" name="no_note" value="0">
	<input type="hidden" name="custom" value="<?php echo $this->get_variable("uid");?>">
	<input type="hidden" name="rm" value="2">
	<input type="hidden" name="return" value="<?php echo $this->make_url('advertiser/paypal_success')?>">
	<button type="button" name="pay" class="button btn btn-primary btn-lg" onclick="javascript:return check_paypal_payment();"><?php echo $this->get_label('pay with paypal');?></button>
	</form>

</div>

</div> 

</div>

<div class="col-md-4 col-sm-8 col-xs-12 special-label-new">

<div class="col-md-12 col-sm-12 col-xs-12 box_div box_div_bg home-box-outer">

<div class="form-group checkbox_head"><label><?php echo $this->get_label('paypal details');?></label></div>


<div class="form-inline">
<div class="form-group"><label style="width: 135px;"><?php echo $this->get_label('paypal email');?></label>
<label style="width: 20px;">:</label>
<label><?php echo Configuration::get_instance()->read('paypal_email')?></label>
</div></div>

</div>
</div>
</div>
</div>

<?php }?>

<?php if($value['id'] >5){


if($value['name'] =='checkout')
$pathname='2co';
else
$pathname=$value['name'];


?>
<?php if($this->get_addon_status($pathname.'_enabled') ==1){?>
<div class="col-md-12 col-sm-12 col-xs-12 payment-tr padding-side" id="payment-tr<?php echo $value['id'];?>" style="display: none;">
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
select_payment($('input[name=payment]:checked').val());


$(document).ready(function () {

	payment_mode = <?php echo intval($payment_mode);?>;

	pamount = 0;

	if(payment_mode == 1)
	pamount	= $("#camount").val();
	else if(payment_mode == 2)
	pamount	= $("#bamount").val();
	else if(payment_mode == 3)
	pamount	= $("#paamount").val();	

	if(pamount > 0)
	fund_calculation(payment_mode,pamount);	
	
	
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
		fund_calculation(3,pamount);
	});
});


function fund_calculation(ptype,pamount)
{
	$("#loading"+ptype).show();
	
	country	= "<?php echo $country;?>";

	dataparam = "amount="+pamount+"&ptype="+ptype+"&country="+country+"";
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




</script>
