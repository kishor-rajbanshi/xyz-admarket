<?php 
$this->dispatch("layout/header/21");

$payid=$this->get_variable('payid');
$uid=$this->get_variable('uid');
$payamount=$this->get_variable('total');
$paytype=$this->get_variable('paytype');
$refunded_amount=$this->get_variable('refunded_amount');

$acc_balance=$this->get_variable('acc_balance');
$bonus_balance=$this->get_variable('bonus_balance');
$refund_from=$this->get_variable('refund_from');



$amount=$this->get_variable('amount');
$comment=$this->get_variable('comment');

$data=$this->get_result('data');
$data_count=count($data);

$validate=array(
		"amount"=>array(
				"notNull"=>array($this->get_message("not null"))

		),
		"comment"=>array(
				"notNull"=>array($this->get_message("not null"))
		
		)
);
?>

<div class="sub_menu_main"><?php echo $this->get_label('manage refund');?></div>

<?php $this->dispatch("links/links/23/".$uid."/".$payid);?>

<div class="inner-box">
<div class="pages-input">





<?php if($refunded_amount < $payamount){?>

<?php 
$form=$this->create_form();
$form->start("form_refund","","post",$validate);

?>

<table style="width: 100%;" cellpadding="0" cellspacing="0">
<tr>
<td style="width: 120px;"><?php echo $this->get_label('payment type');?></td>
<td style="width: 30px;">:</td>
<td>

<a href="<?php echo $this->make_url("advertiser/payment_details/".$payid); ?>"><?php echo $this->get_payment_mode($paytype);?></a>



</td>
</tr>

<tr>
<td ><?php echo $this->get_label('username');?></td><td>:</td>
<td><a href="<?php echo $this->make_url("user/profile/".$uid."/0");?>"><?php echo $this->get_user_name($uid);?></a></td>
</tr>


<tr>
<td ><?php echo $this->get_label('account balance');?></td>
<td>:</td>
<td><?php echo $this->get_money_format($acc_balance);?></td>
</tr>

<tr>
<td ><?php echo $this->get_label('bonus balance');?></td>
<td>:</td>
<td><?php echo $this->get_money_format($bonus_balance);?></td>
</tr>


<?php if($paytype ==4){?>
<tr>
<td ><?php echo $this->get_label('refund from');?></td>
<td>:</td>
<td>

<select name="refund_from" id="refund_from">
<option value="1" <?php if($refund_from ==1){?>selected<?php }?>><?php echo $this->get_label('bonus balance');?></option>
<option value="0" <?php if($refund_from ==0){?>selected<?php }?>><?php echo $this->get_label('account balance');?></option>
</select>
</td>
</tr>
<?php }?>

<tr>
<td ><?php echo $this->get_label('payment amount');?></td>
<td>:</td>
<td><?php echo $this->get_money_format($payamount);?></td>
</tr>


<?php if($refunded_amount >0){?>
<tr>
<td ><?php echo $this->get_label('already refunded');?></td>
<td>:</td>
<td><?php echo $this->get_money_format($refunded_amount);?></td>
</tr>
<?php }?>

<tr>
<td ><?php echo $this->get_label('refund amount');?></td>
<td>:</td>
<td>
<input type="text" name="amount" id="amount" value="<?php echo $amount;?>" size="5" onkeyup="this.value=this.value.replace(/[^0-9\.]/g, '');" />
<?php echo Configuration::get_instance()->read('currency_symbol');?>
<span class="compulsory"> *</span>
</td>
</tr>

<tr>
<td><?php echo $this->get_label('comment');?></td>
<td>:</td>
<td>

<textarea rows="5" cols="30" name="comment" id="comment" ><?php echo $comment;?></textarea>
<span class="compulsory"> *</span>

</td>
</tr>

<tr>
<td></td>
<td></td>
<td>


<input type="hidden" name="payid" id="payid" value="<?php echo $payid;?>" />
<input type="submit" name="rsubmit" id="rsubmit" value="<?php echo $this->get_label('submit');?>" />

</td>
</tr>
</table>

<?php $form->end(); ?>

<br/> 
<?php }?>



<?php if($data_count >0){?>

<div class="heading-underline"><?php echo $this->get_label('refund list');?></div>




<table  style="width: 100%;" class="data_table" cellpadding="0" cellspacing="0">

<tr class="row_heading_tr">
<td style="width: 250px;"><?php echo $this->get_label('amount');?></td>
<td style="width: 450px;"><?php echo $this->get_label('comment');?></td>
<td><?php echo $this->get_label('time');?></td>
</tr>

<?php foreach($data as $key=>$value){	?>
<tr class="row_data_tr">
<td><?php echo $this->get_money_format($value['amount']);?></td>

<td class="ad-popup">
<?php echo substr($value['description'],0,50);?>

<div class="ad-popup-div"><div><?php echo nl2br($value['description']);?></div></div>

</td>
<td><?php echo $this->get_date_format(2,$value['time']);?></td>
</tr>

<?php }?>
</table>
<?php }?>


</div>
</div>
<?php $this->dispatch("layout/footer");?>