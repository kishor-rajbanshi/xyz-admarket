<?php
$this->dispatch("layout/header/3/1/a");
$pay_type=$this->get_variable('pay_type');
$sid=$this->get_variable('sid');
?>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 p-3 mb-2 payment-details">
	<h2 class="page-heading "><div class="page-inner"><i class="fa fa-credit-card icon_red"></i><?php echo $this->get_label('payment details');?></div></h2>
<table id="table-desktop" class="data_table" cellpadding="0" cellspacing="0">
	
	<tbody>
		
	
		
		
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
  <tr class="data_table_content">
					<td style="width: 150px;">
      <?php echo $this->get_label('payment mode');?>
    </td>
      <td style="width: 10px;">:</td>
		<td>
      <?php echo $this->get_payment_mode($val['payment_type']);?>

      <?php if($val['status']==-1){
      if($val['payment_type']==1 || $val['payment_type']==2){?>
      <div class="payment-operation-div" style="display: inline-flex; margin-left:15px;">
         <a href="<?php echo $this->make_url("advertiser/edit_payment/".$val['sid']);?>"><i class="fa fa-edit edit-icon" title="<?php echo $this->get_label('edit');?>"></i></a>
         <a href="<?php echo $this->make_url("advertiser/payment_delete/".$val['sid']);?>" onclick="return confirm('<?php echo $this->get_message('do you really want to delete this payment');?>')"><i class="fa fa-trash delete-icon" title="<?php echo $this->get_label('delete');?>"></i></a>
      </div>
      <?php }}?>
   </td>
</tr>

  <?php if($val['payment_type'] != 4){?>
    <tr class="data_table_content">
	<td style="width: 150px;">
        <?php echo $this->get_label('payment status');?>
      </td>
      <td style="width: 10px;">:</td>
		<td>
        <bdi>
          <?php
          if($val['payment_type'] ==3)
          echo $this->get_payment_status($val['sstatus']);
          else
          echo $this->get_payment_status($val['status']);
          ?>
        </bdi>
     </td>
</tr>
  <?php } ?>

 <tr class="data_table_content">
	<td style="width: 150px;">
      <?php echo $this->get_label('total amount paid');?>
    </td>
      <td style="width: 10px;">:</td>
		<td>
      <bdi><?php  if($payment==3) {echo $this->get_money_format($val['samount']+$val['fee']+$val['tax']);} else { echo $this->get_money_format($val['amount']+$val['fee']+$val['tax']);}?></bdi>
    </td>
</tr>

  <?php if($val['fee'] > 0){?>
    <tr class="data_table_content">
	<td style="width: 150px;">
        <?php echo $this->get_label('payment fee');?>
      </td>
      <td style="width: 10px;">:</td>
		<td>
        <bdi><?php echo $this->get_money_format($val['fee']);?></bdi>
      </td>
</tr>
  <?php }?>

  <?php if($val['tax'] > 0){?>
   <tr class="data_table_content">
	<td style="width: 150px;">
        <?php echo $this->get_label('tax');?>
        <?php
        if(Configuration::get_instance()->read('tax_calculation')==1)
        echo " (".$this->get_label('inclusive').")";
        ?>
      </td>
      <td style="width: 10px;">:</td>
		<td>
        <bdi><?php echo $this->get_money_format($val['tax']);?></bdi>

        <?php
        $json_array=json_decode($val['tax_details'],1);

        foreach($json_array as $jkey=>$jvalue)
        {
        	echo '<div class="notification"><bdi>['.$jvalue[1].'% '.$jvalue[0].'&nbsp; - &nbsp;'.$this->get_money_format($jvalue[2]).']</bdi></div>';
        }
        ?>
     </td>
</tr>
  <?php }?>

  <?php if($val['fee'] > 0 || $val['tax'] > 0){?>
   <tr class="data_table_content">
					<td style="width: 150px;">
        <?php echo $this->get_label('amount credited');?>
      </td>
      <td style="width: 10px;">:</td>
		<td>
        <bdi>
          <?php
          if($payment == 3)
          echo $this->get_money_format($val['samount']);
          else
          echo $this->get_money_format($val['amount']);
          ?>
        </bdi>
      </td>
</tr>
  <?php }?>

  <?php if($val['payment_type']==1 || $val['payment_type']==2){?>
    <?php if($payment==1){?>
   <tr class="data_table_content">
					<td style="width: 150px;">
          <?php echo $this->get_label('check number');?>
       </td>
      <td style="width: 10px;">:</td>
		<td>
          <bdi><?php echo $val['check_number'];?></bdi>
        </div>
      </div>
    <?php }else if($payment==2){?>
      <tr class="data_table_content">
					<td style="width: 150px;">
          <?php echo $this->get_label('account number');?>
       </td>
      <td style="width: 10px;">:</td>
		<td>
          <bdi><?php echo $val['account_number'];?></bdi>
       </td>
</tr>
    <?php }?>

  <tr class="data_table_content">
					<td style="width: 150px;">
        <?php echo $this->get_label('bank name');?>
     </td>
      <td style="width: 10px;">:</td>
		<td>
        <bdi><?php echo $val['bank_name'];?></bdi>
      </td>
</tr>


   <tr class="data_table_content">
					<td style="width: 150px;">
        <?php echo $this->get_label('bank address line1');?>
   </td>
      <td style="width: 10px;">:</td>
		<td>
        <bdi><?php echo $val['address1'];?></bdi>
      </td>
</tr>

    <tr class="data_table_content">
					<td style="width: 150px;">
        <?php echo $this->get_label('bank address line2');?>
     </td>
      <td style="width: 10px;">:</td>
		<td>
        <bdi><?php echo $val['address2'];?></bdi>
      </td>
</tr>

   <tr class="data_table_content">
					<td style="width: 150px;">
        <?php echo $this->get_label('country');?>
     </td>
      <td style="width: 10px;">:</td>
		<td>
        <bdi><?php echo $this->get_country_name($val['country']);?></bdi>
     </td>
</tr>

    <tr class="data_table_content">
					<td style="width: 150px;">
        <?php echo $this->get_label('state');?>
    </td>
      <td style="width: 10px;">:</td>
		<td>
        <bdi><?php echo $val['state'];?></bdi>
     </td>
</tr>

     <tr class="data_table_content">
					<td style="width: 150px;">
        <?php echo $this->get_label('city');?>
     </td>
      <td style="width: 10px;">:</td>
		<td>
        <bdi><?php echo $val['city'];?></bdi>
     </td>
</tr>
<?php } ?>

 <tr class="data_table_content">
					<td style="width: 150px;">
    <?php echo $this->get_label('recevied date');?>
 </td>
      <td style="width: 10px;">:</td>
		<td>
    <bdi>
      <?php
      $date=$val['received_date'];
      if($date == 0)
      $receive_time= $this->get_label("na");
      else
      $receive_time=$this->get_date_format(2,$date);

      echo $receive_time;
      ?>
    </bdi>
   </td>
</tr>

<?php if($val['payment_type'] != 5 && $val['payment_type'] != 3 && $val['comment'] != ""){?>
  <tr class="data_table_content">
					<td style="width: 150px;">
      <?php echo $this->get_label('comment');?>
   </td>
      <td style="width: 10px;">:</td>
		<td>
      <bdi><?php echo $val['comment'];?></bdi>
    </td>
</tr>
<?php } ?>

<?php if($val['payment_type']==3){?>
  <tr class="data_table_content">
					<td style="width: 150px;">
      <?php echo $this->get_label('transaction id');?>
   </td>
      <td style="width: 10px;">:</td>
		<td>
      <bdi><?php echo $val['txnid'];?></bdi>
    </td>
</tr>

<?php if(isset($val['subscriptionid']) && $val['subscriptionid'] !=''){?>
  <tr class="data_table_content">
					<td style="width: 150px;">
      <?php echo $this->get_label('transaction type');?>
  </td>
      <td style="width: 10px;">:</td>
		<td>
      <bdi><?php echo $this->get_label('paypal subscription');?></bdi>
     </td>
</tr>

  <tr class="data_table_content">
					<td style="width: 150px;">
      <?php echo $this->get_label('subscription id');?>
  </td>
      <td style="width: 10px;">:</td>
		<td>
      <bdi><?php echo $val['subscriptionid'];?></bdi>
    </td>
</tr>
<?php }?>

 <tr class="data_table_content">
					<td style="width: 150px;">
    <?php echo $this->get_label('payer email');?>
  </td>
      <td style="width: 10px;">:</td>
		<td>
    <bdi><?php echo $val['payeremail'];?></bdi>
   </td>
</tr>

<tr class="data_table_content">
					<td style="width: 150px;">
    <?php echo $this->get_label('item number');?>
  </td>
      <td style="width: 10px;">:</td>
		<td>
    <bdi>
      <?php
      if($val['itemnumber'] == "")
      echo $this->get_label('na');
      else
      echo $val['itemnumber'];
      ?>
    </bdi>
  </td>
</tr>

<?php }}?>
</tbody>
</table>
	
	</div>
<?php $this->dispatch("layout/footer");?>
