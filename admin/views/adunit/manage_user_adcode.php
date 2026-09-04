<?php 
$this->dispatch("layout/header/2/_21");
$pub=$this->get_variable('pub');


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
?>
<div class="sub_menu_main"><?php echo $this->get_label('manage user adunits');?></div>


<?php $this->dispatch("links/links/29");?>

<table style="width: 100%;" cellpadding="0" cellspacing="0">

<tr><td colspan="5">
 <div class="search_div">
<table class="search_div_table">
<tr>
<td height="20px"></td>
<td>
<?php 
$adpricing=$this->get_variable('adpricing');
$search_by = $this->get_variable('search_by');
$search_text = $this->get_variable('search_text');

$form=$this->create_form();
$form->start("manage_user_adcode",$this->make_url("adunit/manage_user_adcode"),"post");
?>
<select name="pub" id="pub" style="width: 150px;">
<option value="-1" <?php if($pub==-1) echo "selected";?>><?php echo $this->get_label('all publishers');?></option>
<?php 
$res1=$this->get_result('res1');
foreach($res1 as $key1=>$value1)
{?>
<option value="<?php echo $value1['id'];?>" <?php if($pub==$value1['id']) echo "selected";?>><?php echo $value1['username'];?></option>
<?php }?>
</select>
&nbsp;&nbsp;
<?php echo $this->get_pricing_box($adpricing,3);?>
&nbsp;&nbsp;
    <select name="search_by" id="search_by">
    	<option value="0" <?php if($search_by ==0) echo "selected"; ?>><?php echo $this->get_label('search by');?></option>
    	<option value="1" <?php if($search_by ==1) echo "selected"; ?>><?php echo $this->get_label('adunit id');?></option>
        <option value="2" <?php if($search_by ==2) echo "selected"; ?>><?php echo $this->get_label('user name');?></option>
        <option value="3" <?php if($search_by==3) echo "selected"; ?>><?php echo $this->get_label('adunit name');?></option>
        <option value="4" <?php if($search_by==4) echo "selected"; ?>><?php echo $this->get_label('site name');?></option>
    </select>
    <input type="text" name="search_text" id="search_text" value="<?php echo $search_text;?>"/>

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
<td style="width: 60px;"><?php echo $this->get_label('id');?></td>
<td style="width: 120px;"><?php echo $this->get_label('user name');?></td>
<td style="width: 120px;"><?php echo $this->get_label('name');?></td>
<?php if($category_enabled ==1 && $category_enabled_ads !="" && ($adpricing ==-1 || in_array($adpricing,$stringarray))){?>
<td style="width: 180px;"><?php echo $this->get_label('site name');?></td>
<?php }?>

<td style="width: 130px;"><?php echo $this->get_label('adunit preference');?></td>

<?php if($adpricing !=9 && $adpricing !=12){?>
<td style="width: 160px;"><?php echo $this->get_label('adblock');?></td>
<?php }?>


<td ><?php echo $this->get_label('options');?></td>

</tr>

<?php 

$res=$this->get_result('res');
if(count($res)==0)
{
	?>
	
	<tr><td colspan="8" height="30px">&nbsp;<?php echo $this->get_label("no records found");?></td></tr>
	<?php 
}
else
{
$native=0;
foreach($res as $key=>$value)
{
	$aduid=$value['id'];
	$adbid=$value['blockid'];
	if($native_enabled==1)
	{
		$native=$value['native'];
		$layout=$value['layout'];
		$layout_str='';
		
		if($native ==1 && $layout >0)
		$layout_str=$this->get_layout_dim($layout);
	}
?>
<tr class="row_data_tr">
<td>
<?php if($value['display_type'] ==3){?>
<a href="<?php echo $this->make_url("dispatch/sponsored/13/".$aduid,BASE);?>"><?php echo $aduid;?></a>
<?php }else{?>
<a href="<?php echo $this->make_url("report/adcodes_detailed/".$aduid);?>"><?php echo $aduid;?></a>
<?php }?>
</td>
<td><a href="<?php echo $this->make_url("user/profile/".$value['pubid']."/0");?>"><?php echo $this->escape($this->get_user_name($value['pubid']));?></a></td>
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
echo $this->get_adunit_preference($value['display_type']);

if($value['display_type'] ==13)
{
	if($value['video_type'] ==1)
	echo " - ".$this->get_label('vast player');
	else if($value['video_type'] ==2)
	echo " - ".$this->get_label('html5 player');
}

?></td>
<?php if($adpricing !=9 && $adpricing !=12){?>
<td >
<?php if($native == 1)
	echo $layout_str;

else if($value['display_type'] !=9 && $value['display_type'] !=12)
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
else
echo $this->get_label('na');
?>
</td>
<?php }?>

<td>
<a href="<?php echo $this->make_url("adunit/edit/".$aduid);?>"><i class="fa fa-eye edit-icon" title="<?php echo $this->get_label('view');?>"></i></a>

<?php if($value['display_type'] ==3){?>

     
<a href="<?php echo $this->make_url("dispatch/sponsored/13/".$aduid,BASE);?>"><i class="fa fa-bell-o mapping-icon" title="<?php echo $this->get_label('mappings');?>"></i></a>
    


<a href="<?php echo $this->make_url("dispatch/sponsored/16/".$aduid,BASE);?>"><i class="fa fa-calendar package-icon" title="<?php echo $this->get_label('packages');?>"></i></a>

<?php }else{?>


<?php if($value['display_type'] ==12){?>
<a href="<?php echo $this->make_url("ad/view/".$value['aid']);?>"><i class="fa fa-bullhorn ad-icon" title="<?php echo $this->get_label('view ad');?>"></i></a>
<?php }?>

<?php }?>
</td>
</tr>

<?php }?>

<tr><td colspan="8" align="center"><?php echo $this->get_variable('pagination');?></td></tr>

<?php }?>
</table>


</td>
</tr>


</table>

<?php $this->dispatch("layout/footer");?>	