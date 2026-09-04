<?php
$this->dispatch("layout/header/2/_22");

$adpricing=$this->get_variable('adpricing');
$sid=$this->get_variable('sid');
$blockid=$this->get_variable('blockid');
$adbid=$blockid;

$cpc_enabled 			= $this->get_addon_status('cpc_enabled');
$cpa_enabled 			= $this->get_addon_status('cpa_enabled');
$cpm_enabled 			= $this->get_addon_status('cpm_enabled');
$category_enabled=$this->get_addon_status('category-targeting_enabled');
$textimage_enabled=$this->get_addon_status('text-image-ads_enabled');
$interstitial_enabled=$this->get_addon_status('interstitial_enabled');
$skin_enabled=$this->get_addon_status('skin-ads_enabled');
$sponsored_enabled=$this->get_addon_status('sponsored_enabled');
$sticky_enabled=$this->get_addon_status('sticky-ad-display_enabled');
$directlink_enabled = $this->get_addon_status('direct-link-ads_enabled');
$inpage_push_enabled = $this->get_addon_status('inpage-push-ads_enabled');


if($directlink_enabled == 1 && ($cpc_enabled == 1 || $cpm_enabled == 1 || $cpa_enabled == 1))
$directlink_enabled = 1;

$responsive_enabled = $this->get_variable('responsive_enabled');
$responsive_support	= $this->get_variable('responsive_support');

$pop_type           = $this->get_variable('pop_type');
$pop_enabled        = $this->get_variable('pop_enabled');


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
#adpricing, #sid, #adblocktype {
	width: 150px !important;
}

#sid_select_00, #sid_select_01 
{
	width: 150px !important;
}

.common-pricing, .cpm-pricing, .cpa-pricing
{
	display: none;
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
		<td><input type="text" name="name" id="name" value="<?php echo $this->get_variable('name');?>" maxlength="25" style="width: 140px;" /><span	class="compulsory">*</span>
		</td>
	</tr>

	<tr>
		<td><?php echo $this->get_label('adcode type');?></td>
		<td></td>
		<td>
		<select name="adblocktype" id="adblocktype" onchange="LoadAdcodePricing();" style="width: 150px;">

			<?php if($text_ads_enabled ==1){?>
			<option value="1" <?php if($this->get_variable("adblocktype")==1) {echo "selected";}?>><?php echo $this->get_label('text only');?></option>
			<?php }?>

			<option value="2" <?php if($this->get_variable("adblocktype")==2) {echo "selected";}?>><?php echo $this->get_label('banner only');?></option>

			<?php if($textimage_enabled==1){?>
			<option value="11" <?php if($this->get_variable("adblocktype")==11) {echo "selected";}?>><?php echo $this->get_label('textimage only');?></option>
			<?php } ?>


			<?php if($text_ads_enabled ==1 && $textimage_enabled == 1){?>
			<option value="3" <?php if($this->get_variable("adblocktype")==3) {echo "selected";}?>><?php echo $this->get_label('textbanner textimage');?></option>
			<?php }else if($text_ads_enabled ==1){?>
			<option value="3" <?php if($this->get_variable("adblocktype")==3) {echo "selected";}?>><?php echo $this->get_label('textbanner');?></option>
			<?php }else if($textimage_enabled == 1){?>
			<option value="3" <?php if($this->get_variable("adblocktype")==3) {echo "selected";}?>><?php echo $this->get_label('banner textimage');?></option>
			<?php }?>

		

			<?php if($skin_enabled ==1){?>
			<option value="14" <?php if($this->get_variable("adblocktype")==14) {echo "selected";}?>><?php echo $this->get_label('skin');?></option>
			<?php }?>

			<?php if($pop_enabled ==1){?>
				<option value="9" <?php if($this->get_variable("adblocktype")==9) {echo "selected";}?>><?php echo $this->get_label('pop');?></option>
			<?php }?>

			<?php if($directlink_enabled ==1){?>
				<option value="21" <?php if($this->get_variable("adblocktype")==21) {echo "selected";}?>><?php echo $this->get_label('direct link');?></option>
			<?php }?>

			<?php if($video_enabled ==1){?>
				<option value="13" <?php if($this->get_variable("adblocktype")==13) {echo "selected";}?>><?php echo $this->get_label('video');?></option>
			<?php }?>
		</select>
	</td>
	</tr>
	
	<?php if($interstitial_enabled == 1 || $inpage_push_enabled == 1){?>
	<tr class="adblockFormatType" style="display:none;">
		<td><?php echo $this->get_label('adcode format');?></td>
		<td></td>
		<td>
		<select name="adblockFormatType" id="adblockFormatType" onchange="LoadAdcodePricing();" style="width: 150px;">						
			<option value="0" <?php if($this->get_variable("adblockFormatType")==0) {echo "selected";}?>><?php echo $this->get_label('normal ads');?></option>
			
			<?php if($interstitial_enabled == 1){?>
				<option value="1" <?php if($this->get_variable("adblockFormatType") == 1) {echo "selected";}?>><?php echo $this->get_label('interstitial ads');?></option>
			<?php }?>
			
			<?php if($inpage_push_enabled == 1){?>
				<option class="inpagePush" style="display:none;" value="2" <?php if($this->get_variable("adblockFormatType") == 2) {echo "selected";}?>><?php echo $this->get_label('inpage push ads');?></option>
			<?php }?>
		</select>
		</td>
	</tr>
	<?php }else{?>
		<input type="hidden" name="adblockFormatType" id="adblockFormatType" value="0" />
	<?php } ?>	

	

	<tr>
		<td><?php echo $this->get_label('pricing');?></td>
		<td></td>
		<td><?php echo $this->get_pricing_box($adpricing,2);?></td>
	</tr>



	<tr class="video-option" style="display: none;">
		<td><?php echo $this->get_label('adcode for');?></td>
		<td></td>
		<td>
		<select style="width: 150px;" name="adcode_for" id="adcode_for" onchange="<?php if($category_enabled ==1){?>LoadSiteData();<?php }?>LoadVideoOptions();LoadStickySettings();LoadResponsiveSettings();">
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
		<select name="player_size" id="player_size" style="width: 150px;"  <?php if($responsive_enabled == 1){?>onchange="LoadResponsiveSettings();"<?php }?>>
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
		</select></td>
	</tr>
	<?php }?>


	<?php if($linear_support ==1 || $nonlinear_support ==1){

		$res13_linear=$this->get_result('res13_linear');
		?>
	<tr class="video-option-vast" style="display: none;">
		<td><?php echo $this->get_label('supported type');?> <span
			class="compulsory">*</span></td>
		<td></td>
		<td><?php if($linear_support ==1){?>
		<div style="float: left;"><input disabled="disabled" type="checkbox"
			name="linear" id="linear" value="1" checked="checked" /> <?php echo $this->get_label('linear');?>&nbsp;&nbsp;
		</div>
		<?php }?> <?php if($nonlinear_support ==1){?> <?php if($text_ads_enabled ==1){?>
		<div style="float: left;"><input type="checkbox" name="nonlineartext"
			id="nonlineartext" value="1" <?php if($nonlineartext ==1){?>
			checked="checked" <?php }?> /> <?php echo $this->get_label('non linear text');?>&nbsp;&nbsp;
		</div>
		<?php }?> <?php if(count($res13_linear) >0){?>
		<div style="float: left;"><input type="checkbox"
			name="nonlinearbanner" id="nonlinearbanner" value="1"
			<?php if($nonlinearbanner ==1){?> checked="checked" <?php }?>
			onclick="LoadVideoOptions();" /> <?php echo $this->get_label('non linear banner');?>
		</div>
		<?php }?> <?php }?></td>
	</tr>
	<?php }?>


	<?php if($nonlinear_support ==1 && count($res13_linear) >0){?>
	<tr class="video-option-vast-size" style="display: none;">
		<td><?php echo $this->get_label('banner dimension');?></td>
		<td></td>
		<td><select name="nonlinear_size"
			id="nonlinear_size" style="width: 150px;">
			<?php
			foreach($res13_linear as $key=>$result14)
			{
				$height=$result14['height'];
				$width=$result14['width'];
				$id=$result14['id'];
				$diamensions=$result14['width']." x ".$result14['height'];
				?>
			<option value="<?php echo $id;?>"
			<?php if($nonlinear_size == $id) { echo "selected"; }?>><?php echo $diamensions; ?></option>
			<?php }?>
		</select></td>
	</tr>
	<?php }?>


	<?php if($category_enabled ==1){?>
	<tr class="site-class" style="display: none;">
		<td><?php echo $this->get_label('targeting site');?></td>
		<td></td>
		<td><span class="sid_span sid_00" style="display: none;"><?php echo CategoryHelper::get_site_dropdown(0,$sid,1); //sid ?></span>

		<?php if($sponsored_enabled ==1){?> <span class="sid_span sid_01"
			style="display: none;"> <?php echo CategoryHelper::get_site_dropdown(0,$sid,1,1); //sid_cpd ?>
		</span> <?php }?> <span class="compulsory">*</span></td>
	</tr>
	<?php }?>


	<?php if($pop_enabled ==1){

		$pop_ads_support=Configuration::get_instance()->read('pop_ads_support');

		$pop_array=explode('-',$pop_ads_support);

		$pop_up  = intval($pop_array[0]);
		$pop_tab = intval($pop_array[1]);
		?>
		<tr class="pop-class" style="display: none;">
			<td ><?php echo $this->get_label('pop type');?></td>
			<td></td>
			<td>
				<select name="pop_type" id="pop_type" style="width: 150px;">
					<?php if($pop_tab == 1){?>
						<option value="0" <?php if($pop_type == 0) {echo "selected";}?>><?php echo $this->get_label('poptab');?></option>
					<?php }?>

					<?php if($pop_up == 1){?>
						<option value="1" <?php if($pop_type == 1) {echo "selected";}?>><?php echo $this->get_label('popup');?></option>
					<?php }?>
				</select>
	    	</td>
	    </tr>
	<?php } ?>


	<tr class="adblock-size">
	<td><?php echo $this->get_label('select adblock');?></td>
	<td></td>
	<td>
	<span class="banner-select" id="banner-select-1" style="display: none;"> 
		<select name="blockid_1" id="blockid_1" style="width: 150px;" <?php if($responsive_enabled == 1){?>
			onchange="LoadResponsiveSettings();" <?php }?>>
		<?php
		$res1=$this->get_result('res1');

		foreach($res1 as $key=>$result1)
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

	<span class="banner-select" id="banner-select-interstitial-1" style="display: none;"> 
		<select name="blockid_interstitial_1" id="blockid_interstitial_1" style="width: 150px;" <?php if($responsive_enabled == 1){?>
			onchange="LoadResponsiveSettings();" <?php }?>>
		<?php
		$resInterstitial1=$this->get_result('resInterstitial1');

		foreach($resInterstitial1 as $key=>$result1)
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

	<?php if($inpage_push_enabled == 1){?>
		<span class="banner-select" id="banner-select-inpagepush-1" style="display: none;"> 
			<select name="blockid_inpagepush_1" id="blockid_inpagepush_1" style="width: 150px;" <?php if($responsive_enabled == 1){?>
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
		<select name="blockid_2" id="blockid_2" style="width: 150px;" <?php if($responsive_enabled == 1){?>
			onchange="LoadResponsiveSettings();" <?php }?>>
		<?php
		$res2=$this->get_result('res2');

		foreach($res2 as $key=>$result1)
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

	<span class="banner-select" id="banner-select-interstitial-2" style="display: none;"> 
		<select name="blockid_interstitial_2" id="blockid_interstitial_2" style="width: 150px;" <?php if($responsive_enabled == 1){?>
			onchange="LoadResponsiveSettings();" <?php }?>>
		<?php
		$resInterstitial2=$this->get_result('resInterstitial2');

		foreach($resInterstitial2 as $key=>$result1)
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

	<span class="banner-select" id="banner-select-3" style="display: none;"> 
		<select name="blockid_3" id="blockid_3" style="width: 150px;" <?php if($responsive_enabled == 1){?>
			onchange="LoadResponsiveSettings();" <?php }?>>
		<?php
		$res3=$this->get_result('res3');

		foreach($res3 as $key=>$result1)
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

	<span class="banner-select" id="banner-select-interstitial-3" style="display: none;"> 
		<select name="blockid_interstitial_3" id="blockid_interstitial_3" style="width: 150px;" <?php if($responsive_enabled == 1){?>
			onchange="LoadResponsiveSettings();" <?php }?>>
		<?php
		$resInterstitial3=$this->get_result('resInterstitial3');

		foreach($resInterstitial3 as $key=>$result1)
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

	<?php if($inpage_push_enabled == 1){?>
		<span class="banner-select" id="banner-select-inpagepush-3" style="display: none;"> 
			<select name="blockid_inpagepush_3" id="blockid_inpagepush_3" style="width: 150px;" <?php if($responsive_enabled == 1){?>
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
		<select name="blockid_11" id="blockid_11" style="width: 150px;" <?php if($responsive_enabled == 1){?>
			onchange="LoadResponsiveSettings();" <?php }?>>
		<?php
		$res11=$this->get_result('res11');

		foreach($res11 as $key=>$result1)
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

	<span class="banner-select" id="banner-select-interstitial-11" style="display: none;"> 
		<select name="blockid_interstitial_11" id="blockid_interstitial_11" style="width: 150px;" <?php if($responsive_enabled == 1){?>
			onchange="LoadResponsiveSettings();" <?php }?>>
		<?php
		$resInterstitial11=$this->get_result('resInterstitial11');

		foreach($resInterstitial11 as $key=>$result1)
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


	<?php if($inpage_push_enabled == 1){?>
		<span class="banner-select" id="banner-select-inpagepush-11" style="display: none;"> 
			<select name="blockid_inpagepush_11" id="blockid_inpagepush_11" style="width: 150px;" <?php if($responsive_enabled == 1){?>
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
		<select name="blockid_14" id="blockid_14" style="width: 150px;" onchange="Display_skin_prev();">
		<?php
		$res14           = $this->get_result('res14');
		$exist_positions = array();
		$skin_preview    = "";

		foreach($res14 as $key=>$result1)
		{
			$id = $result1['id'];

			$get_skin_positions_res =$this->get_skin_positions($result1['skin_positions']);

			if(!array_key_exists($get_skin_positions_res,$exist_positions) && $get_skin_positions_res !=1)
			{
				$exist_positions[$get_skin_positions_res] =1;
				$get_skin_preview_res =$this->get_skin_preview($id);

				$skin_preview.='<div class="skin_prev" id="skin_prev'.$id.'" style="display:none;"><img src="'.$get_skin_preview_res.'"></div>';
				?>
				<option value="<?php echo $id;?>" <?php if($adbid == $id) { echo "selected"; }?>><?php echo $get_skin_positions_res; ?></option>
			<?php 
			}
		}
		?>
		</select> 
	    </span> 
	    <span class="skin_pos" style="left: 500px; position: absolute;"><?php echo $skin_preview; ?></span>
	<?php }?>
	</td>
	</tr>

	<?php if($skin_enabled ==1){?>
	<tr class="skin_pos" style="display: none;">
		<td style="padding-top: 10px;"><?php echo $this->get_label('main container id'); ?>
		<span class="compulsory">*</span></td>
		<td></td>
		<td style="padding-top: 10px;"><input type="text" name="container_id"
			id="container_id" />
		<div class="notification" style="width: 300px;"><?php echo $this->get_label('main container id description'); ?></div>
		</td>
	</tr>
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
		<tr class="sticky_support" id="sticky_support_div" style="display: none;">
		<td><?php echo $this->get_label('sticky adcode'); ?></td>
		<td></td>
		<td>
		<select name="sticky_support" id="sticky_support" style="width: 150px;" onclick="LoadStickySettings();">
		<option value="0" <?php if($sticky_support == 0){?> selected="selected" <?php }?>><?php echo $this->get_label('no');?></option>
		<option value="1" <?php if($sticky_support == 1){?> selected="selected" <?php }?>><?php echo $this->get_label('yes');?></option>
		</select>
		</td>
		</tr>

		<tr class="sticky_support" id="sticky_position_div" style="display: none;">
		<td><?php echo $this->get_label('sticky position'); ?></td>
		<td></td>
		<td>
		<select name="sticky_position" style="width: 150px;">
		<?php
		foreach($position_array as $poskey=>$posvalue)
		{
			if($posvalue ==1){?>
			<option value="<?php echo $poskey;?>"
			<?php if($sticky_position == $poskey){?> selected="selected"
			<?php }?>><?php echo $this->get_label(strtolower($poskey));?></option>
			<?php
			}
		}
		?>
		</select>
	    </td>
		</tr>

		<tr class="sticky_support" id="sticky_close_div" style="display: none;">
		<td><?php echo $this->get_label('sticky close position'); ?></td>
		<td></td>
		<td>
		<select name="sticky_close_position" style="width: 150px;">
		<option value="0" <?php if($sticky_close_position == 0){?> selected="selected" <?php }?>><?php echo $this->get_label('no');?></option>
		<option value="1" <?php if($sticky_close_position == 1){?> selected="selected" <?php }?>><?php echo $this->get_label('yes');?></option>
		</select>
	    </td>
	    </tr>
	<?php }?>
	<?php if($responsive_enabled == 1){?>
	<tr id="responsive_support_div" style="display: none;">
	<td style="padding-top: 10px;"><?php echo $this->get_label('responsive'); ?></td>
	<td></td>
	<td style="padding-top: 10px;">
	<select name="responsive_support" id="responsive_support" onchange="LoadResponsiveSettings();" style="width: 150px;">
	<option value="0" <?php if($responsive_support == 0){?> selected="selected" <?php }?>><?php echo $this->get_label('no');?></option>
	<option value="1" <?php if($responsive_support == 1){?> selected="selected" <?php }?>><?php echo $this->get_label('yes');?></option>
	</select>	
	</td>
	</tr>

	<tr>
	<td style="height: 5px;"></td>
	<td style="height: 5px;"></td>
	<td style="height: 5px;">
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
			<div class="res_block" id="res_<?php echo $adblockID;?>" style="display:none;">
			<h3><?php echo $this->get_label('responsive adblock dimensions'); ?></h3>

			<div><div style="width: 90px;float: left;"><?php echo $this->get_label('large'); ?> </div>:&nbsp; <?php if($largeDeviceBlockID > 0) echo $this->get_adblock_name($result1['large_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?> 
		    </div>

			<div><div style="width: 90px;float: left;"><?php echo $this->get_label('medium'); ?> </div>:&nbsp; <?php if($mediumDeviceBlockID > 0) echo $this->get_adblock_name($result1['medium_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?> 
		    </div>

			<div><div style="width: 90px;float: left;"><?php echo $this->get_label('small'); ?> </div>:&nbsp; <?php if($smallDeviceBlockID > 0) echo $this->get_adblock_name($result1['small_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?> 
		    </div>

			<div><div style="width: 90px;float: left;"><?php echo $this->get_label('xtra small'); ?> </div>:&nbsp; <?php if($extrasmallDeviceBlockID > 0) echo $this->get_adblock_name($result1['xsmall_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?>
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
			<div class="res_block" id="res_<?php echo $adblockID;?>" style="display:none;">
			<h3><?php echo $this->get_label('responsive adblock dimensions'); ?></h3>

			<div><div style="width: 90px;float: left;"><?php echo $this->get_label('large'); ?> </div>:&nbsp; <?php if($largeDeviceBlockID > 0) echo $this->get_adblock_name($result1['large_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?> 
		    </div>

			<div><div style="width: 90px;float: left;"><?php echo $this->get_label('medium'); ?> </div>:&nbsp; <?php if($mediumDeviceBlockID > 0) echo $this->get_adblock_name($result1['medium_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?> 
		    </div>

			<div><div style="width: 90px;float: left;"><?php echo $this->get_label('small'); ?> </div>:&nbsp; <?php if($smallDeviceBlockID > 0) echo $this->get_adblock_name($result1['small_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?> 
		    </div>

			<div><div style="width: 90px;float: left;"><?php echo $this->get_label('xtra small'); ?> </div>:&nbsp; <?php if($extrasmallDeviceBlockID > 0) echo $this->get_adblock_name($result1['xsmall_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?>
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
			<div class="res_block" id="res_<?php echo $adblockID;?>" style="display:none;">
			<h3><?php echo $this->get_label('responsive adblock dimensions'); ?></h3>

			<div><div style="width: 90px;float: left;"><?php echo $this->get_label('large'); ?> </div>:&nbsp; <?php if($largeDeviceBlockID > 0) echo $this->get_adblock_name($result1['large_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?> 
		    </div>

			<div><div style="width: 90px;float: left;"><?php echo $this->get_label('medium'); ?> </div>:&nbsp; <?php if($mediumDeviceBlockID > 0) echo $this->get_adblock_name($result1['medium_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?> 
		    </div>

			<div><div style="width: 90px;float: left;"><?php echo $this->get_label('small'); ?> </div>:&nbsp; <?php if($smallDeviceBlockID > 0) echo $this->get_adblock_name($result1['small_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?> 
		    </div>

			<div><div style="width: 90px;float: left;"><?php echo $this->get_label('xtra small'); ?> </div>:&nbsp; <?php if($extrasmallDeviceBlockID > 0) echo $this->get_adblock_name($result1['xsmall_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?>
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
			<div class="res_block" id="res_<?php echo $adblockID;?>" style="display:none;">
			<h3><?php echo $this->get_label('responsive adblock dimensions'); ?></h3>

			<div><div style="width: 90px;float: left;"><?php echo $this->get_label('large'); ?> </div>:&nbsp; <?php if($largeDeviceBlockID > 0) echo $this->get_adblock_name($result1['large_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?> 
		    </div>

			<div><div style="width: 90px;float: left;"><?php echo $this->get_label('medium'); ?> </div>:&nbsp; <?php if($mediumDeviceBlockID > 0) echo $this->get_adblock_name($result1['medium_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?> 
		    </div>

			<div><div style="width: 90px;float: left;"><?php echo $this->get_label('small'); ?> </div>:&nbsp; <?php if($smallDeviceBlockID > 0) echo $this->get_adblock_name($result1['small_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?> 
		    </div>

			<div><div style="width: 90px;float: left;"><?php echo $this->get_label('xtra small'); ?> </div>:&nbsp; <?php if($extrasmallDeviceBlockID > 0) echo $this->get_adblock_name($result1['xsmall_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?>
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
			<div class="res_block" id="res_<?php echo $adblockID;?>" style="display:none;">
			<h3><?php echo $this->get_label('responsive adblock dimensions'); ?></h3>

			<div><div style="width: 90px;float: left;"><?php echo $this->get_label('large'); ?> </div>:&nbsp; <?php if($largeDeviceBlockID > 0) echo $this->get_adblock_name($result1['large_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?> 
		    </div>

			<div><div style="width: 90px;float: left;"><?php echo $this->get_label('medium'); ?> </div>:&nbsp; <?php if($mediumDeviceBlockID > 0) echo $this->get_adblock_name($result1['medium_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?> 
		    </div>

			<div><div style="width: 90px;float: left;"><?php echo $this->get_label('small'); ?> </div>:&nbsp; <?php if($smallDeviceBlockID > 0) echo $this->get_adblock_name($result1['small_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?> 
		    </div>

			<div><div style="width: 90px;float: left;"><?php echo $this->get_label('xtra small'); ?> </div>:&nbsp; <?php if($extrasmallDeviceBlockID > 0) echo $this->get_adblock_name($result1['xsmall_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?>
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
			<div class="res_block" id="res_<?php echo $adblockID;?>" style="display:none;">
			<h3><?php echo $this->get_label('responsive adblock dimensions'); ?></h3>

			<div><div style="width: 90px;float: left;"><?php echo $this->get_label('large'); ?> </div>:&nbsp; <?php if($largeDeviceBlockID > 0) echo $this->get_adblock_name($result1['large_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?> 
		    </div>

			<div><div style="width: 90px;float: left;"><?php echo $this->get_label('medium'); ?> </div>:&nbsp; <?php if($mediumDeviceBlockID > 0) echo $this->get_adblock_name($result1['medium_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?> 
		    </div>

			<div><div style="width: 90px;float: left;"><?php echo $this->get_label('small'); ?> </div>:&nbsp; <?php if($smallDeviceBlockID > 0) echo $this->get_adblock_name($result1['small_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?> 
		    </div>

			<div><div style="width: 90px;float: left;"><?php echo $this->get_label('xtra small'); ?> </div>:&nbsp; <?php if($extrasmallDeviceBlockID > 0) echo $this->get_adblock_name($result1['xsmall_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?>
		    </div>

			</div>	
		<?php
		}
	}
	?> 
	<?php
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
			<div class="res_block" id="res_<?php echo $adblockID;?>" style="display:none;">
			<h3><?php echo $this->get_label('responsive adblock dimensions'); ?></h3>

			<div><div style="width: 90px;float: left;"><?php echo $this->get_label('large'); ?> </div>:&nbsp; <?php if($largeDeviceBlockID > 0) echo $this->get_adblock_name($result1['large_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?> 
		    </div>

			<div><div style="width: 90px;float: left;"><?php echo $this->get_label('medium'); ?> </div>:&nbsp; <?php if($mediumDeviceBlockID > 0) echo $this->get_adblock_name($result1['medium_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?> 
		    </div>

			<div><div style="width: 90px;float: left;"><?php echo $this->get_label('small'); ?> </div>:&nbsp; <?php if($smallDeviceBlockID > 0) echo $this->get_adblock_name($result1['small_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?> 
		    </div>

			<div><div style="width: 90px;float: left;"><?php echo $this->get_label('xtra small'); ?> </div>:&nbsp; <?php if($extrasmallDeviceBlockID > 0) echo $this->get_adblock_name($result1['xsmall_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?>
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
			<div class="res_block" id="res_<?php echo $adblockID;?>" style="display:none;">
			<h3><?php echo $this->get_label('responsive adblock dimensions'); ?></h3>

			<div><div style="width: 90px;float: left;"><?php echo $this->get_label('large'); ?> </div>:&nbsp; <?php if($largeDeviceBlockID > 0) echo $this->get_adblock_name($result1['large_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?> 
		    </div>

			<div><div style="width: 90px;float: left;"><?php echo $this->get_label('medium'); ?> </div>:&nbsp; <?php if($mediumDeviceBlockID > 0) echo $this->get_adblock_name($result1['medium_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?> 
		    </div>

			<div><div style="width: 90px;float: left;"><?php echo $this->get_label('small'); ?> </div>:&nbsp; <?php if($smallDeviceBlockID > 0) echo $this->get_adblock_name($result1['small_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?> 
		    </div>

			<div><div style="width: 90px;float: left;"><?php echo $this->get_label('xtra small'); ?> </div>:&nbsp; <?php if($extrasmallDeviceBlockID > 0) echo $this->get_adblock_name($result1['xsmall_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?>
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
			<div class="res_block" id="res_<?php echo $adblockID;?>" style="display:none;">
			<h3><?php echo $this->get_label('responsive adblock dimensions'); ?></h3>

			<div><div style="width: 90px;float: left;"><?php echo $this->get_label('large'); ?> </div>:&nbsp; <?php if($largeDeviceBlockID > 0) echo $this->get_adblock_name($result1['large_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?> 
		    </div>

			<div><div style="width: 90px;float: left;"><?php echo $this->get_label('medium'); ?> </div>:&nbsp; <?php if($mediumDeviceBlockID > 0) echo $this->get_adblock_name($result1['medium_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?> 
		    </div>

			<div><div style="width: 90px;float: left;"><?php echo $this->get_label('small'); ?> </div>:&nbsp; <?php if($smallDeviceBlockID > 0) echo $this->get_adblock_name($result1['small_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?> 
		    </div>

			<div><div style="width: 90px;float: left;"><?php echo $this->get_label('xtra small'); ?> </div>:&nbsp; <?php if($extrasmallDeviceBlockID > 0) echo $this->get_adblock_name($result1['xsmall_dev_adblock']); else echo $this->get_label('responsive adblock not assigned'); ?>
		    </div>

			</div>	
		<?php
		}
	}
	?>

	</td>
	</tr>

	<?php }?>

	<tr>
		<td></td>
		<td></td>
		<td align="left">
			<input type="submit" name="submit" value="<?php echo $this->get_label('create new adunit');?>">
		</td>
	</tr>
	</table>
	</div>
	</div>
	<?php $form->end(); ?>


<script type="text/javascript">
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

	LoadAdcodePricing();
});


<?php if($category_enabled ==1){?>
function LoadSiteData()
{
	$(".site-class").hide();
	var adcodepricing = $("#adpricing").val();
	var adblocktype   = $('#adblocktype').val();

	if(adblocktype == 21) //Directlink Case
	return;

	if(adblocktype == 9) //POP
	adcodepricing = adblocktype;

	var allowed='<?php echo Configuration::get_instance()->read('category_enabled_ads');?>';
	if(allowed != '')
	{
		allowed_array = allowed.split('_');

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

		if(adcodepricing ==3)
		$('.sid_01').show();
		else 
		$('.sid_00').show();
	}
}
<?php }?>


function LoadAdcodePricing()
{
	adblocktype       = $('#adblocktype').val();
	adblockFormatType = $('#adblockFormatType').val();

	if(adblocktype == 2 && adblockFormatType == 2)
	{
		$('#adblockFormatType').val(0);
		adblockFormatType = 0;
	}

	if((adblocktype == 9 || adblocktype == 13 || adblocktype == 14 || adblocktype == 21) && $(".adblockFormatType").length > 0)
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

		if($('.adblock-size').length > 0)
		$('.adblock-size').show();	

		if(adblockFormatType == 1 && $('#banner-select-interstitial-'+adblocktype).length > 0)		
		$('#banner-select-interstitial-'+adblocktype).show();
		else if(adblockFormatType == 2 && $('#banner-select-inpagepush-'+adblocktype).length > 0)		
		$('#banner-select-inpagepush-'+adblocktype).show();
		else if(adblockFormatType == 0 && $('#banner-select-'+adblocktype).length > 0)
		$('#banner-select-'+adblocktype).show();

		if(adblocktype == 14)
		{
			$(".skin_pos").show();
			Display_skin_prev();
		}

		if($(".cpc-pricing").length >0)
		$("#adpricing").val(0);
		else if($(".cpm-pricing").length >0)
		$("#adpricing").val(1);
		else if($(".cpa-pricing").length >0)
		$("#adpricing").val(6);
		else if($(".cpd-pricing").length >0)
		$("#adpricing").val(3);
		else 
		$("#adpricing").val(4);
	}
		
	LoadVideoOptions();
	LoadStickySettings();

	<?php if($responsive_enabled == 1){?>
		LoadResponsiveSettings();
	<?php }?>	

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
	bannersize_sel = $("#blockid_14").val();

	$(".skin_prev").hide();
	$('#skin_prev'+bannersize_sel).show();
}
<?php } ?>
</script>
<?php $this->dispatch("layout/footer");?>