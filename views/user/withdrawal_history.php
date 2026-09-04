<?php $this->dispatch("layout/header/7/3/b");?>
<script type="text/javascript">
$(document).ready(function() {
	CreateResponsiveTable('table-desktop');
	
	$(window).resize(function()
	{
	 	CreateResponsiveTable('table-desktop');
	});
});
</script>

<div class="container">
<h2 class="page_heading new_heading"><?php echo $this->get_label('withdrawalhistory');?></h2>
<div class="page_heading-btm"></div>
</div>


<div class="container">


<?php 
$form=$this->create_form();
$form->start("withdrawalhistory",$this->make_url("user/withdrawal_history"),"post"); 


$referral_enabled=$this->get_variable("referral_enabled");
$pub_status=$this->get_variable("pub_status");
$payment=$this->get_variable("payment");
$payment_type=$this->get_variable("payment_type");
$status=$this->get_variable("status");
$pg=$this->get_variable("pg");


$invoice_download		= Configuration::get_instance()->read('invoice_download');
$apply_tax_rules		= Configuration::get_instance()->read('apply_tax_rules');
$fee_flag				= $this->get_withdrawal_fee_exists($payment);

?>


<div class="search_div" style="float: left;width: 100%;">
<div class="form-group search_div_items" style="width: 160px;">
<select class="form-control" name="payment" id="payment" style="width: 150px;">
<option value="0" <?php if($payment=="0"){ echo "selected"; } ?>><?php echo $this->get_label('withdrawal mode');?></option>
<?php echo $this->get_withdrawal_list($payment);?>
</select>
</div>


<?php if($referral_enabled ==1 && $pub_status ==1){?>
<div class="form-group search_div_items">
<select class="form-control" name="payment_type" id="payment_type" style="max-width: 150px;">
<option value="2" <?php if($payment_type ==2){?>selected="selected"<?php }?> ><?php echo $this->get_label('withdrawal from');?></option>

<?php if($pub_status ==1){?>
<option value="0" <?php if($payment_type ==0){?>selected="selected"<?php }?> ><?php echo $this->get_label('publisher balance');?></option>
<?php }?>

<?php if($referral_enabled ==1){?>
<option value="1" <?php if($payment_type ==1){?>selected="selected"<?php }?>><?php echo $this->get_label('referral balance');?></option>
<?php }?>
</select>
</div>
<?php }else{?>
<input type="hidden" name="payment_type" id="payment_type" value="<?php echo $payment_type;?>" />
<?php }?>


<div class="form-group search_div_items">
<select class="form-control" name="status" id="status" style="width: 150px;">
	<option value="4" <?php if($status=="4"){ echo "selected"; } ?>><?php echo $this->get_label('all status');?></option>
	<option value="1" <?php if($status=="1"){ echo "selected"; } ?> ><?php echo $this->get_label('approved');?></option>
	<option value="-1" <?php if($status=="-1"){ echo "selected"; } ?> ><?php echo $this->get_label('pending');?></option>
	<option value="0" <?php if($status=="0"){ echo "selected"; } ?> ><?php echo $this->get_label('rejected');?></option>
</select>
</div>

<div class="form-group search_div_items">
<input class="btn btn-danger" type="submit" name="select" value="<?php echo $this->get_label('go');?>" />
</div>
</div>
<?php $form->end(); ?>



<table id="table-desktop" class="data_table" cellpadding="0" cellspacing="0" style="margin: 0px;width: 100%;">
<tr class="data_table_head">

<?php if($fee_flag ==1 || $apply_tax_rules == 1){?>
<td><?php echo $this->get_label('withdrawal amount');?></td>
<?php }?>

<?php if($fee_flag ==1){?>
<td><?php echo $this->get_label('fee');?></td>
<?php }?>

<?php if($apply_tax_rules == 1){?>
<td><?php echo $this->get_label('tax');?></td>
<?php }?>

<td><?php echo $this->get_label('amount receivable');?></td>


<td><?php echo $this->get_label('withdrawal mode');?></td>
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
<tr class="data_table_message"><td colspan="11" ><?php echo $this->get_label('no records found');?></td></tr>
<?php 
}
else
{


foreach($res as $key=>$value)
{
	$ad_id=$value['id'];
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
<tr class="data_table_content">

<?php if($fee_flag ==1 || $apply_tax_rules == 1){?>
<td ><bdi><?php echo $this->get_money_format($value['amount']+$value['fee']+$value['tax']);?></bdi></td>
<?php }?>

<?php if($fee_flag ==1){?>
<td ><bdi><?php echo $this->get_money_format($value['fee']);?></bdi></td>
<?php }?>

<?php if($apply_tax_rules == 1){?>
<td ><bdi><?php echo $this->get_money_format($value['tax']);?></bdi></td>
<?php }?>

<td ><bdi><?php echo $this->get_money_format($value['amount']);?></bdi></td>

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
<a href="<?php echo $this->make_url("user/withdrawal_details/".$value['id']);?>" class="link_button"><?php echo $this->get_label('details');?></a>

<?php if($value['status']==-1){
if($value['payment_mode']==1 || $value['payment_mode']==2 || $value['payment_mode']==5 || $value['payment_mode'] >5){?>

<a href="<?php echo $this->make_url("user/withdrawal_delete/".$value['id']."/1/".$payment."/".$status."/".$payment_type."/".$pg);?>" class="link_button" onclick="return confirm('<?php echo $this->get_message('do you really want to delete this withdrawal');?>')"><?php echo $this->get_label('delete');?></a>

<?php }}?>

<?php if($value['status']==1 && $invoice_download ==1){?>
<a href="<?php echo $this->make_url("user/invoice_export/".$value['id']."/".$value['uid']);?>"><i class="fa fa-file-pdf-o" title="<?php echo $this->get_label('payslip');?>" alt="<?php echo $this->get_label('payslip');?>"></i></a>
<?php }?>
</td>

<?php 
}}?>		
</table>
<?php echo $this->get_variable('pagination2');?>

<div style="height: 20px;"></div>
</div>
<?php $this->dispatch("layout/footer");?>