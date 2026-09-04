<?php
$this->dispatch("layout/header/4/_50");

$validate=array(
		"name"=>array(
				"notNull"=>array($this->get_message("not null"))
		),
		"slug_name"=>array(
				"notNull"=>array($this->get_message("not null"))
		),
		"font_file"=>array(
				"notNull"=>array($this->get_message("not null"))
		)
);

$form = $this->create_form();
$form->start("create",$this->make_url("adblock/add_external_font"),"post",$validate); 

$name           = $this->get_variable('name');
$slug_name      = $this->get_variable('slug_name');

?>
<div class="sub_menu_main"><?php echo $this->get_label('add font');?></div>

<?php $this->dispatch("links/links/69");?>

<div class="inner-box">
<div class="pages-input">

<table style="width: 100%;" cellpadding="0" cellspacing="0">
<tr><td ></td><td><?php echo $this->get_label('compulsory message');?></td></tr>


<tr>
<td style="width: 170px;"><?php echo $this->get_label('name');?></td>
<td><input type="text" name="name" value="<?php echo $name;?>" /><span class="compulsory">*</span></td>
</tr>
 
<tr>
<td style="width: 170px;"><?php echo $this->get_label('slug name');?></td>
<td><input type="text" name="slug_name" value="<?php echo $slug_name;?>" /><span class="compulsory">*</span></td>
</tr>

<tr>
<td style="width: 170px;"><?php echo $this->get_label('font file');?></td>
<td>
<input type="file" name="font_file" size="10"><span class="compulsory">*</span><br/>
<span class="notification">[<?php echo $this->get_label('supported font files');?>]</span>
</td>
</tr>

<tr><td></td><td  align="left"><input type="submit" name="submit" value="<?php echo $this->get_label('submit');?>"></td></tr>
</table>
</div>
</div>
<?php $form->end(); ?>
<?php $this->dispatch("layout/footer");?>