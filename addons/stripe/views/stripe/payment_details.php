<?php
$res=$this->get_result('res');
$val=$res[0];
$payment_mode=$this->get_payment_mode($val['payment_type']);
?>


 <table id="table-desktop" class="data_table" cellpadding="0" cellspacing="0">
<tbody>

	<tr class="data_table_content">
	<td style="width: 123px;">
      <?php echo $this->get_label('payment mode');?>
 </td>
					<td style="width: 10px;">:</td>
					<td>
      <?php echo $this->get_label($payment_mode);?>
    </td>
  </tr>

	<tr class="data_table_content">
	<td style="width: 123px;">
			<?php echo $this->get_label('payment status');?>
		</td>
					<td style="width: 10px;">:</td>
					<td>
			<bdi>
				<?php echo $this->get_payment_status($val['sstatus']);?>
			</bdi>
		 </td>
  </tr>

	<tr class="data_table_content">
	<td style="width: 123px;">
			<?php echo $this->get_label('total amount paid');?>
		</td>
					<td style="width: 10px;">:</td>
					<td>
			<?php echo $this->get_money_format($val['samount']+$val['fee']+$val['tax']);?>
		 </td>
  </tr>

	<?php if($val['fee'] > 0){?>
		<tr class="data_table_content">
	<td style="width: 123px;">
				<?php echo $this->get_label('payment fee');?>
		</td>
					<td style="width: 10px;">:</td>
					<td>
				<?php echo $this->get_money_format($val['fee']);?>
			 </td>
  </tr>
	<?php } ?>

	<?php if($val['tax'] > 0){?>
		<tr class="data_table_content">
	<td style="width: 123px;">
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
	<td style="width: 123px;">
				<?php echo $this->get_label('amount credited');?>
		</td>
					<td style="width: 10px;">:</td>
					<td>
				<bdi>
					<?php echo $this->get_money_format($val['samount']);?>
				</bdi>
			 </td>
  </tr>
	<?php }?>

	<tr class="data_table_content">
	<td style="width: 123px;">
			<?php echo $this->get_label('currency');?>
	</td>
					<td style="width: 10px;">:</td>
					<td>
			<bdi>
				<?php echo $val['currency'];?>
			</bdi>
		 </td>
  </tr>

	<tr class="data_table_content">
	<td style="width: 123px;">
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

	<tr class="data_table_content">
	<td style="width: 123px;">
			<?php echo $this->get_label('transaction id');?>
		</td>
					<td style="width: 10px;">:</td>
					<td>
			<bdi>
				<?php echo $val['referencenumber'];?>
			</bdi>
		 </td>
  </tr>

	<?php if($val['paymenttype'] != ""){?>
		<tr class="data_table_content">
	<td style="width: 123px;">
				<?php echo $this->get_label('payment type');?>
			</td>
					<td style="width: 10px;">:</td>
					<td>
				<bdi>
					<?php echo $val['paymenttype'];?>
				</bdi>
		 </td>
  </tr>
	<?php } ?>

	<?php if($val['creditcardtype'] != ""){?>
		<tr class="data_table_content">
	<td style="width: 123px;">
				<?php echo $this->get_label('credit card type');?>
			</td>
					<td style="width: 10px;">:</td>
					<td>
				<bdi>
					<?php echo $val['creditcardtype'];?>
				</bdi>
			 </td>
  </tr>
	<?php } ?>

</tbody>

</table>
