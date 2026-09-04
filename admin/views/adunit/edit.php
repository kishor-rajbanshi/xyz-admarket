<?php $this->dispatch("layout/header/2/_22");?>
<?php 
$basepath=str_replace('https://','',DISPLAY_BASE);
$basepath=str_replace('http://','',$basepath);

$pop_enabled=$this->get_variable('pop_enabled');
$pricing=$this->get_variable('pricing');
$adcode_type=$this->get_variable('adcode_type');
$aduid=$this->get_variable('aduid');

$get_direct_link=0;

if($pop_enabled ==1)
$get_direct_link=intval(Configuration::get_instance()->read('pop_direct_link_enabled'));



 
$res=$this->get_result('res');
$result=$res[0];

$category_enabled=$this->get_addon_status('category-targeting_enabled');
$sponsored_enabled=$this->get_addon_status('sponsored_enabled');
$skin_enabled=$this->get_addon_status('skin-ads_enabled');
$text_ads_enabled=Configuration::get_instance()->read('text-ads_enabled');


$video_enabled=$this->get_variable('video_enabled');
$linear_support=$this->get_variable('linear_support');
$nonlinear_support=$this->get_variable('nonlinear_support');
$html5_player_support=$this->get_variable('html5_player_support');

$adcode_for=$this->get_variable('adcode_for');
$linear=$this->get_variable('linear');


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


if($category_enabled ==1)
{
	if($_POST)
	$sid=$this->get_variable("sid");
	else
	$sid=$result['sid'];
}


if($_POST)
{
	$popup_support=$this->get_variable('popup_support');
	$popunder_support=$this->get_variable('popunder_support');
	$poptab_support=$this->get_variable('poptab_support');
}
else 
{
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

$uid = $result['pubid'];


$validate=array(
		"aduname"=>array(
				"notNull"=>array($this->get_message("not null"))
		));
?>
<?php if($pricing !=9 && $adcode_type !=9 && $result['banner_type'] !=4){?>
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
	    if($('#t').length >0)
	    $('#t').attr('checked',true);

	    $('#td1').show();
	    $('#bd1').hide();
	}

	if(id ==2)
	{
		if($('#b').length >0)
		$('#b').attr('checked',true);

		 $('#td1').hide();
		 $('#bd1').show();
	}
}


$(document).ready(function() {

	type           =<?php echo intval($result['type']);?>;
	bannertype     =<?php echo intval($result['banner_type']);?>;
	pricing        =<?php echo $pricing;?>;

	if(type ==2)
	$(".adcode_details_sub").css("width","16%");

	if($('#sid').length >0)
	$("#sid").css("width","125px");	

	if(pricing ==9)
	{
		$(".pop_div").css("width","30%");
		$(".pop_div input").css("margin","0px");	
	}	

	if(bannertype ==4)
	$(".adcode_details_sub").css("height","125px");	
});
</script>
 
<div class="sub_menu_main"><?php echo $this->get_label('view ad code');?></div>


<?php if($result['pubid'] ==0){?>
<?php $this->dispatch("links/links/21");?>
<?php }?>


<div class="inner-box">
<div class="adcode_div">


<?php if($pricing !=9 && $adcode_type !=9 && $pricing !=13 && $result['banner_type'] !=4){?>
<table style="width: 100%;" cellpadding="0" cellspacing="0">

<?php if($result['type']==3){?>
<tr>
    <td style="height: 30px;">
    <input type="radio" value="1" name="t" id="t" checked="checked" onClick="javascript:ShowPreview(this.value);"><strong><?php echo $this->get_label('text');?></strong>
    &nbsp;&nbsp;
    <input type="radio" value="2" name="t" id="b" onClick="javascript:ShowPreview(this.value);"><strong><?php echo $this->get_label('banner');?></strong>
    </td>
</tr>
<tr>
   <td >
   <div id="td1" >
   <iframe height="<?php echo $result['height']; ?>" width="<?php echo $result['width']; ?>" frameborder="0" src="<?php echo $this->make_url("adunit/preview/".$result['auid']."/1")?>" ></iframe> 
   </div>
    
   <div id="bd1" style="display: none;">
   <iframe height="<?php echo $result['height']; ?>" width="<?php echo $result['width']; ?>" frameborder="0" src="<?php echo $this->make_url("adunit/preview/".$result['auid']."/2")?>" ></iframe> 
   </div>
   </td>
</tr>
<?php }?>

<?php if($result['type']==1){?>
<tr>
  <td >
  <div id="td1" >
  <iframe height="<?php echo $result['height']; ?>" width="<?php echo $result['width']; ?>" frameborder="0" src="<?php echo $this->make_url("adunit/preview/".$result['auid']."/1")?>" ></iframe> 
  </div>
  </td>
</tr>  
<?php }?>
    
<?php if($result['type']==2){ ?>   
<tr>
  <td >
  <div id="bd1" >
  <iframe height="<?php echo $result['height']; ?>" width="<?php echo $result['width']; ?>" frameborder="0" src="<?php echo $this->make_url("adunit/preview/".$result['auid']."/2")?>" ></iframe> 
  </div>
  </td>
</tr>
<?php }?>
<?php if($result['type']==4){?>   
<tr>
   <td >
   <div id="bd1" >
   <iframe height="<?php echo $result['height']; ?>" width="<?php echo $result['width']; ?>" frameborder="0" src="<?php echo $this->make_url("adunit/preview/".$result['auid']."/3")?>" ></iframe> 
   </div>
   </td>
</tr>
<?php }?>


<tr><td style="height: 10px;"></td></tr>
</table>
<?php }?>




<div class="adcode_details adcode-info" style="margin-bottom: 10px;">
<table style="width: 100%;" cellpadding="0" cellspacing="0">

<tr class="adcode_heading"><td><?php echo $this->get_label('ad display code');?>

<div style="cursor: pointer;float: right;"><i class="fa fa-clipboard fa-md" aria-hidden="true" id="adcode" title="<?php echo $this->get_label('copy adcode');?>"></i></div>

</td></tr>

<tr>
<td style="padding: 10px;">
<?php 
if($pricing !=9 && $adcode_type !=9 && $result['banner_type'] ==1)
$pricingdata=5;
else if($pricing !=9 && $adcode_type !=9 && $result['banner_type'] ==4)
$pricingdata=14;
else
$pricingdata=$result['display_type'];

$admarket_name=Configuration::get_instance()->read('admarket_name');
?>

<textarea id="textarea-adcode" style="border: 1px solid #CCCCCC;width: 99%;height: 75px;" readonly="readonly">
<?php if($result['video_type'] !=1){?>
<!-- <?php  echo $admarket_name;?> - <?php echo $this->get_label('ad code');?> -->
<?php }?>
<?php if($pricing !=9 && $adcode_type !=9 && $pricing !=13){?>
<div id="adm-container-<?php echo $aduid;?>"></div><script data-cfasync="false" type="text/javascript" async src="<?php echo '//'.$basepath.DISPLAY_DIR; ?>/items.php?<?php echo $aduid; ?>&<?php echo $uid;?>&<?php echo $result['width'];?>&<?php echo $result['height']; ?>&<?php echo $pricingdata;?>"></script>
<?php }else{?>
<?php if($pricing ==9 || $adcode_type ==9){?>
<script data-cfasync="false" type="text/javascript" async src="<?php echo '//'.$basepath.DISPLAY_DIR; ?>/items.php?<?php echo $aduid; ?>&<?php echo $uid;?>&0&0&<?php echo $pricingdata;?>&9"></script>
<?php }
else if($pricing ==13 && $result['video_type'] ==1)
echo '//'.$basepath.DISPLAY_DIR.'/index.php?page=query/video/'.$this->get_variable('aduid');
else if($pricing ==13 && $result['video_type'] ==2){?>
<div id="adm-container-<?php echo $aduid;?>"></div><script data-cfasync="false" type="text/javascript" async src="<?php echo '//'.$basepath.DISPLAY_DIR; ?>/items.php?<?php echo $aduid; ?>&<?php echo $uid;?>&<?php echo $result['width'];?>&<?php echo $result['height'];?>&<?php echo $pricingdata;?>"></script>
<?php }}?>
<?php if($result['video_type'] !=1){?>
<!-- <?php  echo $admarket_name;?> - <?php echo $this->get_label('ad code');?>  -->
<?php }?>
</textarea>
<?php if($result['video_type'] ==1){?>
<span class="notification" style="float: right;"><?php echo $this->get_label('copy the vast tag');?></span>
<?php }?>

<?php if($pricing ==9 || $adcode_type ==9){?>
<span class="notification" style="float: right;"><?php echo $this->get_label('multiple pop adcodes in a single page is not supported');?></span>
<?php }?>
</td>
</tr>
  
 
<?php if(($pricing ==9 || $adcode_type ==9) && $get_direct_link ==1){?>
<tr class="adcode_heading">
<td style="border-top: 1px solid #CCCCCC;">
<?php echo $this->get_label('pop direct link');?>

<div style="cursor: pointer;float: right;"><i class="fa fa-clipboard fa-md" aria-hidden="true" id="pop-adcode" title="<?php echo $this->get_label('copy adcode');?>"></i></div>
</td>
</tr>
 
      
<tr>
<td style="padding: 10px;">
<textarea id="textarea-pop-adcode" style="border: 1px solid #CCCCCC;width: 99%;height: 25px;" readonly="readonly">
<?php echo BASE.DISPLAY_DIR.'/index.php?page=query/items/&aduid='.$aduid.'&pid='.$uid.'&displaytype=9&direct=1';?>
</textarea>
</td>
</tr>
<?php }?> 
  
</table>
</div>


<?php 
$form=$this->create_form();
$form->start("editadunit",$this->make_url("adunit/edit"),"post",$validate);
?>
<div class="adcode_details adcode-info" style="margin-bottom: 10px;">

<table style="width: 100%;" cellpadding="0" cellspacing="0">

<tr class="adcode_heading"><td colspan="8"><?php echo $this->get_label('basic settings');?> </td></tr>

  <tr><td style="height: 10px;">
  <span>
  <input type="hidden" name="adpricing" id="adpricing" value="<?php echo $result['display_type'];?>" />
  <input type="hidden" name="aduid" id="aduid" value="<?php echo $aduid; ?>" />
  <input type="hidden" name="adtype" id="adtype" value="<?php echo $result['type']; ?>" />
  <input type="hidden" name="adcode_for" id="adcode_for" value="<?php echo $result['video_type']; ?>" />  
  
  </span>
  
  </td></tr>

  <tr>
  <td>

  <div class="adcode_details_sub"> 
  <?php echo $this->get_label('pricing');?>
  <div style="margin-top: 10px;"><?php echo $this->get_adunit_preference($result['display_type'],$result['adcode_type']);?></div>
  </div>
  

	<?php if($category_enabled ==1){?>
	<div class="adcode_details_sub site-class" style="display: none;"> 
	<?php echo $this->get_label('targeting site');?><span class="compulsory">*</span></br>
	<?php 
	if($result['display_type'] !=3)
	echo CategoryHelper::get_site_dropdown($result['pubid'],$sid);
	else
	{
		echo '<div style="margin-top: 10px;">'.CategoryHelper::get_site_name($sid).'</div>';
	?>
		<input type="hidden" name="sid" id="sid" value="<?php echo $sid;?>" />
	<?php }?>
	</div>
	<?php }?> 

  <div class="adcode_details_sub"> 
  <?php echo $this->get_label('name');?><span class="compulsory">*</span></br>
  <input type="text" name="aduname" id="aduname" value="<?php if($_POST) echo $this->get_variable("name");else echo $result['auname'];?>" style="width: 90%;height: 23px;" maxlength="25"/>
  </div>

<?php if($pricing !=9 && $adcode_type !=9 && $result['banner_type'] !=4){?>

<?php if($pricing !=13 || ($pricing ==13 && $result['blockid'] >0)){?>
<div class="adcode_details_sub"> 
<?php echo $this->get_label('credit text');?></br>
<?php echo $this->get_credit_list($result['credittext']);?>

    <script type="text/javascript">
    function CreditLoadData(id,type)
    {
        $(".select-span-li").html($("#list-li-"+id).html());
        $("#credittext").val(id);
    }
    </script>
</div>
<?php }?>

<?php if($result['type']==1 || $result['type']==3 || $result['type']==4){?>
    <div class="adcode_details_sub">
    <?php echo $this->get_label('border type');?></br>
    <select name="border" id="border" style="width: 125px;">
    <option value="1" <?php if($result['abr_type']==1){echo "selected";}?>><?php echo $this->get_label('regular');?></option>
    <option value="0" <?php if($result['abr_type']==0){echo "selected";}?>><?php echo $this->get_label('rounded');?></option>
    </select>
    </div>
<?php }?>


<?php if($pricing !=13 || ($pricing ==13 && $result['blockid'] >0)){?>
<div class="adcode_details_sub"> 
<?php echo $this->get_label('dimension');?> (<?php echo $this->get_label('px');?>)
<div style="margin-top: 10px;"><?php echo $result['width']; ?> x <?php echo $result['height']; ?></div>
</div>
<?php }?>


<?php if($pricing !=13){?>
<div class="adcode_details_sub"> 
<?php echo $this->get_label('adtype');?>
<div style="margin-top: 10px;"><?php echo $this->get_adblock_type($result['type'],$result['banner_type']);?></div>
</div>
<?php }?>




<?php if($result['type']==1 || $result['type']==3 || $result['type']==4){?>

<?php if($result['display_type'] !=3){?>   
<div class="adcode_details_sub">
<?php echo $this->get_label('no of text ads');?>
<div style="margin-top: 10px;"><?php echo $result['textadcount'];?></div>
</div>
<?php }?>
  
    <div class="adcode_details_sub">
	<?php echo $this->get_label('adorientation');?>
    <div style="margin-top: 10px;"><?php echo $this->get_orientation($result['orientaion']);?></div>
    </div>

  <?php }}else{      
    
  if($pricing ==9 || $adcode_type ==9)
  {
  
  	$pop_ads_support=Configuration::get_instance()->read('pop_ads_support');
	
	$pop_array=explode('-',$pop_ads_support);

	$pop_up=intval($pop_array[0]);
	$pop_under=intval($pop_array[1]);
	$pop_tab=intval($pop_array[2]);
	
?>

	<?php if($adcode_type ==9){?>
	<div class="adcode_details_sub">
	<?php echo $this->get_label('adtype');?>
	<div style="margin-top: 10px;"><?php echo $this->get_adblock_type($result['type'],$result['banner_type'],$result['adcode_type']);?></div>
	</div>
	<?php }?>
	
	

	<div class="adcode_details_sub pop_div" style="width: 30%;">
	<?php echo $this->get_label('pop type');?><span class="compulsory">*</span></br>
	<div>
	<?php if($pop_up ==1){?>
	<input style="width: 20px !important;margin: 0px;" type="checkbox" name="popup_support" id="popup_support" value="1" <?php if($popup_support ==1){?> checked="checked" <?php }?> /> <?php echo $this->get_label('popup');?>
	<?php }?>
	
	<?php if($pop_under ==1){?>
	<span><input style="width: 20px !important;margin: 0px;" type="checkbox" name="popunder_support" id="popunder_support" value="1" <?php if($popunder_support ==1){?> checked="checked" <?php }?> /> <?php echo $this->get_label('popunder');?></span>
	<?php }?>
	
	<?php if($pop_tab ==1){?>
	<span><input style="width: 20px !important;margin: 0px;" type="checkbox" name="poptab_support" id="poptab_support" value="1" <?php if($poptab_support ==1){?> checked="checked" <?php }?> /> <?php echo $this->get_label('poptab');?></span>
	<?php }?>
	</div>
    </div>
    <?php } ?>   
    
    
 	<?php if($result['banner_type'] ==4){?>
	<div class="adcode_details_sub">
	<?php echo $this->get_label('adtype');?>
	<div style="margin-top: 10px;"><?php echo $this->get_adblock_type($result['type'],$result['banner_type']);?></div>
	</div>
	
	
	<div class="adcode_details_sub">
	<?php echo $this->get_label('skin layout');?></br>
	<?php
	$skin_position1=html_entity_decode($result['skin_positions']);
	$skin_position=json_decode($skin_position1,1);
	$left=$skin_position['L'] ==1? 'L' :0;
	$right=$skin_position['R']==1? 'R' :0;
	$top=$skin_position['T']==1? 'T' :0; 
	$bottom=$skin_position['B']==1? 'B' :0;
	
	$image_name= $left."_".$right."_".$top."_".$bottom.".png";
	?>
	<img src='<?php echo BASE.ADDON_DIR."/skin-ads/images/".$image_name;?>' />
	</div>	
	
	
	
	<div class="adcode_details_sub">
	<?php echo $this->get_label('main container id');?></br>
	<input type="text" name="container_id" id="container_id" value="<?php if($_POST) echo $container_id; else echo $result['container_id'];?>" style="width: 100px;height: 23px;"/>
	</div>
    
	<?php }?>   

  <?php }?>
  
  
<?php if($pricing ==13) {?>
  
	<div class="adcode_details_sub">
	<?php echo $this->get_label('adcode for');?>
    <div style="margin-top: 10px;">
	<?php 
	if($result['video_type'] ==1)
	echo $this->get_label('vast player');
	else if($result['video_type'] ==2)
	echo $this->get_label('html5 player');
	?>
    </div>
    </div>


<?php if($result['video_type'] ==1 && ($linear_support ==1 || $nonlinear_support ==1)){

$res14=$this->get_result('res14');	
?>
	<div class="adcode_details_sub">
	<?php echo $this->get_label('supported type');?> <span class="compulsory">*</span></br>

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
</div>
   

<?php if($nonlinear_support ==1 && count($res14) >0){?>

<div class="adcode_details_sub video-option-vast-size" style="display: none;">
<?php echo $this->get_label('banner dimension');?></br>

<?php if($nonlinear_size ==0){?>
<select class="form-control" name="nonlinear_size" id="nonlinear_size" style="width: 125px;">
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

	echo '<div style="margin-top: 10px;">'.$size_array[0].' x '.$size_array[1].'</div>';
	?>
<input type="hidden" name="nonlinear_size" id="nonlinear_size" value="<?php echo $nonlinear_size;?>" />
<?php }?>
</div>
<?php }?>


<?php }?>   
    
<?php }?>

 <div class="adcode_details_sub">
	<?php echo $this->get_label('category type');?>
    <div style="margin-top: 10px;"><?php if($_POST) $cat=$this->get_variable('cat_type');else $cat=$result['category_type'];?>
    <input type="radio" name="cat_type" id="cat_type0" value="0" <?php if($cat ==0){?>checked="checked"<?php }?> onclick="" />&nbsp;<?php echo $this->get_label('all');?>&nbsp;
	<input type="radio" name="cat_type" id="cat_type1" value="1" <?php if($cat ==1){?>checked="checked"<?php }?> onclick="" />&nbsp;<?php echo $this->get_label('one');?>&nbsp;
	<input type="radio" name="cat_type" id="cat_type2" value="2" <?php if($cat ==2){?>checked="checked"<?php }?> onclick="" />&nbsp;<?php echo $this->get_label('two');?>&nbsp;
    </div>
    </div>   
  </td> 
  </tr>

</table>
</div> 


<?php if($pricing !=9 && $adcode_type !=9 && $result['banner_type'] !=4){?>
<?php if($pricing !=13 || ($pricing ==13 && $result['blockid'] >0)){?>

<table class="data_table" cellpadding="0" cellspacing="0">
<tr class="row_heading_tr">
<td class="border-right">&nbsp;</td>
<td ><?php echo $this->get_label('credit text');?></td>
<?php if($result['type']==1 || $result['type']==3 || $result['type']==4){ ?>
<td ><?php echo $this->get_label('ad title');?></td>
<td ><?php echo $this->get_label('description');?></td>
<td ><?php echo $this->get_label('displayurl');?></td>
<?php }?>
<td ><?php echo $this->get_label('border');?></td>
<td ><?php echo $this->get_label('background');?></td>
<td ></td>
</tr>


<tr class="row_data_tr">
<td class="border-right"><?php echo $this->get_label('color');?></td>
<td >
<input type="text" id="color5" name="color5" class="color-picker" value="<?php echo $result['ac_color'];?>" style="background-color:<?php echo $result['ac_color'];?>" />
</td>
<?php if($result['type']==1 || $result['type']==3 || $result['type']==4){ ?>
<td >
<input type="text" id="color1" name="color1" class="color-picker"  style="background-color:<?php echo $result['at_color'];?>" value="<?php echo $result['at_color'];?>" />
</td>
<td >
<input type="text" id="color2" name="color2" class="color-picker"  value="<?php echo $result['ad_color'];?>" style="background-color:<?php echo $result['ad_color'];?>" />
</td>
<td >
<input type="text" id="color3" name="color3" class="color-picker" value="<?php echo $result['au_color'];?>" style="background-color:<?php echo $result['au_color'];?>" />
</td>
<?php }?>
<td >
<input type="text" id="color6" name="color6" class="color-picker" value="<?php echo $result['abr_color'];?>" style="background-color:<?php echo $result['abr_color'];?>" />
</td>
<td >
<input type="text" id="color4" name="color4" class="color-picker" value="<?php echo $result['ab_color'];?>" style="background-color:<?php echo $result['ab_color'];?>" />
</td>
<td style="border-left: 1px solid #CCCCCC;width: 150px;">

<div id="picker" style="float: left;"></div>

</td>
</tr>
</table>
<?php }}?>
<div>

<?php if($result['pubid'] ==0){?>
<div style="width: 100%;text-align: center;margin-top: 10px;"><input type="submit" name="submit" value="<?php echo $this->get_label('update adunit');?>" /></div>
<?php }?>

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

<?php if(($pricing ==9 || $adcode_type ==9) && $get_direct_link ==1){?>

$("#pop-adcode").click(function(){
	   $("#textarea-pop-adcode").select();
	   document.execCommand('copy');
	});

<?php }?>


</script>
<?php $this->dispatch("layout/footer");?>