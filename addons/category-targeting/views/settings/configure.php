<?php 
$form=$this->create_form();
$form->start("categorysettings",$this->make_base_url("settings/configure",ADDON_DIR.'/category-targeting'),"post");

$default_site_logo=$this->get_variable("default_site_logo");
$category_enabled_ads=$this->get_variable("category_enabled_ads");
$site_default_status=$this->get_variable("site_default_status");



if($category_enabled_ads !='')
$stringarray=explode('_',$category_enabled_ads);
else
$stringarray=array();

?>
    
<div class="inner-box">
<div class="pages-input">


<table style="width: 100%;" cellpadding="0" cellspacing="0">

<tr>
<td style="height: 30px;" ><?php echo $this->get_label('default site logo'); ?></td>
<td style="width: 20px;">:</td>
<td>
<input type="file" name="logo" id="logo" />

  <?php if($default_site_logo !=''){?>
  <img src="<?php echo '../'.DATA_DIR."/site_logo/".$default_site_logo;?>" />
  
  
  
  <a href="<?php echo $this->make_base_url("settings/delete_logo",ADDON_DIR.'/category-targeting');?>"><img alt="<?php echo $this->get_label('delete');?>" src="images/delete1.png" title="<?php echo $this->get_label('delete');?>" /></a>
  
  
  
  
  <?php }?>
  
  <br/>

<span class="notification">[<?php echo $this->get_label('supported image format');?>]<br/>[<?php echo $this->get_label('site logo size');?>]</span>


</td>
</tr>


<tr>
<td><?php echo $this->get_label('site default status');?></td>
<td style="width: 20px;">:</td>
<td>
<select name="site_default_status" id="site_default_status">
<option value="1"  <?php if($site_default_status ==1)  { ?>selected="selected"<?php } ?> ><?php echo $this->get_label('active');?></option>
<option value="-1" <?php if($site_default_status ==-1) { ?>selected="selected"<?php } ?> ><?php echo $this->get_label('pending');?></option>
</select>
</td>
</tr>


<tr>
<td style="height: 30px;width: 250px;" ><?php echo $this->get_label('category site targeting enabled for');?></td>
<td style="width: 20px;">:</td>
<td>

<?php 
$cpc_enabled=$this->get_addon_status('cpc_enabled');
$cpm_enabled=$this->get_addon_status('cpm_enabled');
$html_enabled=$this->get_addon_status('html_enabled');
$cpa_enabled=$this->get_addon_status('cpa_enabled');
$cpv_enabled=$this->get_addon_status('video-ads_enabled');
$pop_enabled=$this->get_addon_status('pop-ads_enabled');


if($cpv_enabled ==1)
{
    if(intval(Configuration::get_instance()->read('html5_player_support')) ==0)
    $cpv_enabled=0;
}



$string="";

if($cpc_enabled ==1)
$string.="CPC";


if($cpm_enabled ==1 || $html_enabled ==1)
{
	if($string !="")
	$string.=" / ";
	
	$string.="CPM";
}

if($cpa_enabled ==1)
{
	if($string !="")
	$string.=" / ";
	
	$string.="CPA";
}

if($cpv_enabled ==1)
{
	if($string !="")
	$string.=" / ";
	
	$string.="CPV";
}

?>

<?php if($cpc_enabled ==1 || $cpm_enabled ==1 || $html_enabled ==1 || $cpa_enabled ==1 || $cpv_enabled ==1){?>
<input type="checkbox" name="chk_iframe_display" id="chk_iframe_display" value="1" <?php if(in_array('0',$stringarray) || in_array('1',$stringarray) || in_array('2',$stringarray) || in_array('4',$stringarray) || in_array('6',$stringarray) || in_array('13',$stringarray)){?>checked="checked"<?php }?> /><?php echo $this->get_label('iframe display');?>
<span class="notification" style="margin-left: 5px;font-size: 10px;"> 
[<?php echo $string;?>]
</span>
<br/>
<?php }?>

<?php if($pop_enabled ==1){?>
<input type="checkbox" name="chk_pop_display" id="chk_pop_display" value="1" <?php if(in_array('9',$stringarray)){?>checked="checked"<?php }?> /><?php echo $this->get_label('pop display');?><br/>
<?php }?>

</td>
</tr>

     
<tr><td></td><td></td><td align="left"><input type="submit" name="submit" value="<?php echo $this->get_label('update');?>" ></td></tr>
      
</table>
</div> 
</div>
<?php $form->end(); ?>