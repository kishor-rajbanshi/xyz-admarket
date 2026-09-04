<?php
$this->dispatch("layout/header/4/_46");


$validate=array(
		"ctype=>0"=>array("ctext"=>array(
				"notNull"=>array($this->get_message("not null"))

		))
);


$id=$this->get_variable('id');
$ctext=$this->get_variable('ctext');
$ctype=$this->get_variable('ctype');
$oldimage=$this->get_variable('oldimage');

$icontype=$this->get_variable('icontype');
$iconcontent=$this->get_variable('iconcontent');

$form=$this->create_form();
$form->start("editcredit",$this->make_url("credit/edit"),"post",$validate); 
?>

<div class="sub_menu_main"><?php echo $this->get_label('edit credit text');?></div>

<?php $this->dispatch("links/links/20");?>


<div class="inner-box">
<div class="pages-input">


<table style="width: 100%;" cellpadding="0" cellspacing="0">
<tr><td ></td><td><?php echo $this->get_label('compulsory message');?></td></tr>

<tr>
<td style="width:150px;"><?php echo $this->get_label('credit type');?></td>
<td style="font-weight: bold;">
<input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
<input type="hidden" name="ctype" id="ctype" value="<?php echo $ctype;?>" />

<?php 
if($ctype ==0)
echo $this->get_label('text credit');
else if($ctype ==1)
echo $this->get_label('image credit');
?>
</td>
</tr>

<?php if($ctype ==0){?>
<tr class="textcredit">
<td><?php echo $this->get_label('credit text');?></td>
<td><input type="text" name="ctext" id="ctext" value="<?php echo $ctext;?>" /><span class="compulsory">*</span></td>
</tr>
<?php }else if($ctype ==1){?>
<tr class="imagecredit">
<td width="100px"><?php echo $this->get_label('credit image');?></td>
<td><input type="file" name="cimage" id="cimage" size="10"><span class="compulsory">*</span>


<?php if($oldimage !=''){?>
<img src="<?php echo "../".DATA_DIR."/credit/".$oldimage;?>" style="max-width: 150px;max-height: 100px;" />
<?php }?>

<br/>

<span class="notification">[<?php echo $this->get_label('supported image format');?>]</span>
<br/>
<span class="notification">[<?php echo $this->get_label('max credit dimension');?>]</span>
</td>
</tr>
<?php }?>



<tr>
<td><?php echo $this->get_label('credit icon type');?></td>
<td>
<select name="icontype" id="icontype" onchange="javascript:return changecrediticontype();">
<option value="0" <?php if($icontype ==0){?> selected="selected"<?php }?> ><?php echo $this->get_label('text icon');?></option>
<option value="1" <?php if($icontype ==1){?> selected="selected"<?php }?> ><?php echo $this->get_label('image icon');?></option>
</select>
</td>
</tr>


<tr class="textcrediticon">
<td><?php echo $this->get_label('text icon');?></td>
<td><input type="text" name="icontext" id="icontext" value="<?php if($icontype ==0){echo $iconcontent;}?>" /></td>
</tr>


<tr class="imagecrediticon">
<td><?php echo $this->get_label('image icon');?></td>
<td><input type="file" name="iconimage" id="iconimage" size="10" />

<?php if($icontype ==1 && $iconcontent !=''){?>
<img src="<?php echo "../".DATA_DIR."/credit/".$iconcontent;?>" style="max-width: 15px;max-height: 15px;" />
<?php }?>

<br/>
<span class="notification">[<?php echo $this->get_label('supported image format');?>]</span>
<br/>
<span class="notification">[<?php echo $this->get_label('max icon dimension');?>]</span>
</td>
</tr>

<tr>
<td></td>
<td align="left"><input type="submit" name="submit" value="<?php echo $this->get_label('update');?>"></td>
</tr>
</table>
</div> 
</div>
<?php $form->end(); ?>
<?php $this->dispatch("layout/footer");?>
<script type="text/javascript">
function changecrediticontype()
{	
	if($('#icontype').val() ==0)
	{
		$('.textcrediticon').show();
		$('.imagecrediticon').hide();
	}
	else if($('#icontype').val() ==1)
	{
		$('.textcrediticon').hide();
		$('.imagecrediticon').show();
	}
}


$(document).ready(function() 
{
	changecrediticontype();
});
</script>