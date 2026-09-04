<?php
$this->dispatch("layout/header/3/1/a");

$payid=$this->get_variable('payid');
$payamount=$this->get_variable('payamount');
$paytype=$this->get_variable('paytype');
$refunded_amount=$this->get_variable('refunded_amount');

$data=$this->get_result('data');
$data_count=count($data);

$currencyPosition = Configuration::get_instance()->read('currency_position');
$currencySymbol   = Configuration::get_instance()->read('currency_symbol');
?>

<script type="text/javascript">
$(document).ready(function() {
	CreateResponsiveTable('table-desktop');

	$(window).resize(function()
	{
	 	CreateResponsiveTable('table-desktop');
	});
});

</script>



	<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 p-3 mb-3 manage-refund table-outer-box">
		<h2 class="page-heading">
			<div class="page-inner">
				<i class="fa fa-retweet icon_red"></i><?php echo $this->get_label('manage refund');?>
			</div>
		</h2>
		<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 refund-section">
			<div class="form-inline">
				<div class="form-group">
						<label class="form-label"><?php echo $this->get_label('payment mode');?></label>

						<input class="form-control" type="text" name="paymentModeInput" value="<?php echo $this->get_payment_mode($paytype);?>" disabled="disabled" />
				</div>
			</div>

			<div class="form-inline">
				<div class="form-group">
						<label class="form-label"><?php echo $this->get_label('amount');?></label>

						<div class="input-group d-flex <?php if($currencyPosition == 2){?>flex-row-reverse<?php }?>">
							<div class="input-group-text dollar_style"><?php echo $currencySymbol;?></div>

							<input class="form-control" type="text" name="amountInput" value="<?php echo $this->get_number_format($payamount);?>" disabled="disabled" />
						</div>
				</div>
			</div>

			<?php if($refunded_amount > 0){?>
				<div class="form-inline">
					<div class="form-group">
						<label class="form-label"><?php echo $this->get_label('already refunded');?></label>

						<div class="input-group d-flex <?php if($currencyPosition == 2){?>flex-row-reverse<?php }?>">

							<div class="input-group-text dollar_style"><?php echo $currencySymbol;?></div>

							<input class="form-control" type="text" name="amountRefundInput" value="<?php echo $this->get_number_format($refunded_amount);?>" disabled="disabled" />

						</div>
					</div>
				</div>
			<?php }?>
		</div>

		<?php if($data_count > 0){?>

			<div class="section-heading"><?php echo $this->get_label('refund list');?></div>

			<table id="table-desktop" class="data_table" cellpadding="0" cellspacing="0">
				<tr class="data_table_head">
					<td><?php echo $this->get_label('amount');?></td>
					<td><?php echo $this->get_label('comment');?></td>
					<td><?php echo $this->get_label('time');?></td>
				</tr>

				<?php foreach($data as $key=>$value){	?>
					<tr class="data_table_content">
						<td><bdi><?php echo $this->get_money_format($value['amount']);?></bdi></td>
						<td><?php echo nl2br($value['description']);?></td>
						<td><?php echo $this->get_date_format(2,$value['time']);?></td>
					</tr>
				<?php }?>
			</table>

		<?php }?>

</div>
<?php $this->dispatch("layout/footer");?>
