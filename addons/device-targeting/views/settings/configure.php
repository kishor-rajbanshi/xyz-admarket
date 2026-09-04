
<?php 

$os_targeting_enabled=Configuration::get_instance()->read('os-targeting_enabled');
$browser_targeting_enabled=Configuration::get_instance()->read('browser-targeting_enabled');


$form=$this->create_form();
$form->start("devicesettings",$this->make_base_url("settings/configure",ADDON_DIR.'/device-targeting'),"post");
?>
<div class="inner-box">
<div class="pages-input">


<table style="width: 100%;" cellpadding="0" cellspacing="0">

<tr>
<td height="20px"><?php echo $this->get_label('Enable Operating System Targeting');?></td>
<td>&nbsp;:&nbsp;</td>
<td>

  <input type="checkbox"  name="os_enable" <?php if($os_targeting_enabled==1){echo 'checked';}?> value="1">
  
</td>
<td></td>
</tr>
<tr><td><br></td></tr>

<tr>
<td height="20px"><?php echo $this->get_label('Enable Browser Targeting');?></td>
<td>&nbsp;:&nbsp;</td>
<td>
  <input type="checkbox"  name="br_enable" <?php if($browser_targeting_enabled==1){echo 'checked';}?> value="1">
  </td>
<td></td>
</tr>

     
<tr><td></td><td></td><td align="left"><input type="submit" name="submit" value="<?php echo $this->get_label('update');?>" ></td></tr>

</table>
</div>
</div>
<?php $form->end();?>
