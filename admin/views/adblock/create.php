<?php 
$this->dispatch("layout/header/4/_42");

$interstitial_enabled=$this->get_addon_status('interstitial_enabled');
$textimage_enabled=$this->get_addon_status('text-image-ads_enabled');
$video_enabled=$this->get_addon_status('video-ads_enabled');
$text_ads_enabled=Configuration::get_instance()->read('text-ads_enabled');
$skin_enabled=$this->get_addon_status('skin-ads_enabled');

$credittext=intval($this->get_variable('credittext'));

$credittype=$this->get_credit_type($credittext);

?>
<script type="text/javascript" src="<?php echo COMMON_DIR_PATH;?>farbtastic/farbtastic.js"></script>
<link rel="stylesheet" href="<?php echo COMMON_DIR_PATH;?>farbtastic/farbtastic.css" type="text/css" />
<script type="text/javascript" charset="utf-8">
$(document).ready(function() {
	if($('.color-picker').length >0)
	{
	    var f = $.farbtastic('#picker');
	    var p = $('#picker').css('opacity', 0.25);
	    var selected;

	    $('.color-picker').each(function () { f.linkTo(this); $(this).css('opacity', 0.75); })
	      .focus(function() 
	      {
	    	if(selected) {$(selected).css('opacity', 0.75);}
		      
	        f.linkTo(this);
	        $(this).css('opacity',1);
	        p.css('opacity', 1);
	        $(selected = this).css('opacity', 1);
	      });
	 }
});
</script>

<script  type="text/javascript" >

function LoadAspectRatio()
{
		adtype=$('#adtype').val();

		$('#aspect_ratio_tr').hide();

		if(adtype ==5)
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


function ChangeSettings()
{
	var type=$('#adtype').val();
	var bannertype=0;
	
	if(type ==2)
	{
		<?php if($interstitial_enabled ==1 || $skin_enabled ==1){?>
		bannertype=$('#banner_type').val();
		<?php }else{?>
		var bannertype=0;
		<?php }?>
	}
	else if(type ==4)
	var bannertype=3;

	
	$('#banner_type').val(bannertype);
		
	$('.banner-select').hide();
	
	<?php if($interstitial_enabled ==1 || $skin_enabled ==1){?>
	$('.inter-row').hide();
	<?php }?>

	$('.tbdv').hide();
	$('#textdv').hide();
	$('#tidv').hide();
	$('.text-settings').hide();
	$('#bannerdv').hide();
	$("#skindv").hide();
	$(".creditdv").hide();
	$(".credit-row").show();


	if($("#aspect_ratio_tr").length >0)
	$("#aspect_ratio_tr").hide();	


	$('.credit-alignment').show();
	$('.credit-alignment-blank').hide();


	$(".data_table").hide();	
	
	
	
	if(type==1)
	{
		$('.tbdv').show();
		$('#textdv').show();
		$('.text-settings').show();
		$(".creditdv").show();
		
	}
	if(type==2)
	{   
		$('#bannerdv').show();
	
		<?php if($interstitial_enabled ==1 || $skin_enabled ==1){?>
		$('.inter-row').show();
		<?php }?>
	}
	if(type==3)
	{
		$('.tbdv').show();
		$('.text-settings').show();
		
		$('#bannerdv').show();
		$(".creditdv").show();
	}
	if(type==4)
	{
		$('.tbdv').show();
		$('#textdv').show();
		$('.text-settings').show();
		$('#bannerdv').show();
		$('#tidv').show();
		$(".creditdv").show();
		
	}

	if(type==5)
	{
		$('#textdv').show();
		$(".creditdv").show();
		
		$('.credit-alignment').hide();
		$('.credit-alignment-blank').show();

		$(".credit-row").hide();

		LoadAspectRatio();
	}


	
   if(bannertype ==4)  //For Skin Ads
   {
	   $("#skindv").show();
	   $("#bannerdv").hide();
   }
   else
   {
	   $(".data_table").show();	
	   $(".creditdv").show();
   }

	if(type==2 || type==3 || type==4)
	{
		if(bannertype !=4)
		$('#banner-select-0'+bannertype).show();	
	}


	if(type !=2 && type !=4 && type !=5)
	$(".adblock_details_sub").css("width","25%");
	else if(type ==2)	
	$(".adblock_details_sub").css("width","15%");
	else if(type ==4 || type ==5)	
	$(".adblock_details_sub").css("width","20%");	


	if(type ==2 && bannertype ==4)
	$("#skindv").css("width","25%");

	if(type ==4)
	{
		$(".width-adjust").css("width","10%");	
		$(".width-adjust select").css("width","90px");
	}
	else
	{
		$(".width-adjust").css("width","12.5%");	
		$(".width-adjust select").css("width","100px");
	}
	

	if(type ==1 || type ==3 || type ==4)
	$("#lseperator").css("width","138px");	
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
}


	<?php if($credittype ==0){?>
	$('.for-text-credit').show();
	$('.for-text-credit-blank').hide();
	<?php }else if($credittype ==1){?>
	$('.for-text-credit').hide();
	$('.for-text-credit-blank').show();
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

 
  <div class="adblock_details_sub" style="width:20%;"> 
  <?php echo $this->get_label('adblock name');?> <span class="compulsory">*</span><br/>
  <input type="text" name="adbname" id="adbname" value="<?php echo $this->get_variable('adbname');?>" style="width: 90%;height: 23px;"/>
  </div>
  
  <div class="adblock_details_sub"> 
  
    <?php echo $this->get_label('adtype');?>
    <br/>
    <select name="adtype" id="adtype" onchange="ChangeSettings();" style="width: 125px;">
    
    <?php if($text_ads_enabled ==1){?>
    <option value="1" <?php if($this->get_variable("adtype")==1) {echo "selected";}?>><?php echo $this->get_label('text only');?></option>
    <?php }?>
    
    <option value="2" <?php if($this->get_variable("adtype")==2) {echo "selected";}?>><?php echo $this->get_label('banner only');?></option>
    
    <?php if($text_ads_enabled ==1){?>
    <option value="3" <?php if($this->get_variable("adtype")==3) {echo "selected";}?>><?php echo $this->get_label('textbanner');?></option>
    <?php }?>
    
    <?php if($textimage_enabled ==1){?>
    <option value="4" <?php if($this->get_variable("adtype")==4) {echo "selected";}?>><?php echo $this->get_label('textimage adblock');?></option>
    <?php }?>
    
    <?php if($video_enabled ==1){?>
    <option value="5" <?php if($this->get_variable("adtype")==5) {echo "selected";}?>><?php echo $this->get_label('video only');?></option>
    <?php }?>    
    
    </select>   
   </div>
  
  
   <div class="adblock_details_sub" id="textdv" style="width:20%;display: none;">
  
   <span style="float: left;margin-right: 10px;">
   <?php echo $this->get_label('width');?> (<?php echo $this->get_label('px');?>)<span class="compulsory">*</span>
   <br/>
   <input type="text" name="width" id="width" value="<?php echo $this->get_variable('width');?>" size="5" style="height: 23px;width: 55px;" onkeyup="this.value=this.value.replace(/[^0-9\.]/g, '');LoadAspectRatio();"/>
   </span>
   
   <span style="float: left;margin-right: 10px;margin-top: 25px;">x</span>
  
   <span style="float: left;"> 
   <?php echo $this->get_label('height');?> (<?php echo $this->get_label('px');?>)<span class="compulsory">*</span>
   </br>
   <input type="text" name="height" id="height" value="<?php echo $this->get_variable('height');?>" size="5" style="height: 23px;width: 55px;" onkeyup="this.value=this.value.replace(/[^0-9\.]/g, '');LoadAspectRatio();" /> 
   </span>
   
   
   <div id="aspect_ratio_tr" style="display: none;">
   <?php echo $this->get_label('supported aspect ratio adblock');?> : <span id="aspect_ratio_span"></span>
   </div>   
   
   </div>  
  

   
   <?php if($interstitial_enabled ==1 || $skin_enabled ==1){?>
    
   <div class="adblock_details_sub inter-row" style="display: none;"> 
   
    <?php echo $this->get_label('banner type');?>
	</br>
    <select name="banner_type" id="banner_type" onchange="ChangeSettings();" style="width: 125px;">
    
    <option value="0" <?php if($this->get_variable("banner_type")==0) {echo "selected";}?>><?php echo $this->get_label('normal ads');?></option>
    
    <?php if($interstitial_enabled ==1){?>
    <option value="1" <?php if($this->get_variable("banner_type")==1) {echo "selected";}?>><?php echo $this->get_label('interstitial ads');?></option>
    <?php }?>
    
    <?php if($skin_enabled ==1){?>
    <option value="4" <?php if($this->get_variable("banner_type")==4) {echo "selected";}?>><?php echo $this->get_label('skin ads');?></option>
    <?php }?>
       
    </select>
    </div>
     
     
   <?php }else{?>
  <span><input type="hidden" name="banner_type" id="banner_type" value="0" /></span>
  <?php }?>
  	
    
    
 
   <div class="adblock_details_sub" id="bannerdv">
  
   <?php echo $this->get_label('banner size');?>
   </br>
   
	<span class="banner-select" id="banner-select-00" style="display: none;">   
	<select name="bannersize_00" id="bannersize_00" style="width: 110px;">
	<?php 
	$res1=$this->get_result('res1');
	
	foreach($res1 as $key=>$result)
	{
		$height=$result['height'];
		$width=$result['width'];
		$id=$result['id'];
		$diamensions=$result['width']." x ".$result['height'];
	?>
	<option value="<?php echo $id;?>" <?php if($this->get_variable("bannersize")==$id) { echo "selected"; }?>><?php echo $diamensions ?></option>
	<?php }?>
	</select>
	</span>


	<span class="banner-select" id="banner-select-01" style="display: none;">   
	<select name="bannersize_01" id="bannersize_01" style="width: 110px;">
	<?php 
	$res2=$this->get_result('res2');
	
	foreach($res2 as $key=>$result)
	{
		$height=$result['height'];
		$width=$result['width'];
		$id=$result['id'];
		$diamensions=$result['width']." x ".$result['height'];
	?>
	<option value="<?php echo $id;?>" <?php if($this->get_variable("bannersize")==$id) { echo "selected"; }?>><?php echo $diamensions ?></option>
	<?php }?>
	</select>
	</span>



	
	<span class="banner-select" id="banner-select-03" style="display: none;">   
	<select name="bannersize_03" id="bannersize_03" style="width: 125px;">
	<?php 
	$res4=$this->get_result('res4');
	
	foreach($res4 as $key=>$result)
	{
		$height=$result['height'];
		$width=$result['width'];
		$id=$result['id'];
		$diamensions=$result['width']." x ".$result['height'];
	?>
	<option value="<?php echo $id;?>" <?php if($this->get_variable("bannersize")==$id) { echo "selected"; }?>><?php echo $diamensions ?></option>
	<?php }?>
	</select>
	</span>
	
  </div>
     
    
    <div class="adblock_details_sub" id="skindv" style="display: none;"> 
    <?php echo $this->get_label('skin positions');?>
	</br> 
	
    <input type="checkbox" name="skin_position_l" id="skin_position_l" value="1" onclick="check_skin('l');"><?php echo $this->get_label('left');?>
    <input type="checkbox" name="skin_position_r" id="skin_position_r" value="1" onclick="check_skin('r');"><?php echo $this->get_label('right');?>
    <input type="checkbox" name="skin_position_t" id="skin_position_t" value="1" onclick="check_skin('t');"><?php echo $this->get_label('top');?>
    <input type="checkbox" name="skin_position_b" id="skin_position_b" value="1" onclick="check_skin('b');"><?php echo $this->get_label('bottom');?>
    <input type="hidden" name="skin_position" id="skin_position" value="" />

    </div>    
    

	<div class="adblock_details_sub creditdv" style="display: none;">
    <?php echo $this->get_label('credit text');?>
	</br>
    <?php echo $this->get_credit_list($credittext);?>
    <script type="text/javascript">

    function CreditLoadData(id,type)
    {
        $(".select-div-li").html($("#list-li-"+id).html()+'<i class="fa fa-caret-down"></i>');
        $("#credittext").val(id);

        if(type ==1)
        {
			$('.for-text-credit').hide();
			$('.for-text-credit-blank').show();
        }
        else
        {
       		$('.for-text-credit').show();
       		$('.for-text-credit-blank').hide();
        }
    }
    </script>
    </div>


   <div class="adblock_details_sub tbdv width-adjust">
   <?php echo $this->get_label('no of text ads');?>
   </br>
   <select name="no_of_text_ads" size="1" id="no_of_text_ads" style="width: 100px;">
   <?php for($i=1;$i<=10;$i++){?>
   <option value="<?php echo $i;?>"><?php echo $i;?></option>
   <?php }?>
   </select>
   </div> 
  
  
   <div class="adblock_details_sub tbdv width-adjust">
   <?php echo $this->get_label('ad orientation');?>
   </br>
   <select name="ad_orientation" size="1" id="ad_orientation" style="width: 100px;">
   <option value="1"><?php echo $this->get_label('vertical');?></option>
   <option value="2"><?php echo $this->get_label('horizontal');?></option>
   </select>
   </div>
    






    <div class="adblock_details_sub"> 
    <?php echo $this->get_label('allow for publishers');?>
	<br/>
    <select name="allow" id="allow" style="width: 125px;">
    <option value="1"><?php echo $this->get_label('yes');?></option>
    <option value="2"><?php echo $this->get_label('no');?></option>
    </select> 
    </div>


  
  
  

  
  <div class="adblock_details_sub" id="tidv">
  <?php echo $this->get_label('image position');?>
  </br>
  <select name="image_position" id="image_position" size="1" style="width: 125px;">
  <option value="0" selected="selected" ><?php echo $this->get_label('left');?></option>
  <option value="1" ><?php echo $this->get_label('top');?></option>
  <option value="2" ><?php echo $this->get_label('right');?></option>
  <option value="3" ><?php echo $this->get_label('bottom');?></option>
  </select>
  </div>
    
    

  
   <div class="adblock_details_sub tbdv">
   <?php echo $this->get_label('border type');?>
   </br>
   <select name="border" id="border" style="width: 125px;">
   <option value="1"><?php echo $this->get_label('regular');?></option>
   <option value="0"><?php echo $this->get_label('rounded');?></option>
   </select>
   </div>
 
   <div class="adblock_details_sub tbdv">
   <?php echo $this->get_label('line seperator');?>
   </br>
   <select name="lseperator" id="lseperator" style="width: 125px;">
   <option value="0"><?php echo $this->get_label('no');?></option>
   <option value="1"><?php echo $this->get_label('yes');?></option>
   </select>
   </div>
</td>
</tr>



</table>
</div>




<table class="data_table" cellpadding="0" cellspacing="0">
<tr class="row_heading_tr">
<td class="border-right">&nbsp;</td>
<td ><?php echo $this->get_label('credit text');?></td>
<td class="text-settings"><?php echo $this->get_label('ad title');?></td>
<td class="text-settings"><?php echo $this->get_label('description');?></td>
<td class="text-settings"><?php echo $this->get_label('displayurl');?></td>
<td ><?php echo $this->get_label('border');?></td>
<td ><?php echo $this->get_label('background');?></td>
<td ></td>
</tr>

   
   
   
   
<tr class="row_data_tr">
<td class="border-right"><?php echo $this->get_label('font');?></td>
<td >
<span class="for-text-credit">
 <select name="credit_text_font" size="1" id="credit_text_font" style="width: 100px;">
					<option value="Arial, Helvetica, sans-serif" >Arial</option>
					<option value="Courier New, Courier, monospace">Courier New</option>
					<option value="Verdana, Arial, Helvetica, sans-serif">Verdana</option>
					<option value="Times New Roman, Times, serif">Times New Roman</option>
					<option value="Georgia, Times New Roman, Times, serif">Georgia</option>
					</select>
</span>
<div class="for-text-credit-blank">-</div>

</td>
<td class="text-settings">
<select name="title_font" size="1" id="title_font" style="width: 100px;">
						<option value="Arial, Helvetica, sans-serif" >Arial</option>
						<option value="Courier New, Courier, monospace">Courier New</option>
						<option value="Verdana, Arial, Helvetica, sans-serif">Verdana</option>
						<option value="Times New Roman, Times, serif">Times New Roman</option>
						<option value="Georgia, Times New Roman, Times, serif">Georgia</option>
					  </select>
</td>
<td class="text-settings">
 <select name="desc_font" size="1" id="desc_font" style="width: 100px;">
					  <option value="Arial, Helvetica, sans-serif" >Arial</option>
						<option value="Courier New, Courier, monospace">Courier New</option>
						<option value="Verdana, Arial, Helvetica, sans-serif">Verdana</option>
						<option value="Times New Roman, Times, serif">Times New Roman</option>
						<option value="Georgia, Times New Roman, Times, serif">Georgia</option>
					</select>
</td>
<td class="text-settings">
<select name="display_font" size="1" id="display_font" style="width: 100px;">
										<option value="Arial, Helvetica, sans-serif" selected="selected">Arial</option>
										<option value="Courier New, Courier, monospace">Courier New</option>
										<option value="Verdana, Arial, Helvetica, sans-serif">Verdana</option>
										<option value="Times New Roman, Times, serif">Times New Roman</option>

										<option value="Georgia, Times New Roman, Times, serif">Georgia</option>
										</select>
</td>
<td >-</td>
<td >-</td>
<td rowspan="8" style="border-left: 1px solid #CCCCCC;width: 150px;">

<div id="picker" style="float: left;"></div>

</td>
</tr>

   
<tr class="row_data_tr">
<td class="border-right"><?php echo $this->get_label('font size');?> (<?php echo $this->get_label('px');?>)</td>
<td >
<span class="for-text-credit">
 <select name="credit_size" id="credit_size" style="width: 100px;">
   <?php for($i=4;$i<=33;$i++){?>
  <option value="<?php echo $i;?>" <?php if($i ==12){?>selected="selected"<?php }?>><?php echo $i;?></option>
  <?php }?>
 </select>
</span>
<div class="for-text-credit-blank">-</div>

</td>
<td class="text-settings">
<select name="ad_title_size" id="ad_title_size" style="width: 100px;">
  <?php for($i=4;$i<=33;$i++){?>
  <option value="<?php echo $i;?>" <?php if($i ==14){?>selected="selected"<?php }?>><?php echo $i;?></option>
  <?php }?>
</select>
</td>
<td class="text-settings">
<select name="desc_size" id="desc_size" style="width: 100px;">
 <?php for($i=4;$i<=33;$i++){?>
 <option value="<?php echo $i;?>" <?php if($i ==14){?>selected="selected"<?php }?>><?php echo $i;?></option>
 <?php }?>
</select> 
</td>
<td class="text-settings">
<select name="disp_url_size" id="disp_url_size" style="width: 100px;">
   <?php for($i=4;$i<=33;$i++){?>
  <option value="<?php echo $i;?>" <?php if($i ==14){?>selected="selected"<?php }?>><?php echo $i;?></option>
  <?php }?>
</select> 
</td>
<td >-</td>
<td >-</td>
</tr>

   
<tr class="row_data_tr">
<td class="border-right"><?php echo $this->get_label('font weight');?></td>
<td >
<span class="for-text-credit">
<select name="credit_text_font_weight" size="1" id="credit_text_font_weight" style="width: 100px;">
					<option value="1"><?php echo $this->get_label('normal');?></option>
					<option value="2"><?php echo $this->get_label('bold');?></option>
					</select>
</span>
<div class="for-text-credit-blank">-</div>

</td>
<td class="text-settings">
<select name="ad_title_font_weight" size="1" id="ad_title_font_weight" style="width: 100px;">
					  <option value="1"><?php echo $this->get_label('normal');?></option>
					  <option value="2"><?php echo $this->get_label('bold');?></option>
								</select>
</td>
<td class="text-settings">
 <select name="ad_desc_font_weight" size="1" id="ad_desc_font_weight" style="width: 100px;">
					   <option value="1"><?php echo $this->get_label('normal');?></option>
					  <option value="2"><?php echo $this->get_label('bold');?></option>
					</select>
</td>
<td class="text-settings">
<select name="ad_disp_url_font_weight" size="1" id="ad_disp_url_font_weight" style="width: 100px;">
										<option value="1"><?php echo $this->get_label('normal');?></option>
										<option value="2"><?php echo $this->get_label('bold');?></option>
										</select>
</td>
<td >-</td>
<td >-</td>
</tr>

   
<tr class="row_data_tr">
<td class="border-right"><?php echo $this->get_label('decoration');?></td>
<td >
<span class="for-text-credit">
<select name="credit_text_decoration" size="1" id="credit_text_decoration" style="width: 100px;">
					<option value="1" selected="selected"><?php echo $this->get_label('none');?></option>
					<option value="2"><?php echo $this->get_label('underline');?></option>
					</select>
</span>
<div class="for-text-credit-blank">-</div>

</td>
<td class="text-settings">
 <select name="ad_title_decoration" size="1" id="ad_title_decoration" style="width: 100px;">
					  <option value="1"><?php echo $this->get_label('none');?></option>
					  <option value="2"><?php echo $this->get_label('underline');?></option>
											  </select>
</td>
<td class="text-settings">
 <select name="ad_desc_decoration" size="1" id="ad_desc_decoration" style="width: 100px;">
					  <option value="1"><?php echo $this->get_label('none');?></option>
					  <option value="2"><?php echo $this->get_label('underline');?></option>
 					  </select>
</td>
<td class="text-settings">
<select name="ad_disp_url_decoration" size="1" id="ad_disp_url_decoration" style="width: 100px;">
										<option value="1" selected="selected"><?php echo $this->get_label('none');?></option>
										<option value="2"><?php echo $this->get_label('underline');?></option>
										</select>
</td>
<td >-</td>
<td >-</td>
</tr>

   
<tr class="row_data_tr">
<td class="border-right"><?php echo $this->get_label('line height');?> (<?php echo $this->get_label('px');?>)</td>
<td >
<span class="for-text-credit">
<input type="text" name="cheight" id="cheight" value="15" style="width: 90px;" onkeyup="this.value=this.value.replace(/[^0-9]/g, '');" />
</span>
<div class="for-text-credit-blank">-</div>

</td>
<td class="text-settings">
<input type="text" name="theight" id="theight" value="15" style="width: 90px;" onkeyup="this.value=this.value.replace(/[^0-9]/g, '');" />
</td>
<td class="text-settings">
<input type="text" name="dheight" id="dheight" value="15" style="width: 90px;" onkeyup="this.value=this.value.replace(/[^0-9]/g, '');"/>
</td>
<td class="text-settings">
<input type="text" name="uheight" id="uheight" value="15" style="width: 90px;" onkeyup="this.value=this.value.replace(/[^0-9]/g, '');"/>
</td>
<td >-</td>
<td >-</td>
</tr>

   
<tr class="row_data_tr credit-row">
<td class="border-right"><?php echo $this->get_label('alignment');?></td>
<td >
<span class="credit-alignment">
<select name="credit_text_alignment" size="1" id="credit_text_alignment" style="width: 100px;">
					<option value="0" selected="selected"><?php echo $this->get_label('left');?></option>
					<option value="1"><?php echo $this->get_label('right');?></option>
					</select>
</span>
<div class="credit-alignment-blank">-</div>
</td>
<td class="text-settings">-</td>
<td class="text-settings">-</td>
<td class="text-settings">-</td>
<td >-</td>
<td >-</td>
</tr>

   
<tr class="row_data_tr credit-row">
<td class="border-right"><?php echo $this->get_label('position');?></td>
<td >
<span class="credit-alignment">
<select name="credit_text_positioning" size="1" id="credit_text_positioning" style="width: 100px;">
					<option value="1"><?php echo $this->get_label('top');?></option>
					<option value="0" selected="selected"><?php echo $this->get_label('bottom');?></option>
					</select>
</span>
<div class="credit-alignment-blank">-</div>
</td>
<td class="text-settings">-</td>
<td class="text-settings">-</td>
<td class="text-settings">-</td>
<td >-</td>
<td >-</td>
</tr>


   
<tr class="row_data_tr">
<td class="border-right"><?php echo $this->get_label('color');?></td>
<td >
<input type="text" id="color5" name="color5" class="color-picker" value="<?php echo "#FFFFFF"; ?>" style="background-color:<?php echo "#FFFFFF"; ?>" />
</td>
<td class="text-settings">
<input type="text" id="color1" name="color1" class="color-picker"  style="background-color:<?php echo " #b50818"; ?>" value="<?php echo " #b50818"; ?>" />
</td>
<td class="text-settings">
<input  type="text" id="color2" name="color2" class="color-picker"  value="<?php echo "#1437d7"; ?>"  style="background-color:<?php echo "#1437d7"; ?>" />
</td>
<td class="text-settings">
<input  type="text" id="color3" name="color3" class="color-picker"  value="<?php echo "#079707"; ?>" style="background-color:<?php echo "#079707"; ?>" />
</td>
<td >
<input type="text" id="color6" name="color6" class="color-picker" value="<?php echo "#888888"; ?>" style="background-color:<?php echo "#888888"; ?>" />
</td>
<td >
<input type="text" id="color4" name="color4" class="color-picker" value="<?php echo "#FFFFFF"; ?>" style="background-color:<?php echo "#FFFFFF"; ?>" />
</td>
</tr>
</table>


<div style="margin-top: 10px;text-align: center;"><input type="submit" name="submit" value="<?php echo $this->get_label('create adblock');?>"></div>

</div>
</td>
</tr>

   

</table>
</div>

<?php $form->end(); ?>
<?php $this->dispatch("layout/footer");?>
<script type="text/javascript">
ChangeSettings();
LoadAspectRatio();
</script>