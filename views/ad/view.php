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


$language_enabled=$this->get_addon_status('language-targeting_enabled');


$video_enabled=$this->get_addon_status('video-ads_enabled');


$adtype=$this->get_variable('adtype');
$parent_ad=$this->get_variable('parent_ad');


$isp_success=$this->get_variable('isp_success');

$adrow=$this->get_result("adrow");
$val=$adrow[0];



$pricing_value=$val['display_type'];



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


if($adtype ==2 || $adtype ==5 || $adtype ==7 || $adtype ==10 || $adtype ==11)
{
	$diamensions_string=$this->get_banner_dimension($val['banner_id']);
	
	$diamensions_array=explode('-',$diamensions_string);
	
	$diamensions=$diamensions_array[0]." x ".$diamensions_array[1];
}



$stringarray=array();
if($category_enabled ==1)
{
	$category_enabled_ads=Configuration::get_instance()->read('category_enabled_ads');
				
	if($category_enabled_ads !='')
	$stringarray=explode('_',$category_enabled_ads);
}
?>

<script type="text/javascript">
function show_tab(id)
{
	if(id==14)
		document.getElementById('isp-iframe').contentDocument.location.reload(true);
	
	$('.tabcontent').hide();
	$('.report_main_table_tab').removeClass('report_main_table_tab_temp');
	
	$('#showstat'+id).show();
	$('#tab_'+id).addClass('report_main_table_tab_temp');


	if($('#normal-'+id).length >0)
	{
		$('.step-box-normal').removeClass("current");
		$('#normal-'+id).addClass("current");
	}
}
</script>	

<style type="text/css">


<?php if($adstatus != -2){?>
.step-box
{
	cursor: pointer;
}
<?php }?>



.report_main_table_tab
{
max-width:14%;
}

@media (max-width: 767px)
{
	.report_main_table_tab
	{
		max-width:100%;
	}
}



</style>


<div class="container">
<h2 class="page_heading"><?php echo $this->get_label('ad details');?>

<div style="float: right;">
<a href="<?php echo $this->make_url("ad/delete/".$aid);?>" onclick="return confirm('<?php echo $this->get_message('do you really want to delete this ad');?>')"><i class="fa fa-trash delete-icon" title="<?php echo $this->get_label('delete');?>"></i></a>

<?php if($adtype !=7 || ($adtype ==7 && $parent_ad >0)){?>
<?php if($val['status'] ==1){
if($val['pause_status'] ==0){?>	
<a href="<?php echo $this->make_url("ad/update_pause_status/".$aid."/1/3");?>"><i class="fa fa-pause pause-icon" title="<?php echo $this->get_label('pause this ad');?>"></i></a>
<?php }if($val['pause_status']==1){?>
<a href="<?php echo $this->make_url("ad/update_pause_status/".$aid."/2/3");?>"><i class="fa fa-play-circle-o resume-icon" title="<?php echo $this->get_label('resume this ad');?>"></i></a>
<?php }}?>

<?php if($val['status'] !=-2){?>
<a href="<?php echo $this->make_url("ad/detailed_statistics/".$aid);?>"><i class="fa fa-bar-chart report-icon" title="<?php echo $this->get_label('reports');?>"></i></a>
<?php }?>
<?php }?>
</div>
</h2>
<div class="page_heading-btm"></div>
</div>

<div class="container">
<h2 class="login_header_inner view_head_div search_div" style="margin-bottom: 8px;width: 100%;padding: 10px;min-height: 40px;"><bdi>

<?php if($retargeting ==1){?>
<i class="fa fa-recycle" title="<?php echo $this->get_label('retargeting');?>"></i>&nbsp;
<?php }?>

<?php echo $this->get_label('name');?> : <?php echo $val['name'];?><?php if($adtype ==2 || $adtype ==7){ echo  ' - '.$diamensions;}?>

<?php if($adtype ==2 && $expandable ==1){ echo $this->get_label('expandable');} ?>
<?php 
echo " - ".$this->get_ad_pricing($aid,$pricing_value);
		
	
if($adtype ==13)
{
	$aspect_ratio=$this->get_aspect_ratio_value($val['aspect_ratio']);
	
	if($aspect_ratio >0)
	echo '<span style="font-size:12px;"> - '.$this->get_label('aspect ratio').' : '.$aspect_ratio.'</span>';
}
?>


</bdi>
<div class="adstate span_link_inner2"><bdi>

<?php echo $this->get_label('status');?> : 
<?php if($val['status']==-1) {?><span class="pending"><i class="fa fa-spinner"></i> <?php echo $this->get_ad_status($val['status']);?></span><?php }?>
<?php if($val['status']==1) {?><span class="active"><i class="fa fa-check-square-o"></i> <?php echo $this->get_ad_status($val['status']);?></span><?php }?>
<?php if($val['status']==0) {?><span class="block"><i class="fa fa-ban"></i> <?php echo $this->get_ad_status($val['status']);?></span><?php }?>
<?php 
if($val['status']==-2) 
{?>
<span id="draft-id" class="pending"><i class="fa fa-spinner"></i> <?php echo $this->get_ad_status($val['status']);?></span>

<span id="active-id" class="active" style="display: none;"><i class="fa fa-check-square-o"></i> <?php echo $this->get_ad_status(1);?></span>
<span id="pending-id" class="pending" style="display: none;"><i class="fa fa-spinner"></i> <?php echo $this->get_ad_status(-1);?></span>

<?php }?>
<?php if($val['status']==1 && $val['pause_status']==1){?>
(<?php echo $this->get_label('paused');?>)	
<?php }?></bdi>
</div>
</h2>
</div>


<?php if($adtype !=7 || ($adtype ==7 && $parent_ad >0)){?>
<div class="container"><?php $this->dispatch("ad/preview/".$aid."/3");?></div>
<?php }?>


<div class="container" style="margin-top: 20px;">
<div class="col-md-12 col-sm-12 col-xs-12 stepdiv" style="display: block;">
<div class="row">

<?php 

$keyword_enabled=Configuration::get_instance()->read('keyword_based_ad_display');
$date_enabled=Configuration::get_instance()->read('date_filter_enabled');
$time_enabled=Configuration::get_instance()->read('time_filter_enabled');
$day_enabled=Configuration::get_instance()->read('day_filter_enabled');


$step_array=array();

$step=1;


if($pricing_value !=3 && $pricing_value !=12)
{
	$step_array['content']=array($step++,$this->get_label('ad content'));
	
	
	if($adtype !=7 || ($adtype ==7 && $parent_ad >0))
	{
		$step_array['locations']=array($step++,$this->get_label('locations'));
		
		if($keyword_enabled ==1 && ($device ==0 || $device ==2))
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
	
	
	
	$step_array['pricing']=array($step++,$this->get_label('pricing'));		
}



if($pricing_value ==3)
{
	$step_array['content']=array($step++,$this->get_label('ad content'));
	
	if($adtype !=7 || ($adtype ==7 && $parent_ad >0))
	{	
		if($category_enabled ==1)
		$step_array['category']=array($step++,$this->get_label('category'));	
		
		$step_array['position']=array($step++,$this->get_label('position mapping'));
	}
}


if($pricing_value ==12)
{
	$step_array['content']=array($step++,$this->get_label('ad content'));
	
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
	
	$step_array['pricing']=array($step++,$this->get_label('pricing'));
}

?>
<ul class="steps steps-5">
<?php

$ii=0;
$laststep=1;
foreach($step_array as $step_key=>$step_value){?>

<li class="step-box step-box-normal <?php if($ii ==0){?> current <?php }?>" id="normal-<?php echo $step_value[0];?>" onclick="show_tab(<?php echo $step_value[0];?>);">

<em><?php echo $this->get_label('step').' '.$step_value[0];?></em>
<span><?php echo $step_value[1];?></span>
</li>


<?php 
$laststep=intval($step_value[0]);

$ii++;
}?>


</ul>

</div>
</div>
</div>


<div class="container label_style special-label">
<div class="under_li_main" style="margin-top:0px;"></div>
<div id="showstat<?php echo $step_array['content'][0];?>" class="tabcontent">
<div class="report_main_table_data new_box_outer">


<?php if($from != 1){?>
<span class="next-box" style="float: right;">
<input class="create_ad_btn" type="button" id="button-next-<?php echo $step_array['content'][0];?>" onclick="show_tab(<?php echo $step_array['content'][0]+1;?>);" value="<?php echo $this->get_label('next');?>" />
</span>
<?php }?>

<iframe id="ad-content-iframe" style="<?php if($pricing_value ==12){?>height: 1700px;<?php }else if($adtype ==7){?>height: 1300px;<?php }else{?>height: 700px;<?php }?>width: 100%;" frameborder="0" src="<?php echo $this->make_url("ad/edit/".$aid."/".$from);?>" allowtransparency="true" scrolling="yes"></iframe>

</div>
</div>


 
<?php if($pricing_value !=3){ ?>
<div id="showstat<?php echo $step_array['pricing'][0];?>" class="tabcontent">
<div class="report_main_table_data new_box_outer">


<span class="previous-box">
<input class="create_ad_btn" type="button" id="button-previous-<?php echo $step_array['pricing'][0];?>" onclick="show_tab(<?php echo $step_array['pricing'][0]-1;?>);" value="<?php echo $this->get_label('previous');?>" />
</span>

<span class="next-box">
<input class="create_ad_btn" type="button" id="button-next-<?php echo $step_array['pricing'][0];?>" onclick="show_tab(<?php echo $step_array['pricing'][0]+1;?>);" value="<?php echo $this->get_label('next');?>" />
</span>

<iframe id="ad-pricing-iframe" height="633px" width="100%" frameborder="0" src="<?php echo $this->make_base_url("ad/pricing/".$aid."/".$pricing_value."/".$step_array['pricing'][0]);?>" allowtransparency="true" ></iframe>
</div>
</div>



<?php if(Configuration::get_instance()->read('keyword_based_ad_display') ==1){?>

<?php if(($device ==0 || $device ==2) && $pricing_value != 12){?>
<div id="showstat<?php echo $step_array['keywords'][0];?>" class="tabcontent"> 
<div class="report_main_table_data new_box_outer" style="padding-bottom:20px;">



<span class="previous-box">
<input class="create_ad_btn" type="button" id="button-previous-<?php echo $step_array['keywords'][0];?>" onclick="show_tab(<?php echo $step_array['keywords'][0]-1;?>);" value="<?php echo $this->get_label('previous');?>" />
</span>

<span class="next-box">
<input class="create_ad_btn" type="button" id="button-next-<?php echo $step_array['keywords'][0];?>" onclick="show_tab(<?php echo $step_array['keywords'][0]+1;?>);" value="<?php echo $this->get_label('next');?>" />
</span>



<iframe id="ad-keyword-iframe" height="800px" width="100%" frameborder="0" src="<?php echo $this->make_url("ad/keywords/".$aid."/".$step_array['keywords'][0]);?>" allowtransparency="true" ></iframe>
</div>
</div>
<?php }?>
<?php }?>
  
  
 
<div id="showstat<?php echo $step_array['locations'][0];?>" class="tabcontent">
<div class="report_main_table_data new_box_outer">

<span class="previous-box">
<input class="create_ad_btn" type="button" id="button-previous-<?php echo $step_array['locations'][0];?>" onclick="show_tab(<?php echo $step_array['locations'][0]-1;?>);" value="<?php echo $this->get_label('previous');?>" />
</span>

<span class="next-box">
<input class="create_ad_btn" type="button" id="button-next-<?php echo $step_array['locations'][0];?>" onclick="show_tab(<?php echo $step_array['locations'][0]+1;?>);" value="<?php echo $this->get_label('next');?>" />
</span>



<?php if($city_enabled ==1){?>
<iframe id="ad-location-iframe" height="700px" width="100%" frameborder="0" src="<?php echo $this->make_base_url("city/locations/".$aid."/".$step_array['locations'][0],ADDON_DIR.'/city-targeting');?>" allowtransparency="true" ></iframe>
<?php }else{?>
<iframe style="min-height:435px;" id="ad-location-iframe" width="100%" frameborder="0" src="<?php echo $this->make_url("ad/locations/".$aid."/".$step_array['locations'][0]);?>" allowtransparency="true" ></iframe>
<?php }?>
</div>
</div>
<?php }?>


<?php if($deviceenabled ==1 && $pricing_value !=3){?>
<div id="showstat<?php echo $step_array['device'][0];?>" class="tabcontent">
<div class="report_main_table_data new_box_outer">


<span class="previous-box">
<input class="create_ad_btn" type="button" id="button-previous-<?php echo $step_array['device'][0];?>" onclick="show_tab(<?php echo $step_array['device'][0]-1;?>);" value="<?php echo $this->get_label('previous');?>" />
</span>

<span class="next-box">
<input class="create_ad_btn" type="button" id="button-next-<?php echo $step_array['device'][0];?>" onclick="show_tab(<?php echo $step_array['device'][0]+1;?>);" value="<?php echo $this->get_label('next');?>" />
</span>


<iframe id="ad-device-iframe" height="430px" width="100%" frameborder="0" src="<?php echo $this->make_base_url("device/device_targeting/".$aid."/".$step_array['device'][0],ADDON_DIR.'/device-targeting');?>" allowtransparency="true" scroll="no" ></iframe>
</div>
</div>  
<?php }?>

<?php if( $connection_enabled ==1 && $isp_success>0){?>  
<div id="showstat<?php echo $step_array['connection'][0];?>" class="tabcontent">
<div class="report_main_table_data new_box_outer" style="padding-bottom: 10px;">



<span class="previous-box">
<input class="create_ad_btn" type="button" id="button-previous-<?php echo $step_array['connection'][0];?>" onclick="show_tab(<?php echo $step_array['connection'][0]-1;?>);" value="<?php echo $this->get_label('previous');?>" />
</span>

<span class="next-box">
<input class="create_ad_btn" type="button" id="button-next-<?php echo $step_array['connection'][0];?>" onclick="show_tab(<?php echo $step_array['connection'][0]+1;?>);" value="<?php echo $this->get_label('next');?>" />
</span>


<iframe id="conn-iframe" height="533px" width="100%" frameborder="0" scrolling="no" src="<?php echo $this->make_base_url("connection/connection_targeting/".$aid."/".$step_array['connection'][0],ADDON_DIR.'/isp-connection-targeting');?>" allowtransparency="true" ></iframe>

</div>
</div>
<?php }



if($isp_enabled ==1 && $isp_success==2){?>

<div id="showstat<?php echo $step_array['isp'][0];?>" class="tabcontent">
<div class="report_main_table_data new_box_outer" style="padding-bottom: 10px;">



<span class="previous-box">
<input class="create_ad_btn" type="button" id="button-previous-<?php echo $step_array['isp'][0];?>" onclick="show_tab(<?php echo $step_array['isp'][0]-1;?>);" value="<?php echo $this->get_label('previous');?>" />
</span>

<span class="next-box">
<input class="create_ad_btn" type="button" id="button-next-<?php echo $step_array['isp'][0];?>" onclick="show_tab(<?php echo $step_array['isp'][0]+1;?>);" value="<?php echo $this->get_label('next');?>" />
</span>

<iframe id="isp-iframe" height="533px" width="100%" frameborder="0"  src="<?php echo $this->make_base_url("isp/isp_targeting/".$aid."/".$step_array['isp'][0],ADDON_DIR.'/isp-connection-targeting');?>" allowtransparency="true" ></iframe>

</div>
</div>


<?php }?>

<?php if($pricing_value !=12){?>  
<?php if($sponsored ==1 && $pricing_value ==3){?>

	<div id="showstat<?php echo $step_array['position'][0];?>" class="tabcontent">
	<div class="report_main_table_data new_box_outer">
	
	<span class="previous-box">
	<input class="create_ad_btn" type="button" id="button-previous-<?php echo $step_array['position'][0];?>" onclick="show_tab(<?php echo $step_array['position'][0]-1;?>);" value="<?php echo $this->get_label('previous');?>" />
	</span>
	
	<span class="next-box">
	<input class="create_ad_btn" type="button" id="button-next-<?php echo $step_array['position'][0];?>" onclick="show_tab(<?php echo $step_array['position'][0]+1;?>);" value="<?php echo $this->get_label('next');?>" />
	</span>
	
	<iframe id="ad-position-iframe" height="533px" width="100%" frameborder="0" src="<?php echo $this->make_base_url("site/position_mapping/".$aid."/0/".$step_array['position'][0],ADDON_DIR.'/sponsored');?>" allowtransparency="true" ></iframe>
	</div>
	</div>
<?php }?>
  
<?php if($category_enabled ==1 && in_array($pricing_value,$stringarray)){?>  
<div id="showstat<?php echo $step_array['category'][0];?>" class="tabcontent">
<div class="report_main_table_data new_box_outer" style="padding-bottom: 10px;">

<span class="previous-box">
<input class="create_ad_btn" type="button" id="button-previous-<?php echo $step_array['category'][0];?>" onclick="show_tab(<?php echo $step_array['category'][0]-1;?>);" value="<?php echo $this->get_label('previous');?>" />
</span>

<span class="next-box">
<input class="create_ad_btn" type="button" id="button-next-<?php echo $step_array['category'][0];?>" onclick="show_tab(<?php echo $step_array['category'][0]+1;?>);" value="<?php echo $this->get_label('next');?>" />
</span>


<iframe id="ad-category-iframe" height="533px" width="100%" frameborder="0" src="<?php echo $this->make_base_url("category/category_targeting/".$aid."/".$step_array['category'][0],ADDON_DIR.'/category-targeting');?>" allowtransparency="true" ></iframe>

</div>
</div>
<?php }?>
  


<?php if($pricing_value !=3){?>

<?php if($retargeting){?>
<div id="showstat<?php echo $step_array['retarget'][0];?>" class="tabcontent">
<div class="report_main_table_data new_box_outer">

<span class="previous-box">
<input class="create_ad_btn" type="button" id="button-previous-<?php echo $step_array['retarget'][0];?>" onclick="show_tab(<?php echo $step_array['retarget'][0]-1;?>);" value="<?php echo $this->get_label('previous');?>" />
</span>

<span class="next-box">
<input class="create_ad_btn" type="button" id="button-next-<?php echo $step_array['retarget'][0];?>" onclick="show_tab(<?php echo $step_array['retarget'][0]+1;?>);" value="<?php echo $this->get_label('next');?>" />
</span>

<iframe id="ad-retargeting-iframe" height="333px" width="100%" frameborder="0" src="<?php echo $this->make_base_url("retargeting/ad_retargeting/".$aid."/".$step_array['retarget'][0],ADDON_DIR.'/retargeting');?>" allowtransparency="true" ></iframe>
</div>
</div>
<?php }?>


<?php if($time_target_enabled ==1 && ($date_enabled ==1 || $time_enabled ==1 || $day_enabled ==1)){?>
<div id="showstat<?php echo $step_array['time'][0];?>" class="tabcontent">
<div class="report_main_table_data new_box_outer">

<span class="previous-box">
<input class="create_ad_btn" type="button" id="button-previous-<?php echo $step_array['time'][0];?>" onclick="show_tab(<?php echo $step_array['time'][0]-1;?>);" value="<?php echo $this->get_label('previous');?>" />
</span>

<span class="next-box">
<input class="create_ad_btn" type="button" id="button-next-<?php echo $step_array['time'][0];?>" onclick="show_tab(<?php echo $step_array['time'][0]+1;?>);" value="<?php echo $this->get_label('next');?>" />
</span>

<iframe id="ad-time-iframe" height="833px" width="100%" frameborder="0" src="<?php echo $this->make_base_url("time/time_targeting/".$aid."/".$step_array['time'][0],ADDON_DIR.'/time-targeting');?>" allowtransparency="true" ></iframe>
</div>
</div>  
<?php }?>



<?php if($language_enabled ==1){?>  
<div id="showstat<?php echo $step_array['language'][0];?>" class="tabcontent">
<div class="report_main_table_data new_box_outer" style="padding-bottom: 10px;">

<span class="previous-box">
<input class="create_ad_btn" type="button" id="button-previous-<?php echo $step_array['language'][0];?>" onclick="show_tab(<?php echo $step_array['language'][0]-1;?>);" value="<?php echo $this->get_label('previous');?>" />
</span>

<span class="next-box">
<input class="create_ad_btn" type="button" id="button-next-<?php echo $step_array['language'][0];?>" onclick="show_tab(<?php echo $step_array['language'][0]+1;?>);" value="<?php echo $this->get_label('next');?>" />
</span>

<iframe id="browser_lang-iframe" style="height: 450px;width: 100%;" frameborder="0" scrolling="no" src="<?php echo $this->make_base_url("language/language_targeting/".$aid."/".$step_array['language'][0],ADDON_DIR.'/language-targeting');?>" allowtransparency="true" ></iframe>

</div>
</div>
<?php }?>


<?php }}?>




</div>
<div style="height: 20px;"></div>

<?php $this->dispatch("layout/footer");?>

<script type="text/javascript">
function LoadNextContent()
{
	if(!validate_create())
	retun;
	
	pricing=<?php echo $pricing_value;?>;

	
	if($('#normal-'+id).length >0)
	{
		$('.step-box-normal').removeClass("current");
		$('#normal-'+id).addClass("current");

	}
}

function LoadPreviousContent()
{
	if($('#normal-'+id).length >0)
	{
		$('.step-box-normal').removeClass("current");
		$('#normal-'+id).addClass("current");
	}
}


function LoadStepBox()
{
	if($('.step-box-normal').length >0)
	{
		step_length=$('.step-box-normal').length;

		outer_width=$('.stepdiv').outerWidth();

		total_width=outer_width-(step_length*3);  

		$('.step-box-normal').css("width",(total_width/step_length)+'px');
	}
}


$(document).ready(function() {
	LoadStepBox();
	
	$(window).resize(function()
	{
		LoadStepBox();
	});
});


var laststep=<?php echo $laststep;?>;

<?php if($from > 0){?>
show_tab(<?php echo $from; ?>);
<?php }else{
if($adstatus == -2){?>
show_tab(laststep);
<?php }else{?>
show_tab(1);
<?php }}?>


if($('#button-next-'+laststep).length >0)
$('#button-next-'+laststep).hide();	

</script>
