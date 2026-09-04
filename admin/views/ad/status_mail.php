<?php
$this->dispatch("layout/header/1/_11");
$validate=array(
		"subject"=>array(
				"notNull"=>array($this->get_message("not null"))
		),
		"message"=>array(
				"notNull"=>array($this->get_message("not null"))
				)
);




$aid=$this->get_variable('aid');
$status=$this->get_variable('status');
$frompg=$this->get_variable('frompg');
$subject=$this->get_variable('subject');
$message=$this->get_variable('message');
$yes_no=$this->get_variable('yes_no');


$duration=$this->get_variable('duration');
$at=$this->get_variable('at');
$st=$this->get_variable('st');
$pg=$this->get_variable('pg');
$adv=$this->get_variable('adv');
$adpricing=$this->get_variable('adpricing');
$search_by=$this->get_variable('search_by');
$premiumStatus=$this->get_variable('premiumStatus');


$form=$this->create_form();
$form->start("status_mail",$this->make_url("ad/status_mail"),"post",$validate);



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

<div class="sub_menu_main"><?php echo $this->get_label('send mail to adv');?></div><?php $this->dispatch("links/links/10");?>

<input type="hidden" name="search_by" value="<?php echo $search_by;?>">
<table style="width: 100%;">
<tr><td ></td><td height="10px"><?php echo $this->get_label('compulsory message');?></td></tr>
<tr><td colspan="2" height="10px"></td></tr>
<tr>
<td width="50px"><?php echo $this->get_label('subject');?></td>
<td><input type="text" name="subject"  value="<?php echo $subject;?>" size="34"></td><td><span class="compulsory">*</span></td>
</tr>
<tr><td colspan="2" height="5px"></td></tr>
<tr>
<td><?php echo $this->get_label('message');?></td>
<td >
  <textarea name="message" id="message" style="width: 90%;margin-top: 5px;"><?php echo $this->get_variable('message');?></textarea>

</td><td><span class="compulsory">*</span></td>
</tr>

<tr><td colspan="2" height="4px"></td></tr>
<tr><td></td><td><input type="checkbox" name="yes_no" id="yes_no" value="1" style="vertical-align: top; " <?php if($yes_no==1) {echo "checked";}?>/>&nbsp;&nbsp;<?php echo $this->get_label('send mail or not');?></td></tr>


<tr><td colspan="2" height="10px"></td></tr>

<tr>
<td></td>
<td   align="left">



<input type="hidden" name="duration" value="<?php echo $duration;?>">
<input type="hidden" name="at" value="<?php echo $at;?>">
<input type="hidden" name="st" value="<?php echo $st;?>">
<input type="hidden" name="pg" value="<?php echo $pg;?>">
<input type="hidden" name="adv" value="<?php echo $adv;?>">
<input type="hidden" name="adpricing" value="<?php echo $adpricing;?>">
<input type="hidden" name="premiumStatus" value="<?php echo $premiumStatus;?>">


<input type="hidden" name="aid" value="<?php echo $aid?>">
<input type="hidden" name="status" value="<?php echo $status?>">
<input type="hidden" name="frompg" value="<?php echo $frompg?>">




<input type="submit" name="submit" value="<?php echo $this->get_label('send mail');?>">
</td>
</tr>
<tr><td colspan="2" height="10px"></td></tr>
</table>
<?php $form->end(); ?>
<?php $this->dispatch("layout/footer");?>