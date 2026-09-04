<?php $this->dispatch("layout/header/22/_222");

$res=$this->get_result('res');

 
$filename1='_check.csv';




$filename2='_bank.csv';
$filename3='_paypal.csv';
 
 
 $customWithdrawals = $this->get_result('customWithdrawals');
 
 

?>


<div class="sub_menu_main"><?php echo $this->get_label('report files');?></div>


<table class="data_table" cellpadding="0" cellspacing="0">
<tr class="row_heading_tr">
<td><?php echo $this->get_label('date');?></td>
<td><?php echo $this->get_label('check');?></td>
<td><?php echo $this->get_label('bank');?></td>
<td><?php echo $this->get_label('paypal');?></td>

<?php foreach($customWithdrawals as $key => $value){?>
<td><?php echo $value['name'];?></td>
<?php }?>

</tr>
<?php if(count($res)==0){?>
<tr><td colspan="20" height="30px">&nbsp;<?php echo $this->get_label('no records found');?></td></tr>
<?php }else {

 foreach ($res as $file => $val){

 	?>
<tr class="row_data_tr">
<td ><?php echo $this->get_date_format(2,$val['request_time']);?></td>
<td ><?php  if(file_exists(DATA_DIR_PATH.'payment_requests/'.$val['request_time'].$filename1)){?><a title="<?php echo $val['request_time'].$filename1;?>" href="<?php echo $this->make_url('user/download_report/'.$val['request_time'].$filename1);?>"class="link_button"><?php echo $this->get_label('download');?></a><?php } else echo $this->get_label('na');?></td>


<td ><?php  if(file_exists(DATA_DIR_PATH.'payment_requests/'.$val['request_time'].$filename2)){?><a title="<?php echo $val['request_time'].$filename2;?>" href="<?php echo $this->make_url('user/download_report/'.$val['request_time'].$filename2);?>"class="link_button"><?php echo $this->get_label('download');?></a><?php } else echo $this->get_label('na');?></td>


<td ><?php  if(file_exists(DATA_DIR_PATH.'payment_requests/'.$val['request_time'].$filename3)){?><a title="<?php echo $val['request_time'].$filename3;?>" href="<?php echo $this->make_url('user/download_report/'.$val['request_time'].$filename3);?>"class="link_button"><?php echo $this->get_label('download');?></a><?php } else echo $this->get_label('na');?></td>



<?php 


foreach($customWithdrawals as $key => $value){

	$customName = str_replace(" ","_",$value['name']).'.csv';
?>

<td ><?php if(file_exists(DATA_DIR_PATH.'payment_requests/'.$val['request_time'].'_'.$customName)){?><a title="<?php echo $val['request_time'].'_'.$customName;?>" href="<?php echo $this->make_url('user/download_report/'.$val['request_time'].'_'.$customName);?>"class="link_button"><?php echo $this->get_label('download');?></a><?php } else echo $this->get_label('na');?></td>


<?php }?>



 
</tr>

<?php 
 }
}?>

<tr><td align="center" colspan="20"><?php echo $this->get_variable('link');?></td></tr>

</table>



<?php $this->dispatch("layout/footer");?>	
