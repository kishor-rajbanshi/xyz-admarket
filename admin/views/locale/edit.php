<?php 
$this->dispatch("layout/header/3/_39");

$validate=array(
		"name"=>array(
				"notNull"=>array($this->get_message("not null"))
		),
		"desc"=>array(
				"notNull"=>array($this->get_message("not null"))
		)
);


$form=$this->create_form();
$form->start("edit_locale","","post",$validate); 
?>

<div class="sub_menu_main"><?php echo $this->get_label('edit locale');?></div>
<?php $this->dispatch("links/links/56");?>


<div class="inner-box">
<div class="pages-input">


<table style="width: 100%;" cellpadding="0" cellspacing="0">
    
  <tr><td></td><td><?php echo $this->get_label('compulsory message');?></td></tr>
  
  <tr>
  <td width="120px"><?php echo $this->get_label('locale name'); ?></td>
  <td>
  <input type="hidden" name="id" id="id" value="<?php echo $this->get_variable('id'); ?>"/>
  <input name="name" type="text" id="name" value="<?php echo $this->get_variable('name'); ?>"><span class="compulsory">*</span>
  </td>
  </tr>
  
  <tr>
  <td ><?php echo $this->get_label('locale desc'); ?></td>
  <td><input name="desc" type="text" id="desc" value="<?php echo $this->get_variable('desc'); ?>"><span class="compulsory">*</span></td>
  </tr>
   
  
  <tr>
  <td ><?php echo $this->get_label('locale direction'); ?></td>
  <td>
  <select name="direction" id="direction">
  <option value="0" <?php if($this->get_variable('direction') ==0){ echo "selected";}?>><?php echo $this->get_label('ltr');?></option>
  <option value="1" <?php if($this->get_variable('direction') ==1){ echo "selected";}?>><?php echo $this->get_label('rtl');?></option>
  </select>
  </td>
  </tr> 
  
   
  <tr>
  <td></td>
  <td><input type="submit" name="submit" value="<?php echo $this->get_label('submit'); ?>"></td>
  </tr>
  </table>
</div>
</div>
<?php 
$form->end(); 
$this->dispatch("layout/footer");
?>