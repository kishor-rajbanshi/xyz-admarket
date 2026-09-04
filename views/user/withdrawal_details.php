<?php 
$this->dispatch("layout/header/7/3/b");

$res=$this->get_result('res');
$val=$res[0];


$request=$val['request_time'];
$request_time=$this->get_date_format(2,$request);

$process=$val['process_time'];
if($process==0)
$process_time= $this->get_label("na");
else
$process_time=$this->get_date_format(2,$process);



$mode=$this->get_variable("mode");
?>

<div class="container"><h2 class="page_heading"><?php echo $this->get_label('withdrawal details');?></h2>
<div class="page_heading-btm"></div>
</div>

  <div class="container label_style">
   <div class="col-md-12 col-sm-12 col-xs-12 box_style">
   <div class="col-md-12 col-sm-12 col-xs-12 box_div">




<div class="form-inline">
<div class="form-group"><label style="width: 150px;"><?php echo $this->get_label('withdrawal mode');?></label>
<label style="width: 20px;">:</label>
<label><?php echo $this->get_withdrawal_mode($mode);?></label>
</div></div>


<div class="form-inline">
<div class="form-group"><label style="width: 150px;"><?php echo $this->get_label('withdrawal from');?></label>
<label style="width: 20px;">:</label>
<label>
<?php
if($val['withdrawal_type'] ==1)
echo $this->get_label('referral balance');
else
echo $this->get_label('publisher balance');
?>
</label>
</div></div>


<?php if($val['fee'] > 0 || $val['tax'] > 0){?>
<div class="form-inline">
<div class="form-group"><label style="width: 150px;"><?php echo $this->get_label('withdrawal amount');?></label>
<label style="width: 20px;">:</label>
<label><bdi><?php echo $this->get_money_format($val['amount']+$val['fee']+$val['tax']);	?></bdi></label>
</div></div>
<?php }?>

<?php if($val['fee'] > 0){?>
<div class="form-inline">
<div class="form-group"><label style="width: 150px;"><?php echo $this->get_label('fee');?></label>
<label style="width: 20px;">:</label>
<label><bdi><?php echo $this->get_money_format($val['fee']);	?></bdi></label>
</div></div>
<?php }?>

<?php if($val['tax'] > 0){?>
<div class="form-inline">
<div class="form-group"><label style="width: 150px;vertical-align: top;"><?php echo $this->get_label('tax');?></label>
<label style="width: 20px;">:</label>
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

<div class="form-inline">
<div class="form-group"><label style="width: 150px;"><?php echo $this->get_label('amount receivable');?></label>
<label style="width: 20px;">:</label>
<label><bdi><?php echo $this->get_money_format($val['amount']);	?></bdi></label>
</div></div>



<?php if($mode==1)      //check
{
?>
<div class="form-inline">
<div class="form-group"><label style="width: 150px;"><?php echo $this->get_label('payee name');?></label>
<label style="width: 20px;">:</label>
<label><?php echo $val['payee_name'];?></label>
</div></div>



<div class="form-inline">
<div class="form-group"><label style="width: 150px;"><?php echo $this->get_label('payee address line1');?></label>
<label style="width: 20px;">:</label>
<label><?php echo $val['address_line1'];?></label>
</div></div>



<div class="form-inline">
<div class="form-group"><label style="width: 150px;"><?php echo $this->get_label('payee address line2');?></label>
<label style="width: 20px;">:</label>
<label><?php echo $val['address_line2'];?></label>
</div></div>



<div class="form-inline">
<div class="form-group"><label style="width: 150px;"><?php echo $this->get_label('city');?></label>
<label style="width: 20px;">:</label>
<label><?php echo $val['city'];?></label>
</div></div>
		

			
<div class="form-inline">
<div class="form-group"><label style="width: 150px;"><?php echo $this->get_label('state');?></label>
<label style="width: 20px;">:</label>
<label><?php echo $val['state'];?></label>
</div></div>
		

			
<div class="form-inline">
<div class="form-group"><label style="width: 150px;"><?php echo $this->get_label('country');?></label>
<label style="width: 20px;">:</label>
<label><?php echo $this->get_country_name($val['country']);?></label>
</div></div>


<?php }else if($mode==2)       //bank
{
?>
<div class="form-inline">
<div class="form-group"><label style="width: 150px;"><?php echo $this->get_label('payee name');?></label>
<label style="width: 20px;">:</label>
<label><?php echo $val['payee_name'];?></label>
</div></div>



<div class="form-inline">
<div class="form-group"><label style="width: 150px;"><?php echo $this->get_label('bank name');?></label>
<label style="width: 20px;">:</label>
<label><?php echo $val['bank_name'];?></label>
</div></div>



<div class="form-inline">
<div class="form-group"><label style="width: 150px;"><?php echo $this->get_label('account no');?></label>
<label style="width: 20px;">:</label>
<label><?php echo $val['account_number'];?></label>
</div></div>



<div class="form-inline">
<div class="form-group"><label style="width: 150px;"><?php echo $this->get_label('bank address line1');?></label>
<label style="width: 20px;">:</label>
<label><?php echo $val['address_line1'];?></label>
</div></div>



<div class="form-inline">
<div class="form-group"><label style="width: 150px;"><?php echo $this->get_label('bank address line2');?></label>
<label style="width: 20px;">:</label>
<label><?php echo $val['address_line2'];?></label>
</div></div>




<div class="form-inline">
<div class="form-group"><label style="width: 150px;"><?php echo $this->get_label('city');?></label>
<label style="width: 20px;">:</label>
<label><?php echo $val['city'];?></label>
</div></div>
		

<div class="form-inline">
<div class="form-group"><label style="width: 150px;"><?php echo $this->get_label('state');?></label>
<label style="width: 20px;">:</label>
<label><?php echo $val['state'];?></label>
</div></div>
		

<div class="form-inline">
<div class="form-group"><label style="width: 150px;"><?php echo $this->get_label('country');?></label>
<label style="width: 20px;">:</label>
<label><?php echo $this->get_country_name($val['country']);?></label>
</div></div>



<div class="form-inline">
<div class="form-group"><label style="width: 150px;"><?php echo $this->get_label('swift/routing no');?></label>
<label style="width: 20px;">:</label>
<label><?php echo $val['swift_number'];?></label>
</div></div>


<?php }else if($mode==3){?>

<div class="form-inline">
<div class="form-group"><label style="width: 150px;"><?php echo $this->get_label('paypal email');?></label>
<label style="width: 20px;">:</label>
<label><?php echo $val['paypal_email'];?></label>
</div></div>



<?php }else if($mode==5){?>
<div class="form-inline">
<div class="form-group"><label style="width: 150px;"><?php echo $this->get_label('user name');?></label>
<label style="width: 20px;">:</label>
<label><?php echo $this->get_variable("usname");?></label>
</div></div>

<?php }else if($mode >5) {
	$colalready=$this->get_result('colalready');

	foreach($colalready as $key=>$row123)
	{
		$carray=explode('_',$row123['Field']);
		if($carray[0] == $mode)
		{
		
			if($carray[0].'_'.$carray[1].'_' == $mode.'_detail_')
			{
				if($val['status'] ==1)
				{
					?>
					
			<div class="form-inline">
<div class="form-group"><label style="width: 150px;"><?php echo $row123['Comment'];?></label>
<label style="width: 20px;">:</label>
<label><?php if($val[$row123['Field']] !=''){echo $val[$row123['Field']];}else{echo $this->get_label('na');}?></label>
</div></div>
		
					
					<?php 					
				}
				
				
			}
			else 
			{
			?>
<div class="form-inline">
<div class="form-group"><label style="width: 150px;"><?php echo $row123['Comment'];?></label>
<label style="width: 20px;">:</label>
<label><?php if($val[$row123['Field']] !=''){echo $val[$row123['Field']];}else{echo $this->get_label('na');}?></label>
</div></div>

	<?php }}
	}?>
<?php 	
}
?>	

<div class="form-inline">
<div class="form-group"><label style="width: 150px;"><?php echo $this->get_label('request date');?></label>
<label style="width: 20px;">:</label>
<label><?php echo $request_time;?></label>
</div></div>


<div class="form-inline">
<div class="form-group"><label style="width: 150px;"><?php echo $this->get_label('process date');?></label>
<label style="width: 20px;">:</label>
<label><?php echo $process_time;?></label>
</div></div>



<?php if(isset($val['comments']) && $val['comments'] !=''){?>

<div class="form-inline">
<div class="form-group"><label style="width: 150px;"><?php echo $this->get_label('comment');?></label>
<label style="width: 20px;">:</label>
<label><?php echo $val['comments'];?></label>
</div></div>
<?php }?>

<div class="form-inline">
<div class="form-group"><label style="width: 150px;"><?php echo $this->get_label('status');?></label>
<label style="width: 20px;">:</label>
<label><?php echo $this->get_payment_status($val['status']);?>




<?php 
if($val['status'] ==-1)
{
	if($mode==1 || $mode==2 || $mode==5 || $mode >5)
	{?>
	&nbsp;<a href="<?php echo $this->make_url("user/withdrawal_delete/".$val['sid']);?>" class="link_button" onclick="return confirm('<?php echo $this->get_message('do you really want to delete this withdrawal');?>')"><?php echo $this->get_label('delete');?></a>
	<?php }
}
?>
</label>
</div></div>	

</div></div></div></div>
<?php $this->dispatch("layout/footer");?>