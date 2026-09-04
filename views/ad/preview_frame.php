<?php $this->dispatch("layout/header_iframe");?>

<?php
$aid=$this->get_variable('aid');

$res=$this->get_result('res');
$val=$res[0];
$type=$val['type'];
$banner=$val['banner'];

$html5=intval($val['html5']);

$expandable_banner="";
$expandable=0;
$expandable_enabled=$this->get_addon_status('expandable-banners_enabled');

if($type ==2 && $expandable_enabled ==1)
{
	$expandable_banner=$val['expandable_banner'];
	$expandable=$val['expandable'];

	if($expandable_banner =="")
	$expandable=0;
}


if($type ==2 || $type ==7 || $type ==11)
{
	$res1=$this->get_result('res1');
	$val1=$res1[0];
}

$bannerList       = $val['banner_list'];

$bannerListArray  = array();

if($bannerList != "")
$bannerListArray  = json_decode($bannerList,1);


$resFontExternal    = $this->get_result("resFontExternal");
$adLayoutID         = intval($this->get_variable("adLayoutID"));
$adcodeID           = intval($this->get_variable("adcodeID"));
$textimageName      = "";
?>
<style type="text/css">
<?php foreach($resFontExternal as $fkey=>$fvalue){?>
@font-face {
  font-family: <?php echo $fvalue['slug_name'];?>;
  src: url(<?php echo BASE.DATA_DIR.'/fonts/'.$fvalue['file_name'];?>);
}
<?php } ?>
</style>



<?php if($type ==7){?>
<style type="text/css">
.dummytitle{color: <?php echo $val['title_color'];?> !important;}
.dummydescription{color: <?php echo $val['desc_color'];?> !important;}
.dummyurl{color: <?php echo $val['url_color'];?> !important;}
.dummyprice{color: <?php echo $val['price_color'];?> !important;}
.dummyofferprice{color: <?php echo $val['offer_price_color'];?> !important;}

.dummybutton {color: <?php echo $val['ab_text_color'];?> !important;background-color: <?php echo $val['ab_background_color'];?> !important;}
.dummybutton:hover {background-color: <?php echo $val['ab_bhover_color'];?> !important;}

.span-text{color:<?php echo $val['ah_text_color'];?> !important;}

.span-button-preview{color:<?php echo $val['abh_text_color'];?> !important;background-color: <?php echo $val['abh_background_color'];?> !important;}
.span-button-preview:hover {background-color: <?php echo $val['abh_bhover_color'];?> !important;}

.display-outer-style{border-color:<?php echo $val['border_color'];?> !important;background-color: <?php echo $val['background_color'];?> !important;}
.singleadsection-style{border-color:<?php echo $val['border_color'];?> !important;background-color: <?php echo $val['ad_background_color'];?> !important;}

.slideleftinner-style, .sliderightinner-style {border-color:<?php echo $val['border_color'];?> !important;color:<?php echo $val['border_color'];?> !important;background-color: <?php echo $val['background_color'];?> !important;}
</style>
<?php }?>

<?php
if($type == 2 || $type == 7)
{
		if($type ==2)
		{
 				if($html5 == 0)
				{
						if(count($bannerListArray) > 0)
						{
								foreach($bannerListArray as $bKey=>$bValue)
								{
 										if(file_exists(DATA_DIR.'/'.$aid.'/'.$bValue)){?>
											<div class="ad-popup">
												<div><?php echo str_replace("-", " x ",$this->get_banner_dimension($bKey));?></div>
												<div>
													<a href="<?php echo $val['click_url'];?>">
														<img style="max-width: 500px;max-height: 500px;" alt="<?php echo $this->get_label('banner');?>" src="<?php echo DATA_DIR.'/'.$aid.'/'.$bValue;?>" />
													</a>
												</div>
											</div>
									  <?php }
							  }
						} else { ?>
							<div class="ad-popup">
								<a href="<?php echo $val['click_url'];?>">
									<img style="max-width: 500px;max-height: 500px;" alt="<?php echo $this->get_label('banner');?>" src="<?php echo DATA_DIR.'/'.$aid.'_'.$banner;?>" />
								</a>

								<?php if($expandable == 1){?>
									<div class="ad-popup-div">
										<img style="max-width: 500px;max-height: 500px;" alt="<?php echo $this->get_label('banner');?>" src="<?php echo DATA_DIR.'/'.$aid.'_exp_'.$expandable_banner;?>" />
									</div>
								<?php }?>
							</div>
					 <?php }
				 } else { ?>

						<div class="ad-popup" style="max-width: 150px;">

							<input id="button-html5-show" type="button" class="submit-button" onclick="LoadHTML5Preview('<?php echo BASE.DATA_DIR."/html5/".$aid."/html5/index.html";?>');" value="<?php echo $this->get_label('show preview');?>" />

							<input id="button-html5-hide" type="button" class="submit-button" onclick="HideHTML5Preview();" value="<?php echo $this->get_label('hide preview');?>" style="display: none;" />

							<div id="iframe-html5-div" class="iframe-html5-div" style="width: <?php echo $val1['width'];?>px;height: <?php echo $val1['height'];?>px;display: none;">
								<img id="iframe-loader" src="images/load.gif" style="display:none;" />
								<iframe id="iframe-html5" class="html5-iframe" src="" style="width: <?php echo $val1['width'];?>px;height: <?php echo $val1['height'];?>px;"></iframe>
							</div>

						</div>

				<?php }
			} else { ?>

				<div class="row mb-2">
					<div class="layout-div-create">
						<div class="mb-2">
							<?php echo $this->get_display_layout_name($val['display_layout']);?>
						</div>
					</div>

					<div id = "layout-preview-<?php echo $val['display_layout'];?>">
						 <?php echo $this->get_display_preview($val['display_layout'],$aid,0);?>
	</div>

</div>

			<?php }
 		}
		else if($type == 1 || $type == 11)
		{
				if($type == 11)
				{
						if(file_exists(DATA_DIR.'/'.$val['id'].'_'.$val['banner']))
						$textimageName = $val['id'].'_'.$val['banner'];
				}
				?>
				<img id="loading" src="images/load.gif" style="display: none;" />

				<div class="previewSection" style="display: none;"></div>
		<?php
		} else if($type == 14) {

		$json_array = $this->get_array('json_array');

		$imagerow   = $json_array[0];

		foreach($imagerow as $key1 => $value1)
		{
				$bannerpath='';

				if(file_exists(DATA_DIR.'/'.$aid.'/'.$value1))
				$bannerpath=DATA_DIR.'/'.$aid.'/'.$value1;

		    if($bannerpath != ""){?>
				<div class="mb-3">
					<bdi>
						<?php
							$array = explode('_',$value1);

							if(isset($array[0]) && isset($array[1]))
							echo $array[0].' x '.$array[1];
						?>
					</bdi>
				</div>

				<div>
						<img class="img-fluid" style="max-width:700px;" alt="<?php echo $this->get_label('banner');?>" src="<?php echo $bannerpath;?>" />
				</div>
			<?php
		    }
		}
}
?>

<script type="text/javascript">
function SlideLeft(direction)
{
	//direction => 0 ltr
	//direction => 1 rtl

	marginleft=$(".slideinner-style").css("left");
	marginleft=marginleft.replace('px');
	currentindex=$('#currentindex').val();
	blockcount=$('#blockcount').val();

	if(currentindex ==1)
	return;

	if(direction ==0)
	$('.sliderightinner-style').show();
	else if(direction ==1)
	$('.slideleftinner-style').show();

	if(parseInt(currentindex)-1 == 1 && direction ==0)
	$('.slideleftinner-style').hide();
	else if(parseInt(currentindex)-1 == 1 && direction ==1)
	$('.sliderightinner-style').hide();

	$('#currentindex').val(parseInt(currentindex)-1);


	holderwidth=$('.slideinnerholder-style').css('width');
	holderwidth=holderwidth.replace('px');


	$(".slideinner-style").animate({"left": (parseInt(marginleft)+parseInt(holderwidth))+"px"}, "slow");
}


function SlideRight(direction)
{

	//direction => 0 ltr
	//direction => 1 rtl

	marginleft=$(".slideinner-style").css("left");
	marginleft=marginleft.replace('px');
	holderwidth=$('.slideinnerholder-style').css('width');
	holderwidth=holderwidth.replace('px');
	currentindex=$('#currentindex').val();
	blockcount=$('#blockcount').val();

	if(currentindex == blockcount)
	return;

	if(direction ==0)
	$('.slideleftinner-style').show();
	else if(direction ==1)
	$('.sliderightinner-style').show();

	if(parseInt(currentindex)+1 == blockcount && direction ==0)
	$('.sliderightinner-style').hide();
	else if(parseInt(currentindex)+1 == blockcount && direction ==1)
	$('.slideleftinner-style').hide();

	$('#currentindex').val(parseInt(currentindex)+1);
	$(".slideinner-style").animate({"left": (parseInt(marginleft)-parseInt(holderwidth))+"px"}, "slow");

}

function LoadHTML5Preview(srcpath)
{
	$('#button-html5-show').hide();
	$('#button-html5-hide').show();
	$('#iframe-html5').attr("src",srcpath);
	$('#iframe-html5-div').show();
	$('#iframe-loader').show();
}

function HideHTML5Preview()
{
	$('#button-html5-show').show();
	$('#button-html5-hide').hide();
	$('#iframe-html5-div').hide();
	$('#iframe-html5').attr("src","");
	$('#iframe-loader').hide();
}


<?php if($type == 1 || $type == 11){?>
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

function AdPreview()
{
    titleEnabled               = 0;
    descriptionEnabled         = 0;
    urlEnabled                 = 0;
    textimageSize 			   = 0;
    bannerName                 = "";

    adLayoutID                 = <?php echo $adLayoutID; ?>;
    adcodeID                   = <?php echo $adcodeID; ?>;
    titleText                  = "<?php echo $val['title']; ?>";
    descriptionText            = "<?php echo $val['description']; ?>";
    displayurlText             = "<?php echo $val['display_url']; ?>";
	buttonText 				   = "<?php echo $val['cta_button_text']; ?>";
	adType 					   = <?php echo $type; ?>;


	if(adType == 11)
	{
		textimageSize              = <?php echo $val['banner_id']; ?>;
		bannerName                 = "<?php echo $textimageName; ?>";
	}

    if(titleText != "")
   	titleEnabled           = 1;

    if(descriptionText != "")
   	descriptionEnabled     = 1;

    if(displayurlText != "")
   	urlEnabled             = 1;

    if((titleEnabled == 0 && descriptionEnabled == 0 && urlEnabled == 0) || (adType != 1 && adType != 11))
    {
        $('.previewSection').html("");
        return;
    }


    $("#loading").show();

    dataparam    = "adLayoutID="+adLayoutID+"&adcodeID="+adcodeID+"&adType="+adType+"&titleEnabled="+titleEnabled+"&descriptionEnabled="+descriptionEnabled+"&urlEnabled="+urlEnabled+"&titleText="+titleText+"&descriptionText="+descriptionText+"&displayurlText="+displayurlText+"&buttonText="+buttonText+"&textimageSize="+textimageSize+"&bannerName="+bannerName;

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

$(document).ready(function() {
	AdPreview();
});
<?php } ?>
</script>
<?php $this->dispatch("layout/footer_iframe");?>
