<?php 
$this->dispatch("layout/header/3/2/a");

$payid=$this->get_variable('payid');
$payamount=$this->get_variable('payamount');
$paytype=$this->get_variable('paytype');
$refunded_amount=$this->get_variable('refunded_amount');

$data=$this->get_result('data');
$data_count=count($data);
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

<div class="container"><h2 class="page_heading"><?php echo $this->get_label('manage refund');?></h2>
<div class="page_heading-btm"></div>
</div>

  <div class="container label_style">
   <div class="col-md-12 col-sm-12 col-xs-12 box_style">
   <div class="col-md-12 col-sm-12 col-xs-12 box_div">


<div class="form-inline">
<div class="form-group"><label style="width: 135px;"><?php echo $this->get_label('payment mode');?></label>
<label style="width: 20px;">:</label>
<label><?php echo $this->get_payment_mode($paytype);?></label>
</div></div>

			
<div class="form-inline">
<div class="form-group"><label style="width: 135px;"><?php echo $this->get_label('amount');?></label>
<label style="width: 20px;">:</label>
<label><bdi><?php  echo $this->get_money_format($payamount);?></bdi></label>
</div></div>


<?php if($refunded_amount >0){?>
<div class="form-inline">
<div class="form-group"><label style="width: 135px;"><?php echo $this->get_label('already refunded');?></label>
<label style="width: 20px;">:</label>
<label><?php echo $this->get_money_format($refunded_amount);?></label>
</div></div>
<?php }?>

<?php if($data_count >0){?>

<div class="page_heading"><?php echo $this->get_label('refund list');?></div>

<table id="table-desktop" class="data_table" cellpadding="0" cellspacing="0" style="margin: 0px;width: 100%;">
<tr class="data_table_head">
<td style="width: 250px;"><?php echo $this->get_label('amount');?></td>
<td style="width: 450px;"><?php echo $this->get_label('comment');?></td>
<td><?php echo $this->get_label('time');?></td>
</tr>

<?php foreach($data as $key=>$value){	?>
<tr class="data_table_content">
<td><bdi><?php echo $this->get_money_format($value['amount']);?></bdi></td>
<td ><?php echo nl2br($value['description']);?></td>
<td><?php echo $this->get_date_format(2,$value['time']);?></td>
</tr>

<?php }?>
</table>
<?php }?>

</div></div></div>
<?php $this->dispatch("layout/footer");?>