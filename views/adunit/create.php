<?php
$this->dispatch("layout/header/6/1/p");

$uid=$this->get_variable('uid');
$sid=$this->get_variable('sid');
$adbid=$this->get_variable("blockid");
$adpricing=$this->get_variable('adpricing');
$native=$this->get_variable('native');

$cpa_enabled=$this->get_addon_status('cpa_enabled');
$cpm_enabled=$this->get_addon_status('cpm_enabled');
$html_enabled=$this->get_addon_status('html_enabled');
$textimage_enabled=$this->get_addon_status('text-image-ads_enabled');
$category_enabled=$this->get_addon_status('category-targeting_enabled');
$sponsored_enabled=$this->get_addon_status('sponsored_enabled');
$interstitial_enabled=$this->get_addon_status('interstitial_enabled');
$cpc_enabled=$this->get_addon_status('cpc_enabled');
$skin_enabled=$this->get_addon_status('skin-ads_enabled');
$nativeads_enabled=$this->get_addon_status('native-ad-display_enabled');




$cpm_data_enabled=0;

if($cpm_enabled ==1 || $html_enabled ==1)
$cpm_data_enabled=1;




$name=$this->get_variable('name');
$header=$name;
if($nativeads_enabled ==1 && $header=='')
{
	$header=Configuration::get_instance()->read('nativead_header');
	
}

$text_ads_enabled=Configuration::get_instance()->read('text-ads_enabled');


$popup_support=$this->get_variable('popup_support');
$popunder_support=$this->get_variable('popunder_support');
$poptab_support=$this->get_variable('poptab_support');
$pop_enabled=$this->get_variable('pop_enabled');

if($cpm_enabled ==1 || $html_enabled ==1)
$cpm_enabled=1;	

$video_enabled=$this->get_variable('video_enabled');
$linear_support=$this->get_variable('linear_support');
$nonlinear_support=$this->get_variable('nonlinear_support');
$html5_player_support=$this->get_variable('html5_player_support');
$vast_adcode_enabled=intval($this->get_variable('vast_adcode_enabled'));


$adcode_for=$this->get_variable('adcode_for');
$linear=$this->get_variable('linear');
$nonlinearbanner=$this->get_variable('nonlinearbanner');
$nonlineartext=$this->get_variable('nonlineartext');
$nonlinear_size=$this->get_variable('nonlinear_size');


if($text_ads_enabled ==0 && $nonlineartext ==1)
$nonlineartext=0;

$layout=$this->get_variable('layout');
$type1=$this->get_variable('type');
$customcode=$this->get_variable('customcode');
$responsive=$this->get_variable("responsive");
if ($customcode=='')
{
	$customcode='<style type="text/css">
	/* enter your CSS here */
</style>

<script type="text/javascript">
	// enter your JavaScript here
</script>';
}
?>
<style type="text/css">
#adpricing
{
	width:100% !important;
}
</style>


<div class="container"><h2 class="page_heading"><?php echo $this->get_label('create new adunit');?></h2>
<div class="page_heading-btm"></div>
</div>

  <div class="container label_style special-label">
   <div class="col-md-12 col-sm-12 col-xs-12 box_style">
   <div class="col-md-12 col-sm-12 col-xs-12">
   <div class="col-md-12 col-sm-12 col-xs-12 box_div box_div_bg" style="padding-bottom:30px;">





<?php 



$form=$this->create_form();
$form->start("createadunit",$this->make_url("adunit/create"),"post"); 
?>

  
  
<?php if($nativeads_enabled==1){?>
<div class="search_div" style="min-height: 50px; ">
<div class="search_div_table form-inline col-md-12 col-sm-12 col-xs-12">
<div class=" form-inline ">

<div >
<div class="form-group">
<label><?php echo $this->get_label('ad display');?></label>
<select class="form-control" name="native" id="native" onchange="javascript:return changeaddisplay();" style="width: 150px;">
<option value="0" <?php if($native==0){?>selected <?php }?>><?php echo $this->get_label('predefined');?></option>
<option value="1" <?php if($native==1){?>selected <?php }?>><?php echo $this->get_label('native');?></option>
</select>
</div></div>
</div>
</div>
</div>
<?php }else{?>
<input type="hidden" name="native" id="native" value="0" />
<?php }?>

<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" id="normal">
<div class="form-group">
<label><?php echo $this->get_label('adunit name');?> <span class="compulsory">*</span></label>
<input class="form-control" type="text" name="name" id="name" value="<?php echo $this->get_variable('name');?>" maxlength="25" />

</div>




<div class="form-group">
<label><?php echo $this->get_label('adunit type');?></label>

<?php 
$ad_display_priority=Configuration::get_instance()->read('ad_display_priority');

$priority_array=explode('_',$ad_display_priority);

if(Configuration::get_instance()->read('ad_preference') ==1 || count($priority_array) ==1)
echo $this->get_pricing_box($adpricing,2);
else 
echo $this->get_pricing_box($adpricing,5);	
?>

</div>



<div class="form-group video-option" style="display: none;">
<label><?php echo $this->get_label('adcode for');?></label>

<select name="adcode_for" id="adcode_for" class="form-control" onchange="<?php if($category_enabled ==1){?>LoadSiteData();<?php }?>LoadVideoOptions();">

<?php if($vast_adcode_enabled ==1 && ($linear_support ==1 || $nonlinear_support ==1)){?>
<option value="1" <?php if($adcode_for ==1){?> selected="selected" <?php }?>><?php echo $this->get_label('vast player');?></option>
<?php }?>
<?php if($html5_player_support ==1){?>
<option value="2" <?php if($adcode_for ==2){?> selected="selected" <?php }?>><?php echo $this->get_label('html5 player');?></option>
<?php }?>
</select>

</div>


<?php if($html5_player_support ==1){?>
<div class="form-group video-option-html5" style="display: none;">
<label><?php echo $this->get_label('player dimension');?></label>

<select class="form-control" name="player_size" id="player_size" >
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
</div>
<?php }?>

<?php if($vast_adcode_enabled ==1 && ($linear_support ==1 || $nonlinear_support ==1)){

$res14=$this->get_result('res14');
?>

<div class="form-group video-option-vast" style="display: none;">
<label><?php echo $this->get_label('supported type');?> <span class="compulsory">*</span></label>

<div style="width: 100%;height: 20px;">

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
</div>
</div>
<?php }?>



<?php if($nonlinear_support ==1 && count($res14) >0){?>
<div class="form-group video-option-vast-size" style="display: none;">
<label><?php echo $this->get_label('banner dimension');?></label>

<select class="form-control" name="nonlinear_size" id="nonlinear_size">
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
</div>
<?php }?>

<?php if($category_enabled ==1){?>
<div class="form-group site-class" style="display: none;">
<label><?php echo $this->get_label('targeting site');?> <span class="compulsory">*</span></label>

<span class="sid_span sid_00" style="display: none;"><?php echo CategoryHelper::get_site_dropdown($uid,$sid,1); //sid ?></span>

<?php if($sponsored_enabled ==1){?> 
<span class="sid_span sid_01" style="display: none;">
<?php echo CategoryHelper::get_site_dropdown($uid,$sid,1,0,1); //sid_cpd ?>
</span>
<?php }?>
</div>

<?php }?>






<?php if($pop_enabled ==1){

	$pop_ads_support=Configuration::get_instance()->read('pop_ads_support');
	
	$pop_array=explode('-',$pop_ads_support);

	$pop_up=intval($pop_array[0]);
	$pop_under=intval($pop_array[1]);
	$pop_tab=intval($pop_array[2]);
	
?>


<div class="form-group pop-class" style="display: none;">
<label><?php echo $this->get_label('pop type');?> <span class="compulsory">*</span></label>


<?php if($pop_up ==1){?>
<span><input style="width: 20px !important;" type="checkbox" name="popup_support" id="popup_support" value="1" <?php if($popup_support ==1){?> checked="checked" <?php }?> /> <?php echo $this->get_label('popup');?></span>
<?php }?>

<?php if($pop_under ==1){?>
<span><input style="width: 20px !important;" type="checkbox" name="popunder_support" id="popunder_support" value="1" <?php if($popunder_support ==1){?> checked="checked" <?php }?> /> <?php echo $this->get_label('popunder');?></span>
<?php }?>

<?php if($pop_tab ==1){?>
<span><input style="width: 20px !important;" type="checkbox" name="poptab_support" id="poptab_support" value="1" <?php if($poptab_support ==1){?> checked="checked" <?php }?> /> <?php echo $this->get_label('poptab');?></span>
<?php }?>

</div>


<?php }?>



   
<div class="form-group adb-tr">
<label><?php echo $this->get_label('adblock type');?></label>

    <select class="form-control" name="adblocktype" id="adblocktype" onchange="DisplayDeviceBlock();">
    
    <?php if($text_ads_enabled ==1){?>
    <option value="1" <?php if($this->get_variable("adblocktype")==1) {echo "selected";}?>><?php echo $this->get_label('text only');?></option>
    <?php }?>
    
    <option value="2" <?php if($this->get_variable("adblocktype")==2) {echo "selected";}?>><?php echo $this->get_label('banner only');?></option>
    
    <?php if($text_ads_enabled ==1){?>
    <option value="3" <?php if($this->get_variable("adblocktype")==3) {echo "selected";}?>><?php echo $this->get_label('textbanner');?></option>
    <?php }?>
   
   <?php if($interstitial_enabled ==1){?>
   <option value="5" <?php if($this->get_variable("adblocktype")==5) {echo "selected";}?>><?php echo $this->get_label('interstitial code');?></option>
   <?php }?>  
    
   <?php if($textimage_enabled ==1){?>
   <option value="11" <?php if($this->get_variable("adblocktype")==11) {echo "selected";}?>><?php echo $this->get_label('textimage');?></option>
   <?php }?>
   
   <?php if($skin_enabled ==1){?>
   <option value="14" <?php if($this->get_variable("adblocktype")==14) {echo "selected";}?>><?php echo $this->get_label('skin');?></option>
   <?php }?>   
   
   </select>

</div>



<div class="form-group adb-size">
<label><?php echo $this->get_label('select adblock');?></label>



<span class="banner-select" id="banner-select-1" style="display: none;">   
<select class="form-control" name="blockid_1" id="blockid_1" >
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
<select class="form-control" name="blockid_2" id="blockid_2" >
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
<select class="form-control" name="blockid_3" id="blockid_3" >
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
<select class="form-control" name="blockid_7" id="blockid_7" >
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
<select class="form-control" name="blockid_9" id="blockid_9" >
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
<select class="form-control" name="blockid_14" id="blockid_14" onchange="Display_skin_prev();">
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
<div><?php echo $skin_preview; ?></div>
</span>
<?php }?>


</div>

<?php if($skin_enabled ==1){?>
<div class="form-group" id="skin_pos" style="display:none;">
<label><?php echo $this->get_label('main container id'); ?> <span class="compulsory">*</span></label>
<input class="form-control" type="text" name="container_id" id="container_id" />
</div>
<?php }?>

</div>







<?php if($nativeads_enabled ==1){?>
<div class="form-group col-md-12 col-sm-12 col-xs-12" id="native_display" style="padding-top:30px;">
<div class="form-group col-md-12 col-sm-12 col-xs-12">
<label class="col-md-4 col-sm-4 col-xs-12"  ><?php echo $this->get_label('header text');?> <span class="compulsory">*</span></label>
<div class="col-md-8 col-sm-8 col-xs-12"><input class="form-control" type="text" name="name1" id="name" value="<?php echo $header;?>" maxlength="25"  />
</div>
</div>


<div class="form-group col-md-12 col-sm-12 col-xs-12">
<label class="col-md-4 col-sm-4 col-xs-12" ><?php echo $this->get_label('adunit type');?></label>
<div class="col-md-8 col-sm-8 col-xs-12">

<?php 
$ad_display_priority=Configuration::get_instance()->read('ad_display_priority');

$priority_array=explode('_',$ad_display_priority);

$preference=intval(Configuration::get_instance()->read('ad_preference'));
?>

<select class="form-control" name="adpricing1" id="adpricing1" onchange="javascript:return changeadpricing();" style="width: 150px;">

<?php if($cpc_enabled==1 && ($preference ==1 || count($priority_array) ==1)){?>
<option value="0" <?php if($adpricing==0){echo 'selected ';}?>><?php echo $this->get_label('ppc');?></option>
<?php }?>
<?php if($cpm_enabled==1 && ($preference ==1 || count($priority_array) ==1)){?>
<option value="1" <?php if($adpricing==1){echo 'selected ';}?>><?php echo $this->get_label('cpm');?></option>
<?php }?>

<?php if($cpa_enabled==1 && ($preference ==1 || count($priority_array) ==1)){?>
<option value="6" <?php if($adpricing==6){echo 'selected ';}?>><?php echo $this->get_label('cpa');?></option>
<?php }?>
<?php 
$string='';


		if((($cpc_enabled ==1 && $cpm_data_enabled ==1) || ($cpc_enabled ==1 && $cpa_enabled ==1) || ($cpm_data_enabled ==1 && $cpa_enabled ==1)))
		{
	    	
	    	

			if($cpc_enabled ==1 && $cpm_data_enabled ==1 && $cpa_enabled ==1)
			$string=$this->get_label('ppc/cpm/cpa');
			else if($cpc_enabled ==1 && $cpm_data_enabled ==1)
			$string=$this->get_label('ppc/cpm');
			else if($cpc_enabled ==1 && $cpa_enabled ==1)
			$string=$this->get_label('ppc/cpa');		
			else if($cpm_data_enabled ==1 && $cpa_enabled ==1)
			$string=$this->get_label('cpm/cpa');	
	    	
	    	
	   		
		}		

		if($string!='')
		{?>
		<option value="4"  <?php if($adpricing==4){echo 'selected ';}?>><?php echo $string;?></option>
		
		<?php 
			
		}
		

?>
</select>
</div>
<div class="col-md-6 col-sm-3 col-xs-12"></div>
</div>



<?php if($category_enabled ==1){?>
<div class="form-group col-md-12 col-sm-12 col-xs-12 site-class" style="display: none;">
<label class="col-md-4 col-sm-4 col-xs-12" ><?php echo $this->get_label('targeting site');?> <span class="compulsory">*</span></label>
<div class="col-md-8 col-sm-8 col-xs-12">
<span class="sid_span sid_10" style="display: none;"><?php echo CategoryHelper::get_site_dropdown($uid,$sid,1,1); //sid_native ?></span>

<?php if($sponsored_enabled ==1){?> 
<span class="sid_span sid_11" style="display: none;">
<?php echo CategoryHelper::get_site_dropdown($uid,$sid,1,1,1); //sid_native_cpd ?>
</span>
<?php }?>
</div>
</div>
<?php }?>



   
<div class="form-group col-md-12 col-sm-12 col-xs-12 adb-tr">
<label class="col-md-4 col-sm-4 col-xs-12" ><?php echo $this->get_label('ad content type');?><span class="compulsory">*</span></label>
  <div class="col-md-8 col-sm-8 col-xs-12">
   <select class="form-control" name="adblocktype1" id="adblocktype1" onchange="show_layout();" >
    <option value="-1" <?php if($this->get_variable("adblocktype")==-1) {echo "selected";}?>><?php echo $this->get_label('select');?></option>	 
    
    <?php if($text_ads_enabled ==1){?>
    <option value="1" <?php if($this->get_variable("adblocktype")==1) {echo "selected";}?> ><?php echo $this->get_label('text only');?></option>
    <?php }?>
    
   <?php if($textimage_enabled ==1){?>
   <option value="11" <?php if($this->get_variable("adblocktype")==11) {echo "selected";}?>><?php echo $this->get_label('textimage');?></option>
   <?php }?>
   
   
   
    </select>
    </div>
</div>
<div class="col-md-12 col-sm-12 col-xs-12"></div>
<div class="form-group col-md-12 col-sm-12 col-xs-12" id="layout_select1" style="display: none;">
<label class="col-md-4 col-sm-4 col-xs-12" ><?php echo $this->get_label('select ad layout');?><span class="compulsory">*</span></label>


<div class="col-md-8 col-sm-8 col-xs-12 radio-toolbar">
<?php 
$layout1=$this->get_result('layouttxt');

if(count($layout1))
{ 
foreach($layout1 as $val)
{?>
 <input type="radio" name="layout" value="<?php echo $val['id'];?>" id="layout<?php echo $val['id'];?>" <?php if($layout==$val['id'])echo 'checked';?>> <label for="layout<?php echo $val['id'];?>" ><span class="ui-button-text"><?php echo $val['columns'].'x'.$val['rows'];?></span></label>
 <?php }}else echo $this->get_label('no records found');?>
 
 <?php if(count($layout1) >0){?> 
<br/>
<span class="notification"><?php echo $this->get_label('click one of the above button');?></span>
<?php }?>

</div>
</div>
 
<div class="form-group col-md-12 col-sm-12 col-xs-12 " id="layout_select2" style="display: none;">
<label class="col-md-4 col-sm-4 col-xs-12"><?php echo $this->get_label('select ad layout');?><span class="compulsory">*</span></label>


<div class="col-md-8 col-sm-8 col-xs-12 radio-toolbar">
<?php 
$layout2=$this->get_result('layoutimg');

if(count($layout2))
{ 
foreach($layout2 as $key=> $val)
{?>
 <input type="radio" name="layout" value="<?php echo $val['id'];?>" id="layout<?php echo $val['id'];?>" <?php if($layout==$val['id'])echo 'checked';?>> <label for="layout<?php echo $val['id'];?>" ><span class="ui-button-text"><?php echo $val['columns'].'x'.$val['rows'];?></span></label>
 <?php }} else echo $this->get_label('no records found');?>
 
 <?php if(count($layout1) >0){?> 
<br/>
<span class="notification"><?php echo $this->get_label('click one of the above button');?></span>
<?php }?>
</div>
</div>

<div class="form-group col-md-12 col-sm-12 col-xs-12 " id="img_dim" >
<label class="col-md-4 col-sm-4 col-xs-12"><?php echo $this->get_label('prefered image dimension');?>(in px)</label>
<div class="col-md-8 col-sm-8 col-xs-12"><select name="img_dim" id="img_dim" class="form-control" >
<?php 
$res1=$this->get_result('res_dim');

foreach($res1 as $key=>$result)
{
	$height=$result['height'];
	$width=$result['width'];
	$id=$result['id'];
	$diamensions=$result['width']." x ".$result['height'];
?>
<option value="<?php echo $id;?>" <?php if($this->get_variable("img_dim")==$id) { echo "selected"; }?>><?php echo $diamensions ?></option>
<?php }?>
</select>
</div>
<div class="col-md-6 col-sm-3 col-xs-12"></div>

</div>
<div class="form-group col-md-12 col-sm-12 col-xs-12 native" >
<label class="col-md-4 col-sm-4 col-xs-12" ><?php echo $this->get_label('custom css and js');?></label>
<div class="col-md-8 col-sm-8 col-xs-12"><textarea  style="height:200px !important; width:650px"; class="form-control"  name="customcode" id="customcode" ><?php echo $customcode;?></textarea>
</div>
</div>




<div class="form-group col-md-12 col-sm-12 col-xs-12 native" id="responsivediv" >
<label class="col-md-8 col-sm-12 col-xs-12" for="responsive"><input type="checkbox" name="responsive" id="responsive" value="1" <?php if($responsive==1) echo 'checked';?>>&nbsp;&nbsp;<?php echo $this->get_label('make ad responsive');?></label>
</div>



</div>
<?php }?>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">


<input class="btn btn-primary create_ad_btn" type="submit" name="submit" value="<?php echo $this->get_label('create new adunit');?>" />

</div>
<?php $form->end(); ?>
</div></div></div>



<script type="text/javascript">
function DisplayDeviceBlock()
{
	pricing=$("#adpricing").val();
	adblocktype=$('#adblocktype').val();

	$('.banner-select').hide();

	if($("#skin_pos").length >0)
	$("#skin_pos").hide();

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
		$("#skin_pos").show();

		Display_skin_prev();
	}
}

function LoadBannerType()
{
	if($("#adpricing").val() !=9 && $("#adpricing").val() !=13)
	{
		if($('.pop-class').length >0)
		$('.pop-class').hide();

		$('.adb-tr').show();
		$('.adb-size').show();

		$('.video-option').hide();	
		$('#adcode_for').val(0);
		

		DisplayDeviceBlock();
	}
	else if($("#adpricing").val() ==9)
	{
		$('.video-option').hide();	
		$('#adcode_for').val(0);
		
		if($('.pop-class').length >0)
		$('.pop-class').show();

		$('.adb-tr').hide();
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


	$("#adpricing1").change(function()
	{
		<?php if($category_enabled ==1){?>
		LoadSiteData();
		<?php }?>
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

				if($("#sid_select_10").length > 0)
				$("#sid_select_10").val(0);	

				if($("#sid_select_11").length > 0)
				$("#sid_select_11").val(0);	
			}	
		}
	}


	if($('.sid_span').length > 0)
	{
		$('.sid_span').hide();

		if($('#native').val() ==1 && $('#adpricing1').length >0 && $('#adpricing1').val() ==3)
		$('.sid_11').show();
		else if($('#native').val() ==1 && $('#adpricing1').length >0 && $('#adpricing1').val() !=3)
		$('.sid_10').show();
		else if($('#native').val() ==0 && $('#adpricing').val() ==3)
		$('.sid_01').show();
		else if($('#native').val() ==0)
		$('.sid_00').show();
	}
}
<?php }?>



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


function changeaddisplay()
{
	if($('#native').val()==1)
	{
		$('#normal').hide();
		$('#native_display').show();
	}
	else 
	{
		$('#normal').show();
		$('#native_display').hide();
	}

	<?php if($category_enabled ==1){?>
	LoadSiteData();
	<?php }?>	
}


function show_layout()
{

	if($('#adblocktype1').val()==1)
	{
		$('#layout_select2').hide();
		$('#layout_select1').show();
		$('#img_dim').hide();
		
	}else  if($('#adblocktype1').val()==11)
	{
		$('#layout_select2').show();
		$('#layout_select1').hide();
		$('#img_dim').show();
		
	}
	else 
		{	
		$('#layout_select1').hide();
		$('#layout_select2').hide();
		$('#img_dim').hide();
		
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

	
changeaddisplay();
show_layout();
</script>

<?php $this->dispatch("layout/footer");?>