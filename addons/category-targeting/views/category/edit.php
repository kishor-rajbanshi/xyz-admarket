<?php
$validate=array(
		"category"=>array(
				"notNull"=>array($this->get_message("mandatory"))
		)
);

$pid=$this->get_variable('pid');
$id=$this->get_variable('id');
$category=$this->get_variable('category');
$keyword=$this->get_variable('keyword');
$description=$this->get_variable('description');
$iab_id_category=$this->get_variable('iab_id_category');
$exclusive_targeting = $this->get_variable('exclusive_targeting');


$form=$this->create_form();
$form->start("edit_category","","post",$validate);?>

<div class="sub_menu_main"><?php echo $this->get_label('edit category');?></div>

<?php $this->dispatch("links/links/31");?>

<div class="inner-box">
<div class="pages-input">
<table style="width: 100%;" cellpadding="0" cellspacing="0">
  <tr>
    <td ><input type="hidden" name="id" id="id" value="<?php echo $id; ?>" /></td>
    <td><?php echo $this->get_label('compulsory message');?></td>
  </tr>

  <tr>
    <td width="150px"><?php echo $this->get_label('category name'); ?></td>
    <td ><input name="category" type="text" id="category" value="<?php echo $category; ?>"><span class="compulsory">*</span></td>
  </tr>

	<?php if($pid == 0){?>
		<tr>
			<td><?php echo $this->get_label('exclusive targeting only'); ?></td>
			<td><input type="checkbox" name="exclusive_targeting" id="exclusive_targeting" value="1" <?php if($exclusive_targeting == 1){?> checked="checked" <?php } ?> /></td>
		</tr>
	<?php } ?>

<tr>
<td style="width:150px;"><?php echo $this->get_label('iab id'); ?></td>
<td>
<select name="iab_id_category" id="iab_id_category" style="width: 233px;">
<option value="0"><?php echo $this->get_label('select');?></option>
<?php echo CategoryHelper::get_category_dropdown(0,0,$iab_id_category,1);?>
</select>
</td>
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