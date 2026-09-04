<?php
$this->dispatch("layout/header/2/2/a");

$cpc_enabled            = $this->get_addon_status('cpc_enabled');
$cpm_enabled            = $this->get_addon_status('cpm_enabled');
$cpa_enabled            = $this->get_addon_status('cpa_enabled');
$cpp_enabled            = $this->get_addon_status('cpp_enabled');
$pop_enabled            = $this->get_addon_status('pop-ads_enabled');
$directlink_enabled 	= $this->get_addon_status('direct-link-ads_enabled');
$sponsored              = $this->get_addon_status('sponsored_enabled');
$affiliate_enabled      = $this->get_addon_status('affiliate-ads_enabled');
$retargeting_enabled    = $this->get_addon_status('retargeting_enabled');
$textimage_enabled      = $this->get_addon_status('text-image-ads_enabled');
$category_enabled       = $this->get_addon_status('category-targeting_enabled');
$time_target_enabled    = $this->get_addon_status('time-targeting_enabled');
$city_enabled           = $this->get_addon_status('city-targeting_enabled');
$language_enabled       = $this->get_addon_status('language-targeting_enabled');
$isp_enabled            = $this->get_addon_status('isp-targeting_enabled');
$connection_enabled     = $this->get_addon_status('connectiontype-targeting_enabled');
$deviceenabled          = $this->get_addon_status('device-targeting_enabled');
$expandable_enabled     = $this->get_variable('expandable_enabled');
$expandable             = $this->get_variable('expandable');
$video_enabled          = $this->get_variable('video_enabled');
$linear_support         = $this->get_variable('linear_support');
$nonlinear_support      = $this->get_variable('nonlinear_support');
$html5_player_support   = $this->get_variable('html5_player_support');
$skin_enabled           = $this->get_variable('skin_enabled');
$html5_enabled          = $this->get_variable('html5_enabled');
$ecommerce_enabled      = $this->get_variable('ecommerce_enabled');
$positionID             = $this->get_variable('positionID');
$positionAdType         = $this->get_variable('positionAdType');
$positionAdcodeType     = $this->get_variable('positionAdcodeType');
$additional_banners     = $this->get_variable('additional_banners');


$bannerAdSupport        = intval($this->get_variable('bannerAdSupport'));
$textimageAdSupport     = intval($this->get_variable('textimageAdSupport'));
$ecommerceAdSupport     = intval($this->get_variable('ecommerceAdSupport'));
$positionBannerSize     = intval($this->get_variable('positionBannerSize'));
$positionTextImageSize  = intval($this->get_variable('positionTextImageSize'));
$adblockID              = intval($this->get_variable('adblockID'));
$cpdRate                = $this->get_variable('cpdRate');
$sitePid 								= intval($this->get_variable('sitePid'));
$text_ads_enabled       = Configuration::get_instance()->read('text-ads_enabled');
$cpdPackageDays         = Configuration::get_instance()->read('cpd_package_days');

$resFontExternal        = $this->get_result("resFontExternal");


$firstParameter         = intval($this->get_variable('firstParameter'));
$cloneFlag              = intval($this->get_variable('cloneFlag'));
$parentAdID             = intval($this->get_variable('parentAdID'));


if($positionID > 0)
{
			$cpdProfitPercentage     = $this->get_variable("cpdProfitPercentage");

	    $cpd_rate_including_admin_profit = Configuration::get_instance()->read('cpd_rate_including_admin_profit');

	    if($sitePid == 0 || $cpd_rate_including_admin_profit == 1)
	    $calculatedRate          = $cpdRate;
	    else
	    $calculatedRate          = ($cpdRate * 100)/$cpdProfitPercentage;

			$cpdPackageDaysArray     = explode(",",$cpdPackageDays);
}


if($positionID > 0)
{
	$cpc_enabled            = 0;
	$cpm_enabled            = 0;
	$cpa_enabled            = 0;
	$pop_enabled            = 0;
	$directlink_enabled     = 0;
	$affiliate_enabled      = 0;
	$retargeting_enabled    = 0;
	$city_enabled           = 0;
	$language_enabled       = 0;
	$isp_enabled            = 0;
	$connection_enabled     = 0;
	$deviceenabled          = 0;
	$video_enabled          = 0;

	if($ecommerceAdSupport == 0)
    $ecommerce_enabled = 0;

	if($textimageAdSupport == 0)
    $textimage_enabled = 0;
}


$allowed_js_urls="";
$allowed_font_urls="";

	if($html5_enabled==1)
	{
		$allowed_js_urls	= trim(Configuration::get_instance()->read('allowed_js_urls'));
		$allowed_font_urls	= trim(Configuration::get_instance()->read('allowed_font_urls'));
	}


	$jsarray=array();
	if($allowed_js_urls !="")
	{
		$jsarray1=explode(',',$allowed_js_urls);

		foreach($jsarray1 as $k1=>$v1)
		{
			if(trim($v1) !="")
			$jsarray[]=strtolower(trim($v1));
		}
	}

	$jsarray[]=strtolower(BASE.DISPLAY_DIR."/js/cta.js");

	$i=0;
	$string='';
	foreach($jsarray as $k=>$v)
	{
		if($i ==0)
		$string.='<div class="text-decoration-underline mb-1">'.$this->get_label('supported remote js').'</div>';

		$string.='<div>'.$v.'</div>';

		$i=$i+1;
	}



	$jsarray=array();
	if($allowed_font_urls !="")
	{
		$jsarray1=explode(',',$allowed_font_urls);

		foreach($jsarray1 as $k1=>$v1)
		{
			if(trim($v1) !="")
			$jsarray[]=strtolower(trim($v1));
		}
	}

	$ii=0;
	$string1='';
	foreach($jsarray as $k=>$v)
	{
		if($ii ==0)
		$string1.='<div class="text-decoration-underline mb-1">'.$this->get_label('supported remote fonts').'</div>';

		$string1.='<div>'.$v.'</div>';

		$ii=$ii+1;
	}

	$string=$string.$string1;


$banner_type=intval($this->get_variable('banner_type'));
$uid=$this->get_variable('uid');


$retargeting=$this->get_variable('retargeting');
$vast_support=$this->get_variable('vast_support');

$file_type=intval($this->get_variable('file_type'));
$rowid=$this->get_variable('rowid');

$maxtitle   		= Configuration::get_instance()->read('max_ad_title_length');
$maxdesc    		= Configuration::get_instance()->read('max_ad_desc_length');
$maxdispurl 	    = Configuration::get_instance()->read('max_display_url_length');
$ctaButtonLength 	= Configuration::get_instance()->read('cta_button_text_length');


$isp_success=$this->get_variable('isp_success');

$stringarray=array();
if($category_enabled ==1)
{
	$category_enabled_ads=Configuration::get_instance()->read('category_enabled_ads');

	if($category_enabled_ads !='')
	$stringarray=explode('_',$category_enabled_ads);
}




$banner_dimension_count=$this->get_banner_dimension_count(2);






if($textimage_enabled ==1)
{
	$textimage_dimension_count=$this->get_banner_dimension_count(11);

	if($textimage_dimension_count ==0)
	$textimage_enabled=0;
}



if($ecommerce_enabled ==1)
{
	$display_layout_count=$this->get_display_layout_count();

	$ecommerce_dimension_count=$this->get_banner_dimension_count(7);

	if($display_layout_count ==0 || $ecommerce_dimension_count ==0)
	$ecommerce_enabled=0;
}

if($skin_enabled ==1)
{
	$skin_dimension_count=$this->get_banner_dimension_count(14);

	if($skin_dimension_count ==0)
	$skin_enabled=0;
}

$sizeflag=0;


$color1=$this->get_variable('color1');
$color2=$this->get_variable('color2');
$color3=$this->get_variable('color3');
$color4=$this->get_variable('color4');
$color5=$this->get_variable('color5');
$color6=$this->get_variable('color6');
$color7=$this->get_variable('color7');
$color8=$this->get_variable('color8');
$color9=$this->get_variable('color9');
$color10=$this->get_variable('color10');
$color11=$this->get_variable('color11');
$color12=$this->get_variable('color12');
$color13=$this->get_variable('color13');
$color14=$this->get_variable('color14');
$color15=$this->get_variable('color15');
$color16=$this->get_variable('color16');
?>

<style type="text/css">
<?php foreach($resFontExternal as $fkey=>$fvalue){?>
@font-face {
  font-family: <?php echo $fvalue['slug_name'];?>;
  src: url(<?php echo BASE.DATA_DIR.'/fonts/'.$fvalue['file_name'];?>);
}
<?php } ?>

.maxsize { display:none }

.pop-option
{
	display: none;
}

.directlink-option
{
	display: none;
}
.affiliate-option
{
	display: none;
}
.ad-popup-div
{
	display:none;
	position: absolute;
	padding: 5px;
	min-height: 30px;
	min-width: 50px;
	border: 1px solid #CCCCCC;
	background-color: #FFFFFF;
	color: #4E4E4E !important;
	font-size: 14px !important;
	right: 7px;
	z-index:999;
}

.ad-popup-div .title
{
	color: #c52d2f !important;
	padding: 2px 0;
	text-decoration: underline;
	height: 30px;
}

.ad-popup:hover .ad-popup-div
{
	display:block;
}

.dummytitle-style{color: <?php echo $color1;?>;}
.dummydescription-style{color: <?php echo $color2;?>;}
.dummyurl-style{color: <?php echo $color3;?>;}
.dummyprice-style{color: <?php echo $color7;?>;}
.dummyofferprice-style{<?php echo $color8;?>;}
.dummybutton-style {color: <?php echo $color9;?>;}
.span-text{color:<?php echo $color12;?>;}
.span-button{color:<?php echo $color13;?>;}
.display-outer-style{border-color:<?php echo $color4;?>;background-color: <?php echo $color5;?>;}
.singleadsection-style{border-color:<?php echo $color4;?>;background-color: <?php echo $color6;?>;}
</style>
<script type="text/javascript">
$(document).ready(function() {
	CreateResponsiveTable('table-desktop');

	$(window).resize(function()
	{
	 	CreateResponsiveTable('table-desktop');

		if($('.retarget-div').length >0)
		{
			if(pricing == 0 || (pricing == 6 && type != 12))
			{
				$('.retarget-td-pop').show();

				if($('.retarget-tr-pop').length >0)
				$('.retarget-tr-pop').show();
			}
			else
			{
				$('.retarget-td-pop').hide();

				if($('.retarget-tr-pop').length >0)
				$('.retarget-tr-pop').hide();
			}
		}
	});
});

function ChangeRadioType(data)
{
	$('#banner_type_value').val(data);

	type=$('#type').val();

	if(type == 2)
	LoadMaxSize('02');

	if(data == 1)
	{
		if($('.spec_tr_expandable').length > 0)
		$('.spec_tr_expandable').hide();

		if($('.spec_tr_expandable_exp').length >0)
		$('.spec_tr_expandable_exp').hide();

		if($('.expandable_checkbox').length > 0)
		$('.expandable_checkbox').prop('checked',false);

		if($('.vast-div').length > 0)
		{
			$('.vast-div').hide();
			$('#vast_support').prop('checked',false);
		}
	}
}


<?php if($ecommerce_enabled ==1){?>
$(document).ready(function() {

	var focusid = "";
	var oldselected = "";

	$('.color-picker').change(function()
	{
		focusid=this.id;
  		colorchange = true;

  		$('#'+focusid).css("background-color",$('#'+focusid).val());

  		focuscolor=$('#'+focusid).val();

        ApplyColor(focusid,focuscolor);
	});

  	$('.color-picker').click(function()
  	{
  		focusid=this.id;
  		colorchange = true;

  		oldselected = $('#'+focusid).val();

  		$('.color-picker').removeClass('color-selected');
  		$('#'+focusid).addClass('color-selected');
	});

	ApplyColor("","");
});


function ApplyColor(focusid,focuscolor)
{

	if(focusid =="" && focuscolor =="")
	{
		$('<style type="text/css">.dummybutton-style {background-color:'+$('#color10').val()+';}</style>').appendTo('head');
		$('<style type="text/css">.dummybutton-style:hover {background-color:'+$('#color11').val()+';}</style>').appendTo('head');

		$('<style type="text/css">.span-button {background-color:'+$('#color14').val()+';}</style>').appendTo('head');
		$('<style type="text/css">.span-button:hover {background-color:'+$('#color15').val()+';}</style>').appendTo('head');
	}
	else
	{
		if(focusid == 'color1')
		$('.dummytitle-style').css('color',focuscolor);
		else if(focusid == 'color2')
		$('.dummydescription-style').css('color',focuscolor);
		else if(focusid == 'color3')
		$('.dummyurl-style').css('color',focuscolor);
		else if(focusid == 'color4')
		{
			$('.display-outer-style').css('border-color',focuscolor);
			$('.singleadsection-style').css('border-color',focuscolor);
		}
		else if(focusid == 'color5')
		$('.display-outer-style').css('background-color',focuscolor);
		else if(focusid == 'color6')
		$('.singleadsection-style').css('background-color',focuscolor);
		else if(focusid == 'color7')
		$('.dummyprice-style').css('color',focuscolor);
		else if(focusid == 'color8')
		$('.dummyofferprice-style').css('color',focuscolor);
		else if(focusid == 'color9')
		$('.dummybutton-style').css('color',focuscolor);
		else if(focusid == 'color10')
		{
			currentcolor00=focuscolor;
			$('<style type="text/css">.dummybutton-style {background-color:'+currentcolor00+';}</style>').appendTo('head');
		}
		else if(focusid == 'color11')
		{
			currentcolor0=focuscolor;
			$('<style type="text/css">.dummybutton-style:hover {background-color:'+currentcolor0+';}</style>').appendTo('head');
		}
		else if(focusid == 'color12')
		$('.span-text').css('color',focuscolor);
		else if(focusid == 'color13')
		$('.span-button').css('color',focuscolor);
		else if(focusid == 'color14')
		{
			currentcolor11=focuscolor;
			$('<style type="text/css">.span-button {background-color:'+currentcolor11+';}</style>').appendTo('head');
		}
		else if(focusid == 'color15')
		{
			currentcolor1=focuscolor;
			$('<style type="text/css">.span-button:hover {background-color:'+currentcolor1+';}</style>').appendTo('head');
		}
		//else if(focusid == 'color16')
		//$('.singleadsection-style').css('background-color',focuscolor);

	}
}



<?php }?>


function RemoveRow(id)
{
	rowid=$('#row-id').val();
	rowidarray=rowid.split('-');

	rowidarraycount=rowidarray.length;

	if(rowidarraycount ==1)
	{
		alert('<?php echo $this->get_message('minimum one row required');?>');
		return false;
	}

	string="";
	for(i=0;i < rowidarraycount;i++)
	{
		if(rowidarray[i] != id)
		{
			if(string !="")
			string+='-';

			string+=rowidarray[i];
		}
	}


	$('#row-id').val(string);


	if($('#tr-id-'+id).length >0)
	$('#tr-id-'+id).remove();

	if($('#table-tr-id-'+id).length >0)
	$('#table-tr-id-'+id).remove();
}



function ChangeFileType(type)
{

	pricing=$('#adpricing').val();



	if(type == 0)
	{
		$('.ecommerce_csv').show();
		$('.ecommerce_pop').hide();


		$('.file-size-class').html('');
	}
	else
	{
		$('.ecommerce_csv').hide();
		$('.ecommerce_pop').show();


		rowid=$('#row-id').val();
		rowidarray=rowid.split('-');

		rowidarray.sort(function(a,b){return a-b});

		rowidarraycount=rowidarray.length;

		rowvalue=0;

		if(type ==2)
		{
			rowvalue=parseInt(rowidarray[rowidarraycount-1])+1;

			rowidarray.push(rowvalue);

			rowidarraycount=parseInt(rowidarraycount)+1;


			$('#row-id').val(rowid+'-'+rowvalue);
		}



		for(i=0;i < rowidarraycount;i++)
		{


			ecommerce_ad_title="";
			ecommerce_ad_description="";
			ecommerce_ad_display_url="";
			ecommerce_ad_click_url="";
			ecommerce_image_url="";
			ecommerce_ad_retargeting_url="";
			ecommerce_ad_price="";
			ecommerce_ad_offer_price="";


			if(rowidarray[i] != rowvalue && type ==1)
			{
				ecommerce_ad_title=$('#ecommerce_ad_title_'+rowidarray[i]).html();
				ecommerce_ad_description=$('#ecommerce_ad_description_'+rowidarray[i]).html();
				ecommerce_ad_display_url=$('#ecommerce_ad_display_url_'+rowidarray[i]).html();
				ecommerce_ad_click_url=$('#ecommerce_ad_click_url_'+rowidarray[i]).html();
				ecommerce_image_url=$('#ecommerce_image_url_'+rowidarray[i]).html();


				if(pricing ==0 || pricing ==6)
				ecommerce_ad_retargeting_url=$('#ecommerce_ad_retargeting_url_'+rowidarray[i]).html();


				ecommerce_ad_price=$('#ecommerce_ad_price_'+rowidarray[i]).html();
				ecommerce_ad_offer_price=$('#ecommerce_ad_offer_price_'+rowidarray[i]).html();
			}


			if($('#table-desktop #tr-id-'+rowidarray[i]).length ==0)
			{

                string='<tr id="tr-id-'+rowidarray[i]+'" class="data_table_content">';
                string+='<td ><input type="text" class="form-control" maxlength="<?php echo $maxtitle;?>" name="ad_title_'+rowidarray[i]+'" id="ad_title_'+rowidarray[i]+'" value="'+ecommerce_ad_title+'" /></td>';
                string+='<td ><input type="text" class="form-control" maxlength="<?php echo $maxdesc;?>" name="ad_description_'+rowidarray[i]+'" id="ad_description_'+rowidarray[i]+'" value="'+ecommerce_ad_description+'" /></td>';
                string+='<td ><input type="text" class="form-control" maxlength="<?php echo $maxdispurl;?>" name="ad_display_url_'+rowidarray[i]+'" id="ad_display_url_'+rowidarray[i]+'" value="'+ecommerce_ad_display_url+'" /></td>';
                string+='<td ><input type="text" class="form-control" name="ad_click_url_'+rowidarray[i]+'" id="ad_click_url_'+rowidarray[i]+'" value="'+ecommerce_ad_click_url+'" /></td>';


				<?php if($retargeting_enabled ==1){?>
				string+='<td class="retarget-td-pop"><input type="text" class="form-control" name="ad_retargeting_url_'+rowidarray[i]+'" id="ad_retargeting_url_'+rowidarray[i]+'" value="'+ecommerce_ad_retargeting_url+'" /></td>';
				<?php }?>


				string+='<td ><input type="text" class="form-control" name="image_url_'+rowidarray[i]+'" id="image_url_'+rowidarray[i]+'" value="'+ecommerce_image_url+'" /></td>';
				string+='<td ><input type="text" class="form-control" name="ad_price_'+rowidarray[i]+'" id="ad_price_'+rowidarray[i]+'" value="'+ecommerce_ad_price+'" style="width: 75px;" /></td>';
				string+='<td ><input type="text" class="form-control" name="ad_offer_price_'+rowidarray[i]+'" id="ad_offer_price_'+rowidarray[i]+'" value="'+ecommerce_ad_offer_price+'" style="width: 75px;" /></td>';
				string+='<td >';
				string+='<a class="link_button" onclick="ChangeFileType(2);" title="<?php echo $this->get_label('add row');?>">+</a>&nbsp;<a class="link_button" onclick="RemoveRow('+rowidarray[i]+');" title="<?php echo $this->get_label('remove row');?>">x</a>';
				string+='</td>';
				string+='</tr>';


				$('#table-desktop').append(string);
			}



			if($('#table-desktop-mobile').length >0 && $('#table-desktop-mobile #table-tr-id-'+rowidarray[i]).length ==0)
			{

				string1='<tr><td>';
				string1+='<table id="table-tr-id-'+rowidarray[i]+'">';
				string1+='<tbody>';
				string1+='<tr>';
				string1+='<td class="table-head-responsive"><?php echo $this->get_label('title').' ['.$this->get_label('max characters',array('x'=>$maxtitle)).']';?></td>';
				string1+='<td><input maxlength="<?php echo $maxtitle;?>" name="ad_title_'+rowidarray[i]+'" id="ad_title_'+rowidarray[i]+'" value="'+ecommerce_ad_title+'" type="text" class="form-control"></td>';
				string1+='</tr>';
				string1+='<tr>';
				string1+='<td class="table-head-responsive"><?php echo $this->get_label('description').' ['.$this->get_label('max characters',array('x'=>$maxdesc)).']';?><span class="compulsory">*</span></td>';
				string1+='<td><input maxlength="<?php echo $maxdesc;?>" name="ad_description_'+rowidarray[i]+'" id="ad_description_'+rowidarray[i]+'" value="'+ecommerce_ad_description+'" type="text" class="form-control" ></td>';
				string1+='</tr>';
				string1+='<tr>';
				string1+='<td class="table-head-responsive"><?php echo $this->get_label('display url').' ['.$this->get_label('max characters',array('x'=>$maxdispurl)).']';?><span class="compulsory">*</span></td>';
				string1+='<td><input maxlength="<?php echo $maxdispurl;?>" name="ad_display_url_'+rowidarray[i]+'" id="ad_display_url_'+rowidarray[i]+'" value="'+ecommerce_ad_display_url+'" type="text" class="form-control"></td>';
				string1+='</tr>';
				string1+='<tr>';
				string1+='<td class="table-head-responsive"><?php echo $this->get_label('landing page');?><span class="compulsory">*</span></td>';
				string1+='<td><input name="ad_click_url_'+rowidarray[i]+'" id="ad_click_url_'+rowidarray[i]+'" value="'+ecommerce_ad_click_url+'" type="text" class="form-control"></td>';
				string1+='</tr>';

				<?php if($retargeting_enabled ==1){?>
				string1+='<tr class="retarget-tr-pop">';
				string1+='<td class="table-head-responsive"><?php echo $this->get_label('retargeting url');?></td>';
				string1+='<td><input name="ad_retargeting_url_'+rowidarray[i]+'" id="ad_retargeting_url_'+rowidarray[i]+'" value="'+ecommerce_ad_retargeting_url+'" type="text" class="form-control"></td>';
				string1+='</tr>';
				<?php }?>

				string1+='<tr>';
				string1+='<td class="table-head-responsive"><?php echo $this->get_label('image path');?>';
				string1+='<div class="notification" style="font-weight: normal;"><bdi>[<?php echo $this->get_label('supported image format ecommerce');?>]</bdi></div>';

				string1+='<div class="notification" style="font-weight: normal;"><span class="file-size-class"><bdi></bdi></span></div>';

				string1+='</td>';
				string1+='<td><input name="image_url_'+rowidarray[i]+'" id="image_url_'+rowidarray[i]+'" value="'+ecommerce_image_url+'" type="text" class="form-control"></td>';
				string1+='</tr>';
				string1+='<tr>';
				string1+='<td class="table-head-responsive"><?php echo $this->get_label('sale price');?></td>';
				string1+='<td><input name="ad_price_'+rowidarray[i]+'" id="ad_price_'+rowidarray[i]+'" value="'+ecommerce_ad_price+'" style="width: 75px;" type="text" class="form-control"></td>';
				string1+='</tr>';
				string1+='<tr>';
				string1+='<td class="table-head-responsive"><?php echo $this->get_label('offer price');?></td>';
				string1+='<td><input name="ad_offer_price_'+rowidarray[i]+'" id="ad_offer_price_'+rowidarray[i]+'" value="'+ecommerce_ad_offer_price+'" style="width: 75px;" type="text" class="form-control"></td>';
				string1+='</tr>';
				string1+='<tr>';
				string1+='<td class="table-head-responsive"><?php echo $this->get_label('actions');?></td>';
				string1+='<td><a class="link_button" onclick="ChangeFileType(2);" title="<?php echo $this->get_label('add row');?>">+</a>&nbsp;<a class="link_button" onclick="RemoveRow('+rowidarray[i]+');" title="<?php echo $this->get_label('remove row');?>">x</a></td>';
				string1+='</tr>';
				string1+='</tbody>';
				string1+='</table>'
				string1+='</td></tr>';


				$('#table-desktop-mobile').append(string1);
			}
		}


		typedata=$('#bannersize_07').val();
		layout=$('#layoutid-'+typedata).val();

		$('.file-size-class').html($('#max-size-'+typedata).html());
	}




	if(type ==0)
	$('#file_type_hidden').val(0);
	else
	$('#file_type_hidden').val(1);



	if($('.retarget-div').length >0)
	{
		if(pricing == 0 || (pricing == 6 && type != 12))
		{
			$('.retarget-td-pop').show();

			if($('.retarget-tr-pop').length >0)
			$('.retarget-tr-pop').show();
		}
		else
		{
			$('.retarget-td-pop').hide();

			if($('.retarget-tr-pop').length >0)
			$('.retarget-tr-pop').hide();
		}
	}
}


function FillCheckBox(id)
{
	idlist=$('#supported_size').val();
	pricing=$('#adpricing').val();


	idarray=idlist.split('-');

	idarraycount=idarray.length;

	newstring="";
	firstsize=0;
	sizeid=0;
	flag=0;
	oldlayout=0;


	/*
	if(pricing == 3)
	{
		if($('.spc-chk-box').length >0)
		$('.spc-chk-box').prop('checked',false);

		if($('#chk_diamension_'+id).length >0)
		$('#chk_diamension_'+id).prop('checked',true);
	}
	*/


    for(i=0;i < idarraycount;i++)
    {
    	iddata=idarray[i].split('_');

    	if($('#chk_diamension_'+iddata[0]).prop('checked'))
    	{
			if(id == iddata[0] || id ==0)
			{
    			flag=1;

    			layoutvalue=$('#layoutid-'+iddata[0]).val();

    			$('#hidden-layout-'+iddata[0]).show();
    			$('#layoutid-'+iddata[0]).show();

    			if(layoutvalue >0)
    			{
    				$('#show-span-button-'+iddata[0]).show();
    				$('#ad-count-'+layoutvalue).show();
    			}
			}

			if(newstring !="")
			newstring+='-';

			newstring+=iddata[0];

			if(iddata[1] > firstsize)
			{
				sizeid=iddata[0];
				firstsize=iddata[1];
			}
    	}
    	else
    	{
    		oldlayout=$('#layoutid-'+iddata[0]).val();

    		$('#layoutid-'+iddata[0]).val(0);
    		$('#layoutid-'+iddata[0]).hide();
    		$('#show-span-button-'+iddata[0]).hide();

    		$('#ad-count-'+oldlayout).hide();
   			$('#layout-preview-'+oldlayout).hide();
    	}
    }



    $('#size_checked').val(newstring);

	if($('.maxsize').length >0)
	$('.maxsize').hide();

	$('.support-format').hide();


	$('#bannersize_07').val(sizeid);

	if($('#max-size-'+sizeid).length >0)
	{
		$('#max-size-'+sizeid).show();
		$('#support-format0').show();

		$('.file-size-class').html($('#max-size-'+sizeid).html());
	}


	<?php if($expandable_enabled ==1){?>
	if($('.spec_tr_expandable').length >0)
	$('.spec_tr_expandable').hide();

	if($('.spec_tr_expandable_exp').length >0)
	$('.spec_tr_expandable_exp').hide();
	<?php } ?>

	if(flag ==1 && id >0)
	LoadDisplayLayout(id);
}


function LoadMaxSize(type)
{
	bannertype=$('#banner_type_value').val();

	if($('.maxsize').length >0)
	$('.maxsize').hide();


	$('.support-format').hide();

	if($('.html5-sample-download').length >0)
	$('.html5-sample-download').hide();

	if($('#bannersize_'+type).length >0)
	{
		typedata=$('#bannersize_'+type).val();

		if($('#max-size-'+typedata).length >0)
		{
			if(bannertype == 1)
			{
				$('#max-size-html5-'+typedata).show();
				$('#support-format0-html5').show();
				//$('.html5-sample-download').show();
			}
			else
			{
				$('#max-size-'+typedata).show();
				$('#support-format0').show();
			}
		}

		if(bannertype == 1)
		{
			if($('.maxsize-exp').length > 0)
			$('.maxsize-exp').hide();

			if($('.maxsize-exp-html5').length > 0)
			$('.maxsize-exp-html5').show();
		}
		else
		{
			if($('.maxsize-exp').length > 0)
			$('.maxsize-exp').show();

			if($('.maxsize-exp-html5').length > 0)
			$('.maxsize-exp-html5').hide();
		}
	}


	<?php if($expandable_enabled ==1){?>

	if($('.spec_tr_expandable').length >0)
	$('.spec_tr_expandable').hide();

	if($('.spec_tr_expandable_exp').length >0)
	$('.spec_tr_expandable_exp').hide();

	if(type =='02')
	{
		bannersize=$('#bannersize_'+type).val();

		if($('#exp-div-'+bannersize).length >0)
		$('#exp-div-'+bannersize).show();

		LoadExpandableSettings();
	}
	<?php } ?>

	<?php if($skin_enabled ==1){ ?>

	bannersize_sel=$('#bannersize_'+type).val();

	$(".skin_prev").hide();
	$('#skin_prev'+bannersize_sel).show();

	<?php } ?>


	ShowVASTSettings();


	/*
	if($('#adpricing').val() == 3 && ($('#type').val() ==2 || $('#type').val() ==11))
	{
		$('.spec_tr_banner_responsive').hide();

		if($('.load_bannerSizeID').length > 0)
		$('.load_bannerSizeID').hide();

		LoadMultipleBannerUpload();
	}
	else
	{
		$('.spec_tr_banner_responsive').hide();

		if($('.load_bannerSizeID').length > 0)
		$('.load_bannerSizeID').hide();
	}
	*/
}



/*
function LoadMultipleBannerUpload()
{
	bannerSizeID = 0;
	bannerTypeID = 0;

	if($('#type').val() ==2)
	bannerSizeID = 'bannersize_02';
	else if($('#type').val() ==11)
	bannerSizeID = 'bannersize_11';

	bannerTypeID = $('#type').val();

	$('#additional_banners').val("");


	bannerSizeSelectID = $('#'+bannerSizeID).val();

	if(bannerSizeSelectID > 0)
    {
    	if($('#load_'+bannerSizeID).length > 0)
		$('#load_'+bannerSizeID).show();

		dataparam    = 'bannerSize='+bannerSizeSelectID+'&bannerType='+bannerTypeID;
		var urlvalue = '<?php echo $this->make_url("ad/get_responsive_size");?>';

		$.ajax(
		{
			type: "POST",
			data: dataparam,
			url: urlvalue,
			success: function(message)
			{
				if(message !="")
				{
					idList       = JSON.parse(message);
					idListLength = idList.length;
					idString     = "";

					for(i = 0;i < idListLength;i++)
					{
						if(idList[i] != bannerSizeSelectID)
						$('#responsive-'+idList[i]).show();

						if(idString != "")
						idString+=",";

						idString+=idList[i];
					}

					$('#additional_banners').val(idString);
				}

				if($('#load_'+bannerSizeID).length > 0)
				$('#load_'+bannerSizeID).hide();
			}
		});
	}
}
*/


function LoadExpandableSettings()
{
	type        = $('#type').val();
	bannertype  = $('#banner_type_value').val();

	if(bannertype == 1)
	{
		if($('.spec_tr_expandable').length > 0)
		$('.spec_tr_expandable').hide();

		if($('.spec_tr_expandable_exp').length >0)
		$('.spec_tr_expandable_exp').hide();

		if($('.expandable_checkbox').length > 0)
		$('.expandable_checkbox').prop('checked',false);

		return;
	}


	type1  = "";

	if(type == 2)
	type1 = '02';

	if(type1 == '02')
	{
		bannersize=$('#bannersize_'+type1).val();

		if($('#exp-div1-'+bannersize).length >0)
		{
			if($('#expandable_'+bannersize).length >0 && $('#expandable_'+bannersize).prop('checked'))
			$('#exp-div1-'+bannersize).show();
			else if($('#expandable_'+bannersize).length >0)
			$('#exp-div1-'+bannersize).hide();
		}


		if(type ==2 && $('#exp-div2-'+bannersize).length >0)
		{
			if($('#expandable_'+bannersize).length >0 && $('#expandable_'+bannersize).prop('checked'))
			$('#exp-div2-'+bannersize).show();
			else if($('#expandable_'+bannersize).length >0)
			$('#exp-div2-'+bannersize).hide();
		}

		if($('#expandable_'+bannersize).prop('checked'))
		$('#expandable_hidden').val(1);
		else
		$('#expandable_hidden').val(0);
	}

	<?php if($video_enabled == 1){?>
	if($('#expandable_'+bannersize).prop('checked') && type ==2 && $('#video_player_support').length >0)
	{
	   	$('#video_player_support').prop('checked',false);
	    $('.video-support').hide();
	}
	else if(type ==2 && $('#video_player_support').length >0)
	$('.video-support').show();
	<?php }?>
}



function ShowVASTSettings()
{
	type=$('#type').val();
	pricing=$('#adpricing').val();

	if($('.vast-div').length >0)
	{
		$('.vast-div').hide();
		$('#vast_support').prop('checked',false);

		bannertype = $('#banner_type_value').val();

		if(bannertype == 1) //HTML5 have no vast option
		return;

		if(pricing ==0 || pricing ==1 || (pricing ==6 && type != 12))
		{
			if(type ==1)
			{
				$('.vast-div').show();

				<?php if($vast_support ==1){?>
				$('#vast_support').prop('checked',true);
				<?php } ?>
			}
			else if(type ==2)
			{
				if($('#vast_banner_id').length >0 && $('#bannersize_02').length >0)
				{
					bannerid=$('#bannersize_02').val();

					vast_array=$('#vast_banner_id').val();

					if(vast_array !='')
					{
						vast_data=vast_array.split('-');
						vast_data_length=vast_data.length;

						for(i=0;i < vast_data_length;i++)
						{
							if(vast_data[i] == bannerid)
							{
								$('.vast-div').show();

								<?php if($vast_support ==1){?>
								$('#vast_support').prop('checked',true);
								<?php } ?>

								break;
							}
						}
					}
				}
			}
		}
	}
}

function changeadpricing()
{
	var type=$('#type').val();

	var pricing=$('#adpricing').val();
	HidePreviewBox();

	var cpp_enabled                     = <?php echo intval($cpp_enabled); ?>;
  	var push_notification_option_length = $("#type option[value='18']").length;

	$('#type').attr('disabled',false);

	if(cpp_enabled == 1 &&  pricing == 18 && push_notification_option_length == 0)
	{
      	optionText  = '<?php echo $this->get_label("push-notification ad"); ?>';
      	optionValue = '18';

		$('#type').append(`<option value="${optionValue}" selected="selected"> ${optionText} </option>`);
	    changeadtype();
	}

	if(cpp_enabled == 1 && pricing != 18 && push_notification_option_length !=0)
	{
		$("#type option[value='18']").remove();
		changeadtype();
	}

	if(pricing == 18)
	type = 18;

		if(
		(pricing != 1 && (type == 9 || type == 13)) || 
			(pricing != 6 && type == 12) || 
			(pricing != 0 && pricing != 1 && pricing != 6 && type == 21)  

		)//If current type is video/affiliate/pop/directlink, Then change to other pricing		
		{
			type=2;
			$('#type').val(2);
			$('#type1').val(2);
		}				

		$('.ad-option').show();

	if(type == 2)
	{
		if($('.banner-radio').length >0)
		$('.banner-radio').show();

		banner_type=$('#banner_type_value').val();

		$('#banner_type'+banner_type).prop('checked',true);
	}
	else
	{
		if($('.banner-radio').length >0)
		$('.banner-radio').hide();

		$('#banner_type_value').val(0);
	}

	$('.spec_tr_video_url').hide();

	if($('.retarget-div').length >0)
	{
		if(pricing == 0 || (pricing == 6 && type != 12))
		{
			$('.retarget-div').show();
			$('.retarget-td-pop').show();

			if($('.retarget-tr-pop').length >0)
			$('.retarget-tr-pop').show();

			$('#csvdownload').attr("href","<?php echo $this->make_url('dispatch/ecommerce-ads/1/0/1');?>");
		}
		else
		{
			$('.retarget-div').hide();
			$('.retarget-td-pop').hide();

			if($('.retarget-tr-pop').length >0)
			$('.retarget-tr-pop').hide();

			$('#retargeting').prop('checked',false);

			$('#csvdownload').attr("href","<?php echo $this->make_url('dispatch/ecommerce-ads/1');?>");
		}
	}

	//if(pricing ==3 && type ==7)
	//FillCheckBox(0);


	$('.banner-select').hide();

	if($('.affiliatediv').length >0)
	$('.affiliatediv').hide();

	if($('.spec_tr_video').length >0)
	$('.spec_tr_video').hide();

	if($('.spec_tr_skin').length >0)
	$('.spec_tr_skin').hide();


	if(type == 12)
	{
		$('.affiliate-content').show();
		$('.adtitle-span').hide();
		$('#title').removeAttr('maxlength');
	}
	else
	{
		if($('.affiliate-content').length >0)
		$('.affiliate-content').hide();

		$('#title').attr('maxlength',<?php echo $maxtitle;?>);
		$('.adtitle-span').show();
	}

	if(pricing == 1 && $(".pop-option").length > 0)
	$(".pop-option").show();
	else if($(".pop-option").length > 0)
	$(".pop-option").hide();
	if(pricing == 1 && $(".video-option").length > 0)
	$(".video-option").show();
	else if($(".video-option").length > 0)
	$(".video-option").hide();
	
	if((pricing == 0 || pricing == 1 || pricing == 6) && $(".directlink-option").length > 0)
	$(".directlink-option").show();
	else if($(".directlink-option").length > 0)
	$(".directlink-option").hide();

	if(pricing == 6 && $(".affiliate-option").length > 0)
	$(".affiliate-option").show();
	else if($(".affiliate-option").length > 0)
	$(".affiliate-option").hide();
	
	if(type == 12)
	{
		$('.spec_tr_both').show();

		$('.spec_tr_text').show();
		
		$('#spec_tr_description').hide();
		$('.spec_tr_textonly').hide();

		$('.spec_tr_banner').hide();
		$('#banner-select-01').hide();

		if($('.spec_tr_ecommerce').length >0)
		$('.spec_tr_ecommerce').hide();

		$('.affiliatediv').show();

		if($('.spec_tr_expandable').length >0)
		$('.spec_tr_expandable').hide();

		if($('.spec_tr_expandable_exp').length >0)
		$('.spec_tr_expandable_exp').hide();

		if($('.spec_tr_skin').length >0)
		$('.spec_tr_skin').hide();
	}
	else if(type == 13)
	{
		$('.spec_tr_video').show();

		$('#ad_type_div').show();

		$('.spec_tr_text').hide();
		$('.spec_tr_textonly').hide();

		$('.spec_tr_banner').hide();

		if($('.spec_tr_ecommerce').length >0)
		$('.spec_tr_ecommerce').hide();

		$('.affiliatediv').hide();

		$('.spec_tr_video_url').show();

		if($('.spec_tr_expandable').length >0)
		$('.spec_tr_expandable').hide();

		if($('.spec_tr_expandable_exp').length >0)
		$('.spec_tr_expandable_exp').hide();

		if($('.spec_tr_skin').length >0)
		$('.spec_tr_skin').hide();
	}
	else
	{
		$('#ad_type_div').show();

		if($('#type').val() != 11)
		$('#banner-select-02').show();

		if($('#type').val() ==1)
		{
			$('.spec_tr_text').show();

			if($('.affiliate-content').length >0)
			$('.affiliate-content').hide();

			if($('.spec_tr_expandable').length >0)
			$('.spec_tr_expandable').hide();

			if($('.spec_tr_expandable_exp').length >0)
			$('.spec_tr_expandable_exp').hide();

			if($('.spec_tr_skin').length >0)
			$('.spec_tr_skin').hide();
		}
		else if($('#type').val() ==2)
		{
			$('.spec_tr_banner').show();

			if($('#type').val() ==2)
			{
				$('#banner-select-02').show();

				if($('.spec_tr_skin').length >0)
				$('.spec_tr_skin').hide();
			}
			else
			{
				$('#banner-select-02').hide();

				if($('.spec_tr_expandable').length >0)
				$('.spec_tr_expandable').hide();

				if($('.spec_tr_expandable_exp').length >0)
				$('.spec_tr_expandable_exp').hide();

				if($('.spec_tr_skin').length >0)
				$('.spec_tr_skin').hide();
			}

			if($('.affiliate-content').length >0)
			$('.affiliate-content').hide();
		}
		else if($('#type').val() ==11)
		{
			$('#banner-select-11').show();


			$('.spec_tr_text').show();
			$('.spec_tr_banner').show();

			if($('.affiliate-content').length >0)
			$('.affiliate-content').hide();

			if($('.spec_tr_expandable').length >0)
			$('.spec_tr_expandable').hide();

			if($('.spec_tr_expandable_exp').length >0)
			$('.spec_tr_expandable_exp').hide();

			if($('.spec_tr_skin').length >0)
			$('.spec_tr_skin').hide();
		}
		else if($('#type').val() ==14)
		{
			$('.spec_tr_skin').show();
			$('.spec_tr_both').show();

			$('#banner-select-14').show();

			if($('.spec_tr_expandable').length >0)
			$('.spec_tr_expandable').hide();

			if($('.spec_tr_expandable_exp').length >0)
			$('.spec_tr_expandable_exp').hide();
		}
	}

	ShowVASTSettings();

	if($('.step-box-normal').length >0)
	$('.step-box-normal').hide();

	if($('.step-box-cpd').length >0)
	$('.step-box-cpd').hide();

	if($('.step-box-affiliate').length >0)
	$('.step-box-affiliate').hide();

	if($('.step-box-directlink').length >0)
	$('.step-box-directlink').hide();	

	if(pricing == 3)
	{
		if($('.step-box-cpd').length >0)
		$('.step-box-cpd').show();
	}
	else if(type == 12)
	{
		if($('.step-box-affiliate').length >0)
		$('.step-box-affiliate').show();
	}
	else if(type == 21)
	{
		if($('.step-box-directlink').length >0)
		$('.step-box-directlink').show();
	}
	else
	{
		if($('.step-box-normal').length >0)
		$('.step-box-normal').show();
	}

	if(pricing == 18)
	{
		$('#type').val(18);
		$('#type1').val(18);
		$('#type').attr('disabled',true);
		$('.spec_tr_banner').hide();
	}
}


function DynamicDemo()
{
	hdata=$('#hdata').val();

	if(hdata =="")
	hdata="<?php echo $this->get_label('headline text');?>";


	callactiontext=$('#callactiontext').val();

	if($('#htext').prop('checked'))
	{
		if($('.span-text').length >0)
		{
			$('.span-text').html(hdata);
			$('.span-button').hide();
			$('.span-text').show();
		}
	}
	else if($('#hbutton').prop('checked'))
	{
		if($('.span-button').length >0)
		{
			$('.span-button').html(hdata);
			$('.span-text').hide();
			$('.span-button').show();
		}
	}

	if($('.dummybutton-style').length >0 && callactiontext !="")
	$('.dummybutton-style').html(callactiontext);
}


function changeadtype()
{
	type      = $('#type').val();
	adpricing = $('#adpricing').val();

		$('.ad-option').show();

		if(
		(adpricing != 1 && (type == 9 || type == 13)) || 
			(adpricing != 6 && type == 12) || 
			(adpricing != 0 && adpricing != 1 && adpricing != 6 && type == 21)  

		)//If current type is video/affiliate/pop/directlink, Then change to other pricing
		{
			type = 2;
			$('#type').val(2);
			$('#type1').val(2);
		}	
	

	if($('#notify_icon').length >0)
	$('#notify_icon').hide();

	if($('.spec_tr_ecommerce').length >0)
	$('.spec_tr_ecommerce').hide();

	if($('.spec_tr_expandable').length >0)
	$('.spec_tr_expandable').hide();

	if($('.spec_tr_expandable_exp').length >0)
	$('.spec_tr_expandable_exp').hide();

	if($('.spec_tr_skin').length >0)
	$('.spec_tr_skin').hide();


	$('.spec_tr_text').hide();
	$('.spec_tr_both').hide();
	$('.spec_tr_banner').hide();

	if($('.spec_tr_video').length >0)
	$('.spec_tr_video').hide();

	if($('#type').val() ==1)
	{
		$('.spec_tr_text').show();
		$('.spec_tr_both').show();

		$('#type').val(1);
		$('#type1').val(1);
	}
	else if($('#type').val() == 2)
	{
		$('.spec_tr_banner').show();
		$('.spec_tr_both').show();

		$('#type1').val(2);
	}
	else if($('#type').val() ==11)
	{
		$('.spec_tr_text').show();
		$('.spec_tr_banner').show();
		$('.spec_tr_both').show();


		$('#type1').val(11);
	}
	else if($('#type').val() == 12)
	{
		$('.spec_tr_banner').show();
		$('.spec_tr_both').show();

		$('#type1').val(12);
	}
	else if($('#type').val() ==13)
	{
		$('.spec_tr_video').show();
		$('.spec_tr_both').show();

		$('.spec_tr_video_url').show();

		$('#type1').val(13);
	}
	else if($('#type').val() ==14)
	{
		$('.spec_tr_skin').show();
		$('.spec_tr_both').show();

		$('#type1').val(14);
	}
	else if($('#type').val() ==7)
	{
		$('.spec_tr_ecommerce').show();

		$('#type1').val(7);

		ChangeFileType($('#file_type_hidden').val());

		FillCheckBox(0);
	}
	else if($('#type').val() ==9)
	{
		$('.spec_tr_both').show();
		
		$('#type1').val(9);
	}
	else if($('#type').val() ==21)
	{
		$('.spec_tr_both').show();

		$('#type1').val(21);
	}
	else if($('#type').val() ==18)
	{
		$('.spec_tr_text').show();
		$('.spec_tr_textonly').hide();
		$('#spec_tr_description').show();
		$('.spec_tr_video_url').show();
		$('.spec_tr_both').show();
		$('#notify_icon').show();
		$('#type1').val(18);
	}	

	changeadpricing();

	if(type ==2)
	LoadMaxSize('02');
	else if(type ==11)
	LoadMaxSize('11');
	else if(type ==14)
	LoadMaxSize('14');
}

function HidePreviewBox()
{
	if($('.previewSection').length > 0)
	{
		$('.previewSection').html('');
		$('.previewCloseDiv').hide();
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
   
	if(adType == 18)
   	return;

	if(adType == 11)
	textimageSize              = $('#bannersize_11').val();

    if($('#title').val() != "")
    {
    	titleEnabled           = 1;
    	titleText              = $('#title').val();
    }

    if($('#desc').val() != "")
    {
    	descriptionEnabled     = 1;
    	descriptionText        = $('#desc').val();
    }

    if($('#displayurl').val() != "")
    {
    	urlEnabled             = 1;
    	displayurlText         = $('#displayurl').val();
    }

    if($('#cta_button_text').val() != "")
    buttonText             = $('#cta_button_text').val();

    if((titleEnabled == 0 && descriptionEnabled == 0 && urlEnabled == 0) || (adType != 1 && adType != 11 && adType!= 18))
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
            $('.previewCloseDiv').show();

            $('.previewSection').html(message);
        }
    });
}

function ShowPreview(id)
{
	$('.layout-preview-banner-'+id).slideDown('slow');

	LoadLayoutPreview(id);
}

function HidePreview(id, layoutID)
{
	$('#layout-preview-'+id).hide();

	$('.layout-preview-banner-'+layoutID).hide();
}

function LoadDisplayLayout(bannerid)
{
	if($('#type').val() ==7)
	{
		if($('#chk_diamension_'+bannerid).prop('checked'))
    	{
			if($('#hidden-layout-'+bannerid).length >0)
			$('#hidden-layout-'+bannerid).show();
    	}
		else
		{
			if($('#hidden-layout-'+bannerid).length >0)
			$('#hidden-layout-'+bannerid).hide();
	    }

		LoadLayoutPreview(bannerid);
	}
	else
	{
		$('.display-layout-span').hide();
	}
}

function LoadLayoutPreview(banner)
{
	id=$('#layoutid-'+banner).val();

	if(id >0)
	{
		$('.layout-preview-banner-'+banner+' .layout-preview').hide();

		$('#layout-preview-'+id).show();

		if($('#chk_diamension_'+banner).prop('checked'))
		$('#show-span-button-'+banner).show();
		else
		$('#show-span-button-'+banner).hide();
	}
	else
	{
		$('.layout-preview-banner-'+banner+' .layout-preview').hide();

		$('#show-span-button-'+banner).hide();
	}

	idlist=$('#supported_size').val();
	idarray=idlist.split('-');
	idarraycount=idarray.length;

	$('.ad-count-list').hide();

    for(i=0;i < idarraycount;i++)
    {
    	iddata=idarray[i].split('_');

    	if($('#chk_diamension_'+iddata[0]).prop('checked'))
    	{
    		layout=$('#layoutid-'+iddata[0]).val();

			if(layout > 0)
			$('#ad-count-'+layout).show();
    	}
    }


	ChangeFileType($('#file_type_hidden').val());
}
</script>
<?php

$validate=array(
		"name"=>array(
				"notNull"=>array($this->get_message("not null"))
		),
		"type=>1"=>array(
				"title"=>array("notNull"=>array($this->get_message("not null"))),
				"desc"=>array("notNull"=>array($this->get_message("not null"))),
				"displayurl"=>array("notNull"=>array($this->get_message("not null"))),
				"clickurl"=>array("notNull"=>array($this->get_message("not null")))
		),
		"type=>2"=>array(
				"banner"=>array("notNull"=>array($this->get_message("not null"))),
				"clickurl"=>array("notNull"=>array($this->get_message("not null")))
		),
		"type=>7"=>array(
		         "ecommercelogo"=>array("notNull"=>array($this->get_message("please upload logo image"))),
				 "hdata"=>array("notNull"=>array($this->get_message("not null"))),
				 "hlink"=>array("notNull"=>array($this->get_message("not null")))
		),
		"type=>9"=>array(
				"clickurl"=>array("notNull"=>array($this->get_message("not null")))
		),
		"type=>11"=>array(
				"title"=>array("notNull"=>array($this->get_message("not null"))),
				"desc"=>array("notNull"=>array($this->get_message("not null"))),
				"displayurl"=>array("notNull"=>array($this->get_message("not null"))),
				"banner"=>array("notNull"=>array($this->get_message("not null"))),
				"clickurl"=>array("notNull"=>array($this->get_message("not null")))
		),
		"type=>12"=>array(
				"title"=>array("notNull"=>array($this->get_message("not null"))),
				"description"=>array("notNull"=>array($this->get_message("not null"))),
				"clickurl"=>array("notNull"=>array($this->get_message("not null")))
		),
		"type=>13"=>array(
				"videofile"=>array("notNull"=>array($this->get_message("not null"))),
				"displayurl"=>array("notNull"=>array($this->get_message("not null"))),
				"clickurl"=>array("notNull"=>array($this->get_message("not null")))
		),
		"type=>14"=>array(
				"clickurl"=>array("notNull"=>array($this->get_message("not null")))
		),
		"type=>18"=>array(
				"title"=>array("notNull"=>array($this->get_message("not null"))),
				"desc"=>array("notNull"=>array($this->get_message("not null"))),
				"clickurl"=>array("notNull"=>array($this->get_message("not null")))
		),
		"type=>21"=>array(
				"clickurl"=>array("notNull"=>array($this->get_message("not null")))
		)		
);

if($skin_enabled ==1)
{
	$skin_array=array();
	$res14_banner=$this->get_result('res14_banner');

	foreach($res14_banner as $key=>$result)
	{
		$skin_array['type=>14']['banner_type_value=>0']['skin_banner_'.$result['id']] =array("notNull"=>array($this->get_message("not null")));
	}

	$validate=array_merge($validate,$skin_array);
}

$adpricing=$this->get_variable('adpricing');


$keyword_enabled = Configuration::get_instance()->read('keyword_based_ad_display');
$date_enabled    = Configuration::get_instance()->read('date_filter_enabled');
$time_enabled    = Configuration::get_instance()->read('time_filter_enabled');
$day_enabled     = Configuration::get_instance()->read('day_filter_enabled');


$step_array            = array();
$step_cpd_array        = array();
$step_aff_array        = array();
$step_directlink_array = array();

$step            = 1;
$step_cpd        = 1;
$step_aff        = 1;
$step_directlink = 1;


$step_array['create']=array($step++,$this->get_label('ad content'));

$step_array['pricing']=array($step++,$this->get_label('pricing'));

$step_array['locations']=array($step++,$this->get_label('locations'));

if($keyword_enabled ==1)
$step_array['keywords']=array($step++,$this->get_label('keywords'));

if($category_enabled ==1)
$step_array['category']=array($step++,$this->get_label('category'));

if($deviceenabled ==1)
$step_array['device']=array($step++,$this->get_label('device'));

if($connection_enabled ==1 && $isp_success >0)
$step_array['connection']=array($step++,$this->get_label('connection'));

if($isp_enabled ==1 && $isp_success ==2)
$step_array['isp']=array($step++,$this->get_label('isp'));

if($retargeting ==1)
$step_array['retarget']=array($step++,$this->get_label('retargeting'));

if($time_target_enabled ==1 && ($date_enabled ==1 || $time_enabled ==1 || $day_enabled ==1))
$step_array['time']=array($step++,$this->get_label('time'));

if($language_enabled ==1)
$step_array['language']=array($step++,$this->get_label('language'));


if($sponsored ==1)
{
	$step_cpd_array['create']=array($step_cpd++,$this->get_label('ad content'));

	$step_cpd_array['position']=array($step_cpd++,$this->get_label('ad cpd targeting'));
}


if($affiliate_enabled ==1)
{
	$step_aff_array['create']=array($step_aff++,$this->get_label('ad content'));

	$step_aff_array['pricing']=array($step_aff++,$this->get_label('pricing'));

	$step_aff_array['locations']=array($step_aff++,$this->get_label('locations'));

	if($deviceenabled ==1)
	$step_aff_array['device']=array($step_aff++,$this->get_label('device'));

	if($connection_enabled ==1 && $isp_success >0)
	$step_aff_array['connection']=array($step_aff++,$this->get_label('connection'));

	if($isp_enabled ==1 && $isp_success ==2)
	$step_aff_array['isp']=array($step_aff++,$this->get_label('isp'));
}

if($directlink_enabled ==1)
{
	$step_directlink_array['create']=array($step_directlink++,$this->get_label('ad content'));

	$step_directlink_array['pricing']=array($step_directlink++,$this->get_label('pricing'));

	$step_directlink_array['locations']=array($step_directlink++,$this->get_label('locations'));

	if($deviceenabled ==1)
	$step_directlink_array['device']=array($step_directlink++,$this->get_label('device'));

	if($connection_enabled ==1 && $isp_success >0)
	$step_directlink_array['connection']=array($step_directlink++,$this->get_label('connection'));

	if($isp_enabled ==1 && $isp_success ==2)
	$step_directlink_array['isp']=array($step_directlink++,$this->get_label('isp'));

	if($time_target_enabled ==1 && ($date_enabled ==1 || $time_enabled ==1 || $day_enabled ==1))
	$step_directlink_array['time']=array($step_directlink++,$this->get_label('time'));

	if($language_enabled ==1)
	$step_directlink_array['language']=array($step_directlink++,$this->get_label('language'));
}

$form=$this->create_form();
$form->start("create",$this->make_url("ad/create"),"post",$validate);
?>

<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 p-3 mb-3 ad-create">
<h2 class="page-heading" ><div class="page-inner"><i class="fa fa-plus-circle icon_red"></i><?php echo $this->get_label('create ad');?></div></h2>


<div class="row m-0">
	<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 my-4 my-4">

	<ul id="progressbar" class="d-flex">
		<?php
		$ii = 0;
		foreach($step_array as $step_key => $step_value){?>
		<li class="step-box step-box-normal <?php if($ii == 0){?> current <?php }?>" id="normal-<?php echo $step_value[0];?>">
			<span class="progress-span"><?php echo $step_value[1];?></span>
		</li>
		<?php
		$ii++;
		}?>
	</ul>


	<?php if($sponsored == 1){?>
	<ul id="progressbar" class="d-flex">
		<?php
		$ii = 0;
		foreach($step_cpd_array as $step_key => $step_value){?>
		<li class="step-box step-box-cpd <?php if($ii == 0){?> current <?php }?>" id="cpd-<?php echo $step_value[0];?>">
			<span class="progress-span"><?php echo $step_value[1];?></span>
		</li>
		<?php
		$ii++;
		}?>
	</ul>
	<?php }?>

	<?php if($affiliate_enabled == 1){?>
	<ul id="progressbar" class="d-flex">
		<?php
		$ii = 0;
		foreach($step_aff_array as $step_key => $step_value){?>
		<li class="step-box step-box-affiliate <?php if($ii == 0){?> current <?php }?>" id="affiliate-<?php echo $step_value[0];?>">
			<span class="progress-span"><?php echo $step_value[1];?></span>
		</li>
		<?php
		$ii++;
		}?>
	</ul>
	<?php }?>

	<?php if($directlink_enabled == 1){?>
		<ul id="progressbar" class="d-flex">
			<?php
			$ii = 0;
			foreach($step_directlink_array as $step_key => $step_value){?>
			<li class="step-box step-box-directlink <?php if($ii == 0){?> current <?php }?>" id="directlink-<?php echo $step_value[0];?>">
				<span class="progress-span"><?php echo $step_value[1];?></span>
			</li>
			<?php
			$ii++;
			}?>
		</ul>
	<?php }?>

	</div>
</div>


<div class="row">

	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3">
		<label class="form-label mb-1"><?php echo $this->get_label('pricing');?> <span class="compulsory">*</span></label>
		<?php
		if($positionID == 0)
		{
				if($cloneFlag == 0)
				echo $this->get_pricing_box($adpricing);
				else
				{?>
						<select class="form-select" name="adpricing" id="adpricing">
							<option value="<?php echo $adpricing; ?>"><?php echo $this->get_ad_pricing($parentAdID,$adpricing);?></option>
						</select>
				<?php
				}
		}
		else
		{
			?>
		<select class="form-select" name="adpricing" id="adpricing">
			<option value="3"><?php echo $this->get_label('cpd');?></option>
		</select>
		<?php }?>

		<input type="hidden" name="positionID" id="positionID" value="<?php echo $positionID;?>" />
		<input type="hidden" name="banner_type_value" id="banner_type_value" value="<?php echo $banner_type;?>" />
	</div>


	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3" id="ad_type_div">
		<label class="form-label mb-1"><?php echo $this->get_label('adtype');?> <span class="compulsory">*</span></label>
		<?php if($positionID == 0){

		if($cloneFlag == 0){?>
		  <select class="form-select" name="type" id="type" onchange="javascript:return changeadtype();" >

			<?php if($text_ads_enabled ==1){?>
			<option class="ad-option" value="1" <?php if($this->get_variable('type')==1) { echo "selected"; }?>><?php echo $this->get_label('textad');?></option>
			<?php }?>

			<option class="ad-option" value="2" <?php if($this->get_variable('type')==2) { echo "selected"; }?>><?php echo $this->get_label('bannerad');?></option>

		<?php if($textimage_enabled ==1){?>
	    <option class="ad-option" value="11" <?php if($this->get_variable('type')==11) {echo "selected";}?>><?php echo $this->get_label('textimage ad');?></option>
	    <?php }?>

		<?php if($ecommerce_enabled ==1){?>
	    <option class="ad-option" value="7" <?php if($this->get_variable('type')==7) {echo "selected";}?>><?php echo $this->get_label('ecommerce ad');?></option>
	    <?php }?>


	    <?php if($video_enabled ==1 && ($linear_support ==1 || $html5_player_support ==1)){?>
	    <option class="ad-option video-option" value="13" <?php if($this->get_variable('type')==13) {echo "selected";}?>><?php echo $this->get_label('video ad');?></option>
	    <?php }?>

	    <?php if($skin_enabled ==1){?>
	    <option class="ad-option" value="14" <?php if($this->get_variable('type')==14) {echo "selected";}?> ><?php echo $this->get_label('skin ad');?></option>
	    <?php }?>

	    <?php if($pop_enabled ==1){?>
	    <option class="ad-option pop-option" value="9" <?php if($this->get_variable('type')==9) {echo "selected";}?>><?php echo $this->get_label('popad');?></option>
	    <?php }?>

	    <?php if($directlink_enabled ==1){?>
	    <option class="ad-option directlink-option" value="21" <?php if($this->get_variable('type')==21) {echo "selected";}?>><?php echo $this->get_label('directlink ad');?></option>
	    <?php }?>

	    <?php if($affiliate_enabled ==1){?>
	    <option class="ad-option affiliate-option" value="12" <?php if($this->get_variable('type')==12) { echo "selected"; }?>><?php echo $this->get_label('affiliate'); ?></option>
	    <?php }?>
	</select>
		<?php } else {?>
			<select class="form-select" name="type" id="type" onchange="javascript:return changeadtype();" >
				<option class="ad-option" value="<?php echo $this->get_variable('type'); ?>"><?php echo $this->get_ad_type($this->get_variable('type'));?></option>
			</select>
		<?php }}else{ ?>
			<select class="form-select" name="type" id="type" onchange="javascript:return changeadtype();" >
				<?php if($text_ads_enabled ==1 && ($positionAdType == 1 || $positionAdType == 3)){?>
				<option class="ad-option" value="1" <?php if($this->get_variable('type')==1) { echo "selected"; }?>><?php echo $this->get_label('textad');?></option>
				<?php }?>


				<?php if($bannerAdSupport ==1 && ($positionAdType == 2 || $positionAdType == 3)){?>
				<option class="ad-option" value="2" <?php if($this->get_variable('type')==2) { echo "selected"; }?>><?php echo $this->get_label('bannerad');?></option>
				<?php }?>

				<?php if($textimage_enabled ==1 && $textimageAdSupport == 1 && ($positionAdType == 11 || $positionAdType == 3)){?>
		    <option class="ad-option" value="11" <?php if($this->get_variable('type')==11) {echo "selected";}?>><?php echo $this->get_label('textimage ad');?></option>
		    <?php }?>

				<?php if($ecommerce_enabled ==1 && $ecommerceAdSupport == 1 && ($positionAdType == 2 || $positionAdType == 3)){?>
		    <option class="ad-option" value="7" <?php if($this->get_variable('type')==7) {echo "selected";}?>><?php echo $this->get_label('ecommerce ad');?></option>

		    <?php }?>

		    <?php if($skin_enabled ==1 && $positionAdType == 14){?>
		    <option class="ad-option" value="14" <?php if($this->get_variable('type')==14) {echo "selected";}?> ><?php echo $this->get_label('skin ad');?></option>
		    <?php }?>
			</select>
		<?php } ?>
	</div>

<?php if($positionID > 0 && count($cpdPackageDaysArray) > 0){ ?>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3">
	<label class="form-label mb-1"><?php echo $this->get_label('cpd package');?> <span class="compulsory">*</span></label>
	<?php
	$cpdPackage = $this->get_variable("cpdPackage");

	foreach($cpdPackageDaysArray as $pkey=>$pvalue)
	{
		$dayRate = $this->get_money_format($pvalue * $calculatedRate);
		?>
		<div class="form-check">
	      <input class="form-check-input mt-1" type="radio" name="<?php echo 'package_'.$positionID;?>" id="<?php echo 'package_'.$positionID.'_'.$pvalue;?>" <?php if($cpdPackage == $pvalue){?> checked <?php }?> value="<?php echo $pvalue; ?>" />
	      <label class="form-check-label" for="packageDaysRate">
	        <bdi><?php echo $pvalue." ".$this->get_label("package days rate",array("x"=>$dayRate));?></bdi>
	      </label>
	  </div>
		<?php
	}
	?>
</div>
<?php }?>

<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3">
  <label class="form-label mb-1"><?php echo $this->get_label('name');?> <span class="compulsory">*</span></label>
  <input class="form-control" type="text" name="name" id="name" value="<?php echo $this->get_variable('name');?>" maxlength="25" />
</div>


<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3 spec_tr_text">
    <label class="form-label mb-1"><?php echo $this->get_label('title');?> <span class="compulsory">*</span></label>
    <input class="form-control" type="text" name="title" id="title" value="<?php echo $this->get_variable('title');?>" maxlength="<?php echo $maxtitle;?>" onkeyup="AdPreview(1);" />
    <div class="notification adtitle-span">
			<bdi>[<?php echo $this->get_label('max character',array('x'=>$maxtitle));?>]</bdi>

    	<i id="preview-show" onclick="AdPreview(0);" class="previewshow fa fa-laptop preview-icon" title="<?php echo $this->get_label('ad demo');?>" alt="<?php echo $this->get_label('ad demo');?>"></i>

    	<img id="loading" src="images/load.gif" style="display: none;" />
    </div>
</div>

<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3 spec_tr_text" id="spec_tr_description">
  <label class="form-label mb-1"><?php echo $this->get_label('description');?> <span class="compulsory">*</span></label>
	<input class="form-control" type="text" name="desc" id="desc" value="<?php echo $this->get_variable('desc');?>" maxlength="<?php echo $maxdesc;?>" onkeyup="AdPreview(2);" />
	<div class="notification">
		<bdi>[<?php echo $this->get_label('max character',array('x'=>$maxdesc));?>]</bdi>
	</div>
</div>

<?php if($affiliate_enabled == 1){?>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3 spec_tr_text affiliate-content" style="display: none;">
	<label class="form-label mb-1"><?php echo $this->get_label('description');?> <span class="compulsory">*</span></label>
	<textarea class="form-control" rows="5" name="description" id="description"><?php echo $this->get_variable('desc');?></textarea>
</div>
<?php }?>

<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3 spec_tr_text spec_tr_textonly spec_tr_video_url">
	<label class="form-label mb-1"><?php echo $this->get_label('display url');?> <span class="compulsory">*</span></label>
	<input class="form-control" type="text" name="displayurl" id="displayurl" value="<?php echo $this->get_variable('displayurl');?>" maxlength="<?php echo $maxdispurl;?>" placeholder="<?php echo $this->get_label('example url');?>" onkeyup="AdPreview(3);" />
	<div class="notification">
		<bdi>[<?php echo $this->get_label('max character',array('x'=>$maxdispurl));?>]</bdi>
	</div>
</div>

<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3 spec_tr_text spec_tr_textonly">
	<label class="form-label mb-1"><?php echo $this->get_label('cta button text');?></label>
	<input class="form-control" type="text" name="cta_button_text" id="cta_button_text" value="<?php echo $this->get_variable('cta_button_text');?>" maxlength="<?php echo $ctaButtonLength;?>" placeholder="<?php echo $this->get_label('shop now');?>" onkeyup="AdPreview(0);" />
	<div class="notification">
		<bdi>[<?php echo $this->get_label('max character',array('x'=>$ctaButtonLength));?>]</bdi>
	</div>
</div>

<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3 spec_tr_both">
	<label class="form-label mb-1"><?php echo $this->get_label('click url');?> <span class="compulsory">*</span></label>
	<input class="form-control" type="text" name="clickurl" value="<?php echo $this->get_variable('clickurl');?>" placeholder="<?php echo $this->get_label('example url');?>" />
	<div class="notification">
		<bdi>[<?php echo $this->get_label('macro parameters').' : '.$this->get_label('macro parameters content');?>]</bdi>
	</div>
</div>

<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3" id="notify_icon" style="display:none;">
	<label class="form-label mb-1"><?php echo $this->get_label('notification icon image');?></label>
	<input class="form-control" type="file" name="notify_icon_image" id="notify_icon_image" placeholder="<?php echo $this->get_label('notification icon image');?>" />
	<div class="notification">
		<bdi>[<?php echo $this->get_label('supported image format');?>]</bdi>
	</div>
	<div class="notification">
		<bdi>[<?php echo $this->get_label('preferred icon image size is', array("x"=>Configuration::get_instance()->read('push_notification_icon_width')." x ".Configuration::get_instance()->read('push_notification_icon_height'))); ?>]</bdi>
	</div>
</div>


<?php if($html5_enabled == 1){?>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3 banner-radio" style="display: none;">
	<label class="form-label mb-1"><?php echo $this->get_label('banner type');?></label>
  <div>
		<div class="form-check">
      <input class="form-check-input mt-1" type="radio" name="banner_type" id="banner_type0" value="0" <?php if($banner_type ==0){?>checked="checked"<?php }?> onclick="ChangeRadioType(0);" />
      <label class="form-check-label" for="normalBanner"><?php echo $this->get_label('normal banner');?></label>
    </div>
    <div class="form-check">
      <input class="form-check-input mt-1" type="radio" name="banner_type" id="banner_type1" value="1" <?php if($banner_type ==1){?>checked="checked"<?php }?> onclick="ChangeRadioType(1);" />
      <label class="form-check-label" for="html5Banner"><?php echo $this->get_label('html5 banner');?></label>
    </div>
	</div>
</div>
<?php }?>

<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3 spec_tr_banner">
	<label class="form-label mb-1"><?php echo $this->get_label('banner size');?></label>
	<?php
	$vast_string='';

	$res1=$this->get_result('res1');


	if($ecommerce_enabled ==1)
	$res3=$this->get_result('res3');

	if($textimage_enabled ==1)
	$res6=$this->get_result('res6');

	if($skin_enabled ==1)
	{
		$res14_banner=$this->get_result('res14_banner');
		$res14_adblock=$this->get_result('res14_adblock');
	}

	if($positionID == 0)
	{
		if($banner_dimension_count > 0){?>
		<span class="banner-select" id="banner-select-02" style="display: none;">
			<select class="form-select" name="bannersize_02" id="bannersize_02" onchange="LoadMaxSize('02');">
			<?php
			foreach($res1 as $key=>$result)
			{
					$height    = $result['height'];
					$width     = $result['width'];
					$id        = $result['id'];
					$dimension = $result['width']." x ".$result['height'];

					if($result['vast_video_support'] == 1)
					{
							if($vast_string != '')
							$vast_string.='-';

							$vast_string.=$result['id'];
					}

					?>
					<option value="<?php echo $id?>" <?php if($this->get_variable("bannersize")==$id) { echo "selected"; }?>><?php echo $dimension ?></option>
					<?php }?>
					</select>
					<span class="load_bannerSizeID" id="load_bannersize_02" style="display: none;"><img src="<?php echo BASE;?>images/load.gif"/></span>
		</span>
		<?php }?>

		<input type="hidden" name="vast_banner_id" id="vast_banner_id" value="<?php echo $vast_string;?>" />



		<?php if($textimage_enabled ==1){?>
		<span class="banner-select" id="banner-select-11" style="display: none;">
			<select class="form-select" name="bannersize_11" id="bannersize_11" onchange="LoadMaxSize('11');AdPreview(0);">
			<?php

			foreach($res6 as $key=>$result)
			{
				$height=$result['height'];
				$width=$result['width'];
				$id=$result['id'];
				$dimension=$result['width']." x ".$result['height'];
			?>
			<option value="<?php echo $id?>" <?php if($this->get_variable("bannersize")==$id) { echo "selected"; }?>><?php echo $dimension ?></option>
			<?php }?>
			</select>
			<span class="load_bannerSizeID" id="load_bannersize_11" style="display: none;"><img src="<?php echo BASE;?>images/load.gif"/></span>
		</span>
		<?php }

	} else {?>

	<?php if($positionAdType == 2 || $positionAdType == 3){?>
	<span class="banner-select" id="banner-select-02" style="display: none;">
		<input type="hidden" name="bannersize_02" id="bannersize_02" value="<?php echo $positionBannerSize;?>" />
		<input type="text" class="form-control" disabled="disabled" value="<?php echo str_replace("-", " x ",$this->get_banner_dimension($positionBannerSize));?>" />
	</span>
	<?php } ?>

	<?php if($positionAdType == 3 || $positionAdType == 11){?>
	<span class="banner-select" id="banner-select-11" style="display: none;">
		<input type="hidden" name="bannersize_11" id="bannersize_11" value="<?php echo $positionTextImageSize;?>" />
		<input type="text" class="form-control" disabled="disabled" value="<?php echo str_replace("-", " x ",$this->get_banner_dimension($positionTextImageSize));?>" />
	</span>
	<?php } ?>

<?php } ?>
</div>

<?php if($skin_enabled == 1){?>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3 spec_tr_skin">
	<label class="form-label mb-1"><?php echo $this->get_label('skin positions');?></label>

	<span class="banner-select" id="banner-select-14" style="display: none;">
		<?php if($positionID == 0){?>
		<select class="form-select" name="bannersize_14" id="bannersize_14" onchange="LoadMaxSize('14');">
			<?php
			$skin_preview ='';
			foreach($res14_adblock as $key=>$result)
			{
				$height=$result['height'];
				$width=$result['width'];
				$id=$result['id'];
				$dimension=$result['name'];
				$image_name= $this->get_skin_preview($id);

				$skin_preview .='<div class="skin_prev" id="skin_prev'.$id.'"><img src="'.$image_name.'"></div>';
			?>
			<option value="<?php echo $id;?>" <?php if($this->get_variable("bannersize") == $id) { echo "selected"; }?>><?php echo $dimension ?></option>
			<?php	} ?>
		</select>
		<?php }else{ ?>

		<?php if($positionAdType == 14){?>
		<input type="hidden" name="bannersize_14" id="bannersize_14" value="<?php echo $adblockID;?>" />
		<input type="text" class="form-control" disabled="disabled" value="<?php echo $this->get_adblock_name($adblockID);?>" />
		<?php }
	 	} ?>

		<div class="mt-1"> <?php echo $skin_preview; ?></div>
	</span>
</div>

<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3 spec_tr_skin " style="display :none;">
	<?php
	$iiii = 0;
	$ids  = '';
	foreach($res14_banner as $key=>$result)
	{
		$height=$result['height'];
		$width=$result['width'];
		$id=$result['id'];

		if($ids !='')
		$ids .=',';

		$ids .=$id;
		?>
		<label class="form-label mb-1">
			<bdi><?php echo $this->get_label('banner')." ".$width.' x '.$height;?></bdi><span class="compulsory">*</span>
		</label>
		<input class="form-control" type="file" name="skin_banner_<?php echo $id; ?>" />

		<?php if($iiii == 0){?>
		<div class="notification">
			<bdi>[<?php echo $this->get_label('supported image format');?>]</bdi>
		</div>
		<?php }?>

		<div class="notification" id="max-size-skin-<?php echo $result['id'];?>">
			<bdi>[<?php echo $this->get_label('max file size',array('x'=>$result['filesize']));?>]</bdi>
		</div>
		<?php
		$iiii++;
	}?>

	<input type ="hidden" name="existing_dimensions" value ="<?php echo $ids; ?>" />
</div>
<?php }

if($ecommerce_enabled == 1)
{
	$support_string = "";
	$size_checked   = $this->get_variable('size_checked');
	$banner_select  = $this->get_variable("bannersize");

	$res3 = $this->get_result('res3');

	foreach($res3 as $key => $result)
	{
			if($support_string != "")
			$support_string.= '-';

			$support_string.= $result['id'].'_'.$result['filesize'];
	}
	?>
	<input type="hidden" name="bannersize_07" id="bannersize_07" value="<?php echo $banner_select;?>" />
	<input type="hidden" name="supported_size" id="supported_size" value="<?php echo $support_string;?>" />
	<input type="hidden" name="size_checked" id="size_checked" value="<?php echo $size_checked;?>" />

	<?php
	$res3=$this->get_result('res3');

	foreach($res3 as $key=>$result)
	{
		$height    = $result['height'];
		$width     = $result['width'];
		$id        = $result['id'];
		$dimension = $result['width']." x ".$result['height'];
		?>

		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mb-0 spec_tr_ecommerce">
			<div class="form-check form-check-inline">
				<input class="form-check-input spc-chk-box mt-1" type="checkbox" name="chk_diamension_<?php echo $id;?>" id="chk_diamension_<?php echo $id;?>" onclick="FillCheckBox(<?php echo $id;?>);" value="1" <?php if($this->get_variable('chk_diamension_'.$result['id']) ==1){?> checked="checked" <?php }?> />
				<label class="form-check-label">
					<bdi>
						<?php echo $this->get_label('banner size');?> -
						<?php echo $dimension;?>
					</bdi>
			  </label>
			</div>
		</div>

		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-0 spec_tr_ecommerce">
			<span class="display-layout-span" id="hidden-layout-<?php echo $result['id'];?>" style="display: none;">
				<?php echo $this->get_display_layout_list($result['id'],intval($this->get_variable('layoutid_'.$result['id'])));?>

				<div class="notification">
					<?php echo $this->get_max_ad_count($id);?>
				</div>

				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mt-2">
						<span class="link_button show-span-button mb-2" id="show-span-button-<?php echo $result['id'];?>" style="display:none;" onclick="ShowPreview(<?php echo $result['id'];?>);">
							<?php echo $this->get_label('preview');?>
						</span>
					</div>
				</div>

			</span>
		</div>

		<div class="row mb-3 layout-preview-banner-<?php echo $id;?>" style = "height : <?php echo $height + 40;?>px;display : none;">
				<div class="col-sm-12 col-md-12 col-xs-12 position-absolute">
					<?php echo $this->get_display_ad_preview($id); ?>
				</div>
		</div>
		<?php }
}?>


<?php if($affiliate_enabled == 1)
{
		$res1  = $this->get_result('res1');
		$idata = 0;

		foreach($res1 as $key => $result)
		{?>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3 affiliate-content">
				<label class="form-label mb-1"><bdi><?php echo $this->get_label('banner').' - '.$result['width']." x ".$result['height'];?></bdi></label>
				<input class="form-control" type="file" name="banner_<?php echo $result['id'];?>" />

				<div class="notification">
					<bdi>[<?php echo $this->get_label('max file size',array('x'=>$result['filesize']));?>]</bdi>
				</div>

				<?php if($idata == 0){?>
				<div class="notification">
					<bdi>[<?php echo $this->get_label('supported image format');?>]</bdi>
				</div>
				<?php }?>
			</div>
		<?php
		$idata = $idata+1;
		}
}?>


<?php if($ecommerce_enabled == 1){

	$htype          = intval($this->get_variable('htype'));
	$hdata					= $this->get_variable('hdata');
	$hlink					= $this->get_variable('hlink');
	$callactiontext	= $this->get_variable('callactiontext');
	?>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mb-3 spec_tr_ecommerce" style="display: none;">
		<div class="row m-0">

		<div class="col-sm-12 col-md-12 col-xs-12 p-0 mb-2">
			<div class="notification"><?php echo $this->get_label('same color apply for all display layouts');?></div>
		</div>

		<div class="col-sm-12 col-md-12 col-xs-12 p-0">
			<span class="adinfo-color-layout">
				<div><?php echo $this->get_label('ad title');?></div>
				<div><input type="color" id="color1" name="color1" class="color-picker" value="<?php echo $color1; ?>" /></div>
			</span>

			<span class="adinfo-color-layout">
				<div><?php echo $this->get_label('ad description');?></div>
				<div><input type="color" id="color2" name="color2" class="color-picker" value="<?php echo $color2; ?>" /></div>
			</span>

			<span class="adinfo-color-layout">
				<div><?php echo $this->get_label('ad display url');?></div>
				<div><input type="color" id="color3" name="color3" class="color-picker" value="<?php echo $color3; ?>" /></div>
			</span>

			<span class="adinfo-color-layout">
				<div><?php echo $this->get_label('background');?></div>
				<div><input type="color" id="color5" name="color5" class="color-picker" value="<?php echo $color5; ?>" /></div>
			</span>

			<span class="adinfo-color-layout">
				<div><?php echo $this->get_label('ad background');?></div>
				<div><input type="color" id="color6" name="color6" class="color-picker" value="<?php echo $color6; ?>" /></div>
			</span>

			<span class="adinfo-color-layout">
				<div><?php echo $this->get_label('border');?></div>
				<div><input type="color" id="color4" name="color4" class="color-picker" value="<?php echo $color4; ?>" /></div>
			</span>

			<span class="adinfo-color-layout">
				<div><?php echo $this->get_label('price');?></div>
				<div><input type="color" id="color7" name="color7" class="color-picker" value="<?php echo $color7; ?>" /></div>
			</span>

			<span class="adinfo-color-layout">
				<div><?php echo $this->get_label('ad offer price');?></div>
				<div><input type="color" id="color8" name="color8" class="color-picker" value="<?php echo $color8; ?>" /></div>
			</span>
		</div>

		<!--
		<div class="col-sm-12 col-md-12 col-xs-12" style="display: none;">
			<div class="col-sm-4 col-md-4 col-xs-12" style="display: none;">
				<div><?php echo $this->get_label('selected border');?></div>
				<div><input type="color" id="color16" name="color16" class="color-picker" value="<?php echo $color16; ?>" /></div>
			</div>
		</div>
		-->

		<div class="col-sm-12 col-md-12 col-xs-12 p-0 my-2">
			<div class="text-decoration-underline mb-1">
				<?php echo $this->get_label('call to action button');?>
			</div>
		</div>

		<div class="col-sm-12 col-md-12 col-xs-12 p-0">
			<span class="adinfo-color-layout">
				<div><?php echo $this->get_label('ad button color');?></div>
				<div><input type="color" id="color9" name="color9" class="color-picker" value="<?php echo $color9; ?>" /></div>
			</span>

			<span class="adinfo-color-layout">
				<div><?php echo $this->get_label('background button');?></div>
				<div><input type="color" id="color10" name="color10" class="color-picker" value="<?php echo $color10; ?>" /></div>
			</span>

			<span class="adinfo-color-layout">
				<div><?php echo $this->get_label('background hover');?></div>
				<div><input type="color" id="color11" name="color11" class="color-picker" value="<?php echo $color11; ?>" /></div>
			</span>
		</div>

		<div class="col-sm-12 col-md-12 col-xs-12 p-0 my-2">
			<div class="text-decoration-underline mb-1">
				<?php echo $this->get_label('headline settings');?>
			</div>
		</div>

		<div class="col-sm-12 col-md-12 col-xs-12 p-0">
			<span class="adinfo-color-layout">
				<div><?php echo $this->get_label('ad heading');?></div>
				<div><input type="color" id="color12" name="color12" class="color-picker" value="<?php echo $color12; ?>" /></div>
			</span>

			<span class="adinfo-color-layout">
				<div><?php echo $this->get_label('ad heading button');?></div>
				<div><input type="color" id="color13" name="color13" class="color-picker" value="<?php echo $color13; ?>" /></div>
			</span>

			<span class="adinfo-color-layout">
				<div><?php echo $this->get_label('background button');?></div>
				<div><input type="color" id="color14" name="color14" class="color-picker" value="<?php echo $color14; ?>" /></div>
			</span>

			<span class="adinfo-color-layout">
				<div><?php echo $this->get_label('background hover');?></div>
				<div><input type="color" id="color15" name="color15" class="color-picker" value="<?php echo $color15; ?>" /></div>
			</span>
		</div>
	</div>
</div>


<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3 spec_tr_ecommerce" style="display: none;">
	<label class="form-label mb-1"><?php echo $this->get_label('upload logo');?> <span class="compulsory">*</span></label>
	<input class="form-control" type="file" name="ecommercelogo" id="ecommercelogo" />
</div>

<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3 spec_tr_ecommerce" style="display: none;">
	<label class="form-label mb-1"><?php echo $this->get_label('headline display type');?></label>
	<div>
		<div class="form-check">
      <input class="form-check-input mt-1" type="radio" name="htype" id="htext" value="0" <?php if($htype ==0){?>checked="checked"<?php }?> onclick="DynamicDemo();" />
      <label class="form-check-label" for="normalText"><?php echo $this->get_label('text');?></label>
    </div>
    <div class="form-check">
      <input class="form-check-input mt-1" type="radio" name="htype" id="hbutton" value="1" <?php if($htype ==1){?>checked="checked"<?php }?> onclick="DynamicDemo();" />
      <label class="form-check-label" for="normalButton"><?php echo $this->get_label('button');?></label>
    </div>
	</div>
</div>

<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3 spec_tr_ecommerce" style="display: none;">
	<label class="form-label mb-1"><?php echo $this->get_label('headline text');?> <span class="compulsory">*</span></label>
	<input class="form-control" type="text" name="hdata" id="hdata" onkeyup="DynamicDemo();" value="<?php echo $hdata;?>" maxlength="<?php echo Configuration::get_instance()->read('max_headline_length');?>" />
	<div class="notification">
		<bdi>[<span class="headlinespan"><?php echo $this->get_label('max character',array('x'=>Configuration::get_instance()->read('max_headline_length')));?></span>]</bdi>
	</div>
</div>



<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3 spec_tr_ecommerce" style="display: none;">
	<label class="form-label mb-1"><?php echo $this->get_label('headline url');?> <span class="compulsory">*</span></label>
	<input class="form-control" type="text" name="hlink" id="hlink" value="<?php echo $hlink;?>" placeholder="<?php echo $this->get_label('example url');?>" />
</div>

<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3 spec_tr_ecommerce" style="display: none;">
	<label class="form-label mb-1"><?php echo $this->get_label('call action text');?></label>
	<input class="form-control" type="text" name="callactiontext" id="callactiontext" onkeyup="DynamicDemo();" value="<?php echo $callactiontext;?>" maxlength="<?php echo Configuration::get_instance()->read('max_call_action_length');?>" />
	<div class="notification">
		<bdi>[<span class="callactionspan"><?php echo $this->get_label('max character',array('x'=>Configuration::get_instance()->read('max_call_action_length')));?></span>]</bdi>
	</div>
</div>

<input type="hidden" name="file_type" id="file_type1" value="1" />
<input type="hidden" name="file_type_hidden" id="file_type_hidden" value="1" />

<!--
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3 spec_tr_ecommerce" style="display: none;">
<label class="form-label mb-1"><?php echo $this->get_label('file type');?></label>

<input type="radio" name="file_type" id="file_type0" value="0" <?php if($file_type ==0){?>checked="checked"<?php }?> onclick="ChangeFileType(0);" /><?php echo $this->get_label('csv');?>

&nbsp;&nbsp;
<input type="radio" name="file_type" id="file_type1" value="1" <?php if($file_type ==1){?>checked="checked"<?php }?> onclick="ChangeFileType(1);" /><?php echo $this->get_label('manual entry');?>

<input type="hidden" name="file_type_hidden" id="file_type_hidden" value="<?php echo $file_type;?>" />
</div>


<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3 spec_tr_ecommerce ecommerce_csv" style="display: none;">
<label class="form-label mb-1"><?php echo $this->get_label('csv file');?> <span class="compulsory">*</span>
<span style="float: right;"><?php echo $this->get_label('csv sample');?>&nbsp;&nbsp;<a id="csvdownload" href="<?php echo $this->make_url('dispatch/ecommerce-ads/1');?>"><i class="fa fa-file-text-o" title="<?php echo $this->get_label('csv sample');?>" alt="<?php echo $this->get_label('csv sample');?>"></i></a></span>
</label>
<input class="form-control" type="file" name="csvfile" size="10">
</div>
-->

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mb-3 spec_tr_ecommerce ecommerce_pop" style="display: none;">
<!-- Button trigger modal -->
     <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#EcommerceManage">
         <?php echo $this->get_label('insert items');?>
     </button>

		 <!-- <span class="link_button" data-toggle="modal" data-target="#EcommerceManage" style="white-space: nowrap;"><?php echo $this->get_label('insert items');?></span> -->

			<div style="display: none;">
				<?php
				$rowidarray=explode('-',$rowid);
				foreach($rowidarray as $k1=>$v1)
				{
					?>
					<div id="ecommerce_hidden_<?php echo $v1;?>" style="display: none;">
						<div id="ecommerce_ad_title_<?php echo $v1;?>" style="display: none;"><?php echo $this->get_variable('ad_title_'.$v1);?></div>
						<div id="ecommerce_ad_description_<?php echo $v1;?>" style="display: none;"><?php echo $this->get_variable('ad_description_'.$v1);?></div>
						<div id="ecommerce_ad_display_url_<?php echo $v1;?>" style="display: none;"><?php echo $this->get_variable('ad_display_url_'.$v1);?></div>
						<div id="ecommerce_ad_click_url_<?php echo $v1;?>" style="display: none;"><?php echo $this->get_variable('ad_click_url_'.$v1);?></div>
						<div id="ecommerce_image_url_<?php echo $v1;?>" style="display: none;"><?php echo $this->get_variable('image_url_'.$v1);?></div>
						<div id="ecommerce_ad_retargeting_url_<?php echo $v1;?>" style="display: none;"><?php echo $this->get_variable('ad_retargeting_url_'.$v1);?></div>
						<div id="ecommerce_ad_price_<?php echo $v1;?>" style="display: none;"><?php echo $this->get_variable('ad_price_'.$v1);?></div>
						<div id="ecommerce_ad_offer_price_<?php echo $v1;?>" style="display: none;"><?php echo $this->get_variable('ad_offer_price_'.$v1);?></div>
					</div>
					<?php
				}
				?>
			</div>
</div>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 spec_tr_ecommerce ecommerce_pop" style="display: none;">
   <div class="modal fade" id="EcommerceManage" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  		<div class="modal-dialog modal-xl">
      		<div class="modal-content">
        			<div class="modal-header">
              		<h4 class="modal-title"><?php echo $this->get_label('ecommerce manage');?></h4>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            	</div>
  		<div class="modal-body" style="overflow-x: auto;">
                  <table id="table-desktop" class="data_table" cellpadding="0" cellspacing="0">
	                  <tr class="data_table_head">
		                  <td ><?php echo $this->get_label('title').' ['.$this->get_label('max characters',array('x'=>$maxtitle)).']';?><span class="compulsory">*</span></td>
		                  <td ><?php echo $this->get_label('description').' ['.$this->get_label('max characters',array('x'=>$maxdesc)).']';?><span class="compulsory">*</span></td>
		                  <td ><?php echo $this->get_label('display url').' ['.$this->get_label('max characters',array('x'=>$maxdispurl)).']';?><span class="compulsory">*</span></td>
		                  <td ><?php echo $this->get_label('landing page');?><span class="compulsory">*</span></td>

											<?php if($retargeting_enabled ==1){?>
		                  <td class="retarget-td-pop" style="display: none;"><?php echo $this->get_label('retargeting url');?></td>
		                  <?php }?>

											<td ><?php echo $this->get_label('image path');?><span class="compulsory">*</span>
		                      <div class="notification"><bdi>[<?php echo $this->get_label('supported image format ecommerce');?>]</bdi></div>
		                      <div class="notification"><span class="file-size-class"></span></div>
		                  </td>
		                  <td ><?php echo $this->get_label('sale price');?></td>
		                  <td ><?php echo $this->get_label('offer price');?></td>
		                  <td ><?php echo $this->get_label('actions');?></td>
	                  </tr>
                  </table>
            			<button type="button" class="link_button float-end mt-1" data-bs-dismiss="modal"><?php echo $this->get_label('save');?></button>
                  <input type="hidden" id="row-id" name="rowid" value="<?php echo $rowid;?>" />
      			</div>
      	 </div>
  	 	</div>
  	</div>
</div>
<?php } ?>

<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3 spec_tr_banner">
	<label class="form-label mb-1"><?php echo $this->get_label('banner');?> <span class="compulsory">*</span></label>

	<?php if($html5_enabled == 1){?>
	<span class="html5-sample-download float-end" style="display: none;">
		<?php echo $this->get_label('html5 sample');?>&nbsp;&nbsp;
		<a href="<?php echo $this->make_url('dispatch/html5-ads/1');?>">
			<i class="fa fa-file-zip-o download-icon" title="<?php echo $this->get_label('download sample zip');?>" alt="<?php echo $this->get_label('download sample zip');?>"></i>
		</a>
	</span>
	<?php }?>

	<input class="form-control" type="file" name="banner" />
	<input type="hidden" name="additional_banners" id="additional_banners" value="<?php echo $additional_banners;?>" />


	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 spec_tr_banner spec_tr_ecommerce">
		<div class="notification support-format" id="support-format0" >
			<bdi>[<?php echo $this->get_label('supported image format');?>]</bdi>
		</div>

	<?php
	$sizeflag  = 1;
	$sizearray = array();

	if($banner_dimension_count > 0)
	{
	 	foreach($res1 as $key=>$result)
		{
			$sizearray[$result['id']] = 1;
			?>
			<div class="notification maxsize" id="max-size-<?php echo $result['id'];?>" style="display: none;">
				<bdi>[<?php echo $this->get_label('max file size',array('x'=>$result['filesize']));?>]</bdi>
			</div>
	<?php }}?>



	<?php if($ecommerce_enabled == 1){
	if(!isset($sizearray[$result['id']])){

		$sizearray[$result['id']] = 1;

	  foreach($res3 as $key=>$result){?>
			<div class="notification maxsize" id="max-size-<?php echo $result['id'];?>" style="display: none;">
				<bdi>[<?php echo $this->get_label('max file size',array('x'=>$result['filesize']));?>]</bdi>
			</div>
	<?php }}}

	if($textimage_enabled == 1){?>
	<?php foreach($res6 as $key=>$result){?>
		<div class="notification maxsize" id="max-size-<?php echo $result['id'];?>" style="display: none;">
			<bdi>[<?php echo $this->get_label('max file size',array('x'=>$result['filesize']));?>]</bdi>
		</div>
	<?php }}?>

		<div class="notification support-format" id="support-format0-html5" style="display: none;">

			<?php echo $this->get_label('html5 banner ad creation note');?>

			<i class="fa fa-info-circle info-icon ms-1 mt-1 html5-note"
				 tabindex="0"
				 data-bs-toggle="popover"
				 data-bs-placement="bottom"
				 data-bs-html="true"
				 data-bs-trigger="focus"
				 data-bs-title="<?php echo $this->get_label("read me");?>"
			></i>
			<div class="html5-info-div">
				<div class="mb-1"><bdi>* <?php echo $this->get_label('only zip files supported');?></bdi></div>

				<?php if($html5_enabled ==1){
				if($banner_dimension_count > 0){
				foreach($res1 as $key=>$result){?>
				<div class="maxsize mb-1" id="max-size-html5-<?php echo $result['id'];?>" style="display: none;">
					<div><bdi>* <?php echo $this->get_label('max file size',array('x'=>$result['html5_filesize']));?></bdi></div>
				</div>
				<?php }}?>

				<?php }?>

				<div class="mb-1">* <?php echo $this->get_label('index file required');?></div>

				<div class="mb-1">* <?php echo $this->get_label('please use click function');?></div>

				<div class="mb-1">* <?php echo $this->get_label('cta js file should be include in index file');?></div>

				<?php if($string != ""){?>
				<div><?php echo $string;?></div>
				<?php }?>

				<?php if(Configuration::get_instance()->read('validate_zip_folder_structure') == 1){?>
				<div class="mb-1">
					<div class="text-decoration-underline mb-1"><?php echo $this->get_label('supported folder structure');?></div>
					<div><?php echo Configuration::get_instance()->read('allowed_folders');?></div>
				</div>
				<?php }

				$allowed_file_formats 			    = Configuration::get_instance()->read('allowed_file_formats');
				$allowed_image_file_extensions  = Configuration::get_instance()->read('allowed_image_file_extensions');
				$allowed_video_file_extensions  = Configuration::get_instance()->read('allowed_video_file_extensions');
				$allowed_font_file_extensions	  = Configuration::get_instance()->read('allowed_font_file_extensions');

				if($allowed_file_formats != ""){?>
				<div class="text-decoration-underline mb-1"><?php echo $this->get_label('supported file extensions');?></div>
				<?php echo $allowed_file_formats;?>
				<?php }?>


				<?php if($allowed_image_file_extensions != ""){?>
				<div class="text-decoration-underline mb-1"><?php echo $this->get_label('supported image file extensions');?></div>
				<?php echo $allowed_image_file_extensions;?>
				<?php }?>

				<?php if($allowed_video_file_extensions != ""){?>
				<div class="text-decoration-underline mb-1"><?php echo $this->get_label('supported video file extensions');?></div>
				<?php echo $allowed_video_file_extensions;?>
				<?php }?>


				<?php if($allowed_font_file_extensions != ""){?>
				<div class="text-decoration-underline mb-1"><?php echo $this->get_label('supported font file extensions');?></div>
				<?php echo $allowed_font_file_extensions;?>
				<?php }?>

			</div>
		</div>
	</div>

</div>


<?php foreach($res1 as $responsiveKey=>$responsiveValue){?>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3 spec_tr_banner_responsive" id="responsive-<?php echo $responsiveValue['id'];?>" style="display: none;">
	<label class="form-label mb-1"><bdi><?php echo $this->get_label('banner');?> <?php echo $responsiveValue['width'].' x '.$responsiveValue['height'];?></bdi></label>
	<input class="form-control" type="file" name="banner_responsive_<?php echo $responsiveValue['id'];?>" />
</div>
<?php } ?>




<?php
if($textimage_enabled == 1){
foreach($res6 as $responsiveKey=>$responsiveValue){?>
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3 spec_tr_banner_responsive" id="responsive-<?php echo $responsiveValue['id'];?>" style="display: none;">
		<label class="form-label mb-1"><bdi><?php echo $this->get_label('banner');?> <?php echo $responsiveValue['width'].' x '.$responsiveValue['height'];?></bdi></label>
		<input class="form-control" type="file" name="banner_responsive_<?php echo $responsiveValue['id'];?>" />
	</div>
<?php }} ?>

<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3 spec_tr_video" style="display: none;">
	<?php if($video_enabled ==1 && ($linear_support ==1 || $html5_player_support ==1)){?>
	<label class="form-label mb-1"><?php echo $this->get_label('video file');?> <span class="compulsory">*</span></label>
	<input class="form-control" type="file" name="videofile" />

	<?php if($linear_support ==1 && $html5_player_support ==1){?>
	<div class="notification" ><bdi>[<?php echo $this->get_label('supported video format html5 vast');?>]</bdi></div>
	<?php } else if($linear_support ==1){?>
	<div class="notification" ><bdi>[<?php echo $this->get_label('supported video format');?>]</bdi></div>
	<?php }else if($html5_player_support ==1){?>
	<div class="notification" ><bdi>[<?php echo $this->get_label('supported video format html5');?>]</bdi></div>
	<?php }?>

	<div class="notification"><bdi>[<?php echo $this->get_label('max video file size',array('x'=>Configuration::get_instance()->read('video_file_max_size')));?>]</bdi></div>

	<div class="notification"><bdi>[<?php echo $this->get_label('max video duration',array('x'=>Configuration::get_instance()->read('video_file_max_duration')));?>]</bdi></div>

	<div class="notification"><bdi>[<?php echo $this->get_label('supported aspect ratio',array('x'=>$this->get_aspect_ratio_list()));?>]</bdi></div>
	<?php }?>
</div>

<?php if(
		$expandable_enabled == 1 ||
		$retargeting_enabled == 1 ||
		($video_enabled == 1 && $nonlinear_support == 1)
	){
	?>
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
		<?php if($retargeting_enabled == 1){?>
			<div class="form-check retarget-div mb-2" style="display: none;">
				<input class="form-check-input mt-1" type="checkbox" name="retargeting" id="retargeting" value="1" <?php if($retargeting ==1){?>checked="checked"<?php }?> />
				<label class="form-check-label"><?php echo $this->get_label('allow retargeting');?></label>
			</div>
		<?php } ?>

		<?php if($video_enabled == 1 && $nonlinear_support == 1){?>
			<div class="form-check vast-div mb-2" style="display: none;">
				<input class="form-check-input mt-1" type="checkbox" name="vast_support" id="vast_support" value="1" <?php if($vast_support ==1){?>checked="checked"<?php }?> />
				<label class="form-check-label"><?php echo $this->get_label('allow in vast player');?></label>
			</div>
		<?php } ?>

		<?php
		if($expandable_enabled == 1)
		{
				$arraybanner=array();

				if($banner_dimension_count > 0)
				{
						foreach($res1 as $key=>$result)
						{
								if($result['expandable_support'] == 1 && $result['expandable_width'] > 0 && $result['expandable_height'] > 0)
								$arraybanner[$result['id']]=$result;
						}
				}

				foreach($arraybanner as $key => $result){?>
					<div class="form-check spec_tr_expandable" id="exp-div-<?php echo $result['id'];?>">
						<input class="form-check-input mt-1 expandable_checkbox" type="checkbox" name="expandable_<?php echo $result['id'];?>" id="expandable_<?php echo $result['id'];?>" onclick="LoadExpandableSettings();" value="1" <?php if($expandable ==1){?> checked="checked" <?php }?> />
						<label class="form-check-label"><?php echo $this->get_label('enable expandable banner');?></label>
					</div>
				<?php } ?>

		</div> <!-- Div closing in case of expandable addon enabled -->


		<?php	foreach($arraybanner as $key => $result){?>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3 spec_tr_expandable" id="exp-div1-<?php echo $result['id'];?>">
					<label class="form-label mb-1"><?php echo $this->get_label('expandable banner size');?></label>

					<input class="form-control" type="text" name="expandableSizeTemp" value="<?php echo $result['expandable_width'].' x '.$result['expandable_height'];?>" disabled="disabled" />

					<div class="notification support-format-exp">
						<bdi>[<?php echo $this->get_label('supported image format');?>]</bdi>
					</div>

					<?php
					if($banner_dimension_count > 0)
					{
						foreach($res1 as $key123 => $result123){

							if($result123['id'] == $result['id']){?>
								<div class="notification maxsize-exp">
									<bdi>[<?php echo $this->get_label('max file size',array('x'=>$result123['filesize']));?>]</bdi>
								</div>

								<?php if($html5_enabled ==1){?>
									<div class="notification maxsize-exp-html5">
										<bdi>[<?php echo $this->get_label('max file size',array('x'=>$result123['html5_filesize']));?>]</bdi>
									</div>
								<?php
								}
								break;
							}
						}
				  }
				  ?>
			</div>

			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3 spec_tr_expandable_exp" id="exp-div2-<?php echo $result['id'];?>">
				<label class="form-label mb-1"><?php echo $this->get_label('expandable banner');?> </label>
				<input class="form-control" type="file" name="expandable_banner_<?php echo $result['id'];?>" />
			</div>
			<?php
			}

	 } else { ?>
	</div>	<!-- Div closing in case of expandable addon disabled -->
	<?php }
} ?>


	<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
		<div class="previewCloseDiv" onclick="HidePreviewBox();" style="display: none;">
			<span>x</span>
		</div>
		<div class="previewSection" style="display: none;"></div>
	</div>

	<div class="col-md-12 col-sm-12 col-xs-12">
		<input type="hidden" name="type1" id="type1" value="1" />
		<input type="hidden" name="firstParameter" id="firstParameter" value="<?php echo $firstParameter; ?>" />
		<input type="hidden" name="parentAdID" id="parentAdID" value="<?php echo $parentAdID; ?>" />
		<input class="submit-button" type="submit" name="submit" value="<?php echo $this->get_label('next');?>" />
	</div>

	</div>
</div>
<?php $form->end(); ?>

<?php $this->dispatch("layout/footer");?>

<script type="text/javascript">
$(document).ready(function() {

	if($(".html5-info-div").length > 0)
	{
			var options = {
						content : $(".html5-info-div").html()
				}

			var buttonElement = document.getElementsByClassName("html5-note")[0];
			var popover       = new bootstrap.Popover(buttonElement, options);
	}

changeadtype();

<?php if($ecommerce_enabled ==1){?>
DynamicDemo();
<?php }?>


if($('#type').val() ==2)
LoadMaxSize('02');
else if($('#type').val() ==11)
LoadMaxSize('11');
else if($('#type').val() ==14)
LoadMaxSize('14');

});
</script>
