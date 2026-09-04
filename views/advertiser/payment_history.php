<?php
$this->dispatch("layout/header/3/1/a");


$payment=$this->get_variable("payment");
$status=$this->get_variable("status");
$pg=$this->get_variable("pg");
$pendingMessage = 0;

$invoice_download=Configuration::get_instance()->read('invoice_download');

$apply_tax_rules		= Configuration::get_instance()->read('apply_tax_rules');

$fee_flag=$this->get_fee_exists($payment);
?>
<script type="text/javascript">
function change()
{
		if(document.getElementById('payment').value == 4)
		document.getElementById('status-div').style.display = 'none';
		else
		document.getElementById('status-div').style.display = '';
}


$(document).ready(function() {
	CreateResponsiveTable('table-desktop');

	$(window).resize(function()
	{
	 	CreateResponsiveTable('table-desktop');
	});
});

</script>


<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 p-3 mb-3 manage-payment-history table-outer-box">

<h2 class="page-heading page-heading-flex"><div class="page-inner"><i class="fa fa-calendar icon_red"></i><?php echo $this->get_label('payments');?></div>


<a class="create-btn" href="<?php echo $this->make_base_url("advertiser/add_fund");?>">
    <p><i class="fa fa-plus-circle" aria-hidden="true"></i> <?php echo $this->get_label('add fund');?></p>
</a></h2>



<?php
$form=$this->create_form();
$form->start("payment_history",$this->make_url("advertiser/payment_history"),"post");
?>
<div class="row mb-3 px-0 search_div">
	<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12 mb-1">
		<select class="form-select" name="payment" id="payment" onchange="javascript:change();">
			<option value="0" <?php if($payment=="0"){ echo "selected"; } ?>><?php echo $this->get_label('all payments');?></option>
			<?php echo $this->get_payment_list($payment);?>
		</select>
	</div>

	<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12 mb-1" id = "status-div">
			<select class="form-select" name="status" id="status">
				<option value="4" <?php if($status=="4"){ echo "selected"; } ?>><?php echo $this->get_label('all status');?></option>
				<option value="1" <?php if($status=="1"){ echo "selected"; } ?> ><?php echo $this->get_label('approved');?></option>
				<option value="-1" <?php if($status=="-1"){ echo "selected"; } ?> ><?php echo $this->get_label('pending');?></option>
				<option value="0" <?php if($status=="0"){ echo "selected"; } ?> ><?php echo $this->get_label('rejected');?></option>
			</select>
	</div>

	<div class="col-auto">
			<input class="btn btn-info" type="submit" name="select" value="<?php echo $this->get_label('go');?>" />
	</div>
</div>
<?php $form->end(); ?>

<table id="table-desktop" class="data_table" cellpadding="0" cellspacing="0">
	<tr class="data_table_head">

		<?php if($fee_flag ==1 || $apply_tax_rules == 1){?>
		<td><?php echo $this->get_label('total amount paid');?></td>
		<?php }?>

		<?php if($fee_flag ==1){?>
		<td><?php echo $this->get_label('fee');?></td>
		<?php }?>

		<?php if($apply_tax_rules == 1){?>
		<td><?php echo $this->get_label('tax');?></td>
		<?php }?>


		<td><?php echo $this->get_label('amount credited');?></td>


		<td><?php echo $this->get_label('payment mode');?></td>
		<td><?php echo $this->get_label('received date');?></td>
		<td><?php echo $this->get_label('status');?></td>
		<td><?php echo $this->get_label('actions');?></td>
	</tr>

<?php
$res2 = $this->get_result('res2');

if(count($res2) == 0){?>
<tr class="data_table_content">
	<td colspan="10" ><?php echo $this->get_label('no records found');?></td>
</tr>
<?php } else {


foreach($res2 as $key=>$value)
{
		if($value['received_date'] == 0)
		$time1 = $this->get_label("na");
		else
		$time1 = $this->get_date_format(2,$value['received_date']);

		$uid=$value['uid'];
		$payid=$value['id'];

		$total=$value['amount'];

		if($fee_flag ==1)
		$total=$total+$value['fee'];

		if($apply_tax_rules == 1)
		$total=$total+$value['tax'];
?>
<tr class="data_table_content">

	<?php if($fee_flag ==1 || $apply_tax_rules == 1){?>
	<td ><bdi><?php echo $this->get_money_format($total);?></bdi></td>
	<?php }?>

	<?php if($fee_flag ==1){?>
	<td ><bdi><?php echo $this->get_money_format($value['fee']);?></bdi></td>
	<?php }?>

	<?php if($apply_tax_rules == 1){?>
	<td ><bdi><?php echo $this->get_money_format($value['tax']);?></bdi></td>
	<?php }?>

	<td ><bdi><?php echo $this->get_money_format($value['amount']);?></bdi></td>


	<td ><?php echo ucfirst($this->get_payment_mode($value['payment_type'],1));?></td>
	<td ><?php echo $time1;?></td>

	<?php if($value['payment_type']!=4){?>
	<td >
		<?php
		echo $this->get_payment_status($value['status']);

		if($value['status'] == -1 && $value['payment_type'] == 12)
		{
			$pendingMessage = 1;
			echo "  *";
		}
		?>
	</td>
	<?php } else { ?>
	<td ><?php echo $this->get_label('na');?></td>
	<?php }	?>

<td >

<?php
//For exclude zaincash pending payment
if($value['payment_type'] != 21 || ($value['payment_type'] == 21 && $value['status'] != -1)){?>

<?php if($value['payment_type'] == 4){?>

<a href="<?php echo $this->make_url("advertiser/payment_details/".$payid);?>">
	<i class="fa fa-bar-chart details-icon" title="<?php echo $this->get_label('details');?>"></i>
</a>

<?php }else{?>

<a href="<?php echo $this->make_url("advertiser/payment_details/".$payid);?>">
	<i class="fa fa-bar-chart details-icon" title="<?php echo $this->get_label('details');?>"></i>
</a>

<?php if($value['status'] == -1){
if($value['payment_type'] == 1 || $value['payment_type'] == 2){?>
 <a href="<?php echo $this->make_url("advertiser/edit_payment/".$payid."/1/".$payment."/".$status."/".$pg);?>">
	 <i class="fa fa-edit edit-icon" title="<?php echo $this->get_label('edit');?>"></i>
 </a>

 <a href="<?php echo $this->make_url("advertiser/payment_delete/".$payid."/1/".$payment."/".$status."/".$pg);?>" onclick="return confirm('<?php echo $this->get_message('do you really want to delete this payment');?>')">
	 <i class="fa fa-trash delete-icon" title="<?php echo $this->get_label('delete');?>"></i>
 </a>

<?php }}?>

<?php }?>

<?php if($value['payment_type'] != 5 && $value['status'] == 1 && $this->get_refund_count($payid) > 0){?>
<a target="_parent" href="<?php echo $this->make_url("advertiser/refund/".$payid);?>">
	<i class="fa fa-refresh refund-icon" title="<?php echo $this->get_label('refund details');?>"></i>
</a>
<?php }?>


<?php if($value['status'] == 1 && $invoice_download == 1){ ?>
<a target="_parent" href="<?php echo $this->make_url("advertiser/invoice_export/".$value['id']."/".$value['uid']);?>">
	<i class="fa fa-file-pdf-o pdf-icon" title="<?php echo $this->get_label('pdf download');?>"></i>
</a>
<?php } ?>

<?php
}else{
echo $this->get_label('na');
}
?>

</td>
</tr>

<?php }}?>
</table>

	<div class="row">
		<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 mt-3 d-flex flex-row-reverse">
			<?php echo $this->get_variable('pagination1');?>
		</div>
	</div>

	<?php if($pendingMessage == 1 && $this->get_addon_status('bitcoin_enabled') == 1){?>
		<div class="row">
			<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 mt-3 notification">
				<?php echo $this->get_message('bitcoin message star');?>
			</div>
		</div>
	<?php } ?>

</div>

<?php $this->dispatch("layout/footer");?>
<script type="text/javascript">
change();
</script>
