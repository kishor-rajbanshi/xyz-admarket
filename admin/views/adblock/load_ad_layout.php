<?php 
$native_enabled  = $this->get_addon_status('native-ad-display_enabled');
?> 
<table style="width: 100%;" cellpadding="0" cellspacing="0" >
<?php 
if($native_enabled == 1 || $native_enabled == 0)
{
	?>
	<tr><td>
	<?php 
	$form   = $this->create_form();
	$form->start("managelayout","","post");
	?>		
	<div class="search_div">
	<table class="search_div_table">
	  <tr>
	    <td style="width: 120px;">
	    <select name="layoutType" id="layoutType">
	    <option value="-1"><?php echo $this->get_label('select layout');?></option>
	    <option value="1"><?php echo $this->get_label('normal ad');?></option>    
	    <option value="2"><?php echo $this->get_label('native ad');?></option>  
	    </select>
	    </td>
	    <td>&nbsp;&nbsp; <input type="button" name="search" onclick="LoadLayout();" value="<?php echo $this->get_label('go');?>" />
	    </td> 
	  </tr>
	</table>
	</div>
	<?php $form->end(); ?>
	</td>
	</tr>
<?php }else{ ?> 
<span><input type="hidden" name="layoutType" id="layoutType" value="1" /></span>
<?php } ?>

<tr><td style="height: 10px;"></td></tr>
<tr><td>
<table  class="data_table" cellpadding="0" cellspacing="0">
<tr class="row_heading_tr">
<td style="width: 200px;" ><?php echo $this->get_label('layout name');?></td>
<td style="width: 150px;" ><?php echo $this->get_label('layout type');?></td>
<td><?php echo $this->get_label('actions');?></td>
</tr>
<?php 
$res = $this->get_result('res');
if(count($res) == 0){?>
<tr class="row_data_tr"><td colspan="3" height="30px"><?php echo $this->get_label('no records found');?></td></tr>
<?php }else{

foreach($res as $key=>$value)
{
	if($value['CTA_section_width'] > 0)
	$CTASectionSize = $value['CTA_section_width'];
	else 
	$CTASectionSize = $value['CTA_section_height'];
	?>

<tr class="row_data_tr">
<td>
<input type="text" disabled="disabled" name="name_temp<?php echo $value['id'];?>" id="name_temp<?php echo $value['id'];?>" value="<?php echo $value['layout'];?>" style="border: 0px;" />
</td>

<td>
<?php 
if($value['layout_type'] == 1)
echo $this->get_label("normal ad");
else if($value['layout_type'] == 2)
echo $this->get_label("native ad");
?>
</td>

<td>
<input type="hidden" name="layout_type_temp<?php echo $value['id'];?>" id="layout_type_temp<?php echo $value['id'];?>" value="<?php echo $value['layout_type'];?>" />
<input type="hidden" name="minimum_width_temp<?php echo $value['id'];?>" id="minimum_width_temp<?php echo $value['id'];?>" value="<?php echo $value['minimum_width'];?>" />
<input type="hidden" name="minimum_height_temp<?php echo $value['id'];?>" id="minimum_height_temp<?php echo $value['id'];?>" value="<?php echo $value['minimum_height'];?>" />
<input type="hidden" name="maximum_width_temp<?php echo $value['id'];?>" id="maximum_width_temp<?php echo $value['id'];?>" value="<?php echo $value['maximum_width'];?>" />
<input type="hidden" name="maximum_height_temp<?php echo $value['id'];?>" id="maximum_height_temp<?php echo $value['id'];?>" value="<?php echo $value['maximum_height'];?>" />
<input type="hidden" name="theme_setting_temp<?php echo $value['id'];?>" id="theme_setting_temp<?php echo $value['id'];?>" value="<?php echo $value['layout_theme'];?>" />
<input type="hidden" name="font_setting_temp<?php echo $value['id'];?>" id="font_setting_temp<?php echo $value['id'];?>" value="<?php echo $value['layout_font'];?>" />
<input type="hidden" name="title_temp<?php echo $value['id'];?>" id="title_temp<?php echo $value['id'];?>" value="<?php echo $value['title_enabled'];?>" />
<input type="hidden" name="description_temp<?php echo $value['id'];?>" id="description_temp<?php echo $value['id'];?>" value="<?php echo $value['description_enabled'];?>" />
<input type="hidden" name="displayurl_temp<?php echo $value['id'];?>" id="displayurl_temp<?php echo $value['id'];?>" value="<?php echo $value['url_enabled'];?>" />
<input type="hidden" name="cta_button_temp<?php echo $value['id'];?>" id="cta_button_temp<?php echo $value['id'];?>" value="<?php echo $value['button_enabled'];?>" />

<input type="hidden" name="cta_position_temp<?php echo $value['id'];?>" id="cta_position_temp<?php echo $value['id'];?>" value="<?php echo $value['CTA_position'];?>" />
<input type="hidden" name="cta_section_size_temp<?php echo $value['id'];?>" id="cta_section_size_temp<?php echo $value['id'];?>" value="<?php echo $CTASectionSize;?>" />

<input type="hidden" name="cta_border_radius_temp<?php echo $value['id'];?>" id="cta_border_radius_temp<?php echo $value['id'];?>" value="<?php echo $value['CTA_border_radius'];?>" />
<input type="hidden" name="cta_padding_horizontal_temp<?php echo $value['id'];?>" id="cta_padding_horizontal_temp<?php echo $value['id'];?>" value="<?php echo $value['CTA_padding_horizontal'];?>" />
<input type="hidden" name="cta_padding_vertical_temp<?php echo $value['id'];?>" id="cta_padding_vertical_temp<?php echo $value['id'];?>" value="<?php echo $value['CTA_padding_vertical'];?>" />
<input type="hidden" name="content_slide_temp<?php echo $value['id'];?>" id="content_slide_temp<?php echo $value['id'];?>" value="<?php echo $value['content_slide'];?>" />
<input type="hidden" name="slide_direction_temp<?php echo $value['id'];?>" id="slide_direction_temp<?php echo $value['id'];?>" value="<?php echo $value['slide_direction'];?>" />
<input type="hidden" name="slide_duration_temp<?php echo $value['id'];?>" id="slide_duration_temp<?php echo $value['id'];?>" value="<?php echo $value['slide_duration'];?>" />
<input type="hidden" name="title_border_bottom_temp<?php echo $value['id'];?>" id="title_border_bottom_temp<?php echo $value['id'];?>" value="<?php echo $value['title_border_bottom'];?>" />
<input type="hidden" name="description_border_bottom_temp<?php echo $value['id'];?>" id="description_border_bottom_temp<?php echo $value['id'];?>" value="<?php echo $value['description_border_bottom'];?>" />
<input type="hidden" name="displayurl_border_bottom_temp<?php echo $value['id'];?>" id="displayurl_border_bottom_temp<?php echo $value['id'];?>" value="<?php echo $value['displayurl_border_bottom'];?>" />


<a id="button_edit<?php echo $value['id'];?>" address-target="<?php echo $value['id'];?>" data-toggle="modal" data-target="#NewLayout"><i class="fa fa-edit edit-icon" title="<?php echo $this->get_label('edit');?>"></i></a>


<?php if($value['default_layout'] == 0){?>
<a onclick="DeleteLayout(<?php echo $value['id'];?>);"><i class="fa fa-trash delete-icon" title="<?php echo $this->get_label('delete');?>"></i></a>
<?php } ?>

<span id="load<?php echo $value['id'];?>" style="display: none;"><img src="images/load.gif"/></span>

</td>
</tr>
<?php }}?>		
</table>
</td></tr>
</table>