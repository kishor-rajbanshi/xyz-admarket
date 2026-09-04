<?php
$this->dispatch("layout/header/1/_12");

$resFontExternal        = $this->get_result("resFontExternal");

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

$validate5=array(
		"name"=>array(
				"notNull"=>array($this->get_message("not null"))

		),
		"title"=>array(
				"notNull"=>array($this->get_message("not null"))

		),
	
		"clickurl"=>array(
				"notNull"=>array($this->get_message("not null"))

		),
		"desc"=>array(
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
}
$aid=$this->get_variable("aid");


$frompg=$this->get_variable("frompg");
$tp=$this->get_variable("tp");
$st=$this->get_variable("st");
$pg=$this->get_variable("pg");


?>
<style type="text/css">
<?php foreach($resFontExternal as $fkey=>$fvalue){?>
@font-face {
  font-family: <?php echo $fvalue['slug_name'];?>;
  src: url(<?php echo BASE.DATA_DIR.'/fonts/'.$fvalue['file_name'];?>);
}
<?php } ?>
</style>
<div class="sub_menu_main"><?php echo $this->get_label('edit default ad');?></div>

<?php $this->dispatch("links/links/17");

$maxTitleLength       = Configuration::get_instance()->read('max_ad_title_length');
$maxDescriptionLength = Configuration::get_instance()->read('max_ad_desc_length');
$maxDisplayurlLength  = Configuration::get_instance()->read('max_display_url_length');
$ctaButtonLength 	  = Configuration::get_instance()->read('cta_button_text_length');
?>

<div class="inner-box">
<div class="pages-input">


<table style="width: 100%;" cellpadding="0" cellspacing="0">
<tr><td style="width: 150px;"></td><td height="10px"><?php echo $this->get_label('compulsory message');?></td></tr>

<tr><td colspan="2">	

<?php if($type==1) {

$form=$this->create_form();
$form->start("edit_default1",$this->make_url("ad/edit_default/".$aid),"post",$validate);

?>
<table>
<tr>
<td style="width: 150px;"><?php echo $this->get_label('name');?></td>
<td><input type="text" name="name" value="<?php if($_POST) {echo $this->get_variable("name");} else { echo $value1['name'];}?>"><span class="compulsory">*</span></td>
</tr>

<tr>
<td><?php echo $this->get_label('adtitle');?></td>
<td><input type="text" name="title" id="title" value="<?php if($_POST) {echo $this->get_variable("title");} else { echo $value1['title'];}?>" maxlength="<?php echo $maxTitleLength;?>" onkeyup="AdPreview(1);"><span class="compulsory">*</span><div class="notification">[<?php echo $this->get_label('max character',array('x'=>$maxTitleLength));?>]</div></td>
</tr>

<tr>
<td><?php echo $this->get_label('description');?></td>
<td><input type="text" name="desc" id="desc" value="<?php if($_POST) {echo $this->get_variable("desc");} else { echo $value1['description'];}?>" maxlength="<?php echo $maxDescriptionLength;?>" onkeyup="AdPreview(2);"><span class="compulsory">*</span><div class="notification">[<?php echo $this->get_label('max character',array('x'=>$maxDescriptionLength));?>]</div></td>
</tr>

<tr>
<td><?php echo $this->get_label('displayurl');?></td>
<td><input type="text" name="displayurl" id="displayurl" value="<?php if($_POST) {echo $this->get_variable("displayurl");} else { echo $value1['display_url'];}?>" maxlength="<?php echo $maxDisplayurlLength;?>" onkeyup="AdPreview(3);"><span class="compulsory">*</span><div class="notification">[<?php echo $this->get_label('max character',array('x'=>$maxDisplayurlLength));?>]</div></td>
</tr>

<tr>
<td><?php echo $this->get_label('cta button text');?></td>
<td><input type="text" name="cta_button_text" id="cta_button_text" value="<?php if($_POST) {echo $this->get_variable("cta_button_text");} else { echo $value1['cta_button_text'];}?>" maxlength="<?php echo $ctaButtonLength;?>" onkeyup="AdPreview(0);" /><div class="notification">[<?php echo $this->get_label('max character',array('x'=>$ctaButtonLength));?>]</div></td>
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


<?php } else if($type == 18) {     

$form=$this->create_form();
$form->start("edit_default18",$this->make_url("ad/edit_default/".$aid),"post",$validate5);

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
<td><?php echo $this->get_label('clickurl');?></td>
<td><input type="text" name="clickurl" value="<?php if($_POST) {echo $this->get_variable("clickurl");} else { echo $value1['click_url'];}?>"><span class="compulsory">*</span><div class="notification">[<?php echo $this->get_label('example url');?>]</div></td>
</tr>

<tr>
<td><?php echo $this->get_label('icon image');?></td>
<td><input type="file" name="notify_icon_image"><div class="notification">[<?php echo $this->get_label('supported image format');?>,&nbsp;<?php echo $this->get_label('preferred icon image size is', array("x"=>Configuration::get_instance()->read('push_notification_icon_width')." x ".Configuration::get_instance()->read('push_notification_icon_height'))); ?>]</div></td>
</tr>

<tr><td></td><td>
<input type="hidden" name="type" value="<?php echo $type;?>">

<input type="hidden" name="frompg" value="<?php echo $frompg;?>">
<input type="hidden" name="tp" value="<?php echo $tp;?>">
<input type="hidden" name="st" value="<?php echo $st;?>">
<input type="hidden" name="pg" value="<?php echo $pg;?>">
<input type="submit" name="submit" value="<?php echo $this->get_label('edit default ad');?>"></td></tr>
</table>

<?php $form->end(); 

} else if($type==2) {
	
	$form=$this->create_form();
	$form->start("edit_default2",$this->make_url("ad/edit_default/".$aid),"post",$validate1);
	
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
<?php $form->end(); ?>

<?php } else if($type==9) {
	$form=$this->create_form();
	$form->start("edit_default9",$this->make_url("ad/edit_default/".$aid),"post",$validate1);
	
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

<tr><td></td><td>
<input type="hidden" name="type" value="<?php echo $type;?>">

<input type="hidden" name="frompg" value="<?php echo $frompg;?>">
<input type="hidden" name="tp" value="<?php echo $tp;?>">
<input type="hidden" name="st" value="<?php echo $st;?>">
<input type="hidden" name="pg" value="<?php echo $pg;?>">
<input type="submit" name="submit" value="<?php echo $this->get_label('edit default ad');?>"></td></tr>
</table>
<?php $form->end(); ?>

<?php 
} else if($type == 21) {

$form=$this->create_form();
$form->start("edit_default21",$this->make_url("ad/edit_default/".$aid),"post",$validate1);

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

<tr><td></td><td>
<input type="hidden" name="type" value="<?php echo $type;?>">

<input type="hidden" name="frompg" value="<?php echo $frompg;?>">
<input type="hidden" name="tp" value="<?php echo $tp;?>">
<input type="hidden" name="st" value="<?php echo $st;?>">
<input type="hidden" name="pg" value="<?php echo $pg;?>">
<input type="submit" name="submit" value="<?php echo $this->get_label('edit default ad');?>"></td></tr>
</table>
<?php $form->end(); ?>

<?php } else if($type == 11) {
	$form=$this->create_form();
	$form->start("edit_default11",$this->make_url("ad/edit_default/".$aid),"post",$validate);
	?>

<table style="width: 100%;" cellpadding="0" cellspacing="0">
<tr>
<td style="width: 150px;"><?php echo $this->get_label('name');?></td>
<td><input type="text" name="name" value="<?php if($_POST) {echo $this->get_variable("name");} else { echo $value1['name'];}?>"><span class="compulsory">*</span></td>
</tr>

<tr>
<td><?php echo $this->get_label('adtitle');?></td>
<td><input type="text" name="title" id="title" value="<?php if($_POST) {echo $this->get_variable("title");} else { echo $value1['title'];}?>" maxlength="<?php echo $maxTitleLength;?>" onkeyup="AdPreview(1);"><span class="compulsory">*</span><div class="notification">[<?php echo $this->get_label('max character',array('x'=>$maxTitleLength));?>]</div></td>
</tr>

<tr>
<td><?php echo $this->get_label('description');?></td>
<td><input type="text" name="desc" id="desc" value="<?php if($_POST) {echo $this->get_variable("desc");} else { echo $value1['description'];}?>" maxlength="<?php echo $maxDescriptionLength;?>" onkeyup="AdPreview(2);"><span class="compulsory">*</span><div class="notification">[<?php echo $this->get_label('max character',array('x'=>$maxDescriptionLength));?>]</div></td>
</tr>

<tr>
<td><?php echo $this->get_label('displayurl');?></td>
<td><input type="text" name="displayurl" id="displayurl" value="<?php if($_POST) {echo $this->get_variable("displayurl");} else { echo $value1['display_url'];}?>" maxlength="<?php echo $maxDisplayurlLength;?>" onkeyup="AdPreview(3);"><span class="compulsory">*</span><div class="notification">[<?php echo $this->get_label('max character',array('x'=>$maxDisplayurlLength));?>]</div></td>
</tr>

<tr>
<td><?php echo $this->get_label('cta button text');?></td>
<td><input type="text" name="cta_button_text" id="cta_button_text" value="<?php if($_POST) {echo $this->get_variable("cta_button_text");} else { echo $value1['cta_button_text'];}?>" maxlength="<?php echo $ctaButtonLength;?>" onkeyup="AdPreview(0);" /><div class="notification">[<?php echo $this->get_label('max character',array('x'=>$ctaButtonLength));?>]</div></td>
</tr>



<tr>
<td><?php echo $this->get_label('clickurl');?></td>
<td><input type="text" name="clickurl" value="<?php if($_POST) {echo $this->get_variable("clickurl");} else { echo $value1['click_url'];}?>"><span class="compulsory">*</span><div class="notification">[<?php echo $this->get_label('example url');?>]</div></td>
</tr>

<tr>
<td><?php echo $this->get_label('banner size');?></td>
<td>
<select name="bannersize" id="bannersize" onchange="AdPreview(0);" style="width: 166px;">
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

<?php $form->end(); ?>

<?php } else if($type ==13) {

$form=$this->create_form();
$form->start("edit_default13",$this->make_url("ad/edit_default/".$aid),"post",$validate3);

?>
<table style="width: 100%;" cellpadding="0" cellspacing="0">
<tr>
<td style="width: 150px;"><?php echo $this->get_label('name');?></td>
<td><input type="text" name="name" value="<?php if($_POST) {echo $this->get_variable("name");} else { echo $value1['name'];}?>"><span class="compulsory">*</span></td>
</tr>

<tr>
<td><?php echo $this->get_label('displayurl');?></td>
<td><input type="text" name="displayurl" value="<?php if($_POST) {echo $this->get_variable("displayurl");} else { echo $value1['display_url'];}?>" maxlength="<?php echo $maxDisplayurlLength;?>"><span class="compulsory">*</span><div class="notification">[<?php echo $this->get_label('max character',array('x'=>$maxDisplayurlLength));?>]</div></td>
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
<?php $form->end(); ?>

<?php } else if($type ==14) {
	
$form=$this->create_form();
$form->start("edit_default14",$this->make_url("ad/edit_default/".$aid),"post",$validate1);
	
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
<?php $form->end(); ?>
<?php }?>
</td>

<?php if($type == 1 || $type == 11){?>
<td style="vertical-align: top;">	
<span><img id="loading" src="images/load.gif" style="display: none;" /></span>
<div class="previewSection" style="margin-top: 0px;"></div>
</td>
<?php }?>

</tr>
</table>
</div> 
</div>
<script type="text/javascript">
<?php if($type == 14){?>
function LoadSkinLayout()
{
	skinsize=$('#bannersize').val();

	$(".skin_prev").hide();
	$('#skin_prev'+skinsize).show();
}
<?php }?>

$(document).ready(function() {
	<?php if($type == 14){?>
	LoadSkinLayout();
	<?php }?>

	<?php if($type == 1 || $type == 11){?>
	AdPreview(0);
	<?php }?>
});

function AdPreview(section)
{
    titleEnabled               = 0;
    descriptionEnabled         = 0;
    urlEnabled                 = 0;
    textimageSize 			   = 0;
    titleText                  = "";
    descriptionText            = "";
    displayurlText             = "";
	buttonText 				   = "";
	bannerName                 = "";
	adType 					   = <?php echo $type;?>;


	if(adType == 11)
	{
		textimageSize              = $('#bannersize').val();	
		bannerName                 = '<?php echo $aid."_".$value1['banner'];?>';
	}

    if($('#title').val() != "")
    {
    	titleEnabled           = 1; 
    	titleText              = $('#title').val();
    }


    if($('#desc').val() != "")
    {
    	descriptionEnabled     = 1;
    	descriptionText        = $('#desc').val();
    }

    if($('#displayurl').val() != "")
    {
    	urlEnabled             = 1;
    	displayurlText         = $('#displayurl').val();
    }

    if($('#cta_button_text').val() != "")
    buttonText             = $('#cta_button_text').val();    

    if((titleEnabled == 0 && descriptionEnabled == 0 && urlEnabled == 0) || (adType != 1 && adType != 11))
    {
        $('.previewSection').html("");
        return;
    }


    if(section == 1 && $('.ad-layout-title').length > 0 && titleText != "")
    {
    	$('.ad-layout-title a p').html(titleText);
    	return;
    }	

    if(section == 2 && $('.ad-layout-description').length > 0 && descriptionText != "")
    {
    	$('.ad-layout-description a p').html(descriptionText);
    	return;
    }

    if(section == 3 && $('.ad-layout-displayurl').length > 0 && displayurlText != "")
    {
    	$('.ad-layout-displayurl a p').html(displayurlText);
    	return;
    }



    $("#loading").show();


    dataparam    = "adType="+adType+"&titleEnabled="+titleEnabled+"&descriptionEnabled="+descriptionEnabled+"&urlEnabled="+urlEnabled+"&titleText="+titleText+"&descriptionText="+descriptionText+"&displayurlText="+displayurlText+"&buttonText="+buttonText+
        "&textimageSize="+textimageSize+"&bannerName="+bannerName;

    var urlvalue = '<?php echo $this->make_url("ad/load_ad_preview");?>';

    $.ajax(
    {
        type: "POST",
        data: dataparam,
        url: urlvalue,
        success: function(message)
        {                       
            $("#loading").hide();
            $('.previewSection').show();
            $('.previewSection').html(message);
        }
    });
}

</script>
<?php $this->dispatch("layout/footer");?>
