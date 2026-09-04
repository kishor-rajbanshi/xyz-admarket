<?php 
$this->dispatch("layout/header/2/1/a");
$interstitial_enabled=$this->get_addon_status('interstitial_enabled');
$cpc_enabled=$this->get_addon_status('cpc_enabled');
$cpm_enabled=$this->get_addon_status('cpm_enabled');
$cpa_enabled=$this->get_addon_status('cpa_enabled');
$pop_enabled=$this->get_addon_status('pop-ads_enabled');
$affiliate_enabled=$this->get_addon_status('affiliate-ads_enabled');

$expandable_enabled=$this->get_variable('expandable_enabled');
$expandable=$this->get_variable('expandable');
$video_enabled=$this->get_variable('video_enabled');
$linear_support=$this->get_variable('linear_support');
$nonlinear_support=$this->get_variable('nonlinear_support');
$html5_player_support=$this->get_variable('html5_player_support');
$skin_enabled=$this->get_variable('skin_enabled');

$uid=$this->get_variable('uid');
$title_length=intval(Configuration::get_instance()->read('max_ad_title_length'));

$retargeting_enabled=$this->get_addon_status('retargeting_enabled');
$textimage_enabled=$this->get_addon_status('text-image-ads_enabled');
$ecommerce_enabled=$this->get_variable('ecommerce_enabled');

$text_ads_enabled=Configuration::get_instance()->read('text-ads_enabled');


$retargeting=$this->get_variable('retargeting');
$vast_support=$this->get_variable('vast_support');

$file_type=intval($this->get_variable('file_type'));
$rowid=$this->get_variable('rowid');

$maxtitle=Configuration::get_instance()->read('max_ad_title_length');
$maxdesc=Configuration::get_instance()->read('max_ad_desc_length');
$maxdispurl=Configuration::get_instance()->read('max_display_url_length');



$sponsored=$this->get_addon_status('sponsored_enabled');
$category_enabled=$this->get_addon_status('category-targeting_enabled');
$time_target_enabled=$this->get_addon_status('time-targeting_enabled');
$city_enabled=$this->get_addon_status('city-targeting_enabled');
$language_enabled=$this->get_addon_status('language-targeting_enabled');
$isp_enabled=$this->get_addon_status('isp-targeting_enabled');
$connection_enabled=$this->get_addon_status('connectiontype-targeting_enabled');

$isp_success=$this->get_variable('isp_success');

$stringarray=array();
if($category_enabled ==1)
{
	$category_enabled_ads=Configuration::get_instance()->read('category_enabled_ads');
				
	if($category_enabled_ads !='')
	$stringarray=explode('_',$category_enabled_ads);
}




$banner_dimension_count=$this->get_banner_dimension_count(2);


if($interstitial_enabled ==1)
{
	$interstitial_dimension_count=$this->get_banner_dimension_count(5);

	if($interstitial_dimension_count ==0)
	$interstitial_enabled=0;
}



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
.search_div_table
{
	margin-left: 0px;
}


.maxsize-exp , .maxsize { display:none }

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




.form-horizontal .control-label
{
   padding-top:0px;
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

		if($('.retarget-div').length >0)
		{
			if(pricing ==0 || pricing ==6)
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
</script>


<script type="text/javascript">
<?php if($ecommerce_enabled ==1){?>
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
                string+='<td ><input type="text" maxlength="<?php echo $maxtitle;?>" name="ad_title_'+rowidarray[i]+'" id="ad_title_'+rowidarray[i]+'" value="'+ecommerce_ad_title+'" /></td>';
                string+='<td ><input type="text" maxlength="<?php echo $maxdesc;?>" name="ad_description_'+rowidarray[i]+'" id="ad_description_'+rowidarray[i]+'" value="'+ecommerce_ad_description+'" /></td>';
                string+='<td ><input type="text" maxlength="<?php echo $maxdispurl;?>" name="ad_display_url_'+rowidarray[i]+'" id="ad_display_url_'+rowidarray[i]+'" value="'+ecommerce_ad_display_url+'" /></td>';
                string+='<td ><input type="text" name="ad_click_url_'+rowidarray[i]+'" id="ad_click_url_'+rowidarray[i]+'" value="'+ecommerce_ad_click_url+'" /></td>';


				<?php if($retargeting_enabled ==1){?>
				string+='<td class="retarget-td-pop"><input type="text" name="ad_retargeting_url_'+rowidarray[i]+'" id="ad_retargeting_url_'+rowidarray[i]+'" value="'+ecommerce_ad_retargeting_url+'" /></td>';
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



				<?php if($retargeting_enabled ==1){?>
				string1+='<tr class="retarget-tr-pop">';
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


		typedata=$('#bannersize_02').val();
		layout=$('#layoutid-'+typedata).val();

		$('.file-size-class').html($('#max-size-'+typedata).html());
	}



	
	if(type ==0)
	$('#file_type_hidden').val(0);
	else
	$('#file_type_hidden').val(1);



	if($('.retarget-div').length >0)
	{
		if(pricing ==0 || pricing ==6)
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
	flag1=0;
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

	$('.support-format').hide();

	if($('.maxsize-exp').length >0)
	$('.maxsize-exp').hide();

	if($('.support-format-exp').length >0)
	$('.support-format-exp').hide();

		

	$('#bannersize_02').val(sizeid);

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


function LoadMaxSize(type)
{
	bannertype=$('#banner_type_value').val();

	if($('.maxsize').length >0)
	$('.maxsize').hide();

	if($('.maxsize-exp').length >0)
	$('.maxsize-exp').hide();	

	$('.support-format').hide();

	if($('.support-format-exp').length >0)
	$('.support-format-exp').hide();	


	if($('#bannersize_'+type).length >0)
	{
		typedata=$('#bannersize_'+type).val();

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
	}


	<?php if($expandable_enabled ==1){?>

	if($('.spec_tr_expandable').length >0)
	$('.spec_tr_expandable').hide();

	if($('.spec_tr_expandable_exp').length >0)
	$('.spec_tr_expandable_exp').hide();


	if(type =='00')
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
}



function LoadExpandableSettings()
{
	type=$('#type').val();

	type1="";

	if(type ==2)
	type1='00';

	if(type1 =='00')
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
	
	<?php if($video_enabled ==1){?>
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

		if(pricing ==0 || pricing ==1 || pricing ==6) 
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
				if($('#vast_banner_id').length >0 && $('#bannersize_00').length >0)
				{
					bannerid=$('#bannersize_00').val();
					
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
	type=$('#type').val();
	pricing=$('#adpricing').val();


	if(pricing ==13)
	{
		type=13;
		$('#type').val(13);
		$('#type1').val(13);
		$('.ad-option').hide();			
		$('.video-option').show();		
	}	
	else
	{
		if(pricing !=9 && pricing !=12)
		{
			if(type ==0 || type ==13)
			{
				type=2;
				$('#type').val(2);
				$('#type1').val(2);
			}
		}
		else
		{
			type=0;
			$('#type').val(0);
			$('#type1').val(0);
		}
		
		$('.ad-option').show();			
		$('.video-option').hide();			
	}


	$('.spec_tr_video_url').hide();	

		

	if($('.retarget-div').length >0)
	{
		if(pricing ==0 || pricing ==6)
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

	if($('.popdiv').length >0)
	$('.popdiv').hide();

	if($('.affiliatediv').length >0)
	$('.affiliatediv').hide();

	if($('.spec_tr_video').length >0)
	$('.spec_tr_video').hide();

	if($('.spec_tr_skin').length >0)
	$('.spec_tr_skin').hide();
	

	if(pricing ==12)
	{
		$('.affiliate-content').show();	

		$('.adtitle-span').hide();

		$('#title').removeAttr('maxlength');
	}
	else
	{
		if($('.affiliate-content').length >0)
		$('.affiliate-content').hide();

		$('#title').attr('maxlength',<?php echo $title_length;?>);
		
		$('.adtitle-span').show();
	}


	if(pricing !=9 && pricing !=12 && $('#type').val() ==0)
	{
		$('#type').val(2);	
		$('#type1').val(2);
	}

	
	if(pricing ==9)
	{
		$('#ad_type_div').hide();
		$('#type').val(0);	
		$('#type1').val(0);
		
		$('.spec_tr_both').show();


		$('.spec_tr_text').hide();	
		$('.spec_demo_text').hide();	
		$('.spec_tr_banner').hide();

		if($('.spec_tr_ecommerce').length >0)
		$('.spec_tr_ecommerce').hide();


		$('#banner-select-01').hide();		

		$('.popdiv').show();	



		if($('.spec_tr_expandable').length >0)
		$('.spec_tr_expandable').hide();

		if($('.spec_tr_expandable_exp').length >0)
		$('.spec_tr_expandable_exp').hide();

		if($('.spec_tr_skin').length >0)
		$('.spec_tr_skin').hide();

	}
	else if(pricing ==12)
	{
		$('#ad_type_div').hide();
		$('#type').val(0);	
		$('#type1').val(0);

		$('.spec_tr_both').show();
		

		$('.spec_tr_text').show();	
		$('.spec_tr_textonly').hide();	

		$('.spec_demo_text').hide();	
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
	else if(pricing ==13)
	{
		$('.spec_tr_video').show();
		
		$('#ad_type_div').show();

		$('.spec_tr_text').hide();	
		$('.spec_tr_textonly').hide();	

		$('.spec_demo_text').hide();	
		$('.spec_tr_banner').hide();
		$('#banner-select-01').hide();		

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

		if($('#type').val() !=11)
		$('#banner-select-00').show();	

		
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
		else if($('#type').val() ==2 || $('#type').val() ==5)
		{
			$('.spec_tr_banner').show();

			if($('#type').val() ==2)
			{
				$('#banner-select-00').show();		
				$('#banner-select-01').hide();		

				if($('.spec_tr_skin').length >0)
				$('.spec_tr_skin').hide();					
			}
			else	
			{
				$('#banner-select-00').hide();		
				$('#banner-select-01').show();	

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
			$('#banner-select-05').show();			
			

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

	
	$('.stepdiv').show();	
	

	if($('.step-box-normal').length >0)
	$('.step-box-normal').hide();	

	if($('.step-box-cpd').length >0)
	$('.step-box-cpd').hide();	
	
	if($('.step-box-aff').length >0)
	$('.step-box-aff').hide();		
	
	if(pricing ==3)
	{
		if($('.step-box-cpd').length >0)
		$('.step-box-cpd').show();
	}
	else if(pricing ==12)
	{
		if($('.step-box-aff').length >0)
		$('.step-box-aff').show();
	}
	else
	{
		if($('.step-box-normal').length >0)
		$('.step-box-normal').show();
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

	if(textimage ==11)
	$('.text-image-class').show();
	else
	$('.text-image-class').hide();



	if(tt !='' || dd !='' || uu !='')
	$('.spec_demo_text').show();
}



function changeadtype()
{
	type=$('#type').val();
	adpricing=$('#adpricing').val();

	if(adpricing ==13)
	{
		type=13;
		$('#type').val(13);
		$('#type1').val(13);
		$('.ad-option').hide();			
		$('.video-option').show();			
	}	
	else
	{
		$('.ad-option').show();			
		$('.video-option').hide();

		
		if(adpricing !=9 && adpricing !=12)
		{
			if(type ==0)
			{
				type=2;
				$('#type').val(2);
				$('#type1').val(2);
			}
		}
		else
		{
			type=0;
			$('#type').val(0);
			$('#type1').val(0);
		}
	}


	if($('.spec_tr_ecommerce').length >0)
	$('.spec_tr_ecommerce').hide();

	if(type ==11)
	$('.text-image-class').show();
	else
	$('.text-image-class').hide();

	
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
	
	if($('#adpricing').val() !=9 && $('#adpricing').val() !=12)
	{
		if($('#type').val() ==1)
		{
			$('.spec_tr_text').show();	
			$('.spec_tr_both').show();

			$('#type').val(1);
			$('#type1').val(1);
		}
		else if($('#type').val() ==2 || $('#type').val() ==5 || $('#type').val() ==0)
		{
			$('.spec_tr_banner').show();
			$('.spec_tr_both').show();
	
			$('#type1').val($('#type').val());


			if($('#type').val() ==0)
			{
				$('#type').val(2);
				$('#type1').val(2);
			}
		}
		else if($('#type').val() ==11)
		{
			$('.spec_tr_text').show();	
			$('.spec_tr_banner').show();
			$('.spec_tr_both').show();
			$('.text-image-class').show();	

	
			$('#type1').val(11);
		}
		else if($('#type').val() ==13)
		{
			$('.spec_tr_video').show();
			$('.spec_tr_both').show();
			
			$('.text-image-class').hide();	

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
	}
	else
	{
		$('#type').val(0);
		$('#type1').val(0);

		if($('#adpricing').val() == 9 || $('#adpricing').val() == 12)
		$('.spec_tr_both').show();
	}
	
	changeadpricing();

	if(type ==2)
	LoadMaxSize('00');
	else if(type ==5)
	LoadMaxSize('01');
	else if(type ==11)
	LoadMaxSize('05');
	else if(type ==14)
	LoadMaxSize('14');	
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
		"type=>2"=>array("banner_type_value=>0"=>array(
				"banner"=>array("notNull"=>array($this->get_message("not null"))),
				"clickurl"=>array("notNull"=>array($this->get_message("not null")))
		)),
		"type=>5"=>array("banner_type_value=>0"=>array(
				"banner"=>array("notNull"=>array($this->get_message("not null"))),
				"clickurl"=>array("notNull"=>array($this->get_message("not null")))
		)),
		"type=>7"=>array(
		         "ecommercelogo"=>array("notNull"=>array($this->get_message("please upload logo image"))),
				 "hdata"=>array("notNull"=>array($this->get_message("not null"))),
				 "hlink"=>array("notNull"=>array($this->get_message("not null")))
		),				
		"type=>11"=>array(
				"title"=>array("notNull"=>array($this->get_message("not null"))),
				"desc"=>array("notNull"=>array($this->get_message("not null"))),
				"displayurl"=>array("notNull"=>array($this->get_message("not null"))),
				"banner"=>array("notNull"=>array($this->get_message("not null"))),
				"clickurl"=>array("notNull"=>array($this->get_message("not null")))
		),
		"type=>13"=>array(
				"videofile"=>array("notNull"=>array($this->get_message("not null"))),
				"displayurl"=>array("notNull"=>array($this->get_message("not null"))),
				"clickurl"=>array("notNull"=>array($this->get_message("not null")))
		),
		"type=>14"=>array("banner_type_value=>0"=>array(
				"clickurl"=>array("notNull"=>array($this->get_message("not null")))
		))		
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

?>

<div class="container"><h2 class="page_heading new_heading"><?php echo $this->get_label('create ad');?></h2>
<div class="page_heading-btm"></div>
</div>

  <div class="container label_style special-label">


<?php 
$form=$this->create_form();
$form->start("create",$this->make_url("ad/create"),"post",$validate);
?>

<div class="search_div search_div_table form-inline" style="width: 100%;float: left;">
<div class="form-group ad-create-top-box">
<?php echo $this->get_label('pricing');?>
<?php echo $this->get_pricing_box($adpricing);?>
<input type="hidden" name="banner_type_value" id="banner_type_value" value="0" />
</div>

<div class="form-group ad-create-top-box" id="ad_type_div">
<?php echo $this->get_label('ad type');?>
<select class="form-control" name="type" id="type" onchange="javascript:return changeadtype();" style="width: 150px;" >
	
	<?php if($pop_enabled ==1 || $affiliate_enabled ==1){?>
	<option class="ad-option" value="0" <?php if($this->get_variable('type')==0) { echo "selected"; }?>><?php echo $this->get_label('select');?></option>
	<?php }?>
	
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
    
    <?php if($interstitial_enabled ==1){?>
    <option class="ad-option" value="5" <?php if($this->get_variable('type')==5) {echo "selected";}?>><?php echo $this->get_label('interstitial ad');?></option>
    <?php }?>
    
    <?php if($video_enabled ==1 && ($linear_support ==1 || $html5_player_support ==1)){?>
    <option class="video-option" value="13" <?php if($this->get_variable('type')==13) {echo "selected";}?>><?php echo $this->get_label('video ad');?></option>
    <?php }?>    
    
    <?php if($skin_enabled ==1){?>
    <option class="ad-option" value="14" <?php if($this->get_variable('type')==14) {echo "selected";}?> ><?php echo $this->get_label('skin ad');?></option>
    <?php }?>    
	
</select>
</div>
</div>

<div class="col-md-12 col-sm-12 col-xs-12 stepdiv" style="display: block;">
<div class="row">
<?php 

$deviceenabled=$this->get_addon_status('device-targeting_enabled');
$keyword_enabled=Configuration::get_instance()->read('keyword_based_ad_display');
$date_enabled=Configuration::get_instance()->read('date_filter_enabled');
$time_enabled=Configuration::get_instance()->read('time_filter_enabled');
$day_enabled=Configuration::get_instance()->read('day_filter_enabled');


$step_array=array();
$step_cpd_array=array();
$step_aff_array=array();

$step=1;
$step_cpd=1;
$step_aff=1;


$step_array['create']=array($step++,$this->get_label('ad content'));
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

$step_array['pricing']=array($step++,$this->get_label('pricing'));


if($sponsored ==1)
{
	$step_cpd_array['create']=array($step_cpd++,$this->get_label('ad content'));
	
	if($category_enabled ==1)
	$step_cpd_array['category']=array($step_cpd++,$this->get_label('category'));
		
	$step_cpd_array['position']=array($step_cpd++,$this->get_label('position mapping'));
}


if($affiliate_enabled ==1)
{
	$step_aff_array['create']=array($step_aff++,$this->get_label('ad content'));
	//$step_aff_array['locations']=array($step_aff++,$this->get_label('locations'));
	
	if($deviceenabled ==1)
	$step_aff_array['device']=array($step_aff++,$this->get_label('device'));
	
	if($connection_enabled ==1 && $isp_success >0)
	$step_aff_array['connection']=array($step_aff++,$this->get_label('connection'));
	
	if($isp_enabled ==1 && $isp_success ==2)
	$step_aff_array['isp']=array($step_aff++,$this->get_label('isp'));
	
	$step_aff_array['pricing']=array($step_aff++,$this->get_label('pricing'));
}

?>

<ul class="steps steps-5">
<?php 
$ii=0;
foreach($step_array as $step_key=>$step_value){?>
<li class="step-box step-box-normal <?php if($ii ==0){?> current <?php }?>" id="normal-<?php echo $step_value[0];?>">
<em><?php echo $this->get_label('step').' '.$step_value[0];?></em>
<span><?php echo $step_value[1];?></span>
</li>
<?php 
$ii++;
}?>
</ul>


<?php if($sponsored ==1){?>
<ul class="steps steps-5">
<?php 
$ii=0;
foreach($step_cpd_array as $step_key=>$step_value){?>
<li class="step-box step-box-cpd <?php if($ii ==0){?> current <?php }?>" id="cpd-<?php echo $step_value[0];?>">
<em><?php echo $this->get_label('step').' '.$step_value[0];?></em>
<span><?php echo $step_value[1];?></span>
</li>	
<?php 
$ii++;	
}?>
</ul>
<?php }?>


<?php if($affiliate_enabled ==1){?>
<ul class="steps steps-5">
<?php 
	$ii=0;
	foreach($step_aff_array as $step_key=>$step_value){?>
	<li class="step-box step-box-aff <?php if($ii ==0){?> current <?php }?>" id="aff-<?php echo $step_value[0];?>">
	<em><?php echo $this->get_label('step').' '.$step_value[0];?></em>
	<span><?php echo $step_value[1];?></span>
	</li>		
<?php 
$ii++;
}?>
</ul>
<?php }?>





</div>
</div>

<div class="col-md-12 col-sm-12 col-xs-12 layout_div_style ad-operation new_box">

<div class="col-md-12 col-sm-12 col-xs-12 create-box">
<div class="col-md-12 col-sm-12 col-xs-12 box_div_advertiser">
<h2 class="paypal_head"><?php echo $this->get_label('ad content');?></h2>


<div class="row ad-create-edit" style="margin-top: 10px;">
<div class="col-sm-6 col-md-6 col-xs-12">
<div><?php echo $this->get_label('name');?> <span class="compulsory">*</span></div>
<div><input class="form-control" type="text" name="name" id="name" value="<?php echo $this->get_variable('name');?>" maxlength="25" /></div>
</div>
</div>


<div class="row ad-create-edit spec_tr_text">
<div class="col-sm-6 col-md-6 col-xs-12">

<div><?php echo $this->get_label('title');?> <span class="compulsory">*</span></div>
<div>
<input class="form-control" type="text" name="title" id="title" onkeyup="ShowDemo(1);" value="<?php echo $this->get_variable('title');?>" maxlength="<?php echo Configuration::get_instance()->read('max_ad_title_length');?>">
<div class="notification adtitle-span"><bdi>[<span class="desktopspan"><?php echo $this->get_label('max character',array('x'=>Configuration::get_instance()->read('max_ad_title_length')));?></span>]</bdi>

<i id="preview-show" onclick="ShowDemo(0);" style="cursor: pointer;float: right;font-size: 16px;" class="fa fa-laptop" title="<?php echo $this->get_label('ad demo');?>" alt="<?php echo $this->get_label('ad demo');?>"></i>

<div class="ad_demo_div spec_demo_text" style="display: none;right: 0px;">

<table style="width: 100%;">
<tr>
<td class="text-image-class" style="display: none;">
<div style="width: 75px;height: 75px;background-color: #CCCCCC;"></div>
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



<div class="row ad-create-edit spec_tr_text spec_tr_textonly">
<div class="col-sm-6 col-md-6 col-xs-12">
<div ><?php echo $this->get_label('description');?> <span class="compulsory">*</span></div>
<div >
<input class="form-control" type="text" name="desc" id="desc" onkeyup="ShowDemo(1);" value="<?php echo $this->get_variable('desc');?>" maxlength="<?php echo Configuration::get_instance()->read('max_ad_desc_length');?>">
<div class="notification"><bdi>[<span class="desktopspan"><?php echo $this->get_label('max character',array('x'=>Configuration::get_instance()->read('max_ad_desc_length')));?></span>]</bdi></div>
</div>
</div>
</div>





<?php if($affiliate_enabled ==1){?>
<div class="row ad-create-edit spec_tr_text affiliate-content" style="display: none;">
<div class="col-sm-6 col-md-6 col-xs-12">
<div ><?php echo $this->get_label('description');?> <span class="compulsory">*</span></div>
<div >
<textarea class="form-control" style="height: 120px !important;" rows="5" name="description" id="description"><?php echo $this->get_variable('desc');?></textarea>
</div></div></div>
<?php }?>




<div class="row ad-create-edit spec_tr_text spec_tr_textonly spec_tr_video_url">
<div class="col-sm-6 col-md-6 col-xs-12">
<div ><?php echo $this->get_label('display url');?> <span class="compulsory">*</span></div>
<div >
<input class="form-control" type="text" name="displayurl" id="displayurl" onkeyup="ShowDemo(1);" value="<?php echo $this->get_variable('displayurl');?>" maxlength="<?php echo Configuration::get_instance()->read('max_display_url_length');?>" placeholder="<?php echo $this->get_label('example url');?>" />
<div class="notification"><bdi>[<span class="desktopspan"><?php echo $this->get_label('max character',array('x'=>Configuration::get_instance()->read('max_display_url_length')));?></span>]</bdi></div>
</div></div></div>




<div class="row ad-create-edit spec_tr_both">
<div class="col-sm-6 col-md-6 col-xs-12">
<div ><?php echo $this->get_label('click url');?> <span class="compulsory">*</span></div>
<div >
<input class="form-control" type="text" name="clickurl" value="<?php echo $this->get_variable('clickurl');?>" placeholder="<?php echo $this->get_label('example url');?>" />
</div></div></div>









<div class="row ad-create-edit spec_tr_banner">
<div class="col-sm-6 col-md-6 col-xs-12">
<div ><?php echo $this->get_label('banner size');?></div>
<div >

<?php 
$vast_string='';
$res1=$this->get_result('res1');

if($banner_dimension_count >0){?>
<span class="banner-select" id="banner-select-00" style="display: none;">   
<select class="form-control" name="bannersize_00" id="bannersize_00" onchange="LoadMaxSize('00');">
<?php 

foreach($res1 as $key=>$result)
{
	$height=$result['height'];
	$width=$result['width'];
	$id=$result['id'];
	$dimension=$result['width']." x ".$result['height'];
	
	if($result['vast_video_support'] ==1)
	{
		if($vast_string !='')
		$vast_string.='-';		
		
		$vast_string.=$result['id'];
	}
	
?>
<option value="<?php echo $id?>" <?php if($this->get_variable("bannersize")==$id) { echo "selected"; }?>><?php echo $dimension ?></option>
<?php }?>
</select>
</span>
<?php }?>

<input type="hidden" name="vast_banner_id" id="vast_banner_id" value="<?php echo $vast_string;?>" />


<?php if($interstitial_enabled ==1){?>
<span class="banner-select" id="banner-select-01" style="display: none;">   
<select class="form-control" name="bannersize_01" id="bannersize_01" onchange="LoadMaxSize('01');">
<?php 
$res2=$this->get_result('res2');

foreach($res2 as $key=>$result)
{
	$height=$result['height'];
	$width=$result['width'];
	$id=$result['id'];
	$dimension=$result['width']." x ".$result['height'];
?>
<option value="<?php echo $id?>" <?php if($this->get_variable("bannersize")==$id) { echo "selected"; }?>><?php echo $dimension ?></option>
<?php }?>
</select>
</span>
<?php }?>

<?php if($textimage_enabled ==1){?>

<span class="banner-select" id="banner-select-05" style="display: none;">   
<select class="form-control" name="bannersize_05" id="bannersize_05" onchange="LoadMaxSize('05');">
<?php 
$res6=$this->get_result('res6');

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
</span>
<?php }?>


</div></div></div>


<?php if($skin_enabled ==1){?>

<div class="row ad-create-edit spec_tr_skin">
<div class="col-sm-6 col-md-6 col-xs-12">
<div ><?php echo $this->get_label('banner size');?></div>
<div >

<span class="banner-select" id="banner-select-14" style="display: none;">
<select class="form-control" name="bannersize_14" id="bannersize_14" onchange="LoadMaxSize('14');">
<?php
$res14_adblock=$this->get_result('res14_adblock');
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
<?php

}?>
</select>
<div> <?php echo $skin_preview; ?></div>
</span>


</div></div></div>


<div class="row ad-create-edit spec_tr_skin " style="display :none;">
<div class="col-sm-6 col-md-6 col-xs-12">

<div ><?php echo $this->get_label('banner');?><span class="compulsory">*</span></div>
<div>

<?php
$res14_banner=$this->get_result('res14_banner');
$iiii=0;
$ids= '';
foreach($res14_banner as $key=>$result)
{
	$height=$result['height'];
	$width=$result['width'];
	$id=$result['id'];

	if($ids !='')
	$ids .=',';
	
	$ids .=$id;
	?>
<span> <?php echo $width.' x '.$height;?>
<input class="form-control" type="file" name="skin_banner_<?php echo $id; ?>" size="10">
</span>

<?php if($iiii ==0){?>
<span class="notification"><bdi>[<?php echo $this->get_label('supported image format');?>]</bdi></span>
<?php }?>

<i class="notification " id="max-size-skin-<?php echo $result['id'];?>"><bdi>[<?php echo $this->get_label('max file size',array('x'=>$result['filesize']));?>]</bdi></i><br>
<?php 
$iiii++;
}?>

<input type ="hidden" name="existing_dimensions" value ="<?php echo $ids; ?>" >

</div></div></div>
<?php }?>



<?php if($ecommerce_enabled ==1){?>
<?php
	$support_string="";

	$size_checked=$this->get_variable('size_checked');
	$banner_select=$this->get_variable("bannersize");

	$res3=$this->get_result('res3');

	foreach($res3 as $key=>$result)
	{
		if($support_string !="")
		$support_string.='-';

		$support_string.=$result['id'].'_'.$result['filesize'];
	}
	
?>

<input type="hidden" name="bannersize_02" id="bannersize_02" value="<?php echo $banner_select;?>" />
<input type="hidden" name="supported_size" id="supported_size" value="<?php echo $support_string;?>" />
<input type="hidden" name="size_checked" id="size_checked" value="<?php echo $size_checked;?>" />




<div class="row ad-create-edit spec_tr_ecommerce">
<div class="col-sm-6 col-md-6 col-xs-12">
<div ><?php echo $this->get_label('banner size');?></div>
</div></div>



<?php
$res3=$this->get_result('res3');

foreach($res3 as $key=>$result)
{
	$height=$result['height'];
	$width=$result['width'];
	$id=$result['id'];
	$dimension=$result['width']." x ".$result['height'];
?>

<div class="row ad-create-edit spec_tr_ecommerce">
<div class="col-sm-6 col-md-6 col-xs-12">
<div >

<input class="spc-chk-box" type="checkbox" name="chk_diamension_<?php echo $id;?>" id="chk_diamension_<?php echo $id;?>" onclick="FillCheckBox(<?php echo $id;?>);" value="1" <?php if($this->get_variable('chk_diamension_'.$result['id']) ==1){?> checked="checked" <?php }?> /> <bdi><?php echo $dimension; ?></bdi> &nbsp;

<span class="display-layout-span" id="hidden-layout-<?php echo $result['id'];?>" style="display: none;">
<?php echo $this->get_display_layout_list($result['id'],intval($this->get_variable('layoutid_'.$result['id'])));?>
</span>


<span class="link_button show-span-button" id="show-span-button-<?php echo $result['id'];?>" style="display:none;white-space: nowrap;" onclick="ShowPreview(<?php echo $result['id'];?>);"><?php echo $this->get_label('preview');?></span>

<?php echo $this->get_max_ad_count($id);?>

</div></div></div>

<div class="row ad-create-edit spec_tr_ecommerce" style="margin-bottom: 0px;margin-top: 10px;display: none;">
<div class="col-sm-12 col-md-12 col-xs-12 hide-span layout-preview-banner-<?php echo $id;?>">
<?php echo $this->get_display_ad_preview($id); ?>
</div></div>

<?php }?>

<?php }?>





<div class="row ad-create-edit spec_tr_banner spec_tr_ecommerce">
<div class="col-sm-6 col-md-6 col-xs-12">
<span class="notification support-format" id="support-format0" ><bdi>[<?php echo $this->get_label('supported image format');?>]</bdi></span>

<?php 
$sizeflag=1;

$sizearray=array();
	?>

<?php if($banner_dimension_count >0){?>
<?php foreach($res1 as $key=>$result){

	$sizearray[$result['id']]=1;
	?>
<span class="notification maxsize" id="max-size-<?php echo $result['id'];?>" style="display: none;"><bdi>[<?php echo $this->get_label('max file size',array('x'=>$result['filesize']));?>]</bdi></span>
<?php }}?>


<?php if($interstitial_enabled ==1){?>

<?php foreach($res2 as $key=>$result){?>
<span class="notification maxsize" id="max-size-<?php echo $result['id'];?>" style="display: none;"><bdi>[<?php echo $this->get_label('max file size',array('x'=>$result['filesize']));?>]</bdi></span>
<?php }}?>


<?php if($ecommerce_enabled ==1){?>
<?php if(!isset($sizearray[$result['id']])){

	$sizearray[$result['id']]=1;
	?>

<?php foreach($res3 as $key=>$result){?>
<span class="notification maxsize" id="max-size-<?php echo $result['id'];?>" style="display: none;"><bdi>[<?php echo $this->get_label('max file size',array('x'=>$result['filesize']));?>]</bdi></span>


<?php }}}?>





<?php if($textimage_enabled ==1){?>
<?php foreach($res6 as $key=>$result){?>
<span class="notification maxsize" id="max-size-<?php echo $result['id'];?>" style="display: none;"><bdi>[<?php echo $this->get_label('max file size',array('x'=>$result['filesize']));?>]</bdi></span>
<?php }}?>


</div></div>



<?php 
if($affiliate_enabled ==1)
{
	$res1=$this->get_result('res1');
	$idata=0;
	foreach($res1 as $key=>$result)
	{?>
		<div class="row ad-create-edit affiliate-content">
		<div class="col-sm-6 col-md-6 col-xs-12">
		<div ><?php echo $this->get_label('banner').' - '.$result['width']." x ".$result['height'];?></div>
		<div >
		<input class="form-control" type="file" name="banner_<?php echo $result['id'];?>" size="10">
		
		<span class="notification"><bdi>[<?php echo $this->get_label('max file size',array('x'=>$result['filesize']));?>]</bdi>
		
		<?php if($idata ==0){?>
		<span class="notification"><bdi>[<?php echo $this->get_label('supported image format');?>]</bdi></span>
		<?php }?>
		
		
		</span>
		
		
		</div></div></div>
	<?php 
	$idata=$idata+1;
	}}?>






<?php if($ecommerce_enabled ==1){


	$htype=intval($this->get_variable('htype'));
	$hdata=$this->get_variable('hdata');
	$hlink=$this->get_variable('hlink');
	$callactiontext=$this->get_variable('callactiontext');


	?>
<div class="row ad-create-edit spec_tr_ecommerce" style="margin-bottom: 10px;font-size: 10px;display: none;">

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




<div class="row ad-create-edit spec_tr_ecommerce" style="display: none;">
<div class="col-sm-6 col-md-6 col-xs-12">
<div ><?php echo $this->get_label('upload logo');?> <span class="compulsory">*</span></div>
<div >

<input class="form-control" type="file" name="ecommercelogo" id="ecommercelogo" size="10" />

</div></div></div>



<div class="row ad-create-edit spec_tr_ecommerce" style="display: none;">
<div class="col-sm-6 col-md-6 col-xs-12">
<div ><?php echo $this->get_label('headline display type');?></div>
<div >

<input type="radio" name="htype" id="htext" value="0" <?php if($htype ==0){?>checked="checked"<?php }?> onclick="DynamicDemo();" />&nbsp;<?php echo $this->get_label('text');?>

&nbsp;

<input type="radio" name="htype" id="hbutton" value="1" <?php if($htype ==1){?>checked="checked"<?php }?> onclick="DynamicDemo();" />&nbsp;<?php echo $this->get_label('button');?>


</div></div></div>


<div class="row ad-create-edit spec_tr_ecommerce" style="display: none;">
<div class="col-sm-6 col-md-6 col-xs-12">
<div ><?php echo $this->get_label('headline text');?> <span class="compulsory">*</span></div>
<div >

<input class="form-control" type="text" name="hdata" id="hdata" onkeyup="DynamicDemo();" value="<?php echo $hdata;?>" maxlength="<?php echo Configuration::get_instance()->read('max_headline_length');?>">
<div class="notification"><bdi>[<span class="headlinespan"><?php echo $this->get_label('max character',array('x'=>Configuration::get_instance()->read('max_headline_length')));?></span>]</bdi></div>


</div></div></div>





<div class="row ad-create-edit spec_tr_ecommerce" style="display: none;">
<div class="col-sm-6 col-md-6 col-xs-12">
<div ><?php echo $this->get_label('headline url');?> <span class="compulsory">*</span></div>
<div >

<input class="form-control" type="text" name="hlink" id="hlink" value="<?php echo $hlink;?>" placeholder="<?php echo $this->get_label('example url');?>" />

</div></div></div>



<div class="row ad-create-edit spec_tr_ecommerce" style="display: none;">
<div class="col-sm-6 col-md-6 col-xs-12">
<div ><?php echo $this->get_label('call action text');?></div>
<div >

<input class="form-control" type="text" name="callactiontext" id="callactiontext" onkeyup="DynamicDemo();" value="<?php echo $callactiontext;?>" maxlength="<?php echo Configuration::get_instance()->read('max_call_action_length');?>">
<div class="notification"><bdi>[<span class="callactionspan"><?php echo $this->get_label('max character',array('x'=>Configuration::get_instance()->read('max_call_action_length')));?></span>]</bdi></div>

</div></div></div>









<input type="hidden" name="file_type" id="file_type1" value="1" />
<input type="hidden" name="file_type_hidden" id="file_type_hidden" value="1" />

<!-- 
<div class="row ad-create-edit spec_tr_ecommerce" style="display: none;">
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
<span style="float: right;"><?php echo $this->get_label('csv sample');?>&nbsp;&nbsp;<a id="csvdownload" href="<?php echo $this->make_url('dispatch/ecommerce-ads/1');?>"><i class="fa fa-file-text-o" title="<?php echo $this->get_label('csv sample');?>" alt="<?php echo $this->get_label('csv sample');?>"></i></a></span>
</div>
<div >
<input class="form-control" type="file" name="csvfile" size="10">
</div></div></div>
-->




<div class="row ad-create-edit spec_tr_ecommerce ecommerce_pop" style="display: none;">
<div class="col-sm-6 col-md-6 col-xs-12">
<div >

<span class="link_button" data-toggle="modal" data-target="#EcommerceManage" style="white-space: nowrap;"><?php echo $this->get_label('insert items');?></span>

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
<td ><?php echo $this->get_label('title').' ['.$this->get_label('max characters',array('x'=>$maxtitle)).']';?><span class="compulsory">*</span></td>
<td ><?php echo $this->get_label('description').' ['.$this->get_label('max characters',array('x'=>$maxdesc)).']';?><span class="compulsory">*</span></td>
<td ><?php echo $this->get_label('display url').' ['.$this->get_label('max characters',array('x'=>$maxdispurl)).']';?><span class="compulsory">*</span></td>
<td ><?php echo $this->get_label('landing page');?><span class="compulsory">*</span></td>



<?php if($retargeting_enabled ==1){?>
<td class="retarget-td-pop" style="display: none;"><?php echo $this->get_label('retargeting url');?></td>
<?php }?>



<td ><?php echo $this->get_label('image path');?><span class="compulsory">*</span>

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





<div class="clearfix"></div>
<div class="row ad-create-edit spec_tr_banner">
<div class="col-sm-6 col-md-6 col-xs-12">
<div ><?php echo $this->get_label('banner');?> <span class="compulsory">*</span></div>
<div >
<input class="form-control" type="file" name="banner" size="10">
</div></div></div>



<?php if($expandable_enabled ==1){?>

<?php
$arraybanner=array();

if($banner_dimension_count >0)
{
	foreach($res1 as $key=>$result)
	{
		if($result['expandable_support'] ==1 && $result['expandable_width'] >0 && $result['expandable_height'] >0)
		$arraybanner[$result['id']]=$result;
	}
}

$dataid=0;
foreach($arraybanner as $key=>$result)
{
?>

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

<?php if($banner_dimension_count >0){?>
<?php foreach($res1 as $key123=>$result123){?>
<span class="notification maxsize-exp" id="max-size-exp-<?php echo $result123['id'];?>" style="display: none;"><bdi>[<?php echo $this->get_label('max file size',array('x'=>$result123['filesize']));?>]</bdi></span>
<?php }}?>

</div>
<?php }?>

</div></div></div>


<div class="row ad-create-edit spec_tr_expandable_exp" id="exp-div2-<?php echo $result['id'];?>">
<div class="col-sm-6 col-md-6 col-xs-12">
<div ><?php echo $this->get_label('expandable banner');?> <span class="compulsory">*</span></div>
<div >
<input class="form-control" type="file" name="expandable_banner_<?php echo $result['id'];?>" size="10" />
</div></div></div>

<?php

$dataid=$dataid+1;

}}?>


<div class="row ad-create-edit spec_tr_video" style="display: none;">
<?php if($video_enabled ==1 && ($linear_support ==1 || $html5_player_support ==1)){?>
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
<?php }?>
</div>




<?php if($pop_enabled ==1){

	$pop_ads_support=Configuration::get_instance()->read('pop_ads_support');
	
	$pop_array=explode('-',$pop_ads_support);
	
	$pop_type=intval($this->get_variable('pop_type'));
	
	
	$pop_up=intval($pop_array[0]);
	$pop_under=intval($pop_array[1]);
	$pop_tab=intval($pop_array[2]);
	$pop_window_width=$this->get_variable('pop_window_width');
	
	$pop_window_height=$this->get_variable('pop_window_height');
	
	if($pop_type ==0 && $pop_up ==1)
	$pop_type=1;
	else if($pop_type ==0 && $pop_under ==1)
	$pop_type=2;
	else if($pop_type ==0 && $pop_tab ==1)
	$pop_type=3;	
	
	
/*  $pop_type=1; POP UP Only
 *  $pop_type=2; POP UNDER Only
 *  $pop_type=3; POP TAB Only
 *  $pop_type=4; POP UP & POP UNDER 
 *  $pop_type=5; POP UNDER & POP TAB 
 *  $pop_type=6; POP UP & POP TAB 
 *  $pop_type=7; POP UP & POP UNDER & POP TAB 
 */	
	?>
<div class="row ad-create-edit popdiv" style="display: none;">


<div class="col-sm-6 col-md-6 col-xs-12">

<div><?php echo $this->get_label('pop window width');?></div>
<div >
<input type="text" class="form-control" name="pop_window_width" id="pop_window_width" value="<?php echo $pop_window_width;?>" size="5" onkeyup="this.value=this.value.replace(/[^0-9]/g, '');">

</div>

<div><?php echo $this->get_label('pop window height');?></div>
<div ><input type="text" class="form-control" name="pop_window_height" id="pop_window_height" value="<?php echo $pop_window_height;?>" size="5" onkeyup="this.value=this.value.replace(/[^0-9]/g, '');">

</div>
<div style="margin-top: 10px;"><?php echo $this->get_label('pop type');?> <span class="compulsory">*</span></div>
<div>

<?php if($pop_up ==1){ ?>
<span><input style="width: 20px !important;" type="checkbox" name="pop_up" id="pop_up" value="1" <?php if($pop_type ==1 || $pop_type ==4 || $pop_type ==6 || $pop_type ==7){?> checked="checked" <?php }?> /> <?php echo $this->get_label('popup');?></span>
<?php }?>

<?php if($pop_under ==1){ ?>
<span><input style="width: 20px !important;" type="checkbox" name="pop_under" id="pop_under" value="1" <?php if($pop_type ==2 || $pop_type ==4 || $pop_type ==5 || $pop_type ==7){?> checked="checked" <?php }?> /> <?php echo $this->get_label('popunder');?></span>
<?php }?>

<?php if($pop_tab ==1){ ?>
<span><input style="width: 20px !important;" type="checkbox" name="pop_tab" id="pop_tab" value="1" <?php if($pop_type ==3 || $pop_type ==5 || $pop_type ==6 || $pop_type ==7){?> checked="checked" <?php }?> /> <?php echo $this->get_label('poptab');?></span>
<?php }?>
</div>
</div>
</div>
<?php }?>







<div class="row ad-create-edit" >
<div class="col-sm-6 col-md-6 col-xs-12">
<?php if($retargeting_enabled ==1){?>
<div class="col-sm-6 col-md-6 col-xs-12 retarget-div padding-side" style="display: none;">
<div ><?php echo $this->get_label('allow retargeting');?></div>
<div >
<input style="width: 20px !important;" type="checkbox" name="retargeting" id="retargeting" value="1" <?php if($retargeting ==1){?>checked="checked"<?php }?> />
</div></div>
<?php }?>



<?php if($video_enabled ==1 && $nonlinear_support ==1){?>
<div class="col-sm-6 col-md-6 col-xs-12 vast-div padding-side" style="display: none;">
<div ><?php echo $this->get_label('allow in vast player');?></div>
<div >
<input style="width: 20px !important;" type="checkbox" name="vast_support" id="vast_support" value="1" <?php if($vast_support ==1){?>checked="checked"<?php }?> />
</div></div>
<?php }?>

</div>
</div>
</div></div>



<div class="col-md-12 col-sm-12 col-xs-12" style="text-align: center;">
<div class="submit-box-outer">
<input type="hidden" name="type1" id="type1" value="1" />
<input class="create_ad_btn" type="submit" name="submit" value="<?php echo $this->get_label('next');?>" />
</div></div>



</div>
<?php $form->end(); ?>

</div>
<div style="height: 20px;"></div>

<?php $this->dispatch("layout/footer");?>

<script type="text/javascript">
function LoadStepBox()
{
	if($('.step-box-normal').length >0)
	{
		step_length=$('.step-box-normal').length;

		outer_width=$('.stepdiv').outerWidth();

		total_width=outer_width-(step_length*3);  
		
		$('.step-box-normal').css("width",(total_width/step_length)+'px');
	}


	<?php if($sponsored ==1){ ?>
	
	if($('.step-box-cpd').length >0)
	{
		step_length=$('.step-box-cpd').length;

		outer_width=$('.stepdiv').outerWidth();

		total_width=outer_width-(step_length*3)-15;  
		
		$('.step-box-cpd').css("width",(total_width/step_length)+'px');
	}
	<?php }?>

	<?php if($affiliate_enabled ==1){?>
	if($('.step-box-aff').length >0)
	{
		step_length=$('.step-box-aff').length;

		outer_width=$('.stepdiv').outerWidth();

		total_width=outer_width-(step_length*3)-15;  
		
		$('.step-box-aff').css("width",(total_width/step_length)+'px');
	}
	<?php }?>
}


$(document).ready(function() {
changeadtype();
LoadStepBox();

<?php if($ecommerce_enabled ==1){?>
DynamicDemo();
<?php }?>


if($('#type').val() ==2)
LoadMaxSize('00');
else if($('#type').val() ==5)
LoadMaxSize('01');
else if($('#type').val() ==11)
LoadMaxSize('05');
else if($('#type').val() ==14)
LoadMaxSize('14');


$(window).resize(function()
{
	LoadStepBox();
});

});
</script>