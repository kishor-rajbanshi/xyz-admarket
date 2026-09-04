<table  class="data_table" cellpadding="0" cellspacing="0">
<tr class="row_heading_tr">
<td style="width: 350px;" ><?php echo $this->get_label('font name');?></td>
<td><?php echo $this->get_label('actions');?></td>
</tr>
<?php 
$ecommerce_enabled=$this->get_variable('ecommerce_enabled');


$res=$this->get_result('res');
if(count($res)==0){?>
<tr class="row_data_tr"><td colspan="2" height="30px"><?php echo $this->get_label('no records found');?></td></tr>
<?php }else{

foreach($res as $key=>$value){?>

<tr class="row_data_tr">
<td>
<input type="text" disabled="disabled" name="name_temp<?php echo $value['id'];?>" id="name_temp<?php echo $value['id'];?>" value="<?php echo $value['font_name'];?>" style="border: 0px;"/>




<input type="hidden" name="title_lineheight_temp<?php echo $value['id'];?>" id="title_lineheight_temp<?php echo $value['id'];?>" value="<?php echo $value['title_lineheight'];?>" />
<input type="hidden" name="desc_lineheight_temp<?php echo $value['id'];?>" id="desc_lineheight_temp<?php echo $value['id'];?>" value="<?php echo $value['desc_lineheight'];?>" />
<input type="hidden" name="url_lineheight_temp<?php echo $value['id'];?>" id="url_lineheight_temp<?php echo $value['id'];?>" value="<?php echo $value['url_lineheight'];?>" />
<input type="hidden" name="credit_lineheight_temp<?php echo $value['id'];?>" id="credit_lineheight_temp<?php echo $value['id'];?>" value="<?php echo $value['credit_lineheight'];?>" />
<input type="hidden" name="title_size_temp<?php echo $value['id'];?>" id="title_size_temp<?php echo $value['id'];?>" value="<?php echo $value['title_size'];?>" />
<input type="hidden" name="desc_size_temp<?php echo $value['id'];?>" id="desc_size_temp<?php echo $value['id'];?>" value="<?php echo $value['desc_size'];?>" />



<input type="hidden" name="url_size_temp<?php echo $value['id'];?>" id="url_size_temp<?php echo $value['id'];?>" value="<?php echo $value['url_size'];?>" />
<input type="hidden" name="credit_size_temp<?php echo $value['id'];?>" id="credit_size_temp<?php echo $value['id'];?>" value="<?php echo $value['credit_size'];?>" />
<input type="hidden" name="title_font_temp<?php echo $value['id'];?>" id="title_font_temp<?php echo $value['id'];?>" value="<?php echo $value['title_font'];?>" />
<input type="hidden" name="desc_font_temp<?php echo $value['id'];?>" id="desc_font_temp<?php echo $value['id'];?>" value="<?php echo $value['desc_font'];?>" />
<input type="hidden" name="url_font_temp<?php echo $value['id'];?>" id="url_font_temp<?php echo $value['id'];?>" value="<?php echo $value['url_font'];?>" />
<input type="hidden" name="credit_font_temp<?php echo $value['id'];?>" id="credit_font_temp<?php echo $value['id'];?>" value="<?php echo $value['credit_font'];?>" />
<input type="hidden" name="title_weight_temp<?php echo $value['id'];?>" id="title_weight_temp<?php echo $value['id'];?>" value="<?php echo $value['title_weight'];?>" />
<input type="hidden" name="desc_weight_temp<?php echo $value['id'];?>" id="desc_weight_temp<?php echo $value['id'];?>" value="<?php echo $value['desc_weight'];?>" />
<input type="hidden" name="url_weight_temp<?php echo $value['id'];?>" id="url_weight_temp<?php echo $value['id'];?>" value="<?php echo $value['url_weight'];?>" />



<input type="hidden" name="credit_weight_temp<?php echo $value['id'];?>" id="credit_weight_temp<?php echo $value['id'];?>" value="<?php echo $value['credit_weight'];?>" />
<input type="hidden" name="title_decoration_temp<?php echo $value['id'];?>" id="title_decoration_temp<?php echo $value['id'];?>" value="<?php echo $value['title_decoration'];?>" />
<input type="hidden" name="desc_decoration_temp<?php echo $value['id'];?>" id="desc_decoration_temp<?php echo $value['id'];?>" value="<?php echo $value['desc_decoration'];?>" />
<input type="hidden" name="url_decoration_temp<?php echo $value['id'];?>" id="url_decoration_temp<?php echo $value['id'];?>" value="<?php echo $value['url_decoration'];?>" />
<input type="hidden" name="credit_decoration_temp<?php echo $value['id'];?>" id="credit_decoration_temp<?php echo $value['id'];?>" value="<?php echo $value['credit_decoration'];?>" />


<?php if($ecommerce_enabled ==1){?> 
	<input type="hidden" name="head_lineheight_temp<?php echo $value['id'];?>" id="head_lineheight_temp<?php echo $value['id'];?>" value="<?php echo $value['head_lineheight'];?>" />
	<input type="hidden" name="button_lineheight_temp<?php echo $value['id'];?>" id="button_lineheight_temp<?php echo $value['id'];?>" value="<?php echo $value['button_lineheight'];?>" />
	<input type="hidden" name="price_lineheight_temp<?php echo $value['id'];?>" id="price_lineheight_temp<?php echo $value['id'];?>" value="<?php echo $value['price_lineheight'];?>" />
	<input type="hidden" name="head_size_temp<?php echo $value['id'];?>" id="head_size_temp<?php echo $value['id'];?>" value="<?php echo $value['head_size'];?>" />
	<input type="hidden" name="button_size_temp<?php echo $value['id'];?>" id="button_size_temp<?php echo $value['id'];?>" value="<?php echo $value['button_size'];?>" />
	<input type="hidden" name="price_size_temp<?php echo $value['id'];?>" id="price_size_temp<?php echo $value['id'];?>" value="<?php echo $value['price_size'];?>" />
	<input type="hidden" name="head_font_temp<?php echo $value['id'];?>" id="head_font_temp<?php echo $value['id'];?>" value="<?php echo $value['head_font'];?>" />
	<input type="hidden" name="button_font_temp<?php echo $value['id'];?>" id="button_font_temp<?php echo $value['id'];?>" value="<?php echo $value['button_font'];?>" />
	<input type="hidden" name="price_font_temp<?php echo $value['id'];?>" id="price_font_temp<?php echo $value['id'];?>" value="<?php echo $value['price_font'];?>" />
	<input type="hidden" name="head_weight_temp<?php echo $value['id'];?>" id="head_weight_temp<?php echo $value['id'];?>" value="<?php echo $value['head_weight'];?>" />
	<input type="hidden" name="button_weight_temp<?php echo $value['id'];?>" id="button_weight_temp<?php echo $value['id'];?>" value="<?php echo $value['button_weight'];?>" />
	<input type="hidden" name="price_weight_temp<?php echo $value['id'];?>" id="price_weight_temp<?php echo $value['id'];?>" value="<?php echo $value['price_weight'];?>" />
	<input type="hidden" name="head_decoration_temp<?php echo $value['id'];?>" id="head_decoration_temp<?php echo $value['id'];?>" value="<?php echo $value['head_decoration'];?>" />
	<input type="hidden" name="button_decoration_temp<?php echo $value['id'];?>" id="button_decoration_temp<?php echo $value['id'];?>" value="<?php echo $value['button_decoration'];?>" />
	<input type="hidden" name="price_decoration_temp<?php echo $value['id'];?>" id="price_decoration_temp<?php echo $value['id'];?>" value="<?php echo $value['price_decoration'];?>" />
<?php }?>

</td>
<td>

<a id="button_edit<?php echo $value['id'];?>" address-target="<?php echo $value['id'];?>" data-toggle="modal" data-target="#NewFont"><i class="fa fa-edit edit-icon" title="<?php echo $this->get_label('edit');?>"></i></a>

<a onclick="DeleteFont(<?php echo $value['id'];?>);"><i class="fa fa-trash delete-icon" title="<?php echo $this->get_label('delete');?>"></i></a>

<span id="load<?php echo $value['id'];?>" style="display: none;"><img src="images/load.gif"/></span>

</td>
</tr>
<?php }}?>		
</table>