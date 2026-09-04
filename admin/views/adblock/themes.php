<?php 
$this->dispatch("layout/header/4/_47");

$ecommerce_enabled=$this->get_variable('ecommerce_enabled');
?>

<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/bootstrap.min.js'></script>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo COMMON_DIR_PATH;?>css/bootstrap.min.css" />

<script type="text/javascript">
$(document).ready(function() {

    var colorchange = true; 
 	var canvas = document.getElementById('canvas-color');
 	var canvasdimension = canvas.getContext('2d');
    
    	 
    var image = new Image();
    
    image.onload = function ()
    {
    	canvasdimension.drawImage(image, 0, 0, image.width, image.height); 
    }


    var imagesrc = 'images/picker.png';
    image.src = imagesrc;
    
	var focusid = "";
	var oldselected = "";
  	    
  	$('.color-picker').click(function()
  	{
  		focusid=this.id;
  		colorchange = true; 

  		oldselected = $('#'+focusid).val();
  		
  		$('.color-picker').removeClass('color-selected');
  		$('#'+focusid).addClass('color-selected');
	});


	$('#canvas-color').mousemove(function(e) 
	{ 
	    if(colorchange && focusid !="") 
		{
	        var canvasoffset = $(canvas).offset();
			var canvasX = Math.floor(e.pageX - canvasoffset.left);
			var canvasY = Math.floor(e.pageY - canvasoffset.top);
		
		
			var imagedata = canvasdimension.getImageData(canvasX, canvasY, 1, 1);
			var pixel = imagedata.data;
			var color = pixel[2] + 256 * pixel[1] + 65536 * pixel[0];

			$('#'+focusid).val('#' + ('00000' + color.toString(16)).substr(-6));

			$('#'+focusid).css("background-color",'#' + ('00000' + color.toString(16)).substr(-6));
		}
	});

	$('#canvas-color').mouseout(function(e) 
	{ 
		$('#'+focusid).val(oldselected);
		$('#'+focusid).css("background-color",oldselected);
	});
	
		
	$('#canvas-color').click(function(e) 
	{ 
		colorchange = !colorchange;

	    oldselected = $('#'+focusid).val();
	}); 



	$('.color-picker').keyup(function(e) 
	{ 
		$('#'+focusid).css("background-color",$('#'+focusid).val());
	});
});
</script>

<style type="text/css">
.modal-open {overflow: auto;}

.modal-dialog {width:500px;}

h2 {margin-top: 0px;}

*, *::before, *::after {box-sizing: unset !important;}
</style>

<div class="sub_menu_main"><?php echo $this->get_label('ad display themes');?></div>

<table  style="width:100%;" cellpadding="0" cellspacing="0">
<tr><td>


<div id="theme-delete-message" style="display: none;"></div>


<input class="get_popup_btn" style="position: absolute;right: 5px;top: 10px;" type="button" data-toggle="modal" data-target="#NewTheme" value="<?php echo $this->get_label('create theme');?>" />

<div class="modal fade" id="NewTheme" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" style="width: 550px;">
    	<div class="modal-content login-modal">
      		<div class="modal-header login-modal-header">
        	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
        	<h4 class="modal-title"><?php echo $this->get_label('create new theme');?></h4>
      		</div>
      		
      		
      		<div class="modal-body" style="padding: 0px 15px 10px;">
      		
      		<div id="create-message" style="font-size: 15px;display: none;text-transform: none;"></div>
							  	
					 	<div class="tab-content">
					    <div role="tabpanel" class="tab-pane active" style="text-align: left;font-size: 14px;">

							
<table  style="width:100%;margin-top: 10px;" cellpadding="0" cellspacing="0">		


<tr>
<td style="height: 40px;"><?php echo $this->get_label('theme name');?></td>
<td colspan="4"><input type="text" id="name" name="name" class="login-pop" style="width: 200px;" value="" /><span class="compulsory">*</span></td>
</tr>

						
<tr>

<td style="width: 200px;"><?php echo $this->get_label('ad title color');?></td>
<td style="width: 100px;"><input type="text" id="color1" name="color1" class="login-pop color-picker color-selected" size="5" value="<?php echo "#0078FF"; ?>" style="background-color:<?php echo "#0078FF"; ?>" maxlength="7" onkeyup="this.value = this.value.replace(/[^A-Za-z0-9\#]/g,'');" /></td>

<td style="width: 20px;"></td>

<td rowspan="6" colspan="2" style="background-color: #474848;width: 130px;">
<canvas id="canvas-color" var="1" width="200" height="200"></canvas>
</td>
</tr>								
								
<tr>
<td><?php echo $this->get_label('ad description color');?></td>
<td><input type="text" id="color2" name="color2" class="login-pop color-picker" size="5" value="<?php echo "#1437d7"; ?>" style="background-color:<?php echo "#1437d7"; ?>" maxlength="7" onkeyup="this.value = this.value.replace(/[^A-Za-z0-9\#]/g,'');" /></td>
<td></td>  		
</tr>		


<tr>
<td><?php echo $this->get_label('ad display url color');?></td>
<td><input type="text" id="color3" name="color3" class="login-pop color-picker" size="5" value="<?php echo "#6ED166"; ?>" style="background-color:<?php echo "#6ED166"; ?>" maxlength="7" onkeyup="this.value = this.value.replace(/[^A-Za-z0-9\#]/g,'');" /></td>
<td></td>  		
</tr>		


<tr>
<td><?php echo $this->get_label('border color');?></td>
<td><input type="text" id="color4" name="color4" class="login-pop color-picker" size="5" value="<?php echo "#A1A1A1"; ?>" style="background-color:<?php echo "#A1A1A1"; ?>" maxlength="7" onkeyup="this.value = this.value.replace(/[^A-Za-z0-9\#]/g,'');" /></td>

<td></td>  		
</tr>	


<tr>
<td><?php echo $this->get_label('background color');?></td>
<td><input type="text" id="color5" name="color5" class="login-pop color-picker" size="5" value="<?php echo "#E9E9E9"; ?>" style="background-color:<?php echo "#E9E9E9"; ?>" maxlength="7" onkeyup="this.value = this.value.replace(/[^A-Za-z0-9\#]/g,'');" /></td>

<td></td>  		
</tr>
				 
   
<tr>
<td><?php echo $this->get_label('credit color');?></td>
<td><input type="text" id="color6" name="color6" class="login-pop color-picker" size="5" value="<?php echo "#E9E9E9"; ?>" style="background-color:<?php echo "#E9E9E9"; ?>" maxlength="7" onkeyup="this.value = this.value.replace(/[^A-Za-z0-9\#]/g,'');" /></td>

<td></td>  		
</tr>   
   
   
<?php if($ecommerce_enabled ==1){?>  


<tr>
<td><?php echo $this->get_label('ad background color');?></td>
<td><input type="text" id="color17" name="color17" class="login-pop color-picker" size="5" value="<?php echo "#FFFFFF"; ?>" style="background-color:<?php echo "#FFFFFF"; ?>" maxlength="7" onkeyup="this.value = this.value.replace(/[^A-Za-z0-9\#]/g,'');" /></td>
<td></td>  		
</tr>





<tr>
<td style="height: 30px;font-size: 14px;" colspan="5"><span style="text-decoration: underline;"><?php echo $this->get_label('price settings');?></span></td>
</tr>  




<tr>
<td style="height: 30px;"><?php echo $this->get_label('price color');?></td>
<td><input type="text" id="color7" name="color7" class="login-pop color-picker" size="5" value="<?php echo "#F20001"; ?>" style="background-color:<?php echo "#F20001"; ?>" maxlength="7" onkeyup="this.value = this.value.replace(/[^A-Za-z0-9\#]/g,'');" /></td>

<td></td>  	

<td><?php echo $this->get_label('offer price color');?></td>
<td><input type="text" id="color15" name="color15" class="login-pop color-picker" size="5" value="<?php echo "#F20001"; ?>" style="background-color:<?php echo "#F20001"; ?>" maxlength="7" onkeyup="this.value = this.value.replace(/[^A-Za-z0-9\#]/g,'');" /></td>
</tr>  


<tr style="display: none;">
<td style="height: 30px;"><?php echo $this->get_label('selected border color');?></td>
<td><input type="text" id="color16" name="color16" class="login-pop color-picker" size="5" value="<?php echo "#FF0000"; ?>" style="background-color:<?php echo "#FF0000"; ?>" maxlength="7" onkeyup="this.value = this.value.replace(/[^A-Za-z0-9\#]/g,'');" /></td>
<td></td>  	
<td></td>
<td></td>
</tr>

<tr>
<td style="height: 30px;font-size: 14px;" colspan="5"><span style="text-decoration: underline;"><?php echo $this->get_label('call to action button');?></span></td>
</tr> 


<tr>
<td style="height: 40px;"><?php echo $this->get_label('button color');?></td>
<td><input type="text" id="color8" name="color8" class="login-pop color-picker" size="5" value="<?php echo "#FFFFFF"; ?>" style="background-color:<?php echo "#FFFFFF"; ?>" maxlength="7" onkeyup="this.value = this.value.replace(/[^A-Za-z0-9\#]/g,'');" /></td>

<td></td>  	

<td><?php echo $this->get_label('button background');?></td>
<td><input type="text" id="color9" name="color9" class="login-pop color-picker" size="5" value="<?php echo "#373737"; ?>" style="background-color:<?php echo "#373737"; ?>" maxlength="7" onkeyup="this.value = this.value.replace(/[^A-Za-z0-9\#]/g,'');" /></td>
</tr>   
   
<tr>
<td style="height: 30px;"><?php echo $this->get_label('button hover background');?></td>
<td><input type="text" id="color10" name="color10" class="login-pop color-picker" size="5" value="<?php echo "#DF0000"; ?>" style="background-color:<?php echo "#DF0000"; ?>" maxlength="7" onkeyup="this.value = this.value.replace(/[^A-Za-z0-9\#]/g,'');" /></td>

<td></td>  		
<td></td>
<td></td>
</tr>   


<tr>
<td style="height: 30px;font-size: 14px;" colspan="5"><span style="text-decoration: underline;"><?php echo $this->get_label('headline settings');?></span></td>
</tr> 



<tr>
<td style="height: 30px;"><?php echo $this->get_label('heading color');?></td>
<td><input type="text" id="color11" name="color11" class="login-pop color-picker" size="5" value="<?php echo "#FE7C00"; ?>" style="background-color:<?php echo "#FE7C00"; ?>" maxlength="7" onkeyup="this.value = this.value.replace(/[^A-Za-z0-9\#]/g,'');" /></td>

<td></td>  		

<td style="height: 30px;"><?php echo $this->get_label('heading button color');?></td>
<td><input type="text" id="color12" name="color12" class="login-pop color-picker" size="5" value="<?php echo "#FFFFFF"; ?>" style="background-color:<?php echo "#FFFFFF"; ?>" maxlength="7" onkeyup="this.value = this.value.replace(/[^A-Za-z0-9\#]/g,'');" /></td>

</tr>   



<tr>
<td style="height: 30px;"><?php echo $this->get_label('heading button background');?></td>
<td><input type="text" id="color13" name="color13" class="login-pop color-picker" size="5" value="<?php echo "#FE7C00"; ?>" style="background-color:<?php echo "#FE7C00"; ?>" maxlength="7" onkeyup="this.value = this.value.replace(/[^A-Za-z0-9\#]/g,'');" /></td>

<td></td>  		

<td style="height: 30px;"><?php echo $this->get_label('heading button hover');?></td>
<td><input type="text" id="color14" name="color14" class="login-pop color-picker" size="5" value="<?php echo "#FE4200"; ?>" style="background-color:<?php echo "#FE4200"; ?>" maxlength="7" onkeyup="this.value = this.value.replace(/[^A-Za-z0-9\#]/g,'');" /></td>

</tr> 


<?php }?>


<tr>
<td colspan="5" style="text-align: center;height: 50px;">
<input type="button" name="button1" value="<?php echo $this->get_label('submit');?>" onclick="AddTheme();" />
<span id="load" style="display: none;position: absolute;margin-left: 5px;"><img src="images/load.gif"/></span>
</td>
</tr>

</table>
		  	
					</div>
				</div>
	      	</div>
    	</div>
	 </div>
</div>


</td>
</tr>
</table>


<div id="theme-list"></div>



<script type="text/javascript">
function AddTheme()
{
	name=$("#name").val();
	
	color1=$("#color1").val(); 
	color2=$("#color2").val(); 
	color3=$("#color3").val(); 
	color4=$("#color4").val(); 
	color5=$("#color5").val(); 
	color6=$("#color6").val();



	color7="";
	color8="";
	color9="";
	color10="";
	color11="";
	color12="";
	color13="";
	color14="";
	color15="";
	color16="";
	color17="";
	
	<?php if($ecommerce_enabled ==1){?> 
	color7=$("#color7").val(); 
	color8=$("#color8").val(); 
	color9=$("#color9").val(); 
	color10=$("#color10").val(); 
	color11=$("#color11").val(); 
	color12=$("#color12").val(); 
	color13=$("#color13").val(); 
	color14=$("#color14").val(); 
	color15=$("#color15").val(); 
	color16=$("#color16").val(); 
	color17=$("#color17").val(); 
	<?php }?>

	
	

	$("#load").show();

		dataparam="name="+name+"&color1="+color1+"&color2="+color2+"&color3="+color3+"&color4="+color4+"&color5="+color5+"&color6="+color6+"&color7="+color7+"&color8="+color8+"&color9="+color9+"&color10="+color10+"&color11="+color11+"&color12="+color12+"&color13="+color13+"&color14="+color14+"&color15="+color15+"&color16="+color16+"&color17="+color17;

		var urlvalue='<?php echo $this->make_url("adblock/create_theme");?>';

		$.ajax(
		{
			type: "POST",
			data: dataparam,
			url: urlvalue,
			success: function(msg)
			{
				message="";
				
				if(msg ==1)
				message="<?php echo $this->get_message('theme name already exists');?>";
				else if(msg ==2)
				{
					message="<?php echo $this->get_message('theme create success');?>";

					$("#name").val(""); 
				}
				
					
				$('#create-message').html(message);
	
				if(msg !=2)
				$('#create-message').css('color','red');
				else
				$('#create-message').css('color','green');	

				$('#create-message').show();
				
				
				$("#load").hide();

				if(msg ==2)
				{
					LoadTheme();

					setTimeout(function(){
						$('#create-message').slideUp();
					},1500);
					
				}
			}
		});

}

function LoadTheme()
{
	dataparam="";
	var urlvalue='<?php echo $this->make_url("adblock/load_theme");?>';
	$.ajax(
	{
		type: "POST",
		data: dataparam,
		url: urlvalue,
		success: function(msg)
		{
		    $("#theme-list").html(msg);
		}
	});
}


function DeleteTheme(id)
{
	if(confirm("<?php echo $this->get_message('do you really want to delete this theme');?>"))
	{
		$("#load"+id).show();

		dataparam="id="+id;

		var urlvalue='<?php echo $this->make_url("adblock/delete_theme");?>';

		$.ajax(
		{
			type: "POST",
			data: dataparam,
			url: urlvalue,
			success: function(msg)
			{
				message="";

				if(msg ==1)
				message="<?php echo $this->get_message('invalid operation');?>";
				else if(msg ==2)
				{
					message="<?php echo $this->get_message('theme delete success');?>";
					LoadTheme();
				}


				$('#theme-delete-message').html(message);
	
				if(msg ==1)
				$('#theme-delete-message').css('color','red');
				else
				$('#theme-delete-message').css('color','green');	

				$('#theme-delete-message').show();
				
				setTimeout(function(){
					$('#theme-delete-message').slideUp();
				},1500);
			}
		});
	}
}


function EditTheme()
{
	id=$("#idpop").val(); 

	name=$("#namepop").val();
	
	color1=$("#color1pop").val(); 
	color2=$("#color2pop").val(); 
	color3=$("#color3pop").val(); 
	color4=$("#color4pop").val(); 
	color5=$("#color5pop").val(); 
	color6=$("#color6pop").val();



	color7="";
	color8="";
	color9="";
	color10="";
	color11="";
	color12="";
	color13="";
	color14="";
	color15="";
	color16="";
	color17="";
	
	<?php if($ecommerce_enabled ==1){?> 
	color7=$("#color7pop").val(); 
	color8=$("#color8pop").val(); 
	color9=$("#color9pop").val(); 
	color10=$("#color10pop").val(); 
	color11=$("#color11pop").val(); 
	color12=$("#color12pop").val(); 
	color13=$("#color13pop").val(); 
	color14=$("#color14pop").val(); 
	color15=$("#color15pop").val(); 
	color16=$("#color16pop").val(); 
	color17=$("#color17pop").val(); 
	<?php }?>

	

		$("#load-popup").show();

		dataparam="id="+id+"&name="+name+"&color1="+color1+"&color2="+color2+"&color3="+color3+"&color4="+color4+"&color5="+color5+"&color6="+color6+"&color7="+color7+"&color8="+color8+"&color9="+color9+"&color10="+color10+"&color11="+color11+"&color12="+color12+"&color13="+color13+"&color14="+color14+"&color15="+color15+"&color16="+color16+"&color17="+color17;

		var urlvalue='<?php echo $this->make_url("adblock/edit_theme");?>';

		$.ajax(
		{
			type: "POST",
			data: dataparam,
			url: urlvalue,
			success: function(msg)
			{
				message="";

				if(msg ==1)
				message="<?php echo $this->get_message('theme name already exists');?>";
				else if(msg ==3)
				message="<?php echo $this->get_message('invalid operation');?>";	
				else if(msg ==2)
				{
					message="<?php echo $this->get_message('theme edit success');?>";

					$('#nametemp'+id).val(name);
				}

				$('#update-message').html(message);
	
				if(msg !=2)
				$('#update-message').css('color','red');
				else
				$('#update-message').css('color','green');	

				$('#update-message').show();
				
				$("#load-popup").hide();

				if(msg ==2)
				{
					setTimeout(function(){
						$('#update-message').slideUp();
					},1500);
				}
			}
		});
}



$(document).ready(function() {
	LoadTheme();
});
</script>
<?php $this->dispatch("layout/footer");?>	