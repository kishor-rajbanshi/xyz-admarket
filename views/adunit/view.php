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

<div class="container"><h2 class="page_heading new_heading"><?php echo $this->get_label('manage adunits');?></h2>
<div class="page_heading-btm"></div>
</div>

<div class="container">	

<?php 
$category_enabled=$this->get_addon_status('category-targeting_enabled');
$sponsored_enabled=$this->get_addon_status('sponsored_enabled');

$native_enabled=$this->get_addon_status('native-ad-display_enabled');

	$stringarray=array();
	if($category_enabled ==1)
	{
		$category_enabled_ads=Configuration::get_instance()->read('category_enabled_ads');

		if($category_enabled_ads !='')
		$stringarray=explode('_',$category_enabled_ads);
	}


$adpricing=$this->get_variable('adpricing');
$uid=$this->get_variable('uid');
$sid=$this->get_variable('sid');

$form1=$this->create_form();
$form1->start("adunitlist",$this->make_url("adunit/view"),"post");
?>
  
<div class="search_div" style="float: left;width: 100%;">
<div class="form-group search_div_items" style="width: 160px;">
<?php echo $this->get_pricing_box($adpricing,3);?>
</div>

<?php if($category_enabled ==1 && $category_enabled_ads !=""){?>
<div class="form-group site-class search_div_items" style="width: 150px;display: none;">
<?php echo CategoryHelper::get_site_dropdown($uid,$sid);?>
</div>
<?php }?>

<div class="form-group search_div_items">
<input class="btn btn-danger" type="submit" name="search" value="<?php echo $this->get_label('go');?>" />
</div>
</div>
<?php $form1->end(); ?> 	


<table id="table-desktop" class="data_table" cellpadding="0" cellspacing="0">
<tr class="data_table_head">
<td><?php echo $this->get_label('name');?></td>

<?php if($category_enabled ==1 && $category_enabled_ads !="" && ($adpricing ==-1 || in_array($adpricing,$stringarray))){?>
<td><?php echo $this->get_label('site name');?></td>
<?php }?>


<?php if($adpricing !=9 && $adpricing !=12){?>
<td><?php echo $this->get_label('adblock name');?></td>
<?php }?>


<td><?php echo $this->get_label('adunit type');?></td>

<?php if($adpricing !=9 && $adpricing !=12 && $adpricing !=13){?>
<td><?php echo $this->get_label('ad type');?></td>
<?php }?>

<?php if($native_enabled==1){?>
<td><?php echo $this->get_label('adblock type');?></td>
<?php }?>


<td><?php echo $this->get_label('options');?></td>
</tr>



<?php 
$res=$this->get_result('res');
if(count($res)==0)
{
?>
<tr class="data_table_message"><td colspan="8" ><?php echo $this->get_label('no records found');?></td></tr>
<?php 
}
else
{


foreach($res as $key=>$value)
{
	$adunit_id=$value['id'];
	
	?>
<tr class="data_table_content">


<?php if($value['display_type'] !=3){?>
<td ><a href="<?php echo $this->make_url("adunit/detail_statistics/".$adunit_id);?>"><?php echo $value['name'];?></a></td>
<?php }else{?>
<td ><?php echo $value['name'];?></td>
<?php }?>



<?php if($category_enabled ==1 && $category_enabled_ads !=""){
if(($adpricing == -1 && in_array($value['display_type'],$stringarray)) || in_array($value['display_type'],$stringarray)){?>
<td ><?php echo CategoryHelper::get_site_name($value['sid']);?></td>
<?php }else if($adpricing == -1 && !in_array($value['display_type'],$stringarray)){?>
<td ><?php echo $this->get_label('na');?></td>
<?php }} ?>

<?php if($adpricing !=9 && $adpricing !=12){?>
<td ><bdi>
<?php
if($native_enabled ==1 && $value['native'] ==1)
echo $this->get_layout($value['layout']).' - '.$this->get_label('native ad block');
else if($value['display_type'] !=9 && $value['display_type'] !=12)
{
	if($value['display_type'] !=13 || ($value['display_type'] ==13 && $value['blockid'] >0))
	{
		if($value['banner_type'] !=4)
		echo $value['abname'].' - ('.$value['width']." x ".$value['height'].')';
		else
		echo $value['abname'];
	}
	else
	echo $this->get_label('na');
}
else
echo $this->get_label('na');
?>
</bdi></td>
<?php }?>




<td >
<?php 
echo $this->get_adunit_preference($value['display_type']);

if($value['display_type'] ==13)
{
	if($value['video_type'] ==1)
	echo " - ".$this->get_label('vast player');
	else if($value['video_type'] ==2)
	echo " - ".$this->get_label('html5 player');
}

?></td>

<?php if($adpricing !=9 && $adpricing !=12 && $adpricing !=13){?>
<td ><?php 
if($native_enabled ==1 && $value['native']==1)
echo $this->get_nativead_type($value['layout']);
else if($value['display_type'] !=9 && $value['display_type'] !=12)
{
	if($value['display_type'] !=13 || ($value['display_type'] ==13 && $value['blockid'] >0))
	echo $this->get_adblock_type($value['type'],$value['banner_type']);
	else
	echo $this->get_label('na');
}
else
echo $this->get_label('na');
?></td>
<?php }?>


<?php if($native_enabled==1){
	?>
	<td>
	<?php 
	if($value['native']==1)
		echo $this->get_label('native');
	else 
		echo $this->get_label('predefined');
	?>
	</td>
	<?php 
	 }?>


<td >

<?php if($value['display_type'] !=12){
if($native_enabled ==1 &&  (isset($value['native'])) && $value['native']==1 ){?>
<a href="<?php echo $this->make_url("adunit/edit_native/".$adunit_id);?>"><i class="fa fa-edit edit-icon" title="<?php echo $this->get_label('edit view');?>"></i></a>
<?php } else {?>
<a href="<?php echo $this->make_url("adunit/edit/".$adunit_id);?>"><i class="fa fa-edit edit-icon" title="<?php echo $this->get_label('edit view');?>"></i></a>
<?php } 

}else{?>
<a href="<?php echo $this->make_url("dispatch/affiliate-ads/2/".$adunit_id);?>"><i class="fa fa-edit edit-icon" title="<?php echo $this->get_label('edit view');?>"></i></a>
<?php }?> 
 
 
<a href="<?php echo $this->make_url("adunit/delete/".$adunit_id."/".$adpricing."/".$sid);?>" onclick="return confirm('<?php echo $this->get_message('do you really want to delete this adunit');?>')"><i class="fa fa-trash delete-icon" title="<?php echo $this->get_label('delete');?>"></i></a>

<?php if($value['display_type'] ==3){?>
<a href="<?php echo $this->make_url("dispatch/sponsored/17/".$adunit_id,BASE);?>"><i class="fa fa-bell-o mapping-icon" title="<?php echo $this->get_label('mappings');?>"></i></a> 

<a href="<?php echo $this->make_url("dispatch/sponsored/20/".$adunit_id,BASE);?>"><i class="fa fa-calendar package-icon" title="<?php echo $this->get_label('packages');?>"></i></a>
<?php }?>

</td>
</tr>

<?php 
}
}
?>	
</table>

<?php echo $this->get_variable('pagination');?>
<div style="height: 20px;"></div>
</div>
<script type="text/javascript">
$(document).ready(function() {

	$("#adpricing").change(function()
	{
		<?php if($category_enabled ==1 && $category_enabled_ads !=""){?>
		LoadSiteData();
		<?php }?>
	});

	<?php if($category_enabled ==1 && $category_enabled_ads !=""){?>
	LoadSiteData();
	<?php }?>
});

<?php if($category_enabled ==1 && $category_enabled_ads !=""){?>
function LoadSiteData()
{
	$(".site-class").hide();
	var selected=$("#adpricing").val();


	var allowed='<?php echo $category_enabled_ads;?>';

	if(selected ==-1 && allowed !="")
	{
		$(".site-class").show();
		return;
	}

	
	if(allowed !='')
	{
		allowed_array=allowed.split('_');
	
		if($.inArray(selected , allowed_array) >-1)
		$(".site-class").show();
		else
		$("#sid").val(0);	
	}
}
<?php }?>
</script>
<?php $this->dispatch("layout/footer");?>