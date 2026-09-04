<?php
$this->dispatch("layout/header/4/_50");

$pg 				= $this->get_variable('pg');

if($_POST)
{
	$fontID			= $this->get_variable('fontID');
	$name           = $this->get_variable('name');
	$slug_name      = $this->get_variable('slug_name');
}
else 
{
	$res            = $this->get_result('res');
	$value1         = $res[0];

	$fontID			= $value1['id'];	
	$name 			= $value1['name'];
	$slug_name 		= $value1['slug_name'];	
}

$validate=array(
		"name"=>array(
				"notNull"=>array($this->get_message("not null"))
		),
		"slug_name"=>array(
				"notNull"=>array($this->get_message("not null"))
		) 
);

$form = $this->create_form();
$form->start("edit",$this->make_url("adblock/edit_external_font/".$fontID."/".$pg),"post",$validate);
?>

<div class="sub_menu_main"><?php echo $this->get_label('edit font');?></div>

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

<tr><td></td><td align="left">
<input type="hidden" name="fontID" id="fontID" value="<?php echo $fontID;?>" />
<input type="hidden" name="pg" id="pg" value="<?php echo $pg;?>" />
<input type="submit" name="submit" value="<?php echo $this->get_label('submit');?>"></td></tr>
</table>
</div>
</div>
<?php $form->end(); ?>
<?php $this->dispatch("layout/footer");?>