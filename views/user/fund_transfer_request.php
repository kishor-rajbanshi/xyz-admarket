<?php
$this->dispatch("layout/header/7/4/b");
$validate=array(
		"amount"=>array(
				"notNull"=>array($this->get_message("not null")),
				"isPositive"=>array($this->get_message("positive value")),
				"isOverMin"=>array(Configuration::get_instance()->read('min_balance_for_publisher'),$this->get_message("publisher amount less"))
		)
);

$currencyPosition = Configuration::get_instance()->read('currency_position');
$currencySymbol   = Configuration::get_instance()->read('currency_symbol');

$referral_enabled = $this->get_variable("referral_enabled");
$pub_status       = $this->get_variable("pub_status");
$from             = $this->get_variable("from");
$uid              = $this->get_variable("uid");
?>


<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 p-3 mb-3 fund-transfer">
	<h2 class="page-heading "><div class="page-inner"><i class="fa fa-exchange icon_red"></i><?php echo $this->get_label('transfer fund to adv account');?></div></h2>
	<div class="row">
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<?php
			$form=$this->create_form();
			$form->start("cash_withdrawal",$this->make_url("user/fund_transfer_request"),"post",$validate);
			?>

			<?php if($pub_status == 1){?>
			<div class="col-md-12 col-sm-12 col-xs-12 mb-3">
				<label class="form-label">
					<bdi><?php echo $this->get_label('account balance')." : ".$this->get_pub_account_balance($uid);?></bdi>
				</label>
			</div>
			<?php }?>

			<?php if($referral_enabled ==1){?>
			<div class="col-md-12 col-sm-12 col-xs-12 mb-3">
				<label class="form-label">
					<bdi><?php echo $this->get_label('referral balance')." : ".$this->get_referral_balance($uid);?></bdi>
				</label>
			</div>
			<?php }?>


			<?php	if($pub_status == 1 || $referral_enabled == 1){?>
					<div class="col-md-12 col-sm-12 col-xs-12 mb-3">
						<label class="form-label">
				    	<?php echo $this->get_label('withdrawal from');?>
				    </label>
		        <select name="from" id="from" class="form-select">
			        <?php if($pub_status ==1){?>
			        <option value="0" <?php if($from ==0){?>selected="selected"<?php }?> ><?php echo $this->get_label('publisher balance');?></option>
			        <?php }?>

			        <?php if($referral_enabled ==1){?>
			        <option value="1" <?php if($from ==1){?>selected="selected"<?php }?>><?php echo $this->get_label('referral balance');?></option>
			        <?php }?>
				    </select>
				  </div>
				<?php }?>

				<div class="col-md-12 col-sm-12 col-xs-12 mb-3">
					<label class="form-label">
				    	<?php echo $this->get_label('amount');?> <span class="compulsory">*</span>
				  </label>

	        <div class="input-group <?php if($currencyPosition == 2){?>flex-row-reverse<?php }?> mb-3">

					<div class="input-group-text dollar_style"><?php echo $currencySymbol;?></div>

	        <input class="form-control" type="text" name="amount" id="amount" value="<?php echo $this->get_variable('amount');?>" />


	        </div>

				  <div class="notification"><bdi>[<?php echo $this->get_label('publisher minimum withdrawal amount',array('x'=>$this->get_money_format(Configuration::get_instance()->read('min_balance_for_publisher'))));?>]</bdi></div>
				</div>

				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<input class="submit-button" type="submit" name="submit" value="<?php echo $this->get_label('submit');?>" />
				</div>
			<?php $form->end(); ?>
		</div>
	</div>
</div>
<?php $this->dispatch("layout/footer");?>
