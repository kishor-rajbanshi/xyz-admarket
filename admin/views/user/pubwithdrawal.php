<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link rel="stylesheet" href="<?php echo BASE.ADMIN_DIR."/css/style.css";?>" type="text/css" />
<link rel="stylesheet" href="<?php echo COMMON_DIR_PATH;?>font-awesome/css/font-awesome.css" />
</head>
<body style="background: none;">
<?php 
$pub_status=$this->get_variable('pub_status');
if($pub_status!=2)
{
	$uid=$this->get_variable('uid');
	


?>
<table  class="iframe_table" cellpadding="0" cellspacing="0" border="0"  >

<tr><td height="10px"></td></tr>




<?php 
$form=$this->create_form();
$form->start("payment_history",$this->make_url("user/pubwithdrawal/".$uid),"post"); 

$referral_enabled=$this->get_variable("referral_enabled");
$payment_type=$this->get_variable("payment_type");

$payment				= $this->get_variable("payment");
$status					= $this->get_variable("status");
$pg						= $this->get_variable('pg');


$invoice_download		= Configuration::get_instance()->read('invoice_download');
$apply_tax_rules		= Configuration::get_instance()->read('apply_tax_rules');
$fee_flag				= $this->get_withdrawal_fee_exists($payment);
?>
<tr>
<td colspan="7">
 <div class="search_div">
<table class="search_div_table">



<tr>
<td style="width: 160px;">
<select name="payment" id="payment" style="width: 150px;">
<option value="0" <?php if($payment=="0"){ echo "selected"; } ?>><?php echo $this->get_label('withdrawal mode');?></option>
<?php echo $this->get_withdrawal_list($payment);?>
</select>
</td>	
<td style="width: 160px;">
<select name="status" id="status" style="width: 150px;">
	<option value="4" <?php if($status=="4"){ echo "selected"; } ?>><?php echo $this->get_label('allstatus');?></option>
	<option value="1" <?php if($status=="1"){ echo "selected"; } ?> ><?php echo $this->get_label('approved');?></option>
	<option value="-1" <?php if($status=="-1"){ echo "selected"; } ?> ><?php echo $this->get_label('pending');?></option>
	<option value="0" <?php if($status=="0"){ echo "selected"; } ?> ><?php echo $this->get_label('rejected');?></option>
</select>
</td>

<?php if($referral_enabled ==1){?>
<td style="width: 160px;">
<select name="payment_type" id="payment_type" style="width: 150px;">
<option value="2" <?php if($payment_type ==2){?>selected="selected"<?php }?> ><?php echo $this->get_label('from all balance');?></option>

<option value="0" <?php if($payment_type ==0){?>selected="selected"<?php }?> ><?php echo $this->get_label('publisher balance');?></option>

<?php if($referral_enabled ==1){?>
<option value="1" <?php if($payment_type ==1){?>selected="selected"<?php }?>><?php echo $this->get_label('referral balance');?></option>
<?php }?>
</select>
</td>
<?php }else{?>
<input type="hidden" name="payment_type" id="payment_type" value="<?php echo $payment_type;?>" />
<?php }?>

<td>
<input type="hidden" name="uid" value="<?php echo $uid;?>" />
<input type="submit" name="select" class="link_button" value="<?php echo $this->get_label('go');?>">
</td>
</tr>
</table>
</div>
<?php $form->end(); ?>


</td>
</tr>

<tr><td colspan="7" height="10px"></td></tr>

<tr><td colspan="7">
<table style="width: 99%;margin: 4px;" cellpadding="0" cellspacing="0" border="0" class="data_table">
		
<tr class="row_heading_tr">
<?php if($fee_flag ==1 || $apply_tax_rules == 1){?>
<td><?php echo $this->get_label('total');?></td>
<?php }?>

<?php if($fee_flag ==1){?>
<td><?php echo $this->get_label('fee amount');?></td>
<?php }?>

<?php if($apply_tax_rules == 1){?>
<td><?php echo $this->get_label('tax');?></td>
<?php }?>

<td><?php echo $this->get_label('amount receivable');?></td>


<td><?php echo $this->get_label('withdrawal mode');?></td>
<td><?php echo $this->get_label('withdrawal type');?></td>
<td><?php echo $this->get_label('request date');?></td>
<td><?php echo $this->get_label('process date');?></td>
<td><?php echo $this->get_label('status');?></td>
<td><?php echo $this->get_label('options');?></td>
</tr>

<tr><td colspan="10"></td></tr>


<?php 
$res=$this->get_result('res3');

if(count($res)==0)
{?>
<tr><td colspan="10" height="30px">&nbsp;<?php echo $this->get_label('no records found');?></td></tr>
<?php 
}?>


<?php 


foreach($res as $key=>$value)
{
	$ad_id=$value['id'];
	$req_time=$value['request_time'];
	
	$time1=$this->get_date_format(3,$req_time);
	
	
	$t=$value['process_time'];
	if($t==0)
	$time2= $this->get_label("na");
	else
	$time2=$this->get_date_format(3,$t);
	
	?>
<tr class="row_data_tr">

<?php if($fee_flag ==1 || $apply_tax_rules == 1){?>
<td ><?php echo $this->get_money_format($value['amount']+$value['fee']+$value['tax']);?></td>
<?php }?>

<?php if($fee_flag ==1){?>
<td ><?php echo $this->get_money_format($value['fee']);?></td>
<?php }?>

<?php if($apply_tax_rules == 1){?>
<td ><?php echo $this->get_money_format($value['tax']);?></td>
<?php }?>


<td ><?php echo $this->get_money_format($value['amount']);?></td>

<td ><?php echo $this->get_withdrawal_mode($value['payment_mode']);?></td>
<td ><?php
if($value['withdrawal_type'] ==1)
echo $this->get_label('referral balance');
else
echo $this->get_label('publisher balance');
?></td>
<td ><?php echo $time1;?></td>
<td><?php echo $time2;?></td>

<td ><?php echo $this->get_payment_status($value['status']);?></td>
<td >
<a target="_parent" href="<?php echo $this->make_url("user/withdrawal_details/".$value['id']);?>"><i class="fa fa-bar-chart report-icon" title="<?php echo $this->get_label('details');?>"></i></a>

<?php if($value['status']==-1){?>
 <a target="_parent" href="<?php echo $this->make_url("user/approve_withdrawal/".$value['id']."/1/".$payment."/".$status."/".$payment_type."/".$pg);?>"><i class="fa fa-thumbs-o-up approve-icon" title="<?php echo $this->get_label('approve');?>"></i></a>
 <a target="_parent" href="<?php echo $this->make_url("user/reject_withdrawal/".$value['id']."/1/".$payment."/".$status."/".$payment_type."/".$pg);?>"><i class="fa fa-thumbs-o-down reject-icon" title="<?php echo $this->get_label('reject');?>"></i></a>
<?php }?>
<?php 
if($value['status']==1 && $invoice_download==1){?>
<a target="_parent" href="<?php echo $this->make_url("user/invoice_export/".$value['id']."/".$uid);?>"><i class="fa fa-file-pdf-o pdf-icon" title="<?php echo $this->get_label('pdf download');?>"></i></a>
<?php }?>

</td>
</tr>
<?php }?>		
<tr><td colspan="10" align="center"><?php echo $this->get_variable('pagination2');?></td></tr>

</table>
</td></tr>	
</table>

<?php }?>
</body>
</html>