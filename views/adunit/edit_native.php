<?php $this->dispatch("layout/header/6/2/p");

$basepath=str_replace('https://','',BASE);
$basepath=str_replace('http://','',$basepath);

$textimage_enabled=$this->get_addon_status('text-image-ads_enabled');
$text_ads_enabled=Configuration::get_instance()->read('text-ads_enabled');


$pop_enabled=$this->get_variable('pop_enabled');
$pricing=$this->get_variable('pricing');

$colorpicker=$this->get_variable('flag');;
   

?>
<style type="text/css">
.adblock-edit .form-group,.adblock-edit .form-group label
{
	padding-left:0px;
	padding-right:0px;
}
 </style>
 
 
<?php if($pricing !=9 && $colorpicker==1){?>
<script type="text/javascript" src="<?php echo COMMON_DIR_PATH;?>farbtastic/farbtastic.js"></script>
<link rel="stylesheet" href="<?php echo COMMON_DIR_PATH;?>farbtastic/farbtastic.css" type="text/css" />

<script type="text/javascript" charset="utf-8">
$(document).ready(function() {
	if($('.color-picker').length >0)
	{
	    var f = $.farbtastic('#picker');
	    var p = $('#picker').css('opacity', 0.25);
	    var selected;

	    $('.color-picker').each(function () { f.linkTo(this); $(this).css('opacity', 0.75); })
	      .focus(function() 
	      {
	    	if(selected) {$(selected).css('opacity', 0.75);}
		      
	        f.linkTo(this);
	        $(this).css('opacity',1);
	        p.css('opacity', 1);
	        $(selected = this).css('opacity', 1);
	      });
	 }
});
</script>
<?php }?>


<script type="text/javascript">
function ShowPreview(id)
{
	if(id ==1)
	{
	    if($('#text').length >0)
	    $('#text').attr('checked',true);

	    $('#td1').show();
	    $('#bd1').hide();
	}

	if(id ==2)
	{
		if($('#banner').length >0)
		$('#banner').attr('checked',true);

		 $('#td1').hide();
		 $('#bd1').show();
	}
}
 
 </script>
<?php 
$cpm_enabled=$this->get_addon_status('cpm_enabled');
$html_enabled=$this->get_addon_status('html_enabled');
$category_enabled=$this->get_addon_status('category-targeting_enabled');


$res=$this->get_result('res');
$aduid=$this->get_variable('aduid');
$uid=$this->get_variable("uid");



$result=$res[0]; 

$layout=$result['layout'];


$rows=$this->get_variable('rows');
$columns=$this->get_variable('columns');
$img_postion=$this->get_variable('img_postion');
$typedata=$this->get_variable('typedata');

$nativead_minwidth_left_aligned=$this->get_variable('nativead_minwidth_left_aligned');
$nativead_minwidth_top_aligned=$this->get_variable('nativead_minwidth_top_aligned');
$nativead_minheight_left_aligned=$this->get_variable('nativead_minheight_left_aligned');
$nativead_minheight_top_aligned=$this->get_variable('nativead_minheight_top_aligned');



$nativead_header_color=Configuration::get_instance()->read('nativead_header_color');
$nativead_header=Configuration::get_instance()->read('nativead_header');
$responsive_ad_minlimit=Configuration::get_instance()->read('responsive_ad_minlimit');
$responsive_ad_maxlimit=Configuration::get_instance()->read('responsive_ad_maxlimit');
$nativead_padding=Configuration::get_instance()->read('native_padding');
$nativetextad_minwidth=Configuration::get_instance()->read('nativetextad_minwidth');
$nativetextad_minheight=Configuration::get_instance()->read('nativetextad_minheight');





if($typedata ==11)
{
	if($img_postion ==0)
	{
		$width1=$columns*$nativead_minwidth_left_aligned;
	 	$height1=$rows*$nativead_minheight_left_aligned;
	}
	else
	{
		$width1=$columns*$nativead_minwidth_top_aligned;
		$height1=$rows*$nativead_minheight_top_aligned;
	}
}
else 
{
	$width1=$columns*$nativetextad_minwidth;
	$height1=$rows*$nativetextad_minheight;
}


$width1=$width1+2;
$height1=$height1+(30+(2*$nativead_padding))+2; //For headline text & border

$validate=array(
		"aduname"=>array(
				"notNull"=>array($this->get_message("not null"))
		));


$customcode=$result['custom_code'];

if ($customcode=='')
{
	$customcode='<style type="text/css">
	/* enter your CSS here */
	</style>

	<script type="text/javascript">
	// enter your JavaScript here
	</script>';
}

?>

<div class="container"><h2 class="page_heading"><?php echo $this->get_label('edit native ad code');?></h2>
<div class="page_heading-btm"></div>


 <div class="row label_style special-label">
   <div class="col-md-12 col-sm-12 col-xs-12 box_style">
   <div class="col-md-12 col-sm-12 col-xs-12 box_div" style="padding-top: 0px;">



<div class="col-md-12 col-sm-12 col-xs-12">
<div class="col-md-12 col-sm-12 col-xs-12">
<h2 class="page_heading"><?php echo $this->get_label('adunit preview');?></h2>
</div>

	<?php if($typedata ==1){?>
    <div class="col-md-12 col-sm-12 col-xs-12" id="td1" style="height:auto;">
    <iframe class="iframe" id="iframe" style="<?php if($result['responsive']==1){?> width:100%;<?php } else {?>height:<?php echo $height1;?>px; width:<?php echo $width1;?>px;<?php }?>" frameborder="0" src="<?php echo $this->make_url("adunit/preview_native/".$result['auid']."/1")?>"></iframe> 
    </div>
    <?php }?>
    
    <?php if($typedata ==11){?>   
    <div class="col-md-12 col-sm-12 col-xs-12" id="bd1" style="height:auto;">
    <iframe  class="iframe"  id="iframe" style="<?php if($result['responsive']==1){?> width:100%;<?php } else {?>height:<?php echo $height1;?>px; width:<?php echo $width1;?>px;<?php }?>" frameborder="0" src="<?php echo $this->make_url("adunit/preview_native/".$result['auid']."/3")?>"></iframe> 
    </div>
    <?php }?>

</div>




<div class="col-md-12 col-sm-12 col-xs-12">
<div class="col-md-12 col-sm-12 col-xs-12">
<h2 class="page_heading"><?php echo $this->get_label('ad display code');?></h2>
</div>
   
<div class="form-group col-md-12 col-sm-12 col-xs-12">

<div style="cursor: pointer;float: right;margin-bottom: 5px;"><i class="fa fa-clipboard fa-lg" aria-hidden="true" id="adcode" title="<?php echo $this->get_label('copy adcode');?>"></i></div>


<textarea id="textarea-adcode" style="border: 1px solid #CCCCCC;width: 100%;min-height: 120px;" readonly="readonly">
<!-- <?php  echo Configuration::get_instance()->read('admarket_name');?> - <?php echo $this->get_label('ad code');?> -->
<div id="data_<?php echo $aduid;?>"></div><script data-cfasync="false" type="text/javascript" async src="<?php echo '//'.$basepath.DISPLAY_DIR; ?>/items.php?<?php echo $aduid; ?>&<?php echo $uid; ?>&0&0&<?php echo $result['display_type'];?>&<?php echo $result['native'];?>"></script>
<!-- <?php  echo Configuration::get_instance()->read('admarket_name');?> - <?php echo $this->get_label('ad code');?> --></textarea>  
</div>    
</div>
<div class="col-md-12 col-sm-12 col-xs-12 adblock-edit">
<?php 
$form=$this->create_form();
$form->start("editadunit",$this->make_url("adunit/edit_native/".$aduid),"post",$validate);
$flag= $this->get_variable('flag');
$aduname=$this->get_variable('aduname');



if($_POST)
{
	$abrt=$this->get_variable("abrt");
	$sid=$this->get_variable("sid");
	
}
else 
{
	$abrt=$result['abr_type'];
	
	if($category_enabled ==1)
	$sid=$result['sid'];
	
}


?>


<div class="<?php if($pricing !=9){?>col-md-6 col-sm-6<?php }else{?>col-md-12 col-sm-12<?php }?> col-xs-12 box_style">
<div class="col-md-12 col-sm-12 col-xs-12 box_div" style="padding-top: 5px;">
<h2 class="page_heading"><?php echo $this->get_label('basic settings');?></h2>



<div class="form-group col-md-12 col-sm-12 col-xs-12">
<label class="col-md-6 col-sm-6 col-xs-12 " ><?php echo $this->get_label('header text');?><input type="hidden" name="aduid" id="aduid" value="<?php echo $aduid ?>" /></label>
<label class="col-md-6 col-sm-6 col-xs-12 " ><input class="form-control" type="text" name="aduname" id="aduname" value="<?php if($_POST) echo $aduname; else echo $result['auname'];?>" size="12" maxlength="25"/></label>
</div>


<div class="form-group col-md-12 col-sm-12 col-xs-12">
<label class="col-md-6 col-sm-6 col-xs-12 " ><?php echo $this->get_label('adunit type');?></label>
<label class="col-md-6 col-sm-6 col-xs-12 " >
<input type="hidden" name="adpricing" id="adpricing" value="<?php echo $result['display_type'];?>" /><?php echo $this->get_adunit_preference($result['display_type']);?>
</label>
</div>



<?php if($category_enabled ==1){?>
<div class="form-group col-md-12 col-sm-12 col-xs-12 site-class" style="display: none;">
<label class="col-md-6 col-sm-6 col-xs-12 " ><?php echo $this->get_label('targeting site');?> <span class="compulsory">*</span></label>
<label class="col-md-6 col-sm-6 col-xs-12 " ><?php echo CategoryHelper::get_site_dropdown($uid,$sid);?></label>
</div>
<?php }?>


<div class="form-group col-md-12 col-sm-12 col-xs-12 adb-tr">
<label class="col-md-6 col-sm-6 col-xs-12" ><?php echo $this->get_label('ad content type');?><span class="compulsory">*</span></label>
<label>
    <select class="form-control" name="adblocktype" id="adblocktype" onchange="change_adtype();" style="width: 150px;">
  <option value="-1"><?php echo $this->get_label('select');?></option>	 
    
    <?php if($text_ads_enabled ==1){?>
    <option value="1" <?php if($typedata ==1) {echo "selected";}?>><?php echo $this->get_label('text only');?></option>
    <?php }?>
    
	<?php if($textimage_enabled ==1){?>
	<option value="11" <?php if($typedata ==11) {echo "selected";}?>><?php echo $this->get_label('textimage');?></option>
	<?php }?>
    </select> 
</label>
</div>

<div class="form-group col-md-12 col-sm-12 col-xs-12" id="layout_select1" <?php if($typedata !=1){?>style="display: none;" <?php }?>>
<label class="col-md-6 col-sm-6 col-xs-12" ><?php echo $this->get_label('select ad layout');?><span class="compulsory">*</span></label>


<div class="col-md-6 col-sm-6 col-xs-12 radio-toolbar">
<?php 
$layout1=$this->get_result('layouttxt');

if(count($layout1)){ 
foreach($layout1 as $val)
{?>
 <input type="radio" name="layout" value="<?php echo $val['id'];?>" id="layout<?php echo $val['id'];?>" <?php if($layout==$val['id'])echo 'checked';?>> <label for="layout<?php echo $val['id'];?>" ><span class="ui-button-text"><?php echo $val['columns'].'x'.$val['rows'];?></span></label>
 <?php }}else echo $this->get_label('no records found');?>
 
<?php if(count($layout1) >0){?> 
<br/>
<span class="notification"><?php echo $this->get_label('click one of the above button');?></span>
<?php }?>
</div>
</div>

<div class="form-group col-md-12 col-sm-12 col-xs-12 " id="layout_select2" <?php if($typedata !=11){?>style="display: none;" <?php }?>>
<label class="col-md-6 col-sm-6 col-xs-12" ><?php echo $this->get_label('select ad layout');?></label>


<div class="col-md-6 col-sm-6 col-xs-12 radio-toolbar">
<?php 
$layout2=$this->get_result('layoutimg');

if(count($layout2))
{ 
foreach($layout2 as $key=> $val)
{?>
 <input type="radio" name="layout" value="<?php echo $val['id'];?>" id="layout<?php echo $val['id'];?>" <?php if($layout==$val['id'])echo 'checked';?>> <label for="layout<?php echo $val['id'];?>" ><span class="ui-button-text"><?php echo $val['columns'].'x'.$val['rows'];?></span></label>
 <?php }} else echo $this->get_label('no records found');?>
 
<?php if(count($layout2) >0){?> 
<br/>
<span class="notification"><?php echo $this->get_label('click one of the above button');?></span>
<?php }?>

</div>
</div>

<div class="form-group col-md-12 col-sm-12 col-xs-12 " id="img_dim" <?php if($typedata !=11){?> style="display: none;" <?php }?>>
<label class="col-md-6 col-sm-6 col-xs-12" ><?php echo $this->get_label('prefered image dimension');?>(in px)</label>
<div class="col-md-6 col-sm-6 col-xs-12"><select name="img_dim" id="img_dim" class="form-control" style="width: 130px;">
<?php 
$res1=$this->get_result('res_dim');

foreach($res1 as $key=>$result1)
{
	$height=$result1['height'];
	$width=$result1['width'];
	$id=$result1['id'];
	$diamensions=$result1['width']." x ".$result1['height'];
?>
<option value="<?php echo $id;?>" <?php if($result['nativeimg_dimension']==$id) { echo "selected"; }?>><?php echo $diamensions ?></option>
<?php 
}?>
</select><div class="col-md-6 col-sm-3 col-xs-12"></div>
</div>
</div>
<div class="form-group col-md-12 col-sm-12 col-xs-12 " id="img_pos" <?php if($typedata !=11){?> style="display: none;" <?php }?>>
<label class="col-md-6 col-sm-6 col-xs-12" ><?php echo $this->get_label('image position');?></label>
<div class="col-md-6 col-sm-6 col-xs-12"><select name="img_pos" id="img_pos" class="form-control" style="width: 130px;">

<option value="0" <?php if($result['nativeimg_position']==0) { echo "selected"; }?>><?php echo $this->get_label('left');?></option>
<option value="1" <?php if($result['nativeimg_position']==1) { echo "selected"; }?>><?php echo $this->get_label('top');?></option>
</select><div class="col-md-6 col-sm-3 col-xs-12"></div>
</div>
</div>


<div class="form-group col-md-12 col-sm-12 col-xs-12 native" >
<label class="col-md-12 col-sm-12 col-xs-12" ><?php echo $this->get_label('custom css and js');?></label>
<label class="col-md-12 col-sm-12 col-xs-12"><textarea  style="height:155px !important; width:100%"; class="form-control"  name="customcode" id="customcode" ><?php echo $customcode;?></textarea>
</label>
</div>

<div class="form-group col-md-12 col-sm-12 col-xs-12 native" id="responsivediv" >
<label class="col-md-6 col-sm-6 col-xs-12" for="responsive"><?php echo $this->get_label('make ad responsive');?></label><label class="col-md-6 col-sm-6 col-xs-12"><input type="checkbox" name="responsive" id="responsive" value="1" <?php if($result['responsive']==1)echo 'checked';?>></label>
</div>


<?php 
if(Configuration::get_instance()->read('allow_brtype')=="1")
 {
 ?>
<div class="form-group col-md-12 col-sm-12 col-xs-12">  
<label class="col-md-6 col-sm-6 col-xs-12 " ><?php echo $this->get_label('border type');?></label>
<label class="col-md-6 col-sm-6 col-xs-12 " >
    <input type="radio" name="border" id="border" value="1" <?php if($abrt==1){echo "checked";}?>/><?php echo $this->get_label('regular');?>
    <input type="radio" name="border" id="border" value="0" <?php if($abrt==0){echo "checked";}?>/><?php echo $this->get_label('rounded');?>
        <input type="radio" name="border" id="border" value="2" <?php if($abrt==2){echo "checked";}?>/><?php echo $this->get_label('No Border');?>
    
</label>
</div>
   <?php 
 	}
   else 
   {
   ?>
<div class="form-group col-md-12 col-sm-12 col-xs-12">   
<label class="col-md-6 col-sm-6 col-xs-12 " ><?php echo $this->get_label('border type');?></label>
<label class="col-md-6 col-sm-6 col-xs-12 " >
    <?php 
    if($abrt==1)
    echo $this->get_label('regular');
    else  if($abrt==0)
    echo $this->get_label('rounded');
     else  if($abrt==2)
    echo $this->get_label('No Boarder');
    ?>
</label>
<input type="hidden"  name="border" id="border" value="<?php echo $abrt;?>" >
</div>
   <?php }?>

  
   
</div>
</div>




<div class="col-md-6 col-sm-6 col-xs-12 box_style">
<div class="col-md-12 col-sm-12 col-xs-12 box_div" style="padding-top: 5px;">
<h2 class="page_heading"><?php echo $this->get_label('color settings');?></h2> 
 

   
  
 

   <?php 
 if( $colorpicker==1)
{  
	?>
	<label class="col-md-12 col-sm-12 col-xs-12 form-group" ><div id="picker" style="float: left;"></div></label>
	
	<?php 
}
 ?>
   
   
   
   
<?php 
   if(Configuration::get_instance()->read('allow_tcolor')=="1")
   {?>   
<div class="col-md-12 col-sm-12 col-xs-12" >  
<div class="col-md-6 col-sm-6 col-xs-12 form-group" ><?php echo $this->get_label('ad title color');?></div>
<div class="col-md-6 col-sm-6 col-xs-12 form-group" ><input name="color1" type="text" id="color1" class="color-picker"  style="background-color:<?php echo $result['at_color'];?>" value="<?php echo $result['at_color'];?>"  ></div>
</div> 
 <?php } else {?>
 <div class="col-md-12 col-sm-12 col-xs-12" >  
 <div class="col-md-6 col-sm-6 col-xs-12 form-group" ><?php echo $this->get_label('ad title color');?></div>
 <div class="col-md-6 col-sm-6 col-xs-12 form-group" ><input name="color1" type="text" id="color1" class="color-picker"  style="background-color:<?php echo $result['at_color'];?>" value="<?php echo $result['at_color'];?>"  disabled></div>
 </div> 
 <?php } 
  if(Configuration::get_instance()->read('allow_dcolor')=="1")
   {
  		?>
 
 
<div class="col-md-12 col-sm-12 col-xs-12" > 
<div class="col-md-6 col-sm-6 col-xs-12 form-group" ><?php echo $this->get_label('ad description color');?></div>
<div class="col-md-6 col-sm-6 col-xs-12 form-group" ><input  type="text" id="color2" name="color2" class="color-picker"  value="<?php echo $result['ad_color'];?>"  style="background-color:<?php echo $result['ad_color'];?>"></div>
 </div> 
 <?php }


else {?>
 
<div class="col-md-12 col-sm-12 col-xs-12" > 
<div class="col-md-6 col-sm-6 col-xs-12 form-group" ><?php echo $this->get_label('ad description color');?></div>
<div class="col-md-6 col-sm-6 col-xs-12 form-group" ><input  type="text" id="color2" name="color2" class="color-picker"  value="<?php echo $result['ad_color'];?>"  style="background-color:<?php echo $result['ad_color'];?>" disabled></div>
 </div> 
 <?php }
 
  if(Configuration::get_instance()->read('allow_ucolor')=="1")
  {?>
 <div class="col-md-12 col-sm-12 col-xs-12" > 
<div class="col-md-6 col-sm-6 col-xs-12 form-group" ><?php echo $this->get_label('ad display url color');?></div>
<div class="col-md-6 col-sm-6 col-xs-12 form-group" ><input  type="text" id="color3" name="color3" class="color-picker"  value="<?php echo $result['au_color'];?>" style="background-color:<?php echo $result['au_color'];?>"></div>
</div>
 
<?php } 
else {?>
<div class="col-md-12 col-sm-12 col-xs-12" >
<div class="col-md-6 col-sm-6 col-xs-12 form-group" ><?php echo $this->get_label('ad display url color');?></div>
<div class="col-md-6 col-sm-6 col-xs-12 form-group" ><input  type="text" id="color3" name="color3" class="color-picker"  value="<?php echo $result['au_color'];?>" style="background-color:<?php echo $result['au_color'];?>" disabled></div>
</div>
 
<?php } if(Configuration::get_instance()->read('allow_bcolor')=="1")
{?>
<div class="col-md-12 col-sm-12 col-xs-12" >
<div class="col-md-6 col-sm-6 col-xs-12 form-group" ><?php echo $this->get_label('background color');?></div>
<div class="col-md-6 col-sm-6 col-xs-12 form-group" ><input type="text" id="color4" name="color4" class="color-picker" value="<?php echo $result['ab_color'];?>" style="background-color:<?php echo $result['ab_color'];?>"></div>
</div>    
  <?php }
  
  else {
  ?>
  <div class="col-md-12 col-sm-12 col-xs-12" >
  <div class="col-md-6 col-sm-6 col-xs-12 form-group" ><?php echo $this->get_label('background color');?></div>
  <div class="col-md-6 col-sm-6 col-xs-12 form-group" ><input type="text" id="color4" name="color4" class="color-picker" value="<?php echo $result['ab_color'];?>" style="background-color:<?php echo $result['ab_color'];?>" disabled></div>
</div>  
<?php } 
    if(Configuration::get_instance()->read('allow_ccolor')=="1")
{
?>
<div class="col-md-12 col-sm-12 col-xs-12" >
<div class="col-md-6 col-sm-6 col-xs-12 form-group" ><?php echo $this->get_label('credit color');?></div>
<div class="col-md-6 col-sm-6 col-xs-12 form-group" ><input type="text" id="color5" name="color5" class="color-picker" value="<?php echo $result['ac_color'];?>" style="background-color:<?php echo $result['ac_color'];?>"></div>
</div>
 <?php }
 else {?>
 <div class="col-md-12 col-sm-12 col-xs-12" >
 <div class="col-md-6 col-sm-6 col-xs-12 form-group" ><?php echo $this->get_label('credit color');?></div>
 <div class="col-md-6 col-sm-6 col-xs-12 form-group" ><input type="text" id="color5" name="color5" class="color-picker" value="<?php echo $result['ac_color'];?>" style="background-color:<?php echo $result['ac_color'];?>" disabled></div>
</div>
 <?php }
 if(Configuration::get_instance()->read('allow_brcolor')=="1")
 {
 	?>
 	<div class="col-md-12 col-sm-12 col-xs-12" >
 	<div class="col-md-6 col-sm-6 col-xs-12 form-group" ><?php echo $this->get_label('border color');?></div>
	<div class="col-md-6 col-sm-6 col-xs-12 form-group" ><input type="text" id="color8" name="color8" class="color-picker" value="<?php echo $result['abr_color'];?>" style="background-color:<?php echo $result['abr_color'];?>"></div>
 </div>	
 	<?php 
 }
 else 
 {
 	?>
 	<div class="col-md-12 col-sm-12 col-xs-12" >
 	<div class="col-md-6 col-sm-6 col-xs-12 form-group" ><?php echo $this->get_label('border color');?></div>
	<div class="col-md-6 col-sm-6 col-xs-12 form-group" ><input type="text" id="color8" name="color8" class="color-picker" value="<?php echo $result['abr_color'];?>" style="background-color:<?php echo $result['abr_color'];?>" disabled></div>
</div> 	
 	<?php 
 }
 if(Configuration::get_instance()->read('allow_htcolor')=="1")
 {
 ?>
 
 <div class="col-md-12 col-sm-12 col-xs-12" >
<div class="col-md-6 col-sm-6 col-xs-12 form-group" ><?php echo $this->get_label('header text color');?></div>
<div class="col-md-6 col-sm-6 col-xs-12 form-group" ><input type="text" id="color6" name="color6" class="color-picker" value="<?php echo $result['htxt_color'];?>" style="background-color:<?php echo $result['htxt_color'];?>"></div>
</div>
  <?php }
  else {?>  
  <div class="col-md-12 col-sm-12 col-xs-12" >
<div class="col-md-6 col-sm-6 col-xs-12 form-group" ><?php echo $this->get_label('header text color');?></div>
<div class="col-md-6 col-sm-6 col-xs-12 form-group" ><input type="text" id="color6" name="color6" class="color-picker" value="<?php echo $result['htxt_color'];?>" style="background-color:<?php echo $result['htxt_color'];?>" disabled></div>
</div>  
  <?php }
  if(Configuration::get_instance()->read('allow_htbgcolor')=="1")
 {
  ?>
  <div class="col-md-12 col-sm-12 col-xs-12" >
<div class="col-md-6 col-sm-6 col-xs-12 form-group" ><?php echo $this->get_label('header text background color');?></div>
<div class="col-md-6 col-sm-6 col-xs-12 form-group" ><input type="text" id="color7" name="color7" class="color-picker" value="<?php echo $result['htxt_bgcolor'];?>" style="background-color:<?php echo $result['htxt_bgcolor'];?>"></div>
 </div>   
    
  <?php }
  else 
  {?>
  <div class="col-md-12 col-sm-12 col-xs-12" >
  <div class="col-md-6 col-sm-6 col-xs-12 form-group" ><?php echo $this->get_label('header text background color');?></div>
  <div class="col-md-6 col-sm-6 col-xs-12 form-group" ><input type="text" id="color7" name="color7" class="color-picker" value="<?php echo $result['htxt_bgcolor'];?>" style="background-color:<?php echo $result['htxt_bgcolor'];?>" disabled></div>
 </div>
  <?php }?>
</div>
</div>
  
  
  
 
 
 
 
<div class="col-md-12 col-sm-12 col-xs-12" style="text-align: center;">
<input class="btn btn-primary btn-lg" type="submit" name="submit" value="<?php echo $this->get_label('update adunit');?>" />
</div>
   

<?php $form->end(); ?>

</div>

</div></div></div>

</div>
<script type="text/javascript">
$(document).ready(function() {

	
	$("#adpricing").change(function()
	{
		<?php if($category_enabled ==1){?>
		LoadSiteData();
		<?php }?>
	});

	<?php if($category_enabled ==1){?>
	LoadSiteData();
	<?php }?>
});

<?php if($category_enabled ==1){?>
function LoadSiteData()
{
	$(".site-class").hide();
	var selected=$("#adpricing").val();
	var allowed='<?php echo Configuration::get_instance()->read('category_enabled_ads');?>';
	if(allowed !='')
	{
		allowed_array=allowed.split('_');
	
		if($.inArray(selected , allowed_array) >-1)
		$(".site-class").show();
		else
		$("#sid").val(0);	
	}
}
<?php }?>




function change_adtype()
{
	
	type=$('#type1').val();
	pricing=$("#adpricing").val();
	adblocktype=$('#adblocktype').val();
	adpricing=$("#adpricing").val();

	
	 if(adblocktype==11 )
    {
    	$("#layout_select2").show();
    	$("#layout_select1").hide();
    	$("#img_dim").show();
    	$("#img_pos").show();
    }
    else if(adblocktype==1 )
    {
    	$("#layout_select1").show();
    	$("#layout_select2").hide();
    	$("#img_dim").hide();
    	$("#img_pos").hide();
    	
    }
	
	
}

$("#adcode").click(function(){
	   $("#textarea-adcode").select();
	   document.execCommand('copy');
	});

</script>
<?php $this->dispatch("layout/footer");?>