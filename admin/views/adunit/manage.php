<?php $this->dispatch("layout/header/2/_22");?>

<div class="sub_menu_main"><?php echo $this->get_label('manage adunits');?></div>

<?php $this->dispatch("links/links/21");?>

<table style="width: 100%;" cellpadding="0" cellspacing="0">
<tr><td colspan="5">
<div class="search_div">
<table class="search_div_table">
<tr>
<td height="20px"></td>
<td>
<?php
$category_enabled=$this->get_addon_status('category-targeting_enabled');
$textimage_enabled=$this->get_addon_status('text-image-ads_enabled');
$sticky_enabled=$this->get_addon_status('sticky-ad-display_enabled');

$adpricing=$this->get_variable('adpricing');
$sid=$this->get_variable('sid');
$form=$this->create_form();
$form->start("manage",$this->make_url("adunit/manage"),"post");
?>

<?php echo $this->get_pricing_box($adpricing,3);?>
&nbsp;&nbsp;

<?php if($category_enabled ==1){?>
<span class="site-class">
&nbsp;&nbsp;
<?php echo CategoryHelper::get_site_dropdown(0,$sid);?>&nbsp;&nbsp;
</span>
<?php }?>

<input type="submit" name="stat" value="<?php echo $this->get_label('go');?>"/>
<?php $form->end(); ?>
</td>
</tr>
</table>
</div>

</td></tr>


<tr><td colspan="5" height="10px"></td></tr>



<tr><td colspan="5" height="10px">

<table class="data_table" cellpadding="0" cellspacing="0">
<tr class="row_heading_tr">
<td style="width: 120px;"><?php echo $this->get_label('id');?></td>

<td style="width: 120px;"><?php echo $this->get_label('name');?></td>
<?php if($category_enabled ==1){?>
<td style="width: 170px;"><?php echo $this->get_label('site name');?></td>
<?php }?>
<td style="width: 130px;"><?php echo $this->get_label('adcode pricing');?></td>
<td ><?php echo $this->get_label('adcode format');?></td>
<td style="width: 100px;"><?php echo $this->get_label('options');?></td>

</tr>

<?php

$res=$this->get_result('res');
if(count($res)==0)
{
	?>
	<tr><td colspan="8" height="30px"><?php echo $this->get_label("no records found");?></td></tr>
	<?php
}
else
{

	$textimageData = 0;

foreach($res as $key=>$value)
{
	$aduid=$value['id'];
	$adbid=$value['blockid'];

	if($textimage_enabled == 1 && $value['textimage_size'] > 0)
	$textimageData	= 1;
	else
	$textimageData  = 0;

?>
<tr class="row_data_tr">
<td><a href="<?php echo $this->make_url("report/adcodes_detailed/".$aduid);?>"><?php echo $aduid;?></a></td>
<td >
<a href="<?php echo $this->make_url("report/adcodes_detailed/".$aduid);?>"><?php echo $value['name'];?></a>
</td>

<?php if($category_enabled == 1){?>
<?php if($value['sid'] > 0){?>
<td ><?php echo CategoryHelper::get_site_name($value['sid']);?></td>
<?php }else {?>
<td ><?php echo $this->get_label('na');?></td>
<?php }} ?>
<td >
<?php
echo $this->get_adunit_preference($value['display_type'], $value['adcode_type']);

if($value['adcode_type'] == 13)
{
	if($value['video_type'] ==1)
	echo " - ".$this->get_label('vast player');
	else if($value['video_type'] ==2)
	echo " - ".$this->get_label('html5 player');
}
?></td>
<td >
<?php 
if($value['adcode_type'] == 9 || $value['adcode_type'] ==12 || ($value['adcode_type'] == 13 && $value['blockid'] == 0) || $value['adcode_type'] == 18 || $value['adcode_type'] == 21)
echo $this->get_adcode_type_value($value['adcode_type']);
else
{
	if($value['adcode_type'] != 13 || ($value['adcode_type'] == 13 && $value['blockid'] > 0))
	{
		if($value['banner_type'] != 4)
		echo $value['width']." x ".$value['height']." - ".$this->get_adcode_type_value($value['ad_type'], $textimageData, $value['banner_type'], $value['adcode_type']);
		else
		echo $this->get_adblock_type($value['type'],$value['banner_type']);//Skin

		if($sticky_enabled ==1)
		{
			if($value['sticky_support'] ==1)
			echo '<div>'.$this->get_label('sticky').'</div>';
		}
	}
	else
	echo $this->get_label('na');
}
?>
</td>
<td align="left">
<?php if($value['display_type'] != 18){?>
<a href="<?php echo $this->make_url("adunit/edit/".$aduid);?>"><i class="fa fa-edit edit-icon" title="<?php echo $this->get_label('edit');?>"></i></a>
<?php } ?>

<a href="<?php echo $this->make_url("adunit/delete/".$aduid."/".$adpricing."/".$sid);?>" onclick="return confirm('<?php echo $this->get_message('adunit delete alert');?>');"><i class="fa fa-trash delete-icon" title="<?php echo $this->get_label('delete');?>"></i></a>

<?php if($value['display_type'] ==3){?>
<a href="<?php echo $this->make_url("dispatch/sponsored/13/".$aduid,BASE);?>"><i class="fa fa-bell-o mapping-icon" title="<?php echo $this->get_label('mappings');?>"></i></a>
<?php }?>


</td></tr>
<?php }?>
<tr><td colspan="8" align="center"><?php echo $this->get_variable('pagination');?></td></tr>
<?php }?>
</table>
</td>
</tr>

</table>

<script type="text/javascript">
$(document).ready(function() 
{
	if($('#adpricing').length > 0)
	$('#adpricing').removeClass("form-control");
});
</script>
<?php $this->dispatch("layout/footer");?>