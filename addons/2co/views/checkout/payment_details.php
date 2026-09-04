<?php 
$res=$this->get_result('res');
$val=$res[0];
?>

<div class="form-inline">
<div class="form-group"><label style="width: 135px;"><?php echo $this->get_label('payment mode');?></label>
<label style="width: 20px;">:</label>
<label><?php echo $this->get_payment_mode($val['payment_type']);?></label>
</div></div>

<div class="form-inline">
<div class="form-group"><label style="width: 135px;"><?php echo $this->get_label('2co order id');?></label>
<label style="width: 20px;">:</label>
<label><?php echo $val['txnid'];?></label>
</div></div>



<div class="form-inline">
<div class="form-group"><label style="width: 135px;"><?php echo $this->get_label('2co invoice id');?></label>
<label style="width: 20px;">:</label>
<label><?php echo $val['invoice_id'];?></label>
</div></div>


<div class="form-inline">
<div class="form-group"><label style="width: 135px;"><?php echo $this->get_label('total amount paid');?></label>
<label style="width: 20px;">:</label>
<label><?php echo $this->get_money_format($val['samount']+$val['fee']+$val['tax']);?></label>
</div></div>

<?php if($val['fee'] > 0){?>
<div class="form-inline">
<div class="form-group"><label style="width: 135px;"><?php echo $this->get_label('fee');?></label>
<label style="width: 20px;">:</label>
<label><?php echo $this->get_money_format($val['fee']);?></label>
</div></div>
<?php }?>


<?php if($val['tax'] > 0){?>
<div class="form-inline">
<div class="form-group">
<label style="width: 135px;vertical-align: top;"><?php echo $this->get_label('tax');if(Configuration::get_instance()->read('tax_calculation')==1){echo $this->get_label('inclusive');}?></label>
<label style="width: 20px;vertical-align: top;">:</label>
<label style="vertical-align: top;">
<div class="tax-details-page" style="margin-left: 0px;margin-top: 0px;"><bdi><?php echo $this->get_money_format($val['tax']);?></bdi></div>

<?php  
$json_array=json_decode($val['tax_details'],1);

foreach($json_array as $jkey=>$jvalue)
{
	echo '<div class="notification tax-details-page" style="margin-top: 0px;"><bdi>['.$jvalue[1].'% '.$jvalue[0].'&nbsp; - &nbsp;'.$this->get_money_format($jvalue[2]).']</bdi></div>';
}
?>
</label>
</div></div>
<?php }?>

<?php if($val['fee'] > 0 || $val['tax'] > 0){?>
<div class="form-inline">
<div class="form-group"><label style="width: 135px;"><?php echo $this->get_label('amount credited');?></label>
<label style="width: 20px;">:</label>
<label><?php echo $this->get_money_format($val['samount']);?></label>
</div></div>
<?php }?>



<div class="form-inline">
<div class="form-group"><label style="width: 135px;"><?php echo $this->get_label('currency');?></label>
<label style="width: 20px;">:</label>
<label><?php echo $val['currency'];?></label>
</div></div>

<div class="form-inline">
<div class="form-group"><label style="width: 135px;"><?php echo $this->get_label('recevied date');?></label>
<label style="width: 20px;">:</label>
<label><?php 

$date=$val['received_date'];
if($date==0)
$receive_time= $this->get_label("na");
else
$receive_time=$this->get_date_format(2,$date);

echo $receive_time;

?></label>
</div></div>

<div class="form-inline">
<div class="form-group"><label style="width: 135px;"><?php echo $this->get_label('status');?></label>
<label style="width: 20px;">:</label>
<label><?php echo $this->get_payment_status($val['sstatus']);?></label>
</div></div>


<div class="form-inline">
<div class="form-group"><label style="width: 135px;"><?php echo $this->get_label('payment type');?></label>
<label style="width: 20px;">:</label>
<label><?php if($val['paymenttype'] !=''){echo $val['paymenttype'];}else{echo $this->get_label('na');}?></label>
</div></div>