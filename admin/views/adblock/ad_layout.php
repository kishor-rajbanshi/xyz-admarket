<?php 
$this->dispatch("layout/header/4/_49");

$text_ads_enabled   = $this->get_variable('text_ads_enabled');
$textimage_enabled  = $this->get_variable('textimage_enabled');
$native_enabled     = $this->get_variable('native_enabled');


$res1               = $this->get_result('res1');
$res2               = $this->get_result('res2');
$resFontExternal    = $this->get_result("resFontExternal");
?>
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/bootstrap-3.0.3.min.js'></script>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo COMMON_DIR_PATH;?>css/bootstrap-3.0.3.min.css" />

<style type="text/css">
<?php foreach($resFontExternal as $fkey=>$fvalue){?>
@font-face {
  font-family: <?php echo $fvalue['slug_name'];?>;
  src: url(<?php echo BASE.DATA_DIR.'/fonts/'.$fvalue['file_name'];?>);
}
<?php } ?>   
.modal-open {overflow: auto;}

.modal-dialog {width:1100px !important;}

h2 {margin-top: 0px;}

*, *::before, *::after {box-sizing: unset !important;}

.ad-layout-preview {margin: 0px auto;}

</style>

<div class="sub_menu_main"><?php echo $this->get_label('text ad layout');?></div>

<table  style="width:100%;" cellpadding="0" cellspacing="0">
<tr><td>

<div id="layout-delete-message" style="display: none;"></div>

<input class="get_popup_btn" style="position: absolute;right: 5px;top: 10px;" type="button" address-target="0" data-toggle="modal" data-target="#NewLayout" value="<?php echo $this->get_label('create layout');?>" />

<div class="modal fade" id="NewLayout" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
    	<div class="modal-content login-modal">
      		<div class="modal-header login-modal-header">
        	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
        	<h4 id="head-create" class="modal-title"><?php echo $this->get_label('create new layout');?></h4>
        	<h4 id="head-edit" style="display: none;" class="modal-title"><?php echo $this->get_label('edit layout');?></h4>
      		</div>
      		
      		<div class="modal-body" style="padding: 0px 15px 10px;">
      		
      		<div id="create-message" style="font-size: 15px;display: none;text-transform: none;"></div>
							  	
			<div class="tab-content">
			<div role="tabpanel" class="tab-pane active" style="text-align: left;font-size: 14px;">
							
<table  style="width:100%;margin-top: 10px;" cellpadding="0" cellspacing="0">		
<tr>
<td style="height: 80px;vertical-align: top;" ><?php echo $this->get_label('layout name');?><span class="compulsory">*</span>
<div>
<input type="text" id="name" name="name" class="login-pop" style="width: 150px;height: 28px;" value="" />
</div>
</td>



<td style="vertical-align: top;">
<?php echo $this->get_label('layout type');?>
<div class="layout-div-content layout-div-select" style="display: none;">
<select class="class-drop-font" name="layout_type" id="layout_type" style="width: 108px;" onchange="SetCTAPosition(0);">
    <option value="1"><?php echo $this->get_label('normal ad');?></option> 
    <?php if($native_enabled == 1 || $native_enabled == 0){?>   
    <option value="2"><?php echo $this->get_label('native ad');?></option> 
    <?php } ?>
</select>
</div>

<div class="layout-div-content layout-div-normal" style="display: none;padding-top: 5px;"><?php echo $this->get_label("normal ad");?>
</div>

<div class="layout-div-content layout-div-native" style="display: none;padding-top: 5px;"><?php echo $this->get_label("native ad");?>
</div>
</td>

<td style="vertical-align: top;">
<?php echo $this->get_label('select theme');?><span class="compulsory">*</span>
<div>
<select class="class-drop-font" name="theme_setting" id="theme_setting" style="width: 108px;" onchange="AdLayoutPreview();">
<?php foreach($res1 as $key1 => $value1){?>
<option value="<?php echo $value1['id'];?>"><?php echo $value1['theme'];?></option>
<?php }?>
</select>
</div>
</td>
<td style="vertical-align: top;">

<?php echo $this->get_label('select font');?><span class="compulsory">*</span>
<div>
<select class="class-drop-font" name="font_setting" id="font_setting" style="width: 108px;" onchange="AdLayoutPreview();">
<?php foreach($res2 as $key2 => $value2){?>
<option value="<?php echo $value2['id'];?>"><?php echo $value2['font_name'];?></option>
<?php }?>
</select>
</div>
</td>


<td colspan="2" style="vertical-align: top;">

<?php echo $this->get_label('elements supported');?><span class="compulsory">*</span>

<div style="width: 200px;">
<div>
<div style="float: left;width: 100px;">
<input type="checkbox" name="title" id="title" value="1" onclick="SetCTAPosition(0);"/> <?php echo $this->get_label('ad title');?>
</div>

<div style="float: left;width: 100px;">
<input type="checkbox" name="description" id="description" value="1" onclick="SetCTAPosition(0);" /> <?php echo $this->get_label('description');?>
</div>
</div>

<div>
<div style="float: left;width: 100px;">
<input type="checkbox" name="displayurl" id="displayurl" value="1" onclick="SetCTAPosition(0);" /> <?php echo $this->get_label('displayurl');?>
</div>

<div style="float: left;width: 100px;">
<input type="checkbox" name="cta_button" id="cta_button" value="1" onclick="SetCTAPosition(0);" /> <?php echo $this->get_label('cta button');?>
</div>
</div>
</div>
</td> 
</tr>					
<tr >
<td class="cta-position" style="vertical-align: top;">
<?php echo $this->get_label('cta button position');?><span class="compulsory">*</span>
<div class="cta-position-div cta-position-div-below"><input type="radio" name="cta_position" id="cta_position_2" value="2" class="cta_position_checkbox" onclick="SetCTAPosition(1);" /> <?php echo $this->get_label('below the ad layout');?></div>
<div class="cta-position-div cta-position-div-next" ><input type="radio" name="cta_position" id="cta_position_1" value="1" class="cta_position_checkbox" onclick="SetCTAPosition(1);" /> <?php echo $this->get_label('next to the ad layout');?></div>
<div class="cta-position-div cta-position-div-title"><input type="radio" name="cta_position" id="cta_position_3" value="3" class="cta_position_checkbox" onclick="SetCTAPosition(1);" /> <?php echo $this->get_label('next to the title');?></div>
<div class="cta-position-div cta-position-div-description"><input type="radio" name="cta_position" id="cta_position_4" value="4" class="cta_position_checkbox" onclick="SetCTAPosition(1);" /> <?php echo $this->get_label('next to the description');?></div>
<div class="cta-position-div cta-position-div-displayurl"><input type="radio" name="cta_position" id="cta_position_5" value="5" class="cta_position_checkbox" onclick="SetCTAPosition(1);" /> <?php echo $this->get_label('next to the display url');?></div>
<div class="cta-position-div cta-position-div-description-displayurl" style="width:200px;"><input type="radio" name="cta_position" id="cta_position_6" value="6" class="cta_position_checkbox" onclick="SetCTAPosition(1);" /> <?php echo $this->get_label('next to the description & display url');?></div>
</td>



<td class="cta-section-size" style="vertical-align: top;">
<span class="cta-section-width"><bdi><?php echo $this->get_label('cta section width');?></bdi></span>
<span class="cta-section-height"><bdi><?php echo $this->get_label('cta section height');?></bdi></span>

<span class="compulsory">*</span>
<div>
<input style="width: 80px;height: 30px;" type="number" min="30" name="cta_section_size" id="cta_section_size" value="80" onkeyup="this.value=this.value.replace(/[^0-9]/g, '');" onchange="AdLayoutPreview();" />
</div>
</td>

<td class="cta-section-size" style="vertical-align: top;">
<?php echo $this->get_label('cta border radius');?> (<?php echo $this->get_label('px');?>)
<div>
<select name="cta_border_radius" id="cta_border_radius" style="width: 108px;" onchange="AdLayoutPreview();">
<?php for($j = 0; $j <= 50; $j++){?>
<option value="<?php echo $j; ?>"><?php echo $j; ?></option>
<?php } ?>
</select>
</td>


<td class="cta-section-size" style="vertical-align: top;display: none;">
<?php echo $this->get_label('cta padding horizontal');?> (<?php echo $this->get_label('px');?>)
<div>
<select name="cta_padding_horizontal" id="cta_padding_horizontal" style="width: 108px;" onchange="AdLayoutPreview();">
<?php for($j = 1; $j <= 25; $j++){?>
<option value="<?php echo $j; ?>"><?php echo $j; ?></option>
<?php } ?>
</select>
</td>

<td colspan="2" class="cta-section-size" style="vertical-align: top;display: none;">
<?php echo $this->get_label('cta padding vertical');?> (<?php echo $this->get_label('px');?>)
<div>
<select name="cta_padding_vertical" id="cta_padding_vertical" style="width: 108px;" onchange="AdLayoutPreview();">
<?php for($j = 1; $j <= 25; $j++){?>
<option value="<?php echo $j; ?>"><?php echo $j; ?></option>
<?php } ?>
</select>
</td>
</tr>

<tr class="cta-position"><td colspan="5" style="height: 10px;"></td></tr>
<tr >

<td class="minimum_size_section" style="display:none;">
<?php echo $this->get_label('minimum layout width');?> (<?php echo $this->get_label('px');?>)
<span class="compulsory">*</span>
<div>
<input style="width: 80px;height: 30px;" type="number" min="80" name="minimum_width" id="minimum_width" onkeyup="this.value=this.value.replace(/[^0-9]/g, '');" onchange="AdLayoutPreview();" />
</div>
</td>

<td class="minimum_size_section" style="display:none;">
<?php echo $this->get_label('minimum layout height');?> (<?php echo $this->get_label('px');?>)
<span class="compulsory">*</span>
<div>
<input style="width: 80px;height: 30px;" type="number" min="80" name="minimum_height" id="minimum_height" onkeyup="this.value=this.value.replace(/[^0-9]/g, '');" onchange="AdLayoutPreview();" />
</div>
</td>



<td class="maximum_size_section" style="display:none;">
<?php echo $this->get_label('maximum layout width');?> (<?php echo $this->get_label('px');?>)
<span class="compulsory">*</span>
<div>
<input style="width: 80px;height: 30px;" type="number" min="80" name="maximum_width" id="maximum_width" onkeyup="this.value=this.value.replace(/[^0-9]/g, '');" onchange="AdLayoutPreview();" />
</div>
</td>

<td class="maximum_size_section" style="display:none;">
<?php echo $this->get_label('maximum layout height');?> (<?php echo $this->get_label('px');?>)
<span class="compulsory">*</span>
<div>
<input style="width: 80px;height: 30px;" type="number" min="80" name="maximum_height" id="maximum_height" onkeyup="this.value=this.value.replace(/[^0-9]/g, '');" onchange="AdLayoutPreview();" />
</div>
</td>



<td class="ad_content_slide" style="display:none;">
<?php echo $this->get_label('ad content slide');?>
<div>
<select name="content_slide" id="content_slide" style="width: 108px;" onchange="SetCTAPosition(1);">
<option value="0"><?php echo $this->get_label('no');?></option>
<option value="1"><?php echo $this->get_label('yes');?></option>
</select>
</div>
</td>

<td class="slide-section" style="display: none;">
<?php echo $this->get_label('slide direction');?>
<div>
<select name="slide_direction" id="slide_direction" style="width: 108px;" onchange="SetCTAPosition(1);">
<option value="0"><?php echo $this->get_label('vertical');?></option>
<option value="1"><?php echo $this->get_label('horizontal');?></option>
</select>
</div>
</td>

<td class="slide-section" style="display: none;">
<?php echo $this->get_label('slide duration');?> (<?php echo $this->get_label('sec');?>)
<div>
<select name="slide_duration" id="slide_duration" style="width: 108px;" onchange="SetCTAPosition(1);">
<?php for($j = 1; $j <= 60; $j++){?>
<option value="<?php echo $j; ?>"><?php echo $j; ?></option>
<?php } ?>
</select>
</td>

<td class="title-border" style="display: none;">

<?php echo $this->get_label('title border bottom');?>
<div>
<select name="title_border_bottom" id="title_border_bottom" style="width: 108px;" onchange="SetCTAPosition(1);">
<option value="0"><?php echo $this->get_label('no');?></option>
<option value="1"><?php echo $this->get_label('yes');?></option>
</select>
</div>
</td>   

<td class="description-border" style="display: none;">

<?php echo $this->get_label('description border bottom');?>
<div>
<select name="description_border_bottom" id="description_border_bottom" style="width: 108px;" onchange="SetCTAPosition(1);">
<option value="0"><?php echo $this->get_label('no');?></option>
<option value="1"><?php echo $this->get_label('yes');?></option>
</select>
</div>
</td>


<td class="displayurl-border" style="display: none;">
<?php echo $this->get_label('displayurl border bottom');?>
<div>
<select name="displayurl_border_bottom" id="displayurl_border_bottom" style="width: 108px;" onchange="SetCTAPosition(1);">
<option value="0"><?php echo $this->get_label('no');?></option>
<option value="1"><?php echo $this->get_label('yes');?></option>
</select>
</div>
</td>
</tr>


<tr>
<td colspan="5" style="text-align: center;height: 80px;">
<input type="hidden" name="id" id="id" value="0" />

<input id="add-layout" type="button" name="button1" value="<?php echo $this->get_label('submit');?>" onclick="AddLayout();" />
<input id="edit-layout" style="display: none;" type="button" name="button11" value="<?php echo $this->get_label('submit');?>" onclick="EditLayout();" />
</td>
</tr>
</table>
<div style="text-align: center;margin-bottom: 10px;"><img id="loading" src="images/load.gif" style="display: none;" /></div>
<div class="previewSection" style="margin-top: 0px;"></div>

		  	
					</div>
				</div>
	      	</div>
    	</div>
	 </div>
</div>


</td>
</tr>
</table>

<div id="layout-list"></div>

<script type="text/javascript">
function AdLayoutPreview()
{
    titleEnabled               = 0;
    descriptionEnabled         = 0;
    urlEnabled                 = 0;
    buttonEnabled              = 0;
    buttonSectionWidth         = 0;
    buttonSectionHeight        = 0; 
    titleBorder                = 0;
    descriptionBorder          = 0;
    displayurlBorder           = 0;  
    contentSlide               = 0;
    slideDirection             = 0;
    slideDuration              = 0;
    CTAPosition                = 0;
    CTABorderRadius            = 0;
    CTAPaddingHorizontal       = 0;
    CTAPaddingVertical         = 0;
   
    adblockFont                = $("#font_setting").val();
    adblockTheme               = $("#theme_setting").val();

    if($('#title').prop("checked"))
    {
        titleEnabled           = 1; 

        if($('#title_border_bottom').val() == 1)
        titleBorder            = 1;   
    }

    if($('#description').prop("checked"))
    {
        descriptionEnabled     = 1;

        if($('#description_border_bottom').val() == 1)
        descriptionBorder      = 1; 
    }

    if($('#displayurl').prop("checked"))
    {
        urlEnabled             = 1;

        if($('#displayurl_border_bottom').val() == 1)
        displayurlBorder       = 1;  
    }

    if($('#cta_button').prop("checked"))
    buttonEnabled              = 1;    

    if(titleEnabled == 0 && descriptionEnabled == 0 && urlEnabled == 0)
    {
        $('.previewSection').html("");
        return;
    }


    if(buttonEnabled == 1)
    {
        CTAPosition = $("input[name='cta_position']:checked").val();

        if(CTAPosition == 2)
        buttonSectionHeight     = $('#cta_section_size').val();
        else 
        buttonSectionWidth      = $('#cta_section_size').val();
        
        CTABorderRadius         = $('#cta_border_radius').val();
        CTAPaddingHorizontal    = $('#cta_padding_horizontal').val();
        CTAPaddingVertical      = $('#cta_padding_vertical').val();
    }

    if(CTAPosition == 0 || CTAPosition == 1 || CTAPosition == 2)
    {
        contentSlide            = $('#content_slide').val();
        slideDirection          = $('#slide_direction').val()  //slide_direction  0 vertical/ 1 horizontal
        slideDuration           = $('#slide_duration').val();
    }


    if($('#layout_type').val() == 2)
    {
        adblockWidth  = $('#minimum_width').val();
        adblockHeight = $('#minimum_height').val();
    }
    else
    {
        if($('#maximum_width').val() > 0)
        adblockWidth  = $('#maximum_width').val();
        else 
        adblockWidth  = 300;

        if($('#maximum_height').val() > 0)
        adblockHeight = $('#maximum_height').val();
        else 
        adblockHeight = 250;
    }

    $("#loading").show();

    dataparam    = "adblockFont="+adblockFont+"&adblockTheme="+adblockTheme+"&titleEnabled="+titleEnabled+"&descriptionEnabled="+descriptionEnabled+"&urlEnabled="+urlEnabled+"&buttonEnabled="+buttonEnabled+"&buttonSectionWidth="+buttonSectionWidth+"&buttonSectionHeight="+buttonSectionHeight+"&titleBorder="+titleBorder+"&descriptionBorder="+descriptionBorder+
        "&displayurlBorder="+displayurlBorder+"&contentSlide="+contentSlide+"&slideDirection="+slideDirection+"&slideDuration="+slideDuration+"&CTAPosition="+CTAPosition+"&CTABorderRadius="+CTABorderRadius+"&CTAPaddingHorizontal="+CTAPaddingHorizontal+"&CTAPaddingVertical="+CTAPaddingVertical+"&adblockWidth="+adblockWidth+"&adblockHeight="+adblockHeight;

    var urlvalue = '<?php echo $this->make_url("adblock/load_layout_preview");?>';

    $.ajax(
    {
        type: "POST",
        data: dataparam,
        url: urlvalue,
        success: function(message)
        {                       
            $("#loading").hide();
            
            $('.previewSection').html(message);
        }
    });
}


var slideInterval;

function startContentSlide(contentWidth,contentHeight,CTAPosition,sectionCount)
{
    increment = 1;

    if(slideInterval)
    clearInterval(slideInterval);    


    if(sectionCount == 1 || $('#content_slide').val() == 0 || ($('#cta_button').prop("checked") &&CTAPosition != 1 && CTAPosition != 2))
    return;

    slideDirection = $('#slide_direction').val();
    slideDuration  = $('#slide_duration').val();

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

function SetCTAPosition(type)
{
    $('.ad-layout-preview').show();
    
    var ii = 0;

    if($('#title').prop("checked"))
    ii++;

    if($('#description').prop("checked"))
    ii++;  

    if($('#displayurl').prop("checked"))
    ii++; 


    $('.cta-position-div').hide();  
    $('.cta-section-width').hide();    
    $('.cta-section-height').hide();    
    
    if($('#cta_button').prop("checked") && ($('#title').prop("checked") || $('#description').prop("checked") || $('#displayurl').prop("checked")))
    {
        if(type == 0)
        $('#cta_position_2').prop('checked',true);

        $('.cta-position').show();
        $('.cta-section-size').show();

        $('.cta-position-div-next').show();
        $('.cta-position-div-below').show();
    }
    else
    {
        $('.cta_position_checkbox').prop('checked',false);

        $('.cta-position').hide();
        $('.cta-section-size').hide();
    }


    if(ii > 1 && (!$('#cta_button').prop("checked") || ($('#cta_button').prop("checked") && ($("input[name='cta_position']:checked").val() == 1 || $("input[name='cta_position']:checked").val() == 2))))
    {
        if($('#layout_type').val() == 1)
        {
            $('.ad_content_slide').show(); 

            if($('#content_slide').val() == 1)
            $('.slide-section').show(); 
            else 
            $('.slide-section').hide(); 
        }
        else 
        {
            $('.ad_content_slide').hide(); 
            $('#content_slide').val(0);
            $('.slide-section').hide(); 
        }
    }
    else 
    {
        $('.ad_content_slide').hide(); 
        $('#content_slide').val(0);
        $('.slide-section').hide(); 
    } 

    if($('#layout_type').val() == 1)
    {
        $('.minimum_size_section').hide(); 
        $('.maximum_size_section').show(); 
    }
    else if($('#layout_type').val() == 2)
    {
        $('.minimum_size_section').show();     
        $('.maximum_size_section').hide(); 
    }
    

    if($('#title').prop("checked") && $('#content_slide').val() == 0 && ($('#description').prop("checked") || $('#displayurl').prop("checked") || ($('#cta_button').prop("checked") && $('#cta_position_2').prop('checked'))))
    $('.title-border').show();  
    else 
    {
        $('.title-border').hide();   
        $('#title_border_bottom').val(0);        
    }


    if($('#description').prop("checked") && $('#content_slide').val() == 0 && ($('#displayurl').prop("checked") || ($('#cta_button').prop("checked") && $('#cta_position_2').prop('checked'))))
    $('.description-border').show();  
    else 
    {
        $('.description-border').hide();  
        $('#description_border_bottom').val(0);        
    }


    if($('#displayurl').prop("checked") && $('#content_slide').val() == 0 && $('#cta_button').prop("checked") && $('#cta_position_2').prop('checked'))
    $('.displayurl-border').show();  
    else 
    {
        $('.displayurl-border').hide(); 
        $('#displayurl_border_bottom').val(0);        
    }


 
    if($('#title').prop("checked") && $('#cta_button').prop("checked") && ($('#description').prop("checked") || $('#displayurl').prop("checked")))
    $('.cta-position-div-title').show();  
    else
    $('.cta-position-div-title').hide();  


    if($('#description').prop("checked") && $('#cta_button').prop("checked") && ($('#title').prop("checked") || $('#displayurl').prop("checked")))
    {
        $('.cta-position-div-description').show();

        if($('#displayurl').prop("checked"))
        $('.cta-position-div-description-displayurl').show();  
        else 
        $('.cta-position-div-description-displayurl').hide();  
    }
    else 
    $('.cta-position-div-description').hide();


    if($('#displayurl').prop("checked") && $('#cta_button').prop("checked") && ($('#title').prop("checked") || $('#description').prop("checked")))
    {
        $('.cta-position-div-displayurl').show();

        if($('#description').prop("checked"))
        $('.cta-position-div-description-displayurl').show();  
        else 
        $('.cta-position-div-description-displayurl').hide();  
    }
    else 
    $('.cta-position-div-displayurl').hide();
    


    if($('#cta_position_2').prop('checked'))
    $('.cta-section-height').show();  
    else
    $('.cta-section-width').show();  


    AdLayoutPreview();
}

function AddLayout()
{
    title                      = 0;   
    description                = 0; 
    displayurl                 = 0;     
    cta_button                 = 0;    
    cta_position               = 0;
    minimum_width              = 0;
    minimum_height             = 0;
    maximum_width              = 0;
    maximum_height             = 0;


	name                       = $("#name").val();	
    layout_type                = $("#layout_type").val();       
	theme_setting              = $("#theme_setting").val(); 
	font_setting               = $("#font_setting").val(); 

    if(layout_type == 1)
    {
        maximum_width          = $("#maximum_width").val(); 
        maximum_height         = $("#maximum_height").val();      
    }
    else if(layout_type == 2)
    {
        minimum_width          = $("#minimum_width").val(); 
        minimum_height         = $("#minimum_height").val();      
    }



    if($('#title').prop("checked")) 
    title                      = 1;    

    if($('#description').prop("checked")) 
    description                = 1; 

    if($('#displayurl').prop("checked")) 
    displayurl                 = 1; 

    if($('#cta_button').prop("checked")) 
    {
        cta_button             = 1;
        cta_position           = $("input[name='cta_position']:checked").val();     
    }     	


	cta_section_size           = $("#cta_section_size").val(); 
	cta_border_radius          = $("#cta_border_radius").val(); 
	cta_padding_horizontal     = $("#cta_padding_horizontal").val(); 
	cta_padding_vertical       = $("#cta_padding_vertical").val();
	content_slide              = $("#content_slide").val(); 
	slide_direction            = $("#slide_direction").val(); 
	slide_duration             = $("#slide_duration").val(); 
	title_border_bottom        = $("#title_border_bottom").val(); 
	description_border_bottom  = $("#description_border_bottom").val(); 
	displayurl_border_bottom   = $("#displayurl_border_bottom").val(); 

    if(title == 0 && description == 0 && displayurl == 0)
    {
        $('#create-message').html('<?php echo $this->get_message('mandatory');?>');
        $('#create-message').css('color','red');
        $('#create-message').show();
                
        setTimeout(function(){
            $('#create-message').slideUp();
        },1500);
                    
        return;
    }
	


	$("#loading").show();
	
	dataparam="name="+name+"&layout_type="+layout_type+"&layout_minimum_width="+minimum_width+"&layout_minimum_height="+minimum_height+"&layout_maximum_width="+maximum_width+"&layout_maximum_height="+maximum_height+"&theme_setting="+theme_setting+"&font_setting="+font_setting+"&title="+title+
		"&description="+description+"&displayurl="+displayurl+"&cta_button="+cta_button+"&cta_position="+cta_position+
		"&cta_section_size="+cta_section_size+"&cta_border_radius="+cta_border_radius+"&cta_padding_horizontal="+cta_padding_horizontal+"&cta_padding_vertical="+cta_padding_vertical+
		"&content_slide="+content_slide+"&slide_direction="+slide_direction+"&slide_duration="+slide_duration+"&title_border_bottom="+title_border_bottom+
		"&description_border_bottom="+description_border_bottom+"&displayurl_border_bottom="+displayurl_border_bottom;

	var urlvalue='<?php echo $this->make_url("adblock/create_ad_layout");?>';

	$.ajax(
	{
			type: "POST",
			data: dataparam,
			url: urlvalue,
			success: function(msg)
			{
				message = "";
				
				if(msg == 1)
				message="<?php echo $this->get_message('layout name already exists');?>";
                else if(msg == 3)
                message="<?php echo $this->get_message('mandatory');?>";
				else if(msg == 2)
				{
					message="<?php echo $this->get_message('layout create success');?>";

					$("#name").val("");    
                    $("#minimum_width").val("300");      
                    $("#minimum_height").val("250");
                    $("#maximum_width").val("300");      
                    $("#maximum_height").val("250");
					$("#theme_setting").val(1); 
					$("#font_setting").val(1); 
                    $('#title').prop("checked",false);
                    $('#description').prop("checked",false);
                    $('#displayurl').prop("checked",false);
                    $('#cta_button').prop("checked",false);
					$("#cta_border_radius").val(0); 
					$("#cta_padding_horizontal").val(1); 
					$("#cta_padding_vertical").val(1); 
					$("#content_slide").val(0); 
					$("#slide_direction").val(0); 
                    $("#slide_duration").val(1); 
					$("#title_border_bottom").val(0); 
					$("#description_border_bottom").val(0); 
					$("#displayurl_border_bottom").val(0); 
				}
				
					
				$('#create-message').html(message);
	
				if(msg != 2)
				$('#create-message').css('color','red');
				else
				$('#create-message').css('color','green');	

				$('#create-message').show();
				
				
				$("#loading").hide();

				if(msg == 2)
				{
                    SetCTAPosition(0);
					LoadLayout();

					setTimeout(function(){
						$('#create-message').slideUp();
					},1500);
					
				}
			}
		});

}

function LoadLayout()
{
    if($('#layoutType').length > 0)
    layoutType = $('#layoutType').val();
    else 
    layoutType = -1;

	dataparam  = "layoutType="+layoutType;
	var urlvalue='<?php echo $this->make_url("adblock/load_ad_layout");?>';
	$.ajax(
	{
		type: "POST",
		data: dataparam,
		url: urlvalue,
		success: function(msg)
		{
		    $("#layout-list").html(msg);
		}
	});
}


function DeleteLayout(id)
{
	if(confirm("<?php echo $this->get_message('do you really want to delete this layout');?>"))
	{
		$("#load"+id).show();

		dataparam="id="+id;

		var urlvalue='<?php echo $this->make_url("adblock/delete_ad_layout");?>';

		$.ajax(
		{
			type: "POST",
			data: dataparam,
			url: urlvalue,
			success: function(msg)
			{
                $("#load"+id).hide();

				message="";

				if(msg ==1)
				message="<?php echo $this->get_message('invalid');?>";
                else if(msg ==3)
                message="<?php echo $this->get_message('adblocks already using this ad layout');?>";
                else if(msg ==4)
                message="<?php echo $this->get_message('layout delete failed');?>"; 
                else if(msg ==5)
                message="<?php echo $this->get_message('native adcode already using this ad layout');?>";     
				else if(msg ==2)
				{
					message="<?php echo $this->get_message('layout delete success');?>";
					LoadLayout();
				}                

				$('#layout-delete-message').html(message);
	
				if(msg == 2)
                $('#layout-delete-message').css('color','green');   
                else
				$('#layout-delete-message').css('color','red');

				$('#layout-delete-message').show();
				
				setTimeout(function(){
					$('#layout-delete-message').slideUp();
				},1500);
			}
		});
	}
}


function EditLayout()
{
    title                      = 0;   
    description                = 0; 
    displayurl                 = 0;    
    cta_button                 = 0;     
    cta_position               = 0;
    minimum_width              = 0; 
    minimum_height             = 0; 
    maximum_width              = 0; 
    maximum_height             = 0; 


    id                         = $("#id").val(); 
    name                       = $("#name").val();  
    layout_type                = $("#layout_type").val();   
    theme_setting              = $("#theme_setting").val(); 
    font_setting               = $("#font_setting").val(); 

    if(layout_type == 1)
    {
        maximum_width          = $("#maximum_width").val(); 
        maximum_height         = $("#maximum_height").val();      
    }
    else if(layout_type == 2)
    {
        minimum_width          = $("#minimum_width").val(); 
        minimum_height         = $("#minimum_height").val();      
    }

    

    if($('#title').prop("checked")) 
    title                      = 1;    

    if($('#description').prop("checked")) 
    description                = 1; 

    if($('#displayurl').prop("checked")) 
    displayurl                 = 1; 

    if($('#cta_button').prop("checked")) 
    {
        cta_button             = 1;
        cta_position           = $("input[name='cta_position']:checked").val();     
    }    

    cta_section_size           = $("#cta_section_size").val(); 
    cta_border_radius          = $("#cta_border_radius").val(); 
    cta_padding_horizontal     = $("#cta_padding_horizontal").val(); 
    cta_padding_vertical       = $("#cta_padding_vertical").val();
    content_slide              = $("#content_slide").val(); 
    slide_direction            = $("#slide_direction").val(); 
    slide_duration             = $("#slide_duration").val(); 
    title_border_bottom        = $("#title_border_bottom").val(); 
    description_border_bottom  = $("#description_border_bottom").val(); 
    displayurl_border_bottom   = $("#displayurl_border_bottom").val(); 

    if(title == 0 && description == 0 && displayurl == 0)
    {
        $('#create-message').html('<?php echo $this->get_message('mandatory');?>');
        $('#create-message').css('color','red');
        $('#create-message').show();
                
        setTimeout(function(){
            $('#create-message').slideUp();
        },1500);
                    
        return;
    }

	$("#loading").show();

    dataparam = "id="+id+"&name="+name+"&layout_type="+layout_type+"&layout_minimum_width="+minimum_width+"&layout_minimum_height="+minimum_height+"&layout_maximum_width="+maximum_width+"&layout_maximum_height="+maximum_height+"&theme_setting="+theme_setting+"&font_setting="+font_setting+"&title="+title+
        "&description="+description+"&displayurl="+displayurl+"&cta_button="+cta_button+"&cta_position="+cta_position+
        "&cta_section_size="+cta_section_size+"&cta_border_radius="+cta_border_radius+"&cta_padding_horizontal="+cta_padding_horizontal+"&cta_padding_vertical="+cta_padding_vertical+
        "&content_slide="+content_slide+"&slide_direction="+slide_direction+"&slide_duration="+slide_duration+"&title_border_bottom="+title_border_bottom+
        "&description_border_bottom="+description_border_bottom+"&displayurl_border_bottom="+displayurl_border_bottom;


	var urlvalue='<?php echo $this->make_url("adblock/edit_ad_layout");?>';

	$.ajax(
	{
		type: "POST",
		data: dataparam,
		url: urlvalue,
		success: function(msg)
		{
			message="";


            if(msg == 1)
            message="<?php echo $this->get_message('layout name already exists');?>";
            else if(msg == 3)
            message="<?php echo $this->get_message('mandatory');?>";
            else if(msg == 4)
            message="<?php echo $this->get_message('invalid');?>";        
            else if(msg == 2)
            {
                message="<?php echo $this->get_message('layout edit success');?>";

				$("#name_temp"+id).val(name);
                $("#layout_type_temp"+id).val(layout_type);
                $("#minimum_width_temp"+id).val(minimum_width);
                $("#minimum_height_temp"+id).val(minimum_height);
                $("#maximum_width_temp"+id).val(maximum_width);
                $("#maximum_height_temp"+id).val(maximum_height);
				$("#theme_setting_temp"+id).val(theme_setting); 
				$("#font_setting_temp"+id).val(font_setting); 
				$("#title_temp"+id).val(title); 
				$("#description_temp"+id).val(description); 
				$("#displayurl_temp"+id).val(displayurl); 
				$("#cta_button_temp"+id).val(cta_button); 
				$("#cta_position_temp"+id).val(cta_position); 
				$("#cta_section_size_temp"+id).val(cta_section_size); 
				$("#cta_border_radius_temp"+id).val(cta_border_radius); 
				$("#cta_padding_horizontal_temp"+id).val(cta_padding_horizontal); 
				$("#cta_padding_vertical_temp"+id).val(cta_padding_vertical); 
				$("#content_slide_temp"+id).val(content_slide); 
				$("#slide_direction_temp"+id).val(slide_direction); 
				$("#slide_duration_temp"+id).val(slide_duration); 
				$("#title_border_bottom_temp"+id).val(title_border_bottom); 
				$("#description_border_bottom_temp"+id).val(description_border_bottom); 
				$("#displayurl_border_bottom_temp"+id).val(displayurl_border_bottom); 					
			}

			$('#create-message').html(message);
	
			if(msg != 2)
			$('#create-message').css('color','red');
			else
			$('#create-message').css('color','green');	

			$('#create-message').show();
			
			$("#loading").hide();

			if(msg == 2)
			{
				setTimeout(function(){
					$('#create-message').slideUp();
				},1500);
			}
		}
	});
}



$(document).ready(function() {

    SetCTAPosition(0);

	LoadLayout();

	$('#NewLayout').on('show.bs.modal', function (event) {

		id = $(event.relatedTarget).attr('address-target');


        $('.layout-div-content').hide();







		if(id > 0)
		{
			$('#head-create').hide();
			$('#head-edit').show();

			$('#add-layout').hide();
			$('#edit-layout').show();


			$("#name").val($("#name_temp"+id).val());
            $("#layout_type").val($("#layout_type_temp"+id).val());
            $("#minimum_width").val($("#minimum_width_temp"+id).val());
            $("#minimum_height").val($("#minimum_height_temp"+id).val());  
            $("#maximum_width").val($("#maximum_width_temp"+id).val());
            $("#maximum_height").val($("#maximum_height_temp"+id).val());  

			$("#theme_setting").val($("#theme_setting_temp"+id).val()); 
			$("#font_setting").val($("#font_setting_temp"+id).val()); 
				

            if($("#layout_type_temp"+id).val() == 1)
            $('.layout-div-normal').show();
            else if($("#layout_type_temp"+id).val() == 2)
            $('.layout-div-native').show();


            if($("#title_temp"+id).val() == 1)
            $('#title').prop("checked",true);
            else 
            $('#title').prop("checked",false);

            if($("#description_temp"+id).val() == 1)
            $('#description').prop("checked",true);
            else 
            $('#description').prop("checked",false);

            if($("#displayurl_temp"+id).val() == 1)
            $('#displayurl').prop("checked",true);
            else 
            $('#displayurl').prop("checked",false);

            if($("#cta_button_temp"+id).val() == 1)
            $('#cta_button').prop("checked",true);
            else 
            $('#cta_button').prop("checked",false);


            if($("#cta_position_temp"+id).val() > 0)
            {
                ctaPosition = $("#cta_position_temp"+id).val();

                $('.cta_position_checkbox').prop('checked',false);

                $('#cta_position_'+ctaPosition).prop('checked',true);
            }

				
			$("#cta_section_size").val($("#cta_section_size_temp"+id).val()); 
			$("#cta_border_radius").val($("#cta_border_radius_temp"+id).val()); 
			$("#cta_padding_horizontal").val($("#cta_padding_horizontal_temp"+id).val()); 
			$("#cta_padding_vertical").val($("#cta_padding_vertical_temp"+id).val()); 
			$("#content_slide").val($("#content_slide_temp"+id).val()); 
			$("#slide_direction").val($("#slide_direction_temp"+id).val()); 
			$("#slide_duration").val($("#slide_duration_temp"+id).val()); 
			$("#title_border_bottom").val($("#title_border_bottom_temp"+id).val()); 	
			$("#description_border_bottom").val($("#description_border_bottom_temp"+id).val()); 
			$("#displayurl_border_bottom").val($("#displayurl_border_bottom_temp"+id).val()); 

            SetCTAPosition(1);
		}
		else
		{
			id = 0;

			$('#head-create').show();

			if($('#head-edit').length >0)
			$('#head-edit').hide();

			$('#add-layout').show();

			if($('#edit-layout').length >0)
			$('#edit-layout').hide();

            $('.layout-div-select').show();


            $("#name").val("");
            $("#layout_type").val(1);
            $("#minimum_width").val("300");
            $("#minimum_height").val("250");
            $("#maximum_width").val("300");
            $("#maximum_height").val("250");
            $("#theme_setting").val(1); 
            $("#font_setting").val(1); 
            $('#title').prop("checked",false);
            $('#description').prop("checked",false);
            $('#displayurl').prop("checked",false);
            $('#cta_button').prop("checked",false);
            $("#cta_border_radius").val(0); 
            $("#cta_padding_horizontal").val(1); 
            $("#cta_padding_vertical").val(1); 
            $("#content_slide").val(0); 
            $("#slide_direction").val(0); 
            $("#slide_duration").val(1); 
            $("#title_border_bottom").val(0); 
            $("#description_border_bottom").val(0); 
            $("#displayurl_border_bottom").val(0); 


            SetCTAPosition(0);
		}


		$('#create-message').html("");
		$('#create-message').hide();
		
		$('#id').val(id);		
	});
	
});
</script>
<?php $this->dispatch("layout/footer");?>	