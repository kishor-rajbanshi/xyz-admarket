<?php 
$this->dispatch("layout/header/4/_47");

$ecommerce_enabled = $this->get_variable('ecommerce_enabled');
$native_enabled    = $this->get_addon_status('native-ad-display_enabled');

?>

<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/bootstrap-3.0.3.min.js'></script>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo COMMON_DIR_PATH;?>css/bootstrap-3.0.3.min.css" />
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
<td colspan="5"><input type="text" id="name" name="name" class="login-pop" style="width: 90%;" value="" /><span class="compulsory">*</span></td>
</tr>

						
<tr>

<td style="width: 160px;height: 30px;"><?php echo $this->get_label('ad title color');?></td>
<td style="width: 95px;"><input type="color" id="color1" name="color1" class="color-picker" value="<?php echo "#0078FF"; ?>" /></td>

<td style="width: 5px;"></td>

<td><?php echo $this->get_label('title hover');?></td>
<td><input type="color" id="color23" name="color23" class="color-picker" value="<?php echo "#000000"; ?>" /></td>
</td>
</tr>	
					
					
<tr>
<td style="height: 30px;"><?php echo $this->get_label('title background');?></td>
<td><input type="color" id="color18" name="color18" class="color-picker" value="<?php echo "#FFFFFF"; ?>" /></td>
<td></td>  
</tr>	



<tr>
<td style="height: 30px;"><?php echo $this->get_label('ad description color');?></td>
<td><input type="color" id="color2" name="color2" class="color-picker" value="<?php echo "#1437d7"; ?>" /></td>
<td></td>
<td><?php echo $this->get_label('description hover');?></td>
<td><input type="color" id="color24" name="color24" class="color-picker" value="<?php echo "#000000"; ?>" /></td>

</tr>


<tr>
<td style="height: 30px;"><?php echo $this->get_label('description background');?></td>
<td><input type="color" id="color19" name="color19" class="color-picker" value="<?php echo "#FFFFFF"; ?>" /></td>
<td></td>  		
</tr>

<tr>
<td style="height: 30px;"><?php echo $this->get_label('ad display url color');?></td>
<td><input type="color" id="color3" name="color3" class="color-picker" value="<?php echo "#6ED166"; ?>" /></td>
<td></td>  	

<td><?php echo $this->get_label('display url hover');?></td>
<td><input type="color" id="color25" name="color25" class="color-picker" value="<?php echo "#000000"; ?>" /></td>


</tr>		


<tr>
<td style="height: 30px;"><?php echo $this->get_label('display url background');?></td>
<td><input type="color" id="color20" name="color20" class="color-picker" value="<?php echo "#FFFFFF"; ?>" /></td>
<td></td>  		
</tr>

<tr>
<td style="height: 30px;"><?php echo $this->get_label('adcode background color');?></td>
<td><input type="color" id="color5" name="color5" class="color-picker" value="<?php echo "#E9E9E9"; ?>" /></td>
<td></td>  	
<td ><?php echo $this->get_label('image background');?></td>
<td ><input type="color" id="color22" name="color22" class="color-picker"  value="<?php echo "#FFFFFF"; ?>" /></td>
</tr>


<?php if($ecommerce_enabled ==1){?> 
<tr>
<td style="height: 30px;"><?php echo $this->get_label('ad background color');?></td>
<td><input type="color" id="color17" name="color17" class="color-picker" value="<?php echo "#FFFFFF"; ?>" /></td>
<td></td>  		
</tr>
<?php } ?>


<tr>
<td style="height: 30px;"><?php echo $this->get_label('border color');?></td>
<td ><input type="color" id="color4" name="color4" class="color-picker"  value="<?php echo "#A1A1A1"; ?>" /></td>
<td></td> 
<td><?php echo $this->get_label('credit color');?></td>
<td><input type="color" id="color6" name="color6" class="color-picker" value="<?php echo "#E9E9E9"; ?>" />
</td>
</tr>



<?php if($native_enabled == 1 || $native_enabled == 0){?>
<tr>
<td style="height: 30px;font-size: 14px;" colspan="5"><span style="text-decoration: underline;"><?php echo $this->get_label('native ads settings');?></span></td>
</tr> 


<tr>
<td style="height: 30px;"><?php echo $this->get_label('heading text');?></td>
<td><input type="color" id="color27" name="color27" class="color-picker" value="<?php echo "#FFFFFF"; ?>" /></td>

<td></td>  	

<td><?php echo $this->get_label('heading hover');?></td>
<td><input type="color" id="color26" name="color26" class="color-picker" value="<?php echo "#FFFFFF"; ?>" /></td>
</tr>   
   
<tr>
<td style="height: 30px;"><?php echo $this->get_label('heading background');?></td>
<td><input type="color" id="color28" name="color28" class="color-picker" value="<?php echo "#000000"; ?>" />
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
<td><input type="color" id="color8" name="color8" class="color-picker" value="<?php echo "#FFFFFF"; ?>" /></td>

<td></td>  	

<td><?php echo $this->get_label('button background');?></td>
<td><input type="color" id="color9" name="color9" class="color-picker" value="<?php echo "#373737"; ?>" /></td>
</tr>   
   
<tr>
<td style="height: 30px;"><?php echo $this->get_label('cta background');?></td>
<td><input type="color" id="color21" name="color21" class="color-picker" value="<?php echo "#FFFFFF"; ?>" /></td>

<td></td>  

<td ><?php echo $this->get_label('button hover background');?></td>
<td><input type="color" id="color10" name="color10" class="color-picker" value="<?php echo "#DF0000"; ?>" /></td>
</tr>   



   
<?php if($ecommerce_enabled ==1){?>  

<tr>
<td style="height: 30px;font-size: 14px;" colspan="5"><span style="text-decoration: underline;"><?php echo $this->get_label('price settings');?></span></td>
</tr>  

<tr>
<td style="height: 30px;"><?php echo $this->get_label('price color');?></td>
<td><input type="color" id="color7" name="color7" class="color-picker" value="<?php echo "#F20001"; ?>" /></td>

<td></td>  	

<td><?php echo $this->get_label('offer price color');?></td>
<td><input type="color" id="color15" name="color15" class="color-picker" value="<?php echo "#F20001"; ?>" /></td>
</tr>  


<tr style="display: none;">
<td style="height: 30px;"><?php echo $this->get_label('selected border color');?></td>
<td><input type="color" id="color16" name="color16" class="color-picker" value="<?php echo "#FF0000"; ?>" /></td>
<td></td>  	
<td></td>
<td></td>
</tr>


<tr>
<td style="height: 30px;font-size: 14px;" colspan="5"><span style="text-decoration: underline;"><?php echo $this->get_label('headline settings');?></span></td>
</tr> 



<tr>
<td style="height: 30px;"><?php echo $this->get_label('heading color');?></td>
<td><input type="color" id="color11" name="color11" class="color-picker" value="<?php echo "#FE7C00"; ?>" /></td>

<td></td>  		

<td style="height: 30px;"><?php echo $this->get_label('heading button color');?></td>
<td><input type="color" id="color12" name="color12" class="color-picker" value="<?php echo "#FFFFFF"; ?>" /></td>

</tr>   



<tr>
<td style="height: 30px;"><?php echo $this->get_label('heading button background');?></td>
<td><input type="color" id="color13" name="color13" class="color-picker" value="<?php echo "#FE7C00"; ?>" /></td>

<td></td>  		

<td style="height: 30px;"><?php echo $this->get_label('heading button hover');?></td>
<td><input type="color" id="color14" name="color14" class="color-picker" value="<?php echo "#FE4200"; ?>" /></td>

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

	color8=$("#color8").val(); 
	color9=$("#color9").val(); 
	color10=$("#color10").val(); 	


	color18=$("#color18").val(); 	
	color19=$("#color19").val(); 	
	color20=$("#color20").val(); 	
	color21=$("#color21").val(); 	
	color22=$("#color22").val(); 	

	color23=$("#color23").val(); 	
	color24=$("#color24").val(); 	
	color25=$("#color25").val(); 	
	color26=$("#color26").val(); 	
	color27=$("#color27").val(); 	
	color28=$("#color28").val(); 	




	color7="";
	color11="";
	color12="";
	color13="";
	color14="";
	color15="";
	color16="";
	color17="";
	
	<?php if($ecommerce_enabled ==1){?> 
	color7=$("#color7").val(); 
	color11=$("#color11").val(); 
	color12=$("#color12").val(); 
	color13=$("#color13").val(); 
	color14=$("#color14").val(); 
	color15=$("#color15").val(); 
	color16=$("#color16").val(); 
	color17=$("#color17").val(); 
	<?php }?>

	
	

	$("#load").show();

		dataparam="name="+name+"&color1="+color1+"&color2="+color2+"&color3="+color3+"&color4="+color4+"&color5="+color5+"&color6="+color6+"&color7="+color7+"&color8="+color8+"&color9="+color9+"&color10="+color10+"&color11="+color11+"&color12="+color12+"&color13="+color13+"&color14="+color14+"&color15="+color15+"&color16="+color16+"&color17="+color17+"&color18="+color18+"&color19="+color19+"&color20="+color20+"&color21="+color21+"&color22="+color22+"&color23="+color23+"&color24="+color24+"&color25="+color25+"&color26="+color26+"&color27="+color27+"&color28="+color28;


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
	color8=$("#color8pop").val(); 
	color9=$("#color9pop").val(); 
	color10=$("#color10pop").val(); 
	color18=$("#color18pop").val(); 
	color19=$("#color19pop").val(); 
	color20=$("#color20pop").val(); 
	color21=$("#color21pop").val(); 
	color22=$("#color22pop").val(); 

	color23=$("#color23pop").val(); 	
	color24=$("#color24pop").val(); 	
	color25=$("#color25pop").val(); 	
	color26=$("#color26pop").val(); 	
	color27=$("#color27pop").val(); 	
	color28=$("#color28pop").val(); 

	color7="";
	color11="";
	color12="";
	color13="";
	color14="";
	color15="";
	color16="";
	color17="";
	
	<?php if($ecommerce_enabled ==1){?> 
	color7=$("#color7pop").val();
	color11=$("#color11pop").val(); 
	color12=$("#color12pop").val(); 
	color13=$("#color13pop").val(); 
	color14=$("#color14pop").val(); 
	color15=$("#color15pop").val(); 
	color16=$("#color16pop").val(); 
	color17=$("#color17pop").val(); 	
	<?php }?>

	

		$("#load-popup").show();

		dataparam="id="+id+"&name="+name+"&color1="+color1+"&color2="+color2+"&color3="+color3+"&color4="+color4+"&color5="+color5+"&color6="+color6+"&color7="+color7+"&color8="+color8+"&color9="+color9+"&color10="+color10+"&color11="+color11+"&color12="+color12+"&color13="+color13+"&color14="+color14+"&color15="+color15+"&color16="+color16+"&color17="+color17+"&color18="+color18+"&color19="+color19+"&color20="+color20+"&color21="+color21+"&color22="+color22+"&color23="+color23+"&color24="+color24+"&color25="+color25+"&color26="+color26+"&color27="+color27+"&color28="+color28;

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
					$('#color1temp'+id).val(color1);
					$('#color2temp'+id).val(color2);
					$('#color3temp'+id).val(color3)
					$('#color4temp'+id).val(color4)
					$('#color5temp'+id).val(color5)
					$('#color6temp'+id).val(color6)
					$('#color8temp'+id).val(color8)
					$('#color9temp'+id).val(color9)
					$('#color10temp'+id).val(color10);
					$('#color18temp'+id).val(color18);
					$('#color19temp'+id).val(color19);
					$('#color20temp'+id).val(color20);
					$('#color21temp'+id).val(color21);
					$('#color22temp'+id).val(color22);
					$('#color23temp'+id).val(color23);
					$('#color24temp'+id).val(color24);
					$('#color25temp'+id).val(color25);
					$('#color26temp'+id).val(color26);
					$('#color27temp'+id).val(color27);
					$('#color28temp'+id).val(color28);


					<?php if($ecommerce_enabled ==1){?> 
					$('#color7temp'+id).val(color7);
					$('#color11temp'+id).val(color11);
					$('#color12temp'+id).val(color12);
					$('#color13temp'+id).val(color13);
					$('#color14temp'+id).val(color14);
					$('#color15temp'+id).val(color15);
					$('#color16temp'+id).val(color16);
					$('#color17temp'+id).val(color17);
					<?php }?>

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