<?php $this->dispatch("layout/header/2/_22");?>

<div class="sub_menu_main"><?php echo $this->get_label('manage adunits');?></div>

<?php $this->dispatch("links/links/21");?>

<table style="width: 100%;" cellpadding="0" cellspacing="0">
<tr><td colspan="5">
<div class="search_div">
<table class="search_div_table">
<tr>
<td height="20px"></td>
<td>
<?php 
$category_enabled=$this->get_addon_status('category-targeting_enabled');
$sponsored_enabled=$this->get_addon_status('sponsored_enabled');
$affiliate_enabled=$this->get_addon_status('affiliate-ads_enabled');


	$stringarray=array();
	if($category_enabled ==1)
	{
		$category_enabled_ads=Configuration::get_instance()->read('category_enabled_ads');

		if($category_enabled_ads !='')
		$stringarray=explode('_',$category_enabled_ads);
	}





$adpricing=$this->get_variable('adpricing');
$sid=$this->get_variable('sid');
$form=$this->create_form();
$form->start("manage",$this->make_url("adunit/manage"),"post");
?>

<?php echo $this->get_pricing_box($adpricing,3);?>
&nbsp;&nbsp;

<?php if($category_enabled ==1 && $category_enabled_ads !=""){?>
<span class="site-class" style="display: none;">
&nbsp;&nbsp;
<?php echo CategoryHelper::get_site_dropdown(0,$sid);?>&nbsp;&nbsp;
</span>
<?php }?>

<input type="submit" name="stat" value="<?php echo $this->get_label('go');?>"/>
<?php $form->end(); ?>
</td>
</tr>
</table>
</div>

</td></tr>


<tr><td colspan="5" height="10px"></td></tr>



<tr><td colspan="5" height="10px">

<table class="data_table" cellpadding="0" cellspacing="0">
<tr class="row_heading_tr">
<td style="width: 120px;"><?php echo $this->get_label('id');?></td>

<td style="width: 120px;"><?php echo $this->get_label('name');?></td>
<?php if($category_enabled ==1 && $category_enabled_ads !="" && ($adpricing ==-1 || in_array($adpricing,$stringarray))){?>
<td style="width: 170px;"><?php echo $this->get_label('site name');?></td>
<?php }?>

<td style="width: 150px;"><?php echo $this->get_label('adblock');?></td>
<td style="width: 130px;"><?php echo $this->get_label('adunit preference');?></td>
<td ><?php echo $this->get_label('options');?></td>

</tr>

<?php 

$res=$this->get_result('res');
if(count($res)==0)
{
	?>
	
	<tr><td colspan="8" height="30px"><?php echo $this->get_label("no records found");?></td></tr>
	<?php 
}
else
{


foreach($res as $key=>$value)
{
	$aduid=$value['id'];
	$adbid=$value['blockid'];
	
?>
<tr class="row_data_tr">
<td>
<?php if($value['display_type'] ==3){?>
<a href="<?php echo $this->make_url("dispatch/sponsored/13/".$aduid,BASE);?>"><?php echo $aduid;?></a>
<?php }else{?>
<a href="<?php echo $this->make_url("report/adcodes_detailed/".$aduid);?>"><?php echo $aduid;?></a>
<?php }?>
</td>
<td >
<?php if($value['display_type'] ==3){?>
<a href="<?php echo $this->make_url("dispatch/sponsored/13/".$aduid,BASE);?>"><?php echo $value['name'];?></a>
<?php }else{?>
<a href="<?php echo $this->make_url("report/adcodes_detailed/".$aduid);?>"><?php echo $value['name'];?></a>
<?php }?>
</td>

<?php if($category_enabled ==1 && $category_enabled_ads !=""){

if(($adpricing == -1 && in_array($value['display_type'],$stringarray)) || in_array($value['display_type'],$stringarray)){?>
<td ><?php echo CategoryHelper::get_site_name($value['sid']);?></td>
<?php }else if($adpricing == -1 && !in_array($value['display_type'],$stringarray)){?>

<td ><?php echo $this->get_label('na');?></td>

<?php }} ?>

<td >
<?php 
if($value['display_type'] !=9 && $value['adcode_type'] !=9)
{
	if($value['display_type'] !=13 || ($value['display_type'] ==13 && $value['blockid'] >0))
	{
		if($value['banner_type'] !=4)
		echo $value['width']." x ".$value['height']."-".$this->get_adblock_type($value['type'],$value['banner_type']);
		else
		echo $this->get_adblock_type($value['type'],$value['banner_type']);
	}
	else
	echo $this->get_label('na');
}
else if($value['adcode_type'] ==9)
echo $this->get_adblock_type($value['type'],$value['banner_type'],$value['adcode_type']);
else
echo $this->get_label('na');
?>
</td>
<td >
<?php 
echo $this->get_adunit_preference($value['display_type'],$value['adcode_type']);

if($value['display_type'] ==13)
{
	if($value['video_type'] ==1)
	echo " - ".$this->get_label('vast player');
	else if($value['video_type'] ==2)
	echo " - ".$this->get_label('html5 player');
}

?></td>

<td align="left"><a href="<?php echo $this->make_url("adunit/edit/".$aduid);?>"><i class="fa fa-edit edit-icon" title="<?php echo $this->get_label('edit');?>"></i></a>


<a href="<?php echo $this->make_url("adunit/delete/".$aduid."/".$adpricing."/".$sid);?>" onclick="return confirm('<?php echo $this->get_message('adunit delete alert');?>');"><i class="fa fa-trash delete-icon" title="<?php echo $this->get_label('delete');?>"></i></a>



<?php if($value['display_type'] ==3){?>

    
<a href="<?php echo $this->make_url("dispatch/sponsored/13/".$aduid,BASE);?>"><i class="fa fa-bell-o mapping-icon" title="<?php echo $this->get_label('mappings');?>"></i></a>
     

<a href="<?php echo $this->make_url("dispatch/sponsored/16/".$aduid,BASE);?>"><i class="fa fa-calendar package-icon" title="<?php echo $this->get_label('packages');?>"></i></a>

<?php }?>


</td></tr>
<?php }?>
<tr><td colspan="8" align="center"><?php echo $this->get_variable('pagination');?></td></tr>
<?php }?>
</table>


</td>
</tr>


</table>

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


	<?php if($affiliate_enabled ==1){?>
	$('#affiliate-data').hide();
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