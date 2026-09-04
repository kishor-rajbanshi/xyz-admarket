<?php $this->dispatch("layout/header/22/_221");?>

<link rel="stylesheet" type="text/css" media="all" href="//code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/jquery-ui-1.8.23.custom.min.js' ></script>

<script type="text/javascript">
$(document).ready(function() {
	 $("#rdate").datepicker({dateFormat : 'mm-dd-yy'});
	 $("#pdate").datepicker({dateFormat : 'mm-dd-yy'});
});
</script>


<div class="sub_menu_main"><?php echo $this->get_label('manage withdrawal history');?></div>


<table style="width: 100%;" cellpadding="0" cellspacing="0" >


<tr><td colspan="8" style="height: 10px;"></td></tr>

<?php 
$form=$this->create_form();
$form->start("withdrawalhistory",$this->make_url("user/withdrawal_history"),"post"); 


$referral_enabled=$this->get_variable("referral_enabled");
$payment_type=$this->get_variable("payment_type");
$payment=$this->get_variable("payment");
$status=$this->get_variable("status");
$pub=$this->get_variable('pub');
$pg=$this->get_variable("pg");
$pdate=$this->get_variable("pdate");
$rdate=$this->get_variable("rdate");

$invoice_download		= Configuration::get_instance()->read('invoice_download');
$apply_tax_rules		= Configuration::get_instance()->read('apply_tax_rules');
$fee_flag				= $this->get_withdrawal_fee_exists($payment);
?>

<tr>
<td colspan="8">
<div class="search_div" style="height: 55px;">
<table class="search_div_table">
<tr>
<td style="width: 155px;">
<select name="pub" id="pub" style="width: 150px;">
<option value="0" <?php if($pub==0) echo "selected";?>><?php echo $this->get_label('all publishers');?></option>
<?php 
$res1=$this->get_result('res1');
foreach($res1 as $key1=>$value1)
{
?>
<option value="<?php echo $value1['id'];?>" <?php if($pub==$value1['id']) echo "selected";?>><?php echo $value1['username'];?></option>
<?php 
}
?>
</select>
</td>
<td style="width: 155px;">
<select name="payment" id="payment" style="width: 150px;">
<option value="0" <?php if($payment=="0"){ echo "selected"; } ?>><?php echo $this->get_label('withdrawal mode');?></option>
<?php echo $this->get_withdrawal_list($payment);?>
</select>
</td>
<td style="width: 155px;">
<select name="status" id="status" style="width: 150px;">
	<option value="4" <?php if($status=="4"){ echo "selected"; } ?>><?php echo $this->get_label('allstatus');?></option>
	<option value="1" <?php if($status=="1"){ echo "selected"; } ?> ><?php echo $this->get_label('approved');?></option>
	<option value="-1" <?php if($status=="-1"){ echo "selected"; } ?> ><?php echo $this->get_label('pending');?></option>
	<option value="0" <?php if($status=="0"){ echo "selected"; } ?> ><?php echo $this->get_label('rejected');?></option>
</select>
</td>


<?php if($referral_enabled ==1){?>
<td style="width: 155px;">
<select name="payment_type" id="payment_type" style="width: 150px;">
<option value="2" <?php if($payment_type ==2){?>selected="selected"<?php }?> ><?php echo $this->get_label('withdrawal from');?></option>

<option value="0" <?php if($payment_type ==0){?>selected="selected"<?php }?> ><?php echo $this->get_label('publisher balance');?></option>

<?php if($referral_enabled ==1){?>
<option value="1" <?php if($payment_type ==1){?>selected="selected"<?php }?>><?php echo $this->get_label('referral balance');?></option>
<?php }?>
</select>
</td>
<?php }else{?>
<td><input type="hidden" name="payment_type" id="payment_type" value="<?php echo $payment_type;?>"></td>
<?php }?>

<td style="width: 120px;">
<?php echo $this->get_label('request date');?>
<input type="text" id="rdate" name="rdate" value="<?php echo $rdate;?>" placeholder="<?php echo $this->get_label('request date');?>" style="height: 23px;width: 100px;" />  
</td>

<td style="width: 120px;">
<?php echo $this->get_label('process date');?>
<input type="text" id="pdate" name="pdate" value="<?php echo $pdate;?>" placeholder="<?php echo $this->get_label('process date');?>" style="height: 23px;width: 100px;" />  
</td>

<td>
<input type="submit" name="select" value="<?php echo $this->get_label('go');?>"/>


<!-- 
<input type="submit" name="mass_approve" onclick="return confirm('<?php echo $this->get_message('confirm');?>');" value="<?php echo $this->get_label('mass approve');?>" />
--> 

</td>
</tr>
</table>
</div>
<?php $form->end(); ?>


<tr><td colspan="8" height="10px"></td></tr>
<tr><td colspan="8"></td></tr>



<tr><td colspan="8">
<table class="data_table" cellpadding="0" cellspacing="0">
<tr class="row_heading_tr">
<td><?php echo $this->get_label('publisher');?></td>

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


<td><?php echo $this->get_label('mode');?></td>
<td><?php echo $this->get_label('withdrawal from');?></td>
<td><?php echo $this->get_label('request date');?></td>
<td><?php echo $this->get_label('process date');?></td>
<td><?php echo $this->get_label('status');?></td>
<td><?php echo $this->get_label('options');?></td>
</tr>

<?php 
$res=$this->get_result('res3');
if(count($res)==0)
{?>
<tr><td colspan="11" height="30px">&nbsp;<?php echo $this->get_label('no records found');?></td></tr>
<?php 
}
else
{

foreach($res as $key=>$value)
{

	$req_time=$value['request_time'];
	
	$time1=$this->get_date_format(3,$req_time);
	
	$t=$value['process_time'];
	if($t==0)
	{
		$time2= $this->get_label("na");
	}
	else
	{
		$time2=$this->get_date_format(3,$t);
	}
	
	?>
<tr class="row_data_tr">



<td >
<?php 
if($this->get_user_name($value['uid'])=="")
{
echo $this->get_label("deleted");
}
else
{?>
<a href="<?php echo $this->make_url("user/profile/".$value['uid']."/3");?>"><?php echo $this->escape($this->get_user_name($value['uid']));?></a>
<?php 
}
?>
</td>

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

<td class="<?php if($value['status'] ==0){ ?>blk<?php }
    else if($value['status'] ==-1){ ?>pend<?php }
	else if($value['status'] ==1){ ?>active<?php }?>"><?php echo $this->get_payment_status($value['status']);?></td>
<td >
<a  href="<?php echo $this->make_url("user/withdrawal_details/".$value['id']);?>"><i class="fa fa-bar-chart report-icon" title="<?php echo $this->get_label('details');?>"></i></a>

<?php if($value['status']==-1){?>
  <a  href="<?php echo $this->make_url("user/approve_withdrawal/".$value['id']."/0/".$payment."/".$status."/".$pub."/".$payment_type."/".$pdate."/".$rdate."/".$pg);?>"><i class="fa fa-thumbs-o-up approve-icon" title="<?php echo $this->get_label('approve');?>"></i></a>
  <a  href="<?php echo $this->make_url("user/reject_withdrawal/".$value['id']."/0/".$payment."/".$status."/".$pub."/".$payment_type."/".$pdate."/".$rdate."/".$pg);?>"><i class="fa fa-thumbs-o-down reject-icon" title="<?php echo $this->get_label('reject');?>"></i></a>

<?php }
if($value['status']==1 && $invoice_download==1){?>
<a href="<?php echo $this->make_url("user/invoice_export/".$value['id']."/".$value['uid']);?>"><i class="fa fa-file-pdf-o pdf-icon" title="<?php echo $this->get_label('pdf download');?>"></i></a>
<?php }?>
</td>

<?php 
}}?>		

<tr><td colspan="11" align="center"><?php echo $this->get_variable('pagination2');?></td></tr>
</table>



</td></tr></table>
<?php $this->dispatch("layout/footer");?>	