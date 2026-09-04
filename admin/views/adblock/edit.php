<?php 
$this->dispatch("layout/header/4/_42");

$inpage_push_enabled = $this->get_addon_status('inpage-push-ads_enabled');

$res     		= $this->get_result('res');
$result  		= $res[0];

$adbid   		= $this->get_variable('adbid');
$adType  		= $result['type']; 
$bannerSize 	= $result['bannersize'];
$bannerType 	= $result['banner_type'];
$adblockWidth   = $result['width'];
$adblockHeight  = $result['height'];
$image_name     = "";
    

if($_POST)
{
	$adblockName 				= $this->get_variable("adbname");
	$allowForPublisher 			= $this->get_variable("allowForPublisher");
	$textimageSize 				= $this->get_variable("textimage_bannersize");
	$creditText    				= $this->get_variable("credittext");
	$credit_text_alignment    	= $this->get_variable("credit_text_alignment");
	$credit_text_positioning    = $this->get_variable("credit_text_positioning");
	$borderType 				= $this->get_variable("borderType");
	$adblockFont 				= $this->get_variable("adblockFont");
	$adblockTheme 				= $this->get_variable("adblockTheme");
	$image_position 			= $this->get_variable("image_position");
	$layout_list        		= $this->get_variable('adblockLayoutList');
	$allow_inpage_push_ads 		= $this->get_variable('allow_inpage_push_ads');
}
else
{
	$adblockName 				= $result['name'];
	$allowForPublisher 			= $result['allowpublisher'];
	$textimageSize 				= $result['textimage_size'];
	$creditText    				= $result['credit_text'];
	$credit_text_alignment    	= $result['creditalignment'];
	$credit_text_positioning    = $result['creditposition'];
	$borderType 				= $result['bordertype'];
	$adblockFont 				= $result['font'];
	$adblockTheme 				= $result['theme'];
	$image_position 			= $result['image_position'];
	$layout_list        		= $result['ad_layout_list'];

	if(isset($result['allow_inpage_push_ads']))
	$allow_inpage_push_ads 		= intval($result['allow_inpage_push_ads']);
}


if($layout_list == "")
$layout_list 					= '[]';

$text_ads_enabled               = Configuration::get_instance()->read('text-ads_enabled');
$credittextDisplay    			= Configuration::get_instance()->read('credit_text_display_mouse_hover');
$textimage_enabled 				= $this->get_addon_status('text-image-ads_enabled');

if($textimage_enabled == 1 || $textimage_enabled == 0)
$textimage_enabled = 1;

if($textimage_enabled == 1 && $textimageSize > 0)
$textimageData	= 1;
else
$textimageData  = 0;	

$resTheme           = $this->get_result('resTheme');
$resFont            = $this->get_result('resFont');
$resLayout          = $this->get_result('resLayout');
$resFontExternal    = $this->get_result("resFontExternal");
?>
<style type="text/css">
<?php foreach($resFontExternal as $fkey=>$fvalue){?>
@font-face {
  font-family: <?php echo $fvalue['slug_name'];?>;
  src: url(<?php echo BASE.DATA_DIR.'/fonts/'.$fvalue['file_name'];?>);
}
<?php } ?>

.sub_menu_main
{
	z-index: 1;
}

.head_links
{
	z-index: 1;
}
</style>
 
<script type="text/javascript">
function LoadTextImagePosition()
{
    textimage_selected = $('#textimage_bannersize').val();

    if(textimage_selected == 0)
    $('.textImagePositionDiv').hide();
    else
    $('.textImagePositionDiv').show();

	if($("#textimage_bannersize").val() > 0)
	$(".previewTextImageButton").show();
	else
	$(".previewTextImageButton").hide();
}


function LoadAspectRatio()
{
	adtype = $('#adtype').val();

	$('#aspect_ratio_tr').hide();
	
	if(adtype ==5)
	{
		width   = <?php echo $adblockWidth;?>;
		height  = <?php echo $adblockHeight;?>;

		$('#aspect_ratio_span').html("");

		supported_ratio="";
	
		if(width >0 && height >0)
		{
			divide=Math.round((width/height)*1000)/1000;

			aspect_ratio=$('#aspect_ratio').val();

			if(aspect_ratio !="")
			{
				aspect_ratio_array=aspect_ratio.split(',');

				aspect_ratio_length=aspect_ratio_array.length;

				for(i=0;i < aspect_ratio_length;i++)
				{
					if(aspect_ratio_array[i] != "")
					{
						sub_content_array=aspect_ratio_array[i].split('-');

						sub_content_length=sub_content_array.length;

						if(sub_content_length >1)
						{
							if(divide == sub_content_array[1])
							{
								if(supported_ratio !="")
								supported_ratio+=",";

								supported_ratio+=sub_content_array[0];
							}
						}

					}
				}
			}

			if(supported_ratio !="")
			{
				$('#aspect_ratio_span').html(supported_ratio);
	  			$('#aspect_ratio_tr').show();
	  			return;
			}
	  		else
	  		{
	  			$('#aspect_ratio_span').html("<?php echo $this->get_label('none');?>");
	  			$('#aspect_ratio_tr').show();
	  			return;
	  		}
		}
  		else
			return;
	}
	else
	return;
}


function LoadAdblockPreview(previewType)
{
	adBlockType        = $("#adtype").val();	
	bannerType		   = $("#banner_type").val();

	oldAdBlockType     = adBlockType;

	if(adBlockType == 3 && previewType == 0)
	{
		textAdsEnabled      = <?php echo $text_ads_enabled; ?>;
		textimageAdsEnabled = <?php echo $textimage_enabled; ?>;

		if(textAdsEnabled == 1)
		adBlockType = 1;
		else 
		adBlockType = 2;		
	}
	else if(previewType > 0)
	adBlockType        = previewType;	


	if($("#adblock_ad_layout").length > 0)
	adblockLayout      = parseInt($("#adblock_ad_layout").val());
	else 
	adblockLayout      = 0;	

	if(bannerType == 4)
	{
		$('.previewSection').html('');
		return;
	}
	else if(adblockLayout == 0 && (adBlockType == 1 || adBlockType == 3 || adBlockType == 4))
	{
		$('.previewSection').html('');
		return;		
	}

	adblockWidth  = 0;
	adblockHeight = 0;
	bannerSize    = 0;
	textimageSize = 0;
	imagePosition = 0;
	
	if(adBlockType == 1 || adBlockType == 4 || adBlockType == 5)
	{
		adblockWidth  = <?php echo $adblockWidth;?>;
		adblockHeight = <?php echo $adblockHeight;?>;

		if(adBlockType == 4)
		{
			textimageSize  = $("#textimage_bannersize").val();
			imagePosition  = $("#image_position").val();
		}
	}


	if(adBlockType == 2 || oldAdBlockType == 3)
	{
		bannerSize     = <?php echo $bannerSize;?>;			

		textimageSize  = $("#textimage_bannersize").val();
		imagePosition  = $("#image_position").val();
	}



	if(adBlockType == 4 && textimageSize > 0)
	$('#previewRadio4').prop("checked",true);
    else 
    {
    	if(adBlockType == 2 && $('#previewRadio2').length > 0)
    	$('#previewRadio2').prop("checked",true);	
    	else 
    	{
	    	if($('#previewRadio1').length > 0)
	    	$('#previewRadio1').prop("checked",true);	
	    	else 
	    	$('#previewRadio2').prop("checked",true);	
		}
    }

	borderType     	   = 1;
	creditText     	   = 0;
	creditAlignment    = 0;
	creditPositioning  = 0;	


    if($("#borderType").length > 0)
	borderType     	   = $("#borderType").val();

	if($("#credittext").length > 0)
	creditText     	   = $("#credittext").val();

	if($("#credit_text_alignment").length > 0)
	creditAlignment    = $("#credit_text_alignment").val();

	if($("#credit_text_positioning").length > 0)
	creditPositioning  = $("#credit_text_positioning").val();


	adblockFont        = 0;
	adblockTheme       = 0;

	if(oldAdBlockType == 2 || oldAdBlockType == 5)
	{
		adblockFont    = $("#adblock_font").val();
		adblockTheme   = $("#adblock_theme").val();
	}


	$("#load").show();

	dataparam    = "adBlockType="+adBlockType+"&oldAdBlockType="+oldAdBlockType+"&adblockFont="+adblockFont+"&adblockTheme="+adblockTheme+"&adblockLayout="+adblockLayout+"&bannerSize="+bannerSize+"&textimageSize="+textimageSize+"&imagePosition="+imagePosition+"&adblockWidth="+adblockWidth+"&adblockHeight="+adblockHeight+
		"&borderType="+borderType+"&creditText="+creditText+"&creditAlignment="+creditAlignment+"&creditPositioning="+creditPositioning;

	var urlvalue = '<?php echo $this->make_url("adblock/load_adblock_preview");?>';

	$.ajax(
	{
		type: "POST",
		data: dataparam,
		url: urlvalue,
		success: function(message)
		{						
			$("#load").hide();
			
            $('.previewSection').html(message);
		}
	});
}

function CreditLoadData(id,type)
{
    $(".select-div-li").html($("#list-li-"+id).html()+'<i class="fa fa-caret-down"></i>');
    $("#credittext").val(id);

    bannerType = $('#banner_type').val();

    if(id == 0 || bannerType == 4)//Video or Skin
    $('.creditTextSettings').hide();	
	else 
	$('.creditTextSettings').show();

	LoadAdblockPreview(0);
}


function AdmarketCreditLoad()
{
	if($('#creditIconDiv').length > 0)
	{
		$('#creditIconDiv').hide();
		$('#creditDiv').show();
	}
}
function AdmarketCreditDisable()
{
	<?php if($credittextDisplay == 1){?>
	$('#creditIconDiv').show();
	$('#creditDiv').hide();
	<?php }else{ ?>
	$('#creditIconDiv').hide();
	$('#creditDiv').show();
	<?php }?>
}

<?php if($credittextDisplay == 0){?>
AdmarketCreditLoad();
<?php }?>
</script>

<?php 
$validate=array(
	    "adbname"=>array(
				"notNull"=>array($this->get_message("not null"))
		)
	);
?>
<div class="sub_menu_main"><?php echo $this->get_label('edit adblock');?></div>

<?php $this->dispatch("links/links/18");?>

<div class="inner-box">

<table style="width: 100%;" cellpadding="0" cellspacing="0">
<tr ><td>

<div class="adblock_div" style="min-height: 700px;">
<div class="adblock_details adblock-info">

<?php 
$form=$this->create_form();
$form->start("editadblock",$this->make_url("adblock/edit/".$adbid),"post",$validate); 
?>

<table style="width: 100%;">
<tr class="adblock_heading"><td colspan="8"><?php echo $this->get_label('basic settings');?> </td></tr>
<tr><td style="height: 10px;"></td></tr>

<tr>
<td>
<?php 
$aspect_ratio="";

if($adType == 5)
$aspect_ratio=$this->get_aspect_ratio_list(2);	
?>  
<span>
<input type="hidden" name="adbid" id="adbid" value="<?php echo $adbid; ?>" />
<input type="hidden" name="adtype" id="adtype" value="<?php echo $adType;?>" />
<input type="hidden" name="banner_type" id="banner_type" value="<?php echo $bannerType;?>" /> 
<input type="hidden" name="aspect_ratio" id="aspect_ratio" value="<?php echo $aspect_ratio;?>" />
</span>

<div class="adblock_details_sub"> 
<?php echo $this->get_label('adblock name');?> <span class="compulsory">*</span><br/>
<input type="text" name="adbname" id="adbname" value="<?php echo $adblockName;?>" style="width: 90%;height: 23px;" />
</div>
  

<div class="adblock_details_sub"> 
<?php echo $this->get_label('allow for publishers');?>
<br/>
<select name="allowForPublisher" id="allowForPublisher"  style="width: 125px;">
<option value="1" <?php if($allowForPublisher == 1){echo "selected";}?>><?php echo $this->get_label('yes');?></option>
<option value="2" <?php if($allowForPublisher == 2){echo "selected";}?>><?php echo $this->get_label('no');?></option>
</select>
</div>

<div class="adblock_details_sub"> 
<?php echo $this->get_label('adtype');?>
<div style="margin-top: 10px;"><?php echo $this->get_adblock_type($adType,0,$textimageData);?></div>
</div>
  
  
<?php if($bannerType != 4){?>  
   <div class="adblock_details_sub">      
   <?php echo $this->get_label('adblock size');?> 
   <br/>
   <div style="float: left;margin-right: 10px;width: 100%;">
   <?php 
   echo '<div style="margin-top: 10px;">'.$adblockWidth." x ".$adblockHeight."</div>";
   ?>
   </div>
   
   <?php if($adType ==5){?>
   <div id="aspect_ratio_tr">
   <?php echo $this->get_label('supported aspect ratio adblock');?> : <span id="aspect_ratio_span"></span>
   </div>
   <?php }?>  
   </div>  
<?php }?>  

<?php if($adType == 1 || $adType == 2 || $adType == 3 || $adType == 4){?>    
<div class="adblock_details_sub"> 
<?php echo $this->get_label('adblock type');?> 
<br/>
<div style="margin-top: 10px;">
    <?php 
    if($bannerType == 0 || $bannerType == 3)
    echo $this->get_label('normal ads');
    else if($bannerType == 1)
    echo $this->get_label('interstitial ads');
    else if($bannerType == 4)
    echo $this->get_label('skin ads');   
    ?>
</div>
</div>
<?php }?>

<?php if($inpage_push_enabled == 1 && ($adType == 1 || $adType == 3 || $adType == 4) && $bannerType == 0){?>    
	<div class="adblock_details_sub inpagePushSection"> 
		<?php echo $this->get_label('allow inpage push ads');?>
		</br>		
		<select name="allow_inpage_push_ads" id="allow_inpage_push_ads" style="width: 125px;">
			<option value="0" <?php if($allow_inpage_push_ads == 0) {echo "selected";}?>><?php echo $this->get_label('no');?></option>
			<option value="1" <?php if($allow_inpage_push_ads == 1) {echo "selected";}?>><?php echo $this->get_label('yes');?></option>
		</select>
	</div>		 
<?php }else{?>
	<span><input type="hidden" name="allow_inpage_push_ads" id="allow_inpage_push_ads" value="0" /></span>
<?php }?>

 
<?php if($bannerType != 4){?>   
<?php if($textimage_enabled == 1 && ($adType == 3 || $adType == 4)){?>
<div class="adblock_details_sub">
<?php echo $this->get_label('textimage size');?>
</br>  
<select name="textimage_bannersize" id="textimage_bannersize" style="width: 130px;" onchange="LoadTextImagePosition();LoadAdblockPreview(4);">
<?php if($adType == 3){?>
<option value="0"><?php echo $this->get_label('select');?></option>
<?php } ?>
<?php 
$res2 = $this->get_result('res2');
foreach($res2 as $key=>$result1)
{
	$bid      	 = $result1['id'];
	$height      = $result1['height'];
	$width       = $result1['width'];
	$diamensions = $result1['width']." x ".$result1['height'];
?>
	<option value="<?php echo $bid?>" <?php if($textimageSize == $bid){echo "selected";}?> ><?php echo $diamensions ?></option>
<?php } ?>
</select>
</div>

  
<div class="adblock_details_sub textImagePositionDiv">
<?php echo $this->get_label('image position');?>
</br>
<select name="image_position" id="image_position" size="1" style="width: 125px;" onchange="LoadAdblockPreview(4);">
<option value="0" <?php if($image_position == 0){echo "selected";}?>><?php echo $this->get_label('left');?></option>
<option value="1" <?php if($image_position == 1){echo "selected";}?>><?php echo $this->get_label('top');?></option>
<option value="2" <?php if($image_position == 2){echo "selected";}?>><?php echo $this->get_label('right');?></option>
<option value="3" <?php if($image_position == 3){echo "selected";}?>><?php echo $this->get_label('bottom');?></option>
</select>
</div>  
<?php }?>


<div class="adblock_details_sub creditTextDiv">
<?php echo $this->get_label('credit text');?>
</br>
<?php echo $this->get_credit_list($creditText);?>
</div>


<div class="adblock_details_sub creditTextSettings" <?php if($creditText == 0){?> style="display: none;" <?php } ?> >
<?php echo $this->get_label('credit alignment');?>
</br>
<select name="credit_text_alignment" size="1" id="credit_text_alignment" style="width: 125px;" onchange="LoadAdblockPreview(0);">
<option value="0" <?php if($credit_text_alignment == 0){echo "selected";}?> ><?php echo $this->get_label('left');?></option>
<option value="1" <?php if($credit_text_alignment == 1){echo "selected";}?>><?php echo $this->get_label('right');?></option>
</select>
</div>


<div class="adblock_details_sub creditTextSettings" <?php if($creditText == 0){?> style="display: none;" <?php } ?> >
<?php echo $this->get_label('credit position');?>
</br>
<select name="credit_text_positioning" size="1" id="credit_text_positioning" style="width: 125px;" onchange="LoadAdblockPreview(0);">
<option value="1" <?php if($credit_text_positioning == 1){echo "selected";}?> ><?php echo $this->get_label('top');?></option>
<option value="0" <?php if($credit_text_positioning == 0){echo "selected";}?> ><?php echo $this->get_label('bottom');?></option>
</select>
</div>
<?php }?>
     
     
<?php if($bannerType == 4){?> 
<div class="adblock_details_sub"> 
&nbsp;<?php echo $this->get_label('skin positions');?>
</br> 
<?php
$skin_position1             = html_entity_decode($result['skin_positions']);
$skin_position              = json_decode($skin_position1,1);

$left 						= $skin_position['L']  == 1 ? 'checked' : ''; 
$right 						= $skin_position['R']  == 1 ? 'checked' : '';
$top 						= $skin_position['T']  == 1 ? 'checked' : ''; 
$bottom 					= $skin_position['B']  == 1 ? 'checked' : '';


$leftImage  				= $skin_position['L'] == 1 ? 'L' :0; 
$rightImage 				= $skin_position['R'] == 1 ? 'R' :0;
$topImage    				= $skin_position['T'] == 1 ? 'T' :0; 
$bottomImage 				= $skin_position['B'] == 1 ? 'B' :0;

$image_name                 = "".$leftImage."_".$rightImage."_".$topImage."_".$bottomImage.".png";
?>

<div>
<input disabled="disabled" readonly="readonly" type="checkbox" name="skin_position_l" id="skin_position_l" <?php echo $left; ?> value="1" /><?php echo $this->get_label('left');?>
<input disabled="disabled" readonly="readonly" type="checkbox" name="skin_position_r" id="skin_position_r" <?php echo $right; ?> value="1" /><?php echo $this->get_label('right');?>
</div>
<div>
<input disabled="disabled" readonly="readonly" type="checkbox" name="skin_position_t" id="skin_position_t" <?php echo $top; ?> value="1" /><?php echo $this->get_label('top');?>
<input disabled="disabled" readonly="readonly" type="checkbox" name="skin_position_b" id="skin_position_b" <?php echo $bottom; ?> value="1" /><?php echo $this->get_label('bottom');?>
</div>
</div>    
<?php } ?>  



<?php if($adType == 1 || $adType == 3 || $adType == 4){?>
<div class="adblock_details_sub">
<?php echo $this->get_label('border type');?>
</br>
<select name="borderType" id="borderType" style="width: 125px;" onchange="LoadAdblockPreview(0);">
<option value="1" <?php if($borderType == 1){echo "selected";}?>><?php echo $this->get_label('regular');?></option>
<option value="0" <?php if($borderType == 0){echo "selected";}?>><?php echo $this->get_label('rounded');?></option>
</select>
</div>

<?php }?>   
   

<?php if($adType == 2 || $adType == 5){?>
<div class="adblock_details_sub fontDiv">
<?php echo $this->get_label('select font');?>
</br>
<select name="adblock_font" id="adblock_font" size="1" style="width: 125px;" onchange="LoadAdblockPreview(0);">

<?php foreach($resFont as $fontKey => $fontValue){?>
<option value="<?php echo $fontValue['id'];?>" <?php if($adblockFont == $fontValue['id']){?> echo "selected"; <?php }?>  ><?php echo $fontValue['font_name'];?></option>
<?php }?>
</select>
</div>


<div class="adblock_details_sub themeDiv">
<?php echo $this->get_label('select theme');?>
</br>
<select name="adblock_theme" id="adblock_theme" size="1" style="width: 125px;" onchange="LoadAdblockPreview(0);">	
<?php foreach($resTheme as $themeKey => $themeValue){?>
<option value="<?php echo $themeValue['id'];?>" <?php if($adblockTheme == $themeValue['id']){?> echo "selected"; <?php }?>><?php echo $themeValue['theme'];?></option>
<?php }?>
</select>
</div>
<?php } ?>

<?php if($adType == 1 || $adType == 3 || $adType == 4){?>
<div class="adblock_details_sub adLayoutDiv" style="display: block;">
<div><?php echo $this->get_label('choose layout');?></div>
<div>

<?php 
$i            		= 0;
$firstID      		= 0;
$initialValue 		= 0;
$initialValueData 	= 0;

if(count($resLayout) > 0)
{
	$layoutListArray 	= json_decode($layout_list);
	$layoutArrayCount   = count($layoutListArray);

	foreach($resLayout as $layoutKey => $layoutValue){

		$checkedString    = "";

        if($i == 0)
		$firstID = $layoutValue['id'];

		if($layoutArrayCount > 0)
		{
			if(in_array($layoutValue['id'], $layoutListArray))
			{
				$checkedString    = ' checked ="checked" ';

				if($initialValueData == 0)
				$initialValueData = $layoutValue['id'];
			}
		}

		?>
		<div class="layout-section-div">
		<input style="cursor: pointer;" type="checkbox" name="checkbox_layout" id="checkbox_layout_<?php echo $layoutValue['id'];?>" value="<?php echo $layoutValue['id'];?>" onclick="LayoutChecked(<?php echo $layoutValue['id'];?>);" <?php echo $checkedString; ?> />
		<?php echo $layoutValue['layout'];?>

		<span class="layout-section-preview-span">
			<input style="cursor: pointer;" type="radio" name="radio_preview" id="radio_preview_<?php echo $layoutValue['id'];?>" <?php if($i == 0 || $initialValueData == $layoutValue['id']){?> checked="checked" <?php } ?> onclick="LayoutChecked(<?php echo $layoutValue['id'];?>);" />
			<i class="fa fa-laptop fa-1x layout-preview-icon" title="<?php echo $this->get_label('preview');?>" onclick="LayoutChecked(<?php echo $layoutValue['id'];?>);"></i>
		</span>
		</div>
	<?php 
		$i++;
	}
}
else
{?>
	<div style="height : 25px;padding-top:5px;">
	<?php echo $this->get_label('no layout found');?>
	</div>

<?php }

if($initialValueData > 0)
$initialValue = $initialValueData;
else 
$initialValue = $firstID;
?>

</div>
<input type="hidden" name="adblock_ad_layout" id="adblock_ad_layout" value="<?php echo $initialValue; ?>" />
<input type="hidden" name="layout_list" id="layout_list" value="<?php echo $layout_list; ?>" />
</div>
<?php } ?>

</td>
</tr>
</table>

<?php if($adType == 1 || $adType == 3 || $adType == 4){?>
<div class="notification"><?php echo $this->get_label("multiple layout choose notification");?></div>
<?php } ?>


<div style="margin-top: 10px;text-align: center;">
	<input type="submit" name="submit" value="<?php echo $this->get_label('edit adblock');?>" />
</div>

<?php if($adType == 3){?>
<div>
<?php if($text_ads_enabled == 1){?>
<span class="previewButtonList previewTextButton">
<input type="radio" name="previewRadio" id="previewRadio1" value="1" checked="checked" onclick="LoadAdblockPreview(1);" />&nbsp;<?php echo $this->get_label('text');?>&nbsp;</span>
<?php }?>

<span class="previewButtonList previewBannerButton">
<input type="radio" name="previewRadio" id="previewRadio2" value="2" <?php if($text_ads_enabled == 0){?> checked="checked" <?php }?> onclick="LoadAdblockPreview(2);" />&nbsp;<?php echo $this->get_label('banner');?>&nbsp;</span>

<?php if($textimage_enabled == 1){?>
<span class="previewButtonList previewTextImageButton">
<input type="radio" name="previewRadio" id="previewRadio4" value="4" onclick="LoadAdblockPreview(4);" />&nbsp;<?php echo $this->get_label('textimage');?>&nbsp;</span>
<?php } ?>
</div>
<?php } ?>

<div><img id="load" src="images/load.gif" style="display: none;" /></div>
<div class="previewSection" ></div>


<?php $form->end(); ?>
</div>
</div>

</td>
</tr>
</table>
</div>
<?php $this->dispatch("layout/footer");?>
<script type="text/javascript">
var slideInterval;
var checkedElements = <?php echo $layout_list; ?>

function startContentSlide(contentWidth,contentHeight,CTAPosition,sectionCount,contentSlide,slideDirection,slideDuration)
{
    increment = 1;

    if(slideInterval)
    clearInterval(slideInterval);    


    if(sectionCount == 1 || contentSlide == 0 || (CTAPosition != 0 && CTAPosition != 1 && CTAPosition != 2))
    return;

    slideDuration  = slideDuration * 1000;

    slideInterval = setInterval(function()
    { 
        if(increment < sectionCount)
        {
            increment++;

            if(slideDirection == 1) //1 for Horizontal
            {
                currentLeftValue = parseFloat($(".ad-layout-content-inner").css("left"));
                newLeftValue     = currentLeftValue - contentWidth;

                $(".ad-layout-content-inner").animate({"left": newLeftValue+"px"}, "slow");
            }
            else    //0 for Vertical 
            {
                currentTopValue = parseFloat($(".ad-layout-content-inner").css("top"));
                newTopValue     = currentTopValue - contentHeight;

                $(".ad-layout-content-inner").animate({"top": newTopValue+"px"}, "slow");
            }  

        }
        else
        {
            increment        = 1;

            if(slideDirection == 1) //1 for Horizontal
            {
                $(".ad-layout-content-inner").css("left", contentWidth+"px");
                $(".ad-layout-content-inner").animate({"left": "0px"}, "slow");
            }
            else  //0 for Vertical 
            {
                $(".ad-layout-content-inner").css("top", contentHeight+"px");
                $(".ad-layout-content-inner").animate({"top": "0px"}, "slow");
            }
        }        

    }, slideDuration);

}

function LayoutChecked(id)
{
	$('#radio_preview_'+id).prop("checked",true);

	if($('#checkbox_layout_'+id).prop("checked"))
	{
		alreadyExists = 0;
	    for(var ii = 0; ii < checkedElements.length; ii++)
	    { 		    
	        if(checkedElements[ii] === id)
	        { 
	    		alreadyExists = 1;
	    		break;
	        }	    
	    }

	    if(alreadyExists == 0)
	    checkedElements.push(id);	
	}
	else 
	{
		for(var ii = 0; ii < checkedElements.length; ii++)
		{ 		    
	        if(checkedElements[ii] === id)
	        { 
	            checkedElements.splice(ii, 1); 
	            break;
	        }		    
		}		
	}	

	$('#layout_list').val(JSON.stringify(checkedElements));


	if($('#checkbox_layout_'+id).prop("checked") || $('#radio_preview_'+id).prop("checked"))
	{
    	$('#adblock_ad_layout').val(id);  

    	adBlockType        = $("#adtype").val();	

	    if(adBlockType == 3)
	    {
	    	if($('#previewRadio1').length > 0)
	    	$('#previewRadio1').prop("checked",true);	
	    	else 
	    	$('#previewRadio2').prop("checked",true);	
	    }

    	LoadAdblockPreview(0);	
    }
}

$(document).ready(function() {
LoadAspectRatio();

<?php if($bannerType != 4){?> 	
LoadAdblockPreview(0);	
<?php }else{ 
		$imagePath = BASE.ADDON_DIR.'/skin-ads/images/adblock/'.$image_name;
	?>

	$('.previewSection').html("<div style=\"background-image: url('<?php echo $imagePath; ?>'); background-repeat: no-repeat;background-size: cover;width:300px;height:300px;\"></div>");

<?php } ?>

<?php if($textimage_enabled == 1 && $adType == 3){?>
LoadTextImagePosition();
<?php }?>
});
</script>