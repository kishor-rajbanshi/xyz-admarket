<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script type="text/javascript" src="<?php echo COMMON_DIR_PATH;?>js/jquery.min.js"></script>
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/bootstrap.min.js'></script>
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/common.js'></script>

<?php
$active_theme=$this->read_cookie_param('active_theme');
if($active_theme =="")
$active_theme=Configuration::get_instance()->read('active_theme');
?>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo COMMON_DIR_PATH;?>css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo COMMON_DIR_PATH;?>font-awesome/css/font-awesome.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo BASE;?>css/public-style.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo THEME_DIR_PATH.$active_theme;?>/css/style.css" />


<?php 
$direction=$this->get_variable('direction');
if($direction ==1) {?>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo COMMON_DIR_PATH;?>css/bootstrap-rtl.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo BASE;?>css/public-rtl-style.css" />
<?php }?>

</head>
<body>


<?php
$deviceenabled=$this->get_addon_status('device-targeting_enabled');
$interstitial_enabled=$this->get_addon_status('interstitial_enabled');
$cpc_enabled=$this->get_addon_status('cpc_enabled');
$cpm_enabled=$this->get_addon_status('cpm_enabled');
$languageenabled=Configuration::get_instance()->read('language_enabled');
$cpa_enabled=$this->get_addon_status('cpa_enabled');
$pop_enabled=$this->get_addon_status('pop-ads_enabled');
$affiliate_enabled=$this->get_addon_status('affiliate-ads_enabled');
$title_length=intval(Configuration::get_instance()->read('max_ad_title_length'));


$textimage_enabled=$this->get_addon_status('text-image-ads_enabled');
$expandable_enabled=$this->get_variable('expandable_enabled');
$retargeting_enabled=$this->get_variable('retargeting_enabled');
$ecommerce_enabled=$this->get_variable('ecommerce_enabled');
$video_enabled=$this->get_variable('video_enabled');
$linear_support=$this->get_variable('linear_support');
$nonlinear_support=$this->get_variable('nonlinear_support');
$html5_player_support=$this->get_variable('html5_player_support');

$file_type=intval($this->get_variable('file_type'));
$rowid=$this->get_variable('rowid');

$maxtitle=Configuration::get_instance()->read('max_ad_title_length');
$maxdesc=Configuration::get_instance()->read('max_ad_desc_length');
$maxdispurl=Configuration::get_instance()->read('max_display_url_length');

$device=$this->get_variable('device');
$maxsize=$this->get_variable('maxsize');
$ecommerce_parent=$this->get_variable('ecommerce_parent');

$from=$this->get_variable("from");


if($_POST)
{
    	$cat_type=$this->get_variable("ctype");
	$aid=$this->get_variable("aid");
	$type=$this->get_variable("type");
	$bannerid=$this->get_variable("bannerid");
	$pop_type=intval($this->get_variable('pop_type'));
	$pop_window_height=$this->get_variable('pop_window_height');
	$pop_window_width=$this->get_variable('pop_window_width');
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
	$res=$this->get_result('res');
	$value1=$res[0];
	$type=$value1['type'];
	$aid=$value1['id'];
	$bannerid=$value1['banner_id'];
	
	
	if($pop_enabled ==1)
	{
    	$pop_type=$value1['pop_type'];
    	$pop_window_height=$value1['pop_window_height'];
    	$pop_window_width=$value1['pop_window_width'];
    	
    	
    	if($pop_window_height == 0)
    	$pop_window_height = "";
    	
    	if($pop_window_width == 0)
    	$pop_window_width = "";    	
	}
	else
	{
    	$pop_type=0;
    	$pop_window_height=0;
    	$pop_window_width=0;
	}
	
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

$pricing=$this->get_ad_pricing_value($aid);
?>


<style type="text/css">
.maxsize-exp , .maxsize { display:none }

.form-horizontal .control-label { padding-top:0px;}


.modal-dialog {width:90%;margin:30px auto;}
.modal-body{max-height:700px;}

.data_table{text-align:center;}

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

.label_style label 
{
	line-height: 25px !important;
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
<?php if($ecommerce_enabled ==1 && $type ==7){?>
$(document).ready(function() {


    var colorchange = true; 
 	var canvas = document.getElementById('canvas-color');
 	var canvasdimension = canvas.getContext('2d');
    
    	 
    var image = new Image();
    
    image.onload = function ()
    {
    	canvasdimension.drawImage(image, 0, 0, image.width, image.height); 
    }


    var imagesrc = 'images/picker.png';
    image.src = imagesrc;
    
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


	$('#canvas-color').mousemove(function(e) 
	{ 
	    if(colorchange && focusid !="") 
		{
	        var canvasoffset = $(canvas).offset();
			var canvasX = Math.floor(e.pageX - canvasoffset.left);
			var canvasY = Math.floor(e.pageY - canvasoffset.top);
		
		
			var imagedata = canvasdimension.getImageData(canvasX, canvasY, 1, 1);
			var pixel = imagedata.data;
			var color = pixel[2] + 256 * pixel[1] + 65536 * pixel[0];

			$('#'+focusid).val('#' + ('00000' + color.toString(16)).substr(-6));

			$('#'+focusid).css("background-color",'#' + ('00000' + color.toString(16)).substr(-6));


			ApplyColor(focusid,$('#'+focusid).val());
		}
	});

	$('#canvas-color').mouseout(function(e) 
	{ 
		$('#'+focusid).val(oldselected);
		$('#'+focusid).css("background-color",oldselected);
	});
	
		
	$('#canvas-color').click(function(e) 
	{ 
		colorchange = !colorchange;

	    oldselected = $('#'+focusid).val();
	}); 



	$('.color-picker').keyup(function(e) 
	{ 
		$('#'+focusid).css("background-color",$('#'+focusid).val());

        focuscolor=$('#'+focusid).val();

        ApplyColor(focusid,focuscolor);
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

                
                string+='<td ><input type="text" maxlength="<?php echo $maxtitle;?>" name="ad_title_'+rowidarray[i]+'" id="ad_title_'+rowidarray[i]+'" value="'+ecommerce_ad_title+'" /></td>';
                string+='<td ><input type="text" maxlength="<?php echo $maxdesc;?>" name="ad_description_'+rowidarray[i]+'" id="ad_description_'+rowidarray[i]+'" value="'+ecommerce_ad_description+'" /></td>';
                string+='<td ><input type="text" maxlength="<?php echo $maxdispurl;?>" name="ad_display_url_'+rowidarray[i]+'" id="ad_display_url_'+rowidarray[i]+'" value="'+ecommerce_ad_display_url+'" /></td>';
                string+='<td ><input type="text" name="ad_click_url_'+rowidarray[i]+'" id="ad_click_url_'+rowidarray[i]+'" value="'+ecommerce_ad_click_url+'" /></td>';

				<?php if($retargeting_enabled ==1 && ($pricing ==0 || $pricing ==6)){?>
				string+='<td ><input type="text" name="ad_retargeting_url_'+rowidarray[i]+'" id="ad_retargeting_url_'+rowidarray[i]+'" value="'+ecommerce_ad_retargeting_url+'" /></td>';
				<?php }?>

				string+='<td ><input type="text" name="image_url_'+rowidarray[i]+'" id="image_url_'+rowidarray[i]+'" value="'+ecommerce_image_url+'" /></td>';
				string+='<td ><input type="text" name="ad_price_'+rowidarray[i]+'" id="ad_price_'+rowidarray[i]+'" value="'+ecommerce_ad_price+'" style="width: 75px;" /></td>';
				string+='<td ><input type="text" name="ad_offer_price_'+rowidarray[i]+'" id="ad_offer_price_'+rowidarray[i]+'" value="'+ecommerce_ad_offer_price+'" style="width: 75px;" /></td>';
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
				string1+='<td><input maxlength="<?php echo $maxtitle;?>" name="ad_title_'+rowidarray[i]+'" id="ad_title_'+rowidarray[i]+'" value="'+ecommerce_ad_title+'" type="text"></td>';
				string1+='</tr>';
				string1+='<tr>';
				string1+='<td class="table-head-responsive"><?php echo $this->get_label('description').' ['.$this->get_label('max characters',array('x'=>$maxdesc)).']';?><span class="compulsory">*</span></td>';
				string1+='<td><input maxlength="<?php echo $maxdesc;?>" name="ad_description_'+rowidarray[i]+'" id="ad_description_'+rowidarray[i]+'" value="'+ecommerce_ad_description+'" type="text"></td>';
				string1+='</tr>';
				string1+='<tr>';
				string1+='<td class="table-head-responsive"><?php echo $this->get_label('display url').' ['.$this->get_label('max characters',array('x'=>$maxdispurl)).']';?><span class="compulsory">*</span></td>';
				string1+='<td><input maxlength="<?php echo $maxdispurl;?>" name="ad_display_url_'+rowidarray[i]+'" id="ad_display_url_'+rowidarray[i]+'" value="'+ecommerce_ad_display_url+'" type="text"></td>';
				string1+='</tr>';
				string1+='<tr>';
				string1+='<td class="table-head-responsive"><?php echo $this->get_label('landing page');?><span class="compulsory">*</span></td>';
				string1+='<td><input name="ad_click_url_'+rowidarray[i]+'" id="ad_click_url_'+rowidarray[i]+'" value="'+ecommerce_ad_click_url+'" type="text"></td>';
				string1+='</tr>';

				<?php if($retargeting_enabled ==1 && ($pricing ==0 || $pricing ==6)){?>
				string1+='<tr>';
				string1+='<td class="table-head-responsive"><?php echo $this->get_label('retargeting url');?></td>';
				string1+='<td><input name="ad_retargeting_url_'+rowidarray[i]+'" id="ad_retargeting_url_'+rowidarray[i]+'" value="'+ecommerce_ad_retargeting_url+'" type="text"></td>';
				string1+='</tr>';
				<?php }?>
				
				string1+='<tr>';
				string1+='<td class="table-head-responsive"><?php echo $this->get_label('image path');?>';
				string1+='<div class="notification" style="font-weight: normal;"><bdi>[<?php echo $this->get_label('supported image format ecommerce');?>]</bdi></div>';

				string1+='<div class="notification" style="font-weight: normal;"><span class="file-size-class"><bdi></bdi></span></div>';

				string1+='</td>';
				string1+='<td><input name="image_url_'+rowidarray[i]+'" id="image_url_'+rowidarray[i]+'" value="'+ecommerce_image_url+'" type="text"></td>';
				string1+='</tr>';
				string1+='<tr>';
				string1+='<td class="table-head-responsive"><?php echo $this->get_label('sale price');?></td>';
				string1+='<td><input name="ad_price_'+rowidarray[i]+'" id="ad_price_'+rowidarray[i]+'" value="'+ecommerce_ad_price+'" style="width: 75px;" type="text"></td>';
				string1+='</tr>';
				string1+='<tr>';
				string1+='<td class="table-head-responsive"><?php echo $this->get_label('offer price');?></td>';
				string1+='<td><input name="ad_offer_price_'+rowidarray[i]+'" id="ad_offer_price_'+rowidarray[i]+'" value="'+ecommerce_ad_offer_price+'" style="width: 75px;" type="text"></td>';
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
	flag1=0;
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

			flag1=1;
        	
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

	if($('.support-format-exp').length >0)
	$('.support-format-exp').hide();	
	
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

	if(flag1 ==0)
	{
		if($('.hide-span').length >0)
		$('.hide-span').hide();
	}
	else
	{
		if($('.hide-span').length >0)
		$('.hide-span').show();
	}
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

		if(pricing ==0 || pricing ==1 || pricing ==6) 
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
	
	if($('#bannersize_0').length >0)
	{
		typedata=$('#bannersize_0').val();

		if($('.maxsize').length >0)
		$('.maxsize').hide();

		if($('.maxsize-exp').length >0)
		$('.maxsize-exp').hide();		

		$('.support-format').hide();

		if($('.support-format-exp').length >0)
		$('.support-format-exp').hide();		

		if($('#max-size-'+typedata).length >0)
		{
			$('#max-size-'+typedata).show();
			$('#support-format0').show();
		}

		if($('#max-size-exp-'+typedata).length >0)
		{
			$('#max-size-exp-'+typedata).show();
			$('#support-format0-exp').show();
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
	type=$('#type').val();

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



function ShowDemo(type)
{
	if(type ==1)
	{
		$('.spec_demo_text').hide();	
		return;
	}
	
	if($('.spec_demo_text').css('display') =='block')
	{
		$('.spec_demo_text').hide();	
		return;
	}	
	
	tt=$('#title').val(); 
	dd=$('#desc').val();  
	uu=$('#displayurl').val();  
	
	tt=tt.replace("<","&lt;");
	tt=tt.replace(">","&gt;");
	
	dd=dd.replace("<","&lt;");
	dd=dd.replace(">","&gt;");
	
	uu=uu.replace("<","&lt;");
	uu=uu.replace(">","&gt;");



	$('#titledemo').html(tt);
	$('#descdemo').html(dd);
	$('#urldemo').html(uu);


	textimage=$('#type').val();

	if(textimage ==11 || textimage ==18)
	$('.text-image-class').show();	
	else
	$('.text-image-class').hide();	
	
	if(tt !='' || dd !='' || uu !='')
	$('.spec_demo_text').show();
}

function ShowPreview(id)
{
	$('.hide-span').slideDown('slow');

	LoadLayoutPreview(id);
}


function HidePreview(id)
{
	$('#layout-preview-'+id).hide();
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

		if($('.hide-span').length >0)
		$('.hide-span').hide();
	}
}

function LoadLayoutPreview(banner)
{
	id=$('#layoutid-'+banner).val();

	if(id >0)
	{
		$('.hide-span').show();

		$('.layout-preview-banner-'+banner+' .layout-preview').hide();

		$('#layout-preview-'+id).show();

		if($('#chk_diamension_'+banner).prop('checked'))
		$('#show-span-button-'+banner).show();
		else
		$('#show-span-button-'+banner).hide();			
	}
	else
	{
		$('.hide-span').hide();

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

			if(layout >0)
			$('#ad-count-'+layout).show();	
    	}
    }

	<?php if($ecommerce_parent ==0){?>
	ChangeFileType($('#file_type_hidden').val());
	<?php }?>
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
		"type=>2"=>array("banner_type_value=>0"=>array(
				"clickurl"=>array("notNull"=>array($this->get_message("not null")))
		)),		
		"type=>5"=>array("banner_type_value=>0"=>array(
				"clickurl"=>array("notNull"=>array($this->get_message("not null")))
		)),		
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
		"type=>13"=>array(
				"displayurl"=>array("notNull"=>array($this->get_message("not null"))),
				"clickurl"=>array("notNull"=>array($this->get_message("not null")))
		),
		"type=>14"=>array("banner_type_value=>0"=>array(
				"clickurl"=>array("notNull"=>array($this->get_message("not null")))
		))
);


if($affiliate_enabled ==1)
{
	$newarray=array("pricing=>12"=>array(
	
		"title"=>array("notNull"=>array($this->get_message("not null"))),
		"description"=>array("notNull"=>array($this->get_message("not null"))),
		"clickurl"=>array("notNull"=>array($this->get_message("not null")))
		)
	);

	$validate=array_merge($validate,$newarray);
	
}
?>

<div class="container label_style special-label ad-operation" style="width: 100%;">

<?php 

$form=$this->create_form();
$form->start("edit",$this->make_url("ad/edit/".$aid),"post",$validate);	
		
?>

<div class="col-md-12 col-sm-12 col-xs-12 layout_div_style">

<div class="col-md-12 col-sm-12 col-xs-12">
<div class="col-md-12 col-sm-12 col-xs-12 box_div_advertiser">
<h2 class="paypal_head"><?php echo $this->get_label('ad content');?></h2>


<div class="row ad-create-edit form-special1" style="margin-top: 10px;">
<div class="col-sm-6 col-md-6 col-xs-12">
<div><?php echo $this->get_label('name');?> <span class="compulsory">*</span></div>
<div>


<?php if($type !=7 || ($type ==7 && $ecommerce_parent ==0)){?>
<input class="form-control" type="text" name="name" maxlength="25" value="<?php if($_POST) { echo $this->get_variable('name'); } else { echo $value1['name']; }?>" />
<?php }else{?>

<?php if($_POST) { echo $this->get_variable('name'); } else { echo $value1['name']; }?>

<input type="hidden" name="name" value="<?php if($_POST) { echo $this->get_variable('name'); } else { echo $value1['name']; }?>" />

<?php }?>

</div></div></div>


<?php if($type==1 || $type==11 || $type==18) {?>
<div class="row ad-create-edit">
<div class="col-sm-6 col-md-6 col-xs-12">
<div><?php echo $this->get_label('title');?> <span class="compulsory">*</span></div>
<div >
<input class="form-control" type="text" name="title" id="title" onkeyup="ShowDemo(1)" value="<?php if($_POST) { echo $this->read_post_param('title'); } else { echo $value1['title'];}?>" maxlength="<?php echo Configuration::get_instance()->read('max_ad_title_length');?>">
<div class="notification"><bdi>[<span class="desktopspan"><?php echo $this->get_label('max character',array('x'=>Configuration::get_instance()->read('max_ad_title_length')));?></span>]</bdi>


<i id="preview-show" onclick="ShowDemo(0);" style="cursor: pointer;float: right;font-size: 16px;" class="fa fa-laptop" title="<?php echo $this->get_label('ad demo');?>" alt="<?php echo $this->get_label('ad demo');?>"></i>

<div class="ad_demo_div spec_demo_text" style="display: none;right: 0px;">

<table style="width: 100%;">
<tr>
<td class="text-image-class" style="display: none;vertical-align: middle;">

<?php if($_POST) {?>
<div style="width: 75px;height: 75px;background-color: #CCCCCC;"></div>
<?php }else{?>
<div style="max-width: 75px;max-height: 75px;">
<img alt="<?php echo $this->get_label('banner');?>" class="img-responsive" src="<?php echo DATA_DIR;?>/<?php echo $aid;?>_<?php echo $value1['banner'];?>" />
</div>
<?php }?>

</td>
<td style="padding-left: 2px;">
<div id="titledemo"></div>
<div id="descdemo"></div>
<div id="urldemo"></div>
</td>
</tr>
</table>
</div>





</div>
</div></div></div>


<div class="row ad-create-edit">
<div class="col-sm-6 col-md-6 col-xs-12">
<div><?php echo $this->get_label('description');?> <span class="compulsory">*</span></div>
<div>
<input class="form-control" type="text" name="desc" id="desc" onkeyup="ShowDemo(1)" value="<?php if($_POST) { echo $this->read_post_param('desc'); } else { echo $value1['description'];}?>" maxlength="<?php echo Configuration::get_instance()->read('max_ad_desc_length');?>"><div class="notification"><bdi>[<span class="desktopspan"><?php echo $this->get_label('max character',array('x'=>Configuration::get_instance()->read('max_ad_desc_length')));?></span>]</bdi></div>
</div></div></div>

<?php }?>


<?php if($type==1 || $type==11 || $type==13 || $type==18) {?>

<div class="row ad-create-edit">
<div class="col-sm-6 col-md-6 col-xs-12">
<div><?php echo $this->get_label('display url');?> <span class="compulsory">*</span></div>
<div >
<input class="form-control" type="text" name="displayurl" id="displayurl" onkeyup="ShowDemo(1)" value="<?php if($_POST) { echo $this->read_post_param('displayurl'); } else { echo $value1['display_url'];}?>" maxlength="<?php echo Configuration::get_instance()->read('max_display_url_length');?>" placeholder="<?php echo $this->get_label('example url');?>" /><div class="notification"><bdi>[<span class="desktopspan"><?php echo $this->get_label('max character',array('x'=>Configuration::get_instance()->read('max_display_url_length')));?></span>]</bdi></div>
</div></div></div>

<?php }


if($type ==2 || $type ==5 || $type ==7 || $type==11 || $type==18)
{
	$res1=$this->get_result('res1');

	if($ecommerce_enabled ==1 && $type ==7)
	{
		$support_string="";
	

		$size_checked=$this->get_variable('size_checked');
		$banner_select=$this->get_variable("bannersize");
			
		foreach($res1 as $key=>$result)
		{
			if($support_string !="")
			$support_string.='-';
				
			$support_string.=$result['id'].'_'.$result['filesize'];
		}
	?>
	
	<input type="hidden" name="bannersize_0" id="bannersize_0" value="<?php echo $banner_select;?>" />
	<input type="hidden" name="supported_size" id="supported_size" value="<?php echo $support_string;?>" />
	<input type="hidden" name="size_checked" id="size_checked" value="<?php echo $size_checked;?>" />
	<?php 
	}	
}



$vast_string='';

if($type ==13)
{
?>
	
<div class="row ad-create-edit spec_tr_video">
<div class="col-sm-6 col-md-6 col-xs-12">
<div ><?php echo $this->get_label('video file');?> <span class="compulsory">*</span></div>
<div >
<input class="form-control" type="file" name="videofile" size="10" />

<?php if($linear_support ==1 && $html5_player_support ==1){?>
<span class="notification" ><bdi>[<?php echo $this->get_label('supported video format html5 vast');?>]</bdi></span>
<?php } else if($linear_support ==1){?>
<span class="notification" ><bdi>[<?php echo $this->get_label('supported video format');?>]</bdi></span>
<?php }else if($html5_player_support ==1){?>
<span class="notification" ><bdi>[<?php echo $this->get_label('supported video format html5');?>]</bdi></span>
<?php }?>

<span class="notification"><bdi>[<?php echo $this->get_label('max video file size',array('x'=>Configuration::get_instance()->read('video_file_max_size')));?>]</bdi></span>

<span class="notification"><bdi>[<?php echo $this->get_label('max video duration',array('x'=>Configuration::get_instance()->read('video_file_max_duration')));?>]</bdi></span>

<span class="notification"><bdi>[<?php echo $this->get_label('supported aspect ratio',array('x'=>$this->get_aspect_ratio_list()));?>]</bdi></span>

</div></div>
</div>
	
	
	<?php 

}
else if($type ==2 || $type ==5 || $type==11 || $type ==14 || $type==18){?>


<div class="row ad-create-edit form-special1">
<div class="col-sm-6 col-md-6 col-xs-12">
<div>
<?php 
if($type ==14) 
echo $this->get_label('skin positions'); 
else 
echo $this->get_label('banner size');
?>
</div>
<div>

<span class="banner-select" id="banner-select-0">   
<select class="form-control" name="bannersize_0" id="bannersize_0" onchange="LoadMaxSize();">
<?php 

if($type ==14) 
$res1=$this->get_result('res14_adblock');

$vast_string="";	
$skin_preview="";	
	
	foreach($res1 as $key=>$result)
	{
		$height=$result['height'];
		$width=$result['width'];
		$id=$result['id'];
		
		if($type ==14) 
		{
			$diamensions=$result['name'];
			$image_name= $this->get_skin_preview($id);
			
			$skin_preview.='<div class="skin_prev" id="skin_prev'.$id.'" ';
			
			if($bannerid !=$id) 
			$skin_preview.=' style="display:none;" ';
			
			$skin_preview.='><img src="'.$image_name.'"></div>';
		}
		else
		{
			$diamensions=$result['width']." x ".$result['height'];
			
			if($type ==2 && $result['vast_video_support'] ==1)
			{
				if($vast_string !='')
				$vast_string.='-';		
				
				$vast_string.=$result['id'];
			}	
		}
	?>
	<option value="<?php echo $id;?>" <?php if($bannerid ==$id) { echo "selected"; }?>><?php echo $diamensions;?></option>
	<?php }?>
</select>
</span>
<input type="hidden" name="vast_banner_id" id="vast_banner_id" value="<?php echo $vast_string;?>" />

<?php if($type ==14){?>
<?php echo $skin_preview;?>
<?php }?>

</div></div></div>

<div class="clearfix"></div>

<?php if($type ==14){?>

<div class="row ad-create-edit form-special1">
<div class="col-sm-6 col-md-6 col-xs-12">
<div><?php echo $this->get_label('update banner');?></div>
<div>
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
<span> <?php echo $width.' x '.$height;?>
<input class="form-control" type="file" name="skin_banner_<?php echo $id; ?>" size="10" />
</span>

<?php if($iiii ==0){?>
<span class="notification"><bdi>[<?php echo $this->get_label('supported image format');?>]</bdi></span>
<?php }?>

<i class="notification " id="max-size-skin-<?php echo $result['id'];?>"><bdi>[<?php echo $this->get_label('max file size',array('x'=>$result['filesize']));?>]</bdi></i><br>
<?php 
$iiii++;
}?>

<input type ="hidden"  name="existing_dimensions" value ="<?php echo $ids; ?>" />

</div></div></div>

<?php }else{?>
<div class="row ad-create-edit form-special1">
<div class="col-sm-6 col-md-6 col-xs-12">
<div><?php echo $this->get_label('update banner');?></div>
<div>
<input class="form-control" type="file" name="banner" id="banner" size="10" />
<span class="notification"><bdi>[<?php echo $this->get_label('supported image format');?>]</bdi></span>


<?php foreach($res1 as $key=>$result){?>
<span class="notification maxsize" id="max-size-<?php echo $result['id'];?>"><bdi>[<?php echo $this->get_label('max file size',array('x'=>$result['filesize']));?>]</bdi></span>
<?php }?>

</div></div></div>
<?php }?>


<?php }else if($type ==7){?>

<?php if($ecommerce_parent >0){?>

<div class="row ad-create-edit spec_tr_ecommerce">
<div class="col-sm-6 col-md-6 col-xs-12">
<div ><?php echo $this->get_label('banner size');?></div>
<div >

<div>
<input type="checkbox" name="chk_dummy_diamension_<?php echo $bannerid;?>" id="chk_dummy_diamension_<?php echo $bannerid;?>" value="1" disabled checked="checked" /> <bdi><?php echo $this->get_dimension(0,$bannerid);?></bdi> &nbsp;

<input type="checkbox" name="chk_diamension_<?php echo $bannerid;?>" id="chk_diamension_<?php echo $bannerid;?>" value="1" readonly="readonly" checked="checked" style="display: none;" />
</div>

<span class="display-layout-span" id="hidden-layout-<?php echo $bannerid;?>" style="display: none;"> 
<?php echo $this->get_display_layout_list($bannerid,intval($this->get_variable('layoutid_'.$bannerid)));?>
</span>

<span class="link_button show-span-button" id="show-span-button-<?php echo $bannerid;?>" style="display:none;white-space: nowrap;" onclick="ShowPreview(<?php echo $bannerid;?>);"><?php echo $this->get_label('preview');?></span>

<?php echo $this->get_max_ad_count($bannerid);?>


</div></div></div>

<div class="row ad-create-edit spec_tr_ecommerce" style="margin-bottom: 0px;margin-top: 10px;">
<div class="col-sm-12 col-md-12 col-xs-12 hide-span layout-preview-banner-<?php echo $bannerid;?>">
<?php echo $this->get_display_ad_preview($bannerid); ?>
</div></div>

<?php }else{?>


<div class="row ad-create-edit">
<div class="col-sm-6 col-md-6 col-xs-12">
<div ><?php echo $this->get_label('banner size');?></div>
</div></div>




<?php 
foreach($res1 as $key=>$result)
{
	$height=$result['height'];
	$width=$result['width'];
	$id=$result['id'];
	$diamensions=$result['width']." x ".$result['height'];
?>

<div class="row ad-create-edit">
<div class="col-sm-6 col-md-6 col-xs-12">
<div >


<div>
<input type="checkbox" name="chk_diamension_<?php echo $id;?>" id="chk_diamension_<?php echo $id;?>" onclick="FillCheckBox(<?php echo $id;?>);" value="1" <?php if($this->get_variable('chk_diamension_'.$result['id']) ==1){?> checked="checked" <?php }?> /> <bdi><?php echo $diamensions; ?></bdi> &nbsp;
</div>

<span class="display-layout-span" id="hidden-layout-<?php echo $result['id'];?>" style="display: none;"> 
<?php echo $this->get_display_layout_list($result['id'],intval($this->get_variable('layoutid_'.$result['id'])));?>
</span>

	
<span class="link_button show-span-button" id="show-span-button-<?php echo $result['id'];?>" style="display:none;white-space: nowrap;" onclick="ShowPreview(<?php echo $result['id'];?>);"><?php echo $this->get_label('preview');?></span>

<?php echo $this->get_max_ad_count($id);?>


</div></div></div>



<div class="row ad-create-edit spec_tr_ecommerce" style="margin-bottom: 0px;margin-top: 10px;">
<div class="col-sm-12 col-md-12 col-xs-12 hide-span layout-preview-banner-<?php echo $id;?>">
<?php echo $this->get_display_ad_preview($id); ?>
</div></div>


<?php }?>




<?php }?>



<?php }?>






<?php 
if($expandable_enabled ==1 && $type ==2)
{
	$arraybanner=array();

	foreach($res1 as $key=>$result)
	{
		if($result['expandable_support'] ==1 && $result['expandable_width'] >0 && $result['expandable_height'] >0)
		$arraybanner[$result['id']]=$result;
	}

$dataid=0;
foreach($arraybanner as $key=>$result){?>

<div class="row ad-create-edit spec_tr_expandable" id="exp-div-<?php echo $result['id'];?>">
<div class="col-sm-6 col-md-6 col-xs-12">
<div ><?php echo $this->get_label('enable expandable banner');?></div>
<div >
<input type="checkbox" name="expandable_<?php echo $result['id'];?>" id="expandable_<?php echo $result['id'];?>" onclick="LoadExpandableSettings();" value="1" <?php if($expandable ==1){?> checked="checked" <?php }?> style="width: 20px !important;" />
</div></div></div>


<div class="row ad-create-edit spec_tr_expandable" id="exp-div1-<?php echo $result['id'];?>">
<div class="col-sm-6 col-md-6 col-xs-12">
<div ><?php echo $this->get_label('expandable banner size');?></div>
<div >
<bdi><?php echo $result['expandable_width'].' x '.$result['expandable_height'];?></bdi>

<?php if($dataid ==0){?>
<div>
<span class="notification support-format-exp" id="support-format0-exp" ><bdi>[<?php echo $this->get_label('supported image format');?>]</bdi></span>

<?php foreach($res1 as $key123=>$result123){?>
<span class="notification maxsize-exp" id="max-size-exp-<?php echo $result123['id'];?>" style="display: none;"><bdi>[<?php echo $this->get_label('max file size',array('x'=>$result123['filesize']));?>]</bdi></span>
<?php }?>
</div>
<?php }?>
</div></div></div>

<div class="row ad-create-edit spec_tr_expandable" id="exp-div2-<?php echo $result['id'];?>" <?php if($banner_type ==1){?>style="display: none;"<?php }?>>
<div class="col-sm-6 col-md-6 col-xs-12">
<div ><?php echo $this->get_label('expandable banner');?> </div>
<div >

<input class="form-control" type="file" name="expandable_banner_<?php echo $result['id'];?>" size="10" />

</div></div></div>

<?php 
$dataid=$dataid+1;
}?>

<?php }?>


<?php if($affiliate_enabled ==1 && $type ==0 && $pricing ==12){?>


<div class="row ad-create-edit">
<div class="col-sm-6 col-md-6 col-xs-12">
<div><?php echo $this->get_label('title');?> <span class="compulsory">*</span></div>
<div>
<input class="form-control" type="text" name="title" id="title" value="<?php if($_POST) { echo $this->read_post_param('title'); } else { echo $value1['title'];}?>">
</div></div></div>


<div class="row ad-create-edit">
<div class="col-sm-6 col-md-6 col-xs-12">
<div><?php echo $this->get_label('description');?> <span class="compulsory">*</span></div>
<div >
<textarea class="form-control" style="height: 120px !important;" rows="5" name="desc" id="desc"><?php if($_POST) { echo $this->read_post_param('desc'); } else { echo $value1['description'];}?></textarea>
</div></div></div>

<?php }?>




<?php if($type !=7){?>
<div class="row ad-create-edit form-special1">
<div class="col-sm-6 col-md-6 col-xs-12">
<div><?php echo $this->get_label('click url');?> <span class="compulsory">*</span></div>
<div>
<input class="form-control" type="text" name="clickurl" value="<?php if($_POST) { echo $this->read_post_param('clickurl'); } else { echo $value1['click_url'];}?>" placeholder="<?php echo $this->get_label('example url');?>" />
</div></div></div>
<?php }?>

<!-- code added -->

<div class="row ad-create-edit spec_tr_both">
<div class="col-sm-6 col-md-6 col-xs-12">
<div ><?php echo $this->get_label('category type');?> </div>
<div >
<?php if($_POST) $cat_type=$this->get_variable("ctype");else $cat_type=$value1['category_type'];?>
<input type="radio" name="cat_type" id="cat_type0" value="0" <?php if($cat_type ==0){?>checked="checked"<?php }?> onclick="" />&nbsp;<?php echo $this->get_label('all');?>&nbsp;&nbsp;&nbsp;
<input type="radio" name="cat_type" id="cat_type1" value="1" <?php if($cat_type ==1){?>checked="checked"<?php }?> onclick="" />&nbsp;<?php echo $this->get_label('one');?>&nbsp;&nbsp;&nbsp;
<input type="radio" name="cat_type" id="cat_type2" value="2" <?php if($cat_type ==2){?>checked="checked"<?php }?> onclick="" />&nbsp;<?php echo $this->get_label('two');?>&nbsp;&nbsp;&nbsp;

</div></div></div>
<!-- code added -->



<?php if($ecommerce_enabled ==1 && $type ==7){?>

<div class="row ad-create-edit spec_tr_ecommerce" style="margin-bottom: 10px;font-size: 10px;">

<div class="col-sm-6 col-md-6 col-xs-12 hide-span">

<div class="row">
<div class="col-sm-12 col-md-12 col-xs-12 row">


<div class="col-sm-8 col-md-8 col-xs-12 row">


<div class="col-sm-12 col-md-12 col-xs-12">
<div><span class="notification"><?php echo $this->get_label('same color apply for all display layouts');?></span></div>
</div>	


<div class="col-sm-12 col-md-12 col-xs-12">
<span style="float: left;padding: 0 12px 0px 0px;">
<div><?php echo $this->get_label('ad title');?></div>
<div><input type="text" id="color1" name="color1" class="color-picker color-selected" size="5" value="<?php echo $color1; ?>" style="background-color:<?php echo $color1; ?>" maxlength="7" onkeyup="this.value = this.value.replace(/[^A-Za-z0-9\#]/g,'');" /></div>
</span>	
	
<span style="float: left;padding: 0 12px 0px 0px;">
<div><?php echo $this->get_label('ad display url');?></div>
<div><input type="text" id="color3" name="color3" class="color-picker" size="5" value="<?php echo $color3; ?>" style="background-color:<?php echo $color3; ?>" maxlength="7" onkeyup="this.value = this.value.replace(/[^A-Za-z0-9\#]/g,'');" /></div>
</span>		
	
	
	
<span style="float: left;padding: 0 12px 0px 0px;">
<div><?php echo $this->get_label('background');?></div>
<div><input type="text" id="color5" name="color5" class="color-picker" size="5" value="<?php echo $color5; ?>" style="background-color:<?php echo $color5; ?>" maxlength="7" onkeyup="this.value = this.value.replace(/[^A-Za-z0-9\#]/g,'');" /></div>
</span>		
	
		
<span style="float: left;padding: 0 12px 0px 0px;">
<div><?php echo $this->get_label('ad background');?></div>
<div><input type="text" id="color6" name="color6" class="color-picker" size="5" value="<?php echo $color6; ?>" style="background-color:<?php echo $color6; ?>" maxlength="7" onkeyup="this.value = this.value.replace(/[^A-Za-z0-9\#]/g,'');" /></div>
</span>		

</div>
	
	
	
<div class="col-sm-12 col-md-12 col-xs-12">

<span style="float: left;padding: 0 12px 0px 0px;">
<div><?php echo $this->get_label('border');?></div>
<div><input type="text" id="color4" name="color4" class="color-picker" size="5" value="<?php echo $color4; ?>" style="background-color:<?php echo $color4; ?>" maxlength="7" onkeyup="this.value = this.value.replace(/[^A-Za-z0-9\#]/g,'');" /></div>
</span>	
	
<span style="float: left;padding: 0 12px 0px 0px;">
<div><?php echo $this->get_label('price');?></div>
<div><input type="text" id="color7" name="color7" class="color-picker" size="5" value="<?php echo $color7; ?>" style="background-color:<?php echo $color7; ?>" maxlength="7" onkeyup="this.value = this.value.replace(/[^A-Za-z0-9\#]/g,'');" /></div>
</span>	

<span style="float: left;padding: 0 12px 0px 0px;">
<div><?php echo $this->get_label('ad offer price');?></div>
<div><input type="text" id="color8" name="color8" class="color-picker" size="5" value="<?php echo $color8; ?>" style="background-color:<?php echo $color8; ?>" maxlength="7" onkeyup="this.value = this.value.replace(/[^A-Za-z0-9\#]/g,'');" /></div>
</span>


<span style="float: left;padding: 0 12px 0px 0px;">
<div><?php echo $this->get_label('ad description');?></div>
<div><input type="text" id="color2" name="color2" class="color-picker" size="5" value="<?php echo $color2; ?>" style="background-color:<?php echo $color2; ?>" maxlength="7" onkeyup="this.value = this.value.replace(/[^A-Za-z0-9\#]/g,'');" /></div>
</span>	
	
</div>



<div class="col-sm-12 col-md-12 col-xs-12" style="display: none;">
<div class="col-sm-4 col-md-4 col-xs-12" style="display: none;">
<div><?php echo $this->get_label('selected border');?></div>
<div><input type="text" id="color16" name="color16" class="color-picker" size="5" value="<?php echo $color16; ?>" style="background-color:<?php echo $color16; ?>" maxlength="7" onkeyup="this.value = this.value.replace(/[^A-Za-z0-9\#]/g,'');" /></div>
</div>
</div>


<div class="col-sm-12 col-md-12 col-xs-12">
<div><span style="text-decoration: underline;"><?php echo $this->get_label('call to action button');?></span></div>
</div>	

<div class="col-sm-12 col-md-12 col-xs-12">

<span style="float: left;padding: 0 12px 0px 0px;">
<div><?php echo $this->get_label('ad button color');?></div>
<div><input type="text" id="color9" name="color9" class="color-picker" size="5" value="<?php echo $color9; ?>" style="background-color:<?php echo $color9; ?>" maxlength="7" onkeyup="this.value = this.value.replace(/[^A-Za-z0-9\#]/g,'');" /></div>
</span>	
	
<span style="float: left;padding: 0 12px 0px 0px;">
<div><?php echo $this->get_label('background button');?></div>
<div><input type="text" id="color10" name="color10" class="color-picker" size="5" value="<?php echo $color10; ?>" style="background-color:<?php echo $color10; ?>" maxlength="7" onkeyup="this.value = this.value.replace(/[^A-Za-z0-9\#]/g,'');" /></div>
</span>	

<span style="float: left;padding: 0 12px 0px 0px;">
<div><?php echo $this->get_label('background hover');?></div>
<div><input type="text" id="color11" name="color11" class="color-picker" size="5" value="<?php echo $color11; ?>" style="background-color:<?php echo $color11; ?>" maxlength="7" onkeyup="this.value = this.value.replace(/[^A-Za-z0-9\#]/g,'');" /></div>
</span>

</div>

<div class="col-sm-12 col-md-12 col-xs-12">
<div><span style="text-decoration: underline;"><?php echo $this->get_label('headline settings');?></span></div>
</div>

<div class="col-sm-12 col-md-12 col-xs-12">


<span style="float: left;padding: 0 12px 0px 0px;">
<div><?php echo $this->get_label('ad heading');?></div>
<div><input type="text" id="color12" name="color12" class="color-picker" size="5" value="<?php echo $color12; ?>" style="background-color:<?php echo $color12; ?>" maxlength="7" onkeyup="this.value = this.value.replace(/[^A-Za-z0-9\#]/g,'');" /></div>
</span>	
	
<span style="float: left;padding: 0 12px 0px 0px;">
<div><?php echo $this->get_label('ad heading button');?></div>
<div><input type="text" id="color13" name="color13" class="color-picker" size="5" value="<?php echo $color13; ?>" style="background-color:<?php echo $color13; ?>" maxlength="7" onkeyup="this.value = this.value.replace(/[^A-Za-z0-9\#]/g,'');" /></div>
</span>	

<span style="float: left;padding: 0 12px 0px 0px;">
<div><?php echo $this->get_label('background button');?></div>
<div><input type="text" id="color14" name="color14" class="color-picker" size="5" value="<?php echo $color14; ?>" style="background-color:<?php echo $color14; ?>" maxlength="7" onkeyup="this.value = this.value.replace(/[^A-Za-z0-9\#]/g,'');" /></div>
</span>

<span style="float: left;padding: 0 12px 0px 0px;">
<div><?php echo $this->get_label('background hover');?></div>
<div><input type="text" id="color15" name="color15" class="color-picker" size="5" value="<?php echo $color15; ?>" style="background-color:<?php echo $color15; ?>" maxlength="7" onkeyup="this.value = this.value.replace(/[^A-Za-z0-9\#]/g,'');" /></div>
</span>

</div>


</div>



<div class="col-sm-4 col-md-4 col-xs-12" style="margin-top: 10px;">
<div style="background-color: #474848;width: 105px;height:105px;margin:0px auto;"><canvas id="canvas-color" var="1"></canvas></div>
</div>	




</div>
</div>

</div>	

</div>




<?php if($ecommerce_parent ==0){?>
<div class="row ad-create-edit spec_tr_ecommerce">
<div class="col-sm-6 col-md-6 col-xs-12">
<div ><?php echo $this->get_label('upload logo');?></div>
<div >

<input class="form-control" type="file" name="ecommercelogo" id="ecommercelogo" size="10" />

</div></div></div>
<?php }?>





<div class="row ad-create-edit spec_tr_ecommerce">
<div class="col-sm-6 col-md-6 col-xs-12">
<div ><?php echo $this->get_label('headline display type');?></div>
<div >

<input type="radio" name="htype" id="htext" value="0" <?php if($htype ==0){?>checked="checked"<?php }?> onclick="DynamicDemo();" />&nbsp;<?php echo $this->get_label('text');?>

&nbsp;

<input type="radio" name="htype" id="hbutton" value="1" <?php if($htype ==1){?>checked="checked"<?php }?> onclick="DynamicDemo();" />&nbsp;<?php echo $this->get_label('button');?>


</div></div></div>


<div class="row ad-create-edit spec_tr_ecommerce">
<div class="col-sm-6 col-md-6 col-xs-12">
<div ><?php echo $this->get_label('headline text');?> <span class="compulsory">*</span></div>
<div >

<input class="form-control" type="text" name="hdata" id="hdata" onkeyup="DynamicDemo();" value="<?php echo $hdata;?>" maxlength="<?php echo Configuration::get_instance()->read('max_headline_length');?>">
<div class="notification"><bdi>[<span class="headlinespan"><?php echo $this->get_label('max character',array('x'=>Configuration::get_instance()->read('max_headline_length')));?></span>]</bdi></div>


</div></div></div>





<div class="row ad-create-edit spec_tr_ecommerce">
<div class="col-sm-6 col-md-6 col-xs-12">
<div ><?php echo $this->get_label('headline url');?> <span class="compulsory">*</span></div>
<div >

<input class="form-control" type="text" name="hlink" id="hlink" value="<?php echo $hlink;?>" placeholder="<?php echo $this->get_label('example url');?>" />

</div></div></div>



<div class="row ad-create-edit spec_tr_ecommerce">
<div class="col-sm-6 col-md-6 col-xs-12">
<div ><?php echo $this->get_label('call action text');?></div>
<div >

<input class="form-control" type="text" name="callactiontext" id="callactiontext" onkeyup="DynamicDemo();" value="<?php echo $callactiontext;?>" maxlength="<?php echo Configuration::get_instance()->read('max_call_action_length');?>">
<div class="notification"><bdi>[<span class="callactionspan"><?php echo $this->get_label('max character',array('x'=>Configuration::get_instance()->read('max_call_action_length')));?></span>]</bdi></div>

</div></div></div>








<?php if($ecommerce_parent ==0){?>

<input type="hidden" name="file_type" id="file_type1" value="1" />
<input type="hidden" name="file_type_hidden" id="file_type_hidden" value="1" />


<!-- 
<div class="row ad-create-edit spec_tr_ecommerce">
<div class="col-sm-6 col-md-6 col-xs-12">
<div ><?php echo $this->get_label('file type');?></div>
<div >
<input type="radio" name="file_type" id="file_type0" value="0" <?php if($file_type ==0){?>checked="checked"<?php }?> onclick="ChangeFileType(0);" /><?php echo $this->get_label('csv');?>

&nbsp;&nbsp;
<input type="radio" name="file_type" id="file_type1" value="1" <?php if($file_type ==1){?>checked="checked"<?php }?> onclick="ChangeFileType(1);" /><?php echo $this->get_label('manual entry');?>


<input type="hidden" name="file_type_hidden" id="file_type_hidden" value="<?php echo $file_type;?>" />

</div></div></div>



<div class="row ad-create-edit spec_tr_ecommerce ecommerce_csv" style="display: none;">
<div class="col-sm-6 col-md-6 col-xs-12">
<div ><?php echo $this->get_label('csv file');?> <span class="compulsory">*</span>
<span style="float: right;"><?php echo $this->get_label('csv sample');?>&nbsp;&nbsp;
<?php if($pricing ==0 || $pricing ==6){?>
<a id="csvdownload" href="<?php echo $this->make_url('dispatch/ecommerce-ads/1/'.$aid.'/1');?>"><i class="fa fa-file-text-o" title="<?php echo $this->get_label('csv sample');?>" alt="<?php echo $this->get_label('csv sample');?>"></i></a>
<?php }else{?>
<a id="csvdownload" href="<?php echo $this->make_url('dispatch/ecommerce-ads/1/'.$aid);?>"><i class="fa fa-file-text-o" title="<?php echo $this->get_label('csv sample');?>" alt="<?php echo $this->get_label('csv sample');?>"></i></a>
<?php }?>
</span>
</div>
<div >
<input class="form-control" type="file" name="csvfile" size="10" />
</div></div></div>
-->


<div class="row ad-create-edit spec_tr_ecommerce ecommerce_pop" style="display: none;">
<div class="col-sm-6 col-md-6 col-xs-12">
<div >

<span class="link_button" data-toggle="modal" data-target="#EcommerceManage" style="white-space: nowrap;"><?php echo $this->get_label('edit items');?></span>

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

</div></div></div>



<div class="row ad-create-edit spec_tr_ecommerce ecommerce_pop" style="display: none;">
<div class="col-sm-12 col-md-12 col-xs-12">

<div class="modal fade" id="EcommerceManage" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
    	<div class="modal-content login-modal">
      		<div class="modal-header login-modal-header">
        		<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
        		<h4 class="modal-title text-center"><?php echo $this->get_label('ecommerce manage');?></h4>
      		</div>
      		
      		
      		<div class="modal-body" style="padding: 10px 15px 25px;">

<div style="overflow-y:auto;height: 500px;width: 100%;">

<table id="table-desktop" class="data_table" cellpadding="0" cellspacing="0">
<tr class="data_table_head">
<td ><?php echo $this->get_label('image');?></td>
<td ><?php echo $this->get_label('title').' ['.$this->get_label('max characters',array('x'=>$maxtitle)).']';?><span class="compulsory">*</span></td>
<td ><?php echo $this->get_label('description').' ['.$this->get_label('max characters',array('x'=>$maxdesc)).']';?><span class="compulsory">*</span></td>
<td ><?php echo $this->get_label('display url').' ['.$this->get_label('max characters',array('x'=>$maxdispurl)).']';?><span class="compulsory">*</span></td>
<td ><?php echo $this->get_label('landing page');?><span class="compulsory">*</span></td>

<?php if($retargeting_enabled ==1 && ($pricing ==0 || $pricing ==6)){?>
<td ><?php echo $this->get_label('retargeting url');?></td>
<?php }?>

<td style="padding: 0px;"><?php echo $this->get_label('image path');?><span class="compulsory">*</span>

<div class="notification" style="font-weight: normal;"><bdi>[<?php echo $this->get_label('supported image format ecommerce');?>]</bdi></div>

<div class="notification" style="font-weight: normal;"><span class="file-size-class"></span></div>

</td>
<td ><?php echo $this->get_label('sale price');?></td>
<td ><?php echo $this->get_label('offer price');?></td>
<td ><?php echo $this->get_label('actions');?></td>
</tr>
</table>

<button type="button" class="link_button" data-dismiss="modal" style="float: right;"><?php echo $this->get_label('save');?></button>



<input type="hidden" id="row-id" name="rowid" value="<?php echo $rowid;?>" />
</div>

	      	</div>
    	</div>
	 </div>
</div>

</div></div>
<?php }?>


<?php }?>

<?php if($affiliate_enabled ==1 && $type ==0 && $pricing ==12){?>
<?php 
$res1=$this->get_result('res1');

$iii=0;
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
	
<div class="row ad-create-edit">
<div class="col-sm-6 col-md-6 col-xs-12">
<div></div>
<div>
<span class="notification"><bdi>[<?php echo $this->get_label('supported image format');?>]</bdi></span>
</div></div></div>
<?php }?>


<div class="row ad-create-edit affiliate-content">
<div class="col-sm-6 col-md-6 col-xs-12">
<div><?php echo $this->get_label('banner').' - '.$result['width']." x ".$result['height'];?></div>
<div >
<input class="form-control" type="file" name="banner_<?php echo $result['id'];?>" size="10">


<div class="notification"><bdi>[<?php echo $this->get_label('max file size',array('x'=>$result['filesize']));?>]</bdi>


<?php if($bannerpath !=''){?>
<span class="ad-popup" style="cursor: pointer;float: right;font-size: 16px;">
<i id="preview-show" class="fa fa-laptop" title="<?php echo $this->get_label('ad demo');?>" alt="<?php echo $this->get_label('ad demo');?>"></i>

<div class="ad-popup-div" style="right: 0px;">
<img class="img-responsive" style="max-width:700px;" alt="<?php echo $this->get_label('banner');?>" src="<?php echo $bannerpath;?>" />
</div>

</span>
<?php }?>

</div>

</div></div></div>



<?php 
$iii=$iii+1;
}?>


<?php }?>



<?php if($pop_enabled ==1 && (($type ==0 && $pricing ==9) || $type == 9)){


	$pop_ads_support=Configuration::get_instance()->read('pop_ads_support');
	
	$pop_array=explode('-',$pop_ads_support);
	
	
	$pop_up=intval($pop_array[0]);
	$pop_under=intval($pop_array[1]);
	$pop_tab=intval($pop_array[2]);
	
/*  $pop_type=1; POP UP Only
 *  $pop_type=2; POP UNDER Only
 *  $pop_type=3; POP TAB Only
 *  $pop_type=4; POP UP & POP UNDER 
 *  $pop_type=5; POP UNDER & POP TAB 
 *  $pop_type=6; POP UP & POP TAB 
 *  $pop_type=7; POP UP & POP UNDER & POP TAB 
 */	
	
	?>
<div class="row ad-create-edit form-special1 popdiv">
<div class="col-sm-6 col-md-6 col-xs-12">



<div><?php echo $this->get_label('pop window width');?></div>
<div >
<input type="text" name="pop_window_width" class="form-control" id="pop_window_width" value="<?php echo $pop_window_width;?>" onkeyup="this.value=this.value.replace(/[^0-9]/g, '');">

</div>

<div><?php echo $this->get_label('pop window height');?></div>
<div ><input type="text" name="pop_window_height"  class="form-control" id="pop_window_height" value="<?php echo $pop_window_height;?>"  onkeyup="this.value=this.value.replace(/[^0-9]/g, '');">

</div>
<div><?php echo $this->get_label('pop type');?> <span class="compulsory">*</span></div>
<div >

<?php if($pop_up ==1){?>
<span><input style="width: 20px !important;" type="checkbox" name="pop_up" id="pop_up" value="1" <?php if($pop_type ==1 || $pop_type ==4 || $pop_type ==6 || $pop_type ==7){?> checked="checked" <?php }?> /> <?php echo $this->get_label('popup');?></span>
<?php }?>

<?php if($pop_under ==1){?>
<span><input style="width: 20px !important;" type="checkbox" name="pop_under" id="pop_under" value="1" <?php if($pop_type ==2 || $pop_type ==4 || $pop_type ==5 || $pop_type ==7){?> checked="checked" <?php }?> /> <?php echo $this->get_label('popunder');?></span>
<?php }?>

<?php if($pop_tab ==1){?>
<span><input style="width: 20px !important;" type="checkbox" name="pop_tab" id="pop_tab" value="1" <?php if($pop_type ==3 || $pop_type ==5 || $pop_type ==6 || $pop_type ==7){?> checked="checked" <?php }?> /> <?php echo $this->get_label('poptab');?></span>
<?php }?>

</div></div>




</div>
<?php }?>




<div class="row ad-create-edit">
<div class="col-sm-6 col-md-6 col-xs-12">
<?php if($retargeting_enabled ==1 && ($pricing ==0 || $pricing ==6)){?>
<?php if($type !=7 || ($type ==7 && $ecommerce_parent ==0)){?>
<div class="col-sm-6 col-md-6 col-xs-12 padding-side">
<div><?php echo $this->get_label('allow retargeting');?></div>
<div>
<input style="width: 20px !important;" type="checkbox" name="retargeting" id="retargeting" value="1" <?php if($retargeting ==1){?>checked="checked"<?php }?> />
</div>
</div>
<?php }else{?>
<input type="hidden" name="retargeting" id="retargeting" value="<?php echo $retargeting;?>" />
<?php }?>
<?php }?>


<?php if($video_enabled ==1 && $nonlinear_support ==1 && ($pricing ==0 || $pricing ==1 || $pricing ==6) && ($type ==1 || $type ==2)){?>
<div class="col-sm-6 col-md-6 col-xs-12 padding-side vast-div">
<div ><?php echo $this->get_label('allow in vast player');?></div>
<div >
<input style="width: 20px !important;" type="checkbox" name="vast_support" id="vast_support" value="1" <?php if($vast_support ==1){?>checked="checked"<?php }?> />
</div>
</div>
<?php }else{?>
<input type="hidden" name="vast_support" id="vast_support" value="0" />
<?php }?>

</div>
</div>



</div></div>

<div class="col-md-12 col-sm-12 col-xs-12">
<div class="create_ad_div">

<input type="hidden" name="from" value="<?php echo $from;?>" />
<input type="hidden" name="pricing" id="pricing" value="<?php echo $pricing;?>" />
<input type="hidden" name="banner_type_value" id="banner_type_value" value="0" />
<input type="hidden" name="type" id="type" value="<?php echo $type;?>" />
<input type="hidden" name="aid" value="<?php echo $aid;?>" />
<input type="hidden" name="expandable_hidden" id="expandable_hidden" value="<?php echo $expandable;?>" />
<input class="create_ad_btn" type="submit" name="submit" value="<?php echo $this->get_label('update');?>" />
</div></div>

</div>
<?php $form->end(); ?>

</div>

<script type="text/javascript">
$(document).ready(function() {
<?php if($ecommerce_enabled ==1 && $type ==7){?>
DynamicDemo();

FillCheckBox(0);

<?php if($ecommerce_parent ==0){?>
ChangeFileType(<?php echo $file_type;?>);
<?php }?>

<?php }?>


<?php if($type ==2 || $type ==5 || $type ==7 || $type==11 || $type==18){?>
LoadMaxSize();
<?php }?>
});
</script>
</body>
</html>