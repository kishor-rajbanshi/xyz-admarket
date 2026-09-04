<?php $this->dispatch("layout/header_iframe");?>

<?php
$resFontExternal        = $this->get_result("resFontExternal");


$deviceenabled=$this->get_addon_status('device-targeting_enabled');
$cpc_enabled=$this->get_addon_status('cpc_enabled');
$cpm_enabled=$this->get_addon_status('cpm_enabled');
$languageenabled=Configuration::get_instance()->read('language_enabled');
$cpa_enabled=$this->get_addon_status('cpa_enabled');
$pop_enabled=$this->get_addon_status('pop-ads_enabled');
$affiliate_enabled=$this->get_addon_status('affiliate-ads_enabled');


$maxtitle   		= Configuration::get_instance()->read('max_ad_title_length');
$maxdesc    		= Configuration::get_instance()->read('max_ad_desc_length');
$maxdispurl 	    = Configuration::get_instance()->read('max_display_url_length');
$ctaButtonLength 	= Configuration::get_instance()->read('cta_button_text_length');


$textimage_enabled    = $this->get_addon_status('text-image-ads_enabled');
$expandable_enabled   = $this->get_variable('expandable_enabled');
$retargeting_enabled  = $this->get_variable('retargeting_enabled');
$ecommerce_enabled    = $this->get_variable('ecommerce_enabled');
$video_enabled        = $this->get_variable('video_enabled');
$linear_support       = $this->get_variable('linear_support');
$nonlinear_support    = $this->get_variable('nonlinear_support');
$html5_player_support = $this->get_variable('html5_player_support');

$file_type 			= intval($this->get_variable('file_type'));
$rowid 				= $this->get_variable('rowid');
$html5_enabled     	= $this->get_variable('html5_enabled');
$currentBannerName 	= $this->get_variable('currentBannerName');


$allowed_js_urls 	= "";
$allowed_font_urls  = "";

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


	$i      = 0;
	$string = '';
	foreach($jsarray as $k=>$v)
	{
		if($i == 0)
		$string.='<div class="text-decoration-underline mb-1">'.$this->get_label('supported remote js').'</div>';

		$string.='<div class="mb-1">'.$v.'</div>';

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

	$ii      = 0;
	$string1 = '';
	foreach($jsarray as $k=>$v)
	{
		if($ii == 0)
		$string1.='<div class="text-decoration-underline mb-1">'.$this->get_label('supported remote fonts').'</div>';

		$string1.='<div class="mb-1">'.$v.'</div>';

		$ii=$ii+1;
	}

	$string=$string.$string1;


$banner_type=intval($this->get_variable('banner_type'));
$upload_success=$this->get_variable('upload_success');
$additional_banners     = $this->get_variable('additional_banners');


$device=$this->get_variable('device');
$maxsize=$this->get_variable('maxsize');
$ecommerce_parent=$this->get_variable('ecommerce_parent');

$from=$this->get_variable("from");


if($_POST)
{
	$aid=$this->get_variable("aid");
	$type=$this->get_variable("type");
	$pricing=$this->get_variable("pricing");
	$bannerid=$this->get_variable("bannerid");
	$htype=intval($this->get_variable('htype'));
	$hdata=$this->get_variable('hdata');
	$hlink=$this->get_variable('hlink');
	$callactiontext=$this->get_variable('callactiontext');

	$retargeting=$this->get_variable('retargeting');
	$vast_support=$this->get_variable('vast_support');

	$expandable=$this->get_variable('expandable');


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

}
else
{
	$res      = $this->get_result('res');
	$value1   = $res[0];
	$type     = $value1['type'];
	$aid      = $value1['id'];
	$bannerid = $value1['banner_id'];
	$pricing  = $value1['display_type'];

	if($retargeting_enabled ==1)
	$retargeting=$value1['retargeting'];
	else
	$retargeting=0;

	if($expandable_enabled ==1)
	$expandable=$value1['expandable'];
	else
	$expandable=0;

	$vast_support=$value1['video_player_support'];

	if($ecommerce_enabled ==1)
	{
		$htype=$value1['headline_type'];
		$hdata=$value1['headline_text'];
		$hlink=$value1['headline_link'];
		$callactiontext=$value1['action_text'];
	}
	else
	{
		$htype=0;
		$hdata="";
		$hlink="";
		$callactiontext="";
	}

	$color1=$value1['title_color'];
	$color2=$value1['desc_color'];
	$color3=$value1['url_color'];
	$color4=$value1['border_color'];
	$color5=$value1['background_color'];
	$color6=$value1['ad_background_color'];
	$color7=$value1['price_color'];
	$color8=$value1['offer_price_color'];
	$color9=$value1['ab_text_color'];
	$color10=$value1['ab_background_color'];
	$color11=$value1['ab_bhover_color'];
	$color12=$value1['ah_text_color'];
	$color13=$value1['abh_text_color'];
	$color14=$value1['abh_background_color'];
	$color15=$value1['abh_bhover_color'];
	$color16=$value1['ad_selection_color'];
}
?>
<style type="text/css">
<?php foreach($resFontExternal as $fkey=>$fvalue){?>
@font-face {
  font-family: <?php echo $fvalue['slug_name'];?>;
  src: url(<?php echo BASE.DATA_DIR.'/fonts/'.$fvalue['file_name'];?>);
}
<?php } ?>

.maxsize-exp , .maxsize { display:none }

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
	right: 0px;
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
	});
});
</script>
<script type="text/javascript">
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
	bannerName                 = "";
	adType 					   = $('#type').val();


	if(adType == 11)
	{
		textimageSize              = $('#bannersize_0').val();
		bannerName                 = '<?php echo $aid."_".$currentBannerName;?>';
	}

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

            $('.previewSection').show();
            $('.previewCloseDiv').show();

            $('.previewSection').html(message);
        }
    });
}


function ChangeRadioType(data)
{
	$('#banner_type_value').val(data);

	type=$('#type').val();

	if(type ==2)
	LoadMaxSize();

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


<?php if($ecommerce_enabled ==1 && $type ==7){?>
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
			ecommerce_image="";

			if(rowidarray[i] != rowvalue && type ==1)
			{
				ecommerce_ad_title=$('#ecommerce_ad_title_'+rowidarray[i]).html();
				ecommerce_ad_description=$('#ecommerce_ad_description_'+rowidarray[i]).html();
				ecommerce_ad_display_url=$('#ecommerce_ad_display_url_'+rowidarray[i]).html();
				ecommerce_ad_click_url=$('#ecommerce_ad_click_url_'+rowidarray[i]).html();
				ecommerce_image_url=$('#ecommerce_image_url_'+rowidarray[i]).html();
				ecommerce_ad_retargeting_url=$('#ecommerce_ad_retargeting_url_'+rowidarray[i]).html();
				ecommerce_ad_price=$('#ecommerce_ad_price_'+rowidarray[i]).html();
				ecommerce_ad_offer_price=$('#ecommerce_ad_offer_price_'+rowidarray[i]).html();
				ecommerce_image=$('#ecommerce_ad_image_'+rowidarray[i]).val();
			}


			if($('#table-desktop #tr-id-'+rowidarray[i]).length ==0)
			{
                string='<tr id="tr-id-'+rowidarray[i]+'" class="data_table_content">';


                string+='<td >';

				if(ecommerce_image !="")
				string+='<img style="max-width:50px;max-height:50px;" src="'+ecommerce_image+'" />';

				string+='</td>';


                string+='<td ><input type="text" class="form-control" maxlength="<?php echo $maxtitle;?>" name="ad_title_'+rowidarray[i]+'" id="ad_title_'+rowidarray[i]+'" value="'+ecommerce_ad_title+'" /></td>';
                string+='<td ><input type="text" class="form-control" maxlength="<?php echo $maxdesc;?>" name="ad_description_'+rowidarray[i]+'" id="ad_description_'+rowidarray[i]+'" value="'+ecommerce_ad_description+'" /></td>';
                string+='<td ><input type="text" class="form-control" maxlength="<?php echo $maxdispurl;?>" name="ad_display_url_'+rowidarray[i]+'" id="ad_display_url_'+rowidarray[i]+'" value="'+ecommerce_ad_display_url+'" /></td>';
                string+='<td ><input type="text" class="form-control" name="ad_click_url_'+rowidarray[i]+'" id="ad_click_url_'+rowidarray[i]+'" value="'+ecommerce_ad_click_url+'" /></td>';

				<?php if($retargeting_enabled ==1 && ($pricing == 0 || ($pricing == 6 && $type != 12))){?>
				string+='<td ><input type="text" class="form-control" name="ad_retargeting_url_'+rowidarray[i]+'" id="ad_retargeting_url_'+rowidarray[i]+'" value="'+ecommerce_ad_retargeting_url+'" /></td>';
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
				string1+='<td class="table-head-responsive"><?php echo $this->get_label('image');?></td>';
				string1+='<td>';

				if(ecommerce_image !="")
				string1+='<img style="max-width:50px;max-height:50px;" src="'+ecommerce_image+'" />';

				string1+='</td>';
				string1+='</tr>';


				string1+='<tr>';
				string1+='<td class="table-head-responsive"><?php echo $this->get_label('title').' ['.$this->get_label('max characters',array('x'=>$maxtitle)).']';?></td>';
				string1+='<td><input maxlength="<?php echo $maxtitle;?>" name="ad_title_'+rowidarray[i]+'" id="ad_title_'+rowidarray[i]+'" value="'+ecommerce_ad_title+'" type="text" class="form-control"></td>';
				string1+='</tr>';
				string1+='<tr>';
				string1+='<td class="table-head-responsive"><?php echo $this->get_label('description').' ['.$this->get_label('max characters',array('x'=>$maxdesc)).']';?><span class="compulsory">*</span></td>';
				string1+='<td><input maxlength="<?php echo $maxdesc;?>" name="ad_description_'+rowidarray[i]+'" id="ad_description_'+rowidarray[i]+'" value="'+ecommerce_ad_description+'" type="text" class="form-control"></td>';
				string1+='</tr>';
				string1+='<tr>';
				string1+='<td class="table-head-responsive"><?php echo $this->get_label('display url').' ['.$this->get_label('max characters',array('x'=>$maxdispurl)).']';?><span class="compulsory">*</span></td>';
				string1+='<td><input maxlength="<?php echo $maxdispurl;?>" name="ad_display_url_'+rowidarray[i]+'" id="ad_display_url_'+rowidarray[i]+'" value="'+ecommerce_ad_display_url+'" type="text" class="form-control"></td>';
				string1+='</tr>';
				string1+='<tr>';
				string1+='<td class="table-head-responsive"><?php echo $this->get_label('landing page');?><span class="compulsory">*</span></td>';
				string1+='<td><input name="ad_click_url_'+rowidarray[i]+'" id="ad_click_url_'+rowidarray[i]+'" value="'+ecommerce_ad_click_url+'" type="text" class="form-control"></td>';
				string1+='</tr>';

				<?php if($retargeting_enabled ==1 && ($pricing == 0 || ($pricing == 6 && $type != 12))){?>
				string1+='<tr>';
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


		typedata=$('#bannersize_0').val();
		layout=$('#layoutid-'+typedata).val();

		$('.file-size-class').html($('#max-size-'+typedata).html());
	}




	//if(type ==0)
	//$('#file_type_hidden').val(0);
	//else
	$('#file_type_hidden').val(1);
}


function FillCheckBox(id)
{
	idlist=$('#supported_size').val();

	idarray=idlist.split('-');

	idarraycount=idarray.length;

	newstring="";
	firstsize=0;
	sizeid=0;
	flag=0;
	oldlayout=0;
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

	if($('.maxsize-exp').length >0)
	$('.maxsize-exp').hide();

	$('.support-format').hide();

	$('#bannersize_0').val(sizeid);

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


function ShowVASTSettings()
{
	type=$('#type').val();
	pricing=$('#pricing').val();


	if(type != 2)
	return;

	if($('.vast-div').length >0)
	{
		$('.vast-div').hide();
		$('#vast_support').prop('checked',false);


		bannertype = $('#banner_type_value').val();

		if(bannertype == 1) //HTML5 have no vast option
		return;


		if(pricing == 0 || pricing == 1 || (pricing == 6 && type != 12))
		{
			if($('#vast_banner_id').length >0 && $('#bannersize_0').length >0)
			{
				bannerid=$('#bannersize_0').val();

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

function LoadMaxSize()
{
	type=$('#type').val();
	bannertype=$('#banner_type_value').val();

	if($('#bannersize_0').length >0)
	{
		typedata=$('#bannersize_0').val();

		if($('.maxsize').length >0)
		$('.maxsize').hide();

		if($('.maxsize-exp').length >0)
		$('.maxsize-exp').hide();

		$('.support-format').hide();

		if($('.html5-sample-download').length >0)
		$('.html5-sample-download').hide();

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

		<?php if($expandable_enabled ==1){?>
		if(type ==2)
		{
			if($('.spec_tr_expandable').length >0)
			$('.spec_tr_expandable').hide();

			if($('.spec_tr_expandable_exp').length >0)
			$('.spec_tr_expandable_exp').hide();

			if($('#exp-div-'+typedata).length >0)
			$('#exp-div-'+typedata).show();

			LoadExpandableSettings();
		}
		<?php } ?>
	}

	ShowVASTSettings();

	if(type ==14)
	{
		bannersize_sel=$("#bannersize_0").val();

		$(".skin_prev").hide();
		$('#skin_prev'+bannersize_sel).show();
	}
}


function LoadExpandableSettings()
{
	type 		= $('#type').val();
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



	if(type ==2)
	{
		bannersize=$('#bannersize_0').val();

		if($('#exp-div1-'+bannersize).length >0)
		{
			if($('#expandable_'+bannersize).length >0 && $('#expandable_'+bannersize).prop('checked'))
			$('#exp-div1-'+bannersize).show();
			else if($('#expandable_'+bannersize).length >0)
			$('#exp-div1-'+bannersize).hide();
		}


		if($('#exp-div2-'+bannersize).length >0)
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
	else
	$('#expandable_hidden').val(0);
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
		if($('#type').val() == 7)
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
	id = $('#layoutid-'+banner).val();

	if(id > 0)
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

	idlist       = $('#supported_size').val();
	idarray      = idlist.split('-');
	idarraycount = idarray.length;

	$('.ad-count-list').hide();

  for(i=0;i < idarraycount;i++)
  {
    	iddata = idarray[i].split('_');

    	if($('#chk_diamension_'+iddata[0]).prop('checked'))
    	{
	    		layout=$('#layoutid-'+iddata[0]).val();

					if(layout >0)
					$('#ad-count-'+layout).show();
    	}
  }

	<?php if($ecommerce_parent ==0){?>
	ChangeFileType($('#file_type_hidden').val());
	<?php }?>
}


/*
function LoadMultipleBannerUpload()
{
	bannerSizeID = 0;
	bannerTypeID = 0;

	bannerSizeID = 'bannersize_0';
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
				"clickurl"=>array("notNull"=>array($this->get_message("not null")))
		),
		"type=>7"=>array(

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
				"clickurl"=>array("notNull"=>array($this->get_message("not null")))
		),
		"type=>12"=>array(
				"title"=>array("notNull"=>array($this->get_message("not null"))),
				"description"=>array("notNull"=>array($this->get_message("not null"))),
				"clickurl"=>array("notNull"=>array($this->get_message("not null")))
		),
		"type=>13"=>array(
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

$form=$this->create_form();
$form->start("edit",$this->make_url("ad/edit/".$aid),"post",$validate);
?>
<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 p-3 mb-3 ad-edit">
<h2 class="section-heading" ><?php echo $this->get_label('ad content');?></h2>

<div class="row">

		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3">
			<label class="form-label mb-1"><?php echo $this->get_label('name');?> <span class="compulsory">*</span></label>

			<?php if($type !=7 || ($type ==7 && $ecommerce_parent ==0)){?>
			<input class="form-control" type="text" name="name" maxlength="25" value="<?php if($_POST) { echo $this->get_variable('name'); } else { echo $value1['name']; }?>" />
			<?php }else{?>

			<input class="form-control" type="text" name="nameTemp" maxlength="25" value="<?php if($_POST) { echo $this->get_variable('name'); } else { echo $value1['name']; }?>" disabled="disabled" />

			<input type="hidden" name="name" value="<?php if($_POST) { echo $this->get_variable('name'); } else { echo $value1['name']; }?>" />

			<?php }?>
		</div>

		<?php if($type == 1 || $type == 11 || $type == 18){?>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3">
					<label class="form-label mb-1"><?php echo $this->get_label('title');?> <span class="compulsory">*</span></label>

					<input class="form-control" type="text" name="title" id="title" onKeyUp="AdPreview(1);" value="<?php if($_POST) { echo $this->read_post_param('title'); } else { echo $value1['title'];}?>" maxlength="<?php echo $maxtitle;?>" />

					<div class="notification">
						<bdi>[<?php echo $this->get_label('max character',array('x'=>$maxtitle));?>]</bdi>

						<i id="preview-show" onClick="AdPreview(0);" class="previewshow fa fa-laptop preview-icon pt-1" title="<?php echo $this->get_label('ad demo');?>" alt="<?php echo $this->get_label('ad demo');?>"></i>

						<img id="loading" src="images/load.gif" style="display: none;" />
					</div>
			</div>

			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3">
					<label class="form-label mb-1"><?php echo $this->get_label('description');?> <span class="compulsory">*</span></label>

					<input class="form-control" type="text" name="desc" id="desc" onKeyUp="AdPreview(2);" value="<?php if($_POST) { echo $this->read_post_param('desc'); } else { echo $value1['description'];}?>" maxlength="<?php echo $maxdesc;?>" />
					<div class="notification"><bdi>[<?php echo $this->get_label('max character',array('x'=>$maxdesc));?>]</bdi></div>
			</div>
		<?php }?>

		<?php if($type == 1 || $type == 11 || $type == 13){?>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3">
					<label class="form-label mb-1"><?php echo $this->get_label('display url');?> <span class="compulsory">*</span></label>

					<input class="form-control" type="text" name="displayurl" id="displayurl" onKeyUp="AdPreview(3);" value="<?php if($_POST) { echo $this->read_post_param('displayurl'); } else { echo $value1['display_url'];}?>" maxlength="<?php echo $maxdispurl;?>" placeholder="<?php echo $this->get_label('example url');?>" />
					<div class="notification"><bdi>[<?php echo $this->get_label('max character',array('x'=>$maxdispurl));?>]</bdi></div>
			</div>
		<?php }

		if($type == 1 || $type == 11){?>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3">
					<label class="form-label mb-1"><?php echo $this->get_label('cta button text');?></label>

					<input class="form-control" type="text" name="cta_button_text" id="cta_button_text" onKeyUp="AdPreview(0);" value="<?php if($_POST) { echo $this->read_post_param('cta_button_text'); } else { echo $value1['cta_button_text'];}?>" maxlength="<?php echo $ctaButtonLength;?>" placeholder="<?php echo $this->get_label('shop now');?>" />
					<div class="notification"><bdi>[<?php echo $this->get_label('max character',array('x'=>$ctaButtonLength));?>]</bdi></div>
			</div>
		<?php }

		if($type == 2 || $type == 7 || $type == 11)
		{
				$res1 = $this->get_result('res1');

				if($ecommerce_enabled == 1 && $type == 7)
				{
						$support_string = "";

						$size_checked   = $this->get_variable('size_checked');
						$banner_select  = $this->get_variable("bannersize");

						foreach($res1 as $key => $result)
						{
								if($support_string != "")
								$support_string.= '-';

								$support_string.= $result['id'].'_'.$result['filesize'];
						}
						?>

						<input type="hidden" name="bannersize_0" id="bannersize_0" value="<?php echo $banner_select;?>" />
						<input type="hidden" name="supported_size" id="supported_size" value="<?php echo $support_string;?>" />
						<input type="hidden" name="size_checked" id="size_checked" value="<?php echo $size_checked;?>" />
						<?php
				}
		}

		if($type == 13){?>

			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3 spec_tr_video">
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
			</div>

		<?php } else if($type == 2 || $type == 5 || $type == 11 || $type == 14){?>

				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3">
				<label class="form-label mb-1">
					<?php
					if($type == 14)
					echo $this->get_label('skin positions');
					else
					echo $this->get_label('banner size');
					?>
				</label>

				<span class="banner-select" id="banner-select-0">
					<?php
					$vast_string  = "";
					$skin_preview = "";

					if($pricing != 3){?>
					<select class="form-select" name="bannersize_0" id="bannersize_0" onChange="LoadMaxSize();<?php if($type == 11){?> AdPreview(0); <?php } ?>">
					<?php
					if($type == 14)
					$res1 = $this->get_result('res14_adblock');

					foreach($res1 as $key=>$result)
					{
							$height = $result['height'];
							$width  = $result['width'];
							$id     = $result['id'];

							if($type == 14)
							{
									$diamensions = $result['name'];
									$image_name  = $this->get_skin_preview($id);

									$skin_preview.= '<div class="skin_prev mt-1" id="skin_prev'.$id.'" ';

									if($bannerid != $id)
									$skin_preview.= ' style="display:none;" ';

									$skin_preview.= '><img src="'.$image_name.'" /></div>';
							}
							else
							{
									$diamensions = $result['width']." x ".$result['height'];

									if($type == 2 && $result['vast_video_support'] ==1)
									{
											if($vast_string !='')
											$vast_string.= '-';

											$vast_string.= $result['id'];
									}
							}
							?>
							<option value="<?php echo $id;?>" <?php if($bannerid == $id) { echo "selected"; }?>><?php echo $diamensions;?></option>
						<?php } ?>
						</select>
					<?php } else { ?>
						<input type="hidden" name="bannersize_0" id="bannersize_0" value="<?php echo $bannerid;?>" />
						<bdi><?php echo str_replace("-", " x ", $this->get_banner_dimension($bannerid));?></bdi>
					<?php } ?>
				</span>
				<input type="hidden" name="vast_banner_id" id="vast_banner_id" value="<?php echo $vast_string;?>" />

				<?php
				if($type == 14)
				echo $skin_preview;
				?>
				</div>

				<?php if($type == 14){

					$res14_banner = $this->get_result('res14_banner');
					$ids  = "";
					$iiii = 0;
					foreach($res14_banner as $key => $result)
					{
							$height = $result['height'];
							$width  = $result['width'];
							$id     = $result['id'];

							if($ids !="")
							$ids.= ",";

							$ids.= $id;
							?>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3">
									<label class="form-label mb-1"><bdi><?php echo $width.' x '.$height." ".$this->get_label("banner");?></bdi></label>
									<input class="form-control" type="file" name="skin_banner_<?php echo $id; ?>" size="10" />

									<?php if($iiii == 0){?>
									<div class="notification"><bdi>[<?php echo $this->get_label('supported image format');?>]</bdi></div>
									<?php }?>

									<div class="notification" id="max-size-skin-<?php echo $result['id'];?>"><bdi>[<?php echo $this->get_label('max file size',array('x'=>$result['filesize']));?>]</bdi></div>
							</div>
				<?php
				$iiii++;
				}?>

				<input type ="hidden"  name="existing_dimensions" value ="<?php echo $ids; ?>" />

				<?php } else {

					if($type == 2 && $html5_enabled == 1){?>
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3 banner-radio">
								<label class="d-flex form-label mb-1"><?php echo $this->get_label('banner type');?></label>

								<div class="form-check">
								  <input class="form-check-input mt-1" type="radio" name="banner_type" id="banner_type0" value="0" <?php if($banner_type ==0){?>checked<?php }?> onClick="ChangeRadioType(0);" />
								  <label class="form-check-label" for="normalBanner"><?php echo $this->get_label('normal banner');?></label>
								</div>
								<div class="form-check">
								  <input class="form-check-input mt-1" type="radio" name="banner_type" id="banner_type1" value="1" <?php if($banner_type ==1){?>checked<?php }?> onClick="ChangeRadioType(1);" />
								  <label class="form-check-label" for="html5Banner"><?php echo $this->get_label('html5 banner');?></label>
								</div>
						</div>
					<?php }?>

					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3">
						<label class="d-flex form-label mb-1"><?php echo $this->get_label('banner');?></label>

						<input class="form-control" type="file" name="banner" id="banner" size="10" />
							<span class="load_bannerSizeID" id="load_bannersize_0" style="display: none;">
								<img src="<?php echo BASE;?>images/load.gif"/>
							</span>

							<?php
							if($html5_enabled == 1){

							if($upload_success == 1)
							$sample_label = $this->get_label('html5 zip');
							else
							$sample_label = $this->get_label('html5 sample');
							?>

							<span class="html5-sample-download" style="display: none;">
							<?php echo $sample_label;?>&nbsp;&nbsp;<a href="
							<?php
							if($banner_type ==1 && $upload_success ==1)
							echo $this->make_url('dispatch/html5-ads/1/'.$aid.'/1');
							else
							echo $this->make_url('dispatch/html5-ads/1');
							?>"><i class="fa fa-file-zip-o download-icon" title="<?php echo $sample_label;?>" alt="<?php echo $sample_label;?>"></i></a>
							</span>
							<?php }?>


							<div class="notification support-format" id="support-format0"><bdi>[<?php echo $this->get_label('supported image format');?>]</bdi></div>

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

										<?php
										if($html5_enabled == 1){

										foreach($res1 as $key=>$result){?>
										<div class="maxsize mb-1" id="max-size-html5-<?php echo $result['id'];?>">
											<div><bdi>* <?php echo $this->get_label('max file size',array('x'=>$result['html5_filesize']));?></bdi></div>
										</div>
										<?php }
										}
										?>

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
										<?php }?>


									<?php
									$allowed_file_formats 			   = Configuration::get_instance()->read('allowed_file_formats');
									$allowed_image_file_extensions = Configuration::get_instance()->read('allowed_image_file_extensions');
									$allowed_video_file_extensions = Configuration::get_instance()->read('allowed_video_file_extensions');
									$allowed_font_file_extensions	 = Configuration::get_instance()->read('allowed_font_file_extensions');
									?>

									<?php if($allowed_file_formats != ""){?>
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

					<?php foreach($res1 as $key => $result){?>
						<div class="notification maxsize" id="max-size-<?php echo $result['id'];?>"><bdi>[<?php echo $this->get_label('max file size',array('x'=>$result['filesize']));?>]</bdi></div>
					<?php }?>

				</div>
		<?php }?>

		<?php if(($type == 2 || $type == 11) && $pricing == 3)
		{		//For multi banner upload
		 		foreach($res1 as $responsiveKey=>$responsiveValue){?>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3 spec_tr_banner_responsive" id="responsive-<?php echo $responsiveValue['id'];?>" style="display: none;">
						<label class="d-flex form-label mb-1"><bdi><?php echo $this->get_label('banner');?> - <?php echo $responsiveValue['width'].' x '.$responsiveValue['height'];?></bdi></label>
						<input class="form-control" type="file" name="banner_responsive_<?php echo $responsiveValue['id'];?>" />
					</div>
		<?php }
			}
 	 }
	 else if($type == 7)
	 {
		 	if($ecommerce_parent > 0){

				$getDimensionData      = $this->get_dimension(0,$bannerid);

				$getDimensionDataArray = explode("x", $getDimensionData);

				$getDimensionHeight    = trim($getDimensionDataArray[1]);
				?>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mb-0 spec_tr_ecommerce">

					<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-0">

						<div>
							<input class="form-check-input mt-1" type="checkbox" name="chk_diamension_<?php echo $bannerid;?>" id="chk_diamension_<?php echo $bannerid;?>" value="1" readonly="readonly" checked="checked" style="display: none;" />
							<label class="form-check-label">
								<bdi>
									<?php echo $this->get_label('banner size');?> -
									<?php echo $getDimensionData;?>
								</bdi>
							</label>
						</div>

					<span class="display-layout-span" id="hidden-layout-<?php echo $bannerid;?>" style="display: none;">
						<?php echo $this->get_display_layout_list($bannerid,intval($this->get_variable('layoutid_'.$bannerid)));?>

						<div class="notification">
							<?php echo $this->get_max_ad_count($bannerid);?>
						</div>

						<div class = "row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mt-2">
								<span class="link_button show-span-button mb-2" id="show-span-button-<?php echo $bannerid;?>" style="display:none;" onClick="ShowPreview(<?php echo $bannerid;?>);">
									<?php echo $this->get_label('preview');?>
								</span>
							</div>
						</div>

					</span>
				</div>
				</div>
				</div>

				<div class="row mb-3 layout-preview-banner-<?php echo $bannerid;?>" style = "height : <?php echo $getDimensionHeight + 40;?>px;display : none;">
						<div class="col-sm-12 col-md-12 col-xs-12 position-absolute">
							<?php echo $this->get_display_ad_preview($bannerid); ?>
						</div>
				</div>

			<?php } else {

				foreach($res1 as $key=>$result)
				{
						$height      = $result['height'];
						$width       = $result['width'];
						$id          = $result['id'];
						$diamensions = $result['width']." x ".$result['height'];
						?>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mb-0">
							<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-0">
								<div class="form-check form-check-inline">
									<input class="form-check-input mt-1" type="checkbox" name="chk_diamension_<?php echo $id;?>" id="chk_diamension_<?php echo $id;?>" onClick="FillCheckBox(<?php echo $id;?>);" value="1" <?php if($this->get_variable('chk_diamension_'.$result['id']) ==1){?> checked="checked" <?php }?> />
									<label class="form-check-label"><bdi><?php echo $this->get_label('banner size')." - ".$diamensions;?></bdi></label>
								</div>


									<span class="display-layout-span" id="hidden-layout-<?php echo $result['id'];?>" style="display: none;">
									<?php echo $this->get_display_layout_list($result['id'],intval($this->get_variable('layoutid_'.$result['id'])));?>

									<div class="notification">
										<?php echo $this->get_max_ad_count($id);?>
									</div>

									<div class = "row">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mt-2">
											<span class="link_button show-span-button mb-2" id="show-span-button-<?php echo $result['id'];?>" style="display:none;" onClick="ShowPreview(<?php echo $result['id'];?>);">
												<?php echo $this->get_label('preview');?>
											</span>
										</div>
									</div>

								</span>
							</div>
							</div>
						</div>

						<div class="row mb-3 layout-preview-banner-<?php echo $id;?>" style = "height : <?php echo $height + 40;?>px;display : none;">
								<div class="col-sm-12 col-md-12 col-xs-12 position-absolute">
									<?php echo $this->get_display_ad_preview($id); ?>
								</div>
						</div>

				<?php
				}
		}
}

if($type != 7){?>
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3">
			<label class="form-label mb-1"><?php echo $this->get_label('click url');?> <span class="compulsory">*</span></label>
			<input class="form-control" type="text" name="clickurl" value="<?php if($_POST) { echo $this->read_post_param('clickurl'); } else { echo $value1['click_url'];}?>" placeholder="<?php echo $this->get_label('example url');?>" />
			<div class="notification">
				<bdi>
					<?php echo $this->get_label('macro parameters').' : '.$this->get_label('macro parameters content');?>
				</bdi>
			</div>
	</div>
<?php }

if($pricing == 18){?>
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3">
			<label class="form-label mb-1"><?php echo $this->get_label('notification icon image');?></label>
			<input class="form-control" type="file" name="notify_icon_image" id="notify_icon_image" placeholder="<?php echo $this->get_label('notification icon image');?>" />

			<div class="notification">
				<bdi>[<?php echo $this->get_label('supported image format');?>]</bdi>
			</div>

			<div class="notification">
				<bdi>[<?php echo $this->get_label('preferred icon image size is', array("x"=>Configuration::get_instance()->read('push_notification_icon_width')." x ".Configuration::get_instance()->read('push_notification_icon_height'))); ?>]</bdi>
			</div>
	</div>
<?php }

if(
		($expandable_enabled == 1 && $type == 2) ||
		($retargeting_enabled == 1 && ($pricing == 0 || ($pricing == 6 && $type != 12)) && ($type != 7 || ($type == 7 && $ecommerce_parent == 0))) ||
		($video_enabled == 1 && $nonlinear_support == 1 && ($pricing == 0 || $pricing == 1 || $pricing == 6) && ($type == 1 || $type == 2))
	){

	?>
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mt-2 mb-3">

			<?php
			if($retargeting_enabled ==1 && ($pricing == 0 || ($pricing == 6 && $type != 12)))
			{
			   if($type !=7 || ($type ==7 && $ecommerce_parent ==0)){?>

						<div class="form-check">
							<input class="form-check-input mt-1" type="checkbox" name="retargeting" id="retargeting" value="1" <?php if($retargeting == 1){?>checked="checked"<?php }?> />
							<label class="form-check-label"><?php echo $this->get_label('allow retargeting');?></label>
						</div>
				 <?php } else {?>
					 <input type="hidden" name="retargeting" id="retargeting" value="<?php echo $retargeting;?>" />
				 <?php }
		 	}?>

			<?php if($video_enabled ==1 && $nonlinear_support ==1 && ($pricing ==0 || $pricing ==1 || $pricing ==6) && ($type ==1 || $type ==2)){?>
				<div class="form-check">
					<input class="form-check-input mt-1" type="checkbox" name="vast_support" id="vast_support" value="1" <?php if($vast_support ==1){?>checked="checked"<?php }?> />
					<label class="form-check-label"><?php echo $this->get_label('allow in vast player');?></label>
				</div>
			<?php } else { ?>
				<input type="hidden" name="vast_support" id="vast_support" value="0" />
			<?php } ?>

			<?php
			if($expandable_enabled == 1 && $type == 2)
			{
					$arraybanner = array();

					foreach($res1 as $key=>$result)
					{
							if($result['expandable_support'] == 1 && $result['expandable_width'] > 0 && $result['expandable_height'] > 0)
							$arraybanner[$result['id']] = $result;
					}

					foreach($arraybanner as $key=>$result){?>

						<div class="form-check spec_tr_expandable" id="exp-div-<?php echo $result['id'];?>">
							<input class="form-check-input mt-1 expandable_checkbox" type="checkbox" name="expandable_<?php echo $result['id'];?>" id="expandable_<?php echo $result['id'];?>" onClick="LoadExpandableSettings();" value="1" <?php if($expandable ==1){?> checked="checked" <?php }?> />
							<label class="form-check-label"><?php echo $this->get_label('enable expandable banner');?></label>
						</div>
					<?php } ?>

			</div> <!-- Div closing in case of expandable addon enabled -->


			<?php	foreach($arraybanner as $key=>$result){?>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3 spec_tr_expandable" id="exp-div1-<?php echo $result['id'];?>">
						<label class="form-label mb-1"><?php echo $this->get_label('expandable banner size');?></label>

						<input class="form-control" type="text" name="expandableSizeTemp" value="<?php echo $result['expandable_width'].' x '.$result['expandable_height'];?>" disabled="disabled" />

						<div class="notification support-format-exp">
							<bdi>[<?php echo $this->get_label('supported image format');?>]</bdi>
						</div>

						<?php
						foreach($res1 as $key123=>$result123){

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
						?>
					</div>

					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3 spec_tr_expandable" id="exp-div2-<?php echo $result['id'];?>" <?php if($banner_type ==1){?>style="display: none;"<?php }?>>
						<label class="form-label mb-1"><?php echo $this->get_label('expandable banner');?> </label>
						<input class="form-control" type="file" name="expandable_banner_<?php echo $result['id'];?>" />
					</div>
				<?php
				}

		 } else { ?>
		</div>	<!-- Div closing in case of expandable addon disabled -->
		<?php }
}

if($affiliate_enabled == 1 && $pricing == 6 && $type == 12){?>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3">
	<label class="form-label mb-1"><?php echo $this->get_label('title');?> <span class="compulsory">*</span></label>
	<input class="form-control" type="text" name="title" id="title" value="<?php if($_POST) { echo $this->read_post_param('title'); } else { echo $value1['title'];}?>" />
</div>

<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3">
	<label class="form-label mb-1"><?php echo $this->get_label('description');?> <span class="compulsory">*</span></label>
	<textarea class="form-control" rows="5" name="desc" id="desc"><?php if($_POST) { echo $this->read_post_param('desc'); } else { echo $value1['description'];}?></textarea>
</div>
<?php }?>

<?php if($ecommerce_enabled == 1 && $type == 7){?>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mb-3 spec_tr_ecommerce">

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
			<div class="text-decoration-underline">
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
				<div class="text-decoration-underline"><?php echo $this->get_label('headline settings');?></div>
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


<?php if($ecommerce_parent == 0){?>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3 spec_tr_ecommerce">
	<label class="form-label mb-1"><?php echo $this->get_label('upload logo');?></label>
	<input class="form-control" type="file" name="ecommercelogo" id="ecommercelogo" />
</div>
<?php }?>

<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3 spec_tr_ecommerce">
	<label class="form-label mb-1"><?php echo $this->get_label('headline display type');?></label>

	<div class="m-0">
		<div class="form-check">
			<input class="form-check-input mt-1" type = "radio" name = "htype" id = "htext" value = "0" <?php if($htype == 0){?>checked="checked"<?php }?> onClick="DynamicDemo();" />
			<label class="form-check-label"><?php echo $this->get_label('text');?></label>
		</div>

		<div class="form-check">
			<input class="form-check-input mt-1" type = "radio" name = "htype" id = "hbutton" value = "1" <?php if($htype == 1){?>checked="checked"<?php }?> onClick="DynamicDemo();" />
			<label class="form-check-label"><?php echo $this->get_label('button');?></label>
		</div>
	</div>

</div>


<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3 spec_tr_ecommerce">
	<label class="form-label mb-1"><?php echo $this->get_label('headline text');?> <span class="compulsory">*</span></label>
	<input class="form-control" type="text" name="hdata" id="hdata" onKeyUp="DynamicDemo();" value="<?php echo $hdata;?>" maxlength="<?php echo Configuration::get_instance()->read('max_headline_length');?>">
	<div class="notification">
		<bdi>[<span class="headlinespan"><?php echo $this->get_label('max character',array('x'=>Configuration::get_instance()->read('max_headline_length')));?></span>]</bdi>
	</div>
</div>

<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3 spec_tr_ecommerce">
	<label class="form-label mb-1"><?php echo $this->get_label('headline url');?> <span class="compulsory">*</span></label>
	<input class="form-control" type="text" name="hlink" id="hlink" value="<?php echo $hlink;?>" placeholder="<?php echo $this->get_label('example url');?>" />
</div>

<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3 spec_tr_ecommerce">
	<label class="form-label mb-1"><?php echo $this->get_label('call action text');?></label>
	<input class="form-control" type="text" name="callactiontext" id="callactiontext" onKeyUp="DynamicDemo();" value="<?php echo $callactiontext;?>" maxlength="<?php echo Configuration::get_instance()->read('max_call_action_length');?>" />

	<div class="notification">
		<bdi>[<span class="callactionspan"><?php echo $this->get_label('max character',array('x'=>Configuration::get_instance()->read('max_call_action_length')));?></span>]</bdi>
	</div>
</div>

<?php if($ecommerce_parent == 0){?>

<input type="hidden" name="file_type" id="file_type1" value="1" />
<input type="hidden" name="file_type_hidden" id="file_type_hidden" value="1" />


<!--
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3 spec_tr_ecommerce">
<label class="form-label mb-1"><?php echo $this->get_label('file type');?></label>

<input type="radio" name="file_type" id="file_type0" value="0" <?php if($file_type ==0){?>checked="checked"<?php }?> onclick="ChangeFileType(0);" /><?php echo $this->get_label('csv');?>

&nbsp;&nbsp;
<input type="radio" name="file_type" id="file_type1" value="1" <?php if($file_type ==1){?>checked="checked"<?php }?> onclick="ChangeFileType(1);" /><?php echo $this->get_label('manual entry');?>

<input type="hidden" name="file_type_hidden" id="file_type_hidden" value="<?php echo $file_type;?>" />
</div>

<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-1 spec_tr_ecommerce ecommerce_csv" style="display: none;">
<label class="form-label mb-1"><?php echo $this->get_label('csv file');?> <span class="compulsory">*</span>
<span style="float: right;"><?php echo $this->get_label('csv sample');?>&nbsp;&nbsp;
<?php if($pricing == 0 || ($pricing == 6 && $type != 12)){?>
<a id="csvdownload" href="<?php echo $this->make_url('dispatch/ecommerce-ads/1/'.$aid.'/1');?>"><i class="fa fa-file-text-o" title="<?php echo $this->get_label('csv sample');?>" alt="<?php echo $this->get_label('csv sample');?>"></i></a>
<?php }else{?>
<a id="csvdownload" href="<?php echo $this->make_url('dispatch/ecommerce-ads/1/'.$aid);?>"><i class="fa fa-file-text-o" title="<?php echo $this->get_label('csv sample');?>" alt="<?php echo $this->get_label('csv sample');?>"></i></a>
<?php }?>
</span>
</label>
<input class="form-control" type="file" name="csvfile" size="10" />
</div>
-->


<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mb-3 spec_tr_ecommerce ecommerce_pop" style="display: none;">
<!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#EcommerceManage">
     	<?php echo $this->get_label('insert items');?>
    </button>
<!--<span class="link_button" data-toggle="modal" data-target="#EcommerceManage" style="white-space: nowrap;"><?php echo $this->get_label('edit items');?></span>-->

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
			<input type="hidden" name="ecommerce_ad_image_<?php echo $v1;?>" id="ecommerce_ad_image_<?php echo $v1;?>" style="display: none;" value="<?php echo $this->get_variable('ad_image_'.$v1);?>" />
			</div>
			<?php
		}
		?>
	</div>
</div>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 spec_tr_ecommerce ecommerce_pop" style="display: none;">
	<div class="modal fade" id="EcommerceManage" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"  style="overflow:scroll;">
    	<div class="modal-dialog modal-xl" style="max-width:100%;">
        	<div class="modal-content">
          		<div class="modal-header">
            		<h4 class="modal-title"><?php echo $this->get_label('ecommerce manage');?></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          		</div>
        			<div class="modal-body" style="overflow-x: auto;">
                	<table id="table-desktop" class="data_table" cellpadding="0" cellspacing="0">
                    	<tr class="data_table_head">
                        <td ><?php echo $this->get_label('image');?></td>
                        <td ><?php echo $this->get_label('title').' ['.$this->get_label('max characters',array('x'=>$maxtitle)).']';?><span class="compulsory">*</span></td>
                        <td ><?php echo $this->get_label('description').' ['.$this->get_label('max characters',array('x'=>$maxdesc)).']';?><span class="compulsory">*</span></td>
                        <td ><?php echo $this->get_label('display url').' ['.$this->get_label('max characters',array('x'=>$maxdispurl)).']';?><span class="compulsory">*</span></td>
                        <td ><?php echo $this->get_label('landing page');?><span class="compulsory">*</span></td>

                        <?php if($retargeting_enabled == 1 && ($pricing == 0 || ($pricing == 6 && $type != 12))){?>
                        <td ><?php echo $this->get_label('retargeting url');?></td>
                        <?php }?>

                        <td><?php echo $this->get_label('image path');?><span class="compulsory">*</span>

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
<?php }
}

if($affiliate_enabled == 1 && $pricing == 6 && $type == 12)
{
	$res1 = $this->get_result('res1');

	$iii  = 0;
	foreach($res1 as $key=>$result)
	{
		$bannerpath='';

		$currentimage=$this->get_banner_name($aid,$result['id']);
		if($currentimage !="")
		{
			if(file_exists(DATA_DIR.'/banners/'.$aid.'/'.$currentimage))
			$bannerpath=DATA_DIR.'/banners/'.$aid.'/'.$currentimage;
			else
			$bannerpath='';
		}


	if($iii ==0){?>

	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-1">
		<div class="notification">
			<bdi>[<?php echo $this->get_label('supported image format');?>]</bdi>
		</div>
	</div>
	<?php }?>

	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-1 affiliate-content">
		<label class="form-label mb-1">
			<bdi><?php echo $this->get_label('banner').' - '.$result['width']." x ".$result['height'];?></bdi>
		</label>

		<input class="form-control" type="file" name="banner_<?php echo $result['id'];?>" />

		<div class="notification">
			<bdi>[<?php echo $this->get_label('max file size',array('x'=>$result['filesize']));?>]</bdi>

		<?php if($bannerpath != ''){?>
			<span class="ad-popup previewshow">
				<i id="preview-show" class="fa fa-laptop preview-icon" title="<?php echo $this->get_label('ad demo');?>" alt="<?php echo $this->get_label('ad demo');?>"></i>

				<div class="ad-popup-div">
					<img class="img-responsive" style="max-width:700px;" alt="<?php echo $this->get_label('banner');?>" src="<?php echo $bannerpath;?>" />
				</div>
			</span>
		<?php }?>
		</div>
	</div>

	<?php
	$iii=$iii+1;
	}
}
?>

		<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
			<?php if($type == 1 || $type == 11 || $type == 18){?>
				<div class="previewCloseDiv" onClick="HidePreviewBox();" style="display: none;">
					<span>x</span>
				</div>
				<div class="previewSection" style="display: none;"></div>
			<?php } ?>
		</div>

		<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
			<input type="hidden" name="from" value="<?php echo $from;?>" />
			<input type="hidden" name="pricing" id="pricing" value="<?php echo $pricing;?>" />
			<input type="hidden" name="banner_type_value" id="banner_type_value" value="<?php echo $banner_type;?>" />
			<input type="hidden" name="additional_banners" id="additional_banners" value="<?php echo $additional_banners;?>" />
			<input type="hidden" name="type" id="type" value="<?php echo $type;?>" />
			<input type="hidden" name="aid" value="<?php echo $aid;?>" />
			<input type="hidden" name="expandable_hidden" id="expandable_hidden" value="<?php echo $expandable;?>" />

			<input class="submit-button" type="submit" name="submit" value="<?php echo $this->get_label('update');?>" />
		</div>

	</div>
</div>
<?php $form->end(); ?>

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



<?php if($ecommerce_enabled ==1 && $type ==7){?>
DynamicDemo();

FillCheckBox(0);

<?php if($ecommerce_parent ==0){?>
ChangeFileType(<?php echo $file_type;?>);
<?php }?>

<?php }?>


<?php if($type == 2 || $type == 7 || $type == 11){?>
LoadMaxSize();


<?php
/*
if($type ==2 || $type==11){?>
LoadMultipleBannerUpload();
<?php }
*/
?>

<?php }?>
});
</script>
<?php $this->dispatch("layout/footer_iframe");?>