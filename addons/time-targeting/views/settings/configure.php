<?php 
$form=$this->create_form();
$form->start("timesettings",$this->make_base_url("settings/configure",ADDON_DIR.'/time-targeting'),"post");

$date_filter_enabled=$this->get_variable("date_filter_enabled");
$time_filter_enabled=$this->get_variable("time_filter_enabled");
$day_filter_enabled=$this->get_variable("day_filter_enabled");
?>
<div class="inner-box">
<div class="pages-input">


<table style="width: 100%;" cellpadding="0" cellspacing="0">
<tr>
<td style="height: 30px;width: 130px;" ><?php echo $this->get_label('date filter enabled');?></td>
<td style="width: 20px;">:</td>
<td>
<select name="date_filter_enabled" id="date_filter_enabled">
<option value="1" <?php if($date_filter_enabled ==1){?>selected<?php }?>><?php echo $this->get_label('yes');?></option>
<option value="0" <?php if($date_filter_enabled ==0){?>selected<?php }?>><?php echo $this->get_label('no');?></option>
</select>
</tr>


<tr>
<td style="height: 30px;width: 100px;" ><?php echo $this->get_label('time filter enabled');?></td>
<td style="width: 20px;">:</td>
<td>
<select name="time_filter_enabled" id="time_filter_enabled">
<option value="1" <?php if($time_filter_enabled ==1){?>selected<?php }?>><?php echo $this->get_label('yes');?></option>
<option value="0" <?php if($time_filter_enabled ==0){?>selected<?php }?>><?php echo $this->get_label('no');?></option>
</select>
</tr>



<tr>
<td style="height: 30px;width: 100px;" ><?php echo $this->get_label('day filter enabled');?></td>
<td style="width: 20px;">:</td>
<td>
<select name="day_filter_enabled" id="day_filter_enabled">
<option value="1" <?php if($day_filter_enabled ==1){?>selected<?php }?>><?php echo $this->get_label('yes');?></option>
<option value="0" <?php if($day_filter_enabled ==0){?>selected<?php }?>><?php echo $this->get_label('no');?></option>
</select>
</tr>

<tr><td></td><td></td><td align="left"><input type="submit" name="submit" value="<?php echo $this->get_label('update');?>" ></td></tr>
</table>
</div> 
</div>
<?php $form->end(); ?>