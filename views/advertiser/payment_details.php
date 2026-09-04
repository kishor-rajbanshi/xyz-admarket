<?php 
$this->dispatch("layout/header/3/2/a");
$pay_type=$this->get_variable('pay_type');
$sid=$this->get_variable('sid');
?>


<div class="container"><h2 class="page_heading"><?php echo $this->get_label('payment details');?></h2>
<div class="page_heading-btm"></div>
</div>

  <div class="container label_style">

  <div class="col-md-12 col-sm-12 col-xs-12 box_style">
  <div class="col-md-12 col-sm-12 col-xs-12">
  <div class="col-md-12 col-sm-12 col-xs-12 box_div box_div_bg">


<?php 
if($pay_type >5)
{
	$pname=$this->get_payment_mode_name($pay_type);
	
	if($pname =='checkout')
	$pathname='2co';
	else
	$pathname=$pname;
	
	if($pname =='gourl-bitcoin')
            $pname ='bitcoin';
            
	$this->dispatch($pname."/payment_details/".$sid,ADDON_DIR_PATH.$pathname.'/');
}
else 
{

$res=$this->get_result('res');
$val=$res[0];
$payment=$val['payment_type'];
?>


<div class="form-inline">
<div class="form-group"><label style="width: 135px;"><?php echo $this->get_label('payment mode');?></label>
<label style="width: 20px;">:</label>
<label><?php echo $this->get_payment_mode($val['payment_type']);?></label>

&nbsp;

<?php if($val['status']==-1){
if($val['payment_type']==1 || $val['payment_type']==2){
?>
 <a class="link_button" href="<?php echo $this->make_url("advertiser/edit_payment/".$val['sid']);?>"><?php echo $this->get_label('edit');?></a>

 <a class="link_button" href="<?php echo $this->make_url("advertiser/payment_delete/".$val['sid']);?>" onclick="return confirm('<?php echo $this->get_message('do you really want to delete this payment');?>')"><?php echo $this->get_label('delete');?></a>
 
<?php }}?>


</div></div>

<div class="form-inline">
<div class="form-group"><label style="width: 135px;"><?php echo $this->get_label('total amount paid');?></label>
<label style="width: 20px;">:</label>
<label><bdi><?php  if($payment==3) {echo $this->get_money_format($val['samount']+$val['fee']+$val['tax']);}else { echo $this->get_money_format($val['amount']+$val['fee']+$val['tax']);}?></bdi></label>
</div></div>	

<?php if($val['fee'] > 0){?>
<div class="form-inline">
<div class="form-group"><label style="width: 135px;"><?php echo $this->get_label('fee');?></label>
<label style="width: 20px;">:</label>
<label><bdi><?php echo $this->get_money_format($val['fee']);?></bdi></label>
</div></div>
<?php }?>


<?php if($val['tax'] > 0){?>
<div class="form-inline">
<div class="form-group"><label style="width: 135px;vertical-align: top;"><?php echo $this->get_label('tax');if(Configuration::get_instance()->read('tax_calculation')==1){echo $this->get_label('inclusive');}?></label>
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
<label><bdi><?php  if($payment==3) {echo $this->get_money_format($val['samount']);}else { echo $this->get_money_format($val['amount']);}?></bdi></label>
</div></div>
<?php }?>

<?php if($val['payment_type']==1 || $val['payment_type']==2){?>
<?php if($payment==1){?>
<div class="form-inline">
<div class="form-group"><label style="width: 135px;"><?php echo $this->get_label('check number');?></label>
<label style="width: 20px;">:</label>
<label><?php echo $val['check_number'];?></label>
</div></div>


<?php }else if($payment==2){?>
<div class="form-inline">
<div class="form-group"><label style="width: 135px;"><?php echo $this->get_label('account number');?></label>
<label style="width: 20px;">:</label>
<label><?php echo $val['account_number'];?></label>
</div></div>
<?php }?>

<div class="form-inline">
<div class="form-group"><label style="width: 135px;"><?php echo $this->get_label('bank name');?></label>
<label style="width: 20px;">:</label>
<label><?php echo $val['bank_name'];?></label>
</div></div>


<div class="form-inline">
<div class="form-group"><label style="width: 135px;"><?php echo $this->get_label('bank address line1');?></label>
<label style="width: 20px;">:</label>
<label><?php echo $val['address1'];?></label>
</div></div>
			
<div class="form-inline">
<div class="form-group"><label style="width: 135px;"><?php echo $this->get_label('bank address line2');?></label>
<label style="width: 20px;">:</label>
<label><?php echo $val['address2'];?></label>
</div></div>
			
<div class="form-inline">
<div class="form-group"><label style="width: 135px;"><?php echo $this->get_label('city');?></label>
<label style="width: 20px;">:</label>
<label><?php echo $val['city'];?></label>
</div></div>
			
<div class="form-inline">
<div class="form-group"><label style="width: 135px;"><?php echo $this->get_label('state');?></label>
<label style="width: 20px;">:</label>
<label><?php echo $val['state'];?></label>
</div></div>
			
<div class="form-inline">
<div class="form-group"><label style="width: 135px;"><?php echo $this->get_label('country');?></label>
<label style="width: 20px;">:</label>
<label><?php echo $this->get_country_name($val['country']);?></label>
</div></div>





<?php 
}
?>






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



<?php 
if($val['payment_type']!=4)
{
?>

<div class="form-inline">
<div class="form-group"><label style="width: 135px;"><?php echo $this->get_label('status');?></label>
<label style="width: 20px;">:</label>
<label><?php echo $this->get_payment_status($val['status']);?></label>
</div></div>



<?php 
}
?>

<?php 
if($val['payment_type']!=5 && $val['payment_type']!=3)
{
?>
<div class="form-inline">
<div class="form-group"><label style="width: 135px;"><?php echo $this->get_label('comment');?></label>
<label style="width: 20px;">:</label>
<label><?php if($val['comment']=="") echo $this->get_label('na'); else echo $val['comment'];?></label>
</div></div>



<?php 
}
?>

<?php 
if($val['payment_type']==3)
{
?>
<div class="form-inline">
<div class="form-group"><label style="width: 135px;"><?php echo $this->get_label('transaction id');?></label>
<label style="width: 20px;">:</label>
<label><?php echo $val['txnid'];?></label>
</div></div>




<?php if(isset($val['subscriptionid']) && $val['subscriptionid'] !=''){?>

<div class="form-inline">
<div class="form-group"><label style="width: 135px;"><?php echo $this->get_label('transaction type');?></label>
<label style="width: 20px;">:</label>
<label><?php echo $this->get_label('paypal subscription');?></label>
</div></div>



<div class="form-inline">
<div class="form-group"><label style="width: 135px;"><?php echo $this->get_label('subscription id');?></label>
<label style="width: 20px;">:</label>
<label><?php echo $val['subscriptionid'];?></label>
</div></div>


<?php }?>



<div class="form-inline">
<div class="form-group"><label style="width: 135px;"><?php echo $this->get_label('payer email');?></label>
<label style="width: 20px;">:</label>
<label><?php echo $val['payeremail'];?></label>
</div></div>



<div class="form-inline">
<div class="form-group"><label style="width: 135px;"><?php echo $this->get_label('item number');?></label>
<label style="width: 20px;">:</label>
<label><?php if($val['itemnumber']==""){echo $this->get_label('na');}else {echo $val['itemnumber'];}?></label>
</div></div>
<?php }}?>
</div></div></div></div>
<?php $this->dispatch("layout/footer");?>