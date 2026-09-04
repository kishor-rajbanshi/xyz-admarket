<?php
$this->dispatch("layout/header/4/_44");

if($_POST)
{
	$banner_type=$this->get_variable('banner_type');
	$height=$this->get_variable('height');
	$width=$this->get_variable('width');
	$filesize=$this->get_variable('filesize');
	$bid=$this->get_variable('bid');
	
	$imagead=$this->get_variable('imagead');
	$ecommercead=$this->get_variable('ecommercead');
	$videoad=$this->get_variable('videoad');
	$expandablead=$this->get_variable('expandablead');
	$expandable_width=$this->get_variable('expandable_width');
	$expandable_height=$this->get_variable('expandable_height');
}
else 
{
	$res=$this->get_result('res');
	$value1=$res[0];
	$bid=$value1['id'];
	
	$height=$value1['height'];
	$width=$value1['width'];
	$filesize=$value1['filesize'];
	
	$imagead=$value1['image_support'];
	$ecommercead=$value1['ecommerce_support'];
	$expandablead=$value1['expandable_support'];
	$expandable_width=$value1['expandable_width'];
	$expandable_height=$value1['expandable_height'];	
	
	$banner_type=$value1['banner_type'];
	$videoad=$value1['vast_video_support'];
}

if($width ==0)
$width="";

if($height ==0)
$height="";

if($filesize ==0)
$filesize="";

if($expandable_width ==0)
$expandable_width="";

if($expandable_height ==0)
$expandable_height="";


$st=$this->get_variable('st');
$pg=$this->get_variable('pg');
$alreadyads=$this->get_variable('alreadyads');
$alreadyads_ecommerce=$this->get_variable('alreadyads_ecommerce');
$alreadyads_expandable=$this->get_variable('alreadyads_expandable');

$alreadyads_interstitial=$this->get_variable('alreadyads_interstitial');
$alreadyads_textimage=$this->get_variable('alreadyads_textimage');
$alreadyads_skin=$this->get_variable('alreadyads_skin');


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
$form->start("edit",$this->make_url("banner_dimension/edit/".$bid."/".$st."/".$pg),"post",$validate);
$res1=$this->get_result('res1');

$ecommerce_enabled=$this->get_variable('ecommerce_enabled');
$device_enabled=$this->get_addon_status('device-targeting_enabled');
$interstitial_enabled=$this->get_addon_status('interstitial_enabled');
$textimage_enabled=$this->get_variable('textimage_enabled');
$video_enabled=$this->get_variable('video_enabled');
$expandable_enabled=$this->get_variable('expandable_enabled');
$skin_enabled=$this->get_variable('skin_enabled');

?>

<div class="sub_menu_main"><?php echo $this->get_label('edit banner dimension');?></div>

<?php $this->dispatch("links/links/19");?>

<div class="inner-box">
<div class="pages-input">


<table style="width: 100%;" cellpadding="0" cellspacing="0">
<tr><td ></td><td><?php echo $this->get_label('compulsory message');?></td></tr>
<?php if($interstitial_enabled ==1 || $textimage_enabled ==1 || $skin_enabled ==1){


	if($_POST)
	$banner_type=$this->get_variable('banner_type');
	else
	$banner_type=$value1['banner_type'];
	
	
	?>

<tr>
<td style="width:170px;"><?php echo $this->get_label('banner type');?></td>
<td>
<?php 
if($banner_type ==0)
echo $this->get_label('normal ads');
else if($banner_type ==1)
echo $this->get_label('interstitial ads');
else if($banner_type ==3)
echo $this->get_label('textimage ads');
else if($banner_type ==4)
echo $this->get_label('skin ads');
?>
<input type="hidden" name="banner_type" id="banner_type" value="<?php echo $banner_type;?>" />
</td></tr>
<?php }else{?>
<input type="hidden" name="banner_type" id="banner_type" value="<?php echo $banner_type;?>" />
<?php }?>

<tr>
<td style="width:170px;"><?php echo $this->get_label('width');?></td>
<td>
<input type="text" name="width" value="<?php echo $width;?>" style="width: 50px;" <?php if($alreadyads >0 || $alreadyads_ecommerce >0 || $alreadyads_interstitial >0 || $alreadyads_textimage >0 || $alreadyads_skin >0){?> readonly="readonly" <?php }?> onkeyup="this.value=this.value.replace(/[^0-9]/g, '');" /> <?php echo $this->get_label('px');?><span class="compulsory">*</span></td>
</tr>

<tr>
<td><?php echo $this->get_label('height');?></td>
<td><input type="text" name="height" value="<?php echo $height;?>" style="width: 50px;" <?php if($alreadyads >0 || $alreadyads_ecommerce >0 || $alreadyads_interstitial >0 || $alreadyads_textimage >0 || $alreadyads_skin >0){?> readonly="readonly" <?php }?> onkeyup="this.value=this.value.replace(/[^0-9]/g, '');" /> <?php echo $this->get_label('px');?><span class="compulsory">*</span></td>
</tr>

<tr>
<td><?php echo $this->get_label('filesize');?></td>
<td><input type="text" name="filesize" value="<?php echo $filesize;?>" style="width: 50px;" onkeyup="this.value=this.value.replace(/[^0-9]/g, '');" /> <?php echo $this->get_label('kb');?><span class="compulsory">*</span></td>
</tr>

<?php if($banner_type ==0){?>

<?php if($ecommerce_enabled ==1){?>

<tr class="support-tr">
<td ><?php echo $this->get_label('supported ad types');?></td>
<td>

<?php if($imagead ==1 && $alreadyads >0){?>
<input type="checkbox" name="imagead1" id="imagead1" value="1" checked="checked" disabled="disabled" /> <?php echo $this->get_label('image');?>
<input type="hidden" name="imagead" id="imagead" value="<?php echo $imagead;?>" />
<?php }else{?>
<input type="checkbox" name="imagead" id="imagead" value="1" <?php if($imagead ==1){?> checked="checked" <?php }?> onclick="LoadVASTSettings();<?php if($expandable_enabled ==1){?>LoadExpandableType();<?php }?>" /> <?php echo $this->get_label('image');?>
<?php }?>


<?php if($ecommerce_enabled ==1){?>
&nbsp;
<?php if($ecommercead ==1 && $alreadyads_ecommerce >0){	?>
<input type="checkbox" name="ecommercead1" id="ecommercead1" value="1" checked="checked" disabled="disabled" /> <?php echo $this->get_label('ecommerce');?>
<input type="hidden" name="ecommercead" id="ecommercead" value="<?php echo $ecommercead;?>" /> 
<?php }else{?>
<input type="checkbox" name="ecommercead" id="ecommercead" value="1" <?php if($ecommercead ==1){?> checked="checked" <?php }?> /> <?php echo $this->get_label('ecommerce');?>
<?php }}?>
</td>
</tr>
<?php }else{?>
<input type="hidden" name="imagead" id="imagead" value="1" />
<input type="hidden" name="imagead1" id="imagead1" value="1" />
<?php }?>


<?php if($expandable_enabled ==1){?>

<tr class="support-tr1" style="display: none;">
<td ><?php echo $this->get_label('support expandable type');?></td>
<td>

<input type="radio" name="expandablead" id="expandablead0" value="0" checked="checked" onclick="LoadExpandableSize();" <?php if($banner_type ==0 && $expandablead ==1 && $alreadyads_expandable >0){?> disabled="disabled" <?php }?> /> <?php echo $this->get_label('no');?>
&nbsp;
<input type="radio" name="expandablead" id="expandablead1" value="1" <?php if($expandablead ==1){?> checked="checked" <?php }?> onclick="LoadExpandableSize();" /> <?php echo $this->get_label('yes');?>

</td>
</tr>


<tr class="support-tr2" style="display: none;">
<td ><?php echo $this->get_label('expandable width');?></td>
<td><input type="text" name="expandable_width" value="<?php echo $expandable_width;?>" <?php if($banner_type ==0 && $expandablead ==1 && $alreadyads_expandable >0){?> readonly="readonly" <?php }?> style="width: 50px;" onkeyup="this.value=this.value.replace(/[^0-9]/g, '');">&nbsp;<?php echo $this->get_label('px');?><span class="compulsory">*</span></td>
</tr>

<tr class="support-tr2" style="display: none;">
<td><?php echo $this->get_label('expandable height');?></td>
<td><input type="text" name="expandable_height" value="<?php echo $expandable_height;?>" <?php if($banner_type ==0 && $expandablead ==1 && $alreadyads_expandable >0){?> readonly="readonly" <?php }?> style="width: 50px;" onkeyup="this.value=this.value.replace(/[^0-9]/g, '');">&nbsp;<?php echo $this->get_label('px');?><span class="compulsory">*</span></td>
</tr>

<?php }?>
<?php }?>

<?php if($video_enabled ==1 && $banner_type ==0){?>

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

<tr><td></td><td align="left">
<input type="hidden" name="bid" id="bid" value="<?php echo $bid;?>" />
<input type="hidden" name="st" id="st" value="<?php echo $st;?>" />
<input type="hidden" name="pg" id="pg" value="<?php echo $pg;?>" />
<input type="submit" name="submit" value="<?php echo $this->get_label('submit');?>"></td></tr>
</table>
</div>
</div>
<?php $form->end(); ?>
<?php $this->dispatch("layout/footer");?>
<script type="text/javascript">
function LoadExpandableSize()
{
	if($('#expandablead0').attr('checked'))
	$('.support-tr2').hide();

	if($('#expandablead1').attr('checked'))
	$('.support-tr2').show();
}

function LoadExpandableType()
{
	if(($('#imagead1').length >0 && $('#imagead1').val() ==1) || $('#imagead').attr('checked'))
	{
		$('.support-tr1').show();

		<?php if($banner_type ==0){?>
		LoadExpandableSize();
		<?php }?>
	}
	else
	{
		$('.support-tr1').hide();
		$('.support-tr2').hide();
	}
}


function LoadVASTSettings()
{
	<?php if($video_enabled ==1){?>
	
	if($('#banner_type').val() == 0)
	{
		<?php if($ecommerce_enabled ==1){?>
		if($('#imagead').prop('checked'))
		$('.support-tr-vast').show();
		else
		{
			<?php if($alreadyads >0){?>
			$('.support-tr-vast').show();
			<?php }else{?>
			$('.support-tr-vast').hide();
			<?php }?>
		}
		<?php }else{?>
		$('.support-tr-vast').show();
		<?php }?>
	}
	else
	$('.support-tr-vast').hide();

	<?php }?>
}

$(document).ready(function() {
LoadVASTSettings();

<?php if($expandable_enabled ==1 && $banner_type ==0){?>
LoadExpandableType();
<?php }?>
});
</script>