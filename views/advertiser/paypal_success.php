<?php $this->dispatch("layout/header/3/1/a");?>
<div class="container"><h2 class="page_heading"><?php echo $this->get_label('paypal success');?></h2>
<div class="page_heading-btm"></div>
</div>

  <div class="container label_style">
   <div class="col-md-12 col-sm-12 col-xs-12 box_style">
   <div class="col-md-12 col-sm-12 col-xs-12 box_div">


<table style="width: 100%;" cellpadding="0" cellspacing="0">

<tr>
<td colspan="3" class="head-div"><label><?php echo $this->get_label('payment details');?></label></td>
</tr>

<tr><td colspan="3" height="10px"></td></tr>

<tr>
<td width="150px"><?php echo $this->get_label('payment status');?></td>
<td width="20px">:</td>
<td><?php echo $this->get_variable('payment_status');?></td>
</tr>

<tr><td colspan="3" height="10px"></td></tr>

<tr>
<td><?php echo $this->get_label('payment amount');?></td>
<td>:</td>
<td><?php echo $this->get_money_format($this->get_variable('payment_amount'));?></td>
</tr>

<tr><td colspan="3" height="10px"></td></tr>


<tr>
<td><?php echo $this->get_label('payment currency');?></td>
<td>:</td>
<td><?php echo $this->get_variable('payment_currency');?></td>
</tr>

<tr><td colspan="3" height="10px"></td></tr>


<tr>
<td><?php echo $this->get_label('transaction id');?></td>
<td>:</td>
<td><?php echo $this->get_variable('txn_id');?></td>
</tr>

<tr><td colspan="3" height="10px"></td></tr>

<?php if($this->get_variable('payer_email')!=''){?>
<tr>
<td><?php echo $this->get_label('payer email');?></td>
<td>:</td>
<td style="word-break: break-all !important;"><?php echo $this->get_variable('payer_email');?></td>
</tr>

<tr><td colspan="3" height="10px"></td></tr>
<?php }?>

<?php if($this->get_variable('receiver_email')!=''){?>
<tr>
<td><?php echo $this->get_label('receiver email');?></td>
<td>:</td>
<td style="word-break: break-all !important;"><?php echo $this->get_variable('receiver_email');?></td>
</tr>

<tr><td colspan="3" height="10px"></td></tr>
<?php }?>

<?php 
if($this->get_variable('payment_status')!="Completed")
{
?>
<tr>
<td><?php echo $this->get_label('pending reason');?></td>
<td>:</td>
<td><?php echo $this->get_variable('pending_reason');?></td>
</tr>
<?php 
}
?>
</table>

</div></div></div>
<?php $this->dispatch("layout/footer");?>