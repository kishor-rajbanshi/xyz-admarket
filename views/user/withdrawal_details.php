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


<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 p-3 mb-3 withdrawal-details">
	<h2 class="page-heading "><div class="page-inner"><i class="fa fa fa-credit-card icon_red"></i><?php echo $this->get_label('withdrawal details');?></div></h2>
<table id="table-desktop" class="data_table" cellpadding="0" cellspacing="0">
	<tbody>
 <tr class="data_table_content">
					<td style="width: 150px;">
      <?php echo $this->get_label('withdrawal mode');?>
  </td>
					<td style="width: 10px;">:</td>
					<td>
      <?php echo $this->get_withdrawal_mode($mode);?>
    </td>
					</tr>

  <tr class="data_table_content">
					<td style="width: 150px;">
      <?php echo $this->get_label('withdrawal status');?>
   </td>
					<td style="width: 10px;">:</td>
					<td>
      <?php echo $this->get_payment_status($val['status']);?>
      <?php
      if(Configuration::get_instance()->read('enable_auto_withdrawal') == 2)
      {
          if($val['status'] == -1)
          {
          	if($mode == 1 || $mode == 2 || $mode == 5 || $mode > 5)
          	{?>
          	&nbsp;<a href="<?php echo $this->make_url("user/withdrawal_delete/".$val['sid']);?>" class="link_button" onclick="return confirm('<?php echo $this->get_message('do you really want to delete this withdrawal');?>')"><?php echo $this->get_label('delete');?></a>
          	<?php }
          }
      }
      ?>
   </td>
					</tr>

  <tr class="data_table_content">
					<td style="width: 150px;">
      <?php echo $this->get_label('withdrawal from');?>
   </td>
					<td style="width: 10px;">:</td>
					<td>
      <?php
        if($val['withdrawal_type'] == 1)
        echo $this->get_label('referral balance');
        else
        echo $this->get_label('publisher balance');
      ?>
  </td>
					</tr>

  <?php if($val['fee'] > 0 || $val['tax'] > 0){?>
   <tr class="data_table_content">
					<td style="width: 150px;">
        <?php echo $this->get_label('withdrawal amount');?>
      </td>
					<td style="width: 10px;">:</td>
					<td>
        <bdi><?php echo $this->get_money_format($val['amount']+$val['fee']+$val['tax']);?></bdi>
     </td>
					</tr>
  <?php }?>

  <?php if($val['fee'] > 0){?>
    <tr class="data_table_content">
					<td style="width: 150px;">
        <?php echo $this->get_label('withdrawal fee');?>
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

  <tr class="data_table_content">
					<td style="width: 150px;">
      <?php echo $this->get_label('amount receivable');?>
   </td>
					<td style="width: 10px;">:</td>
					<td>
      <bdi><?php echo $this->get_money_format($val['amount']);?></bdi>
   </td>
					</tr>


<?php
if($mode==1)     //check
{
?>
<tr class="data_table_content">
					<td style="width: 150px;">
    <?php echo $this->get_label('payee name');?>
  </td>
					<td style="width: 10px;">:</td>
					<td>
    <?php echo $val['payee_name'];?>
 </td>
					</tr>

<tr class="data_table_content">
					<td style="width: 150px;">
    <?php echo $this->get_label('payee address line1');?>
 </td>
					<td style="width: 10px;">:</td>
					<td>
    <?php echo $val['address_line1'];?>
 </td>
					</tr>

<tr class="data_table_content">
					<td style="width: 150px;">
    <?php echo $this->get_label('payee address line2');?>
 </td>
					<td style="width: 10px;">:</td>
					<td>
    <?php echo $val['address_line2'];?>
  </td>
					</tr>

<tr class="data_table_content">
					<td style="width: 150px;">
    <?php echo $this->get_label('country');?>
 </td>
					<td style="width: 10px;">:</td>
					<td>
    <?php echo $this->get_country_name($val['country']);?>
 </td>
					</tr>

<tr class="data_table_content">
					<td style="width: 150px;">
    <?php echo $this->get_label('state');?>
 </td>
					<td style="width: 10px;">:</td>
					<td>
    <?php echo $val['state'];?>
 </td>
					</tr>

<tr class="data_table_content">
					<td style="width: 150px;">
    <?php echo $this->get_label('city');?>
</td>
					<td style="width: 10px;">:</td>
					<td>
    <?php echo $val['city'];?>
  </td>
					</tr>

<?php } else if($mode==2)       //bank
{
?>
<tr class="data_table_content">
					<td style="width: 150px;">
    <?php echo $this->get_label('payee name');?>
 </td>
					<td style="width: 10px;">:</td>
					<td>
    <?php echo $val['payee_name'];?>
 </td>
					</tr>

<tr class="data_table_content">
					<td style="width: 150px;">
    <?php echo $this->get_label('bank name');?>
 </td>
					<td style="width: 10px;">:</td>
					<td>
    <?php echo $val['bank_name'];?>
 </td>
					</tr>
<tr class="data_table_content">
					<td style="width: 150px;">
    <?php echo $this->get_label('account no');?>
  </td>
					<td style="width: 10px;">:</td>
					<td>
    <?php echo $val['account_number'];?>
 </td>
					</tr>

<tr class="data_table_content">
					<td style="width: 150px;">
    <?php echo $this->get_label('bank address line1');?>
  </td>
					<td style="width: 10px;">:</td>
					<td>
    <?php echo $val['address_line1'];?>
 </td>
					</tr>

<tr class="data_table_content">
					<td style="width: 150px;">
    <?php echo $this->get_label('bank address line2');?>
 </td>
					<td style="width: 10px;">:</td>
					<td>
    <?php echo $val['address_line2'];?>
  </td>
					</tr>

<tr class="data_table_content">
					<td style="width: 150px;">
    <?php echo $this->get_label('country');?>
  </td>
					<td style="width: 10px;">:</td>
					<td>
    <?php echo $this->get_country_name($val['country']);?>
 </td>
					</tr>

<tr class="data_table_content">
					<td style="width: 150px;">
    <?php echo $this->get_label('state');?>
  </td>
					<td style="width: 10px;">:</td>
					<td>
    <?php echo $val['state'];?>
 </td>
					</tr>

<tr class="data_table_content">
					<td style="width: 150px;">
    <?php echo $this->get_label('city');?>
  </td>
					<td style="width: 10px;">:</td>
					<td>
    <?php echo $val['city'];?>
 </td>
					</tr>

<tr class="data_table_content">
					<td style="width: 150px;">
    <?php echo $this->get_label('swift/routing no');?>
 </td>
					<td style="width: 10px;">:</td>
					<td>
    <?php echo $val['swift_number'];?>
 </td>
					</tr>
<?php }else if($mode == 3){?>

  <tr class="data_table_content">
					<td style="width: 150px;">
      <?php echo $this->get_label('paypal email');?>
  </td>
					<td style="width: 10px;">:</td>
					<td>
      <?php echo $val['paypal_email'];?>
    </td>
					</tr>

<?php } else if($mode == 5){?>

 <tr class="data_table_content">
					<td style="width: 150px;">
      <?php echo $this->get_label('user name');?>
  </td>
					<td style="width: 10px;">:</td>
					<td>
      <?php echo $this->get_variable("usname");?>
   </td>
					</tr>

<?php }else if($mode > 5)
{
	$colalready=$this->get_result('colalready');

	foreach($colalready as $key=>$row123)
	{
  		$carray=explode('_',$row123['Field']);
  		if($carray[0] == $mode)
  		{
    			if($carray[0].'_'.$carray[1].'_' == $mode.'_detail_')
    			{
      				if($val['status'] == 1){?>

                <tr class="data_table_content">
					<td style="width: 150px;">
                    <?php echo $row123['Comment'];?>
                 </td>
					<td style="width: 10px;">:</td>
					<td>
                    <?php
                    if($val[$row123['Field']] !='')
                    echo $val[$row123['Field']];
                    else
                    echo $this->get_label('na');
                    ?>
                  </td>
					</tr>

      			<?php	}
    			} else { ?>

            <tr class="data_table_content">
					<td style="width: 150px;">
                <?php echo $row123['Comment'];?>
             </td>
					<td style="width: 10px;">:</td>
					<td>
                <?php
                if($val[$row123['Field']] !='')
                echo $val[$row123['Field']];
                else
                echo $this->get_label('na');
                ?>
             </td>
					</tr>

    	<?php }
      }
	 }
}
?>

<tr class="data_table_content">
					<td style="width: 150px;">
    <?php echo $this->get_label('request date');?>
  </td>
  <td style="width: 10px;">:</td>
  <td>
    <?php echo $request_time;?>
  </td>
					</tr>

<tr class="data_table_content">
					<td style="width: 150px;">
    <?php echo $this->get_label('process date');?>
 </td>
					<td style="width: 10px;">:</td>
					<td>
    <?php echo $process_time;?>
</td>
					</tr>


<?php if(isset($val['comments']) && $val['comments'] != ""){?>

 <tr class="data_table_content">
					<td style="width: 150px;">
      <?php echo $this->get_label('comment');?>
    </td>
					<td style="width: 10px;">:</td>
					<td>
      <?php echo $val['comments'];?>
  </td>
					</tr>

<?php }?>
</tbody>
	</table>
</div>
<?php $this->dispatch("layout/footer");?>
