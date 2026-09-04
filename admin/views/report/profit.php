<?php 
$this->dispatch("layout/header/7/_73");

$bonus=$this->get_variable("bonus");
$withdrawed=$this->get_variable("withdrawed");
$account=$this->get_variable("account");


	$refunded_amount=$this->get_variable('refunded_amount');
	$refunded_bonus=$this->get_variable('refunded_bonus');
	
	$bonus=$bonus-$refunded_bonus;
	
	if($bonus < 0)
	$bonus=0;


$referral_enabled=$this->get_variable("referral_enabled");
$referralwithdrawed=$this->get_variable("referralwithdrawed");
$referral_balance=$this->get_variable("referral_balance");
?>

<div class="sub_menu_main"><?php echo $this->get_label('profit statistics');?></div>

<?php $this->dispatch("links/links/25");?>

<div class="inner-box">
<div class="detail-pages">
<table style="width: 100%;"  cellpadding="0" cellspacing="0">
<?php 
$datatransfer=$this->get_result('datatransfer');

?>

<?php if(count($datatransfer) >0){?>
<tr>
<td colspan="3"><strong><?php echo $this->get_label('money from advertisers');?></strong></td>
</tr>
<?php }?>
<?php 
$sumamount=0;
foreach($datatransfer as $k123=>$v123)
{
	?>
	<tr>
	<td style="width: 350px;"><?php echo $this->get_label('money from transfer',array('x'=>$this->get_payment_mode($v123['payment_type'])));?></td>
	<td style="width: 10px;">:</td>
	<td ><?php echo $this->get_money_format($v123['amounts']);?></td>
	</tr>
<?php 	

$sumamount=$sumamount+$v123['amounts'];

}
?>

<?php if(count($datatransfer) >0){?>
<tr>
<td></td>
<td></td>
<td><hr></hr></td>
</tr>
<?php }?>

<tr>
<td><span style="font-size: 12px;font-weight: bold;"><?php echo $this->get_label('total money from advertisers');?></span></td>
<td>:</td>
<td><?php echo $total1=$this->get_money_format($sumamount);?></td>
</tr>


<?php if($refunded_amount >0){?>
<tr>
<td><span style="font-size: 13px;font-weight: bold;"><?php echo $this->get_label('total money refund');?></span></td>
<td>:</td>
<td><?php echo $this->get_money_format($refunded_amount);?></td>
</tr>
<?php }?>


<tr>
<td colspan="3"><strong><?php echo $this->get_label('bonus spend');?></strong></td>
</tr>

<tr>
<td style="width: 350px;"><span style="font-size: 13px;font-weight: bold;"><?php echo $this->get_label('total bonus amount');?></span>

<br/> 
<span class="notification">[<?php echo $this->get_label('total bonus amount');?>-<?php echo $this->get_label('total bonus refund');?>]</span>


</td>
<td style="width: 10px;">:</td>
<td ><?php echo $this->get_money_format($bonus);?></td>
</tr>

<tr>
<td colspan="3"><strong><?php echo $this->get_label('money to publishers');?></strong></td>
</tr>

<tr>
<td><?php echo $this->get_label('publishers withdrawal amount');?></td>
<td>:</td>
<td><?php echo $this->get_money_format($withdrawed);?></td>
</tr>



<tr>
<td><?php echo $this->get_label('publishers account balance');?></td>
<td>:</td>
<td><?php echo $this->get_money_format($account);?></td>
</tr>


<tr>
<td></td>
<td></td>
<td><hr></hr></td>
</tr>


<tr>
<td><span style="font-size: 13px;font-weight: bold;"><?php echo $this->get_label('total money to publishers');?></span></td>
<td>:</td>
<td><?php echo $total2=$this->get_money_format($withdrawed+$account);?></td>
</tr>


<?php
$referralstring="";
if($referral_enabled ==1){

	$referralstring='+'.$this->get_label('total referral money to users');
	?>

<tr>
<td colspan="3"><strong><?php echo $this->get_label('referral money to users');?></strong></td>
</tr>

<tr>
<td><?php echo $this->get_label('referral withdrawal amount');?></td>
<td>:</td>
<td><?php echo $this->get_money_format($referralwithdrawed,0);?></td>
</tr>

<tr>
<td><?php echo $this->get_label('users referral account balance');?></td>
<td>:</td>
<td><?php echo $this->get_money_format($referral_balance,0);?></td>
</tr>

<tr>
<td></td>
<td></td>
<td><hr></hr></td>
</tr>


<tr>
<td><span style="font-size: 13px;font-weight: bold;"><?php echo $this->get_label('total referral money to users');?></span></td>
<td>:</td>
<td><?php echo $total2=$this->get_money_format($referralwithdrawed+$referral_balance,0);?></td>
</tr>
<?php }?>

<tr>
<td><span style="font-size: 13px;font-weight: bold;"><?php echo $this->get_label('cash balance');?></span><br>

<?php if($refunded_amount >0){?>
<span class="notification">[<?php echo $this->get_label('total money from advertisers');?>-(<?php echo $this->get_label('total money refund');?>+<?php echo $this->get_label('total bonus amount');?>+<?php echo $this->get_label('total money to publishers').$referralstring;?>)]</span>
<?php }else{?>
<span class="notification">[<?php echo $this->get_label('total money from advertisers');?>-(<?php echo $this->get_label('total bonus amount');?>+<?php echo $this->get_label('total money to publishers').$referralstring;?>)]</span>
<?php }?>

</td>
<td>:</td>
<td>

<?php
$totad=$sumamount;
$totpub=$withdrawed+$account;
$totreferral=$referralwithdrawed+$referral_balance;

echo $this->get_money_format($totad-($bonus+$totpub+$refunded_amount+$totreferral));?>
</td>

</tr>
</table>
</div>
</div>
<?php $this->dispatch("layout/footer");?>