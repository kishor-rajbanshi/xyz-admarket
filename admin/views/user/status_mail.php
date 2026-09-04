<?php
$this->dispatch("layout/header/17");



$uid=$this->get_variable('uid');
$frompg=$this->get_variable('frompg');
$statusadv=$this->get_variable('statusadv');
$statuspub=$this->get_variable('statuspub');



$subject=$this->get_variable('subject');
$message=$this->get_variable('message');
$yes_no=$this->get_variable('yes_no');

$pg=$this->get_variable("pg");
$status=$this->get_variable('status');
$type=$this->get_variable('type');
$search_by=$this->get_variable('search_by');

$form=$this->create_form();
$form->start("status_mail",$this->make_url("user/status_mail/").$uid."/".$statusadv."/".$statuspub."/".$frompg."/".$status."/".$type."/".$search_by."/".$pg,"post");

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

<?php $this->dispatch("links/links/6/".$uid);?>

<div class="inner-box">
<div class="pages-input">


<table style="width: 100%;" cellpadding="0" cellspacing="0">
<tr><td ></td><td><?php echo $this->get_label('compulsory message');?></td></tr>

<tr>
<td style="width: 150px;"><?php echo $this->get_label('subject');?></td>
<td><input type="text" name=subject value="<?php echo $subject;?>" size="34"/></td><td><span class="compulsory">*</span></td>
</tr>

<tr>
<td><?php echo $this->get_label('message');?></td>
<td>
 <textarea name="message" id="message" style="width: 90%;margin-top: 5px;"><?php echo $this->get_variable('message');?></textarea>


</td><td><span class="compulsory">*</span></td>
</tr>

<tr><td></td><td><input type="checkbox" name="yes_no" id="yes_no" value="1" style="vertical-align: top; " <?php if($yes_no==1) {echo "checked";}?>/>&nbsp;&nbsp;<?php echo $this->get_label('send mail or not');?></td></tr>

<tr><td></td>
<td  align="left">
<input type="hidden" name="statusadv" value="<?php echo $statusadv;?>">
<input type="hidden" name="statuspub" value="<?php echo $statuspub;?>">
<input type="hidden" name="frompg" value="<?php echo $frompg;?>">
<input type="hidden" name="uid" value="<?php echo $uid;?>">
<input type="submit" name="submit" value="<?php echo $this->get_label('send mail');?>">
</td>
</tr>

</table>
</div>
</div>
<?php $form->end(); ?>
<?php $this->dispatch("layout/footer");?>