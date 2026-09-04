<?php
$this->dispatch("layout/header/5/_53");
?>
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
$from_date = $this->get_variable('from_date');
$to_date   = $this->get_variable('to_date');

if($from_date != '' && $to_date == '')
$to_date = date("d",time()).'/'.date("m",time()).'/'.date("Y",time());
?>
<div class="sub_menu_main"><?php echo $this->get_label('manage ad statistics');?></div>

<?php $this->dispatch("links/links/8");?>

<table style="width: 100%;" cellpadding="0" cellspacing="0" border="0">

<tr><td colspan="10">
<?php
$ecommerce_enabled 		= $this->get_addon_status('ecommerce-ads_enabled');
$textimage_enabled 		= $this->get_addon_status('text-image-ads_enabled');
$skin_enabled 			= $this->get_addon_status('skin-ads_enabled');
$cpm_enabled            = $this->get_addon_status('cpm_enabled');
$pop_enabled            = $this->get_addon_status('pop-ads_enabled');
$directlink_enabled 		= $this->get_addon_status('direct-link-ads_enabled');
$text_ads_enabled 		= Configuration::get_instance()->read('text-ads_enabled');
$cpa_enabled            = $this->get_addon_status('cpa_enabled');
$affiliate_enabled      = $this->get_addon_status('affiliate-ads_enabled');
$cpp_enabled=$this->get_addon_status('cpp_enabled');
$video_enabled = $this->get_addon_status('video-ads_enabled');


$type 			   = $this->get_variable('type');
$duration 		 = $this->get_variable('duration');
$advertiser  = $this->get_variable('advertiser');
$adpricing 		 = $this->get_variable('adpricing');
$sortBy 		 = $this->get_variable('sortBy');
$orderBy 		 = $this->get_variable('orderBy');


if($from_date == '' && $duration == 7)
$duration = 1;

$form2=$this->create_form();
$form2->start("report-filter",$this->make_url("report/ads"),"post");
?>
<div class="search_div">
<table class="search_div_table">

<tr>

<td style="width: 150px;">
<select name="duration" id="duration" style="width: 140px;">
<option value="1" <?php if($duration==1) { echo "selected"; } ?>><?php echo $this->get_label('today');?></option>
<option value="6" <?php if($duration==6) { echo "selected"; } ?>><?php echo $this->get_label('yesterday');?></option>
<option value="2" <?php if($duration==2) { echo "selected"; } ?>><?php echo $this->get_label('last 14');?></option>
<option value="3" <?php if($duration==3) { echo "selected"; } ?>><?php echo $this->get_label('last 30');?></option>
<option value="4" <?php if($duration==4) { echo "selected"; } ?>><?php echo $this->get_label('last 12 month');?></option>
<option value="5" <?php if($duration==5) { echo "selected"; } ?>><?php echo $this->get_label('all time');?></option>
<option value="7" <?php if($duration==7) { echo "selected"; } ?>><?php echo $this->get_label('custom date');?></option>
</select>

</td>
<td style="width: 200px;">
<input type="text" readonly="readonly" name="from_date" id="from_date" value="<?php echo $from_date;?>" placeholder="<?php echo $this->get_label('from date');?>" />

<input type="text" readonly="readonly" name="to_date" id="to_date" value="<?php echo $to_date;?>" placeholder="<?php echo $this->get_label('to date');?>" />
</td>


<td style="width: 150px;">
    <input type="text" name="username" id="username" style="width: 135px;height: 25px;margin-right: 2px;" list="user-datalist" autocomplete="off"  value="<?php echo $advertiser;?>" placeholder="<?php echo $this->get_label('username');?>" />
     <datalist id="user-datalist"></datalist>
</td>

<td style="width: 150px;">
<?php echo $this->get_pricing_box($adpricing,1);?>
</td>

    <td style="width: 150px;">
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
    <td>    
    <input type="hidden" name="sortBy" id="sortBy" value="<?php echo $sortBy; ?>" />
    <input type="hidden" name="orderBy" id="orderBy" value="<?php echo $orderBy; ?>" />    
    <input type="submit" name="search" value="<?php echo $this->get_label('go');?>" />
    </td>
  </tr>
  </table>
</div>
<?php $form2->end(); ?>

</td></tr>

<tr><td colspan="10" height="10px"></td></tr>
<?php
$reportResult = $this->get_array('reportResult');
$resultCount  = count($reportResult);
?>
<tr><td colspan="10">
<table class="data_table" style="width: 100%;" cellpadding="0" cellspacing="0">
<tr class="row_heading_tr">
<td ><?php echo $this->get_label('ad info');?></td>
<?php
if(isset($reportResult['heading']))
{
	foreach($reportResult['heading'] as $hkey => $hvalue)
	{
		if($hkey == "ctr" || $hkey == "ecpm" || $hkey == "conversionratio")
		continue;	
		?>
		<td <?php if($resultCount > 2){?>class="sortable"<?php } ?> data-type="<?php echo $hkey; ?>">
			<bdi><?php echo $this->get_label($hvalue);?></bdi>
			<?php if($resultCount > 2){?>
				<span class="sort-icons">
						<i class="fa fa-sort-asc <?php if($sortBy == $hkey && $orderBy == "asc"){?>sort-selected<?php } ?>" data-column="<?php echo $hkey; ?>"></i>
						<i class="fa fa-sort-desc <?php if($sortBy == $hkey && $orderBy == "desc"){?>sort-selected<?php } ?>" data-column="<?php echo $hkey; ?>"></i>
				</span>
			<?php } ?>
		</td>
		<?php
	}
}
?>
</tr>

<?php if($resultCount == 2){?>
<tr><td colspan="12" style="height: 30px;">&nbsp;<?php echo $this->get_label('no records found');?></td></tr>
<?php } else {

	foreach($reportResult as $key=>$value)
	{
		if($key == "heading" || $key == "pagination")
		continue;

		if($value['type'] == 7)
		$adName = $value['id']." - ".$value['name']." - ".$value['dimension'];
		else 
		$adName = $value['id']." - ".$value['name'];
		
		?>
		<tr class="row_data_tr">
		<td>
			<div class="report-info">
				<div>
					<a target="_parent" href="<?php echo $this->make_base_url("ad/view/".$value['id'],ADMIN_DIR);?>"><i class="fa fa-bar-chart report-icon" title="<?php echo $this->get_label('details');?>"></i></a>
					<bdi>
						<?php echo $adName;?>
					</bdi>
				</div>
				<div>
					<bdi>
						<?php echo strtoupper($value['pricing_name']);?> - <?php echo $value['adtype'];?>
					</bdi>
				</div>
				<div>
					<a href="<?php echo $this->make_url("user/profile/".$value['uid']."/0");?>"><?php echo $value['userName'];?></a>
				</div>
			</div>
		</td>
		<?php
			foreach($value['reportData'] as $key1 => $value1)
			{
				if($key1 == "ctr" || $key1 == "ecpm" || $key1 == "conversionratio")
				continue;	
				?>
				<td><bdi><?php echo $value1;?></bdi></td>
				<?php
			}
		?>
		</tr>
		<?php
	}?>
	
<?php if(isset($reportResult['pagination'])){?>
<tr><td colspan="16" align="center"><?php echo html_entity_decode($reportResult['pagination']);?></td></tr>
<?php } ?>
<?php } ?>
</table>
</td></tr>
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
<?php $this->dispatch("layout/footer");?>

<script type="text/javascript">
$(document).ready(function()
{
	$("#username").keyup(function(){
		var uname=document.getElementById('username').value;
		var type=1;//alert(type);
		uname=uname.trim();
		var url= "<?php echo $this->make_url('index/get_suggestion_result');?>";
		
		if(uname.length > 3)
		get_suggestion_result("users","username",uname,url,type);
	});
});
</script>