<table  class="data_table" cellpadding="0" cellspacing="0">
<tr class="row_heading_tr">
<td style="width: 350px;" ><?php echo $this->get_label('theme name');?></td>
<td><?php echo $this->get_label('actions');?></td>
</tr>
<?php 
$ecommerce_enabled = $this->get_variable('ecommerce_enabled');
$native_enabled    = $this->get_addon_status('native-ad-display_enabled');


$res=$this->get_result('res');
if(count($res)==0){?>
<tr class="row_data_tr"><td colspan="2" height="30px"><?php echo $this->get_label('no records found');?></td></tr>
<?php }else{

foreach($res as $key=>$value){?>

<tr class="row_data_tr">
<td>
<input type="text" disabled="disabled" name="nametemp<?php echo $value['id'];?>" id="nametemp<?php echo $value['id'];?>" value="<?php echo $value['theme'];?>" style="border: 0px;"/>


<input type="hidden" name="color1temp<?php echo $value['id'];?>" id="color1temp<?php echo $value['id'];?>" value="<?php echo $value['title'];?>" />
<input type="hidden" name="color2temp<?php echo $value['id'];?>" id="color2temp<?php echo $value['id'];?>" value="<?php echo $value['description'];?>" />
<input type="hidden" name="color3temp<?php echo $value['id'];?>" id="color3temp<?php echo $value['id'];?>" value="<?php echo $value['url'];?>" />
<input type="hidden" name="color4temp<?php echo $value['id'];?>" id="color4temp<?php echo $value['id'];?>" value="<?php echo $value['border'];?>" />
<input type="hidden" name="color5temp<?php echo $value['id'];?>" id="color5temp<?php echo $value['id'];?>" value="<?php echo $value['background'];?>" />
<input type="hidden" name="color6temp<?php echo $value['id'];?>" id="color6temp<?php echo $value['id'];?>" value="<?php echo $value['credit'];?>" />


<input type="hidden" name="color8temp<?php echo $value['id'];?>" id="color8temp<?php echo $value['id'];?>" value="<?php echo $value['button'];?>" />
<input type="hidden" name="color9temp<?php echo $value['id'];?>" id="color9temp<?php echo $value['id'];?>" value="<?php echo $value['button_background'];?>" />
<input type="hidden" name="color10temp<?php echo $value['id'];?>" id="color10temp<?php echo $value['id'];?>" value="<?php echo $value['button_hover'];?>" />

<input type="hidden" name="color18temp<?php echo $value['id'];?>" id="color18temp<?php echo $value['id'];?>" value="<?php echo $value['title_background'];?>" />
<input type="hidden" name="color19temp<?php echo $value['id'];?>" id="color19temp<?php echo $value['id'];?>" value="<?php echo $value['description_background'];?>" />
<input type="hidden" name="color20temp<?php echo $value['id'];?>" id="color20temp<?php echo $value['id'];?>" value="<?php echo $value['url_background'];?>" />
<input type="hidden" name="color21temp<?php echo $value['id'];?>" id="color21temp<?php echo $value['id'];?>" value="<?php echo $value['cta_background'];?>" />
<input type="hidden" name="color22temp<?php echo $value['id'];?>" id="color22temp<?php echo $value['id'];?>" value="<?php echo $value['image_background'];?>" />


<input type="hidden" name="color23temp<?php echo $value['id'];?>" id="color23temp<?php echo $value['id'];?>" value="<?php echo $value['title_hover_color'];?>" />
<input type="hidden" name="color24temp<?php echo $value['id'];?>" id="color24temp<?php echo $value['id'];?>" value="<?php echo $value['description_hover_color'];?>" />
<input type="hidden" name="color25temp<?php echo $value['id'];?>" id="color25temp<?php echo $value['id'];?>" value="<?php echo $value['url_hover_color'];?>" />
<input type="hidden" name="color26temp<?php echo $value['id'];?>" id="color26temp<?php echo $value['id'];?>" value="<?php echo $value['heading_hover_color'];?>" />
<input type="hidden" name="color27temp<?php echo $value['id'];?>" id="color27temp<?php echo $value['id'];?>" value="<?php echo $value['heading_color'];?>" />
<input type="hidden" name="color28temp<?php echo $value['id'];?>" id="color28temp<?php echo $value['id'];?>" value="<?php echo $value['heading_background'];?>" />



<?php if($ecommerce_enabled ==1){?> 	
<input type="hidden" name="color7temp<?php echo $value['id'];?>" id="color7temp<?php echo $value['id'];?>" value="<?php echo $value['price'];?>" />
<input type="hidden" name="color11temp<?php echo $value['id'];?>" id="color11temp<?php echo $value['id'];?>" value="<?php echo $value['heading'];?>" />
<input type="hidden" name="color12temp<?php echo $value['id'];?>" id="color12temp<?php echo $value['id'];?>" value="<?php echo $value['heading_button'];?>" />
<input type="hidden" name="color13temp<?php echo $value['id'];?>" id="color13temp<?php echo $value['id'];?>" value="<?php echo $value['heading_button_background'];?>" />
<input type="hidden" name="color14temp<?php echo $value['id'];?>" id="color14temp<?php echo $value['id'];?>" value="<?php echo $value['heading_button_hover'];?>" />
<input type="hidden" name="color15temp<?php echo $value['id'];?>" id="color15temp<?php echo $value['id'];?>" value="<?php echo $value['offer_price'];?>" />
<input type="hidden" name="color16temp<?php echo $value['id'];?>" id="color16temp<?php echo $value['id'];?>" value="<?php echo $value['ad_selection_border'];?>" />
<input type="hidden" name="color17temp<?php echo $value['id'];?>" id="color17temp<?php echo $value['id'];?>" value="<?php echo $value['ad_background'];?>" />	
<?php }?>

</td>
<td>

<a id="button_edit<?php echo $value['id'];?>" address-target="<?php echo $value['id'];?>" data-toggle="modal" data-target="#edit_theme"><i class="fa fa-edit edit-icon" title="<?php echo $this->get_label('edit');?>"></i></a>



<?php if($value['id'] > 1){?>
<a onclick="DeleteTheme(<?php echo $value['id'];?>);"><i class="fa fa-trash delete-icon" title="<?php echo $this->get_label('delete');?>"></i></a>
<?php }?>




<span id="load<?php echo $value['id'];?>" style="display: none;"><img src="images/load.gif"/></span>

</td>
</tr>
<?php }}?>		
</table>

<div class="modal fade" id="edit_theme" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
    	<div class="modal-content login-modal">
      		<div class="modal-header login-modal-header">
        		<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
        		<h4 class="modal-title"><?php echo $this->get_label('edit color theme');?></h4>
      		</div>
      		
      		
      		<div class="modal-body" style="padding: 0px 15px 10px;">
      		
			<div id="update-message" style="font-size: 15px;display: none;text-transform: none;"></div>
							  	
					 	<div class="tab-content">
					    	<div role="tabpanel" class="tab-pane active" style="text-align: left;font-size: 14px;">

									
									<table  style="width:100%;margin-top: 10px;" cellpadding="0" cellspacing="0">		


<tr>
<td style="height: 40px;"><?php echo $this->get_label('theme name');?></td>
<td colspan="4"><input type="text" id="namepop" name="namepop" class="login-pop" style="width: 90%;" value="" /><span class="compulsory">*</span></td>
</tr>

						
<tr>

<td style="width: 160px;height: 30px;"><?php echo $this->get_label('ad title color');?></td>
<td style="width: 80px;"><input type="color" id="color1pop" name="color1pop" class="color-picker"  value="<?php echo "#0078FF"; ?>" /></td>

<td style="width: 5px;"></td>

<td><?php echo $this->get_label('title hover');?></td>
<td><input type="color" id="color23pop" name="color23pop" class="color-picker" value="<?php echo "#000000"; ?>" /></td>
</tr>		


<tr>
<td style="height: 30px;"><?php echo $this->get_label('title background');?></td>
<td><input type="color" id="color18pop" name="color18pop" class="color-picker" value="<?php echo "#FFFFFF"; ?>" /></td>
<td></td>  		
</tr>
						
								
<tr>
<td style="height: 30px;"><?php echo $this->get_label('ad description color');?></td>
<td><input type="color" id="color2pop" name="color2pop" class="color-picker" value="<?php echo "#1437d7"; ?>" /></td>
<td></td>  	

<td><?php echo $this->get_label('description hover');?></td>
<td><input type="color" id="color24pop" name="color24pop" class="color-picker" value="<?php echo "#000000"; ?>" /></td>	
</tr>		


<tr>
<td style="height: 30px;"><?php echo $this->get_label('description background');?></td>
<td><input type="color" id="color19pop" name="color19pop" class="color-picker" value="<?php echo "#FFFFFF"; ?>" /></td>
<td></td>  		
</tr>

<tr>
<td style="height: 30px;"><?php echo $this->get_label('ad display url color');?></td>
<td><input type="color" id="color3pop" name="color3pop" class="color-picker" value="<?php echo "#6ED166"; ?>" /></td>
<td></td>  		

<td><?php echo $this->get_label('display url hover');?></td>
<td><input type="color" id="color25pop" name="color25pop" class="color-picker" value="<?php echo "#000000"; ?>" /></td>

</tr>		


<tr>
<td style="height: 30px;"><?php echo $this->get_label('display url background');?></td>
<td><input type="color" id="color20pop" name="color20pop" class="color-picker" value="<?php echo "#FFFFFF"; ?>" /></td>
<td></td>  		
</tr>

<tr>
<td style="height: 30px;"><?php echo $this->get_label('adcode background color');?></td>
<td><input type="color" id="color5pop" name="color5pop" class="color-picker" value="<?php echo "#E9E9E9"; ?>" /></td>
<td></td> 
<td ><?php echo $this->get_label('image background');?></td>
<td ><input type="color" id="color22pop" name="color22pop" class="color-picker" value="<?php echo "#FFFFFF"; ?>" /></td>
</tr>

<?php if($ecommerce_enabled ==1){?>     
<tr>
<td style="height: 30px;"><?php echo $this->get_label('ad background color');?></td>
<td><input type="color" id="color17pop" name="color17pop" class="color-picker" value="<?php echo "#FFFFFF"; ?>" /></td>
<td></td>  		
</tr>   
<?php } ?>

<tr>
<td style="height: 30px;"><?php echo $this->get_label('border color');?></td>
<td ><input type="color" id="color4pop" name="color4pop" class="color-picker" value="<?php echo "#A1A1A1"; ?>" /></td>
<td></td> 
<td><?php echo $this->get_label('credit color');?></td>
<td><input type="color" id="color6pop" name="color6pop" class="color-picker" value="<?php echo "#E9E9E9"; ?>" /></td>
</tr>
				 
  

<?php if($native_enabled == 1 || $native_enabled == 0){?>
<tr>
<td style="height: 30px;font-size: 14px;" colspan="5"><span style="text-decoration: underline;"><?php echo $this->get_label('native ads settings');?></span></td>
</tr> 


<tr>
<td style="height: 30px;"><?php echo $this->get_label('heading text');?></td>
<td><input type="color" id="color27pop" name="color27pop" class="color-picker" value="<?php echo "#FFFFFF"; ?>" /></td>

<td></td>  	

<td><?php echo $this->get_label('heading hover');?></td>
<td><input type="color" id="color26pop" name="color26pop" class="color-picker" value="<?php echo "#FFFFFF"; ?>" /></td>
</tr>   
   
<tr>
<td style="height: 30px;"><?php echo $this->get_label('heading background');?></td>
<td><input type="color" id="color28pop" name="color28pop" class="color-picker" value="<?php echo "#000000"; ?>" />
</td>
<td></td>  
<td></td>
</tr>   
<?php } ?>




<tr>
<td style="height: 30px;font-size: 14px;" colspan="5"><span style="text-decoration: underline;"><?php echo $this->get_label('call to action button');?></span></td>
</tr> 


<tr>
<td style="height: 30px;"><?php echo $this->get_label('button color');?></td>
<td><input type="color" id="color8pop" name="color8pop" class="color-picker" value="<?php echo "#FFFFFF"; ?>" /></td>

<td></td>  	

<td><?php echo $this->get_label('button background');?></td>
<td><input type="color" id="color9pop" name="color9pop" class="color-picker" value="<?php echo "#373737"; ?>" /></td>
</tr>   
   
<tr>
<td style="height: 30px;"><?php echo $this->get_label('cta background');?></td>
<td><input type="color" id="color21pop" name="color21pop" class="color-picker" value="<?php echo "#FFFFFF"; ?>" /></td>

<td></td>  

<td ><?php echo $this->get_label('button hover background');?></td>
<td><input type="color" id="color10pop" name="color10pop" class="color-picker" value="<?php echo "#DF0000"; ?>" /></td>
</tr>   

<?php if($ecommerce_enabled ==1){?>  

<tr>
<td style="height: 30px;font-size: 14px;" colspan="5"><span style="text-decoration: underline;"><?php echo $this->get_label('price settings');?></span></td>
</tr>  

<tr>
<td style="height: 30px;"><?php echo $this->get_label('price color');?></td>
<td><input type="color" id="color7pop" name="color7pop" class="color-picker" value="<?php echo "#F20001"; ?>" /></td>

<td></td>  	

<td><?php echo $this->get_label('offer price color');?></td>
<td><input type="color" id="color15pop" name="color15pop" class="color-picker" value="<?php echo "#F20001"; ?>" /></td>
</tr>   


<tr style="display: none;">
<td style="height: 30px;"><?php echo $this->get_label('selected border color');?></td>
<td><input type="color" id="color16pop" name="color16pop" class="color-picker" value="<?php echo "#FF0000"; ?>" /></td>
<td></td>  	
<td></td>
<td></td>
</tr>   


<tr>
<td style="height: 30px;font-size: 14px;" colspan="5"><span style="text-decoration: underline;"><?php echo $this->get_label('headline settings');?></span></td>
</tr> 

<tr>
<td style="height: 30px;"><?php echo $this->get_label('heading color');?></td>
<td><input type="color" id="color11pop" name="color11pop" class="color-picker" value="<?php echo "#FE7C00"; ?>" /></td>

<td></td>  	

<td><?php echo $this->get_label('heading button color');?></td>
<td><input type="color" id="color12pop" name="color12pop" class="color-picker" value="<?php echo "#FFFFFF"; ?>" /></td>
</tr>   
   
<tr>
<td style="height: 30px;"><?php echo $this->get_label('heading button background');?></td>
<td><input type="color" id="color13pop" name="color13pop" class="color-picker" value="<?php echo "#FE7C00"; ?>" /></td>

<td></td>  		

<td style="height: 30px;"><?php echo $this->get_label('heading button hover');?></td>
<td><input type="color" id="color14pop" name="color14pop" class="color-picker" value="<?php echo "#FE4200"; ?>" /></td>

</tr> 


<?php }?>
									
<tr>
<td colspan="5" style="text-align: center;height: 50px;">
<input type="hidden" name="idpop" id="idpop" value="" />
<input type="button" name="button11" value="<?php echo $this->get_label('submit');?>" onclick="EditTheme();" />
<span id="load-popup" style="display: none;position: absolute;margin-left: 5px;"><img src="images/load.gif"/></span>
</td>
</tr>									
									
</table>									
									
					    	</div>
					</div>
	      	</div>
    	</div>
	 </div>
</div>





<script type="text/javascript">
$(document).ready(function() {
	$('#edit_theme').on('show.bs.modal', function (event) {

		id=$(event.relatedTarget).attr('address-target');

		$('#namepop').val($('#nametemp'+id).val());


		$('#color1pop').val($('#color1temp'+id).val());
		$('#color2pop').val($('#color2temp'+id).val());
		$('#color3pop').val($('#color3temp'+id).val());
		$('#color4pop').val($('#color4temp'+id).val());
		$('#color5pop').val($('#color5temp'+id).val());
		$('#color6pop').val($('#color6temp'+id).val());
		$('#color8pop').val($('#color8temp'+id).val());
		$('#color9pop').val($('#color9temp'+id).val());
		$('#color10pop').val($('#color10temp'+id).val());
		$('#color18pop').val($('#color18temp'+id).val());
		$('#color19pop').val($('#color19temp'+id).val());
		$('#color20pop').val($('#color20temp'+id).val());
		$('#color21pop').val($('#color21temp'+id).val());
		$('#color22pop').val($('#color22temp'+id).val());

		$('#color23pop').val($('#color23temp'+id).val());
		$('#color24pop').val($('#color24temp'+id).val());
		$('#color25pop').val($('#color25temp'+id).val());
		$('#color26pop').val($('#color26temp'+id).val());
		$('#color27pop').val($('#color27temp'+id).val());
		$('#color28pop').val($('#color28temp'+id).val());


		<?php if($ecommerce_enabled ==1){?> 		
		$('#color7pop').val($('#color7temp'+id).val());
		$('#color11pop').val($('#color11temp'+id).val());
		$('#color12pop').val($('#color12temp'+id).val());
		$('#color13pop').val($('#color13temp'+id).val());
		$('#color14pop').val($('#color14temp'+id).val());
		$('#color15pop').val($('#color15temp'+id).val());
		$('#color16pop').val($('#color16temp'+id).val());
		$('#color17pop').val($('#color17temp'+id).val());
		<?php }?>

		$('#idpop').val(id);		
	});
});
</script>