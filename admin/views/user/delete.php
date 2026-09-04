<?php
$this->dispatch("layout/header");



$uid=$this->get_variable('uid');
$message=$this->get_variable('message');
$subject=$this->get_variable('subject');
$yes_no=$this->get_variable('yes_no');

$pg=$this->get_variable("pg");
$status=$this->get_variable('status');
$type=$this->get_variable('type');
$search_by=$this->get_variable("search_by");

$form=$this->create_form();
$form->start("delete",$this->make_url("user/delete/".$uid."/".$status."/".$type."/".$pg."/".$search_by),"post");


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

<?php $this->dispatch("links/links/7/".$uid);?>

<div class="inner-box">
<div class="pages-input">


<table style="width: 100%;" cellpadding="0" cellspacing="0">
<tr><td ></td><td><?php echo $this->get_label('compulsory message');?></td></tr>

<tr>
<td style="width: 200px;"><?php echo $this->get_label('subject');?></td>
<td><input type="text" name=subject value="<?php echo $subject;?>" size="34"></td><td><span class="compulsory">*</span></td>
</tr>

<tr>
<td><?php echo $this->get_label('Message');?></td>
<td>
 <textarea name="message" id="message" style="width: 90%;margin-top: 5px;"><?php echo $this->get_variable('message');?></textarea>


</td><td><span class="compulsory">*</span></td>
</tr>

<tr><td></td><td><input type="checkbox" name="yes_no" id="yes_no" value="1" style="vertical-align: top; " <?php if($yes_no==1) {echo "checked";}?>/>&nbsp;&nbsp;<?php echo $this->get_label('send mail or not');?></td></tr>

<tr><td></td><td  align="left">
<input type="hidden" name="uid" id="uid" value="<?php echo $uid;?>"/>
<input type="submit" name="submit" value="<?php echo $this->get_label('send mail');?>"></td></tr>
</table>
</div>
</div>
<?php $form->end(); ?>
<?php $this->dispatch("layout/footer");?>