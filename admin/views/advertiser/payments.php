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

$this->dispatch("layout/header/21");

$apply_tax_rules		= Configuration::get_instance()->read('apply_tax_rules');



$form=$this->create_form();
$form->start("payment_history",$this->make_url("advertiser/payments"),"post"); 



$payment=$this->get_variable("payment");
$status=$this->get_variable("status");
$uname=$this->get_variable('adv');
$pg=$this->get_variable("pg");
$invoice_download=Configuration::get_instance()->read('invoice_download');

$fee_flag=$this->get_fee_exists($payment);

?>
<div class="sub_menu_main"><?php echo $this->get_label('manage advertiser payment history');?></div>

<table style="width: 100%;" cellpadding="0" cellspacing="0" >

<tr><td colspan="6" style="height: 10px;"></td></tr>


<tr><td colspan="6">

<div class="search_div">
<table class="search_div_table">
<tr>

<td style="width: 160px;">
<select name="payment" id="payment" onchange="javascript:change();" style="width: 150px;">
<option value="0" <?php if($payment =="0"){ echo "selected"; } ?>><?php echo $this->get_label('payment mode');?></option>
<?php echo $this->get_payment_list($payment);?>
</select></td>
<td style="width: 160px;">
<div id="status1" >
<select name="status" id="status" style="width: 150px;">
	<option value="4" <?php if($status=="4"){ echo "selected"; } ?>><?php echo $this->get_label('allstatus');?></option>
	<option value="1" <?php if($status=="1"){ echo "selected"; } ?> ><?php echo $this->get_label('approved');?></option>
	<option value="-1" <?php if($status=="-1"){ echo "selected"; } ?> ><?php echo $this->get_label('pending');?></option>
	<option value="0" <?php if($status=="0"){ echo "selected"; } ?> ><?php echo $this->get_label('rejected');?></option>
</select>
</div>
</td>
<td style="width: 160px;">
  

 <span id="namedv">  
    <input type="text" name="username" id="username" style="height: 32px;" list="user-datalist" autocomplete="off"  value="<?php echo $uname;?>" placeholder="<?php echo $this->get_label('username');?>" />
     <datalist id="user-datalist"></datalist>

    </span>
</td>
<td>&nbsp;<input type="submit" name="select" value="<?php echo $this->get_label('go');?>">


</td>
</tr>
</table>
</div>

</td></tr>


<tr><td colspan="6" height="10px"></td></tr>
</table>

<?php $form->end(); ?>

<table class="data_table" cellpadding="0" cellspacing="0">
<tr class="row_heading_tr">
<td><?php echo $this->get_label('advertiser');?></td>
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
<td style="width: 120px;"><?php echo $this->get_label('options');?></td>
</tr>

<?php 
$res2=$this->get_result('res2');
if(count($res2)==0)
{?>
<tr><td colspan="9" height="30px">&nbsp;<?php echo $this->get_label('no records found');?></td></tr>
<?php
}
else 
{

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
	
?>
<tr class="row_data_tr">
<td >
<?php 
if($this->get_user_name($uid)=="")
{
    echo $this->get_label("deleted");
}
else
{?>
	<a href="<?php echo $this->make_url("user/profile/".$uid."/2");?>"><?php echo $this->escape($this->get_user_name($uid));?></a>
<?php 
}


$total=$value['amount'];

if($fee_flag ==1)
$total=$total+$value['fee'];

if($apply_tax_rules == 1)
$total=$total+$value['tax'];

?>
</td>
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

<?php 
if($value['payment_type'] !=4)
{?>
<td class="<?php if($value['status'] ==0){ ?>blk<?php }
    else if($value['status'] ==-1){ ?>pend<?php }
	else if($value['status'] ==1){ ?>active<?php }?>"><?php echo $this->get_payment_status($value['status']);?>
	
	
<?php if($refunded_amount > 0){?>
<div><?php echo $this->get_label('refunded');?></div>
<?php } ?>

	</td>
<?php 
}
else 
{
?>
<td ><?php echo $this->get_label('na');?>

<?php if($refunded_amount > 0){?>
<div><?php echo $this->get_label('refunded');?></div>
<?php } ?>

</td>

<?php 
}	?>

<td >

<?php 
//For exclude zaincash pending payment
if($value['payment_type'] != 21 || ($value['payment_type'] == 21 && $value['status'] != -1)){?>


<?php 
if($value['payment_type']==4)
{?>
<a  href="<?php echo $this->make_url("advertiser/payment_details/".$payid);?>"><i class="fa fa-bar-chart report-icon" title="<?php echo $this->get_label('details');?>"></i></a>

<?php 
}else{?>



<a  href="<?php echo $this->make_url("advertiser/payment_details/".$payid); ?>"><i class="fa fa-bar-chart report-icon" title="<?php echo $this->get_label('details');?>"></i></a>
<?php if($value['status']==-1)
{
?>
 <a  href="<?php echo $this->make_url("advertiser/payment_approve/".$payid."/0/".$payment."/".$status."/".$adv."/".$pg);?>"><i class="fa fa-thumbs-o-up approve-icon" title="<?php echo $this->get_label('approve');?>"></i></a>

<?php 
if($value['payment_type']!=12)
{?>
 <a  href="<?php echo $this->make_url("advertiser/payment_reject/".$payid."/0/".$payment."/".$status."/".$adv."/".$pg);?>"><i class="fa fa-thumbs-o-down reject-icon" title="<?php echo $this->get_label('reject');?>"></i></a>
<?php 
}}?>


<?php }?>


<?php if($value['payment_type'] !=5 && $value['status']==1 ){?>

<a target="_parent" href="<?php echo $this->make_url("advertiser/refund/".$payid);?>"><i class="fa fa-arrow-circle-o-right refund-icon" title="<?php echo $this->get_label('refund');?>"></i></a>
<?php 
}
if($value['status']==1 && $invoice_download ==1){?>
<a href="<?php echo $this->make_url("advertiser/invoice_export/".$value['id']."/".$value['uid']);?>"><i class="fa fa-file-pdf-o pdf-icon" title="<?php echo $this->get_label('pdf download');?>"></i></a>
<?php }?>


<?php 
}else{
echo $this->get_label('na');
}
?>







</td></tr>

<?php }}?>		

<tr><td  colspan="9" align="center"><?php echo $this->get_variable('pagination1');?></td></tr>

</table>

<?php $this->dispatch("layout/footer");?>
<script type="text/javascript">
change();

$(document).ready(function(){

	$("#username").keyup(function(){
		var uname=document.getElementById('username').value;
		var type=1;//alert(type);
		uname=uname.trim();
		var url= "<?php echo $this->make_url('index/get_suggestion_result');?>";
		if(uname.length>3)
			get_suggestion_result("users","username",uname,url,type);

	});

	
});

</script>