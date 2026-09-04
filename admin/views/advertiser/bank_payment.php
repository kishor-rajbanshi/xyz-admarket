<?php
$this->dispatch("layout/header/17");

$validate=array(
		"b_name"=>array(
				"notNull"=>array($this->get_message("not null"))
		)
);

$uid=$this->get_variable('uid');
$amount=$this->get_variable('amount');
$uname=$this->get_variable("uname");
$pamount=$this->get_variable('pamount');
$country=$this->get_variable("country");

$form=$this->create_form();
$form->start("bank_payment",$this->make_url("advertiser/bank_payment/".$uid."/".$amount),"post",$validate); 
?>
<div class="sub_menu_main"><?php echo $this->get_label('bank details');?></div>
<div class="inner-box">
<div class="pages-input">


<table style="width: 100%;" cellpadding="0" cellspacing="0">
<tr><td ></td><td ></td><td height="10px"><?php echo $this->get_label('compulsory message');?></td></tr>
<tr>
<td style="width: 200px;"><?php echo $this->get_label('username');?></td><td></td>
<td><a href="<?php echo $this->make_url("user/profile/".$uid."/0");?>"><?php echo $uname;?></a></td>
</tr>

<?php echo $this->fund_calculator(2,$pamount,$uid,1);?>

<tr>
<td><?php echo $this->get_label('account number');?></td><td></td>
<td><input type="text" name="ac_number" id="ac_number" value="<?php echo $this->read_post_param('ac_number');?>" ></td>
</tr>



<tr>
<td><?php echo $this->get_label('bank name');?></td><td></td>
<td><input type="text" name="b_name" id="b_name" value="<?php echo $this->read_post_param('b_name');?>" ><span class="compulsory">*</span></td>
</tr>



<tr>
<td><?php echo $this->get_label('bank address line1');?></td><td></td>
<td><input type="text" name="b_add1" id="b_add1" value="<?php echo $this->read_post_param('b_add1');?>" ></td>
</tr>



<tr>
<td><?php echo $this->get_label('bank address line2');?></td><td></td>
<td><input type="text" name="b_add2" id="b_add2" value="<?php echo $this->read_post_param('b_add2');?>" ></td>
</tr>



<tr>
<td><?php echo $this->get_label('city');?></td><td></td>
<td><input type="text" name="city" id="city" value="<?php echo $this->read_post_param('city');?>" ></td>
</tr>


<tr>
<td><?php echo $this->get_label('state');?></td><td></td>
<td><input type="text" name="state" id="state" value="<?php echo $this->read_post_param('state');?>" ></td>
</tr>


<tr>
<td><?php echo $this->get_label('country');?></td><td></td>
<td><?php echo $this->get_country_name($country);?>
</td>
</tr>


<tr>
<td><?php echo $this->get_label('account holders name');?></td><td></td>
<td><input type="text" name="a_name" id="a_name" value="<?php echo $this->read_post_param('a_name');?>" ></td>
</tr>


<tr>
<td><?php echo $this->get_label('swift/routing number');?></td><td></td>
<td><input type="text" name="swift" id="swift" value="<?php echo $this->read_post_param('swift');?>" ></td>
</tr>


<tr>
<td><?php echo $this->get_label('comment');?></td><td></td>
<td>
<textarea name="comments" rows="4" cols="30"><?php echo $this->read_post_param('comments');?></textarea></td>
</tr>


<tr>
<td></td>
<td colspan="2">
<input type="hidden" name="uid" value="<?php echo $uid;?>" />
<input type="hidden" name="amount" value="<?php echo $pamount;?>" />
<input type="submit" name="submit" value="<?php echo $this->get_label('submit');?>" />
</td>
</tr>

</table>
</div>
</div>
<?php $form->end(); ?>
<?php $this->dispatch("layout/footer");?>