<?php 
$this->dispatch("layout/header/21");
$payment=$this->get_variable('payment');
$uid=$this->get_variable('uid');
$uname=$this->get_variable("uname");
$sid=$this->get_variable("sid");
?>

<div class="sub_menu_main"><?php echo $this->get_label('advertiser payment details');?></div>

<?php $this->dispatch("links/links/23/".$uid."/".$sid);?>


<div class="inner-box">
<div class="detail-pages">


<table  style="width: 100%;" cellpadding="0" cellspacing="0">
<tr>
<td style="width: 150px;"><?php echo $this->get_label('payment type');?></td><td style="width: 20px;">:</td>
<td>
<?php echo $this->get_payment_mode($payment);?>

&nbsp;&nbsp;
<a target="_parent" href="<?php echo $this->make_url("advertiser/refund/".$sid);?>"><i class="fa fa-arrow-circle-o-right refund-icon" title="<?php echo $this->get_label('refund');?>"></i></a>

</td>
</tr>

<tr>
<td width="150px"><?php echo $this->get_label('username');?></td><td>:</td>
<td>
<?php 
if($this->get_user_name($uid)=="")
echo $this->get_label("deleted");
else {?>
<a href="<?php echo $this->make_url("user/profile/".$uid."/0");?>"><?php echo $uname;?></a>
<?php }?>
</td>
</tr>

<?php 
if($payment >5)
{
	$pname=$this->get_payment_mode_name($payment);
	
	if($pname =='checkout')
	$pathname='2co';
	else
	$pathname=$pname;
	
	if($pname =='gourl-bitcoin')
            $pname ='bitcoin';
	$this->dispatch($pname."/payment_main_details/".$sid,ADDON_DIR_PATH.$pathname.'/');
}
else 
{

$res=$this->get_result('res');
$val=$res[0];
?>


<tr>
<td><?php echo $this->get_label('total amount');?></td>
<td>:</td>
<td><?php  if($payment==3) {echo $this->get_money_format($val['samount']+$val['fee']+$val['tax']);}else { echo $this->get_money_format($val['amount']+$val['fee']+$val['tax']);}?></td>
</tr>


<?php if($val['fee'] > 0){?>
<tr>
<td><?php echo $this->get_label('fee');?></td><td>:</td>
<td><?php echo $this->get_money_format($val['fee']);?></td>
</tr>
<?php }?>

<?php if($val['tax'] > 0){?>
<tr>
<td><?php echo $this->get_label('tax'); if(Configuration::get_instance()->read('tax_calculation')==1){echo $this->get_label('inclusive');}?></td><td>:</td>
<td>

<div class="tax-details-page" style="margin-left: 0px;"><?php echo $this->get_money_format($val['tax']);?></div>

<?php  
$json_array=json_decode($val['tax_details'],1);

foreach($json_array as $jkey=>$jvalue)
{
	echo '<div class="notification tax-details-page">['.$jvalue[1].'% '.$jvalue[0].'&nbsp; - &nbsp;'.$this->get_money_format($jvalue[2]).']</div>';
}
?>
</td>
</tr>
<?php }?>


<?php if($val['fee'] > 0 || $val['tax'] > 0){?>
<tr>
<td><?php echo $this->get_label('amount credited');?></td><td>:</td>
<td><?php  if($payment==3) {echo $this->get_money_format($val['samount']);}else { echo $this->get_money_format($val['amount']);}?></td>
</tr>
<?php }?>

<tr>
<td><?php echo $this->get_label('received date');?></td><td>:</td>
<td>

<?php

$t=$val['received_date'];
if($t==0)
$time1= $this->get_label("na");
else
$time1=$this->get_date_format(2,$t);

echo $time1;
?>
</td>
</tr>

<?php if($payment!=4)
{

?>
<tr>
<td><?php echo $this->get_label('status');?></td><td>:</td>
<td><?php echo $this->get_payment_status($val['status']);?>&nbsp;&nbsp;

<?php 
if($payment==1 || $payment==2)
{
if($val['status']==-1)
{
	?>

  <a href="<?php echo $this->make_url("advertiser/payment_approve/").$val['sid']."/2";?>"><i class="fa fa-thumbs-o-up approve-icon" title="<?php echo $this->get_label('approve');?>"></i></a>
  <a href="<?php echo $this->make_url("advertiser/payment_reject/").$val['sid']."/2";?>"><i class="fa fa-thumbs-o-down reject-icon" title="<?php echo $this->get_label('reject');?>"></i></a>

	
<?php }}}?>
</td>
</tr>

<?php if($payment==1){?>
<tr>
<td><?php echo $this->get_label('check number');?></td><td>:</td>
<td><?php echo $val['check_number'];?></td>
</tr>	
<?php }else if($payment==2){?>
<tr>
<td><?php echo $this->get_label('account number');?></td><td>:</td>
<td><?php echo $val['account_number'];?></td>
</tr>	
<?php 
}
?>
<?php if($payment==1 || $payment==2){?>
<tr>
<td><?php echo $this->get_label('bank name');?></td><td>:</td>
<td><?php echo $val['bank_name'];?></td>
</tr>	

<tr>
<td><?php echo $this->get_label('bank address line1');?></td><td>:</td>
<td><?php echo $val['address1'];?></td>
</tr>	

<tr>
<td><?php echo $this->get_label('bank address line2');?></td><td>:</td>
<td><?php echo $val['address2'];?></td>
</tr>	

<tr>
<td><?php echo $this->get_label('bank city');?></td><td>:</td>
<td><?php echo $val['city'];?></td>
</tr>	

<tr>
<td><?php echo $this->get_label('state');?></td><td>:</td>
<td><?php echo $val['state'];?></td>
</tr>	

<tr>
<td><?php echo $this->get_label('country');?></td>
<td>:</td>
<td><?php echo $this->get_country_name($val['country']);?></td>
</tr>	
		
<?php 
}
?>
<?php if($payment!=5 && $payment!=3)
{
?>

<tr>
<td><?php echo $this->get_label('comment');?></td>
<td>:</td>
<td><?php 
if($val['comment'] =="")
echo $this->get_label('na');
else
echo $val['comment'];?></td>
</tr>

<?php 
}
?>

<?php 
if($payment==3)
{
?>

<tr>
<td><?php echo $this->get_label('transaction id');?></td>
<td>:</td>
<td><?php echo $val['txnid'];?></td>
</tr>


<?php if(isset($val['subscriptionid']) && $val['subscriptionid'] !=''){?>
<tr>
<td><?php echo $this->get_label('transaction type');?></td>
<td >:</td>
<td><?php echo $this->get_label('paypal subscription');?></td>
</tr>

<tr>
<td><?php echo $this->get_label('subscription id');?></td>
<td >:</td>
<td><?php echo $val['subscriptionid'];?></td>
</tr>
<?php }?>


<tr>
<td><?php echo $this->get_label('payer email');?></td>
<td>:</td>
<td><?php echo $val['payeremail'];?></td>
</tr>

<tr>
<td><?php echo $this->get_label('item number');?></td>
<td>:</td>
<td><?php if($val['itemnumber']=="") echo $this->get_label('na'); else echo $val['itemnumber'];?></td>
</tr>
<?php }}?>
</table>
</div>
</div>
<?php $this->dispatch("layout/footer");?>