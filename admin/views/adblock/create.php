<?php 
$this->dispatch("layout/header/4/_42");

$interstitial_enabled = $this->get_addon_status('interstitial_enabled');
$textimage_enabled    = $this->get_addon_status('text-image-ads_enabled');
$inpage_push_enabled  = $this->get_addon_status('inpage-push-ads_enabled');

$video_enabled        = $this->get_addon_status('video-ads_enabled');
$skin_enabled         = $this->get_addon_status('skin-ads_enabled');

$credittext           = intval($this->get_variable('credittext'));
$credittype           = $this->get_credit_type($credittext);

$text_ads_enabled     = Configuration::get_instance()->read('text-ads_enabled');
$credittextDisplay    = Configuration::get_instance()->read('credit_text_display_mouse_hover');


if($textimage_enabled == 1 || $textimage_enabled == 0)
$textimage_enabled = 1;


$adbname            = $this->get_variable('adbname');
$height             = $this->get_variable('height');
$width              = $this->get_variable('width');
$adtype             = $this->get_variable('adtype');
$adblockFont        = $this->get_variable('adblockFont');
$adblockTheme       = $this->get_variable('adblockTheme');
$bannersize         = $this->get_variable('bannersize');
$banner_type        = $this->get_variable('banner_type');
$layout_list        = $this->get_variable('adblockLayoutList');
$allow_inpage_push_ads = intval($this->get_variable('allow_inpage_push_ads'));

if($layout_list == "")
$layout_list = '[]';



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


<script  type="text/javascript" >
function LoadAspectRatio()
{
	adtype=$('#adtype').val();

	$('#aspect_ratio_tr').hide();

	if(adtype == 5)
	{
		width=$('#width').val();
		height=$('#height').val();

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




function ChangeSettings()
{
	var type            = $('#adtype').val();
	var bannertype      = 0;
	var bannerDimension = 0;

	<?php if($interstitial_enabled ==1 || $skin_enabled ==1){?>
		bannertype     = $('#banner_type').val();
	<?php }?>


	if($(".inpagePushSection").length > 0)
	{
		if((type == 1 || type == 3 || type == 4) && bannertype == 0)
		$(".inpagePushSection").show();
		else 
		$(".inpagePushSection").hide();
	}

	textAdsEnabled      = <?php echo $text_ads_enabled; ?>;
	textimageAdsEnabled = <?php echo $textimage_enabled; ?>;

	if(type == 4)
	bannerDimension = 3;
	else 
	{
		<?php if($interstitial_enabled ==1 || $skin_enabled ==1){?>
			bannerDimension     = $('#banner_type').val();
		<?php }?>
	}
	
	$('#banner_type').val(bannertype);
		
	$('.banner-select').hide();
	
	<?php if($interstitial_enabled ==1 || $skin_enabled ==1){?>
	$('.interstitialSkinSection').hide();
	<?php }?>

	$('.borderTypeDiv').hide();
	$('.widthHeightDiv').hide();
	$('.textImageDiv').hide();
	$('.textImageBannerDiv').hide();	
	$('.textImagePositionDiv').hide();
	$('.bannerDiv').hide();
	$(".skinDiv").hide();
	$(".fontDiv").hide();
	$(".themeDiv").hide();
	$(".adLayoutDiv").hide();
	$(".previewTextButton").hide();
	$(".previewBannerButton").hide();
	$(".previewTextImageButton").hide();


	if($("#aspect_ratio_tr").length >0)
	$("#aspect_ratio_tr").hide();	


	if(type == 1)
	{
		$('.borderTypeDiv').show();
		$('.widthHeightDiv').show();	

		if($("#width").val() > 0 && $("#height").val() > 0)	
		$(".adLayoutDiv").show();
	}

	if(type == 1 || type == 2 || type == 3 || type == 4)
	{
		<?php if($interstitial_enabled ==1 || $skin_enabled ==1){?>
			$('.interstitialSkinSection').show();

			if(type == 2)
			{
				if($('.skin-option').length > 0)
				$('.skin-option').show();
			}
			else 
			{
				if($('.skin-option').length > 0)
				$('.skin-option').hide();
			}
		<?php }?>
	}


	if(type == 2)
	{   
		$('.bannerDiv').show();
		$('#borderType').val(1);

		if(bannertype != 4)
		{
			$(".fontDiv").show();
			$(".themeDiv").show();
		}	
	}

	if(type == 3)
	{
		$('.borderTypeDiv').show();		
		$('.bannerDiv').show();
		$(".adLayoutDiv").show();

		if(textAdsEnabled == 1)
		$(".previewTextButton").show();

		$(".previewBannerButton").show();

		if(textimageAdsEnabled == 1 && $("#bannersize_04").val() > 0)
		$(".previewTextImageButton").show();

		LoadTextImagePosition();
	}


	if(type == 4)
	{
		$('.borderTypeDiv').show();
		$('.widthHeightDiv').show();
		$('.bannerDiv').show();
		$('.textImageDiv').show();

		if($("#width").val() > 0 && $("#height").val() > 0)
		$(".adLayoutDiv").show();
	}


	if(type == 5) //Video ads
	{
		$('.widthHeightDiv').show();

		if($('#width').val() > 0 && $('#height').val() > 0)

		$('#borderType').val(1);
		$(".fontDiv").show();
		$(".themeDiv").show();
		LoadAspectRatio();
	}


	
   if(bannertype == 4)  //For Skin Ads
   {
   	   $(".creditTextDiv").hide();
   	   $(".creditTextSettings").hide();


	   $(".skinDiv").show();
	   $(".bannerDiv").hide();
   }
   else
   {   	   
	   $(".creditTextDiv").show();

	   if(type != 5 && $("#credittext").val() > 0)
	   $(".creditTextSettings").show();	
	   else 
	   $(".creditTextSettings").hide();
   }

	if(type == 2 || type == 3 || type == 4)
	{
		if(bannertype != 4)
		{
			$('#banner-select-0'+bannerDimension).show();				

			if(type == 3 && (textimageAdsEnabled == 1 || textimageAdsEnabled == 0))
			{
				$('.textImageBannerDiv').show();	
				$('.textImageDiv').hide();
			}
		}
	}
}



function LoadTextImagePosition()
{
   textimage_selected = $('#bannersize_04').val();

   if(textimage_selected == 0)
   $('.textImagePositionDiv').hide();
   else
   $('.textImagePositionDiv').show();

	if($("#bannersize_04").val() > 0)
	$(".previewTextImageButton").show();
	else
	$(".previewTextImageButton").hide();
}


function check_skin(position)
{
 	skin_chk=parseInt($("#skin_position").val());

 	if($("#skin_position_"+position).prop("checked") == true)
    skin_chk =skin_chk +1;
	else
    skin_chk =skin_chk -1;

    if(skin_chk ==0)
    skin_chk='';
    
    $("#skin_position").val(skin_chk);


    leftImage   = 0;
    rightImage  = 0;
    topImage    = 0;
    bottomImage = 0;
    imageName   = "";

    if($("#skin_position_l").prop("checked"))
    leftImage = 'L';	

    if($("#skin_position_r").prop("checked"))
    rightImage = 'R';

    if($("#skin_position_t").prop("checked"))
    topImage = 'T';

    if($("#skin_position_b").prop("checked"))
    bottomImage = 'B';


	if($("#skin_position_l").prop("checked") || $("#skin_position_r").prop("checked") || $("#skin_position_t").prop("checked") || $("#skin_position_b").prop("checked"))
    imageName                 = leftImage+"_"+rightImage+"_"+topImage+"_"+bottomImage+".png";

    $("#skin_image").val(imageName);

    LoadAdblockPreview(0);
}


function LoadAdblockPreview(previewType)
{
	adBlockType        = $("#adtype").val();	
	bannerType		   = $("#banner_type").val();

	if(bannerType == 4)
	{
		skinImage	   = $("#skin_image").val();

		if(skinImage != "")
		{
			imagePath      = "<?php echo BASE.ADDON_DIR.'/skin-ads/images/adblock/';?>"+skinImage;

			$('.previewSection').html("<div style=\"background-image: url("+imagePath+"); background-repeat: no-repeat;background-size: cover;width:300px;height:300px;\"></div>");
		}
		else 
		$('.previewSection').html('');	

		return;
	}

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


	adblockLayout      = parseInt($("#adblock_ad_layout").val());

	if(bannerType == 4)
	{
		$('.previewSection').html('');
		return;
	}
	else if(adblockLayout == 0)
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
		adblockWidth  = parseInt($("#width").val());
		adblockHeight = parseInt($("#height").val());

		if(oldAdBlockType !=3 && (!(adblockWidth > 0) || !(adblockHeight > 0)))
		{
			//alert('<?php echo $this->get_message("mandatory");?>');
			$('.previewSection').html('');
			return;
		}


		if(adBlockType == 4)
		{
			textimageSize  = $("#bannersize_03").val();
			imagePosition  = $("#image_position").val();
		}
	}


	if(adBlockType == 2 || oldAdBlockType == 3)
	{
		if(bannerType == 1)
		bannerSize     = $("#bannersize_01").val();	
		else
		bannerSize     = $("#bannersize_00").val();

		textimageSize  = $("#bannersize_04").val();
		imagePosition  = $("#image_position1").val();
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



	borderType     	   = $("#borderType").val();
	creditText     	   = $("#credittext").val();
	creditAlignment    = $("#credit_text_alignment").val();
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
		),
	    "banner_type=>4"=>array(
	        "skin_position"=>array("notNull"=>array($this->get_message("choose any position")))
	    )
);		

$form=$this->create_form();
$form->start("createadblock",$this->make_url("adblock/create"),"post",$validate); 
?>

<div class="sub_menu_main"><?php echo $this->get_label('create adblock');?></div>

<?php $this->dispatch("links/links/18");?>

<div class="inner-box">

<table style="width: 100%;" cellpadding="0" cellspacing="0">
<tr ><td>
<div class="adblock_div" style="min-height: 700px;">

<div class="adblock_details adblock-info">

<table style="width: 100%;">

<tr class="adblock_heading"><td colspan="8"><?php echo $this->get_label('basic settings');?> </td></tr>
<tr><td style="height: 10px;"></td></tr>
<tr>
<td>
  <?php 
  $aspect_ratio="";
  
  if($video_enabled ==1)
  $aspect_ratio=$this->get_aspect_ratio_list(2);	
  	 
  ?>
  <span><input type="hidden" name="aspect_ratio" id="aspect_ratio" value="<?php echo $aspect_ratio;?>" /></span>

 
<div class="adblock_details_sub"> 
<?php echo $this->get_label('adblock name');?> <span class="compulsory">*</span><br/>
<input type="text" name="adbname" id="adbname" value="<?php echo $adbname;?>" style="width: 90%;height: 23px;"/>
</div>

<div class="adblock_details_sub"> 
<?php echo $this->get_label('allow for publishers');?>
<br/>
<select name="allowForPublisher" id="allowForPublisher" style="width: 125px;">
<option value="1"><?php echo $this->get_label('yes');?></option>
<option value="2"><?php echo $this->get_label('no');?></option>
</select> 
</div>
  
<div class="adblock_details_sub"> 
  
<?php echo $this->get_label('adtype');?>
<br/>
<select name="adtype" id="adtype" onchange="ChangeSettings();LoadAdblockPreview(0);" style="width: 125px;">

<?php if($text_ads_enabled ==1){?>
<option value="1" <?php if($adtype == 1) {echo "selected";}?>><?php echo $this->get_label('text only');?></option>
<?php }?>

<option value="2" <?php if($adtype == 2) {echo "selected";}?>><?php echo $this->get_label('banner only');?></option>

<?php if($textimage_enabled ==1){?>
<option value="4" <?php if($adtype == 4) {echo "selected";}?>><?php echo $this->get_label('textimage only');?></option>
<?php }?>    

<?php if($text_ads_enabled ==1 && $textimage_enabled == 1){?>
<option value="3" <?php if($adtype == 3) {echo "selected";}?>><?php echo $this->get_label('textbanner textimage');?></option>
<?php }else if($text_ads_enabled ==1){?>
<option value="3" <?php if($adtype == 3) {echo "selected";}?>><?php echo $this->get_label('textbanner');?></option>
<?php }else if($textimage_enabled == 1){?>
<option value="3" <?php if($adtype == 3) {echo "selected";}?>><?php echo $this->get_label('banner textimage');?></option>
	<?php }?>
    
<?php if($video_enabled ==1){?>
<option value="5" <?php if($adtype == 5) {echo "selected";}?>><?php echo $this->get_label('video only');?></option>
<?php }?>
</select>   
</div>
  

<div class="adblock_details_sub widthHeightDiv" style="display: none;">

<span style="float: left;margin-right: 10px;">
<?php echo $this->get_label('width');?> (<?php echo $this->get_label('px');?>)<span class="compulsory">*</span>
<br/>
<input type="text" name="width" id="width" value="<?php echo $width;?>" size="5" style="height: 23px;width: 55px;" onkeyup="this.value=this.value.replace(/[^0-9]/g, '');LoadAspectRatio();"/>
</span>

<span style="float: left;margin-right: 10px;margin-top: 25px;">x</span>

<span style="float: left;"> 
<?php echo $this->get_label('height');?> (<?php echo $this->get_label('px');?>)<span class="compulsory">*</span>
</br>
<input type="text" name="height" id="height" value="<?php echo $height;?>" size="5" style="height: 23px;width: 55px;" onkeyup="this.value=this.value.replace(/[^0-9]/g, '');LoadAspectRatio();" /> 
</span>


<div id="aspect_ratio_tr" style="display: none;">
<?php echo $this->get_label('supported aspect ratio adblock');?> : <span id="aspect_ratio_span"></span>
</div>   

</div>  
  
   
<?php if($interstitial_enabled ==1 || $skin_enabled ==1){?>
    
<div class="adblock_details_sub interstitialSkinSection" style="display: none;"> 

<?php echo $this->get_label('adblock type');?>
</br>

<select name="banner_type" id="banner_type" onchange="ChangeSettings();LoadAdblockPreview(0);" style="width: 125px;">

<option value="0" <?php if($banner_type == 0) {echo "selected";}?>><?php echo $this->get_label('normal ads');?></option>

<?php if($interstitial_enabled ==1){?>
<option value="1" <?php if($banner_type == 1) {echo "selected";}?>><?php echo $this->get_label('interstitial ads');?></option>
<?php }?>

<?php if($skin_enabled ==1){?>
<option class="skin-option" style="display:none;" value="4" <?php if($banner_type == 4) {echo "selected";}?>><?php echo $this->get_label('skin ads');?></option>
<?php }?>
   
</select>
</div>
     
<?php }else{?>
<span><input type="hidden" name="banner_type" id="banner_type" value="0" /></span>
<?php }?>
  	
 
   
<?php if($inpage_push_enabled ==1){?>    
	<div class="adblock_details_sub inpagePushSection" style="display: none;"> 
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


   <div class="adblock_details_sub bannerDiv">
   
	<span class="banner-select" id="banner-select-00" style="display: none;">   
	<?php echo $this->get_label('adblock size');?>
    </br>
	<select name="bannersize_00" id="bannersize_00" onchange="LoadAdblockPreview(0);" style="width: 125px;">
	<?php 
	$res1=$this->get_result('res1');
	
	foreach($res1 as $key=>$result)
	{
		$height=$result['height'];
		$width=$result['width'];
		$id=$result['id'];
		$diamensions=$result['width']." x ".$result['height'];
	?>
	<option value="<?php echo $id;?>" <?php if($bannersize == $id) { echo "selected"; }?>><?php echo $diamensions ?></option>
	<?php }?>
	</select>
	</span>


	<span class="banner-select" id="banner-select-01" style="display: none;">   
	<?php echo $this->get_label('adblock size');?>
    </br>	
	<select name="bannersize_01" id="bannersize_01" onchange="LoadAdblockPreview(0);" style="width: 125px;">
	<?php 
	$res2=$this->get_result('res2');
	
	foreach($res2 as $key=>$result)
	{
		$height=$result['height'];
		$width=$result['width'];
		$id=$result['id'];
		$diamensions=$result['width']." x ".$result['height'];
	?>
	<option value="<?php echo $id;?>" <?php if($bannersize == $id) { echo "selected"; }?>><?php echo $diamensions ?></option>
	<?php }?>
	</select>
	</span>



	
	<span class="banner-select" id="banner-select-03" style="display: none;">   
	<?php echo $this->get_label('textimage size');?>
    </br>	
	<select name="bannersize_03" id="bannersize_03" style="width: 125px;" onchange="LoadAdblockPreview(4);">
	<?php 
	$res4=$this->get_result('res4');
	
	foreach($res4 as $key=>$result)
	{
		$height=$result['height'];
		$width=$result['width'];
		$id=$result['id'];
		$diamensions=$result['width']." x ".$result['height'];
	?>
	<option value="<?php echo $id;?>" <?php if($bannersize == $id) { echo "selected"; }?>><?php echo $diamensions ?></option>
	<?php }?>
	</select>
	</span>
	
  </div>
  
<div class="adblock_details_sub textImageBannerDiv" style="display: none;">
	<span>   
<?php echo $this->get_label('textimage size');?>
</br>	
<select name="bannersize_04" id="bannersize_04" style="width: 125px;" onchange="LoadTextImagePosition();LoadAdblockPreview(4);">
<option value="0"><?php echo $this->get_label('select');?></option>	

<?php 
$res4=$this->get_result('res4');

foreach($res4 as $key=>$result)
{
	$height=$result['height'];
	$width=$result['width'];
	$id=$result['id'];
	$diamensions=$result['width']." x ".$result['height'];
?>
<option value="<?php echo $id;?>" <?php if($this->get_variable("bannersize_04") == $id) { echo "selected"; }?>><?php echo $diamensions ?></option>
<?php }?>
</select>
</span> 
</div>
  
  
<div class="adblock_details_sub textImagePositionDiv" style="display: none;">
<?php echo $this->get_label('image position');?>
</br>
<select name="image_position1" id="image_position1" size="1" style="width: 125px;" onchange="LoadAdblockPreview(4);">
<option value="0" selected="selected" ><?php echo $this->get_label('left');?></option>
<option value="1" ><?php echo $this->get_label('top');?></option>
<option value="2" ><?php echo $this->get_label('right');?></option>
<option value="3" ><?php echo $this->get_label('bottom');?></option>
</select>
</div>
  
    
<div class="adblock_details_sub textImageDiv">
<?php echo $this->get_label('image position');?>
</br>
<select name="image_position" id="image_position" size="1" style="width: 125px;" onchange="LoadAdblockPreview(4);">
<option value="0" selected="selected" ><?php echo $this->get_label('left');?></option>
<option value="1" ><?php echo $this->get_label('top');?></option>
<option value="2" ><?php echo $this->get_label('right');?></option>
<option value="3" ><?php echo $this->get_label('bottom');?></option>
</select>
</div>



<div class="adblock_details_sub skinDiv" style="display: none;"> 
&nbsp;<?php echo $this->get_label('skin positions');?>
</br> 
<div>
<input type="checkbox" name="skin_position_l" id="skin_position_l" value="1" onclick="check_skin('l');"><?php echo $this->get_label('left');?>
<input type="checkbox" name="skin_position_r" id="skin_position_r" value="1" onclick="check_skin('r');"><?php echo $this->get_label('right');?>
</div>
<div>
<input type="checkbox" name="skin_position_t" id="skin_position_t" value="1" onclick="check_skin('t');"><?php echo $this->get_label('top');?>
<input type="checkbox" name="skin_position_b" id="skin_position_b" value="1" onclick="check_skin('b');"><?php echo $this->get_label('bottom');?>
</div>
<input type="hidden" name="skin_position" id="skin_position" value="" />
<input type="hidden" name="skin_image" id="skin_image" value="" />

</div>    
    

   <div class="adblock_details_sub borderTypeDiv" style="display: none;">
   <?php echo $this->get_label('border type');?>
   </br>
   <select name="borderType" id="borderType" style="width: 125px;" onchange="LoadAdblockPreview(0);">
   <option value="1"><?php echo $this->get_label('regular');?></option>
   <option value="0"><?php echo $this->get_label('rounded');?></option>
   </select>
   </div>


	<div class="adblock_details_sub creditTextDiv" style="display: none;">
    <?php echo $this->get_label('credit text');?>
	</br>
    <?php echo $this->get_credit_list($credittext);?>
    </div>


	<div class="adblock_details_sub creditTextSettings" style="display: none;">
    <?php echo $this->get_label('credit alignment');?>
	</br>
	<select name="credit_text_alignment" size="1" id="credit_text_alignment" style="width: 125px;" onchange="LoadAdblockPreview(0);">
	<option value="0" selected="selected"><?php echo $this->get_label('left');?></option>
	<option value="1"><?php echo $this->get_label('right');?></option>
	</select>
    </div>


	<div class="adblock_details_sub creditTextSettings" style="display: none;">
    <?php echo $this->get_label('credit position');?>
	</br>
	<select name="credit_text_positioning" size="1" id="credit_text_positioning" style="width: 125px;" onchange="LoadAdblockPreview(0);">
	<option value="1"><?php echo $this->get_label('top');?></option>
	<option value="0" selected="selected"><?php echo $this->get_label('bottom');?></option>
	</select>
    </div>
   



 
	<div class="adblock_details_sub fontDiv" style="display: none;">
	<?php echo $this->get_label('select font');?>
	</br>
	<select name="adblock_font" id="adblock_font" size="1" style="width: 125px;" onchange="LoadAdblockPreview(0);">

	<?php foreach($resFont as $fontKey => $fontValue){?>
	<option value="<?php echo $fontValue['id'];?>"><?php echo $fontValue['font_name'];?></option>
	<?php }?>

	</select>
	</div>


	<div class="adblock_details_sub themeDiv" style="display: none;">
	<?php echo $this->get_label('select theme');?>
	</br>
	<select name="adblock_theme" id="adblock_theme" size="1" style="width: 125px;" onchange="LoadAdblockPreview(0);">
	
	<?php foreach($resTheme as $themeKey => $themeValue){?>
	<option value="<?php echo $themeValue['id'];?>"><?php echo $themeValue['theme'];?></option>
	<?php }?>

	</select>
	</div>


<div class="adblock_details_sub adLayoutDiv">
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
			<i class="fa fa-laptop fa-1x layout-preview-icon" title="<?php echo $this->get_label('preview');?>"></i>
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

</td>
</tr>



</table>

<div class="notification adLayoutDiv"><?php echo $this->get_label("multiple layout choose notification");?></div>
</div>


<div style="margin-top: 10px;text-align: center;">
<input type="submit" name="submit" value="<?php echo $this->get_label('create adblock');?>">
</div>


<div>
<span class="previewButtonList previewTextButton" style="display: none;">
<input type="radio" name="previewRadio" id="previewRadio1" value="1" <?php if($text_ads_enabled == 1){?> checked="checked" <?php }?> onclick="LoadAdblockPreview(1);" />&nbsp;<?php echo $this->get_label('text');?>&nbsp;</span>

<span class="previewButtonList previewBannerButton" style="display: none;">
<input type="radio" name="previewRadio" id="previewRadio2" value="2" <?php if($text_ads_enabled == 0){?> checked="checked" <?php }?> onclick="LoadAdblockPreview(2);" />&nbsp;<?php echo $this->get_label('banner');?>&nbsp;</span>

<span class="previewButtonList previewTextImageButton" style="display: none;">
<input type="radio" name="previewRadio" id="previewRadio4" value="4" onclick="LoadAdblockPreview(4);" />&nbsp;<?php echo $this->get_label('textimage');?>&nbsp;</span>
</div>

<div><img id="load" src="images/load.gif" style="display: none;" /></div>
<div class="previewSection" ></div>

</div>
</td>
</tr>

   

</table>
</div>

<?php $form->end(); ?>
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

    	$('#radio_preview_'+id).prop("checked",true);

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

	ChangeSettings();
	LoadAspectRatio();
	LoadTextImagePosition();
	LoadAdblockPreview(0);	

	$('#width').blur(function()
	{
		if($('#width').val() > 0 && $('#height').val() > 0)
		{
			ChangeSettings();	
			LoadAdblockPreview(0);	
		}
		else 
		{
			$('.previewButtonList').hide();
			$('.previewSection').html("");
		}
	});

	$('#height').blur(function()
	{
		if($('#width').val() > 0 && $('#height').val() > 0)
		{
			ChangeSettings();	
			LoadAdblockPreview(0);	
		}
		else 
		{
			$('.previewButtonList').hide();	
			$('.previewSection').html("");
		}
	});
});

</script>