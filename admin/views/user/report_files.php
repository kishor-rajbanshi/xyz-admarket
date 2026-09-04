<?php $this->dispatch("layout/header/22/_222");

//$filearray=$this->get_array('filearray');
$res=$this->get_result('res');

 
	$filename1='_quickbook.iif';




$filename2='_bank.csv';
 $filename3='_paypal.csv';
?>


<div class="sub_menu_main"><?php echo $this->get_label('report files');?></div>


<table class="data_table" cellpadding="0" cellspacing="0">
<tr class="row_heading_tr">
<td><?php echo $this->get_label('date');?></td>
<td><?php echo $this->get_label('Check');?></td>
<td><?php echo $this->get_label('Bank');?></td>
<td><?php echo $this->get_label('Paypal');?></td>

</tr>
<?php 
if(count($res)==0)
{?>
<tr><td colspan="8" height="30px">&nbsp;<?php echo $this->get_label('no records found');?></td></tr>
<?php 
}
else
{

 foreach ($res as $file => $val){

 	?>
<tr class="row_data_tr">
<td ><?php echo date('m/d/Y',$val['request_time']);?></td>
<td ><?php  if(file_exists(DATA_DIR_PATH.'payment_requests/'.date('Ym',$val['request_time']).$filename1)){echo date('Ymd',$val['request_time']).$filename1; ?>&nbsp;<a  href="<?php echo $this->make_url('user/download_report/'.date('Ym',$val['request_time']).$filename1);?>"class="link_button"><?php echo $this->get_label('download');?></a><?php } else echo $this->get_label('na');?></td>


<td ><?php  if(file_exists(DATA_DIR_PATH.'payment_requests/'.date('Ym',$val['request_time']).$filename2)){echo date('Ymd',$val['request_time']).$filename2;?>&nbsp;<a  href="<?php echo $this->make_url('user/download_report/'.date('Ym',$val['request_time']).$filename2);?>"class="link_button"><?php echo $this->get_label('download');?></a><?php } else echo $this->get_label('na');?></td>


<td ><?php  if(file_exists(DATA_DIR_PATH.'payment_requests/'.date('Ym',$val['request_time']).$filename3)){echo date('Ymd',$val['request_time']).$filename3;?>&nbsp;<a  href="<?php echo $this->make_url('user/download_report/'.date('Ym',$val['request_time']).$filename3);?>"class="link_button"><?php echo $this->get_label('download');?></a><?php } else echo $this->get_label('na');?></td>



 
</tr>

<?php 
 }
}?>

<tr><td align="center" colspan="4"><?php echo $this->get_variable('link');?></td></tr>

</table>



<?php $this->dispatch("layout/footer");?>	