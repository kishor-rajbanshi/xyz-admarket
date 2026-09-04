<?php
$this->dispatch("layout/header/6/2/p");

$uid      			= $this->get_variable('uid');
$sid 				= $this->get_variable('sid');
$adcodeName			= $this->get_variable('name');
$adbid 				= $this->get_variable("blockid");
$adpricing 			= $this->get_variable('adpricing');
$feedads_enabled 	= $this->get_variable('feedads_enabled');
$ad_display 		= $this->get_variable('ad_display');
$responsive_enabled = $this->get_variable('responsive_enabled');
$responsive_support	= $this->get_variable('responsive_support');


$cpa_enabled 			= $this->get_addon_status('cpa_enabled');
$cpm_enabled 			= $this->get_addon_status('cpm_enabled');
$textimage_enabled 		= $this->get_addon_status('text-image-ads_enabled');
$category_enabled 		= $this->get_addon_status('category-targeting_enabled');
$sponsored_enabled 		= $this->get_addon_status('sponsored_enabled');
$interstitial_enabled 	= $this->get_addon_status('interstitial_enabled');
$cpc_enabled 			= $this->get_addon_status('cpc_enabled');
$skin_enabled 			= $this->get_addon_status('skin-ads_enabled');
$nativeads_enabled 		= $this->get_addon_status('native-ad-display_enabled');
$sticky_enabled 		= $this->get_addon_status('sticky-ad-display_enabled');
$inpage_push_enabled    = $this->get_addon_status('inpage-push-ads_enabled');
$directlink_enabled 	= $this->get_addon_status('direct-link-ads_enabled');

$pop_enabled 			 = $this->get_variable('pop_enabled');
$video_enabled 			 = $this->get_variable('video_enabled');

$text_ads_enabled       = intval(Configuration::get_instance()->read('text-ads_enabled'));
$preference             = intval(Configuration::get_instance()->read('ad_preference'));
$ad_display_priority    = Configuration::get_instance()->read('ad_display_priority');
$priority_array         = explode('_',$ad_display_priority);

$text_support         = 0;
$banner_support       = 0;
$textimage_support    = 0;
$interstitial_support = 0;
$skin_support         = 0;
$pop_support          = 0;
$directlink_support   = 0;


if($text_ads_enabled == 1 && ($cpc_enabled == 1 || $cpm_enabled == 1 || $cpa_enabled == 1 || $sponsored_enabled == 1))
$text_support = 1;

if($cpc_enabled == 1 || $cpm_enabled == 1 || $cpa_enabled == 1 || $sponsored_enabled == 1)
$banner_support = 1;

if($textimage_enabled == 1 && ($cpc_enabled == 1 || $cpm_enabled == 1 || $cpa_enabled == 1 || $sponsored_enabled == 1))
$textimage_support = 1;

if($interstitial_enabled == 1 && ($cpc_enabled == 1 || $cpm_enabled == 1 || $cpa_enabled == 1 || $sponsored_enabled == 1))
$interstitial_support = 1;

if($skin_enabled == 1 && ($cpc_enabled == 1 || $cpm_enabled == 1 || $cpa_enabled == 1 || $sponsored_enabled == 1))
$skin_support = 1;

if($pop_enabled == 1 && $cpm_enabled == 1)
$pop_support = 1;

if($directlink_enabled == 1 && ($cpc_enabled == 1 || $cpm_enabled == 1 || $cpa_enabled == 1))
$directlink_support = 1;


$headerText = $adcodeName;

if($nativeads_enabled == 1 && $headerText == '')
$headerText = Configuration::get_instance()->read('nativead_header');

if($directlink_enabled == 1)
{
    $directlink_availability_config = Configuration::get_instance()->read('directlink_availability_for_publishers');
    $directlink_availability_user   = $this->get_variable("directlink_availability");	
}


$maximumAds       = 1;
$maximumRows      = 1;
$maximumColumns   = 1;

if($nativeads_enabled == 1)
{
	$maximumAds       = intval(Configuration::get_instance()->read("maximum_ads_allowed"));
	$maximumRows      = intval(Configuration::get_instance()->read("maximum_rows_allowed"));
	$maximumColumns   = intval(Configuration::get_instance()->read("maximum_columns_allowed"));
}


if($maximumAds == 0)
$maximumAds = 1;

if($maximumRows == 0)
$maximumRows = 1;

if($maximumColumns == 0)
$maximumColumns = 1;

$textimage_size          = $this->get_variable("textimage_size");
$native_ads_count        = $this->get_variable("native_ads_count");
$native_ads_rows_count   = $this->get_variable("native_ads_rows_count");
$native_ads_column_count = $this->get_variable("native_ads_column_count");
$native_layout_id        = $this->get_variable("native_layout_id");

$pop_type                = $this->get_variable('pop_type');
$linear_support 		 = $this->get_variable('linear_support');
$nonlinear_support 		 = $this->get_variable('nonlinear_support');
$html5_player_support 	 = $this->get_variable('html5_player_support');
$vast_adcode_enabled 	 = intval($this->get_variable('vast_adcode_enabled'));


if($video_enabled == 1)
$res13_linear = $this->get_result('res13_linear');

$adcode_for 		= $this->get_variable('adcode_for');
$linear 			= $this->get_variable('linear');
$nonlinearbanner 	= $this->get_variable('nonlinearbanner');
$nonlineartext 		= $this->get_variable('nonlineartext');
$nonlinear_size 	= $this->get_variable('nonlinear_size');


if($text_ads_enabled == 0 && $nonlineartext == 1)
$nonlineartext = 0;

$custom_code               = $this->get_variable('custom_code');
$native_responsive_support = $this->get_variable("native_responsive_support");

if($custom_code == '')
{
	$custom_code = '<style type="text/css">
/* Enter your CSS here */
</style>';
}

$resLayout          = $this->get_result('resLayout');
$resFontExternal    = $this->get_result("resFontExternal");

?>
<style type="text/css">

.common-pricing, .cpm-pricing, .cpa-pricing
{
	display: none;
}


<?php foreach($resFontExternal as $fkey=>$fvalue){?>
@font-face {
  font-family: <?php echo $fvalue['slug_name'];?>;
  src: url(<?php echo BASE.DATA_DIR.'/fonts/'.$fvalue['file_name'];?>);
}
<?php } ?>
</style>


<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 p-3 mb-3 adcode-create">
	<h2 class="page-heading "><div class="page-inner"><i class="fa fa-pencil-square-o icon_red"></i><?php echo $this->get_label('create new adunit');?></div></h2>

<?php
$form=$this->create_form();
$form->start("createadunit",$this->make_url("adunit/create"),"post");
?>
	<div class="row">
      <?php if($nativeads_enabled == 1 || $feedads_enabled == 1){?>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3">
        	<label for="ad_display" class="form-label"><?php echo $this->get_label('ad display');?></label>

          <select class="form-select" aria-label="ad display" name="ad_display" id="ad_display" onchange="javascript:return ChangeAdDisplay();">
          <option value="0" <?php if($ad_display == 0){?>selected <?php }?>><?php echo $this->get_label('predefined');?></option>

          <?php if($nativeads_enabled == 1){?>
          <option value="1" <?php if($ad_display == 1){?>selected <?php }?>><?php echo $this->get_label('native');?></option>
          <?php } ?>

          <?php if($feedads_enabled == 1){?>
          <option value="2" <?php if($ad_display == 2){?>selected <?php }?>><?php echo $this->get_label('feed');?></option>
          <?php } ?>
          </select>
        </div>
      <?php } else { ?>
      <span><input type="hidden" name="ad_display" id="ad_display" value="0" /></span>
      <?php } ?>

	  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3">
	 	<label class="form-label adunitName"><?php echo $this->get_label('adunit name');?> <span class="compulsory">*</span></label>
	  	<input class="form-control" type="text" name="name" id="name" value="<?php echo $adcodeName;?>" maxlength="25" />
	  </div>

	  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3 displayAdcode">
		<label class="form-label"><?php echo $this->get_label('adunit type');?></label>

		<select class="form-select" aria-label="adblock type" name="adblocktype" id="adblocktype" onchange="LoadAdcodePricing();">

		<?php if($text_support ==1){?>
		<option value="1" <?php if($this->get_variable("adblocktype")==1) {echo "selected";}?>><?php echo $this->get_label('text only');?></option>
		<?php }?>

		<?php if($banner_support ==1){?>
		<option value="2" <?php if($this->get_variable("adblocktype")==2) {echo "selected";}?>><?php echo $this->get_label('banner only');?></option>
		<?php }?>

		<?php if($textimage_support ==1){?>
		<option value="11" <?php if($this->get_variable("adblocktype")==11) {echo "selected";}?>><?php echo $this->get_label('textimage only');?></option>
		<?php }?>

		<?php if($text_support ==1 && $textimage_support == 1){?>
		<option value="3" <?php if($this->get_variable("adblocktype")==3) {echo "selected";}?>><?php echo $this->get_label('textbanner textimage');?></option>
		<?php }else if($text_support ==1){?>
		<option value="3" <?php if($this->get_variable("adblocktype")==3) {echo "selected";}?>><?php echo $this->get_label('textbanner');?></option>
		<?php }else if($textimage_support == 1){?>
		<option value="3" <?php if($this->get_variable("adblocktype")==3) {echo "selected";}?>><?php echo $this->get_label('banner textimage');?></option>
		<?php }?>


		<?php if($skin_support ==1){?>
		<option value="14" <?php if($this->get_variable("adblocktype")==14) {echo "selected";}?>><?php echo $this->get_label('skin');?></option>
		<?php }?>

		<?php if($pop_support ==1){?>
		<option value="9" <?php if($this->get_variable("adblocktype")==9) {echo "selected";}?>><?php echo $this->get_label('pop');?></option>
		<?php }?>

		<?php if($directlink_support ==1 && (($directlink_availability_config == 1 && $directlink_availability_user == 2) || $directlink_availability_user == 1)){?>
		<option value="21" <?php if($this->get_variable("adblocktype")==21) {echo "selected";}?>><?php echo $this->get_label('direct link');?></option>
		<?php }?>

		<?php if($video_enabled ==1){?>
		<option value="13" <?php if($this->get_variable("adblocktype")==13) {echo "selected";}?>><?php echo $this->get_label('video');?></option>
		<?php }?>
		</select>
	  </div>
	<?php if($interstitial_enabled ==1 || $inpage_push_enabled == 1){?>
		<div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3 displayAdcode adblockFormatType">
	 		<label class="form-label"><?php echo $this->get_label('adcode format');?></label>
			<select class="form-select" aria-label="adblock type" name="adblockFormatType" id="adblockFormatType" onchange="LoadAdcodePricing();">
			 	<option value="0" <?php if($this->get_variable("adblockFormatType")==0) {echo "selected";}?>><?php echo $this->get_label('normal adcode');?></option>
		 	
				<?php if($interstitial_enabled == 1){?>
					<option value="1" <?php if($this->get_variable("adblockFormatType")==1) {echo "selected";}?>><?php echo $this->get_label('interstitial adcode');?></option>
				<?php }?>
			
				<?php if($inpage_push_enabled == 1){?>
					<option class="inpagePush" style="display:none;" value="2" <?php if($this->get_variable("adblockFormatType") == 2) {echo "selected";}?>><?php echo $this->get_label('inpage push adcode');?></option>
				<?php }?>		
			</select>
		</div>
	<?php }else{ ?>
		<input type="hidden" name="adblockFormatType" id="adblockFormatType" value="0" />
	<?php } ?>	

	  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3 displayAdcode">
		<label class="form-label"><?php echo $this->get_label('adcode pricing');?></label>
		<?php echo $this->get_pricing_box($adpricing, 2);?>
	  </div>

	  <?php if($category_enabled ==1){?>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3 site-class" style="display: none;">
            <label class="form-label"><?php echo $this->get_label('targeting site');?> <span class="compulsory">*</span></label>

            <span class="sid_span sid_00" style="display: none;"><?php echo CategoryHelper::get_site_dropdown($uid,$sid,1); //sid ?></span>

            <?php if($sponsored_enabled ==1){?>
            <span class="sid_span sid_01" style="display: none;">
            <?php echo CategoryHelper::get_site_dropdown($uid,$sid,1,1); //sid_cpd ?>
            </span>
            <?php }?>
        </div>
      <?php }?>

	  <div class="form-group video-option col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3 displayAdcode" style="display: none;">
	 	<label class="form-label"><?php echo $this->get_label('adcode for');?> <span class="compulsory">*</span></label>

		<select name="adcode_for" id="adcode_for" class="form-select" aria-label="adcode for" onchange="<?php if($category_enabled ==1){?>LoadSiteData();<?php }?>LoadVideoOptions();LoadStickySettings();LoadResponsiveSettings();">
			<option value="0"><bdi><?php echo $this->get_label('select');?></bdi></option>

			<?php if($vast_adcode_enabled ==1 && ($linear_support ==1 || $nonlinear_support ==1)){?>
			<option value="1" <?php if($adcode_for ==1){?> selected="selected" <?php }?>><?php echo $this->get_label('vast player');?></option>
			<?php }?>
			<?php if($html5_player_support ==1){?>
			<option value="2" <?php if($adcode_for ==2){?> selected="selected" <?php }?>><?php echo $this->get_label('html5 player');?></option>
			<?php }?>
		</select>
	</div>


	<?php if($html5_player_support ==1){?>
	<div class="form-group video-option-html5 col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3 displayAdcode" style="display: none;">
	<label class="form-label"><?php echo $this->get_label('player dimension');?></label>

		<select class="form-select" aria-label="player size" name="player_size" id="player_size" <?php if($responsive_enabled == 1){?>onchange="LoadResponsiveSettings();"<?php }?>>
		<?php
		$res13=$this->get_result('res13');

		foreach($res13 as $key=>$result1)
		{
			$height=$result1['height'];
			$width=$result1['width'];
			$name=$this->escape($result1['name']);
			$id=$result1['id'];
			$diamensions=$result1['width']." x ".$result1['height'];

			$largeDeviceBlockID      = $result1['large_dev_adblock'];
			$mediumDeviceBlockID     = $result1['medium_dev_adblock'];
			$smallDeviceBlockID      = $result1['small_dev_adblock'];
			$extrasmallDeviceBlockID = $result1['xsmall_dev_adblock'];
		?>
		<option value="<?php echo $id;?>" <?php if($adbid == $id) { echo "selected"; }?> <?php if($responsive_enabled == 1){?> data-lg="<?php echo $largeDeviceBlockID;?>" data-md="<?php echo $mediumDeviceBlockID;?>" data-sm="<?php echo $smallDeviceBlockID;?>" data-xs="<?php echo $extrasmallDeviceBlockID;?>" <?php }?>><?php echo $name." (".$diamensions.") "; ?></option>
		<?php }?>
		</select>
	</div>
	<?php }?>

	<?php if($vast_adcode_enabled ==1 && ($linear_support ==1 || $nonlinear_support ==1)){?>
	<div class="form-group video-option-vast col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3 displayAdcode" style="display: none;">
	<label class="form-label"><?php echo $this->get_label('supported type');?> <span class="compulsory">*</span></label>

		<div class="mt-2">
			<?php if($linear_support ==1){?>
			<span class="mb-3 me-2">
				<input class="form-check-input" disabled="disabled" type="checkbox" name="linear" id="linear" value="1" checked="checked" />
				<label class="form-check-label"><?php echo $this->get_label('linear');?></label>
			</span>
			<?php }?>

			<?php if($nonlinear_support ==1){?>

					<?php if($text_ads_enabled ==1){?>
					<span class="mb-3 me-2">
						<input class="form-check-input" type="checkbox" name="nonlineartext" id="nonlineartext" value="1" <?php if($nonlineartext ==1){?>checked="checked"<?php }?> />
						<label class="form-check-label"><?php echo $this->get_label('non linear text');?></label>
					</span>
					<?php }?>

					<?php if(count($res13_linear) >0){?>
					<span class="mb-3 me-2">
						<input class="form-check-input" type="checkbox" name="nonlinearbanner" id="nonlinearbanner" value="1" <?php if($nonlinearbanner ==1){?>checked="checked"<?php }?> onclick="LoadVideoOptions();" />
						<label class="form-check-label"><?php echo $this->get_label('non linear banner');?></label>
					</span>
					<?php }?>

			<?php }?>
			</div>
	</div>
	<?php }?>



	<?php if($nonlinear_support ==1 && count($res13_linear) >0){?>
	<div class="form-group video-option-vast-size col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3 displayAdcode" style="display: none;">
	<label class="form-label"><?php echo $this->get_label('banner dimension');?></label>

		<select class="form-select" aria-label="nonlinear size" name="nonlinear_size" id="nonlinear_size">
		<?php
		foreach($res13_linear as $key=>$result14)
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

	<?php if($pop_enabled ==1){

		$pop_ads_support=Configuration::get_instance()->read('pop_ads_support');

		$pop_array=explode('-',$pop_ads_support);

		$pop_up=intval($pop_array[0]);
		$pop_tab=intval($pop_array[1]);
	?>
	<div class="form-group pop-class col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3 displayAdcode" style="display: none;">
		<label class="form-label"><?php echo $this->get_label('pop type');?> <span class="compulsory">*</span></label>
		<select class="form-select" name="pop_type" id="pop_type">
			<?php if($pop_tab == 1){?>
				<option value="0" <?php if($pop_type == 0) {echo "selected";}?>><?php echo $this->get_label('poptab');?></option>
			<?php }?>

			<?php if($pop_up == 1){?>
				<option value="1" <?php if($pop_type == 1) {echo "selected";}?>><?php echo $this->get_label('popup');?></option>
			<?php }?>
		</select>
	</div>
	<?php }?>


	<div class="form-group adblock-size col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3 displayAdcode">
	<label class="form-label"><?php echo $this->get_label('select adblock');?></label>

	<span class="banner-select" id="banner-select-1" style="display: none;">
	<select class="form-select" aria-label="banner select 1" name="blockid_1" id="blockid_1" <?php if($responsive_enabled == 1){?>onchange="LoadResponsiveSettings();"<?php }?>>
	<?php
	$res1=$this->get_result('res1');

	foreach($res1 as $key=>$result1)
	{
		$height=$result1['height'];
		$width=$result1['width'];
		$name=$this->escape($result1['name']);
		$id=$result1['id'];
		$diamensions=$result1['width']." x ".$result1['height'];

		$largeDeviceBlockID      = $result1['large_dev_adblock'];
		$mediumDeviceBlockID     = $result1['medium_dev_adblock'];
		$smallDeviceBlockID      = $result1['small_dev_adblock'];
		$extrasmallDeviceBlockID = $result1['xsmall_dev_adblock'];
	?>
	<option value="<?php echo $id;?>" <?php if($adbid == $id) { echo "selected"; }?> <?php if($responsive_enabled == 1){?> data-lg="<?php echo $largeDeviceBlockID;?>" data-md="<?php echo $mediumDeviceBlockID;?>" data-sm="<?php echo $smallDeviceBlockID;?>" data-xs="<?php echo $extrasmallDeviceBlockID;?>" <?php }?>><?php echo $name." (".$diamensions.") "; ?></option>
	<?php }?>
	</select>
	</span>
<span class="banner-select" id="banner-select-interstitial-1" style="display: none;">
<select class="form-select" aria-label="banner select 1" name="blockid_interstitial_1" id="blockid_interstitial_1" <?php if($responsive_enabled == 1){?>onchange="LoadResponsiveSettings();"<?php }?>>
<?php
$resInterstitial1=$this->get_result('resInterstitial1');
foreach($resInterstitial1 as $key=>$result1)
{
	$height=$result1['height'];
	$width=$result1['width'];
	$name=$this->escape($result1['name']);
	$id=$result1['id'];
	$diamensions=$result1['width']." x ".$result1['height'];
	$largeDeviceBlockID      = $result1['large_dev_adblock'];
	$mediumDeviceBlockID     = $result1['medium_dev_adblock'];
	$smallDeviceBlockID      = $result1['small_dev_adblock'];
	$extrasmallDeviceBlockID = $result1['xsmall_dev_adblock'];
?>
<option value="<?php echo $id;?>" <?php if($adbid == $id) { echo "selected"; }?> <?php if($responsive_enabled == 1){?> data-lg="<?php echo $largeDeviceBlockID;?>" data-md="<?php echo $mediumDeviceBlockID;?>" data-sm="<?php echo $smallDeviceBlockID;?>" data-xs="<?php echo $extrasmallDeviceBlockID;?>" <?php }?>><?php echo $name." (".$diamensions.") "; ?></option>
<?php }?>
</select>
</span>
<?php if($inpage_push_enabled == 1){?>
	<span class="banner-select" id="banner-select-inpagepush-1" style="display: none;"> 
		<select class="form-select" aria-label="banner select 1" name="blockid_inpagepush_1" id="blockid_inpagepush_1" <?php if($responsive_enabled == 1){?>
			onchange="LoadResponsiveSettings();" <?php }?>>
		<?php
		$resInpagePush1=$this->get_result('resInpagePush1');
		foreach($resInpagePush1 as $key=>$result1)
		{
			$height 				 = $result1['height'];
			$width 					 = $result1['width'];
			$name 					 = $this->escape($result1['name']);
			$id 					 = $result1['id'];
			$diamensions 			 = $result1['width']." x ".$result1['height'];
			$largeDeviceBlockID      = $result1['large_dev_adblock'];
			$mediumDeviceBlockID     = $result1['medium_dev_adblock'];
			$smallDeviceBlockID      = $result1['small_dev_adblock'];
			$extrasmallDeviceBlockID = $result1['xsmall_dev_adblock'];					
			?>
			<option value="<?php echo $id;?>" <?php if($adbid == $id) { echo "selected"; }?>
			<?php if($responsive_enabled == 1){?> data-lg="<?php echo $largeDeviceBlockID;?>" data-md="<?php echo $mediumDeviceBlockID;?>" data-sm="<?php echo $smallDeviceBlockID;?>" data-xs="<?php echo $extrasmallDeviceBlockID;?>" <?php }?>><?php echo $name." (".$diamensions.") "; ?></option>
		<?php 
		}
		?>
		</select> 
	</span> 
<?php } ?>		


	<span class="banner-select" id="banner-select-2" style="display: none;">
	<select class="form-select" aria-label="banner select 2" name="blockid_2" id="blockid_2" <?php if($responsive_enabled == 1){?>onchange="LoadResponsiveSettings();"<?php }?>>
	<?php
	$res2=$this->get_result('res2');

	foreach($res2 as $key=>$result1)
	{
		$height=$result1['height'];
		$width=$result1['width'];
		$name=$this->escape($result1['name']);
		$id=$result1['id'];
		$diamensions=$result1['width']." x ".$result1['height'];

		$largeDeviceBlockID      = $result1['large_dev_adblock'];
		$mediumDeviceBlockID     = $result1['medium_dev_adblock'];
		$smallDeviceBlockID      = $result1['small_dev_adblock'];
		$extrasmallDeviceBlockID = $result1['xsmall_dev_adblock'];
	?>
	<option value="<?php echo $id;?>" <?php if($adbid == $id) { echo "selected"; }?> <?php if($responsive_enabled == 1){?> data-lg="<?php echo $largeDeviceBlockID;?>" data-md="<?php echo $mediumDeviceBlockID;?>" data-sm="<?php echo $smallDeviceBlockID;?>" data-xs="<?php echo $extrasmallDeviceBlockID;?>" <?php }?>><?php echo $name." (".$diamensions.") "; ?></option>
	<?php }?>
	</select>
	</span>

<span class="banner-select" id="banner-select-interstitial-2" style="display: none;">
<select class="form-select" aria-label="banner select 2" name="blockid_interstitial_2" id="blockid_interstitial_2" <?php if($responsive_enabled == 1){?>onchange="LoadResponsiveSettings();"<?php }?>>
<?php
$resInterstitial2=$this->get_result('resInterstitial2');
foreach($resInterstitial2 as $key=>$result1)
{
	$height=$result1['height'];
	$width=$result1['width'];
	$name=$this->escape($result1['name']);
	$id=$result1['id'];
	$diamensions=$result1['width']." x ".$result1['height'];
	$largeDeviceBlockID      = $result1['large_dev_adblock'];
	$mediumDeviceBlockID     = $result1['medium_dev_adblock'];
	$smallDeviceBlockID      = $result1['small_dev_adblock'];
	$extrasmallDeviceBlockID = $result1['xsmall_dev_adblock'];
?>
<option value="<?php echo $id;?>" <?php if($adbid == $id) { echo "selected"; }?> <?php if($responsive_enabled == 1){?> data-lg="<?php echo $largeDeviceBlockID;?>" data-md="<?php echo $mediumDeviceBlockID;?>" data-sm="<?php echo $smallDeviceBlockID;?>" data-xs="<?php echo $extrasmallDeviceBlockID;?>" <?php }?>><?php echo $name." (".$diamensions.") "; ?></option>
<?php }?>
</select>
</span>

	<span class="banner-select" id="banner-select-3" style="display: none;">
	<select class="form-select" aria-label="banner select 3" name="blockid_3" id="blockid_3" <?php if($responsive_enabled == 1){?>onchange="LoadResponsiveSettings();"<?php }?>>
	<?php
	$res3=$this->get_result('res3');

	foreach($res3 as $key=>$result1)
	{
		$height=$result1['height'];
		$width=$result1['width'];
		$name=$this->escape($result1['name']);
		$id=$result1['id'];
		$diamensions=$result1['width']." x ".$result1['height'];

		$largeDeviceBlockID      = $result1['large_dev_adblock'];
		$mediumDeviceBlockID     = $result1['medium_dev_adblock'];
		$smallDeviceBlockID      = $result1['small_dev_adblock'];
		$extrasmallDeviceBlockID = $result1['xsmall_dev_adblock'];
	?>
	<option value="<?php echo $id;?>" <?php if($adbid == $id) { echo "selected"; }?> <?php if($responsive_enabled == 1){?> data-lg="<?php echo $largeDeviceBlockID;?>" data-md="<?php echo $mediumDeviceBlockID;?>" data-sm="<?php echo $smallDeviceBlockID;?>" data-xs="<?php echo $extrasmallDeviceBlockID;?>" <?php }?>><?php echo $name." (".$diamensions.") "; ?></option>
	<?php }?>
	</select>
	</span>

<span class="banner-select" id="banner-select-interstitial-3" style="display: none;">
<select class="form-select" aria-label="banner select 3" name="blockid_interstitial_3" id="blockid_interstitial_3" <?php if($responsive_enabled == 1){?>onchange="LoadResponsiveSettings();"<?php }?>>
	<?php
$resInterstitial3=$this->get_result('resInterstitial3');

foreach($resInterstitial3 as $key=>$result1)
	{
		$height=$result1['height'];
		$width=$result1['width'];
		$name=$this->escape($result1['name']);
		$id=$result1['id'];
		$diamensions=$result1['width']." x ".$result1['height'];

		$largeDeviceBlockID      = $result1['large_dev_adblock'];
		$mediumDeviceBlockID     = $result1['medium_dev_adblock'];
		$smallDeviceBlockID      = $result1['small_dev_adblock'];
		$extrasmallDeviceBlockID = $result1['xsmall_dev_adblock'];
	?>
	<option value="<?php echo $id;?>" <?php if($adbid == $id) { echo "selected"; }?> <?php if($responsive_enabled == 1){?> data-lg="<?php echo $largeDeviceBlockID;?>" data-md="<?php echo $mediumDeviceBlockID;?>" data-sm="<?php echo $smallDeviceBlockID;?>" data-xs="<?php echo $extrasmallDeviceBlockID;?>" <?php }?>><?php echo $name." (".$diamensions.") "; ?></option>
	<?php }?>
	</select>
	</span>

<?php if($inpage_push_enabled == 1){?>
		<span class="banner-select" id="banner-select-inpagepush-3" style="display: none;"> 
			<select class="form-select" aria-label="banner select 3" name="blockid_inpagepush_3" id="blockid_inpagepush_3" <?php if($responsive_enabled == 1){?>
				onchange="LoadResponsiveSettings();" <?php }?>>
			<?php
			$resInpagePush3=$this->get_result('resInpagePush3');

			foreach($resInpagePush3 as $key=>$result1)
			{
				$height 				 = $result1['height'];
				$width 					 = $result1['width'];
				$name 					 = $this->escape($result1['name']);
				$id 					 = $result1['id'];
				$diamensions 			 = $result1['width']." x ".$result1['height'];
				$largeDeviceBlockID      = $result1['large_dev_adblock'];
				$mediumDeviceBlockID     = $result1['medium_dev_adblock'];
				$smallDeviceBlockID      = $result1['small_dev_adblock'];
				$extrasmallDeviceBlockID = $result1['xsmall_dev_adblock'];					
				?>
				<option value="<?php echo $id;?>" <?php if($adbid == $id) { echo "selected"; }?>
				<?php if($responsive_enabled == 1){?> data-lg="<?php echo $largeDeviceBlockID;?>" data-md="<?php echo $mediumDeviceBlockID;?>" data-sm="<?php echo $smallDeviceBlockID;?>" data-xs="<?php echo $extrasmallDeviceBlockID;?>" <?php }?>><?php echo $name." (".$diamensions.") "; ?></option>
			<?php 
			}
			?>
			</select> 
		</span> 	
<?php } ?>
	<span class="banner-select" id="banner-select-11" style="display: none;">
	<select class="form-select" aria-label="banner select 11" name="blockid_11" id="blockid_11" <?php if($responsive_enabled == 1){?>onchange="LoadResponsiveSettings();"<?php }?>>
	<?php
	$res11=$this->get_result('res11');

	foreach($res11 as $key=>$result1)
	{
		$height=$result1['height'];
		$width=$result1['width'];
		$name=$this->escape($result1['name']);
		$id=$result1['id'];
		$diamensions=$result1['width']." x ".$result1['height'];

		$largeDeviceBlockID      = $result1['large_dev_adblock'];
		$mediumDeviceBlockID     = $result1['medium_dev_adblock'];
		$smallDeviceBlockID      = $result1['small_dev_adblock'];
		$extrasmallDeviceBlockID = $result1['xsmall_dev_adblock'];
	?>
	<option value="<?php echo $id;?>" <?php if($adbid == $id) { echo "selected"; }?> <?php if($responsive_enabled == 1){?> data-lg="<?php echo $largeDeviceBlockID;?>" data-md="<?php echo $mediumDeviceBlockID;?>" data-sm="<?php echo $smallDeviceBlockID;?>" data-xs="<?php echo $extrasmallDeviceBlockID;?>" <?php }?>><?php echo $name." (".$diamensions.") "; ?></option>
	<?php }?>
	</select>
	</span>

<span class="banner-select" id="banner-select-interstitial-11" style="display: none;">
<select class="form-select" aria-label="banner select 11" name="blockid_interstitial_11" id="blockid_interstitial_11" <?php if($responsive_enabled == 1){?>onchange="LoadResponsiveSettings();"<?php }?>>
<?php
$resInterstitial11=$this->get_result('resInterstitial11');

foreach($resInterstitial11 as $key=>$result1)
{
	$height=$result1['height'];
	$width=$result1['width'];
	$name=$this->escape($result1['name']);
	$id=$result1['id'];
	$diamensions=$result1['width']." x ".$result1['height'];

	$largeDeviceBlockID      = $result1['large_dev_adblock'];
	$mediumDeviceBlockID     = $result1['medium_dev_adblock'];
	$smallDeviceBlockID      = $result1['small_dev_adblock'];
	$extrasmallDeviceBlockID = $result1['xsmall_dev_adblock'];
?>
<option value="<?php echo $id;?>" <?php if($adbid == $id) { echo "selected"; }?> <?php if($responsive_enabled == 1){?> data-lg="<?php echo $largeDeviceBlockID;?>" data-md="<?php echo $mediumDeviceBlockID;?>" data-sm="<?php echo $smallDeviceBlockID;?>" data-xs="<?php echo $extrasmallDeviceBlockID;?>" <?php }?>><?php echo $name." (".$diamensions.") "; ?></option>
<?php }?>
</select>
</span>
<?php if($inpage_push_enabled == 1){?>
	<span class="banner-select" id="banner-select-inpagepush-11" style="display: none;"> 
		<select class="form-select" aria-label="banner select 11" name="blockid_inpagepush_11" id="blockid_inpagepush_11" <?php if($responsive_enabled == 1){?>
			onchange="LoadResponsiveSettings();" <?php }?>>
		<?php
		$resInpagePush11=$this->get_result('resInpagePush11');
		foreach($resInpagePush11 as $key=>$result1)
		{
			$height 				 = $result1['height'];
			$width 					 = $result1['width'];
			$name 					 = $this->escape($result1['name']);
			$id 					 = $result1['id'];
			$diamensions 			 = $result1['width']." x ".$result1['height'];
			$largeDeviceBlockID      = $result1['large_dev_adblock'];
			$mediumDeviceBlockID     = $result1['medium_dev_adblock'];
			$smallDeviceBlockID      = $result1['small_dev_adblock'];
			$extrasmallDeviceBlockID = $result1['xsmall_dev_adblock'];				
			?>
		<option value="<?php echo $id;?>" <?php if($adbid == $id) { echo "selected"; }?>
		<?php if($responsive_enabled == 1){?> data-lg="<?php echo $largeDeviceBlockID;?>" data-md="<?php echo $mediumDeviceBlockID;?>" data-sm="<?php echo $smallDeviceBlockID;?>" data-xs="<?php echo $extrasmallDeviceBlockID;?>" <?php }?>><?php echo $name." (".$diamensions.") "; ?></option>
		<?php 
		}
		?>
		</select> 
	</span> 	
<?php } ?>
	<?php if($skin_enabled ==1){?>
	<span class="banner-select" id="banner-select-14" style="display: none;">
	<select class="form-select" aria-label="banner select 14" name="blockid_14" id="blockid_14" onchange="Display_skin_prev();">
	<?php

	$res14=$this->get_result('res14');

	$exist_positions=array();
	$skin_preview="";


	foreach($res14 as $key=>$result1)
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

	<?php if($skin_enabled == 1){?>
	<div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3 displayAdcode" id="skin_pos" style="display:none;">
		<label class="form-label"><?php echo $this->get_label('main container id'); ?> <span class="compulsory">*</span></label>
		<input class="form-control" type="text" name="container_id" id="container_id" />
		<div class="notification"><?php echo $this->get_label('main container id description'); ?></div>
	</div>
	<?php }?>


	<?php
	if($sticky_enabled ==1)
	{
		$sticky_support=$this->get_variable('sticky_support');
		$sticky_position=$this->get_variable('sticky_position');
		$sticky_close_position=$this->get_variable('sticky_close_position');

		$sticky_supported_positions=Configuration::get_instance()->read('sticky_supported_positions');

		$position_array=json_decode($sticky_supported_positions,1);


		?>
	<div class="form-group sticky_support col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3 displayAdcode" id="sticky_support_div" style="display:none;">
		<label class="form-label"><?php echo $this->get_label('sticky adcode'); ?></label>
		<select class="form-select" aria-label="sticky adcode" name="sticky_support" id="sticky_support" onclick="LoadStickySettings();">
		<option value="0" <?php if($sticky_support == 0){?> selected="selected" <?php }?>><?php echo $this->get_label('no');?></option>
		<option value="1" <?php if($sticky_support == 1){?> selected="selected" <?php }?>><?php echo $this->get_label('yes');?></option>
		</select>
	</div>

	<div class="form-group sticky_support col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3 displayAdcode" id="sticky_position_div" style="display:none;">
		<label class="form-label"><?php echo $this->get_label('sticky position'); ?></label>
		<select class="form-select" aria-label="sticky position" name="sticky_position">
		<?php
		foreach($position_array as $poskey=>$posvalue)
		{
		if($posvalue ==1){?>
		<option value="<?php echo $poskey;?>" <?php if($sticky_position == $poskey){?> selected="selected" <?php }?>><?php echo $this->get_label(strtolower($poskey));?></option>
		<?php
		}
		}
		?>
		</select>
	</div>


	<div class="form-group sticky_support col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3 displayAdcode" id="sticky_close_div" style="display:none;">
		<label class="form-label"><?php echo $this->get_label('sticky close position'); ?></label>
		<select class="form-select" aria-label="sticky close position" name="sticky_close_position">
		<option value="0" <?php if($sticky_close_position == 0){?> selected="selected" <?php }?>><?php echo $this->get_label('no');?></option>
		<option value="1" <?php if($sticky_close_position == 1){?> selected="selected" <?php }?>><?php echo $this->get_label('yes');?></option>
		</select>
	</div>
	<?php }?>


	<?php if($responsive_enabled == 1){?>
	<div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3 displayAdcode" id="responsive_support_div" style="display: none;">
	<label class="form-label"><?php echo $this->get_label('responsive'); ?></label>

		<select class="form-select" aria-label="responsive" name="responsive_support" id="responsive_support" onchange="LoadResponsiveSettings();">
		<option value="0" <?php if($responsive_support == 0){?> selected="selected" <?php }?>><?php echo $this->get_label('no');?></option>
		<option value="1" <?php if($responsive_support == 1){?> selected="selected" <?php }?>><?php echo $this->get_label('yes');?></option>
		</select>
	</div>

	<?php
	foreach($res1 as $key=>$result1)
	{
		$adblockID             	 = $result1['id'];
		$largeDeviceBlockID      = $result1['large_dev_adblock'];
		$mediumDeviceBlockID     = $result1['medium_dev_adblock'];
		$smallDeviceBlockID      = $result1['small_dev_adblock'];
		$extrasmallDeviceBlockID = $result1['xsmall_dev_adblock'];


		if($largeDeviceBlockID > 0 || $mediumDeviceBlockID > 0 || $smallDeviceBlockID > 0 || $extrasmallDeviceBlockID > 0)
		{
		?>
			<div class="res_block form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 mb-3 displayAdcode" id="res_<?php echo $adblockID;?>" style="display:none;">
				<label class="form-label"><?php echo $this->get_label('responsive adblock dimensions'); ?></label>

				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3">
						<bdi><?php echo $this->get_label('large'); ?> : <?php if($largeDeviceBlockID > 0) echo $this->get_adblock_name($result1['large_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?></bdi>
					</div>

					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3">
						<bdi><?php echo $this->get_label('medium'); ?> : <?php if($mediumDeviceBlockID > 0) echo $this->get_adblock_name($result1['medium_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?></bdi>
					</div>

					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3">
						<bdi><?php echo $this->get_label('small'); ?> : <?php if($smallDeviceBlockID > 0) echo $this->get_adblock_name($result1['small_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?></bdi>
					</div>

					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3">
						<bdi><?php echo $this->get_label('xtra small'); ?> : <?php if($extrasmallDeviceBlockID > 0) echo $this->get_adblock_name($result1['xsmall_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?></bdi>
					</div>
				</div>
			</div>
		<?php
		}
	}
	?>

<?php
foreach($resInterstitial1 as $key=>$result1)
{
	$adblockID             	 = $result1['id'];
	$largeDeviceBlockID      = $result1['large_dev_adblock'];
	$mediumDeviceBlockID     = $result1['medium_dev_adblock'];
	$smallDeviceBlockID      = $result1['small_dev_adblock'];
	$extrasmallDeviceBlockID = $result1['xsmall_dev_adblock'];
	if($largeDeviceBlockID > 0 || $mediumDeviceBlockID > 0 || $smallDeviceBlockID > 0 || $extrasmallDeviceBlockID > 0)
	{
	?>
		<div class="res_block form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 mb-3" id="res_<?php echo $adblockID;?>" style="display:none;">
		 	<label for="responsiveAdblockDimension" class="form-label"><?php echo $this->get_label('responsive adblock dimensions'); ?></label>
			<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3">
					 <bdi><?php echo $this->get_label('large'); ?> : <?php if($largeDeviceBlockID > 0) echo $this->get_adblock_name($result1['large_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?></bdi>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3">
					<bdi><?php echo $this->get_label('medium'); ?> : <?php if($mediumDeviceBlockID > 0) echo $this->get_adblock_name($result1['medium_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?></bdi>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3">
					 <bdi><?php echo $this->get_label('small'); ?> : <?php if($smallDeviceBlockID > 0) echo $this->get_adblock_name($result1['small_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?></bdi>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3">
					 <bdi><?php echo $this->get_label('xtra small'); ?> : <?php if($extrasmallDeviceBlockID > 0) echo $this->get_adblock_name($result1['xsmall_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?></bdi>
				</div>
			</div>
		</div>
	<?php
	}
}
?>

	<?php
	foreach($res2 as $key=>$result1)
	{
		$adblockID             	 = $result1['id'];
		$largeDeviceBlockID      = $result1['large_dev_adblock'];
		$mediumDeviceBlockID     = $result1['medium_dev_adblock'];
		$smallDeviceBlockID      = $result1['small_dev_adblock'];
		$extrasmallDeviceBlockID = $result1['xsmall_dev_adblock'];


		if($largeDeviceBlockID > 0 || $mediumDeviceBlockID > 0 || $smallDeviceBlockID > 0 || $extrasmallDeviceBlockID > 0)
		{
		?>
			<div class="res_block form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 mb-3 displayAdcode" id="res_<?php echo $adblockID;?>" style="display:none;">
				<label class="form-label"><?php echo $this->get_label('responsive adblock dimensions'); ?></label>

				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3">
						<bdi><?php echo $this->get_label('large'); ?> : <?php if($largeDeviceBlockID > 0) echo $this->get_adblock_name($result1['large_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?></bdi>
					</div>

					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3">
						<bdi><?php echo $this->get_label('medium'); ?> : <?php if($mediumDeviceBlockID > 0) echo $this->get_adblock_name($result1['medium_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?></bdi>
					</div>

					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3">
						<bdi><?php echo $this->get_label('small'); ?> : <?php if($smallDeviceBlockID > 0) echo $this->get_adblock_name($result1['small_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?></bdi>
					</div>

					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3">
						<bdi><?php echo $this->get_label('xtra small'); ?> : <?php if($extrasmallDeviceBlockID > 0) echo $this->get_adblock_name($result1['xsmall_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?></bdi>
					</div>
				</div>
			</div>
		<?php
		}
	}
	?>
<?php
foreach($resInterstitial2 as $key=>$result1)
{
	$adblockID             	 = $result1['id'];
	$largeDeviceBlockID      = $result1['large_dev_adblock'];
	$mediumDeviceBlockID     = $result1['medium_dev_adblock'];
	$smallDeviceBlockID      = $result1['small_dev_adblock'];
	$extrasmallDeviceBlockID = $result1['xsmall_dev_adblock'];


	if($largeDeviceBlockID > 0 || $mediumDeviceBlockID > 0 || $smallDeviceBlockID > 0 || $extrasmallDeviceBlockID > 0)
	{
	?>
		<div class="res_block form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 mb-3" id="res_<?php echo $adblockID;?>" style="display:none;">
			 <label for="responsiveAdblockDimension" class="form-label"><?php echo $this->get_label('responsive adblock dimensions'); ?></label>

			 <div class="row">
 				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3">
 					 <bdi><?php echo $this->get_label('large'); ?> : <?php if($largeDeviceBlockID > 0) echo $this->get_adblock_name($result1['large_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?></bdi>
 				</div>

 				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3">
 					<bdi><?php echo $this->get_label('medium'); ?> : <?php if($mediumDeviceBlockID > 0) echo $this->get_adblock_name($result1['medium_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?></bdi>
 				</div>

 				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3">
 					 <bdi><?php echo $this->get_label('small'); ?> : <?php if($smallDeviceBlockID > 0) echo $this->get_adblock_name($result1['small_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?></bdi>
 				</div>

 				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3">
 					 <bdi><?php echo $this->get_label('xtra small'); ?> : <?php if($extrasmallDeviceBlockID > 0) echo $this->get_adblock_name($result1['xsmall_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?></bdi>
 				</div>
 			</div>
		</div>
	<?php
	}
}
?>
	<?php
	foreach($res3 as $key=>$result1)
	{
		$adblockID             	 = $result1['id'];
		$largeDeviceBlockID      = $result1['large_dev_adblock'];
		$mediumDeviceBlockID     = $result1['medium_dev_adblock'];
		$smallDeviceBlockID      = $result1['small_dev_adblock'];
		$extrasmallDeviceBlockID = $result1['xsmall_dev_adblock'];


		if($largeDeviceBlockID > 0 || $mediumDeviceBlockID > 0 || $smallDeviceBlockID > 0 || $extrasmallDeviceBlockID > 0)
		{
		?>
			<div class="res_block form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 mb-3 displayAdcode" id="res_<?php echo $adblockID;?>" style="display:none;">
				<label class="form-label"><?php echo $this->get_label('responsive adblock dimensions'); ?></label>

				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3">
						<bdi><?php echo $this->get_label('large'); ?> : <?php if($largeDeviceBlockID > 0) echo $this->get_adblock_name($result1['large_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?></bdi>
					</div>

					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3">
						<bdi><?php echo $this->get_label('medium'); ?> : <?php if($mediumDeviceBlockID > 0) echo $this->get_adblock_name($result1['medium_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?></bdi>
					</div>

					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3">
						<bdi><?php echo $this->get_label('small'); ?> : <?php if($smallDeviceBlockID > 0) echo $this->get_adblock_name($result1['small_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?></bdi>
					</div>

					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3">
						<bdi><?php echo $this->get_label('xtra small'); ?> : <?php if($extrasmallDeviceBlockID > 0) echo $this->get_adblock_name($result1['xsmall_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?></bdi>
					</div>
				</div>
			</div>
		<?php
		}
	}
	?>

<?php
foreach($resInterstitial3 as $key=>$result1)
{
	$adblockID             	 = $result1['id'];
	$largeDeviceBlockID      = $result1['large_dev_adblock'];
	$mediumDeviceBlockID     = $result1['medium_dev_adblock'];
	$smallDeviceBlockID      = $result1['small_dev_adblock'];
	$extrasmallDeviceBlockID = $result1['xsmall_dev_adblock'];


	if($largeDeviceBlockID > 0 || $mediumDeviceBlockID > 0 || $smallDeviceBlockID > 0 || $extrasmallDeviceBlockID > 0)
	{
	?>
		<div class="res_block form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 mb-3" id="res_<?php echo $adblockID;?>" style="display:none;">
			 <label for="responsiveAdblockDimension" class="form-label"><?php echo $this->get_label('responsive adblock dimensions'); ?></label>

			 <div class="row">
 				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3">
 					 <bdi><?php echo $this->get_label('large'); ?> : <?php if($largeDeviceBlockID > 0) echo $this->get_adblock_name($result1['large_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?></bdi>
 				</div>

 				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3">
 					<bdi><?php echo $this->get_label('medium'); ?> : <?php if($mediumDeviceBlockID > 0) echo $this->get_adblock_name($result1['medium_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?></bdi>
 				</div>

 				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3">
 					 <bdi><?php echo $this->get_label('small'); ?> : <?php if($smallDeviceBlockID > 0) echo $this->get_adblock_name($result1['small_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?></bdi>
 				</div>

 				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3">
 					 <bdi><?php echo $this->get_label('xtra small'); ?> : <?php if($extrasmallDeviceBlockID > 0) echo $this->get_adblock_name($result1['xsmall_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?></bdi>
 				</div>
 			</div>
		</div>
	<?php
	}
}



	foreach($res11 as $key=>$result1)
	{
		$adblockID             	 = $result1['id'];
		$largeDeviceBlockID      = $result1['large_dev_adblock'];
		$mediumDeviceBlockID     = $result1['medium_dev_adblock'];
		$smallDeviceBlockID      = $result1['small_dev_adblock'];
		$extrasmallDeviceBlockID = $result1['xsmall_dev_adblock'];


		if($largeDeviceBlockID > 0 || $mediumDeviceBlockID > 0 || $smallDeviceBlockID > 0 || $extrasmallDeviceBlockID > 0)
		{
		?>
			<div class="res_block form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 mb-3 displayAdcode" id="res_<?php echo $adblockID;?>" style="display:none;">
			<label class="form-label"><?php echo $this->get_label('responsive adblock dimensions'); ?></label>

			<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3">
						<bdi><?php echo $this->get_label('large'); ?> : <?php if($largeDeviceBlockID > 0) echo $this->get_adblock_name($result1['large_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?></bdi>
				</div>

				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3">
					<bdi><?php echo $this->get_label('medium'); ?> : <?php if($mediumDeviceBlockID > 0) echo $this->get_adblock_name($result1['medium_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?></bdi>
				</div>

				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3">
						<bdi><?php echo $this->get_label('small'); ?> : <?php if($smallDeviceBlockID > 0) echo $this->get_adblock_name($result1['small_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?></bdi>
				</div>

				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3">
						<bdi><?php echo $this->get_label('xtra small'); ?> : <?php if($extrasmallDeviceBlockID > 0) echo $this->get_adblock_name($result1['xsmall_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?></bdi>
				</div>
			</div>

			</div>
		<?php
		}
	}
	?>


<?php
foreach($resInterstitial11 as $key=>$result1)
{
	$adblockID             	 = $result1['id'];
	$largeDeviceBlockID      = $result1['large_dev_adblock'];
	$mediumDeviceBlockID     = $result1['medium_dev_adblock'];
	$smallDeviceBlockID      = $result1['small_dev_adblock'];
	$extrasmallDeviceBlockID = $result1['xsmall_dev_adblock'];
	if($largeDeviceBlockID > 0 || $mediumDeviceBlockID > 0 || $smallDeviceBlockID > 0 || $extrasmallDeviceBlockID > 0)
	{
	?>
		<div class="res_block form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 mb-3" id="res_<?php echo $adblockID;?>" style="display:none;">
		 	<label for="responsiveAdblockDimension" class="form-label"><?php echo $this->get_label('responsive adblock dimensions'); ?></label>
			<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3">
					 <bdi><?php echo $this->get_label('large'); ?> : <?php if($largeDeviceBlockID > 0) echo $this->get_adblock_name($result1['large_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?></bdi>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3">
					<bdi><?php echo $this->get_label('medium'); ?> : <?php if($mediumDeviceBlockID > 0) echo $this->get_adblock_name($result1['medium_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?></bdi>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3">
					 <bdi><?php echo $this->get_label('small'); ?> : <?php if($smallDeviceBlockID > 0) echo $this->get_adblock_name($result1['small_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?></bdi>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3">
					 <bdi><?php echo $this->get_label('xtra small'); ?> : <?php if($extrasmallDeviceBlockID > 0) echo $this->get_adblock_name($result1['xsmall_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?></bdi>
				</div>
			</div>
		</div>
	<?php
	}
}
?>





	<?php
	foreach($res13 as $key=>$result1)
	{
		$adblockID             	 = $result1['id'];
		$largeDeviceBlockID      = $result1['large_dev_adblock'];
		$mediumDeviceBlockID     = $result1['medium_dev_adblock'];
		$smallDeviceBlockID      = $result1['small_dev_adblock'];
		$extrasmallDeviceBlockID = $result1['xsmall_dev_adblock'];


		if($largeDeviceBlockID > 0 || $mediumDeviceBlockID > 0 || $smallDeviceBlockID > 0 || $extrasmallDeviceBlockID > 0)
		{
		?>
			<div class="res_block form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 mb-3 displayAdcode" id="res_<?php echo $adblockID;?>" style="display:none;">
				<label class="form-label"><?php echo $this->get_label('responsive adblock dimensions'); ?></label>

				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3">
						<bdi><?php echo $this->get_label('large'); ?> : <?php if($largeDeviceBlockID > 0) echo $this->get_adblock_name($result1['large_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?></bdi>
					</div>

					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3">
						<bdi><?php echo $this->get_label('medium'); ?> : <?php if($mediumDeviceBlockID > 0) echo $this->get_adblock_name($result1['medium_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?></bdi>
					</div>

					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3">
						<bdi><?php echo $this->get_label('small'); ?> : <?php if($smallDeviceBlockID > 0) echo $this->get_adblock_name($result1['small_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?></bdi>
					</div>

					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3">
						<bdi><?php echo $this->get_label('xtra small'); ?> : <?php if($extrasmallDeviceBlockID > 0) echo $this->get_adblock_name($result1['xsmall_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?></bdi>
					</div>
				</div>
			</div>
		<?php
		}
	}
	?>
	<?php }?>

	<?php if($nativeads_enabled == 1){?>
		<div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3 nativeAdcode" style="display: none;">
			<label class="form-label"><?php echo $this->get_label('adunit type');?></label>
				<select class="form-select" aria-label="adblock type native" name="adblocktype_native" id="adblocktype_native" onchange="LoadTextImageSize();LoadLayoutPreview();">
					<?php if($text_support ==1){?>
					<option value="1" <?php if($this->get_variable("adblocktype") == 1) {echo "selected";}?> ><?php echo $this->get_label('text only');?></option>
					<?php }?>

					<?php if($textimage_support ==1){?>
					<option value="11" <?php if($this->get_variable("adblocktype") == 11) {echo "selected";}?>><?php echo $this->get_label('textimage only');?></option>
					<?php }?>
				</select>
		</div>

		<div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3 nativeAdcode" style="display: none;">
	 	<label class="form-label"><?php echo $this->get_label('adcode pricing');?></label>
		<?php echo $this->get_pricing_box_custom($adpricing, "adpricing_native");?>
	</div>


	<div class="form-group nativeImageDimension col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3 nativeAdcode" style="display: none;">
	 	<label class="form-label"><?php echo $this->get_label('image dimension');?></label>
		<select class="form-select" aria-label="text image size" name="textimage_size" id="textimage_size" onchange="LoadLayoutPreview();">
		<?php
		$resDimension = $this->get_result('resDimension');

		foreach($resDimension as $key=>$result)
		{
			$height  	 = $result['height'];
			$width   	 = $result['width'];
			$id      	 = $result['id'];
			$diamensions = $result['width']." x ".$result['height'];
		?>
		<option value="<?php echo $id;?>" <?php if($textimage_size == $id) { echo "selected"; }?>><?php echo $diamensions ?></option>
		<?php }?>
		</select>
	</div>

	<div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3 nativeAdcode" style="display: none;">
	 	<label class="form-label"><?php echo $this->get_label('choose ad layout'); ?> <span class="compulsory">*</span></label>

		<select class="form-select" aria-label="ad layout" name="native_layout_id" id="native_layout_id" onchange="LoadLayoutPreview();">
		<option value="0" <?php if($native_layout_id == 0){?> selected="selected" <?php }?>><?php echo $this->get_label('select');?></option>
		<?php foreach($resLayout as $layoutKey => $layoutValue){?>
		<option value="<?php echo $layoutValue['id']; ?>" <?php if($native_layout_id == $layoutValue['id']){?> selected="selected" <?php }?>><?php echo $layoutValue['layout'];?></option>
		<?php }?>
		</select>
	</div>

	<div class="form-group col-md-6 col-sm-6 col-xs-6 mb-3 nativeAdcode" style="display: none;">
		<label class="form-label"><?php echo $this->get_label('responsive'); ?></label>

		<select class="form-select" aria-label="responsive" name="native_responsive_support" id="native_responsive_support" onchange="LoadNativeSettings();">
		<option value="0" <?php if($native_responsive_support == 0){?> selected="selected" <?php }?>><?php echo $this->get_label('no');?></option>
		<option value="1" <?php if($native_responsive_support == 1){?> selected="selected" <?php }?>><?php echo $this->get_label('yes');?></option>
		</select>
	</div>

	<div class="form-group col-md-6 col-sm-6 col-xs-6 mb-3 nativeSettings nativeAdsCount nativeAdcode" style="display: none;">
	 	<label class="form-label"><?php echo $this->get_label('allowed ads'); ?></label>

		<select class="form-select" aria-label="native ads count" name="native_ads_count" id="native_ads_count">
		<?php for($i = 1;$i <= $maximumAds;$i++){?>
		<option value="<?php echo $i;?>" <?php if($native_ads_count == $i){?> selected="selected" <?php }?>><?php echo $i;?></option>
		<?php } ?>
		</select>
	</div>

	<div class="form-group col-md-3 col-sm-3 col-xs-3 mb-3 nativeSettings nativeRowColumn nativeAdcode" style="display: none;">
	 	<label class="form-label"><?php echo $this->get_label('rows'); ?></label>

		<select class="form-select" aria-label="rows" name="native_ads_rows_count" id="native_ads_rows_count">
		<?php for($i = 1;$i <= $maximumRows;$i++){?>
		<option value="<?php echo $i;?>" <?php if($native_ads_rows_count == $i){?> selected="selected" <?php }?>><?php echo $i;?></option>
		<?php } ?>
		</select>
	</div>

	<div class="form-group col-md-3 col-sm-3 col-xs-3 mb-3 nativeSettings nativeRowColumn nativeAdcode" style="display: none;">
	 	<label class="form-label"><?php echo $this->get_label('columns'); ?></label>

		<select class="form-select" aria-label="columns" name="native_ads_column_count" id="native_ads_column_count">
		<?php for($i = 1;$i <= $maximumColumns;$i++){?>
		<option value="<?php echo $i;?>" <?php if($native_ads_column_count == $i){?> selected="selected" <?php }?>><?php echo $i;?></option>
		<?php } ?>
		</select>
	</div>


	<div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3 nativeAdcode" style="display: none;">
	 <label class="form-label"><?php echo $this->get_label('custom css');?></label>
	 <textarea  class="form-control" name="custom_code" id="custom_code" rows="5" ><?php echo $custom_code;?></textarea>
	</div>

	<div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3 nativeAdcode" style="display: none;">
		<div><img id="load" src="images/load.gif" style="display: none;" /></div>
		<div class="previewSection" ></div>
	</div>
	<?php } ?>

	<?php if($feedads_enabled == 1){?>
		<div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3 feedAdcode" style="display: none;">
		 	<label class="form-label"><?php echo $this->get_label('adunit type');?></label>

		    <select class="form-select" aria-label="adblock type feed" name="adblocktype_feed" id="adblocktype_feed">

		    <?php if($text_support ==1){?>
		    <option value="1" <?php if($this->get_variable("adblocktype")==1) {echo "selected";}?>><?php echo $this->get_label('text only');?></option>
		    <?php }?>

			<?php if($banner_support ==1){?>
		    <option value="2" <?php if($this->get_variable("adblocktype")==2) {echo "selected";}?>><?php echo $this->get_label('banner only');?></option>
			<?php }?>

		    <?php if($textimage_support ==1){?>
		    <option value="11" <?php if($this->get_variable("adblocktype")==11) {echo "selected";}?>><?php echo $this->get_label('textimage only');?></option>
		    <?php }?>

		    <?php if($text_support ==1 && $textimage_support == 1){?>
		    <option value="3" <?php if($this->get_variable("adblocktype")==3) {echo "selected";}?>><?php echo $this->get_label('textbanner textimage');?></option>
		    <?php }else if($text_support ==1){?>
		    <option value="3" <?php if($this->get_variable("adblocktype")==3) {echo "selected";}?>><?php echo $this->get_label('textbanner');?></option>
		    <?php }else if($textimage_support == 1){?>
		    <option value="3" <?php if($this->get_variable("adblocktype")==3) {echo "selected";}?>><?php echo $this->get_label('banner textimage');?></option>
		 	<?php }?>

		    <?php if($interstitial_support ==1){?>
		    <option value="5" <?php if($this->get_variable("adblocktype")==5) {echo "selected";}?>><?php echo $this->get_label('interstitial code');?></option>
		    <?php }?>
		    </select>
		</div>

		<div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3 feedAdcode" style="display: none;">
		 	<label class="form-label"><?php echo $this->get_label('adcode pricing');?></label>
			<?php echo $this->get_pricing_box_custom($adpricing, "adpricing_feed");?>
		</div>
	<?php }?>

	</div>

	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group" style="text-align: center;">
		<input class="submit-button" type="submit" name="submit" value="<?php echo $this->get_label('next');?>" />
	</div>

</div>
<?php $form->end(); ?>
</div>
<script type="text/javascript">
function LoadLayoutPreview()
{
	adType        = parseInt($("#adblocktype_native").val()); //1=>Text only , 11=>Text+image only
	adLayout      = parseInt($("#native_layout_id").val());

	textimageSize = 0;

	if(adType == 11)
	textimageSize = parseInt($("#textimage_size").val());

	if(adLayout == 0)
	{
		$('.previewSection').html('');
		return;
	}

	$("#load").show();

	dataparam    = "adLayout="+adLayout+"&adType="+adType+"&textimageSize="+textimageSize;

	var urlvalue = '<?php echo $this->make_url("adunit/load_layout_preview");?>';

	$.ajax(
	{
		type: "POST",
		data: dataparam,
		url: urlvalue,
		success: function(message)
		{
			$("#load").hide();

            $('.previewSection').html(message);
		}
	});
}
function LoadNativeSettings()
{
	$('.nativeSettings').hide();

	if($('#native_responsive_support').val() == 1)
	{
		$('.nativeAdsCount').show();
		$('.nativeRowColumn').hide();
	}
	else
	{
		$('.nativeAdsCount').hide();
		$('.nativeRowColumn').show();
	}
}

function LoadTextImageSize()
{
	if($('#ad_display').val() == 1 && $('#adblocktype_native').val() == 11)
	$('.nativeImageDimension').show();
	else
	$('.nativeImageDimension').hide();
}

function ChangeAdDisplay()
{
	if($('#ad_display').val() == 1) //Native
	{
		$(".adunitName").html('<?php echo $this->get_label('header text');?>');
		$("#name").val('<?php echo $headerText; ?>');

		$('.displayAdcode').hide();

		if($('.feedAdcode').length >0)
		$('.feedAdcode').hide();

		$('.nativeAdcode').show();

		LoadTextImageSize();
		LoadNativeSettings();
		LoadLayoutPreview();
		LoadAdcodePricing();
	}
	else if($('#ad_display').val() == 2) //Feed
	{
		$(".adunitName").html('<?php echo $this->get_label('adunit name');?>');
		$("#name").val('<?php echo $adcodeName; ?>');

		$('.displayAdcode').hide();

		if($('.nativeAdcode').length >0)
		$('.nativeAdcode').hide();

		$('.feedAdcode').show();

		LoadAdcodePricing();
	}
	else //Predefiend
	{
		$(".adunitName").html('<?php echo $this->get_label('adunit name');?>');
		$("#name").val('<?php echo $adcodeName; ?>');

		$('.displayAdcode').show();

		if($('.feedAdcode').length >0)
		$('.feedAdcode').hide();

		if($('.nativeAdcode').length >0)
		$('.nativeAdcode').hide();

		LoadAdcodePricing();
	}
}

function LoadStickySettings()
{
	if($('.sticky_support').length >0)
	{
		adblockFormatType = $('#adblockFormatType').val();

		if(adblockFormatType == 1 || adblockFormatType == 2) //Interstitial/In-Page Push
		{
			$('.sticky_support').hide();
			$('#sticky_support').val(0);
		}
		else 
		{
			if(
				$('#adblocktype').val() == 1 || 
				$('#adblocktype').val() == 2 || 
				$('#adblocktype').val() == 3 || 
				$('#adblocktype').val() == 11 || 
				($('#adblocktype').val() == 13 &&  $("#adcode_for").val() == 2)
			) 
			{
				$('#sticky_support_div').show();	

				if($('#sticky_support').val() == 1)
				{
					$('#sticky_position_div').show();	
					$('#sticky_close_div').show();	
				}
				else
				{
					$('#sticky_position_div').hide();
					$('#sticky_close_div').hide();
				}
			}
			else
			$('.sticky_support').hide();		
		}
	}
}

function LoadResponsiveSettings()
{
	if($(".res_block").length > 0)
	$(".res_block").hide();

	if($('#responsive_support_div').length > 0)
	$('#responsive_support_div').hide();
	
	adblocktype       = $('#adblocktype').val();
	adblockFormatType = $('#adblockFormatType').val();
	
	if(adblockFormatType == 2) //In-Page Push
	$('#responsive_support').val(0);
	else 
	{

		if(
			$("#adpricing").val() != 3 && 
			(
				adblocktype == 1 || 
			  	adblocktype == 2 || 
			  	adblocktype == 3 || 
				adblocktype == 5 || 
			  	adblocktype == 11 || 
				(adblocktype == 13 &&  $("#adcode_for").val() == 2)
			)
		  )	
		{
			if(adblocktype == 13 && $("#adcode_for").val() == 2)
			blockID   = '#player_size';	
			else 
			{
				if(adblockFormatType == 1)
				blockID   = '#blockid_interstitial_'+adblocktype;
				else 
				blockID   = '#blockid_'+adblocktype;
				
				
			}

			lg_support = $(blockID).find(':selected').attr('data-lg');
			md_support = $(blockID).find(':selected').attr('data-md');
			sm_support = $(blockID).find(':selected').attr('data-sm');
			xs_support = $(blockID).find(':selected').attr('data-xs');

			if(lg_support > 0 || md_support > 0 || sm_support > 0 || xs_support > 0)
			{
				if($('#responsive_support_div').length > 0)
				$('#responsive_support_div').show();

				if($('#responsive_support').val() == 1)
				{
					adblock = $(blockID).val();

					$("#res_"+adblock).show();
				}
			}
		}
	}
}

$(document).ready(function() {

	$("#adpricing").change(function()
	{
		LoadResponsiveSettings();

		<?php if($category_enabled ==1){?>
		LoadSiteData();
		<?php }?>
	});

	$("#adpricing_native").change(function()
	{
		<?php if($category_enabled ==1){?>
		LoadSiteData();
		<?php }?>
	});

	ChangeAdDisplay();
});


<?php if($category_enabled ==1){?>
function LoadSiteData()
{
	$(".site-class").hide();

	var adDisplay  = $('#ad_display').val(); //0=>Predefined , 1=>Native, 2=>Feed

	if(adDisplay == 2)
	return;

	if(adDisplay == 1)
	var adcodepricing  = $("#adpricing_native").val();
	else
	var adcodepricing  = $("#adpricing").val();

	if(adDisplay == 0)
	{
		var adblocktype   = $('#adblocktype').val();

		if(adblocktype == 21) //Directlink Case
		return;

		if(adblocktype == 9) //POP
		adcodepricing = adblocktype;
	}

	var allowed='<?php echo Configuration::get_instance()->read('category_enabled_ads');?>';
	if(allowed !='')
	{
		allowed_array=allowed.split('_');

		if($.inArray(adcodepricing , allowed_array) >-1)
		$(".site-class").show();
		else
		{
				if($("#sid_select_00").length > 0)
				$("#sid_select_00").val(0);

				if($("#sid_select_01").length > 0)
				$("#sid_select_01").val(0);
		}
	}


	if($('.sid_span').length > 0)
	{
		$('.sid_span').hide();

		if(adDisplay == 0 && adcodepricing == 3)
		$('.sid_01').show();
	    else if(adDisplay == 0 || adDisplay == 1)
	    $('.sid_00').show();
	}
}
<?php }?>


function LoadAdcodePricing()
{
	var adDisplay  = $('#ad_display').val(); //0=>Predefined , 1=>Native, 2=>Feed

	if(adDisplay == 2)
	{
		var adcodepricing  = "adpricing_feed";
		var adblocktype    = $('#adblocktype_feed').val();
	}
	else if(adDisplay == 1)
	{
		var adcodepricing  = "adpricing_native";
		var adblocktype    = $('#adblocktype_native').val();
	}
	else
	{
		var adcodepricing  = "adpricing";
		var adblocktype    = $('#adblocktype').val();
	}
	
	adblockFormatType = $('#adblockFormatType').val();

	if(adblocktype == 2 && adblockFormatType == 2)
	{
		$('#adblockFormatType').val(0);
		adblockFormatType = 0;
	}
	
	if((adDisplay == 1 || adDisplay == 2 || adblocktype == 9 || adblocktype == 13 || adblocktype == 14 || adblocktype == 21) && $(".adblockFormatType").length > 0)
	{
		$(".adblockFormatType").hide();
		$('#adblockFormatType').val(0);
	}
	else if($(".adblockFormatType").length > 0)
	{
		$(".adblockFormatType").show();

		if($(".inpagePush").length > 0)
		{
			if(adblocktype == 1 || adblocktype == 3 || adblocktype == 11)
			$(".inpagePush").show();
			else 
			$(".inpagePush").hide();
		}
	}

	if($(".common-pricing").length > 0)
	$(".common-pricing").hide();

	if($(".cpm-pricing").length > 0)
	$(".cpm-pricing").hide();

	if($(".cpa-pricing").length > 0)
	$(".cpa-pricing").hide();


	if($('.pop-class').length > 0)
	$('.pop-class').hide();

	if($('.adblock-size').length > 0)
	$('.adblock-size').hide();	

	if($('.video-option').length > 0)
	$('.video-option').hide();	

	if($('#adcode_for').length > 0)
	$('#adcode_for').val(0);

	if($(".skin_pos").length > 0)
	$(".skin_pos").hide();

	if($('.banner-select').length > 0)
	$('.banner-select').hide();
	

	if(adblocktype == 9)
	{
		if($(".cpm-pricing").length >0)
		$(".cpm-pricing").show();

		if($('.pop-class').length >0)
		$('.pop-class').show();

		$("#adpricing").val(1);
	}
	else if(adblocktype == 13)
	{
		if($(".cpm-pricing").length >0)
		$(".cpm-pricing").show();

		if($('.video-option').length >0)
		$('.video-option').show();

		$("#adpricing").val(1);
	}
	else if(adblocktype == 21)
	{
		if($(".cpc-pricing").length >0)
		$(".cpc-pricing").show();

		if($(".cpm-pricing").length >0)
		$(".cpm-pricing").show();

		if($(".cpa-pricing").length >0)
		$(".cpa-pricing").show();

		if($(".cpm-pricing").length >0)
		$("#adpricing").val(1);
		else if($(".cpa-pricing").length >0)
		$("#adpricing").val(6);
		else if($(".cpc-pricing").length >0)
		$("#adpricing").val(0);
	}
	else 
	{
		if($(".common-pricing").length >0)
		$(".common-pricing").show();

		if(adDisplay == 0)
		{
			if($('.adblock-size').length > 0)
			$('.adblock-size').show();	

			if(adblockFormatType == 1 && $('#banner-select-interstitial-'+adblocktype).length > 0)		
			$('#banner-select-interstitial-'+adblocktype).show();
			else if(adblockFormatType == 2 && $('#banner-select-inpagepush-'+adblocktype).length > 0)		
			$('#banner-select-inpagepush-'+adblocktype).show();
			else if(adblockFormatType == 0 && $('#banner-select-'+adblocktype).length > 0)
			$('#banner-select-'+adblocktype).show();
					
			if(adblocktype ==14)
			{
				$(".skin_pos").show();
				Display_skin_prev();
			}
		}

		<?php if($preference == 0){?>
			if($(".cpc-pricing").length >0 && $(".cpm-pricing").length >0)
			{
				$(".cpc-pricing").hide();
				$(".cpm-pricing").hide();

				if($(".cpa-pricing").length >0)
				$(".cpa-pricing").hide();

				$("#"+adcodepricing).val(4);
			}
			else if($(".cpc-pricing").length >0 && $(".cpa-pricing").length >0)
			{
				$(".cpc-pricing").hide();
				$(".cpa-pricing").hide();

				if($(".cpm-pricing").length >0)
				$(".cpm-pricing").hide();
			
				$("#"+adcodepricing).val(4);
			}
			else if($(".cpm-pricing").length >0 && $(".cpa-pricing").length >0)
			{
				$(".cpm-pricing").hide();
				$(".cpa-pricing").hide();
				
				if($(".cpc-pricing").length >0)
				$(".cpc-pricing").hide();

				$("#"+adcodepricing).val(4);
			}
			else if($(".cpc-pricing").length >0)
			$("#"+adcodepricing).val(0);
			else if($(".cpm-pricing").length >0)
			$("#"+adcodepricing).val(1);
			else if($(".cpa-pricing").length >0)
			$("#"+adcodepricing).val(6);
			else if($(".cpd-pricing").length >0 && $("#adpricing").length > 0)
			$("#adpricing").val(3);//CPD Special Case
		<?php }else{ ?>
		if($(".cpc-pricing").length >0)
		$("#"+adcodepricing).val(0);
		else if($(".cpm-pricing").length >0)
		$("#"+adcodepricing).val(1);
		else if($(".cpa-pricing").length >0)
		$("#"+adcodepricing).val(6);
		else if($(".cpd-pricing").length >0 && $("#adpricing").length > 0)
		$("#adpricing").val(3);//CPD Special Case
		else 
		$("#"+adcodepricing).val(4);
		<?php } ?>
	}
		
	if(adDisplay == 0)
	{
		LoadVideoOptions();
		LoadStickySettings();

		<?php if($responsive_enabled == 1){?>
			LoadResponsiveSettings();
		<?php }?>	
	}		

	<?php if($category_enabled ==1){?>
		LoadSiteData();
	<?php }?>
}

function LoadVideoOptions()
{
	adblocktype = $('#adblocktype').val();

	if(adblocktype ==13)
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