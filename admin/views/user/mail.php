<?php
$this->dispatch("layout/header/17");



$uid=$this->get_variable('uid');
$frompg=$this->get_variable('frompg');
$message=$this->get_variable('message');
$subject=$this->get_variable('subject');
$pg=$this->get_variable("pg");
$status=$this->get_variable('status');
$type=$this->get_variable('type');
$search_by=$this->get_variable('search_by');


$form=$this->create_form();
$form->start("sendmail",$this->make_url("user/mail/").$uid."/".$frompg."/".$status."/".$type."/".$search_by."/".$pg,"post");


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

<?php $this->dispatch("links/links/4/".$uid);?>

<div class="inner-box">
<div class="pages-input">


<table style="width: 100%;" cellpadding="0" cellspacing="0">

<tr><td ></td><td><?php echo $this->get_label('compulsory message');?></td></tr>

<tr>
<td style="width: 200px;"><?php echo $this->get_label('subject');?></td>
<td><input type="text" name=subject value="<?php echo $subject;?>" size="34"></td><td><span class="compulsory">*</span></td>
</tr>

<tr>
<td><?php echo $this->get_label('message');?></td>
<td>
 <textarea name="message" id="message" style="width: 90%;margin-top: 5px;"><?php echo $this->get_variable('message');?></textarea>



</td><td><span class="compulsory">*</span></td>
</tr>

<tr><td></td><td align="left"><input type="submit" name="submit" value="<?php echo $this->get_label('send mail');?>"></td></tr>
</table>
</div>
</div>
<?php $form->end(); ?>
<?php $this->dispatch("layout/footer");?>