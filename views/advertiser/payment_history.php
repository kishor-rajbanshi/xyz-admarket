<?php
$this->dispatch("layout/header/3/2/a");
 

$payment=$this->get_variable("payment");
$status=$this->get_variable("status");
$pg=$this->get_variable("pg");
$pend=0;
$invoice_download=Configuration::get_instance()->read('invoice_download');

$apply_tax_rules		= Configuration::get_instance()->read('apply_tax_rules');

$fee_flag=$this->get_fee_exists($payment);
?>
<script type="text/javascript">
function change()
{
	if(document.getElementById('payment').value==4)
	document.getElementById('status1').style.display='none';
	else
	document.getElementById('status1').style.display='';
}




$(document).ready(function() {
	CreateResponsiveTable('table-desktop');
	
	$(window).resize(function()
	{
	 	CreateResponsiveTable('table-desktop');
	});
});

</script>



<div class="container"><h2 class="page_heading new_heading"><?php echo $this->get_label('payment history');?></h2>
<div class="page_heading-btm"></div>
</div>

<div class="container">

<?php 
$form=$this->create_form();
$form->start("payment_history",$this->make_url("advertiser/payment_history"),"post");
?>
<div class="search_div" style="float: left;width: 100%;">
<div class="form-group search_div_items" style="width: 180px;">
<select class="form-control" name="payment" id="payment" onchange="javascript:change();" style="width: 150px;">
<option value="0" <?php if($payment=="0"){ echo "selected"; } ?>><?php echo $this->get_label('all payments');?></option>
<?php echo $this->get_payment_list($payment);?>
</select>
	
</div>

<div class="form-group search_div_items" style="width: 150px;">
<div id="status1">
<select class="form-control" name="status" id="status">
	<option value="4" <?php if($status=="4"){ echo "selected"; } ?>><?php echo $this->get_label('all status');?></option>
	<option value="1" <?php if($status=="1"){ echo "selected"; } ?> ><?php echo $this->get_label('approved');?></option>
	<option value="-1" <?php if($status=="-1"){ echo "selected"; } ?> ><?php echo $this->get_label('pending');?></option>
	<option value="0" <?php if($status=="0"){ echo "selected"; } ?> ><?php echo $this->get_label('rejected');?></option>
</select>
</div>
</div>

<div class="form-group search_div_items">
<input class="btn btn-danger" type="submit" name="select" value="<?php echo $this->get_label('go');?>" />
</div>
</div>
<?php $form->end(); ?>


<table id="table-desktop" class="data_table" cellpadding="0" cellspacing="0" style="margin: 0px;width: 100%;">
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
<td><?php echo $this->get_label('options');?></td>
</tr>

<?php
$res2=$this->get_result('res2');
if(count($res2)==0){?>
<tr class="data_table_message"><td colspan="9" ><?php echo $this->get_label('no records found');?></td></tr>
<?php }else {


foreach($res2 as $key=>$value)
{
$t=$value['received_date'];
if($t==0)
$time1= $this->get_label("na");
else
$time1=$this->get_date_format(2,$t);

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

if($value['status'] == -1 && $value['payment_type'] ==12)
{
	$pend=1; 
	echo "  *";
}
?>


</td>
<?php 
}
else 
{
?>
<td ><?php echo $this->get_label('na');?></td>

<?php 
}	?>


<td >

<?php 
if($value['payment_type']==4)
{?>
<a href="<?php echo $this->make_url("advertiser/payment_details/".$payid);?>" class="link_button"><?php echo $this->get_label('details');?></a>&nbsp;

<?php 
}else{?>



<a href="<?php echo $this->make_url("advertiser/payment_details/".$payid);?>" class="link_button"><?php echo $this->get_label('details');?></a>
<?php if($value['status']==-1){
if($value['payment_type']==1 || $value['payment_type']==2){?>
 <a href="<?php echo $this->make_url("advertiser/edit_payment/".$payid."/1/".$payment."/".$status."/".$pg);?>" class="link_button"><?php echo $this->get_label('edit');?></a>

 <a href="<?php echo $this->make_url("advertiser/payment_delete/".$payid."/1/".$payment."/".$status."/".$pg);?>" class="link_button" onclick="return confirm('<?php echo $this->get_message('do you really want to delete this payment');?>')"><?php echo $this->get_label('delete');?></a>
 
<?php }}?>


<?php }?>
<?php 
if($value['payment_type'] !=5 && $value['status']==1 && $this->get_refund_count($payid) >0){?>
<a target="_parent" href="<?php echo $this->make_url("advertiser/refund/".$payid);?>" class="link_button"><?php echo $this->get_label('refund details');?></a>
<?php }?>


<?php if($value['status']==1 && $invoice_download ==1){ ?>
<a target="_parent" href="<?php echo $this->make_url("advertiser/invoice_export/".$value['id']."/".$value['uid']);?>"><i class="fa fa-file-pdf-o" title="<?php echo $this->get_label('pdf download');?>" alt="<?php echo $this->get_label('pdf download');?>"></i></a>
<?php } ?> 


</td></tr>

<?php }}?>	
</table>	
<?php echo $this->get_variable('pagination1');?>


<div style="height: 20px;">
<?php if($pend==1 && $this->get_addon_status('bitcoin_enabled') == 1){?>
<?php echo $this->get_message('bitcoin message star');?>
<?php }?>
</div>


</div>

<?php $this->dispatch("layout/footer");?>
<script type="text/javascript">
change();
</script>