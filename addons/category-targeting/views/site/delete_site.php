<?php 
$sid=$this->get_variable("sid");
$category=$this->get_variable("category");
$status=$this->get_variable("status");
$page=$this->get_variable("page");
?>
<table class="content_table">

<tr><td class="page_heading"><?php echo $this->get_label('site delete confirmation');?></td></tr>
<tr><td height="10px"></td></tr>

<tr><td style="font-size: 12px;"><?php echo $this->get_label("site deletion confirmation");?></td></tr>
<tr>
<td>
<?php 
$form=$this->create_form();
$form->start("deletesites",'',"post");
?>
<input type="hidden" name="sid" id="sid" value="<?php echo $sid;?>" />
<input type="hidden" name="category" id="category" value="<?php echo $category;?>" />
<input type="hidden" name="status" id="status" value="<?php echo $status;?>" />
<input type="hidden" name="page" id="page" value="<?php echo $page;?>" />
    
<input type="submit" name="confirm" id="confirm" value="<?php echo $this->get_label('confirm delete');?>" />

<?php $form->end(); ?> 
</tr>
</table>