<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link rel="stylesheet" href="<?php echo BASE.ADMIN_DIR."/css/style.css";?>" type="text/css" /></head>
<link rel="stylesheet" href="<?php echo COMMON_DIR_PATH;?>font-awesome/css/font-awesome.css" />


<body style="background: none;">

<?php 
$adv_status=$this->get_variable('adv_status');

if($adv_status!=2)
{?>	
<script type="text/javascript">
function change()
{
if(document.getElementById('payment').value==4)
document.getElementById('status1').style.display='none';
else
document.getElementById('status1').style.display='';
}
</script>
<?php

$uid=$this->get_variable('uid');

$apply_tax_rules		= Configuration::get_instance()->read('apply_tax_rules');



$form=$this->create_form();
$form->start("payment_history",$this->make_url("user/advpayment/".$uid),"post"); 



$payment=$this->get_variable("payment");
$status=$this->get_variable("status");
$pg=$this->get_variable('pg');

$invoice_download=Configuration::get_instance()->read('invoice_download');

$fee_flag=$this->get_fee_exists($payment);

?>

<table class="iframe_table">

<tr><td  height="20px"></td></tr>


<tr><td>


 <div class="search_div">
<table class="search_div_table">



<tr>
<td style="width: 160px;">
<select name="payment" id="payment" onchange="javascript:change();" style="width: 150px;">
<option value="0" <?php if($payment=="0"){ echo "selected"; } ?>><?php echo $this->get_label('payment mode');?></option>
<?php echo $this->get_payment_list($payment);?>
</select></td>
<td style="width: 160px;">
<div id="status1">
<select name="status" id="status" style="width: 150px;">
	<option value="4" <?php if($status=="4"){ echo "selected"; } ?>><?php echo $this->get_label('allstatus');?></option>
	<option value="1" <?php if($status=="1"){ echo "selected"; } ?> ><?php echo $this->get_label('approved');?></option>
	<option value="-1" <?php if($status=="-1"){ echo "selected"; } ?> ><?php echo $this->get_label('pending');?></option>
	<option value="0" <?php if($status=="0"){ echo "selected"; } ?> ><?php echo $this->get_label('rejected');?></option>
</select>
</div>
</td>

<td>
<input type="hidden" name="uid" value="<?php echo $uid;?>" />
<input type="submit" name="select"  class="link_button" value="<?php echo $this->get_label('go');?>">
</td>
</tr>
</table>
</div>
<?php $form->end(); ?>


<br>


<table  style="width: 99%;margin: 4px;" cellpadding="0" cellspacing="0" border="0" class="data_table">

<tr class="row_heading_tr">
<td><?php echo $this->get_label('amount credited');?></td>
<?php if($fee_flag ==1){?>
<td><?php echo $this->get_label('fee');?></td>
<?php }?>

<?php if($apply_tax_rules == 1){?>
<td><?php echo $this->get_label('tax');?></td>
<?php }?>

<?php if($fee_flag ==1 || $apply_tax_rules == 1){?>
<td><?php echo $this->get_label('total');?></td>
<?php }?>
<td><?php echo $this->get_label('payment mode');?></td>
<td><?php echo $this->get_label('received date');?></td>
<td><?php echo $this->get_label('status');?></td>
<td><?php echo $this->get_label('options');?></td>
</tr>

<?php 
$res2=$this->get_result('res2');

if(count($res2)==0)
{?>
<tr><td colspan="8" height="30px">&nbsp;<?php echo $this->get_label('no records found');?></td></tr>
<?php
}


foreach($res2 as $key=>$value)
{
$t=$value['received_date'];


if($t==0)
$time1= $this->get_label("NA");
else
$time1=$this->get_date_format(2,$t);


	$uid=$value['uid'];	
	$payid=$value['id'];	
	
	$refunded_amount=$this->get_refund_amount($payid,$uid);
	
	
$total=$value['amount'];

if($fee_flag ==1)
$total=$total+$value['fee'];

if($apply_tax_rules == 1)
$total=$total+$value['tax'];	
	
?>
<tr class="row_data_tr">
<td ><?php echo $this->get_money_format($value['amount'])?></td>

<?php if($fee_flag ==1){?>
<td ><?php echo $this->get_money_format($value['fee'])?></td>
<?php }?>

<?php if($apply_tax_rules == 1){?>
<td ><?php echo $this->get_money_format($value['tax'])?></td>
<?php }?>

<?php if($fee_flag ==1 || $apply_tax_rules == 1){?>
<td ><?php echo $this->get_money_format($total)?></td>
<?php }?>
<td ><?php echo $this->get_payment_mode($value['payment_type'],1);?></td>
<td ><?php echo $time1;?></td>

<?php if($value['payment_type']!=4){?>
<td >
<?php echo $this->get_payment_status($value['status']);?>

<?php if($refunded_amount > 0){?>
<div><?php echo $this->get_label('refunded');?></div>
<?php } ?>


</td>
<?php }else {?>
<td >
<?php echo $this->get_label('na');?>

<?php if($refunded_amount > 0){?>
<div><?php echo $this->get_label('refunded');?></div>
<?php } ?>
</td>
<?php }	?>
<td>


<?php 
//For exclude zaincash pending payment
if($value['payment_type'] != 21 || ($value['payment_type'] == 21 && $value['status'] != -1)){?>


<?php if($value['payment_type']==4){?>
<a target="_parent" href="<?php echo $this->make_url("advertiser/payment_details/".$payid);?>"><i class="fa fa-bar-chart report-icon" title="<?php echo $this->get_label('details');?>"></i></a>
<?php }else{?>
<a target="_parent" href="<?php echo $this->make_url("advertiser/payment_details/".$payid); ?>"><i class="fa fa-bar-chart report-icon" title="<?php echo $this->get_label('details');?>"></i></a>
<?php if($value['status']==-1){?>

<a target="_parent" href="<?php echo $this->make_url("advertiser/payment_approve/".$payid."/1/".$payment."/".$status."/".$pg);?>"><i class="fa fa-thumbs-o-up approve-icon" title="<?php echo $this->get_label('approve');?>"></i></a>

<?php if($value['payment_type'] !=12){?>
 <a target="_parent" href="<?php echo $this->make_url("advertiser/payment_reject/".$payid."/1/".$payment."/".$status."/".$pg);?>"><i class="fa fa-thumbs-o-down reject-icon" title="<?php echo $this->get_label('reject');?>"></i></a>
<?php }}?>

<?php }?>
<?php if($value['payment_type'] !=5 && $value['status']==1){?>
<a target="_parent" href="<?php echo $this->make_url("advertiser/refund/".$payid);?>"><i class="fa fa-arrow-circle-o-right refund-icon" title="<?php echo $this->get_label('refund');?>"></i></a>
<?php }?>

<?php if($value['status']==1 && $invoice_download ==1){?>
<a target="_parent" href="<?php echo $this->make_url("advertiser/invoice_export/".$payid."/".$uid);?>"><i class="fa fa-file-pdf-o pdf-icon" title="<?php echo $this->get_label('pdf download');?>"></i></a>
<?php }?>

<?php 
}else{
echo $this->get_label('na');
}
?>





</td></tr>

<?php }?>		
<tr><td colspan="8" align="center"><?php echo $this->get_variable('pagination1');?></td></tr>
</table>
</td></tr></table>
<?php }?>
<script type="text/javascript">
change();
</script>
</body>
</html>