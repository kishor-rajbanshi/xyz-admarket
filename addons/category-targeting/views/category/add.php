<?php 
$validate=array(
		"category"=>array(
				"notNull"=>array($this->get_message("mandatory"))
		),
);

$pid=$this->get_variable('pid');
$category=$this->get_variable('category');
$keyword=$this->get_variable('keyword');
$description=$this->get_variable('description');

$form=$this->create_form();
$form->start("addcategory","","post",$validate);?>


<div class="sub_menu_main"><?php echo $this->get_label('add category');?></div>

<?php $this->dispatch("links/links/31");?>


<div class="inner-box">
<div class="pages-input">

<table style="width: 100%" cellpadding="0" cellspacing="0">

  <tr>
  <td >&nbsp;<input type="hidden" name="pid" id="pid" value="<?php echo $pid; ?>"/></td>
  <td><?php echo $this->get_label('compulsory message');?></td>
  </tr>
  
 
  <tr>
    <td width="150px"><?php echo $this->get_label('category name'); ?></td>
    <td><input name="category" type="text" id="category" value="<?php echo $category; ?>"><span class="compulsory">*</span></td>
  </tr>
  
  
  <tr>
    <td width="150px"><?php echo $this->get_label('meta keyword'); ?><br>(<?php echo $this->get_label('comma seperated'); ?>)</td>
    <td><input name="keyword" type="text" id="keyword" value="<?php echo $keyword; ?>"  size="30"></td>
  </tr>
  
 
    <tr>
    <td ><?php echo $this->get_label('meta description'); ?></td>
    <td><textarea name="description" id="description" rows="5" cols="43" ><?php echo $description; ?></textarea></td>
  </tr>
  
    
    <tr>
    <td >&nbsp;</td>
    <td><input type="submit" name="Submit" value="<?php echo $this->get_label('submit'); ?>"></td>
  </tr>  
</table>
</div>
</div>
<?php $form->end(); ?>