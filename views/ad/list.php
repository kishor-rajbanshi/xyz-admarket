<?php
$this->dispatch("layout/header/2/2/a");
$type=$this->get_variable('type');
$status=$this->get_variable('status');
$adpricing=$this->get_variable('adpricing');
$pg=$this->get_variable("pg");

$premium_ad_enabled = Configuration::get_instance()->read('allow_premium_ads');

$deviceenabled=$this->get_addon_status('device-targeting_enabled');
$textimage_enabled=$this->get_addon_status('text-image-ads_enabled');
$retargeting_enabled=$this->get_addon_status('retargeting_enabled');
$ecommerce_enabled=$this->get_variable('ecommerce_enabled');
$text_ads_enabled=$this->get_variable('text_ads_enabled');
$skin_enabled=$this->get_addon_status('skin-ads_enabled');
$cpm_enabled=$this->get_addon_status('cpm_enabled');
$cpa_enabled=$this->get_addon_status('cpa_enabled');
$cpp_enabled=$this->get_addon_status('cpp_enabled');
$pop_enabled = $this->get_addon_status('pop-ads_enabled');
$video_enabled = $this->get_addon_status('video-ads_enabled');
$directlink_enabled 		= $this->get_addon_status('direct-link-ads_enabled');
$affiliate_enabled = $this->get_addon_status('affiliate-ads_enabled');

$adCloneEnabled = Configuration::get_instance()->read('enable_advertisers_ad_clone_option');
?>
<script type="text/javascript">
$(document).ready(function() {
	CreateResponsiveTable('table-desktop');

	$(window).resize(function()
	{
	 	CreateResponsiveTable('table-desktop');
	});
});
</script>


<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 p-3 mb-3 manage-ads table-outer-box">

<h2 class="page-heading page-heading-flex"><div class="page-inner"><i class="fa fa-list icon_red"></i><?php echo $this->get_label('manage your ads');?></div> <a class="create-btn" href="<?php echo $this->make_base_url("ad/create");?>">
    <p><i class="fa fa-plus-circle" aria-hidden="true"></i>
 <?php echo $this->get_label('create ad');?></p>
</a></h2>




<?php
$form2=$this->create_form();
$form2->start("manageads",$this->make_url("ad/list"),"post");
?>
<div class="row mb-1 px-0 search_div">
	<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12 mb-1">
		<?php echo $this->get_pricing_box($adpricing,1);?>
	</div>

	<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12 mb-1 type-td">
	    <select class="form-select" aria-label="type" name="type" id="type">
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

	    <?php if($video_enabled ==1 && $cpm_enabled == 1){?>
	    <option class="video-option" style="display:none;" value="13" <?php if($type == 13) {echo "selected";}?>><?php echo $this->get_label('video ad');?></option>
	    <?php }?>

			<?php if($directlink_enabled ==1){?>
	    <option class="directlink-option" style="display:none;" value="21" <?php if($type ==21) {echo "selected";}?>><?php echo $this->get_label('directlink ad');?></option>
	    <?php }?>

	    <?php if($affiliate_enabled ==1 && $cpa_enabled == 1){?>
		<option class="affiliate-option" style="display:none;" value="12" <?php if($type == 12) {echo "selected";}?>><?php echo $this->get_label('affiliate');?></option>
	     <?php }?>
	    </select>
	</div>

	<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12 mb-1">
	    <select class="form-select" aria-label="status" name="status" id="status">
		    <option value="2" <?php if($status==2) echo "selected"; ?>><?php echo $this->get_label('allstatus');?></option>
		    <option value="1" <?php if($status==1) echo "selected"; ?>><?php echo $this->get_label('active');?></option>
		    <option value="-1" <?php if($status==-1) echo "selected"; ?>><?php echo $this->get_label('pending');?></option>
		    <option value="0" <?php if($status==0) echo "selected"; ?>><?php echo $this->get_label('blocked');?></option>
	    </select>
	</div>

	<div class="col-auto ">
		<input class="btn btn-info" type="submit" name="search" value="<?php echo $this->get_label('go');?>" />
	</div>
</div>
<?php $form2->end(); ?>

<?php if($this->get_variable("draftid") > 0){?>
	<span class="notification float-end">* <?php echo $this->get_label('draft ad remove',array('x'=>7));?></span>
<?php }?>

<table id="table-desktop" class="data_table" cellpadding="0" cellspacing="0">
	<tr class="data_table_head">
			<td ><?php echo $this->get_label('ad info');?></td>
			<td ><?php echo $this->get_label('ad status');?></td>
			<td ><?php echo $this->get_label('pricing status');?></td>
			<td ><?php echo $this->get_label('type');?></td>
			<td ><?php echo $this->get_label('actions');?></td>
	</tr>


<?php
$res = $this->get_result('res');

if(count($res) == 0){?>
	<tr class="data_table_content">
		<td colspan="10"><?php echo $this->get_label('no records found');?></td>
	</tr>
<?php } else {


foreach($res as $key=>$value)
{
		$adid=$value['id'];

		$ecommercearray=array();

		if($value['type'] ==7)
		$ecommercearray=$this->get_ecommerce_row_list($adid);

		$ecommercecount=count($ecommercearray);

		if($ecommercecount ==0)
		{
			$ecommercecount=1;
			$ecommercearray[]=0;
		}

		$iii=0;

		foreach($ecommercearray as $gkey=>$gvalue)
		{
			$aidvalue1=0;

			if($value['type'] !=7 || $ecommercecount ==1)
			$aidvalue1=$adid;
			else if($value['type'] ==7)
			$aidvalue1=$gvalue[0];
?>
<tr class="data_table_content <?php if($iii >0){?> alt-class <?php }else{?> base-class <?php }?>">
	<td rowspan="<?php echo $ecommercecount;?>" <?php if($iii >0){?> style="display: none;" <?php }?> >
		<div class="report-info">
			<div>
				<div><?php echo $adid." - ".$value['name'];?> | <span class="pricing-name"><?php echo $this->get_ad_pricing($adid, $value['display_type']);?></span></div>
			</div>

			<?php if($value['type'] == 7 && $ecommercecount >= 1){?>
				<div>
					<a href="<?php echo $this->make_url("ad/view/".$adid);?>" >
						<i class="fa fa-edit edit-icon" title="<?php echo $this->get_label('edit');?>"></i>
					</a>

					<a href="<?php echo $this->make_url("ad/delete/".$adid."/".$type."/".$status."/".$adpricing."/".$pg);?>" onclick="return confirm('<?php echo $this->get_message('do you really want to delete this ad');?>')">
						<i class="fa fa-trash delete-icon" title="<?php echo $this->get_label('delete');?>"></i>
					</a>
				</div>
			<?php }?>					
		</div>		
	</td>
<?php if($value['type'] !=7){?>
	<td >
		<div>
			<bdi>
				<?php 
				if($value['pause_status'] == 1)
				echo $this->get_ad_status($value['status'])." - ".$this->get_label('paused');
				else 
				echo $this->get_ad_status($value['status']);
				?>
			</bdi>
		</div>
		<div>
			
		</div>
	</td>
	<td>
		<?php echo $this->get_pricing_value($aidvalue1, $value['pricing_status'], $value['display_type']);?>
	</td>
	<td >
		<div><bdi>
			<?php echo $this->get_ad_type($value['type'],$value['display_type']);?>&nbsp; 

			<?php if($deviceenabled == 1){?>
				<?php 	
					if($value['device']==2)
					{
						?> 
<i class="fa fa-desktop desktop-icon" title="<?php echo $this->get_label('desktop');?>"></i>
<i class="fa fa-mobile mobile-icon" title="<?php echo $this->get_label('mobile');?>"></i> 
						<?php 
					}
					else if($value['device']==1)
					{
						?> 
<i class="fa fa-mobile mobile-icon" title="<?php echo $this->get_label('mobile');?>"></i>
						<?php 
					}
					else
					{
						?> 
<i class="fa fa-desktop desktop-icon" title="<?php echo $this->get_label('desktop');?>"></i>
						<?php 
					}
				?>
			<?php } ?>

			<?php if($premium_ad_enabled == 1 && $value['premium_ad'] == 1){?>
				<i class="fa fa-star premium-icon" title="<?php echo $this->get_label('premium ad');?>"></i>
			<?php } ?>

			<?php if($retargeting_enabled == 1 && $value['retargeting'] == 1){?>
				<i class="fa fa-recycle retargeting" title="<?php echo $this->get_label('retargeting');?>"></i>
			<?php }?>
			</bdi>		
		</div>
		<?php
		if($value['type'] ==13)
		{
			$aspect_ratio=$this->get_aspect_ratio_value($value['aspect_ratio']);

			if($aspect_ratio >0)
			echo '<div><bdi>'.$this->get_label('aspect ratio').' - '.$aspect_ratio.'</bdi></div>';
		}
				?>
	</td>
<?php }else{ ?>
		<td class="border-start">
			<div>
				<bdi><?php echo $gvalue[1];?></bdi>
			</div>			
		</td>
		<td>
			<bdi><?php echo $this->get_pricing_value($gvalue[0], $gvalue[6], $value['display_type']);?></bdi>	
		</td>
		<td>
			<div>
				<bdi><?php echo $this->get_ad_type(7)." - ".$gvalue[2];?>&nbsp; 

				<?php if($deviceenabled == 1){?>
					<?php 	
						if($value['device']==2)
						{
							?> 
	<i class="fa fa-desktop desktop-icon" title="<?php echo $this->get_label('desktop');?>"></i>
	<i class="fa fa-mobile mobile-icon" title="<?php echo $this->get_label('mobile');?>"></i> 
							<?php 
						}
						else if($value['device']==1)
						{
							?> 
	<i class="fa fa-mobile mobile-icon" title="<?php echo $this->get_label('mobile');?>"></i>
							<?php 
						}
						else
						{
							?> 
	<i class="fa fa-desktop desktop-icon" title="<?php echo $this->get_label('desktop');?>"></i>
							<?php 
						}
					?>
				<?php } ?>

				<?php if($premium_ad_enabled == 1 && $gvalue[5] == 1){?>
					<i class="fa fa-star premium-icon" title="<?php echo $this->get_label('premium ad');?>"></i>
				<?php } ?>

				<?php if($retargeting_enabled == 1 && $value['retargeting'] == 1){?>
					<i class="fa fa-recycle retargeting" title="<?php echo $this->get_label('retargeting');?>"></i>
				<?php }?>
			</bdi>
			</div>
		</td>
<?php }?>

<?php if($value['type'] !=7){

	$aidvalue     = $adid;
	$statusvalue  = $value['status'];
	$pausevalue   = $value['pause_status'];
?>

<td >
	<a href="<?php echo $this->make_url("ad/view/".$adid);?>">
		<i class="fa fa-edit edit-icon" title="<?php echo $this->get_label('edit');?>"></i>
	</a>

	<?php if($adCloneEnabled == 1 && $statusvalue == 1){?>
		<a href="<?php echo $this->make_url("ad/create/-1/".$adid);?>">
			<i class="fa fa-copy clone-icon" title="<?php echo $this->get_label('clone this ad');?>"></i>
		</a>
	<?php } ?>

<?php if($statusvalue ==1){

if($pausevalue ==0){?>
<a href="<?php echo $this->make_url("ad/update_pause_status/".$adid."/1/2/".$type."/".$status."/".$adpricing."/".$pg);?>"><i class="fa fa-pause pause-icon" title="<?php echo $this->get_label('pause this ad');?>"></i></a>
<?php }

if($pausevalue ==1){?>
<a href="<?php echo $this->make_url("ad/update_pause_status/".$adid."/2/2/".$type."/".$status."/".$adpricing."/".$pg);?>"><i class="fa fa-play-circle-o resume-icon" title="<?php echo $this->get_label('resume this ad');?>"></i></a>
<?php }}?>

<a href="<?php echo $this->make_url("ad/detailed_statistics/".$adid);?>" ><i class="fa fa-bar-chart report-icon" title="<?php echo $this->get_label('details');?>"></i></a>

<a href="<?php echo $this->make_url("ad/delete/".$adid."/".$type."/".$status."/".$adpricing."/".$pg);?>" onclick="return confirm('<?php echo $this->get_message('do you really want to delete this ad');?>')"><i class="fa fa-trash delete-icon" title="<?php echo $this->get_label('delete');?>"></i></a>

</td>
<?php }else{?>
<?php
if($ecommercecount >1)
$aidvalue=$gvalue[0];
else
$aidvalue=$adid;
?>

<td >
<a href="<?php echo $this->make_url("ad/view/".$gvalue[0]);?>">
	<i class="fa fa-edit edit-icon" title="<?php echo $this->get_label('edit');?>"></i>
</a>

<?php if($gvalue[3] ==1){

if($gvalue[4] ==0){?>
<a href="<?php echo $this->make_url("ad/update_pause_status/".$gvalue[0]."/1/2/".$type."/".$status."/".$adpricing."/".$pg);?>">
	<i class="fa fa-pause pause-icon" title="<?php echo $this->get_label('pause this ad');?>"></i>
</a>
<?php }

if($gvalue[4] ==1){?>
<a href="<?php echo $this->make_url("ad/update_pause_status/".$gvalue[0]."/2/2/".$type."/".$status."/".$adpricing."/".$pg);?>">
	<i class="fa fa-play-circle-o resume-icon" title="<?php echo $this->get_label('resume this ad');?>"></i>
</a>
<?php }}?>

<a href="<?php echo $this->make_url("ad/detailed_statistics/".$aidvalue);?>" ><i class="fa fa-bar-chart report-icon" title="<?php echo $this->get_label('details');?>"></i></a>

<a href="<?php echo $this->make_url("ad/delete/".$aidvalue."/".$type."/".$status."/".$adpricing."/".$pg);?>" onclick="return confirm('<?php echo $this->get_message('do you really want to delete this ad');?>')">
	<i class="fa fa-trash delete-icon" title="<?php echo $this->get_label('delete');?>"></i>
</a>
</td>
<?php }?>

<?php
$iii=$iii+1;
}}}
?>
</table>

<div class="row">
	<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 mt-3 d-flex flex-row-reverse">
		<?php echo $this->get_variable('pagination');?>
	</div>
</div>

</div>

<script type="text/javascript">
$(document).ready(function() {

$('#adpricing').change(function(){

LoadDropDown();

});

LoadDropDown();
	i			= 0;
	classname	= '';

	$(".data_table_content").each(function() {

		if($(this).hasClass('base-class'))
		{
			if((i % 2) ==0)
			classname="row-background-even";
			else
			classname="row-background-odd";

			$(this).addClass(classname);

			i++;
		}
		else
		{
			if($('.data_table_content').hasClass('alt-class'))
			$(this).addClass(classname);
		}
	});

});

function LoadDropDown()
{
	adpricing=$('#adpricing').val();

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
