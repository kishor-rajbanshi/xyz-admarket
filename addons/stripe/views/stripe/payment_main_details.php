<?php 
$res=$this->get_result('res');
$val=$res[0];
?>

<tr>
<td><?php echo $this->get_label('total amount');?></td>
<td width="20px">:</td>
<td><?php echo $this->get_money_format($val['samount']+$val['fee']+$val['tax']);?></td>
</tr>

<?php if($val['fee'] > 0){?>
<tr>
<td><?php echo $this->get_label('fee');?></td>
<td width="20px">:</td>
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
<td><?php echo $this->get_label('amount credited');?></td>
<td width="20px">:</td>
<td><?php echo $this->get_money_format($val['samount']);?></td>
</tr>
<?php }?>

<tr>
<td><?php echo $this->get_label('currency');?></td>
<td width="20px">:</td>
<td><?php echo $val['currency'];?></td>
</tr>


<tr>
<td><?php echo $this->get_label('recevied date');?></td>
<td width="20px">:</td>
<td><?php 

$date=$val['received_date'];
if($date==0)
$receive_time= $this->get_label("na");
else
$receive_time=$this->get_date_format(2,$date);

echo $receive_time;

?></td>
</tr>	


<tr>
<td><?php echo $this->get_label('status');?></td>
<td width="20px">:</td>
<td><?php echo $this->get_payment_status($val['sstatus']);?>&nbsp;&nbsp;
<?php if($val['sstatus'] ==-1){?>
  <a href="<?php echo $this->make_url("advertiser/payment_approve/".$val['sid']."/2",ADMIN_DIR);?>"><i class="fa fa-thumbs-o-up approve-icon" title="<?php echo $this->get_label('approve');?>"></i></a>
  <a href="<?php echo $this->make_url("advertiser/payment_reject/".$val['sid']."/2",ADMIN_DIR);?>"><i class="fa fa-thumbs-o-down reject-icon" title="<?php echo $this->get_label('reject');?>"></i></a>
<?php }?>
</td>
</tr>	


<tr>
<td><?php echo $this->get_label('transaction id');?></td>
<td width="20px">:</td>
<td><?php echo $val['referencenumber'];?></td>
</tr>


<tr>
<td><?php echo $this->get_label('payment type');?></td>
<td width="20px">:</td>
<td><?php if($val['paymenttype'] !=''){echo $val['paymenttype'];}else{echo $this->get_label('na');}?></td>
</tr>


<tr>
<td><?php echo $this->get_label('credit card type');?></td>
<td width="20px">:</td>
<td><?php if($val['creditcardtype'] !=''){echo $val['creditcardtype'];}else{echo $this->get_label('na');}?></td>
</tr>