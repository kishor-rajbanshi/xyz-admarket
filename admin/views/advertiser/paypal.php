<?php
$this->dispatch("layout/header/17");

$validate=array(
		"transactionid"=>array(
				"notNull"=>array($this->get_message("not null"))
		),
		"email"=>array(
				"notNull"=>array($this->get_message("not null")),
				"isEmail"=>array($this->get_message("invalid email address"))
		)
);

$uid=$this->get_variable('uid');
$amount=$this->get_variable('amount');
$uname=$this->get_variable("uname");
$pamount=$this->get_variable('pamount');

$form=$this->create_form();
$form->start("paypalpayment",$this->make_url("advertiser/paypal/".$uid."/".$amount),"post",$validate); 
?>
<div class="sub_menu_main"><?php echo $this->get_label('paypal details');?></div>

<div class="inner-box">
<div class="pages-input">


<table style="width: 100%;" cellpadding="0" cellspacing="0">

<tr><td ></td><td ></td><td ><?php echo $this->get_label('compulsory message');?></td></tr>

<tr>
<td style="width: 200px;"><?php echo $this->get_label('username');?></td>
<td></td>
<td><a href="<?php echo $this->make_url("user/profile/".$uid."/0");?>"><?php echo $uname;?></a></td>
</tr>

<?php echo $this->fund_calculator(3,$pamount,$uid,1);?>

<tr>
<td><?php echo $this->get_label('transaction id');?></td>
<td></td>
<td><input type="text" name="transactionid" id="transactionid" value="<?php echo $this->read_post_param('transactionid');?>" ><span class="compulsory">*</span></td>
</tr>

<tr>
<td><?php echo $this->get_label('payer email');?></td><td></td>
<td><input type="text" name="email" id="email" value="<?php echo $this->read_post_param('email');?>" ><span class="compulsory">*</span></td>
</tr>

<tr>
<td></td>
<td></td>
<td  align="left">
<input type="hidden" name="uid" value="<?php echo $uid;?>" />
<input type="hidden" name="amount" value="<?php echo $pamount;?>" />
<input type="submit" name="submit" value="<?php echo $this->get_label('submit');?>" />
</td>
</tr>

</table>
</div>
</div>
<?php $form->end(); ?>
<?php $this->dispatch("layout/footer");?>