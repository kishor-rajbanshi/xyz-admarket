<?php
//ob_start();

$normalAdsResultCount 	= 0;
$indexAppend = "";
if(!MOD_REWRITE)
$indexAppend = "index.php?page=";

$nativeAdsCount		= 0;
$feedJSONCount 		= 0;
$exchangeJSONCount = 0;
$creditType 		= 0;
$creditIconType 	= 0;
$expandableflag 	= 0;
$rid 				= 0;
$borderType         = 0;
$imagePosition      = 0;
$expandableEnter    = 0;
$trackInterval      = $this->get_variable("trackInterval");
$trackInterval      = $trackInterval * 1000;


$countrywise_pricing_enabled	= Configuration::get_instance()->read('countrywise_pricing_enabled');

if($countrywise_pricing_enabled == 1)
$decoded_array = json_decode(Configuration::get_instance()->read('countrywise_minimum_price'),1);
else
$decoded_array = array();


$geo_country  = $this->geo_country;

$creditText 			 = "";
$creditIcon 			 = "";
$nativeCustomCode   	 = "";
$nativeResponsiveSupport = "";
$nativeHeader            = "";

$nativeRows         	 = 0;
$nativeColumns         	 = 0;
$nativeAdsAllowed        = 0;

$feedJSONArray 			 = array();
$exchangeJSONArray 	 = array();
$adIDList          		 = array();

$automaticRotation         = intval($this->get_variable('automaticRotation'));
$automaticRotationInterval = intval($this->get_variable('automaticRotationInterval'));


$pid             	= $this->get_variable('pid');
$aduid 				= $this->get_variable('aduid');
$display_type    	= $this->get_variable('display_type');
$feedGetSuccess 	= $this->get_variable('feedGetSuccess');
$feedJSON       	= $this->get_variable('feedJSON');
$exchangeGetSuccess = $this->get_variable('exchangeGetSuccess');
$exchangeJSON       = $this->get_variable('exchangeJSON');
$pop_enabled 		= $this->get_variable('pop_enabled');
$referral_enabled 	= $this->get_variable('referral_enabled');
$adv_ref_enabled 	= $this->get_variable("adv_ref_enabled");
$pub_ref_enabled 	= $this->get_variable("pub_ref_enabled");
$native_enabled 	= $this->get_variable("native_enabled");
$expandable_enabled = $this->get_variable('expandable_enabled');
$skin_enabled 		= $this->get_variable('skin_enabled');
$publisherRid 		= intval($this->get_variable('publisherRid'));
$directlink 		= intval($this->get_variable('directlink'));
$adblockSizeChanged = intval($this->get_variable('adblockSizeChanged'));
$adcodeType         = intval($this->get_variable('adcodeType'));
$totalAdCountAllowed= intval($this->get_variable("totalAdCount"));
$adSectionWidth		= $this->get_variable('adSectionWidth');
$adcodeDisplayType  = $this->get_variable('adcodeDisplayType'); //0 => Normal, 1 => Interetitial, 2 => Inpage-push
$inpagepush		    = $this->get_variable('inpagepush');
$interstitial		= $this->get_variable('interstitial');



$adcodeResult   = $this->get_result('adcodeResult');
$result   		= $adcodeResult[0];

$adLayoutID         = $this->get_variable('adLayoutID');
$adcodeTheme        = $this->get_variable('adcodeTheme');
$adcodeFont         = $this->get_variable('adcodeFont');

$overrideTheme      = Configuration::get_instance()->read('allow_publishers_to_override_theme');

$adLayoutArray  	= array();
$themeArray     	= array();
$fontArray      	= array();

$buttonSectionWidth = 0;
$CTAPosition        = 0;

if($adcodeType == 1 || $adcodeType == 2 || $adcodeType == 11 || $adcodeType == 13)
{
	$resFontExternal    = $this->get_result("resFontExternal");

	if($adLayoutID > 0 && ($adcodeType == 1 || $adcodeType == 11))
	{
		$resAdLayout    = $this->get_result("resAdLayout");
		$adLayoutArray  = $resAdLayout[0];

		$CTAPosition    = intval($adLayoutArray['CTA_position']);
		if($adLayoutArray['button_enabled'] == 1 && $adLayoutArray['CTA_position'] != 2 && $adLayoutArray['CTA_section_width'] > 0)
		$buttonSectionWidth = $adLayoutArray['CTA_section_width'];
	}

	if($adcodeTheme > 0)
	{
		$themeResult    = $this->get_result("themeResult");
		$themeArray     = $themeResult[0];
	}

	if($adcodeFont > 0)
	{
		$fontResult     = $this->get_result("fontResult");
		$fontArray      = $fontResult[0];
	}
}

if($overrideTheme == 1 && $adcodeTheme == -1)
{
	$themeArray['heading_color']            = $result['heading_color'];
	$themeArray['heading_background']       = $result['heading_background'];
	$themeArray['heading_hover_color']      = $result['heading_hover_color'];
    $themeArray['title']              		= $result['title_color'];
    $themeArray['title_background']         = $result['title_background'];
    $themeArray['title_hover_color']        = $result['title_hover_color'];
    $themeArray['description']        		= $result['description_color'];
    $themeArray['description_background']   = $result['description_background'];
    $themeArray['description_hover_color']  = $result['description_hover_color'];
    $themeArray['url']                		= $result['url_color'];
    $themeArray['url_background']           = $result['url_background'];
   	$themeArray['url_hover_color']          = $result['url_hover_color'];
    $themeArray['image_background']         = $result['image_background'];
    $themeArray['credit']             		= $result['credit_text_color'];
    $themeArray['background']              	= $result['adcode_background'];
    $themeArray['border']             		= $result['border_color'];
    $themeArray['button']         			= $result['cta_text_color'];
    $themeArray['button_background']        = $result['cta_button_color'];
    $themeArray['button_hover']        		= $result['cta_hover_color'];
    $themeArray['cta_background']        	= $result['cta_background'];
}

$adcodeBackground  = "#EEEEEE";
$adcodeButtonColor = "#DC0000";
$adcodeButtonText  = "#FFFFFF";
$adcodeBorderColor = "#000000";


if(isset($themeArray['background']))
$adcodeBackground = $themeArray['background'];

if(isset($themeArray['button_background']))
$adcodeButtonColor = $themeArray['button_background'];

if(isset($themeArray['button']))
$adcodeButtonText  = $themeArray['button'];

if(isset($themeArray['border']))
$adcodeBorderColor = $themeArray['border'];


if($feedGetSuccess == 1 && $feedJSON != "")
{
	$feedJSONArray = json_decode($feedJSON,1);
	$feedJSONCount = count($feedJSONArray);
}

if($exchangeGetSuccess == 1 && $exchangeJSON != "")
{
		$exchangeJSONArray = json_decode($exchangeJSON,1);
		$exchangeJSONCount = count($exchangeJSONArray);
}
$credittextDisplay 	= Configuration::get_instance()->read('credit_text_display_mouse_hover');

$base_url 		= BASE;
$trackDomain 		= $this->get_variable("trackDomain");

if(strpos($base_url, 'https') === 0)
$base_url = substr($base_url,6);
else
$base_url = substr($base_url,5);

$creditLink 			= BASE;

if($referral_enabled ==1 && $pid >0 && ($adv_ref_enabled ==1 || $pub_ref_enabled ==1))
$creditLink             = BASE.'?rid='.$pid;

if($adcodeType == 9 && $pop_enabled == 1)
{
	//header("Content-Type: application/javascript");  //Need it for some server requests

	$day_limit = intval(Configuration::get_instance()->read('pop_display_count_day'));
	?>
	function Set_Track_Cookie(name, value, expires, path, domain, secure)
	{
		if(expires)
		expires = expires * 1000 * 60 * 60;

		var expires_date = new Date();

		if(expires >0)
		expires_date.setTime(expires_date.getTime()+expires);
		else
		expires_date.setHours(expires_date.getHours()+1);

		document.cookie = name + "=" +escape( value ) + ";expires=" + expires_date.toUTCString()  + ( ( path ) ? ";path=" + path : "" ) + ( ( domain ) ? ";domain=" + domain : "" ) + ( ( secure ) ? ";secure" : "" );
	}

	{POPTRACKDATA}

	windowwidth		='{POPWINDOWWIDTH}';
	windowheight	='{POPWINDOWHEIGHT}';

	if(windowwidth == 0 || windowwidth == '')
	windowwidth= window.innerWidth;

	if(windowheight == 0 || windowheight == '')
	windowheight= window.innerHeight ;

	var pop_click=0;

	var popEvent = function()
	{
			if(!pop_click)
			{
			pop_click=1;

			{POPBODYREMOVE}

			<?php
			$popcontent=array('operation'=>'popopen','auid'=>$aduid,'addelay'=>$day_limit);
			$popcontent=json_encode($popcontent);
		?>

			window.parent.postMessage('<?php echo $popcontent;?>',"*");

			{POPOPENTYPE}
		}
	};

	if(window.addEventListener)
	window.addEventListener("click",popEvent, false);
	else
	window.attachEvent("onclick",popEvent);


<?php 
}
else if($adcodeType == 21)
{
?>
	<script data-cfasync="false" type="text/javascript">
	var popEvent = function()
	{
		{DIRECTLINKOPENTYPE}
	};
	
	if(window.addEventListener)
	window.addEventListener("load",popEvent, false);
	else
	window.attachEvent("onload",popEvent);
	</script>
<?php 
}
else if($adcodeType != 9)
{

$adImpressionString   = '';

$totalAdsResultCount  = intval($this->get_variable('totalAdsResultCount'));
$balanceAdsForFilling = $this->get_variable('balanceAdsForFilling');


if($feedGetSuccess == 1 && $feedJSONCount > 0)
{
		$totalAdsResultCount  = $feedJSONCount;
		$balanceAdsForFilling = 0;
}
if($exchangeGetSuccess == 1 && $exchangeJSONCount > 0)
{
		$totalAdsResultCount  = $exchangeJSONCount;
		$balanceAdsForFilling = 0;
}


$ecommerce_enabled=$this->get_variable('ecommerce_enabled');
$video_enabled=$this->get_variable('video_enabled');

$interstitial_enabled=$this->get_variable('interstitial_enabled');
$cpa_enabled=$this->get_variable('cpa_enabled');
$textimage_enabled=$this->get_variable('textimage_enabled');


$expandable_width=$this->get_variable('expandable_width');
$expandable_height=$this->get_variable('expandable_height');


$textimageWidth  = $this->get_variable('textimageWidth');
$textimageHeight = $this->get_variable('textimageHeight');
$native          = $this->get_variable('native');


$player_width=0;
$player_height=0;

if($adcodeType ==13)
{
	$player_width  = $this->get_variable('player_width');
	$player_height = $this->get_variable('player_height');
}



	$adcodeID      = $result['auid'];
	$skipenabled   = 0;
	$skipposition  = -1;
	$skipinterval  = 0;
	$addelay       = 0;
	$intercontent  = "";
	$intercontent1 = "";

	if($interstitial_enabled == 1 && $adcodeDisplayType == 1)
	{
		$skipenabled = Configuration::get_instance()->read('display_skip_button');
		$skipposition= Configuration::get_instance()->read('skip_button_position');
		$skipinterval= Configuration::get_instance()->read('interstitial_skip_interval');
		$addelay 	 = intval(Configuration::get_instance()->read('interstitial_interval_visitor'));

		$intercontent  = array('operation'=>'open','auid'=>$adcodeID,'addelay'=>$addelay,'width'=>$result['width'],'height'=>$result['height'],'skipInterval'=>$skipinterval);
		$intercontent  = json_encode($intercontent);

		$intercontent1 = array('operation'=>'close','auid'=>$adcodeID,'addelay'=>$addelay);
		$intercontent1 = json_encode($intercontent1);
	}

	if($adcodeType == 14)
	{
		$container_id   = $result['container_id'];
        $skin_positions = json_decode(html_entity_decode($result['skin_positions']),1);  //For set_result json content return as array, use html_entity_decode
	}


	$sticky_enabled=$this->get_variable('sticky_enabled');

	$sticky_support=0;
	$sticky_position="";

	if($inpagepush == 1)
	{
		$sticky_support  = 1;
		$sticky_position = "TR";
	}
	else if($sticky_enabled ==1)
	{
		$sticky_support=$result['sticky_support'];
	    $sticky_position=$result['sticky_position'];

		$sticky_supported_positions=Configuration::get_instance()->read('sticky_supported_positions');

		$position_array=json_decode($sticky_supported_positions,1);

		if(!isset($position_array[$sticky_position]) || (isset($position_array[$sticky_position]) && $position_array[$sticky_position] ==0))
		{
			$sticky_support=0;
			$sticky_position='';
		}
	}


	$normalAdsResultCount  = $this->get_variable('normalAdsResultCount');
	$defaultAdsResultCount = $this->get_variable('defaultAdsResultCount');


	$sid=$this->get_variable('sid');
	$category_enabled=$this->get_variable('category_enabled');
	$cpd_enabled=$this->get_variable('cpd_enabled');


	if($interstitial_enabled ==1 && $adcodeDisplayType == 1){?>
	<script data-cfasync="false" type="text/javascript">
	window.parent.postMessage('<?php echo $intercontent;?>',"*");
	</script>
	<?php }

    if($adblockSizeChanged == 1)
    {
        $responsive_operation =array('operation'=>'iframe_resize','auid'=>$adcodeID,'pid'=>$pid,'width'=>$result['width'],'height'=>$result['height']);
        $responsive_operation=json_encode($responsive_operation);
        ?>
        <script type="text/javascript">
        window.parent.postMessage('<?php echo $responsive_operation;?>',"*");
        </script>
        <?php
    }


	if($adcodeType != 14)
	{
		$borderType                 = $result['border_type'];

		if($native == 1)
		{
			$credit_text_id   		= Configuration::get_instance()->read('native_credit_text');
			$creditposition   		= Configuration::get_instance()->read('native_creditposition');
			$creditalignment  		= Configuration::get_instance()->read('native_creditalignment');
			$imagePosition     	    = $result['nativeimg_position'];
			$nativeHeader           = $result['name'];

			if($nativeHeader == "")
			$nativeHeader           = Configuration::get_instance()->read('nativead_header');

			$nativeCustomCode   	= $result['custom_code'];
			$nativeResponsiveSupport= $result['responsive_support'];
			$nativeRows         	= $this->get_variable('nativeRows');
			$nativeColumns         	= $this->get_variable('nativeColumns');
			$nativeAdsAllowed       = $this->get_variable('nativeAdsAllowed');
		}
		else
		{
			$credit_text_id   = $result['credit_text'];
			$creditposition   = $result['creditposition'];
			$creditalignment  = $result['creditalignment'];
			$imagePosition    = $result['image_position'];
		}

		if($credit_text_id > 0)
		{
			$credit_text_array 	= $this->get_credittext($credit_text_id,1,1);
			$creditText 		= $credit_text_array[0];
			$creditType 		= $credit_text_array[1];
			$creditIcon 		= $credit_text_array[2];
			$creditIconType 	= $credit_text_array[3];
		}


	if(isset($themeArray['credit']))
	$creditColor = $themeArray['credit'];
	else
	$creditColor = '#FFFFFF';


	if(isset($themeArray['border']))
	$borderColor = $themeArray['border'];
	else
	$borderColor = '#000000';


	if($sticky_support ==1)
	{
		if($adcodeType != 1 && $adcodeType != 11)
		$borderType = 1;

		$close_image    = "";
		$close_position = 0; //Inside the Iframe

		if($inpagepush == 0)
		{
	    	$sticky_close_button_round=Configuration::get_instance()->read('sticky_close_button_round');
		$sticky_close_button_square=Configuration::get_instance()->read('sticky_close_button_square');



		if($borderType == 1 && $sticky_close_button_square != "")
		$close_image=$base_url.DATA_DIR."/sticky/".$sticky_close_button_square;
		else if($borderType == 1)
		$close_image="";
		else if($borderType == 0 && $sticky_close_button_round !="")
		$close_image=$base_url.DATA_DIR."/sticky/".$sticky_close_button_round;
		else if($borderType == 0)
		$close_image="";

			$close_position = $result['sticky_close_position'];
		}

		$stickycontent = array(
								'operation'=>'stickyads',
								'auid'=>$adcodeID,
								'position'=>$sticky_position,
								'credit_position'=>intval($result['creditposition']),
								'credit_alignment'=>intval($result['creditalignment']),
								'width'=>intval($result['width']),
								'height'=>intval($result['height']),
								'close_position'=>$close_position,
								'close_image'=>$close_image,
								'background_color'=>$borderColor,
								'color'=>$creditColor,
								'border'=>$borderType,
								'inpage_push'=>$inpagepush
							);


		$stickycontent=json_encode($stickycontent);
		?>
		<script data-cfasync="false" type="text/javascript">
		window.parent.postMessage('<?php echo $stickycontent;?>',"*");
		</script>
		<?php
	}
?>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height" />
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo DEFAULT_CHARSET; ?>" />
<meta name="robots" content="noindex,nofollow" />

<script data-cfasync="false" type="text/javascript" src="js/jquery.min.js"></script>
<title></title>
<style type="text/css">
<?php
if($adcodeType == 1 || $adcodeType == 2 || $adcodeType == 11 || $adcodeType == 13)
{
	foreach($resFontExternal as $fkey=>$fvalue){?>
	@font-face {
	  font-family: <?php echo $fvalue['slug_name'];?>;
	  src: url(<?php echo $base_url.DATA_DIR.'/fonts/'.$fvalue['file_name'];?>);
	}
<?php }} ?>

body
{
	padding : 0;
	margin  : 0;
}

a
{
	outline : none;
}

.advertise-here {
	width               : <?php echo $result['width'];?>;
	height              : <?php echo $result['height'];?>;
	border 				: 1px solid <?php echo $adcodeBorderColor; ?>;
	text-align 			: center;
	display 			: table-cell;
	vertical-align 		: middle;
	background-color 	: <?php echo $adcodeBackground; ?>;
}

.advertise-here a {
	text-decoration 	: none;
	color 				: <?php echo $adcodeButtonText; ?>;
	background-color 	: <?php echo $adcodeButtonColor; ?>;
	padding 			: 5px;
}



<?php if($ecommerce_enabled ==1){?>

.dummybutton
{
	box-sizing: border-box !important;
	white-space: nowrap;
	border-radius: 2px;
}

.dummyprice
{
	vertical-align: middle;
	white-space: nowrap;
}

.dummyofferprice
{
	vertical-align: middle;
	white-space: nowrap;
}

.display-outer-style
{
   width:<?php  echo $result['width']; ?>px;
   height:<?php  echo $result['height']; ?>px;
   border:1px solid;
   <?php if($borderType == 0){?>
   border-radius: 10px;
   <?php } ?>

   box-sizing: border-box !important;
   overflow: hidden;
   }


.slidesection-style
{
	text-align: center;
	float: left;
	box-sizing: border-box !important;
	position: relative;
	overflow: hidden;
}

.slidesection-style::before
{
	content: " ";
	display: inline-block;
	vertical-align: middle;
}


.headlinesection-style
{
	text-align: center;
	float: left;
	box-sizing: border-box !important;
	white-space:nowrap;
}

.headlinesection-style::before
{
	content: " ";
	display: inline-block;
	vertical-align: middle;
}

.logosection-style
{
	text-align: center;
	float: left;
	box-sizing: border-box !important;
}

.logosection-style::before
{
	content: " ";
	display: inline-block;
	vertical-align: middle;
}

.singleadsection-style
{
	border:1px solid;

	<?php if($borderType == 0){?>
	border-radius: 10px;
	<?php }	?>

	text-align: center;
	float: left;
	box-sizing: border-box !important;
	overflow: hidden;
}

.singleadsection-style::before
{
	content: " ";
	display: inline-block;
	vertical-align: middle;
}

.slideinner-style
{
	box-sizing: border-box !important;
	left: 0px;
	position: relative;
	overflow: hidden;
	display: inline-block;
	vertical-align: middle;
}

.slideblock-style
{
	float: left;
	box-sizing: border-box !important;
	vertical-align: middle;
}

.slideblock-style::before
{
	content: " ";
	display: inline-block;
	vertical-align: middle;
}

.slideblockinner-style
{
	display: inline-block;
	vertical-align: middle;
}

.slideleft-style
{
	height: 100%;
	box-sizing: border-box !important;
	position: absolute;
	left: 0px;
	z-index: 1000;
}

.slideleft-style::before
{
	content: " ";
	display: inline-block;
	vertical-align: middle;
	height: 100%;
}

.slideright-style
{
	height: 100%;
	box-sizing: border-box !important;
	position: absolute;
	right: 0px;
	z-index: 1000;
}

.slideright-style::before
{
	content: " ";
	display: inline-block;
	vertical-align: middle;
	height: 100%;
}

.slideleftinner-style,.sliderightinner-style
{
	padding: 0px 1px 1px 2px;
	cursor: pointer !important;
	border:1px solid;
	border-radius: 5px;
	font-weight: bold;
	font-size: 14px;
}


.slideinnerholder-style
{
	height: 100%;
	float: left;
	box-sizing: border-box !important;
	overflow: hidden;
}

.slideleftinner-style
{
	display: none;
}

.preview-display-style
{
	width: 100%;
	height: 100%;
	text-align: left;
	box-sizing: border-box !important;
}

.ecommerceurl a:link,.ecommerceurl a:visited,.ecommerceurl a:hover,.ecommerceurl a:active,.ecommerceurl a:focus
{
	white-space:nowrap;
}


.span-button-preview
{
	border-radius: 2px;
}

.display-style-right
{
	text-align: right;
}
<?php }?>

</style>
<?php  echo html_entity_decode($nativeCustomCode);?>

</head>
<body>
<?php

if($native == 1){
	$credittextDisplay = 0; //Directly apply credit text always show in case of native ads
	?>

<div class="native-outer-div">

<div class="native-head">

<?php if($nativeHeader !=""){?>
<h4><?php echo $nativeHeader;?></h4>
<?php } ?>

<?php if($creditText != ""){?>

<div id="creditIconDiv" class="creditTextFirst" onMouseOver="AdmarketCreditLoad();"><?php echo $creditIcon;?></div>

<div id="creditDiv" class="creditTextSecond" style="display:none;" onMouseOut="AdmarketCreditDisable();" ><a target="_blank" href="<?php echo $creditLink;?>"><?php echo $creditText;?></a></div>

<?php } ?>

</div>
<?php }?>

<div class="ad-display-section" id="ad-display-section-<?php echo $adcodeID;?>">

<?php
if($adcodeDisplayType == 1 && ($totalAdsResultCount > 0 || ($display_type ==3 && $sid > 0 && $normalAdsResultCount == 0)))
{
	?>
	<span class="skip-div">
	<div class="skip-counter" id="skip-counter"><?php echo $skipinterval;?></div>

	<?php if($skipenabled == 1){?>
	<span class="skip-button" onclick="CloseSkip();"></span>
	<?php }?>
	</span>


	<script data-cfasync="false" type="text/javascript">
	var skipinter='<?php echo $skipinterval;?>';
	var skipinterval=window.setInterval(function(){
		skipinter=skipinter-1;
		document.getElementById('skip-counter').innerHTML=skipinter;

		if(skipinter <=0)
		{
			window.clearInterval(skipinterval);
			window.parent.postMessage('<?php echo $intercontent1;?>',"*");
		}
	}, 1000);

	function CloseSkip()
	{
		window.clearInterval(skipinterval);
		window.parent.postMessage('<?php echo $intercontent1;?>',"*");
	}

	function CloseSkipAd()
	{
		skipinter=0;
	}
	</script>
<?php
}


if($cpd_enabled ==1 && $display_type ==3 && $sid >0 && $normalAdsResultCount == 0)
{
	if($adcodeType == 1 || $adcodeType == 2 || $adcodeType == 3 || $adcodeType == 11)
	{
		?>
	<div class="advertise-here"><a target="_blank" href="<?php echo $base_url.'index.php?page=dispatch/sponsored/25/'.$sid;?>" <?php if($adcodeDisplayType == 1){?>onclick="CloseSkipAd();"<?php }?>>Advertise Here!</a></div>
	<?php }?>
<?php }

} else {?>
<html>
<head>
<script data-cfasync="false" type="text/javascript" src="js/jquery.min.js"></script>
</head>
<body>
<?php }

	if($totalAdsResultCount >0)
	{
	   $i 				= 0;
	   $nativeAdsCount  = $totalAdsResultCount;

	   if($totalAdsResultCount > $totalAdCountAllowed && $totalAdCountAllowed > 0)
	   $nativeAdsCount 	= $totalAdCountAllowed;

       $skin_banners 	= array();
       $skin_height  	= array();


	   if($adcodeType == 13)
	   {
			$controls_enabled	= Configuration::get_instance()->read('html5_video_controls_enabled');
			$autoplay_enabled	= Configuration::get_instance()->read('html5_video_autoplay_enabled');
			$cpv_interval		= intval(Configuration::get_instance()->read('html5_player_impression_tracking_interval'));
			$video_muted		= Configuration::get_instance()->read('html5_video_mute_enabled');
	   }


	   $rateFromFeed = 0;
	   $rateFromExchange = 0;
	   if($normalAdsResultCount >0)
	   {
		   	if($feedGetSuccess == 0 && $exchangeGetSuccess == 0)
			$res1[0] = $this->get_result('normalAdResult');
			else if($feedGetSuccess == 1)
			{
	            $feedAdID   = intval($this->feedAdID);

	            $resultTemp = $this->get_result('normalAdResult');

	            foreach($resultTemp as $rKey => $rValue)
	            {
	            	if(isset($rValue['aid']) && $rValue['aid'] == $feedAdID)
	            	{
						for($ii = 0;$ii < $feedJSONCount;$ii++)
						{
							if(isset($feedJSONArray[$ii]))
							{
								if($adcodeType == 1)
								{
									$rValue['aid']         = $feedAdID.'_'.$ii;
									$rValue['title']       = $feedJSONArray[$ii]['title'];
									$rValue['description'] = $feedJSONArray[$ii]['description'];
									$rValue['display_url'] = $feedJSONArray[$ii]['display_url'];
									$rValue['click_url']   = $feedJSONArray[$ii]['click_url'];
									$rValue['pixel_url']   = $feedJSONArray[$ii]['pixel_url'];

									if($rValue['default_rate'] == 0)
									{
										$rValue['default_rate']   = $feedJSONArray[$ii]['default_rate'];
										$rateFromFeed			  = 1;
									}
								}
								else if($adcodeType == 2)
								{
									$rValue['aid']         = $feedAdID.'_'.$ii;
									$rValue['banner']      = $feedJSONArray[$ii]['banner'];
									$rValue['click_url']   = $feedJSONArray[$ii]['click_url'];
									$rValue['pixel_url']   = $feedJSONArray[$ii]['pixel_url'];

									if($rValue['default_rate'] == 0)
									{
										$rValue['default_rate']   = $feedJSONArray[$ii]['default_rate'];
										$rateFromFeed			  = 1;
									}
								}
								else if($adcodeType == 11)
								{
									$rValue['aid']         = $feedAdID.'_'.$ii;
									$rValue['title']       = $feedJSONArray[$ii]['title'];
									$rValue['description'] = $feedJSONArray[$ii]['description'];
									$rValue['display_url'] = $feedJSONArray[$ii]['display_url'];
									$rValue['banner']      = $feedJSONArray[$ii]['banner'];
									$rValue['click_url']   = $feedJSONArray[$ii]['click_url'];
									$rValue['pixel_url']   = $feedJSONArray[$ii]['pixel_url'];

									if($rValue['default_rate'] == 0)
									{
										$rValue['default_rate']   = $feedJSONArray[$ii]['default_rate'];
										$rateFromFeed			  = 1;
									}
								}

	            				$res1[0][$ii] = $rValue;

	            			}
	            		}

	            		break;
	            	}
	            }
			}
			else if($exchangeGetSuccess == 1)
			{
		$exchangeAdID   = intval($this->exchangeAdID);
		$resultTemp = $this->get_result('normalAdResult');
		foreach($resultTemp as $rKey => $rValue)
		{
			if(isset($rValue['aid']) && $rValue['aid'] == $exchangeAdID)
			{
					for($ii = 0;$ii < $exchangeJSONCount;$ii++)
					{
							if(isset($exchangeJSONArray[$ii]))
							{
									if($adcodeType == 1)
									{
											$rValue['aid']         = $exchangeAdID.'_'.$ii;
											$rValue['title']       = $exchangeJSONArray[$ii]['title'];
											$rValue['description'] = $exchangeJSONArray[$ii]['description'];
											$rValue['display_url'] = $exchangeJSONArray[$ii]['display_url'];
											$rValue['click_url']   = $exchangeJSONArray[$ii]['click_url'];
											$rValue['impression_url'] = $exchangeJSONArray[$ii]['impression_url'];
											$rValue['bid_request_id'] = $exchangeJSONArray[$ii]['bid_request_id'];
											$rValue['impression_id']  = $exchangeJSONArray[$ii]['impression_id'];
											if($rValue['default_rate'] == 0)
											{
													$rValue['default_rate']   = $exchangeJSONArray[$ii]['default_rate'];
													$rateFromExchange			    = 1;
											}
									}
									else if($adcodeType == 2)
									{
											$rValue['aid']         = $exchangeAdID.'_'.$ii;
											$rValue['banner']      = $exchangeJSONArray[$ii]['banner'];
					$rValue['render_content']      = $exchangeJSONArray[$ii]['render_content'];
											$rValue['click_url']   = $exchangeJSONArray[$ii]['click_url'];
											$rValue['impression_url'] = $exchangeJSONArray[$ii]['impression_url'];
											$rValue['bid_request_id'] = $exchangeJSONArray[$ii]['bid_request_id'];
											$rValue['impression_id']  = $exchangeJSONArray[$ii]['impression_id'];
											if($rValue['default_rate'] == 0)
											{
													$rValue['default_rate']   = $exchangeJSONArray[$ii]['default_rate'];
													$rateFromExchange			    = 1;
											}
									}
									else if($adcodeType == 11)
									{
											$rValue['aid']         = $exchangeAdID.'_'.$ii;
											$rValue['title']       = $exchangeJSONArray[$ii]['title'];
											$rValue['description'] = $exchangeJSONArray[$ii]['description'];
											$rValue['display_url'] = $exchangeJSONArray[$ii]['display_url'];
											$rValue['banner']      = $exchangeJSONArray[$ii]['banner'];
											$rValue['click_url']   = $exchangeJSONArray[$ii]['click_url'];
											$rValue['impression_url'] = $exchangeJSONArray[$ii]['impression_url'];
											$rValue['bid_request_id'] = $exchangeJSONArray[$ii]['bid_request_id'];
											$rValue['impression_id']  = $exchangeJSONArray[$ii]['impression_id'];
											if($rValue['default_rate'] == 0)
											{
													$rValue['default_rate']   = $exchangeJSONArray[$ii]['default_rate'];
													$rateFromExchange			    = 1;
											}
									}
									$res1[0][$ii] = $rValue;
							}
					 }
	            		break;
	            	}
	            }
			}


		 	if($balanceAdsForFilling >0)
			{
			 	$res1balance=$this->get_result('normalAdResultBalance');

			 	foreach($res1balance as $key123=>$value123)
			 	{
			 		$res1[0][]=$value123;
			 	}
			}

		 	$cpm_profit_percentage=0;
		 	if($display_type ==1 && $adcodeType != 13)
		 	{
		 		$specific_profit=$this->get_variable('specific_profit');

		 		if($specific_profit >0)
		 		$cpm_profit_percentage=$specific_profit;
		 		else
		 		$cpm_profit_percentage=Configuration::get_instance()->read('cpm_profit_percentage');
		 	}


	   		$cpv_profit_percentage=0;
		 	if($display_type ==1 && $adcodeType == 13)
		 	{
		 		$specific_profit=$this->get_variable('specific_profit_cpv');

		 		if($specific_profit >0)
		 		$cpv_profit_percentage=$specific_profit;
		 		else
		 		$cpv_profit_percentage=Configuration::get_instance()->read('cpv_profit_percentage');
		 	}
	   }


	   if($defaultAdsResultCount >0)
  	   $res1[1]=$this->get_result('defaultAdResult');


  	   $normalImpressionFlag  = 0;
  	   $defaultImpressionFlag = 0;
  	   $adOriginal			  = 0;
  	   $adSectionString		  = "";
  	   $admarket_name         = Configuration::get_instance()->read('admarket_name');


  	   $displayAdContent      = array();

	   if($normalAdsResultCount >0 || $defaultAdsResultCount >0)
	   {
			foreach($res1 as $key => $value)
			{
				foreach($value as $vkey => $result1)
				{
				 	$clickRatePass       = 0;
				 	$clickRatePassString = "";

				 	if($display_type == 0 && $feedGetSuccess == 1)
				 	{

						if($countrywise_pricing_enabled == 1)
						{
								$countryPrice     = $result1['price'];

								if($countryPrice > 0)
								$clickRatePass = $countryPrice;
								else
								{
										if(isset($decoded_array[$geo_country]['cpc']) && $decoded_array[$geo_country]['cpc'] > 0)
										{
												$countryPrice  = $decoded_array[$geo_country]['cpc'];

												$clickRatePass = $countryPrice;
										}
										else
										$clickRatePass = $result1['default_rate'];
								}
						}
						else
						$clickRatePass = $result1['default_rate'];


				 		$clickRatePassString = "/".$clickRatePass."/".md5(intval($result1['aid']).$clickRatePass.$admarket_name);
				 	}

					if($key == 0)
					{
						$adOriginal			  = 1;
						$normalImpressionFlag = 1;

					 	$clksurl='';
					 	$kid_data=0;

					 	if($display_type ==0 || $display_type ==1 || $display_type ==3 || $display_type ==6)
					 	{
					 		if($display_type ==3)
					 		$kid_data=$result1['currentmapid'];
					 		else
					 		$kid_data=$result1['kid'];


						    if($i == 0 && ($adcodeType == 1 || $adcodeType == 2 || $adcodeType == 11))
						    {
						    $botString = md5(rand(0,10));
						    ?>
						    	<div style="display: none;width: 0px;">
									<a href="<?php  echo  $trackDomain.TRACK_DIR.'/'.$indexAppend.'action/validate/'.intval($result1['aid']).'/'.$kid_data.'/'.$result['auid'].'/'.$sid.'/'.$pid.'/{ENCIP}/'.$botString.'/0/'.$result1['retarget']; ?>" target="_blank">
									<?php if($adcodeType == 2){?>
									<img style="height:<?php echo $result['height'];?>px;width:<?php echo $result['width'];?>px;" src="<?php echo $base_url.'images/data.png';?>" border="0" />
									<?php }else{?>
									<?php echo $result1['title']; ?>
									<?php } ?>
									</a>
						    	</div>
						    <?php
						    }

						 	$md5Hash = md5(intval($result1['aid']).$kid_data.$result['auid'].$sid.$pid.$result1['retarget']);

						 	$clksurl      = $trackDomain.TRACK_DIR.'/'.$indexAppend.'action/validate/'.intval($result1['aid']).'/'.$kid_data.'/'.$result['auid'].'/'.$sid.'/'.$pid.'/{ENCIP}/'.$md5Hash.'/0/'.$result1['retarget'].'/'.$this->mybase64_encode($result1['click_url']).$clickRatePassString;
						 	$clks_gallery = $trackDomain.TRACK_DIR.'/'.$indexAppend.'action/validate/'.intval($result1['aid']).'/'.$kid_data.'/'.$result['auid'].'/'.$sid.'/'.$pid.'/{ENCIP}/'.$md5Hash.'/{ECOMID}/'.$result1['retarget'];
				 		}



					 	$profit=0;
					 	$singleimprate=0;


						if($display_type == 1 && $adcodeType != 13)
					 	{
							
					 		if(($feedGetSuccess == 1 && $rateFromFeed == 1) || ($exchangeGetSuccess == 1 && $rateFromExchange == 1))
					 		$singleimprate = $result1['default_rate']/1000;
					 		else
							{
								if($countrywise_pricing_enabled == 1)
								{
										$countryPrice     = $result1['price'];

										if($countryPrice > 0)
										$singleimprate = $countryPrice / 1000;
								 		else
										{
										if($result1['display_type'] == 2)
											  {
														if(isset($decoded_array[$geo_country]['html']) && $decoded_array[$geo_country]['html'] > 0)
														{
																$countryPrice  = $decoded_array[$geo_country]['html'];

																$singleimprate = $countryPrice / 1000;
														}
														else
														$singleimprate=$result1['default_rate']/1000;
												}
											  else
											  {
													   if(isset($decoded_array[$geo_country]['cpm']) && $decoded_array[$geo_country]['cpm'] > 0)
														{
																$countryPrice  = $decoded_array[$geo_country]['cpm'];

																$singleimprate = $countryPrice / 1000;
														}
														else
														$singleimprate=$result1['default_rate'] / 1000;
												}
									}
								}
								else
								$singleimprate=$result1['default_rate'] / 1000;
				  			}



					 		if($pid >0)
					 		{
					 			if($result1['display_type'] ==2)
								$profit=$singleimprate;
								else
			 					$profit=$singleimprate*$cpm_profit_percentage/100;
					 		}
					 		else
					 		$profit=$singleimprate;
					 	}


						if($display_type == 1 && $adcodeType == 13)
					 	{


							if($countrywise_pricing_enabled == 1)
							{
								 $countryPrice     = $result1['price'];

									if($countryPrice > 0)
								$singleimprate = $countryPrice / 1000;
									else
									{
										if(isset($decoded_array[$geo_country]['cpv']) && $decoded_array[$geo_country]['cpv'] > 0)
									{
										$countryPrice = $decoded_array[$geo_country]['cpv'];
										$singleimprate = $countryPrice / 1000;
									}
										else
									$singleimprate=$result1['default_rate'] / 1000;
									}
							}
							else
							$singleimprate=$result1['default_rate'] / 1000;

					 		if($pid >0)
		 					$profit=$singleimprate*$cpv_profit_percentage/100;
		 					else
					 		$profit=$singleimprate;
					 	}


						$rid=0;

						if($display_type ==0 || $display_type ==1 || $display_type ==3 || $display_type ==6)
						{
							$impressionTrackPricing = $display_type;

							if($display_type == 1)
							{
								if($adcodeType == 13)
								$impressionTrackPricing = 13;
								else
								$impressionTrackPricing = $result1['display_type'];

							 	if($result1['display_type'] != 2 && $referral_enabled ==1 && $adv_ref_enabled ==1)
							 	{
							 		if($result1['refferal_status'] == 1)
									$rid=intval($result1['rid']);

							 	}
							}


							$key_id=0;

							if($display_type !=3)
							$key_id=$result1['keyid'];


						    $adImpressionString = $result1['userid'].'|'.intval($result1['aid']).'|'.$kid_data.'|'.$pid.'|'.$result['auid'].'|1|'.$sid.'|'.$impressionTrackPricing.'|'.$key_id;



						    $mapid=intval($result1['aid']);  //Not important

							if($display_type ==1)
							$adImpressionString.='|'.$mapid.'|'.$profit.'|'.$singleimprate.'|'.$rid.'|'.$publisherRid;
							else if($display_type ==3)
							$adImpressionString.='|'.$mapid.'|'.$result1['day_cpd_rate_publisher'].'|'.$result1['day_cpd_rate_total'];


							$this->adListArray[0][$result1['aid']] = $adImpressionString;
						}
					}
			 		else if($key == 1)
			 		{
						$adOriginal			  	= 0;
						$defaultImpressionFlag 	= 1;

						$clksurl = $trackDomain.TRACK_DIR.'/'.$indexAppend.'action/click_default/'.intval($result1['aid']).'/'.$result['auid'];

						$this->adListArray[1][$result1['aid']] = intval($result1['aid']);
			 		}


			 		$bannerUrlPath   = '';
					$bannerListArray = array();

					if(isset($result1['banner_list']) && $result1['banner_list'] != "")
					$bannerListArray = json_decode($result1['banner_list'],1);

					if(count($bannerListArray) > 0 && $feedGetSuccess == 0 && $exchangeGetSuccess == 0 && $native == 0 && ($adcodeType == 2 || $adcodeType == 11))
					{
						if(($result1['type'] == 2 || $result1['type'] == 11) && intval($result1['html5']) == 0)
						{
							$bannerSizeData = $result['bannersize'];

							foreach($bannerListArray as $bKey=>$bValue)
							{
								if($bannerSizeData == $bKey)
								$bannerUrlPath = $base_url.DATA_DIR.'/'.intval($result1['aid']).'/'.$bValue;
							}
						}
					}



			if($adcodeType == 13)
			$bannerUrlPath = $base_url.DATA_DIR.'/video/'.intval($result1['aid']).'/'.$result1['banner'];
			else if($feedGetSuccess == 0 && $exchangeGetSuccess == 0)
			{
				if($bannerUrlPath == "" && ($native == 1 || $adcodeType == 2 || $adcodeType == 11))
				$bannerUrlPath = $base_url.DATA_DIR.'/'.intval($result1['aid']).'_'.$result1['banner'];
			}
			else
			{
				if($native == 1 || $adcodeType == 2 || $adcodeType == 11)
				$bannerUrlPath = $result1['banner'];
			}
			?>

			<?php
			$srcurl         	= "";
			$bannerpath     	= "";
			$expandableContent  = "";
			$expandableflag 	= 0;

			if($adcodeType == 14)
			{
		        $banner_list=json_decode(html_entity_decode($result1['banner']),1);

		        foreach($banner_list as $keyData => $valueData)
		        {
		        	$image_array=explode('_',$valueData);

		            $skin_key=$image_array[0];  //Width of skin banner

		        	$skin_banners[$skin_key]=$base_url.DATA_DIR.'/'.intval($result1['aid']).'/'.$valueData;
		        	$skin_height[$skin_key]=$image_array[1];
		        }


		        $skin_content = array();

		        $skin_content['operation']      		= 'skinAdRender';
		        $skin_content['auid']           		= $adcodeID;
		        $skin_content['skin_positions'] 		= $skin_positions;
		        $skin_content['container_id']   		= $container_id;
		        $skin_content['click_url']				= $clksurl;
		        $skin_content['skin_banners']			= $skin_banners;
				$skin_content['skin_height']			= $skin_height;
				$skin_content['adid']					= intval($result1['aid']);
		        $skin_content							= json_encode($skin_content);

		        $this->adListArray[2][$result1['aid']] 	= $skin_content;
			}
			else if($adcodeType == 2)
			{
				if(isset($result1['html5']) && $result1['html5'] == 1)
				$srcurl = $base_url.DATA_DIR.'/html5/'.intval($result1['aid']).'/html5/index.html#';

				if($adcodeDisplayType == 1)//Interstitial
				{
					if(isset($result1['html5']) && $result1['html5'] == 1 && $result1['cta_support'] == 1)
					$srcurl = $srcurl.$clksurl;
					
					
				}
				else
				{
					if($result1['type'] == 7)
					$this->ecommerceFlag = 1;
					else
					{
						if($expandable_enabled ==1 && isset($result1['expandable']) && $result1['expandable'] ==1 && $result1['expandable_banner'] !="" && $expandable_width >0 && $expandable_height >0)
						$expandableflag = 1;

						if($expandableflag == 1)
						{
							$expandableEnter    = 1;
							$bannerpath   		= $base_url.DATA_DIR."/".intval($result1['aid'])."_exp_".$result1['expandable_banner'];

							$expandableContent  = array('operation'=>'expandable_open','auid'=>$adcodeID,'bannerpath'=>$this->mybase64_encode(trim($bannerpath)),'width'=>$expandable_width,'height'=>$expandable_height,'clksurl'=>$clksurl,'background_color'=>$borderColor,'color'=>$creditColor);
							$expandableContent  = json_encode($expandableContent);


			        		$this->adListArray[3][intval($result1['aid'])][] = $expandableContent;
						}

						if(isset($result1['html5']) && $result1['html5'] ==1 && $result1['type'] == 2 && $result1['cta_support'] ==1)
						$srcurl = $srcurl.$clksurl;
					}
				}
			}


			if($adcodeType == 1 || $adcodeType == 2 || $adcodeType == 11 || $adcodeType == 13)
			{
				$adIDList[] 								   			= intval($result1['aid']);
				$displayAdContent[$result1['aid']]['adID']     			= intval($result1['aid']);
				$displayAdContent[$result1['aid']]['adType']   			= intval($result1['type']);

				if($result1['display_type'] == 2)
				$displayAdContent[$result1['aid']]['htmlAd'] 			= 1;
			    else
			    $displayAdContent[$result1['aid']]['htmlAd'] 			= 0;

				if(isset($result1['html5']) && $result1['html5'] == 1)
				{
					$displayAdContent[$result1['aid']]['html5'] = 1;

					$displayAdContent[$result1['aid']]['cta_support'] 	= intval($result1['cta_support']);
				}
			    else
			    {
			    	$displayAdContent[$result1['aid']]['html5'] 		= 0;
			    	$displayAdContent[$result1['aid']]['cta_support'] 	= 0;
			    }

			    if(isset($result1['retargetid']))
				$displayAdContent[$result1['aid']]['retargetID']   		= intval($result1['retargetid']);
				else
				$displayAdContent[$result1['aid']]['retargetID'] = 0;

				$displayAdContent[$result1['aid']]['advertiserAd'] 		= $adOriginal;

				if(isset($result1['duration_seconds']))
				$displayAdContent[$result1['aid']]['duration_seconds'] 	= intval($result1['duration_seconds']);
				else
				$displayAdContent[$result1['aid']]['duration_seconds'] 	= 0;

				if(isset($result1['mime_type']))
				$displayAdContent[$result1['aid']]['mime_type']  		= $result1['mime_type'];
				else
				$displayAdContent[$result1['aid']]['mime_type']  		= "";

				$displayAdContent[$result1['aid']]['buttonText'] 		= $result1['cta_button_text'];
				$displayAdContent[$result1['aid']]['title']  			= $result1['title'];
				$displayAdContent[$result1['aid']]['description']  		= $result1['description'];
				$displayAdContent[$result1['aid']]['displayUrl']  		= $result1['display_url'];

				$displayAdContent[$result1['aid']]['mediaUrl']  		= $bannerUrlPath;
				if(isset($result1['render_content']) && $result1['render_content'] != '')
				$displayAdContent[$result1['aid']]['renderContent']  		= $result1['render_content'];
				else
				$displayAdContent[$result1['aid']]['renderContent']  		= "";

				if($feedGetSuccess == 1 && isset($result1['pixel_url']) && $result1['pixel_url'] != '')
				$displayAdContent[$result1['aid']]['pixelUrl']  		= $result1['pixel_url'];
				else
				$displayAdContent[$result1['aid']]['pixelUrl']  		= "";

				if($exchangeGetSuccess == 1 && isset($result1['impression_url']) && $result1['impression_url'] != '')
				$displayAdContent[$result1['aid']]['impressionUrl']  = $result1['impression_url'];
				else
				$displayAdContent[$result1['aid']]['impressionUrl']  = "";

				$displayAdContent[$result1['aid']]['srcUrl']  			= $srcurl;


				if($feedGetSuccess == 1)
				$displayAdContent[$result1['aid']]['feedAds']  		= 1;
				else
				$displayAdContent[$result1['aid']]['feedAds']  		= 0;

				if($exchangeGetSuccess == 1)
				$displayAdContent[$result1['aid']]['exchangeAds']  		= 1;
				else
				$displayAdContent[$result1['aid']]['exchangeAds']  		= 0;


				if($result1['type'] == 7)
				$displayAdContent[$result1['aid']]['clickUrl']  		= $clks_gallery;
				else
				$displayAdContent[$result1['aid']]['clickUrl']  		= $clksurl;


				$displayAdContent[$result1['aid']]['expandable']  		= intval($expandableflag);
			}
			else if($adcodeType == 14)
			$adIDList[] 								   			    = intval($result1['aid']);

			$i++;
		}
	}




	if(count($displayAdContent) > 0)
	{
		if($native == 0)
		{
			if($adcodeType == 13)
			{
				$adcodeResultData['width']    		= $player_width;
				$adcodeResultData['height']   		= $player_height;
			}
			else
			{
				$adcodeResultData['width']    		= $result['width'];
				$adcodeResultData['height']   		= $result['height'];
			}


			$adcodeResultData['nativeHeading'] 		= "";
 			$adcodeResultData['nativeRows'] 		= 0;
			$adcodeResultData['nativeColumns'] 		= 0;
			$adcodeResultData['responsiveSupport'] 	= 0;
			$adcodeResultData['nativeAdsCount'] 	= 0;
		}
		else
		{
			if($nativeResponsiveSupport == 0)
			{
				if($nativeAdsCount <= $nativeColumns)
				{
					$nativeRows     = 1;
					$nativeColumns  = $nativeAdsCount;
				}
			    else
		    	$nativeRows     = ceil($nativeAdsCount / $nativeColumns);
			}
			else
			{
				$nativeColumns	   = 1;
				$nativeRows        = 1;
			}


			$adcodeResultData['width']  			= 0;
			$adcodeResultData['height'] 			= 0;
			$adcodeResultData['nativeHeading'] 		= $nativeHeader;
			$adcodeResultData['nativeRows'] 		= $nativeRows;
			$adcodeResultData['nativeColumns'] 		= $nativeColumns;
			$adcodeResultData['responsiveSupport'] 	= $nativeResponsiveSupport;
			$adcodeResultData['nativeAdsCount'] 	= $nativeAdsAllowed;
		}

		$adcodeResultData['adcodeID'] 			    = $adcodeID;
		$adcodeResultData['adLayoutType'] 			= $adcodeType;
		$adcodeResultData['imagePosition'] 			= $imagePosition;
		$adcodeResultData['borderType']   			= $borderType;
		$adcodeResultData['textimageWidth']  		= $textimageWidth;
		$adcodeResultData['textimageHeight'] 		= $textimageHeight;
		$adcodeResultData['displayAds'] 			= $native;


		$adcodeResultData['creditText'] 			= $creditText;
		$adcodeResultData['creditType'] 			= $creditType;
		$adcodeResultData['creditIcon'] 			= $creditIcon;
		$adcodeResultData['creditIconType'] 		= $creditIconType;
		$adcodeResultData['creditAlignment'] 		= $creditalignment;
		$adcodeResultData['creditPositioning'] 		= $creditposition;
		$adcodeResultData['creditUrl'] 				= $creditLink;


		echo $this->get_adlayout_preview($adcodeResultData,$displayAdContent,$adLayoutArray,$fontArray,$themeArray,$adSectionWidth,$adcodeDisplayType);
	}

	if($normalImpressionFlag == 1)
	{
		if($display_type ==0 || $display_type ==1 || $display_type ==3 || $display_type ==6)
		{
			?>
			{IMPRESSION}
		    <?php
		}
	 }

	if($defaultImpressionFlag == 1){?>
	{IMPRESSIONDEFAULT}
    <?php }?>

    {JSCRIPT-REPLACE}


	<?php
	}
}

$this->adListArray 			= json_encode($this->adListArray);

if($automaticRotation == 1 && $automaticRotationInterval > 0 && ($adcodeType == 1 || $adcodeType == 2 || $adcodeType == 11 || $adcodeType == 14) && ($display_type == 0 || $display_type == 1 || $display_type == 3 || $display_type == 6) && $totalAdsResultCount > 1 && ($native == 0 || ($native == 1 && $totalAdCountAllowed == 1)))
{
	$this->adRotationEnabled 	= 1;
	?>
	{IMPRESSIONSRCURL}
<?php
}

if($adcodeType == 14){?>
{SKINCONTENT}
<?php }

if($expandable_enabled == 1 && $adcodeType == 2){?>

{EXPANDABLECONTENT}

<?php }





if($adcodeType != 14){?>
</div>

<?php if($native == 1){?>
</div>
<?php } ?>

<?php }?>


</body>
</html>



<script data-cfasync="false" language="javascript" type="text/javascript">
var rotationRunning    = 1;
var fullAdsRendered    = 0;
var rotationIndex      = 0;
var rotationIndexTemp  = 0;


<?php if($expandableEnter == 1){?>
function LoadExpandableData(adID)
{
	if($("#expandable-content-"+adID).length > 0)
	{
		expandableContentValue = $("#expandable-content-"+adID).val();
		if(expandableContentValue != "")
		{
			rotationRunning = 0;
			window.parent.postMessage(expandableContentValue,"*");
		}
	}
}
<?php } ?>
<?php if($this->ecommerceFlag == 1){?>
function SlideLeft(adid)
{
	marginleft=$(".slideinner-style").css("left");
	marginleft=marginleft.replace("px");
	currentindex=$("#currentindex-"+adid).val();
	if(currentindex ==1)
	return;
	$(".sliderightinner-style").show();
	if(parseInt(currentindex)-1 == 1)
	$(".slideleftinner-style").hide();
	$("#currentindex-"+adid).val(parseInt(currentindex)-1);
	holderwidth=$(".slideinnerholder-style").css("width");
	holderwidth=holderwidth.replace("px");
	$(".slideinner-style").animate({"left": (parseInt(marginleft)+parseInt(holderwidth))+"px"}, "slow");
}
function SlideRight(adid)
{
	marginleft=$(".slideinner-style").css("left");
	marginleft=marginleft.replace("px");
	holderwidth=$(".slideinnerholder-style").css("width");
	holderwidth=holderwidth.replace("px");
	currentindex=$("#currentindex-"+adid).val();
	blockcount=$("#blockcount-"+adid).val();
	if(currentindex == blockcount)
	return;
	$(".slideleftinner-style").show();
	if(parseInt(currentindex)+1 == blockcount)
	$(".sliderightinner-style").hide();
	$("#currentindex-"+adid).val(parseInt(currentindex)+1);
	$(".slideinner-style").animate({"left": (parseInt(marginleft)-parseInt(holderwidth))+"px"}, "slow");
}
<?php } ?>
<?php if($adcodeType != 14){?>
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

<?php }?>

<?php if($native == 1 && $nativeAdsCount > 0){?>

	var adcodeID     		= <?php echo $aduid;?>;
	var	totalAdsCount   	= <?php echo $nativeAdsCount;?>;
	var responsiveSupport   = <?php echo $nativeResponsiveSupport;?>;
	var borderType          = <?php echo $borderType;?>;
	var	columnCount	 	    = <?php echo $nativeColumns;?>;
	var	rowsCount	 	    = <?php echo $nativeRows;?>;
	var adSectionWidth      = <?php echo $adSectionWidth;?>;
	var imagePosition       = <?php echo intval($imagePosition);?>;
	var buttonSectionWidth  = <?php echo $buttonSectionWidth;?>;
	var CTAPosition         = <?php echo $CTAPosition;?>;
	var adsMargin           = 5;

	$(document).ready(function()
	{
		if($('.ad-display-section').length > 0)
		{
			headerHeight = 0;
			if($('.native-head').length > 0)
			headerHeight = $('.native-head').outerHeight();
			if(headerHeight == 0)
			headerHeight = 60; //Direct assign
			if(responsiveSupport == 0)
			{
			adSectionWidth = parseFloat($('.native-outer-div').css("width"));
			    if(adSectionWidth > 0)
			    adSectionTotalWidth = adSectionWidth;
			    else
			    {
			    	if(adSectionWidth == 0 && $('#minimumWidth').length > 0)
			    	adSectionWidth    = $('#minimumWidth').val();
			    	adSectionTotalWidth = adSectionWidth * columnCount;
			    }
			}
			else
			{
			    if(adSectionWidth == 0 && $('#minimumWidth').length > 0)
			    adSectionWidth    = $('#minimumWidth').val();
			    adSectionTotalWidth = adSectionWidth;
			}
			adSectionHeight      = parseFloat($('.native-outer-div').innerHeight());
			if(adSectionHeight > 0)
			adSectionTotalHeight = adSectionHeight;
			else
			{
			     if(adSectionHeight == 0 && $('#minimumHeight').length > 0)
			     adSectionHeight      = $('#minimumHeight').val();
			     if(responsiveSupport == 0)
	 		     adSectionTotalHeight = adSectionHeight * rowsCount;
			     else
			     adSectionTotalHeight = adSectionHeight * totalAdsCount;	//Rows count is not important in responsive case
			}

			if(borderType == 2) //No border
			{
				adCodeWidth  = adSectionTotalWidth;
				adCodeHeight = parseFloat(adSectionTotalHeight) + parseFloat(headerHeight);
			}
			else
			{
				if(responsiveSupport == 0)
				adCodeWidth  = adSectionTotalWidth + 2;	//2px for outer border
				else
				adCodeWidth  = adSectionTotalWidth;

				adCodeHeight = parseFloat(adSectionTotalHeight) + 2 + parseFloat(headerHeight);
			}

			window.parent.postMessage('{"operation":"nativedata","auid":'+adcodeID+',"width":'+adCodeWidth+',"height":'+adCodeHeight+',"nativeResponsive":'+responsiveSupport+'}','*');
		}


		if(responsiveSupport == 1)
		{
			var eventMethod  = window.addEventListener ? "addEventListener" : "attachEvent";
			var eventer      = window[eventMethod];
			var messageEvent = eventMethod == "attachEvent" ? "onmessage" : "message";
			eventer(messageEvent,  function(e)
			{
				var response	= e.data;

				try
				{
					responsedata=JSON.parse(response);

					if(responsedata != "")
					{
					   if(responsedata.adcodeID == adcodeID && responsedata.operation == 'nativeResize')
					   {
					   		minimumWidth    = $('#minimumWidth').val(); //Single ad section width in px with margin/border
					   		minimumHeight   = $('#minimumHeight').val();

					   		textimageWidth  = $('#textimageWidth').val();
					   		textimageHeight = $('#textimageHeight').val();

					   		adSectionWidth = responsedata.currentWidth;

					   		borderMinus = 0;

					   		if(borderType != 2)
					   		borderMinus = 2;

					   		adSectionWidth = adSectionWidth - borderMinus;


							if(adSectionWidth >= minimumWidth)
							{
								if(totalAdsCount > 1)
								{
									widthDivide  = adSectionWidth / minimumWidth;

									if(parseInt(widthDivide) >= totalAdsCount)
									{
										columnCount    = totalAdsCount;
										rowsCount      = 1;
									}
									else
									{
										columnCount    = parseInt(widthDivide);
										rowsCount      = Math.ceil(totalAdsCount / columnCount);
									}

									minimumWidth = adSectionWidth / columnCount;
								}
								else //Only one ad
								{
									minimumWidth = adSectionWidth;

									rowsCount      = 1;
									columnCount    = 1;
								}
							}
							else //Section width less than minimum width
							{


								rowsCount      = totalAdsCount;
								columnCount    = 1;
							}

							oneAdSectionWidth  		= minimumWidth - (adsMargin * 2) - borderMinus;
							oneAdSectionHeight 		= minimumHeight - (adsMargin * 2) - borderMinus;

							totalOuterWidth    		= minimumWidth  * columnCount;
							totalOuterHeight   		= minimumHeight * rowsCount;

							headerHeight = 0;

							if($('.native-head').length > 0)
							headerHeight = $('.native-head').outerHeight();
							if(headerHeight == 0)
							headerHeight = 60; //Direct assign

							totalOuterHeight   		= totalOuterHeight + headerHeight;

							totalOuterWidthPlus    	= totalOuterWidth + borderMinus;
							totalOuterHeightPlus   	= totalOuterHeight + borderMinus;

							$('.native-outer-div').css("width",totalOuterWidth+"px");
							$('.native-outer-div').css("height",totalOuterHeight+"px");

							$('.content-box-section').css("width",oneAdSectionWidth+"px");

							if($('.ad-layout-image').length > 0)
							{
								if(imagePosition == 1 || imagePosition == 3)
								$('.ad-layout-image').css("width",oneAdSectionWidth+"px");

								$('.contentDivOuter').css("width",oneAdSectionWidth+"px");

								if(imagePosition == 0 || imagePosition == 2)

									divOuterSecondWidth = oneAdSectionWidth - textimageWidth;
								else

									divOuterSecondWidth = oneAdSectionWidth;

							}
							else
							{
								$('.contentDivOuter').css("width",oneAdSectionWidth+"px");

								divOuterSecondWidth = oneAdSectionWidth;
							}


							if(CTAPosition == 1)
							divOuterSecondWidth = divOuterSecondWidth - buttonSectionWidth;


							if($('.ad-layout-content').length > 0)
							$('.ad-layout-content').css("width",divOuterSecondWidth+"px");


							if(CTAPosition == 2)
							{
								$('.ad-layout-button').css("width",divOuterSecondWidth+"px");
							}
							else if(CTAPosition == 3)
							{
								divOuterSecondWidth = divOuterSecondWidth - buttonSectionWidth;

								$('.ad-layout-title').css("width",divOuterSecondWidth+"px");
							}
							else if(CTAPosition == 4)
							{
								divOuterSecondWidth = divOuterSecondWidth - buttonSectionWidth;

								$('.ad-layout-description').css("width",divOuterSecondWidth+"px");
							}
							else if(CTAPosition == 5)
							{
								divOuterSecondWidth = divOuterSecondWidth - buttonSectionWidth;

								$('.ad-layout-url').css("width",divOuterSecondWidth+"px");
							}

							else if(CTAPosition == 6)
							{
								divOuterSecondWidth = divOuterSecondWidth - buttonSectionWidth;

								$('.ad-layout-content-inner').css("width",divOuterSecondWidth+"px");
							}
						    window.parent.postMessage('{"operation":"nativedata","auid":'+adcodeID+',"width":'+totalOuterWidthPlus+',"height":'+totalOuterHeightPlus+',"nativeResponsive":'+responsiveSupport+'}','*');
					   }
					}
				} catch (e) {}
			}, false);
		}

	});
<?php }?>

<?php
if($automaticRotation == 1 && $automaticRotationInterval > 0 && ($adcodeType == 1 || $adcodeType == 2 || $adcodeType == 11 || $adcodeType == 14) && ($display_type == 0 || $display_type == 1 || $display_type == 3 || $display_type == 6) && $totalAdsResultCount > 1 && ($native == 0 || ($native == 1 && $totalAdCountAllowed == 1))){?>

	$(document).ready(function()
	{
	var adcodeID     	 = <?php echo intval($aduid);?>;
	var adcodeType 		 = <?php echo intval($adcodeType);?>;
	var trackInterval    = <?php echo intval($trackInterval); ?>;
	var	rotationInterval = <?php echo intval($automaticRotationInterval * 1000);?>; 
		var adIDList         = '<?php echo json_encode($adIDList);?>';
		var adIDListParse    = JSON.parse(adIDList);
		var adIDListLength   = adIDListParse.length;
		var firstAdID        = {FIRSTADID};

		<?php if($adcodeType == 2){?>
		var eventMethod  = window.addEventListener ? "addEventListener" : "attachEvent";
		var eventer      = window[eventMethod];
		var messageEvent = eventMethod == "attachEvent" ? "onmessage" : "message";
		eventer(messageEvent,  function(e)
		{
			var response = e.data;

			try
			{
				responsedata=JSON.parse(response);

				if(responsedata != "")
				{
				   if(responsedata.adcodeID == adcodeID && responsedata.operation == 'rotationEnable')
		   		   rotationRunning = 1;
				}
			} catch (e) {}
		}, false);
		<?php } ?>


		//First display ad id moves to first index of js array
		if(firstAdID > 0)
		{
			keyIndex = adIDListParse.indexOf(firstAdID);

			if(keyIndex > -1) //If this item exists, remove it and append to first key
			adIDListParse.splice(keyIndex, 1);

			adIDListParse.splice(0, 0, firstAdID);

			rotationIndex       = 1;
		}


		var adcodeRotation   = setInterval(function()
		{
			if(rotationRunning == 1)
			{
				if(rotationIndex >= adIDListLength)
				{
					rotationIndex      = 0;
					fullAdsRendered    = 1;
				}

				if(adIDListParse[rotationIndex] > 0 && ((adcodeType != 14 && $('#content-box-section-'+adIDListParse[rotationIndex]).length > 0) || adcodeType == 14))
				{
					//First ad impression tracking url already created
					//After completing one rotation impression tracking not required
		if(adcodeType != 14 && rotationIndex > 0 && fullAdsRendered == 0 && $('#ad-rotation-'+adIDListParse[rotationIndex]).val() != "")
		window.setTimeout(scriptAppend,	trackInterval, adIDListParse[rotationIndex]);



					if(adcodeType != 14)
					{
						$('.content-box-section').hide();
						$('#content-box-section-'+adIDListParse[rotationIndex]).show();
					}
					else
					{
						if($("#skin-content-"+adIDListParse[rotationIndex]).length > 0)
						{
							skinContentValue = $("#skin-content-"+adIDListParse[rotationIndex]).val();

							if(skinContentValue != "")
							{
								window.parent.postMessage(skinContentValue,"*");
								rotationIndexTemp++;
							}
						}
					}
				}

				rotationIndex++;

			}
		}, rotationInterval);




		<?php if($adcodeType == 14){?>

			var eventMethod  = window.addEventListener ? "addEventListener" : "attachEvent";
			var eventer      = window[eventMethod];
			var messageEvent = eventMethod == "attachEvent" ? "onmessage" : "message";
			eventer(messageEvent,  function(e)
			{
				var response = e.data;

				try
				{
					responsedata=JSON.parse(response);

					if(responsedata != "" && responsedata.adcodeID == adcodeID && responsedata.operation == "skinAdRendered")
					{
						if(rotationIndexTemp > 0 && fullAdsRendered == 0)
						window.setTimeout(scriptAppend,	trackInterval, responsedata.adid);
		        	}
				} catch (e) {}
			}, false);

		<?php } ?>



		function scriptAppend(adIDResponse)
		{
			if($("#ad-rotation-"+adIDResponse).length > 0)
			{
				 var script 	  = document.createElement("script");
				 	 script.setAttribute("data-cfasync","false");
				 	 script.type  = "text/javascript";
				   script.async = 1;
					 script.src    = $("#ad-rotation-"+adIDResponse).val();
					 document.getElementsByTagName("head")[0].appendChild(script);
			}
		}


	});

<?php }?>

</script>
<?php }?>
