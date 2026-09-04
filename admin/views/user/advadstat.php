<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link rel="stylesheet" href="<?php echo BASE.ADMIN_DIR."/css/style.css";?>" type="text/css" />
<link rel="stylesheet" href="<?php echo COMMON_DIR_PATH;?>font-awesome/css/font-awesome.css" />

<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/jquery-1.7.min.js'></script>
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>jquery-ui/jquery-ui.min.js'></script>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo COMMON_DIR_PATH;?>jquery-ui/jquery-ui.min.css" />


<script type="text/javascript">
$(document).ready(function() {
	 $("#from_date").datepicker({dateFormat : 'dd/mm/yy'});
	 $("#to_date").datepicker({dateFormat : 'dd/mm/yy'});
});
</script>
<script type="text/javascript">
$(document).ready(function() {

$('#duration').change(function()
{
	ShowHideDate();
});

ShowHideDate();
});

function ShowHideDate()
{
	if($('#duration').val() ==7)
	{
		$('#from_date').show();
		$('#to_date').show();
	}
	else
	{
		$('#from_date').hide();
		$('#to_date').hide();

		$('#from_date').val('');
		$('#to_date').val('');
	}
}
</script>
<?php
$from_date=$this->get_variable('from_date');
$to_date=$this->get_variable('to_date');

if($from_date !='' && $to_date =='')
$to_date=date("d",time()).'/'.date("m",time()).'/'.date("Y",time());

$premium_ad_enabled = Configuration::get_instance()->read('allow_premium_ads');

?>


</head>
<body style="background: none;">

<table  class="iframe_table" cellpadding="0" cellspacing="0" border="0"  >

<tr><td height="10px"></td></tr>
<tr><td colspan="10">
<?php
$textimage_enabled 		= $this->get_addon_status('text-image-ads_enabled');
$skin_enabled 			= $this->get_addon_status('skin-ads_enabled');
$cpm_enabled            = $this->get_addon_status('cpm_enabled');
$pop_enabled            = $this->get_addon_status('pop-ads_enabled');
$directlink_enabled 		= $this->get_addon_status('direct-link-ads_enabled');
$text_ads_enabled 		= $this->get_variable('text_ads_enabled');
$retargeting_enabled	= $this->get_addon_status('retargeting_enabled');
$ecommerce_enabled 		= $this->get_variable('ecommerce_enabled');
$cpa_enabled=$this->get_addon_status('cpa_enabled');
$affiliate_enabled = $this->get_addon_status('affiliate-ads_enabled');
$cpp_enabled=$this->get_addon_status('cpp_enabled');
$video_enabled = $this->get_addon_status('video-ads_enabled');


$type 					= $this->get_variable('type');
$status 				= $this->get_variable('status');
$duration 			= $this->get_variable('duration');
$adpricing 			= $this->get_variable('adpricing');
$premiumStatus  = $this->get_variable('premiumStatus');

$pg 					= $this->get_variable('pg');

$uid 					= $this->get_variable('uid');

if($from_date =='' && $duration ==7)
$duration=1;

$form2=$this->create_form();
$form2->start("manageads",$this->make_url("user/advadstat/".$uid),"post");
?>
<div class="search_div" style="height: 40px;">
<table class="search_div_table" style="vertical-align: top;">
<tr>
<td style="width: 150px;">
<select name="duration" id="duration">
<option value="1" <?php if($duration==1) { echo "selected"; } ?>><?php echo $this->get_label('today');?></option>
<option value="6" <?php if($duration==6) { echo "selected"; } ?>><?php echo $this->get_label('yesterday');?></option>
<option value="2" <?php if($duration==2) { echo "selected"; } ?>><?php echo $this->get_label('last 14');?></option>
<option value="3" <?php if($duration==3) { echo "selected"; } ?>><?php echo $this->get_label('last 30');?></option>
<option value="4" <?php if($duration==4) { echo "selected"; } ?>><?php echo $this->get_label('last 12 month');?></option>
<option value="5" <?php if($duration==5) { echo "selected"; } ?>><?php echo $this->get_label('all time');?></option>
<option value="7" <?php if($duration==7) { echo "selected"; } ?>><?php echo $this->get_label('custom date');?></option>
</select>
</td>
<td style="width: 140px;">
<input type="text" readonly="readonly" name="from_date" id="from_date" value="<?php echo $from_date;?>" placeholder="From Date"  />

<input type="text" readonly="readonly" name="to_date" id="to_date" value="<?php echo $to_date;?>" placeholder="To Date"  />
</td>
<td style="width: 160px;">
  <?php echo $this->get_pricing_box($adpricing,1);?>
</td>
<td style="width: 160px;" class="type-td">
<select name="type" id="type">
<option value="0" <?php if($type==0) echo "selected"; ?>><?php echo $this->get_label('allads');?></option>

<?php if($text_ads_enabled ==1){?>
<option class="ad-option" value="1" <?php if($type==1) echo "selected"; ?>><?php echo $this->get_label('textad');?></option>
<?php }?>

<option class="ad-option" value="2" <?php if($type==2) echo "selected"; ?>><?php echo $this->get_label('bannerad');?></option>

<?php if($textimage_enabled ==1){?>
<option class="ad-option" value="11" <?php if($type ==11) {echo "selected";}?>><?php echo $this->get_label('textimage ad');?></option>
<?php }?>

<?php if($ecommerce_enabled ==1){?>
<option class="ad-option" value="7" <?php if($type ==7) {echo "selected";}?>><?php echo $this->get_label('ecommerce ad');?></option>
<?php }?>

<?php if($skin_enabled ==1){?>
<option class="ad-option" value="14" <?php if($type ==14) {echo "selected";}?>><?php echo $this->get_label('skin ad');?></option>
<?php }?>

<?php if($cpp_enabled ==1){?>
<option class="ad-option" value="18" <?php if($type ==18) {echo "selected";}?>><?php echo $this->get_label('notification ad');?></option>
<?php }?>

<?php if($pop_enabled ==1 && $cpm_enabled == 1){?>
<option class="pop-option" style="display:none;" value="9" <?php if($type ==9) {echo "selected";}?>><?php echo $this->get_label('popad');?></option>
<?php }?>

<?php if($directlink_enabled ==1){?>
<option class="directlink-option" style="display:none;" value="21" <?php if($type ==21) {echo "selected";}?>><?php echo $this->get_label('directlink ad');?></option>
<?php }?>

<?php if($video_enabled ==1 && $cpm_enabled == 1){?>
<option class="video-option" style="display:none;" value="13" <?php if($type == 13) {echo "selected";}?>><?php echo $this->get_label('video ad');?></option>
<?php }?>

<?php if($cpa_enabled == 1 && $affiliate_enabled ==1){?>
<option class="affiliate-option" style="display:none;" value="12" <?php if($type == 12) {echo "selected";}?>><?php echo $this->get_label('affiliate ad');?></option>
<?php }?>
</select>
</td>
<td style="width: 140px;">
<select name="status" id="status">
<option value="2" <?php if($status==2) echo "selected"; ?>><?php echo $this->get_label('allstatus');?></option>
<option value="1" <?php if($status==1) echo "selected"; ?>><?php echo $this->get_label('active');?></option>
<option value="-1" <?php if($status==-1) echo "selected"; ?>><?php echo $this->get_label('pending');?></option>
<option value="0" <?php if($status==0) echo "selected"; ?>><?php echo $this->get_label('blocked');?></option>
</select>
</td>

<?php if($premium_ad_enabled == 1){?>
<td style="width: 140px;">
<select name="premiumStatus" id="premiumStatus">
<option value="2" <?php if($premiumStatus == 2) echo "selected"; ?>><?php echo $this->get_label('premium status');?></option>
<option value="1" <?php if($premiumStatus == 1) echo "selected"; ?>><?php echo $this->get_label('premium');?></option>
<option value="0" <?php if($premiumStatus == 0) echo "selected"; ?>><?php echo $this->get_label('non premium');?></option>
</select>
</td>
<?php } ?>

<td> <input type="submit" name="search" class="link_button" value="<?php echo $this->get_label('go');?>" /></td>
</tr>
</table>
</div>
<?php $form2->end(); ?>
</td></tr>

<tr><td colspan="10" height="10px"></td></tr>

<?php
$adResult = $this->get_array('adResult');
?>

<tr><td colspan="10">
<table style="width: 99%;margin: 4px;" cellpadding="0" cellspacing="0" class="data_table_new">
<tr class="data_table_head">
<td><?php echo $this->get_label('name');?></td>
<td><?php echo $this->get_label('pricing');?></td>
<td><?php echo $this->get_label('ad status');?></td>
<td><?php echo $this->get_label('pricing status');?></td>
<?php
if(isset($adResult['heading']))
{
	foreach($adResult['heading'] as $hkey => $hvalue)
	{
		?>
		<td><bdi><?php echo $this->get_label($hvalue);?></bdi></td>
		<?php
	}
}
?>
<td><?php echo $this->get_label('options');?></td>
</tr>
<?php
if(count($adResult) == 0){?>
<tr class="data_table_content"><td colspan="16" style="height: 30px;"><?php echo $this->get_label('no records found');?></td></tr>
<?php } else {

	foreach($adResult as $key => $value)
	{
		if($key == "heading")
		continue;

		$adid 			= $key;
	    $childCount     = count($value);
	    $rowspan 		= $childCount;

	    $iii            = 0;

		foreach($value as $key1 => $value1)
		{
			$adidChild      = $key1;
			$adType         = $value1['type'];
			$pricingvalue   = $value1['display_type'];
			?>
				<tr class="data_table_content">
				<td rowspan="<?php echo $rowspan;?>" <?php if($iii >0){?> style="display: none;" <?php }?> >
				<?php
				if($retargeting_enabled ==1)
				{
					if($value1['retargeting'] ==1){?>
					<i class="fa fa-recycle" title="<?php echo $this->get_label('retargeting');?>"></i>
					<?php }
				}
				?>

				<?php if($value1['html5'] ==1){?>
				<i class="fa fa-html5" style="font-size: 16px;" title="<?php echo $this->get_label('html5');?>"></i>&nbsp;
				<?php }?>

				<?php if($value1['type'] !=7){?>
				<a target="_parent" href="<?php echo $this->make_base_url("ad/view/".$adid,ADMIN_DIR);?>"><?php echo $value1['name'];?></a>
				<?php }else{?>
				<?php echo $value1['name'];?>
				<?php }?>

				<?php if($value1['type'] != 7 && $value1['type'] != 9 && $value1['type'] != 12 && $value1['type'] != 14 && $value1['type'] != 21 && $value1['html5'] == 0){?>
					<div class="ad-popup-div">
					<?php if($value1['type'] ==1){?>
					<div class="title"><?php echo $value1['title'];?></div>
					<div class="description"><?php echo $value1['description'];?></div>
					<div class="url"><?php echo $value1['display_url'];?></div>
				<?php }
				else if($value1['type'] ==2){?>
					<img style="max-width:700px;" alt="<?php echo $this->get_label('banner');?>" src="../<?php echo DATA_DIR;?>/<?php echo $adid;?>_<?php echo $value1['banner'];?>" />
				<?php }
				else if($value1['type'] ==11){?>
					<table style="width: 100%;">
					<tr>
					<td style="vertical-align: middle;padding-right: 2px;border-bottom: 0px;">
					<img alt="<?php echo $this->get_label('banner');?>" src="<?php echo '../'.DATA_DIR;?>/<?php echo $adid;?>_<?php echo $value1['banner'];?>" />
					</td>
					<td style="border-bottom: 0px;">
					<div class="title"><?php echo $value1['title'];?></div>
					<div class="description"><?php echo $value1['description'];?></div>
					<div class="url"><?php echo $value1['display_url'];?></div>
					</td>
					</tr>
					</table>
				<?php }
				else if($value1['type'] ==13){?>
					<video width="300" height="225" controls >
					<source src="<?php echo '../'.DATA_DIR.'/video/'.$value1['id'].'/'.$value1['banner'];?>" type="<?php echo $value1['mime_type'];?>"></source>
					<?php echo $this->get_label('your browser does not support HTML5 video');?>
					</video>
				<?php }?>
					</div>
				<?php }?>

				</td>

			
					<?php if($ecommerce_enabled ==1 && ($adType ==0 || $adType ==7)){?>
							<?php if($adType != 7){?>
								<td ><?php echo $value1['pricing'];?></td>
							<?php }else{?>
								<td <?php if($value1['type'] ==7){?>class="border-left"<?php }?> ><bdi><a target="_parent" href="<?php echo $this->make_url("ad/view/".$adidChild);?>"><?php echo $value1['pricing'].' - '.$value1['dimension'];?></a></bdi></td>
							<?php }?>
						<?php }else{?>
							<td ><?php echo $value1['pricing'];?></td>
					<?php }?>
						
				<td >

					<?php if($premium_ad_enabled == 1 && $value1['premium_ad'] == 1){?>
					     <i class="fa fa-star" style="font-size: 16px;color:green;" title="<?php echo $this->get_label('premium ad');?>"></i>&nbsp;
					<?php } ?>

					<?php echo $value1['status'];?></td>

					<td><?php	echo $this->get_pricing_value($adidChild, $value1['pricing_status'], $pricingvalue);?></td>

				<?php
				if(isset($value1['reportData']))
				{
					foreach($value1['reportData'] as $key2 => $value2)
					{
						?>
						<td><bdi><?php echo $value2;?></bdi></td>
						<?php
					}
				}
				?>
				<td>

					<?php
					  if($premium_ad_enabled == 1)
					  {
					    if($value1['premium_ad'] == 0){?>
					     <a target="_parent" href="<?php echo $this->make_url("ad/premium/".$adidChild."/1/3/".$duration."/".$type."/".$status."/".$adpricing."/".$premiumStatus."/".$pg);?>"
					       onclick="return confirm('<?php echo $this->get_message('do you really want to set this ad as premium ad');?>')"
					       ><i class="fa fa-star" style="font-size: 16px;color:#8a8e8a;" title="<?php echo $this->get_label('make premium');?>"></i></a>
					    <?php }

					    if($value1['premium_ad'] == 1){?>
					     <a target="_parent" href="<?php echo $this->make_url("ad/premium/".$adidChild."/0/3/".$duration."/".$type."/".$status."/".$adpricing."/".$premiumStatus."/".$pg);?>"
					       onclick="return confirm('<?php echo $this->get_message('do you really want to remove premium status of this ad');?>')"
					       ><i class="fa fa-trash" style="font-size: 16px;color:#e60d53;" title="<?php echo $this->get_label('remove premium');?>"></i></a>
					    <?php }
					  }
					?>



					<a target="_parent" href="<?php echo $this->make_url("ad/change_status/".$adidChild."/3/".$duration."/".$type."/".$status."/".$adpricing."/".$premiumStatus."/".$pg);?>"><i class="fa fa-cogs settings-icon" title="<?php echo $this->get_label('operations');?>"></i></a>
				</td>
			</tr>
			<?php
			$iii++;
		}
	}
	?>
	<tr><td colspan="16" align="center"><?php echo $this->get_variable('pagination');?></td></tr>
	<?php
}
?>
</table>

</td>
</tr>
</table>
<script type="text/javascript">
$(document).ready(function() {

$('#adpricing').change(function(){

LoadDropDown();

});


LoadDropDown();

});

function LoadDropDown()
{
	adpricing = $('#adpricing').val();

	$(".ad-option").hide();

	if($(".pop-option").length > 0)
	$(".pop-option").hide();

	if($(".affiliate-option").length > 0)
	$(".affiliate-option").hide();

	if($(".video-option").length > 0)
	$(".video-option").hide();
	
	if($(".directlink-option").length > 0)
	$(".directlink-option").hide();
	
	$(".ad-option").show();

	if(adpricing == -1)
	{
		if($(".pop-option").length > 0)
		$(".pop-option").show();

		if($(".affiliate-option").length > 0)
		$(".affiliate-option").show();

		if($(".video-option").length > 0)
		$(".video-option").show();
		
		if($(".directlink-option").length > 0)
		$(".directlink-option").show();
	}

	if(adpricing == 1 && $(".pop-option").length > 0)
	$(".pop-option").show();
	
	if(adpricing == 1 && $(".video-option").length > 0)
	$(".video-option").show();
			
	if(adpricing == 6 && $(".affiliate-option").length > 0)
	$(".affiliate-option").show();
	
	if((adpricing == 0 || adpricing == 1 || adpricing == 6) && $(".directlink-option").length > 0)
	$(".directlink-option").show();

	if((adpricing != -1 && adpricing != 1) && ($('#type').val() == 9 || $('#type').val() == 13))
	$('#type').val(0);
	
	if((adpricing != -1 && adpricing != 6) && $('#type').val() == 12)
	$('#type').val(0);

	if((adpricing != -1 && adpricing != 0 && adpricing != 1 && adpricing != 6) && $('#type').val() == 21)
	$('#type').val(0);
}
</script>

</body>
</html>