<?php
$validate=array(
		"transactionid"=>array(
				"notNull"=>array($this->get_message("not null"))
		)
);

$uid=$this->get_variable('uid');
$uname=$this->get_variable("uname");
$amount=$this->get_variable('amount');
$pamount=$this->get_variable('pamount');

$form=$this->create_form();
$form->start("payment","","post",$validate); 
?>
<div class="sub_menu_main"><?php echo $this->get_label('stripe details');?></div>

<div class="inner-box">
<div class="pages-input">

<table style="width: 100%;" cellpadding="0" cellspacing="0">
<tr><td ></td><td ></td><td height="10px"><?php echo $this->get_label('compulsory message');?></td></tr>

<tr>
<td width="20%"><?php echo $this->get_label('username');?></td>
<td></td>
<td><a href="<?php echo $this->make_url("user/profile/".$uid."/0",ADMIN_DIR);?>"><?php echo $uname;?></a></td>
</tr>

<?php echo $this->fund_calculator(8,$pamount,$uid,1);?>

<tr>
<td><?php echo $this->get_label('transaction id');?></td>
<td></td>
<td><input type="text" name="transactionid" id="transactionid" value="<?php echo $this->read_post_param('transactionid');?>" ><span class="compulsory">*</span></td>
</tr>

<tr>
<td></td>
<td></td>
<td  align="left">
<input type="hidden" name="uid" value="<?php echo $uid;?>" />
<input type="hidden" name="amount" value="<?php echo $pamount;?>" />
<input type="submit" name="submit" value="<?php echo $this->get_label('submit');?>" /></td>
</tr>
</table>

</div>
</div>
<?php $form->end(); ?>