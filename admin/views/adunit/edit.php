<?php $this->dispatch("layout/header/2/_22");?>
<?php
$adcodeType    = $this->get_variable('adcodeType');   //Actually it is ad type column value //17 => feed 21 => Direct Link adcode
$adcodeFormat  = $this->get_variable('adcodeFormat');
$feed_auth_key = $this->get_variable('feed_auth_key'); //Feed auth key

$themeResultDefault = array();

$text_ads_enabled   = Configuration::get_instance()->read('text-ads_enabled');
$credittextDisplay  = Configuration::get_instance()->read('credit_text_display_mouse_hover');
$overrideTheme 			= Configuration::get_instance()->read('allow_publishers_to_override_theme');
$inpage_push_enabled = $this->get_addon_status('inpage-push-ads_enabled');


$resTheme    		  = $this->get_result('resTheme');
$resLayout        = $this->get_result('resLayout');
$resFontExternal  = $this->get_result("resFontExternal");

$res         		= $this->get_result('res');
$result      		= $res[0];

$native 			= intval($result['native']);
$pricingdata        = $result['display_type'];

if($native == 1)
$credittextDisplay  = 0; //Directly apply credit text always show in case of native ads

$custom_code 				= "";
$nativeimg_dimension 		= 0;
$nativeimg_position 		= 0;
$native_responsive_support 	= 0;
$native_layout_id 			= 0;
$native_ads_count 			= 0;
$native_ads_rows_count 		= 0;
$native_ads_column_count 	= 0;

$maximumAds       			= 1;
$maximumRows      			= 1;
$maximumColumns   			= 1;

if($native == 1)
{
	$custom_code 				= $result['custom_code'];
	$nativeimg_dimension 		= $result['nativeimg_dimension'];
	$nativeimg_position 		= $result['nativeimg_position'];
	$native_responsive_support 	= $result['responsive_support'];
	$native_layout_id 			= $result['native_layout_id'];
	$native_ads_count 			= $result['native_ads_count'];
	$native_ads_rows_count 		= $result['native_ads_rows_count'];
	$native_ads_column_count 	= $result['native_ads_column_count'];

	$maximumAds       = intval(Configuration::get_instance()->read("maximum_ads_allowed"));
	$maximumRows      = intval(Configuration::get_instance()->read("maximum_rows_allowed"));
	$maximumColumns   = intval(Configuration::get_instance()->read("maximum_columns_allowed"));

	if($native_ads_count > $maximumAds)
	$native_ads_count = $maximumAds;

	if($native_ads_rows_count > $maximumRows)
	$native_ads_rows_count = $maximumRows;

	if($native_ads_column_count > $maximumColumns)
	$native_ads_column_count = $maximumColumns;
}


/************ For avoide unwanted warnings ***********/
if(!isset($result['width']) || $result['width'] == "")
$result['width'] 	   = 0;

if(!isset($result['height']) || $result['height'] == "")
$result['height'] 	   = 0;

if(!isset($result['banner_type']) || $result['banner_type'] == "")
$result['banner_type'] = 0;

if(!isset($result['video_type']) || $result['video_type'] == "")
$result['video_type'] = 0;

if(!isset($result['textimage_size']) || $result['textimage_size'] == "")
$result['textimage_size'] = 0;
/************ For avoide unwanted warnings ***********/

$allow_inpage_push_ads = 0;

if($inpage_push_enabled == 1 && isset($result['allow_inpage_push_ads']))
$allow_inpage_push_ads = intval($result['allow_inpage_push_ads']);

$videoType          = intval($result['video_type']); //1=>Vast player, 2=>HTML5 player

if(($adcodeFormat == 13 && $videoType == 1) || $adcodeFormat == 17 || $adcodeFormat == 21)
$displayBase = $this->get_display_domain(1);
else
$displayBase = $this->get_display_domain(0);

if($native == 1)
$textimageSize      = intval($nativeimg_dimension);
else
$textimageSize      = intval($result['textimage_size']);

$themeRequired      = 0;
$diamensionAdcode   = 0;

if($native == 1)
$themeRequired      = 1;
else if($adcodeFormat == 17)
{
	$themeRequired      = 0;
	$diamensionAdcode   = 0;
}
else
{
	if($adcodeType == 1 || $adcodeType == 2 || $adcodeType == 3 || $adcodeType == 11 || ($adcodeType == 13 && $videoType == 2))
	{
		$themeRequired      = 1;
		$diamensionAdcode   = 1;
	}
}


if($overrideTheme == 1 && $themeRequired == 1){?>
<script type="text/javascript" charset="utf-8">
$(document).ready(function() {

	$('.color-picker').blur(function()
	{
		LoadAdcodePreview(0);
	});

});
</script>
<?php }

$category_enabled   	= $this->get_addon_status('category-targeting_enabled');
$sponsored_enabled  	= $this->get_addon_status('sponsored_enabled');
$sticky_enabled     	= $this->get_addon_status('sticky-ad-display_enabled');
$textimage_enabled  	= $this->get_addon_status('text-image-ads_enabled');

$pop_enabled        = $this->get_variable('pop_enabled');
$responsive_enabled = $this->get_variable('responsive_enabled');
$layoutID    		= $this->get_variable('layoutID');
$pricing            = $this->get_variable('pricing');
$aduid              = $this->get_variable('aduid');


$stringarray = array();
if($category_enabled == 1)
{
	$category_enabled_ads = Configuration::get_instance()->read('category_enabled_ads');

	if($category_enabled_ads != '')
	$stringarray = explode('_',$category_enabled_ads);
}

$siteCheckingPricing = $pricing;

if($adcodeType == 9)
$siteCheckingPricing = $adcodeType;

$uid 				= $result['pubid'];

$adLayoutID  = 0;

if($native == 1)
$adLayoutID  = $native_layout_id;
else
$adLayoutID  = $layoutID;

$sid 					= 0;
$block_id 				= 0;
$responsive_support 	= 0;

$linear_support         = $this->get_variable('linear_support');
$nonlinear_support      = $this->get_variable('nonlinear_support');
$html5_player_support   = $this->get_variable('html5_player_support');
$adcode_for             = $this->get_variable('adcode_for');
$linear                 = $this->get_variable('linear');

if($_POST)
{
	$aduname     		= $this->get_variable('aduname');
	$nonlinearbanner    = $this->get_variable('nonlinearbanner');
	$nonlineartext      = $this->get_variable('nonlineartext');
	$nonlinear_size     = $this->get_variable('nonlinear_size');

	$layoutTheme 		= $this->get_variable('layoutTheme');
	$borderType         = $this->get_variable("borderType");
	$sid                = $this->get_variable("sid");

	$pop_type           = $this->get_variable('pop_type');


	$responsive_support	= $this->get_variable('responsive_support');

	$sticky_support        = $this->get_variable('sticky_support');
	$sticky_position       = $this->get_variable('sticky_position');
	$sticky_close_position = $this->get_variable('sticky_close_position');


	if($sponsored_enabled == 1 && $pricing == 3)
	{
		$maximum_ads    = $this->get_variable('maximum_ads');
		$cpd_rate       = $this->get_variable('cpd_rate');
		$maximum_ads_db = $this->get_variable('maximum_ads_db');
		$cpd_rate_db    = $this->get_variable('cpd_rate_db');
	}
}
else
{
	if($sponsored_enabled == 1 && $pricing == 3)
	{
		$maximum_ads    = $result['maximum_ads'];
		$cpd_rate       = $result['cpd_rate'];
		$maximum_ads_db = $this->get_variable('maximum_ads_db');
		$cpd_rate_db    = $this->get_variable('cpd_rate_db');
	}


	$aduname            = $result['auname'];
	$borderType         = $result['abr_type'];
	$layoutTheme		= $result['adcode_theme'];

	if($category_enabled == 1)
	$sid                = $result['sid'];

	if($pop_enabled ==1)
	{
		if($result['pop_up_support'] == 1)
		$pop_type = 1;

		if($result['pop_tab_support'] == 1)
		$pop_type = 0;
	}
	else
	$pop_type = 0;

	$nonlinearbanner    = $result['non_linear_banner'];
	$nonlineartext      = $result['non_linear_text'];
	$nonlinear_size     = $result['player_size'];

	if($sticky_enabled == 1)
	{
		$sticky_support=$result['sticky_support'];
		$sticky_position=$result['sticky_position'];
		$sticky_close_position=$result['sticky_close_position'];
	}
	else
	{
		$sticky_support=0;
		$sticky_position='';
		$sticky_close_position=0;

	}

	if($responsive_enabled == 1)
	$responsive_support = $result['responsive_support'];
}


if($text_ads_enabled == 0 && $nonlineartext == 1)
$nonlineartext = 0;

if(isset($result['blockid']))
$adBlockID = intval($result['blockid']);
else
$adBlockID = 0;

if($responsive_enabled == 1 && $adBlockID > 0 && $adcodeType != 14)
{
	if($responsive_support > 0)
	$block_id = $adBlockID;

	$responsive_block = $this->get_responsive_block_enabled($adBlockID);
}


if($sticky_enabled ==1)
{
	$sticky_supported_positions=Configuration::get_instance()->read('sticky_supported_positions');

	$position_array=json_decode($sticky_supported_positions,1);

	if(!isset($position_array[$sticky_position]) || (isset($position_array[$sticky_position]) && $position_array[$sticky_position] ==0))
	{
		$sticky_support        = 0;
		$sticky_position       = '';
		$sticky_close_position = 0;
	}
}

$sticky_close_button_round   = "";
$sticky_close_button_square  = "";
if($sticky_support == 1)
{
	$sticky_close_button_round=Configuration::get_instance()->read('sticky_close_button_round');
	$sticky_close_button_square=Configuration::get_instance()->read('sticky_close_button_square');
}


$creditposition  = 0;
$creditalignment = 0;

if(isset($result['creditposition']))
$creditposition=$result['creditposition'];

if(isset($result['creditalignment']))
$creditalignment=$result['creditalignment'];



if($adcodeFormat != 17 && $textimage_enabled == 1 && $textimageSize > 0)
$textimageData	= 1;
else if($adcodeFormat == 17 && $textimage_enabled == 1)
$textimageData	= 1;
else
$textimageData  = 0;

$validate=array(
		"aduname"=>array(
				"notNull"=>array($this->get_message("not null"))
));
?>
<style type="text/css">
<?php foreach($resFontExternal as $fkey=>$fvalue){?>
@font-face {
  font-family: <?php echo $fvalue['slug_name'];?>;
  src: url(<?php echo BASE.DATA_DIR.'/fonts/'.$fvalue['file_name'];?>);
}
<?php } ?>

.previewSection
{
	margin-top: 0px;
}

#sid 
{
	width: 125px;
}
</style>


<script type="text/javascript">
function HidePreviewBox()
{
	if($('.previewSection').length > 0)
	{
		$('.previewSectionOuter').hide();
		$('.previewSection').html('');
	}

    if($('.stickyCloseDiv').length >0)
    $('.stickyCloseDiv').hide();
}


function LoadAdcodePreview(previewType)
{
	adcodeID      = <?php echo intval($aduid); ?>;
	adcodeType    = <?php echo intval($adcodeType);?>;
	adLayoutID    = <?php echo intval($adLayoutID);?>;
	adBlockID     = <?php echo intval($adBlockID); ?>;
	nativeAdcode  = <?php echo intval($native); ?>;

	if(nativeAdcode == 1)
	{
		adLayoutID    = $("#native_layout_id").val();
		nativeHeading = $('#aduname').val();
	}
	else
	{
		adLayoutID    = <?php echo intval($adLayoutID);?>;
		nativeHeading = "";
	}


	if(screen.width < 576)
	deviceType  = 'xsmall_dev_adblock';
	else if(screen.width >= 576 && screen.width < 768)
	deviceType  = 'small_dev_adblock';
	else if(screen.width >= 768 && screen.width < 992)
	deviceType  = 'medium_dev_adblock';
	else if(screen.width >= 992)
	deviceType  = 'large_dev_adblock';


	if(adLayoutID == 0 && (adcodeType == 1 || adcodeType == 3 || adcodeType == 11))
	{
		alert('<?php echo $this->get_message("no ad layout linked to this adblock");?>');

		$('.previewSectionOuter').hide();
		$('.previewSection').html('');
		return;
	}

	previewSectionOuterWidth = $('.previewSectionOuter').outerWidth();

	windowWidth       		 = $(window).width();
	windowHeight      		 = $(window).height();
	adcodeTheme   			 = $('#adcode_theme').val();
	borderType    			 = $('#borderType').val();


	textimageDimension      = 0;
	textimagePosition       = 0;
	nativeAdsCount          = 0;
	nativeRows              = 0;
	nativeColumns           = 0;


	if(nativeAdcode == 1)
	{
		responsiveSupport       = <?php echo intval($native_responsive_support); ?>;
		textimageDimension      = <?php echo intval($nativeimg_dimension); ?>;
		textimagePosition       = <?php echo intval($nativeimg_position); ?>;
		nativeAdsCount       	= <?php echo intval($native_ads_count); ?>;
		nativeRows       		= <?php echo intval($native_ads_rows_count); ?>;
		nativeColumns       	= <?php echo intval($native_ads_column_count); ?>;
	}
	else
	responsiveSupport       = $('#responsive_support').val();


	heading_color 	 	    = "";
	title_color 			= "";
	description_color 		= "";
	url_color 				= "";
	credit_text_color 		= "";
	cta_text_color 		 	= "";
	cta_button_color 		= "";
	border_color 			= "";

	title_hover_color 		= "";
	description_hover_color = "";
	url_hover_color 		= "";
	heading_hover_color 	= "";
	cta_hover_color 		= "";

	adcode_background 		= "";
	title_background 		= "";
	description_background  = "";
	url_background 		 	= "";
	heading_background 	 	= "";
	cta_background 		 	= "";
	image_background 		= "";


	if(adcodeTheme == -1)
	{
		if($('#heading_color').length > 0)
		heading_color 	 	    = $('#heading_color').val();

		if($('#title_color').length > 0)
		title_color 			= $('#title_color').val();

		if($('#description_color').length > 0)
		description_color 		= $('#description_color').val();

		if($('#url_color').length > 0)
		url_color 				= $('#url_color').val();

		if($('#credit_text_color').length > 0)
		credit_text_color 		= $('#credit_text_color').val();

		if($('#cta_text_color').length > 0)
		cta_text_color 		 	= $('#cta_text_color').val();

		if($('#cta_button_color').length > 0)
		cta_button_color 		= $('#cta_button_color').val();

		if($('#border_color').length > 0)
		border_color 			= $('#border_color').val();

		if($('#title_hover_color').length > 0)
		title_hover_color 		= $('#title_hover_color').val();

		if($('#description_hover_color').length > 0)
		description_hover_color = $('#description_hover_color').val();

		if($('#url_hover_color').length > 0)
		url_hover_color 		= $('#url_hover_color').val();

		if($('#heading_hover_color').length > 0)
		heading_hover_color 	= $('#heading_hover_color').val();

		if($('#cta_hover_color').length > 0)
		cta_hover_color 		= $('#cta_hover_color').val();

		if($('#adcode_background').length > 0)
		adcode_background 		= $('#adcode_background').val();

		if($('#title_background').length > 0)
		title_background 		= $('#title_background').val();

		if($('#description_background').length > 0)
		description_background  = $('#description_background').val();

		if($('#url_background').length > 0)
		url_background 		 	= $('#url_background').val();

		if($('#heading_background').length > 0)
		heading_background 	 	= $('#heading_background').val();

		if($('#cta_background').length > 0)
		cta_background 		 	= $('#cta_background').val();

		if($('#image_background').length > 0)
		image_background 		= $('#image_background').val();
	}


    stickySupport 			= 0;
	stickyClosePosition     = 0;
	stickyPosition          = "";


	if($('#sticky_support').length > 0 && $('#sticky_support').val() == 1)
    {
    	stickySupport 			= 1;
		stickyClosePosition     = $('#sticky_close_position').val();
		stickyPosition          = $('#sticky_position').val();
    }


	if(adcodeType == 3 && previewType == 0)
	{
		textAdsEnabled      = <?php echo $text_ads_enabled; ?>;
		textimageAdsEnabled = <?php echo $textimage_enabled; ?>;

		if(textAdsEnabled == 1)
		adcodeType = 1;
		else
		adcodeType = 2;
	}
	else if(previewType > 0)
	adcodeType        = previewType;

	$("#load").show();

	dataparam    = "adcodeID="+adcodeID+"&adcodeType="+adcodeType+"&adcodeTheme="+adcodeTheme+"&borderType="+borderType+"&adLayoutID="+adLayoutID+"&stickySupport="+stickySupport+"&stickyPosition="+stickyPosition+"&stickyClosePosition="+stickyClosePosition+"&windowWidth="+windowWidth+"&windowHeight="+windowHeight+"&deviceType="+deviceType+"&responsiveSupport="+responsiveSupport+"&adBlockID="+adBlockID+"&heading_color="+heading_color+"&title_color="+title_color+"&description_color="+description_color+"&url_color="+url_color+"&credit_text_color="+credit_text_color+"&cta_text_color="+cta_text_color+"&cta_button_color="+cta_button_color+"&border_color="+border_color+"&title_hover_color="+title_hover_color+"&description_hover_color="+description_hover_color+"&url_hover_color="+url_hover_color+"&heading_hover_color="+heading_hover_color+"&cta_hover_color="+cta_hover_color+"&adcode_background="+adcode_background+"&title_background="+title_background+"&description_background="+description_background+"&url_background="+url_background+"&heading_background="+heading_background+"&cta_background="+cta_background+"&image_background="+image_background+"&nativeAdcode="+nativeAdcode+"&textimageDimension="+textimageDimension+"&textimagePosition="+textimagePosition+"&nativeAdsCount="+nativeAdsCount+"&nativeRows="+nativeRows+"&nativeColumns="+nativeColumns+"&nativeHeading="+nativeHeading+"&previewSectionOuterWidth="+previewSectionOuterWidth;

	var urlvalue = '<?php echo $this->make_url("adunit/load_adcode_preview");?>';

	$.ajax(
	{
		type: "POST",
		data: dataparam,
		url: urlvalue,
		success: function(message)
		{
			$("#load").hide();

			if(message == 0)
			{
				$('.previewSection').html("");
				$('.previewSectionOuter').hide();
			}
			else
			{
            	$('.previewSection').html(message);

            	if($('#sticky_support').length > 0 && $('#sticky_support').val() == 1)
           		{
           			$('.previewSectionOuter').show();
           			$('.previewCloseDiv').hide();
           		}
            	else
            	{
            		$('.previewSectionOuter').show();
           		$('.previewCloseDiv').show();
           	}
            }
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
</script>

<div class="sub_menu_main"><?php echo $this->get_label('view ad code');?></div>

<?php if($result['pubid'] ==0){?>
<?php $this->dispatch("links/links/21");?>
<?php }?>

<div style="overflow: auto;background-color: #FFFFFF;">

<div class="adcode_div" style="overflow: auto;padding: 0px;">

<div class="adcode_details adcode-info" style="margin-bottom: 10px;">
<table style="width: 100%;" cellpadding="0" cellspacing="0">

<tr class="adcode_heading" id="adcode_head">
<td>
<?php
if($adcodeFormat == 17)
echo $this->get_label('feed url');
else if($adcodeFormat == 21)
echo $this->get_label('direct link');
else
echo $this->get_label('ad display code');

if($adcodeFormat == 21) 
$copyLabel = $this->get_label('copy direct link');
else
$copyLabel = $this->get_label('copy adcode');
?>

<div style="cursor: pointer; float: right;"><i class="fa fa-clipboard fa-md" aria-hidden="true" id="adcode"
			title="<?php echo $copyLabel;?>"></i></div>
</td>
</tr>

<tr id="adcode_view">
<td style="padding: 10px;">
<?php //For manage very old adcodes
if($adcodeFormat == 5 || $adcodeFormat == 9 || $adcodeFormat == 13 || $adcodeFormat == 14 || $adcodeFormat == 19)
$adcodeDisplay = $adcodeFormat;

else
$adcodeDisplay = $native;






$admarket_name=Configuration::get_instance()->read('admarket_name');
?>
<textarea id="textarea-adcode" style="border: 1px solid #CCCCCC; width: 99%; height: <?php if($adcodeFormat == 21){?>50px; <?php } else { ?> 75px; <?php } ?>"
			readonly="readonly"><?php if($adcodeFormat != 17 && $adcodeFormat != 21 && $videoType !=1){?>
<!-- <?php  echo $admarket_name;?> - <?php echo $this->get_label('ad code');?> -->
<?php }
if($adcodeFormat == 17){

	$bannerString = '';
	$iconString   = '';

	if($result['ad_type'] == 2 || $result['ad_type'] == 3 || $result['ad_type'] == 5)
	$bannerString = '&banner_size={banner_size}';

	if($result['ad_type'] == 3 || $result['ad_type'] == 11)
	$iconString   = '&icon_size={icon_size}';

echo $displayBase.DISPLAY_DIR."/index.php?page=query/feed/&feed=".$aduid."&auth=".$feed_auth_key."&pid=".$uid."&pricing=".$pricingdata."&user_ip={user_ip}&site={site}&type=".$result['ad_type'].$bannerString.$iconString."&keywords={keywords}&ua={ua}&count={count}";?>
<?php
}
else if($adcodeFormat == 21)
echo $displayBase.DISPLAY_DIR."/direct-link/".$aduid."/".$uid."/".$pricingdata; 
else if($adcodeFormat == 9){?>
<script data-cfasync="false" async type="text/javascript" src="<?php echo $displayBase.DISPLAY_DIR; ?>/items.php?<?php echo $aduid; ?>&<?php echo $uid; ?>&0&0&<?php echo $pricingdata;?>&<?php echo $adcodeDisplay;?>"></script>
<?php } 
else if($adcodeFormat == 13 && $videoType == 1)
echo $displayBase.DISPLAY_DIR.'/index.php?page=query/video/'.$this->get_variable('aduid').'/'.$uid;
else if($adcodeFormat == 13 && $videoType == 2){?>
<div id="adm-container-<?php echo $aduid;?>"></div><script data-cfasync="false" async type="text/javascript" src="<?php echo $displayBase.DISPLAY_DIR; ?>/items.php?<?php echo $aduid; ?>&<?php echo $uid; ?>&<?php echo $result['width'];?>&<?php echo $result['height'];?>&<?php echo $pricingdata;?>&<?php echo $adcodeDisplay;?>&<?php echo $block_id;?>"></script>
<?php } else { ?>
<div id="adm-container-<?php echo $aduid;?>"></div><script data-cfasync="false" async type="text/javascript" src="<?php echo $displayBase.DISPLAY_DIR; ?>/items.php?<?php echo $aduid; ?>&<?php echo $uid; ?>&<?php echo $result['width']; ?>&<?php echo $result['height']; ?>&<?php echo $pricingdata;?>&<?php echo $adcodeDisplay;?>&<?php echo $block_id;?>"></script>
<?php }?>
<?php if($adcodeFormat != 17 && $adcodeFormat !=21 && $videoType !=1){?>
<!-- <?php  echo $admarket_name;?> - <?php echo $this->get_label('ad code');?>  -->
<?php }?>
</textarea>
<?php if($videoType == 1){?>
<span class="notification" style="float: right;"><?php echo $this->get_label('copy the vast tag');?></span>
<?php }
if($adcodeFormat == 9){?>
<span class="notification" style="float: right;"><?php echo $this->get_label('multiple pop adcodes in a single page is not supported');?></span>
<?php }
if($adcodeFormat == 14){?>
<span class="notification" style="float: right;"><?php echo $this->get_label('multiple skin adcodes in a single page is not supported');?></span>
<?php }?>
</td>
</tr>
<?php 
if($adcodeFormat == 21)
{ 
	$copyLabelCustom     = $this->get_label('copy custom direct link');
	$copyLabelBackButton = $this->get_label('copy back button direct link');
?>
<tr class="adcode_heading">
<td><?php echo $this->get_label('customized direct link'); ?>
<div style="cursor: pointer; float: right;"><i class="fa fa-clipboard fa-md" aria-hidden="true" id="adcode-custom" title="<?php echo $copyLabelCustom;?>"></i></div>
</td>
</tr>
<tr class="adcode_heading">
<td>
<textarea id="textarea-custom-adcode" style="border: 1px solid #CCCCCC; width: 99%;margin:10px 0px;" rows="12" readonly="readonly">
<style type="text/css">
.action-button {
background: #000000;
color: #FFFFFF;
cursor: pointer;
padding: 10px;
border-radius: 5px;
}
</style>
<a href="<?php echo $displayBase.DISPLAY_DIR.'/direct-link/'.$this->get_variable('aduid')."/".$uid."/".$pricingdata; ?>" target="_blank"><button class="action-button">{REPLACE YOUR TEXT}</button></a>
</textarea>	
</td>
</tr>
<?php if($uid == 0){ ?>
<tr>
<td><span class="notification"><?php echo $this->get_label('direct link custom note');?></span></td>
</tr>
<?php } ?>

<?php /*
<tr class="adcode_heading">
<td><?php echo $this->get_label('back button direct link'); ?>
<div style="cursor: pointer; float: right;"><i class="fa fa-clipboard fa-md" aria-hidden="true" id="adcode-back-button" title="<?php echo $copyLabelBackButton;?>"></i></div>
</td>
</tr>
<tr class="adcode_heading">
<td>
	<?php 
	//window.location.href = ""; 
	?>

<textarea id="textarea-back-button-adcode" style="border: 1px solid #CCCCCC; width: 99%;margin:10px 0px;" rows="8" readonly="readonly">
<script type="text/javascript">
history.pushState(null, null, location.pathname);
window.addEventListener('popstate', function (event) 
{
	window.location.assign("<?php echo $displayBase.DISPLAY_DIR.'/direct-link/'.$this->get_variable('aduid')."/".$uid."/".$pricingdata; ?>");
});
</script>
</textarea>	
</td>
</tr>

<tr>
<td><span class="notification"><?php echo $this->get_label('direct link back button note');?></span></td>
</tr>
*/ ?>


<?php }  ?>
</table>
</div>

<?php
$form=$this->create_form();
$form->start("editadunit",$this->make_url("adunit/edit"),"post",$validate);
?>
<div class="adcode_details adcode-info" style="margin-bottom: 10px;">
<table style="width: 100%;" cellpadding="0" cellspacing="0">



<tr class="adcode_heading">
<td colspan="8">
<?php echo $this->get_label('basic settings');?>

<div class="button-preview">
<?php if($adcodeType == 3 && ($text_ads_enabled == 1 || ($textimage_enabled == 1 && $textimageSize > 0))){?>

<?php if($text_ads_enabled == 1){?>
<a class="link_button" onclick="LoadAdcodePreview(1);"><?php echo $this->get_label('text preview');?></a>
<?php }?>

<a class="link_button" onclick="LoadAdcodePreview(2);"><?php echo $this->get_label('banner preview');?></a>

<?php if($textimage_enabled == 1 && $textimageSize > 0){?>
<a class="link_button" onclick="LoadAdcodePreview(11);"><?php echo $this->get_label('textimage preview');?></a>
<?php } ?>

<?php }else{

if($adcodeType != 9 && $adcodeType != 14 && $adcodeFormat != 17 && $adcodeFormat != 21 && ($adcodeType != 13 || ($adcodeType == 13 && $videoType == 2))){?>
<a class="link_button" onclick="LoadAdcodePreview(<?php echo $adcodeType; ?>);"><?php echo $this->get_label('preview');?></a>
<?php } }?>
</div>
</td>
</tr>

<tr >
<td colspan="8" style="height: 10px;">
<div><img id="load" src="images/load.gif" style="display: none;" /></div>

<div class="previewSectionOuter" style="width: 100%;">
<div class="previewCloseDiv" onclick="HideLayoutPreview();"><span>x</span></div>
<div class="previewSection" ></div>
</div>

<span>
<input type="hidden" name="adpricing" id="adpricing" value="<?php echo $result['display_type'];?>" />
<input type="hidden" name="aduid" id="aduid" value="<?php echo $aduid; ?>" />
<input type="hidden" name="adtype" id="adtype" value="<?php echo $result['type']; ?>" />
<input type="hidden" name="adcode_for" id="adcode_for" value="<?php echo $videoType; ?>" />
</span>

</td>
</tr>
<tr>
<td>
<div class="adcode_details_sub">
<?php
if($native == 1)
echo $this->get_label('header text');
else
echo $this->get_label('name');
?><span class="compulsory">*</span></br>
<input type="text" name="aduname" id="aduname" value="<?php echo $aduname; ?>" style="width: 90%; height: 24px;" maxlength="25" <?php if($native == 1){?> onblur="LoadAdcodePreview(0);" <?php } ?> />
</div>


<div class="adcode_details_sub">
<?php echo $this->get_label('pricing');?>
<div style="margin-top: 10px;"><?php echo $this->get_adunit_preference($result['display_type'],$adcodeType);?></div>
</div>

<?php if($adcodeFormat != 17 && $adcodeFormat != 21 && ($adcodeType == 1 || $adcodeType == 2 || $adcodeType == 3 || $adcodeType == 9 || $adcodeType == 11 || $adcodeType == 13 || $adcodeType == 14) && $category_enabled == 1 && in_array($siteCheckingPricing, $stringarray)){?>
	<div class="adcode_details_sub site-class">
	<?php echo $this->get_label('targeting site');?><span class="compulsory">*</span></br>
	<?php
	if($result['display_type'] != 3)
	echo CategoryHelper::get_site_dropdown($result['pubid'],$sid);
	else {
	echo '<div style="margin-top: 10px;">'.CategoryHelper::get_site_name($sid).'</div>';
	?>
	<input type="hidden" name="sid" id="sid" value="<?php echo $sid;?>" />
	<?php }?>
	</div>
<?php }?>

<?php
if($result['display_type'] == 3)
{
	if($maximum_ads == 0)
	$maximum_ads = $maximum_ads_db;

	if($cpd_rate == 0)
	$cpd_rate = $cpd_rate_db;
	?>
<div class="adcode_details_sub"><?php echo $this->get_label('allowed ads count');?><span class="compulsory">*</span></br>
<input type="text" name="maximum_ads" id="maximum_ads" value="<?php echo $maximum_ads;?>" onkeyup="this.value=this.value.replace(/[^0-9]/g, '');" />

<div class="notification"><?php echo $this->get_label('maximum ads allowed',array('x'=>$maximum_ads_db)); ?></div>
</div>

<div class="adcode_details_sub"><?php echo $this->get_label('cpd rate');?> ( <?php echo Configuration::get_instance()->read('currency_symbol');?> )<span class="compulsory">*</span></br>
<input type="text" name="cpd_rate" id="cpd_rate" value="<?php echo $cpd_rate;?>" onkeyup="this.value=this.value.replace(/[^0-9\.]/g, '');" />
<div class="notification"><?php echo $this->get_label('minimum cpd rate is',array('x'=>$this->get_money_format($cpd_rate_db))); ?></div>
</div>
<?php }?>

<div class="adcode_details_sub"><?php echo $this->get_label('adcode type');?>
<div style="margin-top: 10px;">
    <?php echo $this->get_adcode_type_value($result['ad_type'], $textimageData, $result['banner_type'], $result['adcode_type']);?>
</div>
</div>


<?php if($diamensionAdcode == 1){?>
	<div class="adcode_details_sub">
	<?php echo $this->get_label('adcode dimensions');?> (<?php echo $this->get_label('px');?>)
	<div style="margin-top: 10px;"><?php echo $result['width']; ?> x <?php echo $result['height']; ?></div>
	</div>
<?php }?>


<?php if($textimage_enabled == 1 && ($adcodeType == 3 || $adcodeType == 11) && $textimageSize > 0){?>

<div class="adcode_details_sub">
	<?php echo $this->get_label('textimage size');?>  (<?php echo $this->get_label('px');?>)
	<div style="margin-top: 10px;">
	<?php
	$data=$this->get_banner_dimension($textimageSize);

	$dataarray=explode('-',$data);

	$bannerwidth=intval($dataarray[0]);
	$bannerheight=intval($dataarray[1]);

	echo $bannerwidth." x ".$bannerheight;
	?>
	</div>
</div>

<div class="adcode_details_sub">
	<?php echo $this->get_label('image position');?>
	<div style="margin-top: 10px;">
	<?php
	if($result['image_position']==0)
	echo $this->get_label('left');
	else if($result['image_position']==1)
	echo $this->get_label('top');
	else if($result['image_position']==2)
	echo $this->get_label('right');
	else if($result['image_position']==3)
	echo $this->get_label('bottom');
	?>
	</div>
</div>

<?php } ?>


<?php if($adcodeFormat != 17 && ($adcodeType == 1 || $adcodeType == 3 || $adcodeType == 11)){?>
<div class="adcode_details_sub">
	<?php echo $this->get_label('border type');?></br>
	<select name="borderType" id="borderType" style="width: 125px;" onchange="LoadAdcodePreview(0);">
	<option value="1" <?php if($borderType == 1){echo "selected";}?>><?php echo $this->get_label('regular');?></option>
	<option value="0" <?php if($borderType == 0){echo "selected";}?>><?php echo $this->get_label('rounded');?></option>
	<?php if($native == 1){?>
	<option value="2" <?php if($borderType == 2){?> selected="selected" <?php }?>><?php echo $this->get_label('no border');?></option>
	<?php } ?>
	</select>
	</div>
<?php }?>


<?php if($pricing != 3 && $native == 0 && $diamensionAdcode == 1 && $responsive_enabled == 1 && $responsive_block == 1){?>

<div class="adcode_details_sub">
	<?php echo $this->get_label('responsive');?></br>
	<select name="responsive_support" id="responsive_support" style="width: 125px;" onchange="LoadResponsiveAdblocks();LoadAdcodePreview(0);">
	<option value="0" <?php if($responsive_support == 0){?> selected="selected" <?php }?>><?php echo $this->get_label('no');?></option>
	<option value="1" <?php if($responsive_support == 1){?> selected="selected" <?php }?>><?php echo $this->get_label('yes');?></option>
	</select>
</div>
<?php } else if($native == 1){ ?>

<div class="adcode_details_sub">
	<?php echo $this->get_label('responsive');?>
	<div style="margin-top: 10px;">
	<?php
	if($native_responsive_support == 0)
	echo $this->get_label('no');
	else if($native_responsive_support == 1)
	echo $this->get_label('yes');
	?>
	</div>
</div>


<?php if($native_responsive_support == 1){?>
<div class="adcode_details_sub">
	<?php echo $this->get_label('allowed ads');?>
	<div style="margin-top: 10px;">
	<?php echo $native_ads_count;?>
	</div>
</div>
<?php } ?>


<?php if($native_responsive_support == 0){?>
<div class="adcode_details_sub">
	<?php echo $this->get_label('rows columns');?>
	<div style="margin-top: 10px;">
	<?php echo $native_ads_rows_count.' x '.$native_ads_column_count;?>
	</div>
</div>
<?php } ?>

<?php }else{?>
<input type="hidden" name="responsive_support" id="responsive_support" value="0" />
<?php }?>


<?php
if($adcodeType == 9)
{
	$pop_ads_support = Configuration::get_instance()->read('pop_ads_support');

	$pop_array       = explode('-',$pop_ads_support);
	$pop_up          = intval($pop_array[0]);
	$pop_tab         = intval($pop_array[1]);
	?>

<div class="adcode_details_sub pop_div" style="margin-bottom: 10px;">
	<?php echo $this->get_label('pop type');?></br>
	<select name="pop_type" id="pop_type" style="width: 125px;">
		<?php if($pop_tab == 1){?>
			<option value="0" <?php if($pop_type == 0) {echo "selected";}?>><?php echo $this->get_label('poptab');?></option>
		<?php }?>

		<?php if($pop_up == 1){?>
			<option value="1" <?php if($pop_type == 1) {echo "selected";}?>><?php echo $this->get_label('popup');?></option>
		<?php }?>
	</select>
</div>
<?php } ?>

<?php if($adcodeType == 14){?>
<div class="adcode_details_sub">
	<?php echo $this->get_label('skin layout');?></br>
	<?php
		$skin_position1  = html_entity_decode($result['skin_positions']);
		$skin_position   = json_decode($skin_position1,1);
		$left            = $skin_position['L'] == 1 ? 'L' : 0;
		$right           = $skin_position['R'] == 1 ? 'R' : 0;
		$top             = $skin_position['T'] == 1 ? 'T' : 0;
		$bottom          = $skin_position['B'] == 1 ? 'B' : 0;

		$image_name      = $left."_".$right."_".$top."_".$bottom.".png";
	?>
	<img src='<?php echo BASE.ADDON_DIR."/skin-ads/images/".$image_name;?>' />
</div>

<div class="adcode_details_sub" style="width: 60%;">
	<?php echo $this->get_label('main container id');?></br>
	<input type="text" name="container_id" id="container_id" value="<?php if($_POST) echo $container_id; else echo $result['container_id'];?>" style="width: 100px; height: 23px;" />
	<div class="notification"><?php echo $this->get_label('main container id description'); ?></div>
</div>
<?php }?>

<?php if($adcodeType == 13){?>
<div class="adcode_details_sub">
	<?php echo $this->get_label('adcode for');?>
	<div style="margin-top: 10px;">
	<?php
	if($videoType ==1)
	echo $this->get_label('vast player');
	else if($videoType ==2)
	echo $this->get_label('html5 player');
	?>
	</div>
</div>

<?php
if($videoType ==1 && ($linear_support ==1 || $nonlinear_support ==1))
{
	$res14 = $this->get_result('res14');
	?>
	<div class="adcode_details_sub" style="margin-bottom: 10px;">
	&nbsp;<?php echo $this->get_label('supported type');?><span class="compulsory">*</span></br>

	<div style="width: 100%; height: 20px;">
	<?php if($linear_support ==1){?>
		<div style="float: left;">
		<input disabled="disabled" type="checkbox" name="linear" id="linear" value="1" checked="checked" /> <?php echo $this->get_label('linear');?>&nbsp;&nbsp;
		</div>
	<?php }
	if($nonlinear_support ==1)
	{
		if($text_ads_enabled ==1){?>
		<div style="float: left;">
		<input type="checkbox" name="nonlineartext" id="nonlineartext" value="1" <?php if($nonlineartext ==1){?>checked="checked" <?php }?> /> <?php echo $this->get_label('non linear text');?>&nbsp;&nbsp;
		</div>
		<?php }?>
		<?php if(count($res14) >0){?>
		<div style="float: left;">
		<input type="checkbox" name="nonlinearbanner" id="nonlinearbanner" value="1" <?php if($nonlinearbanner ==1){?> checked="checked" <?php }?> onclick="LoadVideoOptions();" /> <?php echo $this->get_label('non linear banner');?>
		</div>
		<?php }
	}?>
    </div>
</div>

<?php if($nonlinear_support ==1 && count($res14) >0){?>
<div class="adcode_details_sub video-option-vast-size" style="display: none;"><?php echo $this->get_label('banner dimension');?></br>

	<?php if($nonlinear_size ==0){?>
	<select name="nonlinear_size" id="nonlinear_size" style="width: 125px;">
	<?php
	foreach($res14 as $key=>$result14)
	{
		$height=$result14['height'];
		$width=$result14['width'];
		$id=$result14['id'];
		$diamensions=$result14['width']." x ".$result14['height'];
		?>
		<option value="<?php echo $id;?>"
		<?php if($nonlinear_size == $id) { echo "selected"; }?>><?php echo $diamensions; ?></option>
		<?php }?>
	</select>
<?php } else {

			$size_data  = $this->get_banner_dimension($nonlinear_size);
			$size_array = explode('-',$size_data);

			echo '<div style="margin-top: 10px;">'.$size_array[0].' x '.$size_array[1].'</div>';
			?>
			<input type="hidden" name="nonlinear_size" id="nonlinear_size" value="<?php echo $nonlinear_size;?>" /> <?php }?>
		</div>
	<?php }
	}
	}
	
	if($sticky_enabled ==1 && $adcodeFormat != 5 && $adcodeFormat != 17 && $adcodeFormat != 19 && $adcodeFormat != 21 && $native == 0 && (($adcodeType == 1 || $adcodeType == 2 || $adcodeType == 3 || $adcodeType == 11) || ($adcodeType == 13 && $videoType == 2))){?>

		<div class="adcode_details_sub sticky_support" id="sticky_support_div" style="display: none;"><?php echo $this->get_label('sticky adcode'); ?><br />
			<select class="form-control" name="sticky_support" id="sticky_support" style="width: 125px;" onchange="ShowStickyPosition();LoadAdcodePreview(0);">
			<option value="0" <?php if($sticky_support == 0){?> selected="selected" <?php }?>><?php echo $this->get_label('no');?></option>
			<option value="1" <?php if($sticky_support == 1){?> selected="selected" <?php }?>><?php echo $this->get_label('yes');?></option>
			</select>
		</div>


		<div class="adcode_details_sub sticky_support" id="sticky_position_div" style="display: none;">
			<?php echo $this->get_label('sticky position'); ?><br />
			<select name="sticky_position" id="sticky_position" style="width: 125px;" onchange="LoadAdcodePreview(0);">
			<?php
			foreach($position_array as $poskey=>$posvalue)
			{
				if($posvalue ==1){?>
				<option value="<?php echo $poskey;?>" <?php if($sticky_position == $poskey){?> selected="selected" <?php }?>><?php echo $this->get_label(strtolower($poskey));?></option>
				<?php
				}
			}
			?>
			</select>
		</div>


		<div class="adcode_details_sub sticky_support" id="sticky_close_div" style="display: none;"><?php echo $this->get_label('sticky close position'); ?><br />
			<select class="form-control" name="sticky_close_position" id="sticky_close_position" style="width: 125px;" onchange="LoadAdcodePreview(0);">
			<option value="0" <?php if($sticky_close_position == 0){?> selected="selected" <?php }?>><?php echo $this->get_label('no');?></option>
			<option value="1" <?php if($sticky_close_position == 1){?> selected="selected" <?php }?>><?php echo $this->get_label('yes');?></option>
			</select>
		</div>
	<?php }?>

	<?php if($themeRequired == 1){?>

	<div class="adcode_details_sub">
		<?php echo $this->get_label('adcode theme');?></br>
		<select name="adcode_theme" id="adcode_theme" style="width: 125px;" onchange="<?php if($overrideTheme == 1){?>LoadThemeSettings();<?php } ?>LoadAdcodePreview(0);">

		<?php if($adcodeType == 1 || $adcodeType == 3 || $adcodeType == 11){?>
		<option value="0" <?php if($layoutTheme == 0){?> selected="selected" <?php }?> ><?php echo $this->get_label('auto detect');?></option>
		<?php } ?>

		<?php
		foreach($resTheme as $tKey=>$tValue)
		{
			if($tValue['id'] == 1) //Default theme data set to an array
			{
				$themeResultDefault["heading_color"] 		   = $tValue["heading_color"];
				$themeResultDefault["title_color"] 			   = $tValue["title"];
				$themeResultDefault["description_color"] 	   = $tValue["description"];
				$themeResultDefault["url_color"] 			   = $tValue["url"];
				$themeResultDefault["credit_text_color"] 	   = $tValue["credit"];
				$themeResultDefault["cta_text_color"] 		   = $tValue["button"];
				$themeResultDefault["cta_button_color"] 	   = $tValue["button_background"];
				$themeResultDefault["border_color"] 		   = $tValue["border"];
				$themeResultDefault["title_hover_color"] 	   = $tValue["title_hover_color"];
				$themeResultDefault["description_hover_color"] = $tValue["description_hover_color"];
				$themeResultDefault["url_hover_color"] 		   = $tValue["url_hover_color"];
				$themeResultDefault["heading_hover_color"] 	   = $tValue["heading_hover_color"];
				$themeResultDefault["cta_hover_color"] 		   = $tValue["button_hover"];
				$themeResultDefault["adcode_background"] 	   = $tValue["background"];
				$themeResultDefault["title_background"] 	   = $tValue["title_background"];
				$themeResultDefault["description_background"]  = $tValue["description_background"];
				$themeResultDefault["url_background"] 		   = $tValue["url_background"];
				$themeResultDefault["heading_background"] 	   = $tValue["heading_background"];
				$themeResultDefault["cta_background"] 		   = $tValue["cta_background"];
				$themeResultDefault["image_background"] 	   = $tValue["image_background"];
			}

			?>
		<option value="<?php echo $tValue['id'];?>" <?php if($layoutTheme == $tValue['id']){?> selected="selected" <?php }?> ><?php echo $tValue['theme'];?></option>
		<?php } ?>

		<?php if($overrideTheme == 1){?>
		<option value="-1" <?php if($layoutTheme == -1){?> selected="selected" <?php }?> ><?php echo $this->get_label('custom theme');?></option>
		<?php } ?>
		</select>
	</div>

	<?php }else{ ?>
	<span>
	<input type="hidden" name="adcode_theme" id="adcode_theme" value="0" />
	</span>
	<?php }?>


	<?php if($native == 1){ ?>

	<div class="adcode_details_sub">
		<?php echo $this->get_label('choose ad layout');?>
		<div style="margin-top: 10px;">
		<select name="native_layout_id" id="native_layout_id" onchange="LoadAdcodePreview(0);" style="width: 125px;">
		<?php foreach($resLayout as $layoutKey => $layoutValue){?>
		<option value="<?php echo $layoutValue['id']; ?>" <?php if($native_layout_id == $layoutValue['id']){?> selected="selected" <?php }?>><?php echo $layoutValue['layout'];?></option>
		<?php }?>
		</select>
		</div>
	</div>

	<div class="adcode_details_sub">
		<?php echo $this->get_label('custom css');?>
		<div style="margin-top: 10px;">
		<textarea  style="height:30px !important;width: 300px;"; name="custom_code" id="custom_code" ><?php echo $custom_code;?></textarea>
		</div>
	</div>
	<?php }?>
    </td>
	</tr>

	<tr class="themeSettings" style="display: none;">
	<td>

	<?php
	if($overrideTheme == 1 && $themeRequired == 1)
	{
		if($_POST)
		{
			$heading_color 	         = $this->get_variable("heading_color");
			$title_color 			 = $this->get_variable("title_color");
			$description_color 		 = $this->get_variable("description_color");
			$url_color 				 = $this->get_variable("url_color");
			$credit_text_color 		 = $this->get_variable("credit_text_color");
			$cta_text_color 		 = $this->get_variable("cta_text_color");
			$cta_button_color 		 = $this->get_variable("cta_button_color");
			$border_color 			 = $this->get_variable("border_color");

			$title_hover_color 		 = $this->get_variable("title_hover_color");
			$description_hover_color = $this->get_variable("description_hover_color");
			$url_hover_color 		 = $this->get_variable("url_hover_color");
			$heading_hover_color 	 = $this->get_variable("heading_hover_color");
			$cta_hover_color 		 = $this->get_variable("cta_hover_color");

			$adcode_background 		 = $this->get_variable("adcode_background");
			$title_background 		 = $this->get_variable("title_background");
			$description_background  = $this->get_variable("description_background");
			$url_background 		 = $this->get_variable("url_background");
			$heading_background 	 = $this->get_variable("heading_background");
			$cta_background 		 = $this->get_variable("cta_background");
			$image_background 		 = $this->get_variable("image_background");
		}
		else
		{
			$heading_color      	 = $result["heading_color"];
			$title_color 			 = $result["title_color"];
			$description_color 		 = $result["description_color"];
			$url_color 				 = $result["url_color"];
			$credit_text_color 		 = $result["credit_text_color"];
			$cta_text_color 		 = $result["cta_text_color"];
			$cta_button_color 		 = $result["cta_button_color"];
			$border_color 			 = $result["border_color"];

			$title_hover_color 		 = $result["title_hover_color"];
			$description_hover_color = $result["description_hover_color"];
			$url_hover_color 		 = $result["url_hover_color"];
			$heading_hover_color 	 = $result["heading_hover_color"];
			$cta_hover_color 		 = $result["cta_hover_color"];

			$adcode_background 		 = $result["adcode_background"];
			$title_background 		 = $result["title_background"];
			$description_background  = $result["description_background"];
			$url_background 		 = $result["url_background"];
			$heading_background 	 = $result["heading_background"];
			$cta_background 		 = $result["cta_background"];
			$image_background 		 = $result["image_background"];
		}


		if($heading_color == "" && isset($themeResultDefault['heading_color']))
		$heading_color 	         = $themeResultDefault['heading_color'];

		if($title_color == "" && isset($themeResultDefault['title_color']))
		$title_color 			 = $themeResultDefault['title_color'];

		if($description_color == "" && isset($themeResultDefault['description_color']))
		$description_color 		 = $themeResultDefault['description_color'];

		if($url_color == "" && isset($themeResultDefault['url_color']))
		$url_color 				 = $themeResultDefault['url_color'];

		if($credit_text_color == "" && isset($themeResultDefault['credit_text_color']))
		$credit_text_color 		 = $themeResultDefault['credit_text_color'];

		if($cta_text_color == "" && isset($themeResultDefault['cta_text_color']))
		$cta_text_color 		 = $themeResultDefault['cta_text_color'];

		if($cta_button_color == "" && isset($themeResultDefault['cta_button_color']))
		$cta_button_color 		 = $themeResultDefault['cta_button_color'];

		if($border_color == "" && isset($themeResultDefault['border_color']))
		$border_color 			 = $themeResultDefault['border_color'];

		if($title_hover_color == "" && isset($themeResultDefault['title_hover_color']))
		$title_hover_color 		 = $themeResultDefault['title_hover_color'];

		if($description_hover_color == "" && isset($themeResultDefault['description_hover_color']))
		$description_hover_color = $themeResultDefault['description_hover_color'];

		if($url_hover_color == "" && isset($themeResultDefault['url_hover_color']))
		$url_hover_color 		 = $themeResultDefault['url_hover_color'];

		if($heading_hover_color == "" && isset($themeResultDefault['heading_hover_color']))
		$heading_hover_color 	 = $themeResultDefault['heading_hover_color'];

		if($cta_hover_color == "" && isset($themeResultDefault['cta_hover_color']))
		$cta_hover_color 		 = $themeResultDefault['cta_hover_color'];

		if($adcode_background == "" && isset($themeResultDefault['adcode_background']))
		$adcode_background 		 = $themeResultDefault['adcode_background'];

		if($title_background == "" && isset($themeResultDefault['title_background']))
		$title_background 		 = $themeResultDefault['title_background'];

		if($description_background == "" && isset($themeResultDefault['description_background']))
		$description_background  = $themeResultDefault['description_background'];

		if($url_background == "" && isset($themeResultDefault['url_background']))
		$url_background 		 = $themeResultDefault['url_background'];

		if($heading_background == "" && isset($themeResultDefault['heading_background']))
		$heading_background 	 = $themeResultDefault['heading_background'];

		if($cta_background == "" && isset($themeResultDefault['cta_background']))
		$cta_background 		 = $themeResultDefault['cta_background'];

		if($image_background == "" && isset($themeResultDefault['image_background']))
		$image_background 		 = $themeResultDefault['image_background'];

	?>
	<table class="data_table" cellpadding="0" cellspacing="0" style="margin-bottom: 10px;">
	<tr class="row_heading_tr">
	<td></td>
	<td><?php echo $this->get_label('color');?></td>
	<td><?php echo $this->get_label('hover');?></td>
	<td><?php echo $this->get_label('background');?></td>
	</tr>


	<?php if(($adcodeType == 1 || $adcodeType == 11) && $native == 1){?>
	<tr class="row_data_tr">
	<td><?php echo $this->get_label('heading');?></td>
	<td>
	<input type="color" name="heading_color" id="heading_color" class="color-picker" value="<?php echo $heading_color;?>" onkeyup="LoadAdcodePreview(0);" />
	</td>
	<td>
	<input type="color" name="heading_hover_color" id="heading_hover_color" class="color-picker" value="<?php echo $heading_hover_color;?>" onkeyup="LoadAdcodePreview(0);" />
	</td>
	<td>
	<input type="color" name="heading_background" id="heading_background" class="color-picker" value="<?php echo $heading_background;?>" onkeyup="LoadAdcodePreview(0);" />
	</td>
	</tr>
	<?php } ?>


	<?php if($adcodeType == 1 || $adcodeType == 3 || $adcodeType == 11){?>
	<tr class="row_data_tr">
	<td><?php echo $this->get_label('ads title');?></td>
	<td>
	<input type="color" name="title_color" id="title_color" class="color-picker" value="<?php echo $title_color;?>" onkeyup="LoadAdcodePreview(0);" />
	</td>
	<td>
	<input type="color" name="title_hover_color" id="title_hover_color" class="color-picker" value="<?php echo $title_hover_color;?>" onkeyup="LoadAdcodePreview(0);" />
	</td>
	<td>
	<input type="color" name="title_background" id="title_background" class="color-picker" value="<?php echo $title_background;?>" onkeyup="LoadAdcodePreview(0);" />
	</td>
	</tr>

	<tr class="row_data_tr">
	<td><?php echo $this->get_label('description');?></td>
	<td>
	<input type="color" name="description_color" id="description_color" class="color-picker" value="<?php echo $description_color;?>" onkeyup="LoadAdcodePreview(0);" />
	</td>
	<td>
	<input type="color" name="description_hover_color" id="description_hover_color" class="color-picker" value="<?php echo $description_hover_color;?>" onkeyup="LoadAdcodePreview(0);" />
	</td>
	<td>
	<input type="color" name="description_background" id="description_background" class="color-picker" value="<?php echo $description_background;?>" onkeyup="LoadAdcodePreview(0);" />
	</td>
	</tr>

	<tr class="row_data_tr">
	<td><?php echo $this->get_label('display url');?></td>
	<td>
	<input type="color" name="url_color" id="url_color" class="color-picker" value="<?php echo $url_color;?>" onkeyup="LoadAdcodePreview(0);" />
	</td>
	<td>
	<input type="color" name="url_hover_color" id="url_hover_color" class="color-picker" value="<?php echo $url_hover_color;?>" onkeyup="LoadAdcodePreview(0);" />
	</td>
	<td>
	<input type="color" name="url_background" id="url_background" class="color-picker" value="<?php echo $url_background;?>" onkeyup="LoadAdcodePreview(0);" />
	</td>
	</tr>
	<?php } ?>

	<tr class="row_data_tr">
	<td><?php echo $this->get_label('credit text');?></td>
	<td>
	<input type="color" name="credit_text_color" id="credit_text_color" class="color-picker" value="<?php echo $credit_text_color;?>" onkeyup="LoadAdcodePreview(0);" />
	</td>
	<td>-</td>
	<td>-</td>
	</tr>


	<?php if($adcodeType == 1 || $adcodeType == 3 || $adcodeType == 11){?>
	<tr class="row_data_tr">
	<td><?php echo $this->get_label('cta text');?></td>
	<td>
	<input type="color" name="cta_text_color" id="cta_text_color" class="color-picker" value="<?php echo $cta_text_color;?>" onkeyup="LoadAdcodePreview(0);" />
	</td>
	<td>-</td>
	<td>-</td>
	</tr>


	<tr class="row_data_tr">
	<td><?php echo $this->get_label('cta button');?></td>
	<td>
	<input type="color" name="cta_button_color" id="cta_button_color" class="color-picker" value="<?php echo $cta_button_color;?>" onkeyup="LoadAdcodePreview(0);" />
	</td>
	<td>
	<input type="color" name="cta_hover_color" id="cta_hover_color" class="color-picker" value="<?php echo $cta_hover_color;?>" onkeyup="LoadAdcodePreview(0);" />
	</td>
	<td>
	<input type="color" name="cta_background" id="cta_background" class="color-picker" value="<?php echo $cta_background;?>" onkeyup="LoadAdcodePreview(0);" />
	</td>
	</tr>
	<?php } ?>

	<tr class="row_data_tr">
	<td><?php echo $this->get_label('adcode border');?></td>
	<td>
	<input type="color" name="border_color" id="border_color" class="color-picker" value="<?php echo $border_color;?>" onkeyup="LoadAdcodePreview(0);" />
	</td>
	<td>-</td>
	<td>-</td>
	</tr>

	<tr class="row_data_tr">
	<td><?php echo $this->get_label('adcode');?></td>
	<td>-</td>
	<td>-</td>
	<td>
	<input type="color" name="adcode_background" id="adcode_background" class="color-picker" value="<?php echo $adcode_background;?>" onkeyup="LoadAdcodePreview(0);" />
	</td>
	</tr>

	<?php if(($adcodeType == 3 || $adcodeType == 11) && $textimage_enabled == 1 && $textimageSize > 0){?>
	<tr class="row_data_tr">
	<td><?php echo $this->get_label('text+image');?></td>
	<td>-</td>
	<td>-</td>
	<td>
	<input type="color" name="image_background" id="image_background" class="color-picker" value="<?php echo $image_background;?>" onkeyup="LoadAdcodePreview(0);" />
	</td>
	</tr>
	<?php } ?>

	</table>

	<?php } ?>
	</td>
	</tr>

	<?php if($pricing != 3 && $diamensionAdcode == 1 && $responsive_enabled == 1 && $responsive_block == 1){?>
	<tr class="responsive-size" style="display: none;">
	<td style="border-top: 1px solid #CCCCCC; padding: 15px 5px;">

	<div>
	<h2 style="font-size: 16px; font-weight: bold;height: 30px;"><?php echo $this->get_label('responsive adblock dimensions');?></h2>
	</div>

	<div style="width: 50%;float: left;">
	<div style="height: 30px;">
	<div style="width: 110px;float: left;">
	<?php echo $this->get_label('large device');?>
	</div>
	: <?php if($result['large_dev_adblock'] > 0) echo $this->get_adblock_name($result['large_dev_adblock']); else echo $this->get_label('responsive adblock not assigned');?>
	</div>

	<div style="height: 30px;">
	<div style="width: 110px;float: left;">
	<?php echo $this->get_label('medium device');?>
	</div>
	: <?php if($result['medium_dev_adblock'] > 0) echo $this->get_adblock_name($result['medium_dev_adblock']); else echo $this->get_label('responsive adblock not assigned');?>
	</div>
	</div>

	<div style="width: 50%;float: left;">
	<div style="height: 30px;">
	<div style="width: 110px;float: left;">
	<?php echo $this->get_label('small device');?>
	</div>
	: <?php if($result['small_dev_adblock'] > 0) echo $this->get_adblock_name($result['small_dev_adblock']); else echo $this->get_label('responsive adblock not assigned');?>
	</div>

	<div style="height: 30px;">
	<div style="width: 110px;float: left;">
	<?php echo $this->get_label('xtra small device');?>
	</div>
	: <?php if($result['xsmall_dev_adblock'] > 0) echo $this->get_adblock_name($result['xsmall_dev_adblock']); else echo $this->get_label('responsive adblock not assigned');?>
	</div>
	</div>

	</td>
	</tr>
	<?php } ?>



</table>
</div>

<div>
<?php if($result['pubid'] == 0){?>
<div style="width: 100%; text-align: center; margin-top: 10px;">
<input type="submit" name="submit" value="<?php echo $this->get_label('update adunit');?>" />
</div>
<?php }?>
<?php $form->end(); ?>
</div>
</div>
</div>
<script type="text/javascript">
function LoadThemeSettings()
{
	if($('#adcode_theme').length > 0)
	{
		if($('#adcode_theme').val() == -1)
		$('.themeSettings').show();
		else
		$('.themeSettings').hide();
	}
}

var slideInterval;

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

function HideLayoutPreview()
{
	$('.previewSection').html("");
	$('.previewSectionOuter').hide();
}

$(document).ready(function() {

	LoadVideoOptions();

	<?php if($sticky_enabled ==1){?>
	ShowStickyPosition();
	<?php }?>

	<?php if($pricing != 3  && $diamensionAdcode == 1 && $responsive_enabled == 1 && $responsive_block == 1){?>
	LoadResponsiveAdblocks();
	<?php }?>

	<?php if($overrideTheme == 1 && $themeRequired == 1){?>
	LoadThemeSettings();
    <?php } ?>

	$(window).resize(function()
	{
		HideLayoutPreview();
	});
});

function ShowStickyPosition()
{
	if($('.sticky_support').length >0)
	{
		$('#sticky_support_div').show();

		if($('#sticky_support').val() == 1)
		{
			$('#sticky_position_div').show();
			$('#sticky_close_div').show();
		}
		else
		{
			$('#sticky_position_div').hide();
			$('#sticky_close_div').hide();
		}
	}
}

function LoadVideoOptions()
{
	adcodeFormat = <?php echo intval($adcodeFormat); ?>;

	if(adcodeFormat == 13)
	{
		if($("#adcode_for").val() ==1)
		{
			if($('#nonlinearbanner').length >0)
			{
				if($('#nonlinearbanner').prop('checked'))
				$('.video-option-vast-size').show();
				else
				$('.video-option-vast-size').hide();
			}
		}
	}
}

function LoadResponsiveAdblocks()
{
	if($('#responsive_support').val() == 1)
	$(".responsive-size").show();
	else
	$(".responsive-size").hide();
}

$("#adcode").click(function(){
	   $("#textarea-adcode").select();
	   document.execCommand('copy');
	});

$("#adcode-custom").click(function(){
	   $("#textarea-custom-adcode").select();
	   document.execCommand('copy');
	});

//$("#adcode-back-button").click(function(){
//		$("#textarea-back-button-adcode").select();
//		document.execCommand('copy');
//	});	
</script>
<?php $this->dispatch("layout/footer");?>