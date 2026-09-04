<?php
$this->dispatch("layout/header/7/2/b");

$usercountry=$this->get_variable('usercountry');

$validate=array(
		"amount"=>array(
				"notNull"=>array($this->get_message("not null")),
				"isPositive"=>array($this->get_message("positive value")),
				"isOverMin"=>array(Configuration::get_instance()->read('min_balance_for_publisher'),$this->get_message("publisher amount less"))
		)
);

?>






 <div class="container label_style special-label">


<?php 
$res1=$this->get_result('res1');
$value=$res1[0];
$preferred_mode=$value['preferred_mode'];
$referral_enabled=$this->get_variable("referral_enabled");
$adv_status=$this->get_variable("adv_status");
$pub_status=$this->get_variable("pub_status");
$from=intval($this->get_variable("from"));


?>

   <div class="col-md-12 col-sm-12 col-xs-12">
   <h2 class="page_heading"><?php echo $this->get_label('cash withdrawal request');?></h2>
<div class="page_heading-btm"></div>
 
   <div class="col-md-12 col-sm-12 col-xs-12 box_style">
   <div class="col-md-12 col-sm-12 col-xs-12">
   <div class="col-md-12 col-sm-12 col-xs-12 box_div box_div_bg" style="min-height: 200px;">

<div class="form-group col-md-12 col-sm-12 col-xs-12 padding-side" style="margin-top: 10px;margin-bottom: 0px;">

<?php 
$form=$this->create_form();
$form->start("cash_withdrawal",$this->make_url("user/cash_withdrawal"),"post",$validate); 


?>

<?php if($pub_status==1 || $referral_enabled ==1){?>
<div class="col-md-12 col-sm-12 col-xs-12">
<label class="col-md-4 col-sm-4 col-xs-12" style="padding-left: 0px;"><?php echo $this->get_label('withdrawal from');?></label>
<label class="col-md-6 col-sm-6 col-xs-12" style="padding-left: 0px;line-height: 22px;">

<select name="from" id="from" class="form-control" style="max-width: 140px;">
<?php if($pub_status ==1){?>
<option value="0" <?php if($from ==0){?>selected="selected"<?php }?> ><?php echo $this->get_label('publisher balance');?></option>
<?php }?>

<?php if($referral_enabled ==1){?>
<option value="1" <?php if($from ==1){?>selected="selected"<?php }?>><?php echo $this->get_label('referral balance');?></option>
<?php }?>
</select>

</label>
</div>
<?php }?>

<div class="col-md-12 col-sm-12 col-xs-12">
<label class="col-md-4 col-sm-4 col-xs-12" style="padding-left: 0px;"><?php echo $this->get_label('withdrawal amount');?> <span class="compulsory">*</span></label>
<label class="col-md-6 col-sm-6 col-xs-12" style="padding-left: 0px;line-height: 22px;">


<div style="width: 118px;">
<?php if(Configuration::get_instance()->read('currency_position')==1){?>
<span class="dollar_style" ><?php echo Configuration::get_instance()->read('currency_symbol');?></span>
<?php }?>
<input class="form-control dollar_input" type="text" onkeyup="this.value = this.value.replace(/[^0-9\.]/g,'');"  name="amount" id="amount" value="<?php echo $this->get_variable('amount');?>" >

<?php if(Configuration::get_instance()->read('currency_position')!=1){?>
<span class="dollar_style"><?php echo Configuration::get_instance()->read('currency_symbol');?></span>
<?php }?>
</div>

</label>
</div>
<div class="col-md-12 col-sm-12 col-xs-12" style="height:20px;"></div>


<div id="tax-fee-<?php echo $preferred_mode; ?>"></div>

<div class="col-md-12 col-sm-12 col-xs-12">
<label class="col-md-4 col-sm-4 col-xs-12" style="padding-left: 0px;"></label>
<label class="col-md-8 col-sm-8 col-xs-12" style="padding-left: 0px;line-height: 22px;">
<div class="form-group" style="margin-bottom: 2px;">
<input class="btn btn-primary btn-lg" type="submit" name="submit" value="<?php echo $this->get_label('submit');?>" style="margin-top: 2px;" />

<div class="notification"><bdi>[<?php echo $this->get_label('publisher minimum withdrawal amount',array('x'=>$this->get_money_format(Configuration::get_instance()->read('min_balance_for_publisher'))));?>]<bdi></div>

</div>
</label>
</div>

<?php $form->end(); ?>

</div>

</div></div></div></div>



   <div class="col-md-12 col-sm-12 col-xs-12">
   
   
   
   <h2 class="page_heading"><?php echo $this->get_label('your withdrawal details');?></h2>
   
   <div class="page_heading-btm"></div>
   
  <div class="col-md-12 col-sm-12 col-xs-12 box_style">
   <div class="col-md-12 col-sm-12 col-xs-12">
   <div class="col-md-12 col-sm-12 col-xs-12 box_div box_div_bg" style="min-height: 200px;">




<div class="form-inline">
<div class="form-group"><label style="width: 145px;"><?php echo $this->get_label('withdrawal mode');?></label>
<label style="width: 20px;">:</label>
<label><?php echo $this->get_withdrawal_mode($value['preferred_mode']); ?></label>
</div></div>


<?php if($preferred_mode==1){?>

<div class="form-inline">
<div class="form-group"><label style="width: 145px;"><?php echo $this->get_label('payee name');?></label>
<label style="width: 20px;">:</label>
<label><?php echo $value['check_payee_name']; ?></label>
</div></div>



<div class="form-inline">
<div class="form-group"><label style="width: 145px;"><?php echo $this->get_label('payee address line1');?></label>
<label style="width: 20px;">:</label>
<label><?php echo $value['payee_address_line1']; ?></label>
</div></div>



<div class="form-inline">
<div class="form-group"><label style="width: 145px;"><?php echo $this->get_label('payee address line2');?></label>
<label style="width: 20px;">:</label>
<label><?php echo $value['payee_address_line2']; ?></label>
</div></div>




<div class="form-inline">
<div class="form-group"><label style="width: 145px;"><?php echo $this->get_label('city');?></label>
<label style="width: 20px;">:</label>
<label><?php echo $value['payee_city']; ?></label>
</div></div>



<div class="form-inline">
<div class="form-group"><label style="width: 145px;"><?php echo $this->get_label('state');?></label>
<label style="width: 20px;">:</label>
<label><?php echo $value['payee_state']; ?></label>
</div></div>



<div class="form-inline">
<div class="form-group"><label style="width: 145px;"><?php echo $this->get_label('country');?></label>
<label style="width: 20px;">:</label>
<label><?php echo $this->get_country_name($value['payee_country']); ?></label>
</div></div>


<?php } 
else if($preferred_mode==2) { ?>

<div class="form-inline">
<div class="form-group"><label style="width: 145px;"><?php echo $this->get_label('account number');?></label>
<label style="width: 20px;">:</label>
<label><?php echo $value['account_number']; ?></label>
</div></div>



<div class="form-inline">
<div class="form-group"><label style="width: 145px;"><?php echo $this->get_label('payee name');?></label>
<label style="width: 20px;">:</label>
<label><?php echo $value['bank_payee_name']; ?></label>
</div></div>



<div class="form-inline">
<div class="form-group"><label style="width: 145px;"><?php echo $this->get_label('bank address line1');?></label>
<label style="width: 20px;">:</label>
<label><?php echo $value['bank_address_line1']; ?></label>
</div></div>



<div class="form-inline">
<div class="form-group"><label style="width: 145px;"><?php echo $this->get_label('bank address line2');?></label>
<label style="width: 20px;">:</label>
<label><?php echo $value['bank_address_line2']; ?></label>
</div></div>



<div class="form-inline">
<div class="form-group"><label style="width: 145px;"><?php echo $this->get_label('city');?></label>
<label style="width: 20px;">:</label>
<label><?php echo $value['bank_city']; ?></label>
</div></div>



<div class="form-inline">
<div class="form-group"><label style="width: 145px;"><?php echo $this->get_label('state');?></label>
<label style="width: 20px;">:</label>
<label><?php echo $value['bank_state']; ?></label>
</div></div>



<div class="form-inline">
<div class="form-group"><label style="width: 145px;"><?php echo $this->get_label('country');?></label>
<label style="width: 20px;">:</label>
<label><?php echo $this->get_country_name($value['bank_country']); ?></label>
</div></div>



<div class="form-inline">
<div class="form-group"><label style="width: 145px;"><?php echo $this->get_label('swift/routing no');?></label>
<label style="width: 20px;">:</label>
<label><?php echo $value['swift_number']; ?></label>
</div></div>

<?php 
}
else if($preferred_mode==3)
{
?>

<div class="form-inline">
<div class="form-group"><label style="width: 145px;"><?php echo $this->get_label('paypal email');?></label>
<label style="width: 20px;">:</label>
<label><?php echo $value['paypal_email']; ?></label>
</div></div>



<?php 
}
else if($preferred_mode >5)
{
	$colalready=$this->get_result('colalready');
	
	foreach($colalready as $key=>$row123)
	{
		$carray=explode('_',$row123['Field']);
		if($carray[0] == $preferred_mode)
		{
			?>
<div class="form-inline">
<div class="form-group"><label style="width: 145px;"><?php echo $row123['Comment'];?></label>
<label style="width: 20px;">:</label>
<label><?php echo $value[$row123['Field']];?></label>
</div></div>

			<?php 
		}
	}
	
}?>




<div class="form-inline">
<div class="form-group"><label style="width: 145px;"></label>
<label style="width: 20px;"></label>
<label>
<a class="btn btn-primary btn-lg" href="<?php echo $this->make_url("user/withdrawal_configuration");?>"><?php echo $this->get_label('change');?></a></label>
</div></div></div>
</div></div>


</div></div>
<?php $this->dispatch("layout/footer");?>

<script type="text/javascript">
$(document).ready(function () 
{
	withdrawal_calculation();
	
	$("#amount").keyup(function ()
	{
		withdrawal_calculation();
	});
});


function withdrawal_calculation()
{
	pamount=$("#amount").val();

	min_balance=<?php echo Configuration::get_instance()->read('min_balance_for_publisher');?>;

	if(pamount > min_balance && pamount >0)
	{
		dataparam = "amount="+pamount+"&ptype=<?php echo $preferred_mode;?>&country=<?php echo $usercountry;?>";
		$.ajax({
						type: "POST",
						data: dataparam,
						url: "<?php echo $this->make_url("user/withdrawal_calculation")?>",
						success: function(msg)
						{
							$("#tax-fee-<?php echo $preferred_mode; ?>").html(msg);
						}
		      });
	}
}
</script>