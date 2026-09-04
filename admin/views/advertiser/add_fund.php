<script type="text/javascript">
function changepayment()
{
	if($('#type').val() ==4)
	$('#bonus_row').show();
	else
	$('#bonus_row').hide();
}
</script>
<?php
$this->dispatch("layout/header/17");

$validate=array(
		"fund"=>array(
				"notNull"=>array($this->get_message("not null")),
				"isPositive"=>array($this->get_message("positive value"))
				
		)
);

$uid=$this->get_variable('uid');
$form=$this->create_form();
$form->start("add_fund",$this->make_url("advertiser/add_fund/").$uid,"post",$validate); 


?>
<div class="sub_menu_main"><?php echo $this->get_label('add fund to adv');?></div>

<?php $this->dispatch("links/links/3/".$uid);?>

<div class="inner-box">
<div class="pages-input">


<table style="width: 100%;" cellpadding="0" cellspacing="0">

<tr><td ></td><td ></td><td height="10px"><?php echo $this->get_label('compulsory message');?></td></tr>


<tr>
<td style="width: 150px;"><?php echo $this->get_label('username');?></td>
<td></td>
<td><a href="<?php echo $this->make_url("user/profile/".$uid."/0");?>"><?php echo $this->get_variable("usname");?></a></td>
</tr>


<tr>
<td><?php echo $this->get_label('amount');?> (<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>
<td></td>
<td><input type="text" name="fund" id="fund"  onkeyup="this.value = this.value.replace(/[^0-9\.]/g,'');" value="<?php echo $this->get_variable('fund');?>" size="8" >

<span class="compulsory"> *</span></td>
</tr>

<tr>
<td><?php echo $this->get_label('payment mode');?></td>
<td></td>
<td>
<select name="type" id="type" onchange="javascript:return changepayment();" style="width: 125px;">
<?php echo $this->get_payment_list($this->get_variable('type'),1);?>
</select>
</td>
</tr>


<tr id="bonus_row">
<td><?php echo $this->get_label('comment');?></td>
<td></td>
<td><textarea name="comments" rows="4" cols="25"><?php echo $this->get_variable('comments');?></textarea></td>
</tr>

<tr>
<td></td><td></td>
<td  align="left">
<input type="hidden" name="uid" value="<?php echo $uid;?>" />
<input type="submit" name="submit" value="<?php echo $this->get_label('add funds');?>" /></td>
</tr>

</table>
</div>
</div>
<?php $form->end(); ?>
<?php $this->dispatch("layout/footer");?>
<script type="text/javascript">
changepayment();
</script>