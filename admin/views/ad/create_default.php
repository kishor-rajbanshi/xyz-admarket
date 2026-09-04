<script type="text/javascript">
function changeadtype()
{
	if($('#type').val() ==1)
	{
		$('#textad').show();
		$('#bannerad').hide();
		$('#textplusimage').hide();

		if($('#popad').length >0)
		$('#popad').hide();

		if($('#directlink-ad').length >0)
		$('#directlink-ad').hide();
		
		if($('#videoad').length >0)
		$('#videoad').hide();	

		if($('#skinad').length >0)
		$('#skinad').hide();
		if($('#pushad').length >0)
		$('#pushad').hide();	

		AdPreview(0);			
	}
	else if($('#type').val() ==2)
	{	
		$('.sizedropdown').hide();
		
		$('#textplusimage').hide();
		$('#textad').hide();
		$('#bannerad').show();

		if($('#popad').length >0)
		$('#popad').hide();
		
		if($('#directlink-ad').length >0)
		$('#directlink-ad').hide();

		if($('#videoad').length >0)
		$('#videoad').hide();			

		$('#banner_type').val($('#type').val());

		$('#bannersize').show();

		if($('#skinad').length >0)
		$('#skinad').hide();	
		if($('#pushad').length >0)
		$('#pushad').hide();
		$('.previewSection').hide();		
	}
	else if($('#type').val() ==9)
	{	
		$('#textad').hide();
		$('#bannerad').hide();

		if($('#videoad').length >0)
		$('#videoad').hide();			

		$('#popad').show();
		
		if($('#directlink-ad').length >0)
		$('#directlink-ad').hide();

		$('#textplusimage').hide();

		if($('#skinad').length >0)
		$('#skinad').hide();

		if($('#pushad').length >0)
		$('#pushad').hide();
		$('.previewSection').hide();
	}
	else if($('#type').val() ==21)
	{
		$('#textad').hide();
		$('#bannerad').hide();

		if($('#videoad').length >0)
		$('#videoad').hide();

		$('#directlink-ad').show();

		if($('#popad').length >0)
		$('#popad').hide();
		$('#textplusimage').hide();

		if($('#skinad').length >0)
		$('#skinad').hide();		
		
		if($('#pushad').length >0)
		$('#pushad').hide();
		$('.previewSection').hide();	
	}
	else if($('#type').val() ==11)
	{	
		$('#textplusimage').show();
		$('#textad').hide();
		$('#bannerad').hide();

		if($('#popad').length >0)
		$('#popad').hide();
		
		if($('#directlink-ad').length >0)
		$('#directlink-ad').hide();

		if($('#videoad').length >0)
		$('#videoad').hide();	

		if($('#skinad').length >0)
		$('#skinad').hide();
		
		if($('#pushad').length >0)
		$('#pushad').hide();	

		AdPreview(0);		
	}
	else if($('#type').val() ==13)
	{	
		$('#textplusimage').hide();
		$('#textad').hide();
		$('#bannerad').hide();

		if($('#popad').length >0)
		$('#popad').hide();
		
		if($('#directlink-ad').length >0)
		$('#directlink-ad').hide();

		if($('#videoad').length >0)
		$('#videoad').show();	

		if($('#skinad').length >0)
		$('#skinad').hide();
		
		if($('#pushad').length >0)
		$('#pushad').hide();	

		$('.previewSection').hide();	
	}
	else if($('#type').val() ==14)
	{	
		$('#textplusimage').hide();
		$('#textad').hide();
		$('#bannerad').hide();

		if($('#popad').length >0)
		$('#popad').hide();
		
		if($('#directlink-ad').length >0)
		$('#directlink-ad').hide();

		if($('#videoad').length >0)
		$('#videoad').hide();	

		if($('#skinad').length >0)
		$('#skinad').show();	

		$('.previewSection').hide();

		skinsize=$('#bannersize_14').val();

		$(".skin_prev").hide();
		$('#skin_prev'+skinsize).show();
		if($('#pushad').length >0)
		$('#pushad').hide();	
	}
		else if($('#type').val() ==18){ 
		$('#textad').hide();
		$('#bannerad').hide();
		$('#textplusimage').hide();
		
		if($('#popad').length >0)
		$('#popad').hide();
		
		if($('#directlink-ad').length >0)
		$('#directlink-ad').hide();
		
		if($('#videoad').length >0)
		$('#videoad').hide();
		if($('#skinad').length >0)
		$('#skinad').hide();
		if($('#pushad').length >0)
		$('#pushad').show();
		
		}	
}


function AdPreview(section)
{
    titleEnabled               = 0;
    descriptionEnabled         = 0;
    urlEnabled                 = 0;
    textimageSize 			   = 0;
    titleText                  = "";
    descriptionText            = "";
    displayurlText             = "";
	buttonText 				   = "";
	adType 					   = $('#type').val();

	if(adType == 1)
	formString = '#create_default1';
	else if(adType == 11)
	formString = '#create_default11';
	

	if(adType == 11)
	textimageSize              = $(formString+' #bannersize').val();	

    if($(formString+' #title').val() != "")
    {
    	titleEnabled           = 1; 
    	titleText              = $(formString+' #title').val();
    }


    if($(formString+' #desc').val() != "")
    {
    	descriptionEnabled     = 1;
    	descriptionText        = $(formString+' #desc').val();
    }

    if($(formString+' #displayurl').val() != "")
    {
    	urlEnabled             = 1;
    	displayurlText         = $(formString+' #displayurl').val();
    }

    if($(formString+' #cta_button_text').val() != "")
    buttonText             = $(formString+' #cta_button_text').val();    

    if((titleEnabled == 0 && descriptionEnabled == 0 && urlEnabled == 0) || (adType != 1 && adType != 11))
    {
        $('.previewSection').html("");
        return;
    }


    if(section == 1 && $('.ad-layout-title').length > 0 && titleText != "")
    {
    	$('.ad-layout-title a p').html(titleText);
    	return;
    }	

    if(section == 2 && $('.ad-layout-description').length > 0 && descriptionText != "")
    {
    	$('.ad-layout-description a p').html(descriptionText);
    	return;
    }

    if(section == 3 && $('.ad-layout-displayurl').length > 0 && displayurlText != "")
    {
    	$('.ad-layout-displayurl a p').html(displayurlText);
    	return;
    }


    $("#loading").show();

    dataparam    = "adType="+adType+"&titleEnabled="+titleEnabled+"&descriptionEnabled="+descriptionEnabled+"&urlEnabled="+urlEnabled+"&titleText="+titleText+"&descriptionText="+descriptionText+"&displayurlText="+displayurlText+"&buttonText="+buttonText+
        "&textimageSize="+textimageSize;

    var urlvalue = '<?php echo $this->make_url("ad/load_ad_preview");?>';

    $.ajax(
    {
        type: "POST",
        data: dataparam,
        url: urlvalue,
        success: function(message)
        {                       
            $("#loading").hide();
            $('.previewSection').show();
            $('.previewSection').html(message);
        }
    });
}


</script>
<?php
$this->dispatch("layout/header/1/_12");
$textimage_enabled=$this->get_variable('textimage_enabled');
$pop_enabled=$this->get_variable('pop_enabled');
$directlink_enabled=$this->get_variable('directlink_enabled');
$cpv_enabled=$this->get_variable('cpv_enabled');
$skin_enabled=$this->get_variable('skin_enabled');
$text_ads_enabled=Configuration::get_instance()->read('text-ads_enabled');
$cpp_enabled=$this->get_addon_status('cpp_enabled');
$bannersize=intval($this->get_variable("bannersize"));

$resFontExternal        = $this->get_result("resFontExternal");


$validate1=array(
		"name"=>array(
				"notNull"=>array($this->get_message("not null"))

		),
		"title"=>array(
				"notNull"=>array($this->get_message("not null"))
		
		),
		"displayurl"=>array(
				"notNull"=>array($this->get_message("not null"))

		),
		"clickurl"=>array(
				"notNull"=>array($this->get_message("not null"))

		),
		"desc"=>array(
				"notNull"=>array($this->get_message("not null"))
		
		)	
		
);

$validate=array(
		"name"=>array(
				"notNull"=>array($this->get_message("not null"))

		),
		"title"=>array(
				"notNull"=>array($this->get_message("not null"))

		),
		
		"clickurl"=>array(
				"notNull"=>array($this->get_message("not null"))

		),
		"desc"=>array(
				"notNull"=>array($this->get_message("not null"))

		),
		"notify_icon_image"=>array(
				"notNull"=>array($this->get_message("not null"))
		
		)

);

$validate2=array(
		"name"=>array(
				"notNull"=>array($this->get_message("not null"))

		),
		"clickurl"=>array(
				"notNull"=>array($this->get_message("not null"))

		),
		"banner"=>array(
				"notNull"=>array($this->get_message("not null"))
		
		)	
		
);


$validate9=array(
		"name"=>array(
				"notNull"=>array($this->get_message("not null"))
		),
		"clickurl"=>array(
				"notNull"=>array($this->get_message("not null"))
		)
);

$validate21=array(
		"name"=>array(
				"notNull"=>array($this->get_message("not null"))
		),
		"clickurl"=>array(
				"notNull"=>array($this->get_message("not null"))
		)
);

$validate13=array(
		"name"=>array(
				"notNull"=>array($this->get_message("not null"))

		),
		"displayurl"=>array(
				"notNull"=>array($this->get_message("not null"))

		),
		"clickurl"=>array(
				"notNull"=>array($this->get_message("not null"))

		),
		"videofile"=>array(
				"notNull"=>array($this->get_message("not null"))
		)	
);


if($skin_enabled ==1)
{
	$validate14 = array(
			"name"=>array(
					"notNull"=>array($this->get_message("not null"))
			),
			"clickurl"=>array(
					"notNull"=>array($this->get_message("not null"))
			)
	);	
	
	$res14_banner=$this->get_result('res14_banner');
	
	$skin_array=array();
	foreach($res14_banner as $key=>$result)
	{
		$skin_array['skin_banner_'.$result['id']] =array("notNull"=>array($this->get_message("not null")));
	}
	
	$validate14 = array_merge($validate14,$skin_array);
}



$validate11=array(
		"name"=>array(
				"notNull"=>array($this->get_message("not null"))

		),
		"title"=>array(
				"notNull"=>array($this->get_message("not null"))
		
		),
		"displayurl"=>array(
				"notNull"=>array($this->get_message("not null"))

		),
		"clickurl"=>array(
				"notNull"=>array($this->get_message("not null"))

		),
		"desc"=>array(
				"notNull"=>array($this->get_message("not null"))
		
		),
		"banner"=>array(
				"notNull"=>array($this->get_message("not null"))
		
		)		
		
);



?>
<style type="text/css">
<?php foreach($resFontExternal as $fkey=>$fvalue){?>
@font-face {
  font-family: <?php echo $fvalue['slug_name'];?>;
  src: url(<?php echo BASE.DATA_DIR.'/fonts/'.$fvalue['file_name'];?>);
}
<?php } ?>
</style>

<div class="sub_menu_main"><?php echo $this->get_label('create default ad');?></div>

<?php 
$this->dispatch("links/links/16");


$maxTitleLength       = Configuration::get_instance()->read('max_ad_title_length');
$maxDescriptionLength = Configuration::get_instance()->read('max_ad_desc_length');
$maxDisplayurlLength  = Configuration::get_instance()->read('max_display_url_length');
$ctaButtonLength 	  = Configuration::get_instance()->read('cta_button_text_length');

?>

<div class="inner-box">
<div class="pages-input">


<table style="width: 100%;" cellpadding="0" cellspacing="0">
<tr><td></td><td ><?php echo $this->get_label('compulsory message');?></td></tr>

<tr>
<td style="width: 150px;"><?php echo $this->get_label('adtype');?></td>
<td>
<select name="type" id="type" onchange="javascript:return changeadtype();" style="width: 166px;">
<?php if($text_ads_enabled ==1){?>
<option value="1" <?php if($this->get_variable('type') ==1){ echo "selected"; } ?>><?php echo $this->get_label('textad');?></option>
<?php }?>

<option value="2" <?php if($this->get_variable('type') ==2){ echo "selected"; } ?>><?php echo $this->get_label('bannerad');?></option>

<?php if($textimage_enabled ==1){?>
<option value="11" <?php if($this->get_variable('type') ==11){ echo "selected"; } ?>><?php echo $this->get_label('textimage ad');?></option>
<?php }?>

<?php if($pop_enabled ==1){?>
<option value="9" <?php if($this->get_variable('type') ==9){ echo "selected"; } ?>><?php echo $this->get_label('popad');?></option>
<?php }?>

<?php if($directlink_enabled ==1){?>
<option value="21" <?php if($this->get_variable('type') ==21){ echo "selected"; } ?>><?php echo $this->get_label('directlink ad');?></option>
<?php }?>

<?php if($cpv_enabled ==1){?>
<option value="13" <?php if($this->get_variable('type') ==13){ echo "selected"; } ?>><?php echo $this->get_label('video ad');?></option>
<?php }?>

<?php if($skin_enabled ==1){?>
<option value="14" <?php if($this->get_variable('type') ==14){ echo "selected"; } ?>><?php echo $this->get_label('skin ad');?></option>
<?php }?>

<?php if($cpp_enabled ==1){?>
<option value="18" <?php if($this->get_variable('type') ==18){ echo "selected"; } ?>><?php echo $this->get_label('push notification ad');?></option>
<?php }?>

</select>
</td>

<td rowspan="2" style="vertical-align: top;">
	
<span><img id="loading" src="images/load.gif" style="display: none;" /></span>
<div class="previewSection" style="display: none;margin-top: 0px;"></div>

</td>
</tr>

<tr id="textad"><td colspan="2">
<?php 
$form1=$this->create_form();
$form1->start("create_default1",$this->make_url("ad/create_default"),"post",$validate1);

?>



<table style="width: 100%;" cellpadding="0" cellspacing="0">
<tr>
<td style="width: 150px;"><?php echo $this->get_label('name');?></td>
<td><input type="text" name="name" value="<?php echo $this->get_variable('name');?>"><span class="compulsory">*</span></td>
</tr>

<tr>
<td ><?php echo $this->get_label('adtitle');?></td>
<td><input type="text" name="title" id="title" value="<?php echo $this->get_variable('title');?>" maxlength="<?php echo $maxTitleLength;?>" onkeyup="AdPreview(1);" /><span class="compulsory">*</span><div class="notification">[<?php echo $this->get_label('max character',array('x'=>$maxTitleLength));?>]</div></td>
</tr>


<tr>
<td><?php echo $this->get_label('description');?></td>
<td><input type="text" name="desc" id="desc" value="<?php echo $this->get_variable('desc');?>" maxlength="<?php echo $maxDescriptionLength;?>" onkeyup="AdPreview(2);" /><span class="compulsory">*</span><div class="notification">[<?php echo $this->get_label('max character',array('x'=>$maxDescriptionLength));?>]</div></td>
</tr>


<tr>
<td><?php echo $this->get_label('displayurl');?></td>
<td><input type="text" name="displayurl" id="displayurl" value="<?php echo $this->get_variable('displayurl');?>" maxlength="<?php echo $maxDisplayurlLength;?>" onkeyup="AdPreview(3);" /><span class="compulsory">*</span><div class="notification">[<?php echo $this->get_label('max character',array('x'=>$maxDisplayurlLength));?>]</div></td>
</tr>


<tr>
<td><?php echo $this->get_label('cta button text');?></td>
<td><input type="text" name="cta_button_text" id="cta_button_text" value="<?php echo $this->get_variable('cta_button_text');?>" maxlength="<?php echo $ctaButtonLength;?>" onkeyup="AdPreview(0);" /><div class="notification">[<?php echo $this->get_label('max character',array('x'=>$ctaButtonLength));?>]</div></td>
</tr>

<tr>
<td><?php echo $this->get_label('clickurl');?></td>
<td><input type="text" name="clickurl" value="<?php echo $this->get_variable('clickurl');?>"><span class="compulsory">*</span><div class="notification">[<?php echo $this->get_label('example url');?>]</div></td>
</tr>

<tr>
<td></td>
<td>
<input type="hidden" name="type1" id="type1" value="1" />
<input type="submit" name="submit1" value="<?php echo $this->get_label('create ad');?>"></td>
</tr>


</table>

<?php $form1->end(); ?>
</td></tr>


<tr id="pushad" style="display:none;"><td colspan="2">
<?php 
$form6=$this->create_form();
$form6->start("create_default6",$this->make_url("ad/create_default"),"post",$validate);

?>



<table style="width: 100%;" cellpadding="0" cellspacing="0">
<tr>
<td style="width: 150px;"><?php echo $this->get_label('name');?></td>
<td><input type="text" name="name" value="<?php echo $this->get_variable('name');?>"><span class="compulsory">*</span></td>
</tr>

<tr>
<td ><?php echo $this->get_label('adtitle');?></td>
<td><input type="text" name="title" value="<?php echo $this->get_variable('title');?>" maxlength="<?php echo Configuration::get_instance()->read('max_ad_title_length');?>"><span class="compulsory">*</span><div class="notification">[<?php echo $this->get_label('max character',array('x'=>Configuration::get_instance()->read('max_ad_title_length')));?>]</div></td>
</tr>


<tr>
<td><?php echo $this->get_label('description');?></td>
<td><input type="text" name="desc" value="<?php echo $this->get_variable('desc');?>" maxlength="<?php echo Configuration::get_instance()->read('max_ad_desc_length');?>"><span class="compulsory">*</span><div class="notification">[<?php echo $this->get_label('max character',array('x'=>Configuration::get_instance()->read('max_ad_desc_length')));?>]</div></td>
</tr>

<tr>
<td><?php echo $this->get_label('clickurl');?></td>
<td><input type="text" name="clickurl" value="<?php echo $this->get_variable('clickurl');?>"><span class="compulsory">*</span><div class="notification">[<?php echo $this->get_label('example url');?>]</div></td>
</tr>

<tr>
<td><?php echo $this->get_label('icon image');?></td>
<td><input type="file" name="notify_icon_image"><span class="compulsory">*</span><div class="notification">[<?php echo $this->get_label('supported image format');?>,&nbsp;<?php echo $this->get_label('preferred icon image size is', array("x"=>Configuration::get_instance()->read('push_notification_icon_width')." x ".Configuration::get_instance()->read('push_notification_icon_height')));?>]</div></td>
</tr>

<tr>
<td></td>
<td>
<input type="hidden" name="type1" id="type1" value="18" />
<input type="submit" name="submit" value="<?php echo $this->get_label('create ad');?>"></td>
</tr>


</table>

<?php $form6->end(); ?>
</td></tr>


<tr id="bannerad" style="display:none;"><td colspan="2">
<?php 
$form2=$this->create_form();
$form2->start("create_default2",$this->make_url("ad/create_default"),"post",$validate2); 

$res1=$this->get_result('res1');
?>
<table style="width: 100%;" cellpadding="0" cellspacing="0">


<tr>
<td style="width: 150px;"><?php echo $this->get_label('name');?></td>
<td><input type="text" name="name" value="<?php echo $this->get_variable('name');?>"><span class="compulsory">*</span></td>
</tr>


<tr>
<td ><?php echo $this->get_label('banner size');?></td>
<td>
<select class="sizedropdown" name="bannersize" id="bannersize" style="display: none;width: 166px;">
<?php foreach($res1 as $key=>$result)
{
	$height=$result['height'];
	$width=$result['width'];
	$filesize=$result['filesize'];
	$id=$result['id'];
	$diamensions=$result['width']." x ".$result['height'];
	?>
	<option value="<?php echo $id;?>" <?php if($this->get_variable('bannersize') ==$id) { echo "selected"; } ?> ><?php echo $diamensions ?></option>
<?php 
}
?>
</select>
</td>
</tr>

<tr>
<td><?php echo $this->get_label('banner');?></td>
<td><input type="file" name="banner" size="10"><span class="compulsory">*</span><br/>

<span class="notification">[<?php echo $this->get_label('supported image format');?>]</span>
</td>
</tr>

<tr>
<td><?php echo $this->get_label('clickurl');?></td>
<td><input type="text" name="clickurl" value="<?php echo $this->get_variable('clickurl');?>"><span class="compulsory">*</span><div class="notification">[<?php echo $this->get_label('example url');?>]</div></td>
</tr>

<tr>
<td></td>
<td>
<input type="hidden" name="type1" id="type1" value="2" />
<input type="hidden" name="banner_type" id="banner_type" value="2" />
<input type="submit" name="submit2" value="<?php echo $this->get_label('create ad');?>"></td>
</tr>

</table>

<?php $form2->end(); ?>
</td>
</tr>

<tr id="textplusimage" style="display:none;"><td colspan="2">
<?php 
$form11=$this->create_form();
$form11->start("create_default11",$this->make_url("ad/create_default"),"post",$validate11); 
$res4=$this->get_result('res4');

?>
<table style="width: 100%;" cellpadding="0" cellspacing="0">
<tr>
<td style="width: 150px;"><?php echo $this->get_label('name');?></td>
<td><input type="text" name="name" value="<?php echo $this->get_variable('name');?>"><span class="compulsory">*</span></td>
</tr>


<tr>
<td ><?php echo $this->get_label('adtitle');?></td>
<td><input type="text" name="title" id="title" value="<?php echo $this->get_variable('title');?>" maxlength="<?php echo $maxTitleLength;?>" onkeyup="AdPreview(1);" /><span class="compulsory">*</span><div class="notification">[<?php echo $this->get_label('max character',array('x'=>$maxTitleLength));?>]</div></td>
</tr>

<tr>
<td><?php echo $this->get_label('description');?></td>
<td><input type="text" name="desc" id="desc" value="<?php echo $this->get_variable('desc');?>" maxlength="<?php echo $maxDescriptionLength;?>" onkeyup="AdPreview(2);" /><span class="compulsory">*</span><div class="notification">[<?php echo $this->get_label('max character',array('x'=>$maxDescriptionLength));?>]</div></td>
</tr>

<tr>
<td><?php echo $this->get_label('displayurl');?></td>
<td><input type="text" name="displayurl" id="displayurl" value="<?php echo $this->get_variable('displayurl');?>" maxlength="<?php echo $maxDisplayurlLength;?>" onkeyup="AdPreview(3);" /><span class="compulsory">*</span><div class="notification">[<?php echo $this->get_label('max character',array('x'=>$maxDisplayurlLength));?>]</div></td>
</tr>


<tr>
<td><?php echo $this->get_label('cta button text');?></td>
<td><input type="text" name="cta_button_text" id="cta_button_text" value="<?php echo $this->get_variable('cta_button_text');?>" maxlength="<?php echo $ctaButtonLength;?>" onkeyup="AdPreview(0);" /><div class="notification">[<?php echo $this->get_label('max character',array('x'=>$ctaButtonLength));?>]</div></td>
</tr>

<tr>
<td><?php echo $this->get_label('clickurl');?></td>
<td><input type="text" name="clickurl" value="<?php echo $this->get_variable('clickurl');?>"><span class="compulsory">*</span><div class="notification">[<?php echo $this->get_label('example url');?>]</div></td>
</tr>

<tr>
<td ><?php echo $this->get_label('banner size');?></td>
<td>
<select name="bannersize" id="bannersize" style="width: 166px;" onchange="AdPreview(0);">
<?php foreach($res4 as $key=>$result)
{
	$height=$result['height'];
	$width=$result['width'];
	$filesize=$result['filesize'];
	$id=$result['id'];
	$diamensions=$result['width']." x ".$result['height'];
	?>
	<option value="<?php echo $id?>" <?php if($this->get_variable('bannersize') ==$id) { echo "selected"; } ?> ><?php echo $diamensions ?></option>
<?php 
}
?>
</select>
</td>
</tr>

<tr>
<td><?php echo $this->get_label('banner');?></td>
<td><input type="file" name="banner" id="banner" size="10"><span class="compulsory">*</span><br/>

<span class="notification">[<?php echo $this->get_label('supported image format');?>]</span>
</td>
</tr>


<tr>
<td></td>
<td>
<input type="hidden" name="type1" id="type1" value="11" />

<input type="submit" name="submit11" value="<?php echo $this->get_label('create ad');?>"></td>
</tr>
</table>
<?php $form11->end(); ?>
</td>
</tr>

<?php if($pop_enabled ==1){?>
<tr id="popad" style="display:none;"><td colspan="2">
<?php 
$form9=$this->create_form();
$form9->start("create_default9",$this->make_url("ad/create_default"),"post",$validate9); 
?>
<table style="width: 100%;" cellpadding="0" cellspacing="0">

<tr>
<td style="width: 150px;"><?php echo $this->get_label('name');?></td>
<td><input type="text" name="name" value="<?php echo $this->get_variable('name');?>"><span class="compulsory">*</span></td>
</tr>

<tr>
<td><?php echo $this->get_label('clickurl');?></td>
<td><input type="text" name="clickurl" value="<?php echo $this->get_variable('clickurl');?>"><span class="compulsory">*</span><div class="notification">[<?php echo $this->get_label('example url');?>]</div></td>
</tr>

<tr>
<td></td>
<td>
<input type="hidden" name="type1" id="type1" value="9" />
<input type="submit" name="submit9" value="<?php echo $this->get_label('create ad');?>"></td>
</tr>

</table>

<?php $form9->end(); ?>
</td>
</tr>
<?php }?>



<?php if($directlink_enabled ==1){?>
<tr id="directlink-ad" style="display:none;"><td colspan="2">
<?php
$form21=$this->create_form();
$form21->start("create_default21",$this->make_url("ad/create_default"),"post",$validate21);
?>
<table style="width: 100%;" cellpadding="0" cellspacing="0">

<tr>
<td style="width: 150px;"><?php echo $this->get_label('name');?></td>
<td><input type="text" name="name" value="<?php echo $this->get_variable('name');?>"><span class="compulsory">*</span></td>
</tr>

<tr>
<td><?php echo $this->get_label('clickurl');?></td>
<td><input type="text" name="clickurl" value="<?php echo $this->get_variable('clickurl');?>"><span class="compulsory">*</span><div class="notification">[<?php echo $this->get_label('example url');?>]</div></td>
</tr>

<tr>
<td></td>
<td>
<input type="hidden" name="type1" id="type1" value="21" />
<input type="submit" name="submit21" value="<?php echo $this->get_label('create ad');?>"></td>
</tr>

</table>

<?php $form21->end(); ?>
</td>
</tr>
<?php }?>


<?php if($cpv_enabled ==1){?>
<tr id="videoad" style="display:none;"><td colspan="2">
<?php 
$form13=$this->create_form();
$form13->start("create_default13",$this->make_url("ad/create_default"),"post",$validate13);

?>

<table style="width: 100%;" cellpadding="0" cellspacing="0">
<tr>
<td style="width: 150px;"><?php echo $this->get_label('name');?></td>
<td><input type="text" name="name" value="<?php echo $this->get_variable('name');?>"><span class="compulsory">*</span></td>
</tr>

<tr>
<td><?php echo $this->get_label('displayurl');?></td>
<td><input type="text" name="displayurl" value="<?php echo $this->get_variable('displayurl');?>" maxlength="<?php echo $maxDisplayurlLength;?>"><span class="compulsory">*</span><div class="notification">[<?php echo $this->get_label('max character',array('x'=>$maxDisplayurlLength));?>]</div></td>
</tr>

<tr>
<td><?php echo $this->get_label('clickurl');?></td>
<td><input type="text" name="clickurl" value="<?php echo $this->get_variable('clickurl');?>"><span class="compulsory">*</span><div class="notification">[<?php echo $this->get_label('example url');?>]</div></td>
</tr>



<tr>
<td><?php echo $this->get_label('video file');?></td>
<td><input type="file" name="videofile" id="videofile" size="10"><span class="compulsory">*</span><br/>

<span class="notification">[<?php echo $this->get_label('supported video format html5');?>]</span>
<span class="notification">[<?php echo $this->get_label('supported aspect ratio',array('x'=>$this->get_aspect_ratio_list()));?>]</span>

</td>
</tr>


<tr>
<td></td>
<td>
<input type="hidden" name="type1" id="type1" value="13" />
<input type="submit" name="submit13" value="<?php echo $this->get_label('create ad');?>"></td>
</tr>

</table>
<?php $form13->end(); ?>
</td></tr>

<?php }?>




<?php if($skin_enabled ==1){?>
<tr id="skinad" style="display:none;"><td colspan="2">
<?php 
$form14=$this->create_form();
$form14->start("create_default14",$this->make_url("ad/create_default"),"post",$validate14); 
?>
<table style="width: 100%;" cellpadding="0" cellspacing="0">

<tr>
<td style="width: 150px;"><?php echo $this->get_label('name');?><span class="compulsory">*</span></td>
<td><input type="text" name="name" value="<?php echo $this->get_variable('name');?>" /></td>
</tr>


<tr>
<td><?php echo $this->get_label('clickurl');?><span class="compulsory">*</span></td>
<td><input type="text" name="clickurl" value="<?php echo $this->get_variable('clickurl');?>"><div class="notification">[<?php echo $this->get_label('example url');?>]</div></td>
</tr>


<tr>
<td><?php echo $this->get_label('banner size');?></td>
<td>

<select class="sizedropdown" name="bannersize_14" id="bannersize_14" onchange="changeadtype();">
<?php
$res14_adblock=$this->get_result('res14_adblock');
$skin_preview ="";
foreach($res14_adblock as $key=>$result)
{
	$height=$result['height'];
	$width=$result['width'];
	$id=$result['id'];
	$diamensions=$result['name'];
	$image_name= $this->get_skin_preview($id);

	$skin_preview.='<div class="skin_prev" id="skin_prev'.$id.'" style="display:none;"><img src="'.$image_name.'"></div>';	
?>
<option value="<?php echo $id;?>" <?php if($this->get_variable("bannersize")==$id) { echo "selected"; }?>><?php echo $diamensions; ?></option>
<?php }?>
</select>
<div> <?php echo $skin_preview; ?></div>
</td>
</tr>


<?php
$res14_banner=$this->get_result('res14_banner');
$ids="";
$iiii=0;
foreach($res14_banner as $key=>$result)
{
	$height=$result['height'];
	$width=$result['width'];
	$id=$result['id'];

	if($ids !="")
	$ids.=",";
	
	$ids.=$id;
?>
<tr>
<td><?php echo $this->get_label('banner');?> - <?php echo $width.' x '.$height;?><span class="compulsory">*</span></td>
<td>
<input class="form-control" type="file" name="skin_banner_<?php echo $id; ?>" size="10" />

<?php if($iiii ==0){?>
<br/>
<span class="notification"><bdi>[<?php echo $this->get_label('supported image format');?>]</bdi></span>
<?php }?>
</td>
</tr>
<?php 
$iiii++;
}?>

<tr>
<td></td>
<td>
<input type ="hidden" name ="existing_dimensions" value ="<?php echo $ids; ?>" />
<input type="hidden" name="type1" id="type1" value="14" />
<input type="submit" name="submit14" value="<?php echo $this->get_label('create ad');?>"></td>
</tr>

</table>
<?php $form14->end(); ?>
</td>
</tr>
<?php }?>


</table>
</div>
</div>
<?php $this->dispatch("layout/footer");?>
<script type="text/javascript">
changeadtype();
</script>
