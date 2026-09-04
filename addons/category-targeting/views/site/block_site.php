<?php
$message=$this->get_variable('message');
$subject=$this->get_variable('subject');
$yesno=$this->get_variable('yesno');

$sid=$this->get_variable("sid");
$owner=$this->get_variable('owner');
$category=$this->get_variable("category");
$status=$this->get_variable("status");

$featured=$this->get_variable("featured");
$hot=$this->get_variable("hot");
$search_by=$this->get_variable("search_by");
$search_text=$this->get_variable("search_text");

$page=$this->get_variable("page");

$form=$this->create_form();
$form->start("blocksite",'',"post");
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
<div class="sub_menu_main"><?php echo $this->get_label('send mail to pub');?></div>

<?php $this->dispatch("links/links/34");?>


<table style="width: 100%;">
<tr><td ></td><td height="10px"><?php echo $this->get_label('compulsory message');?></td></tr>
<tr><td colspan="2" height="10px"></td></tr>

<tr>
<td width="180px"><?php echo $this->get_label('subject');?></td>
<td><input type="text" name=subject value="<?php echo $subject;?>" size="34"></td><td><span class="compulsory">*</span></td>
</tr>
<tr><td colspan="2" height="4px"></td></tr>
<tr>
<td><?php echo $this->get_label('message');?></td>
<td width="550px">
<textarea name="message" id="message" style="width: 90%;margin-top: 5px;"><?php echo $message;?></textarea>


</td><td><span class="compulsory">*</span></td>
</tr>

<tr><td colspan="2" height="4px"></td></tr>
<tr><td></td><td><input type="checkbox" name="yesno" id="yesno" value="1" style="vertical-align: top; " <?php if($yesno==1) {echo "checked";}?>/>&nbsp;&nbsp;<?php echo $this->get_label('send mail or not');?></td></tr>

<tr><td colspan="2" height="10px"></td></tr>

<tr><td></td><td  align="left">

<input type="hidden" name="sid" id="sid" value="<?php echo $sid;?>" />
<input type="hidden" name="owner" id="owner" value="<?php echo $owner;?>" />
<input type="hidden" name="category" id="category" value="<?php echo $category;?>" />
<input type="hidden" name="status" id="status" value="<?php echo $status;?>" />
<input type="hidden" name="page" id="page" value="<?php echo $page;?>" />

<input type="hidden" name="featured" id="featured" value="<?php echo $featured;?>" />
<input type="hidden" name="hot" id="hot" value="<?php echo $hot;?>" />
<input type="hidden" name="search_by" id="search_by" value="<?php echo $search_by;?>" />
<input type="hidden" name="search_text" id="search_text" value="<?php echo $search_text;?>" />

<input type="submit" name="submit" value="<?php echo $this->get_label('send mail');?>"></td></tr>
</table>
<?php $form->end(); ?>