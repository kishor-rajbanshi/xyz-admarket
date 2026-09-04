<?php $this->dispatch("layout/header/22");
$res=$this->get_result('res');
$val=$res[0];

$mode=$this->get_variable("mode");




if($mode==5)
$pa_id=$val['id'];
else
$pa_id=$val['sid'];


?>

<div class="sub_menu_main"><?php echo $this->get_label('withdrawal details');?></div>


<?php $this->dispatch("links/links/24/".$val['uid']."/".$pa_id);?>


<div class="inner-box">
<div class="detail-pages">

<table  style="width: 100%;" cellpadding="0" cellspacing="0">
<tr>
<td width="150px"><?php echo $this->get_label('payment mode');?></td><td>:</td>
<td><?php echo $this->get_withdrawal_mode($val['payment_mode']);?></td>
</tr>


<tr>
<td width="150px"><?php echo $this->get_label('username');?></td><td>:</td>
<td>
<?php
if($this->get_user_name($val['uid'])=="")
{
echo $this->get_label("deleted");
}
else
{?>
<a href="<?php echo $this->make_url("user/profile/".$val['uid']."/0");?>"><?php echo $this->get_variable("usname");?></a>
<?php
}
?>
</td>
</tr>


<?php if($mode==3)
{
?>
	<tr>
	<td><?php echo $this->get_label('paypal email');?></td><td>:</td>
	<td><?php echo $val['paypal_email'];?></td>
	</tr>


	<?php if($val['status']==1)
	{
	?>
	<tr>
	<td><?php echo $this->get_label('paypal transaction id');?></td><td>:</td>
	<td><?php echo $val['paypal_transaction_id'];?></td>
	</tr>
	<?php
	}
}
?>
<?php if($mode==1 || $mode==2)
{
?>
<tr>
<td><?php echo $this->get_label('payee name');?></td><td>:</td>
<td><?php echo $val['payee_name'];?></td>
</tr>
<?php
}
?>


<?php if($val['fee'] > 0 || $val['tax'] > 0){?>
<tr>
<td><?php echo $this->get_label('withdrawal amount');?></td><td style="width: 20px;">:</td>
<td><?php echo $this->get_money_format($val['amount']+$val['fee']+$val['tax']);?></td>
</tr>
<?php }?>

<?php if($val['fee'] > 0){?>
<tr>
<td><?php echo $this->get_label('fee');?></td><td style="width: 20px;">:</td>
<td><?php echo $this->get_money_format($val['fee']);?></td>
</tr>
<?php }?>

<?php if($val['tax'] > 0){?>
<tr>
<td><?php echo $this->get_label('tax');?></td><td style="width: 20px;">:</td>
<td>

<div class="tax-details-page" style="margin-left: 0px;margin-top: 0px;"><?php echo $this->get_money_format($val['tax']);?></div>


<?php
$json_array=json_decode($val['tax_details'],1);

foreach($json_array as $jkey=>$jvalue)
{
	echo '<div class="notification tax-details-page" style="margin-top: 0px;"><bdi>['.$jvalue[1].'% '.$jvalue[0].'&nbsp; - &nbsp;'.$this->get_money_format($jvalue[2]).']</bdi></div>';
}
?>

</td>
</tr>
<?php }?>


<tr>
<td><?php echo $this->get_label('amount receivable');?></td><td style="width: 20px;">:</td>
<td><?php echo $this->get_money_format($val['amount']);?></td>
</tr>

<?php
if($mode==1)
{
?>
<tr>
<td><?php echo $this->get_label('payee address line1');?></td><td>:</td>
<td><?php echo $val['address_line1'];?></td>
</tr>

<tr>
<td><?php echo $this->get_label('payee address line2');?></td><td>:</td>
<td><?php echo $val['address_line2'];?></td>
</tr>


<?php }?>

<?php if($mode==2){?>
<?php if($val['status']==1){?>
<tr>
<td><?php echo $this->get_label('bank transaction id');?></td><td>:</td>
<td><?php echo $val['bank_transaction_id'];?></td>
</tr>
<?php }?>


<tr>
<td><?php echo $this->get_label('bank name');?></td><td>:</td>
<td><?php echo $val['bank_name'];?></td>
</tr>


<tr>
<td><?php echo $this->get_label('account number');?></td><td>:</td>
<td><?php echo $val['account_number'];?></td>
</tr>

<tr>
<td><?php echo $this->get_label('swift/routing number');?></td><td>:</td>
<td><?php echo $val['swift_number'];?></td>
</tr>


<tr>
<td><?php echo $this->get_label('bank address line1');?></td><td>:</td>
<td><?php echo $val['address_line1'];?></td>
</tr>

<tr>
<td><?php echo $this->get_label('bank address line2');?></td><td>:</td>
<td><?php echo $val['address_line2'];?></td>
</tr>


<?php }?>
<?php if($mode==1 || $mode==2) {?>
<tr>
<td><?php echo $this->get_label('city');?></td><td>:</td>
<td><?php echo $val['city'];?></td>
<tr>

<tr>
<td><?php echo $this->get_label('state');?></td><td>:</td>
<td><?php echo $val['state'];?></td>
</tr>

<tr>
<td><?php echo $this->get_label('country');?></td><td>:</td>
<td><?php echo $this->get_country_name($val['country']);?></td>
</tr>

<?php
}
?>



<?php
if($mode >5)
{
	$colalready=$this->get_result('colalready');

	foreach($colalready as $key=>$row123)
	{
		$carray=explode('_',$row123['Field']);
		if($carray[0] == $mode)
		{

			if($carray[0].'_'.$carray[1].'_' == $mode.'_detail_')
			{
				if($val['status'] ==1)
				{
					?>

			<tr>
			<td><?php echo $row123['Comment'];?></td>
			<td width="10px">:</td>
			<td><?php if($val[$row123['Field']] !=''){echo $val[$row123['Field']];}else{echo $this->get_label('na');}?></td>
			</tr>
<?php } }else{?>


			<tr>
			<td><?php echo $row123['Comment'];?></td>
			<td width="10px">:</td>
			<td><?php if($val[$row123['Field']] !=''){echo $val[$row123['Field']];}else{echo $this->get_label('na');}?></td>
			</tr>

	<?php }}
	}?>

<?php
}
?>


<?php

$time1=$this->get_date_format(2,$val['request_time']);


if($val['process_time']==0)
$time2= $this->get_label("na");
else
$time2=$this->get_date_format(2,$val['process_time']);





?>


<tr>
<td><?php echo $this->get_label('request date');?></td><td>:</td>
<td><?php echo $time1;?></td>
</tr>

<tr>
<td><?php echo $this->get_label('process date');?></td><td>:</td>
<td><?php echo $time2;?></td>
</tr>


<?php if($mode !=5 && $val['comments'] !=''){?>
<tr>
<td><?php echo $this->get_label('comment');?></td><td>:</td>
<td><?php echo $val['comments'];?></td>
</tr>
<?php }?>


<tr>
<td><?php echo $this->get_label('status');?></td><td>:</td>
<td><?php echo $this->get_payment_status($val['status']);?>&nbsp;&nbsp;


<?php if($val['status']==-1 && $mode !=5){?>
   <a href="<?php echo $this->make_url("user/approve_withdrawal/").$val['sid']."/2";?>"><i class="fa fa-thumbs-o-up approve-icon" title="<?php echo $this->get_label('approve');?>"></i></a>
   <a href="<?php echo $this->make_url("user/reject_withdrawal/").$val['sid']."/2";?>"><i class="fa fa-thumbs-o-down reject-icon" title="<?php echo $this->get_label('reject');?>"></i></a>
<?php }?>


<?php if($val['status']==-1 && $mode ==5){?>
   <a href="<?php echo $this->make_url("user/approve_withdrawal/").$val['id']."/2";?>"><i class="fa fa-thumbs-o-up approve-icon" title="<?php echo $this->get_label('approve');?>"></i></a>
   <a href="<?php echo $this->make_url("user/reject_withdrawal/").$val['id']."/2";?>"><i class="fa fa-thumbs-o-down reject-icon" title="<?php echo $this->get_label('reject');?>"></i></a>
<?php }?>

</td>
</tr>
</table>
</div>
</div>
<?php $this->dispatch("layout/footer");?>
