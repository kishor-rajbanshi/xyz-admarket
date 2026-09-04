<?php
$this->dispatch("layout/header/2/2/a");
$aid=$this->get_variable('aid');
$from=$this->get_variable('from');
$device=$this->get_variable('device');
$adstatus=$this->get_variable('adstatus');

$deviceenabled=$this->get_addon_status('device-targeting_enabled');
$sponsored=$this->get_addon_status('sponsored_enabled');
$category_enabled=$this->get_addon_status('category-targeting_enabled');
$time_target_enabled=$this->get_addon_status('time-targeting_enabled');
$city_enabled=$this->get_addon_status('city-targeting_enabled');
$isp_enabled=$this->get_addon_status('isp-targeting_enabled');
$connection_enabled=$this->get_addon_status('connectiontype-targeting_enabled');


$language_enabled = $this->get_addon_status('language-targeting_enabled');
$video_enabled    = $this->get_addon_status('video-ads_enabled');
$adCloneEnabled   = Configuration::get_instance()->read('enable_advertisers_ad_clone_option');

$adtype           = $this->get_variable('adtype');
$parent_ad        = $this->get_variable('parent_ad');
$isp_success      = $this->get_variable('isp_success');

$adrow            = $this->get_result("adrow");
$val              = $adrow[0];

$pricing_value    = $val['display_type'];



$retargeting_enabled=$this->get_addon_status('retargeting_enabled');

$retargeting=0;

if($retargeting_enabled ==1)
$retargeting=$val['retargeting'];

$expandable_banner="";
$expandable=0;
$expandable_enabled=$this->get_addon_status('expandable-banners_enabled');

if($adtype ==2 && $expandable_enabled ==1)
{
	$expandable_banner=$val['expandable_banner'];
	$expandable=$val['expandable'];

	if($expandable_banner =="")
	$expandable=0;
}


if($adtype ==2 || $adtype ==7 || $adtype ==11)
{
	$diamensions_string=$this->get_banner_dimension($val['banner_id']);

	$diamensions_array=explode('-',$diamensions_string);

	$diamensions=$diamensions_array[0]." x ".$diamensions_array[1];
}

$stringarray = array();
if($category_enabled ==1)
{
	$category_enabled_ads=Configuration::get_instance()->read('category_enabled_ads');

	if($category_enabled_ads !='')
	$stringarray=explode('_',$category_enabled_ads);
}


$adName = $val['name'].' - '.$this->get_ad_pricing($aid,$pricing_value);

if($adtype ==2 || $adtype ==7 || $adtype ==11)
$adName.= ' - '.$diamensions;

if($adtype == 2 && $expandable == 1)
$adName.= ' '.$this->get_label('expandable');

if($adtype == 13)
{
		$aspect_ratio = $this->get_aspect_ratio_value($val['aspect_ratio']);

		if($aspect_ratio > 0)
		$adName.= ' - '.$aspect_ratio;
}
?>
<script type="text/javascript">
function show_tab(id)
{
		if(id == 14)
		document.getElementById('isp-iframe').contentDocument.location.reload(true);

		$('.tabcontent').hide();
		$('#showstat'+id).show();

		if($('#step-box-'+id).length >0)
		{
				$('.step-box').removeClass("current");
				$('#step-box-'+id).addClass("current");
		}

		//For iframe height adjustment
		document.getElementById("ad-iframe-"+id).contentWindow.postMessage({"operation" : "findContentHeight", "elementID" : "ad-iframe-"+id}, "*");
}
</script>

<?php
$keyword_enabled=Configuration::get_instance()->read('keyword_based_ad_display');
$date_enabled=Configuration::get_instance()->read('date_filter_enabled');
$time_enabled=Configuration::get_instance()->read('time_filter_enabled');
$day_enabled=Configuration::get_instance()->read('day_filter_enabled');

$step_array=array();

$step=1;

if($pricing_value !=3 && $adtype !=12 && $adtype !=21)
{
		$step_array['content']=array($step++,$this->get_label('ad content'));

		$step_array['pricing']=array($step++,$this->get_label('pricing'));

		if($adtype != 7 || ($adtype == 7 && $parent_ad > 0))
		{
				$step_array['locations']=array($step++,$this->get_label('locations'));

				if($keyword_enabled ==1 && $adtype != 18)
				$step_array['keywords']=array($step++,$this->get_label('keywords'));

				if($category_enabled ==1 && in_array($pricing_value,$stringarray))
				$step_array['category']=array($step++,$this->get_label('category'));

				if($deviceenabled ==1)
				$step_array['device']=array($step++,$this->get_label('device'));

				if($connection_enabled ==1 && $isp_success >0)
				$step_array['connection']=array($step++,$this->get_label('connection'));

				if($isp_enabled ==1 && $isp_success ==2)
				$step_array['isp']=array($step++,$this->get_label('isp'));

				if($retargeting)
				$step_array['retarget']=array($step++,$this->get_label('retargeting'));

				if($time_target_enabled ==1 && ($date_enabled ==1 || $time_enabled ==1 || $day_enabled ==1))
				$step_array['time']=array($step++,$this->get_label('time'));

				if($language_enabled ==1)
				$step_array['language']=array($step++,$this->get_label('language'));
		}
}

if($pricing_value ==3)
{
		$step_array['content']=array($step++,$this->get_label('ad content'));

		if($adtype != 7 || ($adtype == 7 && $parent_ad > 0))
		$step_array['position']=array($step++,$this->get_label('ad cpd targeting'));
}


if($adtype == 12)
{
		$step_array['content']=array($step++,$this->get_label('ad content'));

		$step_array['pricing']=array($step++,$this->get_label('pricing'));

		if($adtype !=7 || ($adtype ==7 && $parent_ad >0))
		{
				$step_array['locations']=array($step++,$this->get_label('locations'));

				if($deviceenabled ==1)
				$step_array['device']=array($step++,$this->get_label('device'));

				if($connection_enabled ==1 && $isp_success >0)
				$step_array['connection']=array($step++,$this->get_label('connection'));

				if($isp_enabled ==1 && $isp_success ==2)
				$step_array['isp']=array($step++,$this->get_label('isp'));
		}
}

if($adtype == 21)
{
	$step_array['content']=array($step++,$this->get_label('ad content'));

	$step_array['pricing']=array($step++,$this->get_label('pricing'));

	$step_array['locations']=array($step++,$this->get_label('locations'));
	
	if($deviceenabled ==1)
	$step_array['device']=array($step++,$this->get_label('device'));

	if($connection_enabled ==1 && $isp_success >0)
	$step_array['connection']=array($step++,$this->get_label('connection'));

	if($isp_enabled ==1 && $isp_success ==2)
	$step_array['isp']=array($step++,$this->get_label('isp'));

	if($time_target_enabled ==1 && ($date_enabled ==1 || $time_enabled ==1 || $day_enabled ==1))
	$step_array['time']=array($step++,$this->get_label('time'));

	if($language_enabled ==1)
	$step_array['language']=array($step++,$this->get_label('language'));
}
?>
<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 px-4 mb-3 manage-ad-edit table-outer-box">
<h2 class="page-heading content-grouping" ><div class="page-inner"><i class="fa fa-pencil-square-o icon_red"></i>	<bdi><?php echo $adName;?></bdi></div>
<div class="heading-word-spacing">



	<?php if($retargeting == 1){?>
	<i class="fa fa-recycle retargeting-icon" title="<?php echo $this->get_label('retargeting');?>"></i>
	<?php }?>

	<?php	if($val['html5'] == 1){?>
	<i class="fa fa-html5 html5-icon" title="<?php echo $this->get_label('html5');?>"></i>
	<?php }	?>

	<span class="adstate">
		<bdi>
			<?php if($val['status'] == -1){?>
				<span class="pending"><i class="fa fa-spinner"></i> <?php echo $this->get_ad_status($val['status']);?></span>
			<?php }?>

			<?php if($val['status'] == 1){?>
				<span class="active"><i class="fa fa-check-square-o"></i> <?php echo $this->get_ad_status($val['status']);?></span>
			<?php }?>

			<?php if($val['status'] == 0){?>
				<span class="block"><i class="fa fa-ban"></i> <?php echo $this->get_ad_status($val['status']);?></span>
			<?php }?>

			<?php if($val['status'] == -2){?>
				<span id="draft-id" class="draft"><i class="fa fa-spinner"></i> <?php echo $this->get_ad_status($val['status']);?></span>
				<span id="active-id" class="active" style="display: none;"><i class="fa fa-check-square-o"></i> <?php echo $this->get_ad_status(1);?></span>
				<span id="pending-id" class="pending" style="display: none;"><i class="fa fa-spinner"></i> <?php echo $this->get_ad_status(-1);?></span>
			<?php }?>

			<?php if($val['status'] == 1 && $val['pause_status'] == 1){?>
			<?php echo " - ".$this->get_label('paused');?>
			<?php }?>
		</bdi>
	</span>


	<div>

		<?php if($adCloneEnabled == 1 && $val['status'] == 1 && $adtype != 7){?>
			<a href="<?php echo $this->make_url("ad/create/-1/".$aid);?>">
				<i class="fa fa-copy clone-icon" title="<?php echo $this->get_label('clone this ad');?>"></i>
			</a>
		<?php } ?>

		<?php
		if($adtype != 7 || ($adtype == 7 && $parent_ad > 0))
		{
			 if($val['status'] == 1)
			 {
				 	if($val['pause_status'] == 0){?>
							<a href="<?php echo $this->make_url("ad/update_pause_status/".$aid."/1/3");?>">
								<i class="fa fa-pause pause-icon" title="<?php echo $this->get_label('pause this ad');?>"></i>
							</a>
					<?php
				  }

					if($val['pause_status'] == 1){?>
							<a href="<?php echo $this->make_url("ad/update_pause_status/".$aid."/2/3");?>">
								<i class="fa fa-play-circle-o resume-icon" title="<?php echo $this->get_label('resume this ad');?>"></i>
							</a>
					<?php
					}
			}

			if($val['status'] != -2){?>
				<a href="<?php echo $this->make_url("ad/detailed_statistics/".$aid);?>">
					 <i class="fa fa-bar-chart report-icon" title="<?php echo $this->get_label('reports');?>"></i>
				</a>
			<?php }
		}
		?>

		<a href="<?php echo $this->make_url("ad/delete/".$aid);?>" onclick="return confirm('<?php echo $this->get_message('do you really want to delete this ad');?>')">
				<i class="fa fa-trash delete-icon" title="<?php echo $this->get_label('delete');?>"></i>
		</a>

	</div>
</div>
</h2>

	<?php
		if($adtype != 7 || ($adtype == 7 && $parent_ad > 0))
		$this->dispatch("ad/preview/".$aid."/3");
	?>
</div>


<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 p-3 mb-3 manage-ad-edit table-outer-box">

	<div class="row m-0">
		<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 my-4 my-4">
			<ul id="progressbar" class="d-flex">
				<?php
				$ii       = 0;
				$laststep = 1;
				foreach($step_array as $step_key=>$step_value){?>

					<li id="step-box-<?php echo $step_value[0];?>" class="step-box <?php if($ii == 0){?> current <?php }?>" onclick="show_tab(<?php echo $step_value[0];?>);" title="<?php echo $step_value[1];?>">
						<span class="progress-span"><?php echo $step_value[1];?></span>
					</li>

				<?php
				$laststep=intval($step_value[0]);

				$ii++;
				}
				?>
			</ul>
		</div>
	</div>

<div id="showstat<?php echo $step_array['content'][0];?>" class="tabcontent" style="display:none;">
	<div class="ad-section-background">

		<span class="next-box">
			<input class="next-previous-button" type="button" id="button-next-<?php echo $step_array['content'][0];?>" onclick="show_tab(<?php echo $step_array['content'][0]+1;?>);" value="<?php echo $this->get_label('next');?>" />
		</span>

		<iframe id="ad-iframe-<?php echo $step_array['content'][0];?>" style="width: 100%;" frameborder="0" src="<?php echo $this->make_url("ad/edit/".$aid."/".$from);?>" allowtransparency="true"></iframe>

	</div>
</div>


<?php if($pricing_value != 3){?>
	<div id="showstat<?php echo $step_array['pricing'][0];?>" class="tabcontent" style="display:none;">
		<div class="ad-section-background">

		<span class="previous-box">
			<input class="next-previous-button" type="button" id="button-previous-<?php echo $step_array['pricing'][0];?>" onclick="show_tab(<?php echo $step_array['pricing'][0]-1;?>);" value="<?php echo $this->get_label('previous');?>" />
		</span>

		<span class="next-box">
			<input class="next-previous-button" type="button" id="button-next-<?php echo $step_array['pricing'][0];?>" onclick="show_tab(<?php echo $step_array['pricing'][0]+1;?>);" value="<?php echo $this->get_label('next');?>" />
		</span>

		<iframe id="ad-iframe-<?php echo $step_array['pricing'][0];?>" style="width : 100%;" frameborder="0" src="<?php echo $this->make_base_url("ad/pricing/".$aid."/".$pricing_value."/".$step_array['pricing'][0]);?>" allowtransparency="true" ></iframe>
		</div>
	</div>

	<?php if($adtype != 7 || ($adtype == 7 && $parent_ad > 0)){

		if($adtype != 12 && $pricing_value != 18 && Configuration::get_instance()->read('keyword_based_ad_display') == 1){?>
			<div id="showstat<?php echo $step_array['keywords'][0];?>" class="tabcontent" style="display:none;">
				<div class="ad-section-background">

					<span class="previous-box">
						<input class="next-previous-button" type="button" id="button-previous-<?php echo $step_array['keywords'][0];?>" onclick="show_tab(<?php echo $step_array['keywords'][0]-1;?>);" value="<?php echo $this->get_label('previous');?>" />
					</span>

					<span class="next-box">
						<input class="next-previous-button" type="button" id="button-next-<?php echo $step_array['keywords'][0];?>" onclick="show_tab(<?php echo $step_array['keywords'][0]+1;?>);" value="<?php echo $this->get_label('next');?>" />
					</span>

					<iframe id="ad-iframe-<?php echo $step_array['keywords'][0];?>" style="width : 100%;" frameborder="0" src="<?php echo $this->make_url("ad/keywords/".$aid."/".$step_array['keywords'][0]);?>" allowtransparency="true" ></iframe>
				</div>
			</div>
		<?php }?>

		<div id="showstat<?php echo $step_array['locations'][0];?>" class="tabcontent" style="display:none;">
			<div class="ad-section-background">

				<span class="previous-box">
					<input class="next-previous-button" type="button" id="button-previous-<?php echo $step_array['locations'][0];?>" onclick="show_tab(<?php echo $step_array['locations'][0]-1;?>);" value="<?php echo $this->get_label('previous');?>" />
				</span>

				<span class="next-box">
					<input class="next-previous-button" type="button" id="button-next-<?php echo $step_array['locations'][0];?>" onclick="show_tab(<?php echo $step_array['locations'][0]+1;?>);" value="<?php echo $this->get_label('next');?>" />
				</span>

				<?php if($city_enabled == 1){?>
					<iframe id="ad-iframe-<?php echo $step_array['locations'][0];?>" style="width : 100%;" frameborder="0" src="<?php echo $this->make_base_url("city/locations/".$aid."/".$step_array['locations'][0],ADDON_DIR.'/city-targeting');?>" allowtransparency="true" ></iframe>
				<?php } else {?>
					<iframe id="ad-iframe-<?php echo $step_array['locations'][0];?>" style="width : 100%;" frameborder="0" src="<?php echo $this->make_url("ad/locations/".$aid."/".$step_array['locations'][0]);?>" allowtransparency="true" ></iframe>
				<?php }?>
			</div>
		</div>
	<?php
		}
}

if($adtype != 7 || ($adtype == 7 && $parent_ad > 0)){

	if($deviceenabled == 1 && $pricing_value != 3){?>
	<div id="showstat<?php echo $step_array['device'][0];?>" class="tabcontent" style="display:none;">
		<div class="ad-section-background">

		<span class="previous-box">
			<input class="next-previous-button" type="button" id="button-previous-<?php echo $step_array['device'][0];?>" onclick="show_tab(<?php echo $step_array['device'][0]-1;?>);" value="<?php echo $this->get_label('previous');?>" />
		</span>

		<span class="next-box">
			<input class="next-previous-button" type="button" id="button-next-<?php echo $step_array['device'][0];?>" onclick="show_tab(<?php echo $step_array['device'][0]+1;?>);" value="<?php echo $this->get_label('next');?>" />
		</span>

		<iframe id="ad-iframe-<?php echo $step_array['device'][0];?>" style="width : 100%;" frameborder="0" src="<?php echo $this->make_base_url("device/device_targeting/".$aid."/".$step_array['device'][0],ADDON_DIR.'/device-targeting');?>" allowtransparency="true"></iframe>
		</div>
	</div>
	<?php }?>

<?php if($pricing_value != 3 && $connection_enabled == 1 && $isp_success > 0){?>
	<div id="showstat<?php echo $step_array['connection'][0];?>" class="tabcontent" style="display:none;">
		<div class="ad-section-background">

			<span class="previous-box">
				<input class="next-previous-button" type="button" id="button-previous-<?php echo $step_array['connection'][0];?>" onclick="show_tab(<?php echo $step_array['connection'][0]-1;?>);" value="<?php echo $this->get_label('previous');?>" />
			</span>

			<span class="next-box">
				<input class="next-previous-button" type="button" id="button-next-<?php echo $step_array['connection'][0];?>" onclick="show_tab(<?php echo $step_array['connection'][0]+1;?>);" value="<?php echo $this->get_label('next');?>" />
			</span>

			<iframe id="ad-iframe-<?php echo $step_array['connection'][0];?>" style="width : 100%;" frameborder="0" src="<?php echo $this->make_base_url("connection/connection_targeting/".$aid."/".$step_array['connection'][0],ADDON_DIR.'/isp-connection-targeting');?>" allowtransparency="true" ></iframe>
		</div>
	</div>
<?php }

if($pricing_value != 3 && $isp_enabled == 1 && $isp_success == 2){?>
	<div id="showstat<?php echo $step_array['isp'][0];?>" class="tabcontent" style="display:none;">
		<div class="ad-section-background">

			<span class="previous-box">
				<input class="next-previous-button" type="button" id="button-previous-<?php echo $step_array['isp'][0];?>" onclick="show_tab(<?php echo $step_array['isp'][0]-1;?>);" value="<?php echo $this->get_label('previous');?>" />
			</span>

			<span class="next-box">
				<input class="next-previous-button" type="button" id="button-next-<?php echo $step_array['isp'][0];?>" onclick="show_tab(<?php echo $step_array['isp'][0]+1;?>);" value="<?php echo $this->get_label('next');?>" />
			</span>

			<iframe id="ad-iframe-<?php echo $step_array['isp'][0];?>" style="width : 100%;" frameborder="0"  src="<?php echo $this->make_base_url("isp/isp_targeting/".$aid."/".$step_array['isp'][0],ADDON_DIR.'/isp-connection-targeting');?>" allowtransparency="true" ></iframe>
		</div>
	</div>
<?php }
}
?>

<?php if($adtype != 12){

if($adtype != 7 || ($adtype == 7 && $parent_ad > 0)){

if($sponsored == 1 && $pricing_value == 3){?>

	<div id="showstat<?php echo $step_array['position'][0];?>" class="tabcontent" style="display:none;">
		<div class="ad-section-background">
			<span class="previous-box">
				<input class="next-previous-button" type="button" id="button-previous-<?php echo $step_array['position'][0];?>" onclick="show_tab(<?php echo $step_array['position'][0]-1;?>);" value="<?php echo $this->get_label('previous');?>" />
			</span>

			<span class="next-box">
				<input class="next-previous-button" type="button" id="button-next-<?php echo $step_array['position'][0];?>" onclick="show_tab(<?php echo $step_array['position'][0]+1;?>);" value="<?php echo $this->get_label('next');?>" />
			</span>

			<iframe id="ad-iframe-<?php echo $step_array['position'][0];?>" style="width : 100%;" frameborder="0" src="<?php echo $this->make_base_url("site/cpd_ad_targeting/".$aid."/0/".$step_array['position'][0],ADDON_DIR.'/sponsored');?>" allowtransparency="true" ></iframe>
		</div>
	</div>
<?php }?>

<?php if($pricing_value != 3 && $category_enabled == 1 && in_array($pricing_value,$stringarray)){?>
	<div id="showstat<?php echo $step_array['category'][0];?>" class="tabcontent" style="display:none;">
		<div class="ad-section-background">

			<span class="previous-box">
				<input class="next-previous-button" type="button" id="button-previous-<?php echo $step_array['category'][0];?>" onclick="show_tab(<?php echo $step_array['category'][0]-1;?>);" value="<?php echo $this->get_label('previous');?>" />
			</span>

			<span class="next-box">
				<input class="next-previous-button" type="button" id="button-next-<?php echo $step_array['category'][0];?>" onclick="show_tab(<?php echo $step_array['category'][0]+1;?>);" value="<?php echo $this->get_label('next');?>" />
			</span>

			<iframe id="ad-iframe-<?php echo $step_array['category'][0];?>" style="width : 100%;" frameborder="0" src="<?php echo $this->make_base_url("category/category_targeting/".$aid."/".$step_array['category'][0],ADDON_DIR.'/category-targeting');?>" allowtransparency="true" ></iframe>

		</div>
	</div>
<?php }?>

<?php if($pricing_value != 3){

if($retargeting == 1){?>
	<div id="showstat<?php echo $step_array['retarget'][0];?>" class="tabcontent" style="display:none;">
		<div class="ad-section-background">

			<span class="previous-box">
				<input class="next-previous-button" type="button" id="button-previous-<?php echo $step_array['retarget'][0];?>" onclick="show_tab(<?php echo $step_array['retarget'][0]-1;?>);" value="<?php echo $this->get_label('previous');?>" />
			</span>

			<span class="next-box">
				<input class="next-previous-button" type="button" id="button-next-<?php echo $step_array['retarget'][0];?>" onclick="show_tab(<?php echo $step_array['retarget'][0]+1;?>);" value="<?php echo $this->get_label('next');?>" />
			</span>

			<iframe id="ad-iframe-<?php echo $step_array['retarget'][0];?>" style="width : 100%;" frameborder="0" src="<?php echo $this->make_base_url("retargeting/ad_retargeting/".$aid."/".$step_array['retarget'][0],ADDON_DIR.'/retargeting');?>" allowtransparency="true" ></iframe>
		</div>
	</div>
<?php }?>

<?php if($time_target_enabled ==1 && ($date_enabled ==1 || $time_enabled ==1 || $day_enabled ==1)){?>
	<div id="showstat<?php echo $step_array['time'][0];?>" class="tabcontent" style="display:none;">
		<div class="ad-section-background">

			<span class="previous-box">
				<input class="next-previous-button" type="button" id="button-previous-<?php echo $step_array['time'][0];?>" onclick="show_tab(<?php echo $step_array['time'][0]-1;?>);" value="<?php echo $this->get_label('previous');?>" />
			</span>

			<span class="next-box">
				<input class="next-previous-button" type="button" id="button-next-<?php echo $step_array['time'][0];?>" onclick="show_tab(<?php echo $step_array['time'][0]+1;?>);" value="<?php echo $this->get_label('next');?>" />
			</span>

			<iframe id="ad-iframe-<?php echo $step_array['time'][0];?>" style="width : 100%;" frameborder="0" src="<?php echo $this->make_base_url("time/time_targeting/".$aid."/".$step_array['time'][0],ADDON_DIR.'/time-targeting');?>" allowtransparency="true" ></iframe>
		</div>
	</div>
<?php }?>

<?php if($language_enabled == 1){?>
	<div id="showstat<?php echo $step_array['language'][0];?>" class="tabcontent" style="display:none;">
		<div class="ad-section-background">

			<span class="previous-box">
				<input class="next-previous-button" type="button" id="button-previous-<?php echo $step_array['language'][0];?>" onclick="show_tab(<?php echo $step_array['language'][0]-1;?>);" value="<?php echo $this->get_label('previous');?>" />
			</span>

			<span class="next-box">
				<input class="next-previous-button" type="button" id="button-next-<?php echo $step_array['language'][0];?>" onclick="show_tab(<?php echo $step_array['language'][0]+1;?>);" value="<?php echo $this->get_label('next');?>" />
			</span>

			<iframe id="ad-iframe-<?php echo $step_array['language'][0];?>" style="width: 100%;" frameborder="0" src="<?php echo $this->make_base_url("language/language_targeting/".$aid."/".$step_array['language'][0],ADDON_DIR.'/language-targeting');?>" allowtransparency="true" ></iframe>

		</div>
	</div>
<?php }
 		}
	}
}
?>
</div>

<?php $this->dispatch("layout/footer");?>

<script type="text/javascript">
function LoadNextContent()
{
	if(!validate_create())
	retun;

	pricing=<?php echo $pricing_value;?>;


	if($('#step-box-'+id).length >0)
	{
		$('.step-box').removeClass("current");
		$('#step-box-'+id).addClass("current");

	}
}

function LoadPreviousContent()
{
	if($('#step-box-'+id).length >0)
	{
		$('.step-box').removeClass("current");
		$('#step-box-'+id).addClass("current");
	}
}

var laststep = <?php echo $laststep;?>;

if($('#button-next-'+laststep).length >0)
$('#button-next-'+laststep).hide();

$(window).on('load', function () 
{
		<?php if($from > 0){?>
		show_tab(<?php echo $from; ?>);
		<?php }else{
		if($adstatus == -2){?>
		show_tab(2);
		<?php }else{?>
		show_tab(1);
		<?php }}?>
});

$(window).resize(function()
{
		var selectedID      = $(".step-box.current").attr("id");
				selectedIDArray = selectedID.split("-");

				if(selectedIDArray.length == 3 && parseInt(selectedIDArray[2]) > 0)
				show_tab(selectedIDArray[2]);
});
</script>
