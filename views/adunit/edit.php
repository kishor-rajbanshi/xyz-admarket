<?php $this->dispatch("layout/header/6/2/p");?>
<?php 
$basepath=str_replace('https://','',DISPLAY_BASE);
$basepath=str_replace('http://','',$basepath);


$pop_enabled=$this->get_variable('pop_enabled');
$pricing=$this->get_variable('pricing');


$get_direct_link=0;

if($pop_enabled ==1 && intval(Configuration::get_instance()->read('pop_direct_link_enabled')) ==1)
$get_direct_link=intval($this->get_variable('get_direct_link'));


?>
<style type="text/css">
.adblock-edit .form-group,.adblock-edit .form-group label
{
	padding-left:0px;
	padding-right:0px;
}
</style>

 
<?php if($pricing !=9){?>
<script type="text/javascript" src="<?php echo COMMON_DIR_PATH;?>farbtastic/farbtastic.js"></script>
<link rel="stylesheet" href="<?php echo COMMON_DIR_PATH;?>farbtastic/farbtastic.css" type="text/css" />

<script type="text/javascript" charset="utf-8">
$(document).ready(function() {
	if($('.color-picker').length >0)
	{
	    var f = $.farbtastic('#picker');
	    var p = $('#picker').css('opacity', 0.25);
	    var selected;

	    $('.color-picker').each(function () { f.linkTo(this); $(this).css('opacity', 0.75); })
	      .focus(function() 
	      {
	    	if(selected) {$(selected).css('opacity', 0.75);}
		      
	        f.linkTo(this);
	        $(this).css('opacity',1);
	        p.css('opacity', 1);
	        $(selected = this).css('opacity', 1);
	      });
	 }
});
</script>
<?php }?>


<script type="text/javascript">
function ShowPreview(id)
{
	if(id ==1)
	{
	    if($('#text').length >0)
	    $('#text').attr('checked',true);

	    $('#td1').show();
	    $('#bd1').hide();
	}

	if(id ==2)
	{
		if($('#banner').length >0)
		$('#banner').attr('checked',true);

		 $('#td1').hide();
		 $('#bd1').show();
	}
}
 
 </script>
<?php 
$cpc_enabled=$this->get_addon_status('cpc_enabled');
$cpa_enabled=$this->get_addon_status('cpa_enabled');
$cpm_enabled=$this->get_addon_status('cpm_enabled');
$html_enabled=$this->get_addon_status('html_enabled');
$category_enabled=$this->get_addon_status('category-targeting_enabled');
$sponsored_enabled=$this->get_addon_status('sponsored_enabled');

if($cpm_enabled ==1 || $html_enabled ==1)
$cpm_enabled=1;

$text_ads_enabled=Configuration::get_instance()->read('text-ads_enabled');
$skin_enabled=$this->get_addon_status('skin-ads_enabled');


$video_enabled=$this->get_variable('video_enabled');
$linear_support=$this->get_variable('linear_support');
$nonlinear_support=$this->get_variable('nonlinear_support');
$html5_player_support=$this->get_variable('html5_player_support');

$adcode_for=$this->get_variable('adcode_for');
$linear=$this->get_variable('linear');


$res=$this->get_result('res');
$aduid=$this->get_variable('aduid');
$uid=$this->get_variable("uid");



$result=$res[0];


if($_POST)
{
	$nonlinearbanner=$this->get_variable('nonlinearbanner');
	$nonlineartext=$this->get_variable('nonlineartext');
	$nonlinear_size=$this->get_variable('nonlinear_size');
}
else
{
	$nonlinearbanner=$result['non_linear_banner'];
	$nonlineartext=$result['non_linear_text'];
	$nonlinear_size=$result['player_size'];
}


if($text_ads_enabled ==0 && $nonlineartext ==1)
$nonlineartext=0;

$validate=array(
		"aduname"=>array(
				"notNull"=>array($this->get_message("not null"))
		));
?>


<div class="container"><h2 class="page_heading"><?php echo $this->get_label('edit adunit');?></h2>
<div class="page_heading-btm"></div>
</div>

 <div class="container label_style special-label">
   <div class="col-md-12 col-sm-12 col-xs-12 box_style">



<?php if($pricing !=9 && $pricing !=13 && $result['banner_type'] !=4){?>


<div class="col-md-12 col-sm-12 col-xs-12">
<div class="col-md-12 col-sm-12 col-xs-12">
<h2 class="page_heading page_heading_inner"><?php echo $this->get_label('adunit preview');?></h2>
</div>

<div class="col-md-12 col-sm-12 col-xs-12">
 <div class="col-md-12 col-sm-12 col-xs-12 box_div_bg" style="padding-top: 10px;">

 <?php 
    if($result['type']==3)
    {
    ?>
<div class="form-group col-md-12 col-sm-12 col-xs-12">
<label class="col-md-3 col-sm-4 col-xs-12" >
<input type="radio" value="1" name="text" id="text" checked="checked" onClick="javascript:ShowPreview(this.value);"><?php echo $this->get_label('text adunit preview');?>
</label>
<label class="col-md-4 col-sm-5 col-xs-12" >
<input type="radio" value="2" name="text" id="banner" onClick="javascript:ShowPreview(this.value);"><?php echo $this->get_label('banner adunit preview');?>
</label>   
</div>


<div class="form-group col-md-12 col-sm-12 col-xs-12" id="td1" style="overflow-x: auto;">
<iframe height="<?php echo $result['height']; ?>" width="<?php echo $result['width']; ?>" frameborder="0" src="<?php echo $this->make_url("adunit/preview/".$result['auid']."/1")?>" ></iframe> 
</div>
    
    
<div class="form-group col-md-12 col-sm-12 col-xs-12" id="bd1" style="display: none;overflow-x: auto;">
<iframe height="<?php echo $result['height']; ?>" width="<?php echo $result['width']; ?>" frameborder="0" src="<?php echo $this->make_url("adunit/preview/".$result['auid']."/2")?>" ></iframe> 
</div>


<?php }?>

<?php if($result['type']==1) { ?>
    <div class="form-group col-md-12 col-sm-12 col-xs-12" id="td1" style="overflow-x: auto;">
    <iframe height="<?php echo $result['height']; ?>" width="<?php echo $result['width']; ?>" frameborder="0" src="<?php echo $this->make_url("adunit/preview/".$result['auid']."/1")?>" ></iframe> 
    </div>
  <?php }?>
    
 <?php if($result['type']==2) { ?>   
    <div class="form-group col-md-12 col-sm-12 col-xs-12" id="bd1" style="overflow-x: auto;">
    <iframe height="<?php echo $result['height']; ?>" width="<?php echo $result['width']; ?>" frameborder="0" src="<?php echo $this->make_url("adunit/preview/".$result['auid']."/2")?>" ></iframe> 
    </div>
  <?php }?>
  
    
   <?php 
    if($result['type']==4)
    {
    ?>   
     <tr>
   
    <td  colspan="2">
    <div id="bd1" >
    <iframe height="<?php echo $result['height']; ?>" width="<?php echo $result['width']; ?>" frameborder="0" src="<?php echo $this->make_url("adunit/preview/".$result['auid']."/3")?>" ></iframe> 
    
    </div>
    
    </td>
   
    <td ></td>
</tr>
  <?php }?>

</div></div></div>

<?php }?>



<div class="col-md-12 col-sm-12 col-xs-12">
<div class="col-md-12 col-sm-12 col-xs-12">
<h2 class="page_heading page_heading_inner"><?php echo $this->get_label('ad display code');?></h2>
</div>
   
   
   <div class="col-md-12 col-sm-12 col-xs-12">
 <div class="col-md-12 col-sm-12 col-xs-12 box_div_bg" style="padding-top: 20px;">
   
<div class="form-group col-md-12 col-sm-12 col-xs-12">

<?php 

if($pricing !=9 && $result['banner_type'] ==1)
$pricingdata=5;
else if($pricing !=9 && $result['banner_type'] ==4)
$pricingdata=14;
else
$pricingdata=$result['display_type'];

$admarket_name=Configuration::get_instance()->read('admarket_name');
?>

<div style="cursor: pointer;float: right;margin-bottom: 5px;"><i class="fa fa-clipboard fa-lg" aria-hidden="true" id="adcode" title="<?php echo $this->get_label('copy adcode');?>"></i></div>

<textarea id="textarea-adcode" style="border: 1px solid #CCCCCC;width: 100%;<?php if($result['video_type'] !=1){?>min-height: 120px;<?php }?>" readonly="readonly">
<?php if($result['video_type'] !=1){?>
<!-- <?php  echo $admarket_name;?> - <?php echo $this->get_label('ad code');?> -->
<?php }?>
<?php if($pricing !=9 && $pricing !=13){?>
<div id="adm-container-<?php echo $aduid;?>"></div><script data-cfasync="false" async type="text/javascript" src="<?php echo '//'.$basepath.DISPLAY_DIR; ?>/items.php?<?php echo $this->get_variable('aduid'); ?>&<?php echo $uid; ?>&<?php echo $result['width']; ?>&<?php echo $result['height']; ?>&<?php echo $pricingdata;?>"></script>
<?php }else{?>
<?php if($pricing ==9){?>
<script data-cfasync="false" async type="text/javascript" src="<?php echo '//'.$basepath.DISPLAY_DIR; ?>/items.php?<?php echo $this->get_variable('aduid'); ?>&<?php echo $uid; ?>&0&0&<?php echo $pricingdata;?>"></script>
<?php }
else if($pricing ==13 && $result['video_type'] ==1)
echo '//'.$basepath.DISPLAY_DIR.'/query/video/'.$this->get_variable('aduid');
else if($pricing ==13 && $result['video_type'] ==2){?>
<div id="adm-container-<?php echo $aduid;?>"></div><script data-cfasync="false" type="text/javascript" async src="<?php echo '//'.$basepath.DISPLAY_DIR; ?>/items.php?<?php echo $this->get_variable('aduid'); ?>&<?php echo $uid; ?>&<?php echo $result['width'];?>&<?php echo $result['height'];?>&<?php echo $pricingdata;?>"></script>
<?php }}?>
<?php if($result['video_type'] !=1){?>
<!-- <?php  echo $admarket_name;?> - <?php echo $this->get_label('ad code');?> -->
<?php }?>
</textarea>  
<?php if($result['video_type'] ==1){?>
<span class="notification"><?php echo $this->get_label('copy the vast tag');?></span>
<?php }?>
<?php if($pricing ==9){?>
<span class="notification"><?php echo $this->get_label('multiple pop adcodes in a single page is not supported');?></span>
<?php }?>



<?php if($pricing ==9 && $get_direct_link ==1){?>
<br/>
<h2 class="page_heading page_heading_inner"><?php echo $this->get_label('pop direct link');?></h2>

<div style="cursor: pointer;float: right;margin-bottom: 5px;"><i class="fa fa-clipboard fa-lg" aria-hidden="true" id="pop-adcode" title="<?php echo $this->get_label('copy adcode');?>"></i></div>

<textarea id="textarea-pop-adcode" style="border: 1px solid #CCCCCC;width: 100%;height: 35px;" readonly="readonly">
<?php echo BASE.DISPLAY_DIR.'/popads/'.$this->get_variable('aduid');?>
</textarea>
<?php }?>

</div>    
</div>

</div>    
</div>

<div class="col-md-12 col-sm-12 col-xs-12 adblock-edit">

<?php 

$form=$this->create_form();
$form->start("editadunit",$this->make_url("adunit/edit/".$aduid),"post",$validate);
$flag= $this->get_variable('flag');
$aduname=$this->get_variable('aduname');


if($_POST)
{
	$abrt=$this->get_variable("abrt");
	$sid=$this->get_variable("sid");
	
	$popup_support=$this->get_variable('popup_support');
	$popunder_support=$this->get_variable('popunder_support');
	$poptab_support=$this->get_variable('poptab_support');
	
}
else 
{
	$abrt=$result['abr_type'];
	
	if($category_enabled ==1)
	$sid=$result['sid'];
	
	if($pop_enabled ==1)
	{
		$popup_support=$result['pop_up_support'];
		$popunder_support=$result['pop_under_support'];
		$poptab_support=$result['pop_tab_support'];
	}
	else
	{
		$popup_support=0;
		$popunder_support=0;
		$poptab_support=0;
	}
	
}


?>


<div class="
<?php if($pricing !=9 && $result['banner_type'] !=4)
{
	if($pricing !=13 || ($pricing ==13 && $result['blockid'] >0)){?>
	col-md-6 col-sm-6
	<?php }else{?>
	col-md-12 col-sm-12
	<?php }?>
<?php }else{?>col-md-12 col-sm-12<?php }?> 

col-xs-12 box_style">

<h2 class="page_heading page_heading_inner"><?php echo $this->get_label('basic settings');?></h2>

   
 <div class="col-md-12 col-sm-12 col-xs-12 box_div_bg" style="padding-top:16px;">
<div class="form-group col-md-12 col-sm-12 col-xs-12">
<label class="col-md-6 col-sm-6 col-xs-12 " ><?php echo $this->get_label('adunit name');?>
<input type="hidden" name="aduid" id="aduid" value="<?php echo $aduid; ?>" />
<input type="hidden" name="adcode_for" id="adcode_for" value="<?php echo $result['video_type']; ?>" />

</label>
<label class="col-md-6 col-sm-6 col-xs-12 " ><input class="form-control" type="text" name="aduname" id="aduname" value="<?php if($_POST) echo $aduname; else echo $result['auname'];?>" size="12" maxlength="25"/></label>
</div>


<?php if($category_enabled ==1){?>
<div class="form-group col-md-12 col-sm-12 col-xs-12 site-class" style="display: none;">
<label class="col-md-6 col-sm-6 col-xs-12 " ><?php echo $this->get_label('targeting site');?> 
<?php if($result['display_type'] !=3){?>
<span class="compulsory">*</span>
<?php }?>
</label>
<label class="col-md-6 col-sm-6 col-xs-12 " >

<?php 
if($result['display_type'] !=3)
echo CategoryHelper::get_site_dropdown($uid,$sid);
else
{
	echo CategoryHelper::get_site_name($sid);
	?>
	<input type="hidden" name="sid" id="sid" value="<?php echo $sid;?>" />
	<?php 
}
?>
</label>
</div>
<?php }?>



<div class="form-group col-md-12 col-sm-12 col-xs-12">
<label class="col-md-6 col-sm-6 col-xs-12 " ><?php echo $this->get_label('adunit type');?></label>
<label class="col-md-6 col-sm-6 col-xs-12 " >
<input type="hidden" name="adpricing" id="adpricing" value="<?php echo $result['display_type'];?>" /><?php echo $this->get_adunit_preference($result['display_type']);?>
</label>
</div>




<?php 
if($pricing !=9)
{
	if($pricing !=13 || ($pricing ==13 && $result['blockid'] >0)){

	if($result['banner_type'] !=4){?>

	<div class="form-group col-md-12 col-sm-12 col-xs-12">
	<label class="col-md-6 col-sm-6 col-xs-12" ><?php echo $this->get_label('adunit dimensions');?></label>
	<label class="col-md-6 col-sm-6 col-xs-12" ><bdi><?php echo $result['width']." x ".$result['height'];?>
	</bdi>
	</label>
	</div>

	<?php }}?>
<?php }?> 






<?php if($pricing !=9 && $pricing !=13){?>


<div class="form-group col-md-12 col-sm-12 col-xs-12">
<label class="col-md-6 col-sm-6 col-xs-12 " ><?php echo $this->get_label('adtype');?></label>
<label class="col-md-6 col-sm-6 col-xs-12 " ><?php echo $this->get_adblock_type($result['type'],$result['banner_type']);?></label>
</div>

 
<?php if($result['type']==1 || $result['type']==3 || $result['type']==4){?>
    
     <?php if($result['display_type'] !=3){?>   
    
<div class="form-group col-md-12 col-sm-12 col-xs-12">
<label class="col-md-6 col-sm-6 col-xs-12 " ><?php echo $this->get_label('no of text ads');?></label>
<label class="col-md-6 col-sm-6 col-xs-12 " ><?php echo $result['textadcount'];?></label>
</div>
<?php }?>
 
<?php 
if(Configuration::get_instance()->read('allow_brtype')=="1")
 {
 ?>
<div class="form-group col-md-12 col-sm-12 col-xs-12">  
<label class="col-md-6 col-sm-6 col-xs-12 " ><?php echo $this->get_label('border type');?></label>
<label class="col-md-6 col-sm-6 col-xs-12 " >
    <input type="radio" name="border" id="border" value="1" <?php if($abrt==1){echo "checked";}?>/><?php echo $this->get_label('regular');?>
    <input type="radio" name="border" id="border" value="0" <?php if($abrt==0){echo "checked";}?>/><?php echo $this->get_label('rounded');?>
</label>
</div>
   <?php 
 	}
   else 
   {
   ?>
<div class="form-group col-md-12 col-sm-12 col-xs-12">   
<label class="col-md-6 col-sm-6 col-xs-12 " ><?php echo $this->get_label('border type');?></label>
<label class="col-md-6 col-sm-6 col-xs-12 " >
    <?php 
    if($result['bordertype']==1)
    echo $this->get_label('regular');
    else  if($result['bordertype']==0)
    echo $this->get_label('rounded');
    ?>
</label>
</div>
   <?php }?>
   
   <?php 
    }
   ?>
   
   
<?php } else if($pricing ==9){

	$pop_ads_support=Configuration::get_instance()->read('pop_ads_support');
	
	$pop_array=explode('-',$pop_ads_support);

	$pop_up=intval($pop_array[0]);
	$pop_under=intval($pop_array[1]);
	$pop_tab=intval($pop_array[2]);
	
?>


<div class="form-group col-md-12 col-sm-12 col-xs-12">
<label class="col-md-6 col-sm-6 col-xs-12 "><?php echo $this->get_label('pop type');?> <span class="compulsory">*</span></label>
<label class="col-md-6 col-sm-6 col-xs-12 ">

<?php if($pop_up ==1){?>
<span><input style="width: 20px !important;" type="checkbox" name="popup_support" id="popup_support" value="1" <?php if($popup_support ==1){?> checked="checked" <?php }?> /> <?php echo $this->get_label('popup');?></span>
<?php }?>

<?php if($pop_under ==1){?>
<span><input style="width: 20px !important;" type="checkbox" name="popunder_support" id="popunder_support" value="1" <?php if($popunder_support ==1){?> checked="checked" <?php }?> /> <?php echo $this->get_label('popunder');?></span>
<?php }?>

<?php if($pop_tab ==1){?>
<span><input style="width: 20px !important;" type="checkbox" name="poptab_support" id="poptab_support" value="1" <?php if($poptab_support ==1){?> checked="checked" <?php }?> /> <?php echo $this->get_label('poptab');?></span>
<?php }?>
</label>
</div>

<?php } else if($pricing ==13){?>

<div class="form-group col-md-12 col-sm-12 col-xs-12">
<label class="col-md-6 col-sm-6 col-xs-12" ><?php echo $this->get_label('adcode for');?></label>
<label class="col-md-6 col-sm-6 col-xs-12" >
<?php 
if($result['video_type'] ==1)
echo $this->get_label('vast player');
else if($result['video_type'] ==2)
echo $this->get_label('html5 player');
?>
</label>
</div>


<?php if($result['video_type'] ==1 && ($linear_support ==1 || $nonlinear_support ==1)){

$res14=$this->get_result('res14');	
?>
<div class="form-group col-md-12 col-sm-12 col-xs-12">
<label class="col-md-6 col-sm-6 col-xs-12" ><?php echo $this->get_label('supported type');?> <span class="compulsory">*</span></label>
<label class="col-md-6 col-sm-6 col-xs-12" >
<div style="width: 100%;height: 20px;">

<?php if($linear_support ==1){?>
<div style="float: left;">
<input disabled="disabled" type="checkbox" name="linear" id="linear" value="1" checked="checked" /> <?php echo $this->get_label('linear');?>&nbsp;&nbsp;
</div>
<?php }?>

<?php if($nonlinear_support ==1){?>

<?php if($text_ads_enabled ==1){?>
<div style="float: left;">
<input  type="checkbox" name="nonlineartext" id="nonlineartext" value="1" <?php if($nonlineartext ==1){?>checked="checked"<?php }?> /> <?php echo $this->get_label('non linear text');?>&nbsp;&nbsp;
</div>
<?php }?>

<?php if(count($res14) >0){?>
<div style="float: left;">
<input  type="checkbox" name="nonlinearbanner" id="nonlinearbanner" value="1" <?php if($nonlinearbanner ==1){?>checked="checked"<?php }?> onclick="LoadVideoOptions();"/> <?php echo $this->get_label('non linear banner');?>
</div>
<?php }?>

<?php }?>
</div>
</label>
</div>



<?php if($nonlinear_support ==1 && count($res14) >0){?>
<div class="form-group col-md-12 col-sm-12 col-xs-12 video-option-vast-size" style="display: none;">
<label class="col-md-6 col-sm-6 col-xs-12" ><?php echo $this->get_label('banner dimension');?></label>
<label class="col-md-6 col-sm-6 col-xs-12" >

<?php if($nonlinear_size ==0){?>
<select class="form-control" name="nonlinear_size" id="nonlinear_size">
<?php 
foreach($res14 as $key=>$result14)
{
	$height=$result14['height'];
	$width=$result14['width'];
	$id=$result14['id'];
	$diamensions=$result14['width']." x ".$result14['height'];
?>
<option value="<?php echo $id;?>" <?php if($nonlinear_size == $id) { echo "selected"; }?>><?php echo $diamensions; ?></option>
<?php }?>
</select>
<?php }else{

	$size_data=$this->get_banner_dimension($nonlinear_size);
	$size_array=explode('-',$size_data);

	echo $size_array[0].' x '.$size_array[1];?>
<input type="hidden" name="nonlinear_size" id="nonlinear_size" value="<?php echo $nonlinear_size;?>" />
<?php }?>

</label>
</div>
<?php }?>


<?php }?>   
  
<?php }?>    





<?php if($result['banner_type'] ==4){?>


<div class="form-group col-md-12 col-sm-12 col-xs-12">
<label class="col-md-6 col-sm-6 col-xs-12 " ><?php echo $this->get_label('main container id');?></label>
<label class="col-md-6 col-sm-6 col-xs-12 " ><input class="form-control" type="text" name="container_id" id="container_id" value="<?php if($_POST) echo $container_id; else echo $result['container_id'];?>" size="150" /></label>
</div>

<div class="form-group col-md-12 col-sm-12 col-xs-12">
<label class="col-md-6 col-sm-6 col-xs-12" ><?php echo $this->get_label('skin layout');?></label>
<label class="col-md-6 col-sm-6 col-xs-12" >
</label>
<?php
$skin_position1=html_entity_decode($result['skin_positions']);
$skin_position=json_decode($skin_position1,1);
$left=$skin_position['L'] ==1? 'L' :0;
$right=$skin_position['R']==1? 'R' :0;
$top=$skin_position['T']==1? 'T' :0; 
$bottom=$skin_position['B']==1? 'B' :0;

$image_name= $left."_".$right."_".$top."_".$bottom.".png";
?>

<img src='<?php echo BASE.ADDON_DIR."/skin-ads/images/".$image_name;?>'>
</div>

<?php }?>
   
</div>
</div>


<?php if($pricing !=9 && $result['banner_type'] !=4){?>

<?php if($pricing !=13 || ($pricing ==13 && $result['blockid'] >0)){?>


<div class="col-md-6 col-sm-6 col-xs-12 box_style">

<h2 class="page_heading page_heading_inner"><?php echo $this->get_label('color settings');?></h2> 
 
  
   
 <div class="col-md-12 col-sm-12 col-xs-12 box_div_bg" style="padding-top: 0px;">
  
<?php if(Configuration::get_instance()->read('allow_ccolor')==1){?>
<label class="col-md-12 col-sm-12 col-xs-12 form-group" ><div id="picker" style="float: left;"></div></label>
<?php } else {?>
<label class="col-md-12 col-sm-12 col-xs-12 form-group" ><?php if($flag!=0){?><div id="picker" style="float: left;"></div><?php }?></label>
<?php }?>
  

<?php if($result['type']==1 || $result['type']==3 || $result['type']==4) { ?>
   
<?php if(Configuration::get_instance()->read('allow_tcolor')==1) {?>
   
<div class="col-md-12 col-sm-12 col-xs-12">
<label class="col-md-6 col-sm-6 col-xs-12 form-group" ><?php echo $this->get_label('ad title color');?></label>
<label class="col-md-6 col-sm-6 col-xs-12 form-group" ><input name="color1" type="text" id="color1" class="color-picker"  style="background-color:<?php echo $result['at_color'];?>" value="<?php echo $result['at_color'];?>"  ></label>
</div>   
<?php } else { ?>
<div class="col-md-12 col-sm-12 col-xs-12">  
<label class="col-md-6 col-sm-6 col-xs-12 form-group" ><?php echo $this->get_label('ad title color');?></label>
<label class="col-md-6 col-sm-6 col-xs-12 form-group" ><?php echo $result['tcolor'];?></label>
</div>   
<?php 
}
  
if(Configuration::get_instance()->read('allow_dcolor')==1){?>
<div class="col-md-12 col-sm-12 col-xs-12">		
<label class="col-md-6 col-sm-6 col-xs-12 form-group" ><?php echo $this->get_label('ad description color');?></label>
<label class="col-md-6 col-sm-6 col-xs-12 form-group" ><input  type="text" id="color2" name="color2" class="color-picker"  value="<?php echo $result['ad_color'];?>"  style="background-color:<?php echo $result['ad_color'];?>"></label>
</div> 
<?php } else  { ?>
<div class="col-md-12 col-sm-12 col-xs-12">		  
<label class="col-md-6 col-sm-6 col-xs-12 form-group" ><?php echo $this->get_label('ad description color');?></label>
<label class="col-md-6 col-sm-6 col-xs-12 form-group" ><?php echo $result['dcolor'];?></label>
</div> 
<?php }
  
if(Configuration::get_instance()->read('allow_ucolor')==1) {?>
  
<div class="col-md-12 col-sm-12 col-xs-12">  
<label class="col-md-6 col-sm-6 col-xs-12 form-group" ><?php echo $this->get_label('ad display url color');?></label>
<label class="col-md-6 col-sm-6 col-xs-12 form-group" ><input  type="text" id="color3" name="color3" class="color-picker"  value="<?php echo $result['au_color'];?>" style="background-color:<?php echo $result['au_color'];?>"></label>
</div>
<?php  }  else  {?>
<div class="col-md-12 col-sm-12 col-xs-12">  
<label class="col-md-6 col-sm-6 col-xs-12 form-group" ><?php echo $this->get_label('ad display url color');?></label>
<label class="col-md-6 col-sm-6 col-xs-12 form-group" ><?php echo $result['ucolor'];?></label>
</div>
<?php }}?>
   
<?php if(Configuration::get_instance()->read('allow_bcolor')==1){?>
<div class="col-md-12 col-sm-12 col-xs-12">
<label class="col-md-6 col-sm-6 col-xs-12 form-group" ><?php echo $this->get_label('background color');?></label>
<label class="col-md-6 col-sm-6 col-xs-12 form-group" ><input type="text" id="color4" name="color4" class="color-picker" value="<?php echo $result['ab_color'];?>" style="background-color:<?php echo $result['ab_color'];?>"></label>
</div>
<?php } else {?>
<div class="col-md-12 col-sm-12 col-xs-12">   
<label class="col-md-6 col-sm-6 col-xs-12 form-group" ><?php echo $this->get_label('background color');?></label>
<label class="col-md-6 col-sm-6 col-xs-12 form-group" ><?php echo $result['bcolor'];?></label>
</div>
<?php }?>   
   
   
<?php if(Configuration::get_instance()->read('allow_ccolor')==1){?>
<div class="col-md-12 col-sm-12 col-xs-12">
<label class="col-md-6 col-sm-6 col-xs-12 form-group" ><?php echo $this->get_label('credit color');?></label>
<label class="col-md-6 col-sm-6 col-xs-12 form-group" ><input type="text" id="color5" name="color5" class="color-picker" value="<?php echo $result['ac_color'];?>" style="background-color:<?php echo $result['ac_color'];?>"></label>
</div>
<?php } else {?>
<div class="col-md-12 col-sm-12 col-xs-12">
<label class="col-md-6 col-sm-6 col-xs-12 form-group" ><?php echo $this->get_label('credit color');?></label>
<label class="col-md-6 col-sm-6 col-xs-12 form-group" ><?php echo $result['ccolor'];?></label>
</div>
<?php }
 
if(Configuration::get_instance()->read('allow_brcolor')==1){?>
 
<div class="col-md-12 col-sm-12 col-xs-12"> 
<label class="col-md-6 col-sm-6 col-xs-12 form-group" ><?php echo $this->get_label('border color');?></label>
<label class="col-md-6 col-sm-6 col-xs-12 form-group" ><input type="text" id="color6" name="color6" class="color-picker" value="<?php echo $result['abr_color'];?>" style="background-color:<?php echo $result['abr_color'];?>"></label>
</div>  
<?php } else {?>
<div class="col-md-12 col-sm-12 col-xs-12"> 
<label class="col-md-6 col-sm-6 col-xs-12 form-group" ><?php echo $this->get_label('border color');?></label>
<label class="col-md-6 col-sm-6 col-xs-12 form-group" ><?php echo $result['br_color'];?></label>
</div>   
<?php }?>

</div>
</div>
<?php }}?> 
  
  
 
 
 
 
<div class="col-md-12 col-sm-12 col-xs-12" style="text-align: center;">


<input class="btn btn-primary btn-lg" type="submit" name="submit" value="<?php echo $this->get_label('update adunit');?>" />
</div>
   

<?php $form->end(); ?>

</div>

</div>
</div>


<script type="text/javascript">
$(document).ready(function() {

	$("#adpricing").change(function()
	{
		<?php if($category_enabled ==1){?>
		LoadSiteData();
		<?php }?>
	});

	<?php if($category_enabled ==1){?>
	LoadSiteData();
	<?php }?>

	LoadVideoOptions();
});

<?php if($category_enabled ==1){?>
function LoadSiteData()
{
	$(".site-class").hide();
	var selected=$("#adpricing").val();
	var allowed='<?php echo Configuration::get_instance()->read('category_enabled_ads');?>';
	if(allowed !='')
	{
		allowed_array=allowed.split('_');

		if($.inArray(selected , allowed_array) >-1)
		$(".site-class").show();
		else
		{
			if(selected ==13 && $("#adcode_for").val() ==1)
			$(".site-class").show();
			else
			$("#sid").val(0);		
		}
	}
}
<?php }?>


function LoadVideoOptions()
{
	pricing=$("#adpricing").val();

	if(pricing ==13)
	{
		if($("#adcode_for").val() ==1)
		{
			if($('#nonlinearbanner').length >0)
			{
				if($('#nonlinearbanner').prop('checked'))
				$('.video-option-vast-size').show();	
				else
				$('.video-option-vast-size').hide();	
			}
		}
	}
}


$("#adcode").click(function(){
	   $("#textarea-adcode").select();
	   document.execCommand('copy');
	});

<?php if($pricing ==9 && $get_direct_link ==1){?>

$("#pop-adcode").click(function(){
	   $("#textarea-pop-adcode").select();
	   document.execCommand('copy');
	});

<?php }?>

</script>
<?php $this->dispatch("layout/footer");?>