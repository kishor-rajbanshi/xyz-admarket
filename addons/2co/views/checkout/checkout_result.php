<?php $this->dispatch("layout/header/3/1/a",PATH_TO_ROOT);?>
<?php if($_POST){?>
<div class="container"><h2 class="page_heading"><?php echo $this->get_label('2co payment report');?></h2></div>

  <div class="container label_style">
   <div class="col-md-12 col-sm-12 col-xs-12 box_style">
   <div class="col-md-12 col-sm-12 col-xs-12 box_div">


<table style="width: 100%;" cellpadding="0" cellspacing="0">

<tr>
<td colspan="3" class="head-div"><label><?php echo $this->get_label('payment details');?></label></td>
</tr>

<tr><td colspan="3" height="10px"></td></tr>


<tr><td><?php echo $this->get_label('payment name');?></td>
<td >:</td>
<td><?php echo $this->get_variable('payment_name');?></td>
</tr>

<tr><td colspan="3" height="10px"></td></tr>



<tr><td><?php echo $this->get_label('payment status');?></td>
<td >:</td>
<td><?php if($this->get_variable('credit_card_processed') =='Y'){echo $this->get_label('approved');}else{echo $this->get_label('not approved');}?></td>
</tr>

<tr><td colspan="3" height="10px"></td></tr>


<tr><td style="width: 150px;"><?php echo $this->get_label('payment amount');?></td>
<td style="width: 20px;">:</td>
<td><?php echo $this->get_variable('payment_amount');?></td>
</tr>

<tr><td colspan="3" height="10px"></td></tr>

<tr><td><?php echo $this->get_label('payment currency');?></td>
<td >:</td>
<td><?php echo $this->get_variable('payment_currency');?></td>
</tr>

<tr><td colspan="3" height="10px"></td></tr>

<tr><td><?php echo $this->get_label('payment method');?></td>
<td >:</td>
<td><?php echo $this->get_variable('pay_method');?></td>
</tr>

<tr><td colspan="3" height="10px"></td></tr>

<tr><td><?php echo $this->get_label('2co order id');?></td>
<td >:</td>
<td><?php echo $this->get_variable('order_number2co');?></td>
</tr>

<tr><td colspan="3" height="10px"></td></tr>

<tr><td><?php echo $this->get_label('2co invoice id');?></td>
<td >:</td>
<td><?php echo $this->get_variable('invoice_id');?></td>
</tr>

<tr><td colspan="3" height="10px"></td></tr>

<tr><td><?php echo $this->get_label('payer email');?></td>
<td >:</td>
<td style="word-break: break-all !important;"><?php echo $this->get_variable('payer_email');?></td>
</tr>

</table> 
</div></div></div>
<?php }?>
<?php $this->dispatch("layout/footer",PATH_TO_ROOT);?>