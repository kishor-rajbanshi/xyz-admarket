<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/preview.js'></script>
<?php
$resFontExternal        = $this->get_result("resFontExternal");

$res=$this->get_result('res');
$val=$res[0];
$type=$val['type'];
$banner=$val['banner'];


$html5=intval($val['html5']);

$textimageName = "";
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

$aid         = $this->get_variable('aid');
$frompg      = $this->get_variable('frompg');

$adPricing   = $val['display_type'];
$bannerListArray = array();
?>
<style type="text/css">
<?php foreach($resFontExternal as $fkey=>$fvalue){?>
@font-face {
  font-family: <?php echo $fvalue['slug_name'];?>;
  src: url(<?php echo BASE.DATA_DIR.'/fonts/'.$fvalue['file_name'];?>);
}
<?php } ?>
</style>

<?php if($type == 7){?>
	<style type="text/css">
		.modal-dialog {width:90%;margin:30px auto;}
		.modal-body{max-height:700px;}

		.data_table{text-align:center;}

		.dummytitle{color: <?php echo $val['title_color'];?>;}
		.dummydescription{color: <?php echo $val['desc_color'];?>;}
		.dummyurl{color: <?php echo $val['url_color'];?>;}
		.dummyprice{color: <?php echo $val['price_color'];?>;}
		.dummyofferprice{color: <?php echo $val['offer_price_color'];?>;}

		.dummybutton {color: <?php echo $val['ab_text_color'];?>;background-color: <?php echo $val['ab_background_color'];?>;}
		.dummybutton:hover {background-color: <?php echo $val['ab_bhover_color'];?>;}

		.span-text{color:<?php echo $val['ah_text_color'];?>;}

		.span-button-preview{color:<?php echo $val['abh_text_color'];?>;background-color: <?php echo $val['abh_background_color'];?>;}
		.span-button-preview:hover {background-color: <?php echo $val['abh_bhover_color'];?>;}

		.display-outer-style{border-color:<?php echo $val['border_color'];?>;background-color: <?php echo $val['background_color'];?>;}
		.singleadsection-style{border-color:<?php echo $val['border_color'];?>;background-color: <?php echo $val['ad_background_color'];?>;}

		.slideleftinner-style, .sliderightinner-style {border-color:<?php echo $val['border_color'];?>;color:<?php echo $val['border_color'];?>;background-color: <?php echo $val['background_color'];?>;}
	</style>
<?php }?>


<div class="row m-0 mt-1 mb-3 p-0">
	<div class="col-md-12 col-sm-12 col-xs-12">

		<?php
		if($adPricing != 3 &&  $adPricing != 18 && ($type == 2 || $type == 7))
		{
		    if($type == 2)
			{
				 	if($html5 == 0){?>
					<span>
						<input type="button" class="submit-button previewSectionShow preview-button" onclick="$('.previewSection').toggle();" value="<?php echo $this->get_label('show preview');?>" />
					</span>
					<div class="previewCloseDiv" onclick="HidePreviewBox();" style="display: none;">
						<span>x</span>
					</div>
					<div class="previewSection" style="display:none">
						<div class="ad-popup">
							<a href="<?php echo $val['click_url'];?>">
								<img class="img-fluid" alt="<?php echo $this->get_label('banner');?>" src="<?php echo DATA_DIR;?>/<?php echo $aid;?>_<?php echo $banner;?>" />
							</a>

							<?php if($expandable == 1){?>
								<div class="ad-popup-div">
									<img class="img-fluid" alt="<?php echo $this->get_label('banner');?>" src="<?php echo DATA_DIR;?>/<?php echo $aid;?>_exp_<?php echo $expandable_banner;?>" />
								</div>
							<?php }?>
						</div>
					</div>				  
				    <?php } else {

						$res1 = $this->get_result('res1');
						$val1 = $res1[0];
						?>

						<input id="button-html5-show" type="button" class="submit-button" onclick="LoadHTML5Preview('<?php echo BASE.DATA_DIR."/html5/".$aid."/html5/index.html";?>');" value="<?php echo $this->get_label('show preview');?>" />

						<input id="button-html5-hide" type="button" class="submit-button" onclick="HideHTML5Preview();" value="<?php echo $this->get_label('hide preview');?>" style="display: none;" />

						<div id="iframe-html5-div" class="iframe-html5-div" style="width: <?php echo $val1['width'];?>px;height: <?php echo $val1['height'];?>px;display: none;">
								<img id="iframe-loader" src="images/load.gif" style="display:none;" />
								<iframe id="iframe-html5" class="html5-iframe" src="" style="width: <?php echo $val1['width'];?>px;height: <?php echo $val1['height'];?>px;"></iframe>
						</div>

				<?php }

			} else { ?>

				<div class="row mb-2">
					<div class="layout-div-create">
						<div class="mb-2">
							<?php echo $this->get_display_layout_name($val['display_layout']);?>
						</div>

						<span class="show-span">
							<span class="submit-button" onclick="LoadLayoutPreview(<?php echo $val['display_layout'];?>);">
								<?php echo $this->get_label('show preview');?>
							</span>
						</span>

						<span class="hide-span">
							<span class="submit-button" onclick="LoadLayoutPreview(0);">
								<?php echo $this->get_label('hide preview');?>
							</span>
						</span>
					</div>

					<div class="layout-preview" id="layout-preview-<?php echo $val['display_layout'];?>">
						<?php echo $this->get_display_preview($val['display_layout'],$aid,0);?>
					</div>
				</div>

		<?php }
		} else if($adPricing == 18 && $type == 18) {

				$ad_details   = json_decode($this->getAdDetails($aid),1);
				$img_icon_url = BASE.DATA_DIR."/notification_icons/".$aid."_".$ad_details['banner'];
				?>
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
					  <div class="push-banner-div float-start">
						  	<img src="<?php echo $img_icon_url;?>" />
						</div>
						<div class="mt-3">
						  	<div class="ad-layout-title"><?php echo $ad_details['title'];?></div>
						  	<div class="ad-layout-description"><?php echo $ad_details['description'];?></div>
						</div>
					</div>
				</div>
		<?php } else if($type == 9 || $type == 21){ ?>

		<a onclick="LoadPop('<?php echo $val['click_url'];?>');"><?php echo $val['click_url'];?></a>

		<?php } else if($type == 12 || $type == 14 || ($adPricing == 3 && $type == 2)) {

			if($type == 14)
			{
					$json_array = $this->get_array('json_array');

					$imagerow   = $json_array[0];
			}
			else if($adPricing == 3)
			{
				$bannerListArray = json_decode($val['banner_list'],1);

				$imagerow        = $bannerListArray;
			}
			else
			$imagerow = $this->get_result('imagerow');


			if($adPricing == 3){?>
			<div class="col-md-12 col-sm-12 col-xs-12">
				<span>
					<input type="button" class="submit-button previewSectionShow preview-button" onclick="$('.previewSection').toggle();" value="<?php echo $this->get_label('show preview');?>" />
				</span>
				<div class="previewCloseDiv" onclick="HidePreviewBox();" style="display: none;">
					<span>x</span>
				</div>
				<div class="previewSection" style="display:none">
					<div class="ad-popup">
						<a href="<?php echo $val['click_url'];?>">
							<img class="img-fluid" alt="<?php echo $this->get_label('banner');?>" src="<?php echo DATA_DIR;?>/<?php echo $aid;?>_<?php echo $banner;?>" />
						</a>

						<?php if($expandable == 1){?>
							<div class="ad-popup-div">
								<img class="img-fluid" alt="<?php echo $this->get_label('banner');?>" src="<?php echo DATA_DIR;?>/<?php echo $aid;?>_exp_<?php echo $expandable_banner;?>" />
							</div>
						<?php }?>
					</div>
				</div>	
			</div>
			<?php } else { ?>
				<span class="submit-button" data-bs-toggle="modal" data-bs-target="#Affiliate-Preview"><?php echo $this->get_label('preview');?></span>
			<?php }

	 		} else if($type == 1 || $type == 11) {

				if($type == 11)
				{
						if($adPricing ==3)
						{
							$bannerListArray = json_decode($val['banner_list'],1);

							$imagerow = $bannerListArray;
						}

						if(file_exists(DATA_DIR.'/'.$val['id'].'_'.$val['banner']))
						$textimageName = $val['id'].'_'.$val['banner'];
				}
				?>

			<div>
				<span>
					<input type="button" class="submit-button previewSectionShow preview-button" onclick="AdPreview();" value="<?php echo $this->get_label('show preview');?>" />
					<img id="loading" src="images/load.gif" style="display: none;" />
				</span>
				<div class="previewCloseDiv" onclick="HidePreviewBox();" style="display: none;">
					<span>x</span>
				</div>
				<div class="previewSection" style="display: none;"></div>
			</div>

		<?php } else if($type ==13){ ?>

			<video width="300" height="225" controls >
					<source src="<?php echo DATA_DIR.'/video/'.$val['id'].'/'.$val['banner'];?>" type="<?php echo $val['mime_type'];?>"></source>
					<?php echo $this->get_label('your browser does not support HTML5 video');?>
			</video>

		<?php }?>

	</div>

	<?php if($adPricing == 3 || $type == 12 || $type == 14){?>
	<div class="modal fade" id="Affiliate-Preview" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

		<div class="modal-dialog modal-xl">
	     <div class="modal-content">
      		<div class="modal-header">
        		<h5 class="modal-title" id="exampleModalLabel">
	        		<?php
		        		if($type == 14)
		        		echo $this->get_label('skin ads banners');
		        	  else if($adPricing == 3)
		        	  echo $this->get_label('ad banners');
		        		else
		        		echo $this->get_label('manage affiliate banners');
	        		?>
        		</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      		</div>

    	  	<div class="modal-body">

	      		<div class="col-md-12 col-sm-12 col-xs-12">
	      			<div id="delete-message" style="display: none;"></div>
	      		</div>

						<div class="col-md-12 col-sm-12 col-xs-12" style="overflow: auto;max-height: 450px;">

						<?php if(!is_array($imagerow) || count($imagerow) == 0){?>

							<div class="col-md-12 col-sm-12 col-xs-12">
								<?php echo $this->get_label('no banners found');?>
							</div>

						<?php } else { ?>

						<?php
						foreach($imagerow as $key1=>$value1)
						{
								$bannerpath  = '';
								$bannerID    = 0;
								$fromData    = 0;

								if($type == 14)
								{
										if(file_exists(DATA_DIR.'/'.$aid.'/'.$value1))
										$bannerpath=DATA_DIR.'/'.$aid.'/'.$value1;
								}
								else if($adPricing == 3)
								{
										$bannerID = $key1;
										$fromData = 1;

										if(file_exists(DATA_DIR.'/'.$aid.'/'.$value1))
										$bannerpath=DATA_DIR.'/'.$aid.'/'.$value1;
								}
								else
								{
										$bannerID = $value1['id'];
										$fromData = 0;

										$currentimage = $this->get_banner_name($aid,$value1['banner_size']);
										if($currentimage != "")
										{
												if(file_exists(DATA_DIR.'/banners/'.$aid.'/'.$currentimage))
												$bannerpath=DATA_DIR.'/banners/'.$aid.'/'.$currentimage;
										}
								}

								if($bannerpath != ""){?>
								<div class="col-md-12 col-sm-12 col-xs-12 mb-3" <?php if($adPricing ==3 || $type ==12){?>id="div-dimension-<?php echo $bannerID;?>"<?php }?>>
									<div class="mb-3 section-sub-heading">
											<bdi>
												<?php
												if($type == 14)
												{
														$array=explode('_',$value1);

														if(isset($array[0]) && isset($array[1]))
														echo $array[0].' x '.$array[1];
												}
												else if($adPricing ==3)
												{
												    echo str_replace("-", " x ",$this->get_banner_dimension($key1));
												}
												else
												echo $value1['width'].' x '.$value1['height'];

											  if($adPricing == 3 || $type == 12)
												{
											 			if($val['banner_id'] != $bannerID)
														{?>
																<i class="fa fa-times delete-icon"  onclick="DeleteImage('<?php echo $bannerID;?>',<?php echo $fromData;?>);" title="<?php echo $this->get_label('delete');?>" alt="<?php echo $this->get_label('delete');?>"></i>

																<span id="load<?php echo $bannerID;?>" style="display: none;">
																	<img src="images/load.gif" />
																</span>
														<?php }
												}
												?>
											</bdi>
									</div>
									<div>
											<img class="img-fluid" style="max-width:700px;" alt="<?php echo $this->get_label('banner');?>" src="<?php echo $bannerpath;?>" />
									</div>
								</div>
								<?php
									}
							 }
						}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php } ?>

	<?php
	$trackDomain              = $this->get_track_domain(0);
	$trackDomainWwithProtocol = $this->get_track_domain(0, 1);

	if($frompg == 3 && $val['display_type'] == 6){
		
		$conversionTrackingType = Configuration::get_instance()->read('cpa_conversion_tracking_type');
		?>

	<div class="col-md-12 col-sm-12 col-xs-12 mt-3 p-0">

	<?php if($conversionTrackingType == 2){?>
	<div class="col-lg-12 col-sm-12 col-md-12 col-12">
	<?php } else { ?>
	<div class="col-lg-6 col-sm-6 col-md-6 col-12">
	<?php } ?>
	
	
	<div class="tab-nav">
		<?php 
		$direction = $this->get_locale_direction();
		$tabCount = 1;
		
		if($conversionTrackingType == 2)
		$tabCount = 2;		

		$tabWidth = round((100 / $tabCount), 2);
		$index = 0; ?>
		<?php if($conversionTrackingType == 0 || $conversionTrackingType == 2){?>
			<button class="tab-btn tab-btn-<?php echo $index; ?>" data-label="<?php echo $index; ?>" onClick="manageTabClicks('user', 'conversion_code', <?php echo $index;?>, <?php echo $tabCount;?>, <?php echo $direction;?>);"><span><?php echo $this->get_label('conversion tracking using js');?></span></button>
		<?php  
		$index++;
		}?>

		<?php 
		if($conversionTrackingType == 1 || $conversionTrackingType == 2){
		?>
			<button class="tab-btn tab-btn-<?php echo $index; ?>" data-label="<?php echo $index; ?>" onClick="manageTabClicks('user', 'conversion_code', <?php echo $index;?>, <?php echo $tabCount;?>, <?php echo $direction;?>);"><span><?php echo $this->get_label('conversion tracking using postback url');?></span></button>
		<?php  }?>
		<div class="tab-indicator" id="tabIndicator" style="width: <?php echo $tabWidth; ?>%;"></div>
    </div></div>
	
	<?php if($conversionTrackingType == 0 || $conversionTrackingType == 2){?>
	<div class="p-2 manage-ad-edit table-outer-box report-div-tab report-div-tab-0" style="border-top-left-radius: 0px;">
		<div class="form-check">
			<input type="radio" class="form-check-input mt-1" value="1" checked="checked" />
			<label class="form-check-label"><?php echo $this->get_label('landing tracking code');?></label>

			<div class="float-end mb-2 trackingcodecopy">
				<i class="fa fa-clipboard fa-lg tracking-icon" aria-hidden="true" id="trackingcode3" title="<?php echo $this->get_label('copy code');?>"></i>
			</div>
		</div>


	<textarea id="textarea-trackingclick" class="form-control" rows="4" readonly="readonly"><!-- <?php  echo Configuration::get_instance()->read('admarket_name');?> - <?php echo $this->get_label('landing tracking code');?> -->
<script data-cfasync="false" type="text/javascript" src="<?php echo $trackDomain.TRACK_DIR; ?>/click.php?<?php echo $aid; ?>"></script>
<!-- <?php  echo Configuration::get_instance()->read('admarket_name');?> - <?php echo $this->get_label('landing tracking code');?>  -->
</textarea>
	<div class="notification p-0"><?php echo $this->get_label('please copy landing code');?></div>

	<div class="form-check mt-3">
		<input type="radio" class="form-check-input mt-1" name="conversion_tracking" id="conversion_tracking1" value="1" checked="checked" />
		<label class="form-check-label"><?php echo $this->get_label('conversion tracking code');?></label>

		<div id="dtrackingcode" class="float-end mb-2 trackingcodecopy">
			<i class="fa fa-clipboard fa-lg tracking-icon" aria-hidden="true" id="trackingcode1" title="<?php echo $this->get_label('copy code');?>"></i>
		</div>
	</div>



	<textarea id="textarea-trackingcode" class="form-control" rows="10" readonly="readonly"><!-- <?php  echo Configuration::get_instance()->read('admarket_name');?> - <?php echo $this->get_label('tracking code');?> -->
<script data-cfasync="false" type="text/javascript">
var script=document.createElement("script");
script.setAttribute("data-cfasync","false");
script.type 	= "text/javascript";
script.async	= "false";
var _coName		= "_tracking_"+<?php echo $aid; ?>;
var _coStart 	= document.cookie.indexOf(_coName + "=");
var _coLength	= _coStart + _coName.length + 1;
if((!_coStart) && (_coName != document.cookie.substring(0,_coName.length)))
var _dataValue	= null;
else if(_coStart == -1)
var _dataValue	= null;
else {
		var _coEnd = document.cookie.indexOf(";",_coLength);
		if(_coEnd == -1)
		_coEnd = document.cookie.length;
		var _dataValue= unescape(document.cookie.substring(_coLength,_coEnd));
}
if(_dataValue != null && _dataValue != ""){
    var _dataList   = _dataValue.split("-");
    if(_dataList[0] == <?php echo $aid;?>){
    script.src="<?php echo $trackDomain.TRACK_DIR; ?>/index.php?page=action/conversion/"+_dataValue;
	  document.getElementsByTagName("head")[0].appendChild(script);
}}
</script>
<!-- <?php  echo Configuration::get_instance()->read('admarket_name');?> - <?php echo $this->get_label('tracking code');?>  --></textarea>

	<div class="notification p-0"><?php echo $this->get_label('please copy conversion code');?></div>
	</div>
	<?php } ?>


	<?php if($conversionTrackingType == 1 || $conversionTrackingType == 2){?>
	<div class="p-2 manage-ad-edit table-outer-box report-div-tab report-div-tab-1" style="border-top-left-radius: 0px;">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="float-end mb-2 trackingcodecopy">
				<i class="fa fa-clipboard fa-lg tracking-icon" aria-hidden="true" id="trackingcode2" title="<?php echo $this->get_label('copy code');?>"></i>
			</div>

			<textarea id="textarea-trackingurl"  class="form-control" rows="2" readonly="readonly"><?php echo $trackDomainWwithProtocol.TRACK_DIR; ?>/index.php?page=action/conversion/{POSTBACK-DATA}</textarea>
		
			<div class="notification p-0"><?php echo $this->get_label('conversion tracking note1');?></div>
			<div class="notification p-0"><?php echo $this->get_label('conversion tracking note2');?></div>
			<div class="notification p-0"><?php echo $this->get_label('conversion tracking note3');?></div>

		</div>
	</div>
	<?php } ?>	

	</div>
<?php }?>

</div>

<?php if($type == 9 || $type == 21){?>
<script type="text/javascript">
function LoadPop(url)
{
	window.open(url,'_blank'); //New Tab
}
</script>
<?php }?>
<script type="text/javascript">
function DeleteImage(bannerID,fromData)
{
	if(confirm("<?php echo $this->get_message('do you really want to delete this ad banner');?>"))
	{
		$("#load"+bannerID).show();

		aid = <?php echo $aid;?>;

		dataparam="bannerID="+bannerID+"&aid="+aid+"&fromData="+fromData;

		var urlvalue='<?php echo $this->make_url("ad/delete_banner");?>';

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
					message="<?php echo $this->get_message('ad banner delete success');?>";

					$('#div-dimension-'+bannerID).remove();
				}


				$('#delete-message').html(message);

				if(msg ==1)
				$('#delete-message').css('color','red');
				else
				$('#delete-message').css('color','green');

				$('#delete-message').show();


				$("#load"+bannerID).hide();

				setTimeout(function(){
					$('#delete-message').slideUp();
				},1500);
			}
		});
	}
}


function LoadLayoutPreview(id)
{
	if(id >0)
	{
		if($('#layout-preview-'+id).css('display') =='none')
		{
			$('.layout-preview').show();
			$('#layout-preview-'+id).css('display','block');

		}
		else
		{
			$('.layout-preview').hide();
			$('#layout-preview-'+id).css('display','none');
		}

		$('.show-span').hide();
		$('.hide-span').show();
	}
	else
	{
		$('.layout-preview').hide();
		$('.hide-span').hide();
		$('.show-span').show();
	}
}




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



<?php if($val['display_type'] == 6){?>
	$(document).ready(function() {
		manageTabClicks('user', 'conversion_code', 0, <?php echo $tabCount;?>, <?php echo $direction;?>);
	});
<?php } ?>


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

<?php if($val['display_type'] ==6){?>
$("#trackingcode1").click(function(){
	   $("#textarea-trackingcode").select();
	   document.execCommand('copy');
	});

$("#trackingcode2").click(function(){
	   $("#textarea-trackingurl").select();
	   document.execCommand('copy');
	});

$("#trackingcode3").click(function(){
	   $("#textarea-trackingclick").select();
	   document.execCommand('copy');
	});
<?php }?>

function HidePreviewBox()
{
	if($('.previewSection').length > 0)
	{
		$('.previewSection').html('');
		$('.previewCloseDiv').hide();
		$(".previewSectionShow").show();
	}
}

function AdPreview()
{
    titleEnabled               = 0;
    descriptionEnabled         = 0;
    urlEnabled                 = 0;
    textimageSize 			   = 0;
    bannerName                 = "";

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

    dataparam    = "adType="+adType+"&titleEnabled="+titleEnabled+"&descriptionEnabled="+descriptionEnabled+"&urlEnabled="+urlEnabled+"&titleText="+titleText+"&descriptionText="+descriptionText+"&displayurlText="+displayurlText+"&buttonText="+buttonText+
        "&textimageSize="+textimageSize+"&bannerName="+bannerName;

    var urlvalue = '<?php echo $this->make_url("ad/load_ad_preview");?>';

    $.ajax(
    {
        type: "POST",
        data: dataparam,
        url: urlvalue,
        success: function(message)
        {
            $("#loading").hide();
            $(".previewSectionShow").hide();

            $('.previewSection').show();
            $('.previewCloseDiv').show();

            $('.previewSection').html(message);
        }
    });
}
</script>
