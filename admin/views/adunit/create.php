<?php
$this->dispatch("layout/header/2/_22");
$cat_type=$this->get_variable('cat_type');

$adpricing=$this->get_variable('adpricing');
$sid=$this->get_variable('sid');
$blockid=$this->get_variable('blockid');
$adbid=$blockid;


$category_enabled=$this->get_addon_status('category-targeting_enabled');
$textimage_enabled=$this->get_addon_status('text-image-ads_enabled');
$interstitial_enabled=$this->get_addon_status('interstitial_enabled');
$skin_enabled=$this->get_addon_status('skin-ads_enabled');
$sponsored_enabled=$this->get_addon_status('sponsored_enabled');


$cpc_enabled=$this->get_addon_status('cpc_enabled');
$cpm_enabled=$this->get_addon_status('cpm_enabled');
$cpa_enabled=$this->get_addon_status('cpa_enabled');


$pop_addon_usage = $this->get_variable('pop_addon_usage');



$popup_support=$this->get_variable('popup_support');
$popunder_support=$this->get_variable('popunder_support');
$poptab_support=$this->get_variable('poptab_support');
$pop_enabled=$this->get_variable('pop_enabled');


$text_ads_enabled=Configuration::get_instance()->read('text-ads_enabled');

$video_enabled=$this->get_variable('video_enabled');
$linear_support=$this->get_variable('linear_support');
$nonlinear_support=$this->get_variable('nonlinear_support');
$html5_player_support=$this->get_variable('html5_player_support');

$adcode_for=$this->get_variable('adcode_for');
$linear=$this->get_variable('linear');
$nonlinearbanner=$this->get_variable('nonlinearbanner');
$nonlineartext=$this->get_variable('nonlineartext');
$nonlinear_size=$this->get_variable('nonlinear_size');


if($text_ads_enabled ==0 && $nonlineartext ==1)
$nonlineartext=0;

$form=$this->create_form();
$form->start("createadunit",$this->make_url("adunit/create"),"post"); 


?>
<style type="text/css">
#adpricing,#sid,#adblocktype
{
width:150px !important;
}
</style>


<div class="sub_menu_main"><?php echo $this->get_label('create new adunit');?></div>


<?php $this->dispatch("links/links/21");?>

<div class="inner-box">
<div class="pages-input">


<table style="width: 100%;" cellpadding="0" cellspacing="0">

<tr>
<td style="width: 170px;"><?php echo $this->get_label('name');?></td>
<td style="width: 10px;"></td>
<td><input type="text" name="name" id="name" value="<?php echo $this->get_variable('name');?>" maxlength="25"><span class="compulsory">*</span>
</td>
</tr>


<tr>
<td ><?php echo $this->get_label('pricing');?></td>
<td></td>
<td><?php echo $this->get_pricing_box($adpricing,2);?></td>
</tr>



<tr class="video-option" style="display: none;">
<td><?php echo $this->get_label('adcode for');?></td>
<td></td>
<td>
<select style="width: 150px;" name="adcode_for" id="adcode_for" class="form-control" onchange="<?php if($category_enabled ==1){?>LoadSiteData();<?php }?>LoadVideoOptions();">

<?php if($linear_support ==1 || $nonlinear_support ==1){?>
<option value="1" <?php if($adcode_for ==1){?> selected="selected" <?php }?>><?php echo $this->get_label('vast player');?></option>
<?php }?>
<?php if($html5_player_support ==1){?>
<option value="2" <?php if($adcode_for ==2){?> selected="selected" <?php }?>><?php echo $this->get_label('html5 player');?></option>
<?php }?>
</select>
</td>
</tr>


<?php if($html5_player_support ==1){?>
<tr class="video-option-html5" style="display: none;">
<td><?php echo $this->get_label('player dimension');?></td>
<td></td>
<td>
<select class="form-control" name="player_size" id="player_size" style="width: 150px;">
<?php 
$res13=$this->get_result('res13');

foreach($res13 as $key=>$result13)
{
	$height=$result13['height'];
	$width=$result13['width'];
	$name=$this->escape($result13['name']);
	$type=$this->get_adblock_type($result13['type']);
	$id=$result13['id'];
	$diamensions=$result13['width']." x ".$result13['height'];
?>
<option value="<?php echo $id;?>" <?php if($adbid == $id) { echo "selected"; }?>><?php echo $name." (".$diamensions.") "; ?></option>
<?php }?>
</select>
</td>
</tr>
<?php }?>

<?php if($linear_support ==1 || $nonlinear_support ==1){

$res14=$this->get_result('res14');
?>
<tr class="video-option-vast" style="display: none;">
<td><?php echo $this->get_label('supported type');?> <span class="compulsory">*</span></td>
<td></td>
<td>

<?php if($linear_support ==1){?>
<div style="float: left;">
<input disabled="disabled" type="checkbox" name="linear" id="linear" value="1" checked="checked" /> <?php echo $this->get_label('linear');?>&nbsp;&nbsp;
</div>
<?php }?>

<?php if($nonlinear_support ==1){?>

<?php if($text_ads_enabled ==1){?>
<div style="float: left;">
<input  type="checkbox" name="nonlineartext" id="nonlineartext" value="1" <?php if($nonlineartext ==1){?>checked="checked"<?php }?> /> <?php echo $this->get_label('non linear text');?>&nbsp;&nbsp;
</div>
<?php }?>


<?php if(count($res14) >0){?>
<div style="float: left;">
<input  type="checkbox" name="nonlinearbanner" id="nonlinearbanner" value="1" <?php if($nonlinearbanner ==1){?>checked="checked"<?php }?> onclick="LoadVideoOptions();"/> <?php echo $this->get_label('non linear banner');?>
</div>
<?php }?>

<?php }?>
</td>
</tr>
<?php }?>


<?php if($nonlinear_support ==1 && count($res14) >0){?>
<tr class="video-option-vast-size" style="display: none;">
<td><?php echo $this->get_label('banner dimension');?></td>
<td></td>
<td>

<select class="form-control" name="nonlinear_size" id="nonlinear_size" style="width: 150px;">
<?php 
foreach($res14 as $key=>$result14)
{
	$height=$result14['height'];
	$width=$result14['width'];
	$id=$result14['id'];
	$diamensions=$result14['width']." x ".$result14['height'];
?>
<option value="<?php echo $id;?>" <?php if($nonlinear_size == $id) { echo "selected"; }?>><?php echo $diamensions; ?></option>
<?php }?>
</select>
</td>
</tr>
<?php }?>


<?php if($category_enabled ==1){?>
<tr class="site-class" style="display: none;">
<td ><?php echo $this->get_label('targeting site');?></td>
<td></td>
<td>

<span class="sid_span sid_00" style="display: none;"><?php echo CategoryHelper::get_site_dropdown(0,$sid,1); //sid ?></span>

<?php if($sponsored_enabled ==1){?> 
<span class="sid_span sid_01" style="display: none;">
<?php echo CategoryHelper::get_site_dropdown(0,$sid,1,0,1); //sid_cpd ?>
</span>
<?php }?>

<span class="compulsory">*</span></td>
</tr>
<?php }?>



  <tr class="adb-tr">
   <td><?php echo $this->get_label('adblock type');?></td>
   <td></td>
    <td>
    <select name="adblocktype" id="adblocktype" onchange="DisplayDeviceBlock();">
    
    <?php if($text_ads_enabled ==1){?>
    <option value="1" <?php if($this->get_variable("adblocktype")==1) {echo "selected";}?>><?php echo $this->get_label('text only');?></option>
    <?php }?>
    
    <option value="2" <?php if($this->get_variable("adblocktype")==2) {echo "selected";}?>><?php  echo $this->get_label('banner only');?></option>
    
    <?php if($text_ads_enabled ==1){?>
    <option value="3" <?php if($this->get_variable("adblocktype")==3) {echo "selected";}?>><?php echo $this->get_label('textbanner');?></option>
    <?php }?>
   
    <?php if($interstitial_enabled ==1){?>
    <option value="5" <?php if($this->get_variable("adblocktype")==5) {echo "selected";}?>><?php echo $this->get_label('interstitial code');?></option>
    <?php }?>

    <?php if($pop_addon_usage ==1){?>
    <option class="pop-option" value="9" <?php if($this->get_variable("adblocktype")==9) {echo "selected";}?>><?php echo $this->get_label('pop');?></option>
    <?php }?>  
   

	<?php if($textimage_enabled==1){?>
    <option value="11" <?php if($this->get_variable("adblocktype")==11) {echo "selected";}?>><?php echo $this->get_label('textimage');?></option>
    <?php } ?>
    
    <?php if($skin_enabled ==1){?>
    <option value="14" <?php if($this->get_variable("adblocktype")==14) {echo "selected";}?>><?php echo $this->get_label('skin');?></option>
    <?php }?>   
      
    </select>
    </td>
    </tr>



<?php if($pop_enabled ==1){

	$pop_ads_support=Configuration::get_instance()->read('pop_ads_support');
	
	$pop_array=explode('-',$pop_ads_support);

	$pop_up=intval($pop_array[0]);
	$pop_under=intval($pop_array[1]);
	$pop_tab=intval($pop_array[2]);
	
?>


<tr class="pop-class" style="display: none;">
<td style="width: 200px;"><?php echo $this->get_label('pop type');?></td>
<td></td>
<td >
<?php if($pop_up ==1){?>
<span><input style="width: 20px !important;" type="checkbox" name="popup_support" id="popup_support" value="1" <?php if($popup_support ==1){?> checked="checked" <?php }?> /> <?php echo $this->get_label('popup');?></span>
<?php }?>

<?php if($pop_under ==1){?>
<span><input style="width: 20px !important;" type="checkbox" name="popunder_support" id="popunder_support" value="1" <?php if($popunder_support ==1){?> checked="checked" <?php }?> /> <?php echo $this->get_label('popunder');?></span>
<?php }?>

<?php if($pop_tab ==1){?>
<span><input style="width: 20px !important;" type="checkbox" name="poptab_support" id="poptab_support" value="1" <?php if($poptab_support ==1){?> checked="checked" <?php }?> /> <?php echo $this->get_label('poptab');?></span>
<?php }?>
<span class="compulsory">*</span></td>
</tr>
<?php }?>



<tr class="adb-size">
<td><?php echo $this->get_label('select adblock');?></td>
<td></td>
<td>


<span class="banner-select" id="banner-select-1" style="display: none;">   
<select name="blockid_1" id="blockid_1" style="width: 233px;">
<?php 
$res1=$this->get_result('res1');

foreach($res1 as $key=>$result1)
{
	$height=$result1['height'];
	$width=$result1['width'];
	$name=$this->escape($result1['name']);
	$type=$this->get_adblock_type($result1['type']);
	$id=$result1['id'];
	$diamensions=$result1['width']." x ".$result1['height'];
?>
<option value="<?php echo $id;?>" <?php if($adbid == $id) { echo "selected"; }?>><?php echo $name." (".$diamensions.") "; ?></option>
<?php }?>
</select>
</span>


<span class="banner-select" id="banner-select-2" style="display: none;">   
<select name="blockid_2" id="blockid_2" style="width: 233px;">
<?php 
$res2=$this->get_result('res2');

foreach($res2 as $key=>$result1)
{
	$height=$result1['height'];
	$width=$result1['width'];
	$name=$this->escape($result1['name']);
	$type=$this->get_adblock_type($result1['type']);
	$id=$result1['id'];
	$diamensions=$result1['width']." x ".$result1['height'];
?>
<option value="<?php echo $id;?>" <?php if($adbid == $id) { echo "selected"; }?>><?php echo $name." (".$diamensions.") "; ?></option>
<?php }?>
</select>
</span>


<span class="banner-select" id="banner-select-3" style="display: none;">   
<select name="blockid_3" id="blockid_3" style="width: 233px;">
<?php 
$res3=$this->get_result('res3');

foreach($res3 as $key=>$result1)
{
	$height=$result1['height'];
	$width=$result1['width'];
	$name=$this->escape($result1['name']);
	$type=$this->get_adblock_type($result1['type']);
	$id=$result1['id'];
	$diamensions=$result1['width']." x ".$result1['height'];
?>
<option value="<?php echo $id;?>" <?php if($adbid == $id) { echo "selected"; }?>><?php echo $name." (".$diamensions.") "; ?></option>
<?php }?>
</select>
</span>


<span class="banner-select" id="banner-select-7" style="display: none;">   
<select name="blockid_7" id="blockid_7" style="width: 233px;">
<?php 
$res7=$this->get_result('res7');

foreach($res7 as $key=>$result1)
{
	$height=$result1['height'];
	$width=$result1['width'];
	$name=$this->escape($result1['name']);
	$type=$this->get_adblock_type($result1['type']);
	$id=$result1['id'];
	$diamensions=$result1['width']." x ".$result1['height'];
?>
<option value="<?php echo $id;?>" <?php if($adbid == $id) { echo "selected"; }?>><?php echo $name." (".$diamensions.") "; ?></option>
<?php }?>
</select>
</span>


<span class="banner-select" id="banner-select-9" style="display: none;">   
<select name="blockid_9" id="blockid_9" style="width: 233px;">
<?php 
$res9=$this->get_result('res9');

foreach($res9 as $key=>$result1)
{
	$height=$result1['height'];
	$width=$result1['width'];
	$name=$this->escape($result1['name']);
	$type=$this->get_adblock_type($result1['type']);
	$id=$result1['id'];
	$diamensions=$result1['width']." x ".$result1['height'];
?>
<option value="<?php echo $id;?>" <?php if($adbid == $id) { echo "selected"; }?>><?php echo $name." (".$diamensions.") "; ?></option>
<?php }?>
</select>
</span>


<?php if($skin_enabled ==1){?>
<span class="banner-select" id="banner-select-14" style="display: none;">
<select name="blockid_14" id="blockid_14" onchange="Display_skin_prev();">
<?php

$res15=$this->get_result('res15');

$exist_positions=array();
$skin_preview="";


foreach($res15 as $key=>$result1)
{
	$id=$result1['id'];

	$get_skin_positions_res =$this->get_skin_positions($result1['skin_positions']);

	if(!array_key_exists($get_skin_positions_res,$exist_positions) && $get_skin_positions_res !=1)
	{
		$exist_positions[$get_skin_positions_res] =1;
		$get_skin_preview_res =$this->get_skin_preview($id);
	
		$skin_preview.='<div class="skin_prev" id="skin_prev'.$id.'" style="display:none;"><img src="'.$get_skin_preview_res.'"></div>';
	?>
	<option value="<?php echo $id;?>" <?php if($adbid == $id) { echo "selected"; }?>><?php echo $get_skin_positions_res; ?></option>
	<?php }}?>
</select>
</span>
<span class="skin_pos" style="left: 500px;position: absolute;"><?php echo $skin_preview; ?></span>
<?php }?>
</td>
</tr>

<?php if($skin_enabled ==1){?>
<tr class="skin_pos" style="display:none;">
<td style="padding-top: 10px;"><?php echo $this->get_label('main container id'); ?> <span class="compulsory">*</span></td>
<td></td>
<td style="padding-top: 10px;"><input type="text" name="container_id" id="container_id" /></td>
</tr>
<?php }?>


<!-- code added -->
<?php ?>
<tr>
<td style="width: 170px;"><?php echo $this->get_label('category type');?></td>
<td style="width: 10px;"></td>
<td><?php if($_POST) $cat=$this->read_post_param('cat_type');?>
<input type="radio" name="cat_type" id="cat_type0" value="0" <?php if($cat==0){?>checked="checked"<?php } ?> onclick="" />&nbsp;<?php echo $this->get_label('all');?>&nbsp;&nbsp;&nbsp;
<input type="radio" name="cat_type" id="cat_type1" value="1" <?php if($cat ==1){?>checked="checked"<?php }?> onclick="" />&nbsp;<?php echo $this->get_label('one');?>&nbsp;&nbsp;&nbsp;
<input type="radio" name="cat_type" id="cat_type2" value="2" <?php if($cat ==2){?>checked="checked"<?php }?> onclick="" />&nbsp;<?php echo $this->get_label('two');?>&nbsp;&nbsp;&nbsp;

</td>
</tr>
<!-- code added -->

<tr><td></td><td></td>
<td  align="left"><input type="submit" name="submit" value="<?php echo $this->get_label('create new adunit');?>"></td>
</tr>
</table>
</div>
</div>
<?php $form->end(); ?>


<script type="text/javascript">
function LoadBannerType()
{
	if(($("#adpricing").val() == 0 || $("#adpricing").val() == 3 || $("#adpricing").val() == 13) && $('#adblocktype').val() == 9)	
	$('#adblocktype').val(0);	   	

	
	if($("#adpricing").val() !=9 && $("#adpricing").val() !=13 && $('#adblocktype').val() !=9)
	{
		if($('.pop-class').length >0)
		$('.pop-class').hide();

		$('.adb-size').show();
		$('.adb-tr').show();		

		if($('.video-option').length >0)
		$('.video-option').hide();	

		if($('#adcode_for').length >0)
		$('#adcode_for').val(0);

		DisplayDeviceBlock();
	}
	else if($("#adpricing").val() ==9 || $('#adblocktype').val() ==9)	
	{
		if($('.video-option').length >0)
		$('.video-option').hide();	

		if($('#adcode_for').length >0)
		$('#adcode_for').val(0);
		
		if($('.pop-class').length >0)
		$('.pop-class').show();

		$('.adb-tr').show();
		$('.adb-size').hide();		
	}
	else if($("#adpricing").val() ==13)
	{
		$('.video-option').show();
		
		if($('.pop-class').length >0)
		$('.pop-class').hide();

		$('.adb-tr').hide();
		$('.adb-size').hide();
	}	
}


$(document).ready(function() {

	$("#adpricing").change(function()
	{
		<?php if($category_enabled ==1){?>
		LoadSiteData();
		<?php }?>

		LoadBannerType();

		LoadVideoOptions();
	});

	<?php if($category_enabled ==1){?>
	LoadSiteData();
	<?php }?>

	LoadBannerType();

	LoadVideoOptions();	
});


<?php if($category_enabled ==1){?>
function LoadSiteData()
{
	$(".site-class").hide();
	var selected=$("#adpricing").val();
	var allowed='<?php echo Configuration::get_instance()->read('category_enabled_ads');?>';
	if(allowed !='')
	{
		allowed_array=allowed.split('_');

		if($.inArray(selected , allowed_array) >-1)
		$(".site-class").show();
		else
		{
			if(selected ==13 && $("#adcode_for").val() ==1)
			$(".site-class").show();
			else
			{
				if($("#sid_select_00").length > 0)
				$("#sid_select_00").val(0);	

				if($("#sid_select_01").length > 0)
				$("#sid_select_01").val(0);	
			}
		}
	}



	if($('.sid_span').length > 0)
	{
		$('.sid_span').hide();

		if($('#adpricing').val() ==3)
		$('.sid_01').show();
		else 
		$('.sid_00').show();
	}
}
<?php }?>


function DisplayDeviceBlock()
{
	pricing=$("#adpricing").val();
	adblocktype=$('#adblocktype').val();

	$('.banner-select').hide();

	if($(".skin_pos").length >0)
	$(".skin_pos").hide();

	pop_addon_usage = <?php echo $pop_addon_usage; ?>;

	if(pop_addon_usage == 1)
	{
	    $('.pop-option').hide();	
	        
	    if(pricing == 1 || pricing == 6)
	   	$('.pop-option').show();
	   	else if(pricing == 4)
	   	{
			<?php if($cpm_enabled ==1 || $cpa_enabled ==1){?>
			$('.pop-option').show();
			<?php }?>
	   	}
	 }

	
	if(adblocktype != 9)
	{
		$(".adb-size").show();	

		if($('.pop-class').length >0)
		$('.pop-class').hide();		
		
		if(adblocktype ==1)
		$('#banner-select-1').show();
		else if(adblocktype ==2)
		$('#banner-select-2').show();
		else if(adblocktype ==3)
		$('#banner-select-3').show();
		else if(adblocktype ==5)
		$('#banner-select-7').show();
		else if(adblocktype ==11)
		$('#banner-select-9').show();
		else if(adblocktype ==14)
		{
			$('#banner-select-14').show();
			$(".skin_pos").show();
	
			Display_skin_prev();
		}

		<?php if($pop_addon_usage == 1){?>
		if($("#adpricing option[value='4']").length > 0)
		{
			<?php 
			$label_data = "";

			if($cpc_enabled ==1 && $cpm_enabled ==1 && $cpa_enabled ==1)
			$label_data = $this->get_label('ppc/cpm/cpa');
			else if($cpc_enabled ==1 && $cpm_enabled ==1)
			$label_data = $this->get_label('ppc/cpm');
			else if($cpc_enabled ==1 && $cpa_enabled ==1)
			$label_data = $this->get_label('ppc/cpa');	
			else if($cpm_enabled ==1 && $cpa_enabled ==1)
			$label_data = $this->get_label('cpm/cpa');	
			?>

			$("#adpricing option[value='4']").text('<?php echo $label_data;?>');
		}
		<?php }?>
	}
	else
	{
		if($("#adpricing option[value='4']").length > 0)
		{
			<?php if($cpm_enabled == 1 && $cpa_enabled == 1){?>
			$("#adpricing option[value='4']").text('<?php echo $this->get_label('cpm/cpa');?>');
			<?php } else if($cpm_enabled == 1){?>
			$("#adpricing option[value='4']").text('<?php echo $this->get_label('cpm');?>');
			<?php } else if($cpa_enabled == 1){?>
			$("#adpricing option[value='4']").text('<?php echo $this->get_label('cpa');?>')
			<?php }?>
		}

		if($('.video-option').length >0)
		$('.video-option').hide();	

		if($('#adcode_for').length >0)
		$('#adcode_for').val(0);
		
		if($('.pop-class').length >0)
		$('.pop-class').show();

		$('.adb-size').hide();
	}
	
}

function LoadVideoOptions()
{
	pricing=$("#adpricing").val();

	if(pricing ==13)
	{
		if($("#adcode_for").val() ==1)
		{
			if($('.video-option-vast').length >0)
			$('.video-option-vast').show();

			if($('.video-option-html5').length >0)
			$('.video-option-html5').hide();

			if($('#nonlinearbanner').length >0)
			{
				if($('#nonlinearbanner').prop('checked'))
				$('.video-option-vast-size').show();	
				else
				$('.video-option-vast-size').hide();	
			}
		}
		else if($("#adcode_for").val() ==2)
		{
			if($('.video-option-html5').length >0)
			$('.video-option-html5').show();
			
			if($('.video-option-vast').length >0)
			$('.video-option-vast').hide();

			if($('.video-option-vast-size').length >0)
			$('.video-option-vast-size').hide();			
		}
	}
	else
	{
		if($('.video-option-html5').length >0)
		$('.video-option-html5').hide();

		if($('.video-option-vast').length >0)
		$('.video-option-vast').hide();

		if($('.video-option-vast-size').length >0)
		$('.video-option-vast-size').hide();		
	}
}

<?php if($skin_enabled ==1){?>
function Display_skin_prev()
{
	bannersize_sel=$("#blockid_14").val();

	$(".skin_prev").hide();
	$('#skin_prev'+bannersize_sel).show();
}
<?php } ?>
</script>
<?php $this->dispatch("layout/footer");?>