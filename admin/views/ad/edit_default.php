<?php
$this->dispatch("layout/header/1/_12");

$pop_enabled=$this->get_variable('pop_enabled');
$cpv_enabled=$this->get_variable('cpv_enabled');
$skin_enabled=$this->get_variable('skin_enabled');


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
		)
);

$validate4=array(
		"name"=>array(
				"notNull"=>array($this->get_message("not null"))
		),
		"clickurl"=>array(
				"notNull"=>array($this->get_message("not null"))
		)
);


if($_POST)
{
	$type=$this->get_variable("type");
	$banner_id=$this->get_variable('bannersize');
}
else 
{
	$res=$this->get_result('res');
	$value1=$res[0];
	
	$type=$value1['type'];
	$banner_id=$value1['banner_id'];
	
	
	
	
	if($type ==0 && $value1['display_type']==9)
	$type=9;
}
$aid=$this->get_variable("aid");


$frompg=$this->get_variable("frompg");
$tp=$this->get_variable("tp");
$st=$this->get_variable("st");
$pg=$this->get_variable("pg");


?>
<div class="sub_menu_main"><?php echo $this->get_label('edit default ad');?></div>

<?php $this->dispatch("links/links/17");?>

<div class="inner-box">
<div class="pages-input">


<table style="width: 100%;" cellpadding="0" cellspacing="0">
<tr><td style="width: 150px;"></td><td height="10px"><?php echo $this->get_label('compulsory message');?></td></tr>

<tr><td colspan="2">	

<?php if($type==1) {

$form=$this->create_form();
$form->start("edit_default",$this->make_url("ad/edit_default/".$aid),"post",$validate);

?>
<table>
<tr>
<td style="width: 150px;"><?php echo $this->get_label('name');?></td>
<td><input type="text" name="name" value="<?php if($_POST) {echo $this->get_variable("name");} else { echo $value1['name'];}?>"><span class="compulsory">*</span></td>
</tr>

<tr>
<td><?php echo $this->get_label('adtitle');?></td>
<td><input type="text" name="title" value="<?php if($_POST) {echo $this->get_variable("title");} else { echo $value1['title'];}?>" maxlength="<?php echo Configuration::get_instance()->read('max_ad_title_length');?>"><span class="compulsory">*</span><div class="notification">[<?php echo $this->get_label('max character',array('x'=>Configuration::get_instance()->read('max_ad_title_length')));?>]</div></td>
</tr>

<tr>
<td><?php echo $this->get_label('description');?></td>
<td><input type="text" name="desc" value="<?php if($_POST) {echo $this->get_variable("desc");} else { echo $value1['description'];}?>" maxlength="<?php echo Configuration::get_instance()->read('max_ad_desc_length');?>"><span class="compulsory">*</span><div class="notification">[<?php echo $this->get_label('max character',array('x'=>Configuration::get_instance()->read('max_ad_desc_length')));?>]</div></td>
</tr>

<tr>
<td><?php echo $this->get_label('displayurl');?></td>
<td><input type="text" name="displayurl" value="<?php if($_POST) {echo $this->get_variable("displayurl");} else { echo $value1['display_url'];}?>" maxlength="<?php echo Configuration::get_instance()->read('max_display_url_length');?>"><span class="compulsory">*</span><div class="notification">[<?php echo $this->get_label('max character',array('x'=>Configuration::get_instance()->read('max_display_url_length')));?>]</div></td>
</tr>

<tr>
<td><?php echo $this->get_label('clickurl');?></td>
<td><input type="text" name="clickurl" value="<?php if($_POST) {echo $this->get_variable("clickurl");} else { echo $value1['click_url'];}?>"><span class="compulsory">*</span><div class="notification">[<?php echo $this->get_label('example url');?>]</div></td>
</tr>

<tr><td></td><td>
<input type="hidden" name="type" value="<?php echo $type;?>">

<input type="hidden" name="frompg" value="<?php echo $frompg;?>">
<input type="hidden" name="tp" value="<?php echo $tp;?>">
<input type="hidden" name="st" value="<?php echo $st;?>">
<input type="hidden" name="pg" value="<?php echo $pg;?>">
<input type="submit" name="submit" value="<?php echo $this->get_label('edit default ad');?>"></td></tr>
</table>

<?php $form->end(); ?>


<?php } else if($type==2 || $type==5) {
	
	$form1=$this->create_form();
	$form1->start("edit_default1",$this->make_url("ad/edit_default/".$aid),"post",$validate1);
	
	?>
<table style="width: 100%;" cellpadding="0" cellspacing="0">
<tr>
<td style="width: 150px;"><?php echo $this->get_label('name');?></td>
<td><input type="text" name="name" value="<?php if($_POST) {echo $this->get_variable("name");} else { echo $value1['name'];}?>"><span class="compulsory">*</span></td>
</tr>

<tr>
<td><?php echo $this->get_label('banner size');?></td>
<td>
<select name="bannersize" id="bannersize">
<?php 
$res1=$this->get_result('res1');
foreach($res1 as $key=>$result)
{
	$height=$result['height'];
	$width=$result['width'];
	$id=$result['id']; 
	$diamensions=$result['width']." x ".$result['height'];
?>
<option value="<?php echo $id;?>" <?php if($id==$banner_id){ echo "selected"; }?>><?php echo $diamensions; ?></option>
<?php }?>
</select>
</td>
</tr>

<tr>
<td><?php echo $this->get_label('banner');?></td>
<td><input type="file" name="banner" id="banner" size="10">
<br/>
<span class="notification">[<?php echo $this->get_label('supported image format');?>]</span>
</td>
</tr>


<tr>
<td><?php echo $this->get_label('clickurl');?></td>
<td><input type="text" name="clickurl" value="<?php if($_POST) {echo $this->get_variable("clickurl");} else { echo $value1['click_url'];}?>"><span class="compulsory">*</span><div class="notification">[<?php echo $this->get_label('example url');?>]</div></td>
</tr>

<tr><td></td><td>
<input type="hidden" name="type" value="<?php echo $type;?>">

<input type="hidden" name="frompg" value="<?php echo $frompg;?>">
<input type="hidden" name="tp" value="<?php echo $tp;?>">
<input type="hidden" name="st" value="<?php echo $st;?>">
<input type="hidden" name="pg" value="<?php echo $pg;?>">
<input type="submit" name="submit" value="<?php echo $this->get_label('edit default ad');?>"></td></tr>
</table>
<?php $form1->end(); ?>

<?php } else if($type==9) {
	$form2=$this->create_form();
	$form2->start("edit_default2",$this->make_url("ad/edit_default/".$aid),"post",$validate2);
	
	?>
<table style="width: 100%;" cellpadding="0" cellspacing="0">
<tr>
<td style="width: 150px;"><?php echo $this->get_label('name');?></td>
<td><input type="text" name="name" value="<?php if($_POST) {echo $this->get_variable("name");} else { echo $value1['name'];}?>"><span class="compulsory">*</span></td>
</tr>

<tr>
<td><?php echo $this->get_label('clickurl');?></td>
<td><input type="text" name="clickurl" value="<?php if($_POST) {echo $this->get_variable("clickurl");} else { echo $value1['click_url'];}?>"><span class="compulsory">*</span><div class="notification">[<?php echo $this->get_label('example url');?>]</div></td>
</tr>

<?php 

	if($pop_enabled ==1)
	$pop_type=$value1['pop_type'];
	else
	$pop_type=0;
	
	
	if($_POST)
	$pop_type=intval($this->get_variable('pop_type'));
	else
	{
		if($pop_enabled ==1)
		$pop_type=$value1['pop_type'];
		else
		$pop_type=0;
	}


	$pop_ads_support=Configuration::get_instance()->read('pop_ads_support');
	
	$pop_array=explode('-',$pop_ads_support);
	
	
	$pop_up=intval($pop_array[0]);
	$pop_under=intval($pop_array[1]);
	$pop_tab=intval($pop_array[2]);
	
/*  $pop_type=1; POP UP Only
 *  $pop_type=2; POP UNDER Only
 *  $pop_type=3; POP TAB Only
 *  $pop_type=4; POP UP & POP UNDER 
 *  $pop_type=5; POP UNDER & POP TAB 
 *  $pop_type=6; POP UP & POP TAB 
 *  $pop_type=7; POP UP & POP UNDER & POP TAB 
 */	
?>	

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

<tr><td></td><td>
<input type="hidden" name="type" value="<?php echo $type;?>">

<input type="hidden" name="frompg" value="<?php echo $frompg;?>">
<input type="hidden" name="tp" value="<?php echo $tp;?>">
<input type="hidden" name="st" value="<?php echo $st;?>">
<input type="hidden" name="pg" value="<?php echo $pg;?>">
<input type="submit" name="submit" value="<?php echo $this->get_label('edit default ad');?>"></td></tr>
</table>
<?php $form2->end(); ?>

<?php } else if($type==11){
	$form4=$this->create_form();
	$form4->start("edit_default4",$this->make_url("ad/edit_default/".$aid),"post",$validate2);
	?>

<table style="width: 100%;" cellpadding="0" cellspacing="0">
<tr>
<td style="width: 150px;"><?php echo $this->get_label('name');?></td>
<td><input type="text" name="name" value="<?php if($_POST) {echo $this->get_variable("name");} else { echo $value1['name'];}?>"><span class="compulsory">*</span></td>
</tr>

<tr>
<td><?php echo $this->get_label('adtitle');?></td>
<td><input type="text" name="title" value="<?php if($_POST) {echo $this->get_variable("title");} else { echo $value1['title'];}?>" maxlength="<?php echo Configuration::get_instance()->read('max_ad_title_length');?>"><span class="compulsory">*</span><div class="notification">[<?php echo $this->get_label('max character',array('x'=>Configuration::get_instance()->read('max_ad_title_length')));?>]</div></td>
</tr>

<tr>
<td><?php echo $this->get_label('description');?></td>
<td><input type="text" name="desc" value="<?php if($_POST) {echo $this->get_variable("desc");} else { echo $value1['description'];}?>" maxlength="<?php echo Configuration::get_instance()->read('max_ad_desc_length');?>"><span class="compulsory">*</span><div class="notification">[<?php echo $this->get_label('max character',array('x'=>Configuration::get_instance()->read('max_ad_desc_length')));?>]</div></td>
</tr>

<tr>
<td><?php echo $this->get_label('displayurl');?></td>
<td><input type="text" name="displayurl" value="<?php if($_POST) {echo $this->get_variable("displayurl");} else { echo $value1['display_url'];}?>" maxlength="<?php echo Configuration::get_instance()->read('max_display_url_length');?>"><span class="compulsory">*</span><div class="notification">[<?php echo $this->get_label('max character',array('x'=>Configuration::get_instance()->read('max_display_url_length')));?>]</div></td>
</tr>

<tr>
<td><?php echo $this->get_label('clickurl');?></td>
<td><input type="text" name="clickurl" value="<?php if($_POST) {echo $this->get_variable("clickurl");} else { echo $value1['click_url'];}?>"><span class="compulsory">*</span><div class="notification">[<?php echo $this->get_label('example url');?>]</div></td>
</tr>

<tr><td></td><td>

<tr>
<td><?php echo $this->get_label('banner size');?></td>
<td>
<select name="bannersize">
<?php 
$res1=$this->get_result('res1');
foreach($res1 as $key=>$result)
{
	$height=$result['height'];
	$width=$result['width'];
	$id=$result['id']; 
	$diamensions=$result['width']." x ".$result['height'];
?>	
<option value="<?php echo $id;?>" <?php if($id == $banner_id){echo "selected";}?>><?php echo $diamensions; ?></option>
<?php }?>
</select>
</td>
</tr>

<tr>
<td><?php echo $this->get_label('banner');?></td>
<td><input type="file" name="banner" id="banner" size="10">
<br/>
<span class="notification">[<?php echo $this->get_label('supported image format');?>]</span>
</td>
</tr>
<tr>
<td>
<input type="hidden" name="type" value="<?php echo $type;?>">

<input type="hidden" name="frompg" value="<?php echo $frompg;?>">
<input type="hidden" name="tp" value="<?php echo $tp;?>">
<input type="hidden" name="st" value="<?php echo $st;?>">
<input type="hidden" name="pg" value="<?php echo $pg;?>">
</td>
<td>
<input type="submit" name="submit" value="<?php echo $this->get_label('edit default ad');?>">
</td></tr>
</table>

<?php $form4->end(); ?>

<?php } else if($type ==13) {

$form5=$this->create_form();
$form5->start("edit_default5",$this->make_url("ad/edit_default/".$aid),"post",$validate3);

?>
<table style="width: 100%;" cellpadding="0" cellspacing="0">
<tr>
<td style="width: 150px;"><?php echo $this->get_label('name');?></td>
<td><input type="text" name="name" value="<?php if($_POST) {echo $this->get_variable("name");} else { echo $value1['name'];}?>"><span class="compulsory">*</span></td>
</tr>

<tr>
<td><?php echo $this->get_label('displayurl');?></td>
<td><input type="text" name="displayurl" value="<?php if($_POST) {echo $this->get_variable("displayurl");} else { echo $value1['display_url'];}?>" maxlength="<?php echo Configuration::get_instance()->read('max_display_url_length');?>"><span class="compulsory">*</span><div class="notification">[<?php echo $this->get_label('max character',array('x'=>Configuration::get_instance()->read('max_display_url_length')));?>]</div></td>
</tr>


<tr>
<td><?php echo $this->get_label('clickurl');?></td>
<td><input type="text" name="clickurl" value="<?php if($_POST) {echo $this->get_variable("clickurl");} else { echo $value1['click_url'];}?>"><span class="compulsory">*</span><div class="notification">[<?php echo $this->get_label('example url');?>]</div></td>
</tr>


<tr>
<td><?php echo $this->get_label('video file');?></td>
<td><input type="file" name="videofile" id="videofile" size="10"><span class="compulsory">*</span><br/>

<span class="notification">[<?php echo $this->get_label('supported video format html5');?>]</span>
<span class="notification">[<?php echo $this->get_label('supported aspect ratio',array('x'=>$this->get_aspect_ratio_list()));?>]</span>

</td>
</tr>

<tr><td></td><td>
<input type="hidden" name="type" value="<?php echo $type;?>">
<input type="hidden" name="frompg" value="<?php echo $frompg;?>">
<input type="hidden" name="tp" value="<?php echo $tp;?>">
<input type="hidden" name="st" value="<?php echo $st;?>">
<input type="hidden" name="pg" value="<?php echo $pg;?>">

<input type="submit" name="submit" value="<?php echo $this->get_label('edit default ad');?>"></td></tr>

</table>
<?php $form5->end(); ?>

<?php } else if($type ==14) {
	
$form6=$this->create_form();
$form6->start("edit_default6",$this->make_url("ad/edit_default/".$aid),"post",$validate4);
	
?>
<table style="width: 100%;" cellpadding="0" cellspacing="0">
<tr>
<td style="width: 150px;"><?php echo $this->get_label('name');?><span class="compulsory">*</span></td>
<td><input type="text" name="name" value="<?php if($_POST) {echo $this->get_variable("name");} else { echo $value1['name'];}?>"></td>
</tr>


<tr>
<td><?php echo $this->get_label('clickurl');?><span class="compulsory">*</span></td>
<td><input type="text" name="clickurl" value="<?php if($_POST) {echo $this->get_variable("clickurl");} else { echo $value1['click_url'];}?>"><div class="notification">[<?php echo $this->get_label('example url');?>]</div></td>
</tr>

<tr>
<td><?php echo $this->get_label('banner size');?></td>
<td>
<select name="bannersize" id="bannersize" onchange="LoadSkinLayout();">
<?php 
$res1=$this->get_result('res14_adblock');

$skin_preview="";	
	
foreach($res1 as $key=>$result)
{
	$height=$result['height'];
	$width=$result['width'];
	$id=$result['id'];
	$diamensions=$result['name'];
	$image_name=$this->get_skin_preview($id);
			
	$skin_preview.='<div class="skin_prev" id="skin_prev'.$id.'" style="display:none;" ><img src="'.$image_name.'"></div>';
	?>
	<option value="<?php echo $id;?>" <?php if($banner_id == $id) { echo "selected"; }?>><?php echo $diamensions;?></option>
	<?php }?>
</select>

<?php echo $skin_preview;?>
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

<tr><td></td><td>
<input type="hidden" name="existing_dimensions" value ="<?php echo $ids; ?>" />
<input type="hidden" name="type" value="<?php echo $type;?>" />
<input type="hidden" name="frompg" value="<?php echo $frompg;?>" />
<input type="hidden" name="tp" value="<?php echo $tp;?>" />
<input type="hidden" name="st" value="<?php echo $st;?>" />
<input type="hidden" name="pg" value="<?php echo $pg;?>" />
<input type="submit" name="submit" value="<?php echo $this->get_label('edit default ad');?>"></td></tr>
</table>
<?php $form6->end(); ?>
<?php }?>
</td></tr>
</table>
</div> 
</div>
<?php if($type ==14){?>
<script type="text/javascript">
function LoadSkinLayout()
{
	skinsize=$('#bannersize').val();

	$(".skin_prev").hide();
	$('#skin_prev'+skinsize).show();
}

$(document).ready(function() {
LoadSkinLayout();
});
</script>
<?php }?>
<?php $this->dispatch("layout/footer");?>