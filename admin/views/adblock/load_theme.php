<script type="text/javascript">
$(document).ready(function() {

    var colorchangepop = true; 
 	var canvaspop = document.getElementById('canvas-color-pop');
 	var canvaspopdimension = canvaspop.getContext('2d');
    
    	 
    var imagepop = new Image();
    
    imagepop.onload = function ()
    {
    	canvaspopdimension.drawImage(imagepop, 0, 0, imagepop.width, imagepop.height); 
    }


    var imagepopsrc = 'images/picker.png';
    imagepop.src = imagepopsrc;
    

  	    
	var focusidpop = "";
	var oldselectedpop = "";
  	    
  	$('.color-picker').click(function()
  	{
  		focusidpop=this.id;
  		colorchangepop = true; 

  		oldselectedpop = $('#'+focusidpop).val();
  		
  		$('.color-picker').removeClass('color-selected');
  		$('#'+focusidpop).addClass('color-selected');
	});


	$('#canvas-color-pop').mousemove(function(e) 
	{ 
	    if(colorchangepop && focusidpop !="") 
		{
	        var canvaspopoffset = $(canvaspop).offset();
			var canvaspopX = Math.floor(e.pageX - canvaspopoffset.left);
			var canvaspopY = Math.floor(e.pageY - canvaspopoffset.top);
		
		
			var imagepopdata = canvaspopdimension.getImageData(canvaspopX, canvaspopY, 1, 1);
			var pixelpop = imagepopdata.data;
			var colorpop = pixelpop[2] + 256 * pixelpop[1] + 65536 * pixelpop[0];

			$('#'+focusidpop).val('#' + ('00000' + colorpop.toString(16)).substr(-6));

			$('#'+focusidpop).css("background-color",'#' + ('00000' + colorpop.toString(16)).substr(-6));
		}
	});

	$('#canvas-color-pop').mouseout(function(e) 
	{ 
		$('#'+focusidpop).val(oldselectedpop);
		$('#'+focusidpop).css("background-color",oldselectedpop);
	});
	
		
	$('#canvas-color-pop').click(function(e) 
	{ 
		colorchangepop = !colorchangepop;

	    oldselectedpop = $('#'+focusidpop).val();
	}); 



	$('.color-picker').keyup(function(e) 
	{ 
		$('#'+focusidpop).css("background-color",$('#'+focusidpop).val());
	});
});
</script>


<table  class="data_table" cellpadding="0" cellspacing="0">
<tr class="row_heading_tr">
<td style="width: 350px;" ><?php echo $this->get_label('theme name');?></td>
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
<input type="text" disabled="disabled" name="nametemp<?php echo $value['id'];?>" id="nametemp<?php echo $value['id'];?>" value="<?php echo $value['theme'];?>" style="border: 0px;"/>


<input type="hidden" name="color1temp<?php echo $value['id'];?>" id="color1temp<?php echo $value['id'];?>" value="<?php echo $value['title'];?>" />
<input type="hidden" name="color2temp<?php echo $value['id'];?>" id="color2temp<?php echo $value['id'];?>" value="<?php echo $value['description'];?>" />
<input type="hidden" name="color3temp<?php echo $value['id'];?>" id="color3temp<?php echo $value['id'];?>" value="<?php echo $value['url'];?>" />
<input type="hidden" name="color4temp<?php echo $value['id'];?>" id="color4temp<?php echo $value['id'];?>" value="<?php echo $value['border'];?>" />
<input type="hidden" name="color5temp<?php echo $value['id'];?>" id="color5temp<?php echo $value['id'];?>" value="<?php echo $value['background'];?>" />
<input type="hidden" name="color6temp<?php echo $value['id'];?>" id="color6temp<?php echo $value['id'];?>" value="<?php echo $value['credit'];?>" />

<?php if($ecommerce_enabled ==1){?> 	
<input type="hidden" name="color7temp<?php echo $value['id'];?>" id="color7temp<?php echo $value['id'];?>" value="<?php echo $value['price'];?>" />
<input type="hidden" name="color8temp<?php echo $value['id'];?>" id="color8temp<?php echo $value['id'];?>" value="<?php echo $value['button'];?>" />
<input type="hidden" name="color9temp<?php echo $value['id'];?>" id="color9temp<?php echo $value['id'];?>" value="<?php echo $value['button_background'];?>" />
<input type="hidden" name="color10temp<?php echo $value['id'];?>" id="color10temp<?php echo $value['id'];?>" value="<?php echo $value['button_hover'];?>" />
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



<?php if($value['theme_type'] ==0){?>
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
<td colspan="4"><input type="text" id="namepop" name="namepop" class="login-pop" style="width: 200px;" value="" /><span class="compulsory">*</span></td>
</tr>

						
<tr>

<td style="width: 200px;"><?php echo $this->get_label('ad title color');?></td>
<td style="width: 100px;"><input type="text" id="color1pop" name="color1pop" class="login-pop color-picker color-selected" size="5" value="<?php echo "#0078FF"; ?>" style="background-color:<?php echo "#0078FF"; ?>" maxlength="7" onkeyup="this.value = this.value.replace(/[^A-Za-z0-9\#]/g,'');" /></td>

<td style="width: 20px;"></td>

<td rowspan="6" colspan="2" style="background-color: #474848;width: 130px;">
<canvas id="canvas-color-pop" var="1" width="200" height="200"></canvas>
</td>
</tr>								
								
<tr>
<td><?php echo $this->get_label('ad description color');?></td>
<td><input type="text" id="color2pop" name="color2pop" class="login-pop color-picker" size="5" value="<?php echo "#1437d7"; ?>" style="background-color:<?php echo "#1437d7"; ?>" maxlength="7" onkeyup="this.value = this.value.replace(/[^A-Za-z0-9\#]/g,'');" /></td>
<td></td>  		
</tr>		


<tr>
<td><?php echo $this->get_label('ad display url color');?></td>
<td><input type="text" id="color3pop" name="color3pop" class="login-pop color-picker" size="5" value="<?php echo "#6ED166"; ?>" style="background-color:<?php echo "#6ED166"; ?>" maxlength="7" onkeyup="this.value = this.value.replace(/[^A-Za-z0-9\#]/g,'');" /></td>
<td></td>  		
</tr>		


<tr>
<td><?php echo $this->get_label('border color');?></td>
<td><input type="text" id="color4pop" name="color4pop" class="login-pop color-picker" size="5" value="<?php echo "#A1A1A1"; ?>" style="background-color:<?php echo "#A1A1A1"; ?>" maxlength="7" onkeyup="this.value = this.value.replace(/[^A-Za-z0-9\#]/g,'');" /></td>

<td></td>  		
</tr>	


<tr>
<td><?php echo $this->get_label('background color');?></td>
<td><input type="text" id="color5pop" name="color5pop" class="login-pop color-picker" size="5" value="<?php echo "#E9E9E9"; ?>" style="background-color:<?php echo "#E9E9E9"; ?>" maxlength="7" onkeyup="this.value = this.value.replace(/[^A-Za-z0-9\#]/g,'');" /></td>

<td></td>  		
</tr>
				 
   
<tr>
<td><?php echo $this->get_label('credit color');?></td>
<td><input type="text" id="color6pop" name="color6pop" class="login-pop color-picker" size="5" value="<?php echo "#E9E9E9"; ?>" style="background-color:<?php echo "#E9E9E9"; ?>" maxlength="7" onkeyup="this.value = this.value.replace(/[^A-Za-z0-9\#]/g,'');" /></td>

<td></td>  		
</tr>   
   
   
<?php if($ecommerce_enabled ==1){?>  

<tr>
<td style="height: 30px;"><?php echo $this->get_label('ad background color');?></td>
<td><input type="text" id="color17pop" name="color17pop" class="login-pop color-picker" size="5" value="<?php echo "#FFFFFF"; ?>" style="background-color:<?php echo "#FFFFFF"; ?>" maxlength="7" onkeyup="this.value = this.value.replace(/[^A-Za-z0-9\#]/g,'');" /></td>
<td></td>  		
</tr>



<tr>
<td style="height: 30px;font-size: 14px;" colspan="5"><span style="text-decoration: underline;"><?php echo $this->get_label('price settings');?></span></td>
</tr>  

<tr>
<td style="height: 30px;"><?php echo $this->get_label('price color');?></td>
<td><input type="text" id="color7pop" name="color7pop" class="login-pop color-picker" size="5" value="<?php echo "#F20001"; ?>" style="background-color:<?php echo "#F20001"; ?>" maxlength="7" onkeyup="this.value = this.value.replace(/[^A-Za-z0-9\#]/g,'');" /></td>

<td></td>  	

<td><?php echo $this->get_label('offer price color');?></td>
<td><input type="text" id="color15pop" name="color15pop" class="login-pop color-picker" size="5" value="<?php echo "#F20001"; ?>" style="background-color:<?php echo "#F20001"; ?>" maxlength="7" onkeyup="this.value = this.value.replace(/[^A-Za-z0-9\#]/g,'');" /></td>
</tr>   


<tr style="display: none;">
<td style="height: 30px;"><?php echo $this->get_label('selected border color');?></td>
<td><input type="text" id="color16pop" name="color16pop" class="login-pop color-picker" size="5" value="<?php echo "#FF0000"; ?>" style="background-color:<?php echo "#FF0000"; ?>" maxlength="7" onkeyup="this.value = this.value.replace(/[^A-Za-z0-9\#]/g,'');" /></td>
<td></td>  	
<td></td>
<td></td>
</tr>   


<tr>
<td style="height: 30px;font-size: 14px;" colspan="5"><span style="text-decoration: underline;"><?php echo $this->get_label('call to action button');?></span></td>
</tr> 


<tr>
<td style="height: 30px;"><?php echo $this->get_label('button color');?></td>
<td><input type="text" id="color8pop" name="color8pop" class="login-pop color-picker" size="5" value="<?php echo "#FFFFFF"; ?>" style="background-color:<?php echo "#FFFFFF"; ?>" maxlength="7" onkeyup="this.value = this.value.replace(/[^A-Za-z0-9\#]/g,'');" /></td>

<td></td>  	

<td><?php echo $this->get_label('button background');?></td>
<td><input type="text" id="color9pop" name="color9pop" class="login-pop color-picker" size="5" value="<?php echo "#373737"; ?>" style="background-color:<?php echo "#373737"; ?>" maxlength="7" onkeyup="this.value = this.value.replace(/[^A-Za-z0-9\#]/g,'');" /></td>
</tr>   
   
<tr>
<td style="height: 30px;"><?php echo $this->get_label('button hover background');?></td>
<td><input type="text" id="color10pop" name="color10pop" class="login-pop color-picker" size="5" value="<?php echo "#DF0000"; ?>" style="background-color:<?php echo "#DF0000"; ?>" maxlength="7" onkeyup="this.value = this.value.replace(/[^A-Za-z0-9\#]/g,'');" /></td>

<td></td>  		
<td></td>
<td></td>
</tr>   

<tr>
<td style="height: 30px;font-size: 14px;" colspan="5"><span style="text-decoration: underline;"><?php echo $this->get_label('headline settings');?></span></td>
</tr> 

<tr>
<td style="height: 30px;"><?php echo $this->get_label('heading color');?></td>
<td><input type="text" id="color11pop" name="color11pop" class="login-pop color-picker" size="5" value="<?php echo "#FE7C00"; ?>" style="background-color:<?php echo "#FE7C00"; ?>" maxlength="7" onkeyup="this.value = this.value.replace(/[^A-Za-z0-9\#]/g,'');" /></td>

<td></td>  	

<td><?php echo $this->get_label('heading button color');?></td>
<td><input type="text" id="color12pop" name="color12pop" class="login-pop color-picker" size="5" value="<?php echo "#FFFFFF"; ?>" style="background-color:<?php echo "#FFFFFF"; ?>" maxlength="7" onkeyup="this.value = this.value.replace(/[^A-Za-z0-9\#]/g,'');" /></td>
</tr>   
   
<tr>
<td style="height: 30px;"><?php echo $this->get_label('heading button background');?></td>
<td><input type="text" id="color13pop" name="color13pop" class="login-pop color-picker" size="5" value="<?php echo "#FE7C00"; ?>" style="background-color:<?php echo "#FE7C00"; ?>" maxlength="7" onkeyup="this.value = this.value.replace(/[^A-Za-z0-9\#]/g,'');" /></td>

<td></td>  		

<td style="height: 30px;"><?php echo $this->get_label('heading button hover');?></td>
<td><input type="text" id="color14pop" name="color14pop" class="login-pop color-picker" size="5" value="<?php echo "#FE4200"; ?>" style="background-color:<?php echo "#FE4200"; ?>" maxlength="7" onkeyup="this.value = this.value.replace(/[^A-Za-z0-9\#]/g,'');" /></td>

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


		$('#color1pop').css('background-color',$('#color1temp'+id).val());
		$('#color2pop').css('background-color',$('#color2temp'+id).val());
		$('#color3pop').css('background-color',$('#color3temp'+id).val());
		$('#color4pop').css('background-color',$('#color4temp'+id).val());
		$('#color5pop').css('background-color',$('#color5temp'+id).val());
		$('#color6pop').css('background-color',$('#color6temp'+id).val());



		<?php if($ecommerce_enabled ==1){?> 		
		$('#color7pop').val($('#color7temp'+id).val());
		$('#color8pop').val($('#color8temp'+id).val());
		$('#color9pop').val($('#color9temp'+id).val());
		$('#color10pop').val($('#color10temp'+id).val());
		$('#color11pop').val($('#color11temp'+id).val());
		$('#color12pop').val($('#color12temp'+id).val());
		$('#color13pop').val($('#color13temp'+id).val());
		$('#color14pop').val($('#color14temp'+id).val());
		$('#color15pop').val($('#color15temp'+id).val());
		$('#color16pop').val($('#color16temp'+id).val());
		$('#color17pop').val($('#color17temp'+id).val());


		$('#color7pop').css('background-color',$('#color7temp'+id).val());
		$('#color8pop').css('background-color',$('#color8temp'+id).val());
		$('#color9pop').css('background-color',$('#color9temp'+id).val());
		$('#color10pop').css('background-color',$('#color10temp'+id).val());
		$('#color11pop').css('background-color',$('#color11temp'+id).val());
		$('#color12pop').css('background-color',$('#color12temp'+id).val());
		$('#color13pop').css('background-color',$('#color13temp'+id).val());
		$('#color14pop').css('background-color',$('#color14temp'+id).val());
		$('#color15pop').css('background-color',$('#color15temp'+id).val());
		$('#color16pop').css('background-color',$('#color16temp'+id).val());
		$('#color17pop').css('background-color',$('#color17temp'+id).val());
		<?php }?>

		$('#idpop').val(id);		
	});
});
</script>