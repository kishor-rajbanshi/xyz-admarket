<?php
$this->dispatch("layout/header/4/_46");



$validate=array(
		"ctype=>0"=>array("ctext"=>array(
				"notNull"=>array($this->get_message("not null"))

		)),
		"ctype=>1"=>array("cimage"=>array(
				"notNull"=>array($this->get_message("not null"))
		))	
);
$form=$this->create_form();
$form->start("createcredit",$this->make_url("credit/create"),"post",$validate); 

$ctype=$this->get_variable('ctype');
$icontype=$this->get_variable('icontype');
?>
<div class="sub_menu_main"><?php echo $this->get_label('create new credit text');?></div>

<?php $this->dispatch("links/links/20");?>


<div class="inner-box">
<div class="pages-input">


<table style="width: 100%;" cellpadding="0" cellspacing="0">
<tr><td ></td><td><?php echo $this->get_label('compulsory message');?></td></tr>

<tr>
<td style="width:150px;"><?php echo $this->get_label('credit type');?></td>
<td>
<select name="ctype" id="ctype" onchange="javascript:return changecredittype();">
<option value="0" <?php if($ctype =="0"){ echo "selected"; } ?>><?php echo $this->get_label('text credit');?></option>
<option value="1" <?php if($ctype =="1"){ echo "selected"; } ?>><?php echo $this->get_label('image credit');?></option>
</select>
</td>
</tr>


<tr class="textcredit">
<td><?php echo $this->get_label('credit text');?></td>
<td><input type="text" name="ctext" id="ctext" value="<?php echo $this->get_variable('ctext');?>"><span class="compulsory">*</span></td>
</tr>


<tr class="imagecredit">
<td><?php echo $this->get_label('credit image');?></td>
<td><input type="file" name="cimage" id="cimage" size="10" /><span class="compulsory">*</span><br/>

<span class="notification">[<?php echo $this->get_label('supported image format');?>]</span>
<br/>
<span class="notification">[<?php echo $this->get_label('max credit dimension');?>]</span>
</td>
</tr>

<tr>
<td><?php echo $this->get_label('credit icon type');?></td>
<td>
<select name="icontype" id="icontype" onchange="javascript:return changecrediticontype();">
<option value="0" <?php if($icontype ==0){?> selected="selected"<?php }?>><?php echo $this->get_label('text icon');?></option>
<option value="1" <?php if($icontype ==1){?> selected="selected"<?php }?>><?php echo $this->get_label('image icon');?></option>
</select>
</td>
</tr>


<tr class="textcrediticon">
<td><?php echo $this->get_label('text icon');?></td>
<td><input type="text" name="icontext" id="icontext" value="<?php echo $this->get_variable('icontext');?>" /></td>
</tr>


<tr class="imagecrediticon">
<td><?php echo $this->get_label('image icon');?></td>
<td><input type="file" name="iconimage" id="iconimage" size="10" /><br/>

<span class="notification">[<?php echo $this->get_label('supported image format');?>]</span>
<br/>
<span class="notification">[<?php echo $this->get_label('max icon dimension');?>]</span>
</td>
</tr>



<tr><td></td>
<td  align="left"><input type="submit" name="submit" value="<?php echo $this->get_label('create');?>"></td>
</tr>
</table>
</div> 
</div>
<?php $form->end(); ?>
<?php $this->dispatch("layout/footer");?>
<script type="text/javascript">
function changecredittype()
{
	if($('#ctype').val() ==0)
	{
		$('.textcredit').show();
		$('.imagecredit').hide();
	}
	else if($('#ctype').val() ==1)
	{
		$('.textcredit').hide();
		$('.imagecredit').show();
	}
}

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
	changecredittype();
	changecrediticontype();
});
</script>