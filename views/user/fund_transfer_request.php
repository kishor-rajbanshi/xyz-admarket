<?php
$this->dispatch("layout/header/7/4/b");
$validate=array(
		"amount"=>array(
				"notNull"=>array($this->get_message("not null")),
				"isPositive"=>array($this->get_message("positive value")),
				"isOverMin"=>array(Configuration::get_instance()->read('min_balance_for_publisher'),$this->get_message("publisher amount less"))
		)
);

?> 


<div class="container"><h2 class="page_heading"><?php echo $this->get_label('transfer fund to adv account');?></h2>
<div class="page_heading-btm"></div>
</div>

  <div class="container label_style special-label">
  <div class="col-md-12 col-sm-12 col-xs-12 box_style new_box_inner">
   <div class="col-md-12 col-sm-12 col-xs-12">
   <div class="col-md-12 col-sm-12 col-xs-12 box_div box_div_bg">


<?php 

$referral_enabled=$this->get_variable("referral_enabled");
$pub_status=$this->get_variable("pub_status");
$from=$this->get_variable("from");


$form=$this->create_form();
$form->start("cash_withdrawal",$this->make_url("user/fund_transfer_request"),"post",$validate); 

?>


<?php if($pub_status==1 || $referral_enabled ==1){?>
<div class="form-group col-md-12 col-sm-12 col-xs-12 padding-side" style="margin-top: 10px;">
<label class="col-md-2 col-sm-2 col-xs-12" ><?php echo $this->get_label('withdrawal from');?></label>
<div class="col-md-4 col-sm-5 col-xs-12">

<select name="from" id="from" class="form-control" style="max-width: 140px;">
<?php if($pub_status ==1){?>
<option value="0" <?php if($from ==0){?>selected="selected"<?php }?> ><?php echo $this->get_label('publisher balance');?></option>
<?php }?>

<?php if($referral_enabled ==1){?>
<option value="1" <?php if($from ==1){?>selected="selected"<?php }?>><?php echo $this->get_label('referral balance');?></option>
<?php }?>
</select>

</div>
<div class="col-md-6 col-sm-5 col-xs-12"></div>
</div>
<?php }?>



<div class="form-group col-md-12 col-sm-12 col-xs-12 padding-side" style="margin-top: 10px;">
<label class="col-md-2 col-sm-2 col-xs-12"><?php echo $this->get_label('amount');?> <span class="compulsory">*</span></label>
<div class="col-md-4 col-sm-5 col-xs-12">

<div class="transfer-div">
<?php if(Configuration::get_instance()->read('currency_position')==1){?>
<span class="dollar_style" ><?php echo Configuration::get_instance()->read('currency_symbol');?></span>
<?php }?>
<input class="form-control dollar_input" type="text" name="amount" id="amount" value="<?php echo $this->get_variable('amount');?>" />

<?php if(Configuration::get_instance()->read('currency_position')!=1){?>
<span class="dollar_style"><?php echo Configuration::get_instance()->read('currency_symbol');?></span>
<?php }?>
</div>


<div class="notification notification-dir"><bdi>[<?php echo $this->get_label('publisher minimum withdrawal amount',array('x'=>$this->get_money_format(Configuration::get_instance()->read('min_balance_for_publisher'))));?>]</bdi></div>
</div>
<div class="col-md-6 col-sm-5 col-xs-12"></div>
</div>


<div class="form-group col-md-12 col-sm-12 col-xs-12">
<label class="col-md-2 col-sm-2 col-xs-12"></label>
<div class="col-md-3 col-sm-5 col-xs-12">
<input class="btn btn-primary btn-lg" type="submit" name="submit" value="<?php echo $this->get_label('submit');?>" /></div>
<div class="col-md-7 col-sm-5 col-xs-12"></div>
</div>


<?php $form->end(); ?>
</div></div></div></div>



<?php $this->dispatch("layout/footer");?>