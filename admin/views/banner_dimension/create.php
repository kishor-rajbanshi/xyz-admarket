<?php
$this->dispatch("layout/header/4/_44");

$validate=array(
		"width"=>array(
				"notNull"=>array($this->get_message("not null")),
				"isPositive"=>array($this->get_message("positive value"))

		),
		"height"=>array(
				"notNull"=>array($this->get_message("not null")),
				"isPositive"=>array($this->get_message("positive value"))

		)
);

$form=$this->create_form();
$form->start("create",$this->make_url("banner_dimension/create"),"post",$validate); 

$video_enabled=$this->get_variable('video_enabled');
$device_enabled=$this->get_addon_status('device-targeting_enabled');
$interstitial_enabled=$this->get_addon_status('interstitial_enabled');
$textimage_enabled=$this->get_variable('textimage_enabled');
$ecommerce_enabled=$this->get_variable('ecommerce_enabled');
$expandable_enabled=$this->get_variable('expandable_enabled');
$skin_enabled=$this->get_variable('skin_enabled');

$imagead=$this->get_variable('imagead');
$expandablead=$this->get_variable('expandablead');
$ecommercead=$this->get_variable('ecommercead');
$videoad=$this->get_variable('videoad');
?>
<div class="sub_menu_main"><?php echo $this->get_label('new banner dimension');?></div>

<?php $this->dispatch("links/links/19");?>

<div class="inner-box">
<div class="pages-input">


<table style="width: 100%;" cellpadding="0" cellspacing="0">
<tr><td ></td><td><?php echo $this->get_label('compulsory message');?></td></tr>

<?php if($interstitial_enabled ==1 || $textimage_enabled ==1 || $skin_enabled ==1){?>
<tr>
<td style="width: 170px;"><?php echo $this->get_label('banner type');?></td>
<td>
<select name="banner_type" id="banner_type" style="width: 100px;" onchange="LoadVASTSettings();<?php if($ecommerce_enabled ==1){?>LoadSettings();<?php }?>" >
	<option value="0" <?php if($this->get_variable('banner_type')==0) { echo "selected"; }?>><?php echo $this->get_label('normal ads');?></option>
    
	<?php if($interstitial_enabled ==1){?>
	<option value="1" <?php if($this->get_variable('banner_type')==1) { echo "selected"; }?>><?php echo $this->get_label('interstitial ads');?></option>
    <?php }?>

	<?php if($textimage_enabled ==1){?>
	<option value="3" <?php if($this->get_variable('banner_type')==3) { echo "selected"; }?>><?php echo $this->get_label('textimage ads');?></option>
	<?php }?>
	
	<?php if($skin_enabled ==1){?>
	<option value="4" <?php if($this->get_variable('banner_type')==4) { echo "selected"; }?>><?php echo $this->get_label('skin ads');?></option>
	<?php }?>
		
</select>
</td></tr>
<?php }else{?>
<input type="hidden" name="banner_type" id="banner_type" value="0" />
<?php }?>

<tr>
<td style="width: 170px;"><?php echo $this->get_label('width');?></td>
<td><input type="text" name="width" value="<?php echo $this->get_variable('width');?>" style="width: 50px;" onkeyup="this.value=this.value.replace(/[^0-9]/g, '');">&nbsp;<?php echo $this->get_label('px');?><span class="compulsory">*</span></td>
</tr>

<tr>
<td><?php echo $this->get_label('height');?></td>
<td><input type="text" name="height" value="<?php echo $this->get_variable('height');?>" style="width: 50px;" onkeyup="this.value=this.value.replace(/[^0-9]/g, '');">&nbsp;<?php echo $this->get_label('px');?><span class="compulsory">*</span></td>
</tr>

<tr class="file-size-tr">
<td><?php echo $this->get_label('filesize');?></td>
<td><input type="text" name="filesize" value="<?php echo $this->get_variable('filesize');?>" style="width: 50px;" onkeyup="this.value=this.value.replace(/[^0-9]/g, '');">&nbsp;<?php echo $this->get_label('kb');?><span class="compulsory">*</span></td>
</tr>


<?php if($ecommerce_enabled ==1){?>

<tr class="support-tr" style="display: none;">
<td ><?php echo $this->get_label('supported ad types');?></td>
<td>

<input type="checkbox" name="imagead" id="imagead" value="1" <?php if($imagead ==1){?> checked="checked" <?php }?> onclick="LoadVASTSettings();<?php if($expandable_enabled ==1){?>LoadExpandableType();<?php }?>"/> <?php echo $this->get_label('image');?>

<span class="special-span">
<?php if($ecommerce_enabled ==1){?>
&nbsp;
<input type="checkbox" name="ecommercead" id="ecommercead" value="1" <?php if($ecommercead ==1){?> checked="checked" <?php }?> /> <?php echo $this->get_label('ecommerce');?>
<?php }?>
</span>
</td>
</tr>

<?php }else{?>
<input type="hidden" name="imagead" id="imagead" value="1" />
<?php }?>




<?php if($expandable_enabled ==1){?>

<tr class="support-tr1" style="display: none;">
<td ><?php echo $this->get_label('support expandable type');?></td>
<td>

<input type="radio" name="expandablead" id="expandablead0" value="0" checked="checked" onclick="LoadExpandableSize();" /> <?php echo $this->get_label('no');?>
&nbsp;
<input type="radio" name="expandablead" id="expandablead1" value="1" <?php if($expandablead ==1){?> checked="checked" <?php }?> onclick="LoadExpandableSize();" /> <?php echo $this->get_label('yes');?>

</td>
</tr>


<tr class="support-tr2" style="display: none;">
<td ><?php echo $this->get_label('expandable width');?></td>
<td><input type="text" name="expandable_width" value="<?php echo $this->get_variable('expandable_width');?>" style="width: 50px;" onkeyup="this.value=this.value.replace(/[^0-9]/g, '');">&nbsp;<?php echo $this->get_label('px');?><span class="compulsory">*</span></td>
</tr>

<tr class="support-tr2" style="display: none;">
<td><?php echo $this->get_label('expandable height');?></td>
<td><input type="text" name="expandable_height" value="<?php echo $this->get_variable('expandable_height');?>" style="width: 50px;" onkeyup="this.value=this.value.replace(/[^0-9]/g, '');">&nbsp;<?php echo $this->get_label('px');?><span class="compulsory">*</span></td>
</tr>
<?php }?>


<?php if($video_enabled ==1){?>
<tr class="support-tr-vast">
<td ><?php echo $this->get_label('allow in vast player');?></td>
<td>
<select name="videoad" id="videoad">
<option value="1" <?php if($videoad ==1){?> selected <?php }?>><?php echo $this->get_label('yes');?></option>
<option value="0" <?php if($videoad ==0){?> selected <?php }?>><?php echo $this->get_label('no');?></option>
</select>
</td>
</tr>
<?php }?>

<tr><td></td><td  align="left"><input type="submit" name="submit" value="<?php echo $this->get_label('submit');?>"></td></tr>
</table>
</div>
</div>
<?php $form->end(); ?>
<script type="text/javascript">
function LoadVASTSettings()
{
	<?php if($video_enabled ==1){?>
	
	if($('#banner_type').val() == 0)
	{
		<?php if($ecommerce_enabled ==1){?>
		if($('#imagead').prop('checked'))
		$('.support-tr-vast').show();
		else
		$('.support-tr-vast').hide();
		<?php }else{?>
		$('.support-tr-vast').show();
		<?php }?>
	}
	else
	$('.support-tr-vast').hide();

	<?php }?>
}

function LoadSettings()
{
	if($('#banner_type').val() == 0)
	{
		if($('.support-tr').length >0)
		$('.support-tr').show();

		<?php if($expandable_enabled ==1){?>
		LoadExpandableType();
		<?php }?>		

        $('.special-span').show(); 
	}
	else
	{
		if($('.support-tr').length >0)
		$('.support-tr').hide();

		if($('.support-tr1').length >0)
		$('.support-tr1').hide();	

		if($('.support-tr2').length >0)
		$('.support-tr2').hide();			
		
		$('.special-span').hide();
	}
}

function LoadExpandableSize()
{
	if($('#expandablead0').attr('checked'))
	$('.support-tr2').hide();

	if($('#expandablead1').attr('checked'))
	$('.support-tr2').show();
}

function LoadExpandableType()
{
	if($('#banner_type').val() == 0 && $('#imagead').attr('checked'))
	{
		$('.support-tr1').show();
		
		LoadExpandableSize();
	}
	else
	{
		$('.support-tr1').hide();
		$('.support-tr2').hide();
	}
}


$(document).ready(function() {
	<?php if($ecommerce_enabled ==1){?>
	LoadSettings();
	
	<?php if($expandable_enabled ==1){?>
	LoadExpandableType();
	<?php }}?>

	LoadVASTSettings();

	<?php if($expandable_enabled ==1){?>
	LoadExpandableSize();
	<?php }?>	
});
</script>
<?php $this->dispatch("layout/footer");?>