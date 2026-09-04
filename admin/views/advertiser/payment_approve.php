<?php
$this->dispatch("layout/header/21");
$validate=array(
		"subject"=>array(
				"notNull"=>array($this->get_message("not null"))
		),
		"message"=>array(
						"notNull"=>array($this->get_message("not null"))
		)
);
$sid=$this->get_variable('sid');
$uid=$this->get_variable('uid');
$yes_no=$this->get_variable('yes_no');
$frompage=$this->get_variable('frompage');


$pmt=$this->get_variable('pmt');
$st=$this->get_variable('st');
$pg=$this->get_variable('pg');
$usr=$this->get_variable('usr');




$form=$this->create_form();
$form->start("payment_mail",$this->make_url("advertiser/payment_approve/".$sid."/".$frompage),"post",$validate);


?>
<script src="<?php echo BASE;?>library/ckeditor/ckeditor.js"></script>
<script>
window.onload = function() {
	CKEDITOR.replace( 'message', {
		filebrowserBrowseUrl: '<?php echo $this->make_url("ckeditor/browse");?>',
		filebrowserUploadUrl: '<?php echo $this->make_url("ckeditor/upload");?>',
		
	   	});
};
</script>

<div class="sub_menu_main"><?php echo $this->get_label('send mail to adv');?></div>

<?php $this->dispatch("links/links/23/".$uid."/".$sid);?>

<div class="inner-box">
<div class="pages-input">


<table style="width: 100%;" cellpadding="0" cellspacing="0">

<tr><td ></td><td><?php echo $this->get_label('compulsory message');?></td></tr>

<tr>
<td style="width: 200px;"><?php echo $this->get_label('subject');?></td>
<td><input type="text" name=subject value="<?php echo $this->get_variable('subject');?>" size="34"></td><td><span class="compulsory">*</span></td>
</tr>
<tr>
<td><?php echo $this->get_label('message');?></td>
<td>
 <textarea name="message" id="message" style="width: 90%;margin-top: 5px;"><?php echo $this->get_variable('message');?></textarea>

</td><td><span class="compulsory">*</span><br></td>

</tr>
<tr>
<td><?php echo $this->get_label('comment');?></td>
<td><textarea name="comments" rows="4" cols="48"><?php echo $this->get_variable('comments');?></textarea></td><td></td>
</tr>


<tr><td></td><td><input type="checkbox" name="yes_no" id="yes_no" value="1" style="vertical-align: top; " <?php if($yes_no==1) {echo "checked";}?>/>&nbsp;&nbsp;<?php echo $this->get_label('send mail or not');?></td></tr>

<tr><td></td>
<td  align="left">


<input type="hidden" name="pmt" value="<?php echo $pmt;?>"/>
<input type="hidden" name="st" value="<?php echo $st;?>"/>
<input type="hidden" name="pg" value="<?php echo $pg;?>"/>
<input type="hidden" name="usr" value="<?php echo $usr;?>"/>


<input type="hidden" name="sid" value="<?php echo $sid;?>"/>
<input type="hidden" name="frompage" value="<?php echo $frompage;?>"/>
<input type="submit" name="submit" value="<?php echo $this->get_label('send mail');?>"/>
</td>
</tr>
</table>
</div> 
</div>
<?php $form->end(); ?>
<?php $this->dispatch("layout/footer");?>