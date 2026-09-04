<?php 
$form=$this->create_form();
$form->start("interstitialsettings",$this->make_base_url("settings/configure",ADDON_DIR.'/interstitial'),"post");


	$interstitial_interval_visitor=$this->get_variable("interstitial_interval_visitor");
	$interstitial_skip_interval=$this->get_variable("interstitial_skip_interval");
	$display_skip_button=$this->get_variable("display_skip_button");
	$skip_button_position=$this->get_variable("skip_button_position");
	
				
?>
     
<div class="inner-box">
<div class="pages-input">


<table style="width: 100%;" cellpadding="0" cellspacing="0">

<tr><td></td><td><?php echo $this->get_label('compulsory message');?></td></tr>

<tr>
<td width="240px" height="30px"><?php echo $this->get_label('interstitial interval visitor');?></td>
<td><input type="text" name="interstitial_interval_visitor" id="interstitial_interval_visitor" value="<?php echo $interstitial_interval_visitor;?>" size="5" onkeyup="this.value=this.value.replace(/[^0-9]/g, '');" >
&nbsp;<?php echo $this->get_label('min');?>
</td>
</tr>


<tr>
<td width="240px" height="30px"><?php echo $this->get_label('interstitial skip interval');?></td>
<td>
<select name="interstitial_skip_interval" id="interstitial_skip_interval" style="width: 100px;">
<?php for($i=1;$i <=33;$i++){?>
<option value="<?php echo $i;?>"  <?php if($interstitial_skip_interval ==$i){ ?>selected="selected"<?php } ?>><?php echo $i;?></option>
<?php }?>
</select>
&nbsp;
<?php echo $this->get_label('sec');?>
</td>
</tr>



<tr>
<td height="30px"><?php echo $this->get_label('display skip button');?></td>
<td>
<select name="display_skip_button" id="display_skip_button" style="width: 100px;">
<option value="1"  <?php if($display_skip_button ==1) { ?>selected="selected"<?php } ?> ><?php echo $this->get_label('yes');?></option>
<option value="0"  <?php if($display_skip_button ==0) { ?>selected="selected"<?php } ?> ><?php echo $this->get_label('no');?></option>
</select>
</td>
</tr>


<tr>
<td height="30px"><?php echo $this->get_label('skip button position');?></td>
<td>
<select name="skip_button_position" id="skip_button_position" style="width: 100px;">
<option value="0"  <?php if($skip_button_position ==0) { ?>selected="selected"<?php } ?> ><?php echo $this->get_label('top left');?></option>
<option value="1"  <?php if($skip_button_position ==1) { ?>selected="selected"<?php } ?> ><?php echo $this->get_label('top right');?></option>
<option value="2"  <?php if($skip_button_position ==2) { ?>selected="selected"<?php } ?> ><?php echo $this->get_label('bottom right');?></option>
<option value="3"  <?php if($skip_button_position ==3) { ?>selected="selected"<?php } ?> ><?php echo $this->get_label('bottom left');?></option>
</select>
</td>
</tr>

<tr><td></td><td align="left"><input type="submit" name="submit" value="<?php echo $this->get_label('update');?>" ></td></tr>
</table>
</div>
</div>
<?php $form->end(); ?>