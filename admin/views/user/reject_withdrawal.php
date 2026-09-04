<?php
$this->dispatch("layout/header/22");
$validate=array(
		"subject"=>array(
				"notNull"=>array($this->get_message("not null"))
		),
		"message"=>array(
						"notNull"=>array($this->get_message("not null"))
		),
);
$sid=$this->get_variable('sid');
$uid=$this->get_variable('uid');
$yes_no=$this->get_variable('yes_no');
$frompage=$this->get_variable('frompage');
$payment_mode=$this->get_variable('payment_mode');
$payment_type=$this->get_variable('payment_type');

$pmt=$this->get_variable('pmt');
$st=$this->get_variable('st');
$pg=$this->get_variable('pg');
$usr=$this->get_variable('usr');
$pdate=$this->get_variable('pdate');
$rdate=$this->get_variable('rdate');


$form=$this->create_form();
$form->start("payment_mail",$this->make_url("user/reject_withdrawal/".$sid."/".$frompage),"post",$validate);

?>
<div class="sub_menu_main"><?php echo $this->get_label('send mail to pub');?></div>

<?php $this->dispatch("links/links/24/".$uid."/".$sid);?>

<div class="inner-box">
<div class="pages-input">


<table style="width: 100%;" cellpadding="0" cellspacing="0">
<tr><td ></td><td><?php echo $this->get_label('compulsory message');?></td></tr>

<tr>
<td style="width: 150px;"><?php echo $this->get_label('subject');?></td>
<td><input type="text" name=subject value="<?php echo $this->get_variable('subject');?>" size="34"></td><td><span class="compulsory">*</span></td>
</tr>

<tr>
<td><?php echo $this->get_label('message');?></td>
<td>

<?php 
				$oFCKeditor = new FCKeditor('message') ;
				$oFCKeditor->BasePath = LIB_DIR_PATH.'FCKeditor/' ;
				$oFCKeditor->Value = $this->get_variable('message');
				$oFCKeditor->Create() ;
?>
</td><td><span class="compulsory">*</span></td>
</tr>
<?php if($payment_mode !=5)
{
?>
<tr>
<td><?php echo $this->get_label('comments');?></td>
<td><textarea name="comments" rows="4" cols="48"><?php echo $this->get_variable('comments');?></textarea></td><td></td>
</tr>
<?php 
}
?>

<tr><td></td><td><input type="checkbox" name="yes_no" id="yes_no" value="1" style="vertical-align: top; " <?php if($yes_no==1) {echo "checked";}?>/>&nbsp;&nbsp;<?php echo $this->get_label('send mail or not');?></td></tr>

<tr><td></td>
<td  align="left">
<input type="hidden" name="sid" value="<?php echo $sid?>">
<input type="hidden" name="frompage" value="<?php echo $frompage?>">
<input type="hidden" name="pmt" value="<?php echo $pmt;?>"/>
<input type="hidden" name="st" value="<?php echo $st;?>"/>
<input type="hidden" name="pg" value="<?php echo $pg;?>"/>
<input type="hidden" name="usr" value="<?php echo $usr;?>"/>
<input type="hidden" name="payment_type" value="<?php echo $payment_type;?>"/>
<input type="hidden" name="pdate" value="<?php echo $pdate;?>"/>
<input type="hidden" name="rdate" value="<?php echo $rdate;?>"/>
<input type="submit" name="submit" value="<?php echo $this->get_label('send mail');?>">
</td>
</tr>
</table>
</div>
</div>
<?php $form->end(); ?>
<?php $this->dispatch("layout/footer");?>