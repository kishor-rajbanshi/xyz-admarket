<?php $this->dispatch("layout/header/6/2/p");?>
<script type="text/javascript">
$(document).ready(function() {
	CreateResponsiveTable('table-desktop');

	$(window).resize(function()
	{
	 	CreateResponsiveTable('table-desktop');
	});
});
</script>

<?php
$textimage_enabled=$this->get_addon_status('text-image-ads_enabled');
$category_enabled=$this->get_addon_status('category-targeting_enabled');
$sticky_enabled=$this->get_addon_status('sticky-ad-display_enabled');
$native_enabled=$this->get_addon_status('native-ad-display_enabled');
$feedads_enabled = $this->get_variable('feedads_enabled');
$adpricing=$this->get_variable('adpricing');
$uid=$this->get_variable('uid');
$sid=$this->get_variable('sid');
?>


<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 p-3 mb-3 manage-adcode-list table-outer-box">

<h2 class="page-heading page-heading-flex"><div class="page-inner"><i class="fa fa-paper-plane-o icon_red"></i><?php echo $this->get_label('manage adunits');?></div>

<a class="create-btn" href="<?php echo $this->make_base_url("adunit/create");?>">
    <p><i class="fa fa-plus-circle" aria-hidden="true"></i> <?php echo $this->get_label('create');?></p>
</a></h2>

<?php
$form1=$this->create_form();
$form1->start("adunitlist",$this->make_url("adunit/view"),"post");
?>
	<div class="row mb-3 px-0 search_div">
	<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12 mb-1">
		<?php echo $this->get_pricing_box($adpricing,3);?>
	</div>
	<?php if($category_enabled ==1){?>
	<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12 mb-1 site-class">
		<?php echo CategoryHelper::get_site_dropdown($uid,$sid);?>
	</div>
	<?php }?>
	<div class="col-auto">
		<input class="btn btn-info" type="submit" name="search" value="<?php echo $this->get_label('go');?>" />
	</div>
</div>
<?php $form1->end(); ?>

<table id="table-desktop" class="data_table" cellpadding="0" cellspacing="0">
<tr class="data_table_head">
	<td><?php echo $this->get_label('adcode info');?></td>
	<?php if($category_enabled ==1){?>
		<td><?php echo $this->get_label('site name');?></td>
	<?php }?>
	<td><?php echo $this->get_label('pricing');?></td>
	<td><?php echo $this->get_label('adcode type');?></td>
	<td><?php echo $this->get_label('actions');?></td>
</tr>
<?php
$res = $this->get_result('res');

if(count($res) == 0){?>
<tr class="data_table_content"><td colspan="9" ><?php echo $this->get_label('no records found');?></td></tr>
<?php } else {

	$textimageData = 0;

	foreach($res as $key=>$value)
	{
			$adunit_id=$value['id'];

			if($value['adcode_type'] != 17 && $textimage_enabled == 1 && $value['textimage_size'] > 0)
			$textimageData	= 1;
			else if($value['adcode_type'] == 17 && $textimage_enabled == 1)
			$textimageData	= 1;
			else
			$textimageData  = 0;
		?>
<tr class="data_table_content">
<td ><bdi><?php echo $adunit_id." - ".$value['name'];?></bdi></td>

<?php if($category_enabled == 1){?>
<?php if($value['sid'] > 0){?>
<td ><?php echo CategoryHelper::get_site_name($value['sid']);?></td>
<?php }else{?>
<td ><?php echo $this->get_label('na');?></td>
<?php }}?>

<td>
	<bdi>
	<?php echo $this->get_adunit_preference($value['display_type'],$value['adcode_type']);?>
	</bdi>
</td>
<td>
<bdi>
<?php 
if($feedads_enabled == 1 && $value['adcode_type'] == 17)
{
	echo $this->get_adcode_type_value($value['ad_type'],$textimageData);

	echo " - ".$this->get_label('feed');
}
else if($native_enabled == 1 && isset($value['native']) && $value['native'] == 1)
{
	echo $this->get_adcode_type_value($value['ad_type']);

	if($value['responsive_support'] ==1)
	echo " - ".$this->get_label('native');
	else
	echo " - ".$value['native_ads_rows_count'].' x '.$value['native_ads_column_count'].' - '.$this->get_label('native');
}
else if($value['adcode_type'] == 9 || $value['adcode_type'] == 12 || $value['adcode_type'] == 13 || $value['adcode_type'] == 18 || $value['adcode_type'] == 21)
{
	echo $this->get_adcode_type_value($value['adcode_type']);

	if($value['adcode_type'] == 13)
	{
		if($value['video_type'] ==1)
		echo " - ".$this->get_label('vast player');
		else if($value['video_type'] ==2)
		echo " - ".$this->get_label('html5 player');
	}
}
else
{
	echo $this->get_adcode_type_value($value['ad_type'],$textimageData, $value['banner_type'], $value['adcode_type']);

	echo " - ".$value['width']." x ".$value['height'];

	if($sticky_enabled ==1)
	{
		if($value['sticky_support'] ==1)
		echo " - ".$this->get_label('sticky');
	}
}
?>
</bdi>	
</td>
<td>
	<?php if($value['adcode_type'] != 12 && $value['display_type'] != 18){?>
		<a href="<?php echo $this->make_url("adunit/edit/".$adunit_id);?>">
			<i class="fa fa-edit edit-icon" title="<?php echo $this->get_label('edit view');?>"></i>
		</a>
	<?php }else if($value['adcode_type'] ==12){ ?>
		<a href="<?php echo $this->make_url("dispatch/affiliate-ads/2/".$adunit_id);?>">
			<i class="fa fa-edit edit-icon" title="<?php echo $this->get_label('edit view');?>"></i>
		</a>
	<?php }?>

	<a href="<?php echo $this->make_url("adunit/detail_statistics/".$adunit_id);?>">
		<i class="fa fa-bar-chart report-icon" title="<?php echo $this->get_label('details');?>"></i>
	</a>

	<?php if($value['display_type'] == 3){?>
		<a href="<?php echo $this->make_url("dispatch/sponsored/17/".$adunit_id,BASE);?>">
			<i class="fa fa-bell-o mapping-icon" title="<?php echo $this->get_label('mappings');?>"></i>
		</a>
	<?php }?>


	<a href="<?php echo $this->make_url("adunit/delete/".$adunit_id."/".$adpricing."/".$sid);?>" onclick="return confirm('<?php echo $this->get_message('do you really want to delete this adunit');?>')">
		<i class="fa fa-trash delete-icon" title="<?php echo $this->get_label('delete');?>"></i>
	</a>

	
</td>
</tr>

<?php }}?>
</table>

<div class="row">
	<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 mt-3 d-flex flex-row-reverse">
		<?php echo $this->get_variable('pagination');?>
	</div>
</div>

</div>
<?php $this->dispatch("layout/footer");?>
