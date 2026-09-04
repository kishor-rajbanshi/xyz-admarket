<script language="JavaScript"> 
	function check_2co_payment()
	{
		   amount=$('#2coamount').val();
		   minamount=<?php echo Configuration::get_instance()->read('min_amount_for_advertiser');?>;
           
		   if(amount =="" || amount ==0)
		   {
			  alert("<?php echo $this->get_message("mandatory");?>");
			  $('#2coamount').focus();
			  return false;
		   }
		   else if(amount < minamount)
		   {
			  alert("<?php echo $this->get_message("advertiser amount less");?>");
			  $('#2coamount').focus();
			  return false;
		   }
		   else
		   {
		       ptotal=$('#total10').val();
			   $('#li_0_price').val(ptotal);
		   	   document.Advertiser_Payments1.submit();
		   }
	} 
</script>

<?php 
$country=$this->get_user_country($this->get_variable("uid"));
?>

<div class="col-md-12 col-sm-12 col-xs-12 box_div box_div_bg">
<div class="form-group col-md-12 col-sm-12 col-xs-12 padding-side" ><label><?php echo $this->get_label('amount');?> <span class="compulsory">*</span></label>


<div style="width: 118px;">
<?php if(Configuration::get_instance()->read('currency_position')==1){?>
<span class="dollar_style"><?php echo Configuration::get_instance()->read('currency_symbol');?></span>
<?php }?>
<input class="form-control dollar_input" type="text" onkeyup="this.value = this.value.replace(/[^0-9\.]/g,'');" name="2coamount" id="2coamount" value="" />

<?php if(Configuration::get_instance()->read('currency_position')!=1){?>
<span class="dollar_style"><?php echo Configuration::get_instance()->read('currency_symbol');?></span>
<?php }?>
</div>
<span id="loading10" style="display: none;"><img src="<?php echo BASE.'images/load.gif';?>" /></span>

</div>

<div class="row" id="tax-fee-10" ></div>


<div class="form-group">
<!--<form name="Advertiser_Payments1" id="Advertiser_Payments1" action="https://sandbox.2checkout.com/checkout/purchase" method="post">-->
<form name="Advertiser_Payments1" id="Advertiser_Payments1" action="https://www.2checkout.com/checkout/purchase" method="post">
<input name="sid" value="<?php echo Configuration::get_instance()->read('2co_sid');?>" type="hidden">
<input name="mode" value="2CO" type="hidden">
<input name="li_0_price" id="li_0_price" value="" type="hidden">
<input name="li_0_name" value="<?php echo Configuration::get_instance()->read('admarket_name')."-User Fund Deposit";?>" type="hidden">
<input name="li_0_type" value="product" type="hidden">
<input name="li_0_quantity" value="1" type="hidden">
<input name="li_0_tangible" value="N" type="hidden">
<input name="currency_code" value="<?php echo Configuration::get_instance()->read('system_currency');?>" type="hidden">
<input name="li_0_product_id" id="li_0_product_id" value="<?php echo $this->get_variable("uid");?>" type="hidden">
<input name="x_receipt_link_url" value="<?php echo $this->make_base_url('checkout/checkout_result',ADDON_DIR.'/2co');?>" type="hidden">
<button type="button" name="pay" class="button btn btn-primary btn-lg" onclick="javascript:return check_2co_payment();"><?php echo $this->get_label('pay with 2co');?></button>
</form>	
	
</div>  

</div> 
<script type="text/javascript">
$(document).ready(function ()
{
	amount=$("#2coamount").val();

	if(amount > 0)
	fund_calculation(10,amount);	

	$("#2coamount").keyup(function () 
	{
		amount=$("#2coamount").val();
	
		if(amount > 0)
		fund_calculation(10,amount);	
	});
});
</script>