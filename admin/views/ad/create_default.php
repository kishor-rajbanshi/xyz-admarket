<script type="text/javascript">
function changeadtype()
{
	if($('#type').val() ==1)
	{
		$('#textad').show();
		$('#bannerad').hide();
		$('#textplusimage').hide();

		if($('#popad').length >0)
		$('#popad').hide();

		if($('#videoad').length >0)
		$('#videoad').hide();	

		if($('#skinad').length >0)
		$('#skinad').hide();				
	}
	else if($('#type').val() ==2 || $('#type').val() ==5)
	{	
		$('.sizedropdown').hide();
		
		$('#textplusimage').hide();
		$('#textad').hide();
		$('#bannerad').show();

		if($('#popad').length >0)
		$('#popad').hide();

		if($('#videoad').length >0)
		$('#videoad').hide();			

		$('#banner_type').val($('#type').val());

		if($('#type').val() ==2)
		$('#bannersize').show();
		else
		$('#bannersize1').show();	

		if($('#skinad').length >0)
		$('#skinad').hide();			
	}
	else if($('#type').val() ==9)
	{	
		$('#textad').hide();
		$('#bannerad').hide();

		if($('#videoad').length >0)
		$('#videoad').hide();			

		$('#popad').show();
		$('#textplusimage').hide();

		if($('#skinad').length >0)
		$('#skinad').hide();			
	}
	else if($('#type').val() ==11)
	{	
		$('#textplusimage').show();
		$('#textad').hide();
		$('#bannerad').hide();

		if($('#popad').length >0)
		$('#popad').hide();

		if($('#videoad').length >0)
		$('#videoad').hide();	

		if($('#skinad').length >0)
		$('#skinad').hide();			
	}
	else if($('#type').val() ==13)
	{	
		$('#textplusimage').hide();
		$('#textad').hide();
		$('#bannerad').hide();

		if($('#popad').length >0)
		$('#popad').hide();

		if($('#videoad').length >0)
		$('#videoad').show();	

		if($('#skinad').length >0)
		$('#skinad').hide();		
	}
	else if($('#type').val() ==14)
	{	
		$('#textplusimage').hide();
		$('#textad').hide();
		$('#bannerad').hide();

		if($('#popad').length >0)
		$('#popad').hide();

		if($('#videoad').length >0)
		$('#videoad').hide();	

		if($('#skinad').length >0)
		$('#skinad').show();	


		skinsize=$('#bannersize_14').val();

		$(".skin_prev").hide();
		$('#skin_prev'+skinsize).show();
	}	
}
</script>
<?php
$this->dispatch("layout/header/1/_12");
$interstitial_enabled=$this->get_variable('interstitial_enabled');
$textimage_enabled=$this->get_variable('textimage_enabled');
$pop_enabled=$this->get_variable('pop_enabled');
$cpv_enabled=$this->get_variable('cpv_enabled');
$skin_enabled=$this->get_variable('skin_enabled');
$text_ads_enabled=Configuration::get_instance()->read('text-ads_enabled');

$bannersize=intval($this->get_variable("bannersize"));


$validate=array(
		"name"=>array(
				"notNull"=>array($this->get_message("not null"))

		),
		"title"=>array(
				"notNull"=>array($this->get_message("not null"))
		
		),
		"displayurl"=>array(
				"notNull"=>array($this->get_message("not null"))

		),
		"clickurl"=>array(
				"notNull"=>array($this->get_message("not null"))

		),
		"desc"=>array(
				"notNull"=>array($this->get_message("not null"))
		
		)	
		
);

$validate1=array(
		"name"=>array(
				"notNull"=>array($this->get_message("not null"))

		),
		"clickurl"=>array(
				"notNull"=>array($this->get_message("not null"))

		),
		"banner"=>array(
				"notNull"=>array($this->get_message("not null"))
		
		)	
		
);


$validate2=array(
		"name"=>array(
				"notNull"=>array($this->get_message("not null"))
		),
		"clickurl"=>array(
				"notNull"=>array($this->get_message("not null"))
		)
);

$validate3=array(
		"name"=>array(
				"notNull"=>array($this->get_message("not null"))

		),
		"displayurl"=>array(
				"notNull"=>array($this->get_message("not null"))

		),
		"clickurl"=>array(
				"notNull"=>array($this->get_message("not null"))

		),
		"videofile"=>array(
				"notNull"=>array($this->get_message("not null"))
		)	
);


if($skin_enabled ==1)
{
	$validate4=array(
			"name"=>array(
					"notNull"=>array($this->get_message("not null"))
			),
			"clickurl"=>array(
					"notNull"=>array($this->get_message("not null"))
			)
	);	
	
	$res14_banner=$this->get_result('res14_banner');
	
	$skin_array=array();
	foreach($res14_banner as $key=>$result)
	{
		$skin_array['skin_banner_'.$result['id']] =array("notNull"=>array($this->get_message("not null")));
	}
	
	$validate4=array_merge($validate4,$skin_array);
}

?>
<div class="sub_menu_main"><?php echo $this->get_label('create default ad');?></div>

<?php $this->dispatch("links/links/16");?>

<div class="inner-box">
<div class="pages-input">


<table style="width: 100%;" cellpadding="0" cellspacing="0">
<tr><td></td><td ><?php echo $this->get_label('compulsory message');?></td></tr>

<tr>
<td style="width: 150px;"><?php echo $this->get_label('adtype');?></td>
<td>
<select name="type" id="type" onchange="javascript:return changeadtype();">
<?php if($text_ads_enabled ==1){?>
<option value="1" <?php if($this->get_variable('type') ==1){ echo "selected"; } ?>><?php echo $this->get_label('textad');?></option>
<?php }?>

<option value="2" <?php if($this->get_variable('type') ==2){ echo "selected"; } ?>><?php echo $this->get_label('bannerad');?></option>

<?php if($pop_enabled ==1){?>
<option value="9" <?php if($this->get_variable('type') ==9){ echo "selected"; } ?>><?php echo $this->get_label('popad');?></option>
<?php }?>
<?php if($textimage_enabled ==1){?>
<option value="11" <?php if($this->get_variable('type') ==11){ echo "selected"; } ?>><?php echo $this->get_label('textimage ad');?></option>
<?php }?>

<?php if($interstitial_enabled ==1){?> 
<option value="5" <?php if($this->get_variable('type') ==5) {echo "selected";}?>><?php echo $this->get_label('interstitial ad');?></option>
<?php }?>   

<?php if($cpv_enabled ==1){?>
<option value="13" <?php if($this->get_variable('type') ==13){ echo "selected"; } ?>><?php echo $this->get_label('video ad');?></option>
<?php }?>

<?php if($skin_enabled ==1){?>
<option value="14" <?php if($this->get_variable('type') ==14){ echo "selected"; } ?>><?php echo $this->get_label('skin ad');?></option>
<?php }?>


</select>
</td>
</tr>

<tr id="textad"><td colspan="2">
<?php 
$form=$this->create_form();
$form->start("create_default",$this->make_url("ad/create_default"),"post",$validate);

?>



<table style="width: 100%;" cellpadding="0" cellspacing="0">
<tr>
<td style="width: 150px;"><?php echo $this->get_label('name');?></td>
<td><input type="text" name="name" value="<?php echo $this->get_variable('name');?>"><span class="compulsory">*</span></td>
</tr>

<tr>
<td ><?php echo $this->get_label('adtitle');?></td>
<td><input type="text" name="title" value="<?php echo $this->get_variable('title');?>" maxlength="<?php echo Configuration::get_instance()->read('max_ad_title_length');?>"><span class="compulsory">*</span><div class="notification">[<?php echo $this->get_label('max character',array('x'=>Configuration::get_instance()->read('max_ad_title_length')));?>]</div></td>
</tr>


<tr>
<td><?php echo $this->get_label('description');?></td>
<td><input type="text" name="desc" value="<?php echo $this->get_variable('desc');?>" maxlength="<?php echo Configuration::get_instance()->read('max_ad_desc_length');?>"><span class="compulsory">*</span><div class="notification">[<?php echo $this->get_label('max character',array('x'=>Configuration::get_instance()->read('max_ad_desc_length')));?>]</div></td>
</tr>


<tr>
<td><?php echo $this->get_label('displayurl');?></td>
<td><input type="text" name="displayurl" value="<?php echo $this->get_variable('displayurl');?>" maxlength="<?php echo Configuration::get_instance()->read('max_display_url_length');?>"><span class="compulsory">*</span><div class="notification">[<?php echo $this->get_label('max character',array('x'=>Configuration::get_instance()->read('max_display_url_length')));?>]</div></td>
</tr>


<tr>
<td><?php echo $this->get_label('clickurl');?></td>
<td><input type="text" name="clickurl" value="<?php echo $this->get_variable('clickurl');?>"><span class="compulsory">*</span><div class="notification">[<?php echo $this->get_label('example url');?>]</div></td>
</tr>


<!-- code added -->

<tr>
<td><?php echo $this->get_label('category type');?> </td>
<td>
<?php  
if($_POST) 
$cat = $this->read_post_param('cat_type');
else
$cat = 0;
?>
<input type="radio" name="cat_type" id="cat_type0" value="0" <?php if($cat ==0){?>checked="checked"<?php }?> onclick="" />&nbsp;<?php echo $this->get_label('all');?>&nbsp;&nbsp;&nbsp;
<input type="radio" name="cat_type" id="cat_type1" value="1" <?php if($cat ==1){?>checked="checked"<?php }?> onclick="" />&nbsp;<?php echo $this->get_label('one');?>&nbsp;&nbsp;&nbsp;
<input type="radio" name="cat_type" id="cat_type2" value="2" <?php if($cat ==2){?>checked="checked"<?php }?> onclick="" />&nbsp;<?php echo $this->get_label('two');?>&nbsp;&nbsp;&nbsp;
</td>
</tr>
<!-- code added -->


<tr>
<td></td>
<td>
<input type="hidden" name="type1" id="type1" value="1" />
<input type="submit" name="submit" value="<?php echo $this->get_label('create ad');?>"></td>
</tr>


</table>

<?php $form->end(); ?>
</td></tr>


<tr id="bannerad" style="display:none;"><td colspan="2">
<?php 
$form1=$this->create_form();
$form1->start("create_default1",$this->make_url("ad/create_default"),"post",$validate1); 

$res1=$this->get_result('res1');
$res2=$this->get_result('res2');
?>
<table style="width: 100%;" cellpadding="0" cellspacing="0">


<tr>
<td style="width: 150px;"><?php echo $this->get_label('name');?></td>
<td><input type="text" name="name" value="<?php echo $this->get_variable('name');?>"><span class="compulsory">*</span></td>
</tr>


<tr>
<td ><?php echo $this->get_label('banner size');?></td>
<td>
<select class="sizedropdown" name="bannersize" id="bannersize" style="display: none;">
<?php foreach($res1 as $key=>$result)
{
	$height=$result['height'];
	$width=$result['width'];
	$filesize=$result['filesize'];
	$id=$result['id'];
	$diamensions=$result['width']." x ".$result['height'];
	?>
	<option value="<?php echo $id;?>" <?php if($this->get_variable('bannersize') ==$id) { echo "selected"; } ?> ><?php echo $diamensions ?></option>
<?php 
}
?>
</select>

<select class="sizedropdown" name="bannersize1" id="bannersize1" style="display: none;">
<?php foreach($res2 as $key=>$result)
{
	$height=$result['height'];
	$width=$result['width'];
	$filesize=$result['filesize'];
	$id=$result['id'];
	$diamensions=$result['width']." x ".$result['height'];
	?>
	<option value="<?php echo $id;?>" <?php if($this->get_variable('bannersize') ==$id) { echo "selected"; } ?> ><?php echo $diamensions ?></option>
<?php 
}
?>
</select>




</td>
</tr>

<tr>
<td><?php echo $this->get_label('banner');?></td>
<td><input type="file" name="banner" size="10"><span class="compulsory">*</span><br/>

<span class="notification">[<?php echo $this->get_label('supported image format');?>]</span>
</td>
</tr>

<tr>
<td><?php echo $this->get_label('clickurl');?></td>
<td><input type="text" name="clickurl" value="<?php echo $this->get_variable('clickurl');?>"><span class="compulsory">*</span><div class="notification">[<?php echo $this->get_label('example url');?>]</div></td>
</tr>

<!-- code added -->

<tr>
<td><?php echo $this->get_label('category type');?> </td>
<td><?php  if($_POST) $cat=$this->read_post_param('cat_type');?>
<input type="radio" name="cat_type" id="cat_type0" value="0" <?php if($cat ==0){?>checked="checked"<?php }?> onclick="" />&nbsp;<?php echo $this->get_label('all');?>&nbsp;&nbsp;&nbsp;
<input type="radio" name="cat_type" id="cat_type1" value="1" <?php if($cat ==1){?>checked="checked"<?php }?> onclick="" />&nbsp;<?php echo $this->get_label('one');?>&nbsp;&nbsp;&nbsp;
<input type="radio" name="cat_type" id="cat_type2" value="2" <?php if($cat ==2){?>checked="checked"<?php }?> onclick="" />&nbsp;<?php echo $this->get_label('two');?>&nbsp;&nbsp;&nbsp;
</td>
</tr>
<!-- code added -->


<tr>
<td></td>
<td>
<input type="hidden" name="type1" id="type1" value="2" />
<input type="hidden" name="banner_type" id="banner_type" value="2" />
<input type="submit" name="submit" value="<?php echo $this->get_label('create default ad');?>"></td>
</tr>

</table>

<?php $form1->end(); ?>
</td>
</tr>

<tr id="textplusimage" style="display:none;"><td colspan="2">
<?php 
$form4=$this->create_form();
$form4->start("create_default4",$this->make_url("ad/create_default"),"post",$validate1); 
$res4=$this->get_result('res4');

?>
<table style="width: 100%;" cellpadding="0" cellspacing="0">
<tr>
<td style="width: 150px;"><?php echo $this->get_label('name');?></td>
<td><input type="text" name="name" value="<?php echo $this->get_variable('name');?>"><span class="compulsory">*</span></td>
</tr>


<tr>
<td ><?php echo $this->get_label('adtitle');?></td>
<td><input type="text" name="title" value="<?php echo $this->get_variable('title');?>" maxlength="<?php echo Configuration::get_instance()->read('max_ad_title_length');?>"><span class="compulsory">*</span><div class="notification">[<?php echo $this->get_label('max character',array('x'=>Configuration::get_instance()->read('max_ad_title_length')));?>]</div></td>
</tr>

<tr>
<td><?php echo $this->get_label('description');?></td>
<td><input type="text" name="desc" value="<?php echo $this->get_variable('desc');?>" maxlength="<?php echo Configuration::get_instance()->read('max_ad_desc_length');?>"><span class="compulsory">*</span><div class="notification">[<?php echo $this->get_label('max character',array('x'=>Configuration::get_instance()->read('max_ad_desc_length')));?>]</div></td>
</tr>

<tr>
<td><?php echo $this->get_label('displayurl');?></td>
<td><input type="text" name="displayurl" value="<?php echo $this->get_variable('displayurl');?>" maxlength="<?php echo Configuration::get_instance()->read('max_display_url_length');?>"><span class="compulsory">*</span><div class="notification">[<?php echo $this->get_label('max character',array('x'=>Configuration::get_instance()->read('max_display_url_length')));?>]</div></td>
</tr>

<tr>
<td><?php echo $this->get_label('clickurl');?></td>
<td><input type="text" name="clickurl" value="<?php echo $this->get_variable('clickurl');?>"><span class="compulsory">*</span><div class="notification">[<?php echo $this->get_label('example url');?>]</div></td>
</tr>

<tr>
<td ><?php echo $this->get_label('banner size');?></td>
<td>
<select name="bannersize" id="bannersize">
<?php foreach($res4 as $key=>$result)
{
	$height=$result['height'];
	$width=$result['width'];
	$filesize=$result['filesize'];
	$id=$result['id'];
	$diamensions=$result['width']." x ".$result['height'];
	?>
	<option value="<?php echo $id?>" <?php if($this->get_variable('bannersize') ==$id) { echo "selected"; } ?> ><?php echo $diamensions ?></option>
<?php 
}
?>
</select>
</td>
</tr>

<tr>
<td><?php echo $this->get_label('banner');?></td>
<td><input type="file" name="banner" id="banner" size="10"><span class="compulsory">*</span><br/>

<span class="notification">[<?php echo $this->get_label('supported image format');?>]</span>
</td>
</tr>


<tr>
<td></td>
<td>
<input type="hidden" name="type1" id="type1" value="11" />

<input type="submit" name="submit" value="<?php echo $this->get_label('create ad');?>"></td>
</tr>
</table>
<?php $form4->end(); ?>
</td>
</tr>


<?php if($pop_enabled ==1){

	$pop_ads_support=Configuration::get_instance()->read('pop_ads_support');
	
	$pop_array=explode('-',$pop_ads_support);
	
	$pop_type=intval($this->get_variable('pop_type'));
	
	
	$pop_up=intval($pop_array[0]);
	$pop_under=intval($pop_array[1]);
	$pop_tab=intval($pop_array[2]);
	
	if($pop_type ==0 && $pop_up ==1)
	$pop_type=1;
	else if($pop_type ==0 && $pop_under ==1)
	$pop_type=2;
	else if($pop_type ==0 && $pop_tab ==1)
	$pop_type=3;	
	
	
/*  $pop_type=1; POP UP Only
 *  $pop_type=2; POP UNDER Only
 *  $pop_type=3; POP TAB Only
 *  $pop_type=4; POP UP & POP UNDER 
 *  $pop_type=5; POP UNDER & POP TAB 
 *  $pop_type=6; POP UP & POP TAB 
 *  $pop_type=7; POP UP & POP UNDER & POP TAB 
 */	
	?>

<tr id="popad" style="display:none;"><td colspan="2">
<?php 
$form2=$this->create_form();
$form2->start("create_default2",$this->make_url("ad/create_default"),"post",$validate2); 
?>
<table style="width: 100%;" cellpadding="0" cellspacing="0">

<tr>
<td style="width: 150px;"><?php echo $this->get_label('name');?></td>
<td><input type="text" name="name" value="<?php echo $this->get_variable('name');?>"><span class="compulsory">*</span></td>
</tr>


<tr>
<td><?php echo $this->get_label('clickurl');?></td>
<td><input type="text" name="clickurl" value="<?php echo $this->get_variable('clickurl');?>"><span class="compulsory">*</span><div class="notification">[<?php echo $this->get_label('example url');?>]</div></td>
</tr>


<tr>
<td><?php echo $this->get_label('pop type');?> <span class="compulsory">*</span></td>
<td>
<?php if($pop_up ==1){?>
<span><input style="width: 20px !important;" type="checkbox" name="pop_up" id="pop_up" value="1" <?php if($pop_type ==1 || $pop_type ==4 || $pop_type ==6 || $pop_type ==7){?> checked="checked" <?php }?> /> <?php echo $this->get_label('popup');?></span>
<?php }?>

<?php if($pop_under ==1){?>
<span><input style="width: 20px !important;" type="checkbox" name="pop_under" id="pop_under" value="1" <?php if($pop_type ==2 || $pop_type ==4 || $pop_type ==5 || $pop_type ==7){?> checked="checked" <?php }?> /> <?php echo $this->get_label('popunder');?></span>
<?php }?>

<?php if($pop_tab ==1){?>
<span><input style="width: 20px !important;" type="checkbox" name="pop_tab" id="pop_tab" value="1" <?php if($pop_type ==3 || $pop_type ==5 || $pop_type ==6 || $pop_type ==7){?> checked="checked" <?php }?> /> <?php echo $this->get_label('poptab');?></span>
<?php }?>
</td>
</tr>

<!-- code added -->
<tr>
<td><?php echo $this->get_label('category type');?> </td>
<td><?php $cat=0;if($_POST) $cat=$this->read_post_param('cat_type');?>
<input type="radio" name="cat_type" id="cat_type0" value="0" <?php if($cat ==0){?>checked="checked"<?php }?> onclick="" />&nbsp;<?php echo $this->get_label('all');?>&nbsp;&nbsp;&nbsp;
<input type="radio" name="cat_type" id="cat_type1" value="1" <?php if($cat ==1){?>checked="checked"<?php }?> onclick="" />&nbsp;<?php echo $this->get_label('one');?>&nbsp;&nbsp;&nbsp;
<input type="radio" name="cat_type" id="cat_type2" value="2" <?php if($cat ==2){?>checked="checked"<?php }?> onclick="" />&nbsp;<?php echo $this->get_label('two');?>&nbsp;&nbsp;&nbsp;
</td>
</tr>
<!-- code added -->


<tr>
<td></td>
<td>
<input type="hidden" name="type1" id="type1" value="9" />
<input type="submit" name="submit" value="<?php echo $this->get_label('create default ad');?>"></td>
</tr>

</table>

<?php $form2->end(); ?>
</td>
</tr>
<?php }?>


<?php if($cpv_enabled ==1){?>
<tr id="videoad" style="display:none;"><td colspan="2">
<?php 
$form3=$this->create_form();
$form3->start("create_default3",$this->make_url("ad/create_default"),"post",$validate3);

?>

<table style="width: 100%;" cellpadding="0" cellspacing="0">
<tr>
<td style="width: 150px;"><?php echo $this->get_label('name');?></td>
<td><input type="text" name="name" value="<?php echo $this->get_variable('name');?>"><span class="compulsory">*</span></td>
</tr>

<tr>
<td><?php echo $this->get_label('displayurl');?></td>
<td><input type="text" name="displayurl" value="<?php echo $this->get_variable('displayurl');?>" maxlength="<?php echo Configuration::get_instance()->read('max_display_url_length');?>"><span class="compulsory">*</span><div class="notification">[<?php echo $this->get_label('max character',array('x'=>Configuration::get_instance()->read('max_display_url_length')));?>]</div></td>
</tr>

<tr>
<td><?php echo $this->get_label('clickurl');?></td>
<td><input type="text" name="clickurl" value="<?php echo $this->get_variable('clickurl');?>"><span class="compulsory">*</span><div class="notification">[<?php echo $this->get_label('example url');?>]</div></td>
</tr>



<tr>
<td><?php echo $this->get_label('video file');?></td>
<td><input type="file" name="videofile" id="videofile" size="10"><span class="compulsory">*</span><br/>

<span class="notification">[<?php echo $this->get_label('supported video format html5');?>]</span>
<span class="notification">[<?php echo $this->get_label('supported aspect ratio',array('x'=>$this->get_aspect_ratio_list()));?>]</span>

</td>
</tr>


<tr>
<td></td>
<td>
<input type="hidden" name="type1" id="type1" value="13" />
<input type="submit" name="submit" value="<?php echo $this->get_label('create ad');?>"></td>
</tr>

</table>
<?php $form3->end(); ?>
</td></tr>

<?php }?>




<?php if($skin_enabled ==1){?>
<tr id="skinad" style="display:none;"><td colspan="2">
<?php 
$form5=$this->create_form();
$form5->start("create_default5",$this->make_url("ad/create_default"),"post",$validate4); 
?>
<table style="width: 100%;" cellpadding="0" cellspacing="0">

<tr>
<td style="width: 150px;"><?php echo $this->get_label('name');?><span class="compulsory">*</span></td>
<td><input type="text" name="name" value="<?php echo $this->get_variable('name');?>" /></td>
</tr>


<tr>
<td><?php echo $this->get_label('clickurl');?><span class="compulsory">*</span></td>
<td><input type="text" name="clickurl" value="<?php echo $this->get_variable('clickurl');?>"><div class="notification">[<?php echo $this->get_label('example url');?>]</div></td>
</tr>


<tr>
<td><?php echo $this->get_label('banner size');?></td>
<td>

<select class="sizedropdown" name="bannersize_14" id="bannersize_14" onchange="changeadtype();">
<?php
$res14_adblock=$this->get_result('res14_adblock');
$skin_preview ="";
foreach($res14_adblock as $key=>$result)
{
	$height=$result['height'];
	$width=$result['width'];
	$id=$result['id'];
	$diamensions=$result['name'];
	$image_name= $this->get_skin_preview($id);

	$skin_preview.='<div class="skin_prev" id="skin_prev'.$id.'" style="display:none;"><img src="'.$image_name.'"></div>';	
?>
<option value="<?php echo $id;?>" <?php if($this->get_variable("bannersize")==$id) { echo "selected"; }?>><?php echo $diamensions; ?></option>
<?php }?>
</select>
<div> <?php echo $skin_preview; ?></div>
</td>
</tr>


<?php
$res14_banner=$this->get_result('res14_banner');
$ids="";
$iiii=0;
foreach($res14_banner as $key=>$result)
{
	$height=$result['height'];
	$width=$result['width'];
	$id=$result['id'];

	if($ids !="")
	$ids.=",";
	
	$ids.=$id;
?>
<tr>
<td><?php echo $this->get_label('banner');?> - <?php echo $width.' x '.$height;?><span class="compulsory">*</span></td>
<td>
<input class="form-control" type="file" name="skin_banner_<?php echo $id; ?>" size="10" />

<?php if($iiii ==0){?>
<br/>
<span class="notification"><bdi>[<?php echo $this->get_label('supported image format');?>]</bdi></span>
<?php }?>
</td>
</tr>
<?php 
$iiii++;
}?>

<tr>
<td></td>
<td>
<input type ="hidden" name ="existing_dimensions" value ="<?php echo $ids; ?>" />
<input type="hidden" name="type1" id="type1" value="14" />
<input type="submit" name="submit" value="<?php echo $this->get_label('create default ad');?>"></td>
</tr>

</table>
<?php $form5->end(); ?>
</td>
</tr>
<?php }?>


</table>
</div>
</div>
<?php $this->dispatch("layout/footer");?>
<script type="text/javascript">
changeadtype();
</script>